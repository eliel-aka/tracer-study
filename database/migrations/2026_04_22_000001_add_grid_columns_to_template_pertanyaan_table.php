<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('template_pertanyaan', 'grid_columns')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->json('grid_columns')->nullable()->after('visualisasi');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('template_pertanyaan', 'grid_columns')) {
            Schema::table('template_pertanyaan', function (Blueprint $table) {
                $table->dropColumn('grid_columns');
            });
        }
    }
};
