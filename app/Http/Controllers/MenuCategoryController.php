<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MenuCategory;
use Illuminate\Support\Facades\Http;

class MenuCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
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

        // Per-page
        $allowedPerPage = [5, 10, 25, 50];
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;
        
        $categories = $query->paginate($perPage)->withQueryString();
        return view('admin.products.food.food-categories', compact('categories', 'search','perPage', 'allowedPerPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'Name' => 'required|string|max:255|unique:MenuCategories,Name',
            'Description' => 'nullable|string',
            'IsActive' => 'nullable|boolean',
            'ImageUrl' => 'required|url',
        ],
        [
            'Name.unique'       => 'This food category already exists.',
            'ImageUrl.required' => 'Food Item image URL is required.',
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

        $category = MenuCategory::create([
            'MenuCategoryId' => Str::uuid(),
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'ImageUrl' => $validated['ImageUrl'],
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
            'ImageUrl' => 'required|url',
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

        $category->update([
            'Name' => $validated['Name'],
            'Description' => $validated['Description'] ?? null,
            'ImageUrl' => $validated['ImageUrl'],
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

        if ($category->menuItems()->exists()) {
            return redirect()->route('product.food.category')
                ->with('delete_error', 'Cannot delete this category because it has menu items.');
        }

        $category->delete();

        return redirect()->route('product.food.category')->with('success', 'Category deleted');
    }
}
