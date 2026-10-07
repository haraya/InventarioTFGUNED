<?php

namespace App\Livewire\Equipo;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Equipo;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Laboratorio;
use Livewire\WithPagination;




class EquipoIndexComponent extends Component
{ use WithPagination;

    //Variables para la paginacion y busqueda
    public $perPage = 10;
    public $buscar = '';




    //Variable para el id del equipo
    public $equipo_id;

   

    //Variable para el modal de solicitud de equipo
    public $modalVisualizar = false;


    //Variable para del Form equipo
    public $equipoForm;

    public function modalVistaEquipo($id)
    {
     
        $this->modalVisualizar = true;
        $this->equipo_id = $id;

        $equipo = Equipo::find($id);
        $this->equipoForm['numero_activo'] = $equipo->numero_activo;
        $this->equipoForm['nombre'] = $equipo->nombre;
        $this->equipoForm['marca'] = $equipo->marca;
        $this->equipoForm['modelo'] = $equipo->modelo;
        $this->equipoForm['serie'] = $equipo->serie;
        $this->equipoForm['fecha_adquisicion'] = $equipo->fecha_adquisicion;
        $this->equipoForm['estado'] = $equipo->estado;
        $this->equipoForm['categoria'] = $equipo->categoria;
       
        $this->equipoForm['laboratorio'] = $equipo->laboratorio;
        
      #  $this->equipoForm['laboratorio'] = $equipo->laboratorio ?? null;
        $this->equipoForm['responsable'] = $equipo->responsable;
        $this->equipoForm['caracteristicas'] = $equipo->caracteristicas;
        $this->equipoForm['condiciones_uso'] = $equipo->condiciones_uso;

        $this->resetValidation();
    }



    public function render()
    {
                $query = Equipo::query();

      

        // Aplicar búsqueda si existe un término de búsqueda
        if ($this->buscar) {
            $query->where(function ($q) {
                $q->where('nombre', 'like', '%' . $this->buscar . '%')
                    ->orWhere('numero_activo', 'like', '%' . $this->buscar . '%')
                    ->orWhere('marca', 'like', '%' . $this->buscar . '%');
            });
        }

        // Obtener equipos paginados
        $equipos = $query->orderBy('id', 'desc')->paginate($this->perPage);
        
        return view('livewire.equipo.equipo-index-component',['equipos' => $equipos]);
    }
}
