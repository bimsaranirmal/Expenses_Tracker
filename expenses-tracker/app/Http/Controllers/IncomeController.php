<?php

namespace App\Http\Controllers;

use App\Models\Income;
use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Income::where('user_id', Auth::id());
        $from = $request->input('from', date('Y-m-01'));
        $to = $request->input('to', date('Y-m-t'));

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        $query->where('date', '>=', $from);
        $query->where('date', '<=', $to);

        $incomes = $query->orderBy('date', 'desc')->get();
        $categories = IncomeCategory::where('user_id', Auth::id())->get();

        // Analytics
        $predictionService = new \App\Services\PredictionService();
        $predictionResult = $predictionService->predictNextMonthIncome(Auth::id());
        $predictedIncome = $predictionResult['prediction'];
        $predictionConfidence = $predictionResult['r_squared'] * 100;

        $anomalyService = new \App\Services\AnomalyService();
        $anomalies = $anomalyService->detectIncomeAnomalies(Auth::id(), $from, $to);

        $insightService = new \App\Services\InsightService();
        $insights = $insightService->generateIncomeInsights(Auth::id());

        return view('income.index', compact('incomes', 'categories', 'from', 'to', 'predictedIncome', 'predictionConfidence', 'anomalies', 'insights'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        $data['user_id'] = Auth::id();
        $income = Income::create($data);
        session()->flash('success', 'Income added successfully.');
        return response()->json(['success' => true, 'income' => $income, 'message' => 'Income added successfully.']);
    }

    public function update(Request $request, $id)
    {
        $income = Income::where('user_id', Auth::id())->findOrFail($id);
        $data = $request->validate([
            'category_id' => 'required|exists:income_categories,id',
            'amount' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'date' => 'required|date',
        ]);
        $income->update($data);
        session()->flash('success', 'Income updated successfully.');
        return response()->json(['success' => true, 'income' => $income, 'message' => 'Income updated successfully.']);
    }

    public function destroy($id)
    {
        $income = Income::where('user_id', Auth::id())->findOrFail($id);
        $income->delete();
        session()->flash('success', 'Income deleted successfully.');
        return response()->json(['success' => true, 'message' => 'Income deleted successfully.']);
    }
}
