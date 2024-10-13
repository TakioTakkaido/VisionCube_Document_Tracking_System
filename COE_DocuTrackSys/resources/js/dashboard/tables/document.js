import { editDocument } from "../editForm";

// SHOW INCOMING DOCUMENTS
export function showIncoming(){
    $('.dashboardTableTitle').html('Incoming Documents');
    
    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" + 
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showIncoming,
            dataSrc: 'incomingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'recipient'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        destroy: true,
        pagination: true,
        language: {
            emptyTable: "No incoming documents present."
        },
        createdRow: function(row, data) {
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });


            $(row).on('click', function(event) {
                event.preventDefault();
                $(row).popover('hide');
                documentPreview(data.id, row);
            });


            $(row).on('contextmenu', function(event) {
                event.preventDefault();
                $('.popover').popover('hide');

                var incoming, outgoing, archived;
                switch (data.category) {
                    case 'Incoming':
                        incoming = 'disabled';
                        break;
                    case 'Outgoing':
                        outgoing = 'disabled';
                        break;
                    case 'Archived':
                        archived = 'disabled';
                        break;
                    default:
                        break;
                }

                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="editDocumentBtn${data.id}">Edit Document</button>` +
                        `<a type="button" class="list-group-item list-group-item-action" id="downloadFileBtn${data.id}" href="${window.routes.downloadDocument.replace(':id', data.id)}">Download File</a>` +
                        `<button type="button" class="list-group-item" id="viewDocumentBtn${data.id}">View Document Versions</button>` +
                        `<button type="button" class="list-group-item" id="moveDocumentBtn${data.id}">Move Document</button>` +
                        `<div class="list-group-item dropright">` +
                        `<div class="dropdown-menu" id="moveDocumentDropdown${data.id}" aria-hidden="true">
                            <a class="dropdown-item ${incoming}" href="#" id="moveIncoming${data.id}">Incoming</a>
                            <a class="dropdown-item ${outgoing}" href="#" id="moveOutgoing${data.id}">Outgoing</a>
                            <a class="dropdown-item ${archived}" href="#" id="moveArchived${data.id}">Archived</a>
                        </div>`+
                        `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#editDocumentBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        editDocument(data.id);
                    });

                    $('#moveDocumentBtn' + data.id).off('click').on('click', function(event) {
                        console.log('move');
                        $('#moveDocumentDropdown' + data.id).toggleClass('show');
                    });

                    $('#moveIncoming' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Incoming', row);
                    });

                    $('#moveOutgoing' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Outgoing', row);
                    });

                    $('#moveArchived' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Archived', row);
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

// SHOW OUTGOING DOCUMENTS
export function showOutgoing(){
    $('.dashboardTableTitle').html('Outgoing Documents');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" + 
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showOutgoing,
            dataSrc: 'outgoingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'recipient'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        destroy: true,
        pagination: true,
        language: {
            emptyTable: "No outgoing documents present."
        },
        createdRow: function(row, data){
            // Make cursor upon entering the row
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });

            // Document preview 
            $(row).on('click', function(event){
                event.preventDefault();
                $(row).popover('hide');
                documentPreview(data.id, row);
            });

            // Document menu
            $(row).on('contextmenu', function(event){
                event.preventDefault();
                $('.popover').popover('hide');

                var incoming, outgoing, archived;
                // Determine the status of the document
                switch (data.category) {
                    case 'Incoming':
                        incoming = 'disabled';
                    break;
                    case 'Outgoing':
                        outgoing = 'disabled';
                    break;
                    case 'Archived':
                        archived = 'disabled';
                    break;
                    default:
                        break;
                }
                // Create popover
                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="editDocumentBtn${data.id}">Edit Document</button>` +
                        `<a type="button" class="list-group-item list-group-item-action" id="downloadFileBtn${data.id}" href="${window.routes.downloadDocument.replace(':id', data.id)}">Download File</a>` +
                        `<button type="button" class="list-group-item" id="viewDocumentBtn${data.id}">View Document Versions</button>` +
                        `<button type="button" class="list-group-item" id="moveDocumentBtn${data.id}">Move Document</button>` +
                        `<div class="list-group-item dropright">` +
                        `<div class="dropdown-menu" id="moveDocumentDropdown${data.id}" aria-hidden="true">
                            <a class="dropdown-item ${incoming}" href="#" id="moveIncoming${data.id}">Incoming</a>
                            <a class="dropdown-item ${outgoing}" href="#" id="moveOutgoing${data.id}">Outgoing</a>
                            <a class="dropdown-item ${archived}" href="#" id="moveArchived${data.id}">Archived</a>
                        </div>`+
                        `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function (event) {
                    // Edit document button
                    $('#editDocumentBtn' + data.id).off('click').on('click', function (event) {
                        event.stopPropagation();
                        
                        $(row).popover('toggle');
                        // Edit document Function
                        editDocument(data.id);
                    });
                  
                    // Move document btn
                    $('#moveDocumentBtn' + data.id).off('click').on('click', function(event) {
                        console.log('move');
                        $('#moveDocumentDropdown' + data.id).toggleClass('show');
                    });

                    // Move incoming
                    $('#moveIncoming' + data.id).off('click').on('click', function (event) {
                        moveDocument(data.id, 'Incoming', row);
                    });

                    // Move outgoing
                    $('#moveOutgoing' + data.id).off('click').on('click', function (event) {
                        moveDocument(data.id, 'Outgoing', row);
                    });

                    // Move archived
                    $('#moveArchived' + data.id).off('click').on('click', function (event) {
                        moveDocument(data.id, 'Archived', row);
                    });
                  
                    // View document versions
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

// SHOW ARCHIVED DOCUMENTS
export function showArchived(){
    $('.dashboardTableTitle').html('Archived Documents');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");
    
    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" + 
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.routes.showArchived,
            dataSrc: 'archivedDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'recipient'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        destroy: true,
        pagination: true,
        language: {
            emptyTable: "No archived documents present."
        },
        createdRow: function(row, data) {
            // Custom behavior: cursor change on hover
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });

            // Document preview
            $(row).on('click', function(event) {
                event.preventDefault();
                $(row).popover('hide');
                documentPreview(data.id, row);
            });

            // Document context menu
            $(row).on('contextmenu', function(event) {
                event.preventDefault();
                $('.popover').popover('hide');

                var incoming, outgoing, archived;
                // Determine the status of the document
                switch (data.category) {
                    case 'Incoming':
                        incoming = 'disabled';
                        break;
                    case 'Outgoing':
                        outgoing = 'disabled';
                        break;
                    case 'Archived':
                        archived = 'disabled';
                        break;
                    default:
                        break;
                }

                $(this).popover({
                    content: `<div class="list-group menu">`+
                        `<button type="button" class="list-group-item" id="editDocumentBtn${data.id}">Edit Document</button>` +
                        `<a type="button" class="list-group-item list-group-item-action" id="downloadFileBtn${data.id}" href="${window.routes.downloadDocument.replace(':id', data.id)}">Download File</a>` +
                        `<button type="button" class="list-group-item" id="viewDocumentBtn${data.id}">View Document Versions</button>` +
                        `<button type="button" class="list-group-item" id="moveDocumentBtn${data.id}">Move Document</button>` +
                        `<div class="list-group-item dropright">` +
                        `<div class="dropdown-menu" id="moveDocumentDropdown${data.id}" aria-hidden="true">
                            <a class="dropdown-item ${incoming}" href="#" id="moveIncoming${data.id}">Incoming</a>
                            <a class="dropdown-item ${outgoing}" href="#" id="moveOutgoing${data.id}">Outgoing</a>
                            <a class="dropdown-item ${archived}" href="#" id="moveArchived${data.id}">Archived</a>
                        </div>`+
                        `</div>`,
                    html: true,
                    container: 'body',
                    placement: 'right',
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#editDocumentBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        editDocument(data.id);
                    });

                    $('#moveDocumentBtn' + data.id).off('click').on('click', function(event) {
                        console.log('move');
                        $('#moveDocumentDropdown' + data.id).toggleClass('show');
                    });

                    $('#moveIncoming' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Incoming', row);
                    });

                    $('#moveOutgoing' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Outgoing', row);
                    });

                    $('#moveArchived' + data.id).off('click').on('click', function(event) {
                        moveDocument(data.id, 'Archived', row);
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

// Move Document Dropdown
function moveDocument(id, location, row){
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
        'id' : id,
        'category' : location
    }

    $.ajax({
        method: "POST",
        url: window.routes.moveDocument,
        data: formData,
        success: function (response) {
            console.log("Document moved successfully!");
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('toggle');
        }
    });
}

// View Document Versions
function viewDocumentVersions(id){
    console.log('view document versions');
}

// Document Preview
function documentPreview(id, row){
    $.ajax({
        method: "GET",
        url: window.routes.previewDocument.replace(':id', id),
        success: function (response) {
            $('#documentPreviewIFrame').attr('src', response.fileLink + `#toolbar=0&navpanes=0`);
            console.log($('#documentPreviewIFrame').attr('src'));
            $(row).popover('hide');
            $('#documentPreview').modal('show');
        }
    });
}