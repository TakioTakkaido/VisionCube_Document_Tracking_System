<?php

namespace App\Models;

// VISION CUBE SOFTWARE CO. 
// Model: Account
// Represents the account that of the user in
// COE Document Tracking System. 
// It contains:
// -Information relevant to the account
// Contributor/s: 
// Calulut, Joshua Miguel C.

use App\AccountRole;
use App\Models\ResetPasswordToken;
use App\Models\EmailVerificationToken;

use Illuminate\Database\Eloquent\Relations\HasOne;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\Feature\Auth\EmailVerificationTest;

class Account extends Authenticatable implements MustVerifyEmail {
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Basic info
        'name',
        'email',
        'password',
        'role',
        'deactivated',
        
        // Access Functions
        'canUpload',
        'canEdit',
        'canMove',
        'canArchive',
        'canDownload',
        'canReport',

        'lastChangedName'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',

        'email_verification_token',
        'used_email_token',
        
        'reset_password_token',
        'used_password_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    protected function isAdmin() : bool{
        return $this->role == 'Admin';
    }

    public function reset_password_token(){
        return $this->hasOne(ResetPasswordToken::class);
    }

    public function generateResetPasswordToken() {
        $this->reset_password_token()->create([
            'token' => Str::random(60),
            'used' => false,
        ]);
    }

    public function email_verification_token(){
        return $this->hasOne(EmailVerificationToken::class);
    }

    

    public function generateVerifyEmailToken() {
        $this->email_verification_token()->delete();

        $token = new EmailVerificationToken([
            'token' => Str::random(60),
            'used' => false
        ]);

        $this->email_verification_token()->save($token);
        $this->save();
    } 

    public function isVerified() : bool {
        return $this->email_verified_at !== null;
    }

    public function newlyUpdatedDocuments(){
        return $this->belongsToMany(Document::class, 
            'new_update_documents',
            'account_id',
            'new_update_document_id'
        );
    }

    public function newlyUploadedDocuments(){
        return $this->belongsToMany(Document::class, 
            'new_upload_documents', 
            'account_id',
            'new_upload_document_id'
        );
    }

    public function newlyUploadedReports(){
        return $this->belongsToMany(Report::class, 
            'new_upload_reports', 
            'account_id',
            'new_upload_report_id'
        );
    }
}

