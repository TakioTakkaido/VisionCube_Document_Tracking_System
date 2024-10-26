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

use App\AccountRole;
use App\DocumentCategory;
use App\DocumentStatus;
use App\DocumentType;

use App\Models\Document;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\FileExtension;
use App\Models\Log as ModelsLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DocumentController extends Controller{
    //Upload Document
    public function upload(Request $request){
        $series = $request->input('seriesRequired') === 'true' ? 'required|integer|max:9999|min:0' : 'nullable|integer';
        $memo = $request->input('memoRequired') === 'true' ? 'required|integer|max:9999|min:0' : 'nullable|integer';
        $request->validate([
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'series_number' => $series,
            'memo_number' => $memo,
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'file' => 'required|file|mimes:'.FileExtension::getFileExtensions(),
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

            'file.required' => 'Softcopy file is required!',
            'file.mimes' => 'Softcopy file must be of types: '.FileExtension::getFileExtensions(),

            'category.required' => 'Document category is required!',

            'status.required' => 'Document status is required!',

            'assignee.required' => 'Assignee is required!',

            'document_date.required' => 'Date is required!'
        ]);

        $document = Document::create([
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'file' => $request->file('file')->store('public/documents'), // Store the file and get the path
            'owner_id' => Auth::user()->id,
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
            'version' => 1
        ]);

        $document->createVersion();

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
    public function showIncoming(){
        // Find all incoming documents
        $documents = Document::where('category', DocumentCategory::INCOMING->value)->get();

        foreach($documents as $document){
            $document_date = strtotime($document->document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->canEdit = Auth::user()->canEdit;
            $document->canMove = Auth::user()->canMove;
            $document->canArchive = Auth::user()->canArchive;
            $document->canDownload = Auth::user()->canDownload;
            $document->canPrint = Auth::user()->canPrint;
        }

        // Log
        Log::channel('daily')->info('Incoming documents obtained: {documents}', ['documents' => $documents]);

        return response()->json([
            'incomingDocuments' => $documents
        ]);
    }

    // Display all outgoing documents
    public function showOutgoing(Request $request){
        // Find outgoing documents
        $documents = Document::where('category', DocumentCategory::OUTGOING->value)->get();

        foreach($documents as $document){
            $document_date = strtotime($document->document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->canEdit = Auth::user()->canEdit;
            $document->canMove = Auth::user()->canMove;
            $document->canArchive = Auth::user()->canArchive;
            $document->canDownload = Auth::user()->canDownload;
            $document->canPrint = Auth::user()->canPrint;
        }

        // Return outgoing documents
        return response()->json([
            'outgoingDocuments' => $documents
        ]);
    }

    // Display archived documents by type
    public function showArchived(Request $request){
        // Get all archived documents
        $documents = Document::where('category', DocumentCategory::ARCHIVED->value)->get();

        foreach($documents as $document){
            $document_date = strtotime($document->document_date);
            $document->document_date = date('M. d, Y', $document_date);
            $document->canEdit = Auth::user()->canEdit;
            $document->canMove = Auth::user()->canMove;
            $document->canArchive = Auth::user()->canArchive;
            $document->canDownload = Auth::user()->canDownload;
            $document->canPrint = Auth::user()->canPrint;
        }

        

        // Return archived documents
        return response()->json([
            'archivedDocuments' => $documents
        ]);
    }

    // Get Document for Editing Document
    public function show(Request $request){
        $document = Document::find($request->id);
        $filePath = "public/documents/". basename($document->file); // Assuming $document->file contains the filename
        $fileLink = Storage::url($filePath); // This generates the URL for accessing the document
        
        return response()->json([
            'document' => $document,
            'senderArray' => json_decode($document->senderArray),
            'recipientArray' => json_decode($document->recipientArray),
            'fileLink' => asset($fileLink),
        ]);
    }

    // Get Document Versions
    public function showDocumentVersions(Request $request){
        // Find document
        $document = Document::find($request->id);

        // Find document versions, related to this document
        $documentVersions = $document->versions()->get();
        // foreach($documentVersions as $version){
        //     $version->content = $version->content);
        // }
        // dd($documentVersions);
        // Return document versions
        return response()->json([
            'documentVersions' => $documentVersions
        ]);
    }

    // Edit Document
    public function edit(Request $request){
        // Find the current document
        $document = Document::find($request->document_id);

        // Validate the response
        $series = $request->input('seriesRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $memo = $request->input('memoRequired') === 'true' ? 'required|number|max:9999|min:0' : 'nullable|integer';
        $file = $request->file('file') ? 'required|file|mimes:pdf,'.FileExtension::getFileExtensions() : 'nullable';
        $request->validate([
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'series_number' => $series,
            'memo_number' => $memo,
            'file' => $file,
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
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

            'file.required' => 'Softcopy file is required!',
            'file.mimes' => 'Softcopy file must be of types: .pdf, '.FileExtension::getFileExtensions(),

            'category.required' => 'Document category is required!',

            'status.required' => 'Document status is required!',

            'assignee.required' => 'Assignee is required!',

            'document_date.required' => 'Date is required!'
        ]);

        $document->type = $request->input('type');
        $document->status = $request->input('status');
        $document->sender = $request->input('sender');
        $document->senderArray = json_encode($request->input('senderArray'));
        $document->recipient = $request->input('recipient');
        $document->recipientArray = json_encode($request->input('recipientArray'));
        $document->series_number = $request->input('seriesNo');
        $document->memo_number = $request->input('memoNo');
        $document->document_date = $request->input('document_date');
        $document->subject = $request->input('subject');
        $document->assignee = $request->input('assignee');
        $document->category = $request->input('category');

        // Temporary implementation 
        if($request->file('file')){
            if($document->file && Storage::exists("public/documents/". basename($document->file))){
                Storage::delete("public/documents/". basename($document->file));
            }

            $filePath = $request->file('file')->store('public/documents');

            $document->file = $filePath;
        }

        // Save the new document credentials
        $document->save();

        $document->createVersion();

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
        // 
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

        // Move document to new category
        $document->category = $request->category;

        // Save new document credentials
        $document->save();

        // Create new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role,
            'description' => 'Moved document'
        ]);
    }

    // Document preview
    public function preview(Request $request){
        $document = Document::find($request->id);
        $document_date = strtotime($document->document_date);
        $document->document_date = date('M. d, Y', $document_date);
        $filePath = "public/documents/". basename($document->file); // Assuming $document->file contains the filename
        $fileLink = Storage::url($filePath); // This generates the URL for accessing the document
        
        return response()->json([
            'document' => $document,
            'fileLink' => asset($fileLink)
        ]);
    }

    // Document Statistics
    public function getDocumentStatistics(){
        return response()->json([
            'incoming' => sizeof(Document::where('category', DocumentCategory::INCOMING->value)->get()),
            'outgoing' => sizeof(Document::where('category', DocumentCategory::OUTGOING->value)->get()),
            'archived' => sizeof(Document::where('category', DocumentCategory::ARCHIVED->value)->get()),
        ]);
    }
}
