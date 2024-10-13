<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller {
    public function update(Request $request){
        // Validate the request

        // Get participant by id
        // Check whether the participant already exists or not
        if ($request->id != null){
            // Status exists, find the participant
            $participant = Participant::find($request->id);

            // Change value
            $participant->value = $request->value;

            // Save
            $participant->save();

            // Log
        } else {
            // Participant doesn't exist
            // Create participant
            Participant::create([
                'value' => $request->input('value')
            ]);

            // Log
        } 

        // Return success
        return response()->json([
            'success' => 'Participant updated successfully.'
        ]);
    }

    public function delete(Request $request) {
        // Find group by id
        $participant = Participant::find($request->id);

        // Delete
        $participant->delete();

        // Returnn success
        return response()->json([
            'success' => 'Participant deleted successfully'
        ]);
    }
}
