<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->string('no_invoice')->unique();
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('technician_id')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('handphone_id')->constrained('handphones')->onDelete('cascade');
            $table->text('damage_description')->nullable();
            $table->decimal('estimated_cost', 10, 2)->nullable();
            $table->enum('status', ['accepted', 'process', 'finished', 'taken', 'cancelled'])->default('accepted');
            $table->decimal('total_cost', 10, 2)->default(0);
            $table->decimal('other_cost', 10, 2)->default(0);
            $table->decimal('paid', 10, 2)->default(0);
            $table->decimal('change', 10, 2)->default(0);
            $table->string('paymentmethod')->nullable();
            $table->string('status_paid')->default('unpaid');
            $table->date('received_date')->nullable();
            $table->date('completed_date')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
