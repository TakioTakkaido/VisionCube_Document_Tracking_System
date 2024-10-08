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
use App\Models\Log as ModelsLog;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DocumentController extends Controller{
    //Upload Document
    public function upload(Request $request){
        $request->validate([
            'type' => 'required|string',
            'sender' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            // 'file' => 'required|file|mimes:pdf,doc,docx',
            'file' => 'required|file',
            'category' => 'required|string',
            'status' => 'required|string',
            'assignee' => 'required|string',
        ], [
            'type.required' => 'Document type is required!',
            'sender.required' => 'Document sender is required!',
            'sender.max' => 'Sender name can only have up to 255 characters!',

            'recipient.required' => 'Document recipient is required!',
            'recipient.max' => 'Recipient name can only have up to 255 characters!',

            'subject.required' => 'Document subject is required!',

            'file.required' => 'Softcopy file is required!',
            'file.mimes' => 'Softcopy file must be .pdf, .docx, or .doc type!',

            'category.required' => 'Document category is required!',
            'status.required' => 'Document status is required!',
            'assignee.required' => 'Assignee is required!'
        ]);

        Document::create([
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'file' => $request->file('file')->store('public/documents'), // Store the file and get the path
            'owner_id' => Auth::user()->id,
            'sender' => $request->input('sender'),
            'recipient' => $request->input('recipient'),
            'subject' => $request->input('subject'),
            'assignee' => $request->input('assignee'),
            'category' => $request->input('category')
        ]);

        // Create log 
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
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

        // Log
        Log::channel('daily')->info('Incoming documents obtained: {documents}', ['documents' => $documents]);

        // Create log class
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
            'description' => 'Viewed incoming documents'
        ]);

        return response()->json([
            'incomingDocuments' => $documents
        ]);
    }

    // Display all outgoing documents
    public function showOutgoing(Request $request){
        // Find outgoing documents
        $outgoingDocuments = Document::where('category', DocumentCategory::OUTGOING->value)->get();

        // Create a new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
            'description' => 'View outgoing documents'
        ]);

        // Return outgoing documents
        return response()->json([
            'outgoingDocuments' => $outgoingDocuments
        ]);
    }

    // Display archived documents by type
    public function showArchived(Request $request){
        // Get all archived documents
        $documents = Document::where('category', DocumentCategory::ARCHIVED->value)->get();

        // Create new log 
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
            'description' => 'View archived documents'
        ]);

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
            'fileLink' => asset($fileLink),
        ]);
    }

    // Get Document Versions
    public function showDocumentVersions(Request $request){
        // Find document versions, related to this document

        // Return document versions
    }

    // Edit Document
    public function edit(Request $request){
        // Find the current document
        $document = Document::find($request->document_id);

        // Validate the response
        $request->validate([
            'type' => ['required', Rule::in(array_column(DocumentType::cases(), 'value'))],
            'sender' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx',
            'category' => ['required', Rule::in(array_column(DocumentCategory::cases(), 'value'))],
            'status' => ['required', Rule::in(array_column(DocumentStatus::cases(), 'value'))],
            'assignee' => ['required', Rule::in(array_column(AccountRole::cases(), 'value'))],
        ], [
            'type.required' => 'Document type is required!',
            'sender.required' => 'Document sender is required!',
            'sender.max' => 'Sender name can only have up to 255 characters!',

            'recipient.required' => 'Document recipient is required!',
            'recipient.max' => 'Recipient name can only have up to 255 characters!',

            'subject.required' => 'Document subject is required!',

            'category.required' => 'Document category is required!',
            'status.required' => 'Document status is required!',
            'assignee.required' => 'Assignee is required!'
        ]);

        $document->type = $request->input('type');
        $document->status = $request->input('status');
        $document->sender = $request->input('sender');
        $document->recipient = $request->input('recipient');
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

        // Create a new log
        ModelsLog::create([
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
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
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
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
            'account' => Auth::user()->name . " • " . Auth::user()->role->value,
            'description' => 'Moved document'
        ]);
    }

    // Document preview
    public function preview(Request $request){
        $document = Document::find($request->id);
        $filePath = "public/documents/". basename($document->file); // Assuming $document->file contains the filename
        $fileLink = Storage::url($filePath); // This generates the URL for accessing the document
        
        return response()->json([
            'fileLink' => asset($fileLink),
        ]);
    }
}