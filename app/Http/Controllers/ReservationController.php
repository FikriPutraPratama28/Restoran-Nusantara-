<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    /**
     * Store a newly created reservation
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date|after:today',
            'time' => 'required|date_format:H:i',
            'guests' => 'required|integer|min:1|max:50',
            'name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email',
            'notes' => 'nullable|string|max:1000',
            'tableArea' => 'required|in:indoor,outdoor',
            'tableNumber' => 'required|string|max:20',
            'paymentMethod' => 'required|in:cash,qris,va',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|integer|exists:menus,id',
            'items.*.qty' => 'required|integer|min:1',
        ]);

        try {
            // Resolve menus from DB
            $menuIds = collect($validated['items'])->pluck('id')->toArray();
            $menus = \App\Models\Menu::whereIn('id', $menuIds)->get()->keyBy('id');

            $orderedItems = [];
            foreach ($validated['items'] as $item) {
                $menu = $menus->get($item['id']);
                if ($menu) {
                    $orderedItems[] = [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'price' => (int) $menu->price,
                        'qty' => (int) $item['qty'],
                        'image' => $menu->image,
                    ];
                }
            }

            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'reservation_code' => Reservation::generateCode(),
                'reservation_date' => $validated['date'],
                'reservation_time' => $validated['time'],
                'number_of_guests' => $validated['guests'],
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'notes' => $validated['notes'],
                'table_area' => $validated['tableArea'],
                'table_number' => $validated['tableNumber'],
                'payment_method' => $validated['paymentMethod'],
                'ordered_items' => $orderedItems,
                'status' => 'pending',
            ]);

            // Log activity
            ActivityLog::log(
                'create_reservation',
                'Reservation',
                "Reservasi baru dibuat oleh " . ($reservation->user_id ? $reservation->customer_name : 'Guest') . " untuk tanggal {$validated['date']} jam {$validated['time']}",
                $reservation
            );

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat! Admin akan mengkonfirmasi reservasi Anda dalam waktu singkat.',
                'redirect' => route('reservation.receipt', ['code' => $reservation->reservation_code]),
                'reservation' => $reservation,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membuat reservasi: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Show reservation receipt/struk
     */
    public function receipt($code)
    {
        $reservation = Reservation::where('reservation_code', $code)->firstOrFail();
        return view('pages.receipt', compact('reservation'));
    }

    /**
     * Get reservations for authenticated user
     */
    public function myReservations(Request $request)
    {
        $reservations = Reservation::where('user_id', auth()->id())
            ->orderBy('reservation_date', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'reservations' => $reservations,
        ]);
    }

    /**
     * Cancel a reservation
     */
    public function cancel(Reservation $reservation)
    {
        // Pastikan user adalah pemilik reservasi atau admin
        if ($reservation->user_id !== auth()->id() && !auth()->user()->isAdmin()) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk membatalkan reservasi ini.',
            ], 403);
        }

        try {
            $reservation->update(['status' => 'cancelled']);

            ActivityLog::log(
                'cancel_reservation',
                'Reservation',
                "Reservasi {$reservation->reservation_code} dibatalkan oleh " . (auth()->user() ? auth()->user()->name : 'Guest'),
                $reservation
            );

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibatalkan.',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal membatalkan reservasi.',
            ], 500);
        }
    }
}
