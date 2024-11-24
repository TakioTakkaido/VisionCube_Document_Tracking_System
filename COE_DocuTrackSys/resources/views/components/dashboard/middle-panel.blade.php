<div class="middle-panel">
    {{-- Side Bar --}}
    <x-dashboard.side-bar />
    
    <div class="table-panel">
        {{-- Loading Screen --}}
        <div id="loadingHomepage" class="active container justify-content-center align-items-center" style="height: 100%; position: absolute; width: 100%; display: flex; overflow-x: hidden; overflow-y: hidden; z-index: 1000">
            <div class="spinner-border" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        
        {{-- About Page --}}
        <div class="about">
            <x-dashboard.top-panel.about />
        </div>

        <div class="mission">
            <x-dashboard.top-panel.mission />
        </div>

        <div class="vision">
            <x-dashboard.top-panel.vision />
        </div>

        <div class="contactUs">
            <x-dashboard.top-panel.contact-us />
        </div>
        
        {{-- Homepage --}}

        <div class="homepage">
            <x-dashboard.homepage />
        </div>
        {{-- Document Table --}}
        <div class="dashboard-table">
            <x-dashboard.table/>
        </div>

        {{-- Profile Settings --}}
        <div class="profile-settings">
            <x-dashboard.system-settings.profile />
        </div>

        {{-- Account Settings --}}
        <div class="account-settings">
            <x-dashboard.system-settings.account />
        </div>

        {{-- Document Settings --}}
        <div class="document-settings">
            <x-dashboard.system-settings.document />
        </div>

        {{-- Attachment Upload Settings --}}
        <div class="attachmentUpload-settings">
            <x-dashboard.system-settings.attachmentUpload />
        </div>

        {{-- Sys info upload Settings --}}
        <div class="sysInfo-settings">
            <x-dashboard.system-settings.sysInfo />
        </div>
    </div>
</div>