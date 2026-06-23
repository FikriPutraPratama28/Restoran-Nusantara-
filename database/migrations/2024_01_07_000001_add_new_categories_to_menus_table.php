<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL: alter enum to add new category values
        DB::statement("ALTER TABLE menus MODIFY COLUMN category ENUM(
            'makanan','minuman','dessert','snack','paket',
            'seafood','aneka-snack','aneka-sayur','nasi-kotak','acara-khusus'
        ) NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE menus MODIFY COLUMN category ENUM(
            'makanan','minuman','dessert','snack','paket'
        ) NOT NULL");
    }
};
