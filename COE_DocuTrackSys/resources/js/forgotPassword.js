// AJAX Request to Submit Forgot Password Form
$(document).on('submit', '#forgotPasswordForm', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'email' : $('#email').val(),
        '_token' : $('#token').val()
    }

    $.ajax({    
        method: "POST",
        url: window.routes.forgotPassword,
        data: formData,
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);

            // Change the input field, and
            // Add error text
            if (data.errors.email){
                $('#email').addClass('error-input');
                $('<span class="error" id="error-email">'+data.errors.email+'</span>').insertAfter('#email');
            }
        }
    });
});

// Event to remove the error color of the form, and the error message
// when input is being typed
$('#email').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-email').remove();
    $(this).removeClass('error-input');
});