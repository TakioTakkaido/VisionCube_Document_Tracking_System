import { showNotification } from "../../../notification";

// Edit Profile SysInfoName
$('.systemNameBtn').on('click', '#editSysInfoNameBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $("#editSysInfoNameBtn").data('value');

    $('#systemName').prop('disabled', false);

    $('.systemNameBtn').html(`
        <button class="btn btn-primary editSysInfo" id="resetSysInfoNameBtn" data-value="${profileSysInfoName}" style="width: 100%; !important"><i class='bx bx-x' style="font-size: 20px;"></i></button>
    `);
});

// Reset Editing Profile SysInfoName
$('.systemNameBtn').on('click', '#resetSysInfoNameBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $('#resetSysInfoNameBtn').data('value');

    $('#systemName').prop('disabled', true);
    $('#systemName').val(profileSysInfoName);

    $('.systemNameBtn').html(`
        <button class="btn btn-primary editSysInfo" id="editSysInfoNameBtn" data-value="${profileSysInfoName}" style="width: 100%; !important"><i class='bx bx-edit-alt' style="font-size: 20px;"></i></button>
    `);

    $('#saveSysInfoNameBtn').addClass('disabled');
});

// Profile SysInfoName Input
$('#systemName').on('input', function(event){
    $('#saveSysInfoNameBtn').removeClass('disabled');
});

// Update Profile SysInfoName
$('#saveSysInfoNameBtn').on('click', function(event){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'name' : $('#systemName').val()
    }

    $.ajax({
        type: "POST",
        url: window.routes.updateSysInfo,
        data: formData,
        success: function (response) {
            showNotification('System name updated successfully!');
            // Update the header
            $('#topPanelSystemName').html($('#systemName').val());

            // Update the form
            $('#resetSysInfoNameBtn').data('value', $('#systemName').val());

            // Close the form
            $('#resetSysInfoNameBtn').trigger('click');
        },
        error: function(response) {
            showNotification('Error updating name.');
        },
        beforeSend: function(){
            $('.loading').show();
            showNotification('Updating system name...');
        },
        complete: function(){
            $('.loading').hide();
        }
    });
});