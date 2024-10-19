<div>
    <h3 id="systemSettingsTitle">System Settings</h3>
    <h4>Document Settings</h4>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Sender and Recipients --}}
    <h5>Edit Sender and Recipients</h5>
    <p>Add, remove or delete senders and recipients for the document tracking system.</p>
    {{-- 
        Participants List  simple dropdown
        Add/Edit Participant simple form

        Group List simple dropdown
        Add/Edit Group simple form
        Add/Edit Participant to Group group/participants
    --}}
    <div class="row align-items-center pl-2">
        {{-- Form for editing --}}
    
        <form id="updateParticipantForm" method="POST" autocomplete="off" class="flex-grow-1">
            @csrf
            @method('POST')
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="participantId" >
                
            <div class="input-group flex-grow-1">
                <div class="input-group-prepend">
    
                {{-- Dropdown List of Categories --}}
                {{-- Participant Dropdown Button --}}
    
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="participantList" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Participant List
                    </button>
                    <div class="dropdown">
                        {{-- Participant List --}}
                        <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="participantList">
                            @foreach ($participants as $participant)
                                <div class="dropdown-item d-flex justify-content-between align-items-center participant" style="max-width: 1000px !important;">
                                    {{-- Document Participant --}}
                                    <span class="text-left">{{$participant->value}}</span>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-primary btn-sm editParticipantBtn" 
                                            id={{$participant->id}} data-id={{$participant->id}} value={{$participant->value}}>Edit</button>
                                        <button type="button" class="btn btn-primary btn-sm deleteParticipantBtn" 
                                            id={{$participant->id}} data-id={{$participant->id}} value={{$participant->value}}
                                            data-toggle="modal" data-target="#confirmDeleteParticipant">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
    
                <input type="text" class="form-control" name="text" id="participantText" placeholder="Add Document Participant">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="participantSaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="participantCancelBtn">Cancel</button>
                </div>
            </div>                        
        </form>
    
        {{-- Popup Confirmation of Deletion --}}
        <div class="modal fade" id="confirmDeleteParticipant" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteParticipant" style="z-index: 1060;" aria-hidden="true">
            <div class="modal-dialog" role="participant">
                <div class="modal-content">
                    <div class="modal-body" id="confirmDeleteParticipantText">
                        Confirm deleting participant: .{{$participant->value}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmDeleteParticipantBtn" data-id="">Yes</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteParticipantBtn">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div> 
    
    {{-- ////////////////////////////////////////////////////////////////// --}}
    <p>You can manage the senders and recipient for the document tracking system using groups.</p>
    <div class="row align-items-center pl-2">
        {{-- Form for editing --}}
    
        <form id="updateParticipantGroupForm" method="POST" autocomplete="off" class="flex-grow-1">
            @csrf
            @method('POST')
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="participantGroupId" >
                
            <div class="input-group flex-grow-1">
                <div class="input-group-prepend">
    
                {{-- Dropdown List of Categories --}}
                {{-- ParticipantGroup Dropdown Button --}}
    
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="participantGroupList" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Participant Group List
                    </button>
                    <div class="dropdown">
                        {{-- Participant Group List --}}
                        <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="participantGroupList">
                            @foreach ($participantGroups as $participantGroup)
                                <div class="dropdown-item d-flex justify-content-between align-items-center participantGroup" style="max-width: 1000px !important;">
                                    {{-- Document ParticipantGroup --}}
                                    <span class="text-left">{{$participantGroup->value}}</span>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-primary btn-sm editParticipantGroupMemberBtn" 
                                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}>Edit Members</button>
                                        <button type="button" class="btn btn-primary btn-sm editParticipantGroupBtn" 
                                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}>Edit</button>
                                        <button type="button" class="btn btn-primary btn-sm deleteParticipantGroupBtn" 
                                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}
                                            data-toggle="modal" data-target="#confirmDeleteParticipantGroup">Delete</button>
                                        
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
    
                <input type="text" class="form-control" name="text" id="participantGroupText" placeholder="Add Document Participant">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="participantGroupSaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="participantGroupCancelBtn">Cancel</button>
                </div>
            </div>                        
        </form>

        {{-- Popup Confirmation of Deletion --}}
        <div class="modal fade" id="confirmDeleteParticipantGroup" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteParticipantGroup" style="z-index: 1060;" aria-hidden="true">
            <div class="modal-dialog" role="participantGroup">
                <div class="modal-content">
                    <div class="modal-body" id="confirmDeleteParticipantGroupText">
                        Confirm deleting participantGroup: .{{$participantGroup->value}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmDeleteParticipantGroupBtn" data-id="">Yes</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteParticipantGroupBtn">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div> 

    {{-- ////////////////////////////////////////////////////////////////// --}}
    <p>Manage participants that belong in your selected participant group.</p>
    <div class="row align-items-center pl-2">
        {{-- Form for editing --}}
        <form id="updateParticipantGroupMembersForm" method="POST" autocomplete="off" class="flex-grow-1">
            @csrf
            @method('POST')
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="selectedParticipantGroupId" >
                
            <div class="input-group flex-grow-1">
                {{-- Dropdown List of Participants from Selected Group --}}
                {{-- ParticipantGroupGroup Dropdown Button --}}
    
                <button class="btn btn-secondary dropdown-toggle" type="button" id="participantGroupGroupListBtn" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    Group List
                </button>
                <div class="dropdown">
                    {{-- Participant Group List --}}
                    <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="participantGroupGroupList" id="groupList">
                    </div>
                </div>
            </div>
            
            <div class="input-group flex-grow-1">
                {{-- Dropdown List of Participants from Selected Group --}}
                {{-- ParticipantGroupGroup Dropdown Button --}}
    
                <button class="btn btn-secondary dropdown-toggle" type="button" id="participantGroupParticipantListBtn" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    Participant List
                </button>
                <div class="dropdown">
                    {{-- Participant Group List --}}
                    <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="participantGroupParticipantList" id="participantGroupParticipantList">
                    </div>
                </div>
            </div>

            <div class="input-group-append">
                <button type="submit" class="btn btn-primary"    id="participantGroupMembersSaveBtn">Save Changes</button>        
                <button type="button" class="btn btn-secondary"  id="participantGroupMembersCancelBtn">Cancel</button>
            </div>                       
        </form>
    </div> 

    {{-- ////////////////////////////////////////////////////////////////// --}}
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
    <div class="row align-items-center pl-2">
        {{-- Form for editing --}}
    
        <form id="updateCategoryForm" method="POST" autocomplete="off" class="flex-grow-1">
            @csrf
            @method('POST')
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="categoryId" >
                
            <div class="input-group flex-grow-1">
                <div class="input-group-prepend">
    
                {{-- Dropdown List of Categories --}}
                {{-- Category Dropdown Button --}}
    
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryList" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Categories List
                    </button>
                    <div class="dropdown">
                        {{-- Category List --}}
                        <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="categoryList">
                            @foreach ($categories as $category)
                                <div class="dropdown-item d-flex justify-content-between align-items-center category" style="max-width: 1000px !important;">
                                    {{-- Document Category --}}
                                    <span class="text-left">{{$category->value}}</span>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-primary btn-sm editCategoryBtn" 
                                            id={{$category->id}} data-id={{$category->id}} value={{$category->value}}>Edit</button>
                                        <button type="button" class="btn btn-primary btn-sm deleteCategoryBtn" 
                                            id={{$category->id}} data-id={{$category->id}} value={{$category->value}}
                                            data-toggle="modal" data-target="#confirmDeleteCategory">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
    
                <input type="text" class="form-control" name="text" id="categoryText" placeholder="Add Document Category">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="categorySaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="categoryCancelBtn">Cancel</button>
                </div>
            </div>                        
        </form>
    
        {{-- Popup Confirmation of Deletion --}}
        <div class="modal fade" id="confirmDeleteCategory" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteCategory" style="z-index: 1060;" aria-hidden="true">
            <div class="modal-dialog" role="category">
                <div class="modal-content">
                    <div class="modal-body" id="confirmDeleteCategoryText">
                        Confirm deleting category: .{{$category->value}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmDeleteCategoryBtn" data-id="">Yes</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteCategoryBtn">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    {{-- ////////////////////////////////////////////////////////////////// --}}
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

    {{-- ////////////////////////////////////////////////////////////////// --}}
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
        <button type="button" class="btn btn-secondary"  id="fileExtensionResetBtn">Reset to Default</button>
    </form>
</div>