<?php

namespace App\Livewire\Usuario;

use Livewire\Component;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Models\Institucion;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Livewire\Forms\UsuarioForm;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class GestionUsuarioComponent extends Component
{

    use WithPagination;

    //Atributos para la paginacion y busqueda de usuarios
    public $buscar = '';
    public $sort = 'asc';
    public $perPage = 10;

    //Atributos para los roles y las instituciones
    public $roles;
    public $instituciones;

    //Instancia de InstitucionForm
    public UsuarioForm $UsuarioForm;

    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $modalTitulo = '';

    public $mostrarModalEliminar = false;


    public function cargarRoles_Instituciones()
    {
        if (Auth::user()->rol_actual === 'Superadmin') {
            $this->roles = Role::all();
        } else {
            $this->roles = Role::whereNotIn('name', ['Administrador', 'Superadmin'])->get();
        }
        $this->instituciones = Institucion::all();
    }


    public function mount()
    {
        $this->cargarRoles_Instituciones();
    }




    //Metodo para abrir modal de crear usuario
    public function modalCrearUsuario()
    {
        $this->modoEditar = false;
        $this->UsuarioForm->reset();
        $this->modalTitulo = 'Registrar nuevo Usuario';
        $this->mostrarModal = true;
        $this->resetValidation();
    }


    //Metodo para crear usuario
    public function crear()
    {
        $this->UsuarioForm->crearUsuario();
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha creado el usuario de manera exitosa');
        $this->reset();
        $this->resetPage();
        $this->cargarRoles_Instituciones();

    }


    //Metodo para abrir modal de editar usuario
    public function modalEditarUsuario($id)
    {
        $this->modoEditar = true;
        $this->UsuarioForm->reset();
        $this->modalTitulo = 'Editar Usuario';
        $this->UsuarioForm->usuario_id = $id;
        $user = User::find($id);
        $this->UsuarioForm->name = $user->name;
        $this->UsuarioForm->telefono = $user->telefono;
        $this->UsuarioForm->email = $user->email;

        $institucion = Institucion::find($user->institucion_id);
        $this->UsuarioForm->institucion_id = ($institucion && $institucion->visible) ? $user->institucion_id : '';

        $this->UsuarioForm->rolesSeleccionados = $user->roles->pluck('name')->toArray();
        $this->UsuarioForm->informacion_adicional = $user->informacion_contacto_adicional;
        $this->mostrarModal = true;
        $this->resetValidation();
    }


    //Metodo para actualizar usuario
    public function actualizar()
    {
        $this->UsuarioForm->actualizarUsuario($this->UsuarioForm->usuario_id);
        $this->mostrarModal = false;
        session()->flash('success', 'Se ha actualizado el usuario de manera exitosa');
        $this->reset('UsuarioForm');
        $this->resetPage();
        $this->cargarRoles_Instituciones();
    }

    //Metodo para abrir modal de eliminar usuario
    public function modalEliminarUsuario($id)
    {
       
        $usuario = User::find($id);
       
        if($usuario->id === 1){
            session()->flash('error', 'No se puede eliminar el usuario Superadmin');
            return;
        }
        $this->UsuarioForm->usuario_id = $id;
        $this->UsuarioForm->name = $usuario->name;
        $this->modalTitulo = 'Eliminar Usuario';
        $this->mostrarModalEliminar = true;
        $this->resetValidation();
    }

    //Metodo para eliminar usuario
    public function eliminar()
    {
        $this->UsuarioForm->eliminarUsuario($this->UsuarioForm->usuario_id);
        $this->reset('UsuarioForm');
        session()->flash('success', 'Se ha eliminado el usuario de manera exitosa');
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
        $query = User::query();
        
        // Aplicar filtro de búsqueda si existe
        if ($this->buscar) {
            $query->where('name', 'like', '%' . $this->buscar . '%')
                ->orWhere('email', 'like', '%' . $this->buscar . '%');
        }

        // Filtrar usuarios según el rol del usuario actual
        if (Auth::user()->rol_actual === 'Superadmin') {
            // Mostrar todos los usuarios para Superadmin
            $usuarios = $query->orderBy('id', 'desc')->paginate($this->perPage);
        } else {
            // Para Admin, mostrar solo usuarios con roles específicos
            $usuarios = $query->whereHas('roles', function($q) {
                $q->whereIn('name', ['Responsable del Activo', 'Usuario']);
            })->orderBy('id', 'desc')->paginate($this->perPage);
        }

        return view('livewire.usuario.gestion-usuario-component', [
            'usuarios' => $usuarios,
            'usuarios_count' => User::count()
        ]);
    }
}
