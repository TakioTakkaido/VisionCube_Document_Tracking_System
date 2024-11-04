<?php

namespace App\Http\Middleware;

use App\Http\Controllers\SettingsController;
use App\Models\Log;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log as FacadesLog;
use Symfony\Component\HttpFoundation\Response;

class UnderMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response {
        // Check if under maintenance
        // Not in login page -> maintence page

        // In login page -> stay in login page
        // If logged in and not admin -> maintenance page

        // Go only to the login page
        if (SettingsController::getMaintenanceStatus() && $request->route()->getName() !== 'show.underMaintenance'){
            // Check if login page -> stay in login page
            if (Auth::user()->role !== 'Admin'){
                FacadesLog::channel('daily')->info('System under maintenance');
                return redirect()->route('show.underMaintenance');
            }
        }

        return $next($request);
    }
}
