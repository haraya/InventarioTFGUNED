<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;
class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;
    use HasRoles;
    use LogsActivity;
    use SoftDeletes;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    #protected $table = 'invent_rec_invest_cnr_usuarios';
    protected $fillable = [
        'name',
        'email',
        'telefono',
        'rol_actual',
        'frase_recuperacion',
        'password',
        'institucion_id',
        'informacion_contacto_adicional',

    ];

    public function responsable_equipos()
    {
        return $this->hasMany(Equipo::class, 'user_id');
    }

    public function solicitudes()
    {
        return $this->hasOne(Solicitud::class, 'user_id');
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class, 'institucion_id');
    }

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
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

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó el usuario con ID: {$this->id}",
                    'updated' => "Se actualizó el usuario con ID: {$this->id}",
                    'deleted' => "Se eliminó el usuario con ID: {$this->id}",
                    default => $eventName
                };
            });
    }

}
