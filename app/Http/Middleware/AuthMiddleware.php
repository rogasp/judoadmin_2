<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AuthMiddleware extends Authenticate
{
    protected function redirectTo(Request $request): string
    {
        return route('pages:auth:register');

    }
}
