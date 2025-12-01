<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();

            // MAGIA POLIMÓRFICA:
            // Esto crea automáticamente dos columnas:
            // 1. payable_id (El ID de la orden o consulta: ej. 5)
            // 2. payable_type (El modelo: "App\Models\OrdenExamen" o "App\Models\Consulta")
            $table->morphs('payable'); 

            // Datos del cobro
            $table->decimal('monto_total', 10, 2);
            $table->string('metodo_pago'); // Efectivo, Transferencia, Tarjeta
            $table->string('referencia')->nullable(); // Nro comprobante

            // Quién cobró
            $table->foreignId('cajero_id')->constrained('users');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};