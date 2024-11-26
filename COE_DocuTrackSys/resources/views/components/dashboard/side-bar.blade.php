{{-- Side Panel --}}
<div class="side-panel container p-0">

    {{-- Home Button --}}
    <div class="row p-3">
        <button class="home-btn {{ ($maintenance || !$isVerified) ? 'disabled' : '' }}" id="homePageBtn">
            <i class='bx bx-home'></i>
            <span>Home</span>
        </button>
    </div>

    <div class="row list-group-flush side-panel-section">
            {{-- Accounts Section (Visible to Admins Only) --}}
            @if($isAdmin)
                <a class="list-group-item sidepanel-btn {{!$isVerified ? 'disabled' : ''}}" id="accountBtn">
                    <i class='bx bx-user'></i>
                    <span>Accounts</span>
                </a>
                {{-- Accounts Dropdown --}}
                <div class="dropdown-container" id="accountsDropdown">
                    <a class="list-group-item sidepanel-btn" id="activeBtn">
                        <i class='bx bx-user-check'></i>
                        <span>Active</span>
                        <span class="badge badge-primary newDocuments hide ml-3"></span>
                    </a>
                    <a class="list-group-item sidepanel-btn" id="deactivatedBtn">
                        <i class='bx bx-user-x'></i>
                        <span>Deactivated</span>
                        <span class="badge badge-primary newDocuments hide ml-3"></span>
                    </a>
                </div>
            @endif

            {{-- Documents Section --}}
            <a class="list-group-item sidepanel-btn {{( $maintenance || !$isVerified) ? 'disabled' : '' }}" id="documentBtn">
                <i class='bx bx-file'></i>
                <span>Documents</span>
                <span class="badge badge-primary newDocuments hide ml-3" id="totalNewUpdated">1</span>
            </a>
            
            {{-- Documents Dropdown --}}
            <div class="dropdown-container" id="documentsDropdown">
                <a class="list-group-item sidepanel-btn" id="incomingBtn" data-id="Incoming">
                    <i class='bx bx-archive-in'></i>
                    <span>Incoming</span>
                    <span class="badge badge-primary newDocuments hide ml-3" id="totalNewUpdatedIncoming">1</span>
                </a>
                <a class="list-group-item sidepanel-btn" id="outgoingBtn" data-id="Outgoing">
                    <i class='bx bx-archive-out'></i>
                    <span>Outgoing</span>
                    <span class="badge badge-primary newDocuments hide ml-3" id="totalNewUpdatedOutgoing">1</span>
                </a>
                <a class="list-group-item sidepanel-btn" id="recycleBinBtn">
                    <i class='bx bx-trash'></i>
                    <span>Trash</span>
                </a>
            </div>

            {{-- Archives (Visible to Admins Only) --}}
            @if($isAdmin)
                <a class="list-group-item sidepanel-btn {{ ( $maintenance || !$isVerified)  ? 'disabled' : '' }}" id="archivedBtn">
                    <i class='bx bx-archive'></i>
                    <span>Archives</span>
                </a>

                {{-- Reports --}}
                <a class="list-group-item sidepanel-btn {{ ($maintenance || !$isVerified) ? 'disabled' : '' }}" id="reportsBtn">
                    <i class='bx bxs-report'></i>
                    <span style="margin-right: 38px;">Reports</span>
                    <span class="badge badge-primary newDocuments hide ml-3" id="totalNewUploadedReport"></span>
                </a>

                {{-- Logs --}}
                <a class="list-group-item sidepanel-btn {{( $maintenance || !$isVerified) ? 'disabled' : '' }}" id="logBtn">
                    <i class='bx bx-history'></i>
                    <span>Logs</span>
                </a>
            @endif

            {{-- System Settings --}}
            <a class="list-group-item sidepanel-btn" id="systemSettingsBtn">
                <i class='bx bx-cog'></i>
                <span>Settings</span>
            </a>

            {{-- System Settings Dropdown --}}
            <div class="dropdown-container" id="systemSettingsDropdown">
                <a class="list-group-item sidepanel-btn" id="profileSettingsBtn">
                    <i class='bx bxs-user-circle'></i>
                    <span>Profile</span>
                </a>
                @if ($isAdmin)
                    <a class="list-group-item sidepanel-btn {{!$isVerified ? 'disabled' : ''}}" id="accountSettingsBtn">
                        <i class='bx bx-user'></i>
                        <span>Account</span>
                    </a>
                    <a class="list-group-item sidepanel-btn {{!$isVerified ? 'disabled' : ''}}" id="documentSettingsBtn">
                        <i class='bx bx-file'></i>
                        <span>Document Form</span>
                    </a>
                    <a class="list-group-item sidepanel-btn {{!$isVerified ? 'disabled' : ''}}" id="attachmentUploadBtn">
                        <i class='bx bx-cloud-upload'></i>
                        <span>Attachment Upload</span>
                    </a>
                    <a class="list-group-item sidepanel-btn {{!$isVerified ? 'disabled' : ''}}" id="sysInfoSettingsBtn">
                        <i class='bx bxs-info-circle' ></i>
                        <span>System Information</span>
                    </a>
                @endif
            </div>
    </div>
</div>
