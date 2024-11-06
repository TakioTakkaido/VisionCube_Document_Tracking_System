<div class="middle-panel">
    {{-- Side Bar --}}
    <x-dashboard.side-bar />
    
    <div class="table-panel">
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
    </div>
</div>