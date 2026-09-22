<?php

namespace App\Services;

use App\Models\Survey;
use App\Models\SurveyBlock;
use App\Models\TemplatePertanyaan;
use App\Models\TemplateJawaban;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

/**
 * Menghitung pertanyaan berikutnya berdasarkan jawaban,
 * menerapkan branching rules ala Google Forms.
 */
class SurveyFlowService
{
    /**
     * @param  Survey $survey
     * @param  TemplatePertanyaan $currentQuestion
     * @param  array $givenAnswer  // contoh: ['answer_option_id' => 123] atau ['value' => 'A']
     * @return TemplatePertanyaan|null  // null = selesai/terminal
     */
    public function nextQuestion(Survey $survey, TemplatePertanyaan $currentQuestion, array $givenAnswer): ?TemplatePertanyaan
    {
        try {
            // 1) Cek rule spesifik dari answer_option (TemplateJawaban)
            $targetBlockId = $this->matchOptionNavigation($currentQuestion, $givenAnswer);
            
            if ($targetBlockId !== false) {
                if ($targetBlockId === 'end') {
                    return null; // selesai
                }
                
                if ($targetBlockId !== 'next' && $targetBlockId !== null) {
                    return $this->firstQuestionOfBlock((int) $targetBlockId);
                }
            }

            // 2) Default: pertanyaan berikutnya dalam blok yang sama
            $nextInBlock = $this->nextQuestionInSameBlock($currentQuestion);
            if ($nextInBlock) {
                return $nextInBlock;
            }

            // 3) Jika habis, lanjut ke blok berikutnya (berdasarkan urutan / target_section_id pada blok)
            $nextBlock = $this->nextBlock($survey, $currentQuestion->block_id);
            if (!$nextBlock || $nextBlock->is_terminal) {
                return null; // selesai
            }

            return $this->firstQuestionOfBlock($nextBlock->id);
        } catch (\Exception $e) {
            Log::error('Error in SurveyFlowService::nextQuestion: ' . $e->getMessage(), [
                'survey_id' => $survey->id,
                'current_question_id' => $currentQuestion->id,
                'given_answer' => $givenAnswer
            ]);
            
            // Fallback to default behavior
            return $this->nextQuestionInSameBlock($currentQuestion);
        }
    }

    /**
     * Match navigation rule based on selected answer option directly from TemplateJawaban
     * Returns: 
     * - false (no matching option or no custom navigation set)
     * - 'end' (end survey)
     * - 'next' (next block)
     * - numeric string (target block ID)
     */
    protected function matchOptionNavigation(TemplatePertanyaan $q, array $answer)
    {
        $answerOptionId = Arr::get($answer, 'answer_option_id');
        
        // Handle checkbox which uses 'value' as an array of IDs, but custom navigation 
        // usually doesn't work well for multiple choices. We'll take the first one if it exists.
        if (!$answerOptionId && isset($answer['value']) && is_array($answer['value'])) {
            $answerOptionId = Arr::first($answer['value']);
        }
        
        if (!$answerOptionId) return false;

        $option = TemplateJawaban::find($answerOptionId);
        
        if ($option && $option->navigation_target) {
            return $option->navigation_target;
        }

        return false;
    }

    /**
     * Get first question of a specific block
     */
    protected function firstQuestionOfBlock(int $blockId): ?TemplatePertanyaan
    {
        return TemplatePertanyaan::query()
            ->where('block_id', $blockId)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get next question in the same block
     */
    protected function nextQuestionInSameBlock(TemplatePertanyaan $q): ?TemplatePertanyaan
    {
        if (!$q->block_id) {
            // Fallback: if no block_id, use old logic based on survey and urutan
            return TemplatePertanyaan::query()
                ->where('id_survey', $q->id_survey)
                ->where('urutan', '>', $q->urutan)
                ->orderBy('urutan')
                ->first();
        }

        return TemplatePertanyaan::query()
            ->where('id_survey', $q->id_survey)
            ->where('block_id', $q->block_id)
            ->where('urutan', '>', $q->urutan)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get next block based on block's own target_section_id or urutan
     */
    protected function nextBlock(Survey $survey, ?int $currentBlockId): ?SurveyBlock
    {
        if (!$currentBlockId) return null;
        
        $current = SurveyBlock::query()->find($currentBlockId);
        if (!$current) return null;

        // Check if block has explicit target
        if ($current->is_terminal) {
            return null;
        }
        
        if ($current->target_section_id) {
            return SurveyBlock::find($current->target_section_id);
        }

        // Fallback to sequential next
        return SurveyBlock::query()
            ->where('survey_id', $survey->id)
            ->where('urutan', '>', $current->urutan)
            ->orderBy('urutan')
            ->first();
    }

    /**
     * Get all blocks for a survey ordered by urutan
     */
    public function getSurveyBlocks(int $surveyId): \Illuminate\Database\Eloquent\Collection
    {
        return SurveyBlock::query()
            ->where('survey_id', $surveyId)
            ->orderBy('urutan')
            ->get();
    }

    /**
     * Check if there are any cycles in the branching rules (optional validation)
     */
    public function detectCycles(int $surveyId): array
    {
        $blocks = $this->getSurveyBlocks($surveyId);
        $visited = [];
        $recursionStack = [];
        $cycles = [];

        foreach ($blocks as $block) {
            if (!isset($visited[$block->id])) {
                $this->dfsDetectCycle($block, $visited, $recursionStack, $cycles, $surveyId);
            }
        }

        return $cycles;
    }

    /**
     * DFS helper for cycle detection
     */
    private function dfsDetectCycle(SurveyBlock $block, array &$visited, array &$recursionStack, array &$cycles, int $surveyId): void
    {
        $visited[$block->id] = true;
        $recursionStack[$block->id] = true;

        // Collect all potential targets for this block
        $targetBlockIds = [];
        
        // 1. Target from block itself
        if ($block->target_section_id) {
            $targetBlockIds[] = $block->target_section_id;
        }
        
        // 2. Targets from questions inside the block (TemplateJawaban)
        $optionTargets = TemplateJawaban::query()
            ->whereIn('id_template_pertanyaan', function ($query) use ($block) {
                $query->select('id')
                      ->from('template_pertanyaan')
                      ->where('block_id', $block->id);
            })
            ->whereNotNull('navigation_target')
            ->whereNotIn('navigation_target', ['next', 'end', ''])
            ->pluck('navigation_target')
            ->toArray();
            
        foreach ($optionTargets as $targetId) {
            if (is_numeric($targetId)) {
                $targetBlockIds[] = (int) $targetId;
            }
        }
        
        $targetBlockIds = array_unique($targetBlockIds);

        foreach ($targetBlockIds as $targetBlockId) {
            if (!isset($visited[$targetBlockId])) {
                $targetBlock = SurveyBlock::find($targetBlockId);
                if ($targetBlock) {
                    $this->dfsDetectCycle($targetBlock, $visited, $recursionStack, $cycles, $surveyId);
                }
            } elseif (isset($recursionStack[$targetBlockId]) && $recursionStack[$targetBlockId]) {
                $cycles[] = "Cycle detected: Block {$block->kode} -> Block " . 
                          (SurveyBlock::find($targetBlockId)->kode ?? $targetBlockId);
            }
        }

        $recursionStack[$block->id] = false;
    }
}
