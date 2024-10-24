import { showNotification } from "../notification";

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
$('#saveAccountAccessBtn').on('click', function (event) {
    // Prevent other events
    event.preventDefault();
    updateAccountAccess();
});

$('#defaultAccountAccessBtn').on('click', function(event){
    event.preventDefault();
    var secretaryAccesses = [];
    var assistantAccesses = [];
    var clerkAccesses = [];

    // Array Pattern
    // upload
    // edit
    // move 
    // archive
    // download
    // print
    secretaryAccesses = [
        'true',
        'true',
        'true',
        'true',
        'true',
        'true'
    ];
    
    assistantAccesses = [
        'false',
        'true',
        'true',
        'true',
        'true',
        'true'
    ];

    clerkAccesses = [
        'false',
        'true',
        'true',
        'true',
        'false',
        'false'
    ];

    updateAccountAccess(secretaryAccesses, assistantAccesses, clerkAccesses);
});

function updateAccountAccess(secretaryAccesses = [], assistantAccesses = [], clerkAccesses = []){
    if (secretaryAccesses.length == 0){
        $.each($('.editSecretaryRole'), function (index, element) { 
            secretaryAccesses[index] = $(element).is(':checked');
        });
    }

    // Access array
    if (assistantAccesses.length == 0){
        $.each($('.editAssistantRole'), function (index, element) { 
            assistantAccesses[index] = $(element).prop('checked');
        });
    }
    
    // Access array
    if (clerkAccesses.length == 0){ 
        $.each($('.editClerkRole'), function (index, element) { 
            clerkAccesses[index] = $(element).is(':checked');
        });
    }

    var formData = new FormData();
    
    formData = {
        '_token' : $('#token').val(),
        'secretaryAccesses' : secretaryAccesses,
        'assistantAccesses' : assistantAccesses,
        'clerkAccesses' : clerkAccesses,
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
}
//////////////////////////////////////////////////////////////////
// EDITING DOCUMENT TYPE
// Edit Document Type
$('#addTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Create new form data
    var formData = new FormData();
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'value': $('#addTypeText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateType,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Type added successfully!  <a href="#" class="reload">Reload</a>');
            var newTypeId = data.id;
            // Update list group
            // Append a new list item to the list group
            var newListItem = `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemType" id="type${newTypeId}">
                    <span class="text-left mr-auto p-0">${$('#addTypeText').val()}</span>
                    <div class="editTypeBtn mr-2 p-0" data-id=${newTypeId} data-value="${$('#addTypeText').val()}">
                        <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                    </div>
                    <div class="deleteTypeBtn p-0" data-id=${newTypeId} data-value="${$('#addTypeText').val()}" data-toggle="modal" data-target="#confirmDeleteType">
                        <i class='bx bx-trash' style="font-size: 20px;"></i>
                    </div>
                </li>`;
            
            // Append the new item to the list
            $('.systemTypeList').append(newListItem);

            // Optionally, clear the input field after adding
            $('#addTypeText').val('');
        },
        error: function (data) {
            showNotification('Error made when editing type.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing type")
            console.log(data.errors);
        }
    });
});

// Update type Form
$('.systemTypeList').on('click', '.saveTypeBtn' , function(event){
    // Prevent other events
    // event.stopPropagation();

    // Create new form data
    var formData = new FormData();
    var saveTypeBtn = $(this);
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': saveTypeBtn.data('id'),
        'value': $('#editTypeText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateType,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Type edited successfully!  <a href="#" class="reload">Reload</a>');

            // Update list group
            var newTypeText = $('#editTypeText').val();

            // Close edit type
            $('#type' + saveTypeBtn.data('id') + ' .closeEditBtn').trigger('click', newTypeText);
        },
        error: function (data) {
            showNotification('Error made when editing type.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing type")
            console.log(data.errors);
        }
    });
});

$('.systemTypeList').on('click', '.editTypeBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get type id and text
        var typeId = $(this).data('id'); 
        var typeText = $(this).data('value');

        // Replace body of the list into a form
        $('#type' + typeId).html(`
            <input type="text" class="mr-auto p-0" name="typeText" id="editTypeText" value="${typeText}">
            <div class="saveTypeBtn mr-2 p-0" data-id=${typeId}><i class='bx bx-check' style="font-size: 20px;"></i>
            </div>
            <div class="closeEditBtn p-0" id=${"type" + typeId} data-id=${typeId} data-value="${typeText}"><i class='bx bx-x' style="font-size: 20px;"></i>
            </div>
            <div style="display:none" class="typeInfo" data-id=${typeId} data-value="${typeText}"></div>
        `);

        $('#addTypeBtn').addClass('disabled');
        $('#addTypeText').prop('disabled', true);
        $('.systemType').each(function () { 
            var systemType = $(this);
            var editBtn = systemType.find('.editTypeBtn');
            var deleteBtn = systemType.find('.deleteTypeBtn');

            if (systemType.attr('id') !== ('type' + typeId)){
                systemType.addClass('disabled');
                editBtn.addClass('disabled');
                deleteBtn.addClass('disabled');
            }
        });   
    }
});

$('.systemTypeList').on('click', '.closeEditBtn', function(event, newText = null){
    event.preventDefault();
    var typeId = $(this).attr('id');
    var selectedTypeId = $(this).data('id'); 
    var selectedTypeText = (newText != null) ? newText : $(this).data('value');

    if($('#addTypeText').val().length !== 0) {
        $('#addTypeBtn').removeClass('disabled');
    }

    $('#addTypeText').prop('disabled', false);

    $('.systemType').each(function (index) { 
        var systemType = $(this);
        var editBtn = systemType.find('.editTypeBtn');
        var deleteBtn = systemType.find('.deleteTypeBtn');

        if (systemType.attr('id') !== typeId){
            systemType.removeClass('disabled');
            editBtn.removeClass('disabled');
            deleteBtn.removeClass('disabled');
        } else {
            $(this).html(`
                <span class="text-left mr-auto">${selectedTypeText}</span>
                <div class="editTypeBtn mr-2" data-id=${selectedTypeId} data-value="${selectedTypeText}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                </div>
                <div class="deleteTypeBtn" 
                    data-id=${selectedTypeId} data-value="${selectedTypeText}"
                    data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i>
                </div>
            `);
        }
    });
});

$('#addTypeText').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addTypeBtn').removeClass('disabled');
    } else {
        $('#addTypeBtn').addClass('disabled');
    }
});

$('#searchTypeText').on('input', function(event){
    event.preventDefault();
    
    // Reset the border for all items to ensure consistency
    $('.systemType').css('border-bottom', '1px solid rgba(0, 0, 0, .125)');

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemType').each(function(){
        var typeText = $(this).find('span').html().toLowerCase();
        if (typeText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });

    // Ensure the last visible item gets the border style
    var lastVisibleItem = $('.systemType:visible').last();
    
    if (lastVisibleItem.length > 0) {
        lastVisibleItem.css('border-bottom', '2px solid rgba(0, 0, 0, .125)');
    }
});

// DELETE DOCUMENT TYPE
// To trigger delete type confirm
$('.deleteTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteTypeBtn').data('id', $(this).data('id'));
    $('#confirmDeleteTypeText').html("Confirm deleting type: " + $(this).data('value'));
});

// Delete type
$('#confirmDeleteTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;
    var typeId = $(this).data('id');
    formData = {
        '_token' : $('#token').val(),
        'id' : typeId
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.deleteType,
        data: formData,
        success: function (data) {
            showNotification('Type deleted successfully! <a href="#" class="reload">Reload</a>');

            // Remove the type in the front end
            $('#type' + typeId).remove();

            $('#confirmDeleteType').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error deleting type.');
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

    updateFileExtensions();
});

$('#fileExtensionResetBtn').on('click', function(event){
    event.preventDefault();

    var extensions = [];
    extensions = [
        'true',
        'true',
        'true',
        'true',
        'true'
    ];

    updateFileExtensions(extensions);
});

function updateFileExtensions(extensions = []){
    if (extensions.length == 0){    
        $.each($('.editExtension'), function (index, element) {
            extensions[index] = $(element).is(":checked");    
        });
    }

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'extensions' : extensions   
    }

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
}
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






