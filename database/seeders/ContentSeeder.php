<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeroSlide;
use App\Models\Promo;
use App\Models\AboutSection;
use App\Models\TeamMember;
use App\Models\Facility;

class ContentSeeder extends Seeder
{
    public function run(): void
    {
        // ── Hero Slides ──────────────────────────────────────────────────
        HeroSlide::create([
            'title'       => 'Cita Rasa Nusantara di Ujung Jari',
            'subtitle'    => 'Buka Sekarang · Estimasi 15-30 menit',
            'description' => 'Pesan makanan favoritmu, reservasi meja, dan nikmati promo eksklusif — semua dalam satu platform digital yang modern.',
            'cta_text'    => 'Lihat Menu',
            'cta_link'    => '#menu',
            'media_type'  => 'image',
            'image_url'   => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1920&h=1080&fit=crop',
            'is_active'   => true,
            'sort_order'  => 1,
        ]);

        // ── Promos ───────────────────────────────────────────────────────
        $promos = [
            ['title'=>'Diskon 30% Makanan',    'description'=>'Berlaku setiap hari Senin untuk semua menu makanan', 'code'=>'SENIN30',     'discount_type'=>'percent','discount_value'=>30, 'icon'=>'🍔','badge'=>'Mingguan',  'gradient'=>'from-primary-600 to-orange-500','expiry_label'=>'Setiap Senin'],
            ['title'=>'Buy 1 Get 1 Minuman',   'description'=>'Setiap weekend pukul 14.00-17.00 WIB',              'code'=>'WEEKEND2X',   'discount_type'=>'percent','discount_value'=>50, 'icon'=>'🥤','badge'=>'Weekend',   'gradient'=>'from-purple-600 to-pink-500',  'expiry_label'=>'Sabtu & Minggu'],
            ['title'=>'Free Dessert',           'description'=>'Gratis dessert untuk pembelian min. Rp 100.000',    'code'=>'FREEDESSERT', 'discount_type'=>'fixed',  'discount_value'=>30000,'icon'=>'🎂','badge'=>'Permanen', 'gradient'=>'from-green-600 to-teal-500',   'expiry_label'=>'Berlaku terus','min_purchase'=>100000],
            ['title'=>'Diskon 15% New User',   'description'=>'Khusus untuk pelanggan baru yang pertama kali order','code'=>'NEWUSER',    'discount_type'=>'percent','discount_value'=>15, 'icon'=>'👤','badge'=>'New User',  'gradient'=>'from-blue-600 to-cyan-500',    'expiry_label'=>'Sekali pakai'],
            ['title'=>'Potongan Rp 20.000',    'description'=>'Potongan langsung untuk pembelian min. Rp 75.000',  'code'=>'GRATIS20',    'discount_type'=>'fixed',  'discount_value'=>20000,'icon'=>'🎉','badge'=>'Cashback', 'gradient'=>'from-red-600 to-rose-500',     'expiry_label'=>'Berlaku terus','min_purchase'=>75000],
            ['title'=>'Member Diskon 10%',     'description'=>'Diskon 10% untuk semua member terdaftar',           'code'=>'HEMAT10',     'discount_type'=>'percent','discount_value'=>10, 'icon'=>'⭐','badge'=>'Member',    'gradient'=>'from-yellow-500 to-amber-500', 'expiry_label'=>'Berlaku terus'],
        ];
        foreach ($promos as $i => $p) {
            Promo::create(array_merge($p, ['is_active'=>true, 'sort_order'=>$i+1, 'min_purchase'=>$p['min_purchase']??0]));
        }

        // ── About Section ────────────────────────────────────────────────
        AboutSection::create([
            'title'         => 'Berawal dari Cinta terhadap Kuliner',
            'subtitle'      => 'Kisah Kami',
            'description_1' => 'Restoran NUSANTARA didirikan pada tahun 2019 dengan satu misi sederhana: menyajikan cita rasa autentik masakan Nusantara dengan sentuhan modern yang memudahkan semua orang menikmatinya.',
            'description_2' => 'Dimulai dari warung kecil di sudut kota, kini kami telah berkembang menjadi restoran digital yang melayani ribuan pelanggan setiap harinya. Kami percaya bahwa teknologi dan kuliner bisa berjalan beriringan.',
            'image_url'     => 'https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=500&fit=crop',
            'stats'         => [
                ['value'=>'2019', 'label'=>'Tahun Berdiri'],
                ['value'=>'10K+', 'label'=>'Pelanggan Puas'],
                ['value'=>'500+', 'label'=>'Menu Tersedia'],
                ['value'=>'4.9★', 'label'=>'Rating Google'],
            ],
            'is_active' => true,
        ]);

        // ── Team Members ─────────────────────────────────────────────────
        $team = [
            ['name'=>'Pak Budi',  'role'=>'Head Chef',    'emoji'=>'👨‍🍳','gradient'=>'from-orange-400 to-red-500',   'image_url'=>null],
            ['name'=>'Bu Sari',   'role'=>'Pastry Chef',  'emoji'=>'👩‍🍳','gradient'=>'from-pink-400 to-purple-500',  'image_url'=>null],
            ['name'=>'Mas Andi',  'role'=>'Barista',      'emoji'=>'☕',  'gradient'=>'from-amber-400 to-orange-500', 'image_url'=>null],
            ['name'=>'Mbak Rina', 'role'=>'Manager',      'emoji'=>'👩‍💼','gradient'=>'from-blue-400 to-cyan-500',    'image_url'=>null],
        ];
        foreach ($team as $i => $m) {
            TeamMember::create(array_merge($m, ['is_active'=>true, 'sort_order'=>$i+1]));
        }

        // ── Facilities ───────────────────────────────────────────────────
        $facilities = [
            ['title'=>'Ruang Ber-AC',    'description'=>'Ruangan indoor nyaman dengan pendingin udara modern untuk kenyamanan maksimal',                    'icon'=>'❄️','tag'=>'Indoor',    'image_url'=>'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=500&h=300&fit=crop'],
            ['title'=>'Taman Outdoor',   'description'=>'Area outdoor asri dengan taman hijau, cocok untuk bersantai sambil menikmati udara segar',         'icon'=>'🌿','tag'=>'Outdoor',   'image_url'=>'https://images.unsplash.com/photo-1555396273-367ea4eb4db5?w=500&h=300&fit=crop'],
            ['title'=>'Free WiFi',       'description'=>'Koneksi internet cepat tersedia di seluruh area restoran, kecepatan hingga 100 Mbps',              'icon'=>'📶','tag'=>'Teknologi', 'image_url'=>'https://images.unsplash.com/photo-1544148103-0773bf10d330?w=500&h=300&fit=crop'],
            ['title'=>'Parkir Luas',     'description'=>'Area parkir luas dan aman untuk kendaraan roda dua maupun roda empat',                             'icon'=>'🅿️','tag'=>'Parkir',    'image_url'=>'https://images.unsplash.com/photo-1506521781263-d8422e82f27a?w=500&h=300&fit=crop'],
            ['title'=>'Live Music',      'description'=>'Hiburan live music setiap Jumat & Sabtu malam untuk menemani makan malam kamu',                    'icon'=>'🎵','tag'=>'Hiburan',   'image_url'=>'https://images.unsplash.com/photo-1514525253161-7a46d19cd819?w=500&h=300&fit=crop'],
            ['title'=>'Area Anak',       'description'=>'Playground khusus anak-anak agar si kecil bisa bermain dengan aman dan menyenangkan',              'icon'=>'👶','tag'=>'Keluarga',  'image_url'=>'https://images.unsplash.com/photo-1526634332515-d56c5fd16991?w=500&h=300&fit=crop'],
            ['title'=>'Private Room',    'description'=>'Ruang privat eksklusif untuk acara ulang tahun, anniversary, atau pertemuan bisnis',               'icon'=>'🎂','tag'=>'VIP',       'image_url'=>'https://images.unsplash.com/photo-1559339352-11d035aa65de?w=500&h=300&fit=crop'],
            ['title'=>'Akses Difabel',   'description'=>'Fasilitas ramah difabel dengan ramp, toilet khusus, dan area parkir prioritas',                    'icon'=>'♿','tag'=>'Inklusif',  'image_url'=>'https://images.unsplash.com/photo-1529543544282-ea669407fca3?w=500&h=300&fit=crop'],
            ['title'=>'CCTV 24 Jam',     'description'=>'Keamanan terjamin dengan sistem CCTV 24 jam dan petugas keamanan berpengalaman',                   'icon'=>'🔒','tag'=>'Keamanan',  'image_url'=>'https://images.unsplash.com/photo-1466978913421-dad2ebd01d17?w=500&h=300&fit=crop'],
        ];
        foreach ($facilities as $i => $f) {
            Facility::create(array_merge($f, ['is_active'=>true, 'sort_order'=>$i+1]));
        }
    }
}
