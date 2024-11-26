import { showNotification } from "../notification";
import { getNewDocuments } from "./homepage";
import { getDocumentStatistics } from "./homepage/documentStatistics";

// Upload Document
$('#submitDocumentForm').off('click').on('click', function(event) {
    event.preventDefault();

    $('#submitDocumentForm').prop('disabled', true);
    $('#clearUploadBtn').prop('disabled', true);

    var formData = new FormData();
    var senderArray = [];
    var recipientArray = [];

    var seriesRequired = $('#uploadSeriesNo').prop('required');
    var memoRequired = $('#uploadMemoNo').prop('required');
    
    for (var index = 0; index < $('#uploadFrom option').length; index++) {
        var uploadFromOption = $('#uploadFrom option')[index];
        if(uploadFromOption.selected){
            senderArray.push({
                parent: $($('#uploadFrom option')[index]).data('parent'),
                value: $($('#uploadFrom option')[index]).data('name'),
                level: $($('#uploadFrom option')[index]).data('level'),
            });
        }
    }

    for (var index = 0; index < $('#uploadTo option').length; index++) {
        var uploadToOption = $('#uploadTo option')[index];
        if(uploadToOption.selected){
            recipientArray.push({
                parent: $($('#uploadTo option')[index]).data('parent'),
                value: $($('#uploadTo option')[index]).data('name'),
                level: $($('#uploadTo option')[index]).data('level'),
            });
        }
    }

    formData.append('_token', $('meta[name="csrf-token"]').attr('content'));

    if ($('#uploadDocType').val()){
        formData.append('type', $('#uploadDocType').val());
    }
    
    var seriesNo;
    var memoNo;
    if ($('#uploadDocType').val() === 'Memoranda') {
        seriesNo = $('#uploadSeriesNo').val();
        memoNo  = $('#uploadMemoNo').val();
    }
    formData.append('series_number', seriesNo);
    formData.append('memo_number', memoNo);
    formData.append('subject', $('#uploadSubject').val());

    formData.append('seriesRequired', seriesRequired);
    formData.append('memoRequired', memoRequired);

    // Create the name of the sender/receiver
    // Go through every part of select form
    // If the input is selected, make the parent detection function
    // Append the created name to the overall name of the user

    var senderName = '';
    var start = false;
    var currentParent = '';
    var uploadFromOptions = $('#uploadFrom option');

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
        senderName = $('#uploadFromText').val();
    }
    formData.append('sender', senderName);
    formData.append('senderArray', JSON.stringify(senderArray));

    var recipientName = '';
    var start2 = false;
    var currentParent2 = '';
    var uploadToOptions = $('#uploadTo option');
    
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
                    for (var index2 = index - 1; index2 >= 0; index2--){
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
        recipientName = $('#uploadToText').val();
    }

    formData.append('recipient', recipientName);
    formData.append('recipientArray', JSON.stringify(recipientArray));

    formData.append('document_date', $('#uploadDate').val());

    if($('#uploadAssignee').val()){
        formData.append('assignee', $('#uploadAssignee').val());
    }
    
    if($('#uploadStatus').val()){
        formData.append('status', $('#uploadStatus').val());
        formData.append('color', $('#uploadStatus').data('color'));
    }
    
    if($('#uploadCategory').val()){
        formData.append('category', $('#uploadCategory').val());
    }

    var fileInput = $('#softcopy')[0];  
    for(var i = 0; i < fileInput.files.length; i++){
        formData.append('files[]', fileInput.files[i]);  // Correct file append
    }

    // Document Details
    formData.append('event_venue', $('#uploadEventVenue').val());
    formData.append('event_description', $('#uploadEventDescription').val());
    formData.append('event_date', $('#uploadEventDate').val());
    formData.append('drive_id', $('#documentFolder').val())

    $('body').css('cursor', 'progress');
    $.ajax({
        method: 'POST',
        url: window.routes.uploadDocument,
        data: formData,
        processData: false,  // Prevent jQuery from converting the data
        contentType: false,  // Prevent jQuery from overriding the content type
        success: function(response) {
            getDocumentStatistics();
            getNewDocuments();
            $('#submitDocumentForm').prop('disabled', false);
            $('#clearUploadBtn').prop('disabled', false);

            showNotification('Document upload success!');

            $('#clearUploadBtn').trigger('click');
        },
        error: function(data) {
            showNotification('Error in uploading document.');
            $('#submitDocumentForm').prop('disabled', false);
            $('#clearUploadBtn').prop('disabled', false);
            if (data.responseJSON.errors.type){
                $('#typeError').html(data.responseJSON.errors.type);
                $('#uploadDocType').css('border', '1px solid red');
                $('#uploadDocType').css('background-color', '#f09d9d');
                $('#typeError').css('display', 'block');
            }

            if (data.responseJSON.errors.series_number){
                $('#seriesError').html(data.responseJSON.errors.series_number);
                $('#seriesError').css('display', 'block');
                $('#uploadSeriesNo').css('border', '1px solid red');
                $('#uploadSeriesNo').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.memo_number){
                $('#memoError').html(data.responseJSON.errors.memo_number);
                $('#memoError').css('display', 'block');
                $('#uploadMemoNo').css('border', '1px solid red');
                $('#uploadMemoNo').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.sender){
                $('#senderError').html(data.responseJSON.errors.sender);
                $('#senderError').css('display', 'block');
                
                $('.uploadFrom .btn').css({
                    'border': '1px solid red',
                    'background-color': '#f09d9d'
                });

                $('#uploadFromText').css('border', '1px solid red');
                $('#uploadFromText').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.recipient){
                $('#recipientError').html(data.responseJSON.errors.recipient);
                $('#recipientError').css('display', 'block');

                $('.uploadTo .btn').css({
                    'border': '1px solid red',
                    'background-color': '#f09d9d'
                });

                $('#uploadToText').css('border', '1px solid red');
                $('#uploadToText').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.subject){
                $('#subjectError').html(data.responseJSON.errors.subject);
                $('#subjectError').css('display', 'block');
                $('#uploadSubject').css('border', '1px solid red');
                $('#uploadSubject').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.document_date){
                $('#dateError').html(data.responseJSON.errors.document_date);
                $('#dateError').css('display', 'block');
                $('#uploadDate').css('border', '1px solid red');
                $('#uploadDate').css('background-color', '#f09d9d');
            }
            
            if (data.responseJSON.errors.files || data.responseJSON.errors["files.0"]){
                $('#fileError').html(data.responseJSON.errors.files|| data.responseJSON.errors["files.0"]);
                $('#fileError').css('display', 'block');
                $('.uploadFiles').css('border', '1px solid red');
                $('.uploadFiles').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.category){
                $('#categoryError').html(data.responseJSON.errors.category);
                $('#categoryError').css('display', 'block');
                $('#uploadCategory').css('border', '1px solid red');
                $('#uploadCategory').css('background-color', '#f09d9d');
            }
            
            if (data.responseJSON.errors.status){
                $('#statusError').html(data.responseJSON.errors.status);
                $('#statusError').css('display', 'block');
                $('#uploadStatus').css('border', '1px solid red');
                $('#uploadStatus').css('background-color', '#f09d9d');
            }

            if (data.responseJSON.errors.assignee){
                $('#assigneeError').html(data.responseJSON.errors.assignee);
                $('#assigneeError').css('display', 'block');
                $('#uploadAssignee').css('border', '1px solid red');
                $('#uploadAssignee').css('background-color', '#f09d9d');
            }
            
            $('#submitDocumentForm').prop('disabled', false);
            $('#clearUploadBtn').prop('disabled', false);
        },
        beforeSend: function(){
            showNotification('Uploading...');
        },
        complete: function(){
            $('body').css('cursor', 'auto');
        }
    });
});

// Clear Upload Form
$('#clearUploadBtn').on('click', function (event){
    $('#uploadDocumentForm').trigger('reset');
    $('#uploadFrom').selectpicker('deselectAll');
    $('#uploadTo').selectpicker('deselectAll');

    $.each($('.uploadInput'), function () { 
        $(this).css('border', '1px solid #ccc');
        $(this).css('background-color', 'white');
    });

    $.each($('.error'), function () { 
        $(this).css('display', 'none');
    });

    $('.uploadFrom .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });

    $('.uploadTo .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });

    while (dt.items.length > 0) {
        dt.items.remove(globalDataTransfer); // Remove the first item repeatedly until all are removed
    }

    $('.deleteUploadFileBtn').trigger('click');
});

var pos;
// Event to trigger after clicking the options in the select
$('#uploadFrom').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#uploadFromText').val('');
    $('.uploadFrom .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });
    $('#senderError').css('display', 'none');

    pos = $(this).parent().find('.dropdown-menu.inner').scrollTop();
    var level = $(this.options[clickedIndex]).data('level');
    
    if (!$(this.options[clickedIndex]).data('parent')){
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
            var parentFound = false;  // To ensure we find a parent before unchecking

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
                    // Check if this parent has its own parent and continue
                    clickedIndex = index;  // Move up to the found parent's index
                }
                
                // If no parent is found in the loop, break
                if (!$(this.options[clickedIndex]).data('parent')) {
                    break;
                }
            }
        }
    }
    // Check the level of the rest of the stuff below the selected class
    
    $(this).selectpicker('refresh');
}).on('refreshed.bs.select', function(event){
    $(this).parent().find('.dropdown-menu.inner').scrollTop(pos);
});

$('#uploadTo').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#uploadToText').val('');

    $('.uploadTo .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });

    $('#recipientError').css('display', 'none');

    pos = $(this).parent().find('.dropdown-menu.inner').scrollTop();
    var level = $(this.options[clickedIndex]).data('level');

    // Check the level of the rest of the stuff below the selected class
    if (isSelected){
        for (var index = clickedIndex + 1; index < this.options.length; index++) {
            const element = this.options[index];
            if($(element).data('level') > level){
                element.selected = isSelected;
            } else {   
                break;
            }
        }
    } else {
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

                
                // Check if this parent has its own parent and continue
                clickedIndex = index;  // Move up to the found parent's index
            }
            
            // If no parent is found in the loop, break
            if (!$(this.options[clickedIndex]).data('parent')) {
                break;
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

$('#uploadDocType').on('input', function(event){
    event.preventDefault();
    $('#typeError').css('display', 'none');
})

$('#uploadSeriesNo').on('input', function(event){
    event.preventDefault();
    $('#seriesError').css('display', 'none');
})

$('#uploadMemoNo').on('input', function(event){
    event.preventDefault();
    $('#memoError').css('display', 'none');
})

$('#uploadSubject').on('input', function(event){
    event.preventDefault();
    $('#subjectError').css('display', 'none');
})

$('#uploadDate').on('input', function(event){
    event.preventDefault();
    $('#dateError').css('display', 'none');
})


var globalDataTransfer = new DataTransfer();

$('#softcopy').off('input').on('input', function(event){
    event.preventDefault();

    var oldFiles = $('#softcopy')[0].files;
    Array.from(oldFiles).forEach(function(file) {
        globalDataTransfer.items.add(file); // Add file to the DataTransfer object if it's not the deleted file
    });
    
    $('#softcopy')[0].files = globalDataTransfer.files;

    $('.uploadFilesList').html('');

    var uploadFilesList = `<ul class="list-group uploadFilesList" style="width:100%;">
                            </ul>`
    if($('.uploadFiles').data('value') == 'none'){
        $('.uploadFiles').html('');
        $('.uploadFiles').append(uploadFilesList);
    };

    Array.from($('#softcopy')[0].files).forEach((file, index) => {
        $('.uploadFilesList').append(`<li class="list-group-item d-flex justify-content-between align-items-center" data-id=${index}>
            <span class="text-left mr-auto">${file.name}</span >
            <button class="btn btn-primary deleteUploadFileBtn" data-id=${index}>
                <i class='bx bx-trash' style="font-size: 20px;"></i></button>
            </li>`);
    });
    

    $('.uploadFiles').data('value', '');

    $('.uploadFiles').css('overflow-y', 'scroll');

    $('.uploadFiles').css('background-color', 'white');
    $('#fileError').css('display', 'none');

    $('.fileUploadLabel').html('Document Attachment/s ('+$('#softcopy')[0].files.length+' Attachment/s Uploaded): <small style="color: red">*</small>')
});

$(document).on('click', '.deleteUploadFileBtn', function(event){
    event.preventDefault();
    event.stopPropagation();
    var deleteId = $(this).data('id');
    Array.from($('.uploadFilesList .list-group-item')).forEach((file, index) => {
        if ($(file).data('id') == deleteId){
            var dataTransfer = new DataTransfer();
            var deletedFileName = $(file).find('span').html();
            var files = $('#softcopy')[0].files;
            Array.from(files).forEach(function(file) {
                if (file.name !== deletedFileName) {
                    dataTransfer.items.add(file); // Add file to the DataTransfer object if it's not the deleted file
                }
            });

            $('#softcopy')[0].files = dataTransfer.files;
            $(file).remove();
        }
    });
    if ($('#softcopy')[0].files.length === 0) {
        $('.uploadFiles').html('<div>No files added</div>');
        $('.uploadFiles').data('value', 'none');
        $('.fileUploadLabel').html('Document Attachment/s: <small style="color: red">*</small>')
    } else {
        $('.fileUploadLabel').html('Document Attachment/s ('+$('#softcopy')[0].files.length+' Attachment/s Uploaded): <small style="color: red">*</small>')
    }
});

$('#uploadCategory').on('input', function(event){
    event.preventDefault();
    $('#categoryError').css('display', 'none');
});

$('#uploadStatus').on('input', function(event){
    event.preventDefault();
    $(this).data('color', $($('#uploadStatus')[0].options[($(this)[0].selectedIndex)]).data('color'));
    $(this).css('background-color', $($('#uploadStatus')[0].options[($(this)[0].selectedIndex)]).data('color'));
    $('#statusError').css('display', 'none');
});

$('#uploadAssignee').on('input', function(event){
    event.preventDefault();
    $('#assigneeError').css('display', 'none');
});

// For other text input, uncheck all of the choices
$('#uploadFromText').on('input', function(event){
    $('#uploadFrom').selectpicker('deselectAll');
    $('#uploadFrom').selectpicker('refresh');
    $('.uploadFrom .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });
    $('#senderError').css('display', 'none');
});

$('#uploadToText').on('input', function(event){
    $('#uploadTo').selectpicker('deselectAll');
    $('#uploadTo').selectpicker('refresh');
    $('.uploadTo .btn').css({
        'border': '1px solid #ccc',
        'background-color': 'white'
    });
    $('#recipientError').css('display', 'none');
});

// Show up the series no and memo number
$('#uploadDocType').on('input', function(event){
    event.preventDefault();

    if($(this).val() == 'Memoranda'){
        $('#memoInfo').css('display', 'flex');
        $('#uploadSeriesNo').prop('required', true);
        $('#uploadMemoNo').prop('required', true);
    } else {
        $('#memoInfo').css('display', 'none');
        $('#uploadSeriesNo').prop('required', false);
        $('#uploadMemoNo').prop('required', false);
    }
});

$('#otherSender').on('click', function(event){
    $('#uploadFromText').toggle();
    $('.uploadFrom').toggle();

    if (!$('#uploadFromText').is(':visible')) {
        $('#uploadFromText').val('');
        $('#uploadFromText').css('border', '1px solid #dee2e6');
        $('#uploadFromText').css('background-color', 'white');
    }

    if (!$('.uploadFrom').is(':visible')) {
        $('#uploadFrom').selectpicker('deselectAll');
        $('#uploadFrom').selectpicker('refresh');
        $('.uploadFrom .btn').css({
            'background-color': 'white'
        });

        $('.uploadFrom .btn .border').css({
            'border': '1px solid #dee2e6',
        });
    }
});

$('#otherRecipient').on('click', function(event){
    $('#uploadToText').toggle();
    $('.uploadTo').toggle();

    if (!$('#uploadToText').is(':visible')) {
        $('#uploadToText').val('');
        $('#uploadToText').css('border', '1px solid #dee2e6');
        $('#uploadToText').css('background-color', 'white');
    }

    if (!$('.uploadTo').is(':visible')) {
        $('#uploadTo').selectpicker('deselectAll');
        $('#uploadTo').selectpicker('refresh');
        $('.uploadTo .btn').css({
            'background-color': 'white'
        });

        $('.uploadTo .border').css({
            'border': '1px solid #dee2e6'
        })
    }
});

// Document Details
$('#documentDetailsBtn').off('click').on('click', function(event){
    console.log($('#documentDetails').css('display'));
    if ($('#documentDetails').css('display') === 'block'){
        $('#documentDetails').css('display', 'none');
    } else {
        $('#documentDetails').css('display', 'block');
    }
});