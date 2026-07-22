<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_outs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('check_in_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checked_out_by')->constrained('users')->cascadeOnDelete();
            $table->timestamp('actual_check_out');
            $table->decimal('mini_bar_charges', 12, 2)->default(0);
            $table->decimal('restaurant_charges', 12, 2)->default(0);
            $table->decimal('laundry_charges', 12, 2)->default(0);
            $table->decimal('damage_charges', 12, 2)->default(0);
            $table->decimal('other_charges', 12, 2)->default(0);
            $table->decimal('total_additional_charges', 12, 2)->default(0);
            $table->decimal('discount_amount', 12, 2)->default(0);
            $table->decimal('refund_amount', 12, 2)->default(0);
            $table->decimal('final_amount', 12, 2);
            $table->text('room_condition')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_outs');
    }
};
