<?php

namespace App\Livewire\Institucion;

use Livewire\Component;
use App\Models\Institucion;
use App\Livewire\Forms\InstitucionForm;
use Livewire\WithPagination;
use Livewire\WithSorting;

class GestionInstitucionComponent extends Component
{
    use WithPagination;


    //Variables de paginacion y busqueda
    public $perPage = 10;
    public $buscar = '';


    //Instancia de InstitucionForm
    public InstitucionForm $institucionForm;


    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $modalTitulo = '';


    //Metodo para abrir modal de crear institucion
    public function modalCrearInstitucion()
    {
        $this->modoEditar = false;
        $this->institucionForm->reset();
        $this->modalTitulo = 'Registrar nueva Institucion';
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para crear institucion
    public function crear()
    {
        $this->institucionForm->crearInstitucion();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado la institucion de manera exitosa');
        $this->reset('institucionForm');
        $this->resetPage();
    }

    //Metodo para abrir modal de editar institucion
    public function modalEditarInstitucion($id)
    {
        $this->modoEditar = true;
        $this->institucionForm->reset();
        $this->modalTitulo = 'Editar Institucion';
        $this->institucionForm->institucion_id = $id;
        $institucion = Institucion::find($id);
        $this->institucionForm->nombre = $institucion->nombre;
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para actualizar institucion    
    public function actualizar()
    {
        $this->institucionForm->actualizarInstitucion($this->institucionForm->institucion_id);
        $this->mostrarModal = false;
        $this->reset('institucionForm');
        session()->flash('success', 'Se ha actualizado la institucion de manera exitosa');
        $this->resetPage();
    }


    //Actualizar visibilidad de institucion oculto/visible
    public function actualizarVisibilidad($id)
    {
        $institucion = Institucion::find($id);
        if ($institucion) {
            $institucion->visible = !$institucion->visible;
            $institucion->save();
        }
    }

    //Metodo para actualizar la pagina de busqueda
    public function updatingBuscar()
    {
        $this->resetPage();
    }




    public function render()
    {
        $query = Institucion::query();
        if ($this->buscar) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        }

        $instituciones = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.institucion.gestion-institucion-component', [
            'instituciones' => $instituciones,
            'instituciones_count' => Institucion::count(),
        ]);
    }
}
