<section>
    <header>
        <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
            {{ __('Actualizar frase de recuperacion') }}
        </h2>

        <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Asegúrese de que su cuenta utilice una frase de recuperación larga y aleatoria para mantener su seguridad.') }}
        </p>
    </header>
    
    <form method="post" action="{{ route('frase-recuperacion.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('put')

       <div class="row">
        <div class="col-6">
            <x-input-label for="frase_recuperacion_actual" :value="__('Frase de recuperación Actual')" />
            <x-text-input id="frase_recuperacion_actual" name="frase_recuperacion_actual" type="text" class="mt-1 block w-full"  autofocus autocomplete="frase_recuperacion_actual" required />    
            @if (session('errorActual'))
                <p class="mt-2" style="color: red;">{{ session('errorActual') }}</p>
            @endif
        </div>

      


            <div class="col-6">
                <x-input-label for="frase_recuperacion_nueva" :value="__('Nueva Frase de recuperación ')" />
                <x-text-input id="frase_recuperacion_nueva" name="frase_recuperacion_nueva" type="text" class="mt-1 block w-full"  autofocus autocomplete="frase_recuperacion_nueva" required />
                @if (session('error'))
                    <p class="mt-2" style="color: red;">{{ session('error') }}</p>
                @endif
            </div>
       </div>

        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Guardar') }}</x-primary-button>

            @if (session('status') === 'frase-recuperacion-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600 dark:text-gray-400"
                >{{ __('Guardado con éxito.') }}</p>
            @endif
        </div>
    </form>
</section>


