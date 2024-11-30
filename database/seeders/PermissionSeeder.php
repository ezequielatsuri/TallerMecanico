<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permisos = [
            // Categorías
            'ver-categoria',
            'crear-categoria',
            'editar-categoria',
            'eliminar-categoria',

            // Cliente
            'ver-cliente',
            'crear-cliente',
            'editar-cliente',
            'eliminar-cliente',

            // Compra
            'ver-compra',
            'crear-compra',
            'mostrar-compra',
            'eliminar-compra',
            'imprimir-compra',

            // Marca
            'ver-marca',
            'crear-marca',
            'editar-marca',
            'eliminar-marca',

            // Presentaciones
            'ver-presentacione',
            'crear-presentacione',
            'editar-presentacione',
            'eliminar-presentacione',

            'ver-fabricante',
            'crear-fabricante',
            'editar-fabricante',
            'eliminar-fabricante',

            'ver-servicio',
            'crear-servicio',
            'editar-servicio',
            'eliminar-servicio',

            // Producto
            'ver-producto',
            'crear-producto',
            'editar-producto',
            'eliminar-producto',

            // Proveedor
            'ver-proveedore',
            'crear-proveedore',
            'editar-proveedore',
            'eliminar-proveedore',

            // Venta
            'ver-venta',
            'crear-venta',
            'mostrar-venta',
            'eliminar-venta',
            'imprimir-venta',

            // Roles
            'ver-role',
            'crear-role',
            'editar-role',
            'eliminar-role',

            // User
            'ver-user',
            'crear-user',
            'editar-user',
            'eliminar-user',

            // Perfil
            'ver-perfil',
            'editar-perfil',
        ];

        foreach ($permisos as $permiso) {
            Permission::updateOrCreate(
                ['name' => $permiso, 'guard_name' => 'web'], // Condición para buscar
                [] // No hay campos adicionales a actualizar
            );
        }
    }
}
