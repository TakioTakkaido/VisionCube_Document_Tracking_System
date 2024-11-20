<?php

namespace App\View\Components\Dashboard;

use App\Models\FileExtension;
use Closure;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class TopPanel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.top-panel', [
            'user' => Auth::user(),
            'isVerified' => Auth::user()->email_verified_at !== null
        ]);
    }
}
