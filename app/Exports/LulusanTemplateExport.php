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
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class LulusanTemplateExport implements WithHeadings, WithEvents, WithTitle, ShouldAutoSize
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
            'Program Studi',
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'Provinsi',
            'Kabupaten',
            'No HP',
            'Tanggal Lahir',
            'Tahun Lulus',
            'NIP Baru Pengguna Lulusan',
            'NIP Lama Pengguna Lulusan',
        ];
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Template Lulusan';
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $maxRow = 1000; // Apply validation for up to 1000 rows

                // Style header row
                $sheet->getStyle('A1:O1')->applyFromArray([
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

                // -- Program Studi dropdown (Column E) --
                $prodiOptions = '"DIV Komputasi Statistik,DIV Statistika,DIII Statistika"';
                for ($i = 2; $i <= $maxRow; $i++) {
                    $validation = $sheet->getCell("E{$i}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_LIST);
                    $validation->setErrorStyle(DataValidation::STYLE_STOP);
                    $validation->setAllowBlank(true);
                    $validation->setShowDropDown(true);
                    $validation->setShowErrorMessage(true);
                    $validation->setErrorTitle('Input Tidak Valid');
                    $validation->setError('Pilih salah satu Program Studi yang tersedia.');
                    $validation->setPromptTitle('Program Studi');
                    $validation->setPrompt('Pilih Program Studi dari daftar.');
                    $validation->setShowInputMessage(true);
                    $validation->setFormula1($prodiOptions);
                }

                // -- Jabatan dropdown (Column F) --
                $jabatanList = MasterJabatan::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($jabatanList)) {
                    $jabatanOptions = '"' . implode(',', $jabatanList) . '"';
                    if (strlen($jabatanOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("F{$i}")->getDataValidation();
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
                        $this->addHiddenSheetValidation($event, $jabatanList, 'Jabatan', 'F', $maxRow);
                    }
                }

                // -- Satuan Kerja dropdown (Column G) --
                $satkerList = MasterSatuanKerja::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($satkerList)) {
                    $satkerOptions = '"' . implode(',', $satkerList) . '"';
                    if (strlen($satkerOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("G{$i}")->getDataValidation();
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
                        $this->addHiddenSheetValidation($event, $satkerList, 'SatuanKerja', 'G', $maxRow);
                    }
                }

                // -- Unit Kerja dropdown (Column H) --
                $unitKerjaList = MasterUnitKerja::orderBy('nama')->pluck('nama')->toArray();
                if (!empty($unitKerjaList)) {
                    $unitKerjaOptions = '"' . implode(',', $unitKerjaList) . '"';
                    if (strlen($unitKerjaOptions) <= 255) {
                        for ($i = 2; $i <= $maxRow; $i++) {
                            $validation = $sheet->getCell("H{$i}")->getDataValidation();
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
                        $this->addHiddenSheetValidation($event, $unitKerjaList, 'UnitKerja', 'H', $maxRow);
                    }
                }

                // -- Tanggal Lahir format (Column L) --
                $sheet->getStyle("L2:L{$maxRow}")
                    ->getNumberFormat()
                    ->setFormatCode('YYYY-MM-DD');

                // Set column L prompt
                for ($i = 2; $i <= min(50, $maxRow); $i++) {
                    $validation = $sheet->getCell("L{$i}")->getDataValidation();
                    $validation->setType(DataValidation::TYPE_NONE);
                    $validation->setAllowBlank(true);
                    $validation->setShowInputMessage(true);
                    $validation->setPromptTitle('Format Tanggal');
                    $validation->setPrompt('Gunakan format: yyyy-mm-dd (contoh: 1999-05-15)');
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

        // Create or get the hidden reference sheet
        $refSheetName = '_ref';
        if ($spreadsheet->sheetNameExists($refSheetName)) {
            $refSheet = $spreadsheet->getSheetByName($refSheetName);
        } else {
            $refSheet = $spreadsheet->createSheet();
            $refSheet->setTitle($refSheetName);
            $refSheet->setSheetState(\PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::SHEETSTATE_HIDDEN);
        }

        // Find next available column in the reference sheet
        $refCol = $refSheet->getHighestColumn();
        $refCol = ($refCol === 'A' && $refSheet->getCell('A1')->getValue() === null) ? 'A' : ++$refCol;

        // Write items to reference sheet
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
