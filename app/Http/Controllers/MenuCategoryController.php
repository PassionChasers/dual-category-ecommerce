<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MenuCategory;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search', '');
        $status = $request->input('status');
        
        $query = MenuCategory::orderBy('CreatedAt', 'desc');
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER("Name") LIKE ?', ["%{$search}%"])
                  ->orWhereRaw('LOWER("Description") LIKE ?', ["%{$search}%"]);
            });
        }

        // Only apply status filter if specifically set to 'active' or 'inactive'
        if ($status === 'active') {
            $query->where('IsActive', 1);
        } elseif ($status === 'inactive') {
            $query->where('IsActive', 0);
        }
        // If status is null, empty string, or anything else, show all
        
        $categories = $query->paginate(5)->withQueryString();
        return view('admin.products.food.food-categories', compact('categories', 'search'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'IsActive' => 'nullable|boolean',
        ]);

        $category = MenuCategory::create([
            'MenuCategoryId' => Str::uuid(),
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'IsActive' => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('product.food.category')->with('success', 'Category created');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $category = MenuCategory::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'nullable|string',
            'IsActive' => 'nullable|boolean',
        ]);

        $category->update([
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'IsActive' => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('product.food.category')->with('success', 'Category updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $category = MenuCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('product.food.category')->with('success', 'Category deleted');
    }
}
