<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Empresa;
use App\Models\Rol;

class AdminSeeder extends Seeder
{
    public function run()
    {
        // Crear empresa madre
        $empresa = Empresa::create([
            'nombre' => 'Tr3s Marías SPA',
            'rut' => '76381234-9',
            'descripcion' => 'Empresa principal del ERP',
        ]);

        // Crear rol Admin
        $rol = Rol::create([
            'nombre' => 'Admin',
            'descripcion' => 'Administrador del sistema con acceso total',
        ]);

        // Crear usuario administrador
        $admin = Usuario::create([
            'nombre' => 'Administrador General',
            'email' => 'admin@tresmarias.cl',
            'password' => bcrypt('admin123'), 
        ]);

        // Asociar a la empresa y rol
        $admin->empresa()->attach($empresa->id, ['rol_id' => $rol->id]);

        echo "Usuario admin creado: admin@tresmarias.cl / admin123\n";
    }
}
