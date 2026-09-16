<?php

namespace App\Imports;

use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class MasterSatkerImport implements ToModel, WithHeadingRow
{
    protected $type;

    public function __construct(string $type)
    {
        $this->type = $type;
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        $nama = trim($row['nama'] ?? '');

        if (empty($nama)) {
            Log::warning("Skipping row due to missing nama", $row);
            return null;
        }

        try {
            switch ($this->type) {
                case 'jabatan':
                    return MasterJabatan::firstOrCreate(['nama' => $nama]);
                case 'satuan_kerja':
                    return MasterSatuanKerja::firstOrCreate(['nama' => $nama]);
                case 'unit_kerja':
                    return MasterUnitKerja::firstOrCreate(['nama' => $nama]);
                default:
                    return null;
            }
        } catch (\Exception $e) {
            Log::error("Error importing master satker: " . $e->getMessage());
            return null;
        }
    }
}
