//////////////////////////////////////////////////////////////////
// LINKING GMAIL ACCOUNT TO COE DTS

import { showNotification } from "../../../notification";
import {  } from "../../uploadForm";


// Edit Document Type
$('#addDriveAccountBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

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
            showNotification('Verification link sent to ' + $('#addDriveAccountEmail').val());
            var newTypeId = data.id;
            // Update list group
            // Append a new list item to the list group
            var newListItem = `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center driveAccount" id="${"driveAccount" + newTypeId}">
                    <span class="text-left mr-auto p-0">${$('#addDriveAccountEmail').val()}</span>
                    <span class="text-right mr p-0" style="color: gray;">Awaiting Verification</span>    
                    <div class="driveAccountBtn mr-2 p-0 removeEmailLink"
                        data-toggle="modal" data-target="#confirmRemoveEmailLink"
                        data-id=${newTypeId} data-value="${$('#addDriveAccountEmail').val()}"><i class='bx bx-trash' style="font-size: 20px;"></i>
                    </div>
                </li>`;
            
            // Append the new item to the list
            $('.driveAccountList').append(newListItem);

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
        }
    });
});

$('#addDriveAccountEmail').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addDriveAccountBtn').removeClass('disabled');
    } else {
        $('#addDriveAccountBtn').addClass('disabled');
    }
});

$('#searchDriveAccountEmail').on('input', function(event){
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.driveAccountList').each(function(){
        var typeText = $(this).find('span').html().toLowerCase();
        if (typeText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// DELETE DOCUMENT TYPE
// To trigger delete type confirm
$('.removeEmailLink').on('click', function(event){
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
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error removing Google account.');
            console.log(data.errors)
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