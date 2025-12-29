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
        $query = Order::query();

        // Search by product name (order_items table)

        if ($request->filled('search')) {

            $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->whereHas('items', function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        //Filter by order status
        if ($request->filled('orderStatus')) {
            $query->where('order_status', $request->orderStatus);
        }

        // Paginate results with query parameters
        $allOrders = $query->latest()->paginate(5)->appends($request->all());

        //AJAX response
        if($request->ajax()){
            return view('admin.orders.searchedProducts', compact('allOrders'))->render();
        }
        //Normal load
        return view('admin.orders.index', compact('allOrders'));
    }

    //----------------------
    // Food orders
    //----------------------
    public function foodOrders(Request $request)
    {
<<<<<<< HEAD
        $query = Order::query()->where('order_type', 'food');
=======
        $foodOrders = Order::where('order_type', 'food')
       
        ->latest()
        ->paginate(5);
>>>>>>> c0fc83ddb31d95b5044bff30f32d0e4e962de7ca

        // Search by product name (order_items table)
        if ($request->filled('search')) {

            $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->whereHas('items', function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        //Filter by order status
        if ($request->filled('orderStatus')) {
            $query->where('order_status', $request->orderStatus);
        }

        // Paginate results with query parameters
        $foodOrders = $query->latest()->paginate(5)->appends($request->all());
        $allOrders = $foodOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.orders.searchedProducts', compact('allOrders'))->render();
        }
        //Normal load
        return view('admin.orders.food-order.index', compact('foodOrders'));
    }

    //---------------------
    // Medicine Orders
    //---------------------
    public function medicineOrders(Request $request)
    {
        $query = Order::query()->where('order_type', 'medicine');

        // Search by product name (order_items table)
        if ($request->filled('search')) {

            $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->whereHas('items', function ($q) use ($search) {
                $q->where('product_name', 'like', '%' . $search . '%');
            });
        }

        //Filter by order status
        if ($request->filled('orderStatus')) {
            $query->where('order_status', $request->orderStatus);
        }

        // Paginate results with query parameters
        $medicineOrders = $query->latest()->paginate(5)->appends($request->all());
        $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.orders.searchedProducts', compact('allOrders'))->render();
        }
        //Normal load
        return view('admin.orders.medicine-order.index', compact('medicineOrders'));

    }

<<<<<<< HEAD
    //----------------------
    // Customer Orders
    //----------------------
=======
    //Medicine Orders
    public function medicineOrders()
    {
        $medicineOrders = Order::where('order_type', 'medicine')
       
        ->latest()
        ->paginate(5);

        return view('admin.orders.medicine-order.index',compact('medicineOrders'));

    }

    //Customer Orders
>>>>>>> c0fc83ddb31d95b5044bff30f32d0e4e962de7ca
    public function customersOrders()
    {
        $customerOrders = Order::where('user_id', auth()->id())
        ->latest()
        ->get();

    }


}
