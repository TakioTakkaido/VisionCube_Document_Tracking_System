

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
        '_token' : $('meta[name="csrf-token"]').attr('content'),
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