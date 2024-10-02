{{-- Side Panel --}}
<div class="side-panel">
    {{-- Upload Button
        Disabled for anyone not permitted to use it.
    --}}
    <button class="upload-btn" id="uploadBtn" data-toggle="modal" data-target="#uploadModal">
        <i class='bx bx-upload bx-flashing'></i>
        <span>Upload</span>
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
        {{-- Accounts --}}
        <a class="sidepanel-btn" id="accountBtn">
            <span class="dropdown-arrow"><i class='bx bx-paper-plane'></i>
            </span> Accounts
        </a>
        {{-- Accounts Dropdown --}}
        <div class="dropdown-container" id="accountsDropdown">
            <a id="deactivatedBtn" href="#"><i class='bx bx-notepad'></i>Deactivated</a>
        </div>

        {{-- Documents --}}
        <a class="sidepanel-btn" id="documentBtn">
            <span class="dropdown-arrow"><i class='bx bx-archive-in' ></i>
            </span> Documents
        </a>

        {{-- Documents Dropdown --}}
        <div class="dropdown-container" id="documentsDropdown">
            <a id="incomingBtn"><i class='bx bx-envelope'></i>Incoming</a>
            <a id="outgoingBtn"><i class='bx bx-envelope'></i>Outgoing</a>
            <a id="archivedBtn"><i class='bx bx-archive-in' ></i>Archived</a>

            {{-- Archived Dropdown --}}
            <div class="dropdown-container" id="archivedDropdown">
                <a id="archivedLetterBtn"       href="#"><i class='bx bx-notepad'></i>Letters</a>
                <a id="archivedRequisitionsBtn" href="#"><i class='bx bx-notepad'></i>Requistion</a>
                <a id="archivedMemorandaBtn"    href="#"><i class='bx bx-notepad'></i>Memoranda</a>
            </div>
        </div>

        {{-- Logs --}}
        <a class="sidepanel-btn" id="archived-button">
            <span class="dropdown-arrow"><i class='bx bx-archive-in' ></i>
            </span> Logs
        </a>

    </div>
</div>