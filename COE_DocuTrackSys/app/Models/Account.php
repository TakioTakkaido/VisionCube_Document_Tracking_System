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
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'canPrint'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
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


    public function sendEmailVerificationNotification() {
        $this->notify(new VerifyEmail);
    }

    public function isVerified() : bool {
        return $this->email_verified_at !== null;
    }
}

