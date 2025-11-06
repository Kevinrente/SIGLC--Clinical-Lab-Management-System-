<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany; // <-- ¡ESTA ES LA LÍNEA QUE FALTA!

class Examen extends Model
{
    use HasFactory;
    
    protected $fillable = ['nombre', 'codigo', 'precio', 'tiempo_entrega_dias'];

    // Puede ser parte de muchas órdenes
    public function ordenes(): HasMany // <-- Ahora el tipo HasMany está resuelto
    { 
        return $this->hasMany(OrdenExamen::class); 
    }
}


