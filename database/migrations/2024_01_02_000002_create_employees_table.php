<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('employee_code', 20)->unique(); // Kode karyawan, misal: EMP001
            $table->string('jabatan', 100);                // Chef, Kasir, Pelayan, dll
            $table->enum('shift', ['pagi', 'siang', 'malam', 'full'])->default('pagi');
            $table->date('join_date');
            $table->string('address')->nullable();
            $table->string('emergency_contact', 20)->nullable();
            $table->enum('status', ['aktif', 'cuti', 'nonaktif'])->default('aktif');
            $table->decimal('salary', 12, 2)->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
