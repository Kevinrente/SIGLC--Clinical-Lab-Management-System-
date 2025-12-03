<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // CORRECCIÓN: El nombre de la tabla es 'orden_examens' (sin la 'e')
        Schema::table('orden_examens', function (Blueprint $table) {
            
            if (!Schema::hasColumn('orden_examens', 'analisis_ia')) {
                $table->text('analisis_ia')->nullable()->after('estado');
            }
            
            if (!Schema::hasColumn('orden_examens', 'ruta_resultado_pdf')) {
                $table->string('ruta_resultado_pdf')->nullable();
                $table->string('hash_integridad')->nullable();
            }
        });
    }

    public function down()
    {
        Schema::table('orden_examens', function (Blueprint $table) {
            $table->dropColumn(['analisis_ia', 'ruta_resultado_pdf', 'hash_integridad']);
        });
    }

    
};
