//////////////////////////////////////////////////////////////////
// EDITING DOCUMENT TYPE

import { showNotification } from "../../../notification";
import {  } from "../../uploadForm";

// Edit Document Type
$('#addTypeBtn').on('click', function(event){
    // Prevent other events
    event.preventDefault();

    // Create new form data
    var formData = new FormData();
    // Create form data for submission
    formData = {
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'value': $('#addTypeText').val()
    }

    // Submit the data form
    $.ajax({
        method: "POST",
        url: window.routes.updateType,
        data: formData,
        success: function (data) {
           // Log success message
            showNotification('Success', 'Type added successfully!  <a href="#" class="reload">Reload</a>');
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
            showNotification('Error', 'Error made when editing type.');
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
        '_token' : $('meta[name="csrf-token"]').attr('content'),
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
            showNotification('Success', 'Type edited successfully!  <a href="#" class="reload">Reload</a>');

            // Update list group
            var newTypeText = $('#editTypeText').val();

            // Close edit type
            $('#type' + saveTypeBtn.data('id') + ' .closeEditBtn').trigger('click', newTypeText);
        },
        error: function (data) {
            showNotification('Error', 'Error made when editing type.');
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
                    data-toggle="modal" data-target="#confirmDeleteType"><i class='bx bx-trash' style="font-size: 20px;"></i>
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
        '_token' : $('meta[name="csrf-token"]').attr('content'),
        'id' : typeId
    }
    
    $.ajax({
        method: "POST",
        url: window.routes.deleteType,
        data: formData,
        success: function (data) {
            showNotification('Success', 'Type deleted successfully! <a href="#" class="reload">Reload</a>');

            // Remove the type in the front end
            $('#type' + typeId).remove();

            $('#confirmDeleteType').modal('hide');
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            showNotification('Error', 'Error deleting type.');
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