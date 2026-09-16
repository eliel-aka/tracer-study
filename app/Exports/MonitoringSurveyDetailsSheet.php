<?php

namespace App\Exports;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\SurveyUserJawaban;
use App\Models\TemplatePertanyaan;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class MonitoringSurveyDetailsSheet implements FromCollection, WithHeadings, WithTitle, ShouldAutoSize
{
    protected int $surveyId;
    protected EloquentCollection $questions;
    protected EloquentCollection $users;
    protected array $answers = [];

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
        $this->prepareData();
    }

    protected function prepareData(): void
    {
        Survey::findOrFail($this->surveyId);
        $this->questions = TemplatePertanyaan::with('templateJawaban')
            ->where('id_survey', $this->surveyId)
            ->orderBy('urutan')
            ->get();
        $this->users = SurveyUser::getUser($this->surveyId);

        if (!$this->users || $this->users->isEmpty()) {
            return;
        }

        $answers = SurveyUserJawaban::whereIn('survey_user_id', $this->users->pluck('survey_user_id'))
            ->with(['surveyUser', 'template_pertanyaan'])
            ->get();

        $optionMap = [];
        $questionTypeMap = [];
        $gridColumnsMap = [];

        foreach ($this->questions as $question) {
            $questionTypeMap[$question->id] = $question->tipe;
            if (is_array($question->grid_columns)) {
                $gridColumnsMap[$question->id] = $question->grid_columns;
            }
            foreach ($question->templateJawaban as $option) {
                $optionMap[$option->id] = $option->pilihan_jawaban;
            }
        }

        foreach ($answers as $answer) {
            $qid = $answer->template_pertanyaan_id;
            $type = $questionTypeMap[$qid] ?? null;
            $rawJawaban = $answer->jawaban;

            if ($type === 'checkbox') {
                $ids = explode(',', $rawJawaban);
                $labels = [];
                foreach ($ids as $id) {
                    $id = trim($id);
                    $labels[] = $optionMap[$id] ?? $id;
                }
                $formattedJawaban = implode(', ', $labels);
            } elseif ($type === 'radio' || $type === 'select') {
                $formattedJawaban = $optionMap[$rawJawaban] ?? $rawJawaban;
            } elseif ($type === 'multiple_choice_grid') {
                $json = json_decode($rawJawaban, true);
                if (is_array($json)) {
                    $parts = [];
                    foreach ($json as $rowId => $scaleVal) {
                        $rowLabel = $optionMap[$rowId] ?? $rowId;
                        $scaleLabel = $scaleVal;
                        
                        $gCols = $gridColumnsMap[$qid] ?? null;
                        if (is_array($gCols)) {
                            $idx = (int)$scaleVal - 1;
                            if (isset($gCols[$idx])) {
                                $scaleLabel = $gCols[$idx];
                            }
                        }
                        $parts[] = $rowLabel . ': ' . $scaleLabel;
                    }
                    $formattedJawaban = implode('; ', $parts);
                } else {
                    $formattedJawaban = $rawJawaban;
                }
            } else {
                $formattedJawaban = $rawJawaban;
            }

            $this->answers[$answer->survey_user_id][$answer->template_pertanyaan_id] = $formattedJawaban;
        }
    }

    public function title(): string
    {
        return 'Detail Survei';
    }

    public function headings(): array
    {
        $headings = [
            'ID',
            'Nama',
            'Email',
            'Status',
            'Tanggal Mengisi',
        ];

        foreach ($this->questions as $question) {
            $prefix = $question->blok ? '[' . $question->blok . '] ' : '';
            $headings[] = $prefix . $question->pertanyaan;
        }

        return $headings;
    }

    public function collection()
    {
        $data = new EloquentCollection();

        if (!$this->users) {
            return $data;
        }

        foreach ($this->users as $user) {
            $row = [
                $user->survey_user_id,
                $user->nama,
                $user->email,
                $user->status ? 'Sudah Mengisi' : 'Belum Mengisi',
                $user->tanggal_mengisi,
            ];

            foreach ($this->questions as $question) {
                $row[] = $this->answers[$user->survey_user_id][$question->id] ?? '';
            }

            $data->push($row);
        }

        return $data;
    }
}
