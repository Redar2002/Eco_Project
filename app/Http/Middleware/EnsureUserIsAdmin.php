<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

/**
 * Restreint l'accès au back-office aux utilisateurs ayant le rôle « admin ».
 */
class EnsureUserIsAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        abort_unless($request->user() && Gate::forUser($request->user())->allows('access-admin'), 403, 'Accès réservé aux administrateurs.');

        return $next($request);
    }
}
