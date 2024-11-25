<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use App\Http\Controllers\Controller;
use App\Models\Drive;
use App\Models\FileExtension;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SettingsController extends Controller {
    public static function getMaintenanceStatus(){
        $settings = Settings::all()->first();
        return filter_var($settings->maintenance, FILTER_VALIDATE_BOOLEAN);
    }

    public function getMaintenanceStatusFrontend(){
        $settings = Settings::all()->first();
        return response()->json([
            'verified' => Auth::user()->email_verified_at !== null,
            'maintenance' => filter_var($settings->maintenance, FILTER_VALIDATE_BOOLEAN)
        ]);
    }

    public function updateMaintenanceStatus(Request $request){
        $settings = Settings::all()->first();
        $settings->maintenance = $request->input('maintenance');
        // dd($request->input('maintenance'));
        if($request->input('maintenance') !== 'true'){
            // Make the maintenance log
            $details = [];
            // Accesses

            // Accesses: Check or retrieve default values and add descriptions for each role's permissions
            $roles = ['secretary', 'assistant', 'clerk'];
            $permissions = [
                'Can Upload Documents',
                'Can Update Documents',
                'Can Move Documents',
                'Can Archive Documents',
                'Can Download Documents',
                'Can Print Documents'
            ];

            foreach ($roles as $role) {
                // Retrieve the current role's access permissions, falling back to defaults if empty
                $accessRoles = AccountController::{"get" . ucfirst($role) . "Accesses"}();
                
                for($i = 0; $i < count($accessRoles); $i++){
                    if ($accessRoles[$i] === 1){
                        $details['accesses'][$role][] = $permissions[$i];
                    }
                }
            }
            $settings->access = [];

            // Added Participants
            $addedParticipants = $settings->addedParticipant;
            $details['addedParticipant'] = $addedParticipants;
            $settings->addedParticipant = [];

            // Deleted Participants
            $deletedParticipants = $settings->deletedParticipant;
            $details['deletedParticipant'] = $deletedParticipants;
            $settings->deletedParticipant = [];

            // Added Groups
            $addedGroups = $settings->addedParticipantGroup;
            $details['addedParticipantGroup'] = $addedGroups;
            $settings->addedParticipantGroup = [];

            // Deleted Groups
            $deletedGroups = $settings->deletedParticipantGroup;
            $details['deletedParticipantGroup'] = $deletedGroups;
            $settings->deletedParticipantGroup = [];

            // Updated Participant
            $updatedParticipants = $settings->updatedParticipant;
            $details['updatedParticipant'] = $updatedParticipants;
            $settings->updatedParticipant = [];

            // Updated Groups
            $updatedGroups = $settings->updatedParticipantGroup;
            $details['updatedParticipantGroup'] = $updatedGroups;
            $settings->updatedParticipantGroup = [];

            // Added Types
            $addedTypes = $settings->addedType;
            $details['addedType'] = $addedTypes;
            $settings->addedType = [];

            // Deleted Types
            $deletedTypes = $settings->deletedType;
            $details['deletedType'] = $deletedTypes;
            $settings->deletedType = [];

            // Added Types
            $addedStatuses = $settings->addedStatus;
            $details['addedStatus'] = $addedStatuses;
            $settings->addedStatus = [];

            // Deleted Types
            $deletedStatuses = $settings->deletedStatus;
            $details['deletedStatus'] = $deletedStatuses;
            $settings->deletedStatus = [];

            // File Extensions
            $fileExtensions = $settings->fileExtensions == [] ? FileExtension::where('checked', true)->select('value') : $settings->fileExtensions;
            $details['fileExtensions'] = $fileExtensions;
            $settings->fileExtensions = [];

            $drives = Drive::where('canDocument', true)
                            ->where('disabled', false)
                            ->whereNot('verified_at', null)
                            ->orderBy('id', 'asc')
                            ->get();
            foreach($drives as $drive){
                $details['documentDrives'][] = $drive->email;
            }

            $drives = Drive::where('canReport', true)
                            ->where('disabled', false)
                            ->whereNot('verified_at', null)
                            ->orderBy('id', 'asc')
                            ->get();
            foreach($drives as $drive){
                $details['reportDrives'][] = $drive->email;
            }

            $settings->save();
            Log::create([
                'account' => Auth::user()->name.' • '.Auth::user()->role,
                'description' => 'Updated system settings',
                'type' => 'Maintenance',
                'detail' => json_encode($details)
            ]);
        }
        $settings->save();

        return response()->json([
            'success' => 'Settings saved'
        ]);
    }
}
