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
        Schema::table('template_pertanyaan', function (Blueprint $table) {
            $table->bigInteger('min_gaji')->nullable()->after('is_analytic_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_pertanyaan', function (Blueprint $table) {
            $table->dropColumn('min_gaji');
        });
    }
};
