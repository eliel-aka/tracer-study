<?php

namespace App\Http\Controllers;

use App\Models\Lulusan;
use App\Models\PenggunaLulusan;
use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $totalSurvey = Survey::count();
        $totalLulusan = Lulusan::count();
        $totalPenggunaLulusan = PenggunaLulusan::count();
        $totalUsers = $totalLulusan + $totalPenggunaLulusan;

        $surveyAktif = Survey::orderBy('created_at', 'desc')
            ->paginate(10)
            ->appends(request()->query());

        foreach ($surveyAktif as $survey) {
            $assignmentStats = SurveyUser::getAssignmentStats((int) $survey->id);
            $totalResponses = $assignmentStats['total'];
            $completedResponses = $assignmentStats['completed'];
            $survey->completionRate = $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0;
            $survey->completedResponses = $completedResponses;
            $survey->totalResponses = $totalResponses;
        }

        return view('admin.views.dashboard', compact(
            'surveyAktif',
            'totalSurvey',
            'totalLulusan',
            'totalPenggunaLulusan',
            'totalUsers'
        ));
    }

    /**
     * Show visualization page for dashboard menu.
     */
    public function grafik(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
        $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
            ? $selectedSegmentDimension
            : 'prodi';

        $questions = TemplatePertanyaan::where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->where('visualisasi', '!=', '')
            ->get();

        $minTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->min('tahun_lulus');
        $maxTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->max('tahun_lulus');
        $tahunText = '';
        if ($minTahun && $maxTahun) {
            $tahunText = ($minTahun != $maxTahun) ? " Tahun $minTahun-$maxTahun" : " Tahun $minTahun";
        }

        $staticProdiQuestion = new TemplatePertanyaan([
            'pertanyaan' => 'Persentase Lulusan Politeknik Statistika STIS' . $tahunText . ' Menurut Prodi',
            'visualisasi' => 'pie',
        ]);
        $staticProdiQuestion->setAttribute('id', 999999);
        $staticProdiQuestion->setAttribute('blok', 'Informasi Populasi Lulusan');

        $staticProdiTahunQuestion = new TemplatePertanyaan([
            'pertanyaan' => 'Jumlah Lulusan Politeknik Statistika STIS' . $tahunText . ' Menurut Tahun Lulus dan Prodi',
            'visualisasi' => 'stacked_bar',
        ]);
        $staticProdiTahunQuestion->setAttribute('id', 999998);
        $staticProdiTahunQuestion->setAttribute('blok', 'Informasi Populasi Lulusan');

        $questions->prepend($staticProdiTahunQuestion);
        $questions->prepend($staticProdiQuestion);

        $allSurveys = Survey::orderBy('nama')->get();
        $initialChartPayload = $this->buildChartsPayload($survey, $selectedSegmentDimension);

        return view('admin.views.dashboard_grafik', compact(
            'survey',
            'questions',
            'allSurveys',
            'selectedSegmentDimension',
            'initialChartPayload'
        ));
    }

    /**
     * Show analytic table page.
     */
    public function analitik(Request $request, $id)
    {
        $survey = Survey::findOrFail($id);
        $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
        $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
            ? $selectedSegmentDimension
            : 'prodi';

        $questions = TemplatePertanyaan::where('id_survey', $id)
            ->where('is_analytic_table', true)
            ->get();

        $allSurveys = Survey::orderBy('nama')->get();
        $initialAnalitikPayload = $this->buildChartsPayload($survey, $selectedSegmentDimension, null, true);

        return view('admin.views.dashboard_analitik', compact(
            'survey',
            'questions',
            'allSurveys',
            'selectedSegmentDimension',
            'initialAnalitikPayload'
        ));
    }

    /**
     * Get all analytic table data.
     */
    public function getAnalitikData(Request $request, $surveyId): JsonResponse
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
            $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
                ? $selectedSegmentDimension
                : 'prodi';

            $payload = $this->buildChartsPayload($survey, $selectedSegmentDimension, null, true);

            return response()->json($payload);
        } catch (\Exception $e) {
            Log::error('Error generating dashboard analitik data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error generating dashboard analitik data',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Export analytic table to Excel.
     */
    public function exportAnalitik(Request $request, $surveyId)
    {
        $survey = Survey::findOrFail($surveyId);
        $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
        $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
            ? $selectedSegmentDimension
            : 'prodi';

        $payload = $this->buildChartsPayload($survey, $selectedSegmentDimension, null, true);
        
        $filename = 'Analitik_Survey_' . str_replace(' ', '_', $survey->nama) . '_' . date('YmdHis') . '.xlsx';
        
        return \Maatwebsite\Excel\Facades\Excel::download(
            new \App\Exports\AnalitikExport($payload, $survey->nama, $selectedSegmentDimension),
            $filename
        );
    }

    /**
     * Get all chart data for dashboard visualization.
     */
    public function getChartsData(Request $request, $surveyId): JsonResponse
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
            $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
                ? $selectedSegmentDimension
                : 'prodi';

            $payload = $this->buildChartsPayload($survey, $selectedSegmentDimension);

            return response()->json($payload);
        } catch (\Exception $e) {
            Log::error('Error generating dashboard charts data: ' . $e->getMessage());

            return response()->json([
                'error' => 'Error generating dashboard charts data',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Backward-compatible endpoint to get chart data for a specific question.
     */
    public function getChartData(Request $request, $surveyId, $questionId): JsonResponse
    {
        try {
            $survey = Survey::findOrFail($surveyId);
            $selectedSegmentDimension = trim((string) $request->query('breakdown_by', 'prodi'));
            $selectedSegmentDimension = in_array($selectedSegmentDimension, ['prodi', 'tahun_lulus'], true)
                ? $selectedSegmentDimension
                : 'prodi';

            $question = TemplatePertanyaan::where('id_survey', $surveyId)
                ->where('id', $questionId)
                ->firstOrFail();

            if (!$question->visualisasi) {
                throw new \Exception('Visualization type not set for this question');
            }

            Log::info('Starting dashboard chart data generation', [
                'survey_id' => $surveyId,
                'question_id' => $questionId,
                'question_type' => $question->tipe,
                'visualisasi' => $question->visualisasi,
            ]);

            $payload = $this->buildChartsPayload(
                $survey,
                $selectedSegmentDimension,
                [(int) $questionId]
            );

            $chart = $payload['charts'][(string) $questionId] ?? null;

            if ($chart === null) {
                return response()->json([
                    'error' => 'Chart data not found',
                ], 404);
            }

            return response()->json($chart);
        } catch (\Exception $e) {
            Log::error('Error generating dashboard chart data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error generating chart data',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    private function buildChartsPayload(
        Survey $survey,
        string $selectedSegmentDimension,
        ?array $questionIds = null,
        bool $forAnalitik = false
    ): array {
        $questionsQuery = TemplatePertanyaan::query()
            ->where('id_survey', $survey->id)
            ->with(['templateJawaban' => function ($query) {
                $query->orderBy('urutan');
            }])
            ->select(['id', 'pertanyaan', 'visualisasi', 'tipe', 'block_id', 'grid_columns', 'is_analytic_table']);

        if ($forAnalitik) {
            $questionsQuery->where('is_analytic_table', true);
        } else {
            $questionsQuery->whereNotNull('visualisasi')
                ->where('visualisasi', '!=', '');
        }

        if (is_array($questionIds) && count($questionIds) > 0) {
            $questionsQuery->whereIn('id', $questionIds);
        }

        $rawQuestions = $questionsQuery->orderBy('urutan')->get();
        
        $regularQuestions = collect();
        $kompetensiGroups = [];

        foreach ($rawQuestions as $question) {
            $block = \App\Models\SurveyBlock::find($question->block_id);
            if ($block && isset($block->metadata['is_kompetensi']) && $block->metadata['is_kompetensi']) {
                $kompetensiGroups[$block->id][] = $question;
            } else {
                $regularQuestions->push($question);
            }
        }
        
        foreach ($kompetensiGroups as $blockId => $group) {
            if (count($group) === 0) continue;
            $firstQ = $group[0];
            $block = \App\Models\SurveyBlock::find($blockId);
            
            $syntheticQ = clone $firstQ;
            $syntheticQ->tipe = 'kompetensi_grid';
            $syntheticQ->pertanyaan = $block->metadata['pertanyaan_utama'] ?? 'Penilaian Kompetensi';
            $syntheticQ->children = $group;
            $regularQuestions->push($syntheticQ);
        }
        
        $questions = $regularQuestions;
        
        $resolvedQuestionIds = [];
        foreach ($questions as $question) {
            if ($question->tipe === 'kompetensi_grid') {
                foreach ($question->children as $child) {
                    $resolvedQuestionIds[] = $child->id;
                }
            } else {
                $resolvedQuestionIds[] = $question->id;
            }
        }

        $segmentColumn = $selectedSegmentDimension === 'tahun_lulus' ? 'lulusan.tahun_lulus' : 'lulusan.prodi';
        $segmentLabel = $selectedSegmentDimension === 'tahun_lulus' ? 'Tahun Lulus' : 'Program Studi';

        $baseAnswersQuery = SurveyUserJawaban::query()
            ->join('survey_user', 'survey_user.id', '=', 'survey_user_jawaban.survey_user_id')
            ->join('users', 'users.id', '=', 'survey_user.user_id')
            ->where('survey_user.survey_id', $survey->id)
            ->where('survey_user.status', true)
            ->whereNotNull('survey_user_jawaban.jawaban')
            ->where('survey_user_jawaban.jawaban', '!=', '');

        if ($this->normalizeSurveyType($survey->type_survei) === 'pengguna_lulusan') {
            $baseAnswersQuery->leftJoin('pengguna_lulusan', 'pengguna_lulusan.user_id', '=', 'users.id')
                ->leftJoin('lulusan', 'lulusan.nip_pengguna_lulusan', '=', 'pengguna_lulusan.nip');
        } else {
            $baseAnswersQuery->leftJoin('lulusan', 'lulusan.user_id', '=', 'users.id');
        }

        if (!empty($resolvedQuestionIds)) {
            $baseAnswersQuery->whereIn('survey_user_jawaban.template_pertanyaan_id', $resolvedQuestionIds);
        } else {
            $baseAnswersQuery->whereRaw('1 = 0');
        }

        $aggregatedAnswers = (clone $baseAnswersQuery)
            ->select([
                'survey_user_jawaban.template_pertanyaan_id as question_id',
                'survey_user_jawaban.jawaban as answer_label',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_answer')
            ])
            ->groupBy('survey_user_jawaban.template_pertanyaan_id', 'survey_user_jawaban.jawaban')
            ->orderBy('survey_user_jawaban.template_pertanyaan_id')
            ->orderBy('survey_user_jawaban.jawaban')
            ->get()
            ->groupBy('question_id');

        $segmentRanking = (clone $this->buildSurveyUserLulusanBaseQuery($survey))
            ->whereNotNull($segmentColumn)
            ->where($segmentColumn, '!=', '')
            ->select([
                \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value"),
                \Illuminate\Support\Facades\DB::raw('COUNT(DISTINCT survey_user.id) as total_respondents')
            ])
            ->groupBy(\Illuminate\Support\Facades\DB::raw($segmentColumn))
            ->orderByDesc('total_respondents')
            ->orderBy(\Illuminate\Support\Facades\DB::raw($segmentColumn))
            ->get();

        $selectedSegments = $segmentRanking
            ->pluck('segment_value')
            ->take(10) // Increased limit to show more segments
            ->values()
            ->all();

        $segmentedAnswers = (clone $baseAnswersQuery)
            ->whereNotNull($segmentColumn)
            ->where($segmentColumn, '!=', '')
            ->when(!empty($selectedSegments), function ($query) use ($segmentColumn, $selectedSegments) {
                $query->whereIn($segmentColumn, $selectedSegments);
            })
            ->select([
                'survey_user_jawaban.template_pertanyaan_id as question_id',
                \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value"),
                'survey_user_jawaban.jawaban as answer_label',
                \Illuminate\Support\Facades\DB::raw('COUNT(*) as total_answer')
            ])
            ->groupBy('survey_user_jawaban.template_pertanyaan_id', \Illuminate\Support\Facades\DB::raw($segmentColumn), 'survey_user_jawaban.jawaban')
            ->orderBy('survey_user_jawaban.template_pertanyaan_id')
            ->orderBy(\Illuminate\Support\Facades\DB::raw($segmentColumn))
            ->orderBy('survey_user_jawaban.jawaban')
            ->get()
            ->groupBy('question_id');

        $choiceQuestionTypes = ['radio', 'checkbox', 'select', 'multiple_choice_grid', 'kompetensi_grid'];

        $charts = [];
        foreach ($questions as $question) {
            $chartType = $question->visualisasi ?? 'bar';
            $labelOrder = $this->resolveQuestionLabelOrder($question);

            if ($question->tipe === 'gaji') {
                $questionRows = (clone $baseAnswersQuery)
                    ->where('survey_user_jawaban.template_pertanyaan_id', $question->id)
                    ->select([
                        'survey_user_jawaban.jawaban as answer_value',
                        \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value")
                    ])
                    ->get();
                
                $totalSalary = 0;
                $countSalary = 0;
                $segmentGajiData = [];

                foreach ($questionRows as $row) {
                    $val = floatval($row->answer_value);
                    if ($val > 0) {
                        $totalSalary += $val;
                        $countSalary++;
                        
                        $segVal = (string) $row->segment_value;
                        if (!isset($segmentGajiData[$segVal])) {
                            $segmentGajiData[$segVal] = ['total' => 0, 'count' => 0];
                        }
                        $segmentGajiData[$segVal]['total'] += $val;
                        $segmentGajiData[$segVal]['count']++;
                    }
                }
                
                $averageSalary = $countSalary > 0 ? round($totalSalary / $countSalary) : 0;
                
                $segmentCharts = [];
                foreach ($selectedSegments as $segVal) {
                    $segData = $segmentGajiData[$segVal] ?? ['total' => 0, 'count' => 0];
                    $segAverage = $segData['count'] > 0 ? round($segData['total'] / $segData['count']) : 0;
                    
                    $segmentCharts[] = [
                        'segmentLabel' => $segVal,
                        'average' => $segAverage,
                        'count' => $segData['count']
                    ];
                }

                $charts[(string) $question->id] = [
                    'type' => 'average_card',
                    'questionText' => $question->pertanyaan,
                    'blok' => $question->block_id,
                    'segmentDimension' => $segmentLabel,
                    'aggregate' => [
                        'title' => 'Politeknik Statistika STIS',
                        'average' => $averageSalary,
                        'count' => $countSalary
                    ],
                    'segments' => $segmentCharts,
                ];
                
                continue;
            }

            if (in_array($question->tipe, $choiceQuestionTypes, true)) {
                $questionRows = (clone $baseAnswersQuery)
                    ->where('survey_user_jawaban.template_pertanyaan_id', $question->id)
                    ->select([
                        'survey_user_jawaban.jawaban as answer_value',
                        \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value")
                    ])
                    ->get();

                $choiceSummary = $this->summarizeChoiceQuestionRows($question, $questionRows);
                $labelOrder = $choiceSummary['labelOrder'] ?: $labelOrder;
                $aggCounts = $this->alignCountsToLabels($labelOrder, $choiceSummary['aggregateCounts']);
                $aggTotal = array_sum($aggCounts);
                $aggPercentages = $this->countsToPercentages($aggCounts, $aggTotal);

                $segmentCharts = [];
                foreach ($selectedSegments as $segVal) {
                    $counts = $choiceSummary['segmentCounts'][$segVal] ?? [];
                    $counts = $this->alignCountsToLabels($labelOrder, $counts);
                    $segTotal = array_sum($counts);
                    $percentages = $this->countsToPercentages($counts, $segTotal);

                    $segmentCharts[] = [
                        'segmentLabel' => $segVal,
                        'labels' => array_keys($percentages),
                        'percentages' => array_values($percentages),
                        'counts' => array_map(function ($label) use ($counts) {
                            return $counts[$label] ?? 0;
                        }, array_keys($percentages)),
                        'total' => $segTotal,
                    ];
                }

                if (($forAnalitik || true) && $question->tipe === 'kompetensi_grid') {
                    $gridSummary = $this->summarizeKompetensiGroup($question, $baseAnswersQuery, $segmentColumn);
                    
                    $charts[(string) $question->id] = [
                        'type' => 'kompetensi_block',
                        'questionText' => $question->pertanyaan,
                        'blok' => $question->block_id,
                        'segmentDimension' => $segmentLabel,
                        'columns' => $gridSummary['columns'],
                        'maxSkala' => $gridSummary['maxSkala'] ?? count($gridSummary['columns']),
                        'aggregate' => $gridSummary['aggregate'],
                        'segments' => $gridSummary['segments'],
                    ];
                    
                    continue;
                }

                if ($forAnalitik && $question->tipe === 'multiple_choice_grid') {
                    $questionRows = (clone $baseAnswersQuery)
                        ->where('survey_user_jawaban.template_pertanyaan_id', $question->id)
                        ->select([
                            'survey_user_jawaban.jawaban as answer_value',
                            \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value")
                        ])
                        ->get();

                    $gridSummary = $this->summarizeGridQuestionRows($question, $questionRows);
                    
                    $charts[(string) $question->id] = [
                        'type' => 'multiple_choice_grid',
                        'questionText' => $question->pertanyaan,
                        'blok' => $question->block_id,
                        'segmentDimension' => $segmentLabel,
                        'columns' => $gridSummary['columns'],
                        'aggregate' => $gridSummary['aggregate'],
                        'segments' => $gridSummary['segments'],
                    ];
                    
                    continue;
                }

                $questionRows = (clone $baseAnswersQuery)
                    ->where('survey_user_jawaban.template_pertanyaan_id', $question->id)
                    ->select([
                        'survey_user_jawaban.jawaban as answer_value',
                        \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value")
                    ])
                    ->get();

                $choiceSummary = $this->summarizeChoiceQuestionRows($question, $questionRows);
                $labelOrder = $choiceSummary['labelOrder'] ?: $labelOrder;
                $aggCounts = $this->alignCountsToLabels($labelOrder, $choiceSummary['aggregateCounts']);
                $aggTotal = array_sum($aggCounts);
                $aggPercentages = $this->countsToPercentages($aggCounts, $aggTotal);

                $segmentCharts = [];
                foreach ($selectedSegments as $segVal) {
                    $counts = $choiceSummary['segmentCounts'][$segVal] ?? [];
                    $counts = $this->alignCountsToLabels($labelOrder, $counts);
                    $segTotal = array_sum($counts);
                    $percentages = $this->countsToPercentages($counts, $segTotal);

                    $segmentCharts[] = [
                        'segmentLabel' => $segVal,
                        'labels' => array_keys($percentages),
                        'percentages' => array_values($percentages),
                        'counts' => array_map(function ($label) use ($counts) {
                            return $counts[$label] ?? 0;
                        }, array_keys($percentages)),
                        'total' => $segTotal,
                    ];
                }

                $charts[(string) $question->id] = [
                    'type' => $chartType,
                    'questionText' => $question->pertanyaan,
                    'blok' => $question->block_id,
                    'segmentDimension' => $segmentLabel,
                    'aggregate' => [
                        'title' => 'Politeknik Statistika STIS',
                        'labels' => array_keys($aggPercentages),
                        'percentages' => array_values($aggPercentages),
                        'counts' => array_values($aggCounts),
                        'total' => $aggTotal,
                    ],
                    'segments' => $segmentCharts,
                ];

                continue;
            }

            $aggRows = $aggregatedAnswers->get($question->id, collect());
            $aggCounts = [];
            foreach ($aggRows as $row) {
                $aggCounts[(string) $row->answer_label] = (int) $row->total_answer;
            }

            $aggCounts = $this->alignCountsToLabels($labelOrder, $aggCounts);
            $aggTotal = array_sum($aggCounts);
            $aggPercentages = $this->countsToPercentages($aggCounts, $aggTotal);

            $segRows = $segmentedAnswers->get($question->id, collect());
            $segmentData = [];

            foreach ($segRows as $row) {
                $segVal = (string) $row->segment_value;
                if (!isset($segmentData[$segVal])) {
                    $segmentData[$segVal] = [];
                }
                $segmentData[$segVal][(string) $row->answer_label] = (int) $row->total_answer;
            }

            $segmentCharts = [];
            foreach ($selectedSegments as $segVal) {
                $counts = $segmentData[$segVal] ?? [];
                $counts = $this->alignCountsToLabels($labelOrder, $counts);
                $segTotal = array_sum($counts);
                $percentages = $this->countsToPercentages($counts, $segTotal);

                $segmentCharts[] = [
                    'segmentLabel' => $segVal,
                    'labels' => array_keys($percentages),
                    'percentages' => array_values($percentages),
                    'counts' => array_map(function ($label) use ($counts) {
                        return $counts[$label] ?? 0;
                    }, array_keys($percentages)),
                    'total' => $segTotal,
                ];
            }

            $charts[(string) $question->id] = [
                'type' => $chartType,
                'questionText' => $question->pertanyaan,
                'blok' => $question->block_id,
                'segmentDimension' => $segmentLabel,
                'aggregate' => [
                    'title' => 'Politeknik Statistika STIS',
                    'labels' => array_keys($aggPercentages),
                    'percentages' => array_values($aggPercentages),
                    'counts' => array_values($aggCounts),
                    'total' => $aggTotal,
                ],
                'segments' => $segmentCharts,
            ];
        }

        if (!$forAnalitik) {
            $minTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->min('tahun_lulus');
            $maxTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->max('tahun_lulus');
            $tahunText = '';
            if ($minTahun && $maxTahun) {
                $tahunText = ($minTahun != $maxTahun) ? " Tahun $minTahun-$maxTahun" : " Tahun $minTahun";
            }
            
            $prodiStats = \Illuminate\Support\Facades\DB::table('lulusan')
                ->whereNotNull('prodi')
                ->where('prodi', '!=', '')
                ->select('prodi', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('prodi')
                ->get();

            $prodiLabels = [];
            $prodiCounts = [];
            $totalProdi = 0;

            foreach ($prodiStats as $stat) {
                $prodiLabels[] = $stat->prodi;
                $prodiCounts[] = $stat->total;
                $totalProdi += $stat->total;
            }

            $prodiPercentages = [];
            foreach ($prodiCounts as $count) {
                $prodiPercentages[] = $totalProdi > 0 ? round(($count / $totalProdi) * 100, 2) : 0;
            }

            if ($totalProdi > 0) {
                // Stacked Bar Data for 999998
                $prodiTahunStats = \Illuminate\Support\Facades\DB::table('lulusan')
                    ->whereNotNull('tahun_lulus')
                    ->where('tahun_lulus', '!=', '')
                    ->whereNotNull('prodi')
                    ->where('prodi', '!=', '')
                    ->select('tahun_lulus', 'prodi', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                    ->groupBy('tahun_lulus', 'prodi')
                    ->orderBy('tahun_lulus')
                    ->get();

                $yearsMap = [];
                $prodisMap = [];
                foreach ($prodiTahunStats as $stat) {
                    $yearsMap[$stat->tahun_lulus] = true;
                    $prodisMap[$stat->prodi] = true;
                }
                $years = array_keys($yearsMap);
                sort($years);
                $uniqueProdis = array_keys($prodisMap);

                $prodiOrder = ['DIII Statistika', 'DIV Statistika', 'DIV Komputasi Statistik'];
                $orderedProdis = [];
                foreach ($prodiOrder as $p) {
                    if (in_array($p, $uniqueProdis)) {
                        $orderedProdis[] = $p;
                    }
                }
                foreach ($uniqueProdis as $p) {
                    if (!in_array($p, $orderedProdis)) {
                        $orderedProdis[] = $p;
                    }
                }

                $datasets = [];
                foreach ($orderedProdis as $prodi) {
                    $data = [];
                    foreach ($years as $year) {
                        $val = 0;
                        foreach ($prodiTahunStats as $stat) {
                            if ($stat->tahun_lulus == $year && $stat->prodi == $prodi) {
                                $val = $stat->total;
                                break;
                            }
                        }
                        $data[] = $val;
                    }
                    $label = str_replace('DIII', 'D-III', $prodi);
                    $label = str_replace('DIV', 'D-IV', $label);
                    
                    $datasets[] = [
                        'label' => $label,
                        'data' => $data
                    ];
                }

                if (count($years) > 0) {
                    $staticProdiTahunChart = [
                        'type' => 'stacked_bar',
                        'questionText' => 'Jumlah Lulusan Politeknik Statistika STIS' . $tahunText . ' Menurut Tahun Lulus dan Prodi',
                        'blok' => 'Informasi Populasi Lulusan',
                        'segmentDimension' => $segmentLabel,
                        'aggregate' => [
                            'title' => 'Politeknik Statistika STIS',
                            'labels' => $years,
                            'datasets' => $datasets,
                            'percentages' => [], // unused but to avoid undef
                            'counts' => [],
                            'total' => 0,
                        ],
                        'segments' => [],
                    ];
                    $charts = ['999998' => $staticProdiTahunChart] + $charts;
                }
                $staticProdiChart = [
                    'type' => 'pie',
                    'questionText' => 'Persentase Lulusan Politeknik Statistika STIS' . $tahunText . ' Menurut Prodi',
                    'blok' => 'Informasi Populasi Lulusan',
                    'segmentDimension' => $segmentLabel,
                    'aggregate' => [
                        'title' => 'Politeknik Statistika STIS',
                        'labels' => $prodiLabels,
                        'percentages' => $prodiPercentages,
                        'counts' => $prodiCounts,
                        'total' => $totalProdi,
                    ],
                    'segments' => [],
                ];
                $charts = ['999999' => $staticProdiChart] + $charts;
            }
        }

        if ($forAnalitik) {
            $minTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->min('tahun_lulus');
            $maxTahun = \Illuminate\Support\Facades\DB::table('lulusan')->whereNotNull('tahun_lulus')->where('tahun_lulus', '!=', '')->max('tahun_lulus');
            $tahunText = '';
            if ($minTahun && $maxTahun) {
                $tahunText = ($minTahun != $maxTahun) ? " Tahun $minTahun-$maxTahun" : " Tahun $minTahun";
            }

            $prodiTahunStats = \Illuminate\Support\Facades\DB::table('lulusan')
                ->whereNotNull('tahun_lulus')
                ->where('tahun_lulus', '!=', '')
                ->whereNotNull('prodi')
                ->where('prodi', '!=', '')
                ->select('tahun_lulus', 'prodi', \Illuminate\Support\Facades\DB::raw('count(*) as total'))
                ->groupBy('tahun_lulus', 'prodi')
                ->orderBy('tahun_lulus')
                ->get();

            $yearsMap = [];
            $prodisMap = [];
            foreach ($prodiTahunStats as $stat) {
                $yearsMap[$stat->tahun_lulus] = true;
                $prodisMap[$stat->prodi] = true;
            }
            $years = array_keys($yearsMap);
            sort($years);
            
            $prodiOrder = ['DIII Statistika', 'DIV Statistika', 'DIV Komputasi Statistik', 'D-IV Komputasi Statistika']; // covering both name variants
            $orderedProdis = [];
            $uniqueProdis = array_keys($prodisMap);
            foreach ($prodiOrder as $p) {
                if (in_array($p, $uniqueProdis)) {
                    $orderedProdis[] = $p;
                }
            }
            foreach ($uniqueProdis as $p) {
                if (!in_array($p, $orderedProdis)) {
                    $orderedProdis[] = $p;
                }
            }

            $rows = [];
            $colTotals = array_fill(0, count($years), 0);
            $grandTotal = 0;

            foreach ($orderedProdis as $prodi) {
                $rowData = [];
                $rowTotal = 0;
                foreach ($years as $idx => $year) {
                    $val = 0;
                    foreach ($prodiTahunStats as $stat) {
                        if ($stat->tahun_lulus == $year && $stat->prodi == $prodi) {
                            $val = $stat->total;
                            break;
                        }
                    }
                    $rowData[] = $val;
                    $rowTotal += $val;
                    $colTotals[$idx] += $val;
                }
                $rowData[] = $rowTotal;
                $grandTotal += $rowTotal;

                $label = str_replace('DIII', 'D-III', $prodi);
                $label = str_replace('DIV', 'D-IV', $label);

                // Quick fix for screenshot "D-IV Komputasi Statistika" vs "D-IV Komputasi Statistik"
                if (strpos($label, 'Komputasi Statistik') !== false) {
                    $label = 'D-IV Komputasi Statistika';
                }

                $rows[] = [
                    'label' => $label,
                    'data' => $rowData
                ];
            }

            $footerData = $colTotals;
            $footerData[] = $grandTotal;

            if (count($years) > 0) {
                $staticProdiTahunTableChart = [
                    'type' => 'static_crosstab',
                    'questionText' => 'Tabel 2.1. Jumlah Lulusan Politeknik Statistika STIS' . $tahunText . ' menurut Program Studi dan Tahun Kelulusan',
                    'blok' => 'Informasi Populasi Lulusan',
                    'segmentDimension' => $segmentLabel,
                    'aggregate' => [
                        'title' => 'Tabel 2.1. Jumlah Lulusan Politeknik Statistika STIS' . $tahunText . ' menurut Program Studi dan Tahun Kelulusan',
                        'columns' => array_merge([''], $years, ['Jumlah']),
                        'rows' => $rows,
                        'footer' => [
                            'label' => 'Total',
                            'data' => $footerData
                        ]
                    ],
                    'segments' => [],
                ];
                $charts = ['999997' => $staticProdiTahunTableChart] + $charts;
            }
        }

        $summaryQuery = $this->buildSurveyUserLulusanBaseQuery($survey);

        $totalResponses = (clone $summaryQuery)
            ->distinct()
            ->count('survey_user.id');

        $completedResponses = (clone $summaryQuery)
            ->where('survey_user.status', true)
            ->distinct()
            ->count('survey_user.id');

        return [
            'charts' => $charts,
            'segments' => $selectedSegments,
            'segmentDimension' => $segmentLabel,
            'summary' => [
                'totalResponses' => $totalResponses,
                'completedResponses' => $completedResponses,
                'completionRate' => $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0,
            ],
        ];
    }

    private function buildSurveyUserLulusanBaseQuery(Survey $survey): Builder
    {
        $query = SurveyUser::query()
            ->where('survey_user.survey_id', $survey->id)
            ->join('users', 'users.id', '=', 'survey_user.user_id');

        if ($this->normalizeSurveyType($survey->type_survei) === 'pengguna_lulusan') {
            $query->leftJoin('pengguna_lulusan', 'pengguna_lulusan.user_id', '=', 'users.id')
                ->leftJoin('lulusan', 'lulusan.nip_pengguna_lulusan', '=', 'pengguna_lulusan.nip');
        } else {
            $query->leftJoin('lulusan', 'lulusan.user_id', '=', 'users.id');
        }

        return $query;
    }

    private function summarizeKompetensiGroup($syntheticQ, $baseAnswersQuery, $segmentColumn): array
    {
        $firstChild = $syntheticQ->children[0];
        $isGrid = $firstChild->tipe === 'multiple_choice_grid';
        
        $columns = [];
        $gridRowIdsByQuestion = [];
        if ($isGrid) {
            foreach ($firstChild->templateJawaban as $ans) {
                $columns[] = $ans->pilihan_jawaban;
            }
            $maxSkala = count($firstChild->grid_columns ?? []) ?: 5;
            foreach ($syntheticQ->children as $child) {
                $childRowIds = [];
                foreach ($child->templateJawaban as $ans) {
                    $childRowIds[] = (string) $ans->id;
                }
                $gridRowIdsByQuestion[(string)$child->id] = $childRowIds;
            }
        } else {
            foreach ($firstChild->templateJawaban as $ans) {
                $columns[] = $ans->pilihan_jawaban;
            }
            $maxSkala = count($columns);
        }
        
        $rowOptions = [];
        foreach ($syntheticQ->children as $child) {
            $rowOptions[(string) $child->id] = trim($child->pertanyaan);
        }
        
        $childIds = array_keys($rowOptions);
        $dataRows = (clone $baseAnswersQuery)
            ->whereIn('survey_user_jawaban.template_pertanyaan_id', $childIds)
            ->select([
                'survey_user_jawaban.template_pertanyaan_id as question_id',
                'survey_user_jawaban.jawaban as answer_value',
                \Illuminate\Support\Facades\DB::raw("$segmentColumn as segment_value")
            ])
            ->get();
            
        $processData = function($rowsData) use ($columns, $rowOptions, $syntheticQ, $isGrid, $gridRowIdsByQuestion, $maxSkala) {
            $aspectData = [];
            foreach ($rowOptions as $rowId => $rowLabel) {
                $aspectData[$rowId] = [
                    'label' => $rowLabel,
                    'counts' => array_fill(0, count($columns), 0),
                    'total' => 0,
                    'col_totals' => array_fill(0, count($columns), 0)
                ];
            }
            
            // Build legacy map for scalar answers
            $legacyMaps = [];
            if ($isGrid) {
                $scalarAnswersByQ = [];
                foreach ($rowsData as $row) {
                    $rowId = (string) $row->question_id;
                    $val = trim($row->answer_value);
                    if ($val !== '' && $val[0] !== '{' && $val[0] !== '[' && is_numeric($val)) {
                        $scalarAnswersByQ[$rowId][] = (int)$val;
                    }
                }
                foreach ($scalarAnswersByQ as $qId => $vals) {
                    $uniqueVals = array_values(array_unique($vals));
                    sort($uniqueVals);
                    foreach ($uniqueVals as $idx => $id) {
                        $score = min($idx + 1, $maxSkala);
                        $legacyMaps[$qId][(string)$id] = $score;
                    }
                }
            }
            
            foreach ($rowsData as $row) {
                $rowId = (string) $row->question_id;
                if (!isset($aspectData[$rowId])) continue;
                
                $answerVal = $row->answer_value;
                
                if ($isGrid) {
                    $decoded = json_decode($answerVal, true);
                    if (is_array($decoded)) {
                        $currentGridRowIds = $gridRowIdsByQuestion[$rowId] ?? [];
                        foreach ($columns as $idx => $colName) {
                            $gridRowId = $currentGridRowIds[$idx] ?? null;
                            if ($gridRowId && isset($decoded[$gridRowId])) {
                                $score = (int)$decoded[$gridRowId];
                                if ($score >= 1 && $score <= $maxSkala) {
                                    $aspectData[$rowId]['counts'][$idx] += $score;
                                    $aspectData[$rowId]['col_totals'][$idx]++;
                                }
                            }
                        }
                        $aspectData[$rowId]['total']++;
                    } else {
                        // Handle legacy scalar answer
                        $val = trim($answerVal);
                        if (isset($legacyMaps[$rowId][$val])) {
                            $score = $legacyMaps[$rowId][$val];
                            foreach ($columns as $idx => $colName) {
                                $aspectData[$rowId]['counts'][$idx] += $score;
                                $aspectData[$rowId]['col_totals'][$idx]++;
                            }
                            $aspectData[$rowId]['total']++;
                        }
                    }
                } else {
                    $actualText = null;
                    $childQ = null;
                    foreach ($syntheticQ->children as $c) {
                        if ($c->id == $row->question_id) {
                            $childQ = $c;
                            break;
                        }
                    }
                    
                    if ($childQ) {
                        foreach ($childQ->templateJawaban as $ans) {
                            if ((string)$ans->id === (string)$answerVal) {
                                $actualText = $ans->pilihan_jawaban;
                                break;
                            }
                        }
                        if (!$actualText) {
                            $actualText = $answerVal; // fallback for grid text answers if any
                        }
                    }
                    
                    $scoreIndex = -1;
                    if ($actualText) {
                        foreach ($columns as $idx => $colLabel) {
                            if (trim($colLabel) === trim($actualText)) {
                                $scoreIndex = $idx;
                                break;
                            }
                        }
                    }
                    
                    if ($scoreIndex >= 0 && $scoreIndex < count($columns)) {
                        $aspectData[$rowId]['counts'][$scoreIndex]++;
                        $aspectData[$rowId]['total']++;
                    }
                }
            }
            
            $results = [];
            $columnTotals = array_fill(0, count($columns), 0);
            $totalSkalaScore = 0;
            $totalSkala100Score = 0;
            $rowCount = 0;

            foreach ($aspectData as $id => $data) {
                $percentages = [];
                $skalaScore = 0;
                
                if ($isGrid) {
                    $sumPercentages = 0;
                    foreach ($data['counts'] as $idx => $sumScore) {
                        $colTotal = $data['col_totals'][$idx];
                        $avgScore = $colTotal > 0 ? ($sumScore / $colTotal) : 0;
                        $p = $colTotal > 0 ? round(($avgScore / $maxSkala) * 100, 2) : 0;
                        $percentages[] = $p;
                        $columnTotals[$idx] += $p;
                        $sumPercentages += $p;
                    }
                    $skala100 = count($columns) > 0 ? round($sumPercentages / count($columns), 2) : 0;
                    $skalaN = round(($skala100 / 100) * $maxSkala, 2);
                    
                    if ($data['total'] > 0) $rowCount++;
                    $totalSkalaScore += $skalaN;
                    $totalSkala100Score += $skala100;
                } else {
                    foreach ($data['counts'] as $idx => $count) {
                        $p = $data['total'] > 0 ? round(($count / $data['total']) * 100, 2) : 0;
                        $percentages[] = $p;
                        $columnTotals[$idx] += $p;
                        $weight = $maxSkala - $idx;
                        $skalaScore += ($p / 100) * $weight;
                    }
                    if ($data['total'] > 0) $rowCount++;

                    $skalaN = round($skalaScore, 2);
                    $skala100 = round(($skalaScore / $maxSkala) * 100, 2);
                    $totalSkalaScore += $skalaScore;
                    $totalSkala100Score += ($skalaScore / $maxSkala) * 100;
                }

                $results[] = [
                    'label' => $data['label'],
                    'counts' => $data['counts'],
                    'percentages' => $percentages,
                    'skalaN' => $skalaN,
                    'skala100' => $skala100,
                    'total' => $data['total']
                ];
            }

            $totalPercentages = [];
            foreach ($columnTotals as $totalP) {
                $totalPercentages[] = $rowCount > 0 ? round($totalP / $rowCount, 2) : 0;
            }

            $results[] = [
                'label' => 'Total',
                'counts' => array_fill(0, count($columns), 0),
                'percentages' => $totalPercentages,
                'skalaN' => $rowCount > 0 ? round($totalSkalaScore / $rowCount, 2) : 0,
                'skala100' => $rowCount > 0 ? round($totalSkala100Score / $rowCount, 2) : 0,
                'total' => 0,
                'is_total' => true
            ];

            return $results;
        };

        $aggregateResults = $processData($dataRows);

        $segmentDataMap = [];
        foreach ($dataRows as $row) {
            $segVal = (string)$row->segment_value;
            if ($segVal !== '') {
                if (!isset($segmentDataMap[$segVal])) $segmentDataMap[$segVal] = [];
                $segmentDataMap[$segVal][] = $row;
            }
        }

        $segmentedResults = [];
        foreach ($segmentDataMap as $segVal => $segRows) {
            $segmentedResults[] = [
                'segmentLabel' => $segVal,
                'rows' => $processData($segRows)
            ];
        }

        return [
            'columns' => $columns,
            'maxSkala' => $maxSkala,
            'aggregate' => $aggregateResults,
            'segments' => $segmentedResults
        ];
    }

    private function resolveQuestionLabelOrder(TemplatePertanyaan $question): array
    {
        $choiceQuestionTypes = ['radio', 'checkbox', 'select', 'multiple_choice_grid', 'kompetensi_grid'];

        if (!in_array($question->tipe, $choiceQuestionTypes, true)) {
            return [];
        }

        if ($question->tipe === 'multiple_choice_grid') {
            $columns = $question->grid_columns;
            if (empty($columns)) {
                $columns = ['Sangat Tidak Setuju', 'Tidak Setuju', 'Netral', 'Setuju', 'Sangat Setuju'];
            }
            return array_map(fn ($label) => trim((string) $label), $columns);
        }

        return $question->templateJawaban
            ->pluck('pilihan_jawaban')
            ->map(fn ($label) => trim((string) $label))
            ->filter(fn ($label) => $label !== '' && $label !== null)
            ->unique()
            ->values()
            ->all();
    }

    private function summarizeChoiceQuestionRows(TemplatePertanyaan $question, $rows): array
    {
        $labelOrder = $this->resolveQuestionLabelOrder($question);
        $optionLabelById = $question->templateJawaban->pluck('pilihan_jawaban', 'id')->mapWithKeys(function ($label, $id) {
            return [(string) $id => trim((string) $label)];
        })->all();
        $optionLabels = array_flip($labelOrder);

        // Pre-extract all raw values to handle legacy mapping if needed
        $allRawValues = [];
        foreach ($rows as $row) {
            $rawValues = $this->extractRawChoiceValues($question, $row->answer_value);
            foreach ($rawValues as $v) {
                $allRawValues[] = $v;
            }
        }

        // Build a dynamic map for legacy IDs that don't exist in the current template
        $legacyMap = $this->buildLegacyIdMap($question, $allRawValues, $optionLabelById, $labelOrder);

        $aggregateCounts = [];
        $segmentCounts = [];

        foreach ($rows as $row) {
            $segmentValue = isset($row->segment_value) ? (string) $row->segment_value : null;
            $resolvedLabels = $this->extractChoiceAnswerLabels($question, $row->answer_value, $optionLabelById, $optionLabels, $legacyMap);

            foreach ($resolvedLabels as $label) {
                $aggregateCounts[$label] = ($aggregateCounts[$label] ?? 0) + 1;

                if ($segmentValue !== null && $segmentValue !== '') {
                    if (!isset($segmentCounts[$segmentValue])) {
                        $segmentCounts[$segmentValue] = [];
                    }
                    $segmentCounts[$segmentValue][$label] = ($segmentCounts[$segmentValue][$label] ?? 0) + 1;
                }
            }
        }

        return [
            'labelOrder' => $labelOrder,
            'aggregateCounts' => $aggregateCounts,
            'segmentCounts' => $segmentCounts,
        ];
    }

    private function summarizeGridQuestionRows(TemplatePertanyaan $question, $rows): array
    {
        $columns = $this->resolveQuestionLabelOrder($question);
        $rowOptions = $question->templateJawaban->pluck('pilihan_jawaban', 'id')->mapWithKeys(function ($label, $id) {
            return [(string) $id => trim((string) $label)];
        })->all();
        
        $processData = function($dataRows) use ($columns, $rowOptions) {
            $aspectData = [];
            foreach ($rowOptions as $rowId => $rowLabel) {
                $aspectData[$rowId] = [
                    'label' => $rowLabel,
                    'counts' => array_fill(0, count($columns), 0),
                    'total' => 0
                ];
            }

            foreach ($dataRows as $row) {
                $decoded = json_decode($row->answer_value, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $rowId => $val) {
                        $rowId = (string)$rowId;
                        if (isset($aspectData[$rowId])) {
                            $score = (int)$val;
                            if ($score >= 1 && $score <= count($columns)) {
                                $aspectData[$rowId]['counts'][$score - 1]++;
                                $aspectData[$rowId]['total']++;
                            }
                        }
                    }
                }
            }

            $results = [];
            $columnTotals = array_fill(0, count($columns), 0);
            $rowCount = 0;

            foreach ($aspectData as $id => $data) {
                $percentages = [];
                foreach ($data['counts'] as $idx => $count) {
                    $p = $data['total'] > 0 ? round(($count / $data['total']) * 100, 2) : 0;
                    $percentages[] = $p;
                    $columnTotals[$idx] += $p;
                }
                
                if ($data['total'] > 0) $rowCount++;

                $results[] = [
                    'label' => $data['label'],
                    'counts' => $data['counts'],
                    'percentages' => $percentages,
                    'total' => $data['total']
                ];
            }

            // Add Total row (average of percentages)
            $totalPercentages = [];
            foreach ($columnTotals as $totalP) {
                $totalPercentages[] = $rowCount > 0 ? round($totalP / $rowCount, 2) : 0;
            }

            $results[] = [
                'label' => 'Total',
                'counts' => array_fill(0, count($columns), 0), // Not strictly needed for Total row in this format
                'percentages' => $totalPercentages,
                'total' => 0,
                'is_total' => true
            ];

            return $results;
        };

        // Aggregate data
        $aggregateResults = $processData($rows);

        // Segmented data
        $segmentData = [];
        foreach ($rows as $row) {
            $segVal = (string)$row->segment_value;
            if ($segVal !== '') {
                if (!isset($segmentData[$segVal])) $segmentData[$segVal] = [];
                $segmentData[$segVal][] = $row;
            }
        }

        $segmentedResults = [];
        foreach ($segmentData as $segVal => $segRows) {
            $segmentedResults[] = [
                'segmentLabel' => $segVal,
                'rows' => $processData($segRows)
            ];
        }

        return [
            'columns' => $columns,
            'aggregate' => $aggregateResults,
            'segments' => $segmentedResults
        ];
    }

    private function extractRawChoiceValues(TemplatePertanyaan $question, $rawAnswer): array
    {
        if (!is_string($rawAnswer) || $rawAnswer === '') {
            return [];
        }

        $trimmed = trim($rawAnswer);
        if ($trimmed === '') return [];

        if ($trimmed[0] === '[' || $trimmed[0] === '{') {
            $decoded = json_decode($trimmed, true);
            if (is_array($decoded)) {
                if ($question->tipe === 'multiple_choice_grid') {
                    return array_values($decoded);
                }
                return $this->flattenChoiceAnswerValues($decoded);
            }
        } elseif (strpos($trimmed, ',') !== false) {
            return explode(',', $trimmed);
        }

        return [$trimmed];
    }

    private function buildLegacyIdMap(TemplatePertanyaan $question, array $allValues, array $currentMap, array $labelOrder): array
    {
        if ($question->tipe === 'multiple_choice_grid') return [];
        if (empty($labelOrder)) return [];

        $unmappedIds = [];
        foreach ($allValues as $val) {
            $val = (string)$val;
            if (is_numeric($val) && !isset($currentMap[$val])) {
                $unmappedIds[] = (int)$val;
            }
        }

        $unmappedIds = array_values(array_unique($unmappedIds));
        sort($unmappedIds);

        if (empty($unmappedIds)) return [];

        // Heuristic: Map unmapped IDs to labels based on their relative order
        // This handles cases where survey options were recreated
        $legacyMap = [];
        
        // Strategy: For each unmapped ID, try to find a label at the same relative index
        // among all IDs seen for this question.
        $allUniqueIds = array_unique(array_filter($allValues, 'is_numeric'));
        $allUniqueIds = array_map('intval', $allUniqueIds);
        sort($allUniqueIds);

        foreach ($unmappedIds as $id) {
            $pos = array_search($id, $allUniqueIds);
            if ($pos !== false && isset($labelOrder[$pos])) {
                $legacyMap[(string)$id] = $labelOrder[$pos];
            }
        }

        return $legacyMap;
    }

    private function extractChoiceAnswerLabels(TemplatePertanyaan $question, $rawAnswer, array $optionLabelById, array $optionLabels, array $legacyMap = []): array
    {
        $choiceQuestionTypes = ['radio', 'select', 'checkbox', 'multiple_choice_grid'];
        if (!in_array($question->tipe, $choiceQuestionTypes, true)) {
            return [];
        }

        $values = [];
        $decoded = null;

        if (is_string($rawAnswer) && $rawAnswer !== '') {
            $trimmed = trim($rawAnswer);
            if ($trimmed !== '') {
                if ($trimmed[0] === '[' || $trimmed[0] === '{') {
                    $decoded = json_decode($trimmed, true);
                } elseif (strpos($trimmed, ',') !== false) {
                    // Handle comma-separated IDs (common for checkboxes from SurveyUserController)
                    $decoded = explode(',', $trimmed);
                }
            }
        }

        if (is_array($decoded)) {
            if ($question->tipe === 'multiple_choice_grid') {
                // For grid, the decoded is {"rowId": value, ...}
                // We want to aggregate the values (scores/labels)
                $columns = $this->resolveQuestionLabelOrder($question);
                foreach ($decoded as $rowId => $val) {
                    $score = (int)$val;
                    if (isset($columns[$score - 1])) {
                        $values[] = $columns[$score - 1];
                    } else {
                        $values[] = (string)$val;
                    }
                }
            } else {
                $flattened = $this->flattenChoiceAnswerValues($decoded);
                foreach ($flattened as $value) {
                    $values[] = $this->normalizeChoiceAnswerValue($value, $optionLabelById, $optionLabels, $legacyMap);
                }
            }
        } else {
            $values[] = $this->normalizeChoiceAnswerValue($rawAnswer, $optionLabelById, $optionLabels, $legacyMap);
        }

        return array_values(array_unique(array_filter($values, fn($val) => $val !== '' && $val !== null)));
    }

    private function flattenChoiceAnswerValues($value): array
    {
        if (!is_array($value)) {
            return [is_scalar($value) ? (string) $value : ''];
        }

        $flattened = [];
        foreach ($value as $item) {
            if (is_array($item)) {
                foreach ($this->flattenChoiceAnswerValues($item) as $nestedValue) {
                    $flattened[] = $nestedValue;
                }
            } elseif (is_scalar($item)) {
                $flattened[] = (string) $item;
            }
        }

        return $flattened;
    }

    private function normalizeChoiceAnswerValue($rawValue, array $optionLabelById, array $optionLabels, array $legacyMap = []): ?string
    {
        if (is_null($rawValue)) {
            return null;
        }

        $value = trim((string) $rawValue);
        if ($value === '') {
            return null;
        }

        // 1. Direct ID match (Current template)
        if (isset($optionLabelById[$value])) {
            return $optionLabelById[$value];
        }

        // 2. Legacy ID heuristic match
        if (isset($legacyMap[$value])) {
            return $legacyMap[$value];
        }

        // 3. Exact text match
        if (isset($optionLabels[$value])) {
            return $value;
        }

        return $value;
    }

    private function alignCountsToLabels(array $labelOrder, array $counts): array
    {
        if (empty($labelOrder)) {
            return $counts;
        }

        $aligned = [];
        foreach ($labelOrder as $label) {
            $aligned[$label] = (int) ($counts[$label] ?? 0);
        }

        foreach ($counts as $label => $count) {
            if (!array_key_exists($label, $aligned)) {
                $aligned[$label] = (int) $count;
            }
        }

        return $aligned;
    }

    private function countsToPercentages(array $counts, int $total): array
    {
        $percentages = [];

        foreach ($counts as $label => $count) {
            $percentages[$label] = $total > 0 ? round(($count / $total) * 100, 2) : 0;
        }

        return $percentages;
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
}
