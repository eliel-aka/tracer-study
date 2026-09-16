<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyUser;
use App\Models\TemplateJawaban;
use App\Models\TemplatePertanyaan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\LulusanImport;
use App\Imports\PenggunaLulusanImport;

class SurveyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
       $query=Survey::query();

        if ($request->has('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function($q) use ($searchTerm) {
                $q->where('nama', 'like', $searchTerm)
                  ->orWhere('type_survei', 'like', $searchTerm);
            });
        }

        $survey = $query->orderBy('created_at', 'desc')
                        ->paginate(10)
                        ->appends($request->query());
        ///

        foreach ($survey as $srvy) {
            $srvy->tanggal_mulai = Carbon::parse($srvy->tanggal_mulai)->format("d-m-Y");
            $srvy->tanggal_selesai = Carbon::parse($srvy->tanggal_selesai)->format("d-m-Y");

            if (Carbon::parse($srvy->tanggal_selesai) >= now()) {
                $srvy->status = "Aktif";
            } else {
                $srvy->status = "Selesai";
            }
        }

        return view('admin.views.survey.index', compact('survey'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $survey = Survey::paginate(10);
        return view('admin.views.survey.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'type_survei' => 'required|in:lulusan,penggunaLulusan,pengguna_lulusan',
            'deskripsi' => 'nullable|string',
            'sections' => 'nullable|array',
            'sections.*.section_name' => 'required_with:sections|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.navigation_type' => 'nullable|string',
            'sections.*.is_kompetensi' => 'nullable|boolean',
            'sections.*.pertanyaan_utama' => 'nullable|string|max:500',
            'sections.*.questions' => 'required_with:sections|array|min:1',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,multiple_choice_grid,file,date,gaji',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.min_gaji' => 'nullable|numeric|min:0',
            'sections.*.questions.*.is_analytic_table' => 'nullable|boolean',
            'sections.*.questions.*.options' => 'nullable|array',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.questions.*.grid_columns' => 'nullable|array|max:10',
            'sections.*.questions.*.grid_columns.*' => 'string|max:100',
            'sections.*.questions.*.option_navigation' => 'nullable|array',
            'sections.*.questions.*.option_navigation.*' => 'string|max:255',
        ]);

        try {
            $this->validateCompetencyBlocks($validatedData['sections'] ?? []);
            DB::beginTransaction();

            // Create the survey
            $survey = Survey::create([
                'nama' => $validatedData['nama'],
                'tanggal_mulai' => $validatedData['tanggal_mulai'],
                'tanggal_selesai' => $validatedData['tanggal_selesai'],
                'type_survei' => $this->normalizeSurveyTypeInput($validatedData['type_survei']),
                'deskripsi' => $validatedData['deskripsi'] ?? null,
                'created_by' => Auth::id(),
            ]);

            // If sections are provided, create form builder content
            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                $this->createFormBuilderContent($survey, $validatedData['sections']);
            }

            // Always ensure Block 1 is the identity block populated with respondent profile attributes
            \App\Services\RespondentAttributeService::ensureIdentityBlock($survey);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey berhasil dibuat' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'),
                    'survey_id' => $survey->id,
                    'redirect_url' => route('admin.survey.index')
                ], 200);
            }

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil dibuat' . (isset($validatedData['sections']) ? ' dengan form builder!' : '!'));

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error creating survey with form builder', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'request_data' => $request->all()
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan survey: ' . $e->getMessage(),
                    'errors' => ['database' => [$e->getMessage()]]
                ], 422);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat menyimpan survey: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Create form builder content (blocks and questions) - Two-Pass Approach
     */
    private function createFormBuilderContent($survey, $sections)
    {
        Log::info('Creating form builder content with two-pass approach', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections)
        ]);

        // Reindex sections array to ensure sequential order (0, 1, 2, 3...)
        // This fixes the issue where DOM insertion creates non-sequential keys
        $sections = array_values($sections);
        
        Log::info('Sections reindexed for sequential processing', [
            'original_keys' => array_keys($sections),
            'reindexed_count' => count($sections)
        ]);

        // PASS 1: Create all blocks first
        $createdBlocks = [];
        foreach ($sections as $sectionIndex => $sectionData) {
            $kode = 'BLOCK_' . str_pad(($sectionIndex + 1), 2, '0', STR_PAD_LEFT);
            $blockNumber = $sectionIndex + 1;

            $isIdentityBlock = (!empty($sectionData['metadata']['is_identity_block'])) || (stripos($sectionData['section_name'] ?? '', 'identitas') !== false);

            $blockData = [
                'survey_id' => $survey->id,
                'kode' => $kode,
                'nama' => $sectionData['section_name'],
                'deskripsi' => $sectionData['section_description'] ?? '',
                'urutan' => $blockNumber,
                'navigation_type' => $sectionData['navigation_type'] ?? 'next',
                'is_terminal' => false,
                'target_section_id' => null, // Will be updated in PASS 3
                'metadata' => [
                    'is_kompetensi' => isset($sectionData['is_kompetensi']) && $sectionData['is_kompetensi'] == '1',
                    'pertanyaan_utama' => $sectionData['pertanyaan_utama'] ?? '',
                    'is_identity_block' => $isIdentityBlock,
                ],
                'created_at' => now(),
                'updated_at' => now()
            ];

            try {
                $block = \App\Models\SurveyBlock::create($blockData);
                $createdBlocks[$blockNumber] = $block; // Key = block number for easy lookup

                Log::info('Block created in pass 1', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'block_name' => $block->nama
                ]);

            } catch (\Exception $e) {
                Log::error('Failed to create survey block in pass 1', [
                    'error' => $e->getMessage(),
                    'data' => $blockData
                ]);
                throw new \Exception('Gagal membuat survey block: ' . $e->getMessage());
            }
        }

        // PASS 2: Create questions and answers with resolved navigation
        foreach ($sections as $sectionIndex => $sectionData) {
            $blockNumber = $sectionIndex + 1;
            $block = $createdBlocks[$blockNumber];

            if (isset($sectionData['questions']) && is_array($sectionData['questions'])) {
                // Reindex questions array to ensure sequential order (0, 1, 2, 3...)
                $questions = array_values($sectionData['questions']);
                
                $isKompetensi = isset($block->metadata['is_kompetensi']) && $block->metadata['is_kompetensi'];

                if ($isKompetensi && count($questions) > 0) {
                    $baseQuestionData = $questions[0];
                    $indikators = array_filter(array_map('trim', explode("\n", $baseQuestionData['question'])));
                    
                    $expandedQuestions = [];
                    foreach ($indikators as $ind) {
                        $qData = $baseQuestionData;
                        $qData['question'] = $ind;
                        $expandedQuestions[] = $qData;
                    }
                    $questions = $expandedQuestions;
                }

                foreach ($questions as $questionIndex => $questionData) {
                    $question = \App\Models\TemplatePertanyaan::create([
                        'id_survey' => $survey->id,
                        'block_id' => $block->id,
                        'pertanyaan' => $questionData['question'],
                        'deskripsi_pertanyaan' => $questionData['description'] ?? '',
                        'tipe' => $questionData['type'],
                        'urutan' => $questionIndex + 1,
                        'is_required' => isset($questionData['required']) ? (bool)$questionData['required'] : false,
                        'visualisasi' => $this->normalizeVisualizationValue($questionData['visualization'] ?? null),
                        'is_analytic_table' => $isKompetensi ? true : (isset($questionData['is_analytic_table']) ? (bool)$questionData['is_analytic_table'] : false),
                        'grid_columns' => $this->normalizeGridColumns($questionData),
                        'min_gaji' => $questionData['min_gaji'] ?? null,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    Log::info('Question created in pass 2', [
                        'question_id' => $question->id,
                        'block_id' => $block->id
                    ]);

                    // Create template answers for radio, checkbox, select types
                    if (in_array($questionData['type'], ['radio', 'checkbox', 'select', 'multiple_choice_grid']) &&
                        isset($questionData['options']) && is_array($questionData['options'])) {

                        foreach ($questionData['options'] as $optionIndex => $option) {
                            if (!empty(trim($option))) {
                                $optionNavigation = null;

                                // Check if navigation array exists and has value for this option
                                if (isset($questionData['option_navigation']) &&
                                    is_array($questionData['option_navigation']) &&
                                    isset($questionData['option_navigation'][$optionIndex])) {

                                    $navValue = trim($questionData['option_navigation'][$optionIndex]);

                                    Log::info('Processing option navigation', [
                                        'option' => $option,
                                        'option_index' => $optionIndex,
                                        'nav_value_raw' => $questionData['option_navigation'][$optionIndex],
                                        'nav_value_trimmed' => $navValue,
                                        'question_id' => $question->id
                                    ]);

                                    if ($navValue !== '') {
                                        $optionNavigation = $this->simpleNavigationResolve($navValue, $createdBlocks);

                                        Log::info('Option navigation resolved', [
                                            'option' => $option,
                                            'nav_input' => $navValue,
                                            'nav_output' => $optionNavigation
                                        ]);
                                    }
                                }

                                \App\Models\TemplateJawaban::create([
                                    'id_template_pertanyaan' => $question->id,
                                    'pilihan_jawaban' => $option,
                                    'urutan' => $optionIndex + 1,
                                    'navigation_target' => $optionNavigation,
                                    'created_at' => now(),
                                    'updated_at' => now()
                                ]);
                            }
                        }
                    }
                }
            }
        }

        // PASS 3: Update target_section_id for blocks that have default navigation
        foreach ($sections as $sectionIndex => $sectionData) {
            $blockNumber = $sectionIndex + 1;
            $block = $createdBlocks[$blockNumber];

            // Determine target section based on navigation_type
            $targetSectionId = null;
            $navigationType = $sectionData['navigation_type'] ?? 'next';

            Log::info('Processing block navigation_type', [
                'block_number' => $blockNumber,
                'block_name' => $block->nama,
                'navigation_type' => $navigationType
            ]);

            if ($navigationType === 'next') {
                // Point to next block if exists
                $nextBlockNumber = $blockNumber + 1;
                if (isset($createdBlocks[$nextBlockNumber])) {
                    $targetSectionId = $createdBlocks[$nextBlockNumber]->id;
                }
            } elseif ($navigationType === 'end') {
                // No target for end type
                $targetSectionId = null;
            } elseif (strpos($navigationType, 'block_') === 0) {
                // Specific block target - extract block number carefully
                $targetBlockNumberStr = substr($navigationType, 6);
                $targetBlockNumber = (int) $targetBlockNumberStr;

                Log::info('Resolving block navigation type', [
                    'navigation_type' => $navigationType,
                    'target_block_str' => $targetBlockNumberStr,
                    'target_block_number' => $targetBlockNumber
                ]);

                // Validate block number is positive and exists
                if ($targetBlockNumber > 0 && isset($createdBlocks[$targetBlockNumber])) {
                    $targetSectionId = $createdBlocks[$targetBlockNumber]->id;

                    Log::info('Block navigation resolved', [
                        'source_block' => $blockNumber,
                        'target_block_number' => $targetBlockNumber,
                        'target_section_id' => $targetSectionId
                    ]);
                } else {
                    Log::error('Target block not found for navigation_type', [
                        'navigation_type' => $navigationType,
                        'target_block_str' => $targetBlockNumberStr,
                        'target_block_number' => $targetBlockNumber,
                        'available_blocks' => array_keys($createdBlocks),
                        'block_exists' => isset($createdBlocks[$targetBlockNumber]),
                        'block_positive' => $targetBlockNumber > 0
                    ]);
                }
            }

            // Update block with target_section_id
            if ($targetSectionId) {
                $block->update(['target_section_id' => $targetSectionId]);
                Log::info('Updated block target_section_id', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'target_section_id' => $targetSectionId
                ]);
            } else {
                Log::info('No target_section_id set for block', [
                    'block_id' => $block->id,
                    'block_number' => $blockNumber,
                    'navigation_type' => $navigationType
                ]);
            }
        }

        Log::info('Form builder content created successfully', [
            'survey_id' => $survey->id,
            'total_blocks' => count($createdBlocks)
        ]);
    }

    private function normalizeGridColumns(array $questionData): ?array
    {
        if (($questionData['type'] ?? '') !== 'multiple_choice_grid') {
            return null;
        }

        $columns = collect($questionData['grid_columns'] ?? [])
            ->map(fn ($column) => is_string($column) ? trim($column) : '')
            ->filter()
            ->values()
            ->all();

        if (count($columns) >= 1) {
            return $columns;
        }

        return ['Sangat Tidak Setuju', 'Tidak Setuju', 'Netral', 'Setuju', 'Sangat Setuju'];
    }

    /**
     * Simple navigation resolver - Clean approach using pre-created blocks
     */
    private function simpleNavigationResolve($navValue, $createdBlocks)
    {
        // Handle special values that don't need resolution
        if (in_array($navValue, ['next', 'end', ''])) {
            return $navValue;
        }

        // Handle block_X format (including block_1)
        if (strpos($navValue, 'block_') === 0) {
            // Extract block number more carefully
            $blockNumberStr = substr($navValue, 6); // Remove 'block_' prefix
            $blockNumber = (int) $blockNumberStr;

            Log::info('Attempting to resolve navigation', [
                'nav_value' => $navValue,
                'block_number_str' => $blockNumberStr,
                'block_number_int' => $blockNumber,
                'available_blocks' => array_keys($createdBlocks)
            ]);

            // Validate block number is positive and exists
            if ($blockNumber > 0 && isset($createdBlocks[$blockNumber])) {
                $targetBlockId = (string) $createdBlocks[$blockNumber]->id;

                Log::info('Navigation resolved successfully', [
                    'nav_value' => $navValue,
                    'block_number' => $blockNumber,
                    'target_block_id' => $targetBlockId,
                    'target_block_name' => $createdBlocks[$blockNumber]->nama
                ]);

                return $targetBlockId;
            } else {
                Log::error('Block number not found or invalid', [
                    'nav_value' => $navValue,
                    'block_number_str' => $blockNumberStr,
                    'block_number_int' => $blockNumber,
                    'available_blocks' => array_keys($createdBlocks),
                    'block_exists' => isset($createdBlocks[$blockNumber]),
                    'block_positive' => $blockNumber > 0
                ]);

                // Return the nav_value as fallback untuk debugging
                return $navValue;
            }
        }

        // Fallback: return original value for unknown formats
        Log::info('Navigation fallback - unknown format', [
            'nav_value' => $navValue
        ]);
        return $navValue;
    }

    /**
     * Reverse navigation transform - Convert block ID back to block_x format for editing
     */
    private function reverseNavigationTransform($navigationTarget, $surveyBlocks)
    {
        // Handle special values that don't need transformation
        if (in_array($navigationTarget, ['next', 'end', '', null])) {
            return $navigationTarget ?? '';
        }

        // If it's a numeric block ID, find the corresponding block and convert to block_x format
        if (is_numeric($navigationTarget)) {
            foreach ($surveyBlocks as $block) {
                if ($block->id == $navigationTarget) {
                    return 'block_' . $block->urutan;
                }
            }
        }

        // Return original value if no transformation needed
        return $navigationTarget;
    }

    /**
     * Update form builder content (blocks and questions) - Two-Pass Approach
     */
    private function updateFormBuilderContent($survey, $sections)
    {
        Log::info('Updating form builder content with two-pass approach', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections)
        ]);

        try {
            if ($this->surveyHasResponses($survey->id)) {
                $this->syncFormBuilderContentPreservingResponses($survey, $sections);
            } else {
                // Step 1: Completely delete existing survey data
                $this->deleteExistingSurveyData($survey->id);

                // Step 2: Create new content using the same two-pass method as create
                $this->createFormBuilderContent($survey, $sections);
            }

            Log::info('Form builder content updated successfully', [
                'survey_id' => $survey->id
            ]);

        } catch (\Exception $e) {
            Log::error('Error in updateFormBuilderContent', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'survey_id' => $survey->id
            ]);
            throw $e;
        }
    }

    private function syncFormBuilderContentPreservingResponses($survey, $sections)
    {
        Log::info('Syncing form builder content with ID matching', [
            'survey_id' => $survey->id,
            'sections_count' => count($sections)
        ]);

        $sections = array_values($sections);
        
        // 1. Get all current block IDs for this survey
        $currentBlockIds = \App\Models\SurveyBlock::where('survey_id', $survey->id)->pluck('id')->toArray();
        $newBlockIdsFromRequest = collect($sections)->pluck('id')->filter()->map(fn($id) => (int)$id)->toArray();
        
        // Delete blocks that are no longer in the request
        $blocksToDelete = array_diff($currentBlockIds, $newBlockIdsFromRequest);
        if (!empty($blocksToDelete)) {
            \App\Models\SurveyBlock::whereIn('id', $blocksToDelete)->delete();
            Log::info('Deleted blocks no longer in survey', ['block_ids' => $blocksToDelete]);
        }

        // Temporarily clear codes of existing blocks to avoid unique constraint violations during reordering/insertion
        // Kode length is 20, so we use a short prefix
        \App\Models\SurveyBlock::withTrashed()->where('survey_id', $survey->id)->update([
            'kode' => DB::raw("CONCAT('T', id)")
        ]);

        $syncedBlocks = [];

        // First pass: Create or update blocks
        foreach ($sections as $sectionIndex => $sectionData) {
            $blockId = isset($sectionData['id']) && !empty($sectionData['id']) ? (int)$sectionData['id'] : null;
            $block = $blockId ? \App\Models\SurveyBlock::find($blockId) : null;

            $isIdentityBlock = (!empty($sectionData['metadata']['is_identity_block'])) || (!empty($block->metadata['is_identity_block'])) || (stripos($sectionData['section_name'] ?? '', 'identitas') !== false);

            $blockData = [
                'survey_id' => $survey->id,
                'kode' => 'BLOCK_' . str_pad(($sectionIndex + 1), 2, '0', STR_PAD_LEFT),
                'nama' => $sectionData['section_name'],
                'deskripsi' => $sectionData['section_description'] ?? '',
                'urutan' => $sectionIndex + 1,
                'navigation_type' => $sectionData['navigation_type'] ?? 'next',
                'is_terminal' => false,
                'target_section_id' => null,
                'metadata' => [
                    'is_kompetensi' => isset($sectionData['is_kompetensi']) && $sectionData['is_kompetensi'] == '1',
                    'pertanyaan_utama' => $sectionData['pertanyaan_utama'] ?? '',
                    'is_identity_block' => $isIdentityBlock,
                ],
                'updated_at' => now(),
            ];

            if ($block) {
                $block->update($blockData);
            } else {
                $block = \App\Models\SurveyBlock::create($blockData + ['created_at' => now()]);
            }

            $syncedBlocks[$sectionIndex] = $block;
        }

        // Second pass: Update navigation and sync questions
        foreach ($sections as $sectionIndex => $sectionData) {
            $block = $syncedBlocks[$sectionIndex] ?? null;
            if (!$block) continue;

            // Update navigation target
            $navigationType = $sectionData['navigation_type'] ?? 'next';
            $targetSectionId = null;

            if ($navigationType === 'next') {
                $targetSectionId = $syncedBlocks[$sectionIndex + 1]->id ?? null;
            } elseif ($navigationType === 'end') {
                $targetSectionId = null;
            } elseif (strpos($navigationType, 'block_') === 0) {
                $targetIndex = (int) substr($navigationType, 6) - 1;
                $targetSectionId = $syncedBlocks[$targetIndex]->id ?? null;
            }

            $block->update([
                'target_section_id' => $targetSectionId,
                'is_terminal' => $navigationType === 'end',
            ]);

            // Sync questions in this block
            $questionsData = array_values($sectionData['questions'] ?? []);
            
            $isKompetensi = isset($block->metadata['is_kompetensi']) && $block->metadata['is_kompetensi'];

            if ($isKompetensi && count($questionsData) > 0) {
                $baseQuestionData = $questionsData[0];
                $indikators = array_filter(array_map('trim', explode("\n", $baseQuestionData['question'])));
                
                $existingQuestions = \App\Models\TemplatePertanyaan::where('block_id', $block->id)->orderBy('urutan')->get()->toArray();
                
                $expandedQuestions = [];
                foreach ($indikators as $idx => $ind) {
                    $qData = $baseQuestionData;
                    $qData['question'] = $ind;
                    
                    $matchedId = null;
                    $matchIndex = -1;
                    
                    // Match by exact text first
                    foreach ($existingQuestions as $eIdx => $eq) {
                        if ($eq && trim($eq['pertanyaan']) === $ind) {
                            $matchedId = $eq['id'];
                            $matchIndex = $eIdx;
                            break;
                        }
                    }
                    
                    // Fallback to index
                    if (!$matchedId && isset($existingQuestions[$idx]) && $existingQuestions[$idx]) {
                        $matchedId = $existingQuestions[$idx]['id'];
                        $matchIndex = $idx;
                    }
                    
                    if ($matchedId) {
                        $qData['id'] = $matchedId;
                        $existingQuestions[$matchIndex] = null; // Mark as used
                    } else {
                        unset($qData['id']); // New indicator
                    }
                    
                    $expandedQuestions[] = $qData;
                }
                $questionsData = $expandedQuestions;
            }

            // Get current question IDs in this block
            $currentQuestionIds = \App\Models\TemplatePertanyaan::where('block_id', $block->id)->pluck('id')->toArray();
            $newQuestionIdsFromRequest = collect($questionsData)->pluck('id')->filter()->map(fn($id) => (int)$id)->toArray();
            
            // Delete questions no longer in this block
            $questionsToDelete = array_diff($currentQuestionIds, $newQuestionIdsFromRequest);
            if (!empty($questionsToDelete)) {
                \App\Models\TemplatePertanyaan::whereIn('id', $questionsToDelete)->delete();
                Log::info('Deleted questions no longer in block', ['question_ids' => $questionsToDelete, 'block_id' => $block->id]);
            }

            foreach ($questionsData as $questionIndex => $questionData) {
                $qId = isset($questionData['id']) && !empty($questionData['id']) ? (int)$questionData['id'] : null;
                $question = $qId ? \App\Models\TemplatePertanyaan::find($qId) : null;

                $questionPayload = [
                    'id_survey' => $survey->id,
                    'block_id' => $block->id,
                    'pertanyaan' => $questionData['question'],
                    'deskripsi_pertanyaan' => $questionData['description'] ?? '',
                    'tipe' => $questionData['type'],
                    'urutan' => $questionIndex + 1,
                    'is_required' => isset($questionData['required']) ? (bool) $questionData['required'] : false,
                    'visualisasi' => $this->normalizeVisualizationValue($questionData['visualization'] ?? null),
                    'is_analytic_table' => $isKompetensi ? true : (isset($questionData['is_analytic_table']) ? (bool)$questionData['is_analytic_table'] : false),
                    'grid_columns' => $this->normalizeGridColumns($questionData),
                    'min_gaji' => $questionData['min_gaji'] ?? null,
                    'updated_at' => now(),
                ];

                if ($question) {
                    $question->update($questionPayload);
                } else {
                    $question = \App\Models\TemplatePertanyaan::create($questionPayload + ['created_at' => now()]);
                }

                // Sync template answers for choice types
                if (in_array($questionData['type'], ['radio', 'checkbox', 'select', 'multiple_choice_grid']) &&
                    isset($questionData['options']) && is_array($questionData['options'])) {
                    
                    $newOptions = array_filter(array_map('trim', $questionData['options']));
                    $existingOptions = \App\Models\TemplateJawaban::where('id_template_pertanyaan', $question->id)->get();
                    
                    // Delete options that are no longer present
                    foreach ($existingOptions as $existingOpt) {
                        if (!in_array($existingOpt->pilihan_jawaban, $newOptions)) {
                            $existingOpt->delete();
                        }
                    }
                    
                    // Add or update options
                    foreach ($newOptions as $optionIndex => $optionText) {
                        \App\Models\TemplateJawaban::updateOrCreate(
                            [
                                'id_template_pertanyaan' => $question->id,
                                'pilihan_jawaban' => $optionText
                            ],
                            [
                                'urutan' => $optionIndex + 1,
                                'updated_at' => now()
                            ]
                        );
                    }
                } else {
                    // Not a choice type anymore, delete all template answers
                    \App\Models\TemplateJawaban::where('id_template_pertanyaan', $question->id)->delete();
                }
            }
        }

        Log::info('Preserved response data while syncing form builder content using ID matching', [
            'survey_id' => $survey->id,
            'blocks_synced' => count($syncedBlocks)
        ]);
    }

    private function surveyHasResponses(int $surveyId): bool
    {
        // Check if there are any answers
        $hasAnswers = \App\Models\SurveyUserJawaban::query()
            ->join('survey_user', 'survey_user.id', '=', 'survey_user_jawaban.survey_user_id')
            ->where('survey_user.survey_id', $surveyId)
            ->exists();

        if ($hasAnswers) return true;

        // Also check if any user has started but not answered yet
        return \App\Models\SurveyUser::where('survey_id', $surveyId)->exists();
    }

    /**
     * Delete all existing survey data (blocks, questions, answers)
     */
    private function deleteExistingSurveyData($surveyId)
    {
        Log::info('Deleting existing survey data', ['survey_id' => $surveyId]);

        try {
            // Disable foreign key checks for clean deletion
            Schema::disableForeignKeyConstraints();

            // Delete in correct order: answers -> questions -> blocks
            $deletedAnswers = DB::table('template_jawaban')
                ->whereIn('id_template_pertanyaan', function ($query) use ($surveyId) {
                    $query->select('id')
                          ->from('template_pertanyaan')
                          ->where('id_survey', $surveyId);
                })->delete();

            $deletedQuestions = DB::table('template_pertanyaan')
                ->where('id_survey', $surveyId)
                ->delete();

            $deletedBlocks = DB::table('survey_blocks')
                ->where('survey_id', $surveyId)
                ->delete();

            // Re-enable foreign key checks
            Schema::enableForeignKeyConstraints();

            Log::info('Successfully deleted existing survey data', [
                'survey_id' => $surveyId,
                'deleted_answers' => $deletedAnswers,
                'deleted_questions' => $deletedQuestions,
                'deleted_blocks' => $deletedBlocks
            ]);

        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();
            Log::error('Failed to delete existing survey data', [
                'survey_id' => $surveyId,
                'error' => $e->getMessage()
            ]);
            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(survey $survey)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        try {
            $survey = Survey::findOrFail($id);
            \App\Services\RespondentAttributeService::ensureIdentityBlock($survey);
            $survey->type_survei = $this->normalizeSurveyTypeInput($survey->type_survei);
            $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("Y-m-d");
            $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("Y-m-d");

            // Load existing survey blocks with their questions
            $surveyBlocks = \App\Models\SurveyBlock::with([
                'questions.templateJawaban' => function ($query) {
                    $query->orderBy('urutan');
                }
            ])->where('survey_id', $id)->orderBy('urutan')->get();

            // Transform blocks data for the form builder
            $formBuilderData = [];
            if ($surveyBlocks->count() > 0) {
                foreach ($surveyBlocks as $index => $block) {
                    $formBuilderData[$index + 1] = [
                        'id' => $block->id,
                        'section_name' => $block->nama,
                        'section_description' => $block->deskripsi ?? '',
                        'navigation_type' => $block->navigation_type ?? 'next',
                        'metadata' => $block->metadata,
                        'questions' => []
                    ];

                    $isKompetensi = isset($block->metadata['is_kompetensi']) && $block->metadata['is_kompetensi'];

                    if ($isKompetensi && $block->questions->count() > 0) {
                        $firstQuestion = $block->questions->first();
                        $indikators = $block->questions->pluck('pertanyaan')->implode("\n");
                        
                        $questionData = [
                            'id' => $firstQuestion->id,
                            'question' => $indikators,
                            'description' => '',
                            'type' => $firstQuestion->tipe,
                            'required' => $firstQuestion->is_required ? '1' : '0',
                            'visualization' => $this->normalizeVisualizationValueForForm($firstQuestion->visualisasi),
                            'is_analytic_table' => '1',
                            'grid_columns' => $firstQuestion->grid_columns ?? [],
                            'min_gaji' => $firstQuestion->min_gaji,
                        ];
                        
                        $questionData['options'] = [];
                        $questionData['option_navigation'] = [];
                        foreach ($firstQuestion->templateJawaban as $answer) {
                            $questionData['options'][] = $answer->pilihan_jawaban;
                            $navigationValue = $this->reverseNavigationTransform($answer->navigation_target, $surveyBlocks);
                            $questionData['option_navigation'][] = $navigationValue;
                        }
                        
                        $formBuilderData[$index + 1]['questions'] = [1 => $questionData];
                        $formBuilderData[$index + 1]['pertanyaan_utama'] = $block->metadata['pertanyaan_utama'] ?? '';
                    } else {
                        foreach ($block->questions as $qIndex => $question) {
                            $questionData = [
                                'id' => $question->id,
                                'question' => $question->pertanyaan,
                                'description' => $question->deskripsi_pertanyaan ?? '',
                                'type' => $question->tipe,
                                'required' => $question->is_required ? '1' : '0',
                                'visualization' => $this->normalizeVisualizationValueForForm($question->visualisasi),
                                'is_analytic_table' => $question->is_analytic_table ? '1' : '0',
                                'grid_columns' => $question->grid_columns ?? [],
                                'min_gaji' => $question->min_gaji,
                            ];
    
                            if (in_array($question->tipe, ['radio', 'checkbox', 'select', 'multiple_choice_grid'])) {
                                $questionData['options'] = [];
                                $questionData['option_navigation'] = [];
                                foreach ($question->templateJawaban as $answer) {
                                    $questionData['options'][] = $answer->pilihan_jawaban;
                                    // Transform block ID back to block_x format for editing
                                    $navigationValue = $this->reverseNavigationTransform($answer->navigation_target, $surveyBlocks);
                                    $questionData['option_navigation'][] = $navigationValue;
                                }
                            }
    
                            $formBuilderData[$index + 1]['questions'][$qIndex + 1] = $questionData;
                        }
                    }
                }
            }

            return view('admin.views.survey.edit', [
                'survey' => $survey,
                'formBuilderData' => $formBuilderData
            ]);

        } catch (\Exception $e) {
            Log::error('Error loading survey edit form', ['error' => $e->getMessage()]);
            return redirect()->route('admin.survey.index')->with('error', 'Gagal memuat form edit survey');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'nama' => 'required|string|max:255',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after:tanggal_mulai',
            'type_survei' => 'required|in:lulusan,penggunaLulusan,pengguna_lulusan',
            'deskripsi' => 'nullable|string',
            'sections' => 'nullable|array',
            'sections.*.id' => 'nullable|integer',
            'sections.*.section_name' => 'required_with:sections|string|max:255',
            'sections.*.section_description' => 'nullable|string|max:500',
            'sections.*.navigation_type' => 'nullable|string',
            'sections.*.is_kompetensi' => 'nullable|boolean',
            'sections.*.pertanyaan_utama' => 'nullable|string|max:500',
            'sections.*.questions' => 'required_with:sections|array|min:1',
            'sections.*.questions.*.id' => 'nullable|integer',
            'sections.*.questions.*.question' => 'required|string|max:500',
            'sections.*.questions.*.description' => 'nullable|string|max:500',
            'sections.*.questions.*.type' => 'required|in:text,textarea,radio,checkbox,select,multiple_choice_grid,file,date,gaji',
            'sections.*.questions.*.required' => 'boolean',
            'sections.*.questions.*.visualization' => 'nullable|in:bar,pie',
            'sections.*.questions.*.min_gaji' => 'nullable|numeric|min:0',
            'sections.*.questions.*.is_analytic_table' => 'nullable|boolean',
            'sections.*.questions.*.options' => 'nullable|array',
            'sections.*.questions.*.options.*' => 'string|max:255',
            'sections.*.questions.*.grid_columns' => 'nullable|array|max:10',
            'sections.*.questions.*.grid_columns.*' => 'string|max:100',
            'sections.*.questions.*.option_navigation' => 'nullable|array',
            'sections.*.questions.*.option_navigation.*' => 'string|max:255',
        ]);

        try {
            $this->validateCompetencyBlocks($validatedData['sections'] ?? []);
            DB::beginTransaction();

            $survey = Survey::findOrFail($id);

            // Update basic survey info
            $survey->update([
                'nama' => $validatedData['nama'],
                'tanggal_mulai' => $validatedData['tanggal_mulai'],
                'tanggal_selesai' => $validatedData['tanggal_selesai'],
                'type_survei' => $this->normalizeSurveyTypeInput($validatedData['type_survei']),
                'deskripsi' => $validatedData['deskripsi'] ?? null,
            ]);

            // If sections are provided, update form builder content
            if (isset($validatedData['sections']) && is_array($validatedData['sections'])) {
                $this->updateFormBuilderContent($survey, $validatedData['sections']);
            }

            // Always ensure Block 1 is the identity block populated with respondent profile attributes
            \App\Services\RespondentAttributeService::ensureIdentityBlock($survey);

            DB::commit();

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Survey berhasil diupdate!',
                    'survey_id' => $survey->id
                ], 200);
            }

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil diupdate!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating survey with form builder', [
                'error' => $e->getMessage(),
                'survey_id' => $id
            ]);

            // Return JSON response for AJAX requests
            if ($request->ajax() || $request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage()
                ], 422);
            }

            return back()->withErrors(['error' => 'Terjadi kesalahan saat mengupdate survey: ' . $e->getMessage()])->withInput();
        }
    }

    /**
     * Display survey details
     */
    public function details($id)
    {
        $survey = Survey::findOrFail($id);
        $survey_user = SurveyUser::getUser($id);

        $surveyBlocks = \App\Models\SurveyBlock::with([
            'questions.templateJawaban' => function ($query) {
                $query->orderBy('urutan');
            }
        ])->where('survey_id', $id)->orderBy('urutan')->get();

        // Format dates and set status like in index method
        $survey->tanggal_mulai = Carbon::parse($survey->tanggal_mulai)->format("d-m-Y");
        $survey->tanggal_selesai = Carbon::parse($survey->tanggal_selesai)->format("d-m-Y");
        
        // Set status based on end date comparison (same logic as index method)
        if (Carbon::parse($survey->tanggal_selesai) >= now()) {
            $survey->status = "Aktif";
        } else {
            $survey->status = "Selesai";
        }

        return view('admin.views.survey.details', [
            'survey' => $survey,
            'survey_user' => $survey_user,
            'surveyBlocks' => $surveyBlocks
        ]);
    }

    /**
     * Duplicate the specified survey
     */
    public function duplicate($id)
    {
        try {
            DB::beginTransaction();

            // Find the original survey
            $originalSurvey = Survey::findOrFail($id);

            // Create new survey with duplicated data
            $newSurvey = Survey::create([
                'nama' => $originalSurvey->nama . ' (Copy)',
                'tanggal_mulai' => $originalSurvey->tanggal_mulai,
                'tanggal_selesai' => $originalSurvey->tanggal_selesai,
                'type_survei' => $originalSurvey->type_survei,
                'deskripsi' => $originalSurvey->deskripsi,
                'created_by' => Auth::id(),
            ]);

            // Get original survey blocks with questions and answers
            $originalBlocks = \App\Models\SurveyBlock::with([
                'questions.templateJawaban'
            ])->where('survey_id', $id)->orderBy('urutan')->get();

            // Duplicate blocks, questions, and answers
            $blockMapping = []; // To map original block IDs to new block IDs
            
            foreach ($originalBlocks as $originalBlock) {
                // Create new block
                $newBlock = \App\Models\SurveyBlock::create([
                    'survey_id' => $newSurvey->id,
                    'kode' => $originalBlock->kode,
                    'nama' => $originalBlock->nama,
                    'deskripsi' => $originalBlock->deskripsi,
                    'urutan' => $originalBlock->urutan,
                    'navigation_type' => $originalBlock->navigation_type,
                    'is_terminal' => $originalBlock->is_terminal,
                    'target_section_id' => null, // Will be updated later
                    'created_at' => now(),
                    'updated_at' => now()
                ]);

                // Store mapping for later reference
                $blockMapping[$originalBlock->id] = $newBlock->id;

                // Duplicate questions for this block
                foreach ($originalBlock->questions as $originalQuestion) {
                    $newQuestion = \App\Models\TemplatePertanyaan::create([
                        'id_survey' => $newSurvey->id,
                        'block_id' => $newBlock->id,
                        'pertanyaan' => $originalQuestion->pertanyaan,
                        'deskripsi_pertanyaan' => $originalQuestion->deskripsi_pertanyaan,
                        'tipe' => $originalQuestion->tipe,
                        'urutan' => $originalQuestion->urutan,
                        'is_required' => $originalQuestion->is_required,
                        'visualisasi' => $this->normalizeVisualizationValue($originalQuestion->visualisasi),
                        'is_analytic_table' => $originalQuestion->is_analytic_table,
                        'grid_columns' => $originalQuestion->grid_columns,
                        'min_gaji' => $originalQuestion->min_gaji,
                        'created_at' => now(),
                        'updated_at' => now()
                    ]);

                    // Duplicate template answers for this question
                    foreach ($originalQuestion->templateJawaban as $originalAnswer) {
                        // Handle navigation target mapping
                        $navigationTarget = $originalAnswer->navigation_target;
                        if (is_numeric($navigationTarget) && isset($blockMapping[$navigationTarget])) {
                            $navigationTarget = $blockMapping[$navigationTarget];
                        }

                        \App\Models\TemplateJawaban::create([
                            'id_template_pertanyaan' => $newQuestion->id,
                            'pilihan_jawaban' => $originalAnswer->pilihan_jawaban,
                            'urutan' => $originalAnswer->urutan,
                            'navigation_target' => $navigationTarget,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);
                    }
                }
            }

            // Update target_section_id for blocks that reference other blocks
            foreach ($originalBlocks as $originalBlock) {
                if ($originalBlock->target_section_id && isset($blockMapping[$originalBlock->target_section_id])) {
                    $newBlockId = $blockMapping[$originalBlock->id];
                    $newTargetId = $blockMapping[$originalBlock->target_section_id];
                    
                    \App\Models\SurveyBlock::where('id', $newBlockId)
                        ->update(['target_section_id' => $newTargetId]);
                }
            }

            DB::commit();

            return redirect()->route('admin.survey.index')->with('success', 'Survey berhasil diduplikasi!');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error duplicating survey', [
                'survey_id' => $id,
                'error' => $e->getMessage(),
                'user_id' => Auth::id()
            ]);

            return redirect()->route('admin.survey.index')->with('error', 'Gagal menduplikasi survey: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(survey $survey)
    {
        try {
            DB::beginTransaction();

            // Delete all related data in the correct order to avoid foreign key constraint violations
            $surveyId = $survey->id;

            Log::info('Starting survey deletion process', ['survey_id' => $surveyId]);

            // 1. Delete survey user responses (survey_user_jawaban table)
            $deletedJawaban = DB::table('survey_user_jawaban')
                ->whereIn('survey_user_id', function($query) use ($surveyId) {
                    $query->select('id')
                          ->from('survey_user')
                          ->where('survey_id', $surveyId);
                })
                ->delete();

            // 2. Delete survey users
            $deletedSurveyUsers = DB::table('survey_user')
                ->where('survey_id', $surveyId)
                ->delete();

            // 3. Delete template answers (template_jawaban)
            $deletedTemplateJawaban = DB::table('template_jawaban')
                ->whereIn('id_template_pertanyaan', function($query) use ($surveyId) {
                    $query->select('id')
                          ->from('template_pertanyaan')
                          ->where('id_survey', $surveyId);
                })
                ->delete();

            // 4. Delete template questions (template_pertanyaan)
            $deletedTemplatePertanyaan = DB::table('template_pertanyaan')
                ->where('id_survey', $surveyId)
                ->delete();

            // 5. Delete survey blocks
            $deletedSurveyBlocks = DB::table('survey_blocks')
                ->where('survey_id', $surveyId)
                ->delete();

            // 6. Finally delete the survey itself
            $survey->delete();

            DB::commit();

            Log::info('Survey deleted successfully', [
                'survey_id' => $surveyId,
                'deleted_survey_user_jawaban' => $deletedJawaban,
                'deleted_survey_users' => $deletedSurveyUsers,
                'deleted_template_jawaban' => $deletedTemplateJawaban,
                'deleted_template_pertanyaan' => $deletedTemplatePertanyaan,
                'deleted_survey_blocks' => $deletedSurveyBlocks
            ]);

            return redirect()->route('admin.survey.index')->with('success', 'Survey beserta seluruh data terkait berhasil dihapus.');

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error deleting survey', [
                'survey_id' => $survey->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->route('admin.survey.index')->with('error', 'Gagal menghapus survey: ' . $e->getMessage());
        }
    }

    private function normalizeSurveyTypeInput(?string $surveyType): string
    {
        $normalized = strtolower(trim((string) $surveyType));

        if ($normalized === 'pengguna_lulusan' || $normalized === 'penggunalulusan') {
            return 'penggunaLulusan';
        }

        if ($normalized === 'lulusan') {
            return 'lulusan';
        }

        return 'penggunaLulusan';
    }

    private function normalizeVisualizationValue($value): ?string
    {
        $normalized = strtolower(trim((string) $value));

        return in_array($normalized, ['bar', 'pie'], true) ? $normalized : null;
    }

    private function normalizeVisualizationValueForForm($value): string
    {
        return $this->normalizeVisualizationValue($value) ?? '';
    }

    private function validateCompetencyBlocks(array $sections): void
    {
        $errors = [];
        $allowedTypes = ['radio', 'checkbox', 'select', 'multiple_choice_grid'];

        foreach ($sections as $sectionIndex => $sectionData) {
            $isKompetensi = !empty($sectionData['is_kompetensi']) && $sectionData['is_kompetensi'] !== '0';
            if (!$isKompetensi) {
                continue;
            }

            $sectionLabel = 'Block ' . ($sectionIndex + 1);
            $mainQuestion = trim((string) ($sectionData['pertanyaan_utama'] ?? ''));
            if ($mainQuestion === '') {
                $errors['sections.' . $sectionIndex . '.pertanyaan_utama'] = 'Pertanyaan utama pada ' . $sectionLabel . ' wajib diisi.';
            }

            $questions = array_values($sectionData['questions'] ?? []);
            if (empty($questions)) {
                $errors['sections.' . $sectionIndex . '.questions'] = $sectionLabel . ' harus memiliki minimal 1 pertanyaan indikator.';
                continue;
            }

            $referenceType = null;
            $referenceOptions = null;
            $referenceGridColumns = null;

            foreach ($questions as $questionIndex => $questionData) {
                $questionLabel = $sectionLabel . ', pertanyaan ' . ($questionIndex + 1);
                $type = $questionData['type'] ?? '';

                if (!in_array($type, $allowedTypes, true)) {
                    $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.type'] = $questionLabel . ' hanya boleh menggunakan tipe Radio Button, Checkbox, Dropdown, atau Multiple Choice Grid.';
                    continue;
                }

                $indicatorLabel = trim((string) ($questionData['question'] ?? ''));
                if ($indicatorLabel === '') {
                    $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.question'] = $questionLabel . ' wajib memiliki label indikator.';
                }

                $options = $this->normalizeSubmittedValues($questionData['options'] ?? []);
                $gridColumns = $this->normalizeSubmittedValues($questionData['grid_columns'] ?? []);

                if ($referenceType === null) {
                    $referenceType = $type;
                    $referenceOptions = $options;
                    $referenceGridColumns = $gridColumns;
                } else {
                    if ($type !== $referenceType) {
                        $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.type'] = $questionLabel . ' harus menggunakan tipe yang sama dengan pertanyaan indikator pertama pada blok ini.';
                    }

                    if ($options !== $referenceOptions) {
                        $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.options'] = $questionLabel . ' harus menggunakan template pilihan jawaban yang sama dengan pertanyaan indikator pertama pada blok ini.';
                    }

                    if ($type === 'multiple_choice_grid' && $gridColumns !== $referenceGridColumns) {
                        $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.grid_columns'] = $questionLabel . ' harus menggunakan kolom grid yang sama dengan pertanyaan indikator pertama pada blok ini.';
                    }
                }

                if (in_array($type, ['radio', 'checkbox', 'select'], true) && count($options) < 2) {
                    $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.options'] = $questionLabel . ' harus memiliki minimal 2 pilihan jawaban.';
                }

                if ($type === 'multiple_choice_grid' && count($gridColumns) < 1) {
                    $errors['sections.' . $sectionIndex . '.questions.' . $questionIndex . '.grid_columns'] = $questionLabel . ' harus memiliki minimal 1 kolom grid.';
                }
            }
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function normalizeSubmittedValues($values): array
    {
        return collect(is_array($values) ? $values : [])
            ->map(function ($value) {
                return is_string($value) ? trim($value) : trim((string) $value);
            })
            ->filter(fn ($value) => $value !== '')
            ->values()
            ->all();
    }
}

