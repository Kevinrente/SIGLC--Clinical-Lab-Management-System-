<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            // Creamos la columna user_id
            // Debe ser 'nullable' porque ya tienes pacientes creados sin usuario
            $table->foreignId('user_id')
                  ->nullable() 
                  ->after('id') // Para que quede ordenada al inicio
                  ->constrained('users')
                  ->onDelete('set null'); // Si borras el usuario, no borramos el historial médico
        });
    }

    public function down(): void
    {
        Schema::table('pacientes', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
        });
    }
};