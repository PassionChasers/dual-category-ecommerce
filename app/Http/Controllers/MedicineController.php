<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicineRequest;
use App\Models\Medicine;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\View;
use Carbon\Carbon;
use Illuminate\Support\Facades\Http;


class MedicineController extends Controller
{
    public function index(Request $request)
    {
        $query = Medicine::query();

        // Search by name (case-insensitive)
        if ($search = $request->get('search')) {
            $query->whereRaw('LOWER("Name") LIKE ?', ["%{$search}%"]);
        }

        // Filter by category
        if ($category = $request->get('category')) {
            $query->where('MedicineCategoryId', $category);
        }

        // Filter by prescription required
        if ($request->filled('prescription')) {
            if ($request->prescription === 'yes')
                $query->where('PrescriptionRequired', true);
            elseif ($request->prescription === 'no')
                $query->where('PrescriptionRequired', false);
        }

        // Filter by is active
        if ($request->filled('status')) {
            if ($request->status === 'active')
                $query->where('IsActive', true);
            elseif ($request->status === 'inactive')
                $query->where('IsActive', false);
        }

        // Sorting
        $allowedSorts = ['Name', 'Price', 'MRP', 'CreatedAt'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        // Per-page
        $allowedPerPage = [5, 10, 25, 50];
        $perPage = (int) $request->get('per_page', 5);
        $perPage = in_array($perPage, $allowedPerPage) ? $perPage : 5;

        $medicines = $query->paginate($perPage)->appends($request->except('page'));

        // Lightweight list of categories for filters (if your categories live in MedicineCategory model)
        $categories = \App\Models\MedicineCategory::select('MedicineCategoryId', 'Name')->orderBy('Name')->get();

        // Return partial for AJAX
        if ($request->ajax()) {
            return view('admin.products.medicine.medicines_table', compact('medicines', 'categories','perPage', 'allowedPerPage'))->render();
        }

        return view('admin.products.medicine.index', compact('medicines', 'categories','perPage', 'allowedPerPage'));
    }


    public function store(Request $request)
    {
        //Validate form input
        $validated = $request->validate([
            'MedicineCategoryId'   => 'required|uuid|exists:MedicineCategories,MedicineCategoryId',

            'Name'                 => 'required|string|max:255',
            'GenericName'          => 'nullable|string|max:255',
            'BrandName'            => 'nullable|string|max:255',

            'Description'          => 'nullable|string|max:1000',

            'Price'                => 'required|numeric|min:0',
            // 'MRP'                  => 'required|numeric|min:0',

            'PrescriptionRequired' => 'nullable|boolean',

            'Manufacturer'         => 'nullable|string|max:255',

            'ExpiryDate'           => 'nullable|date|after:today',

            'DosageForm'           => 'required|string|max:100',
            'Strength'             => 'nullable|string|max:100',

            'Packaging'            => 'required|string|max:100',

            'IsActive'             => 'nullable|boolean',

            // Image URL basic validation
            'ImageUrl'             => 'required|url',
        ], [
            'ImageUrl.required' => 'Medicine image URL is required.',
            'ImageUrl.url'      => 'Please enter a valid image URL.',
            'ExpiryDate.after'  => 'Expiry date must be a future date.',
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

        //Handle checkboxes safely
        $validated['PrescriptionRequired'] = $request->boolean('PrescriptionRequired');
        $validated['IsActive'] = $request->boolean('IsActive');

        //Generate UUID
        $validated['MedicineId'] = (string) Str::uuid();

        //Save to database
        $medicine = Medicine::create($validated);

        //Redirect with success message
        return redirect()
            ->route('admin.medicines.index')
            ->with('success', "Medicine '{$medicine->Name}' created successfully.");
    }

    public function update(MedicineRequest $request, $id)
    {
        $medicine = Medicine::findOrFail($id);

        $data = $request->only([
            'MedicalStoreId',
            'MedicineCategoryId',
            'Name',
            'GenericName',
            'BrandName',
            'Description',
            'Price',
            'MRP',
            'PrescriptionRequired',
            'Manufacturer',
            'ExpiryDate',
            'DosageForm',
            'Strength',
            'Packaging',
            'IsActive'
        ]);

        // image update: delete old if exists
        if ($request->hasFile('image')) {
            if ($medicine->ImageUrl && Storage::disk('public')->exists($medicine->ImageUrl)) {
                Storage::disk('public')->delete($medicine->ImageUrl);
            }
            $file = $request->file('image');
            $filename = Str::slug($data['Name'] ?? 'medicine') . '-' . time() . '.' . $file->getClientOriginalExtension();
            $path = $file->storeAs('medicines', $filename, 'public');
            $data['ImageUrl'] = $path;
        }

        $medicine->update($data);

        return redirect()->route('admin.medicines.index')->with('success', "Medicine '{$medicine->Name}' updated.");
    }

    public function destroy($id)
    {
        $medicine = Medicine::findOrFail($id);
        // delete image if present
        if ($medicine->ImageUrl && Storage::disk('public')->exists($medicine->ImageUrl)) {
            Storage::disk('public')->delete($medicine->ImageUrl);
        }
        $name = $medicine->Name;
        $medicine->delete();

        return redirect()->route('admin.medicines.index')->with('success', "Medicine '{$name}' deleted.");

        // Stay on the same page
        // return back()->with('success', "Medicine '{$name}' deleted.");
    }

    public function toggleActive($id)
    {
        $medicine = Medicine::findOrFail($id);
        $medicine->IsActive = !$medicine->IsActive;
        $medicine->save();

        return response()->json(['success' => true, 'IsActive' => $medicine->IsActive]);
    }

    
    public function show($id)
    {
        $medicine = Medicine::with('category')->findOrFail($id);

        // If your model stores ratings / review counts, adapt these keys.
        // We'll attempt to provide defaults if fields don't exist.
        $medicine->AvgRating = $medicine->AvgRating ?? $medicine->avg_rating ?? null;
        $medicine->TotalReviews = $medicine->TotalReviews ?? $medicine->total_reviews ?? null;

        // Format date strings for display (optional)
        $medicine->CreatedAtFormatted = $medicine->CreatedAt ? Carbon::parse($medicine->CreatedAt)->toDayDateTimeString() : null;
        $medicine->UpdatedAtFormatted = $medicine->UpdatedAt ? Carbon::parse($medicine->UpdatedAt)->toDayDateTimeString() : null;
        $medicine->ExpiryDateFormatted = $medicine->ExpiryDate ? Carbon::parse($medicine->ExpiryDate)->format('Y-m-d') : null;

        return view('admin.products.medicine.show', compact('medicine'));
    }

    /**
     * Print-friendly view (opens a simple template suitable for window.print()).
     */
    public function print($id)
    {
        $medicine = Medicine::with('category')->findOrFail($id);

        $medicine->AvgRating = $medicine->AvgRating ?? $medicine->avg_rating ?? null;
        $medicine->TotalReviews = $medicine->TotalReviews ?? $medicine->total_reviews ?? null;
        $medicine->CreatedAtFormatted = $medicine->CreatedAt ? Carbon::parse($medicine->CreatedAt)->toDayDateTimeString() : null;
        $medicine->UpdatedAtFormatted = $medicine->UpdatedAt ? Carbon::parse($medicine->UpdatedAt)->toDayDateTimeString() : null;
        $medicine->ExpiryDateFormatted = $medicine->ExpiryDate ? Carbon::parse($medicine->ExpiryDate)->format('Y-m-d') : null;

        // A simplified print layout (same blade but with print-specific class)
        return view('admin.products.medicine.print', compact('medicine'));
    }

    /**
     * Export PDF using barryvdh/laravel-dompdf if available.
     * If not installed, gracefully redirect back with a message.
     */
    public function exportPdf($id)
    {
        $medicine = Medicine::with('category')->findOrFail($id);

        $medicine->AvgRating = $medicine->AvgRating ?? $medicine->avg_rating ?? null;
        $medicine->TotalReviews = $medicine->TotalReviews ?? $medicine->total_reviews ?? null;
        $medicine->CreatedAtFormatted = $medicine->CreatedAt ? Carbon::parse($medicine->CreatedAt)->toDayDateTimeString() : null;
        $medicine->UpdatedAtFormatted = $medicine->UpdatedAt ? Carbon::parse($medicine->UpdatedAt)->toDayDateTimeString() : null;
        $medicine->ExpiryDateFormatted = $medicine->ExpiryDate ? Carbon::parse($medicine->ExpiryDate)->format('Y-m-d') : null;

        // Check if PDF facade exists (barryvdh/laravel-dompdf)
        if (class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            $pdf = \PDF::loadView('admin.products.medicine.pdf', compact('medicine'))
                ->setPaper('a4', 'portrait');

            $filename = 'medicine-' . ($medicine->MedicineId ?? time()) . '.pdf';
            return $pdf->download($filename);
        }

        // fallback: render print view and instruct user
        return redirect()->route('admin.medicines.show', $id)
            ->with('error', 'PDF export package not installed. Install barryvdh/laravel-dompdf to enable PDF export.');
    }

   
}
