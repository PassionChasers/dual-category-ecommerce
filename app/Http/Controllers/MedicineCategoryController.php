<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\MedicineCategory;
use Illuminate\Support\Facades\Http;

class MedicineCategoryController extends Controller
{
    // ================= INDEX =================
    public function index(Request $request)
    {
        $query = MedicineCategory::query();

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(Name) LIKE ?', ['%' . strtolower($search) . '%'])
                  ->orWhereRaw('LOWER(Description) LIKE ?', ['%' . strtolower($search) . '%']);
            });
        }

        if ($status = $request->get('status')) {
            $query->where('IsActive', $status === 'active' ? 1 : 0);
        }

        $allowedPerPage = [5, 10, 25, 50];
        $perPage = (int) $request->get('per_page', 10);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 10;

        $categories = $query->orderBy('CreatedAt', 'desc')
                            ->paginate($perPage)
                            ->appends($request->except('page'));

        if ($request->ajax()) {
            return view('admin.products.medicine.categories_table', compact('categories', 'perPage', 'allowedPerPage'))->render();
        }

        return view('admin.products.medicine.medicine_categories', compact('categories', 'perPage', 'allowedPerPage'));
    }

    // ================= STORE =================
    public function store(Request $request)
    {
        // Validation
        $request->validate([
            'Name' => 'required|string|max:255|unique:MedicineCategories,Name',
            'Description' => 'required|string',
            'ImageUrl' => 'required|url',
            'IsActive' => 'nullable|boolean',
        ], [
            'Name.required' => 'Category name is required.',
            'Name.unique'       => 'This Medicine category already exists.',
            'Description.required' => 'Description is required.',
            'ImageUrl.required' => 'Medicine image URL is required.',
            'ImageUrl.url'      => 'Please enter a valid image URL.',
        ]);

        //Image URL existence + image type check
        try {
            $response = Http::timeout(5)->retry(2, 100)->head($request['ImageUrl']);

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

        $category = MedicineCategory::create([
            'MedicineCategoryId' => (string) Str::uuid(),
            'Name' => $request->Name,
            'Description' => $request->Description,
            'ImageUrl' => $request->ImageUrl,
            'IsActive' => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('admin.medicine-categories.index')
                         ->with('success', "Category '{$category->Name}' created successfully.");
    }

    // ================= UPDATE =================
    public function update(Request $request, $id)
    {
        // Validation
        $request->validate([
            'Name' => 'required|string|max:255',
            'Description' => 'required|string',
            'ImageUrl' => 'required|url',
            'IsActive' => 'nullable|boolean',
        ], [
            'Name.required' => 'Category name is required.',
            'Description.required' => 'Description is required.',
            'ImageUrl.required' => 'Medicine image URL is required.',
            'ImageUrl.url'      => 'Please enter a valid image URL.',
        ]);

        //Image URL existence + image type check
        try {
            $response = Http::timeout(5)->retry(2, 100)->head($request['ImageUrl']);

            if (
                ! $response->successful() ||
                ! str_starts_with($response->header('Content-Type'), 'image/')
            ) {
                return back()
                    ->withErrors(['ImageUrl' => 'Image URL does not exist or is not a valid image.'])
                    ->withInput()
                    ->with('edit_id', $id);
            }
        } catch (\Exception $e) {
            return back()
                ->withErrors(['ImageUrl' => 'Unable to verify image URL. Please try another one.'])
                ->withInput()
                ->with('edit_id', $id);
        }

        $category = MedicineCategory::findOrFail($id);

        $category->update([
            'Name' => $request->Name,
            'Description' => $request->Description,
            'ImageUrl' => $request->ImageUrl,
            'IsActive' => $request->has('IsActive') ? 1 : 0,
        ]);

        return redirect()->route('admin.medicine-categories.index')
                         ->with('success', "Category '{$category->Name}' updated successfully.");
    }

    // ================= DESTROY =================
    public function destroy($id)
    {
        $category = MedicineCategory::findOrFail($id);

        if ($category->medicineItems()->exists()) {
            return redirect()->route('admin.medicine-categories.index')
                ->with('delete_error', 'Cannot delete this category because it has medicine items.');
        }

        $category->delete();

        return redirect()->route('admin.medicine-categories.index')
                         ->with('success', "Category '{$category->Name}' moved to trash.");
    }

    // ================= TOGGLE ACTIVE =================
    public function toggleActive($id)
    {
        $category = MedicineCategory::findOrFail($id);
        $category->IsActive = !$category->IsActive;
        $category->save();

        return response()->json(['success' => true, 'IsActive' => $category->IsActive]);
    }
}
