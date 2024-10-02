<?php

namespace App\Http\Controllers;

use App\Models\FileExtension;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FileExtensionController extends Controller {

    public function update(Request $request) {
        // Get all extensions
        $fileExtensions = FileExtension::all();
        
        foreach ($fileExtensions as $fileExtension) {
            // Change every value of the extension
            $fileExtension->checked = $request->checked;

            // Save extension
            $fileExtension->save();
        }

        return response()->json([
            'success' => 'File extensions edited succesfully.'
        ]);
    }
    
}
