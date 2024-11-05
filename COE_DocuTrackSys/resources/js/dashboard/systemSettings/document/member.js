import { showNotification } from "../../../notification";

// Sync New Participants to the Selected Participant Group
$('.systemParticipantGroupList').on('click', '.saveParticipantGroupMemberBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get participantGroup id and text
        var participantGroupId = $(this).data('id'); 
        var participantGroupText = $(this).data('value');  

        // Create new form data
        var participantGroupsIDs = [];
        var participantIDs = [];
        $.each($('.systemParticipantGroupParticipantGroup input[type="checkbox"]:checked'), function(index, element) {
            participantGroupsIDs.push($(element).data('id')); // Use the ID from the checkbox
        });
        
        $.each($('.systemParticipantGroupParticipant input[type="checkbox"]:checked'), function(index, element) {
            participantIDs.push($(element).data('id')); // Use the ID from the checkbox
        });

        // Create form data for submission
        
        var formData = new FormData();
        formData = {
            '_token' : $('meta[name="csrf-token"]').attr('content'),
            'id' : participantGroupId,
            'participantGroupsIDs': participantGroupsIDs,
            'participantIDs' : participantIDs
        }

        // Submit the data form
        $.ajax({
            method: "POST",
            url: window.routes.updateParticipantGroupMembers,
            data: formData,
            success: function (data) {
                showNotification('Success', 'Updated participant group members of ' + participantGroupText + ' successfully!');

                $('.closeEditParticipantGroupMemberBtn').trigger('click');
            },
            error: function (data) {
                showNotification('Error', 'Error updating participant group members of ' + participantGroupText + '.');
                console.log(data.errors)
            }
        });        
    }
});

// Display All Members of the Selected Participant Group
$('.systemParticipantGroupList').on('click', '.editParticipantGroupMemberBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get participantGroup id and text
        var participantGroupId = $(this).data('id'); 
        var participantGroupText = $(this).data('value');  

        // Replace body of the list into a edit participants mode
        $('#participantGroup' + participantGroupId).html(`
            <span class="text-left mr-auto">${participantGroupText}</span>
            <div class="saveParticipantGroupMemberBtn mr-2 p-0" data-id=${participantGroupId} data-value="${participantGroupText}"><i class='bx bx-check' style="font-size: 20px;"></i>
            </div>
            <div class="closeEditParticipantGroupMemberBtn p-0" id=${"participantGroup" + participantGroupId} data-id=${participantGroupId} data-value="${participantGroupText}"><i class='bx bx-x' style="font-size: 20px;"></i>
            </div>
            <div style="display:none" class="participantGroupInfo" data-id=${participantGroupId} data-value="${participantGroupText}"></div>
        `);

        // Disable the add participant
        $('#addParticipantBtn').addClass('disabled');
        $('#addParticipantText').prop('disabled', true);

        // Disable other buttons from other participants
        $('.systemParticipant').each(function () { 
            var systemParticipant = $(this);
            var editBtn = systemParticipant.find('.editParticipantBtn');
            var deleteBtn = systemParticipant.find('.deleteParticipantBtn');

            systemParticipant.addClass('disabled');
            editBtn.addClass('disabled');
            deleteBtn.addClass('disabled');
        });   

        // Disable the add participant group
        $('#addParticipantGroupBtn').addClass('disabled');
        $('#addParticipantGroupText').prop('disabled', true);

        // Disable other buttons from other groups
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
        
        // Submit the data form
        $.ajax({
            method: "GET",
            url: window.routes.getParticipantGroupMembers.replace(':id', participantGroupId),
            success: function (data) {
                // Log success message
                showNotification('Success', 'Group member obtained successfully!');

                // Edit the title
                $('#editParticipantGroupMemberTitle').html('<h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients of Selected Group: ' + participantGroupText + '</h6>');

                // Enable the search for the participant group group
                $('#searchParticipantGroupParticipantGroupText').prop('disabled', false);

                // Create a list group for the participant group's groups
                $('.systemParticipantGroupParticipantGroupList').html('');
                $.each(data.groups, function (index, group) {
                    var checked = '';
                    if (participantGroupText != group.value){
                        if (data.checked[index] == 1){
                            checked = 'checked';
                        } else {
                            checked = '';
                        }

                        var dropdownItem = `
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipantGroupParticipantGroup" id="${"participantGroupParticipantGroup" + group.id}">
                            <span class="text-left mr-auto">${group.value}</span>
                            <input type="checkbox" class="mr-2 p-0" class="systemParticipantGroupParticipantGroupChecklist" data-id="${group.id}" data-value="${group.text}" ${checked}>
                            </li>
                        `;
                                
                        $('.systemParticipantGroupParticipantGroupList').append(dropdownItem);
                    }
                });

                // Enable the search for the participant group participants
                $('#searchParticipantGroupParticipantText').prop('disabled', false);

                // Create a list group for the participant group's participants
                $('.systemParticipantGroupParticipantList').html('');
                $.each(data.participants, function (index, group) {
                    var checked = '';
                    if (participantGroupText != group.value){
                        if (data.checked2[index] == 1){
                            checked = 'checked';
                        } else {
                            checked = '';
                        }

                        var dropdownItem = `
                            <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemParticipantGroupParticipant" id="${"participantGroupParticipant" + group.id}">
                            <span class="text-left mr-auto">${group.value}</span>
                            <input type="checkbox" class="mr-2 p-0" class="systemParticipantGroupParticipantChecklist" data-id="${group.id}" data-value="${group.text}" ${checked}>
                            </li>
                        `;
                                
                        $('.systemParticipantGroupParticipantList').append(dropdownItem);
                    }
                });
            },
            error: function (data) {
                showNotification('Error', 'Error made when obtaining group members.');
                // Parse the data from the json response
                var data = JSON.parse(data.responseText);

                // Log error
                console.log("Error occured while editing group")
                console.log(data.errors);
            }
        });   
    }
});

// Search Participant Group Participant Groups
$('#searchParticipantGroupParticipantGroupText').on('input', function(event){
    // Prevent other events
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemParticipantGroupParticipantGroup').each(function(){
        var participantGroupParticipantGroupText = $(this).find('span').html().toLowerCase();
        if (participantGroupParticipantGroupText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// Search Participant Group Participants 
$('#searchParticipantGroupParticipantText').on('input', function(event){
    // Prevent other events
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemParticipantGroupParticipant').each(function(){
        var participantGroupParticipantText = $(this).find('span').html().toLowerCase();
        if (participantGroupParticipantText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// Close Edit Participant Group Member Button
$('.systemParticipantGroupList').on('click', '.closeEditParticipantGroupMemberBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get participantGroup id and text
        var participantGroupId = $(this).data('id'); 
        var participantGroupText = $(this).data('value');  

        // Revert back to the original form
        $('#participantGroup' + participantGroupId).html(`
            <span class="text-left mr-auto">${participantGroupText}</span>
            <div class="editParticipantGroupMemberBtn mr-2 p-0" 
                data-id=${participantGroupId} data-value="${participantGroupText}"><i class='bx bxs-user-detail' style="font-size: 20px;"></i>
            </div>
            <div class="editParticipantGroupBtn mr-2" data-id=${participantGroupId} data-value="${participantGroupText}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
            </div>
            <div class="deleteParticipantGroupBtn" 
                data-id=${participantGroupId} data-value="${participantGroupText}"
                data-toggle="modal" data-target="#confirmDeleteParticipantGroup"><i class='bx bx-trash' style="font-size: 20px;"></i>
            </div>
        `);

        // Enable the add participant
        $('#addParticipantText').prop('disabled', false);

        // Enable other buttons from other participants
        $('.systemParticipant').each(function () { 
            var systemParticipant = $(this);
            var editBtn = systemParticipant.find('.editParticipantBtn');
            var deleteBtn = systemParticipant.find('.deleteParticipantBtn');

            systemParticipant.removeClass('disabled');
            editBtn.removeClass('disabled');
            deleteBtn.removeClass('disabled');
        });   

        // Enable the add participant group
        $('#addParticipantGroupText').prop('disabled', false);

        // Enable other buttons from other groups
        $('.systemParticipantGroup').each(function () { 
            var systemParticipantGroup = $(this);
            var editBtn = systemParticipantGroup.find('.editParticipantGroupBtn');
            var deleteBtn = systemParticipantGroup.find('.deleteParticipantGroupBtn');

            if (systemParticipantGroup.attr('id') !== ('participantGroup' + participantGroupId)){
                systemParticipantGroup.removeClass('disabled');
                editBtn.removeClass('disabled');
                deleteBtn.removeClass('disabled');
            }
        }); 
        
        // Revert the edit participant member title back
        $('#editParticipantGroupMemberTitle').html('<h6 class="p-0 font-weight-bold mb-0">Edit Sender and Recipients of Selected Group</h6>');

        // Disable the search for the participant group group
        $('#searchParticipantGroupParticipantGroupText').val('');
        $('#searchParticipantGroupParticipantGroupText').prop('disabled', true);

        // Remove the group members from the list
        $('.systemParticipantGroupParticipantGroupList').html(`
            <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                No child group in this group.
            </li>
        `);


        // Disable the search for the participant group participant
        $('#searchParticipantGroupParticipantText').val('');
        $('#searchParticipantGroupParticipantText').prop('disabled', true);

        // Remove the participants from the list
        $('.systemParticipantGroupParticipantList').html(`
            <li class="list-group-item p-2 d-flex justify-content-between align-items-center">
                No participants in this group.
            </li>
        `); 
    }
});

// 
$('#updateParticipantGroupMembersForm').on('submit', function(event){
    event.preventDefault();

    
})