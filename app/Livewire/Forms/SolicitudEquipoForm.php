<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Solicitud;
use Illuminate\Support\Facades\Auth;
use App\Models\Equipo;

class SolicitudEquipoForm extends Form
{
    //Atributos para la solicitud de equipo
    public $fecha_solicitud;
    public $fecha_aproximada_devolucion;


    //Atributos para la solicitud de equipo
    public $observaciones;
    public $id_solicitud;
    public $estado_solicitud;
    public $fecha_exacta_devolucion;

    //Atributos para la advertencia
    public $advertencia_estados;


    //Atributos para el equipo 
    public $equipo_id;
    public $equipo_estado;



    //Reglas de validacion
    public function rules()
    {
        return [
            'fecha_solicitud' => ['required', 'date', function ($attribute, $value, $fail) {
                if (date('Y', strtotime($value)) != date('Y')) {
                    $fail('La fecha de solicitud debe ser del año actual.');
                }
            }],
            'fecha_aproximada_devolucion' => ['required', 'date', 'after_or_equal:fecha_solicitud', function ($attribute, $value, $fail) {
                $yearDevolucion = date('Y', strtotime($value));
                $yearActual = date('Y');
                if ($yearDevolucion < $yearActual || $yearDevolucion > ($yearActual + 1)) {
                    $fail('La fecha de devolución aproximada debe ser del año actual o posterior.');
                }
            }],
        ];
    }

    //Mensajes de validacion
    public function messages()
    {
        return [
            'fecha_solicitud.required' => 'La fecha de solicitud es requerida',
            'fecha_solicitud.date' => 'La fecha de solicitud debe ser una fecha válida',
            'fecha_aproximada_devolucion.required' => 'La fecha de devolución es requerida',
            'fecha_aproximada_devolucion.date' => 'La fecha de devolución debe ser una fecha válida',
            'fecha_aproximada_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de solicitud',
        ];
    }

    public function crearSolicitud($equipo_id)
    {
        $this->validate();

        Solicitud::create([
            'user_id' => Auth::user()->id,
            'equipo_id' => $equipo_id,
            'fecha_solicitud' => $this->fecha_solicitud,
            'fecha_aproximada_devolucion' => $this->fecha_aproximada_devolucion,
        ]);
    }

    public function actualizarSolicitud($id, $estado_equipo)
    {
        $solicitud = Solicitud::find($id);

        $this->validate([
            'fecha_exacta_devolucion' => ['nullable', 'date', 'after_or_equal:' . $solicitud->fecha_solicitud, function ($attribute, $value, $fail) {
                if (date('Y', strtotime($value)) != date('Y')) {
                    $fail('La fecha de devolución debe ser del año actual.');
                }
            }],
        ], [

            'fecha_exacta_devolucion.after_or_equal' => 'La fecha de devolución debe ser igual o posterior a la fecha de solicitud',
            'fecha_exacta_devolucion.date' => 'La fecha de devolución debe ser una fecha válida',
        ]);

        $solicitud->update([
            'fecha_exacta_devolucion' => $this->fecha_exacta_devolucion ?: null,
            'estado_solicitud' => $this->estado_solicitud,
            'observaciones' => $this->observaciones,
        ]);

         #Cambiar el estado del equipo 
         $equipo = Equipo::find($solicitud->equipo_id);
         $equipo->update([
             'estado' => $estado_equipo,
         ]);
         $equipo->save();
    }

    public function CambiarEstadoSolicitud($id, $estado_Solic, $estado_Equipo)
    {
        $solicitud = Solicitud::find($id);
        $solicitud->update([
            'estado_solicitud' => $estado_Solic,
        ]);
        $solicitud->save();

        #Cambiar el estado del equipo 
        $equipo = Equipo::find($solicitud->equipo_id);
        $equipo->update([
            'estado' => $estado_Equipo,
        ]);
        $equipo->save();
    }



 


}
