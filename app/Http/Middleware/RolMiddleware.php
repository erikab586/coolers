<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class RolMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        // Si no hay sesión activa → redirigir a login
        if (!Auth::check()) {
            return redirect()->route('usuario.login')
                ->withErrors(['session' => 'Tu sesión ha expirado. Por favor inicia sesión nuevamente.']);
        }

        $user = Auth::user();

        // Verifica si el usuario tiene un rol asignado
        if (!$user->rol) {
            abort(403, 'Tu usuario no tiene un rol asignado.');
        }

        // Normaliza valores
        $rolUsuarioNombre = strtolower(trim($user->rol->nombrerol));
        $rolesNormalizados = array_map(fn($r) => strtolower(trim($r)), $roles);

        // Si el rol del usuario coincide con alguno de los permitidos
        if (in_array($rolUsuarioNombre, $rolesNormalizados)) {
            return $next($request);
        }

        // Si no tiene permiso → error 403
        abort(403, 'No tienes permiso para acceder a esta sección.');
    }
}
