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
})