import { showNotification } from "../../notification";

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
                if(data.role != 'Admin'){
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
                        content:    `<div class="list-group menu p-0">
                                        <div class="list-group-item py-1 px-2 rightClickListItem" id="deactivateBtn${data.id}">
                                            <i class='bx bx-user-x' style="font-size: 15px;"></i>  Deactivate</div>
                                        <div class="dropright p-0">
                                            <div class="list-group-item py-1 px-2 rightClickListItem" id="changeRoleBtn${data.id}">
                                                <i class='bx bx-sort bx-rotate-90' style="font-size: 15px;"></i>  Change Role</div>
                                            <div class="dropdown-menu rightClickDropdown p-0" id="changeRoleDropdown${data.id}" aria-labelledby="changeRoleBtn${data.id}">
                                                <a class="dropdown-item ${secretary}    rightClickDropdownItem py-1 pl-3" href="#" id="changeSecretary${data.id}">Secretary</a>
                                                <a class="dropdown-item ${assistant}    rightClickDropdownItem py-1 pl-3" href="#" id="changeAssistant${data.id}">Assistant</a>
                                                <a class="dropdown-item ${clerk}        rightClickDropdownItem py-1 pl-3" href="#" id="changeClerk${data.id}">Clerk</a>
                                            </div>
                                        </div>
                                    </div>`,
                        html: true,
                        container: 'body',
                        placement: 'right',
                        template:   `<div class="popover p-0 rightClickList">
                                        <div class="popover-body p-0">
                                        </div>
                                    </div>`,
                        trigger: 'focus', 
                        animation: false
                    }).on('inserted.bs.popover', function(event) {
                        $('#deactivateBtn' + data.id).off('click').on('click', function(event) {
                            event.preventDefault();
                            $(row).popover('toggle'); 
                            deactivateAccount(data.id, row); 
                        });
    
                        $('#changeRoleBtn' + data.id).off('mouseenter').on('mouseenter', function(event) {
                            // event.preventDefault();
                            setTimeout(function() {
                                $('#changeRoleDropdown' + data.id).toggleClass('show')
                            }, 300);
                        });
                        
                        $('#changeSecretary' + data.id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                changeRole(data.id, 'Secretary', row);
                            }
                        });
    
                        $('#changeAssistant' + data.id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                changeRole(data.id, 'Assistant', row);
                            }
                        });
    
                        $('#changeClerk' + data.id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                changeRole(data.id, 'Clerk', row);
    
                            }
                        });
                    });
    
    
                    $(this).popover('toggle');  
    
                    $(document).off('click.popover').on('click.popover', function(e) {
                    
                        if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                            $(row).popover('hide'); 
                        }
                    });
                }
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
            {data: 'name'},
            {data: 'email'},
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
                    content:    `<div class="list-group menu p-0">
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="reactivateBtn${data.id}"><i class='bx bx-user-check' style="font-size: 15px;"></i>   Reactivate</div>
                                </div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    template:   `<div class="popover p-0 rightClickList">
                                    <div class="popover-body p-0">
                                    </div>
                                </div>`,
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
        },
        autoWidth: false
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
            $('#dashboardTable').DataTable().ajax.reload();
            showNotification("Account deactivated successfully!");
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
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('toggle');
            showNotification("Account reactivated successfully!");
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
            showNotification('Account changed to ' + newRole + ' successfully!')
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('hide');
        }
    });
}