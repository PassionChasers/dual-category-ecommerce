<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use App\Models\OrderRejection;
use App\Models\User;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\MedicalStore;
use App\Models\Medicine;
use App\Models\DeliveryMan;

class OrderController extends Controller
{

    //Store a newly created resource in storage.

    public function store(Request $request)
    {
        $request->validate([
            'OrderId'            => 'required|uuid|exists:Orders,OrderId',
            'MedicineId'         => 'required|uuid|exists:Medicines,MedicineId',
            'Quantity'           => 'required|integer|min:1',
            'UnitPriceAtOrder'   => 'required|numeric|min:0',
        ]);

        OrderItem::create([
            'OrderId'            => $request->OrderId,
            'MedicineId'         => $request->MedicineId,
            'MenuItemId'         => null,
            'ItemType'           => 'Medicine',
            'UnitPriceAtOrder'   => $request->UnitPriceAtOrder,
            'Quantity'           => $request->Quantity,
        ]);

        return back()->with('success', 'Medicine added successfully.');
    }


    //Store multiple medicines for an order

    public function storeMultiple(Request $request)
    {
        // Validate the arrays
        $request->validate([
            'OrderId' => 'required|exists:Orders,OrderId',
            'MedicineId.*' => 'required|exists:Medicines,MedicineId',
            'Quantity.*' => 'required|numeric|min:1',
            'UnitPriceAtOrder.*' => 'required|numeric|min:0',
        ]);

        $orderId = $request->OrderId;

        $medicineIds = $request->MedicineId;
        $quantities = $request->Quantity;
        $unitPrices = $request->UnitPriceAtOrder;
        $TotalAmount = 0;

        foreach ($medicineIds as $index => $medicineId) {
            // Skip if MedicineId is empty
            if (!$medicineId) continue;

            $TotalAmount = $TotalAmount + (($unitPrices[$index] ?? 0) * ($quantities[$index] ?? 1));

            OrderItem::create([
                'OrderId' => $orderId,
                'MedicineId' => $medicineId,
                'MenuItemId' => null,
                'Quantity' => $quantities[$index] ?? 1,
                'UnitPriceAtOrder' => $unitPrices[$index] ?? 0,
                'ItemType' => 'Medicine',
            ]);
        }

        $order = Order::findOrFail($orderId);

        // Only update the Status and Total Amount
        $order->Status = 1;
        $order->TotalAmount = $TotalAmount;
        $order->save();

        return redirect()->back()->with('success', 'Medicines added successfully!');
    }


    //Show Medicine order  details 
    public function showMedicineDetails(Request $request, $orderId)
    {
        // Parse query type (MenuItem | Medicine | MenuItem,Medicine)
        $typesArray = $request->query('type') && $request->query('type') !== 'null'
            ? explode(',', $request->query('type'))
            : [];

        // Base query with customer
        $query = Order::with('customer');

        // Conditionally load items
        $query->with(['items' => function ($q) use ($typesArray) {
            if (!empty($typesArray)) {
                $q->whereIn('ItemType', $typesArray);
            }
            // else load all items by default
        }]);

        $order = $query->findOrFail($orderId);

        // Fetch active medicines once
        $medicines = cache()->remember('active_medicines', 3600, function () {
            return Medicine::where('IsActive', true)
                ->orderBy('Name')
                ->get();
        });

        // Select view based on role
        return match (Auth::user()->Role) {
            4 => view('admin.orders.medicine-order.show', compact('order', 'medicines')),
            2 => view('admin.orders.BusinessViewOrder.medicalstore.show', compact('order', 'medicines')),
            default => abort(403, 'Unauthorized access'),
        };
    }



    //Show food order details
    public function showFoodDetails(Request $request, $orderId)
    {
        // Parse query type (MenuItem | Medicine | MenuItem,Medicine)
        $typesArray = $request->query('type') && $request->query('type') !== 'null'
            ? explode(',', $request->query('type'))
            : [];

        // Base query with customer
        $query = Order::with('customer');

        // Conditionally load items
        $query->with(['items' => function ($q) use ($typesArray) {
            if (!empty($typesArray)) {
                $q->whereIn('ItemType', $typesArray);
            }
            // else load all items by default
        }]);

        $order = $query->findOrFail($orderId);

        // Select view based on role
        return match (Auth::user()->Role) {
            4 => view('admin.orders.food-order.show', compact('order')),
            3 => view('admin.orders.BusinessViewOrder.restaurant.show', compact('order')),
            default => abort(403, 'Unauthorized access'),
        };
    }



    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:Orders,OrderId',
            'items'    => 'required|array',
            'items.*.qty' => 'required|integer|min:1',
            'items.*.type' => 'required|string',
        ]);

        $order = Order::with('items')->findOrFail($request->order_id);

        $existingItems = $order->items->keyBy('OrderItemId');
        $originalStatus = $order->Status;

        \DB::transaction(function () use ($request, $order, $existingItems, $originalStatus) {
            foreach ($request->items as $item) {
                $orderItemId = $item['order_item_id'] ?? null;

                if ($orderItemId && isset($existingItems[$orderItemId])) {
                    // Update quantity of existing item
                    $existingItems[$orderItemId]->update([
                        'Quantity' => $item['qty'],
                    ]);
                } else {
                    // Create new item
                    $order->items()->create([
                        'ItemName' => $item['name'] ?? null,
                        'Quantity' => $item['qty'],
                        'ItemType' => $item['type'],
                    ]);
                }
            }

            // Restore original status to prevent accidental changes
            $order->update(['Status' => $originalStatus]);
        });

        return redirect()->back()->with('success', 'Order updated successfully.');
    }



    // -----------------
    // Cancel order by admin
    //--------------------------

    public function cancel($id)
    {
        $order = Order::findOrFail($id);

        \DB::transaction(function () use ($order) {
            $order->update([
                'Status'     => Order::STATUS_CANCELLED,
                'CancelledAt'=> now(),
                'BusinessId' => null,
            ]);

            // Optional: trigger notification/event
            // event(new OrderCancelled($order));
        });

        return redirect()->back()
            ->with('success', 'Order has been cancelled successfully.');
    }


    // reject order by business
    public function reject(Request $request,$id)
    {
        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'BusinessId' => 'required|uuid|exists:MedicalStores,MedicalStoreId',
            'BusinessType' => 'required|string|in:MedicalStore,Restaurant',
            'RejectionReason' => 'nullable|string|max:1000',
        ]);

        \DB::transaction(function () use ($order, $validated) {
            $order->update([
                'Status' => Order::STATUS_REJECTED,
            ]);

            // Create rejection record (Eloquent)
            OrderRejection::create([
                'OrderId'         => $order->OrderId,
                'BusinessId'      => $validated['BusinessId'],
                'BusinessType'    => $validated['BusinessType'],
                'RejectionReason' => $validated['RejectionReason'] ?? null,
            ]);

            // Optional: trigger event or notification
            // event(new OrderRejected($order));
        });

        return redirect()->back()
            ->with('success', 'Order has been rejected successfully.');
    }




    // accept order by business
    public function accept($id)
    {
        $order = Order::findOrFail($id);

        \DB::transaction(function () use ($order) {
            $order->update([
                'Status'     => Order::STATUS_ACCEPTED,
                'AcceptedAt' => now(),
            ]);

            // Optional: trigger event or notification
            // event(new OrderAccepted($order));
        });

        return redirect()->back()
            ->with('success', 'Order has been accepted successfully.');
    }


    //----------------------
    //  All orders
    //----------------------
    public function allOrders(Request $request)
    { 
        return view('admin.orders.index', compact('allOrders', 'itemTypes', 'allRestaurants', 'allMedicalStores'));
    }


    //----------------------
    // Food orders
    //----------------------

    //For admin view
    public function foodOrders(Request $request)
    {

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;
        
        $allOrders = Order::whereHas('items', fn($q) => $q->whereNotNull('MenuItemId'))
                    ->with(['items.food' => fn($q) => $q->whereNotNull('MenuItemId')])
                    ->SearchFood($request->search)
                    ->filterStatus($request->status)
                    ->sort($request->sort_by ?? 'CreatedAt', $request->sort_order ?? 'desc')
                    ->paginate($perPage)
                    ->appends($request->except('page'));

        // Cache heavy data
        $itemTypes = cache()->rememberForever('food_order_item_types', function () {
            return OrderItem::select('ItemType')->distinct()->pluck('ItemType');
        });

        $allRestaurants = cache()->remember('active_restaurants', 3600, function () {
            return Restaurant::where('IsActive', true)
                ->orderBy('Priority', 'asc')
                ->get();
        });

        $allDeliveryMan = cache()->remember('delivery_men', 3600, function () {
            return DeliveryMan::with('user')
                ->whereHas('user', fn ($q) => $q->where('Role', 5))
                ->get();
        });

        //AJAX CHECK (RETURN ONLY VIEW DIFFERENCE)
        if ($request->ajax()) {
            return view(
                'admin.orders.food-order.searchedProducts',
                compact('allOrders', 'itemTypes','allRestaurants', 'allDeliveryMan')
            );
        }
     
        return view('admin.orders.food-order.index', 
        compact('allOrders', 'itemTypes','allRestaurants', 'allDeliveryMan'));
    }


    //For Business view 
    public function RestaurantOrders(Request $request)
    {
        $RestaurantId = auth()->user()->restaurants->pluck('RestaurantId')->toArray();
        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;

        $allOrders = Order::whereIn('BusinessId', $RestaurantId)
                ->with(['items.food'])
                ->SearchFood($request->search)
                ->filterStatus($request->status)
                ->sort($request->sort_by, $request->sort_order)
                ->paginate($perPage)
                ->appends($request->except('page'));

        // Cache heavy data
        $itemTypes = cache()->rememberForever('food_order_item_types', function () {
            return OrderItem::select('ItemType')->distinct()->pluck('ItemType');
        });

        $allRestaurants = cache()->remember('active_restaurants', 3600, function () {
            return Restaurant::where('IsActive', true)
                ->orderBy('Priority', 'asc')
                ->get();
        });

        $allDeliveryMan = cache()->remember('delivery_men', 3600, function () {
            return DeliveryMan::with('user')
                ->whereHas('user', fn ($q) => $q->where('Role', 5))
                ->get();
        });

        //AJAX CHECK (RETURN ONLY VIEW DIFFERENCE)
        if ($request->ajax()) {
            return view(
                'admin.orders.BusinessViewOrder.restaurant.searchedProducts',
                compact('allOrders', 'itemTypes','allRestaurants', 'allDeliveryMan')
            );
        }
        else{
            return view('admin.orders.BusinessViewOrder.restaurant.index', 
            compact('allOrders', 'itemTypes','allRestaurants', 'allDeliveryMan'));
        }
    }


    //---------------------
    // Medicine Orders
    //---------------------

    public function medicineOrders(Request $request)
    {
        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;

        $allOrders = Order::whereDoesntHave('items', fn($q) => $q->where('ItemType', 'Food'))
                    ->with(['items.medicine'])  // <-- Add eager loading
                    ->searchMedicine($request->search)
                    ->filterStatus($request->status)
                    ->sort($request->sort_by, $request->sort_order)
                    ->paginate($perPage)
                    ->appends($request->except('page'));

        // Cache heavy data
        $itemTypes = cache()->rememberForever('medicine_order_item_types', function () {
            return OrderItem::select('ItemType')->distinct()->pluck('ItemType');
        });

        $allMedicalStores = cache()->remember('active_medicalstores', 3600, function () {
            return MedicalStore::where('IsActive', true)
                ->orderBy('Priority', 'asc')
                ->get();
        });

        $allDeliveryMan = cache()->remember('delivery_men', 3600, function () {
            return DeliveryMan::with('user')
                ->whereHas('user', fn ($q) => $q->where('Role', 5))
                ->get();
        });

        //AJAX CHECK (RETURN ONLY VIEW DIFFERENCE)
        if ($request->ajax()) {
            return view(
                'admin.orders.medicine-order.searchedProducts',
                compact('allOrders', 'itemTypes','allMedicalStores', 'allDeliveryMan')
            );
        }

        return view('admin.orders.medicine-order.index', 
        compact('allOrders', 'itemTypes','allMedicalStores', 'allDeliveryMan'));
        
    }



    // For medicalstores use only
    public function medicalStoreOrders(Request $request)
    {
        $medicalStoreId = auth()->user()->medicalstores->pluck('MedicalStoreId');
        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;

        $allOrders = Order::whereIn('BusinessId', $medicalStoreId)
                    ->with(['items.medicine'])
                    ->searchMedicine($request->search)
                    ->filterStatus($request->status)
                    ->sort($request->sort_by, $request->sort_order)
                    ->paginate($perPage)
                    ->appends($request->except('page'));

        // Cache heavy data
        $itemTypes = cache()->rememberForever('medicine_order_item_types', function () {
            return OrderItem::select('ItemType')->distinct()->pluck('ItemType');
        });

        $allMedicalStores = cache()->remember('active_medicalstores', 3600, function () {
            return MedicalStore::where('IsActive', true)
                ->orderBy('Priority', 'asc')
                ->get();
        });

        $allDeliveryMan = cache()->remember('delivery_men', 3600, function () {
            return DeliveryMan::with('user')
                ->whereHas('user', fn ($q) => $q->where('Role', 5))
                ->get();
        });

        //AJAX CHECK (RETURN ONLY VIEW DIFFERENCE)
        if ($request->ajax()) {
            return view(
                'admin.orders.BusinessViewOrder.medicalstore.searchedProducts',
                compact('allOrders', 'itemTypes','allMedicalStores', 'allDeliveryMan')
            );
        }

        return view('admin.orders.BusinessViewOrder.medicalstore.index', 
        compact('allOrders', 'itemTypes','allMedicalStores', 'allDeliveryMan'));

    }


    // // Assign Medical Store to Medicine Order / Restaurant to Food Order
    public function assignStore(Request $request)
    {
        $request->validate([
            'order_id'         => 'required|exists:Orders,OrderId',
            'medical_store_id' => 'nullable|exists:MedicalStores,MedicalStoreId',
            'restaurant_id'    => 'nullable|exists:Restaurants,RestaurantId',
        ]);

        $order = Order::findOrFail($request->order_id);

        // Block invalid statuses
        if (in_array($order->Status, [3, 4, 6, 7, 8, 9, 10])) {
            return response()->json([
                'success' => false,
                'message' => 'Order cannot be assigned in its current status'
            ], 403);
        }

        \DB::beginTransaction();

        try {
            if ($request->filled('medical_store_id')) {

                $order->BusinessId   = $request->medical_store_id;
                $order->BusinessType = 'MedicalStore';
                $message = 'Medical store assigned successfully';

            } elseif ($request->filled('restaurant_id')) {

                $order->BusinessId   = $request->restaurant_id;
                $order->BusinessType = 'Restaurant';
                $message = 'Restaurant assigned successfully';

            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No store or restaurant ID provided'
                ], 422);
            }

            // Assigned
            $order->Status = 3;
            $order->save();

            \DB::commit();

            return response()->json([
                'success' => true,
                'status'  => 3,
                'message' => $message
            ]);

        } catch (\Throwable $e) {
            \DB::rollBack();
            \Log::error('Assign store error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Server error'
            ], 500);
        }
    }


    
    //ASSIGN ORDER TO DELIVERY MAN
    public function assignDeliveryMan(Request $request)
    {
        $request->validate([
            'order_id'        => 'required|exists:Orders,OrderId',
            'delivery_man_id' => 'required|exists:DeliveryMen,DeliveryManId',
        ]);

        $order = Order::findOrFail($request->order_id);

        if ($order->Status != 7) {
            return redirect()->back()->with('error', 'Order cannot be assigned unless it is Packed.');
        }

        try {
            $order->DeliveryManId = $request->delivery_man_id;
            $order->Status        = 8; // Shipping
            $order->save();

            return redirect()->back()->with('success', 'Delivery man assigned successfully.');
        } catch (\Throwable $e) {
            \Log::error('Assign Deliveryman error: '.$e->getMessage());
            return redirect()->back()->with('error', 'Server error. Please try again.');
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:Orders,OrderId',
                'status'   => 'required|in:1,4,6,7,8,9,10',
            ]);

            $order = Order::find($request->order_id);

            // Optional: prevent status change if Completed/Cancelled
            // if (in_array($order->Status, ['Completed', 'Cancelled'])) {
            //     return response()->json([
            //         'success' => false,
            //         'message' => 'Cannot change status of Completed or Cancelled orders'
            //     ], 403);
            // }

            $order->Status = $request->status;
            $order->save();

            return response()->json([
                'success' => true,
                'message' => 'Order status updated successfully'
            ]);

        } catch (\Throwable $e) {
            \Log::error('Update status error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500);
        }
    }
    
}
