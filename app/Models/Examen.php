<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Examen extends Model
{
    use HasFactory;

    protected $casts = [
    'campos_dinamicos' => 'array', // Convertirá el JSON a Array PHP
    ];

    protected $fillable = [
        'nombre', 'categoria', 'precio', 
        'unidades', 'valor_referencia', 'campos_dinamicos' // <--- Agregados
    ];

    /**
     * Relación muchos a muchos con OrdenExamen.
     * Un examen puede estar presente en múltiples órdenes.
     */
    public function ordenes(): BelongsToMany
    {
        return $this->belongsToMany(OrdenExamen::class, 'orden_examen_examen');
    }

    // Relación: Qué insumos gasta este examen
    public function insumos()
    {
        return $this->belongsToMany(Insumo::class, 'examen_insumo')
                    ->withPivot('cantidad_necesaria');
    }
}


