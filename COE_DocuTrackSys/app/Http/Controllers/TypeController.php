<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TypeController extends Controller {
    // Edit type
    public function update(Request $request){
        // Validate the request

        // Get type by id
        // Check whether the status already exists or not
        if ($request->id != null){
            // Status exists, find the status
            $type = Type::find($request->id);

            // Change value
            $type->value = $request->value;

            // Save
            $type->save();

            // Log
        } else {
            // Type doesn't exist
            // Create type
            Type::create([
                'value' => $request->input('value')
            ]);

            // Log
        }

        

        // Return success
        return response()->json([
            'success' => 'Type edited successfully.'
        ]);
    }

    // Delete type
    public function delete(Request $request){
        $type = Type::find($request->id);

        $type->delete();

        // Log

        // Return success
        return response()->json([
            'success' => 'Type delete successfully.'
        ]);
    }
}
