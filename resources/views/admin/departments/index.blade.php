@extends('layouts.admin.app')
@section('title', 'Admin | Department Management')

@push('styles')
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Department Management</h2>
            <p class="text-gray-600">Manage all departments and their codes</p>
        </div>
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <form method="GET" class="flex space-x-2">
                <input type="text" name="search" 
                {{-- value="{{ $search }}"  --}}
                placeholder="Search departments..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>
            <button id="new-department-button"
                class="flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none"
                disabled>
                <i class="fas fa-plus mr-1"></i> New Department
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between">
            <h3 class="text-lg font-medium text-gray-900">Department List</h3>
        </div>

        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Name</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Code</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Description</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    
                    <tr>
                        <td class="px-4 py-2">
                           \
                            <img class="w-11 h-11 rounded-full ring-2 ring-indigo-100 group-hover:ring-indigo-300 transition"
                            src="https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?ixlib=rb-4.0.3&q=80&w=100&h=100&fit=crop"
                            alt="Default user">
                        </td>
                        <td class="px-4 py-2 font-semibold text-gray-800">
                           
                            bbb
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                           
                            ccc
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                          
                            ddd
                        </td>
                        <td class="px-4 py-2 flex space-x-2">
                            <button class="edit-btn text-indigo-600 hover:text-indigo-800"
                               
                                >
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="#" class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                  
                </tbody>
            </table>
        </div>

        <div class="flex justify-between items-center mt-4 px-4 py-2 bg-gray-50 border-t border-gray-200 rounded">
            <!-- Left: Results info -->
            <div id="resultsInfo" class="text-gray-700 text-sm">
                Showing 1 to 10 of 20 results
            </div>
        
            <!-- Right: Pagination buttons -->
            <div class="flex space-x-3">
                <button id="prevPageBtn" class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <button id="nextPageBtn" class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>                
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="department-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>
        <div class="bg-white rounded-lg shadow-xl transform transition-all max-w-lg w-full p-6 relative">
            <button type="button" id="close-modal-btn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>
            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">New Department</h3>
            <form id="department-form" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Department Name</label>
                    <input type="text" name="department_name" id="department-name"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                        required>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Department Code</label>
                    <input type="text" name="department_code" id="department-code"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="department-description"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>
                <div class="flex justify-end space-x-2">
                    <button type="button" id="cancel-btn" class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modal = document.getElementById('department-modal');
        const newBtn = document.getElementById('new-department-button');
        const cancelBtn = document.getElementById('cancel-btn');
        const closeBtn = document.getElementById('close-modal-btn');
        const form = document.getElementById('department-form');
        const modalTitle = document.getElementById('modal-title');
        const methodInput = document.getElementById('form-method');
        const nameInput = document.getElementById('department-name');
        const codeInput = document.getElementById('department-code');
        const descInput = document.getElementById('department-description');

        // Create
        newBtn.addEventListener('click', () => {
            modalTitle.innerText = 'New Department';
            form.action = "#";
            methodInput.value = 'POST';
            nameInput.value = '';
            codeInput.value = '';
            descInput.value = '';
            modal.classList.remove('hidden');
        });

        cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
        closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

        // Edit
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', () => {
                modalTitle.innerText = 'Edit Department';
                form.action = `/departments/${btn.dataset.id}`;
                methodInput.value = 'PUT';
                nameInput.value = btn.dataset.name;
                codeInput.value = btn.dataset.code;
                descInput.value = btn.dataset.description;
                modal.classList.remove('hidden');
            });
        });

        // Delete Confirmation
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
                    if (result.isConfirmed) {
                        f.submit();
                    }
                });
            });
        });
    });

    // Toasts
    @if(session('success'))
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'success',
        title: "{{ session('success') }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif

    @if ($errors->any())
    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: 'error',
        title: "{{ $errors->first() }}",
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true
    });
    @endif
</script>
@endpush
