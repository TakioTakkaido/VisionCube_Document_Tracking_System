<div>
    {{-- Today --}}
    <div class="container-fluid border p-3 rounded mb-2">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col">
                <h6 class="font-weight-bold mb-0" style="font-size: 15px;">Daily Tracked Documents</h6>
                <small class="text-muted" id="dateDay"><i>Day: {{ date('M. j, Y') }}</i></small>
            </div>


            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control mr-2" id="analyticsDay" placeholder="Select Day" autocomplete="off" data-value="{{ date('M. j, Y') }}">
                    </div>

                    <div class="col-auto">
                        <button type="button" class="btn btn-primary font-weight-bold text-right reportBtn {{Auth::user()->canReport == false ? 'disabled' : ''}}" id="generateReportDayBtn">
                            Generate Report
                        </button>
                    </div>
                </div>
                
            </div>

            
        </div>

        {{-- Breakdown Daily --}}
        <div class="row">
            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Category</h6>
                <div class="container-fluid border p-3 rounded mb-2 categoryAnalytics" id="categoryDay" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>
                </div>
            </div>

            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Status</h6>
                <div class="container-fluid border p-3 rounded mb-2 statusAnalytics" id="statusDay" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- This Week --}}
    <div class="container-fluid border p-3 rounded mb-2">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0" style="font-size: 15px;">Weekly Tracked Documents</h6>
                <small class="text-muted" id="dateWeek"><i>Week: {{date('M. j, Y', strtotime('last Sunday'))}} -  {{date('M. j, Y', strtotime('next Saturday')) }}</i></small>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control w-15" id="analyticsWeek" placeholder="Select Week" autocomplete="off" data-value="{{ date('M. j, Y') }}">
                    </div>
                    
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary font-weight-bold text-right reportBtn {{Auth::user()->canReport == false ? 'disabled' : ''}}" id="generateReportWeekBtn">
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div>

        {{-- Breakdown Weekly --}}
        <div class="row">
            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Category</h6>
                <div class="container-fluid border p-3 rounded mb-2 categoryAnalytics" id="categoryWeek" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>
                </div>
            </div>

            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Status</h6>
                <div class="container-fluid border p-3 rounded mb-2 statusAnalytics" id="statusWeek" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- This Month --}}
    <div class="container-fluid border p-3 rounded mb-2">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0" style="font-size: 15px;">Monthly Tracked Documents</h6>
                <small class="text-muted" id="dateMonth"><i>Month: {{ date('M. Y') }}</i></small>
            </div>
            
            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control w-15" id="analyticsMonth" placeholder="Select Month" autocomplete="off" data-value="{{ date('M. Y') }}">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary font-weight-bold text-right reportBtn {{Auth::user()->canReport == false ? 'disabled' : ''}}" id="generateReportMonthBtn">
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
        </div> 
        
        {{-- Breakdown Monthly --}}
        <div class="row">
            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Category</h6>
                <div class="container-fluid border p-3 rounded mb-2 categoryAnalytics" id="categoryMonth" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>
                </div>
            </div>

            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Status</h6>
                <div class="container-fluid border p-3 rounded mb-2 statusAnalytics" id="statusMonth" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- This Year --}}
    <div class="container-fluid border p-3 rounded mb-2">
        <div class="row d-flex justify-content-between align-items-center mb-3">
            <div class="col">
                <h6 class="p-0 font-weight-bold mb-0" style="font-size: 15px;">Yearly Tracked Documents</h6>
                <small class="text-muted" id="dateYear"><i>Year: {{ date('Y') }}</i></small>
            </div>
            <div class="col">
                <div class="row">
                    <div class="col">
                        <input type="text" class="form-control w-15" id="analyticsYear" placeholder="Select Year" autocomplete="off" data-value="{{ date('Y') }}">
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-primary font-weight-bold text-right reportBtn {{Auth::user()->canReport == false ? 'disabled' : ''}}" id="generateReportYearBtn">
                            Generate Report
                        </button>
                    </div>
                </div>
            </div>
            
        </div>

        
        <div class="row">
            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Category</h6>
                <div class="container-fluid border p-3 rounded mb-2 categoryAnalytics" id="categoryYear" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>
                </div>
            </div>

            <div class="col">
                <h6 class="p-0 text-left" style="font-size: 15px;">By Status</h6>
                <div class="container-fluid border p-3 rounded mb-2 statusAnalytics" id="statusYear" style="max-height: 250px; overflow-y: scroll;">
                    <span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Generate Report -->
    <div class="modal fade" id="generateReport" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Generate Report</h5>
                </div>
                <div class="modal-body">
                    <div class="container">
                        <form>
                            <input type="text" class="d-none" id="reportDate">
                            <input type="text" class="d-none" id="reportSystemName" value="{{$systemName." Report "}}">
                    
                            <div class="form-group mb-3">
                                <label for="reportFile" class="form-label" style="font-size: 15px;">Report File Name:</label>
                                <input type="text" class="form-control" id="reportFile" placeholder="Report Name" required>
                            </div>
                    
                            <div class="form-group mb-3">
                                <label for="reportFolder" class="form-label" style="font-size: 15px;">Google Drive to Store Report:</label>
                                <select id="reportFolder" class="form-control" style="font-size: 15px;">
                                    @foreach ($drives as $drive)
                                        <option value="{{$drive->id}}">{{$drive->email}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </form>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary reportBtn" id="generateReportBtn">Generate Report</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    {{-- Report Table, stay hidden --}}
    <div class="hidden-content d-none">
        <div id="outgoing-table">
        </div>
        
        <div id="incoming-table">
        </div>
      </div>
</div>