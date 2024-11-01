<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Attachment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'document_version_id'
    ];

    public function file(){
        $filePath = "public/documents/". basename($this->file); // Assuming $document->file contains the filename
        $fileLink = Storage::url($filePath); // This generates the URL for accessing the document

        return asset($fileLink);
    }

    protected function createdAt() : CastsAttribute {
        return CastsAttribute::make(
            get: fn ($value) => (string) Carbon::parse($value)
                ->setTimezone('Asia/Singapore')
                ->format('M. d, Y h:i:s a')
        );
    }
}
