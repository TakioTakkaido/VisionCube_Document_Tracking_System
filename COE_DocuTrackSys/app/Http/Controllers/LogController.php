<?php

namespace App\Http\Controllers;

use App\Models\Log;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LogController extends Controller {
    // Edit document *
    // Login
    // Logout
    // Upload document *
    // Added new user
    // Any system settings
    // Viewed documents *
    // Resetted password

    public function showAllLogs(){
        $logs = Log::all();

        return response()->json([
            'logs' => $logs
        ]);
    }
}