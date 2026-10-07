<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Seleccionar Rol') }} 
        </h2>
        
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <form action="{{ route('roles.cambiar') }}" method="POST">
                    @csrf

                    <div class="mb-4">
                    <h1>
                        Cambiar de Rol
                        <label for="rol" class="block text-sm font-medium text-gray-700">
                          
                        </label>
                    </h1>
                        <select name="rol" id="rol"
                                class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-500">
                            @foreach ($roles as $role)
                                <option value="{{ $role }}" {{ $rolActual === $role ? 'selected' : '' }}>
                                    {{ ucfirst($role) }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Cambiar Rol
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
