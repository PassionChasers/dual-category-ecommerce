<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use App\Models\MedicalStore;
use App\Models\Restaurant;
use App\Models\Order;
use App\Models\Ad;
use App\Enums\AdminOrderStatus;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function index()
    {
        /* ---------------- SAFE DEFAULTS (BLADE SAFE) ---------------- */

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

        $ordersPerDayChart  = ['labels' => [], 'data' => []];
        $revenuePerDayChart = ['labels' => [], 'data' => []];
        $moduleSplitChart   = ['labels' => [], 'data' => []];
        $orderStatusChart   = ['labels' => [], 'data' => []];

        $recentOrders    = collect();
        $pendingOrders   = collect();
        $assignedOrders  = collect();
        $completedOrders = collect();
        $activityFeed    = collect(); // REQUIRED for Blade

        try {
            /* ---------------- USERS ---------------- */
            $userStats = DB::table('Users')
                ->selectRaw('COUNT(*) as total, SUM(CASE WHEN "IsActive" = true THEN 1 ELSE 0 END) as active')
                ->first();

            $stats['totalUsers']  = (int) ($userStats->total ?? 0);
            $stats['activeUsers'] = (int) ($userStats->active ?? 0);

            /* ---------------- SIMPLE COUNTS ---------------- */
            $stats['totalCustomers']     = Customer::count();
            $stats['totalMedicalStores'] = MedicalStore::count();
            $stats['totalRestaurants']   = Restaurant::count();

            /* ---------------- ORDER STATS (SINGLE QUERY) ---------------- */
            $orderStats = DB::table('Orders')
                ->selectRaw(
                    'COUNT(*) as total,
                     SUM(CASE WHEN "Status" IN (?, ?) THEN 1 ELSE 0 END) as pending,
                     SUM(CASE WHEN "Status" = ? THEN 1 ELSE 0 END) as assigned,
                     SUM(CASE WHEN "Status" = ? THEN 1 ELSE 0 END) as completed,
                     SUM(CASE WHEN "Status" = ? THEN 1 ELSE 0 END) as cancelled',
                    [
                        AdminOrderStatus::Pending->value,
                        AdminOrderStatus::PendingReview->value,
                        AdminOrderStatus::Assigned->value,
                        AdminOrderStatus::Completed->value,
                        AdminOrderStatus::Cancelled->value,
                    ]
                )
                ->first();

            $stats['totalOrders']     = (int) ($orderStats->total ?? 0);
            $stats['pendingOrders']   = (int) ($orderStats->pending ?? 0);
            $stats['assignedOrders']  = (int) ($orderStats->assigned ?? 0);
            $stats['completedOrders'] = (int) ($orderStats->completed ?? 0);
            $stats['cancelledOrders'] = (int) ($orderStats->cancelled ?? 0);

            /* ---------------- REVENUE ---------------- */
            $revenueStats = DB::table('Orders')
                ->where('Status', AdminOrderStatus::Completed->value)
                ->selectRaw('SUM("TotalAmount") as total, AVG("TotalAmount") as avg')
                ->first();

            $stats['totalRevenue']  = (float) ($revenueStats->total ?? 0);
            $stats['avgOrderValue'] = (float) ($revenueStats->avg ?? 0);

            /* ---------------- ADS ---------------- */
            $stats['activeAds'] = Ad::where('IsActive', true)->count();

            /* ---------------- MEDICAL / FOOD SPLIT ---------------- */
            $moduleCounts = DB::table('OrderItems')
                ->selectRaw(
                    'COUNT(DISTINCT CASE WHEN lower("ItemType") = \'medicine\' THEN "OrderId" END) as medical,
                     COUNT(DISTINCT CASE WHEN lower("ItemType") IN (\'food\',\'menuitem\') THEN "OrderId" END) as food'
                )
                ->first();

            $stats['medicalOrders'] = (int) ($moduleCounts->medical ?? 0);
            $stats['foodOrders']    = (int) ($moduleCounts->food ?? 0);

            $moduleSplitChart = [
                'labels' => ['Medical', 'Food'],
                'data'   => [$stats['medicalOrders'], $stats['foodOrders']],
            ];

            /* ---------------- ORDER STATUS CHART ---------------- */
            $orderStatusChart = [
                'labels' => ['Pending', 'Assigned', 'Completed', 'Cancelled'],
                'data'   => [
                    $stats['pendingOrders'],
                    $stats['assignedOrders'],
                    $stats['completedOrders'],
                    $stats['cancelledOrders'],
                ],
            ];

            /* ---------------- COMMON ORDER QUERY ---------------- */
            $baseQuery = Order::with('customer', 'items.food', 'items.medicine')
                ->orderBy('CreatedAt', 'desc');

            $recentOrders    = (clone $baseQuery)->limit(5)->get();
            $pendingOrders   = (clone $baseQuery)->whereIn('Status', [
                                    AdminOrderStatus::Pending->value,
                                    AdminOrderStatus::PendingReview->value,
                                ])->limit(5)->get();
            $assignedOrders  = (clone $baseQuery)->where('Status', AdminOrderStatus::Assigned->value)->limit(5)->get();
            $completedOrders = (clone $baseQuery)->where('Status', AdminOrderStatus::Completed->value)->limit(5)->get();

            /* ---------------- LAST 7 DAYS CHARTS ---------------- */
            $from = Carbon::now()->subDays(6)->startOfDay();
            $to   = Carbon::now()->endOfDay();

            $ordersByDay = Order::whereBetween('CreatedAt', [$from, $to])
                ->selectRaw('DATE("CreatedAt") as date, COUNT(*) as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            $revenueByDay = Order::where('Status', AdminOrderStatus::Completed->value)
                ->whereBetween('CreatedAt', [$from, $to])
                ->selectRaw('DATE("CreatedAt") as date, SUM("TotalAmount") as total')
                ->groupBy('date')
                ->pluck('total', 'date');

            for ($i = 0; $i < 7; $i++) {
                $date = $from->copy()->addDays($i)->format('Y-m-d');

                $ordersPerDayChart['labels'][]  = Carbon::parse($date)->format('d M');
                $ordersPerDayChart['data'][]    = (int) ($ordersByDay[$date] ?? 0);

                $revenuePerDayChart['labels'][] = Carbon::parse($date)->format('d M');
                $revenuePerDayChart['data'][]   = (float) ($revenueByDay[$date] ?? 0);
            }

        } catch (\Throwable $e) {
            Log::error('Dashboard load failed', [
                'message' => $e->getMessage(),
                'trace'   => $e->getTraceAsString(),
            ]);
        }

        /* ---------------- RETURN (ALL VARIABLES PRESENT) ---------------- */
        return view('admin.dashboard', compact(
            'stats',
            'ordersPerDayChart',
            'revenuePerDayChart',
            'moduleSplitChart',
            'orderStatusChart',
            'recentOrders',
            'pendingOrders',
            'assignedOrders',
            'completedOrders',
            'activityFeed'
        ));
    }
}
