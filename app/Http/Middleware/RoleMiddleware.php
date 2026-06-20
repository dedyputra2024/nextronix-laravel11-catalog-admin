<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, string $roles): Response
    {
        $user = $request->user();
        abort_unless($user, 403);

        $roleList = array_filter(array_map('trim', explode('|', $roles)));

        if ($user->is_admin || (method_exists($user, 'hasAnyRole') && $user->hasAnyRole($roleList))) {
            return $next($request);
        }

        abort(403, 'Akses ditolak. Role tidak sesuai.');
    }
}
