<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    Maatwebsite\Excel\Facades\Excel::import(new App\Imports\PenggunaLulusanImport(), 'Template_Pengguna_Lulusan_Siap_Import_Final_v2.xlsx');
    echo 'Success';
} catch (\Exception $e) {
    echo $e->getMessage();
}
