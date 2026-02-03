@extends('layouts.admin.app')

@section('title', 'Business Admin Dashboard')

@push('styles')
    {{-- Extra dashboard-specific styles if needed --}}
@endpush
@push('charts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
@endpush


@section('contents')
    {{-- @php
    dd($recentOrders);
@endphp --}}
    <!-- Main content area -->
    <div class="flex-1 overflow-auto bg-gray-50">
        <div id="dashboard-content" class="content-page p-4 md:p-6">
            <div class="mb-6 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
                <div>
                    <!-- <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2> -->
                    <p class="text-gray-600">
                        Welcome back! Here’s an overview of your platform today,
                        <span id="current-date" class="font-medium"></span>.
                    </p>
                </div>

                {{-- Dashboard partial-error flash removed per request --}}
            </div>

            <!-- Charts moved to footer area -->

            {{-- ================= TOP STATS CARDS ================= --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <!-- Total Users -->
                {{-- <a href="{{ route('users.index') }}" class="block">
                    <div class="bg-white overflow-hidden shadow rounded-lg hover:shadow-lg transition">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <i class="fas fa-users text-white"></i>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Users</dt>
                                        <dd class="flex items-baseline">
                                            <div id="stat-totalUsers" class="text-2xl font-semibold text-gray-900">
                                                {{ number_format($stats['totalUsers'] ?? 0) }}
                                            </div>
                                        </dd>
                                    </dl>
                                    <p class="text-xs text-gray-500 mt-1">
                                        Active: <span
                                            id="stat-activeUsers">{{ number_format($stats['activeUsers'] ?? 0) }}</span>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </a> --}}

                <!-- Total Customers -->
                {{-- <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                <i class="fas fa-user-tag text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Customers</dt>
                                    <dd class="flex items-baseline">
                                        <div id="stat-totalCustomers" class="text-2xl font-semibold text-gray-900">
                                            {{ number_format($stats['totalCustomers'] ?? 0) }}
                                        </div>
                                    </dd>
                                </dl>
                                <p class="text-xs text-gray-500 mt-1">
                                    Medical &amp; Food apps combined
                                </p>
                            </div>
                        </div>
                    </div>
                </div> --}}

                <!-- Medical Orders -->
                @if(auth()->user()->Role === 2)
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-emerald-500 rounded-md p-3">
                                <i class="fas fa-prescription-bottle-alt text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Medicine Orders</dt>
                                    <dd class="flex items-baseline">
                                        <div id="stat-medicalOrders" class="text-2xl font-semibold text-gray-900">
                                            {{ number_format($stats['medicalOrders'] ?? 0) }}
                                        </div>
                                    </dd>
                                </dl>
                                <p class="text-xs text-gray-500 mt-1">
                                    From customers for medicines
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Food Orders -->
                @if(auth()->user()->Role === 3)
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-500 rounded-md p-3">
                                <i class="fas fa-hamburger text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dl>
                                    <dt class="text-sm font-medium text-gray-500 truncate">Food Orders</dt>
                                    <dd class="flex items-baseline">
                                        <div id="stat-foodOrders" class="text-2xl font-semibold text-gray-900">
                                            {{ number_format($stats['foodOrders'] ?? 0) }}
                                        </div>
                                    </dd>
                                </dl>
                                <p class="text-xs text-gray-500 mt-1">
                                    From customers for food items
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                <!-- Total Revenue -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                <i class="fas fa-rupee-sign text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Revenue</dt>
                                <dd id="stat-totalRevenue" class="text-2xl font-semibold text-gray-900">
                                    Rs. {{ number_format($stats['totalRevenue'] ?? 0, 2) }}
                                </dd>
                                <p class="text-xs text-gray-500 mt-1">
                                    Paid invoices only
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Avg Order Value -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-teal-500 rounded-md p-3">
                                <i class="fas fa-receipt text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Avg Order Value</dt>
                                <dd id="stat-avgOrderValue" class="text-2xl font-semibold text-gray-900">
                                    Rs. {{ number_format($stats['avgOrderValue'] ?? 0, 2) }}
                                </dd>
                                <p class="text-xs text-gray-500 mt-1">
                                    Based on paid invoices
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Reward Coins -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-yellow-500 rounded-md p-3">
                                <i class="fas fa-coins text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Reward Coins</dt>
                                <dd class="text-xl font-semibold text-gray-900">
                                    Issued: {{ number_format($stats['totalRewardCoinsIssued'] ?? 0) }}
                                </dd>
                                <p class="text-xs text-gray-500 mt-1">
                                    Used: {{ number_format($stats['totalRewardCoinsUsed'] ?? 0) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- SECOND ROW METRICS --}}
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
                

                <!-- Active Ads / Notifications -->
                <div class="bg-white overflow-hidden shadow rounded-lg">
                    <div class="px-4 py-5 sm:p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-pink-500 rounded-md p-3">
                                <i class="fas fa-bullhorn text-white"></i>
                            </div>
                            <div class="ml-5 w-0 flex-1">
                                <dt class="text-sm font-medium text-gray-500 truncate">Active Ads</dt>
                                <dd class="text-xl font-semibold text-gray-900">
                                    {{ number_format($stats['activeAds'] ?? 0) }}
                                </dd>
                                <p class="text-xs text-gray-500 mt-1">
                                    Unread notifications: {{ number_format($stats['unreadNotifications'] ?? 0) }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- ================= ORDER STATUS OVERVIEW ================= --}}
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
                <!-- Pending Orders -->
                <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col h-full">
                    <div class="px-4 py-4 border-b border-gray-200 bg-yellow-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-yellow-800">
                                <i class="fas fa-clock mr-1"></i>Pending Orders
                            </h3>
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $stats['pendingOrders'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 flex-1 overflow-y-auto max-h-80">
                        @forelse($pendingOrders as $order)
                            <div class="px-4 py-3 hover:bg-gray-50 transition text-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">#{{ $order->OrderNumber }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->customer->Name ?? 'Unknown' }}</p>
                                        @if ($order->items && count($order->items) > 0)
                                            <p class="text-xs text-gray-600 mt-1">
                                                @foreach ($order->items as $item)
                                                    <span class="inline-block">
                                                        @if ($item->food)
                                                            {{ $item->food->Name ?? 'Unknown' }}
                                                        @elseif($item->medicine)
                                                            {{ $item->medicine->Name ?? 'Unknown' }}
                                                        @else
                                                            {{ $item->ItemName ?? 'Unknown' }}
                                                        @endif
                                                    </span>
                                                    @if (!$loop->last)
                                                        <span class="mx-1">•</span>
                                                    @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">Rs.
                                            {{ number_format($order->TotalAmount, 2) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->CreatedAt)->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center py-6 text-gray-500 text-sm">
                                No pending orders
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-center border-t border-gray-200 mt-auto">
                        <!-- <a href="#"
                               class="text-sm font-medium text-yellow-600 hover:text-yellow-500">
                                View all pending orders
                            </a> -->
                    </div>
                </div>

                <!-- Assigned Orders -->
                <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col h-full">
                    <div class="px-4 py-4 border-b border-gray-200 bg-purple-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-purple-800">
                                <i class="fas fa-tasks mr-1"></i>Assigned Orders
                            </h3>
                            <span class="bg-purple-100 text-purple-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $stats['assignedOrders'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 flex-1 overflow-y-auto max-h-80">
                        @forelse($assignedOrders as $order)
                            <div class="px-4 py-3 hover:bg-gray-50 transition text-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">#{{ $order->OrderNumber }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->customer->Name ?? 'Unknown' }}</p>
                                        @if ($order->items && count($order->items) > 0)
                                            <p class="text-xs text-gray-600 mt-1">
                                                @foreach ($order->items as $item)
                                                    <span class="inline-block">
                                                        @if ($item->food)
                                                            {{ $item->food->Name ?? 'Unknown' }}
                                                        @elseif($item->medicine)
                                                            {{ $item->medicine->Name ?? 'Unknown' }}
                                                        @else
                                                            {{ $item->ItemName ?? 'Unknown' }}
                                                        @endif
                                                    </span>
                                                    @if (!$loop->last)
                                                        <span class="mx-1">•</span>
                                                    @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">Rs.
                                            {{ number_format($order->TotalAmount, 2) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->CreatedAt)->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center py-6 text-gray-500 text-sm">
                                No assigned orders
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-center border-t border-gray-200 mt-auto">
                        <!-- <a href="#"
                               class="text-sm font-medium text-purple-600 hover:text-purple-500">
                                View all assigned orders
                            </a> -->
                    </div>
                </div>

                <!-- Completed Orders -->
                <div class="bg-white shadow rounded-lg overflow-hidden flex flex-col h-full">
                    <div class="px-4 py-4 border-b border-gray-200 bg-green-50">
                        <div class="flex items-center justify-between">
                            <h3 class="text-sm font-medium text-green-800">
                                <i class="fas fa-check-circle mr-1"></i>Completed
                            </h3>
                            <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-0.5 rounded-full">
                                {{ $stats['completedOrders'] ?? 0 }}
                            </span>
                        </div>
                    </div>
                    <div class="divide-y divide-gray-200 flex-1 overflow-y-auto max-h-80">
                        @forelse($completedOrders as $order)
                            <div class="px-4 py-3 hover:bg-gray-50 transition text-sm">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1">
                                        <p class="font-medium text-gray-900">#{{ $order->OrderNumber }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->customer->Name ?? 'Unknown' }}</p>
                                        @if ($order->items && count($order->items) > 0)
                                            <p class="text-xs text-gray-600 mt-1">
                                                @foreach ($order->items as $item)
                                                    <span class="inline-block">
                                                        @if ($item->food)
                                                            {{ $item->food->Name ?? 'Unknown' }}
                                                        @elseif($item->medicine)
                                                            {{ $item->medicine->Name ?? 'Unknown' }}
                                                        @else
                                                            {{ $item->ItemName ?? 'Unknown' }}
                                                        @endif
                                                    </span>
                                                    @if (!$loop->last)
                                                        <span class="mx-1">•</span>
                                                    @endif
                                                @endforeach
                                            </p>
                                        @endif
                                    </div>
                                    <div class="text-right">
                                        <p class="font-medium text-gray-900">Rs.
                                            {{ number_format($order->TotalAmount, 2) }}</p>
                                        <p class="text-xs text-gray-500">
                                            {{ \Carbon\Carbon::parse($order->CreatedAt)->format('d M, H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="flex items-center justify-center py-6 text-gray-500 text-sm">
                                No completed orders
                            </div>
                        @endforelse
                    </div>
                    <div class="px-4 py-3 bg-gray-50 text-center border-t border-gray-200 mt-auto">
                        <!-- <a href="#"
                               class="text-sm font-medium text-green-600 hover:text-green-500">
                                View all completed orders
                            </a> -->
                    </div>
                </div>
            </div>

            {{-- ================= MAIN DASHBOARD CONTENT ================= --}}


            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Column - 2/3 width -->
                <div class="lg:col-span-2 space-y-6">
                    <!-- Recent Orders -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Orders</h3>
                        </div>
                        <div class="divide-y divide-gray-200">
                            @forelse($recentOrders as $order)
                                <div class="px-4 py-4 sm:px-6">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center flex-1">
                                            <div class="flex-1">
                                                <p class="text-sm font-medium text-gray-900">
                                                    Order #{{ $order->OrderNumber }}
                                                </p>
                                                <p class="text-xs text-gray-500">
                                                    {{ $order->customer->Name ?? 'Unknown Customer' }}
                                                    · {{ $order->CreatedAt->format('d M Y, H:i') }}
                                                </p>
                                                @if ($order->items && count($order->items) > 0)
                                                    <p class="text-xs text-gray-600 mt-1">
                                                        @foreach ($order->items as $item)
                                                            <span class="inline-block">
                                                                @if ($item->food)
                                                                    {{ $item->food->Name ?? 'Unknown Item' }}
                                                                @elseif($item->medicine)
                                                                    {{ $item->medicine->Name ?? 'Unknown Item' }}
                                                                @else
                                                                    {{ $item->ItemName ?? 'Unknown Item' }}
                                                                @endif
                                                                (x{{ $item->Quantity }})
                                                            </span>
                                                            @if (!$loop->last)
                                                                <span class="mx-1">•</span>
                                                            @endif
                                                        @endforeach
                                                    </p>
                                                @endif
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-3">
                                            @php
                                                $status = strtolower($order->Status ?? 'unknown');
                                                $statusMap = [
                                                    'pending' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-800'],
                                                    'pendingreview' => [
                                                        'bg' => 'bg-yellow-100',
                                                        'text' => 'text-yellow-800',
                                                    ],
                                                    'accepted' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                    'preparing' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                    'packed' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                    'dispatched' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-800'],
                                                    'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                                    'completed' => ['bg' => 'bg-green-100', 'text' => 'text-green-800'],
                                                    'cancelled' => ['bg' => 'bg-red-100', 'text' => 'text-red-800'],
                                                ];
                                                $colors = $statusMap[$status] ?? [
                                                    'bg' => 'bg-gray-100',
                                                    'text' => 'text-gray-800',
                                                ];
                                            @endphp
                                            <span
                                                class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded {{ $colors['bg'] }} {{ $colors['text'] }}">
                                                {{ ucfirst(str_replace('pendingreview', 'Pending', $status)) }}
                                            </span>
                                            <span
                                                class="px-2 py-1 rounded text-xs bg-indigo-50 text-indigo-700 font-medium">
                                                Rs. {{ number_format($order->TotalAmount, 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex items-center justify-center py-10">
                                    <p class="text-gray-600 text-lg">No orders found.</p>
                                </div>
                            @endforelse
                        </div>
                        <div class="px-4 py-4 sm:px-6 bg-gray-50 text-sm text-right">
                            <!-- <a href="#"
                                   class="font-medium text-indigo-600 hover:text-indigo-500">
                                    View all orders
                                </a> -->
                        </div>
                    </div>

                    <!-- Charts removed from left column (moved to full-width row) -->
                </div>

                <!-- Right Column - 1/3 width -->
                <div class="space-y-6">
                    <!-- Platform Snapshot -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Platform Snapshot</h3>
                        </div>
                        <div class="p-4 space-y-4 text-sm text-gray-700">
                            {{-- <div class="flex items-center justify-between">
                                <span>Medical Stores</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($stats['totalMedicalStores'] ?? 0) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Restaurants</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($stats['totalRestaurants'] ?? 0) }}
                                </span>
                            </div> --}}
                            <div class="flex items-center justify-between">
                                <span>Total Orders</span>
                                <span class="font-semibold text-gray-900">
                                    {{ number_format($stats['totalOrders'] ?? 0) }}
                                </span>
                            </div>
                            <div class="flex items-center justify-between">
                                <span>Cancelled Orders</span>
                                <span class="font-semibold text-red-600">
                                    {{ number_format($stats['cancelledOrders'] ?? 0) }}
                                </span>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Notifications -->
                    <div class="bg-white shadow rounded-lg overflow-hidden">
                        <div class="px-4 py-5 sm:px-6 border-b border-gray-200 flex items-center justify-between">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">Recent Notifications</h3>
                            <span class="text-xs bg-indigo-50 text-indigo-700 px-2 py-0.5 rounded-full">
                                {{ $stats['unreadNotifications'] ?? 0 }} unread
                            </span>
                        </div>
                        <div class="p-4 space-y-3 max-h-72 overflow-y-auto">
                            @forelse($activityFeed as $notification)
                                <div class="flex items-start gap-3">
                                    <div class="mt-1">
                                        <i class="fas fa-bell text-xs text-indigo-500"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm font-medium text-gray-900">
                                            {{ $notification->title ?? 'Notification' }}
                                        </p>
                                        <p class="text-xs text-gray-500">
                                            {{ Str::limit($notification->message ?? '', 80) }}
                                        </p>
                                        <p class="text-[11px] text-gray-400 mt-1">
                                            {{ optional($notification->created_at)->diffForHumans() ?? '' }}
                                        </p>
                                    </div>
                                </div>
                            @empty
                                <p class="text-sm text-gray-500">No recent notifications.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts: four equal cards in a single row, aligned with other sections -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                <div class="bg-white p-4 sm:p-6 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Orders (Last 7 Days)</h3>
                    <div class="flex-1">
                        <canvas id="ordersPerDayChart" class="w-full h-48"></canvas>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Revenue (Last 7 Days)</h3>
                    <div class="flex-1">
                        <canvas id="revenuePerDayChart" class="w-full h-48"></canvas>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Orders by Module</h3>
                    <div class="flex-1">
                        <canvas id="moduleSplitChart" class="w-full h-48"></canvas>
                    </div>
                </div>
                <div class="bg-white p-4 sm:p-6 shadow rounded-lg flex flex-col">
                    <h3 class="text-sm font-medium text-gray-900 mb-2">Order Status</h3>
                    <div class="flex-1">
                        <canvas id="orderStatusChart" class="w-full h-48"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        <div class="bg-gray-200 text-left p-4 w-full">
            <p class="text-sm text-gray-600">&copy; {{ date('Y') }} Passion Chasers. All rights reserved.</p>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        // Set current date nicely
        document.addEventListener('DOMContentLoaded', function() {
            const el = document.getElementById('current-date');
            if (el) {
                const now = new Date();
                const options = {
                    year: 'numeric',
                    month: 'short',
                    day: 'numeric'
                };
                el.textContent = now.toLocaleDateString(undefined, options);
            }
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ordersPerDay = @json($ordersPerDayChart);
            const revenuePerDay = @json($revenuePerDayChart);
            const moduleSplit = @json($moduleSplitChart);
            const orderStatus = @json($orderStatusChart ?? ['labels' => [], 'data' => []]);

            if (typeof Chart === 'undefined') {
                console.warn('Chart.js is not loaded; dashboard charts will not render.');
                return;
            }

            // Orders per day chart
            const ordersCtx = document.getElementById('ordersPerDayChart');
            if (ordersCtx && ordersPerDay.labels && ordersPerDay.labels.length) {
                new Chart(ordersCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: ordersPerDay.labels,
                        datasets: [{
                            label: 'Orders',
                            data: ordersPerDay.data,
                            backgroundColor: '#6366f1',
                            borderColor: '#4f46e5',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }


            // Revenue per day chart
            const revenueCtx = document.getElementById('revenuePerDayChart');
            if (revenueCtx) {
                new Chart(revenueCtx.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: revenuePerDay.labels && revenuePerDay.labels.length ? revenuePerDay.labels :
                            ['No data'],
                        datasets: [{
                            label: 'Revenue (Rs.)',
                            data: revenuePerDay.data && revenuePerDay.data.length ? revenuePerDay
                                .data : [0],
                            fill: false,
                            backgroundColor: '#22c55e',
                            borderColor: '#16a34a',
                            tension: 0.3
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Module split doughnut
            const moduleCtx = document.getElementById('moduleSplitChart');
            if (moduleCtx && moduleSplit.labels && moduleSplit.labels.length) {
                new Chart(moduleCtx.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: moduleSplit.labels,
                        datasets: [{
                            data: moduleSplit.data,
                            backgroundColor: ['#22c55e', '#f97316', '#6b7280'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            legend: {
                                position: 'bottom'
                            }
                        }
                    }
                });
            }

            // Order Status Distribution bar chart
            const orderStatusCtx = document.getElementById('orderStatusChart');
            if (orderStatusCtx && orderStatus.labels && orderStatus.labels.length) {
                new Chart(orderStatusCtx.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: orderStatus.labels,
                        datasets: [{
                            label: 'Orders',
                            data: orderStatus.data,
                            backgroundColor: ['#fbbf24', '#3b82f6', '#22c55e', '#ef4444'],
                            borderColor: ['#f59e0b', '#1e40af', '#16a34a', '#dc2626'],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        indexAxis: 'x',
                        responsive: true,
                        maintainAspectRatio: false,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            }
                        }
                    }
                });
            }
        });

        // Refresh stats removed — no runtime handler
    </script>
@endpush
