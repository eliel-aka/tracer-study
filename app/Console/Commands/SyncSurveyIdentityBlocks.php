<?php

namespace App\Console\Commands;

use App\Models\Survey;
use App\Services\RespondentAttributeService;
use Illuminate\Console\Command;

class SyncSurveyIdentityBlocks extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'survey:sync-identity-blocks {--survey-id= : Sync specific survey ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ensure Block 1 in all surveys is the Identity Block containing respondent profile attributes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $surveyId = $this->option('survey-id');

        $query = Survey::query();
        if ($surveyId) {
            $query->where('id', $surveyId);
        }

        $surveys = $query->get();

        if ($surveys->isEmpty()) {
            $this->warn('Tidak ada survei yang ditemukan.');
            return 0;
        }

        $this->info("Menyinkronkan blok identitas untuk {$surveys->count()} survei...");

        foreach ($surveys as $survey) {
            $this->line("Memproses survei [ID: {$survey->id}] {$survey->nama} ({$survey->type_survei})...");
            $block = RespondentAttributeService::ensureIdentityBlock($survey);
            $questionCount = $block->questions()->count();
            $this->info("  ✓ Blok 1 [{$block->nama}] siap dengan {$questionCount} pertanyaan atribut.");
        }

        $this->info('Selesai! Seluruh survei memiliki Blok 1 Identitas Responden.');
        return 0;
    }
}
