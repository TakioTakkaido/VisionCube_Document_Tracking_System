<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

class NoDirectAccess {
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        $referer = $request->headers->get('referer');

        if (!$referer || !str_contains($referer, url('/'))) {
            // If no referer or an unexpected referer, block access
            
        Log::channel('daily')->info('Invalid access to a link.'); 
            return redirect()->route('show.login');
        }
        
        Log::channel('daily')->info('Valid access to a link.'); 
        return $next($request);
    }
}
