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
        true,
        true,
        true,
        true,
        true,
        true
    ];
    
    assistantAccesses = [
        false,
        true,
        true,
        true,
        true,
        true
    ];

    clerkAccesses = [
        false,
        true,
        true,
        true,
        false,
        false
    ];

    updateAccountAccess(secretaryAccesses, assistantAccesses, clerkAccesses);
});

function updateAccountAccess(secretaryAccesses = [], assistantAccesses = [], clerkAccesses = []){
    if (secretaryAccesses.length == 0) {
        // Populate `secretaryAccesses` with the checked state of each element
        $('.editSecretaryRole').each(function (index, element) {
            secretaryAccesses[index] = $(element).prop('checked'); // Use `.prop('checked')`
        });
    }
    
    // Restore the `checked` state of each element
    $('.editSecretaryRole').each(function (index, element) {
        $(element).prop('checked', Boolean(secretaryAccesses[index])); // Ensure proper boolean handling
    });

    if (assistantAccesses.length == 0) {
        $('.editAssistantRole').each(function (index, element) {
            assistantAccesses[index] = $(element).prop('checked'); // Use `.prop('checked')`
        });
    }
    
    $('.editAssistantRole').each(function (index, element) {
        $(element).prop('checked', Boolean(assistantAccesses[index])); // Ensure proper boolean handling
    });
    
    // Handle Clerk Role
    if (clerkAccesses.length == 0) {
        $('.editClerkRole').each(function (index, element) {
            clerkAccesses[index] = $(element).prop('checked'); // Use `.prop('checked')`
        });
    }
    
    $('.editClerkRole').each(function (index, element) {
        $(element).prop('checked', Boolean(clerkAccesses[index])); // Ensure proper boolean handling
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
        },
        beforeSend: function(){
            showNotification('Updating account accesses...');
            $('body').css('cursor', 'progress');
            $('.accountAccessBtn').addClass('disabled');

        }, 
        complete: function(){
            $('body').css('cursor', 'auto');
            $('.accountAccessBtn').removeClass('disabled');
        }
    });
}