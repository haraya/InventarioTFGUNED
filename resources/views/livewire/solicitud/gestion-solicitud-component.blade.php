<div>




    <div>
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @elseif (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
    </div>

    @if (auth()->user()->rol_actual == 'Administrador' || auth()->user()->rol_actual == 'Superadmin')
        <div class="row mb-4 justify-content-center">

            <div class="col-auto mb-2">
                <div class="d-flex align-items-center border border-primary rounded shadow-sm card-hover px-3 py-2"
                    style="cursor:pointer; min-width: 180px;" wire:click="filtrarPorEstado('Pendiente')">
                    <i class="bi bi-hourglass-split fs-5 text-primary me-2"></i>
                    <span class="fw-semibold flex-grow-1" style="font-size: 1rem;">Pendientes</span>
                    <span class="badge bg-primary ms-2" style="font-size: 1rem;">{{ $pendientesCount }}</span>
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="d-flex align-items-center border border-warning rounded shadow-sm card-hover px-3 py-2"
                    style="cursor:pointer; min-width: 180px;" wire:click="filtrarPorEstado('Aprobadas y en Proceso')">
                    <i class="bi bi-arrow-repeat fs-5 text-warning me-2"></i>
                    <span class="fw-semibold flex-grow-1" style="font-size: 1rem;">Aprobadas, En Transito,
                        Asignadas</span>
                    <span class="badge bg-warning text-dark ms-2"
                        style="font-size: 1rem;">{{ $aprobadasProcesoCount }}</span>
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="d-flex align-items-center border border-info rounded shadow-sm card-hover px-3 py-2"
                    style="cursor:pointer; min-width: 180px;" wire:click="filtrarPorEstado('Devolucion en Proceso')">
                    <i class="bi bi-arrow-repeat fs-5 text-info me-2"></i>
                    <span class="fw-semibold flex-grow-1" style="font-size: 1rem;">Devolucion en Proceso</span>
                    <span class="badge bg-info text-dark ms-2"
                        style="font-size: 1rem;">{{ $devolucionProcesoCount }}</span>
                </div>
            </div>
            <div class="col-auto mb-2">
                <div class="d-flex align-items-center border border-success rounded shadow-sm card-hover px-3 py-2"
                    style="cursor:pointer; min-width: 180px;" wire:click="filtrarPorEstado('Devolucion completada')">
                    <i class="bi bi-check-circle fs-5 text-success me-2"></i>
                    <span class="fw-semibold flex-grow-1" style="font-size: 1rem;">Completadas</span>
                    <span class="badge bg-success ms-2" style="font-size: 1rem;">{{ $completadasCount }}</span>
                </div>
            </div>

        </div>
    @endif
    <div class="row mt-3">

        @if (auth()->user()->rol_actual == 'Administrador' || auth()->user()->rol_actual == 'Superadmin')
            <div class="col-md-8">
                <button type="button" class="btn btn-primary" wire:click="verificarSolicitudes">
                    Verificar nuevas solicitudes
                </button>
            </div>
        @endif




    </div>


    <div class="row mt-3">

        <div class="col-md-4">
            <label for="estado" class="form-label">Filtro de estado de la solicitud</label>
            <select class="form-control" wire:model.live="estadoFiltro">

                <option value="todas">Todas</option>
                <option value="Aprobadas y en Proceso">Aprobadas, En Transito, Asignadas</option>
                <option value="Pendiente">Pendiente</option>
                <option value="Rechazada">Rechazada</option>
                <option value="Devolucion en Proceso">Devolucion en Proceso</option>
                <option value="Devolucion completada">Devolucion completada</option>
                <option value="Cancelada">Cancelada</option>
            </select>
        </div>

        {{-- @if (auth()->user()->rol_actual == 'Responsable del activo' || auth()->user()->rol_actual == 'Usuario')
            <div class="col-md-8">
                <button type="button" class="btn btn-primary" wire:click="verificarEstadoSolicitudes">
                    Verificar estado de mi solicitudes
                </button>
            </div>
        @endif --}}
        <div class="col-md-8 d-flex justify-content-end">
            <div class="d-flex gap-2 justify-content-end">
                <select class="form-select" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                {{-- <select class="form-select" wire:model.live="sort">
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                </select> --}}
            </div>
        </div>


    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar solicitud por numero de solicitud, nombre del equipo, numero de activo, solicitante, estado de solicitud...">
        </div>
    </div>



    <table class="table table-bordered text-center mt-3">
        <thead>
            <tr>
                <th scope="col"># Solicitud</th>
                <th scope="col">Nombre Equipo</th>
                <th scope="col">Solicitante</th>
                <th scope="col">Fecha de Solicitud</th>
                {{-- <th scope="col">Fecha Aprox Devolucion</th> --}}
                <th scope="col">Estado Solicitud</th>
                <th scope="col">Acciones de solicitud</th>

            </tr>
        </thead>
        <tbody>
            @forelse ($solicitudes as $solicitud)
                <tr>
                    <td>{{ $solicitud->id }}</td>
                    <td>
                        @if ($solicitud->equipo)
                            {{ $solicitud->equipo->nombre }}
                        @else
                            ---
                        @endif

                    </td>
                    <td>
                        @if ($solicitud->user)
                            {{ $solicitud->user->name }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>{{ $solicitud->fecha_solicitud }}</td>

                    <td>
                        @if ($solicitud->estado_solicitud == 'Cancelada')
                            <span class="badge text-bg-danger">{{ $solicitud->estado_solicitud }}</span>
                            <small style="color: #555;font-size: 12px;display: block;margin-top: 2px;">
                                {{ $this->obtenerMensajeEstado($solicitud->estado_solicitud) }}
                            </small>
                        @elseif ($solicitud->estado_solicitud == 'Rechazada')
                            <span class="badge text-bg-danger">{{ $solicitud->estado_solicitud }}</span>
                            <small style="color: #555;font-size: 12px;display: block;margin-top: 2px;">
                                {{ $this->obtenerMensajeEstado($solicitud->estado_solicitud) }}
                            </small>
                        @else
                            <strong> {{ $solicitud->estado_solicitud }}</strong><br>
                            <small style="color: #555;font-size: 12px;display: block;margin-top: 2px;">
                                {{ $this->obtenerMensajeEstado($solicitud->estado_solicitud) }}
                            </small>
                        @endif


                    </td>
                    <td>




                        @if (auth()->user()->rol_actual == 'Administrador' || auth()->user()->rol_actual == 'Superadmin')
                            <button class="btn btn-warning" wire:key="modalEditarSolicitudes-{{ $solicitud->id }}"
                                wire:click="modalEditarSolicitudes({{ $solicitud->id }})">Editar</button>
                        @endif

                        @if (auth()->user()->rol_actual == 'Responsable del activo' || auth()->user()->rol_actual == 'Usuario')
                            <button class="btn btn-primary" wire:key="modalVerSolicitudes-{{ $solicitud->id }}"
                                wire:click="modalVerSolicitudes({{ $solicitud->id }})">Ver mas</button>


                            @if (
                                $solicitud->estado_solicitud == 'Devolucion en proceso' ||
                                    $solicitud->estado_solicitud == 'Cancelada' ||
                                    $solicitud->estado_solicitud == 'Rechazada' ||
                                    $solicitud->estado_solicitud == 'Devolucion completada')
                                <button class="btn btn-secondary" title="Devolver equipo" disabled>
                                    Devolver
                                </button>
                            @else
                                {{-- <button class="btn btn-secondary" wire:key="devolverEquipo-{{ $solicitud->id }}"
                                    wire:click="devolverEquipo({{ $solicitud->id }})">Devolver equipo</button> --}}

                                <button class="btn btn-secondary" title="Devolver equipo"
                                    wire:key="devolverEquipo-{{ $solicitud->id }}"
                                    wire:click="devolverEquipo({{ $solicitud->id }})">

                                    Devolver
                                </button>
                            @endif
                        @endif



                        @if (
                            $solicitud->estado_solicitud == 'Cancelada' ||
                                $solicitud->estado_solicitud == 'Rechazada' ||
                                $solicitud->estado_solicitud == 'Devolucion completada')
                            <button class="btn btn-danger ms-2" disabled>Cancelar</button>
                        @else
                            <button class="btn btn-danger ms-2" wire:key="cancelarSolicitud-{{ $solicitud->id }}"
                                wire:click="cancelarSolicitud({{ $solicitud->id }})">Cancelar</button>
                        @endif

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No se encontraron solicitudes</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $solicitudes->links() }}



    @if ($mostrarModal)
        <x-modal-dialog-bootstrap size="xl">
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                {{-- <p class="text-danger">{{ $mensaje }}</p> --}}
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModal', false)"aria-label="Close"></button>
            </x-slot>
            <x-slot name="contenido">
                <form wire:submit="actualizar">
                    @csrf
                    <div class="modal-body">
                        <div class="card shadow-sm mb-4" style="max-height: 350px; overflow-y: auto;">
                            <div class="card-body">
                                <div class="fw-semibold mb-3" style="font-size: 1.1rem;">
                                    Informacion del equipo
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mb-2">
                                        <strong>Numero de activo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->numero_activo : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Serie del activo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->serie : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Modelo del activo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->modelo : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Nombre del activo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->nombre : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Marca del activo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->marca : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Fecha de adquisicion:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->fecha_adquisicion : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Categoria del equipo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->categoria_id->nombre ?? '---' : '---' }}
                                    </div>
                                    <div class="col-md-3 mb-2">
                                        <strong>Laboratorio del equipo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->laboratorio_id->nombre ?? '---' : '---' }}
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-6 mb-2">
                                        <strong>Responsable del equipo:</strong><br>
                                        <span>Nombre:
                                            {{ $equipoForm->equipo_id ? $equipoForm->responsable_id->name ?? '---' : '---' }}</span><br>
                                        <span>Email:
                                            {{ $equipoForm->equipo_id ? $equipoForm->responsable_id->email ?? '---' : '---' }}</span><br>
                                        <span>Telefono:
                                            {{ $equipoForm->equipo_id ? $equipoForm->responsable_id->telefono ?? '---' : '---' }}</span>
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <strong>Condiciones de uso del equipo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->condiciones_uso ?? '---' : '---' }}
                                    </div>
                                </div>
                                <div class="row mt-2">
                                    <div class="col-md-12 mb-2">
                                        <strong>Caracteristicas del equipo:</strong><br>
                                        {{ $equipoForm->equipo_id ? $equipoForm->caracteristicas ?? '---' : '---' }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm mb-4" style="max-height: 350px; overflow-y: auto;">
                            <div class="card-body">
                                <div class="fw-semibold mb-3" style="font-size: 1.1rem;">
                                    Datos del usuario que solicita el equipo:
                                </div>

                                <div class="row mt-3">
                                    <div class="col-md-6 mb-3">
                                        <strong>Fecha de Solicitud:</strong>

                                        <span class="ms-3">
                                            @if ($fecha_solicitud)
                                                {{ $fecha_solicitud }}
                                            @endif
                                        </span>


                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <strong>Fecha Aproximada de Devolucion:</strong>

                                        <span class="ms-3">
                                            @if ($fecha_aproximada_devolucion)
                                                {{ $fecha_aproximada_devolucion }}
                                            @endif
                                        </span>

                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-4">
                                        <strong>Nombre:</strong>
                                        {{ $usuarioForm->name ?? '---' }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Email:</strong>
                                        {{ $usuarioForm->email ?? '---' }}
                                    </div>
                                    <div class="col-md-4">
                                        <strong>Telefono:</strong>
                                        {{ $usuarioForm->telefono ?? '---' }}
                                    </div>


                                </div>
                                <div class="row mt-2">

                                    <div class="col-md-4">
                                        <strong>Roles:</strong>
                                        @foreach ($usuarioForm->rolesSeleccionados as $rol)
                                            {{ $rol ?? '---' }},
                                        @endforeach

                                    </div>

                                </div>


                            </div>
                        </div>

                        @if ($modoEditar)
                            <div class="row mt-3">
                                <div class="col-md-4 mb-3">
                                    <label for="nombre" class="form-label">Fecha exacta de la Devolucion</label>
                                    <input type="date" class="form-control"
                                        wire:model="gestionSolicitud.fecha_exacta_devolucion">
                                    <span class="text-danger">
                                        @error('gestionSolicitud.fecha_exacta_devolucion')
                                            {{ $errors->first('gestionSolicitud.fecha_exacta_devolucion') }}
                                        @enderror
                                    </span>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label">Estado de la solicitud</label>
                                    <select class="form-control" wire:model.live="gestionSolicitud.estado_solicitud"
                                        wire:change="validarEstados">
                                        <option value="" selected disabled>Seleccione un estado</option>
                                        <option value="Pendiente">Pendiente</option>
                                        <option value="Aprobada">Aprobada</option>
                                        <option value="Rechazada">Rechazada</option>
                                        <option value="Equipo en transito">Equipo en transito</option>
                                        <option value="Equipo Asignado">Equipo Asignado</option>
                                        <option value="Devolucion en proceso">Devolucion en proceso</option>
                                        <option value="Devolucion completada">Devolucion completada</option>
                                        <option value="Cancelada">Cancelada</option>
                                    </select>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="estado" class="form-label">Estado del equipo</label>
                                    <select class="form-control" wire:model.live="equipo_estado"
                                        wire:change="validarEstados">
                                        <option value="" selected disabled>Seleccione un estado</option>
                                        <option value="Disponible">Disponible</option>
                                        <option value="Solicitado">Solicitado</option>
                                        <option value="Reservado">Reservado</option>
                                        <option value="En transito">En transito</option>
                                        <option value="Asignado">Asignado</option>
                                        <option value="En Devolucion">En Devolucion</option>
                                        <option value="En mantenimiento">En mantenimiento</option>
                                        <option value="Fuera de servicio">Fuera de servicio</option>
                                    </select>

                                </div>
                                @if ($gestionSolicitud->advertencia_estados)
                                    <div class="alert alert-warning mt-2 d-flex align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16"
                                            fill="currentColor" class="bi bi-exclamation-triangle me-2"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M7.938 2.016A.13.13 0 0 1 8.002 2a.13.13 0 0 1 .063.016.15.15 0 0 1 .054.057l6.857 11.667c.036.06.035.124.002.183a.2.2 0 0 1-.054.06.1.1 0 0 1-.066.017H1.146a.1.1 0 0 1-.066-.017.2.2 0 0 1-.054-.06.18.18 0 0 1 .002-.183L7.884 2.073a.15.15 0 0 1 .054-.057m1.044-.45a1.13 1.13 0 0 0-1.96 0L.165 13.233c-.457.778.091 1.767.98 1.767h13.713c.889 0 1.438-.99.98-1.767z" />
                                            <path
                                                d="M7.002 12a1 1 0 1 1 2 0 1 1 0 0 1-2 0M7.1 5.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0z" />
                                        </svg>
                                        {{ $gestionSolicitud->advertencia_estados }}
                                    </div>
                                @endif

                            </div>

                            <div class="row mt-3">
                                <div class="col-md-12 mb-3">
                                    <label for="nombre" class="form-label">Observaciones de la solicitud y del
                                        equipo</label>
                                    <textarea class="form-control" wire:model="gestionSolicitud.observaciones"
                                        placeholder="Ingrese las observaciones de la solicitud y del equipo"></textarea>
                                </div>
                            </div>
                        @endif
                    </div>




                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="set('mostrarModal', false)">{{ $modoEditar ? 'Cancelar' : 'Cerrar' }}</button>
                        @if ($modoEditar)
                            <button type="submit" class="btn btn-primary">
                                Guardar
                            </button>
                        @endif
                    </div>
                </form>
            </x-slot>

        </x-modal-dialog-bootstrap>
    @endif




</div>
