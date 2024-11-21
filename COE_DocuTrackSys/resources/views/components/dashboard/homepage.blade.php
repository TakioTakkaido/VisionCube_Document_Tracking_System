<div class="container-fluid p-2">
    <div class="row">
        <div class="col mr-2 p-0 analyticsPanel">
            {{-- Analytics Table --}}
            <div id="reports">
                <x-dashboard.info.reports />
            </div>

            {{-- Upload Form --}}
            <div id="upload" data-upload="1">
                <x-dashboard.forms.upload />
            </div>
        </div>

        {{-- Homepage Buttons --}}
        <div class="col-3 p-0">
            {{-- Upload Document --}}
            <button type="button" class="btn btn-warning w-100 mb-2" id="uploadDocumentBtn" data-upload="0"><strong><i class='bx bx-upload'></i>  Upload New Document</strong></button>

            {{-- View Generated Reports --}}
            <div class="card mb-3">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold" style="font-size: 15px;">Generated Reports</h5>
                            <p class="card-text" style="font-size: 13px;">View all of your generated reports here.</p>
                        </div>
                    </div>
                    <div class="col-md-4 p-1 py-2">
                        <button type="button" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center" id="viewReportsBtn">
                            <i class='bx bxs-report' style="font-size: 3em;"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- View Archives --}}
            <div class="card mb-4">
                <div class="row no-gutters">
                    <div class="col-md-8">
                        <div class="card-body">
                            <h5 class="card-title font-weight-bold" style="font-size: 15px;">Archives</h5>
                            <p class="card-text" style="font-size: 13px;">View all of your archived documents here.</p>
                        </div>
                    </div>
                    <div class="col-md-4 p-1 py-2">
                        <button type="button" class="btn btn-primary w-100 h-100 d-flex align-items-center justify-content-center" id="viewArchivesBtn">
                            <i class='bx bx-archive' style="font-size: 3em;"></i>
                        </button>
                    </div>
                </div>
            </div>

            {{-- Recent Changes --}}
            <button type="button" class="recentUpdates mb-2 font-weight-bold btn btn-primary w-100" style="font-size: 14px; text-align: center;" id="showLatestMaintenanceLog">Maintenance Notes <span class="badge badge-warning" id="latestMaintenanceBadge">!</span></button>

            {{-- @if($isAdmin)
            <div class="documentStatistics container-fluid border rounded p-3 mb-2">
                <h6 class="p-0 font-weight-bold" style="font-size: 14px;">Document Statistics</h6>
                <ul class="list-group p-0" style="font-size: 14px;">
                    <li class="p-2 list-group-item d-flex justify-content-between">Incoming <span class="badge badge-primary" id="incomingBadge"></span></li>
                    <li class="p-2 list-group-item d-flex justify-content-between">Outgoing <span class="badge badge-primary" id="outgoingBadge"></span></li>
                </ul>
            </div>
            @endif --}}
            
            
        </div>

    </div>
</div>