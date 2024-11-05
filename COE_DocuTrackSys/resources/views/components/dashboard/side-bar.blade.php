{{-- Side Panel --}}
<div class="side-panel">
    {{-- Upload Button
        Disabled for anyone not permitted to use it.
    --}}
    <button class="{{$maintenance == true ? 'home-btn disabled' : 'home-btn'}}" id="homePageBtn" >
        <i class='bx bx-home'></i>
        <span>Home</span>
    </button>

    <div class="side-panel-section" id="dropdown-arrow">
        @if($isAdmin)
        {{-- Accounts --}}
        <a class="sidepanel-btn" id="accountBtn" >
            <span class="dropdown-arrow"><i class='bx bx-user'></i>
            </span> Accounts
        </a>
        {{-- Accounts Dropdown --}}
        <div class="dropdown-container" id="accountsDropdown">
            <a id="activeBtn" href="#"><i class='bx bx-user-check'></i>Active</a>
            <a id="deactivatedBtn" href="#"><i class='bx bx-user-x'></i>Deactivated</a>
        </div>
        @endif
        
        {{-- Documents --}}
        <a class="{{$maintenance == true ? 'sidepanel-btn disabled' : 'sidepanel-btn'}}" id="documentBtn" >
            <span class="dropdown-arrow"><i class='bx bx-file'></i>
            </span> Documents
        </a>

        {{-- Documents Dropdown --}}
        <div class="dropdown-container" id="documentsDropdown">
            <a id="incomingBtn" data-id="Incoming"><i class='bx bx-archive-in'></i>Incoming</a>
            <a id="outgoingBtn" data-id="Outgoing"><i class='bx bx-archive-out'></i>Outgoing</a>
            {{-- Recycle Bin --}}
            @if($isAdmin)
            <a id="recycleBinBtn">
                <span class="dropdown-arrow"><i class='bx bx-trash'></i>
                </span> Trash
            </a>
        </div>

        {{-- Archives --}}
        <a class="{{$maintenance == true ? 'sidepanel-btn disabled' : 'sidepanel-btn'}}" id="archivedBtn">
            <span class="dropdown-arrow" data-id="Archived"><i class='bx bx-archive'></i>
            </span> Archives
        </a>

        {{-- Reports --}}
        <a class="{{$maintenance == true ? 'sidepanel-btn disabled' : 'sidepanel-btn'}}" id="recycleBinBtn">
            <span class="dropdown-arrow"><i class='bx bxs-report'></i>
            </span> Reports
        </a>

        {{-- Logs --}}
        <a class="{{$maintenance == true ? 'sidepanel-btn disabled' : 'sidepanel-btn'}}" id="logBtn">
            <span class="dropdown-arrow"><i class='bx bx-history'></i>
            </span> Logs
        </a>
        @endif

        {{-- System Settings --}}
        <a class="sidepanel-btn" id="systemSettingsBtn">
            <span class="dropdown-arrow"><i class='bx bx-cog'></i>
            </span> Settings
        </a>

        {{-- System Settings Dropdown --}}
        <div class="dropdown-container" id="systemSettingsDropdown">
            <a id="profileSettingsBtn"><i class='bx bxs-user-circle'></i>Profile</a>        
            @if($isAdmin)
                <a id="accountSettingsBtn"><i class='bx bx-user'></i>Account</a>
                <a id="documentSettingsBtn"><i class='bx bx-file'></i>Document</a>
            @endif
        </div>

    </div>
</div>