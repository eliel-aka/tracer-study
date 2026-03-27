<?php

namespace App\Http\Controllers;
use App\Models\Survey;
use App\Models\Lulusan;
use App\Models\PenggunaLulusan;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Illuminate\Support\Facades\Log;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Statistik Survei
        $totalSurvey = Survey::count();
        
        // Statistik Users
        $totalLulusan = Lulusan::count();
        $totalPenggunaLulusan = PenggunaLulusan::count();
        $totalUsers = $totalLulusan + $totalPenggunaLulusan;

        $query = Survey::orderBy('created_at', 'desc');

        

        $surveyAktif = $query->paginate(10)->appends(request()->query());
        foreach ($surveyAktif as $survey) {
            $totalResponses = $survey->surveyUsers()->count();
            $completedResponses = $survey->surveyUsers()->where('status', true)->count();
            $completionRate = $totalResponses > 0 ? round(($completedResponses / $totalResponses) * 100, 1) : 0;
            $survey->completionRate = $completionRate;
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
    public function grafik($id)
    {
        $survey = Survey::findOrFail($id);
        $questions = TemplatePertanyaan::where('id_survey', $id)
            ->whereNotNull('visualisasi')
            ->get();

        $allSurveys = Survey::orderBy('nama')->get();

        return view('admin.views.dashboard_grafik', compact('survey', 'questions', 'allSurveys'));
    }

    /**
     * Get chart data for a specific question.
     */
    public function getChartData($surveyId, $questionId)
    {
        try {
            $survey = Survey::findOrFail($surveyId);

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

            $surveyUsers = SurveyUser::where('survey_id', $surveyId)
                ->where('status', true)
                ->get();

            $answers = SurveyUserJawaban::whereIn('survey_user_id', $surveyUsers->pluck('id'))
                ->where('template_pertanyaan_id', $questionId)
                ->get();

            $data = [];
            foreach ($answers as $answer) {
                if (!isset($data[$answer->jawaban])) {
                    $data[$answer->jawaban] = 1;
                } else {
                    $data[$answer->jawaban]++;
                }
            }

            if ($question->tipe === 'radio') {
                arsort($data);
            }

            $chartColors = [
                '#4B0082',
                '#0096FF',
                '#00FF7F',
                '#FFD700',
                '#FF69B4',
                '#8B4513',
                '#4682B4',
                '#D2691E',
                '#9370DB',
                '#3CB371',
            ];

            $chartData = [
                'labels' => array_keys($data),
                'datasets' => [
                    [
                        'label' => $question->pertanyaan,
                        'data' => array_values($data),
                        'backgroundColor' => array_slice($chartColors, 0, count($data)),
                    ],
                ],
            ];

            return response()->json([
                'type' => $question->visualisasi ?? 'bar',
                'data' => $chartData,
            ]);
        } catch (\Exception $e) {
            Log::error('Error generating dashboard chart data: ' . $e->getMessage());
            return response()->json([
                'error' => 'Error generating chart data',
                'details' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

}
