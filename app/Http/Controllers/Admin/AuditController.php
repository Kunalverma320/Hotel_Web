<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AuditController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'user_id'    => 'nullable|integer',
            'module'     => 'nullable|string',
            'action'     => 'nullable|string',
            'start_date' => 'nullable|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
        ]);

        $userId    = $request->input('user_id');
        $module    = $request->input('module');
        $action    = $request->input('action');
        $startDate = $request->input('start_date');
        $endDate   = $request->input('end_date');

        // TODO: Query audit logs with filters

        return view('admin.audit.index', compact('userId', 'module', 'action', 'startDate', 'endDate'));
    }

    public function show($id)
    {
        // TODO: Fetch audit log by id with old/new data

        $log = (object) [
            'id'         => $id,
            'user_id'    => null,
            'module'     => '',
            'action'     => '',
            'model'      => '',
            'model_id'   => null,
            'old_data'   => [],
            'new_data'   => [],
            'ip_address' => '',
            'created_at' => now(),
        ];

        return response()->json($log);
    }
}
