<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CajaSesion extends Model
{
    protected $table = 'caja_sesiones';

    protected $fillable = [
        'user_id',
        'monto_inicial',
        'fecha_apertura',
        'fecha_cierre',
        'total_ingresos',
        'total_egresos',
        'saldo_esperado',
        'saldo_real',
        'diferencia',
        'estado'
    ];

    protected $casts = [
        'fecha_apertura' => 'datetime',
        'fecha_cierre' => 'datetime',
    ];

    // Relación: Una sesión tiene muchos pagos recibidos
    public function pagos()
    {
        return $this->hasMany(Pago::class, 'caja_sesion_id');
    }

    // Relación: El usuario responsable
    public function cajero()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relación con los gastos de este turno
    public function gastos()
    {
        return $this->hasMany(Gasto::class, 'caja_sesion_id');
    }
}