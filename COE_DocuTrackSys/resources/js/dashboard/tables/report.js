import jsPDF from "jspdf";
import "jspdf-autotable";
import { showNotification } from "../../notification";
import { getNewReports } from "../homepage";

$('#generateReportDayBtn').on('click', function(event){
    if ($('#analyticsDay').val().length !== 0){
        $('#reportDate').val($('#analyticsDay').val());
    } else {
        $('#reportDate').val($('#analyticsDay').data('value'));
    }

    console.log($('#reportDate').val())
    $('#generateReport').modal('show');
});

$('#generateReportWeekBtn').on('click', function(event){
    if ($('#analyticsWeek').val().length !== 0){
        $('#reportDate').val($('#analyticsWeek').val());
    } else {
        $('#reportDate').val($('#analyticsWeek').data('value'));
    }

    $('#generateReport').modal('show');
});

$('#generateReportMonthBtn').on('click', function(event){
    if ($('#analyticsMonth').val().length !== 0){
        $('#reportDate').val($('#analyticsMonth').val());
    } else {
        $('#reportDate').val($('#analyticsMonth').data('value'));
    }
    $('#generateReport').modal('show');
});

$('#generateReportYearBtn').on('click', function(event){
    if ($('#analyticsYear').val().length !== 0){
        $('#reportDate').val($('#analyticsYear').val());
    } else {
        $('#reportDate').val($('#analyticsYear').data('value'));
    }
    $('#generateReport').modal('show');
});

$('#generateReportBtn').on('click', function(event){
    var fileName = "COE Document Tracking System Report " + $('#reportDate').val();
    var output = fileName.replace(/\s+/g, '_')
    const doc = new jsPDF();

    //LOGO
    const logo = new Image();
    logo.src = window.routes.logo;
    $(logo).on('load', function () {
        const pageWidth = doc.internal.pageSize.width;
        const logoWidth = 25;
        const logoXPosition = 10;
    
        doc.addImage(logo, "PNG", logoXPosition, 10, logoWidth, 25);
    
        // HEADER
        doc.setFontSize(20);
        const headerText = "COE Document Tracking System";
        const addressText = "Normal Rd, Zamboanga, 7000 Zamboanga del Sur";
        const phoneText = "(+63) 900 000 0000";
        const emailText = "coedeansoffice@wmsu.edu.ph";
        const websiteText = "Website: wmsu.edu.ph";
    
        // HEADER INFORMATIONS
        doc.setFontSize(20);
        doc.text(headerText, pageWidth - 10, 15, { align: "right" });
        doc.setFontSize(10);
        doc.setTextColor(80);
        doc.text(addressText, pageWidth - 10, 22, { align: "right" });
        doc.text(phoneText, pageWidth - 10, 26, { align: "right" });
        doc.text(emailText, pageWidth - 10, 30, { align: "right" });
        doc.text(websiteText, pageWidth - 10, 34, { align: "right" });
    
        // DISPLAY OUTGOING TABLE IN PDF
        const outgoingTableRows = $("#outgoing-table tr").toArray().map((row) => {
            return $(row)
                .find("td, th")
                .toArray()
                .map((cell) => $(cell).text().trim());
        });
    
        const outgoingHead = [outgoingTableRows[0]]; // Header row
        const outgoingBody = outgoingTableRows.slice(1); // Body rows
    
        doc.autoTable({
            head: outgoingHead,
            body: outgoingBody,
            startY: 50,
            theme: "striped",
            headStyles: {
                fillColor: [105, 105, 105], // Dark Gray Headers
                textColor: [255, 255, 255], // White text
            },
        });
    
        const lastY = doc.lastAutoTable.finalY + 10;
    
        // DISPLAY INCOMING TABLE IN PDF
        const incomingTableRows = $("#incoming-table tr").toArray().map((row) => {
            return $(row)
                .find("td, th")
                .toArray()
                .map((cell) => $(cell).text().trim());
        });
    
        const incomingHead = [incomingTableRows[0]]; // Header row
        const incomingBody = incomingTableRows.slice(1); // Body rows
    
        doc.autoTable({
            head: incomingHead,
            body: incomingBody,
            startY: lastY,
            theme: "striped",
            headStyles: {
                fillColor: [105, 105, 105], // Dark Gray Headers
                textColor: [255, 255, 255], // White text
            },
        });
    
        // FOOTER DELETE IF NOT NEEDED thnx
        const pageHeight = doc.internal.pageSize.height;
        doc.text(
            "The Analytic Report is created on a computer and is valid without the signature and stamp.",
            35,
            pageHeight - 10
        );
    
        // GENERATE CURRENT DATE
    
        // SAVE PDF
        var reportFile = doc.output("blob");
        var formData = new FormData();

        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('name', output);
        formData.append('drive_id', $('#reportFolder').val());
        formData.append('file', reportFile);

        // Upload report file to google drive
        $.ajax({
            type: "POST",
            url: window.routes.generateReport,
            data: formData,
            processData: false,  // Prevent jQuery from converting the data
            contentType: false,  // Prevent jQuery from overriding the content type
            success: function (response) {
                showNotification('Report generated successfully!')
                getNewReports();
            },
            error: function (response){
                showNotification('Error generating report.')
            },
            beforeSend: function(){
                showNotification('Generating report...');
            },
            complete: function(){
                $('body').css('cursor', 'auto');
            }
        });
    });
    
});

// Report Table
export function showAllReports(){
    $('#tableOverlay').hide();
    $('#archivedTitle').hide();
    $('#archivedDatePicker').hide();
    $('.dashboardTableTitle').html('Generated Reports');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");
    
    $('#dashboardTable').html(
        "<thead><tr>" +
        '<th></th>' +
        "<th>Timestamp</th>" +
        "<th>Report File Name</th>" + 
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        '<th></th>' +
        "<th>Timestamp</th>" +
        "<th>Report File Name</th>" + 
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showReports,
            dataSrc: 'reports',
            beforeSend: function(){
                $('.loading').show();
            },
            complete: function(){
                $('.loading').hide();
            }
        },
        columnDefs: [
            { type: "datetime", targets : 0 }
        ],
        columns: [
            {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
            {
                data: 'created_at',
                render: function (data){ return moment(data.created_at).format('MMM. DD, YYYY hh:kk a')}
            },
            {data: 'name'},
        ],
        destroy: true,
        pagination: true,
        order: {
            idx: 1,
            dir: 'desc'
        },
        select: {
            style: 'multi',
            selector: 'td:first-child'
        },
        language: {
            emptyTable: "No generated reports present."
        },
        createdRow: function(row, data){
            if(data.newUpload){
                $(row).css('font-weight', 'bold');
            }
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });

            $(row).on('click', function(event){
                if ($(event.target).is('.dt-select-checkbox') || $(event.target).is('.dt-select')) {
                    return;
                }
                event.preventDefault();
                $(row).css('font-weight', 'normal');
                seenReport(data.id);
                $(row).popover('hide');
                viewReport(data.id);
            });

            $(row).on('contextmenu', function(event){
                event.preventDefault();
                seenReport(data.id);
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });

                var selectedRows = $('#dashboardTable').DataTable().rows({ selected: true }).data();

                // Extract the 'id' from each selected row and convert it into an array
                var selectedRows = selectedRows.map(function(rowData) {
                    return rowData.id;  // Assuming 'id' is the property in the row data
                }).toArray();

                var popoverContent = `
                    <div class="list-group menu p-0">
                        <div class="list-group-item py-1 px-2 rightClickListItem" id="downloadReportBtn${data.id}">
                            <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Download Report File</div>
                        <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsReadReportBtn${data.id}">
                            <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Mark As Read</div>
                    </div>
                    `;

                if (selectedRows.length > 1){
                    popoverContent = `
                    <div class="list-group menu p-0">
                        <div class="list-group-item py-1 px-2 rightClickListItem" id="downloadReportAllBtn${data.id}">
                            <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Download All Report Files</div>
                        <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsReadReportAllBtn${data.id}">
                            <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Mark All As Read</div>
                    </div>
                `}

                $(this).popover({
                    content: popoverContent,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual', 
                    template:   `<div class="popover p-0 rightClickList">
                                        <div class="popover-body p-0">
                                        </div>
                                    </div>`,
                    animation: false,
                }).on('inserted.bs.popover', function(event) {
                    $('#downloadReportBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        $(row).css('font-weight', 'normal');
                        downloadReportFile(data.id); 
                    });

                    $('#markAsReadReportBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        $(row).css('font-weight', 'normal');
                        markAsReadReport(data.id); 
                    });

                    $('#downloadReportAllBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        downloadReportFileAll(data.id); 
                    });

                    $('#markAsReadReportAllBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        markAsReadReportAll(data.id); 
                    });
                });

                
                $(this).popover('toggle'); 

                $(document).off('click.popover').on('click.popover', function(e) {
                    if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                        $(row).popover('hide');  
                    }
                });
            });
        }
    });

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    } 
}

function downloadReportFile(id){

}

function downloadReportFileAll(ids){

}

export function seenReport(id){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : id
    }

    $.ajax({
        method: "POST",
        url: window.routes.seenReport,
        data: formData,
        success: function (response) {
            console.log('Document not seen');
        },
        error: function(response) {
            console.log(response);
        },
        complete: function(response) {
            getNewReports();
        }
    });
}

// Mark Documents are Read
export function markAsReadReport(row, id){
    $(row).css('font-weight', 'normal');
    var table = $('#dashboardTable').DataTable();
    table.rows().deselect();
    seenReport(id);
}

export function markAsReadReportAll(ids){
    $('.selected').css('font-weight', 'normal');
    var table = $('#dashboardTable').DataTable();
    table.rows().deselect();
    for(var i = 0; i < ids.length; i++){
        seenReport(ids[i]);
    }
    getNewReports();
}