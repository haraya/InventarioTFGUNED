<div>

    {{-- @if (auth()->user()->rol_actual == 'Administrador')
        <button type="button" class="btn btn-primary" wire:click="abrirModal">
            Nuevo Equipo
        </button>
    @endif --}}


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

    <div class="d-flex justify-content-between">
        <div class="d-flex gap-2">
            <button type="button" class="btn btn-primary" wire:click="modalCrearEquipo">
                Nuevo Equipo
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
            <p class="text-muted">Total de equipos: {{ $equipos_count }}</p>
        </div>
    </div>

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
                <th scope="col">Numero Activo</th>
                <th scope="col">Nombre</th>
                <th scope="col">Marca</th>
                <th scope="col">Estado</th>
                <th scope="col">Categoria</th>

                <th scope="col">Editar</th>
                <th scope="col">Eliminar</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($equipos as $equipo)
                <tr>
                    <td>{{ $equipo->numero_activo }}</td>
                    <td>{{ $equipo->nombre }}</td>
                    <td>{{ $equipo->marca }}</td>
                    <td>
                        {{ $equipo->estado }}
                    </td>
                    <td>
                        @if ($equipo->categoria === null || $equipo->categoria->deleted_at !== null)
                            ---
                        @else
                            {{ $equipo->categoria->nombre ?? 'No asignado' }}
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-warning" wire:click="modalEditarEquipo({{ $equipo->id }})"
                            wire:key="editarEquipo-{{ $equipo->id }}">
                            Editar
                        </button>
                    </td>
                    <td>
                        @if ($equipo->solicitud()->exists())
                            <div class="d-flex gap-2 d-flex justify-content-center ">
                                <span class="text-muted">
                                    No se puede eliminar este equipo,<br> ya que esta asociado a una solicitud.
                                </span>
                            </div>
                        @else
                            <button class="btn btn-danger" 
                                wire:click="modalEliminarEquipo({{ $equipo->id }})"
                                wire:key="eliminarEquipo-{{ $equipo->id }}">Eliminar
                            </button>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No hay equipos</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $equipos->links() }}


    @if ($mostrarModal)
        <!-- Modal para crear/editar un equipo -->
        <x-modal-dialog-bootstrap size="xl">
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{ $modalTitulo }}</h1>
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModal', false)"aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="{{ $modoEditar ? 'actualizar' : 'crear' }}">
                    @csrf
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mt-3">
                                <label for="nombre" class="form-label">Numero de activo</label>
                                <input type="text" class="form-control" wire:model="equipoForm.numero_activo"
                                    placeholder="Numero de activo del equipo">
                                @error('equipoForm.numero_activo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="serie" class="form-label">Serie del activo</label>
                                <input type="text" class="form-control" wire:model="equipoForm.serie"
                                    placeholder="Serie del activo">
                                @error('equipoForm.serie')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="modelo" class="form-label">Modelo del activo</label>
                                <input type="text" class="form-control" wire:model="equipoForm.modelo"
                                    placeholder="Modelo del activo">
                                @error('equipoForm.modelo')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="nombre" class="form-label">Nombre del activo</label>
                                <input type="text" class="form-control" wire:model="equipoForm.nombre"
                                    placeholder="Nombre del activo">
                                @error('equipoForm.nombre')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="marca" class="form-label">Marca del activo</label>
                                <input type="text" class="form-control" wire:model="equipoForm.marca"
                                    placeholder="Marca del activo">
                                @error('equipoForm.marca')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="fecha_adquisicion" class="form-label">Fecha de adquisicion</label>
                                <input type="date" class="form-control" wire:model="equipoForm.fecha_adquisicion"
                                    placeholder="Fecha de adquisicion del activo">
                                @error('equipoForm.fecha_adquisicion')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                        </div>
                        <div class="row">


                            <div class="col-md-4 mt-3">
                                <label for="estado" class="form-label">Estado del activo</label>
                                <select class="form-control" wire:model="equipoForm.estado">
                                    <option value="" selected disabled>Seleccione un estado</option>
                                    <option value="Disponible">Disponible</option>
                                    @if (Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin')
                                        <option value="Reservado">Reservado</option>
                                        <option value="Solicitado">Solicitado</option>
                                        <option value="En transito">En transito</option>
                                        <option value="Asignado">Asignado</option>
                                        <option value="Devolucion">Devolucion</option>
                                    @endif
                                    <option value="En mantenimiento">En mantenimiento</option>
                                    <option value="Fuera de servicio">Fuera de servicio</option>
                                </select>
                                @error('equipoForm.estado')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>





                            <div class="col-md-4 mt-3">
                                <label for="estado" class="form-label">Categoria del equipo</label>
                                <select class="form-control" wire:model="equipoForm.categoria_id">
                                    <option value='' selected disabled>Seleccione una categoria</option>
                                    @foreach ($categorias as $categoria)
                                        <option value="{{ $categoria->id }}">{{ $categoria->nombre }}</option>
                                    @endforeach
                                </select>
                                @error('equipoForm.categoria_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="col-md-4 mt-3">
                                <label for="estado" class="form-label">Laboratorio al que pertenece el
                                    equipo</label>
                                <select class="form-control" wire:model="equipoForm.laboratorio_id">
                                    <option value="" selected disabled>Seleccione un laboratorio</option>
                                    @foreach ($laboratorios as $laboratorio)
                                        @if ($laboratorio->visible)
                                            <option value="{{ $laboratorio->id }}">{{ $laboratorio->nombre }}
                                            </option>
                                        @endif
                                    @endforeach
                                </select>
                                @error('equipoForm.laboratorio_id')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror
                            </div>



                        </div>

                        @if (Auth::user()->rol_actual == 'Administrador' || Auth::user()->rol_actual == 'Superadmin')
                            <div class="row">
                                <div class="col-md-12 mt-3">
                                    <label for="estado" class="form-label">Responsable del equipo</label>
                                    <select class="form-control" wire:model="equipoForm.responsable_id">
                                        <option value="" selected disabled>Seleccione un responsable</option>
                                        @foreach ($usuarios as $usuario)
                                            @if ($usuario->roles->contains('name', 'Responsable del activo'))
                                                <option value="{{ $usuario->id }}">{{ $usuario->name }}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    @error('equipoForm.responsable_id')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                            </div>
                        @endif

                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="estado" class="form-label">Caracteristicas del equipo</label>
                                <textarea class="form-control rounded-3" wire:model="equipoForm.caracteristicas"
                                    placeholder="Descripcion de las caracteristicas del equipo"></textarea>
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="estado" class="form-label">Condiciones de uso del equipo</label>
                                <textarea class="form-control rounded-3" wire:model="equipoForm.condiciones_uso"
                                    placeholder="Esciba las condiciones de uso para el equipo"></textarea>
                            </div>

                        </div>

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

    @if ($mostrarModalEliminar)
        <!-- Modal -->
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
                                ¿Esta seguro que desea eliminar el equipo: <strong>{{ $equipoForm->nombre }}</strong>?
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
