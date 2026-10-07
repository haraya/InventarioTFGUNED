<div>
    @if (Auth::check())
        @if (session()->has('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>{{ session('success') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <strong>{{ session('error') }}</strong>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

    @endif

    <div class="row mt-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar equipo por nombre, numero de activo o marca">
        </div>
        <div class="col-md-6 d-flex justify-content-end">
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




    <table class="table table-bordered text-center mt-3">
        <thead>
            <tr>
                <th scope="col">Numero de activo</th>
                <th scope="col">Nombre</th>
                <th scope="col">Marca</th>

                @if (Auth::check())
                    <th scope="col">Estado</th>
                @endif

                @if (Auth::check())
                    <th scope="col">Solicitar equipo</th>
                @else
                    <th scope="col">Ver detalles</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->numero_activo }}</td>
                    <td>{{ $equipo->nombre }}</td>
                    <td>{{ $equipo->marca }}</td>

                    @if (Auth::check())
                        <td>
                            <span class="badge text-bg-success">{{ $equipo->estado }}</span>
                        </td>
                    @endif
                    <td>

                        <div class="d-flex gap-2 justify-content-center">
                            @if (Auth::check())
                                <button class="btn btn-primary" wire:key="equipo-{{ $equipo->id }}"
                                    wire:click="modalSolicitud({{ $equipo->id }})">Solicitar
                                </button>
                            @else
                                <button class="btn btn-primary" wire:key="equipo-{{ $equipo->id }}"
                                    wire:click="modalSolicitud({{ $equipo->id }})">
                                    Ver detalles
                                </button>
                            @endif

                        </div>
                    </td>
                </tr>

            @empty
                <tr>
                    <td colspan="7" class="text-center">No se encontraron equipos</td>
                </tr>
            @endforelse
        </tbody>

    </table>

    {{ $equipos->links() }}


    @if ($modalSolicitar)
        <!-- Modal -->
        <x-modal-dialog-bootstrap size="xl">
            <x-slot name="header">
                @if (Auth::check())
                    <h1 class="modal-title fs-5">Crear Solicitud de equipo: {{ $equipoForm['nombre'] }}</h1>
                @else
                    <h1 class="modal-title fs-5">Detalles del equipo: {{ $equipoForm['nombre'] }}</h1>
                @endif
                <button type="button" class="btn-close" wire:click="set('modalSolicitar', false)"
                    aria-label="Close"></button>
            </x-slot>
            <x-slot name="contenido">
                <form wire:submit="generarSolicitud">
                    @csrf
                    <div class="modal-body">

                        <div class="card shadow-sm mb-4" style="max-height: 350px; overflow-y: auto;">
                            <div class="card-body">
                                <div class="fw-semibold mb-3" style="font-size: 1.1rem;">
                                    Informacion del equipo
                                </div>
                                <div class="row">
                                    <div class="col-md-3 mt-3">
                                        <strong>Numero de activo:</strong>
                                        <p>
                                            @if ($equipoForm['numero_activo'])
                                                {{ $equipoForm['numero_activo'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Serie del activo:</strong>
                                        <p>
                                            @if ($equipoForm['serie'])
                                                {{ $equipoForm['serie'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>

                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Modelo del activo:</strong>
                                        <p>
                                            @if ($equipoForm['modelo'])
                                                {{ $equipoForm['modelo'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Nombre del activo:</strong>
                                        <p>
                                            @if ($equipoForm['nombre'])
                                                {{ $equipoForm['nombre'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Marca del activo:</strong>
                                        <p>
                                            @if ($equipoForm['marca'])
                                                {{ $equipoForm['marca'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Fecha de adquisicion:</strong>
                                        <p>
                                            @if ($equipoForm['fecha_adquisicion'])
                                                {{ $equipoForm['fecha_adquisicion'] }}
                                            @else
                                                ---
                                            @endif
                                    </div>

                                    <div class="col-md-3 mt-3">
                                        <strong>Categoria del equipo:</strong>
                                        <p>
                                            @if ($equipoForm['categoria'])
                                                {{ $equipoForm['categoria']->nombre }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>

                                    {{-- <div class="col-md-3 mt-3">
                                                <strong>Estado del activo:</strong>
                                                @if ($infoEquipo['estado'])
                                                    <p>{{ $infoEquipo['estado'] }}</p>
                                                @else
                                                    No asignado
                                                @endif
                                            </div> --}}

                                    <div class="col-md-3 mt-3">
                                        <strong>Laboratorio del equipo:</strong>
                                        <p>
                                            @if ($equipoForm['laboratorio'])
                                                @if ($equipoForm['laboratorio']->visible == true)
                                                    {{ $equipoForm['laboratorio']->nombre }}
                                                @else
                                                    ---
                                                @endif



                                            @endif
                                        </p>
                                    </div>

                                </div>

                                <div class="row">


                                    <div class="col-md-6 mt-3">
                                        <strong>Responsable del equipo:</strong>
                                        <p>
                                            @if ($equipoForm['responsable'])
                                                <strong>Nombre:</strong> {{ $equipoForm['responsable']->name }}
                                                <br>
                                                <strong>Email:</strong> {{ $equipoForm['responsable']->email }}
                                                <br>
                                                <strong>Telefono:</strong>
                                                {{ $equipoForm['responsable']->telefono }}
                                            @else
                                                ---
                                            @endif
                                        </p>


                                    </div>
                                    <div class="col-md-6 mt-6">
                                        <strong>Condiciones de uso del equipo:</strong>
                                        <p>
                                            @if ($equipoForm['condiciones_uso'])
                                                {{ $equipoForm['condiciones_uso'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>
                                </div>


                                <div class="row">
                                    <div class="col-md-12 mt-6">
                                        <strong>Caracteristicas del equipo:</strong>
                                        <p>
                                            @if ($equipoForm['caracteristicas'])
                                                {{ $equipoForm['caracteristicas'] }}
                                            @else
                                                ---
                                            @endif
                                        </p>
                                    </div>


                                </div>

                            </div>
                        </div>






                        @if (Auth::check())
                            <hr>
                            <div class="row mt-3">
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Fecha de Solicitud</label>
                                    <input type="date" class="form-control"
                                        wire:model="solicitudEquipoForm.fecha_solicitud">
                                    @error('solicitudEquipoForm.fecha_solicitud')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="nombre" class="form-label">Fecha Aproximada de Devolucion</label>
                                    <input type="date" class="form-control"
                                        wire:model="solicitudEquipoForm.fecha_aproximada_devolucion">
                                    @error('solicitudEquipoForm.fecha_aproximada_devolucion')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        @endif
                    </div>



                    @if (Auth::check())
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary"
                                wire:click="set('modalSolicitar', false)">Cancelar</button>
                            <button type="submit" class="btn btn-primary">Generar solicitud</button>
                        </div>
                    @endif

                </form>
            </x-slot>

        </x-modal-dialog-bootstrap>
    @endif


</div>
