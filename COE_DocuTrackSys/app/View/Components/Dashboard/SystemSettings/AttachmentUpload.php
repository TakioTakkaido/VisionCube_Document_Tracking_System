<?php

namespace App\View\Components\Dashboard\SystemSettings;

use App\Http\Controllers\SettingsController;
use App\Models\Drive;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class AttachmentUpload extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.system-settings.attachment-upload', [
            'drives' => Drive::all(),
            'verifiedDrives' => Drive::whereNot('verified_at', null)->get(),
            'maintenance' => SettingsController::getMaintenanceStatus()
        ]);
    }
}
