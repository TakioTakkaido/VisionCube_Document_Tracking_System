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
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Account extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role'
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
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    // Verify if user is the admin
    public function isAdmin(){
        return $this->role === AccountRole::ADMIN;
    }

    // Below are the relationships of Document to other models in the system.
    // Document belongs to one User
    // Document has many DocumentVersions

    public function documents(){
        return $this->hasMany(Document::class);
    }
}

