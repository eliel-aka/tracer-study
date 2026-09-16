<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('survey_blocks', function (Blueprint $table) {
            if (!Schema::hasColumn('survey_blocks', 'is_kompetensi_mode')) {
                $table->boolean('is_kompetensi_mode')->default(false)->after('metadata');
            }

            if (!Schema::hasColumn('survey_blocks', 'kompetensi_config')) {
                $table->json('kompetensi_config')->nullable()->after('is_kompetensi_mode');
            }
        });
    }

    public function down(): void
    {
        Schema::table('survey_blocks', function (Blueprint $table) {
            if (Schema::hasColumn('survey_blocks', 'kompetensi_config')) {
                $table->dropColumn('kompetensi_config');
            }
            if (Schema::hasColumn('survey_blocks', 'is_kompetensi_mode')) {
                $table->dropColumn('is_kompetensi_mode');
            }
        });
    }
};
