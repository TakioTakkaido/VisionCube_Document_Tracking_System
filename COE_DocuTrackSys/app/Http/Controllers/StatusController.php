<?php

namespace App\Http\Controllers;

use App\Models\Status;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Settings;
use App\View\Components\Dashboard\Forms\Upload;
use Illuminate\Http\Request;

class StatusController extends Controller {
    // Edit status
    public function update(Request $request){
        // Get the settings
        $settings = Settings::all()->first();

        // Check whether the status already exists or not
        if ($request->id != null){
            // Status exists, find the status
            $status = Status::find($request->id);

            // Get status values
            $statusText = $status->value;
            $statusColor = $status->color;

            // Change value
            $status->value = $request->value;
            $status->color = $request->color;

            // Save
            $status->save();

            // Get all latest document versions
            $documents = Document::all();

            $latestVersions = [];
            foreach($documents as $document){
                array_push($latestVersions, $document->version());
            }
            
            // If it contains that type, update it
            foreach($latestVersions as $version){
                if($version->status == $statusText && $version->color == $statusColor) {
                    // Create a new document version
                    $documentVersion = DocumentVersion::create([
                        'document_id'       =>      $version->document_id,
                        'version_number'    =>      ($version->version_number) + 1,
                        'description'       =>      'Updated document status',
                        'modified_by'       =>      'System Settings',

                        'type'              =>      $version->status,
                        'status'            =>      $request->value,
                        'sender'            =>      $version->sender,
                        'senderArray'       =>      $version->senderArray,
                        'recipient'         =>      $version->recipient,
                        'recipientArray'    =>      $version->recipientArray,
                        'subject'           =>      $version->subject,
                        'assignee'          =>      $version->assignee,
                        'category'          =>      $version->category,
                        'series_number'     =>      $version->series_number,
                        'memo_number'       =>      $version->memo_number,
                        'document_date'     =>      $version->document_date,
                        'color'             =>      $request->color,

                        // Previous document information
                        'previous_type'             =>      $version->previous_type,
                        'previous_status'           =>      $version->status,
                        'previous_sender'           =>      $version->previous_sender,
                        'previous_recipient'        =>      $version->previous_recipient,
                        'previous_subject'          =>      $version->previous_subject,
                        'previous_assignee'         =>      $version->previous_assignee,
                        'previous_category'         =>      $version->previous_category,
                        'previous_series_number'    =>      $version->previous_series_number,
                        'previous_memo_number'      =>      $version->previous_memo_number
                    ]);

                    $document = Document::find($version->document_id);

                    $document->versions()->save($documentVersion);
                }
            }

            // Log the status
            $statuses = $settings->addedStatus ?? [];
            $statuses[] = $status->value;
            $settings->addedStatus = $statuses;
            $settings->save();
        } else {
            // Status doesn't exist
            // Create status
            $status = Status::create([
                'value' => $request->input('value'),
                'color' => $request->input('color')
            ]);
    
            // Log the status
            $statuses = $settings->addedStatus ?? [];
            $statuses[] = $status->value;
            $settings->addedStatus = $statuses;
            $settings->save();
        }

        // Return success
        return response()->json([
            'success' => 'Status edited successfully.',
            'id' => $status->id
        ]);
    }

    // Delete status
    public function delete(Request $request){
        // Get the settings
        $settings = Settings::all()->first();

        // Find the status
        $status = Status::find($request->id);

        // Log the status
        $statuses = $settings->deletedStatus ?? [];
        $statuses[] = $status->value;
        $settings->deletedStatus = $statuses;
        $settings->save();

        // Delete status
        $status->delete();

        // Return success
        return response()->json([
            'success' => 'Status deleted successfully.'
        ]);
    }
}
