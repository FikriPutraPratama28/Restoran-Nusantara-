<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    private function getUserId()
    {
        if (Auth::check()) {
            return Auth::id();
        }
        if (session('admin_logged_in') && session('admin_email')) {
            $admin = \App\Models\User::where('email', session('admin_email'))->first();
            return $admin ? $admin->id : null;
        }
        return null;
    }

    /** Halaman semua notifikasi */
    public function index()
    {
        $notifications = Notification::forUser($this->getUserId())
            ->latest()
            ->paginate(20);

        // Tandai semua sebagai dibaca saat halaman dibuka
        Notification::forUser($this->getUserId())->unread()->update(['read_at' => now()]);

        return view('notifications.index', compact('notifications'));
    }

    /** Mark satu notifikasi sebagai dibaca (AJAX) */
    public function markRead(Notification $notification)
    {
        abort_if($notification->user_id !== $this->getUserId(), 403);
        $notification->markAsRead();

        return response()->json(['success' => true]);
    }

    /** Mark semua sebagai dibaca (AJAX) */
    public function markAllRead()
    {
        Notification::forUser($this->getUserId())->unread()->update(['read_at' => now()]);

        return response()->json(['success' => true, 'message' => 'Semua notifikasi ditandai dibaca.']);
    }

    /** Hapus satu notifikasi */
    public function destroy(Notification $notification)
    {
        abort_if($notification->user_id !== $this->getUserId(), 403);
        $notification->delete();

        return back()->with('success', 'Notifikasi dihapus.');
    }

    /** API: ambil jumlah unread (untuk polling) */
    public function unreadCount()
    {
        $count = Notification::forUser($this->getUserId())->unread()->count();
        $latest = Notification::forUser($this->getUserId())->unread()->latest()->take(5)->get();

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
