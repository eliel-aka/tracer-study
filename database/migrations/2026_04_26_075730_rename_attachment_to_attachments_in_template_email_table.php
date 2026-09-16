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
        Schema::table('template_email', function (Blueprint $table) {
            $table->text('attachments')->nullable();
        });

        // Convert existing data if any
        $templates = \Illuminate\Support\Facades\DB::table('template_email')->whereNotNull('attachment')->get();
        foreach ($templates as $template) {
            \Illuminate\Support\Facades\DB::table('template_email')->where('id', $template->id)->update([
                'attachments' => json_encode([$template->attachment])
            ]);
        }

        Schema::table('template_email', function (Blueprint $table) {
            $table->dropColumn('attachment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('template_email', function (Blueprint $table) {
            $table->string('attachment')->nullable();
        });

        // Try to revert first element of JSON array back to single string
        $templates = \Illuminate\Support\Facades\DB::table('template_email')->whereNotNull('attachments')->get();
        foreach ($templates as $template) {
            $attachments = json_decode($template->attachments, true);
            if (is_array($attachments) && count($attachments) > 0) {
                \Illuminate\Support\Facades\DB::table('template_email')->where('id', $template->id)->update([
                    'attachment' => $attachments[0]
                ]);
            }
        }

        Schema::table('template_email', function (Blueprint $table) {
            $table->dropColumn('attachments');
        });
    }
};
