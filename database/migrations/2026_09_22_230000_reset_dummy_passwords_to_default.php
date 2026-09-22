<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Reset password seluruh data dummy lulusan dan pengguna lulusan menjadi 'password'
        DB::table('users')
            ->whereIn('role', ['lulusan', 'pengguna_lulusan'])
            ->where(function ($q) {
                $q->where('email', 'like', 'lulusan%')
                  ->orWhere('email', 'like', 'penggunaLulusan%')
                  ->orWhere('email', 'like', 'penggunalulusan%')
                  ->orWhere('email', 'like', '%@example.com')
                  ->orWhere('email', 'lulusan@lulusan.com')
                  ->orWhere('email', 'penggunaLulusan@penggunaLulusan.com');
            })
            ->update([
                'password' => Hash::make('password'),
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed
    }
};
