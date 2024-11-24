import { showNotification } from "../../../notification";

// Edit Profile SysInfoName
$('.systemVisionBtn').on('click', '#editSysInfoVisionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $("#editSysInfoVisionBtn").data('value');

    $('#systemVision').prop('disabled', false);

    $('.systemVisionBtn').html(`
        <button class="btn btn-warning editSysInfo mb-2" id="resetSysInfoVisionBtn" data-value="${profileSysInfoName}">Reset WMSU<br>Vision Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoVisionBtn">Change WMSU<br>Vision Page</button>
    `);
});

// Reset Editing Profile SysInfoName
$('.systemVisionBtn').on('click', '#resetSysInfoVisionBtn', function(event){
    event.preventDefault();

    var profileSysInfoName = $('#resetSysInfoVisionBtn').data('value');

    $('#systemVision').prop('disabled', true);
    $('#systemVision').val(profileSysInfoName);

    $('.systemVisionBtn').html(`
        <button type="button" class="btn btn-primary editSysInfo mb-2" id="editSysInfoVisionBtn" data-value="${profileSysInfoName}">Edit WMSU<br>Vision Page</button>
        <button type="button" class="btn btn-primary disabled editSysInfo" id="saveSysInfoVisionBtn">Change WMSU<br>Vision Page</button>
    `);

    $('#saveSysInfoVisionBtn').addClass('disabled');
});

// Profile SysInfoName Input
$('#systemVision').on('input', function(event){
    $('#saveSysInfoVisionBtn').removeClass('disabled');
});

// Update Profile SysInfoName
$('.systemVisionBtn').on('click', '#saveSysInfoVisionBtn' , function(event){
    event.preventDefault();
    if (!$(this).hasClass('disabled')){
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
            },
            beforeSend: function(){
                showNotification('Editing WMSU Vision...');
                $('.loading').show();
            },
            complete: function(){
                $('.loading').hide();
            }
        });
    }
});