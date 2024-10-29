<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'version_id'
    ];

    public function file(){
        $filePath = "public/documents/". basename($this->file); // Assuming $document->file contains the filename
        $fileLink = Storage::url($filePath); // This generates the URL for accessing the document

        return asset($fileLink);
    }
}
