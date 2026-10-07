<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RolController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $roles = $user->roles->pluck('name'); 
        
        $rolActual = session('rol_actual', $roles->first()); // predeterminado
      //  dd($rolActual);
    return view('roles.index', compact('roles', 'rolActual'));
    }

    public function cambiarRol(Request $request)
    {
        $request->validate([
            'rol' => 'required|string'
        ]);

        $rol = $request->rol;
        //dd($rol);

        /**@var User $user */
        $user = Auth::user();
        $user->rol_actual = $rol;
        $user->save();

        // Guardar rol en sesión (temporal)
        session(['rol_actual' => $rol]);

        // Redirigir al dashboard según rol
        return match ($rol) {
            'Superadmin' => redirect()->route('admin.dashboard'),
            'Administrador' => redirect()->route('admin.dashboard'),
            'Responsable del activo' => redirect()->route('responsable_activos.dashboard'),
            'Usuario' => redirect()->route('dashboard'),
        };
    }

    public function superAdmin()
    {
        return view('admin.dashboard');
    }

    public function admin()
    {
        return view('admin.dashboard');
    }

    public function responsable_activos()
    {
        return view('responsable_activos.dashboard');
    }
}
