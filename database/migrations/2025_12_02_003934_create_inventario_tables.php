<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Tabla de Insumos (Lo que compras)
        Schema::create('insumos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Reactivo Glucosa
            $table->string('unidad_medida'); // ml, unidades, gramos
            $table->decimal('stock_actual', 10, 2)->default(0);
            $table->decimal('stock_minimo', 10, 2)->default(10); // Alerta
            $table->timestamps();
        });

        // 2. Tabla Receta (Relación Examen -> Insumos)
        Schema::create('examen_insumo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examen_id')->constrained('examens')->onDelete('cascade');
            $table->foreignId('insumo_id')->constrained('insumos')->onDelete('cascade');
            $table->decimal('cantidad_necesaria', 10, 2); // Cuánto gasta 1 examen (ej: 0.5)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examen_insumo');
        Schema::dropIfExists('insumos');
    }
};
