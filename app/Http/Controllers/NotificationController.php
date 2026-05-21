<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /** Halaman semua notifikasi */
    public function index()
    {
        $notifications = Notification::forUser(Auth::id())
            ->latest()
            ->paginate(20);

        // Tandai semua sebagai dibaca saat halaman dibuka
        Notification::forUser(Auth::id())->unread()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark satu notifikasi sebagai dibaca (AJAX) */
    public function markRead(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /** Mark semua sebagai dibaca (AJAX) */
    public function markAllRead()
    {
        Notification::forUser(Auth::id())->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Semua notifikasi ditandai dibaca.']);
    }

    /** Hapus satu notifikasi */
    public function destroy(Notification $notification)
    {
        abort_if($notification->user_id !== Auth::id(), 403);
        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /** API: ambil jumlah unread (untuk polling) */
    public function unreadCount()
    {
        $count = Notification::forUser(Auth::id())->unread()->count();
        $latest = Notification::forUser(Auth::id())->unread()->latest()->take(5)->get();

        return response()->json([
            'count'    => $count,
            'latest'   => $latest->map(fn($n) => [
                'id'      => $n->id,
                'title'   => $n->title,
                'message' => $n->message,
                'icon'    => $n->icon,
                'color'   => $n->color,
                'url'     => $n->url,
                'time'    => $n->created_at->diffForHumans(),
            ]),
        ]);
    }
}
