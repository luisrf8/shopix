<?php
// database/seeders/RolesAndAdminSeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class RolesAndAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Crear roles
        $adminRole = Role::create(['name' => 'admin']);
        $vendorRole = Role::create(['name' => 'vendor']);
        $userRole = Role::create(['name' => 'user']);

        // Crear el usuario administrador
        User::create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'password' => Hash::make('admin'),
            'role_id' => $adminRole->id,
        ]);

        // Crear un usuario vendedor de ejemplo
        User::create([
            'name' => 'Vendor',
            'email' => 'vendor@example.com',
            'password' => Hash::make('vendor'),
            'role_id' => $vendorRole->id,
        ]);

        // Crear un usuario normal de ejemplo
        User::create([
            'name' => 'User',
            'email' => 'user@example.com',
            'password' => Hash::make('user'),
            'role_id' => $userRole->id,
        ]);
    }
}
