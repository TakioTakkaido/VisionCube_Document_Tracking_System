//////////////////////////////////////////////////////////////////
// LINKING GMAIL ACCOUNT TO COE DTS

import { showNotification } from "../../../notification";
import {  } from "../../uploadForm";


// Edit Document Type
$('#addDriveAccountBtn').on('click', function(event){
    event.preventDefault();
    // Prevent other events
    if(!$(this).hasClass('disabled')){
        // Create new form data
        var formData = new FormData();
        // Create form data for submission
        formData = {
            '_token' : $('meta[name="csrf-token"]').attr('content'),
            'email': $('#addDriveAccountEmail').val(),
            'verified_at': null
        }

        // Submit the data form
        $.ajax({
            method: "POST",
            url: window.routes.linkEmail,
            data: formData,
            success: function (data) {
            // Log success message
                showNotification('Verification link sent to ' + $('#addDriveAccountEmail').val() + "!");
                var newTypeId = data.drive.id;
                // Update list group
                // Append a new list item to the list group
                var newListItem = `
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount" id="${"driveAccount" + newTypeId}">
                        <span class="text-left mr-auto p-0">${$('#addDriveAccountEmail').val()}</span>
                        <div class="d-flex text-right mr-2 p-0" id="notVerifiedDriveAccount${newTypeId}">
                            <span class="text-right mr-2 p-0" style="color: gray;">Awaiting Verification</span>    
                            <div class="driveAccountBtn p-0 removeEmailLink"
                                data-toggle="modal" data-target="#confirmRemoveEmailLink"
                                data-id=${newTypeId} data-value="${$('#addDriveAccountEmail').val()}"><i class='bx bx-trash' style="font-size: 20px;"></i>
                            </div>
                        </div>
                    </li>`;
                
                // Append the new item to the list
                $('.noDriveAccount').remove();

                $('.driveAccountList').append(newListItem);
                // if($('.driveAccount').length == 0){
                //     $('.driveAccountList').html(`
                //         <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount">
                //             <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                //         </li>
                //     `)
                // }

                // Optionally, clear the input field after adding
                $('#addDriveAccountEmail').val('');
            },
            error: function (data) {
                showNotification('Error made when adding Google account.');
                // Parse the data from the json response
                var data = JSON.parse(data.responseText);

                // Log error
                console.log("Error occured while editing type")
                console.log(data.errors);
            },
            beforeSend: function(){
                $('body').css('cursor', 'progress');
                $('.loading').show();
                $('.attachmentUploadBtn').addClass('disabled');
                showNotification('Sending link to ' + $('#addDriveAccountEmail').val() + "...");
            },
            complete: function(){
                $('body').css('cursor', 'auto');
                $('.loading').hide();
                $('.attachmentUploadBtn').addClass('disabled');
            }
        });
    }
    
});

$('#addDriveAccountEmail').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addDriveAccountBtn').removeClass('disabled');
    } else {
        $('#addDriveAccountBtn').addClass('disabled');
    }
});

$('#searchDriveAccountEmail').on('input', function (event) {
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Remove the "No accounts" placeholder if it exists
    $('.noDriveAccount').remove();

    // Filter the list items based on the search query
    var hasVisibleItems = false;

    $('.driveAccount').each(function () {
        var typeText = $(this).find('span').html().toLowerCase(); // Assuming span contains email
        if (typeText.includes(query)) {
            $(this).removeClass('hide');
            hasVisibleItems = true;
        } else {
            $(this).addClass('hide');
        }
    });

    // If no items are visible, append the "No accounts" message
    if (!hasVisibleItems) {
        $('.driveAccountList').append(`
            <li class="list-group-item p-2 d-flex justify-content-between align-items-center noDriveAccount">
                <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
            </li>
        `);
    }
});


// DELETE DOCUMENT TYPE
// To trigger delete type confirm
$('.driveAccountList').on('click', '.removeEmailLink',function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmRemoveLinkBtn').data('id', $(this).data('id'));
    $('#confirmRemoveEmailText').html("Confirm removing email: " + $(this).data('value'));
});

// Delete type
$('#confirmRemoveLinkBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;
    var typeId = $(this).data('id');
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : typeId
    }

    console.log(formData)
    
    $.ajax({
        method: "POST",
        url: window.routes.removeEmail,
        data: formData,
        success: function (data) {
            showNotification('Google account removed successfully!');

            // Remove the type in the front end
            $('#driveAccount' + typeId).remove();

            $('#confirmRemoveEmailLink').modal('hide');
            
            if($('.driveAccount').length == 0){
                $('.driveAccountList').html(`
                    <li class="list-group-item p-2 d-flex justify-content-between align-items-center noDriveAccount">
                        <span class="text-justify mr-auto p-0">No accounts linked for storage yet.</span>
                    </li>
                `)
            }
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

// Cancel delete type
$('#cancelRemoveLinkBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete type
    $('#confirmRemoveEmailLink').modal('hide');
});

// When drive account is verified
function verifyDriveAccount(){

}