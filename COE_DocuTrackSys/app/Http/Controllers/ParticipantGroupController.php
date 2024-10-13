<?php

namespace App\Http\Controllers;

use App\Models\ParticipantGroup;
use App\Http\Controllers\Controller;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class ParticipantGroupController extends Controller {
    public function update(Request $request){
        // Validate the request

        // Get participant by id
        // Check whether the participant already exists or not
        if ($request->id != null){
            // Status exists, find the participant
            $participantGroup = ParticipantGroup::find($request->id);

            // Change value
            $participantGroup->value = $request->value;

            // Save
            $participantGroup->save();

            // Log
        } else {
            // Participant doesn't exist
            // Create participantGroup
            ParticipantGroup::create([
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
        $participantGroup = ParticipantGroup::find($request->id);

        // Delete
        $participantGroup->delete();

        // Returnn success
        return response()->json([
            'success' => 'Participant group deleted successfully'
        ]);
    }

    // groups can add/remove another group
    // groups can add/remove participant
    public function getParticipantGroupMembers(Request $request){
        // Get the participant group
        $participantGroup = ParticipantGroup::find($request->id);

        // Get its participant groups
        $participantGroups = $participantGroup->groups()->pluck('participant_group_id')->toArray();
        $participants = $participantGroup->participants()->pluck('participant_id')->toArray();

        // Get all participant group
        $allParticipantGroups = ParticipantGroup::all();
        $allParticipants = Participant::all();
        $checked = [];
        $checked2 = [];

        // Compare which participant group belong there or not
        for ($i = 0; $i < sizeof($allParticipantGroups); $i++) {
            if(in_array($allParticipantGroups[$i]->id, $participantGroups)){
                $checked[$i] = 1;
            } else {
                $checked[$i] = 0;
            }
        }

        // Compare which participant group belong there or not
        for ($i = 0; $i < sizeof($allParticipants); $i++) {
            if(in_array($allParticipants[$i]->id, $participants)){
                $checked2[$i] = 1;
            } else {
                $checked2[$i] = 0;
            }
        }

        // Return the array
        return response()->json([
            'groups' => $allParticipantGroups,
            'participants' => $allParticipants,
            'checked' => $checked,
            'checked2' => $checked2
        ]);
    }

    public function updateParticipantGroupMembers(Request $request) {
        // Validate the incoming request
        
        // Find the parent participant group
        $participantGroup = ParticipantGroup::find($request->id);
    
        // Sync participant groups
        $participantGroup->groups()->sync($request->participantGroupsIDs);
        $participantGroup->participants()->sync($request->participantIDs);

        return response()->json([
            'success' => 'Groups under the participant group updated successfully'
        ]);
    }

    public static function showAllGroups() {
        // Get all root groups (groups with no parent group)
        $childGroups = DB::table('participant_group_participant_group')
                ->pluck('participant_group_id')
                ->unique()
                ->toArray();

        $rootGroups = ParticipantGroup::whereNotIn('id', $childGroups)->get();
        // return $rootGroups;
        $groupTree = [];
    
        // Iterate through each root group and get its hierarchy
        foreach ($rootGroups as $rootGroup) {
            self::addToTree($rootGroup, $groupTree, 0);
        }
        
        // Get all participants
        $participants = Participant::all();

        // Add participants of the group under it
        foreach ($participants as $participant) {
            $groupTree[] = [
                'id' => $participant->id, // Participant prefix
                'value' => $participant->value,
                'level' => 0,
                'parent' => null,
                'participant' => true
            ];
        }

        // Return the hierarchical structure
        return $groupTree;
    }
    
    // A recursive function to build hierarchical structure
    public static function addToTree(ParticipantGroup $group, &$tree, $level, $parent = null) {
        // Add the group to the hierarchy
        $tree[] = [
            'id' => $group->id,  // Group prefix
            'value' => $group->value,
            'level' => $level,
            'parent' => $parent,
            'participant' => false
        ];
        
        // Recursively add child groups
        foreach ($group->groups as $childGroup) {
            self::addToTree($childGroup, $tree, $level + 1, $group->value);
        }

        // Add participants of the group under it
        foreach ($group->participants as $participant) {
            $tree[] = [
                'id' => $participant->id, // Participant prefix
                'value' => $participant->value,
                'level' => $level + 1,
                'parent' => $group->value,
                'participant' => true
            ];
        }
    }
}

