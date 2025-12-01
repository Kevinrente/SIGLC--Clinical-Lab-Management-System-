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
            $table->string('nombre'); // Ej: Hemograma Completo
            $table->string('categoria'); // Ej: Hematología, Bioquímica
            $table->decimal('precio', 8, 2)->nullable(); // Opcional
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
