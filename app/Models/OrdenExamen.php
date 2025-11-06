<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo; // <-- ADD THIS LINE!

class OrdenExamen extends Model
{
    use HasFactory;
    
    protected $fillable = ['paciente_id', 'doctor_id', 'consulta_id', 'examen_id', 'ruta_resultado_pdf', 'hash_integridad', 'estado'];

    public function paciente(): BelongsTo // <-- Now resolved
    { 
        return $this->belongsTo(Paciente::class); 
    }
    
    public function doctor(): BelongsTo // <-- Now resolved
    { 
        return $this->belongsTo(Doctor::class); 
    }
    
    public function consulta(): BelongsTo // <-- Now resolved
    { 
        return $this->belongsTo(Consulta::class); 
    }
    
    public function examen(): BelongsTo // <-- Now resolved
    { 
        return $this->belongsTo(Examen::class); 
    }
}
