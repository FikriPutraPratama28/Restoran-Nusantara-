<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('actor', 100)->nullable();       // nama user saat log dibuat
            $table->string('role', 20)->nullable();         // admin | karyawan | pelanggan | system
            $table->string('action', 80);                   // create_menu, checkin, approve_leave, dll
            $table->string('module', 60);                   // Menu, Attendance, Leave, Employee, Auth
            $table->text('description');                    // kalimat deskriptif
            $table->string('subject_type', 100)->nullable();// model class
            $table->unsignedBigInteger('subject_id')->nullable(); // model id
            $table->json('properties')->nullable();         // data tambahan (before/after)
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['module', 'action']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
