<?php

namespace App\Http\Middleware;
use Illuminate\Support\Facades\Auth;


use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            return redirect('/login');
        }
        $userRole = Auth::user()->role;
        // Verificar si el rol del usuario está en la lista de roles permitidos
        if (! in_array($userRole, $roles)) {
            abort(403, 'Acceso no autorizado');
        }
        return $next($request);
    }

}
