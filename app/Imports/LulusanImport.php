<?php

namespace App\Imports;

use App\Models\Lulusan;
use App\Models\MasterJabatan;
use App\Models\MasterSatuanKerja;
use App\Models\MasterUnitKerja;
use App\Models\SurveyUser;
use App\Models\User;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class LulusanImport implements ToModel, WithHeadingRow
{
    protected $survey_id;
    protected $validJabatan;
    protected $validSatker;
    protected $validUnitKerja;

    public function __construct($survey_id = null)
    {
        $this->survey_id = $survey_id;
        // Cache master data lists for validation
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
        // try {
        //     // Validate required fields
        //     if (empty($row['email']) || empty($row['nama']) || empty($row['nip'])) {
        //         Log::warning("Skipping row due to missing required fields", $row);
        //         return null;
        //     }

        //     // Validate email format
        //     if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
        //         throw new Exception("Invalid email format for: {$row['email']}");
        //     }

        //     // Create the user
        //     $user = User::firstOrCreate(
        //         ['email' => $row['email']], // Check for duplicate email
        //         [
        //             'name' => $row['nama'],
        //             'password' => bcrypt(substr($row['nip'], 0, 5)), // Use first 5 digits of NIP as password
        //             'role' => 'lulusan',
        //         ]
        //     );

        //     // Assign role if not already assigned
        //     if (!$user->hasRole('lulusan')) {
        //         $user->assignRole('lulusan');
        //     }
        try {
            // Add name cleaning function
            $cleanName = function($name) {
                // Split by first comma and take first part
                $nameParts = explode(',', $name, 2);
                return trim($nameParts[0]);
            };

            // Validate required fields
            if (empty($row['nama'])) {
                Log::warning("Skipping row due to missing required fields", $row);
                return null;
            }

            // Clean the name before saving
            $cleanedName = $cleanName($row['nama']);

            // Validate Program Studi against allowed values
            $rawProdi = $row['prodi'] ?? ($row['program_studi'] ?? null);
            $prodi = null;
            if (!empty($rawProdi)) {
                foreach (Lulusan::VALID_PRODI as $validOption) {
                    if (strtolower(trim($rawProdi)) === strtolower($validOption)) {
                        $prodi = $validOption;
                        break;
                    }
                }
            }

            // Validate Jabatan against master data
            $rawJabatan = $row['jabatan'] ?? null;
            $jabatan = null;
            if (!empty($rawJabatan) && in_array(strtolower(trim($rawJabatan)), $this->validJabatan)) {
                $jabatan = trim($rawJabatan);
            }

            // Validate Satuan Kerja against master data
            $rawSatker = $row['satuan_kerja'] ?? null;
            $satuanKerja = null;
            if (!empty($rawSatker) && in_array(strtolower(trim($rawSatker)), $this->validSatker)) {
                $satuanKerja = trim($rawSatker);
            }

            // Validate Unit Kerja against master data
            $rawUnitKerja = $row['unit_kerja'] ?? null;
            $unitKerja = null;
            if (!empty($rawUnitKerja) && in_array(strtolower(trim($rawUnitKerja)), $this->validUnitKerja)) {
                $unitKerja = trim($rawUnitKerja);
            }

            $nipBaru = !empty($row['nip_baru']) ? trim($row['nip_baru']) : (isset($row['nip']) && strlen(trim($row['nip'])) === 18 ? trim($row['nip']) : null);
            $nipLama = !empty($row['nip_lama']) ? trim($row['nip_lama']) : (isset($row['nip']) && strlen(trim($row['nip'])) === 9 ? trim($row['nip']) : null);
            $nipBaruPl = !empty($row['nip_baru_pengguna_lulusan']) ? trim($row['nip_baru_pengguna_lulusan']) : (!empty($row['nip_pengguna_lulusan_baru']) ? trim($row['nip_pengguna_lulusan_baru']) : (isset($row['nip_pengguna_lulusan']) && strlen(trim($row['nip_pengguna_lulusan'])) === 18 ? trim($row['nip_pengguna_lulusan']) : null));
            $nipLamaPl = !empty($row['nip_lama_pengguna_lulusan']) ? trim($row['nip_lama_pengguna_lulusan']) : (!empty($row['nip_pengguna_lulusan_lama']) ? trim($row['nip_pengguna_lulusan_lama']) : (isset($row['nip_pengguna_lulusan']) && strlen(trim($row['nip_pengguna_lulusan'])) === 9 ? trim($row['nip_pengguna_lulusan']) : null));

            //mengeluarkan 8 angka NIP untuk menjadi variabel tanggal_lahir
            if ($nipBaru && empty($row['tanggal_lahir'])) {
                try {
                    $row['tanggal_lahir'] = \Carbon\Carbon::createFromFormat('Ymd', substr($nipBaru, 0, 8))->format('Y-m-d');
                } catch (\Exception $e) {}
            } elseif (!empty($row['nip']) && empty($row['tanggal_lahir'])) {
                try {
                    $row['tanggal_lahir'] = \Carbon\Carbon::createFromFormat('Ymd', substr($row['nip'], 0, 8))->format('Y-m-d');
                } catch (\Exception $e) {}
            }

            // Process dates before creating/updating
            $tanggalLahir = null;
            if (!empty($row['tanggal_lahir'])) {
                // Check if it's an Excel serial date (numeric)
                if (is_numeric($row['tanggal_lahir'])) {
                    // Convert Excel serial date to Carbon date
                    $tanggalLahir = \Carbon\Carbon::createFromFormat('Y-m-d', '1900-01-01')
                        ->addDays($row['tanggal_lahir'] - 2)
                        ->format('Y-m-d');
                } else {
                    // Parse regular date format
                    $tanggalLahir = \Carbon\Carbon::parse($row['tanggal_lahir'])->format('Y-m-d');
                }
            }
            $tahunLulus = !empty($row['tahun_lulus']) ? $row['tahun_lulus'] : null;

            // Find existing lulusan by name and date of birth
            $existingLulusan = null;
            if ($tanggalLahir) {
                $existingLulusan = Lulusan::where('nama', 'LIKE', $cleanedName . '%')
                    ->Where('tanggal_lahir', $tanggalLahir)
                    ->first();
            }
            if ($existingLulusan) {

                // Update existing record with new data
                $existingLulusan->update([
                    'nip' => $nipBaru ?? $nipLama ?? ($row['nip'] ?? $existingLulusan->nip),
                    'nip_baru' => $nipBaru ?? $existingLulusan->nip_baru,
                    'nip_lama' => $nipLama ?? $existingLulusan->nip_lama,
                    'email' => $row['email'] ?? $existingLulusan->email,
                    'prodi' => $prodi ?? $existingLulusan->prodi,
                    'jabatan' => $jabatan ?? $existingLulusan->jabatan,
                    'satuan_kerja' => $satuanKerja ?? $existingLulusan->satuan_kerja,
                    'unit_kerja' => $unitKerja ?? $existingLulusan->unit_kerja,
                    'no_hp' => $row['no_hp'] ?? $existingLulusan->no_hp,
                    'nip_pengguna_lulusan' => $nipBaruPl ?? $nipLamaPl ?? ($row['nip_pengguna_lulusan'] ?? $existingLulusan->nip_pengguna_lulusan),
                    'nip_baru_pengguna_lulusan' => $nipBaruPl ?? $existingLulusan->nip_baru_pengguna_lulusan,
                    'nip_lama_pengguna_lulusan' => $nipLamaPl ?? $existingLulusan->nip_lama_pengguna_lulusan,
                    'tahun_lulus' => $tahunLulus ?? $existingLulusan->tahun_lulus
                ]);

                // Update the associated user if email is provided
                if (!empty($row['email']) && $existingLulusan->user) {
                    $existingLulusan->user->update([
                        'email' => $row['email'],
                        'name' => $cleanedName
                    ]);
                }

                // Create survey_user entry if survey_id is set and doesn't exist
                if ($this->survey_id && $existingLulusan->user) {
                    SurveyUser::firstOrCreate(
                        [
                            'survey_id' => $this->survey_id,
                            'user_id' => $existingLulusan->user->id,
                        ]
                    );
                }

                Log::info("Updated existing lulusan: " . $cleanedName . " with birth date: " . $tanggalLahir);
                return $existingLulusan;
            }

            // Create new record if no existing lulusan found
            // Create the user
            $userPassNip = $nipBaru ?? $nipLama ?? ($row['nip'] ?? '12345');
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $cleanedName,
                    'password' => bcrypt(substr($userPassNip, 0, 5)),
                    'role' => 'lulusan',
                ]
            );

            // Assign role if not already assigned
            if (!$user->hasRole('lulusan')) {
                $user->assignRole('lulusan');
            }

            // Create the lulusan record
            $lulusan = Lulusan::create([
                'user_id' => $user->id,
                'nama' => $cleanedName,
                'nip' => $nipBaru ?? $nipLama ?? ($row['nip'] ?? ''),
                'nip_baru' => $nipBaru,
                'nip_lama' => $nipLama,
                'email' => $row['email'] ?? '',
                'prodi' => $prodi,
                'jabatan' => $jabatan,
                'satuan_kerja' => $satuanKerja,
                'unit_kerja' => $unitKerja,
                'no_hp' => $row['no_hp'] ?? '',
                'nip_pengguna_lulusan' => $nipBaruPl ?? $nipLamaPl ?? ($row['nip_pengguna_lulusan'] ?? ''),
                'nip_baru_pengguna_lulusan' => $nipBaruPl,
                'nip_lama_pengguna_lulusan' => $nipLamaPl,
                'tanggal_lahir' => $tanggalLahir,
                'tahun_lulus' => $tahunLulus,
            ]);

            // Create survey_user entry if survey_id is set
            if ($this->survey_id) {
                SurveyUser::firstOrCreate(
                    [
                        'survey_id' => $this->survey_id,
                        'user_id' => $user->id,
                    ]
                );
            }

            Log::info("Created new lulusan: " . $cleanedName . " with birth date: " . $tanggalLahir);

            return $lulusan;
        } catch (QueryException $e) {
            Log::error("Database error during import: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        } catch (Exception $e) {
            Log::error("Import error: " . $e->getMessage());
            throw new Exception("Error importing data: " . $e->getMessage());
        }
    }
}
