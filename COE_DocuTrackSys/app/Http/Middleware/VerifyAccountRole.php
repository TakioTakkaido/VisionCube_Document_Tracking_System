<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccountRole {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, string $roles): Response {
        // Get current user
        $user = Auth::user();
        $rolesArray = explode(",", $roles);
        // Verify the role
        foreach ($rolesArray as $rolesElem) {
            if ($rolesElem == $user->role){
                Log::channel('daily')->info('Your account is allowed to access this functionality.'); 
                return $next($request);
            }
        }

        Log::channel('daily')->error('{roles}', ['roles' => $rolesArray]); 
        Log::channel('daily')->error('Your account {user}, with role: {role} is not allowed to access this functionality.', [
            'user' => $user->email,
            'role' => $user->role->value
        ]); 
        return redirect()->route('show.dashboard')->withErrors([
            'access_error' => 'Your account is not allowed to access this functionality.'
        ]);
    }
}
