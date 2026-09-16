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
            $table->boolean('is_analytic_table')->default(false)->after('visualisasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_pertanyaan', function (Blueprint $table) {
            $table->dropColumn('is_analytic_table');
        });
    }
};
