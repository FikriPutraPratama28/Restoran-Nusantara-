<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        $module = $request->get('module', '');
        $role   = $request->get('role', '');
        $search = $request->get('search', '');
        $date   = $request->get('date', '');

        $allowedRoles = ['admin', 'pelanggan'];

        $query = ActivityLog::with('user')
            ->whereIn('role', $allowedRoles)
            ->when($module, fn($q) => $q->where('module', $module))
            ->when($role,   fn($q) => $q->where('role', $role))
            ->when($search, fn($q) => $q->where(function($q2) use ($search) {
                $q2->where('description', 'like', "%{$search}%")
                   ->orWhere('actor', 'like', "%{$search}%");
            }))
            ->when($date, fn($q) => $q->whereDate('created_at', $date))
            ->latest();

        $logs = $query->paginate(30)->withQueryString();

        // Stats
        $stats = [
            'total'    => ActivityLog::whereIn('role', $allowedRoles)->count(),
            'today'    => ActivityLog::whereIn('role', $allowedRoles)->whereDate('created_at', today())->count(),
            'modules'  => ActivityLog::whereIn('role', $allowedRoles)
                            ->selectRaw('module, count(*) as total')
                            ->groupBy('module')->pluck('total', 'module')->toArray(),
        ];

        $modules = ActivityLog::whereIn('role', $allowedRoles)->distinct()->pluck('module')->sort()->values();
        $roles   = $allowedRoles;

        return view('admin.activity-log', compact('logs', 'stats', 'modules', 'roles', 'module', 'role', 'search', 'date'));
    }

    public function clear(Request $request)
    {
        $days = (int) $request->get('days', 30);
        $days = max(7, min(365, $days)); // antara 7–365 hari

        $deleted = ActivityLog::where('created_at', '<', now()->subDays($days))->delete();

        ActivityLog::log('clear_logs', 'System',
            "Membersihkan {$deleted} log aktivitas yang lebih dari {$days} hari"
        );

        return back()->with('success', "{$deleted} log aktivitas berhasil dihapus.");
    }
}
