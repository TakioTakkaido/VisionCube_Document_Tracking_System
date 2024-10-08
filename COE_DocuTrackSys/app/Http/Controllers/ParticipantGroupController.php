<?php

namespace App\Http\Controllers;

use App\Models\ParticipantGroup;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ParticipantGroupController extends Controller {
    public function add(Request $request) {
        // Validate
        $request->validate([
            'value' => 'required|string'
        ]);

        // Create
        ParticipantGroup::create([
            'value' => $request->input('value')
        ]);

        // Return succcess
        return response()->json([
            'success' => 'Participant group added successfully'
        ]);
    }

    public function delete(Request $request) {
        // Find group by id
        $participantGroup = ParticipantGroup::where('id', $request->id)->get();

        // Delete
        $participantGroup->delete();

        // Returnn success
        return response()->json([
            'success' => 'Participant group deleted successfully'
        ]);
    }

    public function edit(Request $request) {
        // Find group by id
        $participantGroup = ParticipantGroup::where('id', $request->id)->get();

        // Change name
        $participantGroup->value = $request->value;

        // Save
        $participantGroup->save();;

        // Return success
        // Returnn success
        return response()->json([
            'success' => 'Participant group deleted successfully'
        ]);
    }

    // groups can add/remove another group
    // groups can add/remove participant

    public function updateParticipantGroup(Request $request){
        // Request should contain all of the ids of groups
        // obtained from the updated table

        // Find parent group
        $participantGroup = ParticipantGroup::where('id', $request->id);

        // Sync participantGroups
        $participantGroup->participantGroups()->sync($request->participantGroupsIDs);
        
        return response()->json([
            'success' => 'Groups under the participant group updated successfully'
        ]);
    }

    public function updateParticipant(Request $request){
        // Request should contain all of the ids of participants
        // obtained from the updated table

        // Find parent group
        $participantGroup = ParticipantGroup::where('id', $request->id);

        // Sync participantGroups
        $participantGroup->participants()->sync($request->participantIDs);
        
        return response()->json([
            'success' => 'Participants under the participant group updated successfully'
        ]);
    }
}

