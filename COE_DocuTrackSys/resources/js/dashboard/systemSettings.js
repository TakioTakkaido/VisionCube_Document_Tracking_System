// ADD NEW ACCOUNT
$('#addAccountBtn').on('click', function(event){
    event.preventDefault();

    var formData = new FormData();
    formData = {
        'name' : $('#name').val(),
        'email' : $('#email').val(),
        'password' : $('#password').val(),
        'password_confirmation' : $('#password_confirmation').val(),
        'role' : $('#role').val(),
        '_token' : $('#token').val()
    }

    $.ajax({    
        method: "POST",
        url: window.routes.create,
        data: formData,
        success: function (data) {
            console.log(data.success);
        },
        error: function (data) {
            // Get errors
            var data = JSON.parse(data.responseText);

            // Change the input field, and
            // Add error text
            if (data.errors.name){
                $('#name').addClass('error-input');
                $('<span class="error" id="error-name">'+data.errors.name+'</span>').insertAfter('#name');
            }

            if (data.errors.email){
                $('#email').addClass('error-input');
                $('<span class="error" id="error-email">'+data.errors.email+'</span>').insertAfter('#email');
            }

            if (data.errors.password){
                $('#password').addClass('error-input');
                $('<span class="error" id="error-password">'+data.errors.password+'</span>').insertAfter('#password');
            }

            if (data.errors.password_confirmation){
                $('#password_confirmation').addClass('error-input');
                $('<span class="error" id="error-password-confirmation">'+data.errors.password_confirmation+'</span>').insertAfter('#password_confirmation');
            }
        }
    });
});

// Event to remove the error color of the form, and the error message
// when input is being typed
$('#name').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-name').remove();
    $(this).removeClass('error-input');
});

$('#email').on('input' , function(event){
    event.preventDefault();

    // Remove errors
    $('#error-email').remove();
    $(this).removeClass('error-input');
});

$('#password').on('input' , function(event){
    event.preventDefault();
    
    // Remove errors
    $('#error-password').remove();
    $(this).removeClass('error-input');
});

$('#password_confirmation').on('input' , function(event){
    event.preventDefault();
    
    // Remove errors
    $('#error-password-confirmation').remove();
    $(this).removeClass('error-input');
});

//////////////////////////////////////////////////////////////////
// EDITING ACCESS IN ACCOUNT ROLES
$('#secretarySaveBtn').on('click', function (event) {
    // Prevent other events
    event.preventDefault();

    // Access array
    var accesses = [];

    $.each($('.editSecretaryRole'), function (index, element) { 
        accesses[index] = $(element).is(':checked');
    });

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'accesses' : accesses,
        'role' : 'Assistant'
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.updateRoleAccess,
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
            console.log("Error occured while editing status")
            console.log(data.errors)
        }
    });
})

$('#assistantSaveBtn').on('click', function (event) {
    // Prevent other events
    event.preventDefault();

    // Access array
    var accesses = {};

    $.each($('.editAssistantRole'), function (index, element) { 
        accesses[index] = $(element).prop('checked');
    });
    console.log(accesses);
    var formData = new FormData();
    formData = {
        '_token' : $('#token').val(),
        'accesses' : accesses,
        'role' : 'Assistant'
    }
    console.log('edit assistant');
    $.ajax({
        method: "POST",
        url: window.routes.updateRoleAccess,
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
            console.log("Error occured while editing status")
            console.log(data.errors)
        }
    });
});

$('#clerkSaveBtn').on('click', function (event) {
    // Prevent other events
    event.preventDefault();

    // Access array
    var accesses = {};

    $.each($('.editClerkRole'), function (index, element) { 
        accesses[index] = $(element).is(':checked');
    });

    var formData = new FormData();
    
    formData = {
        '_token' : $('#token').val(),
        'accesses' : accesses,
        'role' : 'Clerk'
    }

    $.ajax({
        method: "POST",
        url: window.routes.updateRoleAccess,
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
            console.log("Error occured while editing status")
            console.log(data.errors)
        }
    });
});
//////////////////////////////////////////////////////////////////
// EDITING DOCUMENT TYPE
// Place new functions here
$('.editTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Populate the text input and id input with the value of the Type
    $('#typeId').val($(this).data('id'));
    $('#typeText').val($(this).val());
});

// To trigger delete type confirm
$('.deleteTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteTypeBtn').data('id', $(this).attr('id'));
    $('#confirmDeleteTypeText').html("Confirm deleting type: " + $(this).val());
});

// Delete type
$('#confirmDeleteTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'id' : $(this).data('id')
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteType,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Deleted successfully');

            // Remove the type in the front end

            $('#confirmDeleteType').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while deleting type")
            console.log(data.errors)
        }
    });
    
});

// Cancel delete type
$('#cancelDeleteTypeBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete type
    $('#confirmDeleteType').modal('hide');

 })

// Remove the text if cancelling
$('#typeCancelBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Empty the field
    $('#typeText').val(''); 
    $('#typeSaveBtn').data('edit') = false;  
});

// Update type Form
$('#updateTypeForm').on('submit', function(event){
    // Prevent other events
    event.preventDefault();

    // Edit Mode
    // Establish route

    // Create new form data
    var formData = new FormData();
    
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': $('#typeId').val(),
        'value': $('#typeText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateType,
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
            console.log("Error occured while editing type")
            console.log(data.errors)
        }
    });
});

//////////////////////////////////////////////////////////////////
// EDITING DOCUMENT STATUS
// For statuses, when clicked
$('.editStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Populate the text input and id input with the value of the status
    $('#statusId').val($(this).data('id'));
    $('#statusText').val($(this).val());
});

// To trigger delete status confirm
$('.deleteStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteStatusBtn').data('id', $(this).attr('id'));
    $('#confirmDeleteStatusText').html("Confirm deleting status: " + $(this).val());
});

// Delete status
$('#confirmDeleteStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'id' : $(this).data('id')
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteStatus,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Deleted successfully');

            // Remove the status in the front end

            $('#confirmDeleteStatus').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while deleting status")
            console.log(data.errors)
        }
    });
    
});

// Cancel delete status
$('#cancelDeleteStatusBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete status
    $('#confirmDeleteStatus').modal('hide');

 })

// Remove the text if cancelling
$('#statusCancelBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Empty the field
    $('#statusText').val(''); 
    $('#statusSaveBtn').data('edit') = false;  
});

// Update Status Form
$('#updateStatusForm').on('submit', function(event){
    // Prevent other events
    event.preventDefault();

    // Edit Mode
    // Establish route

    // Create new form data
    var formData = new FormData();
    
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': $('#statusId').val(),
        'value': $('#statusText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateStatus,
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
            console.log("Error occured while editing status")
            console.log(data.errors)
        }
    });
});

//////////////////////////////////////////////////////////////////
// EDITING FILE EXTENSIONS
// Update File Extensions Form
$('#updateFileExtensionForm').on('submit', function (event) {
    // Prevent other events
    event.preventDefault();

    var extensions = {};

    // Check whether the file extensions were now true or false
    $.each($('.editExtension'), function (index, element) {
        extensions[index] = $(element).is(":checked");    
    });

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'extensions' : extensions   
    }

    console.log(formData);

    $.ajax({
        method: "POST",
        url: window.routes.updateFileExtensions,
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
            console.log("Error occured while editing file extensions")
            console.log(data.errors)
        }
    });
});

//////////////////////////////////////////////////////////////////
// Update Participants
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




