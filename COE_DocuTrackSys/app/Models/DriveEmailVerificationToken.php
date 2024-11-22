<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DriveEmailVerificationToken extends Model {
    use HasFactory;

    protected $fillable = [
        'drive_id',
        'token',
        'used'
    ];

    public function drive(){
        return $this->belongsTo(Drive::class);
    }
}
