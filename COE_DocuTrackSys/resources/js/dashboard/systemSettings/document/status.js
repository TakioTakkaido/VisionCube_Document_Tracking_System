//////////////////////////////////////////////////////////////////
// EDITING DOCUMENT STATUS
// Edit Document status
$('#addStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Create new form data
    var formData = new FormData();
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'value': $('#addStatusText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateStatus,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Success', 'Status added successfully!  <a href="#" class="reload">Reload</a>');
            var newStatusId = data.id;
            // Update list group
            // Append a new list item to the list group
            var newListItem = `
                <li class="list-group-item p-2 d-flex justify-content-between align-items-center systemStatus" id="status${newStatusId}">
                    <span class="text-left mr-auto p-0">${$('#addStatusText').val()}</span>
                    <div class="editStatusBtn mr-2 p-0" data-id=${newStatusId} data-value="${$('#addStatusText').val()}">
                        <i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                    </div>
                    <div class="deleteStatusBtn p-0" data-id=${newStatusId} data-value="${$('#addStatusText').val()}" data-toggle="modal" data-target="#confirmDeleteStatus">
                        <i class='bx bx-trash' style="font-size: 20px;"></i>
                    </div>
                </li>`;
            
            // Append the new item to the list
            $('.systemStatusList').append(newListItem);

            // Optionally, clear the input field after adding
            $('#addStatusText').val('');
        },
        error: function (data) {
            showNotification('Error', 'Error made when editing status.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing status")
            console.log(data.errors);
        }
    });
});

// Update status Form
$('.systemStatusList').on('click', '.saveStatusBtn' , function(event){
    // Prevent other events
    // event.stopPropagation();

    // Create new form data
    var formData = new FormData();
    var saveStatusBtn = $(this);
    // Create form data for submission
    formData = {
        '_token' : $('#token').val(),
        'id': saveStatusBtn.data('id'),
        'value': $('#editStatusText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateStatus,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Success', 'Status edited successfully!  <a href="#" class="reload">Reload</a>');

            // Update list group
            var newStatusText = $('#editStatusText').val();

            // Close edit status
            $('#status' + saveStatusBtn.data('id') + ' .closeEditBtn').trigger('click', newStatusText);
        },
        error: function (data) {
            showNotification('Error', 'Error made when editing status.');
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while editing status")
            console.log(data.errors);
        }
    });
});

$('.systemStatusList').on('click', '.editStatusBtn', function(event){
    if (!$(this).hasClass('disabled')){
        // Prevent other events
        event.preventDefault();

        // Get status id and text
        var statusId = $(this).data('id'); 
        var statusText = $(this).data('value');

        // Replace body of the list into a form
        $('#status' + statusId).html(`
            <input type="text" class="mr-auto p-0" name="statusText" id="editStatusText" value="${statusText}">
            <div class="saveStatusBtn mr-2 p-0" data-id=${statusId}><i class='bx bx-check' style="font-size: 20px;"></i>
            </div>
            <div class="closeEditBtn p-0" id=${"status" + statusId} data-id=${statusId} data-value="${statusText}"><i class='bx bx-x' style="font-size: 20px;"></i>
            </div>
            <div style="display:none" class="statusInfo" data-id=${statusId} data-value="${statusText}"></div>
        `);

        $('#addStatusBtn').addClass('disabled');
        $('#addStatusText').prop('disabled', true);
        $('.systemStatus').each(function () { 
            var systemStatus = $(this);
            var editBtn = systemStatus.find('.editStatusBtn');
            var deleteBtn = systemStatus.find('.deleteStatusBtn');

            if (systemStatus.attr('id') !== ('status' + statusId)){
                systemStatus.addClass('disabled');
                editBtn.addClass('disabled');
                deleteBtn.addClass('disabled');
            }
        });   
    }
});

$('.systemStatusList').on('click', '.closeEditBtn', function(event, newText = null){
    event.preventDefault();
    var statusId = $(this).attr('id');
    var selectedStatusId = $(this).data('id'); 
    var selectedStatusText = (newText != null) ? newText : $(this).data('value');

    if($('#addStatusText').val().length !== 0) {
        $('#addStatusBtn').removeClass('disabled');
    }

    $('#addStatusText').prop('disabled', false);

    $('.systemStatus').each(function (index) { 
        var systemStatus = $(this);
        var editBtn = systemStatus.find('.editStatusBtn');
        var deleteBtn = systemStatus.find('.deleteStatusBtn');

        if (systemStatus.attr('id') !== statusId){
            systemStatus.removeClass('disabled');
            editBtn.removeClass('disabled');
            deleteBtn.removeClass('disabled');
        } else {
            $(this).html(`
                <span class="text-left mr-auto">${selectedStatusText}</span>
                <div class="editStatusBtn mr-2" data-id=${selectedStatusId} data-value="${selectedStatusText}"><i class='bx bx-edit-alt' style="font-size: 20px;"></i>
                </div>
                <div class="deleteStatusBtn" 
                    data-id=${selectedStatusId} data-value="${selectedStatusText}"
                    data-toggle="modal" data-target="#confirmDeleteStatus"><i class='bx bx-trash' style="font-size: 20px;"></i>
                </div>
            `);
        }
    });
});

$('#addStatusText').on('input', function(event){
    event.preventDefault();
    if($(this).val().length !== 0) {
        $('#addStatusBtn').removeClass('disabled');
    } else {
        $('#addStatusBtn').addClass('disabled');
    }
});

$('#searchStatusText').on('input', function(event){
    event.preventDefault();

    // Get the search query
    var query = $(this).val().toLowerCase();

    // Filter the list items based on the search query
    $('.systemStatus').each(function(){
        var statusText = $(this).find('span').html().toLowerCase();
        if (statusText.includes(query)){
            $(this).removeClass('hide');
        } else {
            $(this).addClass('hide');
        }
    });
});

// DELETE DOCUMENT STATUS
// To trigger delete status confirm
$('.deleteStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    $('#confirmDeleteStatusBtn').data('id', $(this).data('id'));
    $('#confirmDeleteStatusText').html("Confirm deleting status: " + $(this).data('value'));
});

// Delete status
$('#confirmDeleteStatusBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    var formData = new FormData;
    var statusId = $(this).data('id');
    formData = {
        '_token' : $('#token').val(),
        'id' : statusId
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.deleteStatus,
        data: formData,
        success: function (data) {
            showNotification('Success', 'Status deleted successfully! <a href="#" class="reload">Reload</a>');

            // Remove the status in the front end
            $('#status' + statusId).remove();

            $('#confirmDeleteStatus').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error', 'Error deleting status.');
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
});