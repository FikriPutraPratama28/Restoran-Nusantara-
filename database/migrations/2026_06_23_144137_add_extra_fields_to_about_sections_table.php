<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            $table->string('badge')->nullable()->after('subtitle');
            $table->string('tagline')->nullable()->after('badge');
            $table->string('chef_label')->nullable()->after('description_2');
            $table->string('chef_sub')->nullable()->after('chef_label');
        });
    }

    public function down(): void
    {
        Schema::table('about_sections', function (Blueprint $table) {
            $table->dropColumn(['badge', 'tagline', 'chef_label', 'chef_sub']);
        });
    }
};
