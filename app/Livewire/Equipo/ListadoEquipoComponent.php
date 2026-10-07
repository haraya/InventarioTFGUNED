<?php

namespace App\Livewire\Equipo;

use Livewire\Component;
use App\Models\Categoria;
use App\Models\Equipo;
use App\Models\Solicitud;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Laboratorio;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\SolicitudEquipoForm;

class ListadoEquipoComponent extends Component
{
    use WithPagination;

    //Variables para la paginacion y busqueda
    public $perPage = 10;
    public $buscar = '';


    //Instancia del formulario de solicitud de equipo
    public SolicitudEquipoForm $solicitudEquipoForm;

    //Variable para el id del equipo
    public $equipo_id;

    public $vista_solicitud = false;


    //Variable para el modal de solicitud de equipo
    public $modalSolicitar = false;


    //Variable para del Form equipo
    public $equipoForm;

    public function modalSolicitud($id)
    {
      
        $this->modalSolicitar = true;
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


    //Metodo para generar la solicitud de equipo
    public function generarSolicitud()
    {
        /* $fechaSolicitud = Carbon::parse($this->fecha_solicitud)->format('d-m-Y');
        $fechaDevolucion = Carbon::parse($this->fecha_aproximada_devolucion)->format('d-m-Y');*/

        $this->solicitudEquipoForm->crearSolicitud($this->equipo_id);
        $equipo = Equipo::find($this->equipo_id);
        $equipo->estado = 'Solicitado';
        $equipo->save();

        session()->flash('success', 'Se ha solicitado el equipo de manera exitosa');

        $this->reset();
        $this->modalSolicitar = false;
    }


    public function render()
    {
        $query = Equipo::query();

        // Si el usuario está autenticado, solo mostrar equipos disponibles
        if (Auth::check()) {
            $query->where('estado', 'Disponible');
        }

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

        return view('livewire.equipo.listado-equipo-component', [
            'equipos' => $equipos,
        ]);
    }
}
