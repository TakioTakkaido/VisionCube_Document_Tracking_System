<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use App\Models\Settings;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ParticipantController extends Controller {
    public function update(Request $request){
        $settings = Settings::all()->first();
        // Check whether the participant already exists or not
        if ($request->id != null){
            // Status exists, find the participant
            $participant = Participant::find($request->id);

            // Change value
            $participant->value = $request->value;

            // Save
            $participant->save();

            // Log the participant
            $participants = $settings->addedParticipant ?? [];
            $participants[] = $participant->value;
            $settings->addedParticipant = $participants;
            $settings->save();
        } else {
            // Participant doesn't exist
            // Create participant
            $participant = Participant::create([
                'value' => $request->input('value')
            ]);

            // Log the participant
            $participants = $settings->addedParticipant ?? [];
            $participants[] = $participant->value;
            $settings->addedParticipant = $participants;
            $settings->save();
        } 

        // Return success
        return response()->json([
            'success' => 'Participant updated successfully.'
        ]);
    }

    public function delete(Request $request) {
        $settings = Settings::all()->first();
        // Find group by id
        $participant = Participant::find($request->id);


        // Log the participant
        $participants = $settings->deletedParticipant ?? [];
        $participants[] = $participant->value;
        $settings->deletedParticipant = $participants;
        $settings->save();

        // Delete
        $participant->delete();

        // Returnn success
        return response()->json([
            'success' => 'Participant deleted successfully'
        ]);
    }
}
