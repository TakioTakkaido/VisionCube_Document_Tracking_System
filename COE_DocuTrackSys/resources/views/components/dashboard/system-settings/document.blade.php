<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-auto text-left">
                <h5>Document Settings</h5>
            </div>

            <!-- Warning text and maintenance button on the right -->
            <div class="col-auto d-flex align-items-center">
                <span class="badge badge-warning text-wrap mr-2 text-justify" style="background-color: transparent; color: black;">
                    @if ($maintenance)
                        Warning: Ensure that all changes to the system<br> are executed before disabling the maintenance mode.
                    @else
                        Warning: Ensure that all accounts are logged<br> out before enabling maintenance mode.
                    @endif
                </span>
                {{-- maintenanceBtn --}}
                <button type="button" class="btn btn-primary" id="maintenanceDocumentBtn" data-target="#confirmMaintenanceDocumentModal" data-toggle="modal">
                    @if ($maintenance)
                        Maintenance Mode: On   
                    @else
                        Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>
    

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Sender and Recipients --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row">
            <div class="col participantSettings">
                <h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients</h6>
            </div>
            <div class="col participantSettings" id="editParticipantGroupMemberTitle">
                <h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients of Selected Group</h6>
            </div>
        </div>
        
        <div class="row">
            <div class="col participantSettings">
                <p>Add, remove or delete senders and recipients for the document tracking system.</p>
            </div>
            <div class="col participantSettings">
                <p>Manage participants that belong in your selected participant group.</p>
            </div>
        </div>

        <!-- 3rd row -->
        <div class="row"> 
            <!-- Add, Search Participant -->
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" id="addParticipantText" placeholder="Add New Sender/Recipient">
                            <div class="input-group-append">
                                <button class="btn btn-primary input-group-text addSettings disabled" id="addParticipantBtn">Add</button>
                            </div>
                        </div>
                    </div>
                
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" id="searchParticipantText" placeholder="Search Sender/Recipient">
                            <div class="input-group-append">
                                <span class="input-group-text search"><i class='bx bx-search' style="text-align: center;"></i></span>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>

            <!-- Add, Search Selected Sender/Recipient Group  -->
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" id="searchParticipantGroupParticipantGroupText" placeholder="Search Sender/Recipient Groups" disabled>
                    <div class="input-group-append">
                        <span class="input-group-text search"><i class='bx bx-search' style="text-align: center;"></i></span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Sender/Recipient List --}}
        <div class="row mb-2">
            <div class="col">   
                <ul class="list-group p-0 mb-1 systemParticipantList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                    @foreach ($participants as $participant)
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipant" id="{{"participant".$participant->id}}">
                            {{-- Document Status --}}
                            <span class="text-left mr-auto p-0">{{$participant->value}}</span>
                            {{-- Edit and Delete Buttons --}}
                            <div class="editParticipantBtn mr-2 p-0" 
                                data-id={{$participant->id}} data-value="{{$participant->value}}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                            </div>
                            <div class="deleteParticipantBtn p-0" 
                                data-id={{$participant->id}} data-value="{{$participant->value}}"
                                data-toggle="modal" data-target="#confirmDeleteParticipant"><i class='bx bx-trash' style="font-size: 20px;"></i>
                            </div>
                        </li>
                    @endforeach
                </ul>

                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmDeleteParticipant" tabindex="-1" aria-labelledby="confirmDeleteParticipantLabel" aria-hidden="true" style="z-index: 1060;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmDeleteParticipantText">
                                <!-- Your confirmation text here -->
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-primary" id="confirmDeleteParticipantBtn" data-id="">Delete</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>


            {{-- Participant Members --}}
            <div class="col">
                {{-- Dropdown List of Group from Selected Group --}}
                <ul class="list-group p-0 mb-1 systemParticipantGroupParticipantGroupList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        No child group in this group.
                    </li>
                </ul>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <p>You can manage the senders and recipient for the document tracking system using groups.</p>
            </div>
        </div>

        {{-- Edit Participant Group--}}
        <div class="row">
            <div class="col participantGroupSettings">
                {{-- Add, Search Participant Group --}}
                <div class="row mb-2">
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" id="addParticipantGroupText" placeholder="Add New Sender/Recipient Groups">
                            <div class="input-group-append">
                                <button class="btn btn-primary addSettings disabled" id="addParticipantGroupBtn">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" id="searchParticipantGroupText" placeholder="Search Sender/Recipient Groups">
                            <div class="input-group-append">
                                <span class="input-group-text search"><i class='bx bx-search' style="text-align: center;"></i></span>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>

            {{-- Add, Search Selected Participant Group Participant --}} 
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" id="searchParticipantGroupParticipantText" placeholder="Search Sender/Recipient Groups" disabled>
                    <div class="input-group-append">
                        <span class="input-group-text search"><i class='bx bx-search' style="text-align: center;"></i></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- ParticipantGroup List --}}
            <div class="col">
                <ul class="list-group p-0 mb-1 systemParticipantGroupList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                    @foreach ($participantGroups as $participantGroup)
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipantGroup" id="{{"participantGroup".$participantGroup->id}}">
                        {{-- Document ParticipantGroup --}}
                        <span class="text-left mr-auto p-0">{{$participantGroup->value}}</span>

                        {{-- Edit and Delete Buttons --}}
                        <div class="editParticipantGroupMemberBtn mr-2 p-0" 
                            data-id={{$participantGroup->id}} data-value="{{$participantGroup->value}}"><i class='bx bxs-user-detail' style="font-size: 20px;"></i>
                        </div>

                        <div class="editParticipantGroupBtn mr-2 p-0" 
                            data-id={{$participantGroup->id}} data-value="{{$participantGroup->value}}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                        </div>
                        <div class="deleteParticipantGroupBtn p-0" 
                            data-id={{$participantGroup->id}} data-value="{{$participantGroup->value}}"
                            data-toggle="modal" data-target="#confirmDeleteParticipantGroup"><i class='bx bx-trash' style="font-size: 20px;"></i>
                        </div>
                        </li>
                    @endforeach
                </ul>
  
                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmDeleteParticipantGroup" tabindex="-1" aria-labelledby="confirmDeleteParticipantGroupLabel" aria-hidden="true" style="z-index: 1060;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmDeleteParticipantGroupText">
                                <!-- Your confirmation text here -->
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-primary" id="confirmDeleteParticipantGroupBtn" data-id="">Delete</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div> 
            </div>

            {{-- Dropdown List of Participants from Selected Group --}}
            <div class="col">
                <ul class="list-group p-0 mb-1 systemParticipantGroupParticipantList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        No participants in this group.
                    </li>
                </ul>
            </div>
        </div>
    </div>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Document Types and Categories --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row">
            {{-- Edit Document Type --}}
            <div class="col typeSettings">
                <h6 class="p-0 font-weight-bold mb-0">Edit Document Type</h6>
                <p>Add, remove or delete types for the document tracking system.</p>

                {{-- Add, Search Document Type --}}
                <div class="row mb-2">
                    <div class="col">
                        <div class="input-group">    
                            <input type="text" class="form-control" name="text" id="addTypeText" placeholder="Add New Document Type">
                            <div class="input-group-append">
                                <button class="btn btn-primary input-group-text addSettings disabled" id="addTypeBtn">Add</button>
                            </div>
                        </div>
                    </div>
                    <div class="col"> 
                        <div class="input-group">
                            <input type="text" class="form-control" name="text" id="searchTypeText" placeholder="Search Document Type">
                            <div class="input-group-append">
                                <span class="input-group-text search"><i class='bx bx-search' style='text-align: center;'></i></span>
                            </div>
                        </div>
                    </div>
                </div>
                
                {{-- Type List --}}
                <div class="row">
                    <div class="col">
                        <ul class="list-group p-0 mb-1 systemTypeList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                            @foreach ($types as $type)
                                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemType" id="{{"type".$type->id}}">
                                    {{-- Document Status --}}
                                    <span class="text-left mr-auto p-0">{{$type->value}}</span>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="editTypeBtn mr-2 p-0" 
                                        data-id={{$type->id}} data-value="{{$type->value}}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                                    </div>
                                    <div class="deleteTypeBtn p-0" 
                                        data-id={{$type->id}} data-value="{{$type->value}}"
                                        data-toggle="modal" data-target="#confirmDeleteType"><i class='bx bx-trash' style="font-size: 20px;"></i>
                                    </div>
                                </li>
                            @endforeach
                        </ul>

                        {{-- Popup Confirmation of Deletion --}}
                        <div class="modal fade" id="confirmDeleteType" tabindex="-1" aria-labelledby="confirmDeleteTypeLabel" aria-hidden="true" style="z-index: 1060;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body" id="confirmDeleteTypeText">
                                        <!-- Your confirmation text here -->
                                    </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-primary" id="confirmDeleteTypeBtn" data-id="">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>
                </div>               
            </div>

            <!-- EDIT DOCUMENT STATUS -->
            <div class="col statusSettings">                    
                <h6 class="p-0 font-weight-bold mb-0">Edit Status</h6>
                <p>Add, remove or delete statuses for the document tracking system.</p>
                
                {{-- Add, Search Document Status --}}
                <div class="row mb-2">
                    <div class="col">
                        <div class="input-group">    
                            <input type="text" class="form-control" name="text" id="addStatusText" placeholder="Add New Status">
                            <div class="input-group-append">
                                <input type="color" class="form-control p-0" id="addStatusColor" title="Choose a color" style="width: 40px; cursor: pointer;" value="#ffffff">
                                <button class="btn btn-primary input-group-text addSettings disabled" id="addStatusBtn">Add</button>
                            </div>
                        </div>
                    </div>

                    <div class="col">
                        <div class="input-group">
                            <input type="text" class="form-control" name="text" id="searchStatusText" placeholder="Search Document Status">
                            <div class="input-group-append">
                                <span class="input-group-text search"><i class='bx bx-search' style='text-align: center;'></i></span>
                            </div>
                        </div>
                    </div>
                </div>  
                               
                {{-- Status List --}}
                <div class="row">
                    <div class="col">
                        <ul class="list-group p-0 mb-1 systemStatusList container border rounded list-group-flush" style="height: 250px; max-height: 250px; overflow-y: scroll;">
                            @foreach ($statuses as $status)
                                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemStatus" id="{{"status".$status->id}}">
                                    {{-- Document Status --}}
                                    <span class="text-left mr-auto p-0">{{$status->value}}</span>
                                    {{-- Status Color --}}
                                    <div class="mr-2 p-0" id="{{"statusColor".$status->id}}" style="height: 20px; width: 20px; border-radius: 50%; background-color: {{$status->color}};"></div>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="editStatusBtn mr-2 p-0" 
                                        data-id={{$status->id}} data-value="{{$status->value}}" data-color="{{$status->color}}" title="Edit status"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                                    </div>
                                    <div class="deleteStatusBtn p-0" 
                                        data-id={{$status->id}} data-value="{{$status->value}}" data-color="{{$status->color}}"
                                        data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
  
                        {{-- Popup Confirmation of Deletion --}}
                        <div class="modal fade" id="confirmDeleteStatus" tabindex="-1" aria-labelledby="confirmDeleteStatusLabel" aria-hidden="true" style="z-index: 1060;">
                            <div class="modal-dialog modal-dialog-centered" role="document">
                                <div class="modal-content">
                                    <div class="modal-body" id="confirmDeleteStatusText">
                                        <!-- Your confirmation text here -->
                                    </div>
                                    <div class="modal-footer"> 
                                        <button type="button" class="btn btn-primary" id="confirmDeleteStatusBtn" data-id="">Delete</button>
                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                                    </div>
                                </div>
                            </div>
                        </div>  
                    </div>                   
                </div>   
            </div>           
        </div>
    </div>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit File Extensions --}}
    <div class="container border p-3 rounded mb-5 position-relative">   
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit File Extensions</h6>
                <p>Choose what files can be uploaded to the system.</p>

                <form id="updateFileExtensionForm" method="post" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="row mb-2">
                        <div class="col">
                            <table>
                                @for ($i = 0; $i < sizeof($fileExtensions); $i++)
                                    @if (($i % 3 == 0))
                                        <tr class="p-2">
                                    @endif
                                        <td class="pr-5">
                                            <input type="checkbox" class="editExtension" name="extensions[]" id="{{$fileExtensions[$i]->id}}" {{$fileExtensions[$i]->checked  ? 'checked' : ''}}>
                                            <label for="{{$fileExtensions[$i]->id}}" class="form-check-label">
                                                {{$fileExtensions[$i]->value}}
                                            </label>
                                        </td>
                                    @if ($i % 3 == 2 || $i == sizeof($fileExtensions) - 1)
                                        </tr>
                                    @endif
                                @endfor
                            </table>
                        </div>
                    </div>

                    <div class="row justify-content-end align-items-end">
                        <div class="col-auto">
                            <button type="submit" class="btn btn-primary"    id="fileExtensionSaveBtn">Save Changes</button>        
                            <button type="button" class="btn btn-warning"    id="fileExtensionResetBtn">Reset to Default</button>
                            <button type="button" class="btn btn-secondary"  id="fileExtensionCancelBtn">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmMaintenanceDocumentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($maintenance)
                            Revert System Maintenance?
                        @else
                            Confirm System Maintenance?
                        @endif
                    </h5>
                </div>
                <div class="modal-body text-justify">
                    @if ($maintenance)
                        The system would disable maintenance, all system changes are going to be reflected in the system.
                    @else
                        The system shall undergo maintenance, make sure all users are logged out, and transaction are settled before undergoing to system to the maintenance.
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" data-maintenance="{{ $maintenance ? 'true' : 'false' }}" class="btn btn-primary maintenanceBtn">
                        @if ($maintenance)
                            Revert Maintenance
                        @else
                            Proceed to Maintenance
                        @endif
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>