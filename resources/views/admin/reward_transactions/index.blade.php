@extends('layouts.admin.app')
@section('title', 'Admin | Reward Transactions')

@section('contents')
<div class="flex-1 p-4 md:p-6 bg-gray-50">
    <div class="mb-6 flex justify-between items-center flex-wrap">
        <div class="mb-2 md:mb-0">
            <h2 class="text-2xl font-bold text-gray-800 flex items-center">
                <i class="fas fa-coins text-yellow-600 mr-2"></i> Reward Transactions
            </h2>
            <p class="text-gray-600">View all reward coin transactions and user details</p>
        </div>
    </div>

    <!-- Filters Section -->
    <div class="bg-white rounded-lg shadow p-4 mb-6">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <!-- Search Input -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Search</label>
                <input type="text" name="search" value="{{ $search }}" placeholder="Name, email, description..."
                    class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
            </div>

            <!-- Type Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Transaction Type</label>
                <select name="type" class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Types</option>
                    @foreach($types as $t)
                        <option value="{{ $t }}" {{ $type === $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select name="status" class="w-full border rounded-md px-3 py-2 text-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">All Status</option>
                    <option value="1" {{ $status === '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ $status === '2' ? 'selected' : '' }}>Active</option>
                    <option value="3" {{ $status === '3' ? 'selected' : '' }}>Redeemed</option>
                    <option value="4" {{ $status === '4' ? 'selected' : '' }}>Expired</option>
                </select>
            </div>

            <!-- Button Group -->
            <div class="flex items-end gap-2">
                <button type="submit" class="flex-1 px-4 py-2 bg-indigo-600 text-white text-sm rounded hover:bg-indigo-700 transition">
                    <i class="fas fa-search mr-2"></i> Filter
                </button>
                <a href="{{ route('admin.reward-transactions.index') }}" class="flex-1 px-4 py-2 bg-gray-300 text-gray-700 text-sm rounded hover:bg-gray-400 transition text-center">
                    <i class="fas fa-redo mr-2"></i> Reset
                </a>
            </div>
        </form>
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
