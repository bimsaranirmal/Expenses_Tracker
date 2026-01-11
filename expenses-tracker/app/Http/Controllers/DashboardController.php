<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Expense;
use App\Models\Income;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Services\PredictionService;
use App\Services\AnomalyService;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        
        // Get current month dates
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        
        // Get previous month dates
        $previousMonthStart = Carbon::now()->subMonth()->startOfMonth();
        $previousMonthEnd = Carbon::now()->subMonth()->endOfMonth();
        
        // Current month expenses
        $currentMonthExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');
        
        // Previous month expenses
        $previousMonthExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
            ->sum('amount');
        
        // Current month income
        $currentMonthIncome = Income::where('user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->sum('amount');
        
        // Previous month income
        $previousMonthIncome = Income::where('user_id', $userId)
            ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
            ->sum('amount');
        
        // Calculate percentage changes
        $expenseChange = $previousMonthExpenses > 0 
            ? (($currentMonthExpenses - $previousMonthExpenses) / $previousMonthExpenses) * 100 
            : 0;
        
        $incomeChange = $previousMonthIncome > 0 
            ? (($currentMonthIncome - $previousMonthIncome) / $previousMonthIncome) * 100 
            : 0;
        
        // Net savings
        $currentNetSavings = $currentMonthIncome - $currentMonthExpenses;
        $previousNetSavings = $previousMonthIncome - $previousMonthExpenses;
        
        // Last 6 months trend data
        $sixMonthsAgo = Carbon::now()->subMonths(6)->startOfMonth();
        $monthlyExpenses = Expense::where('user_id', $userId)
            ->where('date', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();

        $monthlyIncome = Income::where('user_id', $userId)
            ->where('date', '>=', $sixMonthsAgo)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Yearly trend data (12 months)
        $oneYearAgo = Carbon::now()->subMonths(12)->startOfMonth();
        $yearlyExpenses = Expense::where('user_id', $userId)
            ->where('date', '>=', $oneYearAgo)
            ->select(
                DB::raw('DATE_FORMAT(date, "%Y-%m") as month'),
                DB::raw('SUM(amount) as total')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->get();
        
        // Category-wise comparison (top 5)
        $currentCategoryExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->orderByDesc('total')
            ->limit(5)
            ->get();
        
        $previousCategoryExpenses = Expense::where('user_id', $userId)
            ->whereBetween('date', [$previousMonthStart, $previousMonthEnd])
            ->select('category_id', DB::raw('SUM(amount) as total'))
            ->groupBy('category_id')
            ->get()
            ->keyBy('category_id');
        
        // Prepare category comparison data
        $categoryComparison = $currentCategoryExpenses->map(function($item) use ($previousCategoryExpenses) {
            $category = Category::find($item->category_id);
            $previousAmount = $previousCategoryExpenses->get($item->category_id)->total ?? 0;
            $change = $previousAmount > 0 
                ? (($item->total - $previousAmount) / $previousAmount) * 100 
                : 0;
            
            return [
                'name' => $category->name ?? 'Unknown',
                'current' => $item->total,
                'previous' => $previousAmount,
                'change' => $change,
                'limit' => $category->limit_amount ?? 0
            ];
        });
        
        // Additional metrics
        $daysInMonth = Carbon::now()->daysInMonth;
        $daysPassed = Carbon::now()->day;
        $daysRemaining = $daysInMonth - $daysPassed;
        
        $dailyAverageExpense = $daysPassed > 0 ? $currentMonthExpenses / $daysPassed : 0;
        $projectedMonthExpense = $dailyAverageExpense * $daysInMonth;
        
        $expenseCount = Expense::where('user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        $incomeCount = Income::where('user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->count();
        
        // Total budget from all categories
        $totalBudget = Category::where('user_id', $userId)->sum('limit_amount');
        $budgetUtilization = $totalBudget > 0 ? ($currentMonthExpenses / $totalBudget) * 100 : 0;
        
        // Savings rate
        $savingsRate = $currentMonthIncome > 0 ? ($currentNetSavings / $currentMonthIncome) * 100 : 0;
        
        // Recent Transactions (Last 5)
        $recentExpenses = Expense::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'expense',
                    'amount' => $item->amount,
                    'date' => $item->date,
                    'category' => $item->category->name ?? 'Uncategorized',
                    'description' => $item->description ?? $item->title ?? 'Expense'
                ];
            });

        $recentIncomes = Income::where('user_id', $userId)
            ->with('category')
            ->orderByDesc('date')
            ->orderByDesc('created_at')
            ->limit(5)
            ->get()
            ->map(function($item) {
                return [
                    'type' => 'income',
                    'amount' => $item->amount,
                    'date' => $item->date,
                    'category' => $item->category->name ?? 'Uncategorized',
                    'description' => $item->description ?? $item->name ?? 'Income'
                ];
            });

        $recentTransactions = $recentExpenses->concat($recentIncomes)
            ->sortByDesc(function($item) {
                return $item['date'];
            })
            ->take(5);

        // Category Distribution for Donut Chart (Current Month)
        $categoryDistribution = Expense::where('expenses.user_id', $userId)
            ->whereBetween('date', [$currentMonthStart, $currentMonthEnd])
            ->join('categories', 'expenses.category_id', '=', 'categories.id')
            ->select('categories.name', DB::raw('SUM(expenses.amount) as total'))
            ->groupBy('categories.name')
            ->orderByDesc('total')
            ->get();

        // AI Predictions & Insights
        $predictionService = new PredictionService();
        $anomalyService = new AnomalyService();

        // 1. Forecasts
        $expenseForecast = $predictionService->predictNextMonthExpense($userId);
        $incomeForecast = $predictionService->predictNextMonthIncome($userId);

        // 2. Anomalies (Current Month)
        $expenseAnomalies = $anomalyService->detectAnomalies(
            $userId, 
            $currentMonthStart->toDateString(), 
            $currentMonthEnd->toDateString()
        );
        $incomeAnomalies = $anomalyService->detectIncomeAnomalies(
            $userId, 
            $currentMonthStart->toDateString(), 
            $currentMonthEnd->toDateString()
        );
        
        $totalAnomalies = count($expenseAnomalies) + count($incomeAnomalies);

        return view('dashboard', compact(
            'currentMonthExpenses',
            'previousMonthExpenses',
            'currentMonthIncome',
            'previousMonthIncome',
            'expenseChange',
            'incomeChange',
            'currentNetSavings',
            'previousNetSavings',
            'previousNetSavings',
            'currentNetSavings',
            'previousNetSavings',
            'previousNetSavings',
            'monthlyExpenses',
            'monthlyIncome',
            'yearlyExpenses',
            'categoryComparison',
            'dailyAverageExpense',
            'projectedMonthExpense',
            'expenseCount',
            'incomeCount',
            'totalBudget',
            'budgetUtilization',
            'savingsRate',
            'daysRemaining',
            'recentTransactions',
            'categoryDistribution',
            'expenseForecast',
            'incomeForecast',
            'totalAnomalies'
        ));
    }
}
