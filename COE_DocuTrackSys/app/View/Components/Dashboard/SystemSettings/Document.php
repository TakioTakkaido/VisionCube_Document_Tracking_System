<?php

namespace App\View\Components\Dashboard\SystemSettings;

use App\Models\FileExtension;
use App\Models\Participant;
use App\Models\ParticipantGroup;
use App\Models\Status;
use App\Models\Type;
use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Log as FacadesLog;

class Document extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(){}

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string {

        return view('components.dashboard.system-settings.document', [
            'types' => Type::all(),
            'participants' => Participant::all(),
            'participantGroups' => ParticipantGroup::all(),
            'statuses' => Status::all(),
            'fileExtensions' => FileExtension::all(),
        ]);
    }
}
