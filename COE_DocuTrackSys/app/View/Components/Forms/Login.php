<?php

namespace App\View\Components\Forms;

use App\Http\Controllers\SettingsController;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class Login extends Component
{
    /**
     * Create a new component instance.
     */

    // If there is an error, the errors shall be reflected back to the login
    // form and be displayed in its respective input boxes
    public function __construct(public $errors = null){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.forms.login', ['maintenance' => SettingsController::getMaintenanceStatus()]);
    }
}
