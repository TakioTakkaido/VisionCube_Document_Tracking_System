<?php

namespace App\Http\Controllers;

use App\Models\FileExtension;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileExtensionController extends Controller {

    public function update(Request $request) {
        // Get all extensions
        $fileExtensions = FileExtension::all();
        
        for ($i=0; $i < sizeof($fileExtensions); $i++) { 
            $fileExtension = $fileExtensions[$i];

            // Check every file extension in the checklist
            $fileExtension->checked = $request->extensions[$i];

            // Save extension
            $fileExtension->save();
        }

        return response()->json([
            'success' => 'File extensions edited succesfully.'
        ]);
    }
    
}
