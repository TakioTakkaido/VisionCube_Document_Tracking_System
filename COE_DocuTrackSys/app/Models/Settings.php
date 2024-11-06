<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'maintenance',

        // For logging, once logged, clear the contents of all of this
        'detail',
        'access',
        'addedParticipant',
        'deletedParticipant',
        'addedParticipantGroup',
        'deletedParticipantGroup',
        'updatedParticipant',
        'updatedParticipantGroup',
        'updatedParticipant',
        'addedType',
        'deletedType',
        'addedStatus',
        'deletedStatus',
        'fileExtensions'
    ];

    protected function casts(): array {
        return [
            'access' => 'array',
            'addedParticipant' => 'array',
            'deletedParticipant' => 'array',
            'addedParticipantGroup' => 'array',
            'deletedParticipantGroup' => 'array',
            'updatedParticipant' => 'array',
            'updatedParticipantGroup' => 'array',
            'addedType' => 'array',
            'deletedType' => 'array',
            'addedStatus' => 'array',
            'deletedStatus' => 'array',
            'fileExtensions' => 'array'
        ];
    }

    /**
     * Account Accesses:
     * Secretary
     * Can Edit
     * Can etc...
     * 
     * Added Document Sender/Recipient:
     * 
     * Deleted Document Sender/Recipient:
     * 
     * Participant Name Updated Members:
     * 
     * Added Type:
     * 
     * Deleted Type:
     * 
     * Added Status:
     * 
     * Deleted Status: 
     * 
     * Allowed File Extensions:
     * 
     */
}
