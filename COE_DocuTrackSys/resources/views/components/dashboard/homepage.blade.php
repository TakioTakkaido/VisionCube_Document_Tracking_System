<div class="container-fluid p-2">
    <div class="row">
        {{-- Upload Form --}}
        <div class="col mr-2 p-0" id="upload">
            <x-dashboard.forms.upload />
        </div>

        
        <div class="col-3 p-0">
            @if($isAdmin)
            <div class="documentStatistics container-fluid border rounded p-3 mb-2">
                <h6 class="p-0 font-weight-bold" style="font-size: 14px;">Document Statistics</h6>
                <ul class="list-group p-0" style="font-size: 14px;">
                    <li class="p-2 list-group-item d-flex justify-content-between">Incoming <span class="badge badge-secondary " id="incomingBadge"></span></li>
                    <li class="p-2 list-group-item d-flex justify-content-between">Outgoing <span class="badge badge-secondary" id="outgoingBadge"></span></li>
                </ul>
            </div>
            @endif

            <div class="container-fluid border rounded p-3 mb-2 recentUpdates" style="text-align: center;">
                <h6 class="p-0 font-weight-bold" style="font-size: 14px; text-align:left;">Recent Updates</h6>
                No recent changes to the system
            </div>
            <button type="button" class="btn btn-primary w-100" id="addNewAccountBtn"><strong>Add New Account</strong></button>
        </div>

    </div>
</div>