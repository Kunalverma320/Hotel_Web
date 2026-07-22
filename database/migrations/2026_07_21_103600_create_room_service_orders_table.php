<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_service_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('kitchen_order_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('status', ['pending', 'preparing', 'delivered', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('delivery_time')->nullable();
            $table->foreignId('delivery_by')->nullable()->constrained('users')->nullOnDelete();
            $table->decimal('total_amount', 12, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_service_orders');
    }
};
