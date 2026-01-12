<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Restaurant;
use App\Models\MedicalStore;
use App\Models\Medicine;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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


        return view('admin.orders.medicine-order.show', compact('order', 'medicines'));
        
    }


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
    
        return view('admin.orders.food-order.show', compact('order'));
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


    // Cancel order by admin
    public function cancel($id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Only update the Status
        $order->Status = 'Cancelled';
        $order->save();

        // Set BusinessId = NULL in OrderItems where OrderId matches
        OrderItem::where('OrderId', $order->OrderId)
        ->update(['BusinessId' => null]);

        return redirect()->back()
            ->with('success', 'Order has been cancelled successfully');
    }


    // reject order by business
    public function reject($id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Only update the Status
        $order->Status = 'Rejected';
        $order->save();

        // Set status = Rejected in OrderItems where OrderId matches
        OrderItem::where('OrderId', $order->OrderId)
        ->update(['Status' => 'Rejected']);

        return redirect()->back()
            ->with('success', 'Order has been rejected successfully');
    }



    // accept order by business
    public function accept($id, Request $request)
    {
        $order = Order::findOrFail($id);

        // Only update the Status
        $order->Status = 'Accepted';
        $order->save();

        // Set status = Rejected in OrderItems where OrderId matches
        OrderItem::where('OrderId', $order->OrderId)
        ->update(['Status' => 'Preparing']);

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
        // $query = Order::query();
        // $query = Order::with(['items' => function ($q) use ($request) {
        //     if ($request->filled('category')) {
        //         $q->where('ItemType', $request->category);
        //     }
        // }]);

        // $query = Order::with(['items' => function ($q) use ($request) {
        //     if ($request->filled('category')) {
        //         if ($request->category === 'Medicine') {
        //             $q->whereNotNull('MedicineId'); // only medicine items
        //         } elseif ($request->category === 'Food') {
        //             $q->whereNotNull('MenuItemId'); // only food items
        //         }
        //     }
        // }]);

        $query = Order::with('items.medicine', 'items.food');


        // Search name
        // if ($search = $request->get('search')) {

        //     $search = strtolower(trim($request->search));

        //     $query->where(function ($q) use ($search) {
        //         $q->whereHas('items', function ($q2) use ($search) {
        //             $q2->whereRaw('LOWER("ItemName") LIKE ?', ["%{$search}%"]);
        //         });
        //     }); 
        // }

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

        // Filter by category
        // if ($category = $request->get('category')) {
        //     // $query->where('MedicineCategoryId', $category);
        //     $query->where(function ($q) use ($category) {
        //         $q->whereHas('items', function ($q2) use ($category) {
        //              $q2->where('ItemType', $category);
        //         });
        //     }); 
        // }

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

            $query->where(function ($q) use ($search, $request) {
                $q->whereHas('items.food', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                });
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






    public function RestaurantOrders(Request $request)
    {
        $RestaurantId = auth()->user()->restaurants->pluck('RestaurantId')->toArray();

        $query = Order::whereHas('items', function ($q) use ($RestaurantId) {
                    $q->whereIn('BusinessId', $RestaurantId);
                })
                ->with(['items' => function ($q) use ($RestaurantId) {
                    $q->whereIn('BusinessId', $RestaurantId)
                    ->with('food'); // Load food relation if needed
                }]);


        // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->where(function ($q) use ($search, $request) {
                $q->whereHas('items.food', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                });
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

        return view('admin.orders.BusinessViewOrder.restaurant.index', compact('allOrders', 'itemTypes','allRestaurants'));
    }


    //---------------------
    // Medicine Orders
    //---------------------

    public function medicineOrders(Request $request)
    {
        // $query = Order::whereHas('items', function ($q) {
        //             $q->whereNotNull('MedicineId');
        //         })->with(['items' => function ($q) {
        //             $q->whereNotNull('MedicineId')->with('medicine');
        //         }]);

        // $query = Order::whereHas('items', function ($q) {
        //             $q->where('ItemType', 'Medicine');
        //         })->with(['items' => function ($q) {
        //             $q->where('ItemType', 'Medicine')->with('medicine');
        //         }]);

        //  $query = Order::where('RequiresPrescription', true);

        $query = Order::whereDoesntHave('items', function ($q) {
                    $q->where('ItemType', 'Food');
                });

         // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->where(function ($q) use ($search, $request) {
                $q->whereHas('items.medicine', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                });
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
        $medicalStoreIds = auth()->user()->medicalstores->pluck('MedicalStoreId')->toArray();

        $query = Order::whereHas('items', function ($q) use ($medicalStoreIds) {
                    $q->whereIn('BusinessId', $medicalStoreIds);
                })
                ->with(['items' => function ($q) use ($medicalStoreIds) {
                    $q->whereIn('BusinessId', $medicalStoreIds)
                    ->with('medicine'); // Load medicine relation if needed
                }]);

         // Search by product name
        if ($search = $request->get('search')) {
            $search = strtolower(trim($search));

            $query->where(function ($q) use ($search, $request) {
                $q->whereHas('items.medicine', function ($q2) use ($search) {
                    $q2->whereRaw('LOWER(Name) LIKE ?', ["%{$search}%"]);
                });
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

        return view('admin.orders.BusinessViewOrder.medicalstore.index', compact('allOrders', 'itemTypes','allMedicalStores'));
    }



    // Assign Medical Store to Medicine Order / Restaurant to Food Order
    public function assignStore(Request $request)
    {
        try {
            // Validate request
            $request->validate([
                'order_id' => 'required|exists:Orders,OrderId',
                'medical_store_id' => 'nullable|exists:MedicalStores,MedicalStoreId',
                'restaurant_id'    => 'nullable|exists:Restaurants,RestaurantId',
            ]);

            $order = Order::find($request->order_id);

            if (!$order) {
                return response()->json([
                    'success' => false,
                    'message' => 'Order not found'
                ], 404);
            }

            // Prevent assigning for Completed / Cancelled orders
            if (in_array($order->Status, ['Accepted', 'Preparing', 'Packed', 'Completed', 'Cancelled', 'Assigned'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Cannot assign store/restaurant to this order because it is Cancelled or Completed.'
                ], 403);
            }

            // Determine which assignment to do
            if ($request->filled('medical_store_id')) {
                // Assign Medical Store
                OrderItem::where('OrderId', $order->OrderId)
                    ->update(['BusinessId' => $request->medical_store_id]);

                $message = 'Medical store assigned successfully';
            } elseif ($request->filled('restaurant_id')) {
                // Assign Restaurant
                OrderItem::where('OrderId', $order->OrderId)
                    ->update(['BusinessId' => $request->restaurant_id]);

                $message = 'Restaurant assigned successfully';
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'No store or restaurant ID provided'
                ], 422);
            }

            $order->Status = 'Assigned';
            $order->save();

            return response()->json([
                'success' => true,
                'message' => $message
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('Assign store/restaurant error: '.$e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Server error: '.$e->getMessage()
            ], 500);
        }
    }


    public function updateStatus(Request $request)
    {
        try {
            $request->validate([
                'order_id' => 'required|exists:Orders,OrderId',
                'status'   => 'required|in:Pending,Accepted,Preparing,Packed,Completed,Cancelled',
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
