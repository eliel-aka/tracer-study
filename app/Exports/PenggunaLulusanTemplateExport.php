<?php

namespace App\Exports;

use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;

class PenggunaLulusanTemplateExport implements WithHeadings, WithEvents, WithTitle, ShouldAutoSize
{
    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama',
            'NIP Baru',
            'NIP Lama',
            'Email',
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'No HP',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Template Pengguna Lulusan';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $maxRow = 1000;

                // Style header row
                $sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3B82F6'],
                    ],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    ],
                ]);

                // -- Jabatan dropdown (Column E) --
                $jabatanList = MasterJabatan::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($jabatanList)) {
                    $jabatanOptions = '"' . implode(',', $jabatanList) . '"';
                    if (strlen($jabatanOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("E{$i}")->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST);
                            $validation->setErrorStyle(DataValidation::STYLE_WARNING);
                            $validation->setAllowBlank(true);
                            $validation->setShowDropDown(true);
                            $validation->setShowErrorMessage(true);
                            $validation->setErrorTitle('Peringatan');
                            $validation->setError('Jabatan tidak ada di master data. Pastikan sudah menambahkan di Manajemen Satker.');
                            $validation->setPromptTitle('Jabatan');
                            $validation->setPrompt('Pilih Jabatan dari daftar.');
                            $validation->setShowInputMessage(true);
                            $validation->setFormula1($jabatanOptions);
                        }
                    } else {
                        $this->addHiddenSheetValidation($event, $jabatanList, 'Jabatan', 'E', $maxRow);
                    }
                }

                // -- Satuan Kerja dropdown (Column F) --
                $satkerList = MasterSatuanKerja::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($satkerList)) {
                    $satkerOptions = '"' . implode(',', $satkerList) . '"';
                    if (strlen($satkerOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("F{$i}")->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST);
                            $validation->setErrorStyle(DataValidation::STYLE_WARNING);
                            $validation->setAllowBlank(true);
                            $validation->setShowDropDown(true);
                            $validation->setShowErrorMessage(true);
                            $validation->setErrorTitle('Peringatan');
                            $validation->setError('Satuan Kerja tidak ada di master data.');
                            $validation->setPromptTitle('Satuan Kerja');
                            $validation->setPrompt('Pilih Satuan Kerja dari daftar.');
                            $validation->setShowInputMessage(true);
                            $validation->setFormula1($satkerOptions);
                        }
                    } else {
                        $this->addHiddenSheetValidation($event, $satkerList, 'SatuanKerja', 'F', $maxRow);
                    }
                }

                // -- Unit Kerja dropdown (Column G) --
                $unitKerjaList = MasterUnitKerja::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($unitKerjaList)) {
                    $unitKerjaOptions = '"' . implode(',', $unitKerjaList) . '"';
                    if (strlen($unitKerjaOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("G{$i}")->getDataValidation();
                            $validation->setType(DataValidation::TYPE_LIST);
                            $validation->setErrorStyle(DataValidation::STYLE_WARNING);
                            $validation->setAllowBlank(true);
                            $validation->setShowDropDown(true);
                            $validation->setShowErrorMessage(true);
                            $validation->setErrorTitle('Peringatan');
                            $validation->setError('Unit Kerja tidak ada di master data.');
                            $validation->setPromptTitle('Unit Kerja');
                            $validation->setPrompt('Pilih Unit Kerja dari daftar.');
                            $validation->setShowInputMessage(true);
                            $validation->setFormula1($unitKerjaOptions);
                        }
                    } else {
                        $this->addHiddenSheetValidation($event, $unitKerjaList, 'UnitKerja', 'G', $maxRow);
                    }
                }
            },
        ];
    }

    /**
     * Add validation using a hidden reference sheet for lists > 255 chars
     */
    private function addHiddenSheetValidation(AfterSheet $event, array $items, string $sheetName, string $column, int $maxRow): void
    {
        $spreadsheet = $event->sheet->getDelegate()->getParent();

        $refSheetName = '_ref';
        if ($spreadsheet->sheetNameExists($refSheetName)) {
            $refSheet = $spreadsheet->getSheetByName($refSheetName);
        } else {
            $refSheet = $spreadsheet->createSheet();
            $refSheet->setTitle($refSheetName);
            $refSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        }

        $refCol = $refSheet->getHighestColumn();
        $refCol = ($refCol === 'A' && $refSheet->getCell('A1')->getValue() === null) ? 'A' : ++$refCol;

        $refSheet->setCellValue("{$refCol}1", $sheetName);
        foreach ($items as $idx => $item) {
            $refSheet->setCellValue("{$refCol}" . ($idx + 2), $item);
        }

        $lastRow = count($items) + 1;
        $mainSheet = $event->sheet->getDelegate();

        for ($i = 2; $i <= $maxRow; $i++) {
            $validation = $mainSheet->getCell("{$column}{$i}")->getDataValidation();
            $validation->setType(DataValidation::TYPE_LIST);
            $validation->setErrorStyle(DataValidation::STYLE_WARNING);
            $validation->setAllowBlank(true);
            $validation->setShowDropDown(true);
            $validation->setShowErrorMessage(true);
            $validation->setFormula1("'{$refSheetName}'!\${$refCol}\$2:\${$refCol}\${$lastRow}");
        }
    }
}
