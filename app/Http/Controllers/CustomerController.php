<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = User::query()->where('Role', 'Customer');

        // Search by user name
        if ($request->filled('search')) {

            $search = $request->search;
            // $search = ucfirst(strtolower($request->search)); // First letter uppercase

            $query->where('name', 'like', '%' . $search . '%');
        }

        //Filter by online status
        if ($request->filled('onlineStatus')) {
            $query->where('IsActive', $request->onlineStatus);
        }

        // Paginate results with query parameters
        $users = $query->latest()->paginate(10)->appends($request->all());
        // $allOrders = $medicineOrders;

        //AJAX response
        if($request->ajax()){
            return view('admin.users.customers.searchedCustomers', compact('users'))->render();
        }
        //Normal load
        return view('admin.users.customers.index', compact('users'));
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
    public function show(Customer $customer)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Customer $customer)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $customer = User::findOrFail($id);
        $customer->name = $request->input('name');
        $customer->email = $request->input('email');
        $customer->address = $request->input('address');
        // $customer->IsActive = $request->input('IsActive');
        $customer->IsActive = $request->boolean('IsActive');
        $customer->contact_number = $request->input('contact_number');
        $customer->save();

        // return redirect()->route('customers.index')->with('success', 'Customer updated successfully.');

        return back()->with('success', 'Customer updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $customer = User::findOrFail($id);
        $customer->delete();

        return redirect()->route('customers.index')->with('success', 'User deleted successfully.');
    }
}
