<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            $table->boolean('is_available')->default(true)->after('image'); // Adds the column
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('fields', function (Blueprint $table) {
            if (Schema::hasColumn('fields', 'is_available')) {
                $table->dropColumn('is_available'); // Removes the column only if it exists
            }
        });
    }
};
