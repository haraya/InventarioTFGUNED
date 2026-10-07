<x-guest-layout>
    
        <form method="POST" action="{{ route('register') }}">
            @csrf
    
            <!-- Name -->
         
                
            <div>
                <x-input-label for="name" :value="__('Nombre completo')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>
           
     
    
            <!-- Email Address -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('Correo Electrónico')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>
    
            <!-- Password -->
            <div class="mt-4">
                <x-input-label for="password" :value="__('Contraseña')" />
    
                <x-text-input id="password" class="block mt-1 w-full"
                                type="password"
                                name="password"
                                required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
    
            <!-- Confirm Password -->
            {{--<div class="mt-4">
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
    
                <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                type="password"
                                name="password_confirmation" required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>--}}
    
            <div class="mt-4">
                <x-input-label for="frase_recuperacion" :value="__('Frase de recuperacion')" />
    
                <x-text-input id="frase_recuperacion" class="block mt-1 w-full"
                                type="text"
                                name="frase_recuperacion" required autocomplete="new-password" />
    
                <x-input-error :messages="$errors->get('frase_recuperacion')" class="mt-2" />
            </div>
    
            <div class="mt-4">
                <x-input-label for="quiere_ser_responsable" :value="__('¿Eres responsable de un equipo o activo de investigacion?')" />
    
                <select name="quiere_ser_responsable" id="quiere_ser_responsable" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full" required>
                    <option value="" disabled selected>Seleccione una opción</option>
                    <option value="1">Si</option>
                    <option value="0">No</option>
                </select>
    
                <x-input-error :messages="$errors->get('quiere_ser_responsable')" class="mt-2" />
            </div>
    
            <div class="flex items-center justify-between mt-4">
                <x-primary-button>
            
                    <a href="/">
                        {{ __('Volver') }}
                    </a>
                </x-primary-button>
    
                <div class="flex items-center">
                    <a class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800" 
                    href="{{ route('login') }}">
                        {{ __('¿Estás registrado?') }}
                    </a>
        
                    <x-primary-button class="ms-4">
                        {{ __('Registrar') }}
                    </x-primary-button>
    
                </div>
            </div>
        </form>
   
    
</x-guest-layout>
