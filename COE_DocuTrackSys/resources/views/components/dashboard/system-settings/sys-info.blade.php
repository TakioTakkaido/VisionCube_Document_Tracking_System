<div>
    <!-- Header Section -->
    <div class="container p-0 mb-2">
        <div class="row d-flex justify-content-between align-items-center">
            <!-- Title -->
            <div class="col-auto">
                <h5>System Information Settings</h5>
            </div>
        
            <!-- Maintenance Warning and Button -->
            <div class="col-auto d-flex align-items-center">
                <span class="badge badge-warning text-wrap mr-2 text-justify" style="background-color: transparent; color: black;">
                    @if ($maintenance)
                        Warning: Ensure that all changes to the system<br> are executed before disabling the maintenance mode.
                    @else
                        Warning: Ensure that all accounts are logged<br> out before enabling maintenance mode.
                    @endif
                </span>
                {{-- maintenanceBtn --}}
                <button type="button" class="btn btn-primary" id="maintenanceSysInfoBtn" data-target="#confirmMaintenanceSysinfoModal" data-toggle="modal">
                    @if ($maintenance)
                        Maintenance Mode: On   
                    @else
                        Maintenance Mode: Off
                    @endif
                </button>
            </div>
        </div>
    </div>

    <!-- System Name Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <div class="row mb-2">
            <div class="col-12">
                <h6 class="font-weight-bold">Edit System Name</h6>
            </div>
            <div class="col-md-6">
                <div class="input-group">
                    <input type="text" class="form-control" id="systemName" value="{{ $info->name }}" placeholder="Enter the System Name" disabled required>
                    <div class="input-group-append systemNameBtn">
                        <button class="btn btn-primary editSysInfo {{ !$isVerified ? 'disabled' : '' }}" id="editSysInfoNameBtn" data-value="{{ $info->name }}">
                            <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                        </button>
                    </div>
                </div>
            </div>
            <div class="col-md-6 text-right">
                <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoNameBtn">Change System Name</button>
            </div>
        </div>
    </div>

    <!-- Logo Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <h6 class="font-weight-bold">Change System Logo</h6>
        <p>Minimum size requirement: | Minimum resolution:</p>
    </div>

    <!-- Favicon Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <h6 class="font-weight-bold">Change Favicon</h6>
        <p>Minimum size requirement: | Minimum resolution:</p>
    </div>

    <!-- About Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <h6 class="font-weight-bold">Edit About</h6>
        <div class="row">
            <div class="col-md-6">
                <textarea class="form-control" id="systemAbout" rows="5" placeholder="Enter the About Page Details" disabled>{{ $info->about }}</textarea>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end text-right systemAboutBtn">
                <button type="button" class="btn btn-primary editSysInfo" id="editSysInfoAboutBtn" data-value="{{ $info->about }}">Edit About Page</button>
                <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoAboutBtn">Change About Page</button>
            </div>
        </div>
    </div>

    <!-- Mission Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <h6 class="font-weight-bold">Edit WMSU Mission</h6>
        <div class="row">
            <div class="col-md-6">
                <textarea class="form-control" id="systemMission" rows="5" placeholder="Enter the WMSU Mission" disabled>{{ $info->mission }}</textarea>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end  text-right systemMissionBtn">
                <button type="button" class="btn btn-primary editSysInfo" id="editSysInfoMissionBtn" data-value="{{ $info->mission }}">Edit WMSU Mission Page</button>
                <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoMissionBtn">Change WMSU Mission Page</button>
            </div>
        </div>
    </div>

    <!-- Vision Section -->
    <div class="container border p-3 rounded mb-2 position-relative">
        @if (!$maintenance)
            <div class="overlay" title="Settings can only be accessed under maintenance."></div>
        @endif
        <h6 class="font-weight-bold">Edit WMSU Vision</h6>
        <div class="row">
            <div class="col-md-6">
                <textarea class="form-control" id="systemVision" rows="5" placeholder="Enter the WMSU Vision" disabled>{{ $info->vision }}</textarea>
            </div>
            <div class="col-md-6 d-flex flex-column align-items-end  text-right systemVisionBtn">
                <button type="button" class="btn btn-primary editSysInfo" id="editSysInfoVisionBtn" data-value="{{ $info->vision }}">Edit WMSU Vision Page</button>
                <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoVisionBtn">Change WMSU Vision Page</button>
            </div>
        </div>
    </div>

    <!-- Confirm Maintenance Modal -->
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
