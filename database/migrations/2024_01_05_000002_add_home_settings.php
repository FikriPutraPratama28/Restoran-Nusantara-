<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $keys = [
            'home_bg_image'      => 'https://images.unsplash.com/photo-1517248135467-4c7edcad34c4?w=1920&h=1080&fit=crop',
            'home_hero_title'    => 'Cita Rasa Nusantara di Ujung Jari',
            'home_hero_subtitle' => 'Pesan makanan favoritmu, reservasi meja, dan nikmati promo eksklusif — semua dalam satu platform digital yang modern.',
            'home_badge_text'    => 'Buka Sekarang · Estimasi 15-30 menit',
            'home_float_img1'    => 'https://images.unsplash.com/photo-1603133872878-684f208fb84b?w=300&h=300&fit=crop',
            'home_float_img2'    => 'https://images.unsplash.com/photo-1565958011703-44f9829ba187?w=200&h=200&fit=crop',
            'home_float_img3'    => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=200&h=200&fit=crop',
        ];

        foreach ($keys as $key => $value) {
            DB::table('site_settings')->updateOrInsert(
                ['key' => $key],
                ['value' => $value, 'created_at' => now(), 'updated_at' => now()]
            );
        }
    }

    public function down(): void
    {
        DB::table('site_settings')->whereIn('key', [
            'home_bg_image', 'home_hero_title', 'home_hero_subtitle',
            'home_badge_text', 'home_float_img1', 'home_float_img2', 'home_float_img3',
        ])->delete();
    }
};
