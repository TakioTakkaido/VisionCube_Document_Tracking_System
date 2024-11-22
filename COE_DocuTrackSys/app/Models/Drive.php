<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Drive extends Model {
    use HasFactory;

    protected $fillable = [
        'email',
        'verified_at',
        'disabled',
        'canDocument',
        'canReport'
    ];

    protected $hidden = [
        'email_verification_token'
    ];

    public function email_verification_token(){
        return $this->hasOne(DriveEmailVerificationToken::class);
    }
    
    public function generateVerifyEmailToken() {
        $this->email_verification_token()->delete();

        $token = new DriveEmailVerificationToken([
            'token' => Str::random(60),
            'used' => false
        ]);

        $this->email_verification_token()->save($token);
        $this->save();
    } 

    public function documentFolder(){
        return $this->hasOne(Folder::class);
    }

    public function reportFolder(){
        return $this->hasOne(Folder::class);
    }
}
