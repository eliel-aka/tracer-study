<?php

namespace App\Exports;

use App\Models\Lulusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;

class LulusanExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Lulusan::join('users', 'lulusan.user_id', '=', 'users.id')
            ->get(['lulusan.nama', 'lulusan.nip_baru', 'lulusan.nip_lama', 'users.email', 'lulusan.prodi', 'lulusan.jabatan', 'lulusan.satuan_kerja', 'lulusan.unit_kerja', 'lulusan.no_hp', 'lulusan.tanggal_lahir', 'lulusan.tahun_lulus', 'lulusan.nip_baru_pengguna_lulusan', 'lulusan.nip_lama_pengguna_lulusan'])
            ->map(function ($item) {
                // Check completeness: prodi, jabatan, satuan_kerja, unit_kerja must all be filled
                $isComplete = !empty(trim((string) $item->prodi))
                    && !empty(trim((string) $item->jabatan))
                    && !empty(trim((string) $item->satuan_kerja))
                    && !empty(trim((string) $item->unit_kerja));

                return [
                    'nama' => $item->nama,
                    'nip_baru' => $item->nip_baru,
                    'nip_lama' => $item->nip_lama,
                    'email' => $item->email,
                    'prodi' => $item->prodi,
                    'jabatan' => $item->jabatan,
                    'satuan_kerja' => $item->satuan_kerja,
                    'unit_kerja' => $item->unit_kerja,
                    'no_hp' => $item->no_hp,
                    'tanggal_lahir' => $item->tanggal_lahir,
                    'tahun_lulus' => $item->tahun_lulus,
                    'nip_baru_pengguna_lulusan' => $item->nip_baru_pengguna_lulusan,
                    'nip_lama_pengguna_lulusan' => $item->nip_lama_pengguna_lulusan,
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
            'Program Studi',
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'No HP',
            'Tanggal Lahir',
            'Tahun Lulus',
            'NIP Baru Pengguna Lulusan',
            'NIP Lama Pengguna Lulusan',
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
                $sheet->getStyle('A1:N1')->applyFromArray([
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
                    $statusCell = $sheet->getCell("N{$row}")->getValue();
                    if ($statusCell === 'Data Tidak Lengkap') {
                        $sheet->getStyle("A{$row}:N{$row}")->applyFromArray([
                            'fill' => [
                                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                                'startColor' => ['rgb' => 'FEE2E2'], // Light red background
                            ],
                            'font' => [
                                'color' => ['rgb' => 'DC2626'], // Red text
                            ],
                        ]);
                    }
                }
            },
        ];
    }
}