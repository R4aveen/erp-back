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
        // Permisos base
        $set = [
            'crear_producto',
            'editar_producto',
            'eliminar_producto',
            'ver_usuarios',
            'invitar_usuario',
            'crear_subempresa',
            'crear_sucursal' 
        ];

        $permisos = collect($set)->map(fn ($c) =>
            Permiso::firstOrCreate(
                ['clave' => $c],
                ['descripcion' => Str::title(str_replace('_', ' ', $c))]
            )
        );

        // Roles
        $admin   = Rol::firstOrCreate(['nombre' => 'admin']);
        $gerente = Rol::firstOrCreate(['nombre' => 'gerente']);
        $tecnico = Rol::firstOrCreate(['nombre' => 'tecnico']);

        // Asignación
        $admin->permisos()->sync($permisos->pluck('id'));

        $gerente->permisos()->sync(
            $permisos->whereIn('clave', [
                'crear_producto', 'editar_producto',
                'ver_usuarios', 'invitar_usuario'
            ])->pluck('id')
        );

        $tecnico->permisos()->sync(
            $permisos->whereIn('clave', ['crear_producto', 'editar_producto'])
                     ->pluck('id')
        );
    }
}
