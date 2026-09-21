<?php

namespace App\Exports;

use App\Models\PenggunaLulusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class PenggunaLulusanExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PenggunaLulusan::join('users', 'pengguna_lulusan.user_id', '=', 'users.id')
            ->get(['pengguna_lulusan.nama', 'pengguna_lulusan.nip_baru', 'pengguna_lulusan.nip_lama', 'users.email', 'pengguna_lulusan.jabatan', 'pengguna_lulusan.satuan_kerja', 'pengguna_lulusan.unit_kerja', 'pengguna_lulusan.provinsi', 'pengguna_lulusan.kabupaten', 'pengguna_lulusan.no_hp'])
            ->map(function ($item) {
                $isComplete = !empty(trim((string) $item->jabatan))
                    && !empty(trim((string) $item->satuan_kerja))
                    && !empty(trim((string) $item->unit_kerja));

                return [
                    'nama' => $item->nama,
                    'nip_baru' => $item->nip_baru,
                    'nip_lama' => $item->nip_lama,
                    'email' => $item->email,
                    'jabatan' => $item->jabatan,
                    'satuan_kerja' => $item->satuan_kerja,
                    'unit_kerja' => $item->unit_kerja,
                    'provinsi' => $item->provinsi,
                    'kabupaten' => $item->kabupaten,
                    'no_hp' => $item->no_hp,
                    'status_data' => $isComplete ? 'Data Lengkap' : 'Data Tidak Lengkap',
                ];
            });
    }

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
            'Provinsi',
            'Kabupaten',
            'No HP',
            'Status Data',
        ];
    }

    /**
     * @return array
     */
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $highestRow = $sheet->getHighestRow();

                // Style header row
                $sheet->getStyle('A1:K1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'color' => ['rgb' => 'FFFFFF'],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => '3B82F6'],
                    ],
                ]);

                // Highlight rows with "Data Tidak Lengkap" in red
                for ($row = 2; $row <= $highestRow; $row++) {
                    $statusCell = $sheet->getCell("K{$row}")->getValue();
                    if ($statusCell === 'Data Tidak Lengkap') {
                        $sheet->getStyle("A{$row}:K{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEE2E2'],
                            ],
                            'font' => [
                                'color' => ['rgb' => 'DC2626'],
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}
