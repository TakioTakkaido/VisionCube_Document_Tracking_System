<?php

namespace App\Http\Controllers;

use App\Models\DocumentVersion;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DocumentVersionController extends Controller {
    public function show(Request $request){
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
}
