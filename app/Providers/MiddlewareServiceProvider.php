<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Routing\Router;
use App\Http\Middleware\VerificarActivacion;

class MiddlewareServiceProvider extends ServiceProvider
{
    public function boot(Router $router)
    {
        $router->aliasMiddleware('permiso', \App\Http\Middleware\CheckPermiso::class);
        $router->aliasMiddleware('verificar_activacion', VerificarActivacion::class);

    }
}
