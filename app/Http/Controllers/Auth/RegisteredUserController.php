<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;
use Spatie\Permission\Models\Role;
use App\Models\SolicitudResponsable;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(): View
    {
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            // 'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'password' => ['required', Rules\Password::defaults()],
            'frase_recuperacion' => ['required', 'string', 'max:255 ', 'min:4'],
            'quiere_ser_responsable' => ['required'],
        ],
        [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'El correo electrónico ya está en uso',
            'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'frase_recuperacion.required' => 'La frase de recuperación es obligatoria',
            'frase_recuperacion.min' => 'La frase de recuperación debe tener al menos 4 caracteres',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'frase_recuperacion' =>  Hash::make($request->frase_recuperacion),
        ])->assignRole('Usuario');

        $rolUsuario = Role::where('name', 'Usuario')->first();
        
        
        if ($rolUsuario) {
            # $user->assignRole($rolUsuario->name);
            $user->rol_actual = $rolUsuario->name;
          
            $user->save();
        }
            

        if ($request->quiere_ser_responsable == 1) {
            SolicitudResponsable::create([
                'fecha_solicitud' => now(),
                'respon_id' => $user->id,
                'estado' => 'pendiente'
            ]);
        }

       

        event(new Registered($user));
      
        

        Auth::login($user);

        //return redirect(route('dashboard', absolute: false));
        return redirect(route('profile.edit', absolute: false));
    }
}
