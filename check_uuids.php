<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$tables = \Illuminate\Support\Facades\DB::select('SHOW TABLES');
foreach($tables as $t) {
    $table = array_values((array)$t)[0];
    $cols = \Illuminate\Support\Facades\DB::select("SHOW COLUMNS FROM {$table}");
    foreach($cols as $c) {
        if($c->Field == 'id' && strpos($c->Type, 'varchar') !== false) {
            echo "Table: " . $table . PHP_EOL;
        }
    }
}
