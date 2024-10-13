export function showLogs(){
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
            dataSrc: 'logs'
        },
        columns: [
            {data: 'created_at'},
            {data: 'account'},
            {data: 'description'},
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
            console.log('Document preview');
        });

        $(row).on('contextmenu', function(event){
            event.preventDefault();
            console.log('document menu');
        
            $(this).popover({
                content: `<div class="list-group menu">`+
                    `<button type="button" class="list-group-item" id="logsBtn${data.id}">View Logs Information</button>` +
                `</div>`,
                html: true,
                container: 'body',
                placement: 'right',
                trigger: 'manual', 
                animation: false
            }).on('inserted.bs.popover', function(event) {
                $('#logsBtn' + data.id).off('click').on('click', function(event) {
                    $(row).popover('toggle'); 
                    viewLogInformation(data.id); 
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


function viewLogInformation(accountId) {
    console.log(accountId);
}
