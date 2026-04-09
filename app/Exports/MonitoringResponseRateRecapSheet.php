<?php

namespace App\Exports;

use App\Models\Survey;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class MonitoringResponseRateRecapSheet implements FromArray, WithTitle, WithEvents, ShouldAutoSize
{
    protected int $surveyId;
    protected Survey $survey;
    protected array $programs = [];
    protected array $years = [];
    protected array $matrix = [];
    protected array $yearTotals = [];
    protected array $programTotals = [];
    protected array $overallTotals = [
        'responded' => 0,
        'not_responded' => 0,
        'total' => 0,
    ];
    protected int $columnCount = 1;
    protected int $dataStartRow = 5;

    public function __construct(int $surveyId)
    {
        $this->surveyId = $surveyId;
        $this->prepareData();
    }

    public function title(): string
    {
        return 'Rekap Response Rate';
    }

    public function array(): array
    {
        return $this->matrix;
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $lastColumn = Coordinate::stringFromColumnIndex($this->columnCount);
                $blockSize = 3;
                $lastDataRow = $this->dataStartRow + (count($this->years) * $blockSize) - 1;
                $totalRowStart = $lastDataRow + 1;
                $totalRowEnd = $totalRowStart + ($blockSize - 1);

                $sheet->mergeCells('A1:' . $lastColumn . '1');
                $sheet->mergeCells('A2:' . $lastColumn . '2');
                $sheet->mergeCells('A3:A4');
                $sheet->mergeCells('B3:B4');
                $sheet->mergeCells($lastColumn . '3:' . $lastColumn . '4');

                if (count($this->programs) > 1) {
                    $programHeaderStart = Coordinate::stringFromColumnIndex(3);
                    $programHeaderEnd = Coordinate::stringFromColumnIndex(2 + count($this->programs));
                    $sheet->mergeCells($programHeaderStart . '3:' . $programHeaderEnd . '3');
                }

                foreach ($this->years as $index => $year) {
                    $rowStart = $this->dataStartRow + ($index * $blockSize);
                    $sheet->mergeCells('A' . $rowStart . ':A' . ($rowStart + $blockSize - 1));
                }

                $sheet->mergeCells('A' . $totalRowStart . ':A' . $totalRowEnd);

                $sheet->getStyle('A1:' . $lastColumn . $totalRowEnd)->applyFromArray([
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => Border::BORDER_THIN,
                            'color' => ['argb' => 'FFD9E2F3'],
                        ],
                    ],
                ]);

                $sheet->getStyle('A1:' . $lastColumn . '1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14,
                        'color' => ['argb' => 'FFFFFFFF'],
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FF1D4ED8'],
                    ],
                ]);

                $sheet->getStyle('A2:' . $lastColumn . '2')->applyFromArray([
                    'font' => [
                        'italic' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A3:' . $lastColumn . '4')->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                        'wrapText' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFE8EEF9'],
                    ],
                ]);

                $sheet->getStyle('A' . $this->dataStartRow . ':' . $lastColumn . $totalRowEnd)->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('B' . $this->dataStartRow . ':B' . $totalRowEnd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
                $sheet->getStyle('A' . $this->dataStartRow . ':A' . $totalRowEnd)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

                foreach ($this->years as $index => $year) {
                    $rowStart = $this->dataStartRow + ($index * $blockSize);
                    $sheet->getStyle('C' . $rowStart . ':' . $lastColumn . $rowStart)->getNumberFormat()->setFormatCode('0');
                    $sheet->getStyle('C' . ($rowStart + 1) . ':' . $lastColumn . ($rowStart + 1))->getNumberFormat()->setFormatCode('0');
                    $sheet->getStyle('C' . ($rowStart + 2) . ':' . $lastColumn . ($rowStart + 2))->getNumberFormat()->setFormatCode('0.00');
                }

                $sheet->getStyle('C' . $totalRowStart . ':' . $lastColumn . $totalRowStart)->getNumberFormat()->setFormatCode('0');
                $sheet->getStyle('C' . ($totalRowStart + 1) . ':' . $lastColumn . ($totalRowStart + 1))->getNumberFormat()->setFormatCode('0');
                $sheet->getStyle('C' . $totalRowEnd . ':' . $lastColumn . $totalRowEnd)->getNumberFormat()->setFormatCode('0.00');

                $sheet->getStyle('A' . $totalRowStart . ':' . $lastColumn . $totalRowEnd)->applyFromArray([
                    'font' => [
                        'bold' => true,
                    ],
                    'fill' => [
                        'fillType' => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFF3F4F6'],
                    ],
                ]);

                $sheet->freezePane('C5');
                $sheet->setAutoFilter('A4:' . $lastColumn . $totalRowEnd);
                $sheet->getPageSetup()->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE);
                $sheet->getPageSetup()->setFitToWidth(1);
                $sheet->getPageSetup()->setFitToHeight(0);
                $sheet->getRowDimension(1)->setRowHeight(24);
                $sheet->getRowDimension(2)->setRowHeight(20);
                $sheet->getRowDimension(3)->setRowHeight(22);
                $sheet->getRowDimension(4)->setRowHeight(34);
            },
        ];
    }

    protected function prepareData(): void
    {
        $this->survey = Survey::findOrFail($this->surveyId);
        $surveyType = $this->normalizeSurveyType($this->survey->type_survei);

        $query = DB::table('survey_user')
            ->join('users', 'users.id', '=', 'survey_user.user_id');

        if ($surveyType === 'pengguna_lulusan') {
            $query->join('pengguna_lulusan', 'pengguna_lulusan.user_id', '=', 'users.id')
                ->join('lulusan', 'lulusan.nip_pengguna_lulusan', '=', 'pengguna_lulusan.nip');
        } else {
            $query->join('lulusan', 'lulusan.user_id', '=', 'users.id');
        }

        $rows = $query
            ->where('survey_user.survey_id', $this->surveyId)
            ->whereNotNull('lulusan.tahun_lulus')
            ->where('lulusan.tahun_lulus', '!=', '')
            ->whereNotNull('lulusan.prodi')
            ->where('lulusan.prodi', '!=', '')
            ->selectRaw('lulusan.tahun_lulus as tahun_lulus')
            ->selectRaw('lulusan.prodi as prodi')
            ->selectRaw('COUNT(DISTINCT survey_user.id) as total_target')
            ->selectRaw('COUNT(DISTINCT CASE WHEN survey_user.status = 1 THEN survey_user.id END) as total_responded')
            ->groupBy('lulusan.tahun_lulus', 'lulusan.prodi')
            ->orderBy('lulusan.tahun_lulus')
            ->orderBy('lulusan.prodi')
            ->get();

        $this->programs = $rows
            ->pluck('prodi')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $this->years = $rows
            ->pluck('tahun_lulus')
            ->unique()
            ->sort()
            ->values()
            ->all();

        $matrixByYear = [];
        $totalsByProgram = [];
        $yearTotals = [];
        $overallTotals = [
            'responded' => 0,
            'not_responded' => 0,
            'total' => 0,
        ];

        foreach ($rows as $row) {
            $year = (string) $row->tahun_lulus;
            $program = (string) $row->prodi;
            $target = (int) $row->total_target;
            $responded = (int) $row->total_responded;
            $notResponded = max($target - $responded, 0);
            $responseRate = $target > 0 ? round(($responded / $target) * 100, 2) : 0;

            $matrixByYear[$year][$program] = [
                'responded' => $responded,
                'not_responded' => $notResponded,
                'response_rate' => $responseRate,
                'total' => $target,
            ];

            if (!isset($yearTotals[$year])) {
                $yearTotals[$year] = [
                    'responded' => 0,
                    'not_responded' => 0,
                    'total' => 0,
                ];
            }

            $yearTotals[$year]['responded'] += $responded;
            $yearTotals[$year]['not_responded'] += $notResponded;
            $yearTotals[$year]['total'] += $target;

            if (!isset($totalsByProgram[$program])) {
                $totalsByProgram[$program] = [
                    'responded' => 0,
                    'not_responded' => 0,
                    'total' => 0,
                ];
            }

            $totalsByProgram[$program]['responded'] += $responded;
            $totalsByProgram[$program]['not_responded'] += $notResponded;
            $totalsByProgram[$program]['total'] += $target;

            $overallTotals['responded'] += $responded;
            $overallTotals['not_responded'] += $notResponded;
            $overallTotals['total'] += $target;
        }

        $this->yearTotals = $yearTotals;
        $this->programTotals = $totalsByProgram;
        $this->overallTotals = $overallTotals;
        $this->columnCount = 3 + count($this->programs);
        $this->matrix = $this->buildMatrix($matrixByYear, $yearTotals, $totalsByProgram, $overallTotals);
    }

    protected function buildMatrix(array $matrixByYear, array $yearTotals, array $totalsByProgram, array $overallTotals): array
    {
        $rows = [];
        $rows[] = [$this->survey->nama . ' - Rekap Response Rate'];
        $rows[] = ['Per Tahun dan Program Studi'];

        $headerRow = ['Tahun', 'Rincian'];
        $subHeaderRow = ['', ''];

        if (count($this->programs) > 0) {
            $headerRow[] = 'Program Studi';

            for ($index = 1; $index < count($this->programs); $index++) {
                $headerRow[] = '';
            }

            foreach ($this->programs as $program) {
                $subHeaderRow[] = $program;
            }

            $subHeaderRow[] = '';
        }

        $headerRow[] = 'Jumlah';
        $subHeaderRow[] = '';

        $rows[] = $headerRow;
        $rows[] = $subHeaderRow;

        foreach ($this->years as $year) {
            $yearTotal = $yearTotals[$year] ?? [
                'responded' => 0,
                'not_responded' => 0,
                'total' => 0,
            ];

            $row = [$year, 'Mengisi'];

            foreach ($this->programs as $program) {
                $cell = $matrixByYear[$year][$program] ?? [
                    'responded' => 0,
                    'not_responded' => 0,
                    'response_rate' => 0,
                    'total' => 0,
                ];

                $row[] = $cell['responded'];
            }

            $row[] = $yearTotal['responded'];

            $rows[] = $row;

            $row = ['', 'Tidak Mengisi'];

            foreach ($this->programs as $program) {
                $cell = $matrixByYear[$year][$program] ?? [
                    'responded' => 0,
                    'not_responded' => 0,
                    'response_rate' => 0,
                    'total' => 0,
                ];

                $row[] = $cell['not_responded'];
            }

            $row[] = $yearTotal['not_responded'];

            $rows[] = $row;

            $row = ['', 'Respon Rate'];

            foreach ($this->programs as $program) {
                $cell = $matrixByYear[$year][$program] ?? [
                    'responded' => 0,
                    'not_responded' => 0,
                    'response_rate' => 0,
                    'total' => 0,
                ];

                $row[] = $cell['response_rate'];
            }

            $row[] = $yearTotal['total'] > 0
                ? round(($yearTotal['responded'] / $yearTotal['total']) * 100, 2)
                : 0;

            $rows[] = $row;
        }

        $totalRow = ['Total', 'Mengisi'];
        foreach ($this->programs as $program) {
            $total = $totalsByProgram[$program]['total'] ?? 0;
            $responded = $totalsByProgram[$program]['responded'] ?? 0;

            $totalRow[] = $responded;
        }

        $totalRow[] = $overallTotals['responded'];

        $rows[] = $totalRow;

        $totalRow = ['', 'Tidak Mengisi'];
        foreach ($this->programs as $program) {
            $totalRow[] = $totalsByProgram[$program]['not_responded'] ?? 0;
        }

        $totalRow[] = $overallTotals['not_responded'];

        $rows[] = $totalRow;

        $totalRow = ['', 'Respon Rate'];
        foreach ($this->programs as $program) {
            $total = $totalsByProgram[$program]['total'] ?? 0;
            $responded = $totalsByProgram[$program]['responded'] ?? 0;

            $totalRow[] = $total > 0 ? round(($responded / $total) * 100, 2) : 0;
        }

        $totalRow[] = $overallTotals['total'] > 0
            ? round(($overallTotals['responded'] / $overallTotals['total']) * 100, 2)
            : 0;

        $rows[] = $totalRow;

        return $rows;
    }

    protected function normalizeSurveyType(?string $surveyType): string
    {
        $normalizedType = strtolower(trim((string) $surveyType));
        $normalizedType = str_replace(['-', ' '], '_', $normalizedType);

        if ($normalizedType === 'penggunalulusan') {
            return 'pengguna_lulusan';
        }

        return $normalizedType;
    }
}
