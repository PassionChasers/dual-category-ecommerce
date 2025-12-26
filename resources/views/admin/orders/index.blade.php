@extends('layouts.admin.app')
@section('title', 'Admin | All Product Order Management')

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
            <h2 class="text-2xl font-bold text-gray-800">All Order Management</h2>
            <p class="text-gray-600">Manage Food and Medicine Order List</p>
        </div>

      
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <!-- Search Form -->
            <form method="GET" class="flex flex-wrap w-full gap-2">
                <input type="text" name="search" value="" placeholder="Search Orders by ID..."
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
                    <i class="fas fa-plus mr-1"></i> New Item
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
         <div class="px-6 py-4 border-b">
            <h2 class="font-semibold text-gray-800">Orders List</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2">#</th>
                        <th class="px-4 py-2">Order Id</th>
                        <th class="px-4 py-2">Customer</th>
                        <th class="px-4 py-2">Contact</th>
                        <th class="px-4 py-2">Type</th>
                        <th class="px-4 py-2">Store</th>
                        <th class="px-4 py-2">Total</th>
                        <th class="px-4 py-2">Payment</th>
                        <th class="px-4 py-2">Status</th>
                        <th class="px-4 py-2">Date</th>
                        <th class="px-4 py-2">Assign Rider</th>
                        <th class="px-4 py-2">Actions</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-200">
                    @forelse($allOrders as $order)
                        <tr>
                            {{-- Serial --}}
                            <td class="px-4 py-2">
                                {{ ($allOrders->currentPage() - 1) * $allOrders->perPage() + $loop->iteration }}
                            </td>

                            {{-- Order Number --}}
                            <td class="px-4 py-2 font-semibold">
                                {{ $order->order_number }}
                            </td>

                            {{-- Customer --}}
                            <td class="px-4 py-2">
                                {{ $order->user->name ?? 'N/A' }}
                            </td>

                            {{-- Contact --}}
                            <td class="px-4 py-2 text-gray-600">
                                {{ $order->user->contact_number ?? 'N/A' }}
                            </td>

                            {{-- Order Type --}}
                            <td class="px-4 py-2">
                                @if($order->order_type === 'food')
                                    <span class="px-2 py-1 text-xs rounded bg-orange-100 text-orange-800">
                                        🍔 Food
                                    </span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded bg-green-100 text-green-800">
                                        💊 Medicine
                                    </span>
                                @endif
                            </td>

                            {{-- Store --}}
                            <td class="px-4 py-2">
                                {{ $order->medicalstore->Name ?? $order->restaurant->Name ?? 'N/A' }}
                            </td>

                            {{-- Total --}}
                            <td class="px-4 py-2 font-semibold">
                                Rs. {{ number_format($order->total_amount, 2) }}
                            </td>

                            {{-- Payment --}}
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    {{ $order->payment_status === 'paid'
                                        ? 'bg-green-100 text-green-800'
                                        : 'bg-yellow-100 text-yellow-800' }}">
                                    {{ strtoupper($order->payment_method) }} -
                                    {{ ucfirst($order->payment_status) }}
                                </span>
                            </td>

                            {{-- Status --}}
                            <td class="px-4 py-2">
                                <span class="px-2 py-1 rounded text-xs
                                    @switch($order->order_status)
                                        @case('pending') bg-yellow-100 text-yellow-800 @break
                                        @case('accepted') bg-blue-100 text-blue-800 @break
                                        @case('preparing') bg-orange-100 text-orange-800 @break
                                        @case('packed') bg-purple-100 text-purple-800 @break
                                        @case('out_for_delivery') bg-indigo-100 text-indigo-800 @break
                                        @case('delivered') bg-green-100 text-green-800 @break
                                        @case('cancelled') bg-red-100 text-red-800 @break
                                    @endswitch
                                ">
                                    {{ ucfirst(str_replace('_',' ', $order->order_status)) }}
                                </span>
                            </td>

                            {{-- Date --}}
                            <td class="px-4 py-2">
                                {{ $order->created_at->format('Y-m-d') }}
                            </td>

                            {{-- Assign Rider --}}
                            <td class="px-4 py-2">
                                <select class="border rounded px-2 py-1 text-sm">
                                    <option value="">Select Rider</option>
                                    {{-- loop riders here --}}
                                </select>
                            </td>

                            {{-- Actions --}}
                            <td class="px-4 py-2 flex space-x-3">

                                {{-- ------------
                                VIEW ORDERS DETAILS 
                                -------------------}}
                                <a href="{{ route('orders.show', $order->id) }}"
                                class="text-gray-600 hover:text-gray-900">
                                    <i class="fas fa-eye"></i>
                                </a>

                                
                                <a href="javascript:void(0)"
                                class="text-indigo-600 hover:text-indigo-800 edit-btn"
                                data-id="{{ $order->id }}">
                                    <i class="fas fa-edit"></i>
                                </a>

                                {{-- ------------
                                DELETE ORDER 
                                ----------------}}
                                <form method="POST" action="{{ route('orders.destroy', $order->id) }}" class="inline delete-form" data-status="{{ $order->order_status }}">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>

                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="12" class="px-4 py-4 text-center text-gray-500">
                                No orders found.
                            </td>
                        </tr>       
                    @endforelse                
                </tbody>
            </table>
        </div>

        {{-- PAGINATION --}}
        <div class="flex flex-col md:flex-row items-center justify-between px-6 py-4 bg-gray-50 border-t">
            <div class="text-sm text-gray-600">
                Showing <strong>{{ $allOrders->firstItem() ?? 0 }}</strong> to <strong>{{ $allOrders->lastItem() ?? 0 }}</strong> of <strong>{{ $allOrders->total() }}</strong> results
            </div>
            <div class="mt-3 md:mt-0">{{ $allOrders->links() }}</div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
   
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            
            // Delete confirmation
            document.querySelectorAll('.delete-form').forEach(form => {
                form.addEventListener('submit', function (e) {
                    e.preventDefault();

                    const status = form.dataset.status;
                    const blockedStatuses = ['accepted', 'preparing', 'packed'];

                    if (blockedStatuses.includes(status)) {
                        Swal.fire({
                            title: 'Action Not Allowed!',
                            text: 'This order cannot be deleted in its current status.',
                            icon: 'warning',
                            confirmButtonColor: '#6c757d'
                        });
                        return;
                    }

                    Swal.fire({
                        title: 'Are you sure?',
                        text: 'This action cannot be undone!',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#e3342f',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
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