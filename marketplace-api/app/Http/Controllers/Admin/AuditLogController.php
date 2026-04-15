<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Resources\AdminAuditLogResource;
use App\Models\AdminAuditLog;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $logs = AdminAuditLog::with('admin')
            ->when($request->action, fn($q, $action) => $q->where('action', $action))
            ->when($request->admin_id, fn($q, $adminId) => $q->where('admin_id', $adminId))
            ->when($request->target_type, fn($q, $type) => $q->where('target_type', $type))
            ->latest()
            ->paginate($request->per_page ?? 20);

        return response()->json([
            'audit_logs' => AdminAuditLogResource::collection($logs),
            'meta' => [
                'current_page' => $logs->currentPage(),
                'last_page'    => $logs->lastPage(),
                'total'        => $logs->total(),
            ],
        ]);
    }
}
