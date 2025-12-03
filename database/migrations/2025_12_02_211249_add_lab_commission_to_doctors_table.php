<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            // 'porcentaje' (ej: 15%) o 'fijo' (ej: $5.00 por orden)
            $table->string('comision_lab_tipo')->default('porcentaje'); 
            
            // El valor numérico (15 o 5.00)
            $table->decimal('comision_lab_valor', 10, 2)->default(0); 
        });
    }

    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn(['comision_lab_tipo', 'comision_lab_valor']);
        });
    }
};
