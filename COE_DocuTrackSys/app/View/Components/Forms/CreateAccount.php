<?php

namespace App\View\Components\Forms;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class CreateAccount extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public $errors = null){
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string{
        return view('components.forms.create-account');
    }
}
