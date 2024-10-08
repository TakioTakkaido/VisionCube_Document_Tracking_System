<?php

namespace App\Models;

use Attribute;
use Illuminate\Database\Eloquent\Casts\Attribute as CastsAttribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Log extends Model
{
    use HasFactory;

    protected $fillable = [
        'account',
        'description'
    ];

    protected function createdAt() : CastsAttribute {
        return CastsAttribute::make(
            get: fn ($value) => (string) Carbon::parse($value)
                ->setTimezone('Asia/Singapore')
                ->format('M. d, Y h:i:s a')
        );
    }
}
