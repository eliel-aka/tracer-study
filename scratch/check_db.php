<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$blocks = \App\Models\SurveyBlock::where('survey_id', 1)->orderBy('urutan')->get();
echo "Active blocks for survey 1:\n";
foreach ($blocks as $block) {
    echo "ID: {$block->id} | Urutan: {$block->urutan} | Kode: {$block->kode} | Nama: {$block->nama}\n";
}

$trashed = \App\Models\SurveyBlock::onlyTrashed()->where('survey_id', 1)->orderBy('urutan')->get();
echo "\nTrashed blocks for survey 1:\n";
foreach ($trashed as $block) {
    echo "ID: {$block->id} | Urutan: {$block->urutan} | Kode: {$block->kode} | Nama: {$block->nama}\n";
}
