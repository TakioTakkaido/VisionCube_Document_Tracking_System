<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: DocumentStatus
// Contains the different types of document in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum DocumentStatus : string
{
    case ACCEPTED = 'Accepted';
    case IN_PROGRESS = 'In Progress';
    case TO_BE_REVISED = 'To Be Revised';
    case ON_HOLD = 'On Hold';
    case FOLLOW_UP = 'To Be Followed Up';
    case DEFERRED = 'Deferred';
    case ARCHIVED = 'Archived';
    case VOIDED = 'Voided';
    case PENDING = 'Pending';
    case DEFAULT = 'default';
    
}
