$('#uploadBtn').on('click', function (event) {
    event.preventDefault();

    $('#uploadDocument').modal('show');
});

$('#submitDocumentForm').on('click', function(event) {
    event.preventDefault();
    var formData = new FormData();

    formData.append('_token', $('#token').val());
    formData.append('type', $('#uploadDocType').val());
    formData.append('subject', $('#uploadSubject').val());
    formData.append('sender', $('#uploadFrom').val());
    formData.append('recipient', $('#uploadTo').val());
    formData.append('assignee', $('#uploadAssignee').val());
    formData.append('category', $('#uploadCategory').val());
    formData.append('status', $('#uploadStatus').val());

    var fileInput = $('#softcopy')[0];
    if (fileInput.files.length > 0) {
        formData.append('file', fileInput.files[0]);  // Correct file append
    }

    $.ajax({
        method: 'POST',
        url: window.routes.uploadDocument,
        data: formData,
        processData: false,  // Prevent jQuery from converting the data
        contentType: false,  // Prevent jQuery from overriding the content type
        success: function(response) {
            alert('Document uploaded successfully');
            $('#uploadDocument').modal('hide');
            // $('#documentTable').DataTable().ajax.reload();
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON?.message || 'Upload failed');
        }
    });
});
