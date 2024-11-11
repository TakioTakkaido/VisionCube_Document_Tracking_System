import { showNotification } from "./notification";
// AJAX Request to Submit Forgot Password Form
$(document).on('submit', '#forgotPasswordForm', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'email' : $('#email').val(),
        '_token' : $('meta[name="csrf-token"]').attr('content')
    }

    $.ajax({    
        method: "POST",
        url: window.routes.sendResetPasswordLink,
        data: formData,
        success: function (data){
            showNotification('Reset password link sent to ' + $('#email').val() + '!');

            $('#resetPasswordEmail').val($('#email').val());
        },
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);
            showNotification('Error in sending reset password link.');
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
// $('#email').on('input' , function(event){
//     event.preventDefault();

//     // Remove errors
//     $('#error-email').remove();
//     $(this).removeClass('error-input');
// });

$('#resetPasswordBtn').on('click', function(event){
    event.preventDefault();
    var formData = new FormData();

    formData = {
        '_token' : $('#token').val(),
        'email' : $('#passwordReset').data('email'),
        'passwordToken' : $('#passwordReset').data('token'),
        'password' : $('#password').val(),
        'password_confirmation' : $('#confirmPassword').val()
    }

    $.ajax({    
        method: "POST",
        url: window.routes.resetPassword,
        data: formData,
        success: function (data){
            showNotification('Password successfully resetted!');
        },
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);
            showNotification('Error in sending reset password link.');
            // Change the input field, and
            // Add error text
            if (data.errors.email){
                $('#email').addClass('error-input');
                $('<span class="error" id="error-email">'+data.errors.email+'</span>').insertAfter('#email');
            }
        }
    });
})
$('.showPassword').on('click', function(event){
    event.preventDefault();

    if ($('#password').prop('type') === "password") {
        $('#password').prop('type', "text");
        $(this).addClass('bx-show');
        $(this).removeClass('bx-hide');
    } else {
        $('#password').prop('type', "password");
        $(this).addClass('bx-hide');
        $(this).removeClass('bx-show');
    }
})

$('.showConfirmPassword').on('click', function(event){
    event.preventDefault();

    if ($('#confirmPassword').prop('type') === "password") {
        $('#confirmPassword').prop('type', "text");
        $(this).addClass('bx-show');
        $(this).removeClass('bx-hide');
    } else {
        $('#confirmPassword').prop('type', "password");
        $(this).addClass('bx-hide');
        $(this).removeClass('bx-show');
    }
})