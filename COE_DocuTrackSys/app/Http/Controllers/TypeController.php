<?php

namespace App\Http\Controllers;

use App\Models\Type;
use App\Http\Controllers\Controller;
use App\Models\Document;
use App\Models\DocumentVersion;
use App\Models\Settings;
use Illuminate\Http\Request;

class TypeController extends Controller {
    // Edit type
    public function update(Request $request){
        // Get the settings
        $settings = Settings::all()->first();

        // Check whether the status already exists or not
        if ($request->id != null){
            // Status exists, find the status
            // Get type by id
            $type = Type::find($request->id);

            // Get value
            $typeText = $type->value;

            // Change value
            $type->value = $request->value;

            // Save
            $type->save();

            // Get all latest document versions
            $documents = Document::all();

            $latestVersions = [];
            foreach($documents as $document){
                array_push($latestVersions, $document->version());
            }
            
            // If it contains that type, update it
            foreach($latestVersions as $version){
                if($version->type == $typeText) {
                    // Create a new document version
                    $documentVersion = DocumentVersion::create([
                        'document_id'       =>      $version->document_id,
                        'version_number'    =>      ($version->version_number) + 1,
                        'description'       =>      'Updated document type',
                        'modified_by'       =>      'System Settings',

                        'type'              =>      $request->value,
                        'status'            =>      $version->status,
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
                        'color'             =>      $version->color,

                        // Previous document information
                        'previous_type'             =>      $version->type,
                        'previous_status'           =>      $version->previous_status,
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

            // Log the newly updated type
            $types = $settings->addedType ?? [];
            $types[] = $type->value;
            $settings->addedType = $types;
            $settings->save();
        } else {
            // Type doesn't exist
            // Create type
            $type = Type::create([
                'value' => $request->input('value')
            ]);

            // Log the newly updated type
            $types = $settings->addedType ?? [];
            $types[] = $type->value;
            $settings->addedType = $types;
            $settings->save();
        }

        // Return success
        return response()->json([
            'success' => 'Type edited successfully.',
            'id' => $type->id
        ]);
    }

    // Delete type
    public function delete(Request $request){
        // Get the settings
        $settings = Settings::all()->first();

        // Find the type
        $type = Type::find($request->id);

        // Log the deleted type
        $types = $settings->deletedType ?? [];
        $types[] = $type->value;
        $settings->deletedType = $types;
        $settings->save();

        // Delete the type
        $type->delete();

        // Return success
        return response()->json([
            'success' => 'Type deleted successfully.'
        ]);
    }
}
