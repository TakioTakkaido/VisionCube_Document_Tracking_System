<?php

namespace App\Http\Controllers;

use App\Models\Drive;
use App\Http\Controllers\Controller;
use App\Mail\LinkDriveAccount;
use App\Models\EmailVerificationToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
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

        // Create mail
        $client_id = \config('services.google.client_id');
        $redirect_uri = route('drive.callback'); 
    
        // Generate the URL for the Google OAuth 2.0 authorization screen
        $url = "https://accounts.google.com/o/oauth2/v2/auth?" . http_build_query([
            'client_id' => $client_id,
            'redirect_uri' => $redirect_uri,
            'response_type' => 'code',  
            'scope' => 'https://www.googleapis.com/auth/drive.file', 
            'access_type' => 'offline', 
            'prompt' => 'consent', 
            'state' => $drive->id,
            'login_hint' => $drive->email
        ]);

        Mail::to($drive->email)->queue(new LinkDriveAccount($url));

        // Add log

        return response()->json([
            'drive' => $drive
        ]);
    }

    public function callback(Request $request){
        if ($request->has('code')) {
            $code = $request->input('code');  // Get the authorization code

            // Send a request to Google's token endpoint to exchange the code for tokens
            $response = Http::post('https://oauth2.googleapis.com/token', [
                'code' => $code,
                'client_id' => config('services.google.client_id'),
                'client_secret' => config('services.google.client_secret'),
                'redirect_uri' => route('drive.callback'),  // Same URI as before
                'grant_type' => 'authorization_code',
            ]);

            // Get the JSON response from Google
            $tokens = $response->json();

            // Log the entire response to help debug
            Log::debug('Google OAuth response', $tokens);  // This will log the tokens to the log file

            // Optionally, dump the response for interactive debugging
            // dd($tokens);  // Use dd() to stop execution and inspect the output

            if (isset($tokens['refresh_token'])) {
                // We have the refresh token, now store it in the database

                $refreshToken = $tokens['refresh_token'];
                // Retrieve the Drive record using the state (Drive ID)
                $drive = Drive::find($request->query('state'));  // Use 'state' to find the right Drive

                // Log the Drive record to ensure it’s being fetched correctly
                // Log::debug('Drive record found', ['drive_id' => $drive->id, 'email' => $drive->email]);

                // Update the Drive record with the refresh token
                $drive->verified_at = now();
                $drive->refresh_token = $refreshToken;
                $drive->save();

                // Return the drive and token info in response
                // Response make sreut to show the webage
                return response()->json([
                    'drive' => $drive,
                    'refresh_token' => $refreshToken  // Optionally return the token to verify it's correct
                ]);
            } else {
                // Log the error if no refresh token is found
                Log::error('No refresh token received from Google API', ['response' => $tokens]);
                
                return response('Error: No refresh token returned', 400);
            }
        } else {
            // Log the error if the authorization code is not found
            Log::error('No authorization code in callback', ['query_params' => $request->query()]);

            return response('Error: Authorization code not found', 400);
        }
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
            'drives' => Drive::whereNot('id', $request->id)->get()
        ]);
    }

    public function disable(Request $request){
        $drive = Drive::find($request->id);

        $drive->disabled = $request->input('disable');
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
