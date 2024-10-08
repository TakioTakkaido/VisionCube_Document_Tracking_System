<div class="modal fade" id="systemSettings" tabindex="-1" role="dialog" aria-hidden="true">
    {{-- 
    System Settings
        Accounts Settings
            General
                Add Account
                Edit Account
                Set Account Status (deactivate/reactivate)
            Account Roles
                Secretary
                    Edit access
                Asst. Secretary
                Clerk
        Document Settings
            Sender and Recipients
                Uni. President
                Offices
                Office Heads
                Faculty
                Dept. Head
                Secretaries
                Others
                itanong to kay maam ivy...
            Document Category
                Incoming
                Outgoing
                Archived
                more...
            Document Type
                Memoranda
                Letter
                Request
                more...
            Document Status
                Accepted
                etc...
                more...
            File Extensions
                all
                pdf     (default)
                docx    (default)
                doc     (default)
                zip
                rar
                other...
    --}}
    <div class="modal-dialog modal-lg" role="systemSettings">
        <div class="modal-content">
        {{-- Header --}}
        <div class="modal-header">
            <h3 class="modal-title">System Settings</h3>
        </div>
            
        
        {{-- Settings, Place all components here --}}
        <div class="modal-body" id="systemSettingsBody">
            {{-- Account Settings --}}
            <h4>Account Settings</h4>

            {{-- Change Access of Account Roles --}}
            <h5>Change Access of Account Roles</h5>
            <p>Change what each type of account can perform in the system.</p>

            {{-- Show different roles --}}
            {{-- 
                Secretary
                Clerk
             --}}
            
            {{-- Update Secretary Role --}}
            
            <form id="updateSecretaryAccountRole" method="post" autocomplete="off">
                <h6>Secretary</h6>
                @csrf
                @method('POST')
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                {{-- <p>{{$fileExtensions}}</p> --}}

                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canUploadSecretary">
                    <label for="canUploadSecretary" class="form-check-label">Upload Document</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canEditSecretary">
                    <label for="canEditSecretary" class="form-check-label">Edit Document</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canMoveSecretary">
                    <label for="canMoveSecretary" class="form-check-label">Move to Incoming/Outgoing</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canArchivedSecretary">
                    <label for="canArchivedSecretary" class="form-check-label">Move to Archived</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canDownloadSecretary">
                    <label for="canDownloadSecretary" class="form-check-label">Download Document File</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canPrintSecretary">
                    <label for="canPrintSecretary" class="form-check-label">Print Document File</label>
                </div>

                <button type="submit" class="btn btn-primary"    id="secretarySaveBtn">Save Changes</button>        
                <button type="button" class="btn btn-secondary"  id="secretaryCancelBtn">Cancel</button>
            </form>

            {{-- Update Clerk Role --}}
            <form id="updateClerkAccountRole" method="post" autocomplete="off">
                <h6>Clerk</h6>
                @csrf
                @method('POST')
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                {{-- <p>{{$fileExtensions}}</p> --}}
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canUploadClerk">
                    <label for="canUploadClerk" class="form-check-label">Upload Document</label>
                </div>

                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canEditClerk">
                    <label for="canEditClerk" class="form-check-label">Edit Document</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canMoveClerk">
                    <label for="canMoveClerk" class="form-check-label">Move to Incoming/Outgoing</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canArchivedClerk">
                    <label for="canArchivedClerk" class="form-check-label">Move to Archived</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canDownloadClerk">
                    <label for="canDownloadClerk" class="form-check-label">Download Document File</label>
                </div>
                    
                <div class="form-check">
                    <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canPrintClerk">
                    <label for="canPrintClerk" class="form-check-label">Print Document File</label>
                </div>
                    
                <button type="submit" class="btn btn-primary"    id="clerkSaveBtn">Save Changes</button>        
                <button type="button" class="btn btn-secondary"  id="clerkCancelBtn">Cancel</button>
            </form>

            {{-- Show the checklist of functions for each --}}
            {{-- List of functions:
                Upload document
                Edit document
                Move to Incoming/Outgoing
                Move to Archived
                Download document file
                Print document file
            --}}
            {{-- By default:
                Secretary:
                Upload document /
                Edit document /
                Move to Incoming/Outgoing /
                Move to Archived /
                Download document file /
                Print document file /

                Clerk: 
                Upload document
                Edit document /
                Move to Incoming/Outgoing /
                Move to Archived /
                Download document file /
                Print document file /
            --}}

            {{-- Document Settings --}}
            <h4>Document Settings</h4>

            {{-- Edit Sender and Recipients --}}
            <h5>Edit Sender and Recipients</h5>
            <p>Add, remove or delete senders and recipients for the document tracking.</p>
            <div class="row">
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="participantList" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Participants List
                    </button>
                    <div class="dropdown-menu" style="max-height: 400px; overflow-y: scroll;" aria-labelledby="participantList">
                        @foreach ($participants as $participant)
                            <div class="dropdown-item d-flex justify-content-between align-items-center" style="max-width: 1000px !important;" href="#" id={{"participant".$participant->id}}>
                                <span class="text-left">{{$participant->value}}</span>
                                <div class="ml-auto">
                                    <button type="button" class="btn btn-primary btn-sm">Edit</button>
                                    <button type="button" class="btn btn-primary btn-sm">Delete</button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
    
                <div class="form-group row">
                    <label for="text"></label>
                    <input type="text" class="form-control" name="text" id="text" aria-describedby="helpId" placeholder="Add Participant">
                    
                    {{-- If changes are present, show save changes --}}
                    <a name="submit" class="btn btn-primary" href="#" role="button">Save Changes</a>        
                    <a name="cancel" class="btn btn-secondary" href="#" role="button">Cancel</a>
                </div>
            </div>

            {{-- Edit Document Categories --}}
            <h5>Edit Document Categories</h5>
            <p>NOTE: Edited or deleted categories won't affect the status of the previous documents.</p>

            {{-- Form
                Category list, edit and delete
                    Once edit is pressed, it would appear 
                    in the category list and the add would change
                    to Edit
                Add Category
                Save Changes
                    Confirm the changes
                Discard
            --}}

            {{-- Place new form here --}}


            {{-- Dropdown List of Categories --}}
                    


            {{-- Edit Document Type --}}
            <h5>Edit Document Type</h5>
            <p>NOTE: Edited or deleted type won't affect the status of the previous documents.</p>
            {{-- Dropdown List of Document Types --}}

            
            {{-- Place new form here --}}


            {{-- Edit Document Status --}}
            <h5>Edit Document Status</h5>
            <p>NOTE: Edited or deleted statuses won't affect the status of the previous documents.</p>
            
            <div class="row align-items-center pl-2">
                {{-- Form for editing status --}}
                <form id="updateStatusForm" method="POST" autocomplete="off" class="flex-grow-1">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="statusId" >
                        
                    <div class="input-group flex-grow-1">
                        <div class="input-group-prepend">
                            {{-- Dropdown List of Document Status --}}
                            {{-- Status Dropdown Button --}}
                            <button class="btn btn-secondary dropdown-toggle" type="button" id="statusList" data-toggle="dropdown" aria-haspopup="true"
                                aria-expanded="false">
                                Statuses List
                            </button>
                            <div class="dropdown">
                                {{-- Status List --}}
                                <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="statusList">
                                    @foreach ($statuses as $status)
                                        <div class="dropdown-item d-flex justify-content-between align-items-center status" style="max-width: 1000px !important;">
                                            {{-- Document Status --}}
                                            <span class="text-left">{{$status->value}}</span>

                                            {{-- Edit and Delete Buttons --}}
                                            <div class="ml-auto">
                                                <button type="button" class="btn btn-primary btn-sm editStatusBtn" 
                                                    id={{$status->id}} data-id={{$status->id}} value={{$status->value}}>Edit</button>
                                                <button type="button" class="btn btn-primary btn-sm deleteStatusBtn" 
                                                    id={{$status->id}} data-id={{$status->id}} value={{$status->value}}
                                                    data-toggle="modal" data-target="#confirmDeleteStatus">Delete</button>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                        <input type="text" class="form-control" name="text" id="statusText" placeholder="Add Document Status">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"    id="statusSaveBtn">Save Changes</button>        
                            <button type="button" class="btn btn-secondary"  id="statusCancelBtn">Cancel</button>
                        </div>
                    </div>                        
                        
                </form>

                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmDeleteStatus" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteStatus" style="z-index: 1060;" aria-hidden="true">
                    <div class="modal-dialog" role="status">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmDeleteStatusText">
                                Confirm deleting status: .{{$status->value}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="confirmDeleteStatusBtn" data-id="">Yes</button>
                                <button type="button" class="btn btn-secondary" id="cancelDeleteStatusBtn">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Edit File Extensions --}}
            <h5>Edit File Extensions</h5>
            <p>Choose what files can be uploaded to the system</p>
            {{-- Get the current available file extensions --}}
            {{-- Checklist of file extensiosn --}}
            <form id="updateFileExtensionForm" method="post" autocomplete="off">
                @csrf
                @method('POST')
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                {{-- <p>{{$fileExtensions}}</p> --}}
                @foreach ($fileExtensions as $fileExtension)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input editExtension" name="extensions[]" id="{{$fileExtension->id}}" {{$fileExtension->checked == 'true' ? 'checked' : ''}}>
                        <label for="{{$fileExtension->id}}" class="form-check-label">
                            {{$fileExtension->value}}
                        </label>
                    </div>
                @endforeach

                <button type="submit" class="btn btn-primary"    id="fileExtensionSaveBtn">Save Changes</button>        
                <button type="button" class="btn btn-secondary"  id="fileExtensionCancelBtn">Cancel</button>
            </form>
            {{-- Save changes --}}

        </div>

        {{-- Close Button --}}
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelBtn">Cancel</button>
        </div>

        </div>
    </div>
</div>