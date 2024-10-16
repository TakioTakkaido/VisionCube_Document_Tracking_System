<div class="top-panel">
    <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo" class="header-logo">
    <div class="header-text">Document Tracking System</div>
    <div class="profile">
        <div class="account-info" style="color: white;">
            <strong>{{$user->name}}  •  {{$user->role}}</strong>
            
            {{-- Logout Button --}}
            <button type="button" class="btn btn-warning" id="logoutBtn">Logout</button>
        </div>
    </div>
</div>