<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Gasto extends Model
{
    protected $fillable = ['caja_sesion_id', 'descripcion', 'monto', 'comprobante', 'user_id'];

    public function cajaSesion()
    {
        return $this->belongsTo(CajaSesion::class);
    }

    public function usuario()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}