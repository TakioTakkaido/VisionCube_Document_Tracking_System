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
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // 
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request){
        $request->validate([
            'type' => ['required', Rule::in(array_column(DocumentType::cases(), 'value'))],
            'sender' => 'required|string|max:255',
            'recipient' => 'required|string|max:255',
            'subject' => 'required|string',
            'file' => 'required|file|mimes:pdf,doc,docx',
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

            'file.required' => 'Softcopy file is required!',
            'file.mimes' => 'Softcopy file must be .pdf, .docx, or .doc type!',

            'category.required' => 'Document category is required!',
            'status.required' => 'Document status is required!',
            'assignee.required' => 'Assignee is required!'
        ]);

        Document::create([
            'type' => $request->input('type'),
            'status' => $request->input('status'),
            'file' => $request->file('file')->store('documents'), // Store the file and get the path
            'owner_id' => Auth::user()->id,
            'sender' => $request->input('sender'),
            'recipient' => $request->input('recipient'),
            'subject' => $request->input('subject'),
            'assignee' => $request->input('assignee'),
            'category' => $request->input('category')
        ]);

        return response()->json([
            'success' => 'Document created successfully!',
        ]);
    }

    // Display all incoming documents
    public function showIncoming(){
        $incomingDocuments = Document::where('category', DocumentCategory::INCOMING->value)->get();

        return response()->json([
            'incomingDocuments' => $incomingDocuments
        ]);
    }

    // Display all outgoing documents
    public function showOutgoing(){
        $outgoingDocuments = Document::where('category', DocumentCategory::OUTGOING->value)->get();

        return response()->json([
            'outgoingDocuments' => $outgoingDocuments
        ]);
    }

    // Download file from link

    public function download(Request $request)
    {
        $fileUrl = Document::where('id', $request->id)->value('file');
        return Storage::download($fileUrl);
    }


    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        //
    }
}