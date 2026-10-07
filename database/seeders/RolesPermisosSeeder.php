<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Hash;


class RolesPermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Creacion de permisos del sistema
        Permission::create(['name' => 'gestionar administradores']);
        Permission::create(['name' => 'gestionar instituciones']);
        Permission::create(['name' => 'gestionar sedes']);
        Permission::create(['name' => 'gestionar laboratorios']);
        Permission::create(['name' => 'gestionar usuarios']);
        Permission::create(['name' => 'gestionar categorias']);
        Permission::create(['name' => 'gestionar equipos']);
        Permission::create(['name' => 'gestionar solicitudes']);



        // Creacion de roles del sistema
        $superadmin = Role::create(['name' => 'Superadmin']);
        $administrador = Role::create(['name' => 'Administrador']);
        $responsable_activo = Role::create(['name' => 'Responsable del activo']);
        $usuario = Role::create(['name' => 'Usuario']);


        // Asignacion de permisos al rol Superadmin
        $superadmin->givePermissionTo(Permission::all());

        // Asignacion de permisos al rol Administrador
        $administrador->givePermissionTo([
            'gestionar instituciones',
            'gestionar sedes',
            'gestionar laboratorios',
            'gestionar usuarios',
            'gestionar categorias',
            'gestionar equipos',
            'gestionar solicitudes',
        ]);
        
        // Asignacion de permisos al rol Responsable activo
        $responsable_activo->givePermissionTo([
            'gestionar equipos',
            'gestionar solicitudes',
        ]);

        // Asignacion de permisos al rol Usuario
        $usuario->givePermissionTo([
            'gestionar solicitudes',
        ]);

        
        //Creacion de usuario superadmin por defecto 
        $user_superadmin = User::create([
            'name' => 'Superadmin',
            'email' => 'superadmin@gmail.com',
            'password' => bcrypt('admin123'),
            'rol_actual' => 'Superadmin',
        ]);
        // Asignacion de rol superadmin al usuario creado
        $user_superadmin->assignRole([$superadmin]);
        
        //Creacion de usuario administrador por defecto 
        $user_administrador = User::create([
            'name' => 'Administrador',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('admin123'),
            'rol_actual' => 'Administrador',
            'frase_recuperacion' => bcrypt('123'),
        ]);
        // Asignacion de rol administrador al usuario creado
        $user_administrador->assignRole([$administrador, $responsable_activo]);


         //Creacion de usuario responsable por defecto 
         $user_responsable_activo = User::create([
            'name' => 'Responsable',
            'email' => 'responsable@gmail.com',
            'password' => bcrypt('admin123'),
            'rol_actual' => 'Responsable del activo',
            'frase_recuperacion' => bcrypt('123'),
        ])->assignRole($responsable_activo);
        // Asignacion de rol investigador al usuario creado
        #$user_responsable_activo->assignRole($responsable_activo);
    }
}
