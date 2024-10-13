// AJAX Request to Submit Login Form
$(document).on('submit', '#loginForm', function(event){
    event.preventDefault();

    $('#loginBtn').html('Logging In...');

    var formData = new FormData();
    formData = {
        'email' : $('#email').val(),
        'password' : $('#password').val(),
        '_token' : $('#token').val()
    };

    $.ajax({
        method: "POST",
        url: window.routes.login,
        data: formData,
        success: function (){
            console.log('Logged in successfully.');
            $('#loginBtn').html('Log In');
            window.location.href = window.routes.dashboard;
        },
        error: function (data) {
            console.log('Error made in logging in.')
            $('#loginBtn').html('Log In');
            // Get errors
            var data = JSON.parse(data.responseText);
            console.log(data);
            // Change the input field, and
            // Add error text
            if (data.errors.email){
                if ($('#email').hasClass('error-input')) {
                    $('#email').removeClass('error-input');
                }
                $('#email').addClass('error-input');
                $('<span class="error" id="error-email">'+data.errors.email+'</span>').insertAfter('#email');
            }

            if (data.errors.password){
                if ($('#password').hasClass('error-input')) {
                    $('#password').removeClass('error-input');
                }

                $('#password').addClass('error-input');
                $('<span class="error" id="error-password">'+data.errors.password+'</span>').insertAfter('#password');
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

$('#password').on('input' , function(event){
    event.preventDefault();
    
    // Remove errors
    $('#error-password').remove();
    $(this).removeClass('error-input');
});

// Message