@extends('layouts.admin.app')

@php use Illuminate\Support\Str; @endphp

@push('styles')
    <style>
        /* Match product list row height (image row ~48px) */
        .category-table td {
            height: 48px;
        }
    </style>
@endpush

{{-- @section('title', 'Admin | Product Category') --}}
@if(auth()->user()->Role === 4)
{
    @section('title', 'Admin | Food Category')
}
@elseif(auth()->user()->Role === 3)
{
   @section('title', 'Business Admin | Food Category') 
}
@endif

@section('contents')
    <div class="flex-1 p-4 md:p-6 bg-gray-50 overflow-auto">
        <div class="mb-6 flex justify-between items-center flex-wrap">
            <div class="mb-2 md:mb-0">
                <h2 class="text-2xl font-bold text-gray-800">Product Category Management</h2>
                <p class="text-gray-600">Manage all Product categories</p>
            </div>
            <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
                <form id="filter-form" class="flex flex-wrap gap-2 w-full md:w-auto">
                    <input type="text" id="search-input" name="search" placeholder="Search categories..."
                        class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500"
                        value="{{ request('search') ?? '' }}">

                    <select id="status-filter" name="status" class="px-3 py-2 border rounded-md text-sm">
                        <option value="">Active/InActive</option>
                        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>

                    <select name="per_page" id="per-page-filter" class="custom-select pl-2 border rounded-md text-sm">
                        @foreach ($allowedPerPage as $pp)
                            <option value="{{ $pp }}" {{ $perPage == $pp ? 'selected' : '' }}>{{ $pp }}
                                per page</option>
                        @endforeach
                    </select>

                </form>
                <button id="new-category-button"
                    class="w-full md:w-[240px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                    <i class="fas fa-plus mr-1"></i> New Category
                </button>
            </div>
        </div>

        <div id="categories-container" class="bg-white shadow rounded-lg overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h2 class="font-semibold text-gray-800">Category List</h2>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 text-sm category-table">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="px-4 py-2 text-left">SN</th>
                            <th class="px-4 py-2 text-left">Image</th>
                            <th class="px-4 py-2 text-left">Name</th>
                            <th class="px-4 py-2 text-left">Description</th>
                            <th class="px-4 py-2 text-left">IsActive</th>
                            <th class="px-4 py-2 ">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @forelse($categories as $cat)
                            <tr>
                                <td class="px-4 py-2">
                                    {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}</td>
                                <td class="px-4 py-2">
                                    @if ($cat->ImageUrl)
                                        <img src="{{ $cat->ImageUrl }}"
                                            class="w-16 h-12 object-cover rounded cursor-pointer"
                                            onclick="showImage('{{ $cat->ImageUrl }}')">
                                    @else
                                        <div
                                            class="w-16 h-12 bg-gray-100 rounded flex items-center justify-center text-gray-400 text-xs">
                                            No</div>
                                    @endif
                                </td>
                                <td class="px-4 py-2 font-semibold">{{ $cat->Name }}</td>
                                <td class="px-4 py-2">{{ Str::limit($cat->Description, 100) }}</td>
                                <td class="px-4 py-2">
                                    <span
                                        class="px-2 py-1 rounded text-xs {{ $cat->IsActive ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                        {{ $cat->IsActive ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                                <td class="px-4 py-2">
                                    <div class="flex items-center justify-center gap-3">
                                        <button class="edit-category-btn text-indigo-600 hover:text-indigo-800"
                                            data-id="{{ $cat->MenuCategoryId }}" data-name="{{ $cat->Name }}"
                                            data-description="{{ $cat->Description }}"
                                            data-isactive="{{ $cat->IsActive }}"
                                            data-image="{{ $cat->ImageUrl ? $cat->ImageUrl : '' }}">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <form method="POST" action="{{ route('product.food.category.destroy', $cat->MenuCategoryId) }}" class="delete-form inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-800 delete-category-btn" data-name="{{ $cat->Name }}">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-4 text-center text-gray-500">No categories found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{-- PAGINATION --}}
            <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
                <div class="text-sm text-gray-600">
                    Showing <strong>{{ $categories->firstItem() ?? 0 }}</strong> to
                    <strong>{{ $categories->lastItem() ?? 0 }}</strong> of <strong>{{ $categories->total() }}</strong>
                    results
                </div>
                <div class="mt-3 md:mt-0">
                    {{ $categories->links() }}
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div id="category-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
            <!-- modal wrpper -->
            <div class="fixed inset-0 flex items-center justify-center px-4">

                <!-- overlay -->
                <div id="modalOverlay" class="fixed inset-0 bg-blue-950/40 backdrop-blur-[2px]"></div>

                <!-- modal content  -->
                <div id="modalContent" class="bg-white rounded-lg w-full max-w-md relative">

                    <div class="bg-indigo-600 px-6 py-4 rounded-t-lg flex justify-between">
                        <h3 id="category-modal-title" class="text-lg text-white font-medium ">New Category</h3>
                        <button id="close-category-modal"
                            class=" text-gray-200 hover:text-red-500 text-3xl">&times;</button>
                    </div>
                    <div class="px-6 mt-2 pb-4">

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

                        <form id="category-form" method="POST" action="" class="space-y-4">
                            @csrf
                            <input type="hidden" name="_method" id="category-form-method" value="POST">

                            <div>
                                <label class="block text-sm font-medium">Name<span class="text-red-500">*</span></label>
                                <input type="text" name="Name" id="category-name" placeholder="Enter Category" value="{{ old('Name') }}"
                                    class="mt-1 block w-full border border-gray-400 rounded px-3 py-2" required>
                            </div>
                            <div>
                                <label class="block text-sm font-medium">Description<span class="text-red-500">*</span></label>
                                <textarea name="Description" id="category-description" placeholder="Category Description..."
                                    class="mt-1 block w-full border border-gray-400 rounded px-3 py-2" rows="3" required>{{ old('Description') }}</textarea>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Image URL<span class="text-red-500">*</span></label>
                                <input type="url" name="ImageUrl" id="customer-image-url"
                                    placeholder="Enter image URL" value="{{ old('ImageUrl') }}"
                                    class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 sm:text-sm"
                                    required>
                                <img id="image-preview"
                                    class="mt-2 w-28 h-28 rounded-md border border-gray-300 object-cover {{ old('ImageUrl') ? '' : 'hidden' }}"
                                    src="{{ old('ImageUrl') ?? '' }}"
                                    alt="Image Preview" />
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="IsActive" id="category-active" value="1" checked>
                                <label for="category-active" class="text-sm">Active</label>
                            </div>

                            <div class="flex justify-end gap-2">
                                <button type="button" id="category-cancel"
                                    class="px-4 py-2 bg-gray-200 hover:bg-red-500 hover:text-white rounded">Cancel</button>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($errors->any())
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const modal = document.getElementById('category-modal');
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            });
        </script>
    @endif

@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('category-modal');
            const overlay = document.getElementById('modalOverlay');
            const newBtn = document.getElementById('new-category-button');
            const closeBtn = document.getElementById('close-category-modal');
            const cancelBtn = document.getElementById('category-cancel');
            const form = document.getElementById('category-form');
            const title = document.getElementById('category-modal-title');
            const methodInput = document.getElementById('category-form-method');
            const nameInput = document.getElementById('category-name');
            const descInput = document.getElementById('category-description');
            const activeInput = document.getElementById('category-active');
            const imageField = document.getElementById('customer-image-url');
            const imagePreview = document.getElementById('image-preview');

            // Image preview
            imageField.addEventListener('input', () => {
                const url = imageField.value.trim();
                if (url) {
                    imagePreview.src = url;
                    imagePreview.classList.remove('hidden');
                } else {
                    imagePreview.src = '';
                    imagePreview.classList.add('hidden');
                }
            });

            // AJAX SEARCH & FILTERS
            const filterForm = document.getElementById('filter-form');
            const searchInput = document.getElementById('search-input');
            const statusFilter = document.getElementById('status-filter');
            const categoriesContainer = document.getElementById('categories-container');
            const perPageFilter = document.getElementById('per-page-filter');

            const performSearch = () => {
                const formData = new FormData(filterForm);
                const params = new URLSearchParams(formData);
                const url = `{{ route('product.food.category') }}?${params.toString()}`;

                fetch(url, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const newDoc = parser.parseFromString(html, 'text/html');
                        const newContainer = newDoc.getElementById('categories-container');

                        if (newContainer) {
                            categoriesContainer.innerHTML = newContainer.innerHTML;
                            // Re-sync filter values
                            const newStatusFilter = document.getElementById('status-filter');
                            const newSearchInput = document.getElementById('search-input');
                            if (newStatusFilter) newStatusFilter.value = statusFilter.value;
                            if (newSearchInput) newSearchInput.value = searchInput.value;
                            reattachEventListeners();
                        }
                    })
                    .catch(error => console.error('Error:', error));
            };

            function openModal() {
                modal.classList.remove('hidden');
                document.body.classList.add('overflow-hidden');
            }

            function closeModal() {
                modal.classList.add('hidden');
                document.body.classList.remove('overflow-hidden');

            }

            function resetModal() {
                title.innerText = 'New Category';
                form.action = '';
                methodInput.value = 'POST';

                nameInput.value = '';
                descInput.value = '';
                activeInput.checked = true;

                imageField.value = '';
                imagePreview.src = '';
                imagePreview.classList.add('hidden');
            }

            function clearFormErrors() {
                const errorBox = document.getElementById('form-errors');
                if (errorBox) {
                    errorBox.innerHTML = '';
                }
            }

            // Debounce for search input
            let searchTimeout;

            searchInput.addEventListener('keyup', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });

            // Immediate search for status filter
            statusFilter.addEventListener('change', performSearch);
            perPageFilter.addEventListener('change', performSearch);

            const reattachEventListeners = () => {
                document.querySelectorAll('.edit-category-btn').forEach(btn => {
                    btn.addEventListener('click', () => {

                        resetModal();
                        clearFormErrors();

                        title.innerText = 'Edit Category';
                        form.action = `/food-category/${btn.dataset.id}`;
                        methodInput.value = 'PUT';
                        nameInput.value = btn.dataset.name;
                        descInput.value = btn.dataset.description;
                        activeInput.checked = btn.dataset.isactive == 1 || btn.dataset.isactive === 'true';

                        if (btn.dataset.image && btn.dataset.image.trim() !== '') {
                            imageField.value = btn.dataset.image;
                            imagePreview.src = btn.dataset.image;
                            imagePreview.classList.remove('hidden');
                        } else {
                            imageField.value = '';
                            imagePreview.src = '';
                            imagePreview.classList.add('hidden');
                        }

                        openModal();
                    });
                });

                document.querySelectorAll('.delete-category-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        e.preventDefault();
                        const categoryName = btn.dataset.name;
                        const deleteForm = btn.closest('.delete-form');

                        Swal.fire({
                            title: 'Delete Category',
                            text: `Are you sure you want to delete "${categoryName}"?`,
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

            newBtn.addEventListener('click', () => {

                resetModal();
                clearFormErrors();

                title.innerText = 'New Category';
                form.action = "{{ route('product.food.category.store') }}";
                methodInput.value = 'POST';
                nameInput.value = '';
                descInput.value = '';
                activeInput.checked = true;
                imageField.value = '';
                imagePreview.src = '';
                imagePreview.classList.add('hidden');

                openModal();
            });
            overlay.addEventListener('click', closeModal)
            closeBtn.addEventListener('click', closeModal);
            cancelBtn.addEventListener('click', closeModal);

            // Initial attachment of event listeners
            reattachEventListeners();
        });
    </script>
@endpush
