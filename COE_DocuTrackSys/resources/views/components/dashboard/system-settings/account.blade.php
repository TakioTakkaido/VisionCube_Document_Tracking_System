<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-auto text-left">
                <h5>Account Settings</h5>
            </div>
            <div class="col-auto text-right">
                <button type="button" class="btn btn-primary maintenanceBtn" data-maintenance="{{ $maintenance ? 'true' : 'false' }}">
                    @if ($maintenance == true)Maintenance Mode: On   
                    @else Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Account Settings --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay"></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                {{-- Add Account --}}
                <h6 class="p-0 font-weight-bold mb-0">Add New Account</h6>
                <p>Add new account that shall be used by other users in the system.</p>
                <form id="addNewAccount" method="post" autocomplete="off">
                    <div class="row mb-2">
                        <div class="col">
                            <input type="name" name="name" id="name" placeholder="Username">
                        </div>
                        <div class="col">
                            <input type="password" name="password" id="password" placeholder="Password">
                        </div>
                    </div>
            
                    <div class="row mb-2">
                        <div class="col">
                            <input type="email" name="email" id="email" placeholder="Email">
                        </div>
                        <div class="col">
                            <input type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <label for="role">Category:</label>
                            <select id="role" name="role" placeholder="Select Account Role">
                                <option value="">Select Account Role</option>
                                {{-- Obtained document categories using Laravel--}}
                                @foreach ($roles as $role)
                                    @if ($role->value !== 'Admin')
                                        <option value="{{$role->value}}">{{$role->value}}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="col">
                            <a name="submit" id="addAccountBtn" class="btn btn-primary" href="#" role="button">Create Account</a>        
                            <a name="cancel" class="btn btn-secondary" href="#" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded w-100 position-relative">  
        @if ($maintenance != true)
            <div class="overlay"></div>  
        @endif
        <div class="row">
            <div class="col">
                {{-- Change Access of Account Roles --}}
                <h6 class="p-0 font-weight-bold mb-0">Change Access of Account Roles</h6>
                <p>Change what each type of account can perform in the system.</p>
            
                {{-- Show different roles --}}
                {{-- Update Secretary Role --}}
                <form id="updateAccountAccess" method="post" autocomplete="off">
                    @csrf
                    @method('POST')
                    <table class="accountAccessTable table cell-border table-bordered hover pt-1">
                        <thead>
                            <tr>
                                <th>Role</th>
                                <th>Can Upload Document</th>
                                <th>Can Edit Document</th>
                                <th>Can Move Document</th>
                                <th>Can Archive Document</th>
                                <th>Can Download Document File</th>
                                <th>Can Print Document File</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <th>Secretary</th>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canUploadSecretary" 
                                    {{$secretary[0] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canEditSecretary"
                                    {{$secretary[1] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canMoveSecretary" 
                                    {{$secretary[2] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canArchivedSecretary" 
                                    {{$secretary[3] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canDownloadSecretary" 
                                    {{$secretary[4]  == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editSecretaryRole" name="access" id="canPrintSecretary" 
                                    {{$secretary[5] == true ? 'checked' : null}}></td>
                            </tr>
                            <tr>
                                <th>Assistant</th>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canUploadAssistant"
                                    {{$assistant[0] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canEditAssistant"
                                    {{$assistant[1] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canMoveAssistant"
                                    {{$assistant[2] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canArchivedAssistant"
                                    {{$assistant[3] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canDownloadAssistant"
                                    {{$assistant[4] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editAssistantRole" name="access" id="canPrintAssistant"
                                    {{$assistant[5] == true ? 'checked' : null}}></td>
                            </tr>
                            <tr>
                                <th>Clerk</th>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canUploadClerk"
                                    {{$clerk[0] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canEditClerk"
                                    {{$clerk[1] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canMoveClerk"
                                    {{$clerk[2] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canArchivedClerk"
                                    {{$clerk[3] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canDownloadClerk"
                                    {{$clerk[4] == true ? 'checked' : null}}></td>
                                <td><input type="checkbox" class="editClerkRole" name="access" id="canPrintClerk"
                                    {{$clerk[5] == true ? 'checked' : null}}></td>
                            </tr>
                        </tbody>
                    </table>

                    <button type="submit" class="btn btn-primary"    id="saveAccountAccessBtn">Save Changes</button>       
                    <button type="button" class="btn btn-warning"  id="defaultAccountAccessBtn">Reset to Default</button>
                    <button type="cancel" class="btn btn-secondary"  id="accountAccessCancelBtn">Cancel</button> 
                </form>
            </div>
        </div>
    </div>
</div>