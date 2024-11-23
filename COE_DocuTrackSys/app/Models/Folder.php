<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model {
    use HasFactory;

    protected $fillable = [
        'type', //Root, Subroot, Document, Report
        'year',
        'month',
        'folder_id',
        'parent_id',
        'drive_id'
    ];

    public function drive(){
        return $this->belongsTo(Drive::class, 'drive_id'); // Defines inverse of the hasOne in Drive
    }

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }

    public function parentFolder(){
        return $this->belongsTo(Folder::class, 'parent_id');
    }

    public function subFolders(){
        return $this->hasMany(Folder::class, 'parent_id');
    }
}
