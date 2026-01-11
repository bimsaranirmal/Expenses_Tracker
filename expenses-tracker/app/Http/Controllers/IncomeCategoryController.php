<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IncomeCategoryController extends Controller
{
    public function index()
    {
        $categories = IncomeCategory::where('user_id', Auth::id())->get();
        return response()->json($categories);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:income_categories,name,NULL,id,user_id,' . Auth::id(),
                'limit_amount' => 'nullable|numeric|min:0',
            ]);
            $data['user_id'] = Auth::id();
            $category = IncomeCategory::create($data);
            session()->flash('success', 'Category added successfully.');
            return response()->json(['success' => true, 'category' => $category, 'message' => 'Category added successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $category = IncomeCategory::where('user_id', Auth::id())->findOrFail($id);
            $data = $request->validate([
                'name' => 'required|string|max:255|unique:income_categories,name,' . $id . ',id,user_id,' . Auth::id(),
                'limit_amount' => 'nullable|numeric|min:0',
            ]);
            $category->update($data);
            session()->flash('success', 'Category updated successfully.');
            return response()->json(['success' => true, 'category' => $category, 'message' => 'Category updated successfully.']);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->validator->errors()->first(),
                'errors' => $e->validator->errors()
            ], 422);
        }
    }

    public function destroy($id)
    {
        $category = IncomeCategory::where('user_id', Auth::id())->findOrFail($id);
        
        if ($category->incomes()->exists()) {
            session()->flash('error', 'Cannot delete category because it has associated incomes.');
            return response()->json(['success' => false, 'message' => 'Cannot delete category because it has associated incomes.'], 400);
        }

        $category->delete();
        session()->flash('success', 'Category deleted successfully.');
        return response()->json(['success' => true, 'message' => 'Category deleted successfully.']);
    }
}
