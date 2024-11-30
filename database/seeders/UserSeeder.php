<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear o actualizar el usuario
        $user = User::updateOrCreate(
            ['email' => 'admin@gmail.com'], // Condición para buscar duplicados
            [
                'name' => 'Sak Noel',
                'password' => bcrypt('12345678')
            ]
        );

        // Crear o actualizar el rol de administrador
        $rol = Role::firstOrCreate(['name' => 'administrador']);

        // Sincronizar todos los permisos al rol de administrador
        $permisos = Permission::pluck('id', 'id')->all();
        $rol->syncPermissions($permisos);

        // Asignar el rol al usuario
        $user->assignRole($rol->name);
    }
}
