<?php

namespace App\Exports;

use App\Models\PenggunaLulusan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PenggunaLulusanExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return PenggunaLulusan::join('users', 'pengguna_lulusan.user_id', '=', 'users.id')
        ->get(['pengguna_lulusan.id', 'pengguna_lulusan.nama','pengguna_lulusan.nip','users.email', 'pengguna_lulusan.jabatan', 'pengguna_lulusan.satuan_kerja','pengguna_lulusan.unit_kerja', 'pengguna_lulusan.no_hp', 'pengguna_lulusan.created_at', 'pengguna_lulusan.updated_at']);
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
            'Jabatan',
            'Satuan Kerja',
            'Unit Kerja',
            'No HP',
            'Created At',
            'Updated At',
        ];
    }

}
