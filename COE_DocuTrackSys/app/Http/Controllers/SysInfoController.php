<?php

namespace App\Http\Controllers;

use App\Models\SysInfo;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SysInfoController extends Controller {
    public function update(Request $request){
        $info = SysInfo::all()->first();

        if ($request->filled('name')) {
            $info->name = $request->input('name');
        }
        
        if ($request->hasFile('logo')) {
            $logo = $request->file('logo');
    
            // Validate image resolution
            list($width, $height) = getimagesize($logo);
            if ($width < 200 || $height < 50) {
                return response()->json(['error' => 'The system icon must have at least 200x50 resolution.'], 400);
            }
    
            // Store the file
            $logoPath = $logo->store('public/icons');
            $info->logo = $logoPath;  // Store the path to the icon in the database
        }

        if ($request->hasFile('favicon')) {
            $favicon = $request->file('favicon');
    
            // Validate image resolution
            list($width, $height) = getimagesize($favicon);
            if ($width < 32 || $height < 32) {
                return response()->json(['error' => 'The system icon must have at least 200x50 resolution.'], 400);
            }
    
            // Store the file
            $faviconPath = $favicon->store('public/icons');
            $info->favicon = $faviconPath;  // Store the path to the icon in the database
        }

        if ($request->filled('about')) {
            $info->about = $request->input('about');
        }
        
        if ($request->filled('mission')) {
            $info->mission = $request->input('mission');
        }
        
        if ($request->filled('vision')) {
            $info->vision = $request->input('vision');
        }

        // Add similar checks for logo and favicon as needed

        $info->save();

        return response('System information changed successfully');
    }

}
