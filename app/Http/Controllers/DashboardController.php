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
        $pendingOrders = collect();
        $assignedOrders = collect();
        $completedOrders = collect();
        $activityFeed = collect();

        try {
            // OPTIMIZED: Use single query with aggregates instead of multiple count() calls
            $userStats = DB::table('Users')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN "IsActive" = true THEN 1 ELSE 0 END) as active')
                )
                ->first();

            $stats['totalUsers']  = (int) ($userStats->total ?? 0);
            $stats['activeUsers'] = (int) ($userStats->active ?? 0);
// dd($stats['activeUsers']);
            // Quick counts for simple models
            $stats['totalCustomers']     = Customer::count();
            $stats['totalMedicalStores'] = MedicalStore::count();
            $stats['totalRestaurants']   = Restaurant::count();

            // OPTIMIZED: Single query for all order statuses
            $orderStats = DB::table('Orders')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN "Status" = \'Pending\' OR "Status" = \'PendingReview\' THEN 1 ELSE 0 END) as pending'),
                    DB::raw('COUNT(DISTINCT CASE WHEN EXISTS(SELECT 1 FROM "OrderItems" WHERE "OrderItems"."OrderId" = "Orders"."OrderId" AND "OrderItems"."BusinessId" IS NOT NULL) THEN "Orders"."OrderId" END) as assigned'),
                    DB::raw('SUM(CASE WHEN "Status" IN (\'delivered\', \'Completed\') THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN "Status" = \'Cancelled\' THEN 1 ELSE 0 END) as cancelled')
                )
                ->first();
                // dd($orderStats);
                
            $stats['totalOrders']       = (int) ($orderStats->total ?? 0);
            $stats['pendingOrders']     = (int) ($orderStats->pending ?? 0);
            $stats['assignedOrders']    = (int) ($orderStats->assigned ?? 0);
            $stats['completedOrders']   = (int) ($orderStats->completed ?? 0);
            $stats['cancelledOrders']   = (int) ($orderStats->cancelled ?? 0);

            // OPTIMIZED: Single query for revenue stats
            // $invoiceStats = DB::table('Invoices')
            //     ->where('PaymentStatus', 'paid')
            //     ->select(
            //         DB::raw('SUM("TotalAmount") as total_revenue'),
            //         DB::raw('AVG("TotalAmount") as avg_value')
            //     )
            //     ->first();

                // dd( $invoiceStats);

            // $stats['totalRevenue'] = (float) ($invoiceStats->total_revenue ?? 0);
            // $stats['avgOrderValue'] = (float) ($invoiceStats->avg_value ?? 0);

            // Calculate total revenue from completed orders
            $revenueStats = DB::table('Orders')
                ->where('Status', 'Completed')
                ->select(
                    DB::raw('SUM("TotalAmount") as total_revenue'),
                    DB::raw('AVG("TotalAmount") as avg_value')
                )
                ->first();

            $stats['totalRevenue'] = (float) ($revenueStats->total_revenue ?? 0);
            $stats['avgOrderValue'] = (float) ($revenueStats->avg_value ?? 0);

            // OPTIMIZED: Single query for reward coins
            $coinStats = DB::table('RewardTransactions')
                ->select(
                    DB::raw('COUNT(*) as total')
                )
                ->first();

            $stats['totalRewardCoinsIssued'] = (int) ($coinStats->total ?? 0);
            $stats['totalRewardCoinsUsed']   = 0;

            // Quick counts for ads and notifications
            $stats['activeAds']           = Ad::where('IsActive', true)->count();
            $stats['unreadNotifications'] = 0; // Notification table doesn't exist

            // --- Orders by module (medical vs food) ---
            // Count distinct orders that have Medicine items
            $stats['medicalOrders'] = (int) DB::table('OrderItems')
                ->where('ItemType', 'Medicine')
                ->select('OrderId')
                ->distinct()
                ->count();

            // Count distinct orders that have food items
            $stats['foodOrders'] = (int) DB::table('OrderItems')
                ->where('ItemType', 'Food')
                ->select('OrderId')
                ->distinct()
                ->count();

            // --- Orders by status (latest 5 of each) ---
            try {
                $pendingOrders = Order::with('customer', 'items.food', 'items.medicine')
                    ->whereIn('Status', ['Pending', 'PendingReview'])
                    ->orderBy('CreatedAt', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching pending orders', ['error' => $e->getMessage()]);
                $pendingOrders = collect();
            }

            try {
                $assignedOrders = Order::with('customer', 'items.food', 'items.medicine')
                    ->whereHas('items', function ($query) {
                        $query->whereNotNull('BusinessId');
                    })
                    ->orderBy('CreatedAt', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching assigned orders', ['error' => $e->getMessage()]);
                $assignedOrders = collect();
            }

            try {
                $completedOrders = Order::with('customer', 'items.food', 'items.medicine')
                    ->whereIn('Status', ['delivered', 'Completed'])
                    ->orderBy('CreatedAt', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching completed orders', ['error' => $e->getMessage()]);
                $completedOrders = collect();
            }

            // --- Orders per day (last 7 days) ---
            $fromDate = Carbon::now()->subDays(6)->startOfDay();
            $toDate   = Carbon::now()->endOfDay();

            $ordersPerDayRaw = Order::select(
                    DB::raw('DATE("CreatedAt") as date'),
                    DB::raw('COUNT(*) as total')
                )
                ->whereBetween('CreatedAt', [$fromDate, $toDate])
                ->groupBy('date')
                ->orderBy('date')
                ->get();

            $ordersMap = $ordersPerDayRaw->pluck('total', 'date')->toArray();

            $ordersPerDayChart = ['labels' => [], 'data' => []];
            $period = new DatePeriod($fromDate, new DateInterval('P1D'), $toDate->copy()->addDay()->startOfDay());
            foreach ($period as $date) {
                $key = $date->format('Y-m-d');
                $ordersPerDayChart['labels'][] = $date->format('d M');
                $ordersPerDayChart['data'][]   = (int) ($ordersMap[$key] ?? 0);
            }

            // --- Revenue per day (last 7 days) ---
            $revenuePerDayRaw = Order::select(
                    DB::raw('DATE("CreatedAt") as date'),
                    DB::raw('SUM("TotalAmount") as total')
                )
                ->whereIn('Status', ['delivered', 'Completed'])
                ->whereBetween('CreatedAt', [$fromDate, $toDate])
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
            $medicalCount = DB::table('OrderItems')
                ->whereRaw('lower("ItemType") = ?', ['medicine'])
                ->distinct()
                ->count('OrderId');

            $foodCount = DB::table('OrderItems')
                ->where(function ($q) {
                    $q->whereRaw('lower("ItemType") = ?', ['menuitem'])
                      ->orWhereRaw('lower("ItemType") = ?', ['food']);
                })
                ->distinct()
                ->count('OrderId');

            $moduleSplitChart = [
                'labels' => ['Medical', 'Food'],
                'data'   => [$medicalCount, $foodCount],
            ];

            // --- Order Status Distribution Chart ---
            $orderStatusChart = [
                'labels' => ['Pending', 'Assigned', 'Completed', 'Cancelled'],
                'data'   => [
                    $stats['pendingOrders'],
                    $stats['assignedOrders'],
                    $stats['completedOrders'],
                    $stats['cancelledOrders'],
                ],
            ];

            // --- Recent orders with customer ---
            try {
                $recentOrders = Order::with('customer', 'items.food', 'items.medicine')
                    ->orderBy('CreatedAt', 'desc')
                    ->limit(5)
                    ->get();
            } catch (\Exception $e) {
                Log::error('Error fetching recent orders', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                $recentOrders = collect();
            }


            // --- Latest notifications / system activity ---
            $activityFeed = collect(); // Notification table doesn't exist
        } catch (\Throwable $e) {
            // Log everything, but don't break the UI
            Log::error('Error loading admin dashboard', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);

            // Soft feedback for admin removed per UX request
        }

        return view('admin.dashboard', [
            'stats'              => $stats,
            'ordersPerDayChart'  => $ordersPerDayChart,
            'revenuePerDayChart' => $revenuePerDayChart,
            'moduleSplitChart'   => $moduleSplitChart,
            'orderStatusChart'   => $orderStatusChart ?? ['labels' => [], 'data' => []],
            'recentOrders'       => $recentOrders ?? collect(),
            'pendingOrders'      => $pendingOrders ?? collect(),
            'assignedOrders'     => $assignedOrders ?? collect(),
            'completedOrders'    => $completedOrders ?? collect(),
            'activityFeed'       => $activityFeed instanceof Collection ? $activityFeed : collect($activityFeed),
        ]);
    }

    /**
     * Get dashboard statistics as JSON via API
     */
    public function getStats()
    {
        // Safe defaults
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

        try {
            // OPTIMIZED: Use single query with aggregates for user stats
            $userStats = DB::table('Users')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN "IsActive" = true THEN 1 ELSE 0 END) as active')
                )
                ->first();

            $stats['totalUsers']  = (int) ($userStats->total ?? 0);
            $stats['activeUsers'] = (int) ($userStats->active ?? 0);

            // Get simple model counts
            $stats['totalCustomers']     = Customer::count();
            $stats['totalMedicalStores'] = MedicalStore::count();
            $stats['totalRestaurants']   = Restaurant::count();

            // OPTIMIZED: Single query for all order statuses
            $orderStats = DB::table('Orders')
                ->select(
                    DB::raw('COUNT(*) as total'),
                    DB::raw('SUM(CASE WHEN "Status" = \'PendingReview\' THEN 1 ELSE 0 END) as pending'),
                    DB::raw('SUM(CASE WHEN "Status" IN (\'accepted\', \'preparing\', \'packed\') THEN 1 ELSE 0 END) as inprogress'),
                    DB::raw('SUM(CASE WHEN "Status" IN (\'delivered\', \'Completed\') THEN 1 ELSE 0 END) as completed'),
                    DB::raw('SUM(CASE WHEN "Status" = \'Cancelled\' THEN 1 ELSE 0 END) as cancelled')
                )
                ->first();

            $stats['totalOrders']       = (int) ($orderStats->total ?? 0);
            $stats['pendingOrders']     = (int) ($orderStats->pending ?? 0);
            $stats['inProgressOrders']  = (int) ($orderStats->inprogress ?? 0);
            $stats['completedOrders']   = (int) ($orderStats->completed ?? 0);
            $stats['cancelledOrders']   = (int) ($orderStats->cancelled ?? 0);

            // Calculate total revenue from completed orders
            $revenueStats = DB::table('Orders')
                ->where('Status', 'Completed')
                ->select(
                    DB::raw('SUM("TotalAmount") as total_revenue'),
                    DB::raw('AVG("TotalAmount") as avg_value')
                )
                ->first();

            $stats['totalRevenue'] = (float) ($revenueStats->total_revenue ?? 0);
            $stats['avgOrderValue'] = (float) ($revenueStats->avg_value ?? 0);

            // OPTIMIZED: Single query for reward coins
            $coinStats = DB::table('RewardTransactions')
                ->select(
                    DB::raw('COUNT(*) as total')
                )
                ->first();

            $stats['totalRewardCoinsIssued'] = (int) ($coinStats->total ?? 0);
            $stats['totalRewardCoinsUsed']   = 0;

            // Quick counts for ads and notifications
            $stats['activeAds']           = Ad::where('IsActive', true)->count();
            $stats['unreadNotifications'] = 0; // Notification table doesn't exist

        } catch (\Exception $e) {
            Log::error('Error loading dashboard stats API', ['error' => $e->getMessage()]);
        }

        return response()->json(['success' => true, 'data' => $stats]);
    }
}
