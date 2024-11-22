<?php

namespace App\View\Components\Dashboard\SystemSettings;

use App\Http\Controllers\SettingsController;
use App\Models\SysInfo as ModelsSysInfo;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class SysInfo extends Component
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
        return view('components.dashboard.system-settings.sys-info', [
            'info' => ModelsSysInfo::all()->first(),
            'isVerified' => Auth::user()->email_verified_at !== null,
            'maintenance' => SettingsController::getMaintenanceStatus()
        ]);
    }
}
