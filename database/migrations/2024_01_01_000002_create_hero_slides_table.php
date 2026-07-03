<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('hero_slides', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('subtitle')->nullable();
            $table->text('description')->nullable();
            $table->string('cta_text')->default('Lihat Menu');
            $table->string('cta_link')->default('#menu');
            $table->string('image')->nullable();
            $table->string('image_url')->nullable();
            $table->string('media_type')->default('image'); // image | video
            $table->string('video_url')->nullable();
            $table->string('overlay_color')->default('from-black/80 via-black/50 to-transparent');
            $table->boolean('is_active')->default(true);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('hero_slides'); }
};
