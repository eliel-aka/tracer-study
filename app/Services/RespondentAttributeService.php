<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RespondentAttributeService
{
    /**
     * Get dictionary of attributes for a given respondent/survey type.
     */
    public static function getAttributes(string $type): array
    {
        $normalizedType = self::normalizeSurveyType($type);

        if ($normalizedType === 'penggunaLulusan') {
            return [
                'nama' => [
                    'label' => 'Nama Lengkap',
                    'type' => 'text',
                    'description' => 'Nama lengkap pengguna lulusan'
                ],
                'jenis_kelamin' => [
                    'label' => 'Jenis Kelamin',
                    'type' => 'text',
                    'description' => 'Jenis kelamin pengguna lulusan'
                ],
                'nip_baru' => [
                    'label' => 'NIP Baru (18 Digit)',
                    'type' => 'text',
                    'description' => 'Nomor Induk Pegawai 18 digit'
                ],
                'nip_lama' => [
                    'label' => 'NIP Lama (9 Digit)',
                    'type' => 'text',
                    'description' => 'Nomor Induk Pegawai 9 digit'
                ],
                'email' => [
                    'label' => 'Email',
                    'type' => 'text',
                    'description' => 'Alamat email aktif'
                ],
                'jabatan' => [
                    'label' => 'Jabatan',
                    'type' => 'text',
                    'description' => 'Posisi / jabatan di instansi'
                ],
                'satuan_kerja' => [
                    'label' => 'Satuan Kerja',
                    'type' => 'text',
                    'description' => 'Instansi / satuan kerja'
                ],
                'unit_kerja' => [
                    'label' => 'Unit Kerja',
                    'type' => 'text',
                    'description' => 'Bagian / unit kerja'
                ],
                'no_hp' => [
                    'label' => 'No. HP',
                    'type' => 'text',
                    'description' => 'Nomor handphone / WhatsApp'
                ],
            ];
        }

        // Default to lulusan
        return [
            'nama' => [
                'label' => 'Nama Lengkap',
                'type' => 'text',
                'description' => 'Nama lengkap lulusan'
            ],
            'jenis_kelamin' => [
                'label' => 'Jenis Kelamin',
                'type' => 'text',
                'description' => 'Jenis kelamin responden'
            ],
            'nip_baru' => [
                'label' => 'NIP Baru (18 Digit)',
                'type' => 'text',
                'description' => 'Nomor Induk Pegawai 18 digit'
            ],
            'nip_lama' => [
                'label' => 'NIP Lama (9 Digit)',
                'type' => 'text',
                'description' => 'Nomor Induk Pegawai 9 digit'
            ],
            'email' => [
                'label' => 'Email',
                'type' => 'text',
                'description' => 'Alamat email aktif'
            ],
            'prodi' => [
                'label' => 'Program Studi',
                'type' => 'text',
                'description' => 'Program studi kelulusan di STIS'
            ],
            'jabatan' => [
                'label' => 'Jabatan',
                'type' => 'text',
                'description' => 'Posisi / jabatan saat ini'
            ],
            'satuan_kerja' => [
                'label' => 'Satuan Kerja',
                'type' => 'text',
                'description' => 'Instansi / satuan kerja penempatan'
            ],
            'unit_kerja' => [
                'label' => 'Unit Kerja',
                'type' => 'text',
                'description' => 'Bagian / unit kerja saat ini'
            ],
            'no_hp' => [
                'label' => 'No. HP',
                'type' => 'text',
                'description' => 'Nomor handphone / WhatsApp'
            ],
            'tanggal_lahir' => [
                'label' => 'Tanggal Lahir',
                'type' => 'date',
                'description' => 'Tanggal lahir responden'
            ],
            'tahun_lulus' => [
                'label' => 'Tahun Lulus',
                'type' => 'text',
                'description' => 'Tahun kelulusan dari Politeknik Statistika STIS'
            ],
        ];
    }

    /**
     * Normalize survey type string.
     */
    public static function normalizeSurveyType(?string $type): string
    {
        $type = strtolower(trim((string)$type));
        if ($type === 'penggunalulusan' || $type === 'pengguna_lulusan') {
            return 'penggunaLulusan';
        }
        return 'lulusan';
    }

    /**
     * Helper to resolve gender string ('Laki-laki' or 'Perempuan') from profile or NIP.
     */
    public static function resolveGenderFromProfile($profile, ?User $user = null): string
    {
        if (!empty($profile->jenis_kelamin)) {
            $g = strtoupper(trim((string)$profile->jenis_kelamin));
            if (in_array($g, ['L', 'LAKI-LAKI', 'PRIA', '1', 'M', 'MALE'], true)) {
                return 'Laki-laki';
            }
            if (in_array($g, ['P', 'PEREMPUAN', 'WANITA', '2', 'F', 'FEMALE'], true)) {
                return 'Perempuan';
            }
        }

        if ($user && !empty($user->jenis_kelamin)) {
            $g = strtoupper(trim((string)$user->jenis_kelamin));
            if (in_array($g, ['L', 'LAKI-LAKI', 'PRIA', '1', 'M', 'MALE'], true)) {
                return 'Laki-laki';
            }
            if (in_array($g, ['P', 'PEREMPUAN', 'WANITA', '2', 'F', 'FEMALE'], true)) {
                return 'Perempuan';
            }
        }

        // Parse from 18-digit NIP (digit 15 indicates gender: 1 = Laki-laki, 2 = Perempuan)
        $nip = preg_replace('/\D/', '', (string)($profile->nip_baru ?? $profile->nip ?? ($user->nip ?? '')));
        if (strlen($nip) >= 15) {
            $code = substr($nip, 14, 1);
            if ($code === '1') {
                return 'Laki-laki';
            }
            if ($code === '2') {
                return 'Perempuan';
            }
            // Fallback for non-standard test seeders (odd = Laki-laki, even = Perempuan)
            return ((int)substr($nip, -1) % 2 === 0) ? 'Perempuan' : 'Laki-laki';
        }

        return 'Laki-laki';
    }

    /**
     * Extract user's profile attribute values mapped by attribute key.
     */
    public static function getUserProfileValues(User $user): array
    {
        $values = [];
        $isLulusan = $user->hasRole('lulusan') || ($user->role ?? null) === 'lulusan';
        $isPengguna = $user->hasRole('penggunaLulusan') || $user->hasRole('pengguna_lulusan') || in_array($user->role ?? null, ['penggunaLulusan', 'pengguna_lulusan'], true);

        if ($isLulusan && $user->lulusan) {
            $l = $user->lulusan;
            $values = [
                'nama' => $l->nama ?? $user->name,
                'jenis_kelamin' => self::resolveGenderFromProfile($l, $user),
                'nip_baru' => $l->nip_baru ?? '',
                'nip_lama' => $l->nip_lama ?? '',
                'email' => $l->email ?? $user->email,
                'prodi' => $l->prodi ?? '',
                'jabatan' => $l->jabatan ?? '',
                'satuan_kerja' => $l->satuan_kerja ?? '',
                'unit_kerja' => $l->unit_kerja ?? '',
                'no_hp' => $l->no_hp ?? '',
                'tanggal_lahir' => $l->tanggal_lahir ?? '',
                'tahun_lulus' => $l->tahun_lulus ? (string)$l->tahun_lulus : '',
                'nip_baru_pengguna_lulusan' => $l->nip_baru_pengguna_lulusan ?? '',
                'nip_lama_pengguna_lulusan' => $l->nip_lama_pengguna_lulusan ?? '',
            ];
        } elseif ($isPengguna && ($user->penggunaLulusan ?? $user->pengguna_lulusan)) {
            $p = $user->penggunaLulusan ?? $user->pengguna_lulusan;
            $values = [
                'nama' => $p->nama ?? $user->name,
                'jenis_kelamin' => self::resolveGenderFromProfile($p, $user),
                'nip_baru' => $p->nip_baru ?? '',
                'nip_lama' => $p->nip_lama ?? '',
                'email' => $p->email ?? $user->email,
                'jabatan' => $p->jabatan ?? '',
                'satuan_kerja' => $p->satuan_kerja ?? '',
                'unit_kerja' => $p->unit_kerja ?? '',
                'no_hp' => $p->no_hp ?? '',
            ];
        } else {
            // Fallback for user without specific profile
            $values = [
                'nama' => $user->name ?? '',
                'jenis_kelamin' => self::resolveGenderFromProfile((object)[], $user),
                'email' => $user->email ?? '',
            ];
        }

        return $values;
    }

    /**
     * Map a question text / label to its attribute key.
     */
    public static function mapQuestionToAttributeKey(string $questionText, string $type): ?string
    {
        $attributes = self::getAttributes($type);
        $cleanText = strtolower(trim($questionText));

        // Exact label match
        foreach ($attributes as $key => $attr) {
            if (strtolower(trim($attr['label'])) === $cleanText) {
                return $key;
            }
        }

        // Fuzzy / normalized match
        $normalizedMap = [
            'nama lengkap' => 'nama',
            'nama' => 'nama',
            'jenis kelamin' => 'jenis_kelamin',
            'jenis_kelamin' => 'jenis_kelamin',
            'kelamin' => 'jenis_kelamin',
            'gender' => 'jenis_kelamin',
            'jk' => 'jenis_kelamin',
            'sex' => 'jenis_kelamin',
            'nip baru' => 'nip_baru',
            'nip lama' => 'nip_lama',
            'email' => 'email',
            'program studi' => 'prodi',
            'prodi' => 'prodi',
            'jabatan' => 'jabatan',
            'satuan kerja' => 'satuan_kerja',
            'unit kerja' => 'unit_kerja',
            'no hp' => 'no_hp',
            'no. hp' => 'no_hp',
            'nomor hp' => 'no_hp',
            'tanggal lahir' => 'tanggal_lahir',
            'tahun lulus' => 'tahun_lulus',
            'nip baru pengguna lulusan' => 'nip_baru_pengguna_lulusan',
            'nip lama pengguna lulusan' => 'nip_lama_pengguna_lulusan',
            'nip pengguna lulusan' => 'nip_baru_pengguna_lulusan',
        ];

        foreach ($normalizedMap as $needle => $key) {
            if (str_contains($cleanText, $needle) && isset($attributes[$key])) {
                return $key;
            }
        }

        return null;
    }

    /**
     * Ensure a survey has the identity block at Block 1.
     * If not present, it will create or insert it as the first block.
     */
    public static function ensureIdentityBlock(Survey $survey): SurveyBlock
    {
        $type = self::normalizeSurveyType($survey->type_survei);
        $attributes = self::getAttributes($type);

        // Find if an identity block already exists for this survey
        $existingBlock = SurveyBlock::where('survey_id', $survey->id)
            ->where(function ($q) {
                $q->where('metadata->is_identity_block', true)
                  ->orWhere('nama', 'LIKE', '%identitas%');
            })
            ->orderBy('urutan')
            ->first();

        if ($existingBlock) {
            // Update metadata and ensure all questions exist
            $metadata = $existingBlock->metadata ?? [];
            $metadata['is_identity_block'] = true;

            // If it was not urutan 1, move it to urutan 1
            if ($existingBlock->urutan !== 1) {
                SurveyBlock::where('survey_id', $survey->id)
                    ->where('id', '!=', $existingBlock->id)
                    ->where('urutan', '<', $existingBlock->urutan)
                    ->increment('urutan');
                $existingBlock->urutan = 1;
                $existingBlock->kode = 'BLOCK_01';
            }

            $existingBlock->update([
                'nama' => 'Identitas Responden',
                'deskripsi' => 'Data identitas responden diambil otomatis dari profil sistem dan berstatus terkunci (read-only).',
                'metadata' => $metadata
            ]);

            self::syncQuestionsForIdentityBlock($existingBlock, $attributes);
            return $existingBlock;
        }

        // If block 1 exists but is not an identity block, shift all existing blocks up by 1
        DB::transaction(function () use ($survey, &$existingBlock, $type, $attributes) {
            // Shift existing blocks and their questions urutan + 1
            $allBlocks = SurveyBlock::where('survey_id', $survey->id)->orderBy('urutan', 'desc')->get();
            foreach ($allBlocks as $blk) {
                $blk->update([
                    'urutan' => $blk->urutan + 1,
                    'kode' => 'BLOCK_' . str_pad($blk->urutan + 1, 2, '0', STR_PAD_LEFT)
                ]);
            }

            // Create new Block 1
            $existingBlock = SurveyBlock::create([
                'survey_id' => $survey->id,
                'kode' => 'BLOCK_01',
                'nama' => 'Identitas Responden',
                'deskripsi' => 'Data identitas responden diambil otomatis dari profil sistem dan berstatus terkunci (read-only).',
                'urutan' => 1,
                'navigation_type' => 'next',
                'is_terminal' => false,
                'metadata' => [
                    'is_identity_block' => true,
                    'is_kompetensi' => false,
                    'survey_type' => $type
                ]
            ]);

            self::syncQuestionsForIdentityBlock($existingBlock, $attributes);
        });

        return $existingBlock;
    }

    /**
     * Synchronize attribute questions into an identity block.
     */
    public static function syncQuestionsForIdentityBlock(SurveyBlock $block, array $attributes): void
    {
        $surveyId = $block->survey_id;
        $order = 1;
        $processedIds = [];

        foreach ($attributes as $key => $attr) {
            $question = TemplatePertanyaan::where('block_id', $block->id)
                ->where(function ($q) use ($attr) {
                    $q->where('pertanyaan', $attr['label'])
                      ->orWhere('pertanyaan', 'like', '%' . $attr['label'] . '%');
                })
                ->first();

            $payload = [
                'id_survey' => $surveyId,
                'block_id' => $block->id,
                'pertanyaan' => $attr['label'],
                'deskripsi_pertanyaan' => $attr['description'] ?? '',
                'tipe' => $attr['type'],
                'urutan' => $order,
                'is_required' => true,
                'visualisasi' => null,
                'is_analytic_table' => false,
            ];

            if ($question) {
                $question->update($payload);
                $processedIds[] = $question->id;
            } else {
                $newQuestion = TemplatePertanyaan::create($payload);
                $processedIds[] = $newQuestion->id;
            }

            $order++;
        }

        // Delete any questions in this identity block that are no longer needed
        if (count($processedIds) > 0) {
            TemplatePertanyaan::where('block_id', $block->id)
                ->whereNotIn('id', $processedIds)
                ->delete();
        }
    }
}
