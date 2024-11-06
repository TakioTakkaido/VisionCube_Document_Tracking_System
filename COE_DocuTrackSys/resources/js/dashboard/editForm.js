import { showNotification } from "../notification";
import { getDocumentStatistics } from "./homepage/documentStatistics";
import { documentPreview } from "./tables/document";

// Edit Document Btn
var senderName = '';
var recipientName = '';

export function updateDocument(id){
    $('.documentVersion').addClass('disabled');
    $('.documentAttachment').addClass('disabled');

    $.ajax({
        method: 'GET',
        url: window.routes.showDocument.replace(':id', id),
        success: function (response) {
            console.log(response.document);
            $('#documentInfoContainer').hide();
            $('#updateDocument').show();

            $('#documentId').val(response.document.document_id);
            $('#editUploadDocType').val(response.document.type);
            $('#editUploadSeriesNo').val(response.document.series_number);
            $('#editUploadMemoNo').val(response.document.memo_number);

            if (response.document.type != 'Memoranda'){
                $('#editMemoInfo').css('display', 'none');
            } else {
                $('#editMemoInfo').css('display', 'block');
            }

            var senderName = response.document.sender;
            var senderArray = JSON.parse(response.senderArray);
            for (var index = 0; index < senderArray.length; index++) {
                const sender = senderArray[index];
                for (var index2 = 0; index2 < $('#editUploadFrom option').length; index2++) {
                    var element = $('#editUploadFrom option')[index2];
                    if ($(element).data('parent') === sender['parent'] &&
                        $(element).data('name') === sender['value']  && 
                        $(element).data('level') == sender['level']){
                        element.selected = true;
                    }
                }    
            }
            $('#editUploadFrom').selectpicker('refresh');
            if (senderArray.length === 0) {
                $('#editUploadFromText').val(senderName);
            }
            
            var recipientName = response.document.recipient;
            var recipientArray = JSON.parse(response.recipientArray);
            for (var index = 0; index < recipientArray.length; index++) {
                const recipient = recipientArray[index];
                for (var index2 = 0; index2 < $('#editUploadTo option').length; index2++) {
                    var element = $('#editUploadTo option')[index2];
                    if ($(element).data('parent') === recipient['parent'] &&
                        $(element).data('name') === recipient['value'] && 
                        $(element).data('level') == recipient['level']){
                        element.selected = true;
                        console.log('selected');
                    }
                }    
            }
            $('#editUploadTo').selectpicker('refresh');
            if (recipientArray.length === 0) {
                $('#editUploadToText').val(recipientName);
            }

            $('#editUploadDate').val(response.document.document_date);

            $('#editUploadSubject').val(response.document.subject);
            $('#editUploadCategory').val(response.document.category);
            $('#editUploadStatus').val(response.document.status);
            $('#editUploadAssignee').val(response.document.assignee);


            $('#updateDocumentMenuBtn').prop('disabled', false);
            $('#viewDocumentHistoryBtn').prop('disabled', false);
            $('#viewDocumentAttachmentsBtn').prop('disabled', false);
        },
        error: function (xhr){  
            console.log(xhr.responseJSON);
        }
    });
}

$('#submitEditDocumentBtn').off('click').on('click', function(event) {
    $('#submitEditDocumentBtn').prop('disabled', true);
    $('#clearEditBtn').prop('disabled', true);
    event.preventDefault();

    var formData = new FormData();
    var senderArray = [];
    var recipientArray = [];
    var seriesRequired = $('#editUploadSeriesNo').prop('required');
    var memoRequired = $('#editUploadMemoNo').prop('required');

    for (var index = 0; index < $('#editUploadFrom option').length; index++) {
        var uploadEditFromOption = $('#editUploadFrom option')[index];
        if(uploadEditFromOption.selected){
            senderArray.push({
                parent: $($('#editUploadFrom option')[index]).data('parent'),
                value: $($('#editUploadFrom option')[index]).data('name'),
                level: $($('#editUploadFrom option')[index]).data('level')
            });
        }
    }

    for (var index = 0; index < $('#editUploadTo option').length; index++) {
        var uploadEditToOption = $('#editUploadTo option')[index];
        if(uploadEditToOption.selected){
            recipientArray.push({
                parent: $($('#editUploadTo option')[index]).data('parent'),
                value: $($('#editUploadTo option')[index]).data('name'),
                level: $($('#editUploadTo option')[index]).data('level')
            });
        }
    }

    // Manually append fields from the form to the FormData object
    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));
    formData.append('document_id', $('#documentId').val());
    formData.append('type', $('#editUploadDocType').val());

    var seriesNo;
    var memoNo;
    if ($('#editUploadDocType').val() == 'Memoranda') {
        seriesNo = $('#editUploadSeriesNo').val();
        memoNo  = $('#editUploadMemoNo').val();
    }
    formData.append('seriesNo', seriesNo);
    formData.append('memoNo', memoNo);

    formData.append('subject', $('#editUploadSubject').val());

    formData.append('seriesRequired', seriesRequired);
    formData.append('memoRequired', memoRequired);

    var senderName = '';
    var start = false;
    var currentParent = '';
    var uploadFromOptions = $('#editUploadFrom option');
    
    for (var index = 0; index < uploadFromOptions.length; index++) {
        const element = uploadFromOptions[index];
        if (element.selected === true){
            // Determine if it has parent or not

            // This element is the parent
            if (!$(element).data('parent')){
                if (start) {senderName += ', ';}
                // It has a child
                if (!$(element).data('hasChild')){
                    senderName += $(element).data('name') + " (All)";
                } else {
                    senderName += $(element).data('name');
                }
                start = true;
                currentParent = element;
            } else {
                
                // It is a child
                // Find its parent by going back to the top
                if ($(element).data('parent') != $(currentParent).data('parent') &&
                    !currentParent.selected
                ){
                    // How many are selected
                    var count = 0;

                    // No choices are unchecked
                    var hasNoSelected = false;

                    // Index of the parent
                    var parentIndex;

                    // Level of the parent
                    var parentLevel;

                    // Find the parent
                    for (var index2 = index - 1; index2 >= 0; index2--){
                        const element2 = uploadFromOptions[index2];
                        if($(element2).data('level') < $(element).data('level')) {
                            parentIndex = index2;
                            parentLevel = $(element2).data('level');
                            break;
                        } 
                    }
                    
                    // Check what children are selected
                    for (var index3 = parentIndex + 1; index3 < uploadFromOptions.length; index3++){
                        const element3 = uploadFromOptions[index3];
                        // Check if its still a chidren
                        if($(element3).data('level') > parentLevel) {
                            if (element3.selected) {
                                count++;
                            } else {
                                hasNoSelected = true;
                            }
                        } else {
                            break;
                        }
                    }

                    if (count > 0) {
                        if (start) {
                            senderName += ', ';
                        }
                        start = true;
                        if (hasNoSelected) {
                            senderName += $(uploadFromOptions[parentIndex]).data('name') + " (" + count + " selected)";
                        } else {
                            senderName += $(uploadFromOptions[parentIndex]).data('name') + " (All)";
                        }
                    }

                    currentParent = element;
                }
            }
        }
    }
    if (senderName.length === 0){
        senderName = $('#editUploadFromText').val();
    }
    formData.append('sender', senderName);
    formData.append('senderArray', JSON.stringify(senderArray));

    var recipientName = '';
    var start2 = false;
    var currentParent2 = '';
    var uploadToOptions = $('#editUploadTo option');
    
    for (var index = 0; index < uploadToOptions.length; index++) {
        const element = uploadToOptions[index];
        if (element.selected === true){
            // Determine if it has parent or not

            // This element is the parent
            if (!$(element).data('parent')){
                if (start2) {recipientName += ', ';}
                // It has a child
                if (!$(element).data('hasChild')){
                    recipientName += $(element).data('name') + " (All)";
                } else {
                    recipientName += $(element).data('name');
                }
                start2 = true;
                currentParent2 = element;
            } else {
                // It is a child
                // Find its parent by going back to the top
                if ($(element).data('parent') != $(currentParent2).data('parent') &&
                    !currentParent2.selected
                ){
                    // How many are selected
                    var count = 0;

                    // No choices are unchecked
                    var hasNoSelected = false;

                    // Index of the parent
                    var parentIndex;

                    // Level of the parent
                    var parentLevel;

                    // Find the parent
                    for (var index2 = index - 1; index2 > 0; index2--){
                        const element2 = uploadToOptions[index2];
                        if($(element2).data('level') < $(element).data('level')) {
                            parentIndex = index2;
                            parentLevel = $(element2).data('level');
                            break;
                        } 
                    }
                    
                    // Check what children are selected
                    for (var index3 = parentIndex + 1; index3 < uploadFromOptions.length; index3++){
                        const element3 = uploadToOptions[index3];
                        // Check if its still a chidren
                        if($(element3).data('level') > parentLevel) {
                            if (element3.selected) {
                                count++;
                            } else {
                                hasNoSelected = true;
                            }
                        } else {
                            break;
                        }
                    }

                    if (count > 0) {
                        if (start2) {
                            recipientName += ', ';
                        }
                        start2 = true;
                        if (hasNoSelected) {
                            recipientName += $(uploadToOptions[parentIndex]).data('name') + " (" + count + " selected)";
                        } else {
                            recipientName += $(uploadToOptions[parentIndex]).data('name') + " (All)";
                        }
                    }

                    currentParent2 = element;
                }
            }
        }
    }
    if (recipientName.length === 0){
        recipientName = $('#editUploadToText').val();
    }
    formData.append('recipient', recipientName);
    formData.append('recipientArray', JSON.stringify(recipientArray));

    formData.append('document_date', $('#editUploadDate').val());

    formData.append('status', $('#editUploadStatus').val());
    formData.append('assignee', $('#editUploadAssignee').val());
    formData.append('category', $('#editUploadCategory').val());

    var fileInput = $('#editSoftcopy')[0];
    for(var i = 0; i < fileInput.files.length; i++){
        formData.append('files[]', fileInput.files[i]);  // Correct file append
    }

    formData.append('description', $('#editDescription').val());

    $('#updateDocumentMenuBtn').prop('disabled', true);
    $('#viewDocumentHistoryBtn').prop('disabled', true);
    $('#viewDocumentAttachmentsBtn').prop('disabled', true);

    var documentId = $('#documentId').val();
    console.log(documentId);
    $.ajax({
        method: 'POST',
        url: window.routes.editDocument.replace(':id', documentId),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            showNotification('Success', 'Document updated successfully!');
            
            getDocumentStatistics();
            documentPreview(documentId);
             
            $('#dashboardTable').DataTable().ajax.reload();
        },
        error: function(data) {
            showNotification('Error in updating document.');

            if (data.responseJSON.errors.type){
                $('#editTypeError').html(data.responseJSON.errors.type);
                $('#editUploadDocType').css('border', '1px solid red');
                $('#editUploadDocType').css('background-color', 'pink');
                $('#editTypeError').css('display', 'block');
            }

            if (data.responseJSON.errors.series){
                $('#editSeriesError').html(data.responseJSON.errors.series);
                $('#editSeriesError').css('display', 'block');
                $('#editUploadSeriesNo').css('border', '1px solid red');
                $('#editUploadSeriesNo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.memo){
                $('#editMemoError').html(data.responseJSON.errors.memo);
                $('#editMemoError').css('display', 'block');
                $('#editUploadMemoNo').css('border', '1px solid red');
                $('#editUploadMemoNo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.sender){
                $('#editSenderError').html(data.responseJSON.errors.sender);
                $('#editSenderError').css('display', 'block');
                $('#editUploadFrom').css('border', '1px solid red');
                $('#editUploadFrom').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.recipient){
                $('#editRecipientError').html(data.responseJSON.errors.recipient);
                $('#editRecipientError').css('display', 'block');
                $('#editUploadTo').css('border', '1px solid red');
                $('#editUploadTo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.subject){
                $('#editSubjectError').html(data.responseJSON.errors.subject);
                $('#editSubjectError').css('display', 'block');
                $('#editUploadSubject').css('border', '1px solid red');
                $('#editUploadSubject').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.date){
                $('#editDateError').html(data.responseJSON.errors.date);
                $('#editDateError').css('display', 'block');
                $('#editUploadDate').css('border', '1px solid red');
                $('#editUploadDate').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.file){
                $('#editFileError').html(data.responseJSON.errors.file);
                $('#editFileError').css('display', 'block');
                $('#editSoftcopy').css('border', '1px solid red');
                $('#editSoftcopy').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.description){
                $('#editDescriptionError').html(data.responseJSON.errors.description);
                $('#editDescriptionError').css('display', 'block');
                $('#editDescription').css('border', '1px solid red');
                $('#editDescription').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.category){
                $('#editCategoryError').html(data.responseJSON.errors.category);
                $('#editCategoryError').css('display', 'block');
                $('#editUploadCategory').css('border', '1px solid red');
                $('#editUploadCategory').css('background-color', 'pink');
            }
            
            if (data.responseJSON.errors.status){
                $('#editStatusError').html(data.responseJSON.errors.status);
                $('#editStatusError').css('display', 'block');
                $('#editUploadStatus').css('border', '1px solid red');
                $('#editUploadStatus').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.assignee){
                $('#editAssigneeError').html(data.responseJSON.errors.assignee);
                $('#editAssigneeError').css('display', 'block');
                $('#editUploadAssignee').css('border', '1px solid red');
                $('#editUploadAssignee').css('background-color', 'pink');
            }

            $('#updateDocumentMenuBtn').prop('disabled', false);
            $('#viewDocumentHistoryBtn').prop('disabled', false);
            $('#viewDocumentAttachmentsBtn').prop('disabled', false);

            $('#submitEditDocumentBtn').prop('disabled', false);
            $('#clearEditBtn').prop('disabled', false);
        }
    });
});

// Clear Upload Form
$('#clearEditBtn').on('click', function (event){
    $('#editDocumentForm').trigger('reset');
    $('#editUploadFrom').selectpicker('deselectAll');
    $('#editUploadTo').selectpicker('deselectAll');

    $.each($('.editInput'), function () { 
        $(this).css('border', '1px solid #ccc');
        $(this).css('background-color', 'white');
    });

    $.each($('.error'), function () { 
        $(this).css('display', 'none');
    });
});

var pos;
$('#editUploadFrom').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#editUploadFromText').val('');

    $(this).css('border', '1px solid #ccc');
    $(this).css('background-color', 'white');
    $('#editSenderError').css('display', 'none');

    pos = $(this).parent().find('.dropdown-menu.inner').scrollTop();
    var level = $(this.options[clickedIndex]).data('level');

    // Check the level of the rest of the stuff below the selected class
    if (isSelected){
        console.log('has no parent changed');
        for (var index = clickedIndex + 1; index < this.options.length; index++) {
            const element = this.options[index];
            if($(element).data('level') > level){
                element.selected = isSelected;
            } else {   
                break;
            }
        }
    } else {
        if (!isSelected){
            console.log('has parent changed');

            let parentFound = false;  // To ensure we find a parent before unchecking

            // Traverse upwards to find parent elements
            for (var index = clickedIndex - 1; index >= 0; index--) {
                const currentElement = this.options[index];
                const currentLevel = $(currentElement).data('level');
                const clickedLevel = $(this.options[clickedIndex]).data('level');

                // Find the first parent (level less than clicked element)
                if (currentLevel < clickedLevel) {
                    // Uncheck the parent
                    currentElement.selected = isSelected;
                    parentFound = true;

                    console.log('unchecked a parent at level ' + currentLevel);
                    
                    // Check if this parent has its own parent and continue
                    clickedIndex = index;  // Move up to the found parent's index
                }
                
                // If no parent is found in the loop, break
                if (!$(this.options[clickedIndex]).data('parent')) {
                    console.log('no parent anymore');
                    break;
                }
            }            
        }
        
    }

    $(this).selectpicker('refresh');
}).on('refreshed.bs.select', function(event){
    $(this).parent().find('.dropdown-menu.inner').scrollTop(pos);
});

$('#editUploadTo').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#editUploadToText').val('');

    $(this).css('border', '1px solid #ccc');
    $(this).css('background-color', 'white');
    $('#editRecipientError').css('display', 'none');

    pos = $(this).parent().find('.dropdown-menu.inner').scrollTop();
    var level = $(this.options[clickedIndex]).data('level');

    // Check the level of the rest of the stuff below the selected class
    if (isSelected){
        console.log('has no parent changed');
        for (var index = clickedIndex + 1; index < this.options.length; index++) {
            const element = this.options[index];
            if($(element).data('level') > level){
                element.selected = isSelected;
            } else {   
                break;
            }
        }
    } else {
        if (!isSelected) {
            console.log('has parent changed');
    
            let parentFound = false;  // To ensure we find a parent before unchecking
    
            // Traverse upwards to find parent elements
            for (var index = clickedIndex - 1; index >= 0; index--) {
                const currentElement = this.options[index];
                const currentLevel = $(currentElement).data('level');
                const clickedLevel = $(this.options[clickedIndex]).data('level');
    
                // Find the first parent (level less than clicked element)
                if (currentLevel < clickedLevel) {
                    // Uncheck the parent
                    currentElement.selected = isSelected;
                    parentFound = true;
    
                    console.log('unchecked a parent at level ' + currentLevel);
                    
                    // Check if this parent has its own parent and continue
                    clickedIndex = index;  // Move up to the found parent's index
                }
                
                // If no parent is found in the loop, break
                if (!$(this.options[clickedIndex]).data('parent')) {
                    console.log('no parent anymore');
                    break;
                }
            }
        }
    }
    
    

    $(this).selectpicker('refresh');
}).on('refreshed.bs.select', function(event){
    $(this).parent().find('.dropdown-menu.inner').scrollTop(pos);
});

// Event listener for removing the colored error
$.each($('.uploadInput'), function (index, value) { 
    $($('.uploadInput')[index]).on('input', function(event){
        event.preventDefault();
        $(this).css('border', '1px solid #ccc');
        $(this).css('background-color', 'white');
    })
});

$('#editUploadDocType').on('input', function(event){
    event.preventDefault();
    $('#editTypeError').css('display', 'none');
})

$('#editUploadSeriesNo').on('input', function(event){
    event.preventDefault();
    $('#editSeriesError').css('display', 'none');
})

$('#editUploadMemoNo').on('input', function(event){
    event.preventDefault();
    $('#editMemoError').css('display', 'none');
})

$('#editUploadSubject').on('input', function(event){
    event.preventDefault();
    $('#editSubjectError').css('display', 'none');
})

$('#editUploadDate').on('input', function(event){
    event.preventDefault();
    $('#editDateError').css('display', 'none');
})

$('#editSoftcopy').off('input').on('input', function(event){
    event.preventDefault();
    event.stopPropagation();
    var files = this.files;

    var editUploadFilesList = `<ul class="list-group editUploadFilesList" style="width:100%;">
                            </ul>`
    if($('.editUploadFiles').data('value') == 'none'){
        $('.editUploadFiles').html('');
        $('.editUploadFiles').append(editUploadFilesList);
    };

    Array.from(files).forEach((file, index) => {
        $('.editUploadFilesList').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id=${index}>
            <span class="text-left mr-auto">${file.name}</span >
            <button class="btn btn-primary deleteEditUploadFileBtn" data-id=${index} data-name=${file.name}>
                <i class='bx bx-trash' style="font-size: 20px;"></i></button>
            </li>`);
    });

    $('.editUploadFiles').data('value', '');

    $('.editUploadFiles').css('overflow-y', 'scroll');
    $('#editFileError').css('display', 'none');
});

$(document).on('click', '.deleteEditUploadFileBtn', function(event){
    event.preventDefault();
    event.stopPropagation();
    var deleteId = $(this).data('id');
    var fileName = $(this).data('name');
    Array.from($('.editUploadFilesList .list-group-item')).forEach((file, index) => {
        if ($(file).data('id') == deleteId){
            $(file).remove();
        }
    });

    Array.from($('#editSoftcopy').get(0).files).forEach(file => {
        console.log(file.name);
        console.log(fileName);
        if (file.name == fileName){
            file.remove();
        }
    })
});

$('#editUploadCategory').on('input', function(event){
    event.preventDefault();
    $('#editCategoryError').css('display', 'none');
});

$('#editDescription').on('input', function(event){
    event.preventDefault();
    $('#editDescriptionError').css('display', 'none');
});

$('#editUploadStatus').on('input', function(event){
    event.preventDefault();
    $('#editStatusError').css('display', 'none');
});

$('#editUploadAssignee').on('input', function(event){
    event.preventDefault();
    $('#editAssigneeError').css('display', 'none');
});

// For other text input, uncheck all of the choices
$('#editUploadFromText').on('input', function(event){
    $('#editUploadFrom').selectpicker('deselectAll');
    $('#editUploadFrom').selectpicker('refresh');
    $('#editSenderError').css('display', 'none');
});

$('#editUploadToText').on('input', function(event){
    $('#editUploadTo').selectpicker('deselectAll');
    $('#editUploadTo').selectpicker('refresh');
    $('#editRecipientError').css('display', 'none');
});

$('#editUploadDocType').on('input', function(event){
    event.preventDefault();

    if($(this).val() == 'Memoranda'){
        $('#editMemoInfo').css('display', 'block');
        $('#editUploadSeriesNo').prop('required', true);
        $('#editUploadMemoNo').prop('required', true);
    } else {
        $('#editMemoInfo').css('display', 'none');
        $('#editUploadSeriesNo').prop('required', false);
        $('#editUploadMemoNo').prop('required', false);
    }
});

$('#editOtherSender').on('click', function(event){
    $('#editUploadFromText').toggle();
    $('.editFrom').toggle();

    if (!$('#editUploadFromText').is(':visible')) {
        $('#editUploadFromText').val('');
        $('#editUploadFromText').css('border', '1px solid #dee2e6');
        $('#editUploadFromText').css('background-color', 'white');
    }

    if (!$('.editFrom').is(':visible')) {
        $('#editUploadFrom').selectpicker('deselectAll');
        $('#editUploadFrom').selectpicker('refresh');
        $('.editFrom .btn').css({
            'background-color': 'white'
        });

        $('.editFrom .btn .border').css({
            'border': '1px solid #dee2e6',
        });
    }
});

$('#editOtherRecipient').on('click', function(event){
    $('#editUploadToText').toggle();
    $('.editTo').toggle();

    if (!$('#editUploadToText').is(':visible')) {
        $('#editUploadToText').val('');
        $('#editUploadToText').css('border', '1px solid #dee2e6');
        $('#editUploadToText').css('background-color', 'white');
    }

    if (!$('.editTo').is(':visible')) {
        $('#editUploadTo').selectpicker('deselectAll');
        $('#editUploadTo').selectpicker('refresh');
        $('.uploadTo .btn').css({
            'background-color': 'white'
        });

        $('.editTo .border').css({
            'border': '1px solid #dee2e6'
        })
    }
});