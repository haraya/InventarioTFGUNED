<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;



class Categoria extends Model
{
    // Para el log de actividades
    use LogsActivity;

    // Para el soft delete registro eliminado
    use SoftDeletes;


    // Para la tabla
    protected $table = 'invent_rec_invest_cnr_categoria';

    // Para los campos que se pueden llenar
    protected $fillable = ['nombre'];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->logOnlyDirty()
            ->dontSubmitEmptyLogs()
            ->setDescriptionForEvent(function(string $eventName) {
                return match($eventName) {
                    'created' => "Se creó la categoría con ID: {$this->id}",
                    'updated' => "Se actualizó la categoría con ID: {$this->id}",
                    'deleted' => "Se eliminó la categoría con ID: {$this->id}",
                    default => $eventName
                };
            });
    }

    public function equipos()
    {
        return $this->hasMany(Equipo::class, 'categoria_id');

    }
}
