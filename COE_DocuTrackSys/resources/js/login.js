// AJAX Request to Submit Login Form
$(document).on('submit', '#loginForm', function(event){
    event.preventDefault();

    $('#loginBtn').html('Logging In...');

    var formData = new FormData();
    formData = {
        'email' : $('#email').val(),
        'password' : $('#password').val(),
        'remember' : $('#remember').is(':checked'),
        '_token' : $('meta[name="csrf-token"]').attr('content')
    };

    $('#loginBtn').prop('disabled', true);
    
    $.ajax({
        method: "POST",
        url: window.routes.login,
        data: formData,
        success: function (){
            $('#loginBtn').prop('disabled', false);
            $('#loginBtn').html('Log In');
            window.location.href = window.routes.dashboard;
        },
        error: function (data) {
            $('#loginBtn').prop('disabled', false);
            $('#loginBtn').html('Log In');

            // Get errors
            var data = JSON.parse(data.responseText);

            // Change the input field, and
            // Add error text
            if (data.errors.email){
                $('#email').css('border', '1px solid red');
                $('#email').css('background-color', '#f09d9d');

                $('#error-email').html(data.errors.email);
                $('#error-email').css('display', 'block');
            }

            if (data.errors.password){
                $('#password').css('border', '1px solid red');
                $('#password').css('background-color', '#f09d9d');

                $('#error-password').html(data.errors.password);
                $('#error-password').css('display', 'block');
            }
        }
    });
});

// Event to remove the error color of the form, and the error message
// when input is being typed
$('#email').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-email').html();
    $('#error-email').css('display', 'none');

    $(this).css('border', '2px solid rgba(255, 255, 255, .2)');
    $(this).css('background-color', 'transparent');
});

$('#password').on('input' , function(event){
    event.preventDefault();
    
    // Remove errors
    $('#error-password').html();
    $(this).css('border', '2px solid rgba(255, 255, 255, .2)');
    $(this).css('background-color', 'transparent');
});

$('#showPassword').on('click', function(event){
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