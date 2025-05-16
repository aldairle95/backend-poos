<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create(['name' => 'Admin']);
        $usuario = Role::create(['name' => 'Usuario']);
        $vendedor = Role::create(['name' => 'Vendedor']);

            // Crear permisos
        Permission::create(['name' => 'gestionar usuarios']);
        Permission::create(['name' => 'ver productos']);
        Permission::create(['name' => 'crear productos']);
        Permission::create(['name' => 'eliminar productos']);

     // Admin: le das todos los permisos
        $admin->givePermissionTo(Permission::all());

        // Vendedor: asignas usando instancias
        $vendedor->givePermissionTo([
            Permission::findByName('ver productos'),
            Permission::findByName('crear productos')
        ]);

        // Usuario: lo mismo
        $usuario->givePermissionTo([
            Permission::findByName('ver productos')
        ]);
    }

}
