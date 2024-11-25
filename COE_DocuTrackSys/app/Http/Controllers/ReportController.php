<?php

namespace App\Http\Controllers;

use App\Events\GeneratedReport;
use App\Models\Report;
use App\Http\Controllers\Controller;
use App\Models\Account;
use App\Models\Drive;
use DateTime;
use Google\Service\CloudSourceRepositories\Repo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class ReportController extends Controller {
    // Generate
    public function generate(Request $request) {
        // Upload the file to google drive
        $drive = Drive::find($request->input('drive_id'));
        $folder_id = "";

        // Check first whether the drive has a gdrive folder, and then create if none
        // REPLACE WITH DATE FROM INPUT
        $report_date = new DateTime(now());
        $folder_id = $drive->getReportFolder(($report_date->format('Y')), $report_date->format('M'));
        $url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';
        $reportFile = $request->file('file');
        // Metadata for the file (convert to JSON)
        $metadata = [
            'name' => $request->input('name'),
            'parents' => [$folder_id],
        ];
        $metadataJson = json_encode($metadata);

        $fileStore = Http::withToken($drive->token())
            ->attach(
                'metadata', $metadataJson, 'metadata.json', ['Content-Type' => 'application/json; charset=UTF-8']
            )
            ->attach(
                'file', file_get_contents($reportFile->getRealPath()), $request->input('name'), ['Content-Type' => $reportFile->getMimeType()]
            )
            ->post($url);
        if ($fileStore->successful()) {
            // Get file metadata from Google Drive response
            $fileResponse = $fileStore->json();
            $fileId = $fileResponse['id'];
            $fileUrl = $fileId;

            // Create the attachment record with the file URL
            $report = Report::create([
                'name' => $request->input('name'),
                'file' => $fileUrl,
                'drive_folder' => $drive->email
            ]);
            $report->seenUploadedAccounts()->sync([]);

            GeneratedReport::dispatch();

            return response()->json([
                'success' => 'Report created successfully!'
            ]);
        } else {
            // Else, create an error message
            return response()->json([
                'error' => 'File upload unsuccessful'
            ], 422);
        }
    }

    public function download(Request $request) {
        $report = Report::find($request->id);

        return response()->json([
            'fileLink'  => $report->file
        ]);
    }

    public function showAll(Request $request) {
        // Get all reports
        $reports = Report::all();

        $account = Account::find(Auth::user()->id);
        $newlyUploadedReports = $account->newlyUploadedReports()->pluck('new_upload_report_id')->toArray();

        foreach ($reports as $report) {
            $report->newUpload = !(in_array($report->id, $newlyUploadedReports));
        }
        return response()->json([
            'reports' => $reports
        ]);
    }
    
    public function seen(Request $request){
        $report = Report::find($request->id);
        $report->seenUploadedAccounts()->attach(Auth::user()->id);

        return response('Document seen by the account');
    }

    public function getNewReports(){
        $newUpdated = [];
        $totalNewUploaded = 0;
        $account = Account::find(Auth::user()->id);
        // Get all the report ids under the auth user
        $newUploaded = $account->newlyUploadedReports()->pluck('new_upload_report_id')->toArray();


        $reports = Report::all();
        
        // Check whether each report id belong to the previous report id
        foreach($reports as $report){
            if(!(in_array($report->id, $newUploaded))){$totalNewUploaded++;}
        }

        return response()->json([
            'totalNewUploaded' => $totalNewUploaded,
        ]);
    }

    public function show(Request $request){
        $report = Report::find($request->id);

        return response()->json([
            'report' => $report,
            'fileLink' => url("https://drive.google.com/file/d/".$report->file."/preview")
        ]);
    }
}
