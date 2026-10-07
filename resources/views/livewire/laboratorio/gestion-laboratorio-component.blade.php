<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif


    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" wire:click="modalCrearLaboratorio">
                Nuevo Laboratorio
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <p class="text-muted">Total de laboratorios: {{ $laboratorios_count }}</p>
        </div>


    </div>


    <div class="row mt-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar laboratorio por nombre o institucion">
        </div>
        <div class="col-md-6 d-flex justify-content-end">
            <div class="d-flex gap-2 justify-content-end">
                <select class="form-select" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>

                {{--  <select class="form-select" wire:model.live="sort">
                    <option value="asc">Ascendente</option>
                    <option value="desc">Descendente</option>
                </select>  --}}
            </div>
        </div>

    </div>



    <table class="table table-bordered text-center mt-3">
        <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Laboratorio</th>

                <th scope="col"> Sede</th>
                <th scope="col"> Institucion</th>
                <th scope="col">Estado Laboratorio</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($laboratorios as $laboratorio)
                <tr>

                    <td>{{ $laboratorio->id }}</td>

                    <td>
                        {{ $laboratorio->nombre }}

                    </td>
                    <td>
                        @if ($laboratorio->sede && $laboratorio->sede->visible)
                            {{ $laboratorio->sede->nombre }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if ($laboratorio->institucion && $laboratorio->institucion->visible)
                            {{ $laboratorio->institucion->nombre }}
                        @else
                            ---
                        @endif

                    </td>





                    <td>

                        <div class="d-flex gap-2 justify-content-center">
                            @if ($laboratorio->visible)
                                <span class="badge text-bg-success">Visible</span>
                            @else
                                <span class="badge rounded-pill text-bg-danger">Oculto</span>
                            @endif


                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="switchCheckChecked{{ $laboratorio->id }}"
                                    wire:click="actualizarVisibilidad({{ $laboratorio->id }})"
                                    @if ($laboratorio->visible) checked @endif>
                            </div>



                        </div>





                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-warning" 
                                wire:key="editar-{{ $laboratorio->id }}"
                                wire:click="modalEditarLaboratorio({{ $laboratorio->id }})">Editar</button>
                        </div>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No hay laboratorios</td>
                </tr>
            @endforelse
        </tbody>
    </table>


    {{ $laboratorios->links() }}

    @if ($mostrarModal)
        <!-- Modal de crear/editar laboratorio -->
        <x-modal-dialog-bootstrap>
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModal', false)" aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="{{ $modoEditar ? 'actualizar' : 'crear' }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre del Laboratorio</label>
                            <input type="text" class="form-control" wire:model.live="laboratorioForm.nombre"
                                placeholder="Nombre del Laboratorio">
                            <div>
                                @error('laboratorioForm.nombre')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="institucion_id" class="form-label">Seleccione una Institucion o una Sede</label>
                            <select class="form-select" wire:model="laboratorioForm.institucion_id">
                                <option value="" disabled selected>Seleccione una institucion</option>
                                @foreach ($instituciones as $institucion)
                                    @if ($institucion->visible)
                                        <option value="{{ $institucion->id }}">{{ $institucion->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div>
                                @error('laboratorioForm.institucion_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            {{--  <label for="sede_id" class="form-label">Seleccione una Sede</label>  --}}
                            <select class="form-select" wire:model="laboratorioForm.sede_id">
                                <option value="" disabled selected>Seleccione una sede</option>
                                @foreach ($sedes as $sede)
                                    @if ($sede->visible)
                                        <option value="{{ $sede->id }}">{{ $sede->nombre }}</option>
                                    @endif
                                @endforeach
                            </select>
                            <div>
                                @error('laboratorioForm.sede_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="set('mostrarModal', false)">Cerrar</button>
                        <button type="submit" class="btn btn-primary">
                            {{ $modoEditar ? 'Actualizar' : 'Guardar' }}
                        </button>
                    </div>
                </form>
            </x-slot>

        </x-modal-dialog-bootstrap>
    @endif


</div>
