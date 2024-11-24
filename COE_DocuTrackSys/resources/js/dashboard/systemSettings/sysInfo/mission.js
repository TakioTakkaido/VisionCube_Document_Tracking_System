import { showNotification } from "../../../notification";

// Edit Profile SysInfoName
$('.systemMissionBtn').on('click', '#editSysInfoMissionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $("#editSysInfoMissionBtn").data('value');

    $('#systemMission').prop('disabled', false);

    $('.systemMissionBtn').html(`
        <button class="btn btn-warning editSysInfo mb-2" id="resetSysInfoMissionBtn" data-value="${profileSysInfoName}">Reset WMSU<br>Mission Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoMissionBtn">Change WMSU<br>Mission Page</button>
    `);
});

// Reset Editing Profile SysInfoName
$('.systemMissionBtn').on('click', '#resetSysInfoMissionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $('#resetSysInfoMissionBtn').data('value');

    $('#systemMission').prop('disabled', true);
    $('#systemMission').val(profileSysInfoName);

    $('.systemMissionBtn').html(`
        <button type="button" class="btn btn-primary editSysInfo mb-2" id="editSysInfoMissionBtn" data-value="${profileSysInfoName}">Edit WMSU<br>Mission Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoMissionBtn">Change WMSU<br>Mission Page</button>
    `);

    $('#saveSysInfoMissionBtn').addClass('disabled');
});

// Profile SysInfoName Input
$('#systemMission').on('input', function(event){
    $('#saveSysInfoMissionBtn').removeClass('disabled');
});

// Update Profile SysInfoName
$('.systemMissionBtn').on('click', '#saveSysInfoMissionBtn', function(event){
    event.preventDefault();
    if (!$(this).hasClass('disabled')){
        var formData = new FormData();
        formData = {
            '_token' : $('meta[name="csrf-token"]').attr('content'),
            'mission' : $('#systemMission').val()
        }

        $.ajax({
            type: "POST",
            url: window.routes.updateSysInfo,
            data: formData,
            success: function (response) {
                showNotification('WMSU Mission updated successfully!');
                // Update the mission
                $('#topPanelSystemMission').html($('#systemMission').val());

                // Update the form
                $('#resetSysInfoMissionBtn').data('value', $('#systemMission').val());

                // Close the form
                $('#resetSysInfoMissionBtn').trigger('click');
            },
            error: function(response) {
                showNotification('Error updating WMSU Mission.');
            },
            beforeSend: function(){
                showNotification('Editing WMSU Mission...');
                $('.loading').show();
            },
            complete: function(){
                $('.loading').hide();
            }
        });
    }
});