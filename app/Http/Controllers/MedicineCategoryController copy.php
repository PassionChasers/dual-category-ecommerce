<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicineCategory;
use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineCategoryRequest;

class MedicineCategoryController extends Controller
{
    public function __construct()
    {
       
    }

    /**
     * Display a listing of the resource with search, filters, sort and pagination.
     */
    public function index(Request $request)
    {
        $query = MedicineCategory::query();

        // Include trashed filter if requested
        if ($request->get('trashed') === 'with') {
            $query = $query->withTrashed();
        } elseif ($request->get('trashed') === 'only') {
            $query = $query->onlyTrashed();
        }

        // Search across Name and Description
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER("Name") LIKE ?', ["%{$search}%"])
                    ->orWhereRaw('LOWER("Description") LIKE ?', ["%{$search}%"]);
            });
        }

        // Filter by IsActive
        if (!is_null($request->get('status'))) {
            if ($request->get('status') === 'active') {
                $query->where('IsActive', true);
            } elseif ($request->get('status') === 'inactive') {
                $query->where('IsActive', false);
            }
        }

        // Sorting - default newest first
        $allowedSorts = ['Name', 'CreatedAt'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // // Per-page
        // $perPage = (int) $request->get('per_page', 5);
        // $perPage = in_array($perPage, [5, 10, 25, 50]) ? $perPage : 5;

        // Per-page
        $allowedPerPage = [5, 10, 25, 50];
        $perPage = (int) $request->get('per_page', 5);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 5;

        $categories = $query->paginate($perPage)->appends($request->except('page'));

        return view('admin.products.medicine.medicine_categories', compact('categories', 'perPage', 'allowedPerPage'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineCategoryRequest $request)
    {
        // Validate request (if not already validated in MedicineCategoryRequest)
        $validated = $request->validate([
            'Name' => 'required|string|max:255|unique:MedicineCategories,Name',
            'Description' => 'required|string|max:500',
            'IsActive' => 'nullable|boolean',
        ]);

        // Generate UUID as primary key
        $validated['MedicineCategoryId'] = (string) Str::uuid();

        // Ensure IsActive is set to 0 if not provided
        $validated['IsActive'] = $request->has('IsActive') ? 1 : 0;

        // Create the category
        $category = MedicineCategory::create($validated);

        // Redirect with success message
        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' created successfully.");
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MedicineCategoryRequest $request, $id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->update($request->only(['Name', 'Description', 'IsActive']));

        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' updated successfully.");
    }

    /**
     * Soft delete the specified resource.
     */
    public function destroy($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' moved to trash.");
    }

   

    /**
     * Toggle IsActive flag (AJAX friendly).
     */
    public function toggleActive($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->IsActive = !$category->IsActive;
        $category->save();

        return response()->json(['success' => true, 'IsActive' => $category->IsActive]);
    }
}
