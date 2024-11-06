import { showNotification } from "../../notification";
import { updateDocument } from "../editForm";
import { getDocumentStatistics } from "../homepage/documentStatistics";

var update = false;
// SHOW INCOMING DOCUMENTS
export function showDocument(category){
    $('.dashboardTableTitle').html(category + ' Documents');
    
    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    // Get all documents in AJAX
    if (category === "Incoming"){
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
                dataSrc: 'documents'
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {data: 'document_date'},
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
                            documentPreview(data.document_id);
                            update = true;
                        });
                        
                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
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
                dataSrc: 'documents'
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {data: 'document_date'},
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
                        </div>
                    `;
                    
                    if (selectedRows.length > 1){
                        popoverContent = `
                        <div class="list-group menu p-0">
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveIncomingAll${data.document_id}">
                                <i class='bx bxs-file-export' style="font-size: 15px;"></i>  Move All To Incoming</div>
                            <div class="list-group-item py-1 px-2 rightClickListItem" id="moveArchivedAll${data.document_id}">
                                <i class='bx bxs-file-archive' style="font-size: 15px;"></i>  Archive All</div>
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
                        $('#updateDocumentBtn' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            documentPreview(data.document_id);
                            update = true;
                        });
    
                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
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
                dataSrc: 'documents'
            },
            columns: [
                {data: null, orderable: false, searchable: false, render: DataTable.render.select()},
                {data: 'document_date'},
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
                            deleteDocument(data.document_id);
                        });

                        $('#deleteDocumentAll' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
                            deleteAllDocument(selectedRows);
                        });

                        $('#viewAttachments' + data.document_id).off('click').on('click', function(event) {
                            event.stopPropagation();
                            $(row).popover('toggle');
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

    }

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// Move Document Dropdown
function moveDocument(id, location, row){
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
        }
    });
}

// Move All Document Dropdown
function moveAllDocument(ids, location, row){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'ids[]' : ids,
        'category' : location
    }

    console.log(formData);

    $.ajax({
        method: "POST",
        url: window.routes.moveAllDocuments,
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            $(row).popover('hide');
            showNotification('Success', "Documents moved to " + location + " successfully!");
        },
        error: function(response) {
            $(row).popover('hide');
            showNotification('Error', "Documents moved to " + location + " successfully!");
        }
    });
}

// Document Preview
export function documentPreview(id, attachment = false){
    $.ajax({
        method: "GET",
        url: window.routes.previewDocument.replace(':id', id),
        success: function (response) {
            console.log(response.document.category);
            if (response.document.category === "Trash"){
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
            
        }
    });
}

// Document Info Button Event Listeners
$('#restoreDocumentMenuBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');

    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    
    restoreDocument(id);
});

$('#updateDocumentMenuBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');

    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    
    updateDocument(id);
});

$('#viewDocumentHistoryBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');

    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    viewDocumentVersions(id);
});

$('#viewDocumentAttachmentsBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');

    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    viewAttachments(id);
});

// View Document Versions
function viewDocumentVersions(id){
    $('.documentVersion').removeClass('disabled');

    $.ajax({
        type: "GET",
        url: window.routes.showDocumentVersions.replace(':id', id),
        success: function (response) {
            // Clear document info list
            $('#documentInfoTitle').html(`Version History`);
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
        }
    });
}

$(document).on('click', '.documentVersion', function(event){
    event.preventDefault();

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
            console.log(response);
            if (response.version.series_number === "undefined" || response.version.series_number === null){
                response.version.series_number = "N/A";
                response.version.previous_series_number = "N/A";
            }

            if (response.version.memo_number === "undefined" || response.version.memo_number === null){
                response.version.memo_number = "N/A";
                response.version.previous_memo_number = "N/A";
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
        }
    });
})

// View Attachments
function viewAttachments(id){
    $('.documentAttachment').removeClass('disabled');

    $.ajax({
        type: "GET",
        url: window.routes.showAttachments.replace(':id', id),
        success: function (response) {
            // Clear document info list
            $('#documentInfoTitle').html(`Attachments`);
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
        }
    });
}

$(document).on('click', '.documentAttachment', function(event){
    event.preventDefault();

    $('.documentInfo-active').removeClass('documentInfo-active');
    $(this).addClass('documentInfo-active');

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
        }
    });
})

// Restore Document
function restoreDocument(id, rightClick = false){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : id
    }

    $.ajax({
        type: "POST",
        url: window.routes.restoreDocument.replace(':id', id),
        data: formData,
        success: function (response) {
            getDocumentStatistics();
            
            if(!rightClick){
                documentPreview(id);
            }

            $('#dashboardTable').DataTable().ajax.reload();
            showNotification('Success', "Restored successfully!");
        },
        error: function(response){
            console.log(response);
        }
    });
}

function restoreAllDocument(ids){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'ids[]' : ids
    }

    $.ajax({
        method: "POST",
        url: window.routes.restoreAllDocument,
        data: formData,
        success: function (response) {
            $('#dashboardTable').DataTable().ajax.reload();
            showNotification('Success', "Documents restored successfully!");
        },
        error: function(response) {
            showNotification('Error', "Documents restored successfully!");
        }
    });
}

function deleteDocument(id){
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
            showNotification('Success', "Deleted successfully!");
        },
        error: function(response){
            console.log(response);
        }
    });
}

function deleteAllDocument(ids){
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
            showNotification('Success', "Documents deleted successfully!");
        },
        error: function(response) {
            showNotification('Error', "Documents deleted successfully!");
        }
    });
}