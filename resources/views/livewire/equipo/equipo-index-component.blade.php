<div>
    <div>
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
                    <th scope="col">Ver detalles</th>

                </tr>
            </thead>
            <tbody>
                @forelse ($equipos as $equipo)
                    <tr>
                        <td>{{ $equipo->numero_activo }}</td>
                        <td>{{ $equipo->nombre }}</td>
                        <td>{{ $equipo->marca }}</td>


                        <td>

                            <div class="d-flex gap-2 justify-content-center">

                                <button class="btn btn-primary" wire:key="equipo-{{ $equipo->id }}"
                                    wire:click="modalVistaEquipo({{ $equipo->id }})">
                                    Ver detalles
                                </button>


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


        @if ($modalVisualizar)
            <!-- Modal -->
            <x-modal-dialog-bootstrap size="xl">
                <x-slot name="header">

                    <h1 class="modal-title fs-5">Detalles del equipo: {{ $equipoForm['nombre'] }}</h1>

                    <button type="button" class="btn-close" wire:click="set('modalVisualizar', false)"
                        aria-label="Close"></button>
                </x-slot>
                <x-slot name="contenido">

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







                    </div>



                </x-slot>

            </x-modal-dialog-bootstrap>
        @endif


    </div>

</div>
