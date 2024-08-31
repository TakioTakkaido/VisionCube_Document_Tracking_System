<?php

namespace App\Models;

// VISION CUBE SOFTWARE CO. 
// Model: DocumentVersion
// Contains the different versions of a single document.
// Contributor/s: 
// Calulut, Joshua Miguel C.

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DocumentVersion extends Model
{
    use HasFactory;

    protected $fillable = [
        'document_id',
        'version_number',
        'content',
    ];

    // Below are the relationships of Document to other models in the system.
    // DocumentVersion belongs to one Document

    public function document(){
        return $this->belongsTo(Document::class);
    }

}
