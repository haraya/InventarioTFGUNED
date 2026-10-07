<?php

namespace App\Livewire\Solicitud;

use App\Models\Categoria;
use Livewire\Component;
use App\Models\Solicitud;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;
use App\Livewire\Forms\EquipoForm;
use App\Livewire\Forms\SolicitudEquipoForm;
use App\Livewire\Forms\UsuarioForm;
use App\Models\Equipo;

class GestionSolicitudComponent extends Component
{

    use WithPagination;

    //Variables para paginacion y busqueda
    public $buscar;
    public $perPage = 10;
    public $estadoFiltro = 'todas';


    public $pendientesCount = 0;
    public $aprobadasProcesoCount = 0;
    public $devolucionProcesoCount = 0;
    public $completadasCount = 0;

    //Variable para el estado del equipo   
    public $equipo_estado;

    public $fecha_solicitud;
    public $fecha_aproximada_devolucion;

    //Variables de Modal
    public $modoEditar = false;
    public $mostrarModal = false;
    public $modalTitulo = '';


    //Instancia de SolicitudEquipoForm
    public SolicitudEquipoForm $gestionSolicitud;

    //Instancia de UsuarioForm
   public UsuarioForm $usuarioForm;

   

    //Instancia de EquipoForm
    public EquipoForm $equipoForm;


    

    //Modal para ver la solicitud por el usuario
    public function modalVerSolicitudes($id)
    {
        $this->mostrarModal = true;
        $this->modalTitulo = 'Ver Solicitud #' . $id;
        $this->modoEditar = false;
        $this->gestionSolicitud->id_solicitud = $id;

        $solicitud = Solicitud::with(['user', 'equipo'])->find($id);

        // Cargar los datos del equipo usando el EquipoForm
        $this->equipoForm->cargarEquipo($solicitud->equipo_id);
        

        // Cargar los datos del usuario usando el UsuarioForm
        $this->usuarioForm->cargarUsuario($solicitud->user_id);
            #$this->usuarioForm->$solicitud->user();


        $this->fecha_solicitud = $solicitud->fecha_solicitud;
        $this->fecha_aproximada_devolucion = $solicitud->fecha_aproximada_devolucion;
        $this->gestionSolicitud->estado_solicitud = $solicitud->estado_solicitud;
    }

    //Modal para editar la solicitud por el administrador
    public function modalEditarSolicitudes($id)
    {
        $this->mostrarModal = true;
        $this->modalTitulo = 'Editar Solicitud #' . $id;
        $this->modoEditar = true;
        $this->gestionSolicitud->id_solicitud = $id;

        $solicitud = Solicitud::with(['user', 'equipo'])->find($id);

        // Cargar los datos del equipo usando el EquipoForm
        $this->equipoForm->cargarEquipo($solicitud->equipo_id);

        // Cargar los datos del usuario usando el UsuarioForm
        $this->usuarioForm->cargarUsuario($solicitud->user_id);

        $this->fecha_solicitud = $solicitud->fecha_solicitud;
        $this->fecha_aproximada_devolucion = $solicitud->fecha_aproximada_devolucion;

        $this->equipo_estado = $solicitud->equipo->estado;
       
        $this->gestionSolicitud->estado_solicitud = $solicitud->estado_solicitud;
        $this->gestionSolicitud->fecha_exacta_devolucion = $solicitud->fecha_exacta_devolucion;
        $this->gestionSolicitud->observaciones = $solicitud->observaciones;
    }

    //Método para actualizar la solicitud
    public function actualizar()
    {
        $solicitud = Solicitud::find($this->gestionSolicitud->id_solicitud);


        $this->gestionSolicitud->actualizarSolicitud($solicitud->id, $this->equipo_estado);
        session()->flash('success', 'Solicitud #' . $solicitud->id . ' actualizada correctamente');
        $this->resetPage();
        $this->reset();
        $this->mostrarModal = false;
    }



    //Método para cancelar la devolución
    public function cancelarSolicitud($id)
    {
        $this->gestionSolicitud->CambiarEstadoSolicitud($id, "Cancelada", "Disponible");
        session()->flash('success', 'Solicitud #' . $id . ' cancelada correctamente');
    }

    //Metodo para devolver el equipo
    public function devolverEquipo($id)
    {
        $this->gestionSolicitud->CambiarEstadoSolicitud($id, "Devolucion en proceso", "En Devolucion");
        session()->flash('success', 'Solicitud #' . $id . 'en proceso de devolucion');
    }

   
    
    // Método para validar si el estado del equipo es coherente con el estado de la solicitud
    public function validarEstados()
    {
        // Mapeo de estados válidos entre solicitud y equipo
        $estadosValidos = [
            'Pendiente' => ['Disponible', 'Solicitado'],
            'Aprobada' => ['Solicitado', 'Reservado'],
            'Rechazada' => ['Disponible'],
            'Equipo en transito' => ['En transito'],
            'Equipo Asignado' => ['Asignado'],
            'Devolucion en proceso' => ['En Devolucion'],
            'Devolucion completada' => ['Disponible', 'En mantenimiento', 'Fuera de servicio'],
            'Cancelada' => ['Disponible']
        ];

        // Obtener el estado actual de la solicitud
        $estadoSolicitud = $this->gestionSolicitud->estado_solicitud;
        $estadoEquipo = $this->equipo_estado;

        // Si no hay estado de solicitud o equipo, no mostrar advertencia
        if (!$estadoSolicitud || !$estadoEquipo) {
            $this->gestionSolicitud->advertencia_estados = null;
            return;
        }

        // Verificar si la combinación de estados es válida
        if (isset($estadosValidos[$estadoSolicitud])) {
            if (!in_array($estadoEquipo, $estadosValidos[$estadoSolicitud])) {
                $estadosPermitidos = implode('", "', $estadosValidos[$estadoSolicitud]);
                $this->gestionSolicitud->advertencia_estados = "Para el estado de solicitud '{$estadoSolicitud}', el equipo debe estar en uno de estos estados: \"{$estadosPermitidos}\"";
            } else {
                $this->gestionSolicitud->advertencia_estados = null;
            }
        } else {
            $this->gestionSolicitud->advertencia_estados = "Estado de solicitud no válido";
        }

       # $this->reset();
    }


    //Método para verificar las solicitudes pendientes por el administrador
    public function verificarSolicitudes()
    {
        $solicitudes = Solicitud::where('estado_solicitud', 'Pendiente')->get();
        if ($solicitudes->count() > 0) {
            session()->flash('success', 'Existen '.$solicitudes->count().' solicitudes pendientes');
        }else{
            session()->flash('error', 'No existen solicitudes pendientes');
        }
    }

    //Método para filtrar por estado
    public function filtrarPorEstado($estado)
    {
        $this->estadoFiltro = $estado;
        $this->resetPage(); 
    }

    public function verificarEstadoSolicitudes()
    {
        $solicitudes = Solicitud::where('user_id', Auth::user()->id);
    }

    

    // Método para obtener el mensaje basado en el estado
    public function obtenerMensajeEstado($estado)
    {
        switch ($estado) {
            case 'Pendiente':
                return 'Tu solicitud está en espera de aprobación.';
            case 'Aprobada':
                return 'Estamos preparando el equipo para su envío.';
            case 'Rechazada':
                return 'Tu solicitud ha sido rechazada.';
            case 'Equipo en transito':
                return 'El equipo está en tránsito hacia tu ubicación.';
            case 'Equipo Asignado':
                return 'El equipo ha sido asignado al usuario.';
            case 'Devolucion en proceso':
                return 'La devolución del equipo está en curso.';
            case 'Devolucion completada':
                return 'El equipo ha sido devuelto exitosamente.';
            case 'Cancelada':
                return 'La solicitud ha sido cancelada.';
            default:
                return 'Estado desconocido.';
        }
    }




    public function render()
    {
        // Contadores de solicitudes por estado
        $this->pendientesCount = Solicitud::where('estado_solicitud', 'Pendiente')->count();
        $this->aprobadasProcesoCount = Solicitud::whereIn('estado_solicitud', [
            'Aprobada',
            'Equipo en transito',
            'Equipo Asignado',
        ])->count();
        $this->devolucionProcesoCount = Solicitud::where('estado_solicitud', 'Devolucion en proceso')->count();
        $this->completadasCount = Solicitud::where('estado_solicitud', 'Devolucion completada')->count();

        if (Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin') {
            $query = Solicitud::query();

            if ($this->estadoFiltro !== 'todas') {
                if ($this->estadoFiltro == 'Aprobadas y en Proceso') {
                    $query->whereIn('estado_solicitud', ['Aprobada', 'Equipo en transito', 'Equipo Asignado']);
                } else {
                    $query->where('estado_solicitud', $this->estadoFiltro);
                }
            }


            if ($this->buscar) {
                $query->where('id', 'like', '%' . $this->buscar . '%')
                    ->orWhereHas('user', function ($query) {
                        $query->where('name', 'like', '%' . $this->buscar . '%');
                    })
                    ->orWhereHas('equipo', function ($query) {
                        $query->where('nombre', 'like', '%' . $this->buscar . '%')
                            ->orWhere('numero_activo', 'like', '%' . $this->buscar . '%')
                            ->orWhere('marca', 'like', '%' . $this->buscar . '%');
                    })

                    ->orWhere('estado_solicitud', 'like', '%' . $this->buscar . '%');
            }
        } else {
            $query = Solicitud::where('user_id', Auth::user()->id);

            if ($this->estadoFiltro !== 'todas') {
                if ($this->estadoFiltro == 'Aprobadas y en Proceso') {
                    $query->whereIn('estado_solicitud', ['Aprobada', 'Equipo en transito', 'Equipo Asignado']);
                } else {
                    $query->where('estado_solicitud', $this->estadoFiltro);
                }
            }
            if ($this->buscar) {
                $query->where(function ($q) {
                    $q->where('id', 'like', '%' . $this->buscar . '%')
                        ->orWhereHas('user', function ($query) {
                            $query->where('name', 'like', '%' . $this->buscar . '%');
                        })
                        ->orWhereHas('equipo', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->buscar . '%')
                                ->orWhere('numero_activo', 'like', '%' . $this->buscar . '%')
                                ->orWhere('marca', 'like', '%' . $this->buscar . '%');
                        })

                        ->orWhere('estado_solicitud', 'like', '%' . $this->buscar . '%');
                });
            }
        }




        $solicitudes = $query->orderBy('id', 'desc')->paginate($this->perPage);
        return view('livewire.solicitud.gestion-solicitud-component', [
            'solicitudes' => $solicitudes,
        ]);
    }
}
