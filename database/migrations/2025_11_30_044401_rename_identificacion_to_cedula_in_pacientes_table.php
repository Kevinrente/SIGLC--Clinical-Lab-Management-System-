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
        Schema::table('pacientes', function (Blueprint $table) {
            // Renombramos la columna vieja a 'cedula'
            $table->renameColumn('identificacion', 'cedula');
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Para revertir el cambio si fuera necesario
            $table->renameColumn('cedula', 'identificacion');
        });
    }
};
