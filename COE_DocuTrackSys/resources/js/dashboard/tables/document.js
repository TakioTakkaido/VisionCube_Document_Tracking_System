// hinde pa 
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
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
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
            {data: 'sender'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        // createdRow: function(row, data, index){
        //     $(row).on('mouseenter', function(){
        //         document.body.style.cursor = 'pointer';
        //     });

        //     $(row).on('mouseleave', function() {
        //         document.body.style.cursor = 'default';
        //     });

        //     $(row).on('click', function(){
        //         if (!$(row).next().hasClass('document-buttons')){
        //             var editDocumentId = 'edit-document-btn' + data.id;
        //             $(row).after(`
        //                 <tr class="document-buttons">
        //                     <td colspan="6" style="text-align: center;">
        //                         <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
        //                         <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
        //                         <span class="dropdown">    
        //                             <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
        //                                 <i class='bx bxs-file-export'></i> Move Document
        //                             </button>
        //                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                                 <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
        //                                 <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
        //                                 <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
        //                             </div>
        //                         </span>
        //                         <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
        //                     </td>
        //                 </tr>
        //             `);
                    
        //             // Add click event for the dropdown link buttons
        //             const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
        //             dropdownButtons.forEach(btn => {
        //                 btn.addEventListener('click', function(){
        //                     var category = $(this).data('id');
        //                     $.ajax({
        //                         method: "POST",
        //                         url: window.dashboardRoutes.moveDocument
        //                             .replace(':id', data.id)
        //                             .replace(':category', category),
        //                         success: function (response) {
        //                             alert("Document moved successfully!");
        //                             $('#documentTable').DataTable().ajax.reload();
        //                         }
        //                     });
        //                 });
        //             });

        //             // Click event for showing data in document edit
        //             $(document).on('click', '#' + editDocumentId, function(event) {
        //                 event.stopPropagation();
        //                 var documentId = data.id;
        
        //                 $.ajax({
        //                     method: 'GET',
        //                     url: window.dashboardRoutes.showEditDocument.replace(':id', documentId),
        //                     success: function (response) {
        //                         $('#documentId').val(response.document.id);
        //                         $('#ownerId').val(response.document.owner_id);
        //                         $('#editUploadDocType').val(response.document.type);
        //                         $('#editUploadFrom').val(response.document.sender);
        //                         $('#editUploadTo').val(response.document.recipient);
        //                         $('#editUploadSubject').val(response.document.subject);
        //                         $('#fileLink').html(response.document.file);
        //                         $('#editUploadCategory').val(response.document.category);
        //                         $('#editUploadStatus').val(response.document.status);
        //                         $('#editUploadAssignee').val(response.document.assignee);
        //                         $('#pdfIframe').attr('src', response.fileLink);
        //                     },
        //                     error: function (xhr){  
        //                         console.log(xhr.responseJSON);
        //                     }
        //                 });
        //             });
        //         } else {
        //             $(row).next().remove();
        //         }
        //     });
        // },
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
        },
        language: {
            emptyTable: "No incoming documents present."
        }
    });

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// wala na to
export function showAllDocuments(){
    $('.dashboardTableTitle').html('All Documents');

    if ($.fn.DataTable.isDataTable('#dashboardTable')) {
        $('#dashboardTable').DataTable().clear().destroy();
    }

    $('#dashboardTable').html("");

    $('#dashboardTable').html(
        "<thead><tr>" +
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
        "<th>Status</th>" +     
        "<th>Assignee</th>" + 
        "</tr></tfoot>"
    );

    // Get all incoming documents in AJAX
    $('#dashboardTable').DataTable({
        ajax: {
            url: window.dashboardRoutes.showAllDocuments,
            dataSrc: 'documents'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        // createdRow: function(row, data, index){
        //     $(row).on('mouseenter', function(){
        //         document.body.style.cursor = 'pointer';
        //     });

        //     $(row).on('mouseleave', function() {
        //         document.body.style.cursor = 'default';
        //     });

        //     $(row).on('click', function(){
        //         if (!$(row).next().hasClass('document-buttons')){
        //             var editDocumentId = 'edit-document-btn' + data.id;
        //             $(row).after(`
        //                 <tr class="document-buttons">
        //                     <td colspan="6" style="text-align: center;">
        //                         <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
        //                         <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
        //                         <span class="dropdown">    
        //                             <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
        //                                 <i class='bx bxs-file-export'></i> Move Document
        //                             </button>
        //                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                                 <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
        //                                 <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
        //                                 <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
        //                             </div>
        //                         </span>
        //                         <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
        //                     </td>
        //                 </tr>
        //             `);
                    
        //             // Add click event for the dropdown link buttons
        //             const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
        //             dropdownButtons.forEach(btn => {
        //                 btn.addEventListener('click', function(){
        //                     var category = $(this).data('id');
        //                     $.ajax({
        //                         method: "POST",
        //                         url: window.dashboardRoutes.moveDocument
        //                             .replace(':id', data.id)
        //                             .replace(':category', category),
        //                         success: function (response) {
        //                             alert("Document moved successfully!");
        //                             $('#documentTable').DataTable().ajax.reload();
        //                         }
        //                     });
        //                 });
        //             });

        //             // Click event for showing data in document edit
        //             $(document).on('click', '#' + editDocumentId, function(event) {
        //                 event.stopPropagation();
        //                 var documentId = data.id;
        
        //                 $.ajax({
        //                     method: 'GET',
        //                     url: window.dashboardRoutes.showEditDocument.replace(':id', documentId),
        //                     success: function (response) {
        //                         $('#documentId').val(response.document.id);
        //                         $('#ownerId').val(response.document.owner_id);
        //                         $('#editUploadDocType').val(response.document.type);
        //                         $('#editUploadFrom').val(response.document.sender);
        //                         $('#editUploadTo').val(response.document.recipient);
        //                         $('#editUploadSubject').val(response.document.subject);
        //                         $('#fileLink').html(response.document.file);
        //                         $('#editUploadCategory').val(response.document.category);
        //                         $('#editUploadStatus').val(response.document.status);
        //                         $('#editUploadAssignee').val(response.document.assignee);
        //                         $('#pdfIframe').attr('src', response.fileLink);
        //                     },
        //                     error: function (xhr){  
        //                         console.log(xhr.responseJSON);
        //                     }
        //                 });
        //             });
        //         } else {
        //             $(row).next().remove();
        //         }
        //     });
        // },
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
}

// ok na to
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
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
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
            {data: 'sender'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        // createdRow: function(row, data, index){
        //     $(row).on('mouseenter', function(){
        //         document.body.style.cursor = 'pointer';
        //     });

        //     $(row).on('mouseleave', function() {
        //         document.body.style.cursor = 'default';
        //     });

        //     $(row).on('click', function(){
        //         if (!$(row).next().hasClass('document-buttons')){
        //             var editDocumentId = 'edit-document-btn' + data.id;
        //             $(row).after(`
        //                 <tr class="document-buttons">
        //                     <td colspan="6" style="text-align: center;">
        //                         <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
        //                         <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
        //                         <span class="dropdown">    
        //                             <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
        //                                 <i class='bx bxs-file-export'></i> Move Document
        //                             </button>
        //                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                                 <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
        //                                 <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
        //                                 <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
        //                             </div>
        //                         </span>
        //                         <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
        //                     </td>
        //                 </tr>
        //             `);
                    
        //             // Add click event for the dropdown link buttons
        //             const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
        //             dropdownButtons.forEach(btn => {
        //                 btn.addEventListener('click', function(){
        //                     var category = $(this).data('id');
        //                     $.ajax({
        //                         method: "POST",
        //                         url: window.dashboardRoutes.moveDocument
        //                             .replace(':id', data.id)
        //                             .replace(':category', category),
        //                         success: function (response) {
        //                             alert("Document moved successfully!");
        //                             $('#documentTable').DataTable().ajax.reload();
        //                         }
        //                     });
        //                 });
        //             });

        //             // Click event for showing data in document edit
        //             $(document).on('click', '#' + editDocumentId, function(event) {
        //                 event.stopPropagation();
        //                 var documentId = data.id;
        //             });
        //         } else {
        //             $(row).next().remove();
        //         }
        //     });
        // },
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

                if($(this).data('bs.popover')){
                    console.log('has popover');
                    $(this).popover('hide');
                };

                // Preview document
                documentPreview(data.id, row);
                console.log('Document preview');
            });

            // Document menu
            $(row).on('contextmenu',  function(event){
                event.preventDefault();
                console.log('document menu')

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
                    $('#viewDocumentVersionsBtn' + data.id).off('click').on('click', function (event) {
                        event.preventDefault();

                        viewDocumentVersions(id);
                    })
                });
                
                $(this).popover('toggle');

                // Add event listeners after showing the popover
                // $(this).popover()
            });
        }
    });

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

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
        "<th>Status</th>" +     
        "<th>Assignee</th>" +  
        "</tr></thead>" +            
        "<tbody></tbody>" +
        "<tfoot><tr>" + 
        "<th>Type</th>" +
        "<th>Subject</th>" +
        "<th>Sender</th>" +         
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
            {data: 'status'},
            {data: 'assignee'}
        ],
        // createdRow: function(row, data, index){
        //     $(row).on('mouseenter', function(){
        //         document.body.style.cursor = 'pointer';
        //     });

        //     $(row).on('mouseleave', function() {
        //         document.body.style.cursor = 'default';
        //     });

        //     $(row).on('click', function(){
        //         if (!$(row).next().hasClass('document-buttons')){
        //             var editDocumentId = 'edit-document-btn' + data.id;
        //             $(row).after(`
        //                 <tr class="document-buttons">
        //                     <td colspan="6" style="text-align: center;">
        //                         <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
        //                         <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
        //                         <span class="dropdown">    
        //                             <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
        //                                 <i class='bx bxs-file-export'></i> Move Document
        //                             </button>
        //                             <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
        //                                 <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
        //                                 <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
        //                                 <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
        //                             </div>
        //                         </span>
        //                         <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
        //                     </td>
        //                 </tr>
        //             `);
                    
        //             // Add click event for the dropdown link buttons
        //             const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
        //             dropdownButtons.forEach(btn => {
        //                 btn.addEventListener('click', function(){
        //                     var category = $(this).data('id');
        //                     $.ajax({
        //                         method: "POST",
        //                         url: window.dashboardRoutes.moveDocument
        //                             .replace(':id', data.id)
        //                             .replace(':category', category),
        //                         success: function (response) {
        //                             alert("Document moved successfully!");
        //                             $('#documentTable').DataTable().ajax.reload();
        //                         }
        //                     });
        //                 });
        //             });

        //             // Click event for showing data in document edit
        //             $(document).on('click', '#' + editDocumentId, function(event) {
        //                 event.stopPropagation();
        //                 var documentId = data.id;
        
        //                 $.ajax({
        //                     method: 'GET',
        //                     url: window.dashboardRoutes.showEditDocument.replace(':id', documentId),
        //                     success: function (response) {
        //                         $('#documentId').val(response.document.id);
        //                         $('#ownerId').val(response.document.owner_id);
        //                         $('#editUploadDocType').val(response.document.type);
        //                         $('#editUploadFrom').val(response.document.sender);
        //                         $('#editUploadTo').val(response.document.recipient);
        //                         $('#editUploadSubject').val(response.document.subject);
        //                         $('#fileLink').html(response.document.file);
        //                         $('#editUploadCategory').val(response.document.category);
        //                         $('#editUploadStatus').val(response.document.status);
        //                         $('#editUploadAssignee').val(response.document.assignee);
        //                         $('#pdfIframe').attr('src', response.fileLink);
        //                     },
        //                     error: function (xhr){  
        //                         console.log(xhr.responseJSON);
        //                     }
        //                 });
        //             });
        //         } else {
        //             $(row).next().remove();
        //         }
        //     });
        // },
        destroy: true,
        pagination: true,
        language: {
            emptyTable: "No archived documents present."
        },
        createdRow: function(row){
            $(row).on('click', function(event){
                event.preventDefault();
                console.log('Document preview');
                
            });

            $(row).on('contextmenu', function(event){
                event.preventDefault();
                console.log('Right click menu');
            });
            $(row).after(`<div class="list-group">
                    <button type="button" class="list-group-item list-group-item-action active">Edit Document</button>
                    <button type="button" class="list-group-item list-group-item-action">View Document <i class="fa fa-venus-mars" aria-hidden="true"></i></button>
                    <button type="button" class="list-group-item list-group-item-action disabled">Disabled item</button>
                </div>`);
        }
    });

    if (!$('.dashboardTableContents').hasClass('show')) {
        $('.dashboardTableContents').addClass('show');
    }
}

// Edit Document Btn
function editDocument(id){
    $.ajax({
        method: 'GET',
        url: window.routes.showDocument.replace(':id', id),
        success: function (response) {
            console.log('show document');
            $('#documentId').val(response.document.id);
            $('#ownerId').val(response.document.owner_id);
            $('#editUploadDocType').val(response.document.type);
            $('#editUploadFrom').val(response.document.sender);
            $('#editUploadTo').val(response.document.recipient);
            $('#editUploadSubject').val(response.document.subject);
            $('#fileLink').html(response.document.file);
            $('#editUploadCategory').val(response.document.category);
            $('#editUploadStatus').val(response.document.status);
            $('#editUploadAssignee').val(response.document.assignee);
            $('#pdfIframe').attr('src', response.fileLink);

            $('#editDocument').modal('show');
        },
        error: function (xhr){  
            console.log(xhr.responseJSON);
        }
    });
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