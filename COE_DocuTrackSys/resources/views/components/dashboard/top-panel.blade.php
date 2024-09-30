<div class="top-panel">
    <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo" class="header-logo">
    <div class="header-text">Document Tracking System</div>
    <div class="profile">
        <div class="account-info">
            <img src="{{Vite::asset('resources/img/user-photo.jpg')}}" alt="User Photo" class="account-photo">
            <button type="button" class="edit-account btn btn-primary" data-toggle="modal" data-target="#editAccountForm">
                {{$user->name}}  •  {{$user->role}}
            </button>
        </div>
    </div>
</div>