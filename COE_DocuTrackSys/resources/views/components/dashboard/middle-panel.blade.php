<div class="middle-panel">
    {{-- Side Bar --}}
    <x-dashboard.side-bar />
    
    <div class="table-panel">
        {{-- Document Table --}}
        <div class="dashboard-table">
            <x-dashboard.table/>
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