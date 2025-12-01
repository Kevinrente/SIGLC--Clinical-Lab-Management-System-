<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    // 1. Agrega 'user_id' aquí
    protected $fillable = [
        'user_id',          // <--- El que agregamos para el login
        'nombre',
        'apellido',
        'cedula',           // <--- La que renombramos
        'fecha_nacimiento',
        'email',
        'sexo',             // <--- FALTABA ESTE
        'telefono',         // <--- FALTABA ESTE
        'direccion',        // (Agrégalo si lo tienes en tu formulario)
        'grupo_sanguineo',  // (Agrégalo si lo tienes en tu formulario)
    ];

    // ... otras relaciones ...

    // 2. Agrega esta relación
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}