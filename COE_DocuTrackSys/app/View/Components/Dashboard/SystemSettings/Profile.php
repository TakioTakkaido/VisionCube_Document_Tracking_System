<?php

namespace App\View\Components\Dashboard\SystemSettings;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Profile extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct() {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.dashboard.system-settings.profile', [
            'isVerified' => Auth::user()->email_verified_at !== null
        ]);
    }
}
