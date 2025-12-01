<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Modificar la tabla 'examens' (Catálogo)
        // Agregamos datos fijos para que salgan automáticos en el PDF
        Schema::table('examens', function (Blueprint $table) {
            $table->string('unidades', 50)->nullable(); // Ej: "mg/dL", "Ery/Ul"
            $table->string('valor_referencia', 100)->nullable(); // Ej: "70 - 110", "Negativo"
            
            // Si el examen es complejo (como Orina), aquí definimos qué campos pedir
            // Ej: ["Color", "Aspecto", "PH", "Nitritos"]
            $table->jsonb('campos_dinamicos')->nullable(); 
        });

        // 2. Modificar la tabla pivote 'orden_examen_examen' (Resultados)
        Schema::table('orden_examen_examen', function (Blueprint $table) {
            // Usamos JSONB porque Postgres es experto en esto.
            // Aquí guardaremos: {"valor": "100"} o {"Color": "Amarillo", "PH": "5.0"}
            $table->jsonb('resultado')->nullable(); 
            
            $table->text('observaciones')->nullable(); // Para notas al pie del examen
        });
    }

    public function down(): void
    {
        Schema::table('examens', function (Blueprint $table) {
            $table->dropColumn(['unidades', 'valor_referencia', 'campos_dinamicos']);
        });

        Schema::table('orden_examen_examen', function (Blueprint $table) {
            $table->dropColumn(['resultado', 'observaciones']);
        });
    }
};
