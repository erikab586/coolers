<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Traits\HasPermissions;

class User extends Authenticatable
{ 
    use HasFactory, Notifiable, HasPermissions;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */

    protected $fillable = [
        'name',
        'apellidos',
        'email',
        'telefono',
        'password',
        'idrol',
        'estatus',    //Corresponde a si un usuario es eliminado queda desactivos en caso contrario activo
        'fechaconexion',
    ];

   
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function rol()
    {
        return $this->belongsTo(RolUsuario::class, 'idrol');
    }

    public function coolers()
    {
        return $this->belongsToMany(Cooler::class, 'usuario_cooler', 'idusuario', 'idcooler')
                    ->withPivot('estatus')
                    ->withTimestamps();
    }

    public function contrato()
    {
        return $this->belongsTo(Contrato::class, 'idcontrato');
    }

    /**
     * Relación con permisos directos del usuario
     */
    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'permission_user', 'user_id', 'permission_id')
                    ->withTimestamps();
    }

}
