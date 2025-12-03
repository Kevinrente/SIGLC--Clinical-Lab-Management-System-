<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Insumo extends Model
{
    protected $fillable = ['nombre', 'unidad_medida', 'stock_actual', 'stock_minimo'];

    // Relación con los exámenes que usan este insumo
    public function examenes()
    {
        return $this->belongsToMany(Examen::class, 'examen_insumo')
                    ->withPivot('cantidad_necesaria');
    }
}
