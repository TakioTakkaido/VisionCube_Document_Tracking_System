<div class="d-flex flex-column">
    <div class="flex-row">

        {{-- Upload Form --}}
        <x-dashboard.forms.upload />

        
        <div class="flex-column w-25 p-0">
            @if($isAdmin)
            <div class="documentStatistics container border rounded p-3 mb-2">
                <h6 class="p-0 font-weight-bold" style="font-size: 14px;">Document Statistics</h6>
                <ul class="list-group p-0" style="font-size: 14px;">
                    <li class="p-2 list-group-item d-flex justify-content-between">Incoming <span class="badge badge-secondary " id="incomingBadge"></span></li>
                    <li class="p-2 list-group-item d-flex justify-content-between">Outgoing <span class="badge badge-secondary" id="outgoingBadge"></span></li>
                    <li class="p-2 list-group-item d-flex justify-content-between">Archived <span class="badge badge-secondary" id="archivedBadge"></span></li>
                </ul>
            </div>
            @endif

            <div class="container border rounded p-3 mb-2 recentUpdates" style="text-align: center;">
                <h6 class="p-0 font-weight-bold" style="font-size: 14px; text-align:left;">Recent Updates</h6>
                No recent changes to the system
            </div>
            <button type="button" class="btn btn-primary w-100" id="addNewAccountBtn"><strong>Add New Account</strong></button>
        </div>

    </div>
</div>