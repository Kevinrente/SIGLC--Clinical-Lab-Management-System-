<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Paciente extends Model
{
    use HasFactory;

    protected $fillable = [
        'identificacion', 'nombre', 'apellido', 'fecha_nacimiento', 
        'sexo', 'telefono', 'email'
    ];

    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}