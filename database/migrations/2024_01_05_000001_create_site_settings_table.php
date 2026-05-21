<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->timestamps();
        });

        // Seed default values
        DB::table('site_settings')->insert([
            ['key' => 'restaurant_name',    'value' => 'Restoran',    'created_at' => now(), 'updated_at' => now()],
            ['key' => 'restaurant_tagline', 'value' => 'NUSANTARA',   'created_at' => now(), 'updated_at' => now()],
            ['key' => 'logo',               'value' => null,           'created_at' => now(), 'updated_at' => now()],
            ['key' => 'favicon',            'value' => null,           'created_at' => now(), 'updated_at' => now()],
            ['key' => 'description',        'value' => 'Pengalaman kuliner modern dengan cita rasa autentik Nusantara.', 'created_at' => now(), 'updated_at' => now()],
            ['key' => 'address',            'value' => 'Jl. Kuliner No. 1, Jakarta',  'created_at' => now(), 'updated_at' => now()],
            ['key' => 'phone',              'value' => '+62 812-3456-7890',            'created_at' => now(), 'updated_at' => now()],
            ['key' => 'email',              'value' => 'info@restoran.id',             'created_at' => now(), 'updated_at' => now()],
            ['key' => 'primary_color',      'value' => '#f97316',                      'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('site_settings');
    }
};
