<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use App\View\Components\Dashboard\Forms\Upload;
use Illuminate\Http\Request;

class StatusController extends Controller {

    // Edit status
    public function update(Request $request){
        // Validate the request

        // Get status by id
        // Check whether the status already exists or not
        if ($request->id != null){
            // Status exists, find the status
            $status = Status::find($request->id);

            // Change value
            $status->value = $request->value;
            $status->color = $request->color;

            // Save
            $status->save();

            // Log
        } else {
            // Status doesn't exist
            // Create status
            Status::create([
                'value' => $request->input('value'),
                'color' => $request->input('color')
            ]);

            // Log
        }

        // Return success
        return response()->json([
            'success' => 'Status edited successfully.'
        ]);
    }

    // Delete status
    public function delete(Request $request){
        $status = Status::find($request->id);

        $status->delete();

        // Log

        // Return success
        return response()->json([
            'success' => 'Status delete successfully.'
        ]);
    }
}
