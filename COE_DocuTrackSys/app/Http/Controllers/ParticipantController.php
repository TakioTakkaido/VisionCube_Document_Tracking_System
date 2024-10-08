<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller {
    public function add(Request $request) {
        // Validate
        $request->validate([
            'value' => 'required|string'
        ]);

        // Create
        Participant::create([
            'value' => $request->input('value')
        ]);

        // Return succcess
        return response()->json([
            'success' => 'Participant group added successfully'
        ]);
    }

    public function delete(Request $request) {
        // Find group by id
        $participant = Participant::where('id', $request->id)->get();

        // Delete
        $participant->delete();

        // Returnn success
        return response()->json([
            'success' => 'Participant deleted successfully'
        ]);
    }

    public function edit(Request $request) {
        // Find group by id
        $participant = Participant::where('id', $request->id)->get();

        // Change name
        $participant->value = $request->value;

        // Save
        $participant->save();;

        // Return success
        // Returnn success
        return response()->json([
            'success' => 'Participant deleted successfully'
        ]);
    }
}
