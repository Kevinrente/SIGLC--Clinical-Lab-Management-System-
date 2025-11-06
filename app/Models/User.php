<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
// Importaciones requeridas
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasOne;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasRoles; // <-- AÑADIR HasRoles

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // ... (rest of the code remains the same: $hidden, casts, etc.)

    // --- RELACIÓN AÑADIDA ---
    
    /**
     * Un usuario puede ser un doctor.
     */
    public function doctor(): HasOne
    {
        // La clave foránea 'user_id' está en la tabla 'doctors'
        return $this->hasOne(Doctor::class); 
    }
}
