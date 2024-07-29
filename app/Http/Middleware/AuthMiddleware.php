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
        if ($this->isCentralDomain($request)) {
            return route('pages:auth:register'); // Central domain route
        } else {
            return route('pages:tenants:auth:login');
        }

    }

    protected function isCentralDomain(Request $request): bool
    {
        $centralDomains = config('tenancy.central_domains', []);
        $host = $request->getHost();

        return in_array($host, $centralDomains);
    }
}
