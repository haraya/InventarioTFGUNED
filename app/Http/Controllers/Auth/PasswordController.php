<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class PasswordController extends Controller
{
    public function updateFraseRecuperacion(Request $request): RedirectResponse
    {
       if (!Hash::check($request->frase_recuperacion_actual, $request->user()->frase_recuperacion)) {
            return back()->with('errorActual', 'La frase de recuperación actual es incorrecta.');
        }

        if ($request->frase_recuperacion_actual === $request->frase_recuperacion_nueva) {
            return back()->with('error', 'La nueva frase de recuperación no puede ser igual a la actual.');
        }

      $request->user()->update([
            'frase_recuperacion' => Hash::make($request->frase_recuperacion_nueva),
      ]);

       return back()->with('status', 'frase-recuperacion-updated');
      
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ],[
            'current_password.required' => 'La contraseña actual es requerida.',
            'current_password.current_password' => 'La contraseña actual es incorrecta.',
            'password.required' => 'La nueva contraseña es requerida.',
            'password.confirmed' => 'Las contraseñas no coinciden.',
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('status', 'password-updated');
    }
}
