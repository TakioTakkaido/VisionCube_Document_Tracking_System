<div>
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <!-- Title on the left -->
            <div class="col-auto">
                <h5>System Information Settings</h5>
            </div>
        
            <!-- Warning text and maintenance button on the right -->
            <div class="col-auto d-flex align-items-center">
                <span class="badge badge-warning text-wrap mr-2 text-justify" style="background-color: transparent; color: black;">
                    @if ($maintenance)
                        Warning: Ensure that all changes to the system<br> are executed before disabling the maintenance mode.
                    @else
                        Warning: Ensure that all accounts are logged<br> out before enabling maintenance mode.
                    @endif
                </span>
                {{-- maintenanceBtn --}}
                <button type="button" class="btn btn-primary" id="maintenanceAccountBtn" data-target="#confirmMaintenanceSysinfoModal" data-toggle="modal">
                    @if ($maintenance)
                        Maintenance Mode: On   
                    @else
                        Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit System Name</h6>
                <p>Proceed to change the system name with caution.</p>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Change System Logo</h6>
                <p>Minimum size requirement: | Minimum resolution: </p>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Change Favicon</h6>
                <p>Minimum size requirement: | Minimum resolution: </p>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit About</h6>
                <p>Minimum size requirement: | Minimum resolution: </p>
            </div>
        </div>
    </div>
        
    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit WMSU Mission</h6>
                <p>Minimum size requirement: | Minimum resolution: </p>
            </div>
        </div>
    </div>

    <div class="container border p-3 rounded mb-2 position-relative">
        @if ($maintenance != true)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>  
        @endif
        <div class="row mb-2 justify-content-end align-items-end">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0">Edit WMSU Vision</h6>
                <p>Minimum size requirement: | Minimum resolution: </p>
            </div>
        </div>
    </div>

    {{-- Confirm Maintenance Modal --}}
    <div class="modal fade" id="confirmMaintenanceSysinfoModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($maintenance)
                            Revert System Maintenance?
                        @else
                            Confirm System Maintenance?
                        @endif
                    </h5>
                </div>
                <div class="modal-body text-justify">
                    @if ($maintenance)
                        The system would disable maintenance, all system changes are going to be reflected in the system.
                    @else
                        The system shall undergo maintenance, make sure all users are logged out, and transaction are settled before undergoing to system to the maintenance.
                    @endif
                </div>
                <div class="modal-footer">
                    <button type="button" data-maintenance="{{ $maintenance ? 'true' : 'false' }}" class="btn btn-primary maintenanceBtn">
                        @if ($maintenance)
                            Revert Maintenance
                        @else
                            Proceed to Maintenance
                        @endif
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>