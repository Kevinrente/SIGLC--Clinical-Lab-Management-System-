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
        // En la función up()
        Schema::create('examens', function (Blueprint $table) {
            $table->id();
            $table->string('nombre')->unique();
            $table->string('codigo')->nullable();
            $table->decimal('precio', 8, 2);
            $table->integer('tiempo_entrega_dias')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('examens');
    }
};
