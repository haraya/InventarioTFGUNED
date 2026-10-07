<div>
    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <strong>{{ session('success') }}</strong>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" wire:click="modalCrearCategoria">
                Nueva Categoria
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <p class="text-muted">Total de categorias: {{ $categorias_count }}</p>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar categoria por nombre">
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
                {{-- <th scope="col">#</th> --}}
                <th scope="col">Nombre</th>
                <th scope="col">Acciones</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($categorias as $categoria)
                <tr>
                   {{-- <td>{{ $categoria->id }}</td> --}}
                    <td>{{ $categoria->nombre }}</td>
                    <td>
                        <div class="d-flex gap-2 justify-content-center">
                            <button class="btn btn-warning" 
                                wire:key="editarCategoria-{{ $categoria->id }}"
                                wire:click="modalEditarCategoria({{ $categoria->id }})">
                                Editar
                            </button>

                            <button class="btn btn-danger" 
                                wire:key="eliminarCategoria-{{ $categoria->id }}"
                                wire:click="modalEliminarCategoria({{ $categoria->id }})">
                                Eliminar
                            </button>
                        </div>

                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="text-center">No hay categorias</td>
                </tr>
            @endforelse
        </tbody>
    </table>


    {{ $categorias->links() }}


    @if ($mostrarModal)
        <!-- Modal para crear/editar categoria -->
        <x-modal-dialog-bootstrap>
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                <button type="button" class="btn-close" wire:click="set('mostrarModal', false)"
                    aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="{{ $modoEditar ? 'actualizar' : 'crear' }}">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label">Nombre de la Categoria</label>
                            <input type="text" class="form-control" wire:model="categoriaForm.nombre"
                                placeholder="Nombre de la categoria">
                            <div>
                                @error('categoriaForm.nombre')
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


    @if ($mostrarModalEliminar)
        <!-- Modal para eliminar categoria -->
        <x-modal-dialog-bootstrap>
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModalEliminar', false)"aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="eliminar">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-center">
                                ¿Esta seguro que desea eliminar la categoria:
                                <strong>{{ $categoriaForm->nombre }}</strong>?
                            </label>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary"
                            wire:click="set('mostrarModalEliminar', false)">Cerrar</button>
                        <button type="submit" class="btn btn-danger">Eliminar</button>
                    </div>
                </form>
            </x-slot>
        </x-modal-dialog-bootstrap>
    @endif

</div>
