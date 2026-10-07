<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Sede;
use App\Models\Institucion;

class SedeForm extends Form
{
    //Variables de sede
    public $nombre;
    public $visible;
    public $institucion_id = '';
    public $sede_id;

    
    //Reglas de validacion
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|min:3',
            'institucion_id' => 'required|exists:invent_rec_invest_cnr_institucions,id',
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
            'institucion_id.required' => 'La institucion es requerida',
            'institucion_id.exists' => 'La institucion no existe',
        ];
    }


    //Metodo para crear una institucion
    public function crearSede()
    {
        $this->validate();

        Sede::create([
            'nombre' => $this->nombre,
            'visible' => $this->visible ?? false,
            'institucion_id' => $this->institucion_id,
        ]);
    }

    //Metodo para actualizar una institucion
    public function actualizarSede($id)
    {
        $this->validate();

        $sede = Sede::find($id);
        $sede->update([
            'nombre' => $this->nombre,
            'institucion_id' => $this->institucion_id,
        ]);
    }

}
