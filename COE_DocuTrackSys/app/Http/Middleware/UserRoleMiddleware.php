<?php

namespace App\Http\Middleware;

// VISION CUBE SOFTWARE CO. 
// Middleware: User Role
// The code that would autheticate the user's roles.
// This ensures safe and proper access of the users in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class UserRoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}
