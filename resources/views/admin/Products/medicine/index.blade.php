@extends('layouts.admin.app')

@section('title', 'Admin | Medicines')

@push('styles')
    <style>
        .thumb { width:64px; height:48px; object-fit:cover; border-radius:6px; }
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
                    <div class="flex-1 min-w-[150px]">
                        <input
                            type="text"
                            id="search-input"
                            name="search"
                            placeholder="Search medicines..."
                            value="{{ request('search') }}"
                            class="w-full px-3 py-2 border rounded-md focus:ring-indigo-500 focus:border-indigo-500"
                        />
                    </div>
                    
                    <select id="category-filter" name="category" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">All categories</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->MedicineCategoryId }}" {{ request('category') == $cat->MedicineCategoryId ? 'selected' : '' }}>
                                {{ $cat->Name }}
                            </option>
                        @endforeach
                    </select>

                    <select id="status-filter" name="status" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">Status</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                </form>

                <button id="open-create-modal" class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Medicine
                </button>
            </div>
        </div>

        {{-- TABLE --}}
        <div id="medicines-container" class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800">Medicine List</h2>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full text-sm divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-3 text-left">#</th>
                        <th class="px-4 py-3 text-left">Image</th>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Category</th>
                        <th class="px-4 py-3 text-left">Price</th>
                        <th class="px-4 py-3 text-left">Prescription</th>
                        <th class="px-4 py-3 text-left">Expiry</th>
                        <th class="px-4 py-3 text-left">Status</th>
                        <th class="px-4 py-3 text-right">Actions</th>
                    </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">

                    @forelse($medicines as $i => $m)
                        <tr>
                            <td class="px-4 py-3">{{ $medicines->firstItem() + $i }}</td>

                            <td class="px-4 py-3">
                                @if($m->ImageUrl)
                                    <img src="https://pcsdecom.azurewebsites.net{{$m->ImageUrl}}" class="thumb">
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
                            <td class="px-4 py-3">{{ $m->ExpiryDate ?: '-' }}</td>

                            <td class="px-4 py-3">
                                <span class="px-2 py-1 rounded text-xs {{ $m->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $m->IsActive ? 'Active' : 'Inactive' }}
                                </span>
                            </td>

                            <td class="px-4 py-3">
                                <div class="flex items-center justify-center gap-3">
                                    <button class="edit-btn text-indigo-600 hover:text-indigo-800" 
                                        data-id="{{ $m->MedicineId }}"
                                        data-name="{{ e($m->Name) }}"
                                        data-generic="{{ e($m->GenericName) }}"
                                        data-brand="{{ e($m->BrandName) }}"
                                        data-description="{{ e($m->Description) }}"
                                        data-price="{{ $m->Price }}"
                                        data-mrp="{{ $m->MRP }}"
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
                                        <button type="submit" class="text-red-600 hover:text-red-800">
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
        </div>
    </div>

    {{-- ======================================================================= --}}
    {{-- =========================== FULL MODAL ================================= --}}
    {{-- ======================================================================= --}}

    <div id="medicine-modal" class="fixed inset-0 z-50 hidden items-center justify-center px-4">
        <div id="model-overlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px]"></div>

        <div class="bg-white rounded-lg shadow-lg max-w-3xl w-full z-10">
            <div class="px-6 py-4 bg-indigo-600 flex justify-between items-center rounded-t-lg">
                <h3 id="modal-title" class="text-lg text-white font-semibold">New Medicine</h3>
                <button id="close-modal" class="text-white hover:text-red-500 text-2xl  px-1">
                    <i class="fas fa-times"></i>
                </button>
            </div>

            <form id="medicine-form" method="POST" enctype="multipart/form-data"
                class="px-6 py-6 space-y-4">
                @csrf
                <input type="hidden" id="medicine-id" name="id" value="">
                <input type="hidden" id="method-field" name="_method" value="POST">

                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Name</label>
                        <input id="field-name" name="Name" placeholder="Enter Medicine Name" type="text" required class="mt-1 block w-full  border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Category</label>
                        <select id="field-category" name="MedicineCategoryId" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                            <option value="">Select category</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->MedicineCategoryId }}">{{ $cat->Name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Brand / Generic</label>
                        <input id="field-brand" name="BrandName" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" placeholder="Brand">
                        <input id="field-generic" name="GenericName" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2" placeholder="Generic">
                    </div>

                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium">Description</label>
                        <textarea id="field-description" name="Description" placeholder="Description of Medicine..." rows="3" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2"></textarea>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Price</label>
                        <input id="field-price" name="Price" type="number" step="0.01" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">MRP</label>
                        <input id="field-mrp" name="MRP" placeholder="Rs." type="number" step="0.01" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Expiry Date</label>
                        <input id="field-expiry" name="ExpiryDate" type="date" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div class="flex items-center gap-2">
                        <input id="field-prescription" name="PrescriptionRequired" type="checkbox" class="h-4 w-4">
                        <label class="text-sm">Prescription Required</label>
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Manufacturer</label>
                        <input id="field-manufacturer" name="Manufacturer" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Dosage Form</label>
                        <input id="field-dosage" name="DosageForm" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Strength</label>
                        <input id="field-strength" name="Strength" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    <div>
                        <label class="block text-sm font-medium">Packaging</label>
                        <input id="field-packaging" name="Packaging" class="mt-1 block w-full border border-gray-400 rounded-md px-3 py-2">
                    </div>

                    {{-- IMAGE --}}
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium">Image</label>
                        <input id="field-image" name="image" type="file" accept="image/*" class="mt-1 block w-full border border-gray-400 px-3 py-2 rounded-md">
                        <img id="image-preview" class="mt-2 w-28 h-28 rounded-md border border-gray-400 object-cover hidden"/>
                    </div>

                    {{-- ACTIVE --}}
                    <div class="md:col-span-2 flex items-center gap-2">
                        <input id="field-isactive" name="IsActive" type="checkbox" value="1" class="h-4 w-4">
                        <label class="text-sm">Active</label>
                    </div>

                </div>

                <div class="flex justify-end gap-2">
                    <button type="button" id="modal-cancel" class="px-4 py-2 hover:bg-red-500 bg-gray-200 rounded hover:text-white">
                        Cancel
                    </button>

                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded hover:bg-indigo-700">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded',()=>{

            const modal=document.getElementById('medicine-modal');
            const overlay= document.getElementById('model-overlay');
            const openCreate=document.getElementById('open-create-modal');
            const modalTitle=document.getElementById('modal-title');
            const closeModalBtns=[document.getElementById('close-modal'),document.getElementById('modal-cancel')];

            const form=document.getElementById('medicine-form');
            const methodField=document.getElementById('method-field');
            const idField=document.getElementById('medicine-id');

            const nameField=document.getElementById('field-name');
            const categoryField=document.getElementById('field-category');
            const brandField=document.getElementById('field-brand');
            const genericField=document.getElementById('field-generic');
            const descField=document.getElementById('field-description');
            const priceField=document.getElementById('field-price');
            const mrpField=document.getElementById('field-mrp');
            const expiryField=document.getElementById('field-expiry');
            const prescriptionField=document.getElementById('field-prescription');
            const manufacturerField=document.getElementById('field-manufacturer');
            const dosageField=document.getElementById('field-dosage');
            const strengthField=document.getElementById('field-strength');
            const packagingField=document.getElementById('field-packaging');
            const isActiveField=document.getElementById('field-isactive');
            const imageInput=document.getElementById('field-image');
            const imagePreview=document.getElementById('image-preview');

            // AJAX SEARCH & FILTERS
            const filterForm=document.getElementById('filter-form');
            const searchInput=document.getElementById('search-input');
            const categoryFilter=document.getElementById('category-filter');
            const statusFilter=document.getElementById('status-filter');
            const medicinesContainer=document.getElementById('medicines-container');

            const performSearch=()=>{
                const formData=new FormData(filterForm);
                const params=new URLSearchParams(formData);

                fetch(`{{ route('admin.medicines.index') }}?${params.toString()}`,{
                    headers:{
                        'X-Requested-With':'XMLHttpRequest',
                        'Accept':'text/html'
                    }
                })
                .then(response=>response.text())
                .then(html=>{
                    const parser=new DOMParser();
                    const newDoc=parser.parseFromString(html,'text/html');
                    const newContainer=newDoc.getElementById('medicines-container');
                    if(newContainer){
                        medicinesContainer.innerHTML=newContainer.innerHTML;
                        reattachEventListeners();
                    }
                })
                .catch(error=>console.error('Error:',error));
            };

            // Debounce for search input
            let searchTimeout;
            searchInput.addEventListener('input',()=>{
                clearTimeout(searchTimeout);
                searchTimeout=setTimeout(performSearch,500);
            });

            // Immediate search for dropdowns
            categoryFilter.addEventListener('change',performSearch);
            statusFilter.addEventListener('change',performSearch);

            const reattachEventListeners=()=>{
                document.querySelectorAll('.edit-btn').forEach(btn=>{
                    btn.addEventListener('click',function(){
                        modalTitle.innerText='Edit Medicine';
                        methodField.value='PUT';
                        idField.value=this.dataset.id;

                        nameField.value=this.dataset.name;
                        brandField.value=this.dataset.brand;
                        genericField.value=this.dataset.generic;
                        descField.value=this.dataset.description;
                        priceField.value=this.dataset.price;
                        mrpField.value=this.dataset.mrp;
                        prescriptionField.checked=this.dataset.prescription==='1';
                        manufacturerField.value=this.dataset.manufacturer;
                        expiryField.value=this.dataset.expiry;
                        dosageField.value=this.dataset.dosage;
                        strengthField.value=this.dataset.strength;
                        packagingField.value=this.dataset.packaging;
                        categoryField.value=this.dataset.category;
                        isActiveField.checked=this.dataset.isactive==='1';

                        if(this.dataset.image){
                            imagePreview.src=this.dataset.image;
                            imagePreview.classList.remove('hidden');
                        }

                        form.action = `{{ url('admin/medicines') }}/${this.dataset.id}`;
                        openModal();
                    });
                });

                document.querySelectorAll('.delete-form').forEach(frm=>{
                    frm.addEventListener('submit',function(e){
                        e.preventDefault();
                        let form=this;

                        Swal.fire({
                            title:'Are you sure?',
                            text:'This will permanently delete the medicine.',
                            icon:'warning',
                            showCancelButton:true,
                            confirmButtonColor:'#e3342f',
                            cancelButtonColor:'#6c757d',
                            confirmButtonText:'Yes, delete'
                        }).then(r=>{
                            if(r.isConfirmed) form.submit();
                        });
                    });
                });
            };

            const openModal=()=>{
                modal.classList.remove('hidden'); 
                modal.classList.add('flex'); 
            };

            const closeModal=()=>{
                modal.classList.add('hidden');
                form.reset();
                methodField.value="POST";
                idField.value="";
                imagePreview.classList.add('hidden');
                form.action="{{ route('admin.medicines.store') }}";
            };
            
            overlay.addEventListener('click', closeModal)

            openCreate.addEventListener('click', () => {
                modalTitle.innerText = "New Medicine";
                isActiveField.checked = true;
                methodField.value = 'POST';                 
                idField.value = '';
                form.action = "{{ route('admin.medicines.store') }}";
                openModal();
            });

            closeModalBtns.forEach(btn=>btn.addEventListener('click',closeModal));

            // PREVIEW IMAGE
            imageInput.addEventListener('change',()=>{
                if(imageInput.files[0]){
                    imagePreview.src=URL.createObjectURL(imageInput.files[0]);
                    imagePreview.classList.remove('hidden');
                }
            });

            // Initial attachment of event listeners
            reattachEventListeners();

        });
    </script>
@endpush
