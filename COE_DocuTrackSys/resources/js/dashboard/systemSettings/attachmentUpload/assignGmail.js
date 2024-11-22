//////////////////////////////////////////////////////////////////
// ASSIGNING GMAIL ACCOUNT TO COE DTS

import { showNotification } from "../../../notification";

// Search Goglle Account
$('#searchAssignDriveAccountEmail').on('input', function(event){
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.assignDriveAccountList').each(function(){
        var typeText = $(this).find('assignDriveAccountEmail').html().toLowerCase();
        if (typeText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

$('#assignDriveAccountStorageSaveBtn').on('click', function(event){
    event.preventDefault();
    var storages = [];

    $.each($('.assignDriveAccount'), function (index, element) { 
        var driveId = $(this).data('id');
        storages.push({
            'id' : driveId,
            'canReport' : $("#canReport" + driveId).is(':checked'),
            'canDocument' : $("#canDocument" + driveId).is(':checked'),
        });
    });

    var formData = new FormData();
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'storages' : storages
    }

    $.ajax({
        method: "POST",
        url: window.routes.updateAttachmentStorage,
        data: formData,
        success: function (response) {
            showNotification('Updated storage assignment in each accounts!');
        },
        error: function (response) {
            showNotification('Error updating the storage assignment.');
        }
    });
})
