import { showNotification } from "../../../notification";

// Edit Profile Name
$('.profileNameBtn').on('click', '#editNameBtn', function(event){
    event.preventDefault();

    var profileName = $("#editNameBtn").data('value');

    $('#editProfileName').prop('disabled', false);

    $('.profileNameBtn').html(`
        <button class="btn btn-primary editProfile" id="resetNameBtn" data-value="${profileName}" style="width: 100% !important;"><i class='bx bx-x' style="font-size: 20px;"></i></button>
    `);
});

// Reset Editing Profile Name
$('.profileNameBtn').on('click', '#resetNameBtn', function(event){
    event.preventDefault();

    var profileName = $('#resetNameBtn').data('value');

    $('#editProfileName').prop('disabled', true);
    $('#editProfileName').val(profileName);

    $('.profileNameBtn').html(`
        <button class="btn btn-primary editProfile" id="editNameBtn" data-value="${profileName}" style="width: 100% !important;"><i class='bx bx-edit-alt' style="font-size: 20px;"></i></button>
    `);

    $('#saveNameBtn').addClass('disabled');
});

// Profile Name Input
$('#editProfileName').on('input', function(event){
    $('#saveNameBtn').removeClass('disabled');
});

// Update Profile Name
$('#saveNameBtn').on('click', function(event){
    if (!$(this).hasClass('disabled')){
        var formData = new FormData();
        formData = {
            '_token' : $('meta[name="csrf-token"]').attr('content'),
            'name' : $('#editProfileName').val()
        }

        $.ajax({
            type: "POST",
            url: window.routes.editProfileName,
            data: formData,
            success: function (response) {
                showNotification('Profile name updated successfully!');
                // Update the header
                $('#displayProfileName').html($('#editProfileName').val());

                // Update the display profile
                $('#topPanelName').html(response.name);

                // Update the form
                $('#resetNameBtn').data('value', $('#editProfileName').val());

                // Close the form
                $('#resetNameBtn').trigger('click');
            },
            error: function(response) {
                showNotification('Error updating name.');

                if (response.responseJSON.errors.name){
                    $('#profileNameError').html(response.responseJSON.errors.type);
                    $('#profileNameError').css('display', 'block');
                    $('#editProfileName').css('border', '1px solid red');
                    $('#editProfileName').css('background-color', '#f09d9d');
                }
            },
            beforeSend: function(){
                $('.loading').show();
                showNotification('Updating profile name...');
            },
            complete: function(){
                $('.loading').hide();
            }
        });
    }
});