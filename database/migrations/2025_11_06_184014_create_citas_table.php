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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('restrict'); // No eliminar doctor si tiene citas
            $table->dateTime('fecha_hora');
            $table->string('motivo')->nullable();
            $table->enum('estado', ['Pendiente', 'Confirmada', 'Cancelada', 'Completada'])->default('Pendiente');
            $table->timestamps();
            
            // Índice único para evitar doble reserva a la misma hora por el mismo doctor
            $table->unique(['doctor_id', 'fecha_hora']); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
