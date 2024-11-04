<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SettingsController;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyAccount {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        
        // Logged in, not in the dashboard
        if (Auth::check() && $request->route()->getName() !== 'show.dashboard') {
            Log::channel('daily')->info('Your account is logged in to access this functionality.'); 
            return redirect()->route('show.dashboard');
        }
    
        // Not logged in, not in the login page
        if (!Auth::check() && $request->route()->getName() !== 'show.login') {
            Log::channel('daily')->info('Your account is not logged in to access this functionality.'); 
            return redirect()->route('show.login');
        }
    
        return $next($request);
    }
}
