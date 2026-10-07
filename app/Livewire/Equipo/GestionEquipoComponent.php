<?php

namespace App\Livewire\Equipo;

use Livewire\Component;
use App\Models\Equipo;
use App\Models\Categoria;
use App\Models\Laboratorio;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\WithPagination;
use App\Livewire\Forms\EquipoForm;
class GestionEquipoComponent extends Component
{
    use WithPagination;

    //Variables de paginacion y busqueda
    public $perPage = 10;
    public $buscar = '';

    //Variables de categorias, laboratorios y usuarios para el formulario
    public $categorias;
    public $laboratorios;
    public $usuarios;

    //Instancia de EquipoForm
    public EquipoForm $equipoForm;


    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $mostrarModalEliminar = false;
    public $modalTitulo = '';


    public function cargarDatos()
    {
        $this->categorias = Categoria::all();
        $this->laboratorios = Laboratorio::all();
        $this->usuarios = User::all();
    }

    public function mount()
    {
        $this->cargarDatos();
    }

    //Metodo para abrir modal de crear equipo
    public function modalCrearEquipo()
    {
        $this->modoEditar = false;
        $this->equipoForm->reset();
        $this->modalTitulo = 'Registrar nuevo Equipo';
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para crear categoria
    public function crear()
    {
        $this->equipoForm->crearEquipo();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado el equipo de manera exitosa');
        $this->reset();
        $this->resetPage();
        $this->cargarDatos();
    }

    //Metodo para abrir modal de editar equipo
    public function modalEditarEquipo($id)
    {
        $this->modoEditar = true;
        $this->equipoForm->reset();
        $this->modalTitulo = 'Editar Equipo';
        $this->equipoForm->equipo_id = $id;
        $equipo = Equipo::find($id);
        $this->equipoForm->nombre = $equipo->nombre;
        $this->equipoForm->numero_activo = $equipo->numero_activo;
        $this->equipoForm->marca = $equipo->marca;
        $this->equipoForm->modelo = $equipo->modelo;
        $this->equipoForm->serie = $equipo->serie;
        $this->equipoForm->estado = $equipo->estado;
        $this->equipoForm->fecha_adquisicion = $equipo->fecha_adquisicion;

        $categoria = Categoria::find($equipo->categoria_id);
        $this->equipoForm->categoria_id = ($categoria) ? $equipo->categoria_id : '';

        $laboratorio = Laboratorio::find($equipo->laboratorio_id);
        $this->equipoForm->laboratorio_id = ($laboratorio && $laboratorio->visible) ? $equipo->laboratorio_id : '';

        $responsable = User::find($equipo->responsable_id);
        $this->equipoForm->responsable_id = ($responsable) ? $equipo->responsable_id : '';

        $this->equipoForm->caracteristicas = $equipo->caracteristicas;
        $this->equipoForm->condiciones_uso = $equipo->condiciones_uso;
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para actualizar institucion    
    public function actualizar()
    {
        $this->equipoForm->actualizarEquipo($this->equipoForm->equipo_id);
        $this->mostrarModal = false;
        $this->reset();
        session()->flash('success', 'Se ha actualizado el equipo de manera exitosa');
        $this->resetPage();
        $this->cargarDatos();
    }

    //Metodo para abrir modal de eliminar equipo
    public function modalEliminarEquipo($id)
    {
        $equipo = Equipo::findOrFail($id);
        if ($equipo->estado !== 'Disponible') {
            session()->flash('error', 'No se puede eliminar el equipo porque se encuentra en uso. Cambie el estado a Disponible para poder eliminarlo.');
            return;
        }
#dd($equipo->solicitud()->exists());
        if ($equipo->solicitud()->exists()) {
            session()->flash('error', 'No se puede eliminar el equipo porque esta asignado a una solicitud.');
            return;
        }

        $this->equipoForm->equipo_id = $id;
        $this->equipoForm->nombre = $equipo->nombre;
        $this->modalTitulo = 'Eliminar Equipo';
        $this->mostrarModalEliminar = true;
        $this->resetValidation();
    }

    //Metodo para eliminar equipo
    public function eliminar()
    {
        $this->equipoForm->eliminarEquipo($this->equipoForm->equipo_id);
        $this->reset('equipoForm');
        session()->flash('success', 'Se ha eliminado el equipo de manera exitosa');
        $this->mostrarModalEliminar = false;
        $this->resetPage();
        $this->cargarDatos();
    }

    //Metodo para actualizar la pagina de busqueda
    public function updatingBuscar()
    {
        $this->resetPage();
    }


    public function render()
    {

        if (Auth::user()->rol_actual == 'Responsable del activo') {
            $query = Equipo::where('responsable_id', Auth::user()->id);

            if ($this->buscar) {
                $query->where(function ($q) {
                    $q->where('nombre', 'like', '%' . $this->buscar . '%')
                        ->orWhere('numero_activo', 'like', '%' . $this->buscar . '%')
                        ->orWhere('marca', 'like', '%' . $this->buscar . '%');
                })->where('responsable_id', Auth::user()->id);
            }


            $equipo_count = Equipo::where('responsable_id', Auth::user()->id)->count();
            /*  $equipos = $query->where('responsable_id', Auth::user()->id)->orderBy('id', 'desc')->paginate($this->perPage);*/

            $equipos = $query->orderBy('id', 'desc')->paginate($this->perPage);
        } elseif (Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin') {

            $query = Equipo::query();
            if ($this->buscar) {
                $query->where('nombre', 'like', '%' . $this->buscar . '%')
                    ->orWhere('numero_activo', 'like', '%' . $this->buscar . '%')
                    ->orWhere('marca', 'like', '%' . $this->buscar . '%');
            }
            $equipos = $query->orderBy('id', 'desc')->paginate($this->perPage);
            $equipo_count = Equipo::count();
        }




        return view('livewire.equipo.gestion-equipo-component', [
            'equipos' => $equipos,

            'equipos_count' => $equipo_count,
        ]);
    }
}
