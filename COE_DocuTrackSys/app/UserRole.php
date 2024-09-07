<?php

namespace App;

// VISION CUBE SOFTWARE CO. 
// Enum: UserRole
// Contains the different users of the system.
// Contributor/s: 
// Calulut, Joshua Miguel C.

enum UserRole: string
{
    case ADMIN = 'admin';
    case CLERK = 'clerk';
    case ARCHIVES = 'archives';
    case DEFAULT = 'default';
}
