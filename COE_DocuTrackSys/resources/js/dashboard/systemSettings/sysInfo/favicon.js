import { showNotification } from "../../../notification";

$('#systemIcon').on('change', function(event){
    $('#saveSystemIconBtn').removeClass('disabled');
    $('.saveSystemIcon').html($('#systemIcon')[0].files[0].name);
});

$('#saveSystemIconBtn').on('click', function(event){
    event.preventDefault();
    if(!$(this).hasClass('disabled')){
        var formData = new FormData();
        formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
        formData.append('favicon', $('#systemIcon')[0].files[0]);
        console.log(formData);
        $.ajax({
            type: "POST",
            url: window.routes.updateSysInfo,
            data: formData,
            processData: false,  // Prevents jQuery from automatically transforming the data into a query string
            contentType: false,
            success: function (response) {
                showNotification('System icon updated successfully! Please reload to see the changes.');
                $('#clearSystemIconBtn').trigger('click');
            },
            error: function(response) {
                showNotification('Error updating system icon.');
            },
            beforeSend: function(){
                $('.loading').show();
                showNotification('Updating system icon...');
            },
            complete: function(){
                $('.loading').hide();
            }
        });
    }
})

$('#clearSystemIconBtn').on('click', function() {
    // Clear the file input and reset the label
    $('#systemIcon').val('');
    $('.saveSystemIcon').html('Choose file');
    
    // Disable the Save button
    $('#saveSystemIconBtn').addClass('disabled');
});