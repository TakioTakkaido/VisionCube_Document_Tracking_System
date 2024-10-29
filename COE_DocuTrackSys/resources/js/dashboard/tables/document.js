import { showNotification } from "../../notification";
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
        autoWidth: false,
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

                $(this).popover({
                    content:    `<div class="list-group menu p-0">
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="updateDocumentBtn${data.id}">
                                        <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Update</div>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="moveOutgoing${data.id}">
                                        <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move to Outgoing</div>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="viewAttachments${data.id}">
                                        <i class='bx bxs-file' style="font-size: 15px;"></i>  View Attachments</div>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchived${data.id}">
                                        <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive</div>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="deleteDocument${data.id}">
                                        <i class='bx bx-trash' style="font-size: 15px;"></i>  Delete</div>
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
                    $('#updateDocumentBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        editDocument(data.id);
                    });

                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
                    });

                    $('#moveDocumentBtn' + data.id).off('mouseenter').on('mouseenter', function(event) {
                        setTimeout(function() {
                            $('#moveDocumentDropdown' + data.id).toggleClass('show')
                        }, 300);
                    });

                    $('#moveIncoming' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Incoming', row);
                        }
                    });

                    $('#moveOutgoing' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Outgoing', row);
                        }
                    });

                    $('#moveArchived' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Archived', row);
                        }
                    });
                });

                $(this).popover('toggle');

                if (!data.canEdit){
                    $('#updateDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

                if (!data.canMove){
                    $('#moveDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveDocumentBtn' + data.id).prop('disabled', true);
                }
                
                if (!data.canArchive){
                    $('#moveArchived' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveArchived' + data.id).prop('disabled', true);
                }

                if (!data.canDownload){
                    $('#downloadFileBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#downloadFileBtn' + data.id).prop('disabled', true);
                }

                if (!data.canPrint){
                    console.log('cannot print');
                    // $('#updateDocumentBtn' + data.id).css({
                    //     'cursor' : 'not-allowed'
                    // });
                    // $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

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
        autoWidth: false,
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
                    content:    `<div class="list-group menu p-0">
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="updateDocumentBtn${data.id}">
                                        <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Edit Document</div>
                                    <a class="list-group-item py-1 px-2 rightClickListItem list-group-item-action" id="downloadFileBtn${data.id}" href="${window.routes.downloadDocument.replace(':id', data.id)}">
                                        <i class='bx bxs-download' style="font-size: 15px;"></i>  Download File</a>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="viewDocumentVersionsBtn${data.id}"><i class='bx bx-history' style="font-size: 15px;"></i>  View Document Versions</div>
                                    <div class="dropright p-0">
                                        <div class="list-group-item py-1 px-2 rightClickListItem" id="moveDocumentBtn${data.id}"><i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move Document</div>
                                        <div class="dropdown-menu rightClickDropdown p-0" id="moveDocumentDropdown${data.id}" aria-labelledby="moveDocumentBtn${data.id}">
                                            <a class="dropdown-item ${incoming} rightClickDropdownItem py-1 pl-3" href="#" id="moveIncoming${data.id}">Incoming</a>
                                            <a class="dropdown-item ${outgoing} rightClickDropdownItem py-1 pl-3" href="#" id="moveOutgoing${data.id}">Outgoing</a>
                                            <a class="dropdown-item ${archived} rightClickDropdownItem py-1 pl-3" href="#" id="moveArchived${data.id}">Archived</a>
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
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#updateDocumentBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        editDocument(data.id);
                    });

                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
                    });

                    $('#moveDocumentBtn' + data.id).off('mouseenter').on('mouseenter', function(event) {
                        setTimeout(function() {
                            $('#moveDocumentDropdown' + data.id).toggleClass('show')
                        }, 300);
                    });

                    $('#moveIncoming' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Incoming', row);
                        }
                    });

                    $('#moveOutgoing' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Outgoing', row);
                        }
                    });

                    $('#moveArchived' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Archived', row);
                        }
                    });
                });
                
                $(this).popover('toggle');

                if (!data.canEdit){
                    $('#updateDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

                if (!data.canMove){
                    $('#moveDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveDocumentBtn' + data.id).prop('disabled', true);
                }
                
                if (!data.canArchive){
                    $('#moveArchived' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveArchived' + data.id).prop('disabled', true);
                }

                if (!data.canDownload){
                    $('#downloadFileBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#downloadFileBtn' + data.id).prop('disabled', true);
                }

                if (!data.canPrint){
                    console.log('cannot print');
                    // $('#updateDocumentBtn' + data.id).css({
                    //     'cursor' : 'not-allowed'
                    // });
                    // $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

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
        autoWidth: false,
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
                    content:    `<div class="list-group menu p-0">
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="updateDocumentBtn${data.id}">
                                        <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Edit Document</div>
                                    <a class="list-group-item py-1 px-2 rightClickListItem list-group-item-action" id="downloadFileBtn${data.id}" href="${window.routes.downloadDocument.replace(':id', data.id)}">
                                        <i class='bx bxs-download' style="font-size: 15px;"></i>  Download File</a>
                                    <div class="list-group-item py-1 px-2 rightClickListItem" id="viewDocumentVersionsBtn${data.id}"><i class='bx bx-history' style="font-size: 15px;"></i>  View Document Versions</div>
                                    <div class="dropright p-0">
                                        <div class="list-group-item py-1 px-2 rightClickListItem" id="moveDocumentBtn${data.id}"><i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move Document</div>
                                        <div class="dropdown-menu rightClickDropdown p-0" id="moveDocumentDropdown${data.id}" aria-labelledby="moveDocumentBtn${data.id}">
                                            <a class="dropdown-item ${incoming} rightClickDropdownItem py-1 pl-3" href="#" id="moveIncoming${data.id}">Incoming</a>
                                            <a class="dropdown-item ${outgoing} rightClickDropdownItem py-1 pl-3" href="#" id="moveOutgoing${data.id}">Outgoing</a>
                                            <a class="dropdown-item ${archived} rightClickDropdownItem py-1 pl-3" href="#" id="moveArchived${data.id}">Archived</a>
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
                    trigger: 'manual',
                    animation: false
                }).on('inserted.bs.popover', function(event) {
                    $('#updateDocumentBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        editDocument(data.id);
                    });

                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function(event) {
                        event.stopPropagation();
                        $(row).popover('toggle');
                        viewDocumentVersions(data.id, row);
                    });

                    $('#moveDocumentBtn' + data.id).off('mouseenter').on('mouseenter', function(event) {
                        setTimeout(function() {
                            $('#moveDocumentDropdown' + data.id).toggleClass('show')
                        }, 300);
                    });

                    $('#moveIncoming' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Incoming', row);
                        }
                    });

                    $('#moveOutgoing' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Outgoing', row);
                        }
                    });

                    $('#moveArchived' + data.id).off('click').on('click', function(event) {
                        event.preventDefault();
                        if(!$(this).hasClass('disabled')){
                            moveDocument(data.id, 'Archived', row);
                        }
                    });
                });

                $(this).popover('toggle');

                if (!data.canEdit){
                    $('#updateDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

                if (!data.canMove){
                    $('#moveDocumentBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveDocumentBtn' + data.id).prop('disabled', true);
                }
                
                if (!data.canArchive){
                    $('#moveArchived' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#moveArchived' + data.id).prop('disabled', true);
                }

                if (!data.canDownload){
                    $('#downloadFileBtn' + data.id).css({
                        'cursor' : 'not-allowed'
                    });
                    $('#downloadFileBtn' + data.id).prop('disabled', true);
                }

                if (!data.canPrint){
                    console.log('cannot print');
                    // $('#updateDocumentBtn' + data.id).css({
                    //     'cursor' : 'not-allowed'
                    // });
                    // $('#updateDocumentBtn' + data.id).prop('disabled', true);
                }

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
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('hide');
            showNotification("Document moved to " + location + " successfully!");
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

    
}
// View Document Version Content
function viewDocumentVersion(
    created_at,
    document_date,
    type,
    series_number,
    memo_number,
    sender,
    recipient,
    subject,
    assignee,
    category,
    status,
    file,
    modified_by
){
    $('#documentVersionIFrame').attr('src', file + `#scrollbar=1&toolbar=0`);
    $("#documentVersionModifiedDate").html('<strong>Modified At: </strong>'+ created_at);
    $("#documentVersionModifiedBy").html('<strong>Modified By: </strong>'+ modified_by);
    $("#documentVersionDate").html('<strong>Document Date: </strong>'+ document_date);
    $("#documentVersionType").html('<strong>Document Type: </strong>'+ type);

    if ($(this).data('type') == 'Type0') {
        $('#documentVersionMemoInfo').css('display', 'block');
        $('#documentVersionSeriesNo').html('<strong>Series No.: </strong>' + series_number);
        $('#documentVersionMemoNo').html('<strong>Memo No.: </strong>' + memo_number);
    } else {
        $('#documentVersionMemoInfo').css('display', 'hide');
    }

    $("#documentVersionSender").html('<strong>From: </strong>'+ sender);
    $("#documentVersionRecipient").html('<strong>To: </strong>'+ recipient);
    $("#documentVersionSubject").html('<strong>Subject: </strong>'+ subject);
    $("#documentVersionAssignee").html('<strong>Assignee: </strong>'+ assignee);
    $("#documentVersionCategory").html('<strong>Category: </strong>'+ category);
    $("#documentVersionStatus").html('<strong>Status: </strong>'+ status);

    $('#documentPreview').modal('hide');
    $('#viewDocumentVersion').modal('show');
};

// Document Preview
function documentPreview(id, row = null){
    $.ajax({
        method: "GET",
        url: window.routes.previewDocument.replace(':id', id),
        success: function (response) {
            $('#documentPreviewIFrame').attr('src', response.fileLink + `#scrollbar=1&toolbar=0`);
            
            $("#documentDate").html('<strong>Document Date: </strong>'+ response.document.document_date);
            $("#documentType").html(response.document.type);

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
            $("#documentSubject").html(response.document.subject);
            $("#documentAssignee").html('<strong>Assignee: </strong>'+ response.document.assignee);
            $("#documentCategory").html(response.document.category);
            $("#documentStatus").html('<strong>Status: </strong>'+ response.document.status);

            $('#documentLastModifiedDate').html(response.lastModifiedDate);
            $('#documentLastModifiedBy').html(response.lastModifiedBy);

            $('#documentVersionsTable').DataTable({
                ajax: {
                    url: window.routes.showDocumentVersions.replace(':id', id),
                    dataSrc: 'documentVersions'
                },
                columns: [
                    {data: 'version_number'},
                    {data: 'created_at'},
                    {data: 'modified_by'},
                    {data: null},
                    {
                        data: null,
                        render: function(data, type, row){
                            var content = JSON.parse(data.content);
                            return `<span>${content.status}</span>`;
                        }
                    }
                ],
                destroy: true,
                language: {
                    emptyTable: "No document versions present."
                },
                order: {
                    idx: 0,
                    dir: 'desc'
                },
                layout: {
                    bottom: 'paging',
                    bottomStart: null,
                    bottomEnd: null
                },
                renderer: 'bootstrap',
                responsive: true,
                autoWidth: true,
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
                    });
        
                    $(row).on('contextmenu', function(event) {
                        event.preventDefault();
                        $.each($('.popover'), function () { 
                            if ($(this).parent() !== $(row)){
                                $(this).popover('hide');
                            }
                        });
        
                        $(this).popover({
                            content:    `<div class="list-group menu p-0">
                                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewDocumentVersionBtn${data.id}">
                                                <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  View Document Version</div>
                                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewDocumentVersionAttachments${data.id}">
                                                <i class='bx bxs-file' style="font-size: 15px;"></i>  View Attachments</div>
                                        </div>`,
                            html: true,
                            placement: 'left',
                            template:   `<div class="popover p-0 rightClickList rightClickDocuInfo">
                                            <div class="popover-body p-0">
                                            </div>
                                        </div>`,
                            trigger: 'manual',
                            animation: false
                        }).on('inserted.bs.popover', function(event) {
                            $('#viewDocumentVersionBtn' + data.id).off('click').on('click', function(event) {
                                event.stopPropagation();
                                $(row).popover('toggle');
                                var content = JSON.parse(data.content);
                                viewDocumentVersion(
                                    data.created_at,
                                    content.document_date,
                                    content.type,
                                    content.series_number,
                                    content.memo_number,
                                    content.sender,
                                    content.recipient,
                                    content.subject,
                                    content.assignee,
                                    content.category,
                                    content.status,
                                    data.file,
                                    data.modified_by
                                );
                            });
        
                            $('#viewDocumentVersionAttachments' + data.id).off('click').on('click', function(event) {
                                event.stopPropagation();
                                $(row).popover('toggle');
                                viewDocumentVersionAttachments(data.id);
                            });
                        });
                        
                        $(this).popover('toggle');
                        
                        if (!data.canEdit){
                            $('#updateDocumentBtn' + data.id).css({
                                'cursor' : 'not-allowed'
                            });
                            $('#updateDocumentBtn' + data.id).prop('disabled', true);
                        }
        
                        if (!data.canMove){
                            $('#moveDocumentBtn' + data.id).css({
                                'cursor' : 'not-allowed'
                            });
                            $('#moveDocumentBtn' + data.id).prop('disabled', true);
                        }
                        
                        if (!data.canArchive){
                            $('#moveArchived' + data.id).css({
                                'cursor' : 'not-allowed'
                            });
                            $('#moveArchived' + data.id).prop('disabled', true);
                        }
        
                        if (!data.canDownload){
                            $('#downloadFileBtn' + data.id).css({
                                'cursor' : 'not-allowed'
                            });
                            $('#downloadFileBtn' + data.id).prop('disabled', true);
                        }
        
                        if (!data.canPrint){
                            console.log('cannot print');
                            // $('#updateDocumentBtn' + data.id).css({
                            //     'cursor' : 'not-allowed'
                            // });
                            // $('#updateDocumentBtn' + data.id).prop('disabled', true);
                        }
        
                        $(document).off('click.popover').on('click.popover', function(e) {
                            if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                                $(row).popover('hide');  
                            }
                        });
                    });
                }
            })

            $(row).popover('hide');
            $('#documentPreview').modal('show');
        }
    });
}

function viewAttachments(){

}

function viewDocumentVersionAttachments(){
    
}

$('#closeDocumentVersion').on('click', function(event){
    event.preventDefault();
    $('#documentPreview').modal('show');
    $('#viewDocumentVersion').modal('hide');
})