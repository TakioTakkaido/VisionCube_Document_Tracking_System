export function showLogs(){
    $('.dashboardTableTitle').html('System Logs');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");
    
    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Timestamp</th>" +
        "<th>Account</th>" +
        "<th>Description</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Timestamp</th>" +
        "<th>Account</th>" +
        "<th>Description</th>" +  
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showAllLogs,
            dataSrc: 'logs'
        },
        columns: [
            {data: 'created_at'},
            {data: 'account'},
            {data: 'description'},
        ],
        destroy: true,
        pagination: true,
        order: {
            idx: 0,
            dir: 'desc'
        },
        language: {
            emptyTable: "No system logs present."
        },
        createdRow: function(row, data){
        $(row).on('mouseenter', function(){
            document.body.style.cursor = 'pointer';
        });

        $(row).on('mouseleave', function() {
            document.body.style.cursor = 'default';
        });

        $(row).on('click', function(event){
            event.preventDefault();
            $(row).popover('hide');
        });

        $(row).on('contextmenu', function(event){
            event.preventDefault();
            $('.popover').popover('hide');
        
            $(this).popover({
                content: `<div class="list-group menu">`+
                    `<button type="button" class="list-group-item" id="logsBtn${data.id}">View Logs Information</button>` +
                `</div>`,
                html: true,
                container: 'body',
                placement: 'right',
                trigger: 'manual', 
                animation: false
            }).on('inserted.bs.popover', function(event) {
                $('#logsBtn' + data.id).off('click').on('click', function(event) {
                    $(row).popover('toggle'); 
                    viewLogInformation(data.id); 
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


function viewLogInformation(logId) {
    console.log(logId);
}
