<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Selaraskan skema dengan validasi controller: `customer_email` boleh kosong
     * (ReservationController memvalidasi email sebagai `nullable`).
     *
     * Memakai raw ALTER khusus MySQL agar tidak perlu menambah dependency doctrine/dbal
     * yang dibutuhkan oleh ->change() di Laravel 10.
     */
    public function up(): void
    {
        if (Schema::hasColumn('reservations', 'customer_email') && DB::getDriverName() === 'mysql') {
            DB::statement('ALTER TABLE reservations MODIFY customer_email VARCHAR(255) NULL');
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('reservations', 'customer_email') && DB::getDriverName() === 'mysql') {
            DB::statement("UPDATE reservations SET customer_email = '' WHERE customer_email IS NULL");
            DB::statement('ALTER TABLE reservations MODIFY customer_email VARCHAR(255) NOT NULL');
        }
    }
};
