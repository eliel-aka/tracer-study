<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update all existing 'likert' question types to 'multiple_choice_grid'
        DB::table('template_pertanyaan')
            ->where('tipe', 'likert')
            ->update(['tipe' => 'multiple_choice_grid']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to 'likert' if migration is rolled back
        DB::table('template_pertanyaan')
            ->where('tipe', 'multiple_choice_grid')
            ->update(['tipe' => 'likert']);
    }
};

