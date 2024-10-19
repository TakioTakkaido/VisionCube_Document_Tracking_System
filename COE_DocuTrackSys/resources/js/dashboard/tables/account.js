// SHOW ACTIVE ACCOUNTS
export function showActive() {
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
            {data: 'name'},
            {data: 'email'},
            {data: 'role'}
        ],
        language: {
            emptyTable: "No active accounts present."
        },
        destroy: true,
        pagination: true,

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
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });

                var secretary, assistant, clerk;
                switch (data.role) {
                    case 'Secretary':
                        secretary = 'disabled';
                        break;
                    case 'Assistant':
                        assistant = 'disabled';
                        break;
                    case 'Clerk':
                        clerk = 'disabled';
                        break;
                    default:
                        break;
                }


                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="deactivateBtn${data.id}">Deactivate</button>` +
                        
                        `<button type="button" class="list-group-item" id="changeRoleBtn${data.id}">Change Role</button>` +
                        `<div class="list-group-item dropright">` +
                        `<div class="dropdown-menu" id="changeRoleDropdown${data.id}" aria-hidden="true">
                            <a class="dropdown-item ${secretary}" href="#" id="changeSecretary${data.id}">Secretary</a>
                            <a class="dropdown-item ${assistant}" href="#" id="changeAssistant${data.id}">Assistant</a>
                            <a class="dropdown-item ${clerk}" href="#" id="changeClerk${data.id}">Clerk</a>
                        </div>`+
                    `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual', 
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#deactivateBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('toggle'); 
                        deactivateAccount(data.id, row); 
                    });

                    $('#changeRoleBtn' + data.id).off('click').on('click', function(event) {
                        console.log('change');
                        $('#changeRoleDropdown' + data.id).toggleClass('show');
                    });

                    $('#changeSecretary' + data.id).off('click').on('click', function(event) {
                        changeRole(data.id, 'Secretary', row);
                    });

                    $('#changeAssistant' + data.id).off('click').on('click', function(event) {
                        changeRole(data.id, 'Assistant', row);
                    });

                    $('#changeClerk' + data.id).off('click').on('click', function(event) {
                        changeRole(data.id, 'Clerk', row);
                    });
                });


                $(this).popover('toggle');  

                $(document).off('click.popover').on('click.popover', function(e) {
                   
                    if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                        $(row).popover('hide'); 
                    }
                });
                
            });
        },

        autoWidth: false
    });

    // Show the account table
    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// SHOW DEACTIVATED ACCOUNTS
export function showDeactivated() {
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
        language: {
            emptyTable: "No deactivated accounts present."
        },
        destroy: true,
        pagination: true,

        createdRow: function(row, data){
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });


            $(row).on('click', function(event){
                event.preventDefault();
                console.log('Document preview');
            });

            $(row).on('contextmenu', function(event){
                event.preventDefault();
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });

                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="reactivateBtn${data.id}">Reactivate</button>` +
                    `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#reactivateBtn' + data.id).off('click').on('click', function(event) {
                        $(row).popover('hide'); 
                        reactivateAccount(data.id); 
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


    // Show the account table
    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// Default
// Statistics, analytics
// In and out
// Pending for approval documents


// Deactivate account
function deactivateAccount(accountId, row) {
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
    }

    $.ajax({
        method: "POST",
        url: window.routes.deactivateAccount.replace(':id', accountId),
        data: formData,
        success: function (response) {
            console.log("Account deactivated successfully!");
            $('#dashboardTable').DataTable().ajax.reload();
        }
    });
}

// Reactivate account
function reactivateAccount(accountId, row) {
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
    }

    $.ajax({
        method: "POST",
        url: window.routes.reactivateAccount.replace(':id', accountId),
        data: formData,
        success: function (response) {
            console.log("Account reactivated successfully!");
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('toggle');
        }
    });
}

// Change account role
function changeRole(accountId, newRole, row) {
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
    }

    $.ajax({
        method: "POST",
        url: window.routes.editAccountRole
            .replace(':id', accountId)
            .replace(':role', newRole),
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('toggle');
        }
    });
}