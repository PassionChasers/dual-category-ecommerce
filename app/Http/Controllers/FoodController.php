<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $search = $request->get('search');
        
        $query = User::where('Role', 'restaurant');
        
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('Name', 'like', "%{$search}%")
                    ->orWhere('Email', 'like', "%{$search}%")
                    ->orWhere('Phone', 'like', "%{$search}%");
            });
        }
        
        $users = $query->paginate(15)->withQueryString();
        
        return view('admin.products.food.index', compact('users', 'search'));
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
        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:Users,Email',
            'PasswordHash' => 'required|string|min:6',
            'Phone' => 'nullable|string|max:20',
        ]);

        $validated['PasswordHash'] = bcrypt($validated['PasswordHash']);
        $validated['Role'] = 'restaurant';

        User::create($validated);

        return redirect()->route('food.index')->with('success', 'Restaurant created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'Name' => 'required|string|max:255',
            'Email' => 'required|email|unique:Users,Email,' . $id,
            'PasswordHash' => 'nullable|string|min:6',
            'Phone' => 'nullable|string|max:20',
        ]);

        if (!empty($validated['PasswordHash'])) {
            $validated['PasswordHash'] = bcrypt($validated['PasswordHash']);
        } else {
            unset($validated['PasswordHash']);
        }

        $user->update($validated);

        return redirect()->route('food.index')->with('success', 'Restaurant updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->route('food.index')->with('success', 'Restaurant deleted successfully!');
    }

    /**
     * Get all restaurants
     */
    public function allRestaurants()
    {
        $restaurants = User::where('Role', 'restaurant')->paginate(8);
        return view('admin.users.restaurants.index', compact('restaurants'));
    }


    //MY PRODUCTS FUNCTION
    // public function myProducts(Request $request)
    // {
        // $search = $request->get('search');
        // $statusFilter = $request->get('status');

        // $query = Task::with(['priority', 'category', 'assignee', 'requester'])
        //     ->where('assigned_to', auth()->id());

        // if ($search) {
        //     $query->where(function ($q) use ($search) {
        //         $q->where('name', 'like', "%{$search}%")
        //             ->orWhere('description', 'like', "%{$search}%");
        //     });
        // }

        // if ($statusFilter !== null && $statusFilter !== '') {
        //     $query->where('status', intval($statusFilter));
        // }

        // $tasks = $query->orderBy('created_at', 'desc')
        //     ->paginate(12)
        //     ->withQueryString();

        // $priorities = Priority::orderBy('id')->get();
        // $categories = TaskCategory::orderBy('name')->get();

        // return view('admin.tasks.index', compact('tasks', 'priorities', 'categories', 'search', 'statusFilter'));
    //     return view('admin.products.my-product');
    // }
}
