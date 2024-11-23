<?php

namespace App\View\Components\Dashboard;

use App\Models\Drive;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Homepage extends Component
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
        return view('components.dashboard.homepage', [
            'isAdmin'   => Auth::user()->role == 'Admin',
            'drives'    => Drive::where('canReport', true)
                                    ->where('disabled', false)
                                    ->whereNot('verified_at', null)
                                    ->orderBy('id', 'asc')
                                    ->get()
        ]);
    }
}
