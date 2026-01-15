<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

use Illuminate\Http\Request;
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

    /**
     * Display the specified resource.
     */
    public function showProductDetails(Request $request, $orderId)
    {
        $orderTypes = $request->query('type'); // MenuItem | Medicine | MenuItem,Medicine

        // Convert type to array
        $typesArray = [];
        if (!empty($orderTypes) && $orderTypes !== 'null') {
            $typesArray = explode(',', $orderTypes);
        }

        $query = Order::with('customer');

        // Load items conditionally
        if (!empty($typesArray)) {
            $query->with(['items' => function ($q) use ($typesArray) {
                $q->whereIn('ItemType', $typesArray);
            }]);
        } else {
            $query->with('items');
        }

        $order = $query->findOrFail($orderId);

        return view('admin.orders.show', compact('order'));
    }

    //Show Medicine order  details 
    public function showMedicineDetails(Request $request, $orderId)
    {
        $orderTypes = $request->query('type'); // MenuItem | Medicine | MenuItem,Medicine

        // Convert type to array
        $typesArray = [];
        if (!empty($orderTypes) && $orderTypes !== 'null') {
            $typesArray = explode(',', $orderTypes);
        }

        $query = Order::with('customer');

        // Load items conditionally
        if (!empty($typesArray)) {
            $query->with(['items' => function ($q) use ($typesArray) {
                $q->whereIn('ItemType', $typesArray);
            }]);
        } else {
            $query->with('items');
        }

        $order = $query->findOrFail($orderId);

        $medicines = Medicine::where('IsActive', true)
                    ->orderBy('Name')
                    ->get();

        if (Auth::user()->Role == 4) {
            return view('admin.orders.medicine-order.show', compact('order', 'medicines'));
        }
        else if(Auth::user()->Role == 2){
            return view('admin.orders.BusinessViewOrder.medicalstore.show', compact('order', 'medicines'));
        }
        else{
            abort(403, 'Unauthorized access');
        }
        
        
    }


    //Show food order details
    public function showFoodDetails(Request $request, $orderId)
    {
        $orderTypes = $request->query('type'); // MenuItem | Medicine | MenuItem,Medicine

        // Convert type to array
        $typesArray = [];
        if (!empty($orderTypes) && $orderTypes !== 'null') {
            $typesArray = explode(',', $orderTypes);
        }

        $query = Order::with('customer');

        // Load items conditionally
        if (!empty($typesArray)) {
            $query->with(['items' => function ($q) use ($typesArray) {
                $q->whereIn('ItemType', $typesArray);
            }]);
        } else {
            $query->with('items');
        }

        $order = $query->findOrFail($orderId);

        if (Auth::user()->Role == 4) {
            // return view('admin.orders.medicine-order.show', compact('order', 'medicines'));
            return view('admin.orders.food-order.show', compact('order'));
        }
        else if(Auth::user()->Role == 3){
            return view('admin.orders.BusinessViewOrder.restaurant.show', compact('order'));
        }
        else{
            abort(403, 'Unauthorized access');
        }
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


    // Cancel order by admin
    public function cancel($id, Request $request)
    {
        $order = Order::findOrFail($id);
        // Only update the Status
        $order->Status = 9;
        $order->CancelledAt = now();
        $order->BusinessId = null;
        $order->save();
        return redirect()->back()
            ->with('success', 'Order has been cancelled successfully');
    }


    // reject order by business
    public function reject($id, Request $request)
    {
        $order = Order::findOrFail($id);
        // Only update the Status
        $order->Status = 5;
        $order->save();
        return redirect()->back()
            ->with('success', 'Order has been rejected successfully');
    }



    // accept order by business
    public function accept($id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Only update the Status
        $order->Status = 4;
        $order->AcceptedAt = now();
        $order->save();

        return redirect()->back()
            ->with('success', 'Order has been accepted successfully');
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
        $query = Order::with('items.medicine', 'items.food');

        // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->where(function ($q) use ($search, $request) {

                // If category is provided
                if ($request->filled('category')) {
                    if ($request->category === 'Medicine') {
                        $q->whereHas('items.medicine', function ($q2) use ($search) {
                            $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                        });
                    } elseif ($request->category === 'Food') {
                        $q->whereHas('items.food', function ($q2) use ($search) {
                            $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                        });
                    }
                } 
                // If category is NOT provided, search in BOTH
                else {
                    $q->whereHas('items.medicine', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                    })
                    ->orWhereHas('items.food', function ($q2) use ($search) {
                        $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                    });
                }

            });
        }

        if ($request->filled('category')) {
            if ($request->category === 'Medicine') {
                $query->with(['items' => function ($q) {
                    $q->whereNotNull('MedicineId');
                }]);
            } elseif ($request->category === 'Food') {
                $query->with(['items' => function ($q) {
                    $q->whereNotNull('MenuItemId');
                }]);
            }
        }

        // Filter by is Status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        //fetch all active restaurants
        $allRestaurants = Restaurant::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        //fetch all active medicalstores
        $allMedicalStores = MedicalStore::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        $itemTypes = OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        return view('admin.orders.index', compact('allOrders', 'itemTypes', 'allRestaurants', 'allMedicalStores'));
    }


    //----------------------
    // Food orders
    //----------------------

    //For admin view
    public function foodOrders(Request $request)
    {
        $query = Order::whereHas('items', function ($q) {
                    $q->whereNotNull('MenuItemId');
                })->with(['items' => function ($q) {
                    $q->whereNotNull('MenuItemId')->with('food');
                }]);


        // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->whereHas('items.food', function ($q) use ($search) {
                $q->where('MenuItems.Name', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by is Status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        //fetch all active restaurants
        $allRestaurants = Restaurant::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        return view('admin.orders.food-order.index', compact('allOrders', 'itemTypes','allRestaurants'));
    }


    //For Business view 
    public function RestaurantOrders(Request $request)
    {
        $RestaurantId = auth()->user()->restaurants->pluck('RestaurantId')->toArray();

        $query = Order::where('BusinessId', $RestaurantId)
                ->with(['items.food']); // load items + food if needed

        // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->whereHas('items.food', function ($q) use ($search) {
                $q->where('MenuItems.Name', 'ILIKE', "%{$search}%");
            });
        }

        // Filter by is Status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        //fetch all active restaurants
        $allRestaurants = Restaurant::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        $allDeliveryMan = Deliveryman::with('user')
        ->whereHas('user', function ($q) {
            $q->where('Role', 5);
        })
        ->get();

        return view('admin.orders.BusinessViewOrder.restaurant.index', compact('allOrders', 'itemTypes','allRestaurants', 'allDeliveryMan'));
    }


    //---------------------
    // Medicine Orders
    //---------------------

    public function medicineOrders(Request $request)
    {
        $query = Order::whereDoesntHave('items', function ($q) {
                    $q->where('ItemType', 'Food');
                });

         // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));
            $query->whereHas('items.medicine', function ($q) use ($search) {
                $q->where('Medicines.Name', 'ILIKE', "%{$search}%");
            });
        };

        // Filter by prescription required
        // if ($request->filled('prescription')) {
        //     if ($request->prescription === 'yes')
        //         $query->where('PrescriptionRequired', true);
        //     elseif ($request->prescription === 'no')
        //         $query->where('PrescriptionRequired', false);
        // }

        // Filter by is Status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = \App\Models\OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        $allMedicalStores = MedicalStore::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        return view('admin.orders.medicine-order.index', compact('allOrders', 'itemTypes','allMedicalStores'));
    }



    // For medicalstores use only
    public function medicalStoreOrders(Request $request)
    {
        $medicalStoreId = auth()->user()->medicalstores->pluck('MedicalStoreId')->toArray();

        $query = Order::where('BusinessId', $medicalStoreId)
                ->with(['items.medicine']);

        // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->whereHas('items.medicine', function ($q) use ($search) {
                $q->where('Medicines.Name', 'ILIKE', "%{$search}%");
            });
        };

        // Filter by prescription required
        // if ($request->filled('prescription')) {
        //     if ($request->prescription === 'yes')
        //         $query->where('PrescriptionRequired', true);
        //     elseif ($request->prescription === 'no')
        //         $query->where('PrescriptionRequired', false);
        // }

        // Filter by is Status
        if ($request->filled('status')) {
            $query->where('Status', $request->status);
        }

        // Sorting
        $allowedSorts = ['CreatedAt', 'TotalAmount'];
        $sortBy = in_array($request->get('sort_by'), $allowedSorts) ? $request->get('sort_by') : 'CreatedAt';
        $sortOrder = $request->get('sort_order') === 'asc' ? 'asc' : 'desc';
        $query->orderBy($sortBy, $sortOrder);

        /* Pagination */
        $perPage = in_array((int)$request->per_page, [5,10,25,50]) ? $request->per_page : 10;


        $allOrders = $query->paginate($perPage)->appends($request->except('page'));

        $itemTypes = \App\Models\OrderItem::select('ItemType')
        ->distinct()
        ->pluck('ItemType');

        $allMedicalStores = MedicalStore::where('IsActive', true)
        ->orderBy('Priority', 'asc')
        ->get();

        $allDeliveryMan = Deliveryman::with('user')
        ->whereHas('user', function ($q) {
            $q->where('Role', 5);
        })
        ->get();

        return view('admin.orders.BusinessViewOrder.medicalstore.index', compact('allOrders', 'itemTypes','allMedicalStores', 'allDeliveryMan'));
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


    // public function assignDeliveryMan(Request $request)
    // {
    //     $request->validate([
    //         'order_id'         => 'required|exists:Orders,OrderId',
    //         'delivery_man_id' => 'required|exists:DeliveryMen,DeliveryManId',
    //     ]);

    //     // Find the order
    //     $order = Order::findOrFail($request->order_id);

    //     // Only allow assigning if order is Packed (status 7)
    //     if ($order->Status != 7) {
    //         return redirect()->back()->with('error', 'Order cannot be assigned to delivery man unless it is Packed.');
    //     }

    //     try {
    //         // Assign delivery man
    //         $order->DeliveryManId = $request->delivery_man_id;
    //         $order->Status        = 8; // Shipping
    //         $order->ShippingAt = now();
    //         $order->save();

    //         return redirect()->back()->with('success', 'Delivery man assigned successfully.');
    //     } catch (\Throwable $e) {
    //         \Log::error('Assign Deliveryman error: '.$e->getMessage());
    //         return redirect()->back()->with('error', 'Server error. Please try again.');
    //     }
    // }


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
