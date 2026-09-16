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
        Schema::table('survey_user', function (Blueprint $table) {
            $table->timestamp('invitation_email_sent_at')->nullable()->after('tanggal_mengisi');
            $table->timestamp('last_reminder_email_sent_at')->nullable()->after('invitation_email_sent_at');
            $table->integer('reminder_email_count')->default(0)->after('last_reminder_email_sent_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('survey_user', function (Blueprint $table) {
            $table->dropColumn(['invitation_email_sent_at', 'last_reminder_email_sent_at', 'reminder_email_count']);
        });
    }
};
