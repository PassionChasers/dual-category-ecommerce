<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Customer;
use App\Models\MedicalStore;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Invoice;
use App\Models\RewardCoin;
use App\Models\Ad;
use App\Models\Notification;
use Carbon\Carbon;
use DateInterval;
use DatePeriod;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        // Safe defaults so the view never breaks
        $stats = [
            'totalUsers'             => 0,
            'activeUsers'            => 0,
            'totalCustomers'         => 0,
            'totalMedicalStores'     => 0,
            'totalRestaurants'       => 0,
            'totalOrders'            => 0,
            'pendingOrders'          => 0,
            'inProgressOrders'       => 0,
            'completedOrders'        => 0,
            'cancelledOrders'        => 0,
            'totalRevenue'           => 0.0,
            'avgOrderValue'          => 0.0,
            'totalRewardCoinsIssued' => 0,
            'totalRewardCoinsUsed'   => 0,
            'activeAds'              => 0,
            'unreadNotifications'    => 0,
            'medicalOrders'          => 0,
            'foodOrders'             => 0,
        ];

        $ordersPerDayChart = ['labels' => [], 'data' => []];
        $revenuePerDayChart = ['labels' => [], 'data' => []];
        $moduleSplitChart = ['labels' => [], 'data' => []];
        $recentOrders = collect();
        $activityFeed = collect();

        try {
            // OPTIMIZED: Use single query with aggregates instead of multiple count() calls
            $userStats = DB::table('users')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN is_active = true THEN 1 ELSE 0 END) as active')
                )
                ->first();

            $stats['totalUsers']  = (int) ($userStats->total ?? 0);
            $stats['activeUsers'] = (int) ($userStats->active ?? 0);

            // Quick counts for simple models
            $stats['totalCustomers']     = Customer::count();
            $stats['totalMedicalStores'] = MedicalStore::count();
            $stats['totalRestaurants']   = Restaurant::count();

            // OPTIMIZED: Single query for all order statuses
            $orderStats = DB::table('orders')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN status = "pending" THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN status IN ("accepted", "preparing", "dispatched") THEN 1 ELSE 0 END) as inprogress'),
                    DB::raw('SUM(CASE WHEN status IN ("delivered", "completed") THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN status = "cancelled" THEN 1 ELSE 0 END) as cancelled')
                )
                ->first();

            $stats['totalOrders']       = (int) ($orderStats->total ?? 0);
            $stats['pendingOrders']     = (int) ($orderStats->pending ?? 0);
            $stats['inProgressOrders']  = (int) ($orderStats->inprogress ?? 0);
            $stats['completedOrders']   = (int) ($orderStats->completed ?? 0);
            $stats['cancelledOrders']   = (int) ($orderStats->cancelled ?? 0);

            // OPTIMIZED: Single query for revenue stats
            $invoiceStats = DB::table('invoices')
                ->where('payment_status', 'paid')
                ->select(
                    DB::raw('SUM(total_amount) as total_revenue'),
                    DB::raw('AVG(total_amount) as avg_value')
                )
                ->first();

            $stats['totalRevenue'] = (float) ($invoiceStats->total_revenue ?? 0);
            $stats['avgOrderValue'] = (float) ($invoiceStats->avg_value ?? 0);

            // OPTIMIZED: Single query for reward coins
            $coinStats = DB::table('reward_coins')
                ->select(
                    DB::raw('SUM(amount) as issued'),
                    DB::raw('SUM(CASE WHEN is_used = true THEN amount ELSE 0 END) as used')
                )
                ->first();

            $stats['totalRewardCoinsIssued'] = (int) ($coinStats->issued ?? 0);
            $stats['totalRewardCoinsUsed']   = (int) ($coinStats->used ?? 0);

            // Quick counts for ads and notifications
            $stats['activeAds']           = Ad::where('is_active', true)->count();
            $stats['unreadNotifications'] = Notification::where('is_read', false)->count();

            // --- Orders by module (medical vs food) ---
            $ordersByModule = Order::select('module_type', DB::raw('COUNT(*) as total'))
                ->groupBy('module_type')
                ->get()
                ->pluck('total', 'module_type')
                ->toArray();

            $stats['medicalOrders'] = (int) ($ordersByModule['medical'] ?? 0);
            $stats['foodOrders']    = (int) ($ordersByModule['food'] ?? 0);

            // --- Orders per day (last 7 days) ---
            $fromDate = Carbon::now()->subDays(6)->startOfDay();
            $toDate   = Carbon::now()->endOfDay();

            $ordersPerDayRaw = Order::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $ordersMap = $ordersPerDayRaw->pluck('total', 'date')->toArray();

            $ordersPerDayChart = ['labels' => [], 'data' => []];
            $period1 = new DatePeriod($fromDate, new DateInterval('P1D'), $toDate->copy()->addDay()->startOfDay());
            foreach ($period1 as $date) {
                $key = $date->format('Y-m-d');
                $ordersPerDayChart['labels'][] = $date->format('d M');
                $ordersPerDayChart['data'][]   = (int) ($ordersMap[$key] ?? 0);
            }

            // --- Revenue per day (last 7 days) ---
            $revenuePerDayRaw = Invoice::select(
                    DB::raw('DATE(created_at) as date'),
                    DB::raw('SUM(total_amount) as total')
                )
                ->where('payment_status', 'paid')
                ->whereBetween('created_at', [$fromDate, $toDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $revenueMap = $revenuePerDayRaw->pluck('total', 'date')->toArray();

            $revenuePerDayChart = ['labels' => [], 'data' => []];
            $period2 = new DatePeriod($fromDate, new DateInterval('P1D'), $toDate->copy()->addDay()->startOfDay());
            foreach ($period2 as $date) {
                $key = $date->format('Y-m-d');
                $revenuePerDayChart['labels'][] = $date->format('d M');
                $revenuePerDayChart['data'][]   = (float) ($revenueMap[$key] ?? 0);
            }

            // --- Module split (for doughnut chart) ---
            $moduleSplitRaw = Order::select('module_type', DB::raw('COUNT(*) as total'))
                ->groupBy('module_type')
                ->get();

            $moduleSplitChart = [
                'labels' => $moduleSplitRaw->pluck('module_type')
                    ->map(function ($m) {
                        return ucfirst($m ?? 'Unknown');
                    })
                    ->toArray(),
                'data'   => $moduleSplitRaw->pluck('total')->map(fn ($v) => (int) $v)->toArray(),
            ];

            // --- Recent orders with invoice & customer ---
            $recentOrders = Order::with(['customer', 'invoice'])
                ->latest('created_at')
                ->limit(5)
                ->get();

            // --- Latest notifications / system activity ---
            $activityFeed = Notification::latest('created_at')
                ->limit(7)
                ->get();
        } catch (\Throwable $e) {
            // Log everything, but don't break the UI
            Log::error('Error loading admin dashboard', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Soft feedback for admin
            session()->flash('error', 'Some dashboard data could not be loaded. Showing partial information.');
        }

        return view('admin.dashboard', [
            'stats'              => $stats,
            'ordersPerDayChart'  => $ordersPerDayChart,
            'revenuePerDayChart' => $revenuePerDayChart,
            'moduleSplitChart'   => $moduleSplitChart,
            'recentOrders'       => $recentOrders instanceof Collection ? $recentOrders : collect($recentOrders),
            'activityFeed'       => $activityFeed instanceof Collection ? $activityFeed : collect($activityFeed),
        ]);
    }
}
