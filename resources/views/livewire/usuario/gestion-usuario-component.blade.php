<div>
    {{-- Stop trying to control. --}}
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
            <button type="button" class="btn btn-primary mb-3" wire:click="modalCrearUsuario">
                Nuevo Usuario
            </button>
        </div>

        <div class="col-md-6 d-flex justify-content-end">
           {{-- <p class="text-muted">Total de usuarios: {{ $usuarios_count }}</p>--}}
        </div>


    </div>

    <div class="row mt-3">
        <div class="col-md-6">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar usuario por nombre o correo electronico">
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








    <table class="table table-bordered mt-3 text-center">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Institucion</th>
                <th>Roles</th>
                <th>Editar</th>
                @if(Auth::user()->rol_actual === 'Superadmin')
                    <th>Eliminar</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse ($usuarios as $usuario)
                <tr>
                    <td>{{ $usuario->name }}</td>
                    <td>{{ $usuario->email }}</td>
                    <td>
                        @if ($usuario->institucion)
                            {{ $usuario->institucion->nombre }}
                        @else
                            ---
                        @endif
                    </td>
                    <td>
                        @if ($usuario->roles->isNotEmpty())
                            @foreach ($usuario->roles as $rol)
                                <span class="badge bg-info text-dark">{{ $rol->name }}</span>
                            @endforeach
                        @else
                            <span class="text-muted">Sin rol</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-primary" 
                            wire:key="editarUsuario{{ $usuario->id }}"
                            wire:click="modalEditarUsuario({{ $usuario->id }})">
                            Editar
                        </button>
                    </td>
                    @if(Auth::user()->rol_actual === 'Superadmin')
                        <td>
                            @if ($usuario->solicitudes()->exists())
                                <span class="text-muted">
                                    No se puede eliminar el usuario <br> porque esta asignado a una solicitud.
                                </span>
                            @else
                                <button class="btn btn-danger" 
                                wire:key="eliminarUsuario{{ $usuario->id }}"
                                wire:click="modalEliminarUsuario({{ $usuario->id }})">Eliminar</button>
                            @endif
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="{{ Auth::user()->rol_actual === 'Superadmin' ? '6' : '5' }}" class="text-center">No hay usuarios registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    {{ $usuarios->links() }}


    @if ($mostrarModal)
        <div class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" Label">{{$modalTitulo}}</h1>
                        <button type="button" class="btn-close" wire:click="set('mostrarModal', false)"></button>
                    </div>
                    <form wire:submit="{{$modoEditar ? 'actualizar' : 'crear'}}">
                        @csrf
                        <div class="modal-body">
                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Nombre Completo</label>
                                    <input type="text" class="form-control" id="name"
                                        wire:model="UsuarioForm.name" placeholder="Escriba su nombre completo">
                                    @error('UsuarioForm.name')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>

                                <div class="mb-3 col-6">
                                    <label for="emial" class="form-label">Telefonos de Contacto</label>
                                    <input type="text" class="form-control" id="telefono"
                                        wire:model="UsuarioForm.telefono" placeholder="Escriba su numero de telefono">
                                    @error('UsuarioForm.telefono')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Correo Electrónico Principal</label>
                                    <input type="email" class="form-control" id="email"
                                        wire:model="UsuarioForm.email" placeholder="Escriba su correo principal">
                                    @error('UsuarioForm.email')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>


                                <div class="mb-3 col-6">
                                    <label class="form-label">Seleccione la
                                        institución a la que pertenece</label>
                                    <select class="form-select" wire:model="UsuarioForm.institucion_id">
                                        <option value="" disabled selected>Seleccione una institución</option>
                                        @foreach ($instituciones as $institucion)
                                            @if ($institucion->visible)
                                                <option value="{{ $institucion->id }}">{{ $institucion->nombre }}
                                                </option>
                                            @endif
                                        @endforeach
                                    </select>

                                </div>






                            </div>

                            <div class="row">

                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Contraseña Temporal</label>
                                    <input type="text" class="form-control" id="password"
                                        wire:model="UsuarioForm.password" 
                                        placeholder="Escriba una contraseña temporal">
                                    @error('UsuarioForm.password')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror

                                </div>

                                <div class="mb-3 col-6">
                                    <label for="nombre" class="form-label">Frase de Recuperacion Temporal</label>

                                    <input type="text" class="form-control" id="frase_recuperacion"
                                        wire:model="UsuarioForm.frase_recuperacion" 
                                        placeholder="Escriba una frase de recuperacion temporal">
                                    @error('UsuarioForm.frase_recuperacion')
                                        <span class="text-danger">{{ $message }}</span>
                                    @enderror
                                </div>





                            </div>


                            <div class="row">
                                <div class="mb-3 col-12">
                                    <label for="exampleFormControlTextarea1" class="form-label">Informacion Adicional
                                        del Usuario</label>
                                    <textarea class="form-control"
                                        id="exampleFormControlTextarea1"placeholder="Escriba otra informacion adicional del usuario" rows="2"
                                        wire:model="UsuarioForm.informacion_adicional"></textarea>
                                </div>
                            </div>





                            <div class="mb-3">
                                <label for="nombre" class="form-label">Seleccione los roles:</label>
                                @foreach ($roles as $role)
                                    <div class="justify-content-start d-flex">
                                        <div class="form-check">
                                            
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="UsuarioForm.rolesSeleccionados"
                                                    value="{{ $role->name }}" id="role_{{ $role->id }}">

                                                <label class="form-check-label" for="">
                                                    {{ $role->name }}
                                                </label>
                                           

                                        </div>
                                    </div>
                                @endforeach
                                @error('UsuarioForm.rolesSeleccionados')
                                    <span class="text-danger">{{ $message }}</span>
                                @enderror


                            </div>

                        </div>
                        <div class="modal-footer">


                            <button type="button" class="btn btn-secondary"
                                wire:click="set('mostrarModal', false)">Cancelar</button>

                            <button type="submit" class="btn btn-primary">{{$modoEditar ? 'Actualizar' : 'Guardar'}}</button>

                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endif



    @if ($mostrarModalEliminar)
        <!-- Modal -->
        <x-modal-dialog-bootstrap>
            <x-slot name="header">
                <h1 class="modal-title fs-5">{{$modalTitulo}}</h1>
                <button type="button" class="btn-close"
                    wire:click="set('mostrarModalEliminar', false)"aria-label="Close"></button>
            </x-slot>

            <x-slot name="contenido">
                <form wire:submit="eliminar">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="nombre" class="form-label text-center">
                                ¿Esta seguro que desea eliminar el usuario: <strong>{{ $UsuarioForm->name }}</strong>?
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
