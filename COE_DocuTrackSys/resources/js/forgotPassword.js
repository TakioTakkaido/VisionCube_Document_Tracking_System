import { showNotification } from "./notification";
// AJAX Request to Submit Forgot Password Form
$(document).on('submit', '#forgotPasswordForm', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'email' : $('#email').val(),
        '_token' : $('meta[name="csrf-token"]').attr('content')
    }

    $('#sendResetPasswordBtn').html('Sending Reset Password Link...');
    $('#sendResetPasswordBtn').prop('disabled', true);
    $.ajax({    
        method: "POST",
        url: window.routes.sendResetPasswordLink,
        data: formData,
        success: function (data){
            showNotification('Reset password link sent to ' + $('#email').val() + '!');
        },
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);
            console.log(data)
            showNotification('Error in sending reset password link.');
            // Change the input field, and
            // Add error text
            if (data.errors.email){
                $('#email').css('border', '1px solid red');
                $('#email').css('background-color', '#f09d9d');

                $('#error-email').html(data.errors.email);
                $('#error-email').css('display', 'block');
            }
        },
        complete: function(){
            $('#sendResetPasswordBtn').prop('disabled', false);
            $('#sendResetPasswordBtn').html('Send Reset Password Link');
        }
    });
});

$('#email').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-email').html();
    $('#error-email').css('display', 'none');

    $(this).css('border', '2px solid rgba(255, 255, 255, .2)');
    $(this).css('background-color', 'transparent');
});

// RESET PASSWORD
$('#resetPasswordBtn').on('click', function(event){
    event.preventDefault();
    var formData = new FormData();
    $(this).prop('disabled', true);
    $(this).html('Resetting Password...');

    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'reset_password_token' : $('#passwordReset').data('token'),
        'password' : $('#password').val(),
        'password_confirmation' : $('#confirmPassword').val()
    }

    $.ajax({    
        method: "POST",
        url: window.routes.resetPassword,
        data: formData,
        success: function (data){
            showNotification('Password successfully resetted!');
            window.location.href = window.routes.confirm;
        },
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);
            showNotification('Error in resetting password.');
            console.log(data);
            // Change the input field, and
            // Add error text
            if (data.errors.password.length > 1){
                $('#password').css('border', '1px solid red');
                $('#password').css('background-color', '#f09d9d');

                $('#error-password').html(data.errors.password[0]);
                $('#error-password').css('display', 'block');

                $('#confirmPassword').css('border', '1px solid red');
                $('#confirmPassword').css('background-color', '#f09d9d');

                $('#error-password_confirmation').html(data.errors.password[1]);
                $('#error-password_confirmation').css('display', 'block');
            } else {
                $('#password').css('border', '1px solid red');
                $('#password').css('background-color', '#f09d9d');

                $('#error-password').html(data.errors.password);
                $('#error-password').css('display', 'block');
            }

            if (data.errors.password_confirmation){
                $('#confirmPassword').css('border', '1px solid red');
                $('#confirmPassword').css('background-color', '#f09d9d');

                $('#error-password_confirmation').html(data.errors.password_confirmation);
                $('#error-password_confirmation').css('display', 'block');
            }
        },
        complete: function(){
            $('#resetPasswordBtn').prop('disabled', false);
            $('#resetPasswordBtn').html('Reset Password');
        }
    });
})

$('#password').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-password').html();
    $('#error-password').css('display', 'none');

    $(this).css('border', '2px solid rgba(255, 255, 255, .2)');
    $(this).css('background-color', 'transparent');
});

$('#confirmPassword').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-password_confirmation').html();
    $('#error-password_confirmation').css('display', 'none');

    $(this).css('border', '2px solid rgba(255, 255, 255, .2)');
    $(this).css('background-color', 'transparent');
});

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