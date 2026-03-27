<?php

namespace App\Imports;

use App\Models\Lulusan;
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

    public function __construct($survey_id = null)
    {
        $this->survey_id = $survey_id;
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
            $prodi = $row['prodi'] ?? ($row['program_studi'] ?? null);

            //mengeluarkan 8 angka NIP untuk menjadi variabel tanggal_lahir
            if($row['nip']){
                $row['tanggal_lahir'] = \Carbon\Carbon::createFromFormat('Ymd', substr($row['nip'], 0, 8))->format('Y-m-d');
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
                    'nip' => $row['nip'] ?? $existingLulusan->nip,
                    'email' => $row['email'] ?? $existingLulusan->email,
                    'prodi' => $prodi ?? $existingLulusan->prodi,
                    'jabatan' => $row['jabatan'] ?? $existingLulusan->jabatan,
                    'satuan_kerja' => $row['satuan_kerja'] ?? $existingLulusan->satuan_kerja,
                    'unit_kerja' => $row['unit_kerja'] ?? $existingLulusan->unit_kerja,
                    'no_hp' => $row['no_hp'] ?? $existingLulusan->no_hp,
                    'nip_pengguna_lulusan' => $row['nip_pengguna_lulusan'] ?? $existingLulusan->nip_pengguna_lulusan,
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
            $user = User::firstOrCreate(
                ['email' => $row['email']],
                [
                    'name' => $cleanedName,
                    'password' => bcrypt(substr($row['nip'], 0, 5)),
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
                'nip' => $row['nip'] ?? '',
                'email' => $row['email'] ?? '',
                'prodi' => $prodi,
                'jabatan' => $row['jabatan'] ?? '',
                'satuan_kerja' => $row['satuan_kerja'] ?? '',
                'unit_kerja' => $row['unit_kerja'] ?? '',
                'no_hp' => $row['no_hp'] ?? '',
                'nip_pengguna_lulusan' => $row['nip_pengguna_lulusan'] ?? '',
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
