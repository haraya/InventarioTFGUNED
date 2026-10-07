<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" wire:click="modalCrearInstitucion">
                Nueva Institucion
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <p class="text-muted">
                <strong>
                    Total de instituciones: {{ $instituciones_count }}
                </strong>
            </p>
        </div>
    </div>




    <div class="row mt-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar institucion por nombre">
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
                <th scope="col">#</th>
                <th scope="col">Nombre</th>
                <th scope="col">Estado</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($instituciones as $institucion)
                <tr>
                    <td>{{ $institucion->id }}</td>
                    <td>{{ $institucion->nombre }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            @if ($institucion->visible)
                                <span class="badge text-bg-success">Visible</span>
                            @else
                                <span class="badge rounded-pill text-bg-danger">Oculto</span>
                            @endif

                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" role="switch"
                                    id="switchCheckChecked{{ $institucion->id }}"
                                    wire:click="actualizarVisibilidad({{ $institucion->id }})"
                                    @if ($institucion->visible) checked @endif>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-warning" 
                                wire:key="editar-{{ $institucion->id }}"
                                wire:click="modalEditarInstitucion({{ $institucion->id }})">
                                Editar
                            </button>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="text-center">No hay instituciones</td>
                </tr>
            @endforelse
        </tbody>
    </table>


    {{ $instituciones->links() }}


    @if ($mostrarModal)
        <!-- Modal de crear/editar institucion -->
        <x-modal-dialog-bootstrap>
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModal', false)"aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="{{ $modoEditar ? 'actualizar' : 'crear' }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Institucion</label>
                            <input type="text" class="form-control" wire:model.live="institucionForm.nombre"
                                placeholder="Nombre de la institucion">
                        </div>
                        @error('institucionForm.nombre')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="set('mostrarModal', false)">Cerrar</button>
                        <button type="submit"
                            class="btn btn-primary">{{ $modoEditar ? 'Actualizar' : 'Guardar' }}</button>
                    </div>
                </form>
            </x-slot>
        </x-modal-dialog-bootstrap>
    @endif

</div>
