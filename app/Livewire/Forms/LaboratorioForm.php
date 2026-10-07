<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Sede;
use App\Models\Institucion;
use App\Models\Laboratorio;

class LaboratorioForm extends Form
{

    //Variables de laboratorio
    public $nombre;
    public $visible;
    public $institucion_id = '';
    public $sede_id = '';

    //Variable de laboratorio para editar
    public $lab_edit_id;


    //Reglas de validacion
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|min:3',
            /*'institucion_id' => 'required|exists:invent_rec_invest_cnr_institucions,id',
            'sede_id' => 'required|exists:invent_rec_invest_cnr_sedes,id',*/
            'institucion_id' => 'required_without:sede_id|nullable',
            'sede_id' => 'required_without:institucion_id|nullable',
        ];
    }

    //Mensajes de validacion
    public function messages()
    {
        return [
            'nombre.required' => 'El nombre es requerido',
            'nombre.string' => 'El nombre debe ser un texto',
            'nombre.max' => 'El nombre debe tener maximo 255 caracteres',
            'nombre.min' => 'El nombre debe tener minimo 3 caracteres',
            'institucion_id.required_without' => 'Debe seleccionar una institución o una sede',
            'sede_id.required_without' => 'Debe seleccionar una institución o una sede',

        ];
    }


    //Metodo para crear un laboratorio
    public function crearLaboratorio()
    {
        $this->validate();
        $institucion_id = $this->institucion_id === '' ? null : $this->institucion_id;
        $sede_id = $this->sede_id === '' ? null : $this->sede_id;

        Laboratorio::create([
            'nombre' => $this->nombre,
            'visible' => $this->visible ?? false,
            'institucion_id' => $institucion_id,
            'sede_id' => $sede_id,
        ]);
    }

    //Metodo para actualizar un laboratorio
    public function actualizarLaboratorio($id)
    {
        $this->validate();
        $institucion_id = $this->institucion_id === '' ? null : $this->institucion_id;
        $sede_id = $this->sede_id === '' ? null : $this->sede_id;

        $laboratorio = Laboratorio::find($id);
        $laboratorio->update([
            'nombre' => $this->nombre,
            'institucion_id' => $institucion_id,
            'sede_id' => $sede_id,
        ]);
    }
}
