<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        $logs = AuditLog::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('Action', 'like', "%$search%")
                        ->orWhere('AuditableType', 'like', "%$search%")
                        ->orWhere('IpAddress', 'like', "%$search%")
                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('Name', 'like', "%$search%");
                        });
                });
            })
            ->latest('CreatedAt')
            ->paginate(10)
            ->withQueryString();


        return view('admin.audit_logs.index', compact('logs', 'search'));
    }
}
