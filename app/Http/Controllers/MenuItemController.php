<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MenuItem;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\Http;

class MenuItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        $category = $request->get('category');
        $type = $request->get('type');

        $query = MenuItem::with('category');

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                    ->orWhere('Description', 'like', "%{$search}%");
            });
        }

        if ($category) {
            $query->where('MenuCategoryId', $category);
        }

        if ($type !== null && $type !== '') {
            $query->where('IsVeg', (int)$type);
        }

        // Per-page
        $allowedPerPage = [5, 10, 25, 50];
        $perPage = (int) $request->get('per_page', 5);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 5;

        $menuItems = $query->orderBy('CreatedAt', 'desc')
            ->paginate($perPage)
            ->withQueryString();

        

        $categories = MenuCategory::where('IsActive', true)->get();

        return view('admin.products.food.index', compact('menuItems', 'search', 'categories','perPage', 'allowedPerPage'));
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
            'ImageUrl' => 'required|url',
        ],
        [
            'ImageUrl.required' => 'Food image URL is required.',
            'ImageUrl.url'      => 'Please enter a valid image URL.',
        ]);

        //Image URL existence + image type check
        try {
            $response = Http::timeout(5)->retry(2, 100)->head($validated['ImageUrl']);

            if (
                ! $response->successful() ||
                ! str_starts_with($response->header('Content-Type'), 'image/')
            ) {
                return back()
                    ->withErrors(['ImageUrl' => 'Image URL does not exist or is not a valid image.'])
                    ->withInput();
            }
        } catch (\Exception $e) {
            return back()
                ->withErrors(['ImageUrl' => 'Unable to verify image URL. Please try another one.'])
                ->withInput();
        }

        // $imagePath = null;
        // if ($request->hasFile('ImageUrl')) {
        //     $imagePath = $request->file('ImageUrl')->store('menu-items', 'public');
        // }

        MenuItem::create([
            'MenuItemId' => Str::uuid(),
            'Name' => $validated['Name'],
            'Description' => $validated['Description'],
            'Price' => $validated['Price'],
            'MenuCategoryId' => $validated['MenuCategoryId'],
            'IsVeg' => $request->has('IsVeg') ? true : false,
            'IsAvailable' => $request->has('IsAvailable') ? true: false,
            'ImageUrl' => $validated['ImageUrl'],
        ]);

        return redirect()->route('admin.food.index')->with('success', 'Menu item created successfully');
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

        return redirect()->route('admin.food.index')->with('success', 'Menu item updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $menuItem = MenuItem::findOrFail($id);
        $name = $menuItem->Name;

        // Optional: prevent delete if used in orders
        if ($menuItem->orderItems()->exists()) {

            // return response()->json([
            //     'success' => false,
            //     'message' => "This menu item '{$name}' cannot be deleted because it is used in orders."
            // ], 400);

            // return back()->with('error', "MenuItem '{$name}' cannot be deleted because it is used in orders.");

            return redirect()
            ->route('admin.food.index')
            ->with('error', "MenuItem '{$name}' cannot be deleted because it is used in orders.");
        }

        $menuItem->delete();

        // return response()->json([
        //     'success' => true,
        //     'message' => "Menu item  '{$name}' deleted successfully"
        // ]);

        return redirect()
            ->route('admin.food.index')
            ->with('success', "MenuItem '{$name}' deleted successfully.");
    }
}
