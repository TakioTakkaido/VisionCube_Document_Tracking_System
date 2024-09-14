<?php

namespace App;

enum DocumentCategory : string
{
    case INCOMING = 'Incoming';
    case OUTGOING = 'Outgoing';
    case DEFAULT = 'default';
}
