<div>
    <h3 id="systemSettingsTitle">System Settings</h3>
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

    {{-- Dropdown List of Categories --}}
    {{-- Edit Document Type --}}
    <h5>Edit Document Type</h5>
    <p>NOTE: Edited or deleted type won't affect the status of the previous documents.</p>
    {{-- Dropdown List of Document Types --}}
    <div class="row align-items-center pl-2">
        {{-- Form for editing --}}
        <form id="updateTypeForm" method="POST" autocomplete="off" class="flex-grow-1">
            @csrf
            @method('POST')
            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
            <input type="hidden" name="id" id="typeId" >
            <div class="input-group flex-grow-1">
                <div class="input-group-prepend">
                {{-- Dropdown List of Document Types --}}
                {{-- Status Dropdown Button --}}
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="typeList" data-toggle="dropdown" aria-haspopup="true"
                        aria-expanded="false">
                        Types List
                    </button>
                    <div class="dropdown">
                        {{-- Type List --}}
                        <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="typeList">
                            @foreach ($types as $type)
                                <div class="dropdown-item d-flex justify-content-between align-items-center type" style="max-width: 1000px !important;">
                                    {{-- Document Type --}}
                                    <span class="text-left">{{$type->value}}</span>
                                    {{-- Edit and Delete Buttons --}}
                                    <div class="ml-auto">
                                        <button type="button" class="btn btn-primary btn-sm editTypeBtn" 
                                            id={{$type->id}} data-id={{$type->id}} value={{$type->value}}>Edit</button>
                                        <button type="button" class="btn btn-primary btn-sm deleteTypeBtn" 
                                            id={{$type->id}} data-id={{$type->id}} value={{$type->value}}
                                            data-toggle="modal" data-target="#confirmDeleteType">Delete</button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <input type="text" class="form-control" name="text" id="typeText" placeholder="Add Document Type">
                <div class="input-group-append">
                    <button type="submit" class="btn btn-primary"    id="typeSaveBtn">Save Changes</button>        
                    <button type="button" class="btn btn-secondary"  id="typeCancelBtn">Cancel</button>
                </div>
            </div>                        
                
        </form>
    
        {{-- Popup Confirmation of Deletion --}}
    
        <div class="modal fade" id="confirmDeleteType" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteType" style="z-index: 1060;" aria-hidden="true">
            <div class="modal-dialog" role="type">
                <div class="modal-content">
                    <div class="modal-body" id="confirmDeleteTypeText">
                        Confirm deleting type: .{{$type->value}}
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-primary" id="confirmDeleteTypeBtn" data-id="">Yes</button>
                        <button type="button" class="btn btn-secondary" id="cancelDeleteTypeBtn">No</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

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
</div>