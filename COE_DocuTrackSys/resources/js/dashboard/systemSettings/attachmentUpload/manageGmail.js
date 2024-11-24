//////////////////////////////////////////////////////////////////
// MANAGING GMAIL ACCOUNT TO COE DTS

import { showNotification } from "../../../notification";
import {  } from "../../uploadForm";

// Search Goglle Account
$('#searchManageDriveAccountEmail').on('input', function(event){
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    $('.noManageDriveAccount').remove();

    var hasVisibleItems = false;
    // Filter the list items based on the search query
    $('.manageDriveAccount').each(function(){
        var typeText = $(this).find('span').html().toLowerCase();
        if (typeText.includes(query)){
            $(this).removeClass('hide');
            hasVisibleItems = true;
        } else {
            $(this).addClass('hide');
        }
    });

    if(!hasVisibleItems){
    $('.manageDriveAccountList').append(`
        <li class="list-group-item p-2 d-flex justify-content-between align-items-center noManageDriveAccount">
            <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
        </li>
    `)
    }
});

// Transfer Attachments
$('.manageDriveAccountList').on('click', '.transferAttachments', function(event){
    event.preventDefault();
    // Get the id
    var driveId = $(this).data('id');

    var formData = new FormData;
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : driveId
    }

    // Ajax req
    $.ajax({
        method: "GET",
        url: window.routes.getTransferEmails,
        data: formData,
        success: function (data) {
            // Create a list of accounts, with a button to confirm that the user would transfer the attachments to that account
            var transferAccountList = "";

            // Attach the list to the confirm transfer modal
            if (data.drives.length != 0){
                for (var i = 0; i < data.drives.length; i++){
                    var drive = data.drives[i];
                    transferAccountList += `
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center transferDriveAccount" id="${"transferDriveAccount" + drive.id}">
                        <span class="text-left mr-auto p-0">${drive.email}</span>
                        
                        <div class="driveAccountBtn mr-2 p-2 btn btn-primary transferAttachmentsBtn"
                            data-id=${drive.id} data-value="${drive.email}">Transfer
                        </div>
                    </li>`;
                }
            } else {
                transferAccountList += `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                    <span class="text-left mr-auto p-0">No other accounts to transfer attachments from.</span>
                </li>`;
            }

            $('.transferDriveAccountList').html(transferAccountList);
            $('#confirmTransferAttachments').modal('show');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error getting Google accounts.');
            console.log(data.errors)
        },
        beforeSend: function(){
            $('.attachmentUploadBtn').addClass('disabled');
            $('.loading').show();
        },
        complete: function(){
            $('.attachmentUploadBtn').removeClass('disabled');
            $('.loading').hide();
        }
    });
})

$('.transferDriveAccountList').on('click', '.transferAttachmentsBtn', function(event){
    event.preventDefault();

    var transferDriveId = $(this).data('id');
    var transferEmail = $(this).data('value');
    var driveId = $('.transferDriveAccountList').data('id');

    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : driveId,
        'transfer_id' : transferDriveId
    }

    $.ajax({
        method: "POST",
        url: window.routes.transferAttachments,
        data: formData,
        success: function (data) {
            showNotification('Transferred all attachments to ' + transferEmail + '!');

            // Enable the remove link button of the email

            // Hide the modal
            $('#confirmTransferAttachments').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error removing Google account.');
            console.log(data.errors)
        },
        beforeSend: function(){
            showNotification('Transferring files to' + transferEmail + "...");
            $('.attachmentUploadBtn').addClass('disabled');
            $('.loading').show();
        },
        complete: function(){
            $('.attachmentUploadBtn').removeClass('disabled');
            $('.loading').hide();
        }
    });
});

// Disable Email
$('.manageDriveAccountList').on('click', '.disableEmail', function(event){
    event.preventDefault();
    // Get the id
    var driveId = $(this).data('id');
    var disableBtn = $(this);
    var formData = new FormData;
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : driveId,
        'disable' : $(this).data('disable')
    }

    // Ajax req
    $.ajax({
        method: "GET",
        url: window.routes.disableEmail,
        data: formData,
        success: function (data) {
            if($(disableBtn).data('disable') == 1){
                showNotification('Disabled Google account successfully!');
                $('.manageDriveAccount .disabledDriveAccount').css('display', 'block');
                $(disableBtn).data('disable', 0);
                $(disableBtn).html(`<i class='bx bxs-user-check' style="font-size: 20px;"></i>`);
            } else {
                showNotification('Reenabled Google account successfully!');
                $('.manageDriveAccount .disabledDriveAccount').css('display', 'none'); 
                $(disableBtn).data('disable', 1);
                $(disableBtn).html(`<i class='bx bxs-user-x' style="font-size: 20px;"></i>`);
            }
            

            // Attach the list to the confirm transfer modal
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            if($(disableBtn).data('disable') == 1){
                showNotification('Error disabling Google account.');
            } else {
                showNotification('Error reenabling Google account.');
            }
            console.log(data.errors)
        },
        beforeSend: function(){
            $('body').css('cursor', 'progress');
            $('.loading').show();
            if($(disableBtn).data('disable') == 1){
                showNotification('Disabling ' + $('#addDriveAccountEmail').val() + "...");
            } else {
                showNotification('Reenabling ' + $('#addDriveAccountEmail').val() + "...");
            }
        },
        complete: function(){
            $('.loading').hide();
            $('body').css('cursor', 'auto');
        }
    });
})

// Remove email
$('.manageDriveAccountList').on('click', '.removeEmail', function(event){
    // Prevent other events
    console.log($(this));
    event.preventDefault();

    $('#deleteManageDriveAccount').data('id', $(this).data('id'));
    $('#confirmDeleteManageDriveAccountText').html("Confirm removing email: " + $(this).data('value'));
});

// Delete type
$('#deleteManageDriveAccount').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;
    var typeId = $(this).data('id');
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : typeId
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.removeEmail,
        data: formData,
        success: function (data) {
            showNotification('Google account removed successfully!');

            // Remove the type in the front end
            $('#driveAccount' + typeId).remove();
            if($('.driveAccount').length == 0){
                $('.driveAccountList').html(`
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center noDriveAccount">
                        <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                    </li>
                `)
            }

            $('#manageDriveAccount' + typeId).remove();
            if($('.manageDriveAccount').length == 0){
                $('.manageDriveAccountList').html(`
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center noManageDriveAccount">
                        <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                    </li>
                `)
            }

            $('#assignDriveAccount' + typeId).remove();
            if($('.assignDriveAccount').length == 0){
                $('.assignDriveAccountList').append(`
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center assignDriveAccount noAssignDriveAccount">
                        <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                    </li>
                `)
            }

            $('#confirmDeleteManageDriveAccount').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error removing Google account.');
            console.log(data.errors)
        },
        beforeSend: function(){
            $('body').css('cursor', 'progress');
            $('.loading').show();
            showNotification('Removing Google account...');
        },
        complete: function(){
            $('.loading').hide();
            $('body').css('cursor', 'auto');
        }
    });
    
});

// Cancel remove email
$('#cancelDeleteManageDriveAccount').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete type
    $('#confirmDeleteManageDriveAccount').modal('hide');
});