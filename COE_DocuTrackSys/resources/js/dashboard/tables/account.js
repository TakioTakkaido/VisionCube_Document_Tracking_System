export function showAllActiveAccounts() {
    $('.dashboardTableTitle').html('Active Accounts');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Username</th>" +
        "<th>Email</th>" +
        "<th>Role</th>" +            
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Username</th>" +
        "<th>Email</th>" +
        "<th>Role</th>" +
        "</tr></tfoot>"
    );

    // Get all accounts using AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showAllActiveAccounts,
            dataSrc: 'accounts'
        },
        columns: [
            {data: 'email'},
            {data: 'name'},
            {data: 'role'}
        ],
        destroy: true,
        pagination: true,
        createdRow: function(row){
            $(row).on('click', function(event){
                event.preventDefault();
                console.log('Document preview');
            });

            $(row).on('contextmenu', function(event){
                event.preventDefault();
                console.log('Right click menu');
            });
        }
    });

    // Show the account table
    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

export function showAllDeactivatedAccounts() {
    $('.dashboardTableTitle').html('Deactivated Accounts');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");
    
    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Username</th>" +
        "<th>Email</th>" +
        "<th>Role</th>" +            
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Username</th>" +
        "<th>Email</th>" +
        "<th>Role</th>" +
        "</tr></tfoot>"
    );
    // Get all accounts using AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showAllDeactivatedAccounts,
            dataSrc: 'accounts'
        },
        columns: [
            {data: 'email'},
            {data: 'name'},
            {data: 'role'}
        ],
        destroy: true,
        pagination: true,
        createdRow: function(row){
            $(row).on('click', function(event){
                event.preventDefault();
                console.log('Document preview');
            });

            $(row).on('contextmenu', function(event){
                event.preventDefault();
                // Show the list group for the menu
                console.log('Right click menu');
            });
        }
    });

    // Show the account table
    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// Default
// Statistics, analytics
// In and out
// Pending for approval documents