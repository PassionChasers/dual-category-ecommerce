<div class="overflow-x-auto">

    <div class="px-6 py-4 border-b">
        <h2 class="font-semibold text-gray-800">Medicine List</h2>
    </div>
    
    <table class="min-w-full text-sm divide-y divide-gray-200">
        <thead class="bg-gray-50">
        <tr>
            <th class="px-4 py-3 text-left">#</th>
            <th class="px-4 py-3 text-left">Image</th>
            <th class="px-4 py-3 text-left">Name</th>
            <th class="px-4 py-3 text-left">Category</th>
            <th class="px-4 py-3 text-left">Price</th>
            <th class="px-4 py-3 text-left">Prescription</th>
            <th class="px-4 py-3 text-left">Status</th>
            <th class="px-4 py-3 text-center">Actions</th>
        </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-100">

        @forelse($medicines as $i => $m)
            <tr>
                <td class="px-4 py-3">{{ $medicines->firstItem() + $i }}</td>

                <td class="px-4 py-3">
                    @if($m->ImageUrl)
                        {{-- <a href="{{ $m->ImageUrl }}" target="_blank"> --}}
                            <img src="{{ $m->ImageUrl }}" class="thumb cursor-pointer" onclick="showImage('{{ $m->ImageUrl }}')">
                        {{-- </a> --}}
                    @else
                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">No</div>
                    @endif
                </td>

                <td class="px-4 py-3">
                    <div class="font-medium text-gray-900">{{ $m->Name }}</div>
                    <div class="text-xs text-gray-500">{{ $m->BrandName ?: $m->GenericName }}</div>
                </td>

                <td class="px-4 py-3">{{ optional($m->category)->Name }}</td>
                <td class="px-4 py-3">৳ {{ number_format($m->Price,2) }}</td>
                <td class="px-4 py-3">{{ $m->PrescriptionRequired ? 'Yes' : 'No' }}</td>

                <td class="px-4 py-3">
                    <span class="px-2 py-1 rounded text-xs {{ $m->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                        {{ $m->IsActive ? 'Active' : 'Inactive' }}
                    </span>
                </td>

                <td class="px-4 py-3 text-center">
                    <div class="flex items-center justify-center gap-3">
                        <button class="edit-btn text-indigo-600 hover:text-indigo-800" 
                            data-id="{{ $m->MedicineId }}"
                            data-name="{{ e($m->Name) }}"
                            data-generic="{{ e($m->GenericName) }}"
                            data-brand="{{ e($m->BrandName) }}"
                            data-description="{{ e($m->Description) }}"
                            data-price="{{ $m->Price }}"
                            data-prescription="{{ $m->PrescriptionRequired ? '1' : '0' }}"
                            data-manufacturer="{{ e($m->Manufacturer) }}"
                            data-expiry="{{ $m->ExpiryDate }}"
                            data-dosage="{{ e($m->DosageForm) }}"
                            data-strength="{{ e($m->Strength) }}"
                            data-packaging="{{ e($m->Packaging) }}"
                            data-category="{{ $m->MedicineCategoryId }}"
                            data-isactive="{{ $m->IsActive ? '1' : '0' }}"
                            data-image="{{ $m->ImageUrl ? $m->ImageUrl : '' }}">
                            <i class="fas fa-edit"></i>
                        </button>

                        <form action="{{ route('admin.medicines.destroy',$m->MedicineId) }}" method="POST" class="delete-form inline">
                            @csrf @method('DELETE')
                            {{-- <button type="submit" class="text-red-600 hover:text-red-800">
                                <i class="fas fa-trash"></i>
                            </button> --}}

                            <button
                                type="button"
                                class="delete-btn text-red-600 hover:text-red-800"
                                data-id="{{ $m->MedicineId }}"
                                data-name="{{ $m->Name }}">
                                <i class="fas fa-trash"></i>
                            </button>

                        </form>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="9" class="px-6 py-6 text-center text-gray-500">No medicines found.</td></tr>
        @endforelse

        </tbody>
    </table>
</div>

{{-- PAGINATION --}}
<div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
    <div class="text-sm text-gray-600">
        Showing {{ $medicines->firstItem() }} to {{ $medicines->lastItem() }} of {{ $medicines->total() }} results
    </div>
    <div>
        {{ $medicines->links() }}
    </div>
</div>
