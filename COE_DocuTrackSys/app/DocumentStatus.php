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
    case REVISION = 'revision';
    case REJECTED = 'rejected';
    case DEFAULT = 'default';
}
