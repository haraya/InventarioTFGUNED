<?php

namespace App\Livewire\Sede;

use Livewire\Component;
use App\Models\Institucion;
use App\Models\Sede;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\SedeForm;

class GestionSedeComponent extends Component
{
    use WithPagination;

    //Variables de paginacion y busqueda
    public $perPage = 10;
    public $sort = 'asc';
    public $buscar = '';

    //Variables de instituciones para el select
    public $instituciones;

    //Instancia de SedeForm
    public SedeForm $sedeForm;

    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $modalTitulo = '';
    
    //Metodo para obtener las instituciones
    public function mount()
    {
        $this->instituciones = Institucion::all();
    }

    //Metodo para abrir modal de crear sede
    public function modalCrearSede()
    {
        $this->modoEditar = false;
        $this->sedeForm->reset();
        $this->modalTitulo = 'Registrar nueva Sede';  
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para crear una sede
    public function crear()
    {
        $this->sedeForm->crearSede();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado la sede de manera exitosa');
        $this->reset('sedeForm');
        $this->resetPage();
    }

    //Metodo para abrir modal de editar sede
    public function modalEditarSede($id)
    {
        $this->modoEditar = true;
        $this->sedeForm->reset();
        $this->modalTitulo = 'Editar Sede';
        $this->sedeForm->sede_id = $id;
        $sede = Sede::find($id);
        $this->sedeForm->nombre = $sede->nombre;
        
        // Verificar si la institución está visible
        $institucion = Institucion::find($sede->institucion_id);
        $this->sedeForm->institucion_id = ($institucion && $institucion->visible) ? $sede->institucion_id : '';
        
        $this->mostrarModal = true;
        $this->resetValidation();
    }


    //Metodo para actualizar una sede
    public function actualizar()
    {
        $this->sedeForm->actualizarSede($this->sedeForm->sede_id);
        $this->mostrarModal = false;
        $this->reset('sedeForm');
        session()->flash('success', 'Se ha actualizado la sede de manera exitosa');
        $this->resetPage();
    }

    //Metodo para actualizar la visibilidad de una sede
    public function actualizarVisibilidad($id)
    {
        $sede = Sede::find($id);
        if ($sede) {
            $sede->visible = !$sede->visible;
            $sede->save();
        } 
    }


   //Metodo para actualizar la pagina de busqueda
   public function updatingBuscar()
   {
       $this->resetPage();
   }
    
    
    public function render()
    {   
        $query = Sede::query();
        if($this->buscar){
            $query->where('nombre', 'like', '%'.$this->buscar.'%')
            ->orWhereHas('institucion', function($institucionQuery) {
                $institucionQuery->where('nombre', 'like', '%'.$this->buscar.'%');
            });
        }

        $sedes = $query->orderBy('id', 'desc')->paginate($this->perPage);
        
        return view('livewire.sede.gestion-sede-component',[
            'sedes' => $sedes,
            'sedes_count' => Sede::count(),
        ]);
    }
    
}
