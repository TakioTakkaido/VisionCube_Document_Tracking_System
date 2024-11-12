<?php

namespace App\Models;

use App\Models\Account;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EmailVerificationToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'account_id',
        'token',
        'used'
    ];

    public function account(){
        return $this->belongsTo(Account::class);
    }
}
