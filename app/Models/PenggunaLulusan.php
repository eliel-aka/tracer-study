<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaLulusan extends Model
{
    protected $table = 'pengguna_lulusan';
    protected $fillable = ['user_id', 'nama', 'nip', 'nip_baru', 'nip_lama', 'email', 'jabatan', 'satuan_kerja', 'unit_kerja', 'no_hp'];

    public function getNipAttribute($value)
    {
        return $value ?: ($this->attributes['nip_baru'] ?? $this->attributes['nip_lama'] ?? null);
    }

    protected $appends = ['status_data'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Determine data completeness status.
     */
    public function getStatusDataAttribute(): string
    {
        $requiredFields = [
            $this->jabatan,
            $this->satuan_kerja,
            $this->unit_kerja,
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
            return $query->whereNotNull('jabatan')->where('jabatan', '!=', '')
                         ->whereNotNull('satuan_kerja')->where('satuan_kerja', '!=', '')
                         ->whereNotNull('unit_kerja')->where('unit_kerja', '!=', '');
        }

        if ($status === 'tidak_lengkap') {
            return $query->where(function ($q) {
                $q->whereNull('jabatan')->orWhere('jabatan', '')
                  ->orWhereNull('satuan_kerja')->orWhere('satuan_kerja', '')
                  ->orWhereNull('unit_kerja')->orWhere('unit_kerja', '');
            });
        }

        return $query;
    }
}
