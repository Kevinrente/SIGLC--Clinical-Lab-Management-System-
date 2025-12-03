<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('gastos', function (Blueprint $table) {
            $table->id();
            
            // Vinculamos el gasto a la sesión de caja actual
            $table->foreignId('caja_sesion_id')->constrained('caja_sesiones')->onDelete('cascade');
            
            $table->string('descripcion'); // Ej: "Compra de cloro"
            $table->decimal('monto', 10, 2); // Ej: 5.50
            $table->string('comprobante')->nullable(); // Nro de factura o recibo (opcional)
            
            $table->foreignId('user_id')->constrained('users'); // Quién registró el gasto
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('gastos');
    }
};
