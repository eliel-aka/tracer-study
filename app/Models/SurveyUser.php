<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SurveyUser extends Model
{
    protected $table = 'survey_user';
    protected $fillable = ['survey_id', 'user_id', 'status', 'current_question_id', 'guest_token', 'tanggal_mengisi', 'invitation_email_sent_at', 'last_reminder_email_sent_at', 'reminder_email_count'];
    
    // Set default values
    protected $attributes = [
        'status' => 0,
        'tanggal_mengisi' => null,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jawaban()
    {
        return $this->hasMany(SurveyUserJawaban::class);
    }

    private static function normalizeSurveyType(?string $surveyType): string
    {
        $normalizedType = strtolower(trim((string) $surveyType));
        $normalizedType = str_replace(['-', ' '], '_', $normalizedType);

        if ($normalizedType === 'penggunalulusan') {
            return 'pengguna_lulusan';
        }

        return $normalizedType;
    }

    private static function buildAssignedRespondentQuery(int $idSurvey)
    {
        $survey = Survey::where('id', $idSurvey)->first();

        if (!$survey) {
            return null;
        }

        $surveyType = self::normalizeSurveyType($survey->type_survei);

        $query = self::query()
            ->join('survey', 'survey.id', '=', 'survey_user.survey_id')
            ->join('users', 'users.id', '=', 'survey_user.user_id')
            ->where('survey_user.survey_id', $idSurvey);

        if ($surveyType === 'pengguna_lulusan') {
            $query->join('pengguna_lulusan as a', 'users.id', '=', 'a.user_id');
        } else {
            $query->join('lulusan as a', 'users.id', '=', 'a.user_id');
        }

        return $query;
    }

    public static function getAssignmentStats(int $idSurvey): array
    {
        $query = self::buildAssignedRespondentQuery($idSurvey);

        if (!$query) {
            return [
                'total' => 0,
                'completed' => 0,
            ];
        }

        $total = (clone $query)
            ->distinct()
            ->count('survey_user.id');

        $completed = (clone $query)
            ->where('survey_user.status', true)
            ->distinct()
            ->count('survey_user.id');

        return [
            'total' => (int) $total,
            'completed' => (int) $completed,
        ];
    }

    static function getSurveyUser($id_survey)
    {
        $query = self::buildAssignedRespondentQuery((int) $id_survey);

        if ($query) {
            return $query
                ->select('a.*', 'survey_user.id as survey_user_id', 'survey_user.survey_id')
                ->paginate(10, ['*'], 'user_page');
        }

        return false;
    }

    static function getUser($id_survey)
    {
        $query = self::buildAssignedRespondentQuery((int) $id_survey);

        if ($query) {
            return $query
                ->select('a.*', 'survey_user.*', 'survey_user.id as survey_user_id')
                ->get();
        }

        return false;
    }
}
