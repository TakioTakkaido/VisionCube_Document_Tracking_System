<?php

namespace App\View\Components\Dashboard\TopPanel;

use App\Models\SysInfo;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class About extends Component
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
    public function render(): View|Closure|string {
        return view('components.dashboard.top-panel.about', ['about' => SysInfo::first()->about]);
    }
}