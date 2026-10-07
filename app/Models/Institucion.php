<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Institucion extends Model
{
    use LogsActivity;

    protected $table = 'invent_rec_invest_cnr_institucions';
    public $fillable = [
        'nombre',
        'visible',
    ];

    public function sedes()
    {
        return $this->hasMany(Sede::class);
    }

    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class);
    }

    public function usuarios()
    {
        return $this->hasMany(User::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó la institución con ID: {$this->id}",
                    'updated' => "Se actualizó la institución con ID: {$this->id}",
                    'deleted' => "Se eliminó la institución con ID: {$this->id}",
                    default => $eventName
                };
            });
    }


}
