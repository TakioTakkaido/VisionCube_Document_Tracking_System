export function showLogs(){
    $('#tableOverlay').hide();
    $('#archivedTitle').hide();
    $('#archivedDatePicker').hide();
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
            dataSrc: 'logs',
            beforeSend: function(){
                $('.loading').show();
            },
            complete: function(){
                $('.loading').hide();
            }
        }
        ,
        columnDefs: [
            { type: "datetime", targets : 0 }
        ],
        columns: [
            {
                data: 'created_at',
                render: function (data){ return moment(data.created_at).format('MMM. DD, YYYY hh:kk a')}
            },
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

$('#showLatestMaintenanceLog').on('click', function(event){
    event.preventDefault();
    getLatestMaintenanceLog();
});

function getLatestMaintenanceLog(){
    $.ajax({
        method: "GET",
        url: window.routes.getLatestMaintenanceLog,
        success: function (response) {
            $('#logTitle').html('Maintenance Notes');
            $('#latestMaintenanceBadge').html('');
            let logDetail = "";
            $('#logDetailTitle').html('Maintenance Details');            
            for(var i = 0; i < response.length; i++){
                const settings = JSON.parse(response[i].detail);
                if(i == 0){
                    $('#logDate').html(`<strong>Timestamp: </strong>${moment(response[i].created_at).format('MMM. DD, YYYY hh:kk a')}`);
                    $('#logDescription').html(`<strong>Description: </strong>${response[i].description}`);
                    $('#logAccount').html(`<strong>Account: </strong>${response[i].account}`);
                    // Account Accesses
                    logDetail += `<span><strong>Updated Account Accesses: </strong></span><br>`;
                    
                    const accountRoles = ["secretary", "assistant", "clerk"];
                    accountRoles.forEach(role => {
                        const accessList = settings.accesses[role] || [];
                        
                        logDetail += `<span class="text-left mb-1"><strong>${role.charAt(0).toUpperCase() + role.slice(1)}: </strong></span><ul>`;
                        if (accessList.length > 0) {
                            accessList.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                        } else {
                            logDetail += `<li><span>None</span></li>`;
                        }
                        logDetail += `</ul><br>`;
                    });
                
                    // Document Form - Participants
                    const participantTypes = [
                        { key: "addedParticipant", label: "Added/Updated Sender/Recipients" },
                        { key: "deletedParticipant", label: "Deleted Sender/Recipients" },
                        { key: "addedParticipantGroup", label: "Added/Updated Sender/Recipients Group" },
                        { key: "deletedParticipantGroup", label: "Deleted Sender/Recipients Group" }
                    ];
                
                    participantTypes.forEach(type => {
                        const participants = settings[type.key] || [];
                        if (participants.length > 0) {
                            logDetail += `<span><strong>${type.label}: </strong><span><ul>`;
                            participants.forEach(participant => {
                                logDetail += `<span><li>${participant}</li></span>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Updated Sender/Recipient Group Members of Participant Groups
                    const updatedGroups = settings.updatedParticipantGroup || {};
                    if (Object.keys(updatedGroups).length > 0) {
                        logDetail += `<span><strong>Updated Sender/Recipient Group Members of Participant Groups: </strong></span><ul>`;
                        for (const groupId in updatedGroups) {
                            if (updatedGroups.hasOwnProperty(groupId)) {
                                logDetail += `<li><strong>Group ${groupId}</strong><ul>`;
                                updatedGroups[groupId].forEach(member => {
                                    logDetail += `<li><span>${member}</span></li>`;
                                });
                                logDetail += `</li></ul><br>`;
                            }
                        }
                        logDetail += `</ul>`;
                    }
                
                    // Updated Participant Members
                    const updatedParticipants = settings.updatedParticipant || {};
                    if (Object.keys(updatedParticipants).length > 0) {
                        logDetail += `<span><strong>Updated Sender/Recipient Members of Participant Groups: </strong></span><ul>`;
                        for (const participantId in updatedParticipants) {
                            if (updatedParticipants.hasOwnProperty(participantId)) {
                                logDetail += `<li><strong>Participant Group ${participantId}</strong><ul>`;
                                updatedParticipants[participantId].forEach(member => {
                                    logDetail += `<li><span>${member}</span></li>`;
                                });
                                logDetail += `</li></ul><br>`;
                            }
                            logDetail += `</ul>`;
                        }
                    }
                    
                    // Types
                    const typeActions = [
                        { key: "addedType", label: "Added/Updated Types" },
                        { key: "deletedType", label: "Deleted Types" }
                    ];
                
                    typeActions.forEach(type => {
                        const types = settings[type.key] || [];
                        if (types.length > 0) {
                            logDetail += `<span><strong>${type.label}: </strong><ul>`;
                            types.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Statuses
                    const statusActions = [
                        { key: "addedStatus", label: "Added/Updated Statuses" },
                        { key: "deletedStatus", label: "Deleted Statuses" }
                    ];
                
                    statusActions.forEach(status => {
                        const statuses = settings[status.key] || [];
                        if (statuses.length > 0) {
                            logDetail += `<span><strong>${status.label}: </strong><ul>`;
                            statuses.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Allowed File Extensions
                    const extensions = settings.fileExtensions || [];
                    if (extensions.length > 0) {
                        logDetail += `<span><strong>Allowed File Extensions: </strong></span><ul>`;
                        extensions.forEach(ext => {
                            logDetail += `<li><span>.${ext}</span></li>`;
                        });
                        logDetail += `</ul><br>`;
                    }
                    
                    $('#logDetails').html(logDetail);
                } else {
                    $('.logInfo').append(`
                        <br>
                        <span id="logDate${i}"></span><br>
                        <span id="logDescription${i}"></span><br>
                        <span class="mb-2" id="logAccount${i}"></span><br>
                        <div class="logDetails p-0" id="accountDetailLog" data-id="">
                            <div class="p-2 container border rounded" style="max-height: 300px; overflow-y: scroll;">
                                <h6 class="p-0 font-weight-bold mb-3" id="logDetailTitle${i}" style="font-size: 15px;"></h6>
                                <div id="logDetails${i}"></div>
                            </div>
                        </div>`);
        
                    $('#logDate' + i).html(`<strong>Timestamp: </strong>${moment(response[i].created_at).format('MMM. DD, YYYY hh:kk a')}`);
                    $('#logDescription' + i).html(`<strong>Description: </strong>${response[i].description}`);
                    $('#logAccount' + i).html(`<strong>Account: </strong>${response[i].account}`);

                    logDetail = "";
                    
                    // Account Accesses        
                    logDetail += `<span><strong>Updated Account Accesses: </strong></span><br>`;
        
                    const accountRoles = ["secretary", "assistant", "clerk"];
                    accountRoles.forEach(role => {
                        const accessList = settings.accesses[role] || [];
                        
                        logDetail += `<span class="text-left mb-1"><strong>${role.charAt(0).toUpperCase() + role.slice(1)}: </strong></span><ul>`;
                        if (accessList.length > 0) {
                            accessList.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                        } else {
                            logDetail += `<li><span>None</span></li>`;
                        }
                        logDetail += `</ul><br>`;
                    });
                
                    // Document Form - Participants
                    const participantTypes = [
                        { key: "addedParticipant", label: "Added/Updated Sender/Recipients" },
                        { key: "deletedParticipant", label: "Deleted Sender/Recipients" },
                        { key: "addedParticipantGroup", label: "Added/Updated Sender/Recipients Group" },
                        { key: "deletedParticipantGroup", label: "Deleted Sender/Recipients Group" }
                    ];
                
                    participantTypes.forEach(type => {
                        const participants = settings[type.key] || [];
                        if (participants.length > 0) {
                            logDetail += `<span><strong>${type.label}: </strong><span><ul>`;
                            participants.forEach(participant => {
                                logDetail += `<span><li>${participant}</li></span>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Updated Sender/Recipient Group Members of Participant Groups
                    const updatedGroups = settings.updatedParticipantGroup || {};
                    if (Object.keys(updatedGroups).length > 0) {
                        logDetail += `<span><strong>Updated Sender/Recipient Group Members of Participant Groups: </strong></span><ul>`;
                        for (const groupId in updatedGroups) {
                            if (updatedGroups.hasOwnProperty(groupId)) {
                                logDetail += `<li><strong>Group ${groupId}</strong><ul>`;
                                updatedGroups[groupId].forEach(member => {
                                    logDetail += `<li><span>${member}</span></li>`;
                                });
                                logDetail += `</li></ul><br>`;
                            }
                        }
                        logDetail += `</ul>`;
                    }
                
                    // Updated Participant Members
                    const updatedParticipants = settings.updatedParticipant || {};
                    if (Object.keys(updatedParticipants).length > 0) {
                        logDetail += `<span><strong>Updated Sender/Recipient Members of Participant Groups: </strong></span><ul>`;
                        for (const participantId in updatedParticipants) {
                            if (updatedParticipants.hasOwnProperty(participantId)) {
                                logDetail += `<li><strong>Participant Group ${participantId}</strong><ul>`;
                                updatedParticipants[participantId].forEach(member => {
                                    logDetail += `<li><span>${member}</span></li>`;
                                });
                                logDetail += `</li></ul><br>`;
                            }
                            logDetail += `</ul>`;
                        }
                    }
                    
                    // Types
                    const typeActions = [
                        { key: "addedType", label: "Added/Updated Types" },
                        { key: "deletedType", label: "Deleted Types" }
                    ];
                
                    typeActions.forEach(type => {
                        const types = settings[type.key] || [];
                        if (types.length > 0) {
                            logDetail += `<span><strong>${type.label}: </strong><ul>`;
                            types.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Statuses
                    const statusActions = [
                        { key: "addedStatus", label: "Added/Updated Statuses" },
                        { key: "deletedStatus", label: "Deleted Statuses" }
                    ];
                
                    statusActions.forEach(status => {
                        const statuses = settings[status.key] || [];
                        if (statuses.length > 0) {
                            logDetail += `<span><strong>${status.label}: </strong><ul>`;
                            statuses.forEach(item => {
                                logDetail += `<li><span>${item}</span></li>`;
                            });
                            logDetail += `</ul><br>`;
                        }
                    });
                
                    // Allowed File Extensions
                    const extensions = settings.fileExtensions || [];
                    if (extensions.length > 0) {
                        logDetail += `<span><strong>Allowed File Extensions: </strong></span><ul>`;
                        extensions.forEach(ext => {
                            logDetail += `<li><span>.${ext}</span></li>`;
                        });
                        logDetail += `</ul><br>`;
                    }

                    $('#logDetails' + i).html(logDetail);
                }
                    
            }
            
            $('#logInfo').modal('show');
        }
    });
}

function viewLogInformation(logId) {
    $.ajax({
        method: "GET",
        url: window.routes.logInfo.replace(':id', logId),
        success: function (response) {
            $('.logInfo').html(`
                <span id="logDate"></span><br>
                <span id="logDescription"></span><br>
                <span class="mb-2" id="logAccount"></span><br>
                <div class="logDetails p-0" id="accountDetailLog" data-id="">
                    <div class="p-2 container border rounded" style="max-height: 300px; overflow-y: scroll;">
                        <h6 class="p-0 font-weight-bold mb-3" id="logDetailTitle" style="font-size: 15px;"></h6>
                        <div id="logDetails"></div>
                    </div>
                </div>`)
            $('#logTitle').html('Log Information');
            $('#logDate').html(`<strong>Timestamp: </strong>${moment(response.log.created_at).format('MMM. DD, YYYY hh:kk a')}`);
            $('#logDescription').html(`<strong>Description: </strong>${response.log.description}`);
            $('#logAccount').html(`<strong>Account: </strong>${response.log.account}`);
            
            if(response.log.type == 'Account'){
                $('#logDetailTitle').html('Account Details');
                var account = JSON.parse(response.log.detail);
                $('#logDetails').html(`
                    <span><strong>Name: </strong>${account.name}</span><br>
                    <span><strong>Email: </strong>${account.email}</span><br>
                    <span><strong>Role: </strong>${account.role}</span><br>
                `);
            } else if(response.log.type == 'Document'){
                $('#logDetailTitle').html('Document Details');
                var document = JSON.parse(response.log.detail);
                var logDetail = "";
                if (document.length === undefined){
                    logDetail += `
                        <span><strong>Subject: </strong>${document.subject}</span><br>
                        <span><strong>Version: </strong>${document.version_number}</span><br>
                        <span><strong>Date: </strong>${document.document_date}</span><br>
                        <span><strong>Type: </strong>${document.type}</span><br>
                        <span><strong>Sender: </strong>${document.sender}</span><br>
                        <span><strong>Recipient: </strong>${document.recipient}</span><br>
                        <span><strong>Assignee: </strong>${document.assignee}</span><br>
                        <span><strong>Category: </strong>${document.category}</span><br>
                        <span><strong>Series Number: </strong>${document.series_number}</span><br>
                        <span><strong>Memo Number: </strong>${document.memo_number}</span><br>
                        <br>
                    `
                } else {
                    for(var i = 0; i < document.length; i++){
                        logDetail += `
                            <span><strong>Subject: </strong>${document[i].subject}</span><br>
                            <span><strong>Version: </strong>${document[i].version_number}</span><br>
                            <span><strong>Date: </strong>${document[i].document_date}</span><br>
                            <span><strong>Type: </strong>${document[i].type}</span><br>
                            <span><strong>Sender: </strong>${document[i].sender}</span><br>
                            <span><strong>Recipient: </strong>${document[i].recipient}</span><br>
                            <span><strong>Assignee: </strong>${document[i].assignee}</span><br>
                            <span><strong>Category: </strong>${document[i].category}</span><br>
                            <span><strong>Series Number: </strong>${document[i].series_number}</span><br>
                            <span><strong>Memo Number: </strong>${document[i].memo_number}</span><br>
                            <br>
                        `
                    }
                }
                $('#logDetails').html(logDetail);
            } else if(response.log.type == 'Maintenance'){
                $('#logDetailTitle').html('Maintenance Details');
                const settings = JSON.parse(response.log.detail);
                let logDetail = "";
            
                // Account Accesses
                logDetail += `<span><strong>Updated Account Accesses: </strong></span><br>`;
            
                const accountRoles = ["secretary", "assistant", "clerk"];
                accountRoles.forEach(role => {
                    const accessList = settings.accesses[role] || [];
                    
                    logDetail += `<span class="text-left mb-1"><strong>${role.charAt(0).toUpperCase() + role.slice(1)}: </strong></span><ul>`;
                    if (accessList.length > 0) {
                        accessList.forEach(item => {
                            logDetail += `<li><span>${item}</span></li>`;
                        });
                    } else {
                        logDetail += `<li><span>None</span></li>`;
                    }
                    logDetail += `</ul><br>`;
                });
            
                // Document Form - Participants
                const participantTypes = [
                    { key: "addedParticipant", label: "Added/Updated Sender/Recipients" },
                    { key: "deletedParticipant", label: "Deleted Sender/Recipients" },
                    { key: "addedParticipantGroup", label: "Added/Updated Sender/Recipients Group" },
                    { key: "deletedParticipantGroup", label: "Deleted Sender/Recipients Group" }
                ];
            
                participantTypes.forEach(type => {
                    const participants = settings[type.key] || [];
                    if (participants.length > 0) {
                        logDetail += `<span><strong>${type.label}: </strong><span><ul>`;
                        participants.forEach(participant => {
                            logDetail += `<span><li>${participant}</li></span>`;
                        });
                        logDetail += `</ul><br>`;
                    }
                });
            
                // Updated Sender/Recipient Group Members of Participant Groups
                const updatedGroups = settings.updatedParticipantGroup || {};
                if (Object.keys(updatedGroups).length > 0) {
                    logDetail += `<span><strong>Updated Sender/Recipient Group Members of Participant Groups: </strong></span><ul>`;
                    for (const groupId in updatedGroups) {
                        if (updatedGroups.hasOwnProperty(groupId)) {
                            logDetail += `<li><strong>Group ${groupId}</strong><ul>`;
                            updatedGroups[groupId].forEach(member => {
                                logDetail += `<li><span>${member}</span></li>`;
                            });
                            logDetail += `</li></ul><br>`;
                        }
                    }
                    logDetail += `</ul>`;
                }
            
                // Updated Participant Members
                const updatedParticipants = settings.updatedParticipant || {};
                if (Object.keys(updatedParticipants).length > 0) {
                    logDetail += `<span><strong>Updated Sender/Recipient Members of Participant Groups: </strong></span><ul>`;
                    for (const participantId in updatedParticipants) {
                        if (updatedParticipants.hasOwnProperty(participantId)) {
                            logDetail += `<li><strong>Participant Group ${participantId}</strong><ul>`;
                            updatedParticipants[participantId].forEach(member => {
                                logDetail += `<li><span>${member}</span></li>`;
                            });
                            logDetail += `</li></ul><br>`;
                        }
                        logDetail += `</ul>`;
                    }
                }
                
                // Types
                const typeActions = [
                    { key: "addedType", label: "Added/Updated Types" },
                    { key: "deletedType", label: "Deleted Types" }
                ];
            
                typeActions.forEach(type => {
                    const types = settings[type.key] || [];
                    if (types.length > 0) {
                        logDetail += `<span><strong>${type.label}: </strong><ul>`;
                        types.forEach(item => {
                            logDetail += `<li><span>${item}</span></li>`;
                        });
                        logDetail += `</ul><br>`;
                    }
                });
            
                // Statuses
                const statusActions = [
                    { key: "addedStatus", label: "Added/Updated Statuses" },
                    { key: "deletedStatus", label: "Deleted Statuses" }
                ];
            
                statusActions.forEach(status => {
                    const statuses = settings[status.key] || [];
                    if (statuses.length > 0) {
                        logDetail += `<span><strong>${status.label}: </strong><ul>`;
                        statuses.forEach(item => {
                            logDetail += `<li><span>${item}</span></li>`;
                        });
                        logDetail += `</ul><br>`;
                    }
                });
            
                // Allowed File Extensions
                const extensions = settings.fileExtensions || [];
                if (extensions.length > 0) {
                    logDetail += `<span><strong>Allowed File Extensions: </strong></span><ul>`;
                    extensions.forEach(ext => {
                        logDetail += `<li><span>.${ext}</span></li>`;
                    });
                    logDetail += `</ul><br>`;
                }

                // Allowed Drives
                const documentDrives = settings.documentDrives || [];
                if (documentDrives.length > 0) {
                    logDetail += `<span><strong>Allowed Accounts for Document Upload: </strong></span><ul>`;
                    documentDrives.forEach(drive => {
                        logDetail += `<li><span>.${drive}</span></li>`;
                    });
                    logDetail += `</ul><br>`;
                }

                const reportDrives = settings.reportDrives || [];
                if (reportDrives.length > 0) {
                    logDetail += `<span><strong>Allowed Accounts for Report Upload: </strong></span><ul>`;
                    reportDrives.forEach(drive => {
                        logDetail += `<li><span>.${drive}</span></li>`;
                    });
                    logDetail += `</ul><br>`;
                }
            
                $('#logDetails').html(logDetail);
            } else {
                $('#logDetailTitle').html('System Information Details');
                var info = JSON.parse(response.log.detail);
                $('#logDetails').html(`
                    <span><strong>Name: </strong>${info.name}</span><br>
                    <span><strong>Logo: </strong>${info.logo}</span><br>
                    <span><strong>Favicon: </strong>${info.favicon}</span><br>
                    <span><strong>About: </strong>${info.about}</span><br>
                    <span><strong>WMSU Mission: </strong>${info.mission}</span><br>
                    <span><strong>WMSU Vision: </strong>${info.vision}</span><br>
                `);
            }
            
            $('#logInfo').modal('show');
        }
    });
}
