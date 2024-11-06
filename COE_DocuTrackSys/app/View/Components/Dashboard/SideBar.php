<?php

namespace App\View\Components\Dashboard;

use App\Http\Controllers\SettingsController;
use Closure;
use Illuminate\Container\Attributes\Auth;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth as FacadesAuth;
// use App\Models\Account;

class SideBar extends Component
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
        $user = FacadesAuth::user();
        return view('components.dashboard.side-bar', [
            'canUpload' => FacadesAuth::user()->canUpload,
            'isAdmin' => $user->role == 'Admin',
            'maintenance' => SettingsController::getMaintenanceStatus()
        ]);
    }
}
