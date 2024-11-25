<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'file',
        'drive_folder'
    ];

    public function seenUploadedAccounts(){
        return $this->belongsToMany(Account::class, 
            'new_upload_reports', 
            'new_upload_report_id',
            'account_id',
        );
    }
}
