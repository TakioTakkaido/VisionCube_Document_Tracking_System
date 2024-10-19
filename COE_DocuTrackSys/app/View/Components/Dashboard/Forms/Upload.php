<?php

namespace App\View\Components\Dashboard\Forms;

use App\AccountRole;
use App\DocumentCategory;
use App\Http\Controllers\ParticipantGroupController;
use App\Models\Category;
use App\Models\ParticipantGroup;
use App\Models\Status;
use App\Models\Type;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class Upload extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {
        return view('components.dashboard.forms.upload',[ 
            'user'          => Auth::user(),
            'docTypes'      => Type::all(),
            'docStatuses'   => Status::all(),
            'docCategories' => DocumentCategory::cases(),
            'roles'         => AccountRole::cases(),
            'groups'        => ParticipantGroupController::showAllGroups(),
            'canUpload'     => Auth::user()->canUpload
        ]);
    }
}
