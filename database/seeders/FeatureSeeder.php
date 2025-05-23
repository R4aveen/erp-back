<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Feature;
use App\Models\Rol;

class FeatureSeeder extends Seeder
{
    public function run(): void
    {
        // Obtengo el rol super_admin para asignarle las features
        $super = Rol::where('slug', 'super_admin')->first();

        // Lista de features según tus rutas en contentRoutes.tsx
        $features = [
            [
                'clave'      => 'dashboard',
                'texto'      => 'Dashboard',
                'ruta'       => '/dashboard',
                'componente' => 'Dashboard',
                'icono'      => 'HomeIcon',
                'orden'      => 1,
                'grupo'      => 'General',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'profile',
                'texto'      => 'Perfil',
                'ruta'       => '/perfil',
                'componente' => 'ProfilePage',
                'icono'      => 'UserIcon',
                'orden'      => 2,
                'grupo'      => 'General',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'productos',
                'texto'      => 'Productos',
                'ruta'       => '/productos',
                'componente' => 'ProductosPage',
                'icono'      => 'PackageIcon',
                'orden'      => 3,
                'grupo'      => 'Gestión',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'usuarios',
                'texto'      => 'Usuarios',
                'ruta'       => '/usuarios',
                'componente' => 'UsuariosPage',
                'icono'      => 'UsersIcon',
                'orden'      => 4,
                'grupo'      => 'Gestión',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'cotizaciones',
                'texto'      => 'Cotizaciones',
                'ruta'       => '/cotizaciones',
                'componente' => 'Cotizaciones',
                'icono'      => 'FileTextIcon',
                'orden'      => 5,
                'grupo'      => 'Gestión',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'gestion_empresa',
                'texto'      => 'Gestión de Empresas',
                'ruta'       => '/gestion/empresa',
                'componente' => 'EmpresaPage',
                'icono'      => 'BuildingStoreIcon',
                'orden'      => 6,
                'grupo'      => 'Administración',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'gestion_subempresa',
                'texto'      => 'Gestión de Subempresas',
                'ruta'       => '/gestion/subempresa',
                'componente' => 'SubEmpresa',
                'icono'      => 'FactoryIcon',
                'orden'      => 7,
                'grupo'      => 'Administración',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'gestion_sucursal',
                'texto'      => 'Gestión de Sucursales',
                'ruta'       => '/gestion/sucursal',
                'componente' => 'Sucursales',
                'icono'      => 'MapPinIcon',
                'orden'      => 8,
                'grupo'      => 'Administración',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'roles_permisos',
                'texto'      => 'Roles y Permisos',
                'ruta'       => '/gestion/roles-permisos',
                'componente' => 'RolesPermisos',
                'icono'      => 'ShieldCheckIcon',
                'orden'      => 9,
                'grupo'      => 'Administración',
                'subgrupo'   => null,
            ],
            [
                'clave'      => 'gestion_usuarios',
                'texto'      => 'Gestión de Usuarios',
                'ruta'       => '/gestion/usuarios',
                'componente' => 'GestionUsuarios',
                'icono'      => 'UserPlusIcon',
                'orden'      => 10,
                'grupo'      => 'Administración',
                'subgrupo'   => null,
            ],
        ];

        foreach ($features as $data) {
            $feature = Feature::updateOrCreate(
                ['clave' => $data['clave']],
                $data
            );

            if ($super) {
                $feature->roles()->syncWithoutDetaching([$super->id]);
            }
        }

        $this->command->info("✅ Features sembradas y ligadas a super_admin");
    }
}
