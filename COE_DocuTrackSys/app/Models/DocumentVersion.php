<?php

namespace App\Models;

// VISION CUBE SOFTWARE CO. 
// Model: DocumentVersion
// Contains the different versions of a single document.
// Contributor/s: 
// Calulut, Joshua Miguel C.

use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        // Version Info
        'document_id',
        'version_number',
        'modified_by',

        // Document Info
        'type',
        'status',
        'sender',
        'senderArray',
        'recipient',
        'recipientArray',
        'subject',
        'assignee',
        'category',
        'series_number',
        'memo_number',
        'document_date'
    ];

    // Below are the relationships of Document to other models in the system.
    // DocumentVersion belongs to one Document

    public function document(){
        return $this->belongsTo(Document::class);
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

    protected function createdAt() : CastsAttribute {
        return CastsAttribute::make(
            get: fn ($value) => (string) Carbon::parse($value)
                ->setTimezone('Asia/Singapore')
                ->format('M. d, Y h:i:s a')
        );
    }

    protected function display_date() : string {
        return $this->document_date
            ->setTimezone('Asia/Singapore')
            ->format('M. d, Y');
    }
}
