<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: DocumentType
// Contains the different types of document in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum DocumentType : string
{
    case MEMO = 'memo';
    case LETTER = 'letter';
    case REQUISITION = 'requisition';
    case DEFAULT = 'default';
}
