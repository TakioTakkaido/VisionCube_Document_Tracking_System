<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <!-- Title on the left -->
            <div class="col-auto">
                <h5>Attachment Upload Settings</h5>
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
                <button type="button" class="btn btn-primary" id="maintenanceAccountBtn" data-target="#confirmMaintenanceAttachmentModal" data-toggle="modal">
                    @if ($maintenance)
                        Maintenance Mode: On   
                    @else
                        Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Link New Account --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Link Google Account for Attachment Storage</h6>
                <p>Add a new account to have its to create a Google Drive folder from and create files in it.</p>
            </div>
        </div>

        {{-- Linked Accounts --}}
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <div class="input-group">    
                    <input type="email" class="form-control" name="text" id="addDriveAccountEmail" placeholder="Add Gmail Address">
                    <div class="input-group-append">
                        <button class="btn btn-primary input-group-text driveSettings disabled" id="addDriveAccountBtn">Add Account</button>
                    </div>
                </div>
            </div>
            <div class="col"> 
                <div class="input-group">
                    <input type="text" class="form-control" name="text" id="searchDriveAccountEmail" placeholder="Search Gmail Address">
                    <div class="input-group-append">
                        <span class="input-group-text search"><i class='bx bx-search' style='text-align: center;'></i></span>
                    </div>
                </div>
            </div>
        </div>
            
        {{-- Linked Accounts List --}}
        <div class="row mb-2">
            <div class="col">
                <ul class="list-group p-0 mb-1 driveAccountList container border rounded list-group-flush" style="height: 300; max-height: 300; overflow-y: scroll;">
                    @if (count($drives) !== 0)
                        @foreach ($drives as $drive)
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount" id="{{"driveAccount".$drive->id}}">
                                {{-- Email --}}
                                <span class="text-left mr-auto p-0">{{$drive->email}}</span>
                                
                                {{-- Verified or Not --}}
                                @if ($drive->verified_at === null)
                                    <span class="text-right mr-2 p-0" style="color: gray;">Awaiting Verification</span>    
                                    <div class="driveAccountBtn mr-2 p-0 removeEmailLink"
                                        data-toggle="modal" data-target="#confirmRemoveEmailLink"
                                        data-id={{$drive->id}} data-value="{{$drive->email}}"><i class='bx bx-trash' style="font-size: 20px;"></i>
                                    </div>
                                @else
                                    <span class="text-right mr p-0" style="color: gray;">Verified</span>    
                                @endif
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount">
                            <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                        </li>
                    @endif
                    
                </ul>

                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmRemoveEmailLink" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmRemoveEmailText">
                                <!-- Your confirmation text here -->
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-primary" id="confirmRemoveLinkBtn" data-id="">Remove Account</button>
                                <button type="button" class="btn btn-secondary" id="cancelRemoveLinkBtn" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>
    </div>

    {{-- Manage Linked Accounts --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Manage Linked Google Accounts</h6>
                <p>Manage what account shall be used to store attachments with. Only one account would be used for storage.</p>
            </div>
        </div>

        <div class="row mb-2 justify-content-end align-items-end">
            {{-- Linked Accounts --}}
            <div class="col"> 
                <div class="input-group">
                    <input type="text" class="form-control" name="text" id="searchManageDriveAccountEmail" placeholder="Search Gmail Address">
                    <div class="input-group-append">
                        <span class="input-group-text search"><i class='bx bx-search' style='text-align: center;'></i></span>
                    </div>
                </div>
            </div>
        </div>
            
        {{-- Linked Accounts List --}}
        <div class="row mb-2">
            <div class="col">
                <ul class="list-group p-0 mb-1 manageDriveAccountList container border rounded list-group-flush" style="height: 300; max-height: 300; overflow-y: scroll;">
                    @if (count($drives) !== 0)
                        @foreach ($drives as $drive)
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center manageDriveAccount" id="{{"manageDriveAccount".$drive->id}}">
                                {{-- Email --}}
                                <span class="text-left mr-auto p-0">{{$drive->email}}</span>
                                <span class="text-right mr-2 p-0 disabledDriveAccount" style="color: gray; {{ $drive->disabled ? '' : 'display: none;' }}">Disabled</span>
                                
                                {{-- Buttons --}}
                                <div class="driveAccountBtn mr-2 p-0 transferAttachments"
                                    data-id={{$drive->id}} data-value="{{$drive->email}}"><i class='bx bx-transfer' style="font-size: 20px;"></i>
                                </div>

                                <div class="driveAccountBtn mr-2 p-0 disableEmail"
                                    data-id={{$drive->id}} data-value="{{$drive->email}}"><i class='bx bxs-user-x' style="font-size: 20px;"></i>
                                </div>

                                <div class="driveAccountBtn mr-2 p-0 removeEmail"
                                    data-toggle="modal" data-target="#confirmDeleteManageDriveAccount"
                                    data-id={{$drive->id}} data-value="{{$drive->email}}"><i class='bx bx-trash' style="font-size: 20px;"></i>
                                </div>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount">
                            <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                        </li>
                    @endif
                    
                </ul>

                {{-- Confirm Transfer --}}
                <div class="modal fade" id="confirmTransferAttachments" tabindex="-1" aria-hidden="true" style="z-index: 1060;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmTransferAttachmentsText">
                                <ul class="list-group p-0 mb-1 transferDriveAccountList container border rounded list-group-flush" style="height: 300; max-height: 300; overflow-y: scroll;">
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmDeleteManageDriveAccount" tabindex="-1" aria-labelledby="confirmDeleteTypeLabel" aria-hidden="true" style="z-index: 1060;">
                    <div class="modal-dialog modal-dialog-centered" role="document">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmDeleteManageDriveAccountText">
                                <!-- Your confirmation text here -->
                            </div>
                            <div class="modal-footer"> 
                                <button type="button" class="btn btn-primary" id="deleteManageDriveAccount" data-id="">Delete</button>
                                <button type="button" class="btn btn-secondary" id="cancelDeleteManageDriveAccount" data-dismiss="modal">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>  
            </div>
        </div>             
    </div>

    {{-- Assign Accounts --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Manage Attachment Storage of Linked Accounts</h6>
                <p>Manage what kind of attachments are going to be stored in each linked accounts.</p>
            </div>
        </div>

        {{-- Search Linked Accounts Lit --}}
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col"> 
                <div class="input-group">
                    <input type="text" class="form-control" name="text" id="searchAssignDriveAccountEmail" placeholder="Search Gmail Address">
                    <div class="input-group-append">
                        <span class="input-group-text search"><i class='bx bx-search' style='text-align: center;'></i></span>
                    </div>
                </div>
            </div>
        </div>
            
        {{-- Linked Accounts List --}}
        <div class="row mb-2">
            <div class="col">
                <ul class="list-group p-0 mb-1 assignDriveAccountList container border rounded list-group-flush" style="max-height: 300px; overflow-y: scroll;">
                    @if (count($drives) !== 0)
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            {{-- Header Row --}}
                            <span class="text-left font-weight-bold mr-auto p-0">Email</span>
                            <span class="text-right font-weight-bold mr-2 p-0">Reports</span>
                            <span class="text-right font-weight-bold p-0">Documents</span>
                        </li>
                        @foreach ($drives as $drive)
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center assignDriveAccount" id="{{"driveAccount".$drive->id}}" data-id="{{$drive->id}}">
                                {{-- Email --}}
                                <span class="text-left mr-auto p-0 assignDriveAccountEmail">{{$drive->email}}</span>
                                {{-- Previous Data of the Reports and Documents, 
                                    so that when resetting the document 
                                 --}}
                                <input type="hidden" id="{{"prevCanReport".$drive->id}}" data-value="{{(($drive->canReport == true) || ($drive->canReport == 1))}}">
                                {{-- Checkbox for Reports --}}
                                <input class="canReport text-right p-0 mr-2" type="checkbox" id="{{"canReport".$drive->id}}" data-id="{{$drive->id}}" data-value="{{$drive->email}}" 
                                    {{(($drive->canReport == true) || ($drive->canReport == 1)) ? 'checked' : ''}}>
                
                                {{-- Checkbox for Documents --}}
                                <input class="canDocument text-right p-0" type="checkbox" id="{{"canDocument".$drive->id}}" data-id="{{$drive->id}}" data-value="{{$drive->email}}"
                                    {{(($drive->canDocument == true) || ($drive->canDocument == 1)) ? 'checked' : ''}}>
                            </li>
                        @endforeach
                    @else
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount">
                            <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                        </li>
                    @endif
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
        
        <div class="row justify-content-end align-items-end">
            <div class="col-auto">
                <button type="submit" class="btn btn-primary"    id="assignDriveAccountStorageSaveBtn">Save Changes</button>        
                <button type="button" class="btn btn-warning"    id="assignDriveAccountStorageCancelBtn">Cancel</button>
            </div>
        </div>
    </div>

    {{-- Confirm Maintenance Modal --}}
    <div class="modal fade" id="confirmMaintenanceAttachmentModal" tabindex="-1" role="dialog" aria-hidden="true">
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