<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->time('check_in')->nullable();
            $table->time('check_out')->nullable();
            $table->enum('status', ['hadir', 'terlambat', 'izin', 'sakit', 'alpha'])->default('hadir');
            $table->integer('late_minutes')->default(0);   // Menit keterlambatan
            $table->string('check_in_photo')->nullable();  // Foto selfie absensi masuk
            $table->string('check_out_photo')->nullable(); // Foto selfie absensi pulang
            $table->decimal('check_in_lat', 10, 8)->nullable();   // GPS latitude masuk
            $table->decimal('check_in_lng', 11, 8)->nullable();   // GPS longitude masuk
            $table->decimal('check_out_lat', 10, 8)->nullable();  // GPS latitude pulang
            $table->decimal('check_out_lng', 11, 8)->nullable();  // GPS longitude pulang
            $table->string('qr_token', 64)->nullable();    // Token QR Code absensi
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->unique(['employee_id', 'date']); // Satu absensi per hari per karyawan
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
