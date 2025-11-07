<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrdenExamen extends Model
{
    use HasFactory;
    
    /**
     * Los atributos que se pueden asignar masivamente.
     * Reflejan la última versión de la migración.
     */
    protected $fillable = [
        'paciente_id',
        'doctor_id',
        'cita_id',                   // <-- CORREGIDO: Usamos cita_id
        'examenes_solicitados',      // <-- CORREGIDO: Usamos el campo de texto
        'ruta_resultado_pdf', 
        'hash_integridad', 
        'estado',
    ];
    
    // -------------------------------------------------------------------
    // RELACIONES (Asegurando la consistencia con la DB)
    // -------------------------------------------------------------------

    public function paciente(): BelongsTo 
    { 
        return $this->belongsTo(Paciente::class); 
    }
    
    public function doctor(): BelongsTo 
    { 
        return $this->belongsTo(Doctor::class); 
    }
    
    public function cita(): BelongsTo // <-- CORREGIDO: Relación a Cita
    { 
        return $this->belongsTo(Cita::class); 
    }
    
    // Eliminada la relación 'examen' porque ya no existe el campo 'examen_id'
}
