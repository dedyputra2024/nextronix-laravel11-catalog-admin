<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    public function handle(Request $request, Closure $next, string $permissions): Response
    {
        $user = $request->user();
        abort_unless($user, 403);

        $permissionList = array_filter(array_map('trim', explode('|', $permissions)));

        if ($user->is_admin) {
            return $next($request);
        }

        foreach ($permissionList as $permission) {
            if ($user->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Akses ditolak. Permission tidak sesuai.');
    }
}
