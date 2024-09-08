<?php

namespace App;

enum AccountRole : string
{
    case ADMIN = 'admin';
    case CLERK = 'clerk';
    case ARCHIVES = 'archives';
    case DEFAULT = 'default';
}