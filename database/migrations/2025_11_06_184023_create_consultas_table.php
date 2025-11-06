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
        Schema::create('consultas', function (Blueprint $table) {
            $table->id();
            // Relación con la cita (opcional, pero ayuda a la trazabilidad)
            $table->foreignId('cita_id')->nullable()->constrained()->onDelete('set null');
            // Relación directa al doctor y paciente por seguridad
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained()->onDelete('restrict');
            
            $table->text('sintomas')->nullable();
            $table->text('diagnostico');
            $table->text('tratamiento')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultas');
    }
};
