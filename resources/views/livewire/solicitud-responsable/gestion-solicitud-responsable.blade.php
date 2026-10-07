<div>
    <div class="row mt-3">
        <div class="col-md-8">
            <h2 style="font-size: 1.5rem; font-weight: bold;">Solicitudes para ser Responsable de Activos</h2>
        </div>
        <div class="col-md-4 d-flex justify-content-end">
            <div class="d-flex gap-2 justify-content-end">
                <select class="form-select" wire:model.live="perPage">
                    <option value="10">10</option>
                    <option value="20">20</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
            </div>
        </div>
    </div>

    <div class="row mt-3">
        <div class="col-md-12">
            <input type="text" class="form-control" wire:model.live="buscar"
                placeholder="Buscar por nombre del solicitante...">
        </div>
    </div>

    @if (session()->has('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="table-responsive mt-3">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre del Solicitante</th>
                    <th>Fecha de Solicitud</th>
                    <th>Fecha de Aprobación/Rechazo</th>
                    <th>Estado</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($solicitudes as $solicitud)
                    <tr>
                        <td>{{ $solicitud->id }}</td>
                        <td>{{ $solicitud->responsable?->name ?? 'Sin responsable' }}</td>
                        <td>{{ $solicitud->fecha_solicitud }}</td>
                        <td>{{ $solicitud->fecha_aprobacion ?: '---' }}</td>
                        <td>{{ $solicitud->estado ?: 'Pendiente de aprobar' }}</td>
                        <td>
                            @if ($solicitud->estado == 'pendiente')
                            <button class="btn btn-success btn-sm" 
                            wire:key="aprobarSolicitud-{{ $solicitud->id }}"
                            wire:click="aprobarSolicitud({{ $solicitud->id }})">
                                Aprobar
                            </button>
                            <button class="btn btn-danger btn-sm" 
                            wire:key="rechazarSolicitud-{{ $solicitud->id }}"
                            wire:click="rechazarSolicitud({{ $solicitud->id }})">
                                    Rechazar
                                </button>
                            @else
                            <button class="btn btn-success btn-sm" disabled>
                                Aprobar
                            </button>
                            <button class="btn btn-danger btn-sm" disabled>
                                    Rechazar
                                </button>
                            @endif

                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="text-center">No hay solicitudes pendientes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-3">
        {{ $solicitudes->links() }}
    </div>

    
</div> 
