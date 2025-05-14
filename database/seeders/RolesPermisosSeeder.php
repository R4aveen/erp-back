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
        $claves = [
            'super_admin',
            'empresa:*',
            'subempresa:*',
            'sucursal:*',
            'usuario:*',
            'producto:*',
            'cotizacion:*',
        ];

        foreach ($claves as $c) {
            Permiso::firstOrCreate(
                ['clave' => $c],
                ['descripcion' => Str::title(str_replace(['*',':','_'], [' Todos',' :',' '], $c))]
            );
        }

        $map = Permiso::pluck('id','clave');
        $admin = Rol::firstOrCreate(['slug'=>'admin'], ['nombre'=>'Admin']);
        $admin->permisos()->sync($map->values());
    }
}
