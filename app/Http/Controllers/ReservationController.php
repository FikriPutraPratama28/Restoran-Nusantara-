<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
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
            'email' => 'required|email',
            'notes' => 'nullable|string|max:1000',
            'tableArea' => 'required|in:indoor,outdoor',
        ]);

        try {
            $reservation = Reservation::create([
                'user_id' => auth()->id(),
                'reservation_date' => $validated['date'],
                'reservation_time' => $validated['time'],
                'number_of_guests' => $validated['guests'],
                'customer_name' => $validated['name'],
                'customer_phone' => $validated['phone'],
                'customer_email' => $validated['email'],
                'notes' => $validated['notes'],
                'table_area' => $validated['tableArea'],
                'status' => 'pending',
            ]);

            // Log activity
            activity()
                ->causedBy(auth()->user())
                ->withProperties(['reservation_id' => $reservation->id])
                ->log('Reservasi baru dibuat');

            return response()->json([
                'success' => true,
                'message' => 'Reservasi berhasil dibuat! Admin akan mengkonfirmasi reservasi Anda dalam waktu singkat.',
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

            activity()
                ->causedBy(auth()->user())
                ->withProperties(['reservation_id' => $reservation->id])
                ->log('Reservasi dibatalkan');

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
