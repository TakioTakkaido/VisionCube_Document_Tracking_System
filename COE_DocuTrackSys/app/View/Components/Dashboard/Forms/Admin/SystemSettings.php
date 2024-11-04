<?php

namespace App\View\Components\Dashboard\Forms\Admin;

use App\Http\Controllers\SettingsController;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SystemSettings extends Component {
    /**
     * Create a new component instance.
     */

    public function __construct() {}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.forms.admin.system-settings', [
            'maintenance', SettingsController::getMaintenanceStatus()
        ]);
    }
}
