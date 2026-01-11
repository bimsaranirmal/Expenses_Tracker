<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    // Optional: Show categories page
    public function index()
    {
        $categories = auth()->user()->categories;
        return view('categories.index', compact('categories'));
    }

    // Create Category
   // Create Category
public function store(Request $request)
{
    try {
        $request->validate([
            'name' => 'required|unique:categories,name,NULL,id,user_id,' . auth()->id(),
            'limit_amount' => 'nullable|numeric'
        ]);

        $category = Category::create([
            'user_id' => auth()->id(),
            'name' => $request->name,
            'limit_amount' => $request->limit_amount ?? 0,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category added successfully!',
            'category' => $category
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->validator->errors()->first(),
            'errors' => $e->validator->errors()
        ], 422);
    }
}

// Update Category
public function update(Request $request)
{
    try {
        $request->validate([
            'id' => 'required|integer',
            'name' => 'required|unique:categories,name,' . $request->id . ',id,user_id,' . auth()->id(),
            'limit_amount' => 'nullable|numeric'
        ]);

        $category = Category::where('user_id', auth()->id())
                            ->where('id', $request->id)
                            ->firstOrFail();

        $category->update([
            'name' => $request->name,
            'limit_amount' => $request->limit_amount,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Category updated successfully!',
            'category' => $category
        ]);
    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => $e->validator->errors()->first(),
            'errors' => $e->validator->errors()
        ], 422);
    }
}

// Delete Category
    public function destroy($id)
    {
        $category = Category::where('user_id', auth()->id())
                            ->where('id', $id)
                            ->firstOrFail();

        if ($category->expenses()->exists()) {
            return response()->json([
                'success' => false,
                'message' => 'Cannot delete category because it has associated expenses.'
            ], 400);
        }

        $category->delete();

        return response()->json([
            'success' => true,
            'message' => 'Category deleted successfully!'
        ]);
    }

}
