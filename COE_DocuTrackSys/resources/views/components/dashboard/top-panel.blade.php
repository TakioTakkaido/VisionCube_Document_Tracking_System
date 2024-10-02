<div class="top-panel">
    <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo" class="header-logo">
    <div class="header-text">Document Tracking System</div>
    <div class="profile">
        <div class="account-info">
            {{-- Account Picture --}}
            <img src="{{Vite::asset('resources/img/user-photo.jpg')}}" alt="User Photo" class="account-photo">

            {{-- Account Dropdown Button --}}
            <button type="button" class="edit-account btn btn-primary" id="accountMenuDropdownBtn" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" data-target="#editAccountForm">
                {{$user->name}}  •  {{$user->role}}
            </button>

            {{-- Account Dropdown --}}  
            <div class="dropdown-menu" aria-labelledby="accountMenuDropdownBtn">
                {{-- For Admin only --}}
                <a class="dropdown-item" id="systemSettingsBtn" role="button" id="viewAccountBtn" data-toggle="modal" data-target="#systemSettings" href="#">System Settings</a>

                {{-- For Users --}}
                <a class="dropdown-item" href="#">View Account</a>
                <a class="dropdown-item" id="logoutBtn"         href="#">Logout</a>
            </div>
        </div>
    </div>
</div>