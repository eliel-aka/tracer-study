<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $controller = new \App\Http\Controllers\SurveyController();
    $controller->edit(4);
    echo "Success!";
} catch (\Exception $e) {
    echo $e->getMessage() . " on line " . $e->getLine();
}
