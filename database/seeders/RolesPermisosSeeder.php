<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Permiso;
use App\Models\Rol;

class RolesPermisosSeeder extends Seeder
{
    public function run(): void
    {
        // 1) Set de permisos CRUD + wildcards
        $permisosClaves = [
            'super_admin',       // permiso máximo, no removible
            // Wildcards por recurso
            'empresa:*',         // cubre create,view,update,delete
            'subempresa:*',
            'sucursal:*',
            'usuario:*',
            'cotizacion:*',
            'documento:*',
            'producto:*',
            // (Opcional puedes listar todos los específicos si lo deseas)
        ];

        // 2) Seed de Permisos
        foreach ($permisosClaves as $clave) {
            Permiso::firstOrCreate(
                ['clave'       => $clave],
                ['descripcion' => Str::title(str_replace([':','_','*'], [' ', ' ', 'Todos'], $clave))]
            );
        }

        // 3) Map clave→id
        $mapPermisos = Permiso::pluck('id', 'clave');

        // 4) Roles “fijos”
        $admin   = Rol::firstOrCreate(['nombre' => 'admin']);
        $gerente = Rol::firstOrCreate(['nombre' => 'gerente']);
        $tecnico = Rol::firstOrCreate(['nombre' => 'tecnico']);

        // 5a) Admin = TODOS los permisos
        $admin->permisos()->sync($mapPermisos->values());

        // 5b) Gerente = sólo wildcards de lectura/gestión
        $gerente->permisos()->sync(
            $mapPermisos->only([
                'empresa:*',
                'subempresa:*',
                'sucursal:*',
                'usuario:*',
                'cotizacion:*',
                'documento:*',
            ])->values()
        );

        // 5c) Técnico = sólo productos
        $tecnico->permisos()->sync(
            $mapPermisos->only([
                'producto:*',
            ])->values()
        );
    }
}
