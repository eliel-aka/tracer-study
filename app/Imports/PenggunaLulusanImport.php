<?php

namespace App\Imports;

use App\Models\PenggunaLulusan;
use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use App\Models\SurveyUser;
use App\Models\User;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Exception;

class PenggunaLulusanImport implements ToModel, WithHeadingRow
{
    protected $survey_id;
    protected $validJabatan;
    protected $validSatker;
    protected $validUnitKerja;

    public function __construct($survey_id = null)
    {
        $this->survey_id = $survey_id;
        $this->validJabatan = MasterJabatan::pluck('nama')->map(fn($v) => strtolower(trim($v)))->toArray();
        $this->validSatker = MasterSatuanKerja::pluck('nama')->map(fn($v) => strtolower(trim($v)))->toArray();
        $this->validUnitKerja = MasterUnitKerja::pluck('nama')->map(fn($v) => strtolower(trim($v)))->toArray();
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try{
            $nipBaru = !empty($row['nip_baru']) ? trim($row['nip_baru']) : (isset($row['nip']) && strlen(trim($row['nip'])) === 18 ? trim($row['nip']) : null);
            $nipLama = !empty($row['nip_lama']) ? trim($row['nip_lama']) : (isset($row['nip']) && strlen(trim($row['nip'])) === 9 ? trim($row['nip']) : null);
            $nipVal = $nipBaru ?? $nipLama ?? ($row['nip'] ?? null);

            // Validate required fields
            if (empty($row['email']) || empty($row['nama']) || empty($nipVal)) {
                Log::warning("Skipping row due to missing required fields", $row);
                return null;
            }

            // Validate email format
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                throw new Exception("Invalid email format for: {$row['email']}");
            }

            // Create the user
            $defaultPassword = User::generateDefaultPassword($row['nama'], $nipBaru, $nipLama);
            $user = User::firstOrCreate(
                ['email' => $row['email']], // Check for duplicate email
                [
                    'name' => $row['nama'],
                    'password' => bcrypt($defaultPassword),
                    'role' => 'pengguna_lulusan',
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole('penggunaLulusan')) {
                $user->assignRole('penggunaLulusan');
            }

            // Create the penggunaLulusan record
            // Validate against master data
            $rawJabatan = $row['jabatan'] ?? null;
            $jabatan = null;
            if (!empty($rawJabatan) && in_array(strtolower(trim($rawJabatan)), $this->validJabatan)) {
                $jabatan = trim($rawJabatan);
            }

            $rawSatker = $row['satuan_kerja'] ?? null;
            $satuanKerja = null;
            if (!empty($rawSatker) && in_array(strtolower(trim($rawSatker)), $this->validSatker)) {
                $satuanKerja = trim($rawSatker);
            }

            $rawUnitKerja = $row['unit_kerja'] ?? null;
            $unitKerja = null;
            if (!empty($rawUnitKerja) && in_array(strtolower(trim($rawUnitKerja)), $this->validUnitKerja)) {
                $unitKerja = trim($rawUnitKerja);
            }

            $penggunaLulusan = PenggunaLulusan::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nama' => $row['nama'],
                    'nip' => $nipVal,
                    'nip_baru' => $nipBaru,
                    'nip_lama' => $nipLama,
                    'email' => $row['email'],
                    'jabatan' => $jabatan,
                    'satuan_kerja' => $satuanKerja,
                    'unit_kerja' => $unitKerja,
                    'no_hp' => $row['no_hp'] ?? '',
                    'provinsi' => $row['provinsi'] ?? null,
                    'kabupaten' => $row['kabupaten'] ?? null,
                ]
            );

        // Create survey_user entry if survey_id is set
        if ($this->survey_id) {
            SurveyUser::firstOrCreate(
                [
                    'survey_id' => $this->survey_id,
                    'user_id' => $user->id,
                ]
            );
        }
        return $penggunaLulusan;
    } catch (QueryException $e) {
        Log::error("Database error during import: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        } catch (Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
  }
    }
}