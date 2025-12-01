<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // En PostgreSQL, alteramos la columna para permitir nulos
        DB::statement('ALTER TABLE orden_examens ALTER COLUMN doctor_id DROP NOT NULL');
    }

    public function down(): void
    {
        // Para revertir: volvemos a poner que NO acepte nulos
        // (Esto fallaría si hay datos nulos, pero es la reversión lógica)
        DB::statement('ALTER TABLE orden_examens ALTER COLUMN doctor_id SET NOT NULL');
    }
};
