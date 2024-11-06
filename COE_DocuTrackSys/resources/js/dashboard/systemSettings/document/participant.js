//////////////////////////////////////////////////////////////////
// Sender/recipient

import { showNotification } from "../../../notification";
import {  } from "../../uploadForm";

// Edit Document Participant
$('#addParticipantBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Create new form data
    var formData = new FormData();
    // Create form data for submission
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'value': $('#addParticipantText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipant,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Participant added successfully!  <a href="#" class="reload">Reload</a>');
            var newParticipantId = data.id;
            // Update list group
            // Append a new list item to the list group
            var newListItem = `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipant" id="participant${newParticipantId}">
                    <span class="text-left mr-auto p-0">${$('#addParticipantText').val()}</span>
                    <div class="editParticipantBtn mr-2 p-0" data-id=${newParticipantId} data-value="${$('#addParticipantText').val()}">
                        <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                    </div>
                    <div class="deleteParticipantBtn p-0" data-id=${newParticipantId} data-value="${$('#addParticipantText').val()}" data-toggle="modal" data-target="#confirmDeleteParticipant">
                        <i class='bx bx-trash' style="font-size: 20px;"></i>
                    </div>
                </li>`;
            
            // Append the new item to the list
            $('.systemParticipantList').append(newListItem);

            // Optionally, clear the input field after adding
            $('#addParticipantText').val('');
        },
        error: function (data) {
            showNotification('Error made when editing Participant.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occurred while editing Participant")
            console.log(data.errors);
        }
    });
});

// Update Participant Form
$('.systemParticipantList').on('click', '.saveParticipantBtn' , function(event){
    // Prevent other events
    // event.stopPropagation();

    // Create new form data
    var formData = new FormData();
    var saveParticipantBtn = $(this);

    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id': saveParticipantBtn.data('id'),
        'value': $('#editParticipantText').val()
    };

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipant,
        data: formData,
        success: function (data) {
            showNotification('Success', 'Participant edited successfully! <a href="#" class="reload">Reload</a>');

            // Update the list item with the new value
            var newParticipantText = $('#editParticipantText').val();

            // Close edit participant
            $('#participant' + saveParticipantBtn.data('id') + ' .closeEditBtn').trigger('click', newParticipantText);
        },
        error: function (data) {
            showNotification('Error', 'Error editing participant.');
            console.error("Error occurred while editing participant", data);
        }
    });
});

$('.systemParticipantList').on('click', '.editParticipantBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get participant id and text
        var participantId = $(this).data('id'); 
        var participantText = $(this).data('value');

        // Replace body of the list into a form
        $('#participant' + participantId).html(`
            <input participant="text" class="mr-auto p-0" name="participantText" id="editParticipantText" value="${participantText}">
            <div class="saveParticipantBtn mr-2 p-0" data-id=${participantId}><i class='bx bx-check' style="font-size: 20px;"></i>
            </div>
            <div class="closeEditBtn p-0" id=${"participant" + participantId} data-id=${participantId} data-value="${participantText}"><i class='bx bx-x' style="font-size: 20px;"></i>
            </div>
            <div style="display:none" class="participantInfo" data-id=${participantId} data-value="${participantText}"></div>
        `);

        $('#addParticipantBtn').addClass('disabled');
        $('#addParticipantText').prop('disabled', true);
        
        $('.systemParticipant').each(function () { 
            var systemParticipant = $(this);
            var editBtn = systemParticipant.find('.editParticipantBtn');
            var deleteBtn = systemParticipant.find('.deleteParticipantBtn');

            if (systemParticipant.attr('id') !== ('participant' + participantId)){
                systemParticipant.addClass('disabled');
                editBtn.addClass('disabled');
                deleteBtn.addClass('disabled');
            }
        });   
    }
});

$('.systemParticipantList').on('click', '.closeEditBtn', function(event, newText = null){
    event.preventDefault();
    var participantId = $(this).attr('id');
    var selectedParticipantId = $(this).data('id'); 
    var selectedParticipantText = (newText != null) ? newText : $(this).data('value');

    if($('#addParticipantText').val().length !== 0) {
        $('#addParticipantBtn').removeClass('disabled');
    }

    $('#addParticipantText').prop('disabled', false);

    $('.systemParticipant').each(function (index) { 
        var systemParticipant = $(this);
        var editBtn = systemParticipant.find('.editParticipantBtn');
        var deleteBtn = systemParticipant.find('.deleteParticipantBtn');

        if (systemParticipant.attr('id') !== participantId){
            systemParticipant.removeClass('disabled');
            editBtn.removeClass('disabled');
            deleteBtn.removeClass('disabled');
        } else {
            $(this).html(`
                <span class="text-left mr-auto">${selectedParticipantText}</span>
                <div class="editParticipantBtn mr-2" data-id=${selectedParticipantId} data-value="${selectedParticipantText}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                </div>
                <div class="deleteParticipantBtn" 
                    data-id=${selectedParticipantId} data-value="${selectedParticipantText}"
                    data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i>
                </div>
            `);
        }
    });
});

$('#addParticipantText').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addParticipantBtn').removeClass('disabled');
    } else {
        $('#addParticipantBtn').addClass('disabled');
    }
});

$('#searchParticipantText').on('input', function(event){
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemParticipant').each(function(){
        var participantText = $(this).find('span').html().toLowerCase();
        if (participantText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// DELETE DOCUMENT Participant
$('.deleteParticipantBtn').on('click', function(event) {
    event.preventDefault();
    $('#confirmDeleteParticipantBtn').data('id', $(this).data('id'));
    $('#confirmDeleteParticipantText').html("Confirm deleting participant: " + $(this).data('value'));
});

// Confirm Delete Participant
$('#confirmDeleteParticipantBtn').on('click', function(event) {
    event.preventDefault();

    var formData = new FormData();
    var participantId = $(this).data('id');
    formData = {
        '_token': $('meta[name="csrf-token"]').attr('content'),
        'id': participantId
    };

    $.ajax({
        method: "POST",
        url: window.routes.deleteParticipant,
        data: formData,
        success: function(data) {
            showNotification('Success', 'Participant deleted successfully! <a href="#" class="reload">Reload</a>');
            $('#participant' + participantId).remove();
            $('#confirmDeleteParticipant').modal('hide');
        },
        error: function(data) {
            showNotification('Error', 'Error deleting participant.');
            console.error("Error occurred while deleting participant", data);
        }
    });
});

// Cancel Delete Participant
$('#cancelDeleteParticipantBtn').on('click', function(event) {
    event.preventDefault();
    $('#confirmDeleteParticipant').modal('hide');
});