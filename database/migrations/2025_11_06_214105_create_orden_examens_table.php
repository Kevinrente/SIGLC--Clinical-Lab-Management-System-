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
        Schema::create('orden_examens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('restrict');
            $table->foreignId('consulta_id')->nullable()->constrained()->onDelete('set null'); // De qué consulta proviene
            $table->foreignId('examen_id')->constrained()->onDelete('restrict'); // Qué examen es
            
            // Gestión del resultado PDF
            $table->string('ruta_resultado_pdf')->nullable(); // Almacenamiento seguro
            $table->string('hash_integridad', 64)->nullable(); // Para auditoría
            
            $table->enum('estado', ['Solicitado', 'Muestra Tomada', 'En Análisis', 'Finalizado'])->default('Solicitado');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orden_examens');
    }
};
