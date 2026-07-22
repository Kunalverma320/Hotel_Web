<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('room_types', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('room_category_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('slug');
            $table->text('description')->nullable();
            $table->text('short_description')->nullable();
            $table->decimal('base_rate', 12, 2);
            $table->decimal('weekend_rate', 12, 2)->nullable();
            $table->decimal('holiday_rate', 12, 2)->nullable();
            $table->tinyInteger('max_adults')->default(2);
            $table->tinyInteger('max_children')->default(1);
            $table->tinyInteger('max_occupancy')->default(3);
            $table->decimal('extra_bed_rate', 12, 2)->nullable();
            $table->decimal('child_rate', 12, 2)->nullable();
            $table->string('bed_type')->nullable();
            $table->decimal('room_size', 8, 2)->nullable();
            $table->decimal('floor_area', 8, 2)->nullable();
            $table->enum('smoking', ['smoking', 'non_smoking', 'both'])->default('non_smoking');
            $table->boolean('balcony')->default(false);
            $table->boolean('sea_view')->default(false);
            $table->boolean('mountain_view')->default(false);
            $table->boolean('garden_view')->default(false);
            $table->string('image')->nullable();
            $table->boolean('status')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('room_types');
    }
};
