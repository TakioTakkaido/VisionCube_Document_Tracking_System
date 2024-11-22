<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Http\Controllers\Controller;
use App\Mail\LinkDriveAccount;
use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Tests\Feature\Auth\EmailVerificationTest;

class DriveController extends Controller {
    public function add(Request $request){
        $request->validate([
            'email' => 'required|string|email|max:255|unique:drives'
        ]);

        $drive = Drive::create([
            'email' => $request->input('email'),
            'verified_at' => null,
            'disabled' => false
        ]);

        $drive->generateVerifyEmailToken();

        $emailVerificationToken = $drive->email_verification_token()->first(); 

        // Create mail
        Mail::to($drive->email)->queue(new LinkDriveAccount($emailVerificationToken));

        // Add log

        return response()->json([
            'drive' => $drive
        ]);
    }

    public function remove(Request $request){
        $drive = Drive::find($request->id);

        // Delete the folders at the gdrive, and make it sure it has no files there
        // $drive->documentFolder()->deleteFolder();
        // $drive->reportFolder()->deleteFolder();

        $drive->delete();

        // Log

        return response()->json([
            'success' => 'Google account removed successfully'
        ]);
    }

    public function transfer(Request $request){
        $drive = Drive::find($request->id);
        $transferDrive = Drive::find($request->transfer_id);

        // Transfer logic
        /**
         * get all of the attachment ids for each documents and reports
         * get the folder id to the transfer drive
         * attach all documents and reports there, to the transfer drives respective document and reports folder
         * gdrive api logic to move files from one account to the other
         */

        // Log

        return response()->json([
            'success' => 'Attachments transferred successfully!'
        ]);
    }

    public function getTransferEmails(Request $request){
        // Get the drives except itself
        return response()->json([
            'drives' => Drive::whereNot('id', $request)->get()
        ]);
    }


    public function disable(Request $request){
        $drive = Drive::find($request->id);

        $drive->disabled = true;
        $drive->save();

        // Log

        return response()->json([
            'success'=>'Google account disabled successfully!'
        ]);
    }

    public function updateStorage(Request $request){
        $storages  = $request->storages;

        foreach($storages as $storage){
            $drive = Drive::find($storage['id']);
            $drive->canReport = $storage['canReport'] === "true";
            $drive->canDocument = $storage['canDocument'] === "true";
            $drive->save();
        }

        return response()->json([
            'success'=>'Google accounts\' storages assigned successfully!'
        ]);
    }

}
