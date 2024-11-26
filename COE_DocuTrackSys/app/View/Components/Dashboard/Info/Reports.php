<?php

namespace App\View\Components\Dashboard\Info;

use App\Models\Drive;
use App\Models\SysInfo;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Reports extends Component
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
        return view('components.dashboard.info.reports', [
            'drives'    => Drive::where('canReport', true)
                                    ->where('disabled', false)
                                    ->whereNot('verified_at', null)
                                    ->orderBy('id', 'asc')
                                    ->get(),
            'systemName'=> SysInfo::first()->name
        ]);
    }
}
