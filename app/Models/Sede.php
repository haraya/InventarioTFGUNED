<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Sede extends Model
{
    use LogsActivity;

    protected $table = 'invent_rec_invest_cnr_sedes';
    public $fillable = [
        'nombre',
        'visible',
        'institucion_id',
    ];

    public function institucion()
    {
        return $this->belongsTo(Institucion::class);
    }

    public function laboratorios()
    {
        return $this->hasMany(Laboratorio::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó la sede con ID: {$this->id}",
                    'updated' => "Se actualizó la sede con ID: {$this->id}",
                    'deleted' => "Se eliminó la sede con ID: {$this->id}",
                    default => $eventName
                };
            });
    }



}
