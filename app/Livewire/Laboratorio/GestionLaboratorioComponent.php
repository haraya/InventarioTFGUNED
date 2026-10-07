<?php

namespace App\Livewire\Laboratorio;

use Livewire\Component;
use App\Models\Institucion;
use App\Models\Sede;
use App\Models\Laboratorio;
use Livewire\WithPagination;
use App\Livewire\Forms\LaboratorioForm;

class GestionLaboratorioComponent extends Component
{
    use WithPagination;

    //Variables de paginacion y busqueda
    public $perPage = 10;
    public $sort = 'asc';
    public $buscar = '';

    //Instancia de LaboratorioForm
    public LaboratorioForm $laboratorioForm;

    //Variables de instituciones y sedes para el select
    public $instituciones;
    public $sedes;

    //Variables de modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $modalTitulo = '';


    public function mount()
    {
        $this->instituciones = Institucion::all();
        $this->sedes = Sede::all();
    }


    //Metodo para abrir modal de crear laboratorio 
    public function modalCrearLaboratorio()
    {
        $this->modoEditar = false;
        $this->laboratorioForm->reset();
        $this->modalTitulo = 'Registrar nuevo Laboratorio';
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para crear una sede
    public function crear()
    {
        $this->laboratorioForm->crearLaboratorio();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado el laboratorio de manera exitosa');
        $this->reset('laboratorioForm');
        $this->resetPage();
    }

    //Metodo para abrir modal de editar laboratorio
    public function modalEditarLaboratorio($id)
    {
        $this->modoEditar = true;
        $this->laboratorioForm->reset();
        $this->modalTitulo = 'Editar Laboratorio';
        $this->laboratorioForm->lab_edit_id = $id;
        $laboratorio = Laboratorio::find($id);
        $this->laboratorioForm->nombre = $laboratorio->nombre;
        
        // Verificar si la institución está visible
        $institucion = Institucion::find($laboratorio->institucion_id);
        $this->laboratorioForm->institucion_id = ($institucion && $institucion->visible) ? $laboratorio->institucion_id : '';
        
        // Verificar si la sede está visible
        $sede = Sede::find($laboratorio->sede_id);
        $this->laboratorioForm->sede_id = ($sede && $sede->visible) ? $laboratorio->sede_id : '';

        $this->mostrarModal = true;
        $this->resetValidation();
    }


    //Metodo para actualizar una sede
    public function actualizar()
    {
        $this->laboratorioForm->actualizarLaboratorio($this->laboratorioForm->lab_edit_id);
        $this->mostrarModal = false;
        $this->reset('laboratorioForm');
        session()->flash('success', 'Se ha actualizado el laboratorio de manera exitosa');
        $this->resetPage();
    }


    //Metodo para actualizar la visibilidad de un laboratorio
    public function actualizarVisibilidad($id)
    {
        $laboratorio = Laboratorio::find($id);
        if ($laboratorio) {
            $laboratorio->visible = !$laboratorio->visible;
            $laboratorio->save();
        }
    }

    //Metodo para actualizar la pagina de busqueda
    public function updatingBuscar()
    {
        $this->resetPage();
    }



    public function render()
    {
        $query = Laboratorio::query();
        if ($this->buscar) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
            $query->orWhere('institucion_id', 'like', '%' . $this->buscar . '%');
            $query->orWhere('sede_id', 'like', '%' . $this->buscar . '%');
        }
        $laboratorios = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.laboratorio.gestion-laboratorio-component', [
            'laboratorios' => $laboratorios,
            'laboratorios_count' => Laboratorio::count(),
        ]);
    }
}
