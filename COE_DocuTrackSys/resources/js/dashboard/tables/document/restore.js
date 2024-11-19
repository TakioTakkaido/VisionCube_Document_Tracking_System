import { showNotification } from "../../../notification";
import { getNewDocuments } from "../../homepage";

$('#restoreDocumentMenuBtn').on('click', function(event){
    event.preventDefault();
    var id = $(this).data('id');
    $('.loading').show();
    $('#restoreDocumentMenuBtn').prop('disabled', true);
    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);
    $('#documentInfoTitle').html(`
        <h5 class="text-left m-0 ml-2">Version History</h5>
            <div class="spinner-border spinner-border-sm text-muted ml-2" role="status">
        </div>
    `);
    restoreDocument(id);
});

// Restore Document
export function restoreDocument(id, rightClick = false){
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
            showNotification("Restored successfully!");
        },
        error: function(response){
            console.log(response);
            showNotification("Document cannot be restored, or not found.");
        },
        beforeSend: function(){
            showNotification("Restoring document...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto');
            getNewDocuments();
        }
    });
}

export function restoreAllDocument(ids){
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
            showNotification("Documents restored successfully!");
        },
        error: function(response) {
            console.log(response);
            showNotification("Documents cannot be restored, or not found.");
        },
        beforeSend: function(){
            showNotification("Restoring documents...");
            $('body').css('cursor', 'progress')
        },
        complete: function(){
            $('body').css('cursor', 'auto');
            getNewDocuments();
        }
    });
}
