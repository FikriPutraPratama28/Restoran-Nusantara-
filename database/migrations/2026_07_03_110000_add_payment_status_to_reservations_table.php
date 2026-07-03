<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tambah kolom status pembayaran untuk fitur approval admin
     * (lunas / belum lunas). Default 'unpaid' = belum lunas.
     */
    public function up(): void
    {
        if (Schema::hasTable('reservations') && !Schema::hasColumn('reservations', 'payment_status')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->string('payment_status')->default('unpaid')->after('payment_method');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'payment_status')) {
            Schema::table('reservations', function (Blueprint $table) {
                $table->dropColumn('payment_status');
            });
        }
    }
};
