<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipantGroup extends Model {
    use HasFactory;

    protected $fillable = [
        'value'
    ];

    public function groups() {
        return $this->belongsToMany(ParticipantGroup::class, 
        'participant_group_participant_group', 
        'parent_participant_group_id', 
        'participant_group_id');
    }

    public function participants(){
        return $this->belongsToMany(Participant::class, 
        'participant_group_participant',
        'participant_group_id',
        'participant_id');
    }
}
