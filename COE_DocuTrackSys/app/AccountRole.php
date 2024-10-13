<?php

namespace App;

enum AccountRole : string {
    case ADMIN = 'Admin';
    case ASSISTANT = 'Assistant';
    case CLERK = 'Clerk';
    case SECRETARY = 'Secretary';
}