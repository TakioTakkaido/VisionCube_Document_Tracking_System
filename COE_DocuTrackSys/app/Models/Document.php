<?php

namespace App\Models;

// VISION CUBE SOFTWARE CO. 
// Model: Document
// Represents the document that is going to be tracked in the entire
// COE Document Tracking System. 
// It contains:
// -Information relevant to the document
// -Status of the document
// -Versions of the document
// Contributor/s: 
// Calulut, Joshua Miguel C.

// Enums Used

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Document extends Model
{
    use HasFactory;

    // Below are the relationships of Document to other models in the system.
    // Document belongs to one User
    // Document has many DocumentVersions

    public function versions(){
        return $this->hasMany(DocumentVersion::class);
    }

    public function latestVersion() : DocumentVersion {
        return $this->versions()->orderBy('version_number', 'desc')->first();
    }

    public function seenUpdatedAccounts(){
        return $this->belongsToMany(Account::class, 
            'new_update_documents',
            'new_update_document_id',
            'account_id'
        );
    }

    public function seenUploadedAccounts(){
        return $this->belongsToMany(Account::class, 
            'new_upload_documents', 
            'new_upload_document_id',
            'account_id',
        );
    }
}