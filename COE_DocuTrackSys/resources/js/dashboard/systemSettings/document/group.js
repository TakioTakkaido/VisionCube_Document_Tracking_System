//////////////////////////////////////////////////////////////////
// EDITING Sender and Recipients Group

import { showNotification } from "../../../notification";

//ParticipantGroup
$('#addParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Create new form data
    var formData = new FormData();
    // Create form data for submission
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'value': $('#addParticipantGroupText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipantGroup,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Success', 'Group added successfully!');
            var newParticipantGroupId = data.id;
            // Update list group
            // Append a new list item to the list group
            var newListItem = `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipantGroup" id="participantGroup${newParticipantGroupId}">
                    <span class="text-left mr-auto p-0">${$('#addParticipantGroupText').val()}</span>
                    <div class="editParticantBtn mr-2 p-0" data-id=${newParticipantGroupId} data-value="${$('#addParticipantGroupText').val()}">
                        <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                    </div>
                    <div class="deleteParticipantGroupBtn p-0" data-id=${newParticipantGroupId} data-value="${$('#addParticipantGroupText').val()}" data-toggle="modal" data-target="#confirmDeleteParticipantGroup">
                        <i class='bx bx-trash' style="font-size: 20px;"></i>
                    </div>
                </li>`;
            
            // Append the new item to the list
            $('.systemParticipantGroupList').append(newListItem);

            // Optionally, clear the input field after adding
            $('#addParticipantGroupText').val('');

            
        },
        error: function (data) {
            showNotification('Error', 'Error made when editing group.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing group.")
            console.log(data.errors);
        }
    });
});

// Update participant Group Form
$('.systemParticipantGroupList').on('click', '.saveParticipantGroupBtn' , function(event){
    // Prevent other events
    // event.stopPropagation();

    // Create new form data
    var formData = new FormData();
    var saveParticipantGroupBtn = $(this);

    // Create form data for submission
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id': saveParticipantGroupBtn.data('id'),
        'value': $('#editParticipantGroupText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipantGroup,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Success', 'Group edited successfully!');

            // Update list group
            var newParticipantGroupText = $('#editParticipantGroupText').val();

            // Close edit participantGroup
            $('#participantGroup' + saveParticipantGroupBtn.data('id') + ' .closeEditBtn').trigger('click', newParticipantGroupText);

            
        },
        error: function (data) {
            showNotification('Error', 'Error made when editing group.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing group")
            console.log(data.errors);
        }
    });
});

$('.systemParticipantGroupList').on('click', '.editParticipantGroupBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get participantGroup id and text
        var participantGroupId = $(this).data('id'); 
        var participantGroupText = $(this).data('value');

        // Replace body of the list into a form
        $('#participantGroup' + participantGroupId).html(`
            <input type="text" class="mr-auto p-0" name="participantGroupText" id="editParticipantGroupText" value="${participantGroupText}">
            <div class="saveParticipantGroupBtn mr-2 p-0" data-id=${participantGroupId}><i class='bx bx-check' style="font-size: 20px;"></i>
            </div>
            <div class="closeEditBtn p-0" id=${"participantGroup" + participantGroupId} data-id=${participantGroupId} data-value="${participantGroupText}"><i class='bx bx-x' style="font-size: 20px;"></i>
            </div>
            <div style="display:none" class="participantGroupInfo" data-id=${participantGroupId} data-value="${participantGroupText}"></div>
        `);

        $('#addParticipantGroupBtn').addClass('disabled');
        $('#addParticipantGroupText').prop('disabled', true);
        $('.systemParticipantGroup').each(function () { 
            var systemParticipantGroup = $(this);
            var editBtn = systemParticipantGroup.find('.editParticipantGroupBtn');
            var deleteBtn = systemParticipantGroup.find('.deleteParticipantGroupBtn');

            if (systemParticipantGroup.attr('id') !== ('participantGroup' + participantGroupId)){
                systemParticipantGroup.addClass('disabled');
                editBtn.addClass('disabled');
                deleteBtn.addClass('disabled');
            }
        });   
    }
});

$('.systemParticipantGroupList').on('click', '.closeEditBtn', function(event, newText = null){
    event.preventDefault();
    var participantGroupId = $(this).attr('id');
    var selectedParticipantGroupId = $(this).data('id'); 
    var selectedParticipantGroupText = (newText != null) ? newText : $(this).data('value');

    if($('#addParticipantGroupText').val().length !== 0) {
        $('#addParticipantGroupBtn').removeClass('disabled');
    }

    $('#addParticipantGroupText').prop('disabled', false);

    $('.systemParticipantGroup').each(function (index) { 
        var systemParticipantGroup = $(this);
        var editBtn = systemParticipantGroup.find('.editParticipantGroupBtn');
        var deleteBtn = systemParticipantGroup.find('.deleteParticipantGroupBtn');

        if (systemParticipantGroup.attr('id') !== participantGroupId){
            systemParticipantGroup.removeClass('disabled');
            editBtn.removeClass('disabled');
            deleteBtn.removeClass('disabled');
        } else {
            $(this).html(`
                <span class="text-left mr-auto">${selectedParticipantGroupText}</span>
                <div class="editParticipantGroupMemberBtn mr-2 p-0" 
                    data-id=${selectedParticipantGroupId} data-value="${selectedParticipantGroupText}"><i class='bx bxs-user-detail' style="font-size: 20px;"></i>
                </div>
                <div class="editParticipantGroupBtn mr-2" data-id=${selectedParticipantGroupId} data-value="${selectedParticipantGroupText}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                </div>
                <div class="deleteParticipantGroupBtn" 
                    data-id=${selectedParticipantGroupId} data-value="${selectedParticipantGroupText}"
                    data-toggle="modal" data-target="#confirmDeleteParticipantGroup"><i class='bx bx-trash' style="font-size: 20px;"></i>
                </div>
            `);
        }
    });
});

$('#addParticipantGroupText').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addParticipantGroupBtn').removeClass('disabled');
    } else {
        $('#addParticipantGroupBtn').addClass('disabled');
    }
});

$('#searchParticipantGroupText').on('input', function(event){
    event.preventDefault();
    
    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemParticipantGroup').each(function(){
        var participantGroupText = $(this).find('span').html().toLowerCase();
        if (participantGroupText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// DELETE DOCUMENT ParticipantGroup
// To trigger delete ParticipantGroup confirm
$('.deleteParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteParticipantGroupBtn').data('id', $(this).data('id'));
    $('#confirmDeleteParticipantGroupText').html("Confirm deleting group: " + $(this).data('value'));
});

// Delete ParticipantGroup
$('#confirmDeleteParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;
    var participantGroupId = $(this).data('id');
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : participantGroupId
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.deleteParticipantGroup,
        data: formData,
        success: function (data) {
            showNotification('Success', 'Group deleted successfully!');

            // Remove the ParticipantGroup in the front end
            $('#participantGroup' + participantGroupId).remove();

            $('#confirmDeleteParticipantGroup').modal('hide');

            
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error', 'Error deleting group.');
            console.log(data.errors)
        }
    });
    
});

// Cancel delete participant
$('#cancelDeleteParticipantGroupBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete Participant
    $('#confirmDeleteParticipantGroup').modal('hide');
});