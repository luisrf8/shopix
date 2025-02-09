<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id', // Agregamos role_id para asignación masiva
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Relación con el modelo Role.
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    /**
     * Obtén el identificador único del usuario para JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey(); // Retorna el ID del usuario
    }

    /**
     * Obtén las reclamaciones personalizadas que incluirás en el JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        if ($user) {
            $name = $user->name; // Solo accede a 'name' si $user no es null
        } else {
            // Lógica en caso de que el usuario no exista
        }
        return [
            'role' => $this->role->name, // Agrega el rol del usuario, puedes personalizar esto
        ];
    }
}
