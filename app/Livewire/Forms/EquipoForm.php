<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\Equipo;
use Illuminate\Support\Facades\Auth;
class EquipoForm extends Form
{
    //Atributos del formulario de equipo
    public $numero_activo;
    public $nombre;
    public $marca;
    public $modelo;
    public $serie;
    public $estado = '';
    public $fecha_adquisicion;
    public $categoria_id = '';
    public $laboratorio_id = '';
    public $responsable_id = '';
    public $caracteristicas;
    public $condiciones_uso;

    //Variable del equipo_id
    public $equipo_id;

    //Reglas de validación
    public function rules()
    {
        return [
            'numero_activo' => 'required|string|max:255|min:2',
            'nombre' => 'required|string|max:255|min:3',
            'marca' => 'required|string|max:255|min:2',
           'categoria_id' => 'nullable|exists:invent_rec_invest_cnr_categoria,id',
            'laboratorio_id' => 'nullable|exists:invent_rec_invest_cnr_laboratorios,id',
            'responsable_id' => 'required|nullable|exists:users,id',
            'estado' => 'required',
        ];
    }

    //Mensajes de validación
    public function messages()
    {
        return [
            'numero_activo.required' => 'El numero de activo es requerido',
            'numero_activo.max' => 'El numero de activo debe tener maximo 255 caracteres',
            'numero_activo.min' => 'El numero de activo debe tener minimo 2 caracteres',
            'nombre.required' => 'El nombre es requerido',
            'nombre.max' => 'El nombre debe tener maximo 255 caracteres',
            'nombre.min' => 'El nombre debe tener minimo 3 caracteres',
            'marca.required' => 'La marca es requerida',
            'marca.max' => 'La marca debe tener maximo 255 caracteres',
            'marca.min' => 'La marca debe tener minimo 3 caracteres',
            'categoria_id.exists' => 'La categoria no existe',
            'laboratorio_id.exists' => 'El laboratorio no existe',
            'responsable_id.exists' => 'El responsable no existe',
            'responsable_id.required' => 'El responsable es requerido',
            'estado.required' => 'El estado es requerido',
        ];
    }

    //Método para crear un equipo
    public function crearEquipo()
    {

        if (Auth::user()->rol_actual == 'Responsable del activo') {
            $this->responsable_id = Auth::user()->id;
            #$resp_id = Auth::user()->id;
        }elseif(Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin'){
            $resp_id = $this->responsable_id;
        }

        $this->validate();
        $resp_id = $this->responsable_id;
        Equipo::create([
           'numero_activo' => $this->numero_activo,
            'nombre' => $this->nombre,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'serie' => $this->serie,
            'estado' => $this->estado,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'categoria_id' => $this->categoria_id ?: null,
            'laboratorio_id' => $this->laboratorio_id ?: null,
            'responsable_id' => $resp_id ?: null,
            'caracteristicas' => $this->caracteristicas,
            'condiciones_uso' => $this->condiciones_uso,
        ]);
    }

    //Metodo para actualizar un equipo
    public function actualizarEquipo($id)
    {
        $this->validate();
        if (Auth::user()->rol_actual == 'Responsable del activo') {
            $resp_id = Auth::user()->id;
        }elseif(Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin'){
            $resp_id = $this->responsable_id;
        }
        
        $equipo = Equipo::find($id);
        $equipo->update([
           'numero_activo' => $this->numero_activo,
            'nombre' => $this->nombre,
            'marca' => $this->marca,
            'modelo' => $this->modelo,
            'serie' => $this->serie,
            'estado' => $this->estado,
            'fecha_adquisicion' => $this->fecha_adquisicion,
            'categoria_id' => $this->categoria_id ?: null,
            'laboratorio_id' => $this->laboratorio_id ?: null,
            'responsable_id' => $resp_id ?: null,
            'caracteristicas' => $this->caracteristicas,
            'condiciones_uso' => $this->condiciones_uso,
        ]);
    }

    //Metodo para eliminar un equipo
    public function eliminarEquipo($id)
    {
        $equipo = Equipo::find($id);
        $equipo->delete();
    }

    //Método para cargar los datos de un equipo
    public function cargarEquipo($id)
    {
        $equipo = Equipo::find($id);
        #$equipo = Equipo::with(['categoria', 'laboratorio', 'responsable'])->find($id);
       # dd($equipo->responsable->name);
        if ($equipo) {
            $this->equipo_id = $equipo->id;
            $this->numero_activo = $equipo->numero_activo;
            $this->nombre = $equipo->nombre;
            $this->marca = $equipo->marca;
            $this->modelo = $equipo->modelo;
            $this->serie = $equipo->serie;
            $this->estado = $equipo->estado;
            $this->fecha_adquisicion = $equipo->fecha_adquisicion;
            $this->categoria_id = $equipo->categoria;
            $this->laboratorio_id = $equipo->laboratorio;
            $this->responsable_id = $equipo->responsable;
            $this->caracteristicas = $equipo->caracteristicas;
            $this->condiciones_uso = $equipo->condiciones_uso;
        }
    }
}
