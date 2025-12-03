<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
{
    Schema::create('web_configs', function (Blueprint $table) {
        $table->id();
        $table->string('key')->unique();      // Ej: 'titulo_principal'
        $table->text('value')->nullable();    // Ej: 'Cuidado Experto...'
        $table->string('label')->nullable();  // Ej: 'Título del Hero' (Para que el admin sepa qué edita)
        $table->string('type')->default('text'); // 'text', 'textarea', 'image'
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('web_configs');
    }
};
