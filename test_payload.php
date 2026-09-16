<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\ = \->make(Illuminate\Contracts\Console\Kernel::class);
\->bootstrap();

\ = new \App\Http\Controllers\DashboardController();
\ = \App\Models\Survey::find(10); 
\ = new ReflectionClass(\);
\ = \->getMethod('buildChartsPayload');
\->setAccessible(true);
\ = \->invokeArgs(\, [\, 'prodi', null, true]);
echo "TYPE: " . (\['charts']['114']['type'] ?? 'NOT FOUND') . "\n";
echo "TITLE: " . (\['charts']['114']['questionText'] ?? 'NOT FOUND') . "\n";
