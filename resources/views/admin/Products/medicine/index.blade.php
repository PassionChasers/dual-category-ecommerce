@extends('layouts.admin.app')

@if(auth()->user()->Role === 4)
    @section('title', 'Admin | Medicines')
@elseif(auth()->user()->Role === 2)
   @section('title', 'Business Admin | Medicines') 
@endif

@push('styles')
    <style>
        .thumb {
            width: 64px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
        }
    </style>
@endpush

@section('contents')

    <div class="flex-1 overflow-auto bg-gray-50 p-4 md:p-6">
        {{-- HEADER --}}
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h1 class="text-2xl font-semibold text-gray-800">Medicines</h1>
                <p class="text-gray-500 mt-1">Create, view and manage medicines</p>
            </div>

            {{-- FILTERS --}}
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
                <form id="filter-form" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <div class=" group border b rounded-lg focus-within:border-2 ">
                        <input type="text" id="search-input" name="search" placeholder="Search by medicines name........"
                            value="{{ request('search') }}"
                            class="border border-none focus:outline-none px-2 py-2 " 
                        />
                        <button type="submit" onclick="showLoader()" class="px-3 py-2 rounded-r-lg bg-gray-200 hover:bg-gray-400 ">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <select id="category-filter" name="category" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">All categories</option>
                        @foreach ($categories as $cat)
                            <option value="{{ $cat->MedicineCategoryId }}"
                                {{ request('category') == $cat->MedicineCategoryId ? 'selected' : '' }}>
                                {{ $cat->Name }}
                            </option>
                        @endforeach
                    </select>

                    <select id="status-filter" name="status" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">All Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <select name="per_page" id="per-page-filter" class="px-3 py-2 border rounded-md text-sm">
                        @foreach ($allowedPerPage as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}
                                per page</option>
                        @endforeach
                    </select>

                </form>

                <button id="open-create-modal"
                    class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Medicine
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div id="medicines-container" class="bg-white shadow rounded-lg">
            @include('admin.Products.medicine.medicines_table')
        </div>
    </div>

    {{-- ===================== MODAL ===================== --}}
    <div id="medicine-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4 overflow-auto">
        <div id="model-overlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px]"></div>

        <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full z-10">
            <div class="px-6 py-4 bg-indigo-600 flex justify-between items-center rounded-t-lg">
                <h3 id="modal-title" class="text-lg text-white font-semibold">New Medicine</h3>
                <button id="close-modal" class="text-white hover:text-red-500 text-2xl px-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            {{-- ERRORS --}}
            <div id="form-errors">
                @if ($errors->any())
                    <div class="mb-4 bg-red-100 text-red-700 p-3 rounded">
                        <ul class="list-disc pl-5">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>

            {{-- FORM --}}
            <form id="medicine-form" method="POST" class="px-6 py-6 space-y-4">
                @csrf
                <input type="hidden" id="medicine-id" name="id" value="">
                <input type="hidden" id="method-field" name="_method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Name<span class="text-red-500">*</span></label>
                        <input id="field-name" name="Name" value="{{ old('Name') }}" placeholder="Enter Medicine Name"
                            type="text" required class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Category<span class="text-red-500">*</span></label>
                        <select id="field-category" name="MedicineCategoryId"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" required>
                            <option value="">Select category</option>
                            @foreach ($categories as $cat)
                                <option value="{{ $cat->MedicineCategoryId }}"
                                    {{ old('MedicineCategoryId') == $cat->MedicineCategoryId ? 'selected' : '' }}>
                                    {{ $cat->Name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Brand / Generic</label>
                        <input id="field-brand" name="BrandName" value="{{ old('BrandName') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" placeholder="Brand">
                        <input id="field-generic" name="GenericName" value="{{ old('GenericName') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" placeholder="Generic">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Description<span class="text-red-500">*</span></label>
                        <textarea id="field-description" name="Description" placeholder="Description of Medicine..." rows="3"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" required>
                        {{ old('Description') }}
                    </textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Price<span class="text-red-500">*</span></label>
                        <input id="field-price" name="Price" value="{{ old('Price') }}" type="number" step="0.01"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Expiry Date</label>
                        <input id="field-expiry" name="ExpiryDate" value="{{ old('ExpiryDate') }}" type="date"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="field-prescription" name="PrescriptionRequired" type="checkbox" value="1"
                            {{ old('PrescriptionRequired') ? 'checked' : '' }} class="h-4 w-4">
                        <label class="text-sm">Prescription Required</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Manufacturer</label>
                        <input id="field-manufacturer" name="Manufacturer" value="{{ old('Manufacturer') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Dosage Form<span class="text-red-500">*</span></label>
                        <input id="field-dosage" name="DosageForm" value="{{ old('DosageForm') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" required>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Strength</label>
                        <input id="field-strength" name="Strength" value="{{ old('Strength') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Packaging<span class="text-red-500">*</span></label>
                        <input id="field-packaging" name="Packaging" value="{{ old('Packaging') }}"
                            class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" required>
                    </div>

                    {{-- IMAGE URL --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Image URL<span class="text-red-500">*</span></label>
                        <input id="field-image-url" name="ImageUrl" value="{{ old('ImageUrl') }}" type="url"
                            placeholder="Enter image URL"
                            class="mt-1 block w-full border border-gray-400 px-3 py-2 rounded-md" required>
                        <img id="image-preview"
                            class="mt-2 w-28 h-28 rounded-md border border-gray-400 object-cover {{ old('ImageUrl') ? '' : 'hidden' }}"
                            alt="Image Preview" />
                    </div>

                    {{-- ACTIVE --}}
                    <div class="md:col-span-2 flex items-center gap-2">
                        <input id="field-isactive" name="IsActive" type="checkbox" value="1"
                            {{ old('IsActive', 1) ? 'checked' : '' }} class="h-4 w-4">
                        <label class="text-sm">Active</label>
                    </div>
                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="modal-cancel"
                        class="px-4 py-2 hover:bg-red-500 bg-gray-200 rounded hover:text-white">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- This error for Create form only --}}
    @if ($errors->any() && !session('edit_id'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('medicine-modal');
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            });
        </script>
    @endif

    {{-- This error for Edit form restore --}}
    @if ($errors->any() && session('edit_id'))
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('medicine-modal');
                const form = document.getElementById('medicine-form');
                const methodField = document.getElementById('method-field');
                const medicineIdField = document.getElementById('medicine-id');

                modal.classList.remove('hidden');
                modal.classList.add('flex');

                //RESTORE EDIT MODE
                const id = "{{ session('edit_id') }}";
                form.action = `{{ url('admin/medicines') }}/${id}`;
                methodField.value = 'PUT';
                medicineIdField.value = id;
            });
        </script>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            /* ================= IMAGE URL PREVIEW ================= */
            const imageUrlInput = document.getElementById('field-image-url');
            const imagePreview = document.getElementById('image-preview');

            imageUrlInput.addEventListener('input', () => {
                const url = imageUrlInput.value.trim();
                if (url) {
                    imagePreview.src = url;
                    imagePreview.classList.remove('hidden');
                } else {
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                }
            });

            /* ================= MODAL ================= */
            const modal = document.getElementById('medicine-modal');
            const overlay = document.getElementById('model-overlay');
            const openCreate = document.getElementById('open-create-modal');
            const modalTitle = document.getElementById('modal-title');
            const closeModalBtns = [document.getElementById('close-modal'), document.getElementById(
            'modal-cancel')];
            const form = document.getElementById('medicine-form');
            const methodField = document.getElementById('method-field');
            const idField = document.getElementById('medicine-id');

            const nameField = document.getElementById('field-name');
            const categoryField = document.getElementById('field-category');
            const brandField = document.getElementById('field-brand');
            const genericField = document.getElementById('field-generic');
            const descField = document.getElementById('field-description');
            const priceField = document.getElementById('field-price');
            const expiryField = document.getElementById('field-expiry');
            const prescriptionField = document.getElementById('field-prescription');
            const manufacturerField = document.getElementById('field-manufacturer');
            const dosageField = document.getElementById('field-dosage');
            const strengthField = document.getElementById('field-strength');
            const packagingField = document.getElementById('field-packaging');
            const isActiveField = document.getElementById('field-isactive');

            // Open Modal
            const openModal = () => {
                modal.classList.remove('hidden');
                modal.classList.add('flex');
            };

            // Close Modal
            const closeModal = () => {
                modal.classList.add('hidden');
                form.reset();
                methodField.value = 'POST';
                idField.value = '';

                imagePreview.src = '';
                imagePreview.classList.add('hidden');

                form.action = "{{ route('admin.medicines.store') }}";
            };

            // Clear Form Errors
            function clearFormErrors() {
                const errorBox = document.getElementById('form-errors');
                if (errorBox) {
                    errorBox.innerHTML = '';
                }
            }

            // ================= CREATE BUTTON ================= //
            openCreate.addEventListener('click', () => {

                form.reset();
                clearFormErrors();

                modalTitle.innerText = 'New Medicine';
                isActiveField.checked = true;
                methodField.value = 'POST';
                idField.value = '';

                imageUrlInput.value = '';
                imagePreview.src = '';
                imagePreview.classList.add('hidden');

                form.action = "{{ route('admin.medicines.store') }}";
                openModal();
            });


            // ================= CLOSE BUTTONS ================= //
            closeModalBtns.forEach(btn => btn.addEventListener('click', closeModal));
            overlay.addEventListener('click', closeModal);


            /* ================= EDIT BUTTONS ================= */
            const attachEditButtons = () => {
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.onclick = function() {

                        clearFormErrors();

                        modalTitle.innerText = 'Edit Medicine';
                        methodField.value = 'PUT';
                        idField.value = this.dataset.id;

                        nameField.value = this.dataset.name || '';
                        brandField.value = this.dataset.brand || '';
                        genericField.value = this.dataset.generic || '';
                        descField.value = this.dataset.description || '';
                        priceField.value = this.dataset.price || '';
                        prescriptionField.checked = this.dataset.prescription === '1';
                        manufacturerField.value = this.dataset.manufacturer || '';
                        expiryField.value = this.dataset.expiry || '';
                        dosageField.value = this.dataset.dosage || '';
                        strengthField.value = this.dataset.strength || '';
                        packagingField.value = this.dataset.packaging || '';
                        categoryField.value = this.dataset.category || '';
                        isActiveField.checked = this.dataset.isactive === '1';

                        if (this.dataset.image) {
                            imagePreview.src = this.dataset.image;
                            imagePreview.classList.remove('hidden');
                            imageUrlInput.value = this.dataset.image;
                        }

                        form.action = `{{ url('admin/medicines') }}/${this.dataset.id}`;
                        openModal();
                    };
                });

                    // DELETE Medicines BUTTONS
                document.querySelectorAll('.delete-medicine-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const medicineName = btn.dataset.name;
                        const deleteForm = btn.closest('.delete-form');

                        Swal.fire({
                            title: 'Delete Medicine',
                            text: `Are you sure you want to delete "${medicineName}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, delete it!'
                        }).then((result) => {
                            if (result.isConfirmed) {
                                deleteForm.submit();
                            }
                        });
                    });
                });
            };

            /* ================= DELETE BUTTONS ================= */
            // document.addEventListener('click', function(e) {
            //     const btn = e.target.closest('.delete-btn');
            //     if (!btn) return;

            //     const form = btn.closest('form');

            //     Swal.fire({
            //         title: 'Delete Medicine?',
            //         text: 'This action cannot be undone!',
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#dc2626',
            //         cancelButtonColor: '#6b7280',
            //         confirmButtonText: 'Yes, delete it'
            //     }).then((result) => {
            //         if (result.isConfirmed) {
            //             showLoader(); // loader AFTER confirmation
            //             form.submit(); // now submit safely
            //         }
            //     });
            // });


            //     document.addEventListener('click', function (e) {
            //     const btn = e.target.closest('.delete-btn');
            //     if (!btn) return;

            //     const id = btn.dataset.id;
            //     const name = btn.dataset.name;

            //     Swal.fire({
            //         title: 'Delete Medicine?',
            //         text: `Are you sure you want to delete "${name}"?`,
            //         icon: 'warning',
            //         showCancelButton: true,
            //         confirmButtonColor: '#dc2626',
            //         cancelButtonColor: '#6b7280',
            //         confirmButtonText: 'Yes, delete it'
            //     }).then((result) => {
            //         if (!result.isConfirmed) return;

            //         fetch(`/admin/medicines/${id}`, {
            //             method: 'DELETE',
            //             headers: {
            //                 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            //                 'X-Requested-With': 'XMLHttpRequest',
            //                 'Accept': 'application/json'
            //             }
            //         })
            //         .then(res => res.json())
            //         .then(data => {
            //             if (data.success) {
            //                 Swal.fire({
            //                     toast: true,
            //                     position: 'top-end',
            //                     icon: 'success',
            //                     title: data.message || 'Medicine deleted',
            //                     showConfirmButton: false,
            //                     timer: 2500
            //                 });

            //                 performSearch(); //reload table safely
            //                 // window.location.reload();

            //             } else {
            //                 Swal.fire('Error', data.message || 'Delete failed', 'error');
            //             }
            //         })
            //         .catch(() => {
            //             Swal.fire('Error', 'Server error occurred', 'error');
            //         });
            //     });
            // });



            /* ================= AJAX SEARCH / FILTER ================= */
            const filterForm = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const statusFilter = document.getElementById('status-filter');
            const medicinesContainer = document.getElementById('medicines-container');
            const perPageFilter = document.getElementById('per-page-filter');

            const performSearch = () => {
                const params = new URLSearchParams(new FormData(filterForm));

                fetch(`{{ route('admin.medicines.index') }}?${params.toString()}`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(res => res.text())
                    .then(html => {
                        medicinesContainer.innerHTML = html;
                        attachEditButtons();
                    })
                    .catch(() => {
                        medicinesContainer.innerHTML = `
                        <div class="text-center py-16 text-red-600">Error loading data.</div>
                    `;
                    });
            };

            let searchTimeout;
            // searchInput.addEventListener('input',()=>{
            //     clearTimeout(searchTimeout); 
            //     searchTimeout=setTimeout(performSearch,500);
            // });

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            categoryFilter.addEventListener('change', performSearch);
            statusFilter.addEventListener('change', performSearch);
            perPageFilter.addEventListener('change', performSearch);

            attachEditButtons();
        });
    </script>
@endpush
