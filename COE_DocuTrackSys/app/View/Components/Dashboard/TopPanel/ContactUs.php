<?php

namespace App\View\Components\Dashboard\TopPanel;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class ContactUs extends Component
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
        return view('components.dashboard.top-panel.contact-us');
    }
}
