import { showNotification } from "../../../notification";

// Edit Profile SysInfoName
$('.systemAboutBtn').on('click', '#editSysInfoAboutBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $("#editSysInfoAboutBtn").data('value');

    $('#systemAbout').prop('disabled', false);

    $('.systemAboutBtn').html(`
        <button class="btn btn-warning editSysInfo" id="resetSysInfoAboutBtn" data-value="${profileSysInfoName}">Reset WMSU About Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoAboutBtn">Change WMSU About Page</button>
    `);
});

// Reset Editing Profile SysInfoName
$('.systemAboutBtn').on('click', '#resetSysInfoAboutBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $('#resetSysInfoAboutBtn').data('value');

    $('#systemAbout').prop('disabled', true);
    $('#systemAbout').val(profileSysInfoName);

    $('.systemAboutBtn').html(`
        <button type="button" class="btn btn-primary editSysInfo" id="editSysInfoAboutBtn" data-value="${profileSysInfoName}">Edit WMSU About Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoAboutBtn">Change WMSU About Page</button>
    `);

    $('#saveSysInfoAboutBtn').addClass('disabled');
});

// Profile SysInfoName Input
$('#systemAbout').on('input', function(event){
    $('#saveSysInfoAboutBtn').removeClass('disabled');
});

// Update Profile SysInfoName
$('.systemAboutBtn').on('click', '#saveSysInfoAboutBtn' , function(event){
    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'vision' : $('#systemAbout').val()
    }

    $.ajax({
        type: "POST",
        url: window.routes.updateSysInfo,
        data: formData,
        success: function (response) {
            showNotification('About updated successfully!');
            // Update the header
            $('#topPanelSystemAbout').html($('#systemMission').val());

            // Update the form
            $('#resetSysInfoAboutBtn').data('value', $('#systemAbout').val());

            // Close the form
            $('#resetSysInfoAboutBtn').trigger('click');
        },
        error: function(response) {
            showNotification('Error updating About.');
        }
    });
});