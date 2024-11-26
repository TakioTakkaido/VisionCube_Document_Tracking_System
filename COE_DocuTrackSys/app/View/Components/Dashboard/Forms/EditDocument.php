<?php

namespace App\View\Components\Dashboard\Forms;

use App\AccountRole;
use App\Http\Controllers\ParticipantGroupController;
use App\Models\Status;
use App\Models\Type;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class EditDocument extends Component
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
        return view('components.dashboard.forms.edit-document', [
            'user'          => Auth::user(),
            'docTypes'      => Type::all(),
            'docStatuses'   => Status::all(),
            'roles'         => AccountRole::cases(),
            'groups'        => ParticipantGroupController::showAllGroups()
        ]);
    }
}
