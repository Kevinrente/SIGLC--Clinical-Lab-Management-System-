<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne; // <-- IMPORTACIÓN AGREGADA

class Cita extends Model
{
    use HasFactory;
    
    // ... (propiedad fillable y casts, si las hubiere) ...
    
    /**
     * The attributes that are mass assignable.
     * @var array<int, string>
     */
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'fecha_hora',
        'motivo',
        'estado',
    ];
    
    // El casteo es crucial para que la vista show funcione sin errores de tipo
    protected $casts = [
        'fecha_hora' => 'datetime',
    ];

    // -------------------------------------------------------------------
    // RELACIONES
    // -------------------------------------------------------------------

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class, 'paciente_id');
    }
    
    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
    
    /**
     * Una cita puede generar múltiples órdenes de examen.
     */
    public function ordenesExamen(): HasMany
    {
        return $this->hasMany(OrdenExamen::class, 'cita_id');
    }
    
    // -------------------------------------------------------------------
    // NUEVA RELACIÓN: CONSULTA (SOLUCIÓN para "Ver Consulta")
    // -------------------------------------------------------------------
    /**
     * Una cita tiene una consulta asociada (si la consulta ya fue registrada).
     */
    public function consulta(): HasOne
    {
        // Asumiendo que la llave foránea es 'cita_id' en la tabla 'consultas'
        return $this->hasOne(Consulta::class, 'cita_id');
    }
}