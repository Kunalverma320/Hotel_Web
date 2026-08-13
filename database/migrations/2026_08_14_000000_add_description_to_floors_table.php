<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('floors', 'description')) {
            Schema::table('floors', function (Blueprint $table) {
                $table->text('description')->nullable()->after('floor_number');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('floors', 'description')) {
            Schema::table('floors', function (Blueprint $table) {
                $table->dropColumn('description');
            });
        }
    }
};
