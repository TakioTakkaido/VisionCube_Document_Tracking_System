//////////////////////////////////////////////////////////////////
import { showNotification } from "../../../notification";

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

    $.each($('.editSecretaryRole'), function (index, element) { 
        $(element).prop('checked', secretaryAccesses[index] === 'true');
    });

    // Access array
    if (assistantAccesses.length == 0){
        $.each($('.editAssistantRole'), function (index, element) { 
            assistantAccesses[index] = $(element).prop('checked');
        });
    }
    
    $.each($('.editAssistantRole'), function (index, element) { 
        $(element).prop('checked', assistantAccesses[index] === 'true');
    });

    // Access array
    if (clerkAccesses.length == 0){ 
        $.each($('.editClerkRole'), function (index, element) { 
            clerkAccesses[index] = $(element).is(':checked');
        });
    }

    $.each($('.editClerkRole'), function (index, element) { 
        $(element).prop('checked', clerkAccesses[index] === 'true');
    });

    var formData = new FormData();
    
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'secretaryAccesses' : secretaryAccesses,
        'assistantAccesses' : assistantAccesses,
        'clerkAccesses' : clerkAccesses,
    }

    $.ajax({
        method: "POST",
        url: window.routes.updateRoleAccess,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Account accesses updated successfully!');
        },
        error: function (data) {

            // Log error
            showNotification('Error updating account accesses.');
            console.log(data.errors)
        }
    });
}