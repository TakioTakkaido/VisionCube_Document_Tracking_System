import { showNotification } from "../../../notification";
import { updateDocument } from "../../editForm";
import { getNewDocuments } from "../../homepage";

export var update = false;
// SHOW INCOMING DOCUMENTS
export function showDocument(category){
    $('#tableOverlay').hide();
    $('#archivedTitle').hide();
    $('#archivedDatePicker').hide();

    $('.dashboardTableTitle').html(category + ' Documents');
    
    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    // Get all documents in AJAX
    if (category === "Incoming"){
        $('#archivedTitle').hide();
        $('#archivedDatePicker').hide();
        $('#dashboardTable').html(
            "<thead><tr>" +
            "<th></th>" + 
            "<th>Date</th>" +  
            "<th>Type</th>" +
            "<th>Subject</th>" +   
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" +  
            "</tr></thead>" +            
            "<tbody></tbody>" +
            "<tfoot><tr>" + 
            "<th></th>" +
            "<th>Date</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +     
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" + 
            "</tr></tfoot>"
        );

        var table = $('#dashboardTable').DataTable({
            ajax: {
                url: window.routes.showDocuments.replace(':id', category),
                dataSrc: 'documents',
                beforeSend: function(){
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                }
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {
                    data: 'created_at',
                    render: function (data){ return moment(data.created_at).format('MMM DD, YYYY')}
                },
                {data: 'type'},
                {data: 'subject'},
                {data: 'sender'},
                {
                    data: 'status',
                    render: function(data, type, row) {
                        return `<span style="background-color: ${row.color}; padding: 5px; border-radius: 4px;">${data}</span>`;
                    }
                },
                {data: 'assignee'}
            ],
            destroy: true,
            pagination: true,
            language: {
                emptyTable: "No documents present."
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            autoWidth: false,
            createdRow: function(row, data) {
                if(data.newUpdate || data.newUpload){
                    $(row).css('font-weight', 'bold');
                }

                $(row).on('mouseenter', function(){
                    document.body.style.cursor = 'pointer';
                });
    
                $(row).on('mouseleave', function() {
                    document.body.style.cursor = 'default';
                });
    
                $(row).on('click', function(event) {
                    if ($(event.target).is('.dt-select-checkbox') || $(event.target).is('.dt-select')) {
                        return;
                    }
                    event.preventDefault();
                    $(row).popover('hide');
                    $(row).css('font-weight', 'normal');
                    seenDocument(data.document_id);
                    documentPreview(data.document_id);
                });
    
                $(row).on('contextmenu', function(event) {
                    event.preventDefault();
                    $(row).css('background-color', 'white');
                    seenDocument(data.document_id);
                    var selectedRows = $('#dashboardTable').DataTable().rows({ selected: true }).data();

                    // Extract the 'id' from each selected row and convert it into an array
                    var selectedRows = selectedRows.map(function(rowData) {
                        return rowData.document_id;  // Assuming 'id' is the property in the row data
                    }).toArray();

                    $.each($('.popover'), function () { 
                        if ($(this).parent() !== $(row)){
                            $(this).popover('hide');
                        }
                    });
                    
                    var popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="updateDocumentBtn${data.document_id}">
                                <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Update</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveOutgoing${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move to Outgoing</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewAttachments${data.document_id}">
                                <i class='bx bx-paperclip' style="font-size: 15px;"></i>  View Attachments</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchived${data.document_id}">
                                <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrash${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsRead${data.document_id}">
                                <i class='bx bx-envelope-open' style="font-size: 15px;"></i> Mark As Read</div>
                        </div>
                    `;

                    if (selectedRows.length > 1){
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveOutgoingAll${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move All To Outgoing</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchivedAll${data.document_id}">
                                <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrashAll${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsReadAll${data.document_id}">
                                <i class='bx bx-envelope-open' style="font-size: 15px;"></i> Mark All As Read</div>
                        </div>
                    `}

                    // Determine the content of the document per the category    
                    $(this).popover({
                        content: popoverContent,
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
                        $('#updateDocumentBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id);
                            update = true;
                        });
                        
                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id, true);
                        });

                        $('#viewDocumentVersionsBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            viewDocumentVersions(data.document_id, row);
                        });
    
                        $('#moveOutgoing' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Outgoing', row);
                            }
                        });

                        $('#moveOutgoingAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Outgoing', row);
                            }
                        });
    
                        $('#moveArchived' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Archived', row);
                            }
                        });

                        $('#moveArchivedAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Archived', row);
                            }
                        });

                        $('#moveTrash' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Trash', row);
                            }
                        });

                        $('#moveTrashAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Trash', row);
                            }
                        });

                        $('#markAsRead' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                markAsRead(row, data.document_id);
                            }
                        });

                        $('#markAsReadAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                markAsReadAll(selectedRows);
                            }
                        });
                    });
    
                    $(this).popover('toggle');
    
                    if (!data.canEdit){
                        $('#updateDocumentMenuBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#updateDocumentMenuBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canMove){
                        $('#moveDocumentBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveDocumentBtn' + data.document_id).prop('disabled', true);
                    }
                    
                    if (!data.canArchive){
                        $('#moveArchived' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveArchived' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canDownload){
                        $('#downloadFileBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#downloadFileBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canPrint){
                        console.log('cannot print');
                        // $('#updateDocumentBtn' + data.document_id).css({
                        //     'cursor' : 'not-allowed'
                        // });
                        // $('#updateDocumentBtn' + data.document_id).prop('disabled', true);
                    }
    
                    $(document).off('click.popover').on('click.popover', function(e) {
                        if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                            $(row).popover('hide');  
                        }
                    });
                });
            }
        });
    } else if (category === "Outgoing") {
        $('#archivedTitle').hide();
        $('#archivedDatePicker').hide();
        $('#dashboardTable').html(
            "<thead><tr>" +
            "<th></th>" + 
            "<th>Date</th>" +  
            "<th>Type</th>" +
            "<th>Subject</th>" +   
            "<th>Recipient</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" +  
            "</tr></thead>" +            
            "<tbody></tbody>" +
            "<tfoot><tr>" + 
            "<th></th>" +   
            "<th>Date</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +  
            "<th>Recipient</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" + 
            "</tr></tfoot>"
        );

        $('#dashboardTable').DataTable({
            ajax: {
                url: window.routes.showDocuments.replace(':id', category),
                dataSrc: 'documents',
                beforeSend: function(){
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                }
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {
                    data: 'created_at',
                    render: function (data){ return moment(data.created_at).format('MMM DD, YYYY')}
                },
                {data: 'type'},
                {data: 'subject'},
                {data: 'recipient'},
                {
                    data: 'status',
                    render: function(data, type, row) {
                        return `<span style="background-color: ${row.color}; padding: 5px; border-radius: 4px;">${data}</span>`;
                    }
                },
                {data: 'assignee'}
            ],
            destroy: true,
            pagination: true,
            language: {
                emptyTable: "No documents present."
            },
            order: {
                idx: 1,
                dir: 'desc'
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            autoWidth: false,
            createdRow: function(row, data) {
                if(data.newUpdate || data.newUpload){
                    $(row).css('font-weight', 'bold');
                }

                $(row).on('mouseenter', function(){
                    document.body.style.cursor = 'pointer';
                });
    
                $(row).on('mouseleave', function() {
                    document.body.style.cursor = 'default';
                });
    
                $(row).on('click', function(event) {
                    if ($(event.target).is('.dt-select-checkbox') || $(event.target).is('.dt-select')) {
                        return;
                    }
                    event.preventDefault();
                    $(row).css('font-weight', 'normal');
                    seenDocument(data.document_id);
                    $(row).popover('hide');
                    documentPreview(data.document_id);
                });
    
                $(row).on('contextmenu', function(event) {
                    event.preventDefault();
                    $('.popover').each(function() {
                        $(this).popover('dispose'); // Dispose of the popover
                    });
                    
                    var selectedRows = $('#dashboardTable').DataTable().rows({ selected: true }).data();
                    
                    // Extract the 'id' from each selected row and convert it into an array
                    var selectedRows = selectedRows.map(function(rowData) {
                        return rowData.document_id;  // Assuming 'id' is the property in the row data
                    }).toArray();
                    // Determine the content of the document per the category
                    var popoverContent = ``;
                    
                    if (selectedRows.length > 1){
                        console.log('all');
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveIncomingAll${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move All To Incoming</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchivedAll${data.document_id}">
                                <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrashAll${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsReadAll${data.document_id}">
                                <i class='bx bx-envelope-open' style="font-size: 15px;"></i> Mark All As Read</div>
                        </div>
                    `} else {
                        console.log('one only');
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="updateDocumentBtn${data.document_id}">
                                <i class='bx bx-edit-alt' style="font-size: 15px;"></i>  Update</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveIncoming${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move to Incoming</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewAttachments${data.document_id}">
                                <i class='bx bx-paperclip' style="font-size: 15px;"></i>  View Attachments</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchived${data.document_id}">
                                <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrash${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="markAsRead${data.document_id}">
                                <i class='bx bx-envelope-open' style="font-size: 15px;"></i> Mark As Read</div>
                        </div>
                    `;
                    }
    
                    $(this).popover({
                        content: popoverContent,
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
                        $('#updateDocumentBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id);
                            update = true;
                        });
    
                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id, true);
                        });

                        $('#viewDocumentVersionsBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');

                            viewDocumentVersions(data.document_id, row);
                        });

                        $('#moveIncoming' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Incoming', row);
                            }
                        });
    
                        $('#moveIncomingAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Incoming', row);
                            }
                        });
    
                        $('#moveArchived' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Archived', row);
                            }
                        });

                        $('#moveArchivedAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Archived', row);
                            }
                        });

                        $('#moveTrash' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Trash', row);
                            }
                        });

                        $('#moveTrashAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Trash', row);
                            }
                        });

                        $('#markAsRead' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                markAsRead(row, data.document_id);
                            }
                        });

                        $('#markAsReadAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                markAsReadAll(selectedRows, row);
                            }
                        });
                    });
    
                    $(this).popover('toggle');
    
                    if (!data.canEdit){
                        $('#updateDocumentMenuBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#updateDocumentMenuBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canMove){
                        $('#moveDocumentBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveDocumentBtn' + data.document_id).prop('disabled', true);
                    }
                    
                    if (!data.canArchive){
                        $('#moveArchived' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveArchived' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canDownload){
                        $('#downloadFileBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#downloadFileBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canPrint){
                        console.log('cannot print');
                        // $('#updateDocumentBtn' + data.document_id).css({
                        //     'cursor' : 'not-allowed'
                        // });
                        // $('#updateDocumentBtn' + data.document_id).prop('disabled', true);
                    }
    
                    $(document).off('click.popover').on('click.popover', function(e) {
                        if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                            $(row).popover('dispose');  
                        }
                    });
                });
            }
        });
    } else if (category === "Trash") {
        $('#dashboardTable').html(
            "<thead><tr>" +
            "<th></th>" +    
            "<th>Date</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +      
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" +  
            "</tr></thead>" +            
            "<tbody></tbody>" +
            "<tfoot><tr>" + 
            "<th></th>" +    
            "<th>Date</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +  
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" + 
            "</tr></tfoot>"
        );

        $('#dashboardTable').DataTable({
            ajax: {
                url: window.routes.showDocuments.replace(':id', category),
                dataSrc: 'documents',
                beforeSend: function(){
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                }
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {
                    data: 'created_at',
                    render: function (data){ return moment(data.created_at).format('MMM DD, YYYY')}
                },
                {data: 'type'},
                {data: 'subject'},
                {data: 'sender'},
                {
                    data: 'status',
                    render: function(data, type, row) {
                        return `<span style="background-color: ${row.color}; padding: 5px; border-radius: 4px;">${data}</span>`;
                    }
                },
                {data: 'assignee'}
            ],
            destroy: true,
            pagination: true,
            language: {
                emptyTable: "No documents present."
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: {
                idx: 1,
                dir: 'desc'
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
                    if ($(event.target).is('.dt-select-checkbox') || $(event.target).is('.dt-select')) {
                        return;
                    }
                    event.preventDefault();
                    $(row).popover('hide');
                    documentPreview(data.document_id);
                });
    
                $(row).on('contextmenu', function(event) {
                    event.preventDefault();
                    $.each($('.popover'), function () { 
                        if ($(this).parent() !== $(row)){
                            $(this).popover('hide');
                        }
                    });
                    
                    var selectedRows = $('#dashboardTable').DataTable().rows({ selected: true }).data();

                    // Extract the 'id' from each selected row and convert it into an array
                    var selectedRows = selectedRows.map(function(rowData) {
                        return rowData.document_id;  // Assuming 'id' is the property in the row data
                    }).toArray();

                    // Determine the content of the document per the category
                    var popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="restoreDocument${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Restore Document</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewAttachments${data.document_id}">
                                <i class='bx bx-paperclip' style="font-size: 15px;"></i>  View Attachments</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="deleteDocument${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Delete</div>
                        </div>
                    `;
                    
                    if (selectedRows.length > 1){
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="restoreDocumentAll${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Restore All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="deleteDocumentAll${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Delete All</div>
                        </div>
                    `}

                    $(this).popover({
                        content: popoverContent,
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
                        $('#restoreDocument' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            restoreDocument(data.document_id, true);
                        });

                        $('#restoreDocumentAll' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            restoreAllDocument(selectedRows);
                        });

                        $('#deleteDocument' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $('#confirmDeleteDocumentModal').modal('show');
                            $('#confirmDeleteDocumentModal').data('ids', data.document_id);
                            
                            // deleteDocument(data.document_id);
                        });

                        $('#deleteDocumentAll' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $('#confirmDeleteDocumentModal').modal('show');
                            $('#confirmDeleteDocumentModal').data('ids', selectedRows);
                            // deleteAllDocument(selectedRows);
                        });

                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id, true);
                        });

                        $('#viewDocumentVersionsBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            viewDocumentVersions(data.document_id, row);
                        });
                    });
    
                    $(this).popover('toggle');
    
                    if (!data.canEdit){
                        $('#updateDocumentMenuBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#updateDocumentMenuBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canMove){
                        $('#moveDocumentBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveDocumentBtn' + data.document_id).prop('disabled', true);
                    }
                    
                    if (!data.canArchive){
                        $('#moveArchived' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveArchived' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canDownload){
                        $('#downloadFileBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#downloadFileBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canPrint){
                        console.log('cannot print');
                        // $('#updateDocumentBtn' + data.document_id).css({
                        //     'cursor' : 'not-allowed'
                        // });
                        // $('#updateDocumentBtn' + data.document_id).prop('disabled', true);
                    }
    
                    $(document).off('click.popover').on('click.popover', function(e) {
                        if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                            $(row).popover('hide');  
                        }
                    });
                });
            }
        });
    } else {
        $('#archivedTitle').show();
        $('#archivedDatePicker').show();
        $('#dashboardTable').html(
            "<thead><tr>" +
            "<th></th>" +    
            "<th>Date Archived</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +      
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" +  
            "</tr></thead>" +            
            "<tbody></tbody>" +
            "<tfoot><tr>" + 
            "<th></th>" +    
            "<th>Date Archived</th>" + 
            "<th>Type</th>" +
            "<th>Subject</th>" +  
            "<th>Sender</th>" +            
            "<th>Status</th>" +     
            "<th>Assignee</th>" + 
            "</tr></tfoot>"
        );

        // Custom filtering function for DataTable
        // Define the date filter as a separate function
        function dateFilter(settings, data, dataIndex) {
            var min = $('#archivedDatePicker').datepicker('getDate');
            var date = new Date(data[1]);

            if (!min) return true;

            var minFormatted = moment(min).format('MMMM YYYY');
            var tableFormatted = moment(date).format('MMMM YYYY');

            return minFormatted === tableFormatted;
        }

        // Add the filter when needed
        DataTable.ext.search.push(dateFilter);

        // Remove the filter when needed
        DataTable.ext.search = DataTable.ext.search.filter(function(f) {
            return f !== dateFilter;
        });
        
        // Custom filtering function which will search data in column four between two values
        $('#archivedDatePicker').datepicker({
            format: "MM yyyy",     
            minViewMode: 1,
            maxViewMode: 2,
        }).on('changeDate', function(event) {
            table.draw();
        });

        // DataTables initialisation
        var table = $('#dashboardTable').DataTable({
            ajax: {
                url: window.routes.showDocuments.replace(':id', category),
                dataSrc: 'documents',
                beforeSend: function(){
                    $('.loading').show();
                },
                complete: function(){
                    $('.loading').hide();
                }
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {
                    data: 'created_at',
                    render: function(data, type, row) {
                        return moment(data).format('MMM. DD, YYYY');
                    }
                },
                {data: 'type'},
                {data: 'subject'},
                {data: 'sender'},
                {
                    data: 'status',
                    render: function(data, type, row) {
                        return `<span style="background-color: ${row.color}; padding: 5px; border-radius: 4px;">${data}</span>`;
                    }
                },
                {data: 'assignee'}
            ],
            destroy: true,
            pagination: true,
            language: {
                emptyTable: "No documents present."
            },
            select: {
                style: 'multi',
                selector: 'td:first-child'
            },
            order: {
                idx: 1,
                dir: 'desc'
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
                    if ($(event.target).is('.dt-select-checkbox') || $(event.target).is('.dt-select')) {
                        return;
                    }
                    event.preventDefault();
                    $(row).popover('hide');
                    documentPreview(data.document_id);
                });
    
                $(row).on('contextmenu', function(event) {
                    event.preventDefault();
                    $.each($('.popover'), function () { 
                        if ($(this).parent() !== $(row)){
                            $(this).popover('hide');
                        }
                    });
                    
                    var selectedRows = $('#dashboardTable').DataTable().rows({ selected: true }).data();

                    // Extract the 'id' from each selected row and convert it into an array
                    var selectedRows = selectedRows.map(function(rowData) {
                        return rowData.document_id;  // Assuming 'id' is the property in the row data
                    }).toArray();

                    // Determine the content of the document per the category
                    var popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="restoreDocument${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Unarchive</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="viewAttachments${data.document_id}">
                                <i class='bx bx-paperclip' style="font-size: 15px;"></i>  View Attachments</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrash${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash</div>
                        </div>
                    `;
                    
                    if (selectedRows.length > 1){
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="restoreDocumentAll${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Unarchive All</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveTrashAll${data.document_id}">
                                <i class='bx bx-trash' style="font-size: 15px;"></i>  Trash All</div>
                        </div>
                    `}

                    $(this).popover({
                        content: popoverContent,
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
                        $('#restoreDocument' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            restoreDocument(data.document_id, true);
                        });

                        $('#restoreDocumentAll' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            restoreAllDocument(selectedRows);
                        });

                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            $(row).css('font-weight', 'normal');
                            seenDocument(data.document_id);
                            documentPreview(data.document_id, true);
                        });

                        $('#viewDocumentVersionsBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            viewDocumentVersions(data.document_id, row);
                        });

                        $('#moveTrash' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveDocument(data.document_id, 'Trash', row);
                            }
                        });

                        $('#moveTrashAll' + data.document_id).off('click').on('click', function(event) {
                            event.preventDefault();
                            if(!$(this).hasClass('disabled')){
                                moveAllDocument(selectedRows, 'Trash', row);
                            }
                        });
                    });
    
                    $(this).popover('toggle');
    
                    if (!data.canEdit){
                        $('#updateDocumentMenuBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#updateDocumentMenuBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canMove){
                        $('#moveDocumentBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveDocumentBtn' + data.document_id).prop('disabled', true);
                    }
                    
                    if (!data.canArchive){
                        $('#moveArchived' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#moveArchived' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canDownload){
                        $('#downloadFileBtn' + data.document_id).css({
                            'cursor' : 'not-allowed'
                        });
                        $('#downloadFileBtn' + data.document_id).prop('disabled', true);
                    }
    
                    if (!data.canPrint){
                        console.log('cannot print');
                        // $('#updateDocumentBtn' + data.document_id).css({
                        //     'cursor' : 'not-allowed'
                        // });
                        // $('#updateDocumentBtn' + data.document_id).prop('disabled', true);
                    }
    
                    $(document).off('click.popover').on('click.popover', function(e) {
                        if (!$(e.target).closest(row).length && !$(e.target).closest('.popover').length) {
                            $(row).popover('hide');  
                        }
                    });
                });
            }
        });

        $('#archivedDatePicker').datepicker('setDate', new Date());
    }

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// View Document Versions
export function viewDocumentVersions(id){
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('#loadingDocument').show();
    $('.documentVersion').removeClass('disabled');
    $.ajax({
        type: "GET",
        url: window.routes.showDocumentVersions.replace(':id', id),
        success: function (response) {
            // Clear document info list
            $('#documentInfoTitle').html(`
                <h5 class="text-left m-0 ml-2">Version History</h5>
            `);
            $('#documentInfoSpinner').show();
            $('#documentInfoList').html('');
            // Had new entries
            for (var i = 0; i < response.documentVersions.length; i++) {
                const version = response.documentVersions[i];
                // Add version description
                $('#documentInfoList').append(`
                    <li class="list-group-item container justify-content-between align-items-center border-bottom documentVersion" id="viewDocumentVersion${version.id}" data-id=${version.id}>
                        <div class="row">
                            <div class="col">
                                <span class="text-left mr-auto"><strong>${version.created_at}</strong></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <span class="text-left mr-auto">• <i>${version.description}</i></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <span class="text-left mr-auto">${version.modified_by}</span>
                            </div>
                        </div>
                    </li>
                    `);
            }
            if (update == true){
                $('#updateDocumentMenuBtn').trigger('click');
            } else {
                $('.documentVersion').first().trigger('click');
            }
        },
        error: function(response){
            console.log(response);
        },
        complete: function(response) {
            $('#documentInfoSpinner').hide();
        }
    });
}

$(document).on('click', '.documentVersion', function(event){
    event.preventDefault();
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('.documentInfo-active').removeClass('documentInfo-active');
    $(this).addClass('documentInfo-active');
    
    var id = $(this).data('id');
    
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);

    $.ajax({
        type: "GET",
        url: window.routes.showDocumentVersion.replace(':id', id),
        success: function (response) {
            if (response.version.series_number === "undefined" || response.version.series_number === null){
                response.version.series_number = "N/A";
            }

            if (response.version.previous_memo_number === "undefined" || response.version.previous_memo_number === null){
                response.version.previous_memo_number = "N/A";
            }


            if (response.version.memo_number === "undefined" || response.version.memo_number === null){
                response.version.memo_number = "N/A";
            }

            if (response.version.previous_series_number === "undefined" || response.version.previous_series_number === null){
                response.version.previous_series_number = "N/A";
            }

            $('#documentInfoContainer').html(`
                <div class="card mt-3">
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Subject:</label>
                                <input type="text" class="form-control" disabled value="${response.version.subject}">
                                <span class="mb-3">Previous: ${response.version.previous_subject}</span>
                            </div>
                            <div class="col-12">
                                <div class="row mt-3">
                                    <div class="col">
                                        <label class="font-weight-bold">Series Number:</label>
                                        <input type="text" class="form-control" disabled value="${response.version.series_number}">
                                        <span>Previous: ${response.version.previous_series_number}</span>
                                    </div>
                                    <div class="col">
                                        <label class="font-weight-bold">Memo Number:</label>
                                        <input type="text" class="form-control" disabled value="${response.version.memo_number}">
                                        <span>Previous: ${response.version.previous_memo_number}</span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Document Type:</label>
                                <input type="text" class="form-control" disabled value="${response.version.type}">
                                <span>Previous: ${response.version.previous_type}</span>
                            </div>

                            <div class="col">
                                <label class="font-weight-bold">Document Date:</label>
                                <input type="text" class="form-control" disabled value="${response.version.document_date}">
                                <span>Previous: ${response.version.previous_document_date}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">From:</label>
                                <input type="text" class="form-control" disabled value="${response.version.sender}">
                                <span>Previous: ${response.version.previous_sender}</span>
                            </div>
                            
                            <div class="col">
                                <label class="font-weight-bold">To:</label>
                                <input type="text" class="form-control" disabled value="${response.version.recipient}">
                                <span>Previous: ${response.version.previous_recipient}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Category:</label>
                                <input type="text" class="form-control" disabled value="${response.version.category}">
                                <span>Previous: ${response.version.previous_category}</span>
                            </div>

                            <div class="col">
                                <label class="font-weight-bold">Status:</label>
                                <input type="text" class="form-control" disabled value="${response.version.status}" style="background-color: ${response.version.color}">
                                <span>Previous: ${response.version.previous_status}</span>
                            </div>

                            <div class="col">
                                <label class="font-weight-bold">Assignee:</label>
                                <input type="text" class="form-control" disabled value="${response.version.assignee}">
                                <span>Previous: ${response.version.previous_assignee}</span>
                            </div>
                        </div>

                        <hr>

                        <h6 class="p-0 font-weight-bold" style="font-size: 15px;">Document Details: </h6> 
                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Event Venue:</label>
                                <input type="text" class="form-control" disabled value="${response.version.event_venue}">
                                <span>Previous: ${response.version.previous_event_venue}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Event Description:</label>
                                <input type="text" class="form-control" disabled value="${response.version.event_description}">
                                <span>Previous: ${response.version.previous_event_description}</span>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col">
                                <label class="font-weight-bold">Event Date:</label>
                                <input type="text" class="form-control" disabled value="${response.version.event_date}">
                                <span>Previous: ${response.version.previous_event_date}</span>
                            </div>
                        </div>
                    </div>
                </div>
            `);
            
            $('#documentInfoContainer').show();
            $('#updateDocument').hide();

            $('#restoreDocumentMenuBtn').prop('disabled', false);
            $('#updateDocumentMenuBtn').prop('disabled', false);
            $('#viewDocumentHistoryBtn').prop('disabled', false);
            $('#viewDocumentAttachmentsBtn').prop('disabled', false);
        },
        error: function(response){
            console.log(response);
        },
        beforeSend: function(){
            $('.loading').show();
            $('#loadingDocument').show();
        },
        complete: function(){
            $('.loading').hide();
            $('#loadingDocument').hide();
        }
    });
});

$('#viewDocumentHistoryBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    $('#documentInfoTitle').html(`
        <h5 class="text-left m-0 ml-2">Version History</h5>
            <div class="spinner-border spinner-border-sm text-muted ml-2" role="status">
        </div>
    `);
    viewDocumentVersions(id);

    console.log("clicked view docu history");
});

export function seenDocument(id){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : id
    }

    $.ajax({
        method: "POST",
        url: window.routes.seenDocument,
        data: formData,
        success: function (response) {
            console.log('Document not seen');
        },
        error: function(response) {
            console.log(response);
        },
        complete: function(response) {
            getNewDocuments();
        }
    });
}

// Document Preview
export function documentPreview(id, attachment = false){
    $.ajax({
        method: "GET",
        url: window.routes.previewDocument.replace(':id', id),
        success: function (response) {
            if (response.document.category === "Trash" || response.document.category === "Archived"){
                $('#updateDocumentMenuBtn').css('display', 'none');
                $('#restoreDocumentMenuBtn').css('display', 'inline-block');
            } else {
                $('#updateDocumentMenuBtn').css('display', 'inline-block');
                $('#restoreDocumentMenuBtn').css('display', 'none');
            }
            $('#documentPreviewIFrame').attr('src', response.fileLink + `#scrollbar=1&toolbar=0`);
            
            $("#documentDate").html('<strong>Document Date: </strong>'+ response.document.created_at);
            $("#documentType").html(response.document.type);

            if (response.document.type == 'Memoranda') {
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
            

            $('#documentLastModifiedDate').html(response.document.created_at);
            $('#documentLastModifiedBy').html(response.document.modified_by);

            $('#updateDocumentMenuBtn').data('id', response.document.document_id);
            $('#restoreDocumentMenuBtn').data('id', response.document.document_id);
            $('#viewDocumentHistoryBtn').data('id', response.document.document_id);
            $('#viewDocumentAttachmentsBtn').data('id', response.document.document_id);

            $('#documentPreview').modal('show');

            $('#restoreDocumentMenuBtn').prop('disabled', true);
            $('#updateDocumentMenuBtn').prop('disabled', true);
            $('#viewDocumentHistoryBtn').prop('disabled', true);
            $('#viewDocumentAttachmentsBtn').prop('disabled', true);
            
            if(attachment == true){
                $('#viewDocumentAttachmentsBtn').trigger('click');
            } else {
                $('#viewDocumentHistoryBtn').trigger('click');
            }

            
        },
        beforeSend: function(){
            $('.loading').show();
        }
    });
}

// View Attachments
export function viewAttachments(id){
    $('.documentAttachment').removeClass('disabled');
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('#loadingDocument').show();
    $.ajax({
        type: "GET",
        url: window.routes.showAttachments.replace(':id', id),
        success: function (response) {
            // Clear document info list
            $('#documentInfoTitle').html(`
                <h5 class="text-left m-0 ml-2">Attachments</h5>
            `);
            $('#documentInfoSpinner').show();
            $('#documentInfoList').html('');
            // Had new entries
            for (var i = 0; i < response.attachments.length; i++) {
                const attachment = response.attachments[i];
                // Add version description
                $('#documentInfoList').append(`
                    <li class="list-group-item container justify-content-between align-items-center border-bottom documentAttachment" id="viewAttachment${attachment.id}" data-id=${attachment.id}>
                        <div class="row">
                            <div class="col">
                                <span class="text-left mr-auto"><strong>${attachment.name}</strong></span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col">
                                <span class="text-left mr-auto" style="overflow-x: hidden">• <i>${attachment.created_at}</i></span>
                            </div>
                        </div>
                    </li>
                    `);
            }

            $('.documentAttachment').first().trigger('click');
        },
        error: function(response){
            console.log(response);
        },
        complete: function(response) {
            $('#documentInfoSpinner').hide();
        }
    });
}

$(document).on('click', '.documentAttachment', function(event){
    event.preventDefault();
    $('.loading').show();

    $('.documentInfo-active').removeClass('documentInfo-active');
    $(this).addClass('documentInfo-active');
    
    $('.documentPreviewInfo').hide();

    var id = $(this).data('id');
    
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);

    $.ajax({
        type: "GET",
        url: window.routes.showAttachment.replace(':id', id),
        success: function (response) {
            $('#documentInfoContainer').html(`
                <iframe 
                    id="documentInfoIframe"
                    src=""
                    style="width: 100%; height: 100%; border:none;"> 
                </iframe>`);

            $('#documentInfoIframe').attr('src', response.fileLink);

            $('#documentInfoContainer').show();
            $('#updateDocument').hide();

            $('#restoreDocumentMenuBtn').prop('disabled', false);
            $('#updateDocumentMenuBtn').prop('disabled', false);
            $('#viewDocumentHistoryBtn').prop('disabled', false);
            $('#viewDocumentAttachmentsBtn').prop('disabled', false);
        },
        error: function(response){
            console.log(response);
        },
        beforeSend: function(){
            $('.loading').show();
        },
        complete: function(){
            $('.loading').hide();
            $('#loadingDocument').hide();
            $('#documentInfoSpinner').hide();
        }
    });
});

$('#viewDocumentAttachmentsBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    $('#documentInfoTitle').html(`
        <h5 class="text-left m-0 ml-2">Attachments</h5>
            <div class="spinner-border spinner-border-sm text-muted ml-2" role="status">
        </div>
    `);
    viewAttachments(id);
});

// Delete Document
export function deleteDocument(id){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : id
    }

    $.ajax({
        type: "POST",
        url: window.routes.deleteDocument.replace(':id', id),
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            showNotification("Deleted successfully!");
        },
        error: function(response){
            console.log(response);
            showNotification("Document might already be deleted, or us not found.");
        },
        beforeSend: function(){
            showNotification("Deleting document...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto');
            getNewDocuments();
        }
    });
}

export function deleteAllDocument(ids){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'ids[]' : ids
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteAllDocument,
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('hide');
            showNotification("Documents deleted successfully!");
        },
        error: function(response) {
            showNotification("Documents might already be deleted, or not found.");
        },
        beforeSend: function(){
            showNotification("Deleting documents...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto');
            getNewDocuments();
        }
    });
}

$('.confirmDeleteBtn').on('click', function(event){
    event.preventDefault();
    $('.loading').show();
    $('#confirmDeleteDocumentModal').modal('hide');
    console.log($(this).data('ids'));
    if (!$(this).data('ids').length){
        deleteAllDocument($('#confirmDeleteDocumentModal').data('ids'));
    } else {
        deleteDocument($('#confirmDeleteDocumentModal').data('ids'));
    }
});

// Move Document Dropdown
export function moveDocument(id, location, row){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
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
        },
        error: function(response){
            console.log(response);
            showNotification("Document cannot be moved to "+location+", or not found.");
        },
        beforeSend: function(){
            showNotification("Moving document...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto');
            getNewDocuments();
        }
    });
}

// Move All Document Dropdown
export function moveAllDocument(ids, location, row){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'ids[]' : ids,
        'category' : location
    }

    $.ajax({
        method: "POST",
        url: window.routes.moveAllDocuments,
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('hide');
            showNotification("Documents moved to " + location + " successfully!");
        },
        error: function(response) {
            $(row).popover('hide');
            showNotification("Documents cannot be moved to "+location+", or not found.");
        },
        beforeSend: function(){
            showNotification("Moving document...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto')
            getNewDocuments();
        }
    });
}

// Mark Documents are Read
export function markAsRead(row, id){
    $(row).css('font-weight', 'normal');
    var table = $('#dashboardTable').DataTable();
    table.rows().deselect();
    seenDocument(id);
}

export function markAsReadAll(ids){
    $('.selected').css('font-weight', 'normal');
    var table = $('#dashboardTable').DataTable();
    table.rows().deselect();
    for(var i = 0; i < ids.length; i++){
        seenDocument(ids[i]);
    }
    getNewDocuments();
}

$('#updateDocumentMenuBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    $('.loading').show();
    $('.documentPreviewInfo').hide();
    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    $('#documentInfoTitle').html(`
        <h5 class="text-left m-0 ml-2">Version History</h5>
    `);
    updateDocument(id);
});