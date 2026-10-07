<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Institucion;
class InstitucionForm extends Form
{
    //Atributos de la institucion
    public $nombre;
    public $visible;
    public $institucion_id;

    //Reglas de validacion
    public function rules()
    {
        return [
            'nombre' => 'required|string|max:255|min:3',
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
        ];
    }


    //Metodo para crear una institucion
    public function crearInstitucion()
    {
        $this->validate();

        Institucion::create([
            'nombre' => $this->nombre,
            'visible' => $this->visible ?? false,
        ]);
    }

    //Metodo para actualizar una institucion
    public function actualizarInstitucion($id)
    {
        $this->validate();

        $institucion = Institucion::find($id);
        $institucion->update([
            'nombre' => $this->nombre,
        ]);
    }

}
