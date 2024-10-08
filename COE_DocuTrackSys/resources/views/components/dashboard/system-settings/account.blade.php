<div>
    <h3 id="systemSettingsTitle">System Settings</h3>
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
</div>