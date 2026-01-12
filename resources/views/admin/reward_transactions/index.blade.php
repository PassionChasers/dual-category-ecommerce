@extends('layouts.admin.app')
@section('title', 'Admin | Reward Transactions')

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-coins text-yellow-600 mr-2"></i> Reward Transactions
            </h2>
            <p class="text-gray-600">View all reward coin transactions and user details</p>
        </div>

        <!-- Filters Section -->
        <div class="flex flex-col md:flex-row md:justify-between md:items-center gap-2">
            <form method="GET" class="flex gap-2 items-center flex-wrap">
                <!-- Search Input -->
                <input type="text" name="search" value="{{ $search }}" placeholder="Search by name, email..."
                    class="px-3 py-2 border rounded-md bg-white text-sm focus:ring-indigo-500 focus:border-indigo-500">
                
                <!-- Search Button -->
                <button type="submit" class="px-3 py-2 rounded-md hover:bg-gray-100 cursor-pointer transition">
                    <i class="fas fa-search"></i>
                </button>

                <!-- Type Filter -->
                <select name="type" onchange="this.form.submit()" class="px-3 py-2 border rounded-md text-sm cursor-pointer">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>

                <!-- Status Filter -->
                <select name="status" onchange="this.form.submit()" class="px-3 py-2 border rounded-md text-sm cursor-pointer">
                    <option value="">All Status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ $status === '2' ? 'selected' : '' }}>Active</option>
                    <option value="3" {{ $status === '3' ? 'selected' : '' }}>Redeemed</option>
                    <option value="4" {{ $status === '4' ? 'selected' : '' }}>Expired</option>
                </select>

                <!-- Reset Button -->
                <a href="{{ route('admin.reward-transactions.index') }}" class="px-3 py-2 bg-gray-200 text-gray-700 text-sm rounded hover:bg-gray-300 transition text-center">
                    <i class="fas fa-redo"></i> Reset
                </a>
            </form>
        </div>
    </div>

    <!-- Table Section -->
    <div class="bg-white shadow rounded-lg overflow-hidden">
        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Reward Transaction Records</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">#</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Customer Name</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Email</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Phone</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Type</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Amount</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Description</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Status</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Date</th>
                        <th class="px-4 py-2 text-left font-semibold text-gray-700">Order ID</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($transactions as $index => $transaction)
                    <tr class="hover:bg-gray-50">
                        <td class="px-4 py-2">{{ $transactions->firstItem() + $index }}</td>
                        <td class="px-4 py-2 font-medium text-gray-800">
                            {{ $transaction->customer->Name ?? 'Unknown' }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ $transaction->customer->Email ?? '-' }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ $transaction->customer->Phone ?? '-' }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                @if($transaction->Type === 'earned') bg-green-100 text-green-700 
                                @elseif($transaction->Type === 'redeemed') bg-red-100 text-red-700 
                                @elseif($transaction->Type === 'bonus') bg-blue-100 text-blue-700 
                                @elseif($transaction->Type === 'referral') bg-purple-100 text-purple-700 
                                @else bg-gray-100 text-gray-700 @endif">
                                {{ ucfirst($transaction->Type) }}
                            </span>
                        </td>
                        <td class="px-4 py-2 font-semibold text-gray-800">
                            {{ number_format($transaction->Amount, 2) }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            {{ Str::limit($transaction->Description, 30) }}
                        </td>
                        <td class="px-4 py-2">
                            <span class="px-2 py-1 rounded text-xs font-medium 
                                @if($transaction->Status == 1) bg-yellow-100 text-yellow-700 
                                @elseif($transaction->Status == 2) bg-green-100 text-green-700 
                                @elseif($transaction->Status == 3) bg-purple-100 text-purple-700 
                                @elseif($transaction->Status == 4) bg-red-100 text-red-700 
                                @else bg-gray-100 text-gray-700 @endif">
                                @if($transaction->Status == 1) Pending
                                @elseif($transaction->Status == 2) Active
                                @elseif($transaction->Status == 3) Redeemed
                                @elseif($transaction->Status == 4) Expired
                                @else Unknown @endif
                            </span>
                        </td>
                        <td class="px-4 py-2 text-gray-600 whitespace-nowrap">
                            {{ $transaction->TransactionDate->format('d M Y, H:i') }}
                        </td>
                        <td class="px-4 py-2 text-gray-600">
                            @if($transaction->OrderId)
                                <a href="{{ route('orders.showProductDetail', $transaction->OrderId) }}" 
                                   class="text-indigo-600 hover:underline text-xs">
                                    {{ Str::limit($transaction->OrderId, 8) }}
                                </a>
                            @else
                                <span class="text-gray-400">-</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="px-4 py-4 text-center text-gray-500">No reward transactions found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($transactions->hasPages())
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
            {{ $transactions->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
