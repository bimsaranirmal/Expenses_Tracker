<?php

namespace App\Services;

use App\Models\Expense;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class InsightService
{
    /**
     * Generate financial insights for the user.
     *
     * @param int $userId
     * @return array List of insight strings
     */
    public function generateInsights($userId)
    {
        $insights = [];

        // 1. Compare Current Month vs Last Month
        $currentMonthTotal = Expense::where('user_id', $userId)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $lastMonthTotal = Expense::where('user_id', $userId)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonthTotal > 0) {
            $diff = $currentMonthTotal - $lastMonthTotal;
            $percentage = ($diff / $lastMonthTotal) * 100;
            
            if ($percentage > 10) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => 'trending-up',
                    'message' => 'Spending is ' . number_format($percentage, 0) . '% higher than last month.'
                ];
            } elseif ($percentage < -10) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'trending-down',
                    'message' => 'Great job! Spending is ' . number_format(abs($percentage), 0) . '% lower than last month.'
                ];
            }
        }

        // 2. Identify Top Spending Category
        // 2. Identify Top Spending Category
        $topCategory = Expense::where('expenses.user_id', $userId)
            ->whereMonth('expenses.date', Carbon::now()->month)
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->first();

        if ($topCategory) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'pie-chart',
                'message' => 'Most money went to ' . $topCategory->name . ' this month.'
            ];
        }

        // 3. Check for Anomalies Count
        $anomalyService = new AnomalyService();
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to = Carbon::now()->endOfMonth()->toDateString();
        $anomalies = $anomalyService->detectAnomalies($userId, $from, $to) ?? [];
        $currentMonthAnomalies = count($anomalies);

        if ($currentMonthAnomalies > 0) {
             $insights[] = [
                'type' => 'danger',
                'icon' => 'alert-circle',
                'message' => $currentMonthAnomalies . ' unusual expense(s) detected.'
            ];
        }

        return $insights;      
    }
    public function generateIncomeInsights($userId)
    {
        $insights = [];

        // 1. Compare Current Month vs Last Month
        $currentMonthTotal = \App\Models\Income::where('user_id', $userId)
            ->whereMonth('date', Carbon::now()->month)
            ->whereYear('date', Carbon::now()->year)
            ->sum('amount');

        $lastMonthTotal = \App\Models\Income::where('user_id', $userId)
            ->whereMonth('date', Carbon::now()->subMonth()->month)
            ->whereYear('date', Carbon::now()->subMonth()->year)
            ->sum('amount');

        if ($lastMonthTotal > 0) {
            $diff = $currentMonthTotal - $lastMonthTotal;
            $percentage = ($diff / $lastMonthTotal) * 100;
            
            if ($percentage > 10) {
                $insights[] = [
                    'type' => 'success',
                    'icon' => 'trending-up',
                    'message' => 'Income is ' . number_format($percentage, 0) . '% higher than last month.'
                ];
            } elseif ($percentage < -10) {
                $insights[] = [
                    'type' => 'warning',
                    'icon' => 'trending-down',
                    'message' => 'Income is ' . number_format(abs($percentage), 0) . '% lower than last month.'
                ];
            }
        }

        // 2. Identify Top Income Source
        $topCategory = \App\Models\Income::where('incomes.user_id', $userId)
            ->whereMonth('incomes.date', Carbon::now()->month)
            ->join('income_categories', 'incomes.category_id', '=', 'income_categories.id')
            ->select('income_categories.name', DB::raw('SUM(incomes.amount) as total'))
            ->groupBy('income_categories.name')
            ->orderByDesc('total')
            ->first();

        if ($topCategory) {
            $insights[] = [
                'type' => 'info',
                'icon' => 'pie-chart',
                'message' => 'Top income source: ' . $topCategory->name . '.'
            ];
        }

        // 3. Anomalies
        $anomalyService = new AnomalyService();
        $from = Carbon::now()->startOfMonth()->toDateString();
        $to = Carbon::now()->endOfMonth()->toDateString();
        $anomalies = $anomalyService->detectIncomeAnomalies($userId, $from, $to);
        $count = count($anomalies);

        if ($count > 0) {
             $insights[] = [
                'type' => 'info', // Neutral for income anomalies (could be good)
                'icon' => 'alert-circle',
                'message' => $count . ' unusual income entries detected.'
            ];
        }

        return $insights;
    }

}
