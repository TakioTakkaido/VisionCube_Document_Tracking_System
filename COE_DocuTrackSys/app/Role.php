<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: Roles
// Contains the different users of the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum Role: string
{
    case ADMIN = 'admin';
    case CLERK = 'clerk';
    case ASST = 'asst';
    case ARCHIVES = 'archives';
}
