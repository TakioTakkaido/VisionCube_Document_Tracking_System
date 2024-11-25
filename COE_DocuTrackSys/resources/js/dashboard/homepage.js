import { showNotification } from "../notification";

$(document).ready(function(){
    $.when(getNewDocuments(), getNewReports(), getMaintenanceStatus()).then(function() {
        // Initialize the DataTable after AJAX completes
        $('#accountAccessTable').DataTable();
        $('#analyticsDay').datepicker({
            format: "M. d, yyyy",
            autoclose: true
        }).on('changeDate', function(event) {
            event.preventDefault();
            getDocumentStatistics($(this).datepicker('getDate'), 'Day');
        });

        $('#analyticsWeek').datepicker({
            calendarWeeks: true,
            format: "M. d, yyyy",
            autoclose: true,
        }).on('changeDate', function(event) {
            event.preventDefault();
            getDocumentStatistics($(this).datepicker('getDate'), 'Week');
        });

        $('#analyticsMonth').datepicker({
            format: "M. yyyy",
            minViewMode: 1
        }).on('changeDate', function(event) {
            event.preventDefault();
            getDocumentStatistics($(this).datepicker('getDate'), 'Month');
        });

        $('#analyticsYear').datepicker({
            format: "yyyy",
            minViewMode: 2
        }).on('changeDate', function(event) {
            event.preventDefault();
            getDocumentStatistics($(this).datepicker('getDate'), 'Year');
        });

        
        $('.loading').hide();
    });
});

export function documentStatistics(reload = true) {
    if (reload == true){
        $('.loading').show();
    } else {
        $('body').css('cursor', 'progress');
    }

    return $.ajax({
        method: 'GET',
        url: window.routes.getDocumentStatistics,
        success: function(response) {
            // Breakdown
            // Day
            var daily = JSON.parse(response.daily);
            var total = daily.total;
            if (total !== 0 && !$('#analyticsDay').val()){
                var dailyCategory = daily.category;
                var dailyStatus = daily.status;
                var dailyColor = daily.color;

                // Category
                var categoryStats = "";
                categoryStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;

                for (var name in dailyCategory) {
                    if (dailyCategory.hasOwnProperty(name)) {
                        if (value != 'Archived' && value != 'Trash'){
                            var value = dailyCategory[name]; // Value for the current category
                    
                            // Calculate the percentage of the progress bar
                            var percentage = (value / total) * 100;
                    
                            // Append the HTML for the category name and progress bar
                            categoryStats += `
                                <div class="mb-1">
                                    <div class="mb-0 d-flex justify-content-between">
                                        <p class="mb-0">${name}</p>
                                        <p class="mb-0">${value}</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }

                // Status
                var statusStats = "";
                statusStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;

                for (var name in dailyStatus) {
                    if (dailyStatus.hasOwnProperty(name)) {
                        var statusData = dailyStatus[name];  // Access the status data object

                        var color = dailyColor[name]; // Get the value for the current color
                        var value = statusData;
                        
                        // Calculate the percentage of the progress bar
                        var percentage = (value / total) * 100;
                
                        // Append the HTML for the status name and progress bar
                        statusStats += `
                            <div class="mb-1">
                                <div class="mb-0 d-flex justify-content-between">
                                    <p class="mb-0">${name}</p>
                                    <p class="mb-0">${value}</p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${color}"></div>
                                </div>
                            </div>
                        `;
                    }
                }

                $('#categoryDay').html(categoryStats);
                $('#statusDay').html(statusStats);
            } else {
                $('#categoryDay').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>`);
                $('#statusDay').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>`);
            }


            // Week
            var weekly = JSON.parse(response.weekly);
            total = weekly.total;
            if (total !== 0 && !$('#analyticsWeek').val()){
                var weeklyCategory = weekly.category;
                var weeklyStatus = weekly.status;
                var weeklyColor = weekly.color;

                // Category
                categoryStats = "";
                categoryStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;

                for (var name in weeklyCategory) {
                    if (weeklyCategory.hasOwnProperty(name)) {
                        if (value != 'Archived' && value != 'Trash'){
                            var value = weeklyCategory[name]; // Value for the current category
                    
                            // Calculate the percentage of the progress bar
                            var percentage = (value / total) * 100;
                    
                            // Append the HTML for the category name and progress bar
                            categoryStats += `
                                <div class="mb-1">
                                    <div class="mb-0 d-flex justify-content-between">
                                        <p class="mb-0">${name}</p>
                                        <p class="mb-0">${value}</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }

                // Status
                statusStats = "";
                statusStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;

                for (var name in weeklyStatus) {
                    if (weeklyStatus.hasOwnProperty(name)) {
                        var statusData = weeklyStatus[name];  // Access the status data object
                
                        var color = weeklyColor[name];
                        var value = statusData; // Get the value for the current color
                
                        // Calculate the percentage of the progress bar
                        var percentage = (value / total) * 100;
                
                        // Append the HTML for the status name and progress bar
                        statusStats += `
                            <div class="mb-1">
                                <div class="mb-0 d-flex justify-content-between">
                                    <p class="mb-0">${name}</p>
                                    <p class="mb-0">${value}</p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${color}"></div>
                                </div>
                            </div>
                        `;
                    }
                }

                $('#categoryWeek').html(categoryStats);
                $('#statusWeek').html(statusStats);

            } else {
                $('#categoryWeek').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>`);
                $('#statusWeek').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>`);
            }

            // Month
            var monthly = JSON.parse(response.monthly);
            total = monthly.total;
            if (total !== 0 && !$('#analyticsMonth').val()){
                var monthlyCategory = monthly.category;
                var monthlyStatus = monthly.status;
                var monthlyColor = monthly.color;
    
                // Category
                categoryStats = "";
                categoryStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;
    
                for (var name in monthlyCategory) {
                    if (monthlyCategory.hasOwnProperty(name)) {
                        if (value != 'Archived' && value != 'Trash'){
                            var value = monthlyCategory[name]; // Value for the current category
                    
                            // Calculate the percentage of the progress bar
                            var percentage = (value / total) * 100;
                    
                            // Append the HTML for the category name and progress bar
                            categoryStats += `
                                <div class="mb-1">
                                    <div class="mb-0 d-flex justify-content-between">
                                        <p class="mb-0">${name}</p>
                                        <p class="mb-0">${value}</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }
    
                // Status
                statusStats = "";
                statusStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;
    
                for (var name in monthlyStatus) {
                    if (monthlyStatus.hasOwnProperty(name)) {
                        var statusData = monthlyStatus[name];  // Access the status data object
                
                        var color = monthlyColor[name]; // Get the color name (key)
                        var value = statusData; // Get the value for the current color
                
                        // Calculate the percentage of the progress bar
                        var percentage = (value / total) * 100;
                
                        // Append the HTML for the status name and progress bar
                        statusStats += `
                            <div class="mb-1">
                                <div class="mb-0 d-flex justify-content-between">
                                    <p class="mb-0">${name}</p>
                                    <p class="mb-0">${value}</p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${color}"></div>
                                </div>
                            </div>
                        `;
                    }
                }
    
                $('#categoryMonth').html(categoryStats);
                $('#statusMonth').html(statusStats);
            } else {
                $('#categoryMonth').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>`);
                $('#statusMonth').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>`);
            }


            // Month
            var yearly = JSON.parse(response.yearly);
            total = yearly.total;
            if (total !== 0 && !$('#analyticsYear').val()){
                var yearlyCategory = yearly.category;
                var yearlyStatus = yearly.status;
                var yearlyColor = yearly.color;
    
                // Category
                categoryStats = "";
                categoryStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;
    
                for (var name in yearlyCategory) {
                    if (yearlyCategory.hasOwnProperty(name)) {
                        if (value != 'Archived' && value != 'Trash'){
                            var value = yearlyCategory[name]; // Value for the current category
                    
                            // Calculate the percentage of the progress bar
                            var percentage = (value / total) * 100;
                    
                            // Append the HTML for the category name and progress bar
                            categoryStats += `
                                <div class="mb-1">
                                    <div class="mb-0 d-flex justify-content-between">
                                        <p class="mb-0">${name}</p>
                                        <p class="mb-0">${value}</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }
    
                // Status
                statusStats = "";
                statusStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;
    
                for (var name in yearlyStatus) {
                    if (yearlyStatus.hasOwnProperty(name)) {
                        var statusData = yearlyStatus[name];  // Access the status data object
                
                        var color = yearlyColor[name]
                        var value = statusData; // Get the value for the current color
                
                        // Calculate the percentage of the progress bar
                        var percentage = (value / total) * 100;
                
                        // Append the HTML for the status name and progress bar
                        statusStats += `
                            <div class="mb-1">
                                <div class="mb-0 d-flex justify-content-between">
                                    <p class="mb-0">${name}</p>
                                    <p class="mb-0">${value}</p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${color}"></div>
                                </div>
                            </div>
                        `;
                    }
                }
    
                $('#categoryYear').html(categoryStats);
                $('#statusYear').html(statusStats);

            } else {
                $('#categoryYear').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>`);
                $('#statusYear').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>`);
            }
        },
        beforeSend: function(){
            showNotification('Checking document analytics...');
        },
        complete: function(){
            $('#loadingHomepage').removeClass('active');
            $('#loadingHomepage').css('display', 'none');
            $('#reports').addClass('active');
            $('.loading').hide();
            showNotification('Analytics is up to date!');
            $('body').css('cursor', 'auto');
        }
    });
}

function getMaintenanceStatus(){
    return $.ajax({
        method: 'GET',
        url: window.routes.getMaintenanceStatus,
        success: function(response) {
            if (response.verified == true){
                if (response.maintenance == true){
                    $('#systemSettingsDropdown').addClass('show');
                    $('#accountSettingsBtn').trigger('click');
                    $('#loadingHomepage').hide();
                } else {
                    $('#homePageBtn').trigger('click');
                }
            } else {
                $('#profileSettingsBtn').trigger('click');
                $('#systemSettingsDropdown').addClass('show');
                $('#loadingHomepage').hide();
                $('#verifyFirst').modal('show');
            }
        }
    });
}

function getDocumentStatistics(date, type){
    $('.loading').show();
    $.ajax({
        method: 'GET',
        url: window.routes.getSpecificDocumentStatistics
            .replace(':date', date.toDateString())
            .replace(':type', type),
        success: function(response) {
            var documents = JSON.parse(response.documents);
            // console.log(documents);
            var category = documents.category;
            var status = documents.status;
            var color = documents.color;
            var total = documents.total;

            if(total !== 0){
                var categoryStats = "";
                categoryStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;

                for (var name in category) {
                    if (category.hasOwnProperty(name)) {
                        if (value != 'Archived' && value != 'Trash'){
                            var value = category[name]; // Value for the current category
                    
                            // Calculate the percentage of the progress bar
                            var percentage = (value / total) * 100;
                    
                            // Append the HTML for the category name and progress bar
                            categoryStats += `
                                <div class="mb-1">
                                    <div class="mb-0 d-flex justify-content-between">
                                        <p class="mb-0">${name}</p>
                                        <p class="mb-0">${value}</p>
                                    </div>
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: ${percentage}%;"></div>
                                    </div>
                                </div>
                            `;
                        }
                    }
                }

                var statusStats = "";
                statusStats += `
                    <div class="mb-1">
                        <div class="mb-0 d-flex justify-content-between">
                            <p class="mb-0">All</p>
                            <p class="mb-0">${total}</p>
                        </div>
                        <div class="progress">
                            <div class="progress-bar" role="progressbar" style="width: 100%; background-color: #810505;" aria-valuemax="${total}"></div>
                        </div>
                    </div>
                `;
                
                for (var name in status) {
                    if (status.hasOwnProperty(name)) {
                        var statusData = status[name];  // Access the status data object
                
                        var col = color[name];
                        var value = statusData; // Get the value for the current color
                
                        // Calculate the percentage of the progress bar
                        var percentage = (value / total) * 100;
                
                        // Append the HTML for the status name and progress bar
                        statusStats += `
                            <div class="mb-1">
                                <div class="mb-0 d-flex justify-content-between">
                                    <p class="mb-0">${name}</p>
                                    <p class="mb-0">${value}</p>
                                </div>
                                <div class="progress">
                                    <div class="progress-bar" role="progressbar" style="width: ${percentage}%; background-color: ${col}"></div>
                                </div>
                            </div>
                        `;
                    }
                }

                    if(type === 'Day'){
                        $('#dateDay').html('<i>Day: ' + moment(date).format('MMM. D, YYYY') + '</i>');
                        $('#categoryDay').html(categoryStats);
                        $('#statusDay').html(statusStats);
                    }else if(type === 'Week'){ 
                        $('#dateWeek').html('<i>Week: ' + moment(date).startOf('week').format('MMM. D, YYYY') + ' - ' + moment(date).endOf('week').format('MMM. D, YYYY') + '</i>');
                        $('#categoryWeek').html(categoryStats);
                        $('#statusWeek').html(statusStats);
                    }else if(type === 'Month'){
                        $('#dateMonth').html('<i>Month: ' + moment(date).format('MMM. YYYY') + '</i>');
                        $('#categoryMonth').html(categoryStats);
                        $('#statusMonth').html(statusStats);
                    }else {
                        $('#dateYear').html('<i>Year: ' + moment(date).format('YYYY') + '</i>');
                        $('#categoryYear').html(categoryStats);
                        $('#statusYear').html(statusStats);
                    }
            } else {
                if(type === 'Day'){
                    $('#dateDay').html('<i>Day: ' + moment(date).format('MMM. D, YYYY') + '</i>');
                    $('#categoryDay').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>`);
                    $('#statusDay').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this day.</span>`);
                }else if(type === 'Week'){
                    $('#dateWeek').html('<i>Week: ' + moment(date).startOf('week').format('MMM. D, YYYY') + ' - ' + moment(date).endOf('week').format('MMM. D, YYYY') + '</i>');
                    $('#categoryWeek').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>`);
                    $('#statusWeek').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this week.</span>`);
                }else if(type === 'Month'){
                    $('#dateMonth').html('<i>Month: ' + moment(date).format('MMM. YYYY') + '</i>');
                    $('#categoryMonth').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>`);
                    $('#statusMonth').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this month.</span>`);
                }else {
                    $('#dateYear').html('<i>Year: ' + moment(date).format('YYYY') + '</i>');
                    $('#categoryYear').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>`);
                    $('#statusYear').html(`<span class="d-flex justify-content-center align-items-center text-justify">No documents tracked at this year.</span>`);
                }
            }
        }, 
        complete: function(){
            $('.loading').hide();
            getReportTable(date, type);
        }
    });
}

export function getReportTable(date, type){
    console.log(date)
    $('.loading').show();
    $.ajax({
        method: 'GET',
        url: window.routes.getReportDocuments
            .replace(':date', date.toDateString())
            .replace(':type', type),
        success: function(response) {
            // Header
            var documents = JSON.parse(response.reportOutgoingDocuments);
            var header = JSON.parse(response.reportHeader);
            console.log(documents)
            console.log(header);
            var outgoingTable = ""
            outgoingTable += '<table id="outgoing-table" class="table table-striped">';
            outgoingTable += `<thead><tr>`;

            // Iterate over the header array and add each item as a table header
            header.forEach(function(columnName) {
                outgoingTable += `<th>${columnName}</th>`;
            });

            outgoingTable += `</tr></thead>`;

            // Body
            outgoingTable += `<tbody>`;
            documents.forEach(data => {
                var row =  ""
                row += `<tr>`
                data.forEach(function(element, index){
                    row += `<td>${data[index]}</td>`
                });
                row  += `</tr>`;

                outgoingTable += row;
            });
            outgoingTable += `</tbody>`;
            
            $('#outgoing-table').html(outgoingTable);

            documents = JSON.parse(response.reportIncomingDocuments);
            var header = JSON.parse(response.reportHeader);
            
            var incomingTable = ""
            incomingTable += '<table id="incoming-table" class="table table-striped">';
            incomingTable += `<thead><tr>`;

            // Iterate over the header array and add each item as a table header
            header.forEach(function(columnName) {
                incomingTable += `<th>${columnName}</th>`;
            });

            incomingTable += `</tr></thead>`;

            // Body
            incomingTable += `<tbody>`;
            documents.forEach(data => {
                var row =  ""
                row += `<tr>`
                data.forEach(function(element, index){
                    row += `<td>${data[index]}</td>`
                });
                row  += `</tr>`;

                incomingTable += row;
            });
            incomingTable += `</tbody>`;
            
            $('#incoming-table').html(incomingTable);
        }, 
        complete: function(){
            $('.loading').hide();
        }
    });
}
export function getNewDocuments(){
    $.ajax({
        method: "GET",
        url: window.routes.getNewDocuments,
        success: function (response) {
            if(response.totalNewUploaded){
                $('#totalNewUploaded').removeClass('hide');     
            } else {
                $('#totalNewUploaded').addClass('hide');     
            }
            
            if(response.totalNewUpdated){
                $('#totalNewUpdated').removeClass('hide'); 
            } else {
                $('#totalNewUpdated').addClass('hide'); 
            }
            
            if(response.totalNewUploadedIncoming){
                $('#totalNewUploadedIncoming').removeClass('hide'); 
            } else {
                $('#totalNewUploadedIncoming').addClass('hide'); 
            }
            
            if(response.totalNewUpdatedIncoming){
                $('#totalNewUpdatedIncoming').removeClass('hide'); 
            } else {
                $('#totalNewUpdatedIncoming').addClass('hide'); 
            }
            
            if(response.totalNewUploadedOutgoing){
                $('#totalNewUploadedOutgoing').removeClass('hide'); 
            } else {
                $('#totalNewUploadedOutgoing').addClass('hide'); 
            }

            
            if(response.totalNewUpdatedOutgoing){
                $('#totalNewUpdatedOutgoing').removeClass('hide'); 
            } else {
                $('#totalNewUpdatedOutgoing').addClass('hide'); 
            }

            $('#totalNewUploaded').html(response.totalNewUploaded);
            $('#totalNewUpdated').html(response.totalNewUpdated);
            $('#totalNewUploadedIncoming').html(response.totalNewUploadedIncoming);
            $('#totalNewUpdatedIncoming').html(response.totalNewUpdatedIncoming);
            $('#totalNewUploadedOutgoing').html(response.totalNewUploadedOutgoing);
            $('#totalNewUpdatedOutgoing').html(response.totalNewUpdatedOutgoing);
            
            // if(!response.totalNewUpdated){}
            // if(!response.totalNewUpdated){}
        }
    });
}

export function getNewReports(){
    $.ajax({
        method: "GET",
        url: window.routes.getNewReports,
        success: function (response) {
            if(response.totalNewUploaded){
                $('#totalNewUploadedReport').removeClass('hide'); 
            } else {
                $('#totalNewUploadedReport').addClass('hide'); 
            }
            $('#totalNewUploadedReport').html(response.totalNewUploaded);
        }
    });
}

$('#uploadDocumentBtn').on('click', function(event){
    event.preventDefault();
    $('#upload').trigger('click');
});

$('#viewReportsBtn').on('click', function(event){
    event.preventDefault();
    $('#reportsBtn').trigger('click');
});

$('#viewArchivesBtn').on('click', function(event){
    event.preventDefault();
    $('#archivedBtn').trigger('click');
});