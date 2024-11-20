<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-auto text-left">
                <h5>Profile Settings</h5>
            </div>
        </div>
    </div>

    {{-- Display Profile --}}
    <div class="container border p-3 rounded mb-3 position-relative">
        <div class="row mb-0">
            <div class="col mb-0">
                <h3 class="mr-auto mb-0" id="displayProfileName">{{Auth::user()->name}}</h3>
            </div>
        </div>
        <div class="row">
            <div class="col">
                <span class="mr-auto small" id="displayProfileEmail">{{Auth::user()->email. ' • ' .Auth::user()->role}}</span>
            </div>
        </div>
    </div>

    {{-- Edit Profile --}}
    <div class="container border p-3 rounded mb-2 position-relative">
        {{-- Change Name --}}
        <div class="row mb-2">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Username</h6>
                <p>Username can only be changed once every 60 days.</p>
                <div class="row align-items-center">
                    <div class="col-6 text-left">
                        <div class="input-group">
                            <input type="text" class="form-control" id="editProfileName" value="{{ Auth::user()->name }}" disabled/>
                            <div class="input-group-append profileNameBtn">
                                <button class="btn btn-primary editProfile {{!$isVerified ? 'disabled' : ''}}" id="editNameBtn" data-value="{{ Auth::user()->name }}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i></button>
                            </div>                            
                        </div>
                        <span class="error" id="profileNameError" style="display:none;"></span>
                    </div>
                    
                    <div class="col-6 d-flex justify-content-end">
                        <!-- Save Button -->
                        <button type="button" class="btn btn-primary disabled editProfile" id="saveNameBtn">Change Username</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        {{-- Change Email --}}
        <div class="row mb-2">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Email</h6>
                <p>Please verify the new email address via the confirmation link before updating it.</p>
                <div class="row align-items-center d-flex justify-content-between">
                    <div class="col-6 text-left">
                        <div class="input-group">
                            <input type="text" class="form-control text-left" id="editProfileEmail" value="{{ Auth::user()->email }}" disabled/>
                            <div class="input-group-append profileEmailBtn">
                                <button class="btn btn-primary editProfile {{!$isVerified ? 'disabled' : ''}}" id="editEmailBtn"><i class='bx bx-edit-alt' style="font-size: 20px;"></i></button>
                            </div>
                        </div>
                    </div>
                    
                    @if (!Auth::user()->isVerified())
                        <span class="badge badge-warning text-wrap mr-2 text-justify" style="background-color: transparent; color: black;">
                            Email not verified. Verify email before using the system.
                        </span>
                    @endif

                    <div class="col-auto d-flex">
                        {{-- Save Button --}}
                        <button type="button" class="btn btn-primary disabled editProfile" id="saveEmailBtn">Change Email</button>

                        <!-- Verify Button -->
                        <button type="button" class="{{!Auth::user()->isVerified() ? "ml-2 btn btn-warning editProfile" : "ml-2 btn btn-warning editProfile disabled"}}" id="verifyEmailBtn">{{!Auth::user()->isVerified() ? 'Verify' : 'Verified!'}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        {{-- Change Password --}}
        <div class="row d-flex justify-content-between align-items-center">
            <div class="col-6 text-left">
                <h6 class="font-weight-bold mb-0">Password</h6>
                <p>To update your password, please press the "Reset Password" button and follow the instructions.</p>
            </div>
            
            <div class="col-6 text-right">
                <!-- Reset Password Button -->
                <a href="{{route('show.forgotPassword')}}" class="btn btn-primary {{!$isVerified ? 'disabled' : ''}}" id="resetPasswordBtn">Reset Password</a>
            </div>
        </div>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="verifyFirst" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Account not Verified</h5>
                </div>
                <div class="modal-body">
                    In order to proceed using the COE Document Tracking System, you must verify your email first.
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>