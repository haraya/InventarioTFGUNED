<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class Solicitud extends Model
{
    use LogsActivity;
    protected $table = 'invent_rec_invest_cnr_solicituds';
    protected $fillable = [
        'fecha_solicitud',
        'fecha_aproximada_devolucion',
        'fecha_exacta_devolucion',
        'estado_solicitud',
        'equipo_id',
        'user_id',
        'observaciones'
    ];

    public function equipo()
    {
        return $this->belongsTo(Equipo::class, 'equipo_id')->withTrashed();
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withTrashed();
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó la solicitud con ID: {$this->id}",
                    'updated' => "Se actualizó la solicitud con ID: {$this->id}",
                    'deleted' => "Se eliminó la solicitud con ID: {$this->id}",
                    default => $eventName
                };
            });
    }

}
