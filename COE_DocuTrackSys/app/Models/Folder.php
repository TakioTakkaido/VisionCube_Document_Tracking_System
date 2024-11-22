<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Folder extends Model {
    use HasFactory;

    protected $fillable = [
        'type',
        'year',
        'month',
        'disabled',
        'folder_id'
    ];

    public function attachments(){
        return $this->hasMany(Attachment::class);
    }
}
