<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Agregar precio base al perfil del Doctor
        Schema::table('doctors', function (Blueprint $table) {
            $table->decimal('precio_consulta', 10, 2)->default(30.00); // Precio por defecto
        });

        // 2. Agregar estado de pago a la tabla de Consultas
        Schema::table('consultas', function (Blueprint $table) {
            $table->boolean('pagado')->default(false);
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('precio_consulta');
        });
        Schema::table('consultas', function (Blueprint $table) {
            $table->dropColumn('pagado');
        });
    }
};
