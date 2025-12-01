<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consulta extends Model
{
    use HasFactory;

    protected $fillable = [
        'cita_id',
        'doctor_id',
        'paciente_id',
        'motivo_consulta',
        'exploracion_fisica',      // <--- NUEVO
        'diagnostico_presuntivo',  // <--- NUEVO
        'diagnostico_confirmado',  // <--- RENOMBRADO
        'receta_medica',           // <--- NUEVO (JSON)
        'pagado'
    ];

    // ESTO ES CLAVE PARA LA RECETA DINÁMICA
    protected $casts = [
        'receta_medica' => 'array', // Laravel convierte JSON <-> Array automáticamente
        'pagado' => 'boolean',
    ];

    public function paciente(): BelongsTo
    {
        return $this->belongsTo(Paciente::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function cita(): BelongsTo
    {
        return $this->belongsTo(Cita::class);
    }

    public function ordenesExamen(): HasMany 
    {
        return $this->hasMany(OrdenExamen::class);
    }

    // Relación con el Pago
    public function pago()
    {
        return $this->morphOne(Pago::class, 'payable');
    }
}