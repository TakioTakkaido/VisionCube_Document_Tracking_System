<div>
    <h3 id="systemSettingsTitle">System Settings</h3>
    {{-- Account Settings --}}
    <h4>Account Settings</h4>
    {{-- Add Account --}}
    <h5>Add New Account</h5>
    <p>Add new account that shall be used by other</p>
    <p>users in the system.</p>
    <form id="addNewAccount" method="post" autocomplete="off">
        @csrf
        @method('POST')
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">

        <div class="form-group row">
            <input type="name" class="form-control" name="name" id="name" placeholder="Username">
            
            <input type="email" class="form-control" name="email" id="email" placeholder="Email">            
        </div>

        <div class="form-group row">
            <input type="password" class="form-control" name="password" id="password" placeholder="Password">

            <input type="password" class="form-control" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
        </div>

        <label for="role">Category:</label>
        <select id="role" name="role" placeholder="Select Account Role">
            <option value="" disabled selected>Select Account Role</option>
            {{-- Obtained document categories using Laravel--}}
            @foreach ($roles as $role)
                @if ($role->value !== 'Admin')
                    <option value="{{$role->value}}">{{$role->value}}</option>
                @endif
            @endforeach
        </select>

        <a name="submit" id="addAccountBtn" class="btn btn-primary" href="#" role="button">Create Account</a>        
        <a name="cancel" class="btn btn-secondary" href="#" role="button">Cancel</a>
    </form>

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
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canUploadSecretary" 
            {{$secretary[0] == true ? 'checked' : null}}>
            <label for="canUploadSecretary" class="form-check-label">Upload Document</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canEditSecretary"
            {{$secretary[1] == true ? 'checked' : null}}>
            <label for="canEditSecretary" class="form-check-label">Edit Document</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canMoveSecretary" 
            {{$secretary[2] == true ? 'checked' : null}}>
            <label for="canMoveSecretary" class="form-check-label">Move to Incoming/Outgoing</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canArchivedSecretary" 
            {{$secretary[3] == true ? 'checked' : null}}>
            <label for="canArchivedSecretary" class="form-check-label">Move to Archived</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canDownloadSecretary" 
            {{$secretary[4]  == true ? 'checked' : null}}>
            <label for="canDownloadSecretary" class="form-check-label">Download Document File</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editSecretaryRole" name="access" id="canPrintSecretary" 
            {{$secretary[5] == true ? 'checked' : null}}>
            <label for="canPrintSecretary" class="form-check-label">Print Document File</label>
        </div>

        <button type="submit" class="btn btn-primary"    id="secretarySaveBtn">Save Changes</button>        
        <button type="button" class="btn btn-secondary"  id="secretaryCancelBtn">Cancel</button>
    </form>

    {{-- Update Assistant Role --}}
    <form id="updateAssistantAccountRole" method="post" autocomplete="off">
        <h6>Assistant</h6>
        @csrf
        @method('POST')
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        {{-- <p>{{$fileExtensions}}</p> --}}
        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canUploadAssistant"
            {{$assistant[0] == true ? 'checked' : null}}>
            <label for="canUploadAssistant" class="form-check-label">Upload Document</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canEditAssistant"
            {{$assistant[1] == true ? 'checked' : null}}>
            <label for="canEditAssistant" class="form-check-label">Edit Document</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canMoveAssistant"
            {{$assistant[2] == true ? 'checked' : null}}>
            <label for="canMoveAssistant" class="form-check-label">Move to Incoming/Outgoing</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canArchivedAssistant"
            {{$assistant[3] == true ? 'checked' : null}}>
            <label for="canArchivedAssistant" class="form-check-label">Move to Archived</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canDownloadAssistant"
            {{$assistant[4] == true ? 'checked' : null}}>
            <label for="canDownloadAssistant" class="form-check-label">Download Document File</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editAssistantRole" name="access" id="canPrintAssistant"
            {{$assistant[5] == true ? 'checked' : null}}>
            <label for="canPrintAssistant" class="form-check-label">Print Document File</label>
        </div>
            
        <button type="submit" class="btn btn-primary"    id="assistantSaveBtn">Save Changes</button>        
        <button type="button" class="btn btn-secondary"  id="assistantCancelBtn">Cancel</button>
    </form>

    {{-- Update Clerk Role --}}
    <form id="updateClerkAccountRole" method="post" autocomplete="off">
        <h6>Clerk</h6>
        @csrf
        @method('POST')
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        {{-- <p>{{$fileExtensions}}</p> --}}
        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canUploadClerk"
            {{$clerk[0] == true ? 'checked' : null}}>
            <label for="canUploadClerk" class="form-check-label">Upload Document</label>
        </div>

        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canEditClerk"
            {{$clerk[1] == true ? 'checked' : null}}>
            <label for="canEditClerk" class="form-check-label">Edit Document</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canMoveClerk"
            {{$clerk[2] == true ? 'checked' : null}}>
            <label for="canMoveClerk" class="form-check-label">Move to Incoming/Outgoing</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canArchivedClerk"
            {{$clerk[3] == true ? 'checked' : null}}>
            <label for="canArchivedClerk" class="form-check-label">Move to Archived</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canDownloadClerk"
            {{$clerk[4] == true ? 'checked' : null}}>
            <label for="canDownloadClerk" class="form-check-label">Download Document File</label>
        </div>
            
        <div class="form-check">
            <input type="checkbox" class="form-check-input editClerkRole" name="access" id="canPrintClerk"
            {{$clerk[5] == true ? 'checked' : null}}>
            <label for="canPrintClerk" class="form-check-label">Print Document File</label>
        </div>
            
        <button type="submit" class="btn btn-primary"    id="clerkSaveBtn">Save Changes</button>        
        <button type="button" class="btn btn-secondary"  id="clerkCancelBtn">Cancel</button>
    </form>
</div>