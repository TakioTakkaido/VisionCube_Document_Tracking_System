<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: DocumentStatus
// Contains the different types of document in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum DocumentStatus : string
{
    case DONE = 'done';
    case ON_PROGRESS = 'on_progress';
    case TO_BE_CHECKED = 'to_be_checked';
    case ON_HOLD = 'on_hold';
    case FOLLOW_UP = 'follow_up';
    case DEFERRED = 'deferred';
    case ARCHIVED = 'archived';
    case DEFAULT = 'default';
}
