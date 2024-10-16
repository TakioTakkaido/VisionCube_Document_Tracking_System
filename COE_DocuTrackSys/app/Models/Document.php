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

use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use App\Models\DocumentVersion;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
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
        'category',
        'series_number',
        'memo_number',
        'document_date',
        'version'
    ];

    // Create new document version
    public function createVersion() : void {
        $filePath = "public/documents/". basename($this->file);
        $fileLink = Storage::url($filePath);
        $documentVersion = DocumentVersion::create([
            'document_id' => $this->id,
            'version_number' => $this->version,
            'content' => $this->toJson(),
            'file' => asset($fileLink),
            'modified_by' => Auth::user()->name . ' • ' . Auth::user()->role
        ]);

        $this->version++;
        $this->save();
        $this->versions()->save($documentVersion);
    }

    
    // Below are the relationships of Document to other models in the system.
    // Document belongs to one User
    // Document has many DocumentVersions

    public function versions(){
        return $this->hasMany(DocumentVersion::class);
    }

    protected function display_date() : string {
        return $this->document_date
            ->setTimezone('Asia/Singapore')
            ->format('M. d, Y');
    }
}

