<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('serviceitems', function (Blueprint $table) {
            $table->unique('service_name', 'unique_service_name');
        });
    }

    public function down(): void
    {
        Schema::table('serviceitems', function (Blueprint $table) {
            $table->dropUnique('unique_service_name');
        });
    }
};