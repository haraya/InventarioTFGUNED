<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class VerificarRol
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $rol): Response
    {
        /*if(Auth::user()->rol_actual !== $rol){
            abort(403, 'Acceso no autorizado.');
        }*/

        $roles = explode('|', $rol);
        if (!in_array(Auth::user()->rol_actual, $roles)) {
            abort(403, 'Acceso no autorizado.');
        }
       
        #return redirect()->back();
        return $next($request);
    }
}
