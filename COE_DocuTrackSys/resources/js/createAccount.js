// AJAX Request to Submit Create Account Form
$(document).on('submit', '#createAccountForm', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'name' : $('#name').val(),
        'email' : $('#email').val(),
        'password' : $('#password').val(),
        'password_confirmation' : $('#password_confirmation').val(),
        '_token' : $('#token').val()
    }

    $.ajax({    
        method: "POST",
        url: window.routes.create,
        data: formData,
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);

            // Change the input field, and
            // Add error text
            if (data.errors.name){
                $('#name').addClass('error-input');
                $('<span class="error" id="error-name">'+data.errors.name+'</span>').insertAfter('#name');
            }

            if (data.errors.email){
                $('#email').addClass('error-input');
                $('<span class="error" id="error-email">'+data.errors.email+'</span>').insertAfter('#email');
            }

            if (data.errors.password){
                $('#password').addClass('error-input');
                $('<span class="error" id="error-password">'+data.errors.password+'</span>').insertAfter('#password');
            }

            if (data.errors.password_confirmation){
                $('#password_confirmation').addClass('error-input');
                $('<span class="error" id="error-password-confirmation">'+data.errors.password_confirmation+'</span>').insertAfter('#password_confirmation');
            }
        }
    });
});

// Event to remove the error color of the form, and the error message
// when input is being typed
$('#name').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-name').remove();
    $(this).removeClass('error-input');
});

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

$('#password_confirmation').on('input' , function(event){
    event.preventDefault();
    
    // Remove errors
    $('#error-password-confirmation').remove();
    $(this).removeClass('error-input');
});