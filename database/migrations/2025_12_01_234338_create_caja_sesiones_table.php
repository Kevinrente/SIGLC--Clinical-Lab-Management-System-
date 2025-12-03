<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('caja_sesiones', function (Blueprint $table) {
            $table->id();
            
            $table->foreignId('user_id')->constrained('users'); // Quién abrió la caja
            
            // Valores de Apertura
            $table->decimal('monto_inicial', 10, 2)->default(0); // Con cuánto cambio empezó
            $table->dateTime('fecha_apertura');
            
            // Valores de Cierre (Se llenan al final)
            $table->dateTime('fecha_cierre')->nullable();
            
            // Resumen del turno (Para no tener que recalcular todo el tiempo)
            $table->decimal('total_ingresos', 10, 2)->default(0); // Total vendido
            $table->decimal('total_egresos', 10, 2)->default(0);  // Gastos (luz, agua...)
            
            // El Arqueo (La prueba de la verdad)
            $table->decimal('saldo_esperado', 10, 2)->nullable(); // (Inicial + Ingresos - Egresos)
            $table->decimal('saldo_real', 10, 2)->nullable();     // Lo que contó la cajera
            $table->decimal('diferencia', 10, 2)->nullable();     // (Real - Esperado). Si es negativo, falta dinero.
            
            $table->string('estado')->default('Abierta'); // 'Abierta', 'Cerrada'
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('caja_sesiones');
    }
};
