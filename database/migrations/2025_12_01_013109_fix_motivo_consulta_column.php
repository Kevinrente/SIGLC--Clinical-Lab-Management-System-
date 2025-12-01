<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            // Opción A: Si existe 'sintomas', la renombramos a 'motivo_consulta'
            if (Schema::hasColumn('consultas', 'sintomas')) {
                $table->renameColumn('sintomas', 'motivo_consulta');
            } 
            // Opción B: Si no existe ninguna, creamos 'motivo_consulta'
            elseif (!Schema::hasColumn('consultas', 'motivo_consulta')) {
                $table->text('motivo_consulta')->nullable();
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            // Revertir cambios (volver a sintomas)
            if (Schema::hasColumn('consultas', 'motivo_consulta')) {
                $table->renameColumn('motivo_consulta', 'sintomas');
            }
        });
    }
};
