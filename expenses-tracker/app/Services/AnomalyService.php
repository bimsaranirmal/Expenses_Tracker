<?php

namespace App\Services;

use App\Models\Expense;
use Illuminate\Support\Facades\DB;

class AnomalyService
{
    /**
     * Detect anomalies in expenses using Z-Score analysis.
     *
     * @param int $userId
     * @return array List of anomaly expense IDs and reasons
     */
    public function detectAnomalies($userId, $from = null, $to = null)
    {
        return $this->runAIDetection($userId, 'expense', $from, $to);
    }

    public function detectIncomeAnomalies($userId, $from = null, $to = null)
    {
        return $this->runAIDetection($userId, 'income', $from, $to);
    }

    private function runAIDetection($userId, $mode, $from = null, $to = null)
    {
        set_time_limit(120); // Allow extra time for Python DL model loading
        
        // 1. Fetch records based on mode
        if ($mode === 'expense') {
            $query = Expense::where('expenses.user_id', $userId)
                ->join('categories', 'expenses.category_id', '=', 'categories.id')
                ->select('expenses.*', 'categories.name as category_name');
        } else {
            $query = \App\Models\Income::where('incomes.user_id', $userId)
                ->join('income_categories', 'incomes.category_id', '=', 'income_categories.id')
                ->select('incomes.*', 'income_categories.name as category_name');
        }
        if ($from && $to) {
            $indexTable = $mode === 'expense' ? 'expenses' : 'incomes';
            $query->whereBetween("$indexTable.date", [$from, $to]);
        }

        $records = $query->get();

        if ($records->isEmpty()) {
            return [];
        }

        $payload = $records->map(function($r) {
            return [
                'id' => $r->id,
                'category_name' => $r->category_name,
                'amount' => (float)$r->amount
            ];
        })->values()->toArray();

        // 2. Call Python AI Script via STDIN (Fixes Windows shell escaping issues)
        $pythonExec = 'c:\\Users\\BIMSARA\\anaconda3\\python.exe';
        $pythonScript = base_path('python/detect_anomaly.py');
        $command = escapeshellarg($pythonExec) . " " . escapeshellarg($pythonScript) . " $mode"; // Mode passed as arg
        
        $descriptorspec = [
            0 => ["pipe", "r"], // stdin
            1 => ["pipe", "w"], // stdout
            2 => ["pipe", "w"]  // stderr
        ];

        $process = proc_open($command, $descriptorspec, $pipes);
        $aiAnomalies = [];

        if (is_resource($process)) {
            fwrite($pipes[0], json_encode($payload));
            fclose($pipes[0]);

            $output = stream_get_contents($pipes[1]);
            fclose($pipes[1]);
            
            $stderr = stream_get_contents($pipes[2]);
            fclose($pipes[2]);

            proc_close($process);
            $aiAnomalies = json_decode($output, true);
        }

        if (is_array($aiAnomalies) && !isset($aiAnomalies['error'])) {
            // Final Polish: For Income, don't flag "small" amounts as unusual
            // even if the AI thinks they are statistically isolated.
            if ($mode === 'income') {
                foreach ($aiAnomalies as $id => $data) {
                    $record = \App\Models\Income::find($id);
                    if ($record && $record->amount < 4000) {
                        unset($aiAnomalies[$id]);
                    }
                }
            }
            return $aiAnomalies;
        }

        // Return empty if AI fails
        return [];
    }
}
