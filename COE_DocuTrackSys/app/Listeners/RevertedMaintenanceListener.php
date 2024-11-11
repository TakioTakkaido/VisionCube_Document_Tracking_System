<?php

namespace App\Listeners;

use App\Events\RevertedMaintenance;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RevertedMaintenanceListener
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(RevertedMaintenance $event): void
    {
        //
    }
}
