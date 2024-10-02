{{-- Template for Document Type, Document Category --}}
{{-- Rename what is labelled --}}
{{-- Example if Document Type:
    from updateStatusForm to updateTypeForm

    Same step to be followed in topPanel.js, in renaming
 --}}
 
<div class="row align-items-center pl-2">
    {{-- Form for editing --}}
    {{-- rename updateStatusForm --}}
    <form id="updateStatusForm" method="POST" autocomplete="off" class="flex-grow-1">
        @csrf
        @method('POST')
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        {{-- rename statusId --}}
        <input type="hidden" name="id" id="statusId" >
            
        <div class="input-group flex-grow-1">
            <div class="input-group-prepend">
                {{-- Dropdown List of Document Status --}}
                {{-- Status Dropdown Button --}}
                {{-- Rename status list --}}
                <button class="btn btn-secondary dropdown-toggle" type="button" id="statusList" data-toggle="dropdown" aria-haspopup="true"
                    aria-expanded="false">
                    {{-- Rename Statuses List --}}
                    Statuses List
                </button>
                <div class="dropdown">
                    {{-- Status List --}}
                    <div class="dropdown-menu" style="max-height: 400px !important; overflow-y: auto;" aria-labelledby="statusList">
                        {{-- Rename Statuses and Status
                            Rename ng mga status na nasa foreach loop na to
                         --}}
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
            {{-- Rename Status Save Text --}}
            <input type="text" class="form-control" name="text" id="statusText" placeholder="Add Document Status">
            <div class="input-group-append">
                {{-- Rename Status Save Button --}}
                <button type="submit" class="btn btn-primary"    id="statusSaveBtn">Save Changes</button>        
                {{-- Rename Status Cancel Button --}}
                <button type="button" class="btn btn-secondary"  id="statusCancelBtn">Cancel</button>
            </div>
        </div>                        
            
    </form>

    {{-- Popup Confirmation of Deletion --}}
    {{-- Rename Confirm Delet Status --}}
    <div class="modal fade" id="confirmDeleteStatus" tabindex="-1" role="dialog" aria-labelledby="confirmDeleteStatus" style="z-index: 1060;" aria-hidden="true">
        {{-- Rename Status --}}
        <div class="modal-dialog" role="status">
            <div class="modal-content">
                {{-- Rename Confirm Delete Status Text --}}
                <div class="modal-body" id="confirmDeleteStatusText">
                    {{-- Rename Status --}}
                    Confirm deleting status: .{{$status->value}}
                </div>
                <div class="modal-footer">
                    {{-- Rename confirm delete status btn--}}
                    <button type="button" class="btn btn-primary" id="confirmDeleteStatusBtn" data-id="">Yes</button>
                    {{-- Rename cancel delete status btn--}}
                    <button type="button" class="btn btn-secondary" id="cancelDeleteStatusBtn">No</button>
                </div>
            </div>
        </div>
    </div>
</div>