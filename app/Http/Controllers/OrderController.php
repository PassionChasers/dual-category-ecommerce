<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.orders.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show($orderId)
    {
        $order = Order::findOrFail($orderId);
        return view('admin.orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($orderId)
    {
        $order = Order::findOrFail($orderId);

        $order->delete(); // order_items auto-deleted

        return back()->with('success', 'Order and its items deleted successfully.');

    }


    //----------------------
    //  All orders
    //----------------------
    public function allOrders(Request $request)
    {
        $query = Order::with([
            'items', 
            'customer.user'
        ]);

        // Search by Product Name OR Customer Name
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {

                // Search by product name (order_items table)
                $q->whereHas('items', function ($q2) use ($search) {
                    $q2->where('ItemName', 'like', "%{$search}%");
                })

                // OR search by customer name (customers table)
                ->orWhereHas('customer', function ($q3) use ($search) {
                    $q3->where('Name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by Order Status
        if ($request->filled('orderStatus')) {
            $query->where('Status', $request->orderStatus);
        }

        // Pagination (CreatedAt is custom column)
        $allOrders = $query
            ->latest('CreatedAt')
            ->paginate(4)
            ->appends($request->all());

        // AJAX response
        if ($request->ajax()) {
            return view('admin.orders.searchedProducts', compact('allOrders'))->render();
        }

        // Normal page load
        return view('admin.orders.index', compact('allOrders'));
    }


    //----------------------
    // Food orders
    //----------------------

    public function foodOrders(Request $request)
    {
        $query = Order::with(['items', 'customer.user'])

            // Must have at least one Medicine item
            ->whereHas('items', function ($q) {
                $q->where('ItemType', 'MenuItem');
            })

            // Must NOT have any NON-Medicine item
            ->whereDoesntHave('items', function ($q) {
                $q->where('ItemType', '!=', 'MenuItem');
            });

        // Search by product or customer name
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('items', function ($q2) use ($search) {
                    $q2->where('ItemName', 'like', "%{$search}%");
                })
                ->orWhereHas('customer', function ($q3) use ($search) {
                    $q3->where('Name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by order status
        if ($request->filled('orderStatus')) {
            $query->where('Status', $request->orderStatus);
        }

        $allOrders = $query
            ->latest('CreatedAt')
            ->paginate(4)
            ->appends($request->all());

        // AJAX response
        if ($request->ajax()) {
            return view('admin.orders.food-order.searchedProducts', compact('allOrders'))->render();
        }

        return view('admin.orders.food-order.index', compact('allOrders'));
    }


    //---------------------
    // Medicine Orders
    //---------------------

    public function medicineOrders(Request $request)
    {
        $query = Order::with(['items', 'customer.user'])

            // Must have at least one Medicine item
            ->whereHas('items', function ($q) {
                $q->where('ItemType', 'Medicine');
            })

            // Must NOT have any NON-Medicine item
            ->whereDoesntHave('items', function ($q) {
                $q->where('ItemType', '!=', 'Medicine');
            });

        // Search by product or customer name
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->whereHas('items', function ($q2) use ($search) {
                    $q2->where('ItemName', 'like', "%{$search}%");
                })
                ->orWhereHas('customer', function ($q3) use ($search) {
                    $q3->where('Name', 'like', "%{$search}%");
                });
            });
        }

        // Filter by order status
        if ($request->filled('orderStatus')) {
            $query->where('Status', $request->orderStatus);
        }

        $allOrders = $query
            ->latest('CreatedAt')
            ->paginate(4)
            ->appends($request->all());

        // AJAX response
        if ($request->ajax()) {
            return view('admin.orders.medicine-order.searchedProducts', compact('allOrders'))->render();
        }

        return view('admin.orders.medicine-order.index', compact('allOrders'));
    }


    //Customer Orders
    public function customersOrders()
    {
        $customerOrders = Order::where('user_id', auth()->id())
        ->latest()
        ->get();

    }


}
