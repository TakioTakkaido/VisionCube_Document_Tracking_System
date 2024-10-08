<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantGroup extends Model {
    use HasFactory;

    protected $fillable = [
        'value'
    ];

    public function groups(){
        return $this->hasMany(ParticipantGroup::class);
    }

    public function participants(){
        return $this->hasMany(Participant::class);
    }
}
