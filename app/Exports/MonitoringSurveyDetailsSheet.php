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
        $this->questions = TemplatePertanyaan::where('id_survey', $this->surveyId)->orderBy('urutan')->get();
        $this->users = SurveyUser::getUser($this->surveyId);

        if (!$this->users || $this->users->isEmpty()) {
            return;
        }

        $answers = SurveyUserJawaban::whereIn('survey_user_id', $this->users->pluck('survey_user_id'))->with(['surveyUser', 'template_pertanyaan'])->get();

        foreach ($answers as $answer) {
            $this->answers[$answer->survey_user_id][$answer->template_pertanyaan_id] = $answer->jawaban;
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
