<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->enum('category', ['makanan','minuman','dessert','snack','paket']);
            $table->unsignedInteger('price');
            $table->unsignedInteger('original_price')->nullable();
            $table->string('image')->nullable();          // path file upload
            $table->string('image_url')->nullable();      // atau URL eksternal
            $table->enum('label', ['best-seller','popular','new',''])->default('');
            $table->boolean('is_stock')->default(true);
            $table->boolean('is_promo')->default(false);
            $table->boolean('is_new')->default(false);
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sold_count')->default(0);
            $table->decimal('rating', 3, 1)->default(0);
            $table->unsignedInteger('review_count')->default(0);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
