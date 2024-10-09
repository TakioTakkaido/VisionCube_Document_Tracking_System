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

// EDITING ACCESS IN ACCOUNT ROLES
$('#updateSecretaryAccountRole').on('submit', function (event) {
    // Prevent other events
    event.preventDefault();

    // Access array
    var accesses = {};

    $.each($('.editSecretaryRole'), function (index, element) { 
        accesses[index] = $(element).is(':checked');
    });

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'accesses' : accesses,
        'role' : 'Secretary'
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

$('#updateClerkAccountRole').on('submit', function (event) {
    // Prevent other events
    event.preventDefault();

    // Access array
    var accesses = {};

    $.each($('.editClerkRole'), function (index, element) { 
        accesses[index] = $(element).is(':checked');
    });

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
})

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

// EDITING DOCUMENT CATEGORY
// Place new functions here
// EDITING DOCUMENT CATEGORY
// Place new functions here
// For categories, when clicked
$('.editCategoryBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Populate the text input and id input with the value of the category
    $('#categoryId').val($(this).data('id'));
    $('#categoryText').val($(this).val());
});

// To trigger delete category confirm
$('.deleteCategoryBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteCategoryBtn').data('id', $(this).attr('id'));
    $('#confirmDeleteCategoryText').html("Confirm deleting category: " + $(this).val());
});

// Delete category
$('#confirmDeleteCategoryBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;

    formData = {
        '_token' : $('#token').val(),
        'id' : $(this).data('id')
    }

    $.ajax({
        method: "POST",
        url: window.routes.deleteCategory,
        data: formData,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

           // Log success message
            console.log(data);
            console.log('Deleted successfully');

            // Remove the category in the front end

            $('#confirmDeleteCategory').modal('hide');
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
$('#cancelDeleteCategoryBtn').on('click', function (event) { 
    // Prevent other events
    event.preventDefault();

    // Close only the modal of confirm delete category
    $('#confirmDeleteCategory').modal('hide');

 })

// Remove the text if cancelling
$('#categoryCancelBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Empty the field
    $('#categoryText').val(''); 
    $('#categorySaveBtn').data('edit') = false;  
});

// Update Category Form
$('#updateCategoryForm').on('submit', function(event){
    // Prevent other events
    event.preventDefault();

    // Edit Mode
    // Establish route

    // Create new form data
    var formData = new FormData();
    
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': $('#categoryId').val(),
        'value': $('#categoryText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateCategory,
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
            console.log("Error occured while editing category")
            console.log(data.errors)
        }
    });
});

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