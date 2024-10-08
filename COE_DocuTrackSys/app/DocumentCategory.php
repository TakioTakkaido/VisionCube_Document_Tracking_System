<?php

namespace App;

enum DocumentCategory : string
{
    case INCOMING = 'Incoming';
    case OUTGOING = 'Outgoing';
    case ARCHIVED = 'Archived';
    case DEFAULT = 'default';
}
