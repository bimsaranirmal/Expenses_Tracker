<?php

use App\Services\AnomalyService;
use App\Models\Income;
use Carbon\Carbon;

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = 3;
$now = Carbon::now();

echo "Running verification for User ID: $userId (Month: {$now->format('F Y')})\n";

$anomalyService = new AnomalyService();

// 1. Test Anomaly Count (Filtered by Current Month)
$from = $now->copy()->startOfMonth()->toDateString();
$to = $now->copy()->endOfMonth()->toDateString();
$anomalies = $anomalyService->detectIncomeAnomalies($userId, $from, $to);
$count = count($anomalies);

echo "Total ANOMALIES in current month: " . $count . "\n";

// 2. Check if specific small salary entries are flagged
$smallSalaries = Income::where('user_id', $userId)
    ->where('amount', '<=', 1200)
    ->whereMonth('date', $now->month)
    ->get();

$allGood = true;
foreach ($smallSalaries as $income) {
    if (isset($anomalies[$income->id])) {
        echo "🚩 STILL FLAGGED: ID {$income->id} | Amount: {$income->amount} | Category: {$income->category_id}\n";
        $allGood = false;
    } else {
        echo "✅ NOT FLAGGED: ID {$income->id} | Amount: {$income->amount}\n";
    }
}

if ($allGood) {
    echo "\nSUCCESS: Small salary entries are no longer being falsely flagged!\n";
} else {
    echo "\nFAILURE: Some small entries are still being flagged.\n";
}

// 3. Verify the huge one is STILL flagged
$hugeOne = Income::where('user_id', $userId)->where('amount', '>=', 1000000)->first();
if ($hugeOne) {
    if (isset($anomalies[$hugeOne->id])) {
        echo "✅ SUCCESS: The huge entry ({$hugeOne->amount}) is still correctly flagged.\n";
    } else {
        echo "🚩 FAILURE: The huge entry ({$hugeOne->amount}) missed detection!\n";
    }
}
