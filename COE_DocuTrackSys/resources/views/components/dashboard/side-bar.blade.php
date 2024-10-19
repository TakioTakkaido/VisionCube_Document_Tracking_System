{{-- Side Panel --}}
<div class="side-panel">
    {{-- Upload Button
        Disabled for anyone not permitted to use it.
    --}}
    <button class="home-btn" id="homePageBtn">
        <i class='bx bx-home'></i>
        <span>Home</span>
    </button>
    
    {{-- Side Panel Buttons 
        -It would be different depending if the role is the admin or just a verified account
        Layout Admin, with number:
            Accounts - Total
                Verified
                Guest
                Deactivated
            Documents - Total of Active
                Incoming
                Outgoing
                Archived
                    Memoranda
                    Letters
                    Requisitions
            Logs
                
        
        Layout Verified User, with number:
            Incoming
            Outgoing
            Archived
                Letters
                Memoranda
                Requisitions
                
    --}}

    <div class="side-panel-section" id="dropdown-arrow">
        @if($isAdmin)
        {{-- Accounts --}}
        <a class="sidepanel-btn" id="accountBtn">
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
        <a class="sidepanel-btn" id="documentBtn">
            <span class="dropdown-arrow"><i class='bx bx-file'></i>
            </span> Documents
        </a>

        {{-- Documents Dropdown --}}
        <div class="dropdown-container" id="documentsDropdown">
            <a id="incomingBtn"><i class='bx bx-archive-in'></i>Incoming</a>
            <a id="outgoingBtn"><i class='bx bx-archive-out'></i>Outgoing</a>
            <a id="archivedBtn"><i class='bx bx-archive'></i>Archived</a>
        </div>

        {{-- Logs --}}
        @if($isAdmin)
        <a class="sidepanel-btn" id="logBtn">
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
            <a id="accountSettingsBtn"><i class='bx bx-user'></i>Account</a>
            @if($isAdmin)
                <a id="documentSettingsBtn"><i class='bx bx-file'></i>Document</a>
            @endif
        </div>

    </div>
</div>