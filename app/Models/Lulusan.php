<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lulusan extends Model
{
    protected $table = 'lulusan';
    protected $fillable = [
        'user_id',
        'nama',
        'nip',
        'nip_baru',
        'nip_lama',
        'email',
        'prodi',
        'jabatan',
        'satuan_kerja',
        'unit_kerja',
        'no_hp',
        'nip_pengguna_lulusan',
        'nip_baru_pengguna_lulusan',
        'nip_lama_pengguna_lulusan',
        'tanggal_lahir',
        'tahun_lulus',
        'provinsi',
        'kabupaten',
    ];

    public function getNipAttribute($value)
    {
        return $value ?: ($this->attributes['nip_baru'] ?? $this->attributes['nip_lama'] ?? null);
    }

    public function getNipPenggunaLulusanAttribute($value)
    {
        return $value ?: ($this->attributes['nip_baru_pengguna_lulusan'] ?? $this->attributes['nip_lama_pengguna_lulusan'] ?? null);
    }

    protected $appends = ['status_data', 'clean_name'];

    /**
     * Valid Program Studi options
     */
    public const VALID_PRODI = [
        'DIV Komputasi Statistik',
        'DIV Statistika',
        'DIII Statistika',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCleanNameAttribute()
    {
        $nameParts = explode(',', $this->nama, 2);
        return trim($nameParts[0]);
    }

    /**
     * Determine data completeness status.
     * "Data Lengkap" = all key fields filled & valid.
     * "Data Tidak Lengkap" = any key field is empty/null.
     */
    public function getStatusDataAttribute(): string
    {
        $requiredFields = [
            $this->prodi,
            $this->jabatan,
            $this->satuan_kerja,
            $this->unit_kerja,
            $this->provinsi,
            $this->kabupaten,
        ];

        foreach ($requiredFields as $field) {
            if (empty(trim((string) $field))) {
                return 'Data Tidak Lengkap';
            }
        }

        return 'Data Lengkap';
    }

    /**
     * Scope to filter by data completeness status.
     */
    public function scopeStatusData($query, string $status)
    {
        if ($status === 'lengkap') {
            return $query->whereNotNull('prodi')->where('prodi', '!=', '')
                         ->whereNotNull('jabatan')->where('jabatan', '!=', '')
                         ->whereNotNull('satuan_kerja')->where('satuan_kerja', '!=', '')
                         ->whereNotNull('unit_kerja')->where('unit_kerja', '!=', '')
                         ->whereNotNull('provinsi')->where('provinsi', '!=', '')
                         ->whereNotNull('kabupaten')->where('kabupaten', '!=', '');
        }

        if ($status === 'tidak_lengkap') {
            return $query->where(function ($q) {
                $q->whereNull('prodi')->orWhere('prodi', '')
                  ->orWhereNull('jabatan')->orWhere('jabatan', '')
                  ->orWhereNull('satuan_kerja')->orWhere('satuan_kerja', '')
                  ->orWhereNull('unit_kerja')->orWhere('unit_kerja', '')
                  ->orWhereNull('provinsi')->orWhere('provinsi', '')
                  ->orWhereNull('kabupaten')->orWhere('kabupaten', '');
            });
        }

        return $query;
    }
}
