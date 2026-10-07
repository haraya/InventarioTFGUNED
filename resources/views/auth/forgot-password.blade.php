<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        {{ __('¿Olvidaste tu contraseña? No hay problema. Solo indícanos tu correo electrónico y tu frase de recuperación para restablecer tu contraseña y podrás elegir una nueva.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    {{--<form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>--}}

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Frase de recuperación -->
        <div>
            <x-input-label for="email" :value="__('Correo electrónico')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <x-input-label for="frase_recuperacion" :value="__('Frase de recuperación')" />
            <x-text-input id="frase_recuperacion" class="block mt-1 w-full" type="text" name="frase_recuperacion" :value="old('frase_recuperacion')" required autofocus />
            <x-input-error :messages="$errors->get('frase_recuperacion')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
           {{-- <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>--}}

            <x-primary-button class="mr-2">
                <a href="{{ route('login') }}">
                    {{ __('Volver') }}
                </a>
            </x-primary-button>

            <x-primary-button>
                {{ __('Enviar') }}
            </x-primary-button>
        </div>
    </form>






</x-guest-layout>
