<div>
    <h5>Document Settings</h5>

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Sender and Recipients --}}
    {{-- participant --}}
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

        <!-- 3rd row -->
        <div class="row"> 
            <!-- column 1  -->
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" placeholder="Add New Sender/Recipient">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="addParticipantBtn">Add</button>
                            </div>
                        </div>
                    </div>
                
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="searchParticipantBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>
                   


            <!--  column 2  -->
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Child Sender/Recipient Group in this Group">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary" id="participantSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>          
            </div>
        </div>

        <!-- 4th row -->
        <div class="row">
            <div class="col">
                {{-- Form for editing --}}
                    <form id="updateParticipantForm" method="POST" autocomplete="off" style="display:none;">
                             @csrf
                            @method('POST')
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type="hidden" name="id" id="participantId" >
                    </form>
                                
                {{-- Dropdown List of Categories --}}
                {{-- Participant List --}}
                    <ul class="list-group p-0 mb-4" style="max-height: 250px; overflow-y: scroll;">
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
                {{-- Form for editing --}}
                <form id="updateParticipantMembersForm" method="POST" autocomplete="off" class="flex-grow-1">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="selectedParticipantId" >
                </form>
        
                {{-- Dropdown List of Group from Selected Group --}}
                <ul class="list-group p-0 mb-1" style="max-height: 250px; overflow-y: scroll;">
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        No child group in this group.
                    </li>
                </ul>
            </div>
        </div>
      

        
        {{-- participantGroup--}}
        <!-- 5th row -->
        <div class="row">
            <div class="col-6">
                <p>You can manage the senders and recipient for the document tracking system using groups.</p>
            </div>
        </div>

        <!-- 6th row -->
        <div class="row">
            <!-- column 1 -->
            <div class="col">
                <div class="row">
                    <div class="col">
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" placeholder="Add New Sender/Recipient Groups">
                            <div class="input-group-append">
                                <button class="btn btn-primary" id="addParticipantGroupBtn">Add</button>
                            </div>
                        </div>
                    </div>


                    <div class="col">
                        {{-- ////////////////////////////////////////////////////////////////// --}}
                        {{-- GROUPS --}}
                        {{-- Form for editing --}}
                        <div class="input-group mb-2">    
                            <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient Groups">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary" id="participantGroupSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                            </div>
                        </div>
                    </div>               
                </div>
            </div>

           
            <!-- column 2 -->   
            <div class="col">
                <div class="input-group mb-2">    
                    <input type="text" class="form-control" name="text" placeholder="Search Sender/Recipient in this Group">
                    <div class="input-group-append">
                            <button type="submit" class="btn btn-primary" id="participantGroupSaveBtn"><i class='bx bx-search' style="text-align: center;"></i></button>
                    </div>
                </div>
            </div>
        </div>


        <!-- 7th row -->
        <div class="row">
            <div class="col">
                <form id="updateParticipantGroupForm" method="POST" autocomplete="off" style="display:none;">
                    @csrf
                    @method('POST')
                    <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                    <input type="hidden" name="id" id="participantGroupId" >
                </form>     

                {{-- Dropdown List of Participants --}}
                {{-- Participant Group List --}}
                <ul class="list-group p-0 mb-2" style="max-height: 250px; overflow-y: scroll;">
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
                {{-- Dropdown List of Participants from Selected Group --}}
                <ul class="list-group p-0 mb-1" style="max-height: 250px; overflow-y: scroll;">
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                        No participant in this group.
                    </li>
                </ul>

                {{--<div class="input-group-append">
                        <button type="submit" class="btn btn-primary"    id="participantGroupMembersSaveBtn">Save Changes</button>        
                        <button type="button" class="btn btn-secondary"  id="participantGroupMembersCancelBtn">Cancel</button>
                    </div>                        --}}
                </div>
            </div>
        </div>
    

    {{-- ////////////////////////////////////////////////////////////////// --}}
    {{-- Edit Document Types and Categories --}}
    <!-- EDIT DOCUMENT TYPE -->
    <div class="container border p-3 rounded mb-5">
        <div class="row">
            <div class="col typeSettings">
                {{-- Edit Document Type --}}
                <h6 class="p-0 font-weight-bold mb-0">Edit Document Type</h6>
                <p>Add, remove or delete types for the document tracking system.</p>

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
                
                <div class="row">
                    <div class="col">
                        {{-- Type List --}}
                        <ul class="list-group p-0 mb-1 systemTypeList" style="max-height: 250px; overflow-y: scroll;">
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
                    </div>
                </div>

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



    <!-- EDIT DOCUMENT STATUS -->
    <div class="col statusSettings">
        <h6 class="p-0 font-weight-bold mb-0">Edit Document Status</h6>
        <p>Add, remove or delete statuses for the document tracking system.</p>
            
                <div class="row mb-2">
                    <div class="col statusSettings"> 
                        <div class="input-group">    
                            <input type="text" class="form-control" name="text" id="addStatusText" placeholder="Add New Document Status">
                            <div class="input-group-append">
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

                <div class="row">
                    <div class="col">
                        {{-- Dropdown List of Categories --}}
                        <ul class="list-group p-0 mb-1" style="max-height: 250px; overflow-y: scroll;">
                        @foreach ($statuses as $status)
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                            {{-- Document Status --}}
                            <span class="text-left mr-auto">{{$status->value}}</span>
                            {{-- Edit and Delete Buttons --}}
                                <div class="editStatusBtn statusBtn mr-2" 
                                    id={{$status->id}} data-id={{$status->id}} value={{$status->value}}><i class='bx bx-edit-alt' style="font-size: 20px;"></i></div>
                                <div class="deleteStatusBtn statusBtn" 
                                    id={{$status->id}} data-id={{$status->id}} value={{$status->value}}
                                    data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i></div>
                            </li>
                        @endforeach
                        </ul>

                        {{-- Form for editing --}}
                        <form id="updateStatusForm" style="display:none;" method="POST" autocomplete="off">
                            @csrf
                            @method('POST')
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type="hidden" name="id" id="statusId">
                            <input type="hidden" name="text" id="statusValue">
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