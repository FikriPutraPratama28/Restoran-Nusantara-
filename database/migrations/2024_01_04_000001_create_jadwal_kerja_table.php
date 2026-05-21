<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jadwal_kerja', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('shift', ['pagi', 'siang', 'malam', 'full'])->default('full');
            $table->enum('hari', ['senin', 'selasa', 'rabu', 'kamis', 'jumat', 'sabtu', 'minggu']);
            $table->time('jam_mulai')->nullable();   // override jam jika berbeda dari default shift
            $table->time('jam_selesai')->nullable();
            $table->boolean('is_active')->default(true);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->unique(['user_id', 'hari']); // satu jadwal per hari per user
            $table->index(['user_id', 'shift']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jadwal_kerja');
    }
};
