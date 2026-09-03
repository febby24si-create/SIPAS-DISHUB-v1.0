<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        $userRole = $request->user()?->role?->name;

        if (!$userRole || !in_array($userRole, $roles)) {
            abort(403, 'Anda Tidak Memiliki Akses ke Halaman Ini.');
        }

        return $next($request);
    }
}
