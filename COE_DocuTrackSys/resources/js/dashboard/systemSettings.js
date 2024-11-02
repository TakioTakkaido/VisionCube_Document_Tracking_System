import { showNotification } from "../notification";


//////////////////////////////////////////////////////////////////
/* Update Participants
$('.editParticipantBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Populate the text input and id input with the value of the category
    $('#participantId').val($(this).data('id'));
    $('#participantText').val($(this).val());
});

// Delete Participants
$('.deleteParticipantBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteParticipantBtn').data('id', $(this).attr('id'));
    $('#confirmDeleteParticipantText').html("Confirm deleting participant: " + $(this).val());
});

$('#confirmDeleteParticipantBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'id' : $(this).data('id')
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteParticipant,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Deleted successfully');

            // Remove the category in the front end

            $('#confirmDeleteParticipant').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while deleting category")
            console.log(data.errors)
        }
    });
    
});

// Cancel delete category
$('#cancelDeleteParticipantBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete category
    $('#confirmDeleteParticipant').modal('hide');

 });

$('#updateParticipantForm').on('submit', function(event){
    // Prevent other events
    event.preventDefault();

    // Edit Mode
    // Establish route

    // Create new form data
    var formData = new FormData();
    
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': $('#participantId').val(),
        'value': $('#participantText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipant,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Edited successfully');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing participant")
            console.log(data.errors)
        }
    });
});


//////////////////////////////////////////////////////////////////

// Update Participant List
$('.editParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Populate the text input and id input with the value of the category
    $('#participantGroupId').val($(this).data('id'));
    $('#participantGroupText').val($(this).val());
});

// To trigger delete category confirm
$('.deleteParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteParticipantGroupBtn').data('id', $(this).attr('id'));
    $('#confirmDeleteParticipantGroupText').html("Confirm deleting participant: " + $(this).val());
});

// Delete category
$('#confirmDeleteParticipantGroupBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'id' : $(this).data('id')
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteParticipantGroup,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Deleted successfully');

            // Remove the category in the front end

            $('#confirmDeleteParticipantGroup').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while deleting category")
            console.log(data.errors)
        }
    });
    
});

$('#cancelDeleteParticipantGroupBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete category
    $('#confirmDeleteParticipantGroup').modal('hide');

 });

$('#updateParticipantForm').on('submit', function(event){
    // Prevent other events
    event.preventDefault();

    // Edit Mode
    // Establish route

    // Create new form data
    var formData = new FormData();
    
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': $('#participantGroupId').val(),
        'value': $('#participantGroupText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipantGroup,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Edited successfully');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing participant")
            console.log(data.errors)
        }
    });
});

// Sync New Participants to the Selected Participant Group
$('.editParticipantGroupMemberBtn').on('click', function(event){
    // Get the id of the selected participant group
    var id = $(this).data('id');
    $('#selectedParticipantGroupId').val($(this).data('id'));
    var name = $(this).val();
    // AJAX request to get the which groups belong to the selected group
    $.ajax({
        method: "GET",
        url: window.routes.getParticipantGroupMembers.replace(':id', id),
        success: function (data) {
            $('#groupList').html('');
            $('#participantGroupParticipantList').html('');
            // Create a dropdown for the groups under participant group
            $('#selectedParticipantGroupId').attr('id', $(this).data('id'));
            $.each(data.groups, function (index, group) {
                if (name != group.value){
                    var dropdownItem = `<div class="dropdown-item d-flex justify-content-between align-items-center participantGroupGroup" style="max-width: 1000px !important;">
                                <div class="ml-auto">
                                    <input type="checkbox" class="form-check-input editParticipantGroupGroups" name="participantGroupIds[]" id="${group.id}" ${data.checked[index] ? 'checked' : null}>
                                    <label for="${group.id}" class="form-check-label">${group.value}</label>
                                </div>
                            </div>`;
                            
                    $('#groupList').append(dropdownItem);
                }
            });

            
            $.each(data.participants, function (index, participants) {
                if (name != participants.value){
                    var dropdownItem = `<div class="dropdown-item d-flex justify-content-between align-items-center participantGroupParticipants" style="max-width: 1000px !important;">
                                <div class="ml-auto">
                                    <input type="checkbox" class="form-check-input editParticipantGroupParticipants" name="participantIds[]" id="${participants.id}" ${data.checked2[index] ? 'checked' : null}>
                                    <label for="${participants.id}" class="form-check-label">${participants.value}</label>
                                </div>
                            </div>`;
                            
                    $('#participantGroupParticipantList').append(dropdownItem);
                }
            });
            // Log success message
            console.log(data);
            console.log('Participant group groups obtained successfully');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining participant group groups")
            console.log(data.errors)
        }
    });
});

$('#updateParticipantGroupMembersForm').on('submit', function(event){
    event.preventDefault();

    // Create new form data
    var participantGroupsIDs = [];
    var participantIDs = [];
    $.each($('.participantGroupGroup input[type="checkbox"]:checked'), function(index, element) {
        participantGroupsIDs.push($(element).attr('id')); // Use the ID from the checkbox
    });
    
    $.each($('.participantGroupParticipants input[type="checkbox"]:checked'), function(index, element) {
        participantIDs.push($(element).attr('id')); // Use the ID from the checkbox
    });

    // Create form data for submission
    
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
        'id' : $('#selectedParticipantGroupId').val(),
        'participantGroupsIDs': participantGroupsIDs,
        'participantIDs' : participantIDs
    }
    console.log(formData);

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateParticipantGroupMembers,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

        // Log success message
            console.log(data);
            console.log('Edited successfully');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing participant")
            console.log(data.errors)
        }
    });
})
// Sync New Participant Groups to the Selected Participant Group

*/




