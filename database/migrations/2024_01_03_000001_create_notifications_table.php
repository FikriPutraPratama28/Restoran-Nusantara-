<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // penerima
            $table->string('type', 60);        // leave_request, leave_approved, leave_rejected, attendance_late, order_new, reservation_new
            $table->string('title', 150);
            $table->text('message');
            $table->string('icon', 10)->default('🔔');
            $table->string('color', 50)->default('bg-blue-100 dark:bg-blue-900/30');
            $table->string('url')->nullable();  // link ke halaman terkait
            $table->nullableMorphs('notifiable'); // polymorphic: leave_request, attendance, dll
            $table->timestamp('read_at')->nullable(); // null = belum dibaca
            $table->timestamps();

            $table->index(['user_id', 'read_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
