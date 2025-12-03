<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Pago extends Model
{
    protected $fillable = [
        'caja_sesion_id', // <--- NUEVO: Para saber de qué turno es el dinero
        'payable_id', 
        'payable_type', 
        'monto_total', 
        'metodo_pago', 
        'referencia', 
        'cajero_id'
    ];

    // Esta función permite acceder al "padre" (sea Consulta u Orden)
    public function payable(): MorphTo
    {
        return $this->morphTo();
    }

    public function cajero(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cajero_id');
    }

    // NUEVA RELACIÓN: Con la Sesión de Caja
    public function cajaSesion(): BelongsTo
    {
        return $this->belongsTo(CajaSesion::class, 'caja_sesion_id');
    }
}