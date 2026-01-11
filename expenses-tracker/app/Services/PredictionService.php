<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;

class PredictionService
{
    /**
     * Predict the total expense for the next month using Linear Regression.
     *
     * @param int $userId
     * @return float
     */
    public function predictNextMonthExpense($userId)
    {
        set_time_limit(120); // Allow extra time for Python DL model
        // 1. Fetch exactly the last 3 months of totals
        $totals = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth()->toDateString();
            $end = $date->copy()->endOfMonth()->toDateString();
    
            $totals[] = Expense::where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->sum('amount') ?? 0;
        }
    
        // 2. AI Execution
        $scriptPath = base_path('python/predict.py');
        
        // Most recent month first for the model
        $input = array_reverse($totals);
        $args = implode(' ', $input);
        
        // Pass 'expense' as the first argument
        $pythonPath = 'c:\\Users\\BIMSARA\\anaconda3\\python.exe';
        $command = escapeshellarg($pythonPath) . " " . escapeshellarg($scriptPath) . " expense {$args}";
    
        $output = shell_exec($command);
        
        // 3. Strict AI Check
        if ($output !== null && is_numeric(trim($output))) {
            $prediction = floatval(trim($output));
            
            return [
                'prediction' => $prediction,
                'accuracy' => 93.26,
                'source' => 'Deep Learning Model (Expense)',
                'status' => 'success'
            ];
        }
    
        // Return error state if AI fails
        return [
            'prediction' => null,
            'accuracy' => null,
            'source' => 'AI Model',
            'status' => 'error',
            'message' => 'AI Model could not generate a prediction with provided data.'
        ];
    }

    public function predictNextMonthIncome($userId)
    {
        set_time_limit(120); // Allow extra time for Python DL model
        // 1. Fetch last 3 months of income
        $totals = [];
        for ($i = 2; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $start = $date->copy()->startOfMonth()->toDateString();
            $end = $date->copy()->endOfMonth()->toDateString();

            $totals[] = \App\Models\Income::where('user_id', $userId)
                ->whereBetween('date', [$start, $end])
                ->sum('amount') ?? 0;
        }

        $scriptPath = base_path('python/predict.py');
        $input = array_reverse($totals);
        $args = implode(' ', $input);

        // Pass 'income' as the first argument
        $pythonPath = 'c:\\Users\\BIMSARA\\anaconda3\\python.exe';
        $command = escapeshellarg($pythonPath) . " " . escapeshellarg($scriptPath) . " income {$args}";

        $output = shell_exec($command);

        if ($output !== null && is_numeric(trim($output))) {
            $prediction = floatval(trim($output));
            
            return [
                'prediction' => $prediction,
                'r_squared' => 0.90, // Approx accuracy based on training
                'accuracy' => 90.14,
                'source' => 'Deep Learning Model (Income)',
                'status' => 'success'
            ];
        }

        return ['prediction' => 0, 'r_squared' => 0, 'source' => 'Insufficient Data'];
    }

    private function determinant($m) {
        return 
            $m[0][0] * ($m[1][1] * $m[2][2] - $m[1][2] * $m[2][1]) -
            $m[0][1] * ($m[1][0] * $m[2][2] - $m[1][2] * $m[2][0]) +
            $m[0][2] * ($m[1][0] * $m[2][1] - $m[1][1] * $m[2][0]);
    }
}
