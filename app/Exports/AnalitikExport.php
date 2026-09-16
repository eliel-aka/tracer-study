<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class AnalitikExport implements FromCollection, WithHeadings, WithTitle, WithStyles, ShouldAutoSize
{
    protected $payload;
    protected $surveyName;
    protected $segmentDimension;

    public function __construct($payload, $surveyName, $segmentDimension)
    {
        $this->payload = $payload;
        $this->surveyName = $surveyName;
        $this->segmentDimension = $segmentDimension;
    }

    public function collection()
    {
        $data = new Collection();
        $charts = $this->payload['charts'] ?? [];

        foreach ($charts as $qId => $chart) {
            // Add Question Header
            $data->push(['Pertanyaan:', $chart['questionText']]);
            $data->push(['Blok:', $chart['blok']]);
            $data->push(['']); // Empty row

            if (($chart['type'] ?? '') === 'multiple_choice_grid') {
                $columns = $chart['columns'] ?? [];
                $headers = array_merge(['Aspek'], $columns);

                // Aggregate Table (STIS)
                $data->push(['Agregat: Politeknik Statistika STIS']);
                $data->push($headers);
                
                $rows = $chart['aggregate'] ?? [];
                foreach ($rows as $row) {
                    $rowData = [$row['label']];
                    foreach ($row['percentages'] as $p) {
                        $rowData[] = number_format($p, 2) . '%';
                    }
                    $data->push($rowData);
                }
                $data->push(['']); // Empty row

                // Segmented Tables
                $segments = $chart['segments'] ?? [];
                foreach ($segments as $seg) {
                    $data->push(['Breakdown (' . ($this->segmentDimension == 'tahun_lulus' ? 'Tahun Lulus' : 'Prodi') . '): ' . $seg['segmentLabel']]);
                    $data->push($headers);
                    
                    $segRows = $seg['rows'] ?? [];
                    foreach ($segRows as $row) {
                        $rowData = [$row['label']];
                        foreach ($row['percentages'] as $p) {
                            $rowData[] = number_format($p, 2) . '%';
                        }
                        $data->push($rowData);
                    }
                    $data->push(['']); // Empty row
                }
            } elseif (($chart['type'] ?? '') === 'kompetensi_block') {
                $columns = $chart['columns'] ?? [];
                $maxSkala = $chart['maxSkala'] ?? count($columns);
                $headers = array_merge(['No.', 'Indikator'], $columns, ['Skala ' . $maxSkala, 'Skala 100']);

                // Aggregate Table (STIS)
                $data->push(['Agregat: Politeknik Statistika STIS']);
                $data->push($headers);
                
                $rows = $chart['aggregate'] ?? [];
                $index = 1;
                foreach ($rows as $row) {
                    if (isset($row['is_total']) && $row['is_total']) {
                        $rowData = ['', 'Total'];
                        foreach ($row['percentages'] as $p) {
                            $rowData[] = number_format($p, 2); // Excel usually prefers numbers, but percentage format matches UI better if string, I'll use string here like the rest
                        }
                        $rowData[] = number_format($row['skalaN'] ?? 0, 2);
                        $rowData[] = number_format($row['skala100'] ?? 0, 2);
                        $data->push($rowData);
                    } else {
                        $rowData = [$index++, $row['label']];
                        foreach ($row['percentages'] as $p) {
                            $rowData[] = number_format($p, 2);
                        }
                        $rowData[] = number_format($row['skalaN'] ?? 0, 2);
                        $rowData[] = number_format($row['skala100'] ?? 0, 2);
                        $data->push($rowData);
                    }
                }
                $data->push(['']); // Empty row

                // Segmented Tables
                $segments = $chart['segments'] ?? [];
                foreach ($segments as $seg) {
                    $data->push(['Breakdown (' . ($this->segmentDimension == 'tahun_lulus' ? 'Tahun Lulus' : 'Prodi') . '): ' . $seg['segmentLabel']]);
                    $data->push($headers);
                    
                    $segRows = $seg['rows'] ?? [];
                    $index = 1;
                    foreach ($segRows as $row) {
                        if (isset($row['is_total']) && $row['is_total']) {
                            $rowData = ['', 'Total'];
                            foreach ($row['percentages'] as $p) {
                                $rowData[] = number_format($p, 2);
                            }
                            $rowData[] = number_format($row['skalaN'] ?? 0, 2);
                            $rowData[] = number_format($row['skala100'] ?? 0, 2);
                            $data->push($rowData);
                        } else {
                            $rowData = [$index++, $row['label']];
                            foreach ($row['percentages'] as $p) {
                                $rowData[] = number_format($p, 2);
                            }
                            $rowData[] = number_format($row['skalaN'] ?? 0, 2);
                            $rowData[] = number_format($row['skala100'] ?? 0, 2);
                            $data->push($rowData);
                        }
                    }
                    $data->push(['']); // Empty row
                }
            } elseif (($chart['type'] ?? '') === 'static_crosstab') {
                $agg = $chart['aggregate'] ?? [];
                
                // Title
                $data->push(['Agregat: ' . ($agg['title'] ?? 'Tabel Statis')]);
                
                // Columns
                $columns = $agg['columns'] ?? [];
                $data->push($columns);

                // Rows
                foreach ($agg['rows'] ?? [] as $row) {
                    $rowData = [$row['label']];
                    foreach ($row['data'] ?? [] as $val) {
                        $rowData[] = $val;
                    }
                    $data->push($rowData);
                }

                // Footer
                if (isset($agg['footer'])) {
                    $footerData = [$agg['footer']['label']];
                    foreach ($agg['footer']['data'] ?? [] as $val) {
                        $footerData[] = $val;
                    }
                    $data->push($footerData);
                }

                $data->push(['']); // Empty row
            } else {
                // Aggregate Table (STIS)
                $data->push(['Agregat: Politeknik Statistika STIS']);
                $data->push(['Opsi Jawaban', 'Jumlah Responden', 'Persentase (%)']);
                
                $agg = $chart['aggregate'] ?? [];
                foreach ($agg['labels'] ?? [] as $i => $label) {
                    $data->push([
                        $label,
                        $agg['counts'][$i],
                        number_format($agg['percentages'][$i], 2) . '%'
                    ]);
                }
                $data->push(['TOTAL', $agg['total'], '100%']);
                $data->push(['']); // Empty row

                // Segmented Tables
                $segments = $chart['segments'] ?? [];
                foreach ($segments as $seg) {
                    $data->push(['Breakdown (' . ($this->segmentDimension == 'tahun_lulus' ? 'Tahun Lulus' : 'Prodi') . '): ' . $seg['segmentLabel']]);
                    $data->push(['Opsi Jawaban', 'Jumlah Responden', 'Persentase (%)']);
                    
                    foreach ($seg['labels'] as $i => $label) {
                        $data->push([
                            $label,
                            $seg['counts'][$i],
                            number_format($seg['percentages'][$i], 2) . '%'
                        ]);
                    }
                    $data->push(['TOTAL', $seg['total'], '100%']);
                    $data->push(['']); // Empty row
                }
            }

            $data->push(['--------------------------------------------------------------------------------']);
            $data->push(['']); // Spacing between questions
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            ['Tabel Analitik Survei: ' . $this->surveyName],
            ['Dicetak pada: ' . date('d/m/Y H:i:s')],
            [''],
        ];
    }

    public function title(): string
    {
        return 'Tabel Analitik';
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true, 'size' => 14]],
            // We can add more styling here but let's keep it simple for now
        ];
    }
}
