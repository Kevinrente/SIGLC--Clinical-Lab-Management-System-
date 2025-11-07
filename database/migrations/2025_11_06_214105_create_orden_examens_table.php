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
        Schema::create('orden_examens', function (Blueprint $table) {
            $table->id();
            
            // Relaciones de la Orden
            $table->foreignId('cita_id')->nullable()->constrained('citas')->onDelete('set null'); 
            $table->foreignId('doctor_id')->constrained('doctors')->onDelete('restrict');
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');
            
            // CORRECCIÓN CLAVE: Eliminamos 'examen_id' para flexibilidad.
            // Si quieres guardar una lista de IDs de exámenes, usarías una tabla pivote (muchos a muchos).
            // Para simplicidad, guardaremos la lista de exámenes como texto.
            $table->text('examenes_solicitados'); // Lista de exámenes (ej: "Glucosa, Hemograma")
            
            // Gestión del resultado PDF (Buenas Prácticas de Seguridad)
            $table->string('ruta_resultado_pdf')->nullable();
            $table->string('hash_integridad', 64)->nullable(); 
            
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
