<?php

namespace App\Http\Controllers;

use App\Exports\MonitoringExport;
use App\Models\Lulusan;
use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Excel;

class MonitoringController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $selectedSurveyId = $request->input('survey_id');
        $selectedGraduationYear = trim((string) $request->input('tahun_lulus', ''));
        $selectedStudyProgram = trim((string) $request->input('prodi', ''));
        $selectedChartType = $request->input('chart_type', 'bar');
        $selectedChartType = in_array($selectedChartType, ['bar', 'pie'], true) ? $selectedChartType : 'bar';

        $filterRequested = $request->hasAny(['survey_id', 'tahun_lulus', 'prodi', 'chart_type']);

        if ($filterRequested) {
            $validator = Validator::make(
                $request->all(),
                [
                    'survey_id' => 'nullable|exists:survey,id',
                    'tahun_lulus' => 'nullable|string|max:255',
                    'prodi' => 'nullable|string|max:255',
                    'chart_type' => 'nullable|in:bar,pie',
                ],
                ['survey_id.exists' => 'Survei yang dipilih tidak valid.']
            );

            if ($validator->fails()) {
                return redirect()->route('admin.monitoring.index', [
                    'search' => $request->input('search'),
                ])->withErrors($validator)->withInput();
            }
        }

        $query = Survey::query();

        if ($request->filled('search')) {
            $searchTerm = $request->input('search');
            $query->where(function ($q) use ($searchTerm) {
                $q->where('nama', 'like', '%' . $searchTerm . '%')
                    ->orWhere('type_survei', 'like', '%' . $searchTerm . '%');
            });
        }

        // Tabel monitoring harus selalu menampilkan seluruh survei.

        $survey = $query->orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends($request->query());

        foreach ($survey as $srvy) {
            $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-Y");
            $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-Y");
            if (Carbon::parse($srvy->tanggal_selesai) >= now()) {
                $srvy->status = "Aktif";
            } else {
                $srvy->status = "Selesai";
            }

            $assignmentStats = SurveyUser::getAssignmentStats((int) $srvy->id);
            $overallTotal = $assignmentStats['total'];
            $overallCompleted = $assignmentStats['completed'];

            $filteredBaseQuery = $this->buildFilteredSurveyUserQuery(
                $srvy,
                $selectedGraduationYear !== '' ? $selectedGraduationYear : null,
                $selectedStudyProgram !== '' ? $selectedStudyProgram : null
            );

            $filteredTotal = (clone $filteredBaseQuery)
                ->distinct()
                ->count('survey_user.id');

            $filteredCompleted = (clone $filteredBaseQuery)
                ->where('survey_user.status', true)
                ->distinct()
                ->count('survey_user.id');

            $srvy->overall_total = $overallTotal;
            $srvy->overall_completed = $overallCompleted;
            $srvy->overall_rate = $overallTotal > 0 ? round(($overallCompleted / $overallTotal) * 100, 2) : 0;

            $srvy->filtered_total = $filteredTotal;
            $srvy->filtered_completed = $filteredCompleted;
            $srvy->filtered_rate = $filteredTotal > 0 ? round(($filteredCompleted / $filteredTotal) * 100, 2) : 0;
            $srvy->is_lulusan_filter_applied = ($selectedGraduationYear !== '' || $selectedStudyProgram !== '');
        }

        $surveyOptions = Survey::orderBy('nama')->get(['id', 'nama']);
        $filterOptionsBySurvey = [];

        foreach (Survey::query()->select(['id', 'type_survei'])->get() as $surveyOption) {
            $assignedLulusanQuery = $this->buildAssignedLulusanQuery($surveyOption);

            $yearProgramPairs = (clone $assignedLulusanQuery)
                ->select(['lulusan.tahun_lulus', 'lulusan.prodi'])
                ->whereNotNull('lulusan.tahun_lulus')
                ->where('lulusan.tahun_lulus', '!=', '')
                ->whereNotNull('lulusan.prodi')
                ->where('lulusan.prodi', '!=', '')
                ->distinct()
                ->get();

            $years = $yearProgramPairs
                ->pluck('tahun_lulus')
                ->unique()
                ->sortDesc()
                ->values()
                ->all();

            $programs = $yearProgramPairs
                ->pluck('prodi')
                ->unique()
                ->sort()
                ->values()
                ->all();

            $programsByYear = $yearProgramPairs
                ->groupBy('tahun_lulus')
                ->map(function (Collection $items) {
                    return $items
                        ->pluck('prodi')
                        ->unique()
                        ->sort()
                        ->values()
                        ->all();
                })
                ->toArray();

            $yearsByProgram = $yearProgramPairs
                ->groupBy('prodi')
                ->map(function (Collection $items) {
                    return $items
                        ->pluck('tahun_lulus')
                        ->unique()
                        ->sortDesc()
                        ->values()
                        ->all();
                })
                ->toArray();

            $filterOptionsBySurvey[(string) $surveyOption->id] = [
                'years' => $years,
                'programs' => $programs,
                'programsByYear' => $programsByYear,
                'yearsByProgram' => $yearsByProgram,
            ];
        }

        $selectedSurveyOptionSet = $selectedSurveyId
            ? ($filterOptionsBySurvey[(string) $selectedSurveyId] ?? ['years' => [], 'programs' => []])
            : ['years' => [], 'programs' => []];
        $graduationYears = collect($selectedSurveyOptionSet['years']);
        $studyPrograms = collect($selectedSurveyOptionSet['programs']);

        $activeFilterType = $selectedGraduationYear !== '' ? 'tahun_lulus' : ($selectedStudyProgram !== '' ? 'prodi' : null);
        $activeFilterValue = $selectedGraduationYear !== '' ? $selectedGraduationYear : ($selectedStudyProgram !== '' ? $selectedStudyProgram : null);

        $selectedSurveyForVisualization = null;
        if ($selectedSurveyId) {
            $selectedSurveyForVisualization = Survey::query()->select(['id', 'nama', 'type_survei'])->find($selectedSurveyId);
        }

        $dynamicChart = [
            'labels' => [],
            'rates' => [],
            'targets' => [],
            'responded' => [],
            'xAxisTitle' => '',
            'yAxisTitle' => 'Response Rate (%)',
            'scenario' => null,
            'showChart' => false,
        ];

        if ($selectedSurveyForVisualization) {
            $dynamicChart = $this->buildDynamicResponseRateChartData(
                $selectedSurveyForVisualization,
                $selectedGraduationYear !== '' ? $selectedGraduationYear : null,
                $selectedStudyProgram !== '' ? $selectedStudyProgram : null
            );
        }

        return view('admin.views.monitoring.index', compact(
            'survey',
            'surveyOptions',
            'graduationYears',
            'studyPrograms',
            'selectedSurveyId',
            'selectedGraduationYear',
            'selectedStudyProgram',
            'selectedChartType',
            'activeFilterType',
            'activeFilterValue',
            'filterOptionsBySurvey',
            'selectedSurveyForVisualization',
            'dynamicChart'
        ));
    }

    private function buildAssignedLulusanQuery($survey): Builder
    {
        if ($this->normalizeSurveyType($survey->type_survei) === 'pengguna_lulusan') {
            return Lulusan::query()
                ->join('pengguna_lulusan', 'pengguna_lulusan.nip', '=', 'lulusan.nip_pengguna_lulusan')
                ->join('users', 'users.id', '=', 'pengguna_lulusan.user_id')
                ->join('survey_user', 'survey_user.user_id', '=', 'users.id')
                ->where('survey_user.survey_id', $survey->id);
        }

        return Lulusan::query()
            ->join('users', 'users.id', '=', 'lulusan.user_id')
            ->join('survey_user', 'survey_user.user_id', '=', 'users.id')
            ->where('survey_user.survey_id', $survey->id);
    }

    private function buildFilteredSurveyUserQuery(Survey $survey, ?string $selectedGraduationYear, ?string $selectedStudyProgram): Builder
    {
        $query = SurveyUser::query()
            ->where('survey_user.survey_id', $survey->id);

        if ($this->normalizeSurveyType($survey->type_survei) === 'pengguna_lulusan') {
            $query->join('users', 'users.id', '=', 'survey_user.user_id')
                ->join('pengguna_lulusan', 'pengguna_lulusan.user_id', '=', 'users.id')
                ->join('lulusan', 'lulusan.nip_pengguna_lulusan', '=', 'pengguna_lulusan.nip');
        } else {
            $query->join('users', 'users.id', '=', 'survey_user.user_id')
                ->join('lulusan', 'lulusan.user_id', '=', 'users.id');
        }

        if (!empty($selectedGraduationYear)) {
            $query->where('lulusan.tahun_lulus', $selectedGraduationYear);
        }

        if (!empty($selectedStudyProgram)) {
            $query->where('lulusan.prodi', $selectedStudyProgram);
        }

        return $query;
    }

    private function buildSurveyUserLulusanBaseQuery(Survey $survey): Builder
    {
        $query = SurveyUser::query()
            ->where('survey_user.survey_id', $survey->id)
            ->join('users', 'users.id', '=', 'survey_user.user_id');

        if ($this->normalizeSurveyType($survey->type_survei) === 'pengguna_lulusan') {
            $query->join('pengguna_lulusan', 'pengguna_lulusan.user_id', '=', 'users.id')
                ->join('lulusan', 'lulusan.nip_pengguna_lulusan', '=', 'pengguna_lulusan.nip');
        } else {
            $query->join('lulusan', 'lulusan.user_id', '=', 'users.id');
        }

        return $query;
    }

    private function buildResponseRateRowsByDimension(Builder $query, string $dimension): array
    {
        $rows = (clone $query)
            ->whereNotNull('lulusan.' . $dimension)
            ->where('lulusan.' . $dimension, '!=', '')
            ->selectRaw('lulusan.' . $dimension . ' as dimension_label')
            ->selectRaw('COUNT(DISTINCT survey_user.id) as total_target')
            ->selectRaw('COUNT(DISTINCT CASE WHEN survey_user.status = 1 THEN survey_user.id END) as total_responded')
            ->groupBy('lulusan.' . $dimension)
            ->orderBy('lulusan.' . $dimension)
            ->get();

        return $rows
            ->map(function ($row) {
                $totalTarget = (int) $row->total_target;
                $totalResponded = (int) $row->total_responded;

                return [
                    'label' => (string) $row->dimension_label,
                    'target' => $totalTarget,
                    'responded' => $totalResponded,
                    'rate' => $totalTarget > 0 ? round(($totalResponded / $totalTarget) * 100, 2) : 0,
                ];
            })
            ->values()
            ->all();
    }

    private function buildSummaryResponseRateRow(Builder $query, string $label): array
    {
        $summary = (clone $query)
            ->selectRaw('COUNT(DISTINCT survey_user.id) as total_target')
            ->selectRaw('COUNT(DISTINCT CASE WHEN survey_user.status = 1 THEN survey_user.id END) as total_responded')
            ->first();

        $totalTarget = (int) ($summary->total_target ?? 0);
        $totalResponded = (int) ($summary->total_responded ?? 0);

        return [
            'label' => $label,
            'target' => $totalTarget,
            'responded' => $totalResponded,
            'rate' => $totalTarget > 0 ? round(($totalResponded / $totalTarget) * 100, 2) : 0,
        ];
    }

    private function buildDynamicResponseRateChartData(Survey $survey, ?string $selectedGraduationYear, ?string $selectedStudyProgram): array
    {
        $baseQuery = $this->buildSurveyUserLulusanBaseQuery($survey);

        if (!empty($selectedGraduationYear)) {
            $baseQuery->where('lulusan.tahun_lulus', $selectedGraduationYear);
        }

        if (!empty($selectedStudyProgram)) {
            $baseQuery->where('lulusan.prodi', $selectedStudyProgram);
        }

        $scenario = 'all';
        $xAxisTitle = 'Program Studi';
        $rows = [];

        if (!empty($selectedGraduationYear) && !empty($selectedStudyProgram)) {
            $scenario = 'year_and_program';
            $xAxisTitle = 'Program Studi';
            $rows[] = $this->buildSummaryResponseRateRow($baseQuery, $selectedStudyProgram);
        } elseif (!empty($selectedGraduationYear)) {
            $scenario = 'year_only';
            $xAxisTitle = 'Program Studi';
            $rows = $this->buildResponseRateRowsByDimension($baseQuery, 'prodi');
        } elseif (!empty($selectedStudyProgram)) {
            $scenario = 'program_only';
            $xAxisTitle = 'Tahun Lulus';
            $rows = $this->buildResponseRateRowsByDimension($baseQuery, 'tahun_lulus');
            usort($rows, function (array $left, array $right) {
                return (int) $left['label'] <=> (int) $right['label'];
            });
        } else {
            $scenario = 'all';
            $xAxisTitle = 'Program Studi';
            $rows = $this->buildResponseRateRowsByDimension($baseQuery, 'prodi');
        }

        $overallSummary = $this->buildSummaryResponseRateRow(
            $this->buildSurveyUserLulusanBaseQuery($survey),
            'Politeknik Statistika STIS'
        );

        return [
            'labels' => array_map(fn(array $row) => $row['label'], $rows),
            'rates' => array_map(fn(array $row) => $row['rate'], $rows),
            'targets' => array_map(fn(array $row) => $row['target'], $rows),
            'responded' => array_map(fn(array $row) => $row['responded'], $rows),
            'overallRate' => $overallSummary['rate'],
            'overallTarget' => $overallSummary['target'],
            'overallResponded' => $overallSummary['responded'],
            'xAxisTitle' => $xAxisTitle,
            'yAxisTitle' => 'Response Rate (%)',
            'scenario' => $scenario,
            'showChart' => count($rows) > 0,
        ];
    }

    private function normalizeSurveyType(?string $surveyType): string
    {
        $normalizedType = strtolower(trim((string) $surveyType));
        $normalizedType = str_replace(['-', ' '], '_', $normalizedType);

        if ($normalizedType === 'penggunalulusan') {
            return 'pengguna_lulusan';
        }

        return $normalizedType;
    }

    /*
     * Visualization methods are intentionally disabled because the feature
     * has been moved to DashboardController.
     *
     * public function grafik($id)
     * {
     *     $survey = Survey::findOrFail($id);
     *     $questions = TemplatePertanyaan::where('id_survey', $id)
     *         ->whereNotNull('visualisasi')
     *         ->get();
     *
     *     $allSurveys = Survey::all();
     *
     *     return view('admin.views.monitoring.grafik', compact('survey', 'questions', 'allSurveys'));
     * }
     *
     * public function getChartData($surveyId, $questionId)
     * {
     *     try {
     *         $survey = Survey::findOrFail($surveyId);
     *
     *         $question = TemplatePertanyaan::where('id_survey', $surveyId)
     *             ->where('id', $questionId)
     *             ->firstOrFail();
     *
     *         if (!$question->visualisasi) {
     *             throw new \Exception('Visualization type not set for this question');
     *         }
     *
     *         Log::info('Starting chart data generation', [
     *             'survey_id' => $surveyId,
     *             'question_id' => $questionId,
     *             'question_type' => $question->tipe,
     *             'visualisasi' => $question->visualisasi
     *         ]);
     *
     *         $surveyUsers = SurveyUser::where('survey_id', $surveyId)
     *             ->where('status', true)
     *             ->get();
     *
     *         $answers = SurveyUserJawaban::whereIn('survey_user_id', $surveyUsers->pluck('id'))
     *             ->where('template_pertanyaan_id', $questionId)
     *             ->get();
     *
     *         $data = [];
     *         foreach ($answers as $answer) {
     *             if (!isset($data[$answer->jawaban])) {
     *                 $data[$answer->jawaban] = 1;
     *             } else {
     *                 $data[$answer->jawaban]++;
     *             }
     *         }
     *
     *         if ($question->tipe === 'radio') {
     *             arsort($data);
     *         }
     *
     *         $chartColors = [
     *             '#4B0082',
     *             '#0096FF',
     *             '#00FF7F',
     *             '#FFD700',
     *             '#FF69B4',
     *             '#8B4513',
     *             '#4682B4',
     *             '#D2691E',
     *             '#9370DB',
     *             '#3CB371',
     *         ];
     *
     *         $chartData = [
     *             'labels' => array_keys($data),
     *             'datasets' => [
     *                 [
     *                     'label' => $question->pertanyaan,
     *                     'data' => array_values($data),
     *                     'backgroundColor' => array_slice($chartColors, 0, count($data))
     *                 ]
     *             ]
     *         ];
     *
     *         return response()->json([
     *             'type' => $question->visualisasi ?? 'bar',
     *             'data' => $chartData
     *         ]);
     *     } catch (\Exception $e) {
     *         Log::error('Error generating chart data: ' . $e->getMessage());
     *         return response()->json([
     *             'error' => 'Error generating chart data',
     *             'details' => config('app.debug') ? $e->getMessage() : null
     *         ], 500);
     *     }
     * }
     */

    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $template_questions = TemplatePertanyaan::with(['survey_user_jawaban' => function ($query) {
            $query->orderBy('urutan', 'asc');
        }])
            ->where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->orderBy('urutan')
            ->get();

        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }

        return view('admin.views.monitoring.details', [
            'survey' => $survey,
            'template_questions' => $template_questions
        ]);
    }

    public function export($surveyId, Excel $excel){
        $file_name = 'export_survey_' . $surveyId . '_' . now()->format('Y-m-d_H-i-s') . '.xlsx';
        return $excel->download(new MonitoringExport($surveyId), $file_name);
    }
}
