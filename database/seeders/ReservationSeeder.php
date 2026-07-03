<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Reservation;
use App\Models\Menu;
use App\Models\User;
use Carbon\Carbon;

class ReservationSeeder extends Seeder
{
    public function run(): void
    {
        $menus = Menu::all();
        if ($menus->isEmpty()) {
            return;
        }

        $pelanggan = User::where('role', 'pelanggan')->first();
        $pelangganId = $pelanggan ? $pelanggan->id : null;

        $names = ['Ahmad Fauzi', 'Siti Aminah', 'Budi Santoso', 'Dewi Lestari', 'Joko Widodo', 'Rina Marlina', 'Hendri Wijaya', 'Susi Susanti', 'Rudi Hartono', 'Megawati'];
        $emails = ['ahmad@gmail.com', 'siti@gmail.com', 'budi@gmail.com', 'dewi@gmail.com', 'joko@gmail.com', 'rina@gmail.com', 'hendri@gmail.com', 'susi@gmail.com', 'rudi@gmail.com', 'mega@gmail.com'];
        $phones = ['081234567890', '081234567891', '081234567892', '081234567893', '081234567894', '081234567895', '081234567896', '081234567897', '081234567898', '081234567899'];
        $paymentMethods = ['qris', 'cash', 'va'];
        $statuses = ['completed', 'confirmed', 'pending', 'cancelled'];

        // Seed reservations over the last 10 days
        for ($i = 9; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->toDateString();
            
            // 2-4 reservations per day
            $numReservations = rand(2, 5);
            for ($j = 0; $j < $numReservations; $j++) {
                $status = ($i === 0) 
                    ? (rand(0, 1) ? 'pending' : 'confirmed') 
                    : (rand(0, 5) === 0 ? 'cancelled' : 'completed');

                $selectedMenus = $menus->random(rand(2, 4));
                $orderedItems = [];
                foreach ($selectedMenus as $menu) {
                    $orderedItems[] = [
                        'id' => $menu->id,
                        'name' => $menu->name,
                        'price' => (int) $menu->price,
                        'qty' => rand(1, 3),
                        'image' => $menu->image_url,
                    ];
                }

                $nameIndex = rand(0, count($names) - 1);
                
                Reservation::create([
                    'user_id' => rand(0, 1) ? $pelangganId : null,
                    'reservation_code' => Reservation::generateCode(),
                    'reservation_date' => $date,
                    'reservation_time' => sprintf('%02d:00', rand(10, 21)),
                    'number_of_guests' => rand(2, 6),
                    'customer_name' => $names[$nameIndex],
                    'customer_phone' => $phones[$nameIndex],
                    'customer_email' => $emails[$nameIndex],
                    'notes' => rand(0, 2) === 0 ? 'Minta meja dekat jendela' : null,
                    'table_area' => rand(0, 1) ? 'indoor' : 'outdoor',
                    'table_number' => (rand(0, 1) ? 'I-' : 'O-') . sprintf('%02d', rand(1, 12)),
                    'status' => $status,
                    'payment_method' => $paymentMethods[rand(0, count($paymentMethods) - 1)],
                    'ordered_items' => $orderedItems,
                ]);
            }
        }
    }
}
