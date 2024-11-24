import { showNotification } from "../../../notification";

$('#systemLogo').on('input', function(event){
    $('#saveSystemLogoBtn').removeClass('disabled');
    $('.saveSystemLogo').html($('#systemLogo')[0].files[0].name);
});

$('#saveSystemLogoBtn').on('click', function(event){
    event.preventDefault();
    if(!$(this).hasClass('disabled')){
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('logo', $('#systemLogo')[0].files[0]);  // Ensure we are sending the first selected file
        $.ajax({
            type: "POST",
            url: window.routes.updateSysInfo,
            data: formData,
            processData: false, // Prevent jQuery from processing the data
            contentType: false, // Prevent jQuery from setting contentType for FormData
            success: function (response) {
                showNotification('System logo updated successfully! Please reload to see the changes.');
                // Close the form
                $('#clearSystemLogoBtn').trigger('click');
            },
            error: function(response) {
                showNotification('Error updating system logo.');
            },
            beforeSend: function(){
                $('.loading').show();
                showNotification('Updating system logo...');
            },
            complete: function(){
                $('.loading').hide();
            }
        });
    }
})

$('#clearSystemLogoBtn').on('click', function() {
    // Clear the file input and reset the label
    $('#systemLogo').val('');
    $('.saveSystemLogo').html('Choose file');
    
    // Disable the Save button
    $('#saveSystemLogoBtn').prop('disabled', true);
});