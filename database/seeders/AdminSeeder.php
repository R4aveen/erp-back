<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Empresa;
use App\Models\Rol;
use App\Models\Permiso;
use App\Models\Usuario;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Empresa matriz
        $empresa = Empresa::firstOrCreate(
            ['nombre' => 'Tr3s Marías SPA'],
            ['rut' => '76381234-9', 'descripcion' => 'Empresa matriz del ERP']
        );

        // 2) Rol super_admin
        $rol = Rol::firstOrCreate(
            ['slug' => 'super_admin'],
            ['nombre' => 'Super Administrador', 'descripcion' => 'Acceso total al sistema']
        );

        // 2b) Asegurar permiso super_admin
        $permSuper = Permiso::firstOrCreate(
            ['clave' => 'super_admin'],
            ['descripcion' => 'Acceso total al sistema']
        );

        // 2c) Asignar permiso al rol
        $rol->permisos()->syncWithoutDetaching([$permSuper->id]);

        // 3) Usuario
        $user = Usuario::firstOrCreate(
            ['email' => 'root@tresmarias.cl'],
            [
                'nombre'           => 'Root Administrator',
                'password'         => Hash::make('root1234'),
                'activado'         => true,
                'token_activacion' => null,
            ]
        );

        // 4) Asociación User ↔ Empresa ↔ Rol
        $user->empresasRoles()->syncWithoutDetaching([
            $empresa->id => [ 'rol_id' => $rol->id ]
        ]);

        $this->command->info("✅ Empresa y super-admin creados: root@tresmarias.cl / root1234");
    }
}