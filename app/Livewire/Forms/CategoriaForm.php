<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Categoria;
class CategoriaForm extends Form
{
    
    //Atributos de la categoria
    public $nombre;
    public $categoria_id;
    

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


    //Metodo para crear una categoria
    public function crearCategoria()
    {
        $this->validate();

        Categoria::create([
            'nombre' => $this->nombre,
        ]);
    }

    //Metodo para actualizar una categoria
    public function actualizarCategoria($id)
    {
        $this->validate();
        $categoria = Categoria::find($id);
        $categoria->update([
            'nombre' => $this->nombre,
        ]);
    }

    //Metodo para eliminar una categoria
    public function eliminarCategoria($id)
    {
        $categoria = Categoria::find($id);
        $categoria->delete();
    }
}
