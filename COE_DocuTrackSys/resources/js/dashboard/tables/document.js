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
        "<th>Date</th>" +  
        "<th>Sender</th>" +            
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +     
        "<th>Date</th>" + 
        "<th>Sender</th>" +            
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
            {data: 'document_date'},
            {data: 'sender'},
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
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });

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
                        `<button type="button" class="list-group-item" id="viewDocumentVersionsBtn${data.id}">View Document Versions</button>` +
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

                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
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
        "<th>Date</th>" +         
        "<th>Recipient</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Date</th>" +
        "<th>Sender</th>" +            
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
            {data: 'document_date'},
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
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });

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
                        `<button type="button" class="list-group-item" id="viewDocumentVersionsBtn${data.id}">View Document Versions</button>` +
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
                    
                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
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
                $.each($('.popover'), function () { 
                    if ($(this).parent() !== $(row)){
                        $(this).popover('hide');
                    }
                });$('.popover').popover('hide');

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
                        `<button type="button" class="list-group-item" id="viewDocumentVersionsBtn${data.id}">View Document Versions</button>` +
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

                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
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
function viewDocumentVersions(id, row){
    // Hide the popover first
    $(row).popover('hide');

    // Show the modal
    $('#documentVersions').modal('show');

    // Ajax request to get document versions

    $('#documentVersionsTable').DataTable({
        ajax: {
            url: window.routes.showDocumentVersions.replace(':id', id),
            dataSrc: 'documentVersions'
        },
        columns: [
            {data: 'created_at'},
            {data: 'version_number'},
            {
                data: null,
                orderable: false,
                render: function(data, type, row){
                    var content = JSON.parse(data.content);
                    return `<a class="viewDocumentVersionContent" 
                    data-date="${content.document_date}"
                    data-type="${content.type}"
                    data-series="${content.series_number}"
                    data-memo="${content.memo_number}"
                    data-sender="${content.sender}"
                    data-recipient="${content.recipient}"
                    data-subject="${content.subject}"
                    data-assignee="${content.assignee}"
                    data-category="${content.category}"
                    data-status="${content.status}"
                    data-file="${data.file}"

                    href="#">View Document</a>`;
                }
            }
        ],
        destroy: true,
        pagination: true,
        language: {
            emptyTable: "No document versions present."
        },
        order: {
            idx: 1,
            dir: 'desc'
        },

        // $(this).on('click', function(event){

        // });
    })
    
    $('#documentVersionsTable').addClass('show');
}
// View Document Version Content
$('#documentVersionsTable tbody').on('click', 'a.viewDocumentVersionContent', function(event){
    event.preventDefault();
    console.log('okay');
    $('#documentVersionIFrame').attr('src', $(this).data('file') + `#scrollbar=1&toolbar=0`);
    $("#documentVersionDate").html('<strong>Document Date: </strong>'+ $(this).data('date'));
    $("#documentVersionType").html('<strong>Document Type: </strong>'+ $(this).data('type'));

    if ($(this).data('type') == 'Type0') {
        $('#documentVersionMemoInfo').css('display', 'block');
        $('#documentVersionSeriesNo').html('<strong>Series No.: </strong>' + $(this).data('series'));
        $('#documentVersionMemoNo').html('<strong>Memo No.: </strong>' + $(this).data('memo'));
    } else {
        $('#documentVersionMemoInfo').css('display', 'hide');
    }

    $("#documentVersionSender").html('<strong>From: </strong>'+ $(this).data('sender'));
    $("#documentVersionRecipient").html('<strong>To: </strong>'+ $(this).data('recipient'));
    $("#documentVersionSubject").html('<strong>Subject: </strong>'+ $(this).data('subject'));
    $("#documentVersionAssignee").html('<strong>Assignee: </strong>'+ $(this).data('assignee'));
    $("#documentVersionCategory").html('<strong>Category: </strong>'+ $(this).data('category'));
    $("#documentVersionStatus").html('<strong>Status: </strong>'+ $(this).data('status'));

    $('#documentVersions').modal('hide');
    $('#viewDocumentVersion').modal('show');
});

// Document Preview
function documentPreview(id, row = null){
    $.ajax({
        method: "GET",
        url: window.routes.previewDocument.replace(':id', id),
        success: function (response) {
            $('#documentPreviewIFrame').attr('src', response.fileLink + `#scrollbar=1&toolbar=0`);
            
            $("#documentDate").html('<strong>Document Date: </strong>'+ response.document.document_date);
            $("#documentType").html('<strong>Document Type: </strong>'+ response.document.type);

            if (response.document.type == 'Type0') {
                $('#documentMemoInfo').css('display', 'block');
                $('#documentSeriesNo').html('<strong>Series No.: </strong>' + response.document.series_number);
                $('#documentMemoNo').html('<strong>Memo No.: </strong>' + response.document.memo_number);
            } else {
                $('#documentMemoInfo').css('display', 'hide');
            }

            $("#documentVersion").html('<strong>Current Version: </strong>'+ response.document.version);
            $("#documentSender").html('<strong>From: </strong>'+ response.document.sender);
            $("#documentRecipient").html('<strong>To: </strong>'+ response.document.recipient);
            $("#documentSubject").html('<strong>Subject: </strong>'+ response.document.subject);
            $("#documentAssignee").html('<strong>Assignee: </strong>'+ response.document.assignee);
            $("#documentCategory").html('<strong>Category: </strong>'+ response.document.category);
            $("#documentStatus").html('<strong>Status: </strong>'+ response.document.status);

            $(row).popover('hide');
            $('#documentPreview').modal('show');
        }
    });
}

$('#closeDocumentVersion').on('click', function(event){
    event.preventDefault();
    $('#documentVersions').modal('show');
    $('#viewDocumentVersion').modal('hide');
})