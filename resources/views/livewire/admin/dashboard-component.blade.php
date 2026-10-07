<div>
    <!-- Menú de navegación y botón de exportar -->
    <div class="mb-6 flex items-center justify-between bg-white dark:bg-gray-800 p-4 rounded-lg shadow-sm">
        <nav class="flex space-x-4">
            <button wire:click="$set('activeTab', 'equipos')" 
                    class="px-4 py-2 rounded-md {{ $activeTab === 'equipos' ? 'btn btn-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Solicitudes de Equipos
            </button>
            <button wire:click="$set('activeTab', 'responsables')" 
                    class="px-4 py-2 rounded-md {{ $activeTab === 'responsables' ? 'btn btn-primary text-white' : 'text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700' }}">
                Solicitudes de Responsables
                @if($pendientesResponsables > 0)
                    <span class="badge bg-danger ms-2">{{ $pendientesResponsables }}</span>
                @endif
            </button>
        </nav>
        <div>
            <livewire:reportes.reporte-solicitudes-pdf  />
        </div>
    </div>

    <!-- Contenido dinámico -->
    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900 dark:text-gray-100">
            @if($activeTab === 'equipos')
                <livewire:solicitud.gestion-solicitud-component />
            @else
                <livewire:solicitud-responsable.gestion-solicitud-responsable />
            @endif
        </div>
    </div>
</div> 