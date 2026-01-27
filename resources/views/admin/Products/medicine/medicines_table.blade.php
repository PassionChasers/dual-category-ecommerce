<div id="medicines-container">
<div class="overflow-x-auto">
    <table class="min-w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th>#</th>
            <th>Image</th>
            <th>Name</th>
            <th>Category</th>
            <th>Price</th>
            <th>Prescription</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        </thead>
        <tbody>
        @forelse($medicines as $i => $m)
        <tr>
            <td>{{ $medicines->firstItem()+$i }}</td>
            <td>
                @if($m->ImageUrl)
                    <a href="{{ $m->ImageUrl }}" target="_blank">
                        <img src="{{ $m->ImageUrl }}" class="thumb">
                    </a>
                @else
                    <span class="text-gray-400">No</span>
                @endif
            </td>
            <td>{{ $m->Name }}</td>
            <td>{{ optional($m->category)->Name }}</td>
            <td>৳ {{ number_format($m->Price,2) }}</td>
            <td>{{ $m->PrescriptionRequired ? 'Yes' : 'No' }}</td>
            <td>
                <span class="{{ $m->IsActive?'bg-green-100 text-green-800':'bg-red-100 text-red-800' }} px-2 py-1 text-xs rounded">
                    {{ $m->IsActive ? 'Active':'Inactive' }}
                </span>
            </td>
            <td>
                <button class="edit-btn text-indigo-600" 
                    data-id="{{ $m->MedicineId }}"
                    data-name="{{ e($m->Name) }}"
                    data-generic="{{ e($m->GenericName) }}"
                    data-brand="{{ e($m->BrandName) }}"
                    data-description="{{ e($m->Description) }}"
                    data-price="{{ $m->Price }}"
                    data-prescription="{{ $m->PrescriptionRequired?'1':'0' }}"
                    data-manufacturer="{{ e($m->Manufacturer) }}"
                    data-expiry="{{ $m->ExpiryDate }}"
                    data-dosage="{{ e($m->DosageForm) }}"
                    data-strength="{{ e($m->Strength) }}"
                    data-packaging="{{ e($m->Packaging) }}"
                    data-category="{{ $m->MedicineCategoryId }}"
                    data-isactive="{{ $m->IsActive?'1':'0' }}"
                    data-image="{{ $m->ImageUrl }}">
                    <i class="fas fa-edit"></i>
                </button>
                <form method="POST" action="{{ route('admin.medicines.destroy',$m->MedicineId) }}" class="inline delete-form">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-red-600"><i class="fas fa-trash"></i></button>
                </form>
            </td>
        </tr>
        @empty
        <tr><td colspan="8" class="text-center text-gray-500 py-4">No medicines found.</td></tr>
        @endforelse
        </tbody>
    </table>

    <div class="flex justify-between items-center px-6 py-4 bg-gray-50 border-t">
        <div class="text-sm text-gray-600">Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }}</div>
        <div>{{ $medicines->links() }}</div>
    </div>
</div>
</div>
