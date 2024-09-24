<?php

namespace App;

enum AccountRole : string
{
    case ADMIN = 'Admin';
    case CLERK = 'Clerk';
    case ASSISTANT = 'Assistant';
    case ARCHIVES = 'Archives';
    case GUEST = 'Guest';
}