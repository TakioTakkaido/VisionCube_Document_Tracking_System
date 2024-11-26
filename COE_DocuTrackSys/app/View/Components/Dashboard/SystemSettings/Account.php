<?php

namespace App\View\Components\Dashboard\SystemSettings;

use App\AccountRole;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\SettingsController;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Account extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string{
        return view('components.dashboard.system-settings.account', [
            'roles' => AccountRole::cases(),
            'secretary' => AccountController::getSecretaryAccesses(),
            'clerk' => AccountController::getClerkAccesses(),
            'assistant' => AccountController::getAssistantAccesses(),
            'maintenance' => SettingsController::getMaintenanceStatus()
        ]);
    }
}
