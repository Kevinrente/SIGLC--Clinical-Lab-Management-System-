<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Doctor extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'apellido', 'licencia_medica', 'especialidad', 'user_id'
    ];

    public function usuario(): BelongsTo
    {
        // Relación uno a uno con la tabla Users para el login
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function citas(): HasMany
    {
        return $this->hasMany(Cita::class);
    }

    public function consultas(): HasMany
    {
        return $this->hasMany(Consulta::class);
    }
}
