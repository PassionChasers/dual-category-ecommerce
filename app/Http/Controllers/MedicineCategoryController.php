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
        // If you have admin middleware:
        // $this->middleware('auth');
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
                $q->where('Name', 'like', "%{$search}%")
                    ->orWhere('Description', 'like', "%{$search}%");
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

        // Per-page
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, [5, 10, 25, 50]) ? $perPage : 10;

        $categories = $query->paginate($perPage)->appends($request->except('page'));

        return view('admin.products.medicine.medicine_categories', compact('categories'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(MedicineCategoryRequest $request)
    {
        $data = $request->only(['Name', 'Description', 'IsActive']);
        // Minimal change: generate UUID and set as primary key
        $data['MedicineCategoryId'] = (string) Str::uuid();

        $category = MedicineCategory::create($data);

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
     * Restore a soft-deleted resource.
     */
    // public function restore($id)
    // {
    //     $category = MedicineCategory::withTrashed()->findOrFail($id);
    //     $category->restore();

    //     return redirect()->route('admin.medicine-categories.index')
    //         ->with('success', "Category '{$category->Name}' restored.");
    // }

    /**
     * Permanently delete a soft-deleted resource.
     */
    // public function forceDelete($id)
    // {
    //     $category = MedicineCategory::withTrashed()->findOrFail($id);
    //     $name = $category->Name;
    //     $category->forceDelete();

    //     return redirect()->route('admin.medicine-categories.index')
    //         ->with('success', "Category '{$name}' permanently deleted.");
    // }

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
