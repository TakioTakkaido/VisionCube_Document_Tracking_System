<?php

namespace App\Http\Controllers;

// VISION CUBE SOFTWARE CO. 
// Controller: DocumentController
// Facilitates the functionalities to access modal data Document
// made by the requests from the view
// 
// It contains:
// -Storing and creation of document
// -Retrieving incoming and outgoing documents
// Contributor/s: 
// Calulut, Joshua Miguel C.

use App\Events\UpdatedDocument;
use App\Events\UploadedDocument;
use App\Models\Document;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\EditDocumentRequest;
use App\Http\Requests\UploadDocumentRequest;
use Illuminate\Http\Request;

use App\Models\Attachment;
use App\Models\DocumentVersion;

use App\Models\Log as ModelsLog;
use App\Models\Status;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PDO;


class DocumentController extends Controller{
    private function token(){
        $client_id = \config('services.google.client_id');
        $client_secret = \config('services.google.client_secret');
        $refresh_token = \config('services.google.refresh_token');
        $response = Http::post('https://oauth2.googleapis.com/token', [
            'client_id' =>  $client_id,
            'client_secret' => $client_secret,
            'refresh_token' => $refresh_token,
            'grant_type' => 'refresh_token'
        ]);

        $access_token = json_decode((string)$response->getBody(), true)['access_token'];
        return $access_token;
    }

    //Upload Document
    public function upload(UploadDocumentRequest $request){
        $request->validated();

        // Create the document
        $document = Document::create();

        // Create the first version
        $documentVersion = DocumentVersion::create([
            'document_id'           => $document->id,
            'version_number'        => 1,
            'description'           => 'Uploaded document',
            'modified_by'           => Auth::user()->name . ' • ' . Auth::user()->role,

            'type'                  => $request->input('type'),
            'status'                => $request->input('status'),
            'sender'                => $request->input('sender'),
            'senderArray'           => json_encode($request->input('senderArray')),
            'recipient'             => $request->input('recipient'),
            'recipientArray'        => json_encode($request->input('recipientArray')),
            'subject'               => $request->input('subject'),
            'assignee'              => $request->input('assignee'),
            'category'              => $request->input('category'),
            'series_number'         => $request->input('series_number'),
            'memo_number'           => $request->input('memo_number'),
            'document_date'         => $request->input('document_date'),
            'color'                 => $request->input('color')
        ]);

        // Create the attachments
        $attachments = [];
        foreach($request->file('files') as $file){
            $url = 'https://www.googleapis.com/upload/drive/v3/files?uploadType=multipart';

            // Metadata for the file (convert to JSON)
            $metadata = [
                'name' => $file->getClientOriginalName(),
                'parents' => [config('services.google.document_folder_id')],
            ];

            $metadataJson = json_encode($metadata);

            $fileStore = Http::withToken($this->token())
                ->attach(
                    'metadata', $metadataJson, 'metadata.json', ['Content-Type' => 'application/json; charset=UTF-8']
                )
                ->attach(
                    'file', file_get_contents($file->getRealPath()), $file->getClientOriginalName(), ['Content-Type' => $file->getMimeType()]
                )
                ->post($url);
            
            if ($fileStore->successful()) {
                // Get file metadata from Google Drive response
                $fileResponse = $fileStore->json();
                $fileId = $fileResponse['id'];
                $fileUrl = $fileId;

                // Create the attachment record with the file URL
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'document_version_id' => $documentVersion->id,
                    'file' => $fileUrl, // Store the file URL instead of local file path
                ]);

                array_push($attachments, $attachment);
            }
        }

        // Attach the attachments
        foreach($attachments as $attachment){
            $documentVersion->attachments()->save($attachment);
        }

        $document->seenUpdatedAccounts()->sync([]);

        $document->versions()->save($documentVersion);

        // Create log 
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Uploaded a new document: '. $request->input('subject'),
            'type' => 'Document',
            'detail' => $documentVersion->toJson()
        ]);

        // All users would not be able to see this document at first

        UploadedDocument::dispatch();

        return response()->json([
            'success' => 'Document created successfully!',
        ]);
    }

    // Edit Document
    public function edit(EditDocumentRequest $request){
        // Find the current document
        $document = Document::find($request->document_id);
        $latestVersion = $document->latestVersion();

        // Validate the response
        $request->validated();

        // Create a new document version
        $documentVersion = DocumentVersion::create([
            'document_id'               => $document->id,
            'version_number'            => ($latestVersion->version_number) + 1,
            'description'               => $request->input('description'),
            'modified_by'               => Auth::user()->name . ' • ' . Auth::user()->role,

            'type'                      => $request->input('type'),
            'status'                    => $request->input('status'),
            'sender'                    => $request->input('sender'),
            'senderArray'               => json_encode($request->input('senderArray')),
            'recipient'                 => $request->input('recipient'),
            'recipientArray'            => json_encode($request->input('recipientArray')),
            'subject'                   => $request->input('subject'),
            'assignee'                  => $request->input('assignee'),
            'category'                  => $request->input('category'),
            'series_number'             => $request->input('series_number'),
            'memo_number'               => $request->input('memo_number'),
            'document_date'             => $request->input('document_date'),
            'color'                     => $request->input('color'),

            // Previous document information
            'previous_type'             => $latestVersion->type == $request->input('type') ? $latestVersion->previous_type : $latestVersion->type,
            'previous_status'           => $latestVersion->status == $request->input('status') ? $latestVersion->previous_status : $latestVersion->status,
            'previous_sender'           => $latestVersion->sender == $request->input('sender') ? $latestVersion->previous_sender : $latestVersion->sender,
            'previous_recipient'        => $latestVersion->recipient == $request->input('recipient') ? $latestVersion->previous_recipient : $latestVersion->recipient,
            'previous_subject'          => $latestVersion->subject == $request->input('subject') ? $latestVersion->previous_subject : $latestVersion->subject,
            'previous_assignee'         => $latestVersion->assignee == $request->input('assignee') ? $latestVersion->previous_assignee : $latestVersion->assignee,
            'previous_category'         => $latestVersion->category == $request->input('category') ? $latestVersion->previous_category : $latestVersion->category,
            'previous_series_number'    => $latestVersion->series_number == $request->input('series_number') ? $latestVersion->previous_series_number : $latestVersion->series_number,
            'previous_memo_number'      => $latestVersion->memo_number == $request->input('memo_number') ? $latestVersion->previous_memo_number : $latestVersion->memo_number
        ]);

        // Create the attachments
        $attachments = [];
        if ($request->file('files') != null){    
            foreach($request->file('files') as $file){
                $fileStore = Http::withHeaders([
                    'Authorization' => 'Bearer '.$this->token(),
                    'Content-Type' => 'application/json',
                ])->post('https://www.googleapis.com/drive/v3/files', [
                    'data'=>$file->getClientOriginalName(),
                    'mimeType'=>$file->getClientMimeType(),
                    'uploadType'=>'resumable'
                ]);
                 
                if($fileStore->successful()){

                }
                $attachment = Attachment::create([
                    'name'                  => $file->getClientOriginalName(),
                    'document_version_id'   => $documentVersion->id,
                    'file'                  => $file->store('public/documents')
                ]);

                array_push($attachments, $attachment);
            }

            // Attach the attachments
            foreach($attachments as $attachment){
                $documentVersion->attachments()->save($attachment);
            }
        }

        // Save the new document version
        $document->versions()->save($documentVersion);

        // Create a new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Updated document: '.$documentVersion->previous_subject,
            'type' => 'Document',
            'detail' => $documentVersion->toJson()
        ]);

        $document->seenUpdatedAccounts()->sync([]);

        UpdatedDocument::dispatch();

        // Return success message
        return response()->json([
            'success' => 'Document created successfully!',
        ]);
    }

    // Delete Document
    public function delete(Request $request){
        $document = Document::find($request->id);

        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Deleted document: '.$document->subject,
            'type' => 'Document',
            'detail' => $document->latestVersion()->toJson()
        ]);
        
        $document->delete();
    }

    // Delete All Documents
    public function deleteAll(Request $request){
        $ids = $request->ids;
        $documents = [];
        foreach($ids as $id){    
            $document = Document::find($id);
            $documents[] = $document->latestVersion()->toArray();
            $document->delete();
        }

        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Deleted '.count($ids).' documents',
            'type' => 'Document',
            'detail' => json_encode($documents)
        ]);
    }

    // Download Document File
    public function download(Request $request){
        // Get file URL
        $fileUrl = Document::where('id', $request->id)->value('file');

        // Create new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Obtained document file URL for download',
            'type' => 'Document',
            'detail' => Document::where('id', $request->id)->toJson()
        ]);

        // Return download document file link
        return Storage::download($fileUrl);
    }

    // Move Document From One Category to Another
    public function move(Request $request){
        // Find the document by id
        $document = Document::find($request->id);

        $latestVersion = $document->latestVersion();

        // Create a new document version
        $documentVersion = DocumentVersion::create([
            'document_id'       => $document->id,
            'version_number'    => ($latestVersion->version_number) + 1,
            'description'       => "Moved document to " . $request->input('category'),
            'modified_by'       => Auth::user()->name . ' • ' . Auth::user()->role,

            'type'              => $latestVersion->type,
            'status'            => $latestVersion->status,
            'sender'            => $latestVersion->sender,
            'senderArray'       => $latestVersion->senderArray,
            'recipient'         => $latestVersion->recipient,
            'recipientArray'    => $latestVersion->recipientArray,
            'subject'           => $latestVersion->subject,
            'assignee'          => $latestVersion->assignee,
            'category'          => $request->input('category'),
            'series_number'     => $latestVersion->series_number,
            'memo_number'       => $latestVersion->memo_number,
            'document_date'     => $latestVersion->document_date,
            'color'             => $latestVersion->color,

            // Previous document information
            'previous_type'             => $latestVersion->previous_type,
            'previous_status'           => $latestVersion->previous_status,
            'previous_sender'           => $latestVersion->previous_sender,
            'previous_recipient'        => $latestVersion->previous_recipient,
            'previous_subject'          => $latestVersion->previous_subject,
            'previous_assignee'         => $latestVersion->previous_assignee,
            'previous_category'         => $latestVersion->category == $request->input('category') ? $latestVersion->previous_category : $latestVersion->category,
            'previous_series_number'    => $latestVersion->previous_series_number,
            'previous_memo_number'      => $latestVersion->previous_memo_number
        ]);

        // Save new document version
        $document->versions()->save($documentVersion);

        // Create new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Moved document: '.$documentVersion->subject.' to ' . $request->category,
            'type' => 'Document',
            'detail' => $documentVersion->toJson()
        ]);

        $document->seenUpdatedAccounts()->sync([]);

        UpdatedDocument::dispatch();

        return response()->json(['message' => 'Document moved successfully']);
    }

    // Move All Documents From One Category to Another
    public function moveAll(Request $request){
        $ids = $request->ids;
        $documents = [];
        foreach($ids as $id){
            // Find the document by id
            $document = Document::find($id);

            $latestVersion = $document->latestVersion();

            // Create a new document version
            $documentVersion = DocumentVersion::create([
                'document_id'       => $document->id,
                'version_number'    => ($latestVersion->version_number) + 1,
                'description'       => "Moved document to " . $request->input('category'),
                'modified_by'       => Auth::user()->name . ' • ' . Auth::user()->role,

                'type'              => $latestVersion->type,
                'status'            => $latestVersion->status,
                'sender'            => $latestVersion->sender,
                'senderArray'       => $latestVersion->senderArray,
                'recipient'         => $latestVersion->recipient,
                'recipientArray'    => $latestVersion->recipientArray,
                'subject'           => $latestVersion->subject,
                'assignee'          => $latestVersion->assignee,
                'category'          => $request->input('category'),
                'series_number'     => $latestVersion->series_number,
                'memo_number'       => $latestVersion->memo_number,
                'document_date'     => $latestVersion->document_date,
                'color'             => $latestVersion->color,

                // Previous document information
                'previous_type'             => $latestVersion->previous_type,
                'previous_status'           => $latestVersion->previous_status,
                'previous_sender'           => $latestVersion->previous_sender,
                'previous_recipient'        => $latestVersion->previous_recipient,
                'previous_subject'          => $latestVersion->previous_subject,
                'previous_assignee'         => $latestVersion->previous_assignee,
                'previous_category'         => $latestVersion->category == $request->input('category') ? $latestVersion->previous_category : $latestVersion->category,
                'previous_series_number'    => $latestVersion->previous_series_number,
                'previous_memo_number'      => $latestVersion->previous_memo_number
            ]);

            $documents[] = $documentVersion->toArray();

            $document->seenUpdatedAccounts()->sync([]);

            // Save new document version
            $document->versions()->save($documentVersion);
        }
        

        // Create new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Moved '.count($ids).' document/s to ' . $request->category,
            'type' => 'Document',
            'detail' => json_encode($documents)
        ]);

        
        UpdatedDocument::dispatch();

        return response()->json(['message' => 'Document moved successfully']);
    }

    // Restore Document
    public function restore(Request $request){
        // Find the document by id
        $document = Document::find($request->id);

        $latestVersion = $document->latestVersion();

        // Create a new document version
        $documentVersion = DocumentVersion::create([
            'document_id'       => $document->id,
            'version_number'    => ($latestVersion->version_number) + 1,
            'description'       => "Restored document",
            'modified_by'       => Auth::user()->name . ' • ' . Auth::user()->role,

            'type'              => $latestVersion->type,
            'status'            => $latestVersion->status,
            'sender'            => $latestVersion->sender,
            'senderArray'       => $latestVersion->senderArray,
            'recipient'         => $latestVersion->recipient,
            'recipientArray'    => $latestVersion->recipientArray,
            'subject'           => $latestVersion->subject,
            'assignee'          => $latestVersion->assignee,
            'category'          => $latestVersion->previous_category,
            'series_number'     => $latestVersion->series_number,
            'memo_number'       => $latestVersion->memo_number,
            'document_date'     => $latestVersion->document_date,
            'color'             => $latestVersion->color,

            // Previous document information
            'previous_type'             => $latestVersion->previous_type,
            'previous_status'           => $latestVersion->previous_status,
            'previous_sender'           => $latestVersion->previous_sender,
            'previous_recipient'        => $latestVersion->previous_recipient,
            'previous_subject'          => $latestVersion->previous_subject,
            'previous_assignee'         => $latestVersion->previous_assignee,
            'previous_category'         => $latestVersion->category,
            'previous_series_number'    => $latestVersion->previous_series_number,
            'previous_memo_number'      => $latestVersion->previous_memo_number
        ]);

        $document->seenUpdatedAccounts()->sync([]);

        // Save new document version
        $document->versions()->save($documentVersion);

        UpdatedDocument::dispatch();

        // Create new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Restored document: '.$documentVersion->subject,
            'type' => 'Document',
            'detail' => $documentVersion->toJson()
        ]);
    }

    // Restore All Documents
    public function restoreAll(Request $request){
        $ids = $request->ids;
        $documents = [];
        foreach($ids as $id){
            // Find the document by id
            $document = Document::find($id);

            $latestVersion = $document->latestVersion();

            // Create a new document version
            $documentVersion = DocumentVersion::create([
                'document_id'       => $document->id,
                'version_number'    => ($latestVersion->version_number) + 1,
                'description'       => "Restored document",
                'modified_by'       => Auth::user()->name . ' • ' . Auth::user()->role,

                'type'              => $latestVersion->type,
                'status'            => $latestVersion->status,
                'sender'            => $latestVersion->sender,
                'senderArray'       => $latestVersion->senderArray,
                'recipient'         => $latestVersion->recipient,
                'recipientArray'    => $latestVersion->recipientArray,
                'subject'           => $latestVersion->subject,
                'assignee'          => $latestVersion->assignee,
                'category'          => $latestVersion->previous_category,
                'series_number'     => $latestVersion->series_number,
                'memo_number'       => $latestVersion->memo_number,
                'document_date'     => $latestVersion->document_date,
                'color'             => $latestVersion->color,

                // Previous document information
                'previous_type'             => $latestVersion->previous_type,
                'previous_status'           => $latestVersion->previous_status,
                'previous_sender'           => $latestVersion->previous_sender,
                'previous_recipient'        => $latestVersion->previous_recipient,
                'previous_subject'          => $latestVersion->previous_subject,
                'previous_assignee'         => $latestVersion->previous_assignee,
                'previous_category'         => $latestVersion->category,
                'previous_series_number'    => $latestVersion->previous_series_number,
                'previous_memo_number'      => $latestVersion->previous_memo_number
            ]);

            $document->seenUpdatedAccounts()->sync([]);

            // Save new document version
            $document->versions()->save($documentVersion);

            $documents[] = $documentVersion->toArray();
        }
        
        UpdatedDocument::dispatch();

        // Create new log
        ModelsLog::create([
            'account'       => Auth::user()->name . " • " . Auth::user()->role,
            'description'   => 'Restored '.count($ids).' documents',
            'type' => 'Document',
            'detail' => json_encode($documents)
        ]);
    }

    // Document Preview
    public function preview(Request $request){
        $document = Document::find($request->id);
        $latestDocumentVersion = $document->latestVersion();
        return response()->json([
            'document' => $latestDocumentVersion
        ]);
    }

    // Display all Documents depending upon the category
    public function showAll(Request $request){
        // Find all incoming documents
        $documents = Document::all();
        $allDocuments = [];
        foreach($documents as $document){
            $document = $document->latestVersion();

            if($document->category == $request->category){
                array_push($allDocuments, $document);
            }

        }

        $newlyUpdatedDocuments = Auth::user()->newlyUpdatedDocuments()->pluck('new_update_document_id')->toArray();
        $newlyUploadedDocuments = Auth::user()->newlyUploadedDocuments()->pluck('new_upload_document_id')->toArray();
        
        foreach($allDocuments as $document){
            $document->canEdit = Auth::user()->canEdit;
            $document->canMove = Auth::user()->canMove;
            $document->canArchive = Auth::user()->canArchive;
            $document->canDownload = Auth::user()->canDownload;
            $document->canPrint = Auth::user()->canPrint;
            $document->newUpload = !(in_array($document->document_id, $newlyUploadedDocuments));
            $document->newUpdate = !(in_array($document->document_id, $newlyUpdatedDocuments));
        }

        usort($allDocuments, function ($a, $b) {
            return strtotime($b->created_at) <=> strtotime($a->created_at);
        });

        // Log
        Log::channel('daily')->info('Documents obtained: {documents}', ['documents' => $allDocuments]);

        return response()->json([
            'documents' => $allDocuments
        ]);
    }

    // Get Document for Editing Document
    public function show(Request $request){
        $document = Document::find($request->id);

        // Get the latest version
        $latestDocumentVersion = $document->latestVersion();
        
        return response()->json([
            'document' => $latestDocumentVersion,
            'senderArray' => json_decode($latestDocumentVersion->senderArray),
            'recipientArray' => json_decode($latestDocumentVersion->recipientArray)
        ]);
    }

    // Get Document Versions
    public function showDocumentVersions(Request $request){
        // Find document
        $document = Document::find($request->id);

        // Find document versions, related to this document
        $documentVersions = $document->versions()->orderBy('created_at', 'desc')->get();

        return response()->json([
            'documentVersions' => $documentVersions
        ]);
    }

    // Get Document Attachments
    public function showAttachments(Request $request){
        // Find document
        $document = Document::find($request->id);

        // Get the versions
        $documentVersions = $document->versions()->orderBy('created_at', 'desc')->get();

        // Get the attachments
        $attachments = [];

        foreach($documentVersions as $documentVersion){
            $documentVersionAttachments = $documentVersion->attachments()->get();
            foreach($documentVersionAttachments as $documentVersionAttachment){
                $documentVersionAttachment->file = basename($documentVersionAttachment->file);
                array_push($attachments, $documentVersionAttachment);
            }
            
        }

        return response()->json([
            'attachments' => $attachments
        ]);
    }

    public function seen(Request $request){
        $document = Document::find($request->id);
        $document->seenUploadedAccounts()->attach(Auth::user()->id);
        $document->seenUpdatedAccounts()->attach(Auth::user()->id);

        return response('Document seen by the account');
    }
    // Homepage
    // Get Document Statistics
    // Document Statistics
    

    public function getDocumentStatisticsCurrent(){
        $documents = Document::all();

        // Get all versions
        $versions = [];
        foreach($documents as $document){
            // Sort versions by latest
            $versions[] = $document->latestVersion();
        }

        // Daily
        $day = date('d');
        $dayDocuments = [];
        foreach($versions as $version){    
            $date = new DateTime($version->created_at);
            if ($date->format('d') == $day && 
                $version->category != 'Trash' && 
                $version->category != 'Archived'){
                // After finding the very first, stop searching immediately
                $dayDocuments[] = $version;
            }
        }
        // Weekly
        $week = date('W');
        $weekDocuments = [];

        foreach($versions as $version){    
            $date = new DateTime($version->created_at);
            if ($date->format('W') == $week && 
            $version->category != 'Trash' && 
            $version->category != 'Archived'){
                // After finding the very first, stop searching immediately
                $weekDocuments[] = $version;
            }
        }
        // Monthly
        $month = date('m');
        $monthDocuments = [];

        foreach($versions as $version){    
            $date = new DateTime($version->created_at);
            if ($date->format('m') == $month && 
            $version->category != 'Trash' && 
            $version->category != 'Archived'){
                // After finding the very first, stop searching immediately
                $monthDocuments[] = $version;
            }
        }

        // Yearly
        $year = date('Y');
        $yearDocuments = [];

        foreach($versions as $version){       
            $date = new DateTime($version->created_at);
            if ($date->format('Y') == $year && 
            $version->category != 'Trash' && 
            $version->category != 'Archived'){
                // After finding the very first, stop searching immediately
                $yearDocuments[] = $version;
            }
        }
        

        // Sort
        // Day
        function incrementCount(&$array, $key1, $key2 = null) {
            if ($key2 === null) {
                $array[$key1] = isset($array[$key1]) ? $array[$key1] + 1 : 1;
            } else {
                $array[$key1][$key2] = isset($array[$key1][$key2]) ? $array[$key1][$key2] + 1 : 1;
            }
        }

        // Daily
        $daily = ["total" => count($dayDocuments)];
        $daily["category"]["Incoming"] = 0;
        $daily["category"]["Outgoing"] = 0;
        $statuses = Status::all();
        foreach($statuses as $status){
            $daily["status"][$status->value] = 0;
            $daily["color"][$status->value] = $status->color;
        }
        foreach ($dayDocuments as $document) {
            if($document->category != 'Trash' && $document->category != 'Archived'){
                incrementCount($daily["category"], $document->category);
                incrementCount($daily["status"], $document->status);
            }
        }

        // Weekly
        $weekly = ["total" => count($weekDocuments)];
        $weekly["category"]["Incoming"] = 0;
        $weekly["category"]["Outgoing"] = 0;
        $statuses = Status::all();
        foreach($statuses as $status){
            $weekly["status"][$status->value] = 0;
            $weekly["color"][$status->value] = $status->color;
        }
        foreach ($weekDocuments as $document) {
            if($document->category != 'Trash' && $document->category != 'Archived'){
                incrementCount($weekly["category"], $document->category);
                incrementCount($weekly["status"], $document->status);
            }
        }

        // Monthly
        $monthly = ["total" => count($monthDocuments)];
        $monthly["category"]["Incoming"] = 0;
        $monthly["category"]["Outgoing"] = 0;
        $statuses = Status::all();
        foreach($statuses as $status){
            $monthly["status"][$status->value] = 0;
            $monthly["color"][$status->value] = $status->color;
        }

        foreach ($monthDocuments as $document) {
            if($document->category != 'Trash' && $document->category != 'Archived'){
                incrementCount($monthly["category"], $document->category);
                incrementCount($monthly["status"], $document->status);
            }
        }

        // Yearly
        $yearly = ["total" => count($yearDocuments)];
        $yearly["category"]["Incoming"] = 0;
        $yearly["category"]["Outgoing"] = 0;
        $statuses = Status::all();
        foreach($statuses as $status){
            $yearly["status"][$status->value] = 0;
            $yearly["color"][$status->value] = $status->color;
        }

        foreach ($yearDocuments as $document) {
            if($document->category != 'Trash' && $document->category != 'Archived'){
                incrementCount($yearly["category"], $document->category);
                incrementCount($yearly["status"], $document->status);
            }
        }


        // Sort data by ascending order
        function sortDataByValue(&$data) {
            // Sort categories by their values in descending order (highest to lowest)
            arsort($data['category']);
        
            // Sort statuses by their color values in descending order (highest to lowest)
            arsort ($data['status']);
        }

        // Daily, Weekly, Monthly, Yearly calculations...
        // Sorting after filling out the counts

        // Example for Daily
        sortDataByValue($daily);
        sortDataByValue($weekly);
        sortDataByValue($monthly);
        sortDataByValue($yearly);

        return response()->json([
            'daily' => json_encode($daily),
            'weekly' => json_encode($weekly),
            'monthly' => json_encode($monthly),
            'yearly' => json_encode($yearly)
        ]);
    }

    public function getDocumentStatistics(Request $request){
        $date = new DateTime($request->date);
        $type = $request->type;
        // Get all documents
        $documents = Document::all();

        // Get all versions
        $versions = [];
        foreach($documents as $document){
            // Sort versions by latest
            $versions[] = $document->latestVersion();
        }

        // Set the type to get
        if($type === 'Day'){
            $date = $date->format('d');
        } else if($type === 'Week'){
            $date = $date->format('W');
        } else if($type === 'Month'){
            $date = $date->format('m');            
        } else {
            $date = $date->format('Y');            
        }

        
        $selectedDocuments = [];
        foreach($versions as $version){    
            $versionDate = new DateTime($version->created_at);
            if ($versionDate->format('d') == $date && 
                $version->category != 'Trash' && 
                $version->category != 'Archived'){
                // After finding the very first, stop searching immediately
                $selectedDocuments[] = $version;
            }
        }

        function incrementDocumentCount(&$array, $key1, $key2 = null) {
            if ($key2 === null) {
                $array[$key1] = isset($array[$key1]) ? $array[$key1] + 1 : 1;
            } else {
                $array[$key1][$key2] = isset($array[$key1][$key2]) ? $array[$key1][$key2] + 1 : 1;
            }
        }

        $documents = [];
        $documents = ["total" => count($selectedDocuments)];
        $documents["category"]["Incoming"] = 0;
        $documents["category"]["Outgoing"] = 0;
        $statuses = Status::all();
        foreach($statuses as $status){
            $documents["status"][$status->value] = 0;
            $documents["color"][$status->value] = $status->color;
        }
        foreach ($selectedDocuments as $document) {
            incrementDocumentCount($documents["category"], $document->category);
            incrementDocumentCount($documents["status"], $document->status);
        }

        // Example for Daily
        arsort($documents['category']);
        
        // Sort statuses by their color values in descending order (highest to lowest)
        arsort ($documents['status']);

        return response()->json([
            'documents' => json_encode($documents)
        ]);
    }

    public function getNewDocuments(){
        $newUpdated = [];
        $totalNewUpdated = 0;
        $totalNewUpdatedIncoming = 0;
        $totalNewUpdatedOutgoing = 0;
        // Get all the document ids under the auth user
        $newUpdated = Auth::user()->newlyUpdatedDocuments()->pluck('new_update_document_id')->toArray();

        // Get all the document ids
        $documentIds = [];
        $incomingDocumentIds = [];
        $outgoingDocumentIds = [];

        $documents = Document::all();
        foreach($documents as $document){
            array_push($documentIds, $document->latestVersion()->document_id);
            if ($document->latestVersion()->category === "Incoming"){
                array_push($incomingDocumentIds, $document->latestVersion()->document_id);
            }

            if ($document->latestVersion()->category === "Outgoing"){
                array_push($outgoingDocumentIds, $document->latestVersion()->document_id);
            }
        }
        
        // Check whether each document id belong to the previous document id
        foreach($documentIds as $id){
            if(!(in_array($id, $newUpdated))){$totalNewUpdated++;}
        }

        // Check whether each document id belong to the previous document id
        foreach($incomingDocumentIds as $id){
            if(!(in_array($id, $newUpdated))){$totalNewUpdatedIncoming++;}
        }

        // Check whether each document id belong to the previous document id
        foreach($outgoingDocumentIds as $id){
            if(!(in_array($id, $newUpdated))){$totalNewUpdatedOutgoing++;}
        }

        return response()->json([
            'totalNewUpdated' => $totalNewUpdated,
            'totalNewUpdatedIncoming' => $totalNewUpdatedIncoming,
            'totalNewUpdatedOutgoing' => $totalNewUpdatedOutgoing
        ]);
    }
}
