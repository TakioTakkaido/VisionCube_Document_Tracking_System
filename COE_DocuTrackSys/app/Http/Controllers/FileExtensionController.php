<?php

namespace App\Http\Controllers;

use App\Models\FileExtension;
use App\Http\Controllers\Controller;
use App\Models\Settings;
use Illuminate\Http\Request;

class FileExtensionController extends Controller {
    public function update(Request $request) {
        // Get the settings
        $settings = Settings::all()->first();

        // Clear the log
        $settings->fileExtensions = [];
        $settings->save();

        // Get all extensions
        $fileExtensions = FileExtension::all();
        
        // dd($request->extensions);
        $fileExtensionLog = [];
        for ($i=0; $i < sizeof($fileExtensions); $i++) { 
            $fileExtension = $fileExtensions[$i];

        
            // Check every file extension in the checklist
            $fileExtension->checked = $request->extensions[$i];

            // Save extension
            $fileExtension->save();

            if($fileExtension->checked == true){
                $fileExtensionLog[] = $fileExtension->value;
            }
        }

        $settings->fileExtensions = $fileExtensionLog;
        $settings->save();

        return response()->json([
            'success' => 'File extensions edited succesfully.'
        ]);
    }
    
}
