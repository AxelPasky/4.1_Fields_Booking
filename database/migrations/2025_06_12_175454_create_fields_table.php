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
        Schema::create('fields', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->enum('type', ['tennis', 'padel', 'calcio', 'basket']); // Esempio di valori per l'ENUM, adattali se necessario
            $table->string('location', 255)->nullable();
            $table->decimal('price_per_hour', 8, 2); 
            $table->string('image', 255)->nullable(); 
            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fields');
    }
};
