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

use App\AccountRole;
use App\DocumentCategory;
use App\DocumentStatus;
use App\DocumentType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\DocumentVersion;
use PDO;

class Document extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'status',
        'owner_id',
        'file',
        'sender',
        'senderArray',
        'recipient',
        'recipientArray',
        'subject',
        'assignee',
        'category'
    ];

    // This function is called whenever the document is updated or revised.
    public static function boot(){
        parent::boot();

        static::updating(function ($document){
            // Get the document's latest version
            $latestVersion = $document->versions()->max('version_number') ?? 0;

            // Create a new document version
            DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => $latestVersion + 1,
                'content' => $document->toJson(), // Store the current state of the document as JSON
            ]);
        });
    }

    // Below are the relationships of Document to other models in the system.
    // Document belongs to one User
    // Document has many DocumentVersions

    public function user(){
        return $this->belongsTo(Account::class);
    }

    public function versions(){
        return $this->hasMany(DocumentVersion::class);
    }
}

