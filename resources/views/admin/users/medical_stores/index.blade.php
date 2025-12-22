@extends('layouts.admin.app')
@section('title', 'Admin | Medicalstores Management')

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">

    {{-- HEADER --}}
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">Medicalstores Management</h2>
            <p class="text-gray-600">Manage all Medical Stores</p>
        </div>
    </div>

    {{-- TABLE --}}
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">ID</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Store Name</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Owner</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">License</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Radius (KM)</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Delivery Fee</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Min Order</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Featured</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                @forelse($medicalstores as $store)
                    <tr>
                        <td class="px-4 py-2">{{ $store->MedicalStoreId }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $store->Name }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $store->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $store->LicenseNumber ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $store->RadiusKm }}</td>
                        <td class="px-4 py-2 text-gray-600">Rs {{ $store->DeliveryFee }}</td>
                        <td class="px-4 py-2 text-gray-600">Rs {{ $store->MinOrder }}</td>

                        <td class="px-4 py-2 text-gray-600">
                            @if($store->IsActive)
                                <span class="px-2 py-1 text-xs bg-green-100 text-green-700 rounded">Active</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-red-100 text-red-700 rounded">Inactive</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-gray-600">
                            @if($store->IsFeatured)
                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-700 rounded">Yes</span>
                            @else
                                <span class="px-2 py-1 text-xs bg-gray-100 rounded">No</span>
                            @endif
                        </td>

                        <td class="px-4 py-2 text-gray-600">
                            <button class="edit-btn text-indigo-600 hover:text-indigo-800" 
                                data-id="{{ $store->MedicalStoreId }}"
                                data-name="{{ $store->Name }}"
                                data-license="{{ $store->LicenseNumber }}"
                                data-radius="{{ $store->RadiusKm }}"
                                data-fee="{{ $store->DeliveryFee }}"
                                data-min="{{ $store->MinOrder }}"
                                data-active="{{ $store->IsActive }}"
                                data-featured="{{ $store->IsFeatured }}"
                            >
                                <i class="fas fa-edit"></i>
                            </button>

                            <form method="POST" action="{{ route('admin.medicalstores.destroy', $store->MedicalStoreId) }}" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="text-center py-4">No medical stores found.</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="p-4 bg-gray-50">
            {{ $medicalstores->links() }}
        </div>
    </div>
</div>

{{-- MODAL --}}
<div id="medicalstore-modal" class="fixed inset-0 hidden z-50">
    <div class="flex items-center justify-center min-h-screen bg-black bg-opacity-40">
        <div class="bg-white p-6 rounded-lg w-full max-w-lg relative">

            <button id="close-modal-btn" class="absolute top-2 right-2 text-gray-500">
                <i class="fas fa-times"></i>
            </button>

            <h3 class="text-lg font-semibold mb-4">Edit Medical Store</h3>

            <form id="medicalstore-form" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label>Store Name</label>
                    <input type="text" name="Name" id="store-name" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label>License Number</label>
                    <input type="text" name="LicenseNumber" id="store-license" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label>Delivery Radius (KM)</label>
                    <input type="number" step="0.1" name="RadiusKm" id="store-radius" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label>Delivery Fee</label>
                    <input type="number" step="0.01" name="DeliveryFee" id="store-fee" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3">
                    <label>Min Order</label>
                    <input type="number" step="0.01" name="MinOrder" id="store-min" class="w-full border rounded px-3 py-2">
                </div>

                <div class="mb-3 flex gap-4">
                    <label>
                        <input type="checkbox" name="IsActive" id="store-active"> Active
                    </label>
                    <label>
                        <input type="checkbox" name="IsFeatured" id="store-featured"> Featured
                    </label>
                </div>

                <div class="text-right">
                    <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-200 rounded">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT --}}
<script>


// for edit modal
document.querySelectorAll('.edit-btn').forEach(btn => {
    btn.onclick = () => {
        document.getElementById('medicalstore-form').action =
            `/admin/medicalstores/${btn.dataset.id}`;

        document.getElementById('store-name').value = btn.dataset.name;
        document.getElementById('store-license').value = btn.dataset.license;
        document.getElementById('store-radius').value = btn.dataset.radius;
        document.getElementById('store-fee').value = btn.dataset.fee;
        document.getElementById('store-min').value = btn.dataset.min;

        document.getElementById('store-active').checked = btn.dataset.active == 1;
        document.getElementById('store-featured').checked = btn.dataset.featured == 1;

        document.getElementById('medicalstore-modal').classList.remove('hidden');
    };
});

document.getElementById('cancel-btn').onclick =
document.getElementById('close-modal-btn').onclick = () => {
    document.getElementById('medicalstore-modal').classList.add('hidden');
};

// SweetAlert for delete confirmation
document.querySelectorAll('.delete-form').forEach(f => {
    f.addEventListener('submit', function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'Are you sure?',
            text: "This action cannot be undone!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e3342f',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) f.submit();
        });
    });
});
</script>

@endsection
