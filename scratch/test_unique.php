<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$survey = \App\Models\Survey::find(4);

// Soft delete a block to simulate the issue
$blockToTrash = \App\Models\SurveyBlock::create([
    'survey_id' => 4,
    'kode' => 'BLOCK_02',
    'nama' => 'Trash Block',
    'urutan' => 2,
]);
$blockToTrash->delete();

// We need an active block that will be shifted
$activeBlock = \App\Models\SurveyBlock::create([
    'survey_id' => 4,
    'kode' => 'BLOCK_01',
    'nama' => 'Active Block',
    'urutan' => 1,
]);

// Now try the old logic (should fail)
try {
    $allBlocks = \App\Models\SurveyBlock::where('survey_id', 4)->orderBy('urutan', 'desc')->get();
    foreach ($allBlocks as $blk) {
        $blk->update([
            'urutan' => $blk->urutan + 1,
            'kode' => 'BLOCK_' . str_pad($blk->urutan + 1, 2, '0', STR_PAD_LEFT)
        ]);
    }
    echo "Old logic succeeded unexpectedly.\n";
} catch (\Exception $e) {
    echo "Old logic failed as expected: " . $e->getMessage() . "\n";
}

// Clean up
$blockToTrash->forceDelete();
$activeBlock->forceDelete();
