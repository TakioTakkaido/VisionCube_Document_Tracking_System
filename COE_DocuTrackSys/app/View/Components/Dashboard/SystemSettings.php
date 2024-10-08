<?php

namespace App\View\Components\Dashboard;

use App\Http\Controllers\AccountController;
use App\Models\Category;
use App\Models\FileExtension;
use App\Models\Participant;
use App\Models\Status;
use App\Models\Type;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SystemSettings extends Component
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
        return view('components.dashboard.system-settings', [
            'categories' => Category::all(),
            'types' => Type::all(),
            'participants' => Participant::all(),
            'statuses' => Status::all(),
            'fileExtensions' => FileExtension::all(),
            'secretary' => AccountController::getSecretaryRole(),
            'clerk' => AccountController::getClerkRole()
        ]);
    }
}
