<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  ...$roles
     */
    public function handle(Request $request, Closure $next, string ...$roles): Response
    {
        if (!$request->user()) {
            return redirect()->route('login');
        }

        // Admin has access to everything
        if ($request->user()->isAdmin()) {
            return $next($request);
        }

        // Check if user has one of the required roles
        if (in_array($request->user()->role, $roles)) {
            return $next($request);
        }

        // Redirect to appropriate page based on role
        if ($request->user()->isAccueil()) {
            return redirect()->route('dashboard')->with('error', 'Accès non autorisé. Votre rôle ne permet pas d\'accéder à cette page.');
        }

        return redirect()->route('dashboard')->with('error', 'Accès non autorisé.');
    }
}
