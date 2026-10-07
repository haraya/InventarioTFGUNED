<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\SoftDeletes;

class Equipo extends Model
{
    use LogsActivity;
    use SoftDeletes;
    protected $table = 'invent_rec_invest_cnr_equipos';
    protected $fillable = [
        'numero_activo',
        'nombre', 
        'marca',
        'modelo',
        'serie',
        'estado',
        'fecha_adquisicion',
       'categoria_id',
       'laboratorio_id',
       'responsable_id',
       'caracteristicas',
       'condiciones_uso'
    ];

    public function solicitud()
    {
        return $this->hasOne(Solicitud::class);
    }

    public function categoria()
    {
        return $this->belongsTo(Categoria::class)->withTrashed();
    }

    public function laboratorio()
    {
        return $this->belongsTo(Laboratorio::class);
    }

    public function responsable()
    {
        return $this->belongsTo(User::class)->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó el equipo con ID: {$this->id}",
                    'updated' => "Se actualizó el equipo con ID: {$this->id}",
                    'deleted' => "Se eliminó el equipo con ID: {$this->id}",
                    default => $eventName
                };
            });
    }

}
