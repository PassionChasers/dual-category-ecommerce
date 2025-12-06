@extends('layouts.admin.app')
@section('title', 'Admin | All Food-Product')

@push('styles')
<!-- add any page-specific styles here -->
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">

    {{-- Flash Messages --}}
    @if(session('success'))
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true
            });
        });
    </script>
    @endif

    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800">Food-item Management</h2>
            <p class="text-gray-600">Manage Order List.</p>
        </div>

      
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap w-full gap-2">
                <input type="text" name="search" value="" placeholder="Search Orders By order ID..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <select name="status" class="flex-shrink-0 border rounded-md px-3 py-2 text-sm">
                    <option value="">All status</option>
                    <option value="0" >Pending</option>
                    <option value="1" >In Progress</option>
                    <option value="2" >Completed</option>
                </select>
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <div class="flex gap-2 mt-2 md:mt-0">
                <!-- New Task Button -->
                <button id="new-task-button"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 whitespace-nowrap">
                    <i class="fas fa-plus mr-1"></i> New Food-Item
                </button>

                <!-- Export Button -->
                <a href="#"
                    class="inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700">
                    <i class="fas fa-file-excel mr-1"></i> Export
                </a>
            </div>
        </div>

    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between">
            <h3 class="text-lg font-medium text-gray-900">Food Order List</h3>
        </div>

        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">Order ID</th>
                        <th class="px-4 py-2">Customer Name</th>
                        <th class="px-4 py-2">Items</th>
                        <th class="px-4 py-2">Qty</th>
                        <th class="px-4 py-2">Total Amount</th>
                        <th class="px-4 py-2">Delivery Type</th>
                        <th class="px-4 py-2">Order Status</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    {{-- @forelse($tasks as $index => $task) --}}
                    <tr>
                        <td class="px-4 py-2">
                           001
                        </td>
                        <td class="px-4 py-2 font-semibold text-gray-800">
                            {{-- {{ $task->name ?? '-' }} --}}
                            Shibu Khan
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{-- {{ $task->category->name ?? '-' }} --}}
                            Burger, Fries, Coke
                        </td>
                        <td class="px-4 py-2" 
                        {{-- id="priority-badge-{{ $task->id }}" --}}
                        >
                            {{-- @php
                            $colors = [
                            3 => ['Low', 'bg-green-100 text-green-800'],
                            2 => ['Medium', 'bg-yellow-100 text-yellow-800'],
                            1 => ['High', 'bg-red-100 text-red-800'],
                            ];
                            $priority = $colors[$task->priority_id] ?? ['None', 'bg-gray-100 text-gray-800'];
                            @endphp --}}
                            <span class="px-2 py-1 rounded text-xs 
                            {{-- {{ $priority[1] }} --}}
                             ">
                             {{-- {{ $priority[0] }} --}}
                                2
                            </span>
                        </td>

                        <td class="px-4 py-2 text-gray-600">
                            {{-- {{ $task->assignee?->name ?? '-' }} --}}
                            500
                        </td>
                        <td class="px-4 py-2">
                            {{-- @if($task->is_requested)
                            @if($task->is_approved == 0)
                            <div class="flex space-x-2">
                                <button class="accept-btn px-2 py-1 bg-green-100 text-green-800 rounded text-xs"
                                    data-id="{{ $task->id }}">Accept</button>
                                <button class="reject-btn px-2 py-1 bg-red-100 text-red-800 rounded text-xs"
                                    data-id="{{ $task->id }}">Reject</button>
                            </div>
                            @elseif($task->is_approved == 1)
                            <span class="px-2 py-1 bg-green-100 text-green-800 rounded text-xs">Accepted</span>
                            @elseif($task->is_approved == 2)
                            <span class="px-2 py-1 bg-red-100 text-red-800 rounded text-xs">Rejected</span>
                            @endif
                            @else
                            <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs whitespace-nowrap">Not Requested</span>
                            @endif --}}
                            {{-- <span class="px-2 py-1 bg-gray-100 text-gray-800 rounded text-xs whitespace-nowrap">Accept/Reject</span> --}}
                            Home Delivery
                        </td>


                        {{-- @php
                        if (!function_exists('statusBadge')) {
                        function statusBadge($status) {
                        return match($status) {
                        0 => '<span class="px-2 py-1 rounded text-xs bg-yellow-100 text-yellow-800">Pending</span>',
                        1 => '<span class="px-2 py-1 rounded text-xs bg-blue-100 text-blue-800">In Progress</span>',
                        2 => '<span class="px-2 py-1 rounded text-xs bg-green-100 text-green-800">Completed</span>',
                        default => '<span class="px-2 py-1 rounded text-xs bg-gray-100 text-gray-800">Unknown</span>',
                        };
                        }
                        }
                        @endphp --}}

                        <td class="px-4 py-2">
                            <select 
                            {{-- data-task-id="{{ $task->id }}" --}}
                                class="task-status-select block border rounded-lg px-2 py-1 text-sm
                                {{-- {{ $task->status == 0 ? 'bg-yellow-100 text-yellow-800' : ($task->status == 1 ? 'bg-blue-100 text-blue-800' : 'bg-green-100 text-green-800') }} --}}
                                "
                                {{-- {{ $task->status == 2 ? 'disabled' : '' }} --}}
                                >
                                <option value="0" 
                                {{-- @selected($task->status == 0) --}}
                                >Pending</option>
                                <option value="1" 
                                {{-- @selected($task->status == 1) --}}
                                >Accepted</option>
                                 <option value="0" 
                                {{-- @selected($task->status == 0) --}}
                                >Preparing</option>
                                 <option value="0" 
                                {{-- @selected($task->status == 0) --}}
                                >Ready</option>
                                <option value="2" 
                                {{-- @selected($task->status == 2) --}}
                                >Delivered</option>
                            </select>
                        </td>

                        <td class="px-4 py-2 flex space-x-2">
                            {{-- <button class="view-btn text-gray-600 hover:text-gray-900" data-id="{{ $task->id }}">
                                <i class="fas fa-eye"></i>
                            </button> --}}
                            <a href="#"
                                class="view-btn text-gray-600 hover:text-gray-900">
                                <button type="button">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </a>

                            <button class="edit-btn text-indigo-600 hover:text-indigo-800"
                             {{-- data-id="{{ $task->id }}" --}}
                             >
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="get" action="#" class="inline delete-form">
                                {{-- @csrf @method('DELETE') --}}
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    {{-- @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No tasks found.</td>
                    </tr>
                    @endforelse --}}
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
<div id="task-modal" class="fixed z-50 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity"></div>

        <!-- Modal content -->
        <div class="bg-white rounded-2xl shadow-2xl transform transition-all max-w-2xl w-full p-8 relative z-20">
            <!-- Close Button -->
            <button type="button" id="close-modal-btn"
                class="absolute top-4 right-4 text-gray-500 hover:text-gray-700 transition">
                <i class="fas fa-times text-xl"></i>
            </button>

            <!-- Modal Title -->
            <h3 class="text-2xl font-semibold text-gray-900 mb-6" id="modal-title">New Product</h3>

            <!-- Form -->
            <form id="task-form" method="get" class="space-y-6" action="#">
                @csrf
                <input type="hidden" name="_method" id="form-method" value="POST">
                <input type="hidden" name="task_id" id="task-id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Category -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                        <select name="task_category_id" id="task_category_id" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">Select Category</option>
                            {{-- @foreach($categories as $c) --}}
                            <option value="">aaaa</option>
                            {{-- @endforeach --}}
                            <option value="">bbbb</option>
                            <option value="">cccc</option>
                            
                        </select>
                    </div>

                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Priority</label>
                        <select name="priority_id" id="priority_id" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                       focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                            <option value="">Select Priority</option>
                            {{-- @foreach($priorities as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach --}}
                            <option value="">High</option>
                            <option value="">medium</option>
                            <option value="">Low</option>
                        </select>
                    </div>

                    <!-- Task Name -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Product Name</label>
                        <input type="text" name="name" id="task-name" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                             focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                    </div>

                    <!-- Due Date -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Due Date</label>
                        <input type="date" name="due_date" id="due_date" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                    focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"
                            min="{{ date('Y-m-d') }}">
                    </div>

                    <!-- Description -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                        <textarea name="description" id="task-desc" rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                            focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition"></textarea>
                    </div>

                    <!-- Assignee Search -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assign To</label>
                        <input type="hidden" name="assigned_to" id="assigned_to">
                        <input type="text" id="assigned_to_search" placeholder="Search user by name or email" class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2
                                      focus:border-indigo-500 focus:ring-2 focus:ring-indigo-200 transition">
                        <div id="assigned_to_suggestions"
                            class="bg-white border mt-1 rounded-lg shadow max-h-48 overflow-auto hidden"></div>
                    </div>

                    <!-- Assigned By -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Assigned By</label>
                        <input type="text" value=""
                            class="mt-1 block w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100" readonly>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-3 mt-6">
                    <button type="button" id="cancel-btn"
                        class="px-5 py-2 bg-gray-200 rounded-lg hover:bg-gray-300 transition font-medium">
                        Cancel
                    </button>
                    <button type="submit" id="save-btn"
                        class="px-5 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition font-medium">
                        Save
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
{{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
    const modal = document.getElementById('task-modal');
    const newBtn = document.getElementById('new-task-button');
    const closeBtn = document.getElementById('close-modal-btn');
    const cancelBtn = document.getElementById('cancel-btn');
    const form = document.getElementById('task-form');
    const modalTitle = document.getElementById('modal-title');
    const methodInput = document.getElementById('form-method');
    const taskIdInput = document.getElementById('task-id');

    // inputs
    const nameInput = document.getElementById('task-name');
    const descInput = document.getElementById('task-desc');
    const categorySelect = document.getElementById('task_category_id');
    const prioritySelect = document.getElementById('priority_id');
    const assignedToSearch = document.getElementById('assigned_to_search');
    const assignedToHidden = document.getElementById('assigned_to');
    const assignedToSuggestions = document.getElementById('assigned_to_suggestions');
    const dueDateInput = document.getElementById('due_date');

    function openModal() { modal.classList.remove('hidden'); }
    function closeModal() { modal.classList.add('hidden'); }

    // open modal for create
    if (newBtn) {
        newBtn.addEventListener('click', () => {
            modalTitle.innerText = 'New Task';
            form.action = "#";
            methodInput.value = 'POST';
            taskIdInput.value = '';
            nameInput.value = '';
            descInput.value = '';
            categorySelect.value = '';
            prioritySelect.value = '';
            assignedToHidden.value = '';
            assignedToSearch.value = '';
            dueDateInput.value = '';
            openModal();
        });
    }

    cancelBtn.addEventListener('click', closeModal);
    closeBtn.addEventListener('click', closeModal);


    // handle edit buttons
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const id = btn.dataset.id;
            try {
                const res = await fetch(`{{ url('/tasks') }}/${id}`, { headers: { 'Accept': 'application/json' } });
                if (!res.ok) throw new Error('Failed to load');
                const task = await res.json();

                modalTitle.innerText = 'Edit Task';
                form.action = `{{ url('/tasks') }}/${task.id}`;
                methodInput.value = 'PUT';
                taskIdInput.value = task.id;
                nameInput.value = task.name ?? '';
                descInput.value = task.description ?? '';
                categorySelect.value = task.task_category_id ?? '';
                prioritySelect.value = task.priority_id ?? '';
                assignedToHidden.value = task.assigned_to ?? '';
                assignedToSearch.value = task.assignee ? `${task.assignee.name} <${task.assignee.email}>` : '';
                    dueDateInput.value = task.due_date ?? '';


                Array.from(form.elements).forEach(el => el.disabled = false);
                openModal();
            } catch (err) {
                Swal.fire('Error', 'Unable to load task details', 'error');
            }
        });
    });

    // Delete confirmation
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

    // Debounce helper
    function debounce(fn, delay=300) {
        let t;
        return function(...args) {
            clearTimeout(t);
            t = setTimeout(() => fn.apply(this, args), delay);
        };
    }

    // AJAX user search
    async function searchUsers(q) {
        if (!q) return [];
        const url = new URL("#", location.origin);
        url.searchParams.set('q', q);
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) return [];
        return await res.json();
    }

    function showSuggestions(container, items) {
        container.innerHTML = '';
        if (!items.length) {
            container.classList.add('hidden');
            return;
        }
        items.forEach(user => {
            const div = document.createElement('div');
            div.className = 'px-3 py-2 cursor-pointer hover:bg-gray-100';
            div.innerText = `${user.name} (${user.email})`;
            div.dataset.id = user.id;
            container.appendChild(div);
        });
        container.classList.remove('hidden');
    }

    assignedToSearch.addEventListener('input', debounce(async () => {
        const users = await searchUsers(assignedToSearch.value);
        showSuggestions(assignedToSuggestions, users);
    }));

    assignedToSuggestions.addEventListener('click', e => {
        if (e.target.dataset.id) {
            assignedToHidden.value = e.target.dataset.id;
            assignedToSearch.value = e.target.innerText;
            assignedToSuggestions.classList.add('hidden');
        }
    });
});
</script>


<meta name="csrf-token" content="{{ csrf_token() }}">

<script>
    document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const showToast = (icon, message) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 2000
        });
    };

    // Helper for dynamic color update
    const setSelectColor = (select, type, value) => {
        select.classList.remove('bg-yellow-100','text-yellow-800','bg-blue-100','text-blue-800','bg-green-100','text-green-800','bg-red-100','text-red-800');
        if (type === 'status') {
            if (value == 0) select.classList.add('bg-yellow-100','text-yellow-800');
            else if (value == 1) select.classList.add('bg-blue-100','text-blue-800');
            else select.classList.add('bg-green-100','text-green-800');
        } else if (type === 'priority') {
            if (value == 1) select.classList.add('bg-red-100','text-red-800');
            else if (value == 2) select.classList.add('bg-yellow-100','text-yellow-800');
            else select.classList.add('bg-green-100','text-green-800');
        }
    };

    // --- STATUS CHANGE ---
    document.querySelectorAll('.task-status-select').forEach(select => {
        select.addEventListener('change', async e => {
            const taskId = select.dataset.taskId;
            const newStatus = select.value;

            const confirm = await Swal.fire({
                title: 'Change Status?',
                text: 'Are you sure you want to update task status?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, update it',
                cancelButtonText: 'Cancel'
            });

            if (!confirm.isConfirmed) {
                window.location.reload();
                return;
            }

            try {
                const res = await fetch(`{{ url('/tasks') }}/${taskId}/status`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ status: newStatus })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed');

                showToast('success', data.message);
                setSelectColor(select, 'status', newStatus);
                if (Number(newStatus) === 2) select.disabled = true;

            } catch (err) {
                showToast('error', err.message);
                window.location.reload();
            }
        });
    });

    // --- PRIORITY CHANGE ---
    document.querySelectorAll('.task-priority-select').forEach(select => {
        select.addEventListener('change', async e => {
            const taskId = select.dataset.taskId;
            const priorityId = select.value;

            try {
                const res = await fetch(`{{ url('/tasks') }}/${taskId}/priority`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrf
                    },
                    body: JSON.stringify({ priority_id: priorityId })
                });

                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed');

                showToast('success', data.message);
                setSelectColor(select, 'priority', priorityId);
            } catch (err) {
                showToast('error', err.message);
            }
        });
    });
});
// Accept or reject
document.addEventListener('DOMContentLoaded', () => {
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const showToast = (icon, message) => {
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: icon,
            title: message,
            showConfirmButton: false,
            timer: 2000,
            timerProgressBar: true
        });
    };

    // Accept
    document.querySelectorAll('.accept-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const taskId = btn.dataset.id;
            const confirmed = await Swal.fire({
                title: 'Accept Task?',
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            });
            if (!confirmed.isConfirmed) return;

            try {
                const res = await fetch(`{{ url('/tasks') }}/${taskId}/approve`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed');

                showToast('success', data.message);
                setTimeout(() => location.reload(), 2000); // reload after toast disappears
            } catch (err) {
                showToast('error', err.message);
            }
        });
    });

    // Reject
    document.querySelectorAll('.reject-btn').forEach(btn => {
        btn.addEventListener('click', async () => {
            const taskId = btn.dataset.id;
            const confirmed = await Swal.fire({
                title: 'Reject Task?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes',
            });
            if (!confirmed.isConfirmed) return;

            try {
                const res = await fetch(`{{ url('/tasks') }}/${taskId}/reject`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': csrf,
                        'Accept': 'application/json'
                    },
                });
                const data = await res.json();
                if (!res.ok) throw new Error(data.message || 'Failed');

                showToast('success', data.message);
                setTimeout(() => location.reload(), 2000); // reload after toast disappears
            } catch (err) {
                showToast('error', err.message);
            }
        });
    });
});


</script>
@endpush