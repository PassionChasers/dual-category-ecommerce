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
    public function show(Request $request, $orderId)
    {
        $orderTypes = $request->query('type'); // could be "MenuItem", "Medicine", or "MenuItem,Medicine"

        $query = Order::with(['customer']);

        // Load items conditionally
        if (!empty($orderTypes) && $orderTypes !== 'null') {
            // Convert comma-separated string to array
            $typesArray = explode(',', $orderTypes);

            $query->with(['items' => function ($q) use ($typesArray) {
                $q->whereIn('ItemType', $typesArray);
            }]);
        } else {
            // fallback: load all items
            $query->with('items');
        }

        $order = $query->findOrFail($orderId);

        return view('admin.orders.show', compact('order', 'orderTypes'));
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
    
    public function update(Request $request)
    {
        $order = Order::with('items')->findOrFail($request->order_id);

        // Store current Status so it doesn’t change
        $originalStatus = $order->Status;

        $existingItems = $order->items->keyBy('OrderItemId');

        foreach ($request->items as $item) {

            if (!empty($item['order_item_id']) && isset($existingItems[$item['order_item_id']])) {

                // EXISTING ITEM → update ONLY quantity
                $existingItems[$item['order_item_id']]->update([
                    'Quantity' => $item['qty'],
                ]);

            } else {
                // NEW ITEM → create full record
                $order->items()->create([
                    'ItemName' => $item['name'],
                    'Quantity' => $item['qty'],
                    'ItemType' => $item['type'],
                ]);
            }
        }

        // Restore original Status (important!)
        $order->update([
            'Status' => $originalStatus,
        ]);

        return back()->with('success', 'Order updated successfully');
    }


    // Cancel order
    public function cancel($id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Only update the Status
        $order->Status = 'Cancelled';
        $order->save();

        return redirect()->back()
            ->with('success', 'Order has been cancelled successfully');
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
        // $query = Order::query();
        $query = Order::with(['items' => function ($q) use ($request) {
            if ($request->filled('category')) {
                $q->where('ItemType', $request->category);
            }
        }]);

        // Search name
        if ($search = $request->get('search')) {

            $search = strtolower(trim($request->search));

            $query->where(function ($q) use ($search) {
                $q->whereHas('items', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
                });
            }); 
        }

        // Filter by category
        if ($category = $request->get('category')) {
            // $query->where('MedicineCategoryId', $category);
            $query->where(function ($q) use ($category) {
                $q->whereHas('items', function ($q2) use ($category) {
                     $q2->where('ItemType', $category);
                });
            }); 
        }
       
        // Filter by prescription required
        // if ($request->filled('prescription')) {
        //     if ($request->prescription === 'yes')
        //         $query->where('PrescriptionRequired', true);
        //     elseif ($request->prescription === 'no')
        //         $query->where('PrescriptionRequired', false);
        // }

        // Filter by is Status
        if ($request->filled('status')) {
            if ($request->status === 'Completed')
                $query->where('Status', 'Completed');
            elseif ($request->status === 'Cancelled')
                $query->where('Status', 'Cancelled');
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'Status', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $sortBy = in_array($request->sort_by, ['CreatedAt']) ? $request->sort_by : 'CreatedAt';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = \App\Models\OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        return view('admin.orders.index', compact('allOrders', 'itemTypes'));
    }


    //----------------------
    // Food orders
    //----------------------

    public function foodOrders(Request $request)
    {
        $query = Order::with(['items' => function ($q) use ($request) {
            // if ($request->filled('category')) {
                $q->where('ItemType', 'MenuItem');
            // }
        }]);

        // Search Product Name
        if ($search = $request->get('search')) {

            $search = strtolower(trim($request->search));

            $query->where(function ($q) use ($search) {
                $q->whereHas('items', function ($q2) use ($search) {
                    // $q2->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
                    $q2->where('ItemType', 'MenuItem')
                    ->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
                });
            }); 
        }

        // Filter by is Status
        if ($request->filled('status')) {
            if ($request->status === 'Completed')
                $query->where('Status', 'Completed');
            elseif ($request->status === 'Cancelled')
                $query->where('Status', 'Cancelled');
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'Status', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $sortBy = in_array($request->sort_by, ['CreatedAt']) ? $request->sort_by : 'CreatedAt';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = \App\Models\OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        return view('admin.orders.food-order.index', compact('allOrders', 'itemTypes'));
    }


    //---------------------
    // Medicine Orders
    //---------------------

    public function medicineOrders(Request $request)
    {
        $query = Order::with(['items' => function ($q) use ($request) {
            $q->where('ItemType', 'Medicine');
        }]);

        // Search name, brand, generic, description
        if ($search = $request->get('search')) {

            $search = strtolower(trim($request->search));

            $query->where(function ($q) use ($search) {
                $q->whereHas('items', function ($q2) use ($search) {
                    // $q2->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
                    $q2->where('ItemType', 'Medicine')
                    ->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
                });
            }); 
        }

        // Filter by prescription required
        // if ($request->filled('prescription')) {
        //     if ($request->prescription === 'yes')
        //         $query->where('PrescriptionRequired', true);
        //     elseif ($request->prescription === 'no')
        //         $query->where('PrescriptionRequired', false);
        // }

        // Filter by is Status
        if ($request->filled('status')) {
            if ($request->status === 'Completed')
                $query->where('Status', 'Completed');
            elseif ($request->status === 'Cancelled')
                $query->where('Status', 'Cancelled');
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'Status', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $sortBy = in_array($request->sort_by, ['CreatedAt']) ? $request->sort_by : 'CreatedAt';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = \App\Models\OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        return view('admin.orders.medicine-order.index', compact('allOrders', 'itemTypes'));
    }


    //Customer Orders
    public function customersOrders()
    {
        $customerOrders = Order::where('user_id', auth()->id())
        ->latest()
        ->get();

    }


}
