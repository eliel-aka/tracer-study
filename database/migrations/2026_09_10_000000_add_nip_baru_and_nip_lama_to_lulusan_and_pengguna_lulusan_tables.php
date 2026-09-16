<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('lulusan', function (Blueprint $table) {
            if (!Schema::hasColumn('lulusan', 'nip_baru')) {
                $table->string('nip_baru', 18)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('lulusan', 'nip_lama')) {
                $table->string('nip_lama', 9)->nullable()->after('nip_baru');
            }
            if (!Schema::hasColumn('lulusan', 'nip_baru_pengguna_lulusan')) {
                $table->string('nip_baru_pengguna_lulusan', 18)->nullable()->after('no_hp');
            }
            if (!Schema::hasColumn('lulusan', 'nip_lama_pengguna_lulusan')) {
                $table->string('nip_lama_pengguna_lulusan', 9)->nullable()->after('nip_baru_pengguna_lulusan');
            }
        });

        Schema::table('pengguna_lulusan', function (Blueprint $table) {
            if (!Schema::hasColumn('pengguna_lulusan', 'nip_baru')) {
                $table->string('nip_baru', 18)->nullable()->after('nama');
            }
            if (!Schema::hasColumn('pengguna_lulusan', 'nip_lama')) {
                $table->string('nip_lama', 9)->nullable()->after('nip_baru');
            }
        });

        // Data migration / backfill from existing nip columns
        $allLulusan = DB::table('lulusan')->get();
        foreach ($allLulusan as $item) {
            $update = [];
            if (!empty($item->nip)) {
                $cleanNip = preg_replace('/[^0-9]/', '', $item->nip);
                if (strlen($cleanNip) === 18) {
                    $update['nip_baru'] = $cleanNip;
                } elseif (strlen($cleanNip) === 9) {
                    $update['nip_lama'] = $cleanNip;
                }
            }
            if (!empty($item->nip_pengguna_lulusan)) {
                $cleanNipPl = preg_replace('/[^0-9]/', '', $item->nip_pengguna_lulusan);
                if (strlen($cleanNipPl) === 18) {
                    $update['nip_baru_pengguna_lulusan'] = $cleanNipPl;
                } elseif (strlen($cleanNipPl) === 9) {
                    $update['nip_lama_pengguna_lulusan'] = $cleanNipPl;
                }
            }
            if (!empty($update)) {
                DB::table('lulusan')->where('id', $item->id)->update($update);
            }
        }

        $allPengguna = DB::table('pengguna_lulusan')->get();
        foreach ($allPengguna as $item) {
            $update = [];
            if (!empty($item->nip)) {
                $cleanNip = preg_replace('/[^0-9]/', '', $item->nip);
                if (strlen($cleanNip) === 18) {
                    $update['nip_baru'] = $cleanNip;
                } elseif (strlen($cleanNip) === 9) {
                    $update['nip_lama'] = $cleanNip;
                }
            }
            if (!empty($update)) {
                DB::table('pengguna_lulusan')->where('id', $item->id)->update($update);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('lulusan', function (Blueprint $table) {
            $table->dropColumn(['nip_baru', 'nip_lama', 'nip_baru_pengguna_lulusan', 'nip_lama_pengguna_lulusan']);
        });

        Schema::table('pengguna_lulusan', function (Blueprint $table) {
            $table->dropColumn(['nip_baru', 'nip_lama']);
        });
    }
};
