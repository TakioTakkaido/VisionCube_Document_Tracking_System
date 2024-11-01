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

use App\DocumentCategory;

use App\Models\Document;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Attachment;
use App\Models\DocumentVersion;
use App\Models\FileExtension;
use App\Models\Log as ModelsLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class DocumentController extends Controller{
    //Upload Document
    public function upload(Request $request){
        $series = $request->input('seriesRequired') === true ? 'required|integer|max:9999|min:0' : 'nullable';
        $memo = $request->input('memoRequired') === true ? 'required|integer|max:9999|min:0' : 'nullable';
        
        $request->validate([
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'series_number' => $series,
            'memo_number' => $memo,
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'files.*' => 'required|file|mimes:'.FileExtension::getFileExtensions(),
            'files' => 'required|array',
            'category' => 'required|string',
            'status' => 'required|string',
            'assignee' => 'required|string',
            'document_date' => 'required'
        ], [
            'type.required' => 'Document type is required!',

            'sender.required' => 'Document sender is required!',
            
            'sender.max' => 'Sender name can only have up to 255 characters!',

            'recipient.required' => 'Document recipient is required!',
            'recipient.max' => 'Recipient name can only have up to 255 characters!',

            'series.required' => 'Series number required for memoranda!',
            'series.min' => 'Series number invalid (Must be between 1-9999 only)!',
            'series.max' => 'Series number invalid (Must be between 1-9999 only)!',

            'memo.required' => 'Memo number required for memoranda!',
            'memo.min' => 'Memo number invalid (Must be between 1-9999 only)!',
            'memo.max' => 'Memo number invalid (Must be between 1-9999 only)!',

            'subject.required' => 'Document subject is required!',

            'files.required' => 'Softcopy file is required!',
            'files.*.mimes' => 'Softcopy file must be of types: '.FileExtension::getFileExtensions(),

            'category.required' => 'Document category is required!',

            'status.required' => 'Document status is required!',

            'assignee.required' => 'Assignee is required!',

            'document_date.required' => 'Date is required!'
        ]);

        // Create the document
        $document = Document::create();

        

        // Create the first version
        $documentVersion = DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => 1,
            'description' => 'Uploaded document',
            'modified_by' => Auth::user()->name . ' • ' . Auth::user()->role,

            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'sender' => $request->input('sender'),
            'senderArray' => json_encode($request->input('senderArray')),
            'recipient' => $request->input('recipient'),
            'recipientArray' => json_encode($request->input('recipientArray')),
            'subject' => $request->input('subject'),
            'assignee' => $request->input('assignee'),
            'category' => $request->input('category'),
            'series_number' => $request->input('series_number'),
            'memo_number' => $request->input('memo_number'),
            'document_date' => $request->input('document_date')
        ]);

        // Create the attachments
        $attachments = [];
        foreach($request->file('files') as $file){
            // Log::channel('daily')->info('{fileName}', ['fileName' => $file->originalName]);
            $attachment = Attachment::create([
                'name' => $file->getClientOriginalName(),
                'document_version_id' => $documentVersion->id,
                'file' => $file->store('public/documents')
            ]);

            array_push($attachments, $attachment);
        }

        // Attach the attachments
        foreach($attachments as $attachment){
            $documentVersion->attachments()->save($attachment);
        }

        $document->versions()->save($documentVersion);

        // Create log 
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Uploaded a new document'
        ]);

        return response()->json([
            'success' => 'Document created successfully!',
        ]);
    }

    // Display all incoming documents
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

        
        foreach($allDocuments as $document){
            $document_date = strtotime($document->document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->canEdit = Auth::user()->canEdit;
            $document->canMove = Auth::user()->canMove;
            $document->canArchive = Auth::user()->canArchive;
            $document->canDownload = Auth::user()->canDownload;
            $document->canPrint = Auth::user()->canPrint;
        }

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

    public function showDocumentVersion(Request $request){
        // Find document version
        $documentVersion = DocumentVersion::find($request->id);
        $document_date = strtotime($documentVersion->document_date);
        $documentVersion->document_date = date('M. d, Y', $document_date);

        if ($documentVersion->previous_document_date != 'N/A'){
            $document_date = strtotime($documentVersion->previous_document_date);
            $documentVersion->previous_document_date = date('M. d, Y', $document_date);
        }

        return response()->json([
            'version' => $documentVersion
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

    // Get Specific Document Attachment
    public function showAttachment(Request $request){
        $attachment = Attachment::find($request->id);

        return response()->json([
            'fileLink' => $attachment->file(),
        ]);
    }

    // Edit Document
    public function edit(Request $request){
        // Find the current document
        $document = Document::find($request->document_id);
        $latestVersion = $document->latestVersion();

        // Validate the response
        $series = $request->input('seriesRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $memo = $request->input('memoRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $file = $request->file('file') ? 'required|file|mimes:,'.FileExtension::getFileExtensions() : 'nullable';
        $request->validate([
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'series_number' => $series,
            'memo_number' => $memo,
            'files.*' => $file,
            'files' => 'nullable',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'category' => 'required|string',
            'status' => 'required|string',
            'assignee' => 'required|string',
            'document_date' => 'required',
            'description' => 'required'
        ],[
            'type.required' => 'Document type is required!',

            'sender.required' => 'Document sender is required!',
            
            'sender.max' => 'Sender name can only have up to 255 characters!',

            'recipient.required' => 'Document recipient is required!',
            'recipient.max' => 'Recipient name can only have up to 255 characters!',

            'series.required' => 'Series number required for memoranda!',
            'series.min' => 'Series number invalid (Must be between 1-9999 only)!',
            'series.max' => 'Series number invalid (Must be between 1-9999 only)!',

            'memo.required' => 'Memo number required for memoranda!',
            'memo.min' => 'Memo number invalid (Must be between 1-9999 only)!',
            'memo.max' => 'Memo number invalid (Must be between 1-9999 only)!',

            'subject.required' => 'Document subject is required!',

            'files.required' => 'Softcopy file is required!',
            'files.*.mimes' => 'Softcopy file must be of types: '.FileExtension::getFileExtensions(),

            'category.required' => 'Document category is required!',

            'status.required' => 'Document status is required!',

            'assignee.required' => 'Assignee is required!',

            'document_date.required' => 'Date is required!',

            'description.required' => 'Description of edit is required!'
        ]);

        // Create a new document version
        $documentVersion = DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => ($latestVersion->version_number) + 1,
            'description' => $request->input('description'),
            'modified_by' => Auth::user()->name . ' • ' . Auth::user()->role,

            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'sender' => $request->input('sender'),
            'senderArray' => json_encode($request->input('senderArray')),
            'recipient' => $request->input('recipient'),
            'recipientArray' => json_encode($request->input('recipientArray')),
            'subject' => $request->input('subject'),
            'assignee' => $request->input('assignee'),
            'category' => $request->input('category'),
            'series_number' => $request->input('series_number'),
            'memo_number' => $request->input('memo_number'),
            'document_date' => $request->input('document_date'),

            // Previous document information
            'previous_type' => $latestVersion->type == $request->input('type') ? $latestVersion->previous_type : $latestVersion->type,
            'previous_status' => $latestVersion->status == $request->input('status') ? $latestVersion->previous_status : $latestVersion->status,
            'previous_sender' => $latestVersion->sender == $request->input('sender') ? $latestVersion->previous_sender : $latestVersion->sender,
            'previous_recipient' => $latestVersion->recipient == $request->input('recipient') ? $latestVersion->previous_recipient : $latestVersion->recipient,
            'previous_subject' => $latestVersion->subject == $request->input('subject') ? $latestVersion->previous_subject : $latestVersion->subject,
            'previous_assignee' => $latestVersion->assignee == $request->input('assignee') ? $latestVersion->previous_assignee : $latestVersion->assignee,
            'previous_category' => $latestVersion->category == $request->input('category') ? $latestVersion->previous_category : $latestVersion->category,
            'previous_series_number' => $latestVersion->series_number == $request->input('series_number') ? $latestVersion->previous_series_number : $latestVersion->series_number,
            'previous_memo_number' => $latestVersion->memo_number == $request->input('memo_number') ? $latestVersion->previous_memo_number : $latestVersion->memo_number
        ]);


        // Create the attachments
        $attachments = [];
        if ($request->file('files') != null){    
            foreach($request->file('files') as $file){
                $attachment = Attachment::create([
                    'name' => $file->getClientOriginalName(),
                    'document_version_id' => $documentVersion->id,
                    'file' => $file->store('public/documents')
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
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Edited document'
        ]);

        // Return success message
        return response()->json([
            'success' => 'Document created successfully!',
        ]);
    }

    // Delete Document
    public function delete(Request $request){
        $document = Document::find($request->id);

        $document->delete();
    }

    // Download file from link
    public function download(Request $request){
        // Get file URL
        $fileUrl = Document::where('id', $request->id)->value('file');

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Obtained document file URL for download'
        ]);

        // Return download document file link
        return Storage::download($fileUrl);
    }

    // Move Documents from one category to another
    public function move(Request $request){
        // Find the document by id
        $document = Document::find($request->id);

        $latestVersion = $document->latestVersion();

        // Create a new document version
        $documentVersion = DocumentVersion::create([
            'document_id' => $document->id,
            'version_number' => ($latestVersion->version_number) + 1,
            'description' => "Moved document to " . $request->category,
            'modified_by' => Auth::user()->name . ' • ' . Auth::user()->role,

            'type' => $latestVersion->type,
            'status' => $latestVersion->status,
            'sender' => $latestVersion->sender,
            'senderArray' => $latestVersion->senderArray,
            'recipient' => $latestVersion->recipient,
            'recipientArray' => $latestVersion->recipientArray,
            'subject' => $latestVersion->subject,
            'assignee' => $latestVersion->assignee,
            'category' => $request->input('category'),
            'series_number' => $latestVersion->series_number,
            'memo_number' => $latestVersion->memo_number,
            'document_date' => $latestVersion->document_date,

            // Previous document information
            'previous_type' => $latestVersion->previous_type,
            'previous_status' => $latestVersion->previous_status,
            'previous_sender' => $latestVersion->previous_sender,
            'previous_recipient' => $latestVersion->previous_recipient,
            'previous_subject' => $latestVersion->previous_subject,
            'previous_assignee' => $latestVersion->previous_assignee,
            'previous_category' => $request->category == $request->input('category') ? $latestVersion->previous_category : $latestVersion->category,
            'previous_series_number' => $latestVersion->previous_series_number,
            'previous_memo_number' => $latestVersion->previous_memo_number
        ]);

        // Save new document version
        $document->versions()->save($documentVersion);

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Moved document to ' . $request->category
        ]);
    }

    // Document preview
    public function preview(Request $request){
        $document = Document::find($request->id);
        $latestDocumentVersion = $document->latestVersion();
        return response()->json([
            'document' => $latestDocumentVersion
        ]);
    }

    // Document Statistics
    public function getDocumentStatistics(){
        $documents = Document::all();
        $incoming = 0;
        $outgoing = 0;

        foreach($documents as $document){
            $latestVersion = $document->latestVersion();

            if($latestVersion->category == 'Incoming'){
                $incoming++;
            } else if ($latestVersion->category == 'Outgoing'){
                $outgoing++;
            }
        }

        
        return response()->json([
            'incoming' => $incoming,
            'outgoing' => $outgoing,
        ]);
    }
}
