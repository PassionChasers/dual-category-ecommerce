<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class FoodController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.products.food.index');
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
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
