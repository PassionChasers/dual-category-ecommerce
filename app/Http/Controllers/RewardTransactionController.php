<?php

namespace App\Http\Controllers;

use App\Models\RewardTransaction;
use Illuminate\Http\Request;

class RewardTransactionController extends Controller
{
    /**
     * Display a listing of all reward transactions with user details
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $type = $request->input('type');
        $status = $request->input('status');

        $transactions = RewardTransaction::with('customer', 'order')
            ->when($search, function ($query) use ($search) {
                $query->where('Description', 'like', "%$search%")
                      ->orWhere('ReferenceId', 'like', "%$search%")
                      ->orWhere('Notes', 'like', "%$search%")
                      ->orWhereHas('customer', function ($q) use ($search) {
                          $q->where('Name', 'like', "%$search%")
                            ->orWhere('Email', 'like', "%$search%")
                            ->orWhere('Phone', 'like', "%$search%");
                      });
            })
            ->when($type, function ($query) use ($type) {
                $query->where('Type', $type);
            })
            ->when($status, function ($query) use ($status) {
                $query->where('Status', $status);
            })
            ->latest('TransactionDate')
            ->paginate(15)
            ->withQueryString();

        // Get unique transaction types for filter dropdown
        $types = RewardTransaction::distinct()
            ->pluck('Type')
            ->sort()
            ->values();

        return view('admin.reward_transactions.index', compact('transactions', 'search', 'type', 'types', 'status'));
    }

    /**
     * Display the specified reward transaction details
     */
    public function show(RewardTransaction $rewardTransaction)
    {
        $rewardTransaction->load('customer', 'order');
        return view('admin.reward_transactions.show', compact('rewardTransaction'));
    }
}
