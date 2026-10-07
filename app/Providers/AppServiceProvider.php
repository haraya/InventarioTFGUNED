<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Auth;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
       /* View::composer('*', function ($view) {
            $rolActual = session('rol_actual');
    
            // Si no hay en sesión y el usuario está logueado, usamos el valor de la BD
            if (!$rolActual && Auth::check()) {
                $rolActual = Auth::user()->rol_actual;
                session(['rol_actual' => $rolActual]);
            }
    
            $view->with('rol_actual', $rolActual);
        });*/
    }
}
