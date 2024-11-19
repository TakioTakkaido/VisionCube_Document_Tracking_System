<div class="top-panel px-3">
    <div class="d-flex align-items-center">
        <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo" class="header-logo mr-2">
        <div class="align-items-center header-text">Document Tracking System</div>
    </div>
    <nav class="nav justify-content-center align-items-center" id="topPanelNav">
      <a class="nav-link" id="about">About</a>
      <a class="nav-link" id="mission">WMSU Mission</a>
      <a class="nav-link" id="vision">WMSU Vision</a>
      <a class="nav-link" id="contact">Contact Us</a>
    </nav>

    <div class="form-group m-0" id="searchBar">
        <div class="input-group">
            <input type="text" class="form-control" id="searchBarText" aria-describedby="helpId" placeholder="Search Documents...">
            <div class="input-group-append">
                <div class="input-group-text"><i class='bx bx-search' style="font-size: 15px;"></i></div>
            </div>
        </div>
    </div>
        
    <div class="account-info d-flex" style="color: white;">
        <strong id="topPanelName">{{$user->name}}  •  {{$user->role}}</strong>
        
        {{-- Logout Button --}}
        <button type="button" class="btn btn-warning" data-toggle="modal" data-target="#confirmLogout">Logout</button>
    </div>
    
    <!-- Modal -->
    <div class="modal fade" id="confirmLogout" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Confirm Logout?</h5>
                </div>
                <div class="modal-body">
                    Are you sure you want to logout?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-warning" id="logoutBtn">Logout</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>