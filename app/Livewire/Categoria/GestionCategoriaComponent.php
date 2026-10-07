<?php

namespace App\Livewire\Categoria;

use Livewire\Component;
use App\Models\Categoria;
use Livewire\WithPagination;
use Livewire\Attributes\Validate;
use App\Livewire\Forms\CategoriaForm;

class GestionCategoriaComponent extends Component
{
    use WithPagination;

    //Atributos de paginacio y busqueda
    public $perPage = 10;
    public $sort = 'asc';
    public $buscar = '';


    //Instancia de CategoriaForm
    public CategoriaForm $categoriaForm;


    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $mostrarModalEliminar = false;
    public $modalTitulo = '';


    //Metodo para abrir modal de crear categoria
    public function modalCrearCategoria()
    {
        $this->modoEditar = false;
        $this->categoriaForm->reset();
        $this->modalTitulo = 'Registrar nueva Categoria';
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para crear categoria
    public function crear()
    {
        $this->categoriaForm->crearCategoria();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado la categoria de manera exitosa');
        $this->reset('categoriaForm');
        $this->resetPage();
    }

    //Metodo para abrir modal de editar categoria
    public function modalEditarCategoria($id)
    {
        $this->modoEditar = true;
        $this->categoriaForm->reset();
        $this->modalTitulo = 'Editar Categoria';
        $this->categoriaForm->categoria_id = $id;
        $categoria = Categoria::find($id);
        $this->categoriaForm->nombre = $categoria->nombre;
        $this->mostrarModal = true;
        $this->resetValidation();
    }

    //Metodo para actualizar institucion    
    public function actualizar()
    {
        $this->categoriaForm->actualizarCategoria($this->categoriaForm->categoria_id);
        $this->mostrarModal = false;
        $this->reset('categoriaForm');
        session()->flash('success', 'Se ha actualizado la categoria de manera exitosa');
        $this->resetPage();
    }

    //Metodo para abrir modal de eliminar categoria
    public function modalEliminarCategoria($id)
    {
        # dd($id);
        $categoria = Categoria::find($id);
        $this->categoriaForm->categoria_id = $id;
        $this->categoriaForm->nombre = $categoria->nombre;
        $this->modalTitulo = 'Eliminar Categoria';
        $this->mostrarModalEliminar = true;
        $this->resetValidation();
    }

    //Metodo para eliminar categoria
    public function eliminar()
    {
        $this->categoriaForm->eliminarCategoria($this->categoriaForm->categoria_id);
        $this->reset('categoriaForm');
        session()->flash('success', 'Se ha eliminado la categoria de manera exitosa');
        $this->mostrarModalEliminar = false;
        $this->resetPage();
    }

    //Metodo para actualizar la pagina de busqueda
    public function updatingBuscar()
    {
        $this->resetPage();
    }


    public function render()
    {
        $query = Categoria::query();
        if ($this->buscar) {
            $query->where('nombre', 'like', '%' . $this->buscar . '%');
        }
        $categorias = $query->orderBy('id', 'desc')->paginate($this->perPage);

        return view('livewire.categoria.gestion-categoria-component', [
            'categorias' => $categorias,
            'categorias_count' => Categoria::count(),
        ]);
    }
}
