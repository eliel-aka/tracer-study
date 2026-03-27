<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenggunaLulusan extends Model
{
    protected $table = 'pengguna_lulusan';
    protected $fillable = ['user_id', 'nama','nip','email', 'jabatan', 'satuan_kerja', 'unit_kerja','no_hp'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
