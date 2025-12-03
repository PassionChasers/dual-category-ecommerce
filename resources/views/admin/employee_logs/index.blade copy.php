@extends('layouts.admin.app')
@section('title', 'Employee Logs | Daily Log Management')

@push('styles')
<!-- Additional page-specific styles (optional) -->
@endpush

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">

    {{-- Flash Message --}}
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
            <h2 class="text-2xl font-bold text-gray-800">Employee Daily Logs</h2>
            <p class="text-gray-600">Monitor and manage daily activities and working hours</p>
        </div>

        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2 w-full md:w-auto">
            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap w-full gap-2">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Search by title or employee..."
                    class="flex-1 min-w-[150px] border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                <button type="submit" class="flex-shrink-0 px-3 py-2 bg-gray-100 text-sm rounded hover:bg-gray-200">
                    <i class="fas fa-search"></i>
                </button>
            </form>

            <!-- New Log Button -->
            <button id="new-log-button"
                class="w-full md:w-[180px] inline-flex items-center justify-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none">
                <i class="fas fa-plus mr-1"></i> New Log
            </button>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex justify-between">
            <h3 class="text-lg font-medium text-gray-900">Logs List</h3>
        </div>

        <div class="overflow-x-auto">
            <table id="taskTable" class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Employee</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Title</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Hours Spent</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Priority</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($logs as $index => $log)
                    <tr>
                        <td class="px-4 py-2">{{ $logs->firstItem() + $index }}</td>
                        <td class="px-4 py-2 font-semibold text-gray-800">{{ $log->employee->user->name ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-700">{{ $log->title ?? '-' }}</td>
                        <td class="px-4 py-2 text-gray-600">{{ $log->hours_spent ?? '0 hrs' }}</td>
                        <td class="px-4 py-2">
                            @php
                            $priorityColors = [
                            1 => ['High', 'bg-red-100 text-red-800'],
                            2 => ['Medium', 'bg-yellow-100 text-yellow-800'],
                            3 => ['Low', 'bg-green-100 text-green-800'],
                            ];
                            $priority = $priorityColors[$log->priority_id] ?? ['None', 'bg-gray-100 text-gray-800'];
                            @endphp
                            <span class="px-2 py-1 rounded text-xs {{ $priority[1] }}">{{ $priority[0] }}</span>
                        </td>
                        <td class="px-4 py-2">{{ $log->status == 0 ? 'Pending' : ($log->status == 1 ? 'In Progress' :
                            'Completed') }}</td>
                        <td class="px-4 py-2 flex space-x-2">
                            <button class="edit-btn text-indigo-600 hover:text-indigo-800" data-id="{{ $log->id }}"
                                data-employee="{{ $log->employee_id }}" data-priority="{{ $log->priority_id }}"
                                data-title="{{ $log->title }}" data-hours="{{ $log->hours_spent }}"
                                data-description="{{ $log->description }}">
                                <i class="fas fa-edit"></i>
                            </button>
                            <form method="POST" action="{{ route('employee_logs.destroy', $log) }}"
                                class="inline delete-form">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-600 hover:text-red-800">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-4 py-4 text-center text-gray-500">No logs found.</td>
                    </tr>
                    @endforelse
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
                <button id="prevPageBtn"
                    class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Previous
                </button>
                <button id="nextPageBtn"
                    class="px-3 py-1 border rounded border-gray-600 text-gray-800 disabled:opacity-50 disabled:cursor-not-allowed">
                    Next
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div id="log-modal" class="fixed z-10 inset-0 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen px-4">
        <!-- Overlay -->
        <div class="fixed inset-0 bg-gray-500 opacity-75"></div>

        <!-- Modal content -->
        <div class="bg-white rounded-lg shadow-xl transform transition-all max-w-2xl w-full p-6 relative">
            <!-- Close button -->
            <button type="button" id="close-modal-btn" class="absolute top-3 right-3 text-gray-500 hover:text-gray-700">
                <i class="fas fa-times text-lg"></i>
            </button>

            <h3 class="text-lg font-medium text-gray-900 mb-4" id="modal-title">New Log</h3>

            <form id="log-form" method="POST" class="space-y-4">
                @csrf
                <input type="hidden" id="form-method" name="_method" value="POST">
                <input type="hidden" id="log-id" name="log_id">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Employee -->
                    {{-- <div>
                        <label class="block text-sm font-medium text-gray-700">Employee</label>
                        <select name="employee_id" id="employee_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Employee</option>
                            @foreach($employees as $emp)
                            <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                            @endforeach
                        </select>
                    </div> --}}
                    <!-- Employee -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Employee</label>
                        <input type="text"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 bg-gray-100 text-gray-700 focus:ring-0 focus:border-gray-300 sm:text-sm cursor-not-allowed"
                            value="{{ auth()->user()->name }}" readonly>
                        <input type="hidden" name="employee_id" id="employee_id"
                            value="{{ auth()->user()?->employeeDetail->id ?? '' }}">
                    </div>


                    <!-- Priority -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Priority</label>
                        <select name="priority_id" id="priority_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Priority</option>
                            @foreach($priorities as $p)
                            <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Title -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Title</label>
                        <input type="text" name="title" id="title"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Hours Spent -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Hours Spent</label>
                        <input type="text" name="hours_spent" id="hours_spent" placeholder="e.g. 4 hrs"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>

                    <!-- Description (full width) -->
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700">Description</label>
                        <textarea name="description" id="description" rows="4"
                            class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                    </div>
                </div>

                <!-- Buttons -->
                <div class="flex justify-end space-x-2 mt-4">
                    <button type="button" id="cancel-btn"
                        class="px-4 py-2 bg-gray-200 rounded-md hover:bg-gray-300">Cancel</button>
                    <button type="submit"
                        class="px-4 py-2 bg-indigo-600 text-white rounded-md hover:bg-indigo-700">Save</button>
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
    const modal = document.getElementById('log-modal');
    const newBtn = document.getElementById('new-log-button');
    const cancelBtn = document.getElementById('cancel-btn');
    const closeBtn = document.getElementById('close-modal-btn');
    const form = document.getElementById('log-form');
    const modalTitle = document.getElementById('modal-title');
    const methodInput = document.getElementById('form-method');
    const logIdInput = document.getElementById('log-id');
    const employeeInput = document.getElementById('employee_id');
    const priorityInput = document.getElementById('priority_id');
    const titleInput = document.getElementById('title');
    const hoursInput = document.getElementById('hours_spent');
    const descInput = document.getElementById('description');

    // Open modal for creating new log
    newBtn.addEventListener('click', () => {
        modalTitle.innerText = 'New Log';
        form.action = "{{ route('employee_logs.store') }}";
        methodInput.value = 'POST';
        logIdInput.value = '';
        employeeInput.value = '';
        priorityInput.value = '';
        titleInput.value = '';
        hoursInput.value = '';
        descInput.value = '';
        modal.classList.remove('hidden');
    });

    // Close modal (cancel or close button)
    cancelBtn.addEventListener('click', () => modal.classList.add('hidden'));
    closeBtn.addEventListener('click', () => modal.classList.add('hidden'));

    // Open modal for editing existing log
    document.querySelectorAll('.edit-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            modalTitle.innerText = 'Edit Log';
            const id = btn.dataset.id;
            form.action = `/employee_logs/${id}`;
            methodInput.value = 'PUT';
            logIdInput.value = id;
           // employeeInput.value = btn.dataset.employee;
            priorityInput.value = btn.dataset.priority;
            titleInput.value = btn.dataset.title;
            hoursInput.value = btn.dataset.hours;
            descInput.value = btn.dataset.description;
            modal.classList.remove('hidden');
        });
    });

    // SweetAlert for delete confirmation
    document.querySelectorAll('.delete-form').forEach(f => {
        f.addEventListener('submit', function(e) {
            e.preventDefault();
            Swal.fire({
                title: 'Are you sure?',
                text: "This log will be deleted permanently!",
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

    // Toast alerts
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
});
</script>
@endpush