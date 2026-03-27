<?php

namespace App\Exports;

use App\Models\Lulusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class LulusanExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Lulusan::join('users', 'lulusan.user_id', '=', 'users.id')
        ->get(['lulusan.id', 'lulusan.nama', 'lulusan.nip', 'users.email', 'lulusan.prodi', 'lulusan.jabatan','lulusan.satuan_kerja','lulusan.unit_kerja', 'lulusan.no_hp', 'lulusan.nip_pengguna_lulusan', 'lulusan.tanggal_lahir','lulusan.tahun_lulus','lulusan.created_at', 'lulusan.updated_at']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'NIP',
            'Email',
            'Program Studi',
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'No HP',
            'NIP Pengguna Lulusan',
            'Tanggal Lahir',
            'Tahun Lulus',
            'Created At',
            'Updated At',
        ];
    }
}