<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OrdenExamen extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'cita_id', 'doctor_id', 'paciente_id', 'estado', 
        'examenes_solicitados', // <--- Asegúrate de que esté aquí
        'ruta_resultado_pdf', 'hash_integridad'
    ];
    
    // -------------------------------------------------------------------
    // RELACIONES EXISTENTES
    // -------------------------------------------------------------------

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

    // -------------------------------------------------------------------
    // NUEVA RELACIÓN: CATÁLOGO DE EXÁMENES
    // -------------------------------------------------------------------
    
    /**
     * Una orden puede contener múltiples exámenes del catálogo.
     * Esta es la relación que usaremos para guardar los checkboxes seleccionados.
     */
    public function examenes()
    {
        return $this->belongsToMany(Examen::class, 'orden_examen_examen')
                    ->withPivot(['resultado', 'observaciones', 'id']) // <--- Importante pedir estos datos
                    ->withTimestamps();
    }

    // Relación con el Pago
    public function pago()
    {
        return $this->morphOne(Pago::class, 'payable');
    }
}