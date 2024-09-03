<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: DocumentStatus
// Contains the different types of document in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum DocumentStatus : string
{
    case APPROVED = 'approved';
    case PENDING = 'pending';
    case REVISION = 'revision';
    case DEFERRED = 'deferred';
    case ARCHIVED = 'archived';
    case OUTGOING = 'outgoing';
    case INCOMING = 'incoming';
    case DEFAULT = 'default';
}
