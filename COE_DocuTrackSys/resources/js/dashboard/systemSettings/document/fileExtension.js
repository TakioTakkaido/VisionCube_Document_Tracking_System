//////////////////////////////////////////////////////////////////
// EDITING FILE EXTENSIONS

import { showNotification } from "../../../notification";

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
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'extensions' : extensions   
    }

    $.ajax({
        method: "POST",
        url: window.routes.updateFileExtensions,
        data: formData,
        success: function (data) {
            showNotification('Allowed file extensions changed successfully!');
        },
        error: function (data) {
            showNotification('Error editing allowed file extensions.');
            console.log(data);
        }
    });
}