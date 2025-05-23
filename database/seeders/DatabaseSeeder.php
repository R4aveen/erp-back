<?php
namespace Database\Seeders;

use App\Models\Feature;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            RolesPermisosSeeder::class,
            AdminSeeder::class,
            FeatureSeeder::class,
        ]);
    }
}
