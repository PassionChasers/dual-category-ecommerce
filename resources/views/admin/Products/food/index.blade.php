@extends('layouts.admin.app')
@section('title', 'Admin | User Management')

@push('styles')
    <style>
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .thumb {
            width: 64px;
            height: 48px;
            object-fit: cover;
            border-radius: 6px;
        }

        .desc-clickable {
            cursor: pointer;
        }
    </style>
@endpush

@section('contents')
    <div class="flex-1 p-4 md:p-6 bg-gray-50 overflow-auto">
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h2 class="text-2xl font-bold text-gray-800">Restaurants Management</h2>
                <p class="text-gray-600">Manage all Restaurants</p>
            </div>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
                <form id="filter-form" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <input type="text" id="search-input" name="search" value="{{ $search ?? '' }}"
                        placeholder="Search food items..."
                        class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">

                    <select id="category-filter" name="category" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">All Categories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->MenuCategoryId }}" {{ request('category') == $category->MenuCategoryId ? 'selected' : '' }}>{{ $category->Name }}</option>
                        @endforeach
                    </select>

                    <select id="type-filter" name="type" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">All Types</option>
                        <option value="1" {{ request('type') === '1' ? 'selected' : '' }}>Veg</option>
                        <option value="0" {{ request('type') === '0' ? 'selected' : '' }}>Non-Veg</option>
                    </select>

                    <select name="per_page" id="per-page-filter" class="custom-select pl-2 border rounded-md text-sm">
                        @foreach($allowedPerPage as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }} per page</option>
                        @endforeach
                    </select>

                </form>
                <button id="new-user-button"
                    class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Restaurant
                </button>
            </div>
        </div>

        <!-- Table -->
        <div id="items-container" class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800">Food Products List</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">SN</th>
                            <th class="px-4 py-2 text-left">Image</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">Category</th>
                            <th class="px-4 py-2 text-left">Price</th>
                            <th class="px-4 py-2 text-left">Type</th>
                            <th class="px-4 py-2 text-left">Available</th>
                            <th class="px-4 py-2">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($menuItems as $item)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ ($menuItems->currentPage() - 1) * $menuItems->perPage() + $loop->iteration }}
                                </td>

                                <td class="px-4 py-2">
                                    @if($item->ImageUrl)
                                        {{-- <a href="{{$item->ImageUrl}}" target="_blank"> --}}
                                            <img src="{{$item->ImageUrl}}" class="thumb cursor-pointer" onclick="showImage('{{ $item->ImageUrl }}')">
                                        {{-- </a> --}}
                                    @else
                                        <div class="w-12 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">
                                            No
                                        </div>
                                    @endif
                                </td>

                                <td class="px-4 py-2 font-semibold">{{ $item->Name }}</td>
                                <td class="px-4 py-2">
                                    <div class="line-clamp-2 desc-clickable" title="{{ $item->Description }}"
                                        data-desc="{{ $item->Description }}" data-img="{{ $item->ImageUrl }}"
                                        data-name="{{ $item->Name }}">
                                        {{ $item->Description }}
                                    </div>
                                </td>
                                <td class="px-4 py-2">{{ $item->category->Name ?? '-' }}</td>
                                <td class="px-4 py-2">৳ {{ number_format($item->Price, 2) }}</td>
                                <td class="px-4 py-2">{{ $item->IsVeg ? 'Veg' : 'Non-Veg' }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $item->IsAvailable ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $item->IsAvailable ? 'Available' : 'Unavailable' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="edit-btn text-indigo-600 hover:text-indigo-800"
                                            data-id="{{ $item->MenuItemId }}" data-name="{{ $item->Name }}"
                                            data-description="{{ $item->Description }}" data-price="{{ $item->Price }}"
                                            data-category="{{ $item->MenuCategoryId }}" data-isveg="{{ $item->IsVeg }}"
                                            data-isavailable="{{ $item->IsAvailable }}" data-image="{{ $item->ImageUrl }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('menu-items.destroy', $item->MenuItemId) }}"
                                            class="delete-form inline">
                                            @csrf @method('DELETE')
                                            {{-- <button type="submit" class="text-red-600 hover:text-red-800">
                                                <i class="fas fa-trash"></i>
                                            </button> --}}
                                            <button
                                                type="button"
                                                class="delete-btn text-red-600 hover:text-red-800"
                                                data-id="{{ $item->MenuItemId }}"
                                                data-name="{{ $item->Name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="px-4 py-4 text-center text-gray-500">No menu items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- PAGINATION --}}
            <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
                <div class="text-sm text-gray-600">
                    Showing <strong>{{ $menuItems->firstItem() ?? 0 }}</strong> to
                    <strong>{{ $menuItems->lastItem() ?? 0 }}</strong> of <strong>{{ $menuItems->total() }}</strong> results
                </div>
                <div class="mt-3 md:mt-0">
                    {{ $menuItems->links() }}
                </div>
            </div>
        </div>
    </div>


    {{--------------------MODAL----------------
    --------------------------------------- --}}

    <div id="customer-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
        <div class="flex items-center justify-center min-h-screen px-4">
            <!-- Overlay -->
            <div id="modalOverlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px] "></div>

            <!-- Modal content with border -->
            <div class="bg-white rounded-lg shadow-xl  transform transition-all max-w-lg w-full pb-6 relative">
                <div class="bg-indigo-600 py-4 px-6 rounded-t-lg">
                    <!-- Close Button -->
                    <button type="button" id="close-modal-btn"
                        class="absolute top-3 right-3 text-gray-100 hover:text-red-600 text-2xl mr-2">
                        <i class="fas fa-times text-lg"></i>
                    </button>
                    <!-- Modal Title -->
                    <h3 class="text-lg font-medium text-white " id="modal-title"></h3>
                </div>
                <div class="px-6 pt-4">
                    <!-- Form -->
                    <form id="customer-form" method="POST" class="space-y-4" enctype="multipart/form-data">
                        @csrf
                        <input type="hidden" id="form-method" name="_method" value="POST">

                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Name</label>
                            <input type="text" name="Name" placeholder="Enter Food Name" id="customer-name" value=""
                                class="mt-1 block w-full border border-gray-300 rounded-md  px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Description -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="Description" id="customer-description" placeholder="Food Description..."
                                rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md  px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required></textarea>
                        </div>

                        <!-- Price -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Price</label>
                            <input type="number" name="Price" id="customer-price" placeholder="Rs." step="0.01" value=""
                                class="mt-1 block w-full border border-gray-300 rounded-md  px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                        </div>

                        <!-- Category -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Category</label>
                            <select name="MenuCategoryId" id="customer-category"
                                class="mt-1 block w-full border border-gray-300 rounded-md  px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                required>
                                <option value="">Select Category</option>
                                @foreach($categories ?? [] as $category)
                                    <option value="{{ $category->MenuCategoryId }}">{{ $category->Name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Is Veg -->
                        <div class="flex items-center">
                            <input type="checkbox" name="IsVeg" id="customer-isveg" value="1"
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="customer-isveg" class="ml-2 block text-sm text-gray-700">Is Vegetarian</label>
                        </div>

                        <!-- Is Available -->
                        <div class="flex items-center">
                            <input type="checkbox" name="IsAvailable" id="customer-isavailable" value="1" checked
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="customer-isavailable" class="ml-2 block text-sm text-gray-700">Is Available</label>
                        </div>

                        <!-- Image -->
                        {{-- <div>
                            <label class="block text-sm font-medium text-gray-700">Image</label>
                            <input type="file" name="ImageUrl" id="customer-image" accept="image/*"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep existing image</p>
                        </div> --}}

                        <!-- Image URL -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Image URL</label>
                            <input type="url" name="ImageUrl" id="customer-image-url" placeholder="Enter image URL"
                                class="mt-1 block w-full border border-gray-300 rounded-md  px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <p class="mt-1 text-xs text-gray-500">Leave blank to keep existing image</p>
                            <img id="image-preview"
                                class="mt-2 w-28 h-28 rounded-md border border-gray-300 object-cover hidden"
                                alt="Image Preview" />
                        </div>

                        <!-- Buttons -->
                        <div class="flex justify-end space-x-2">
                            <button type="button" id="cancel-btn"
                                class="px-4 py-2 bg-gray-200 rounded-md hover:bg-red-500 hover:text-white">Cancel</button>
                            <button type="submit"
                                class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

    <!-- Preview Script -->
    {{--
    <script>
        const imageUrlInput = document.getElementById('customer-image-url');
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
    </script> --}}

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('customer-modal');
            const overlay = document.getElementById('modalOverlay');
            const newBtn = document.getElementById('new-user-button');
            const cancelBtn = document.getElementById('cancel-btn');
            const closeBtn = document.getElementById('close-modal-btn');
            const form = document.getElementById('customer-form');
            const modalTitle = document.getElementById('modal-title');
            const methodInput = document.getElementById('form-method');
            const nameInput = document.getElementById('customer-name');
            const descriptionInput = document.getElementById('customer-description');
            const priceInput = document.getElementById('customer-price');
            const categoryInput = document.getElementById('customer-category');
            const isVegInput = document.getElementById('customer-isveg');
            const isAvailableInput = document.getElementById('customer-isavailable');
            const imageInput = document.getElementById('customer-image');

            // AJAX SEARCH & FILTERS
            const filterForm = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const categoryFilter = document.getElementById('category-filter');
            const typeFilter = document.getElementById('type-filter');
            const itemsContainer = document.getElementById('items-container');
            const perPageFilter = document.getElementById('per-page-filter');

            const performSearch = () => {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `{{ route('menu-items.index') }}?${params.toString()}`;

                console.log('Fetching:', url);

                fetch(url, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'text/html'
                    }
                })
                    .then(response => {
                        console.log('Response status:', response.status);
                        return response.text();
                    })
                    .then(html => {
                        console.log('HTML received, length:', html.length);
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(html, 'text/html');
                        const newContainer = newDoc.getElementById('items-container');
                        console.log('Container found:', !!newContainer);
                        if (newContainer) {
                            itemsContainer.innerHTML = newContainer.innerHTML;
                            reattachEventListeners();
                            console.log('Updated table');
                        }
                    })
                    .catch(error => console.error('Error:', error));
            };
            // open model
            function openModal() {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden'); // 🔒 lock background scroll
            }

            // close model
            function closeModal() {
                modal.classList.add('hidden');
               ocument.body.classList.remove('overflow-hidden');  d// 🔓 unlock scroll
            }

            overlay.addEventListener("click", closeModal);

            // Debounce for search input
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(performSearch, 500);
            });

            // Immediate search for dropdowns
            categoryFilter.addEventListener('change', performSearch);
            typeFilter.addEventListener('change', performSearch);
            perPageFilter.addEventListener('change',performSearch);

            const reattachEventListeners = () => {
                // Edit buttons
                document.querySelectorAll('.edit-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        modalTitle.innerText = 'Edit Item';
                        form.action = `/menu-items/${btn.dataset.id}`;
                        methodInput.value = 'PUT';
                        nameInput.value = btn.dataset.name;
                        descriptionInput.value = btn.dataset.description;
                        priceInput.value = btn.dataset.price;
                        categoryInput.value = btn.dataset.category;
                        isVegInput.checked = btn.dataset.isveg == 1 || btn.dataset.isveg === 'true';
                        isAvailableInput.checked = btn.dataset.isavailable == 1 || btn.dataset.isavailable === 'true';
                        imageInput.value = '';
                        openModal();

                    });
                });

                // Delete forms
                document.querySelectorAll('.delete-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const id = btn.dataset.id;
                        const name = btn.dataset.name;

                        Swal.fire({
                            title: 'Delete Item?',
                            text: `Are you sure you want to delete "${name}"?`,
                            icon: 'warning',
                            showCancelButton: true,
                            confirmButtonColor: '#dc2626',
                            cancelButtonColor: '#6b7280',
                            confirmButtonText: 'Yes, delete it'
                        }).then((result) => {
                            if (!result.isConfirmed) return;

                            fetch(`/menu-items/${id}`, {
                                method: 'DELETE',
                                headers: {
                                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                    'X-Requested-With': 'XMLHttpRequest',
                                    'Accept': 'application/json'
                                }
                            })
                            .then(res => res.json())
                            .then(data => {
                                if (data.success) {
                                    Swal.fire({
                                        toast: true,
                                        position: 'top-end',
                                        icon: 'success',
                                        title: data.message || 'Item deleted',
                                        showConfirmButton: false,
                                        timer: 2500
                                    });

                                    performSearch(); //reload list (pagination + filters preserved)
                                } else {
                                    Swal.fire('Error', data.message || 'Delete failed', 'error');
                                }
                            })
                            .catch(() => {
                                Swal.fire('Error', 'Server error occurred', 'error');
                            });
                        });
                    });
                });


                // Description clickable
                document.querySelectorAll('.desc-clickable').forEach(el => {
                    el.addEventListener('click', () => {
                        const desc = el.dataset.desc || el.textContent;
                        const img = el.dataset.img || '';
                        const name = el.dataset.name || '';
                        let html = '';
                        if (img) html += `<img src="https://pcsdecom.azurewebsites.net${img}" alt="${name}" style="max-width:100%;display:block;margin-bottom:8px;border-radius:6px;">`;
                        html += `<div style="text-align:left">${desc}</div>`;
                        Swal.fire({
                            title: name || 'Description',
                            html: html,
                            width: 600
                        });
                    });
                });
            };


            // Open modal for create
            newBtn.addEventListener('click', () => {
                const imageUrlInput = document.getElementById('customer-image-url');
                const imagePreview = document.getElementById('image-preview');

                imageUrlInput.addEventListener('input', () => {
                    const url = imageUrlInput.value.trim(); // just for preview
                    if (url) {
                        imagePreview.src = url;
                        imagePreview.classList.remove('hidden');
                    } else {
                        imagePreview.src = '';
                        imagePreview.classList.add('hidden');
                    }
                });

                modalTitle.innerText = 'New Item';
                form.action = "{{ route('menu-items.store') }}";
                methodInput.value = 'POST';
                nameInput.value = '';
                descriptionInput.value = '';
                priceInput.value = '';
                categoryInput.value = '';
                isVegInput.checked = false;
                isAvailableInput.checked = true;
                // imageInput.value = '';
                 openModal();
            });

            // Cancel and close
            cancelBtn.addEventListener('click', () => closeModal());
            closeBtn.addEventListener('click', () => closeModal());

            // Initial attachment of event listeners
            reattachEventListeners();

        });

        // Toast alerts
        @if(session('success'))
            Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif

        @if(session('error'))
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif

        @if($errors->any())
            Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ $errors->first() }}", showConfirmButton: false, timer: 3000, timerProgressBar: true });
        @endif
    </script>
@endpush