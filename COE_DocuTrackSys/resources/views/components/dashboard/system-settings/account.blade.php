<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <!-- Title on the left -->
            <div class="col-auto">
                <h5>Account Settings</h5>
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
                <button type="button" class="btn btn-primary" id="maintenanceAccountBtn" data-target="#confirmMaintenanceAccountModal" data-toggle="modal">
                    @if ($maintenance)
                        Maintenance Mode: On   
                    @else
                        Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>

    {{-- Account Settings --}}
    <div class="container border p-3 rounded mb-5 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                {{-- Add Account --}}
                <h6 class="p-0 font-weight-bold mb-0">Add New Account  <small style="color: red; font-size: 11px;">All fields required.</small></h6>
                <p>Add new account that shall be used by other users in the system.</p>
                <form id="addNewAccount" method="post" autocomplete="off" aria-autocomplete="none">
                    <div class="row mb-2">
                        <div class="col">
                            <input class="addAccountInput mb-2" type="name" name="name" id="name" placeholder="Username">
                            <span class="error" id="accountNameError" style="display:none;"></span>
                        </div>
                        <div class="col">
                            <input class="addAccountInput mb-2" type="password" name="password" id="password" placeholder="Password">
                            <span class="error" id="accountPasswordError" style="display:none;"></span>
                        </div>
                    </div>
            
                    <div class="row mb-2">
                        <div class="col">
                            <input class="addAccountInput mb-2" type="email" name="email" id="email" placeholder="Email">
                            <span class="error" id="accountEmailError" style="display:none;"></span>
                        </div>
                        <div class="col">
                            <input class="addAccountInput mb-2" type="password" name="password_confirmation" id="password_confirmation" placeholder="Confirm Password" required>
                            <span class="error" id="accountConfirmPasswordError" style="display:none;"></span>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col">
                            <label for="role">Role:</label>
                            <select class="addAccountInput" id="role" name="role" placeholder="Select Account Role">
                                <option value="">Select Account Role</option>
                                {{-- Obtained document categories using Laravel--}}
                                @foreach ($roles as $role)
                                    @if ($role->value !== 'Admin')
                                        <option value="{{$role->value}}">{{$role->value}}</option>
                                    @endif
                                @endforeach
                            </select>
                            <span class="error" id="accountRoleError" style="display:none;"></span>
                        </div>
                        <div class="col">
                            <a name="submit" id="addAccountBtn" class="btn btn-primary createAccountBtn" href="#" role="button">Create Account</a>        
                            <a name="cancel" id="cancelAccountBtn" class="btn btn-secondary createAccountBtn" href="#" role="button">Cancel</a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded w-100 position-relative">  
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
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

                    <button type="submit" class="btn btn-primary accountAccessBtn"    id="saveAccountAccessBtn">Save Changes</button>       
                    <button type="button" class="btn btn-warning accountAccessBtn"  id="defaultAccountAccessBtn">Reset to Default</button>
                </form>
            </div>
        </div>
    </div>
    
    {{-- Confirm Maintenance Modal --}}
    <div class="modal fade" id="confirmMaintenanceAccountModal" tabindex="-1" role="dialog" aria-hidden="true">
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