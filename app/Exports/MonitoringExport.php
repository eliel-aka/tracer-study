<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MonitoringExport implements WithMultipleSheets
{
    protected $surveyId;

    public function __construct($surveyId)
    {
        $this->surveyId = $surveyId;
    }

    public function sheets(): array
    {
        return [
            new MonitoringSurveyDetailsSheet($this->surveyId),
            new MonitoringResponseRateRecapSheet($this->surveyId),
        ];
    }
}