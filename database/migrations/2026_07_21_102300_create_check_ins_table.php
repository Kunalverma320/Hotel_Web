<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('guest_id')->constrained()->cascadeOnDelete();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('checked_in_by')->constrained('users')->cascadeOnDelete();
            $table->string('key_card_number')->nullable();
            $table->enum('key_card_status', ['active', 'deactivated'])->default('active');
            $table->string('verification_document')->nullable();
            $table->string('verification_type')->nullable();
            $table->string('signature_image')->nullable();
            $table->timestamp('actual_check_in');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_ins');
    }
};
