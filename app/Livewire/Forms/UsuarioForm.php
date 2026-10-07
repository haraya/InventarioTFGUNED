<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UsuarioForm extends Form
{
    
    
    //Atributos para el usuario    
    public $name;
    public $telefono;
    public $email;
    public $password;
    public $frase_recuperacion;
    public $institucion_id = '';
    public $rolesSeleccionados = [];
    public $informacion_adicional;

    //Variable para el usuario_id
    public $usuario_id;
    
    //Metodo para las reglas de validacion
    public function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $this->usuario_id,
            'password' => 'nullable|min:6',
            'frase_recuperacion' => 'nullable|min:4',
            #'institucion_id' => 'required|exists:instituciones,id',
            'rolesSeleccionados' => 'required|array',
            
        ];
    }

    public function messages()
    {
        return [
            'name.required' => 'El nombre es obligatorio',
            'name.max' => 'El nombre no puede tener más de 255 caracteres',
            'email.required' => 'El correo electrónico es obligatorio',
            'email.email' => 'Debe ingresar un correo electrónico válido',
            'email.unique' => 'El correo electrónico ya está en uso',
            #'password.required' => 'La contraseña es obligatoria',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres',
            'rolesSeleccionados.required' => 'Debe seleccionar al menos un rol',
            'rolesSeleccionados.array' => 'Los roles seleccionados deben ser válidos',
            #'frase_recuperacion.required' => 'La frase de recuperación es obligatoria',
            'frase_recuperacion.min' => 'La frase de recuperación debe tener al menos 4 caracteres',
        ];
    }

    //Metodo para crear un usuario
    public function crearUsuario()
    {
        //validar los datos del formulario
        $this->validate();

        if($this->institucion_id == ''){
            $this->institucion_id = null;
        }

        // Preparar los datos del usuario
        $userData = [
            'name' => $this->name,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'informacion_contacto_adicional' => $this->informacion_adicional,
            'rol_actual' => $this->rolesSeleccionados[0],
            'institucion_id' => $this->institucion_id,
        ];

        // Manejar el password
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        // Manejar la frase de recuperación
        if (!empty($this->frase_recuperacion)) {
            $userData['frase_recuperacion'] = Hash::make($this->frase_recuperacion);
        }

        //Creacion del usuario
        $user = User::create($userData)->assignRole($this->rolesSeleccionados);

        $this->reset();
    }


    //Metodo para actualizar un usuario
    public function actualizarUsuario($id)
    {
        $this->validate();

        $institucion_id = $this->institucion_id === '' ? null : $this->institucion_id;

        $user = User::find($id);
      
        $user->syncRoles($this->rolesSeleccionados);
       

        // Preparar los datos básicos del usuario
        $userData = [
            'name' => $this->name,
            'telefono' => $this->telefono,
            'email' => $this->email,
            'informacion_contacto_adicional' => $this->informacion_adicional,
            'rol_actual' => $this->rolesSeleccionados[0],
            'institucion_id' => $institucion_id,
        ];

        // Actualizar password solo si se proporcionó uno nuevo
        if (!empty($this->password)) {
            $userData['password'] = Hash::make($this->password);
        }

        // Actualizar frase de recuperación solo si se proporcionó una nueva
        if (!empty($this->frase_recuperacion)) {
            $userData['frase_recuperacion'] = Hash::make($this->frase_recuperacion);
        }

        $user->update($userData);

        $this->reset();
    }

    //Metodo para eliminar un usuario
    public function eliminarUsuario()
    {
        

        $user = User::find($this->usuario_id);
        $user->delete();

        $this->reset();
        
        
    }

    //Metodo para cargar los datos de un usuario
    public function cargarUsuario($id)
    {
        $user = User::find($id);
        $this->name = $user->name;
        $this->telefono = $user->telefono;
        $this->email = $user->email;
        $this->institucion_id = $user->institucion_id;
        $this->rolesSeleccionados = $user->roles->pluck('name')->toArray();
    }
}
