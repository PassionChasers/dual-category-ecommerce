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


    // All orders
    public function allOrders()
    {
        $allOrders = Order::latest()->paginate(5);
        return view('admin.orders.index',compact('allOrders'));
    }

    //Food orders
    // public function foodOrders()
    // {
    //     $foodOrders = Order::where('order_type', 'food')
    //     // ->where('restaurant_id', auth()->user()->restaurant->id)
    //     ->latest()
    //     ->paginate(5);

    //     return view('admin.orders.food-order.index',compact('foodOrders'));

    // }

    //Medicine Orders
    public function medicineOrders()
    {
        $medicineOrders = Order::where('order_type', 'medicine')
        // ->where('medicalstore_id', auth()->user()->medicalStore->MedicalStoreId)
        ->latest()
        ->paginate(5);

        return view('admin.orders.medicine-order.index',compact('medicineOrders'));

    }

    //Customer Orders
    public function customersOrders()
    {
        $customerOrders = Order::where('user_id', auth()->id())
        ->latest()
        ->get();

    }


public function foodOrders(Request $request)
{
    $foodOrdersQuery = Order::with(['items', 'user', 'restaurant'])
        ->where('order_type', 'food'); // only food orders

    // Search by Order ID
    if ($request->filled('search')) {
        $foodOrdersQuery->where('order_number', 'like', '%' . $request->search . '%');
    }

    // Filter by Status
    if ($request->filled('status')) {
        switch ($request->status) {
            case '0': // Pending
                $foodOrdersQuery->where('order_status', 'pending');
                break;
            case '1': // In Progress
                $foodOrdersQuery->whereIn('order_status', ['accepted', 'preparing', 'packed']);
                break;
            case '2': // Completed
                $foodOrdersQuery->where('order_status', 'delivered');
                break;
        }
    }

    // Paginate results (keep query parameters for search/filter)
    $foodOrders = $foodOrdersQuery->latest()->paginate(5)->withQueryString();

    return view('admin.orders.food-order.index', compact('foodOrders'));
}



}
