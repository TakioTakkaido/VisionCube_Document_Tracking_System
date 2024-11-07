import { showNotification } from "../../../notification";

// Edit Profile Email
$('.profileEmailBtn').on('click', '#editEmailBtn', function(event){
    event.preventDefault();

    var profileEmail = $("#editEmailBtn").data('value');

    $('#editProfileEmail').prop('disabled', false);

    $('.profileEmailBtn').html(`
        <button class="btn btn-primary editProfile" id="resetEmailBtn" data-value="${profileEmail}"><i class='bx bx-x' style="font-size: 20px;"></i></button>
    `);
});

// Reset Editing Profile Email
$('.profileEmailBtn').on('click', '#resetEmailBtn', function(event){
    event.preventDefault();

    var profileEmail = $('#resetEmailBtn').data('value');

    $('#editProfileEmail').prop('disabled', true);
    $('#editProfileEmail').val(profileEmail);

    $('.profileEmailBtn').html(`
        <button class="btn btn-primary editProfile" id="editEmailBtn" data-value="${profileEmail}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i></button>
    `);

    $('#saveEmailBtn').addClass('disabled');
    $('#verifyEmailBtn').removeClass('disabled');
});

// Profile Email Input
$('#editProfileEmail').on('input', function(event){
    $('#saveEmailBtn').removeClass('disabled');
    $('#verifyEmailBtn').addClass('disabled');
});

// Update Profile Email
$('#saveEmailBtn').on('click', function(event){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'email' : $('#editProfileEmail').val()
    }

    $.ajax({
        type: "POST",
        url: window.routes.editProfileEmail,
        data: formData,
        success: function (response) {
            showNotification('Success', 'Email updated successfully!');
            // Update the header
            $('#displayProfileEmail').html($('#editProfileEmail').val());

            // Update the display profile
            $('#topPanelName').html(response.email);

            // Update the form
            $('#resetEmailBtn').data('value', $('#editProfileEmail').val());

            // Close the form
            $('#resetEmailBtn').trigger('click');
        },
        error: function(response) {
            showNotification('Error', 'Error updating email.');

            if (response.responseJSON.errors.email){
                $('#profileEmailError').html(response.responseJSON.errors.type);
                $('#profileEmailError').css('display', 'block');
                $('#editProfileEmail').css('border', '1px solid red');
                $('#editProfileEmail').css('background-color', '#f09d9d');
            }
        }
    });
});

// Verify Profile Email
$('#verifyEmailBtn').on('click', function(event){
    $.ajax({
        type: "GET",
        url: window.routes.verifyEmail,
        success: function (response) {
            showNotification('Success', 'Verification link sent to '+ $('#editProfileEmail').val() + '!');
        },
        error: function(response) {
            showNotification('Error', 'Error sending verification link'+ $('#editProfileEmail').val() +'.');

            if (response.responseJSON.errors.email){
                $('#profileEmailError').html(response.responseJSON.errors.type);
                $('#profileEmailError').css('display', 'block');
                $('#editProfileEmail').css('border', '1px solid red');
                $('#editProfileEmail').css('background-color', '#f09d9d');
            }
        }
    });
});