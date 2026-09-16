<?php

namespace App\Services;

use App\Models\TemplateEmail;
use App\Models\User;
use App\Models\Survey;
use App\Models\SurveyUser;
use App\Mail\SendEmail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class SurveyEmailService
{
    /**
     * Send survey invitation email
     */
    public function sendInvitation(User $user, Survey $survey, ?string $password = null)
    {
        try {
            $template = TemplateEmail::where('type', 'survey_invitation')->first();
            
            if (!$template) {
                throw new \Exception('Survey invitation template not found');
            }

            // Generate password: 2 angka terakhir NIP + 2 huruf terakhir nama + 2 angka terakhir tanggal lahir
            if (!$password) {
                $nip = $user->lulusan->nip ?? $user->penggunaLulusan->nip ?? '';
                $nama = $user->name ?? '';
                $tanggalLahir = $user->lulusan->tanggal_lahir ?? $user->penggunaLulusan->tanggal_lahir ?? '01';
                
                if ($nip && $nama) {
                    $nipLast2 = substr($nip, -2);
                    $namaLast2 = strtolower(substr(preg_replace('/[^A-Za-z]/', '', $nama), -2));
                    $tanggalLast2 = substr(str_replace('-', '', $tanggalLahir), -2);
                    $password = $nipLast2 . $namaLast2 . $tanggalLast2;
                } else {
                    $password = 'password123';
                }
            }

            $subject = $this->replacePlaceholders($template->subject, $user, $survey, $password);
            $body = $this->replacePlaceholders($template->body, $user, $survey, $password);

            $emailData = [
                'nama' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'link' => route('login'),
                'subject' => $subject,
                'body' => $body,
                'survey_name' => $survey->nama,
                'survey_description' => $survey->deskripsi,
                'survey_start_date' => $survey->tanggal_mulai,
                'survey_end_date' => $survey->tanggal_selesai,
                'attachments' => $template->attachments,
            ];

            Mail::to($user->email)->send(new SendEmail($emailData));

            SurveyUser::where('user_id', $user->id)
                ->where('survey_id', $survey->id)
                ->update(['invitation_email_sent_at' => now()]);

            Log::info("Survey invitation sent to {$user->email} for survey: {$survey->nama}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send survey invitation: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send survey reminder email
     */
    public function sendReminder(User $user, Survey $survey, ?string $password = null)
    {
        try {
            $template = TemplateEmail::where('type', 'survey_reminder')->first();
            
            if (!$template) {
                throw new \Exception('Survey reminder template not found');
            }

            // Generate password: 2 angka terakhir NIP + 2 huruf terakhir nama + 2 angka terakhir tanggal lahir
            if (!$password) {
                $nip = $user->lulusan->nip ?? $user->penggunaLulusan->nip ?? '';
                $nama = $user->name ?? '';
                $tanggalLahir = $user->lulusan->tanggal_lahir ?? $user->penggunaLulusan->tanggal_lahir ?? '01';
                
                if ($nip && $nama) {
                    $nipLast2 = substr($nip, -2);
                    $namaLast2 = strtolower(substr(preg_replace('/[^A-Za-z]/', '', $nama), -2));
                    $tanggalLast2 = substr(str_replace('-', '', $tanggalLahir), -2);
                    $password = $nipLast2 . $namaLast2 . $tanggalLast2;
                } else {
                    $password = 'password123';
                }
            }

            $subject = $this->replacePlaceholders($template->subject, $user, $survey, $password);
            $body = $this->replacePlaceholders($template->body, $user, $survey, $password);

            $emailData = [
                'nama' => $user->name,
                'email' => $user->email,
                'password' => $password,
                'link' => route('login'),
                'subject' => $subject,
                'body' => $body,
                'survey_name' => $survey->nama,
                'survey_description' => $survey->deskripsi,
                'survey_start_date' => $survey->tanggal_mulai,
                'survey_end_date' => $survey->tanggal_selesai,
                'attachments' => $template->attachments,
            ];

            Mail::to($user->email)->send(new SendEmail($emailData));

            SurveyUser::where('user_id', $user->id)
                ->where('survey_id', $survey->id)
                ->update([
                    'last_reminder_email_sent_at' => now(),
                    'reminder_email_count' => \Illuminate\Support\Facades\DB::raw('reminder_email_count + 1')
                ]);

            Log::info("Survey reminder sent to {$user->email} for survey: {$survey->nama}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send survey reminder: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send thank you email after survey completion
     */
    public function sendThankYou(User $user, Survey $survey)
    {
        try {
            $template = TemplateEmail::where('type', 'survey_appreciation')->first();
            
            if (!$template) {
                throw new \Exception('Survey appreciation template not found');
            }

            $subject = $this->replacePlaceholders($template->subject, $user, $survey);
            $body = $this->replacePlaceholders($template->body, $user, $survey);

            $emailData = [
                'nama' => $user->name,
                'email' => $user->email,
                'password' => '', // Not needed for thank you email
                'link' => route('user.monitoring.index'),
                'subject' => $subject,
                'body' => $body,
                'survey_name' => $survey->nama,
                'survey_description' => $survey->deskripsi,
                'attachments' => $template->attachments,
            ];

            Mail::to($user->email)->send(new SendEmail($emailData));

            Log::info("Survey thank you email sent to {$user->email} for survey: {$survey->nama}");
            
            return true;
        } catch (\Exception $e) {
            Log::error("Failed to send survey thank you email: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Send bulk invitations to multiple users
     */
    public function sendBulkInvitations(Survey $survey, array $userIds, string $defaultPassword = 'password123')
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($userIds as $userId) {
            $user = User::find($userId);
            if ($user) {
                if ($this->sendInvitation($user, $survey, $defaultPassword)) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } else {
                $failCount++;
            }
        }

        Log::info("Bulk invitation completed for survey {$survey->nama}: {$successCount} success, {$failCount} failed");
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => count($userIds)
        ];
    }

    /**
     * Send reminders to users who haven't completed the survey
     */
    public function sendBulkReminders(Survey $survey, string $defaultPassword = 'password123')
    {
        $surveyUsers = SurveyUser::where('survey_id', $survey->id)
                                ->where('status', false)
                                ->with('user')
                                ->get();

        $successCount = 0;
        $failCount = 0;

        foreach ($surveyUsers as $surveyUser) {
            if ($surveyUser->user) {
                if ($this->sendReminder($surveyUser->user, $survey, $defaultPassword)) {
                    $successCount++;
                } else {
                    $failCount++;
                }
            } else {
                $failCount++;
            }
        }

        Log::info("Bulk reminder completed for survey {$survey->nama}: {$successCount} success, {$failCount} failed");
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $surveyUsers->count()
        ];
    }

    /**
     * Send bulk invitations to a collection of users
     */
    public function sendBulkInvitationsToCollection($users, Survey $survey)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            if ($this->sendInvitation($user, $survey)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        Log::info("Bulk invitation completed for survey {$survey->nama}: {$successCount} success, {$failCount} failed");
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $users->count()
        ];
    }

    /**
     * Send bulk reminders to a collection of users
     */
    public function sendBulkRemindersToCollection($users, Survey $survey)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            if ($this->sendReminder($user, $survey)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        Log::info("Bulk reminder completed for survey {$survey->nama}: {$successCount} success, {$failCount} failed");
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $users->count()
        ];
    }

    /**
     * Send bulk thank you emails to a collection of users
     */
    public function sendBulkThankYouToCollection($users, Survey $survey)
    {
        $successCount = 0;
        $failCount = 0;

        foreach ($users as $user) {
            if ($this->sendThankYou($user, $survey)) {
                $successCount++;
            } else {
                $failCount++;
            }
        }

        Log::info("Bulk thank you completed for survey {$survey->nama}: {$successCount} success, {$failCount} failed");
        
        return [
            'success' => $successCount,
            'failed' => $failCount,
            'total' => $users->count()
        ];
    }

    /**
     * Replace placeholders in email template
     */
    private function replacePlaceholders(string $text, User $user, Survey $survey, ?string $password = null): string
    {
        $placeholders = [
            '{{name}}' => $user->name,
            '{{nama}}' => $user->name,
            '{{email}}' => $user->email,
            '{{password}}' => $password ?? '',
            '{{survey_name}}' => $survey->nama,
            '{{survey_description}}' => $survey->deskripsi,
            '{{date}}' => $survey->tanggal_mulai,
            '{{start_date}}' => $survey->tanggal_mulai,
            '{{end_date}}' => $survey->tanggal_selesai,
            '{{login_url}}' => route('login'),
        ];

        return str_replace(array_keys($placeholders), array_values($placeholders), $text);
    }

    /**
     * Get email template for editing
     */
    public function getTemplate(string $type)
    {
        return TemplateEmail::where('type', $type)->first();
    }

    /**
     * Update email template
     */
    public function updateTemplate(string $type, string $subject, string $body, array $attachmentPaths = [], array $removeAttachments = [])
    {
        try {
            $template = TemplateEmail::where('type', $type)->first();
            
            $data = [
                'subject' => $subject,
                'body' => $body
            ];

            $currentAttachments = $template ? ($template->attachments ?? []) : [];

            // Remove requested attachments
            if (!empty($removeAttachments)) {
                $updatedAttachments = [];
                foreach ($currentAttachments as $path) {
                    if (in_array($path, $removeAttachments)) {
                        Storage::disk('public')->delete($path);
                    } else {
                        $updatedAttachments[] = $path;
                    }
                }
                $currentAttachments = $updatedAttachments;
            }

            // Add new attachments
            if (!empty($attachmentPaths)) {
                $currentAttachments = array_merge($currentAttachments, $attachmentPaths);
            }

            $data['attachments'] = $currentAttachments;

            if ($template) {
                $template->update($data);
            } else {
                $data['type'] = $type;
                TemplateEmail::create($data);
            }

            return true;
        } catch (\Exception $e) {
            Log::error("Failed to update email template: " . $e->getMessage());
            return false;
        }
    }
}
