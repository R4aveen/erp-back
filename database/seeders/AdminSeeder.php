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
            ['rut' => '76381234-9'],
            ['nombre' => 'Tr3s Marías SPA', 'descripcion' => 'Empresa matriz del ERP']
        );

        // 2) Rol super_admin
        $rol = Rol::firstOrCreate(
            ['slug' => 'super_admin'],
            ['nombre' => 'Super Administrador', 'descripcion' => 'Acceso total al sistema']
        );

        // 3) Asignar TODOS los permisos al super_admin
        $permisosIds = Permiso::pluck('id')->toArray();
        $rol->permisos()->sync($permisosIds);

        // 4) Usuario admin
        $user = Usuario::firstOrCreate(
            ['email' => 'rbarrientos@tresmarias.cl'],
            [
                'nombre'           => 'Root Administrator',
                'password'         => Hash::make('root1234'),
                'activado'         => true,
                'token_activacion' => null,
            ]
        );

        // 5) Vincular usuario ↔ rol ↔ empresa (pivot empresa_usuario_rol)
        $user->roles()->syncWithoutDetaching([
            $rol->id => ['empresa_id' => $empresa->id]
        ]);

        // 6) Personalización por defecto
        $user->personalizacion()->updateOrCreate(
            [], 
            [
                'tema'      => '1',
                'font_size' => 14,
            ]
        );

        $this->command->info(" Super-admin creado: rbarrientos@tresmarias.cl / root1234");
    }
}
