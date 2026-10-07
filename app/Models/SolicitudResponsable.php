<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SolicitudResponsable extends Model
{
    //
    protected $table = 'invent_rec_invest_cnr_solict_responsables';

    protected $fillable = [
        'fecha_solicitud',
        'respon_id',
        'estado',
        'fecha_aprobacion',
    ];

    public function responsable()
    {
        return $this->belongsTo(User::class, 'respon_id');
    }
}
