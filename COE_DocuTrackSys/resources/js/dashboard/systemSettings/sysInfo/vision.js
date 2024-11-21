import { showNotification } from "../../../notification";

// Edit Profile SysInfoName
$('.systemVisionBtn').on('click', '#editSysInfoVisionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $("#editSysInfoVisionBtn").data('value');

    $('#systemVision').prop('disabled', false);

    $('.systemVisionBtn').html(`
        <button class="btn btn-warning editSysInfo" id="resetSysInfoVisionBtn" data-value="${profileSysInfoName}">Reset WMSU Vision Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoVisionBtn">Change WMSU Vision Page</button>
    `);
});

// Reset Editing Profile SysInfoName
$('.systemVisionBtn').on('click', '#resetSysInfoVisionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $('#resetSysInfoVisionBtn').data('value');

    $('#systemVision').prop('disabled', true);
    $('#systemVision').val(profileSysInfoName);

    $('.systemVisionBtn').html(`
        <button type="button" class="btn btn-primary editSysInfo" id="editSysInfoVisionBtn" data-value="${profileSysInfoName}">Edit WMSU Vision Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoVisionBtn">Change WMSU Vision Page</button>
    `);

    $('#saveSysInfoVisionBtn').addClass('disabled');
});

// Profile SysInfoName Input
$('#systemVision').on('input', function(event){
    $('#saveSysInfoVisionBtn').removeClass('disabled');
});

// Update Profile SysInfoName
$('.systemVisionBtn').on('click', '#saveSysInfoVisionBtn' , function(event){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'vision' : $('#systemVision').val()
    }

    $.ajax({
        type: "POST",
        url: window.routes.updateSysInfo,
        data: formData,
        success: function (response) {
            showNotification('WMSU Vision updated successfully!');
            // Update the header
            $('#topPanelSystemVision').html($('#systemMission').val());

            // Update the form
            $('#resetSysInfoVisionBtn').data('value', $('#systemVision').val());

            // Close the form
            $('#resetSysInfoVisionBtn').trigger('click');
        },
        error: function(response) {
            showNotification('Error updating WMSU Vision.');
        }
    });
});