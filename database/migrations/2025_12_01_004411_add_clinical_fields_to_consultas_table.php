<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            // 1. Agregar los nuevos campos
            $table->text('exploracion_fisica')->nullable()->after('motivo_consulta');
            $table->string('diagnostico_presuntivo')->nullable()->after('motivo_consulta');
            $table->jsonb('receta_medica')->nullable()->after('pagado');

            // 2. Renombrar diagnostico (Verificamos si existe la columna vieja antes de renombrar)
            if (Schema::hasColumn('consultas', 'diagnostico')) {
                $table->renameColumn('diagnostico', 'diagnostico_confirmado');
            } else {
                // Si no existe la vieja, creamos la nueva directamente
                $table->string('diagnostico_confirmado')->nullable();
            }

            // 3. LA LÍNEA QUE DABA ERROR LA ELIMINAMOS
            // $table->dropColumn('tratamiento_notas'); <--- BORRADA
        });
    }

    public function down(): void
    {
        Schema::table('consultas', function (Blueprint $table) {
            $table->renameColumn('diagnostico_confirmado', 'diagnostico');
            $table->dropColumn(['exploracion_fisica', 'diagnostico_presuntivo', 'receta_medica']);
            $table->text('tratamiento_notas')->nullable();
        });
    }
};