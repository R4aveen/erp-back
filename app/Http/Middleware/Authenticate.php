<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;

class Authenticate extends Middleware
{
    protected function redirectTo($request): ?string
    {
        if (! $request->expectsJson()) {
            abort(401, 'No autenticado');
        }

        return null;
    }

    protected function unauthenticated($request, array $guards)
    {
        throw new AuthenticationException(
            'No autenticado.',
            $guards,
            $this->redirectTo($request)
        );
    }
}
