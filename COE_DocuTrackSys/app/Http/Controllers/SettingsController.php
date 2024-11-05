<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SettingsController extends Controller {
    public static function getMaintenanceStatus(){
        $settings = Settings::all()->first();
        return filter_var($settings->maintenance, FILTER_VALIDATE_BOOLEAN);
    }

    public function getMaintenanceStatusFrontend(){
        $settings = Settings::all()->first();
        return response()->json([
            'maintenance' => filter_var($settings->maintenance, FILTER_VALIDATE_BOOLEAN)
        ]);
    }

    public function updateMaintenanceStatus(Request $request){
        $settings = Settings::all()->first();
        $settings->maintenance = $request->input('maintenance');
        $settings->save();

        return response()->json([
            'success' => 'Settings saved'
        ]);
    }
}
