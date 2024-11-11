<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('documentUploaded', function () {
    return Auth::check();
});

Broadcast::channel('documentUpdated', function () {
    return Auth::check();
});

Broadcast::channel('reportGenerated', function () {
    return Auth::check();
});

Broadcast::channel('maintenanceReverted', function () {
    return Auth::check();
});