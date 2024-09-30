<?php

namespace App\View\Components\Dashboard;

use Closure;

use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class TopPanel extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $user = null){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.dashboard.top-panel');
    }
}
