<?php

use App\Models\Expense;
use App\Services\AnomalyService;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = 3;

$expenses = Expense::where('expenses.user_id', $userId)
    ->join('categories', 'expenses.category_id', '=', 'categories.id')
    ->select('expenses.*', 'categories.name as category_name')
    ->get();

$payload = $expenses->map(function($e) {
    return [
        'id' => $e->id,
        'category_name' => $e->category_name,
        'amount' => (float)$e->amount
    ];
})->values()->toArray();

$jsonStr = json_encode($payload);
echo "PAYLOAD (JSON):\n" . $jsonStr . "\n\n";

$pythonPath = base_path('python/detect_anomaly.py');
$command = "python \"$pythonPath\"";

$descriptorspec = [
    0 => ["pipe", "r"], // stdin
    1 => ["pipe", "w"], // stdout
    2 => ["pipe", "w"]  // stderr
];

echo "EXECUTING COMMAND (via STDIN)...\n";
$process = proc_open($command, $descriptorspec, $pipes);

if (is_resource($process)) {
    fwrite($pipes[0], $jsonStr);
    fclose($pipes[0]);

    $output = stream_get_contents($pipes[1]);
    fclose($pipes[1]);
    
    $stderr = stream_get_contents($pipes[2]);
    fclose($pipes[2]);

    proc_close($process);

    echo "RAW OUTPUT:\n" . $output . "\n\n";
    if ($stderr) echo "STDERR:\n" . $stderr . "\n\n";

    $decoded = json_decode($output, true);
    echo "DECODED:\n";
    print_r($decoded);
} else {
    echo "FAILED TO OPEN PROCESS\n";
}
