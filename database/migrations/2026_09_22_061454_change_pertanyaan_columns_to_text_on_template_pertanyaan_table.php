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
            $table->text('pertanyaan')->change();
            $table->text('deskripsi_pertanyaan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_pertanyaan', function (Blueprint $table) {
            $table->string('pertanyaan', 255)->change();
            $table->string('deskripsi_pertanyaan', 255)->nullable()->change();
        });
    }
};
