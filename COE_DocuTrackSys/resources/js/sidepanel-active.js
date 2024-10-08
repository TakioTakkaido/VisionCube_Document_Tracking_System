$(document).ready( function () {
    var documentTable = $('documentTable').DataTable();

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $(document).on('click', '#editDocumentBtn', function(event) {
        event.preventDefault();

        var formData = new FormData();

        // Manually append fields from the form to the FormData object
        formData.append('document_id', $('#documentId').val());
        formData.append('type', $('#editUploadDocType').val());
        formData.append('subject', $('#editUploadSubject').val());
        formData.append('sender', $('#editUploadFrom').val());
        formData.append('recipient', $('#editUploadTo').val());
        formData.append('status', $('#editUploadStatus').val());
        formData.append('assignee', $('#editUploadAssignee').val());
        formData.append('category', $('#editUploadCategory').val());

        var fileInput = $('#editSoftcopy')[0];
        if (fileInput.files.length > 0) {
            console.log('hasfile');
            console.log(fileInput.files[0])
            formData.append('file', fileInput.files[0]);
        }

        $.ajax({
            method: 'POST',
            url: window.dashboardRoutes.editDocument,
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                alert('Document updated successfully');
                $('#editDocumentModal').modal('hide');
                $('#documentTable').DataTable().ajax.reload();
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON?.message || 'Update failed');
            }
        });
    });
});

// SUGGESTION ON WHAT TO DO, FEEL FREE TO CHANGE THIS, NEEDS HTML TO DISPLAY THE DOCUMENTS IN THE DASHBOARD
// Show incoming documents
document.getElementById('incoming-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    $('#documentTable').DataTable({
        ajax: {
            url: window.dashboardRoutes.showIncoming,
            dataSrc: 'incomingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        createdRow: function(row, data, index){
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });

            $(row).on('click', function(){
                if (!$(row).next().hasClass('document-buttons')){
                    var editDocumentId = 'edit-document-btn' + data.id;
                    $(row).after(`
                        <tr class="document-buttons">
                            <td colspan="6" style="text-align: center;">
                                <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
                                <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
                                <span class="dropdown">    
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
                                        <i class='bx bxs-file-export'></i> Move Document
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
                                        <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
                                        <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
                                    </div>
                                </span>
                                <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
                            </td>
                        </tr>
                    `);
                    
                    // Add click event for the dropdown link buttons
                    const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
                    dropdownButtons.forEach(btn => {
                        btn.addEventListener('click', function(){
                            var category = $(this).data('id');
                            $.ajax({
                                method: "POST",
                                url: window.dashboardRoutes.moveDocument
                                    .replace(':id', data.id)
                                    .replace(':category', category),
                                success: function (response) {
                                    alert("Document moved successfully!");
                                    $('#documentTable').DataTable().ajax.reload();
                                }
                            });
                        });
                    });

                    // Click event for showing data in document edit
                    $(document).on('click', '#' + editDocumentId, function(event) {
                        event.stopPropagation();
                        var documentId = data.id;
        
                        $.ajax({
                            method: 'GET',
                            url: window.dashboardRoutes.showEditDocument.replace(':id', documentId),
                            success: function (response) {
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
                            },
                            error: function (xhr){  
                                console.log(xhr.responseJSON);
                            }
                        });
                    });
                } else {
                    $(row).next().remove();
                }
            });
        },
        destroy: true,
        pagination: true,
    });   
});
// Show outgoing documents
document.getElementById('outgoing-button').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent default link behavior

    $('#documentTable').DataTable({
        ajax: {
            url: window.dashboardRoutes.showOutgoing,
            dataSrc: 'outgoingDocuments'
        },
        columns: [
            {data: 'type'},
            {data: 'subject'},
            {data: 'sender'},
            {data: 'status'},
            {data: 'assignee'}
        ],
        createdRow: function(row, data, index){
            $(row).on('mouseenter', function(){
                document.body.style.cursor = 'pointer';
            });

            $(row).on('mouseleave', function() {
                document.body.style.cursor = 'default';
            });

            $(row).on('click', function(){
                var editDocumentId = 'edit-document-btn' + data.id;
                    $(row).after(`
                        <tr class="document-buttons">
                            <td colspan="6" style="text-align: center;">
                                <a class="btn btn-primary" href="`+window.dashboardRoutes.downloadUrl.replace(':id', data.id)+`"><i class='bx bxs-download'></i> Download File</a>
                                <button class="btn btn-secondary"><i class='bx bx-history'></i> View Document Versions</button>
                                <span class="dropdown">    
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-expanded="false"">
                                        <i class='bx bxs-file-export'></i> Move Document
                                    </button>
                                    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                        <a class="dropdown-item" data-id="Incoming" href="#">Incoming</a>
                                        <a class="dropdown-item" data-id="Outgoing" href="#">Outgoing</a>
                                        <a class="dropdown-item" data-id="Archived" href="#">Archived</a>
                                    </div>
                                </span>
                                <a class="btn btn-secondary" data-id ="`+data.id+`" data-toggle="modal" data-target="#editDocumentModal" id="`+ editDocumentId +`"><i class='bx bxs-edit'></i> Edit Document</a>
                            </td>
                        </tr>
                    `);
                    
                    // Add click event for the dropdown link buttons
                    const dropdownButtons = document.querySelectorAll('.dropdown-item');
                    
                    dropdownButtons.forEach(btn => {
                        btn.addEventListener('click', function(){
                            var category = $(this).data('id');
                            $.ajax({
                                method: "POST",
                                url: window.dashboardRoutes.moveDocument
                                    .replace(':id', data.id)
                                    .replace(':category', category),
                                success: function (response) {
                                    alert("Document moved successfully!");
                                    $('#documentTable').DataTable().ajax.reload();
                                }
                            });
                        });
                    });

                    // Click event for showing data in document edit
                    $(document).on('click', '#' + editDocumentId, function(event) {
                        event.stopPropagation();
                        var documentId = data.id;
        
                        $.ajax({
                            method: 'GET',
                            url: window.dashboardRoutes.showEditDocument.replace(':id', documentId),
                            success: function (response) {
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
                            },
                            error: function (xhr){  
                                console.log(xhr.responseJSON);
                            }
                        });
                    });
            });
        },
        destroy: true,
        pagination: true,
    }); 
});

// $(document).on('click', '#editDocumentBtn', function(event){
//     event.preventDefault();
//     console.log('submitting');

//     var formData = new FormData();

//     // Manually append fields from the form to the FormData object
//     formData.append('document_id', $('#documentId').val());
//     formData.append('type', $('#editUploadDocType').val());
//     formData.append('subject', $('#editUploadSubject').val());
//     formData.append('sender', $('#editUploadFrom').val());
//     formData.append('recipient', $('#editUploadTo').val());
//     formData.append('status', $('#editUploadStatus').val());
//     formData.append('assignee', $('#editUploadAssignee').val());
//     formData.append('category', $('#editUploadCategory').val()); // Optional field, can be nullable

//     var fileInput = $('#softcopy')[0]; // File input element
//     if (fileInput.files.length > 0) {
//         formData.append('file', fileInput.files[0]); // Append the file only if a file was selected
//     }

//     $.ajax({
//         method: 'POST',
//         url: window.dashboardRoutes.editDocument,
//         data: formData,
//         processData: false,  // Important: prevent jQuery from processing the data
//         contentType: false,
//         success: function (response) {
//             alert('Document updated successfully');
//         },
//         error: function (xhr) {
//             alert('Error: ' + xhr.responseJSON?.message || 'Update failed');
//         }
//     });
// });