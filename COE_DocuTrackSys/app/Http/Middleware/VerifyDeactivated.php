<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class VerifyDeactivated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    
    public function handle(Request $request, Closure $next): Response {
        if (!Auth::check() && $request->route()->getName() !== 'show.login') {
            return redirect()->route('show.login');
        }
        
        if (Auth::user()->deactivated == true && $request->route()->getName() !== 'show.deactivated'){
            Log::channel('daily')->info('Account deactivated');
            return redirect()->route('show.deactivated');
        }
        return $next($request);
    }
}
