import { showNotification } from "../../../notification";

// ADD NEW ACCOUNT
$('#addAccountBtn').on('click', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'name' : $('#name').val(),
        'email' : $('#email').val(),
        'password' : $('#password').val(),
        'password_confirmation' : $('#password_confirmation').val(),
        'role' : $('#role').val(),
        '_token' : $('meta[name="csrf-token"]').attr('content')
    }

    $.ajax({    
        method: "POST",
        url: window.routes.createAccount,
        data: formData,
        success: function (data) {
            showNotification('Account added successfully!');
        },
        error: function (data) {
            // Get errors
            showNotification('Error adding account.');
            var data = JSON.parse(data.responseText);

            // Change the input field, and
            // Add error text
            if (data.errors.name){
                $('#accountNameError').html(data.errors.name);
                $('#name').css('border', '1px solid red');
                $('#name').css('background-color', '#f09d9d');
                $('#accountNameError').css('display', 'block');
            }

            if (data.errors.email){
                $('#accountEmailError').html(data.errors.email);
                $('#email').css('border', '1px solid red');
                $('#email').css('background-color', '#f09d9d');
                $('#accountEmailError').css('display', 'block');
            }

            if (data.errors.password){
                $('#accountPasswordError').html(data.errors.password);
                $('#password').css('border', '1px solid red');
                $('#password').css('background-color', '#f09d9d');
                $('#accountPasswordError').css('display', 'block');
            }

            if (data.errors.password_confirmation){
                $('#accountConfirmPasswordError').html(data.errors.password_confirmation);
                $('#password_confirmation').css('border', '1px solid red');
                $('#password_confirmation').css('background-color', '#f09d9d');
                $('#accountConfirmationPasswordError').css('display', 'block');
            }

            if (data.errors.role) {
                $('#accountRoleError').html(data.errors.role);
                $('#role').css('border', '1px solid red');
                $('#role').css('background-color', '#f09d9d');
                $('#accountRoleError').css('display', 'block');
            }
        }
    });
});

$('#cancelAccountBtn').on('click', function (event){
    $('#addNewAccount').trigger('reset');

    $.each($('.addAccountInput'), function () { 
        $(this).css('border', '1px solid #ccc');
        $(this).css('background-color', 'white');
    });

    $.each($('.error'), function () { 
        $(this).css('display', 'none');
    });
});

$.each($('.addAccountInput'), function (index, value) { 
    $($('.addAccountInput')[index]).on('input', function(event){
        event.preventDefault();
        $(this).css('border', '1px solid #ccc');
        $(this).css('background-color', 'white');
    })
});

// Event to remove the error color of the form, and the error message
// when input is being typed
$('#name').on('input' , function(event){
    event.preventDefault();
    $('#accountNameError').css('display', 'none');
});

$('#email').on('input' , function(event){
    event.preventDefault();
    $('#accountEmailError').css('display', 'none');
});

$('#password').on('input' , function(event){
    event.preventDefault();
    $('#accountPasswordError').css('display', 'none');
});

$('#password_confirmation').on('input' , function(event){
    event.preventDefault();
    $('#accountConfirmPasswordError').css('display', 'none');
});

$('#role').on('input' , function(event){
    event.preventDefault();
    $('#accountRoleError').css('display', 'none');
});