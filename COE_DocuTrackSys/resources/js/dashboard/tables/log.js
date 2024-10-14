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
            viewLogInformation(data.id);
        });

        $(row).on('contextmenu', function(event){
            event.preventDefault();
            if ($(row).data('bs.popover')) {
                $(row).popover('toggle');
                console.log('bembem');
            } else {
                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="logsBtn${data.id}">View Logs Information</button>` +
                    `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual', 
                    animation: false,
                    id: data.id
                }).on('inserted.bs.popover', function(event) {
                    $('#logsBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        viewLogInformation(data.id); 
                    });
                });

                
                $(this).popover('show'); 
            }

            $('.popover').each(function (index, element) {
                // Compare the IDs of popovers and hide if it's not the current row's popover
                // console.log("====================")
                // console.log($(element).attr('id'));
                // console.log($(element));
                // console.log($($(this).popover()).attr('id'));
                // console.log($(this).popover());
                console.log($(element));
                if ($(element).attr('id') != $($(this).popover()).attr('id')) {
                    $(element).popover('hide');
                    
                    console.log('Not the same popover, closing');
                }
            });

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
    // New formdata
    var formData = new FormData();

    // Ajax request
    formData = {
        '_token' : $('#token').val()
    }

    $.ajax({
        method: "GET",
        url: window.routes.logInfo.replace(':id', logId),
        data: formData,
        success: function (response) {
            $('#logDate').html(`<strong>Timestamp: </strong>${response.log.created_at}`);
            $('#logAccount').html(`<strong>Account: </strong>${response.log.account}`);
            $('#logDescription').html(`<strong>Details: </strong>${response.log.description}`);
            $('#logInfo').modal('show');
        }
    });

    // Open log info modal
    

}
