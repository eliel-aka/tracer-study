<?php

namespace App\Exports;

use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class MasterSatkerExport implements FromCollection, WithHeadings
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $query = match ($this->type) {
            'jabatan' => MasterJabatan::query(),
            'satuan_kerja' => MasterSatuanKerja::query(),
            'unit_kerja' => MasterUnitKerja::query(),
            default => MasterJabatan::query(),
        };

        return $query->orderBy('nama')->get(['nama']);
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'Nama',
        ];
    }
}
