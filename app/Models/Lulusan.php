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
        'email',
        'prodi',
        'jabatan',
        'satuan_kerja',
        'unit_kerja',
        'no_hp',
        'nip_pengguna_lulusan',
        'tanggal_lahir',
        'tahun_lulus',
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
}
