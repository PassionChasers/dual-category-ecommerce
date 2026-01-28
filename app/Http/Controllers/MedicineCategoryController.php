<?php

namespace App\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\MedicineCategory;
use App\Http\Requests\MedicineCategoryRequest;

class MedicineCategoryController extends Controller
{
    public function index(Request $request)
    {
        $query = MedicineCategory::query();

        // Search filter
        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER("Name") LIKE ?', ["%".strtolower($search)."%"])
                ->orWhereRaw('LOWER("Description") LIKE ?', ["%".strtolower($search)."%"]);
            });
        }

        // Status filter
        if ($status = $request->get('status')) {
            $query->where('IsActive', $status === 'active' ? 1 : 0);
        }

        // Per-page
        $allowedPerPage = [5,10,25,50];
        $perPage = (int) $request->get('per_page', 5);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 5;

        $categories = $query->orderBy('CreatedAt', 'desc')
                            ->paginate($perPage)
                            ->appends($request->except('page'));

        // Return partial for AJAX
        if ($request->ajax()) {
            return view('admin.products.medicine.categories_table', compact('categories', 'perPage', 'allowedPerPage'))->render();
        }

        return view('admin.products.medicine.medicine_categories', compact('categories', 'perPage', 'allowedPerPage'));
    }

    public function store(MedicineCategoryRequest $request)
    {
        $data = $request->validated();
        $data['MedicineCategoryId'] = (string) Str::uuid();
        $data['IsActive'] = $request->has('IsActive') ? 1 : 0;

        $category = MedicineCategory::create($data);

        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' created successfully.");
    }

    public function update(MedicineCategoryRequest $request, $id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->update($request->only(['Name','Description','IsActive']));

        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' updated successfully.");
    }

    public function destroy($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.medicine-categories.index')
            ->with('success', "Category '{$category->Name}' moved to trash.");
    }

    public function toggleActive($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->IsActive = !$category->IsActive;
        $category->save();

        return response()->json(['success' => true, 'IsActive' => $category->IsActive]);
    }
}
