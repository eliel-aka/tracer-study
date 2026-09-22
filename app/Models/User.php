<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function lulusan()
    {
        return $this->hasOne(Lulusan::class);
    }

    public function penggunaLulusan()
    {
        return $this->hasOne(PenggunaLulusan::class);
    }

    public function pengguna_lulusan()
    {
        return $this->penggunaLulusan();
    }

    /**
     * Generate default 6-character password from nama, nip_baru, and nip_lama.
     * Format: 2 huruf terakhir nama + 2 digit terakhir NIP Baru + 2 digit terakhir NIP Lama
     */
    public static function generateDefaultPassword(?string $nama, ?string $nipBaru, ?string $nipLama): string
    {
        // 2 huruf terakhir nama (abaikan gelar/karakter khusus, lowercase)
        $cleanNama = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(',', $nama ?? '')[0]));
        if (strlen($cleanNama) >= 2) {
            $nama2 = substr($cleanNama, -2);
        } elseif (strlen($cleanNama) === 1) {
            $nama2 = $cleanNama . 'x';
        } else {
            $nama2 = 'xx';
        }

        // 2 digit terakhir NIP Baru
        $cleanNipBaru = preg_replace('/[^0-9]/', '', $nipBaru ?? '');
        if (strlen($cleanNipBaru) >= 2) {
            $nipBaru2 = substr($cleanNipBaru, -2);
        } elseif (strlen($cleanNipBaru) === 1) {
            $nipBaru2 = '0' . $cleanNipBaru;
        } else {
            $nipBaru2 = '00';
        }

        // 2 digit terakhir NIP Lama
        $cleanNipLama = preg_replace('/[^0-9]/', '', $nipLama ?? '');
        if (strlen($cleanNipLama) >= 2) {
            $nipLama2 = substr($cleanNipLama, -2);
        } elseif (strlen($cleanNipLama) === 1) {
            $nipLama2 = '0' . $cleanNipLama;
        } else {
            if (strlen($cleanNipBaru) >= 4) {
                $nipLama2 = substr($cleanNipBaru, -4, 2);
            } else {
                $nipLama2 = '00';
            }
        }

        return $nama2 . $nipBaru2 . $nipLama2;
    }

    /**
     * Generate alternative default password permutation:
     * Format: 2 digit terakhir NIP Baru + 2 huruf terakhir nama + 2 digit terakhir NIP Lama
     */
    public static function generateAlternativeDefaultPassword(?string $nama, ?string $nipBaru, ?string $nipLama): string
    {
        $cleanNama = strtolower(preg_replace('/[^a-zA-Z]/', '', explode(',', $nama ?? '')[0]));
        if (strlen($cleanNama) >= 2) {
            $nama2 = substr($cleanNama, -2);
        } elseif (strlen($cleanNama) === 1) {
            $nama2 = $cleanNama . 'x';
        } else {
            $nama2 = 'xx';
        }

        $cleanNipBaru = preg_replace('/[^0-9]/', '', $nipBaru ?? '');
        if (strlen($cleanNipBaru) >= 2) {
            $nipBaru2 = substr($cleanNipBaru, -2);
        } elseif (strlen($cleanNipBaru) === 1) {
            $nipBaru2 = '0' . $cleanNipBaru;
        } else {
            $nipBaru2 = '00';
        }

        $cleanNipLama = preg_replace('/[^0-9]/', '', $nipLama ?? '');
        if (strlen($cleanNipLama) >= 2) {
            $nipLama2 = substr($cleanNipLama, -2);
        } elseif (strlen($cleanNipLama) === 1) {
            $nipLama2 = '0' . $cleanNipLama;
        } else {
            if (strlen($cleanNipBaru) >= 4) {
                $nipLama2 = substr($cleanNipBaru, -4, 2);
            } else {
                $nipLama2 = '00';
            }
        }

        return $nipBaru2 . $nama2 . $nipLama2;
    }
}
