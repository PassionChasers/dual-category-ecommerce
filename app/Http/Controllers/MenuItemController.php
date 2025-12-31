<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MenuItem;
use App\Models\MenuCategory;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');

        $query = MenuItem::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                    ->orWhere('Description', 'like', "%{$search}%");
            });
        }

        $menuItems = $query->orderBy('CreatedAt', 'desc')
            ->paginate(5)
            ->withQueryString();

        $categories = MenuCategory::where('IsActive', true)->get();

        return view('admin.products.food.index', compact('menuItems', 'search', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'required|string',
            'Price' => 'required|numeric|min:0',
            'MenuCategoryId' => 'required|exists:MenuCategories,MenuCategoryId',
            'IsVeg' => 'nullable|boolean',
            'IsAvailable' => 'nullable|boolean',
            'ImageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('ImageUrl')) {
            $imagePath = $request->file('ImageUrl')->store('menu-items', 'public');
        }

        MenuItem::create([
            'MenuItemId' => Str::uuid(),
            'Name' => $validated['Name'],
            'Description' => $validated['Description'],
            'Price' => $validated['Price'],
            'MenuCategoryId' => $validated['MenuCategoryId'],
            'IsVeg' => $request->has('IsVeg') ? 1 : 0,
            'IsAvailable' => $request->has('IsAvailable') ? 1 : 0,
            'ImageUrl' => $imagePath,
        ]);

        return redirect()->route('menu-items.index')->with('success', 'Menu item created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $menuItem = MenuItem::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'required|string',
            'Price' => 'required|numeric|min:0',
            'MenuCategoryId' => 'required|exists:MenuCategories,MenuCategoryId',
            'IsVeg' => 'nullable|boolean',
            'IsAvailable' => 'nullable|boolean',
            'ImageUrl' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $imagePath = $menuItem->ImageUrl;
        if ($request->hasFile('ImageUrl')) {
            $imagePath = $request->file('ImageUrl')->store('menu-items', 'public');
        }

        $menuItem->update([
            'Name' => $validated['Name'],
            'Description' => $validated['Description'],
            'Price' => $validated['Price'],
            'MenuCategoryId' => $validated['MenuCategoryId'],
            'IsVeg' => $request->has('IsVeg') ? 1 : 0,
            'IsAvailable' => $request->has('IsAvailable') ? 1 : 0,
            'ImageUrl' => $imagePath,
        ]);

        return redirect()->route('menu-items.index')->with('success', 'Menu item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $menuItem->delete();

        return redirect()->route('menu-items.index')->with('success', 'Menu item deleted successfully');
    }
}
