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
        return PenggunaLulusan::join('users', 'penggunaLulusan.user_id', '=', 'users.id')
        ->get(['penggunaLulusan.id', 'penggunaLulusan.nama','penggunaLulusan.nip','users.email', 'penggunaLulusan.jabatan', 'penggunaLulusan.satuan_kerja','penggunaLulusan.unit_kerja', 'penggunaLulusan.no_hp', 'penggunaLulusan.created_at', 'penggunaLulusan.updated_at']);
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
