<div>
    <h5>Document Settings</h5>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Sender and Recipients --}}
    <div class="container border p-3 rounded mb-5">
        <div class="row">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients</h6>
            </div>
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients of Selected Group</h6>
            </div>
        </div>
        
        <div class="row">
            <div class="col">
                <p>Add, remove or delete senders and recipients for the document tracking system.</p>
            </div>
            <div class="col">
                <p>Manage participants that belong in your selected participant group.</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="statusSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>

                {{-- Form for editing --}}
                <form id="updateParticipantForm" method="POST" autocomplete="off" style="display:none;">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="participantId" >
                </form>
                    
                {{-- Dropdown List of Categories --}}
                {{-- Participant List --}}
                <ul class="list-group p-0 mb-4" style="max-height: 150px; overflow-y: scroll;">
                    @foreach ($participants as $participant)
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            {{-- Document Participant --}}
                            <span class="text-left mr-auto">{{$participant->value}}</span>
                            {{-- Edit and Delete Buttons --}}
                            <div class="editParticipantBtn mr-2" 
                                id={{$participant->id}} data-id={{$participant->id}} value={{$participant->value}}><i class='bx bx-edit-alt' style="font-size: 20px;"></i></div>
                            <div class="deleteParticipantBtn" 
                                id={{$participant->id}} data-id={{$participant->id}} value={{$participant->value}}
                                data-toggle="modal" data-target="#confirmDeleteParticipant"><i class='bx bx-trash' style="font-size: 20px;"></i></button>
                        </li>
                    @endforeach
                </ul>
            
                {{-- <input type="text" class="form-control" name="text" id="participantText" placeholder="Add Document Participant">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="participantSaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="participantCancelBtn">Cancel</button>
                </div> --}}
            
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
            <div class="col">
                <div>
                    <div class="input-group mb-2">    
                        <input type="text" class="form-control" name="text" placeholder="Search Child Sender/Recipient Group in this Group">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id="statusSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                        </div>
                    </div>
    
                    {{-- Form for editing --}}
                    <form id="updateParticipantGroupMembersForm" method="POST" autocomplete="off" class="flex-grow-1">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="selectedParticipantGroupId" >
                    </form>
    
                    {{-- Dropdown List of Group from Selected Group --}}
                    <ul class="list-group p-0 mb-1" style="max-height: 150px; overflow-y: scroll;">
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            No child group in this group.
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-6">
                <p>You can manage the senders and recipient for the document tracking system using groups.</p>
            </div>
        </div>

        <div class="row">
            <div class="col">
                {{-- ////////////////////////////////////////////////////////////////// --}}
                {{-- GROUPS --}}
                {{-- Form for editing --}}
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient Groups">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="statusSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>

                <form id="updateParticipantGroupForm" method="POST" autocomplete="off" style="display:none;">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="participantGroupId" >
                </form>     

                {{-- Dropdown List of Participants --}}
                {{-- Participant Group List --}}
                <ul class="list-group p-0 mb-2" style="max-height: 150px; overflow-y: scroll;">
                    @foreach ($participantGroups as $participantGroup)
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        {{-- Document ParticipantGroup --}}
                        <span class="text-left mr-auto">{{$participantGroup->value}}</span>
                        {{-- Edit and Delete Buttons --}}
                        <div class="editParticipantGroupMemberBtn mr-2"
                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}><i class='bx bxs-user-detail' style="font-size: 20px;"></i></div>
                        <div class="editParticipantGroupBtn mr-2" 
                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}><i class='bx bx-edit-alt' style="font-size: 20px;"></i></div>
                        <div class="deleteParticipantGroupBtn" 
                            id={{$participantGroup->id}} data-id={{$participantGroup->id}} value={{$participantGroup->value}}
                            data-toggle="modal" data-target="#confirmDeleteParticipantGroup"><i class='bx bx-trash' style="font-size: 20px;"></i></div>
                    </li>
                    @endforeach
                    {{-- <input type="text" class="form-control" name="text" id="participantGroupText" placeholder="Add Document Participant">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"    id="participantGroupSaveBtn">Save Changes</button>        
                        <button type="button" class="btn btn-secondary"  id="participantGroupCancelBtn">Cancel</button>
                    </div> --}}
                </ul>

                
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
                
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient in this Group">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="statusSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>
                {{-- Dropdown List of Participants from Selected Group --}}
                <ul class="list-group p-0 mb-1" style="max-height: 150px; overflow-y: scroll;">
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        No participant in this group.
                    </li>
                </ul>

                {{-- <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="participantGroupMembersSaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="participantGroupMembersCancelBtn">Cancel</button>
                </div>                        --}}
            </div>
        </div>
    </div>
    

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Document Categories --}}
    <div class="container border p-3 rounded mb-5">
        <div class="row">
            <div class="col">
                {{-- Edit Document Status --}}
                <h6 class="p-0 font-weight-bold mb-0">Edit Document Type</h6>
                <p>Add, remove or delete types for the document tracking system.</p>

                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Document Type">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="statusSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>

                {{-- Form for editing status --}}
                <form id="updateStatusForm" method="POST" autocomplete="off" style="display:none;">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="statusId" >
                </form>

               {{-- Type List --}}
                <ul class="list-group p-0 mb-1" style="max-height: 150px; overflow-y: scroll;">
                @foreach ($types as $type)
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        {{-- Document Status --}}
                        <span class="text-left mr-auto">{{$type->value}}</span>
                        {{-- Edit and Delete Buttons --}}
                        <div class="editStatusBtn mr-2" 
                            id={{$type->id}} data-id={{$type->id}} value={{$type->value}}><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                        </div>
                        <div class="deleteStatusBtn" 
                            id={{$type->id}} data-id={{$type->id}} value={{$type->value}}
                            data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i></div>
                    </li>
                @endforeach
                </ul>

                        {{-- <input type="text" class="form-control" name="text" id="statusText" placeholder="Add Document Status">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary"    id="statusSaveBtn">Save Changes</button>        
                            <button type="button" class="btn btn-secondary"  id="statusCancelBtn">Cancel</button>
                        </div> --}}

                {{-- Popup Confirmation of Deletion --}}
                <div class="modal fade" id="confirmDeleteStatus" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteStatus" style="z-index: 1060;" aria-hidden="true">
                    <div class="modal-dialog" role="status">
                        <div class="modal-content">
                            <div class="modal-body" id="confirmDeleteStatusText">
                                Confirm deleting status: .{{$type->value}}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" id="confirmDeleteStatusBtn" data-id="">Yes</button>
                                <button type="button" class="btn btn-secondary" id="cancelDeleteStatusBtn">No</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit Document Categories</h6>
                <p>Add, remove or delete categories for the document tracking system.</p>
            
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

                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Document Category">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="categorySaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>

                {{-- <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" id="categoryText" placeholder="Add Document Category">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary"    id="categorySaveBtn">Save Changes</button>        
                        <button type="button" class="btn btn-secondary"  id="categoryCancelBtn">Cancel</button>
                    </div>
                </div> --}}
                
                {{-- Dropdown List of Categories --}}
                <ul class="list-group p-0 mb-1" style="max-height: 150px; overflow-y: scroll;">
                    @foreach ($categories as $category)
                        <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            {{-- Document Category --}}
                            <span class="text-left mr-auto">{{$category->value}}</span>
                            {{-- Edit and Delete Buttons --}}
                            <div class="editCategoryBtn categoryBtn mr-2" 
                                id={{$category->id}} data-id={{$category->id}} value={{$category->value}}><i class='bx bx-edit-alt' style="font-size: 20px;"></i></div>
                            <div class="deleteCategoryBtn categoryBtn" 
                                id={{$category->id}} data-id={{$category->id}} value={{$category->value}}
                                data-toggle="modal" data-target="#confirmDeleteCategory"><i class='bx bx-trash' style="font-size: 20px;"></i></div>
                        </li>
                    @endforeach
                </ul>

                {{-- Form for editing --}}
                <form id="updateCategoryForm" style="display:none;" method="POST" autocomplete="off">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="categoryId">
                    <input type="hidden" name="text" id="categoryValue">
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
        </div>
    </div>
    
    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit File Extensions --}}
    <div class="container border p-3 rounded mb-5">    
        <div class="row">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit File Extensions</h6>
                <p>Choose what files can be uploaded to the system.</p>

                <form id="updateFileExtensionForm" method="post" autocomplete="off">
                    @csrf
                    @method('POST')
                    <div class="row mb-2">
                        <div class="col">
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            {{-- <p>{{$fileExtensions}}</p> --}}
                            <table>
                                @for ($i = 0; $i < sizeof($fileExtensions); $i++)
                                    @if (($i % 3 == 0))
                                        <tr class="p-2">
                                    @endif
                                        <td class="pr-5">
                                            <input type="checkbox" class="editExtension" name="extensions[]" id="{{$fileExtensions[$i]->id}}" {{$fileExtensions[$i]->checked == 'true' ? 'checked' : ''}}>
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
</div>