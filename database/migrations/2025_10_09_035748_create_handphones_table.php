<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('handphones', function (Blueprint $table) {
            $table->id();
            $table->string('image')->nullable();
            $table->string('brand');
            $table->string('model');
            $table->year('release_year')->nullable();
            $table->enum('is_active', ['active', 'nonactive'])->default('active');
            $table->timestamps();

            // 🔒 Tambahkan constraint unik
            $table->unique(['brand', 'model'], 'unique_brand_model');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handphones');
    }
};