<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: DocumentType
// Contains the different types of document in the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum DocumentType : string
{
    case MEMO = 'Memoranda';
    case LETTER = 'Letter';
    case REQUISITION = 'Requisition';
    case DEFAULT = 'default';
}
