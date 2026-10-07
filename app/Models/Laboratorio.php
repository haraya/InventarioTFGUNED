<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Laboratorio extends Model
{
    use LogsActivity;

    protected $table = 'invent_rec_invest_cnr_laboratorios';
      public $fillable = [
        'nombre',
        'institucion_id',
        'sede_id',
        'visible',
    ];

    public function sede()  
    {
        return $this->belongsTo(Sede::class);
    }

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó el laboratorio con ID: {$this->id}",
                    'updated' => "Se actualizó el laboratorio con ID: {$this->id}",
                    'deleted' => "Se eliminó el laboratorio con ID: {$this->id}",
                    default => $eventName
                };
            });
    }


}
