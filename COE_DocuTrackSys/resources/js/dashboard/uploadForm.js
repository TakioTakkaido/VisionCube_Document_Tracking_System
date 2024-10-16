// Open Upload Form
$('#uploadBtn').on('click', function (event) {
    event.preventDefault();

    $('#uploadDocument').modal('show');
});

// Upload Document
$('#submitDocumentForm').on('click', function(event) {
    $('#submitDocumentForm').prop('disabled', true);
    $('#clearUploadBtn').prop('disabled', true);
    event.preventDefault();
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

    formData.append('_token', $('#token').val());
    formData.append('type', $('#uploadDocType').val());
    
    var seriesNo;
    var memoNo;
    if ($('#uploadDocType').val() == 'Type0') {
        seriesNo = $('#uploadSeriesNo').val();
        memoNo  = $('#uploadMemoNo').val();
    }
    formData.append('seriesNo', seriesNo);
    formData.append('memoNo', memoNo);

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

    formData.append('assignee', $('#uploadAssignee').val());

    // if ($('#uploadCategory').val().length === 0){
    //     formData.append('category', $('#uploadCategory').val());
    // } else {
    //     formData.append('category', $('#uploadCategory').val());
    // }
    console.log($('#uploadCategory').val());
    formData.append('category', $('#uploadCategory').val());
    formData.append('status', $('#uploadStatus').val());

    var fileInput = $('#softcopy')[0];
    if (fileInput.files.length > 0) {
        formData.append('file', fileInput.files[0]);  // Correct file append
    }

    $.ajax({
        method: 'POST',
        url: window.routes.uploadDocument,
        data: formData,
        processData: false,  // Prevent jQuery from converting the data
        contentType: false,  // Prevent jQuery from overriding the content type
        success: function(response) {
            
            $('#uploadDocument').modal('hide');
            $('#documentTable').DataTable().ajax.reload();
            $('#submitDocumentForm').prop('disabled', false);
            $('#clearUploadBtn').prop('disabled', false);
        },
        error: function(data) {
            if (data.responseJSON.errors.type){
                $('#typeError').html(data.responseJSON.errors.type);
                $('#uploadDocType').css('border', '1px solid red');
                $('#uploadDocType').css('background-color', 'pink');
                $('#typeError').css('display', 'block');
            }

            if (data.responseJSON.errors.series){
                $('#seriesError').html(data.responseJSON.errors.series);
                $('#seriesError').css('display', 'block');
                $('#uploadSeriesNo').css('border', '1px solid red');
                $('#uploadSeriesNo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.memo){
                $('#memoError').html(data.responseJSON.errors.memo);
                $('#memoError').css('display', 'block');
                $('#uploadMemoNo').css('border', '1px solid red');
                $('#uploadMemoNo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.sender){
                $('#senderError').html(data.responseJSON.errors.sender);
                $('#senderError').css('display', 'block');
                $('#uploadFrom').css('border', '1px solid red');
                $('#uploadFrom').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.recipient){
                $('#recipientError').html(data.responseJSON.errors.recipient);
                $('#recipientError').css('display', 'block');
                $('#uploadTo').css('border', '1px solid red');
                $('#uploadTo').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.subject){
                $('#subjectError').html(data.responseJSON.errors.subject);
                $('#subjectError').css('display', 'block');
                $('#uploadSubject').css('border', '1px solid red');
                $('#uploadSubject').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.date){
                $('#dateError').html(data.responseJSON.errors.date);
                $('#dateError').css('display', 'block');
                $('#uploadDate').css('border', '1px solid red');
                $('#uploadDate').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.file){
                $('#fileError').html(data.responseJSON.errors.file);
                $('#fileError').css('display', 'block');
                $('#softcopy').css('border', '1px solid red');
                $('#softcopy').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.category){
                $('#categoryError').html(data.responseJSON.errors.category);
                $('#categoryError').css('display', 'block');
                $('#uploadCategory').css('border', '1px solid red');
                $('#uploadCategory').css('background-color', 'pink');
            }
            
            if (data.responseJSON.errors.status){
                $('#statusError').html(data.responseJSON.errors.status);
                $('#statusError').css('display', 'block');
                $('#uploadStatus').css('border', '1px solid red');
                $('#uploadStatus').css('background-color', 'pink');
            }

            if (data.responseJSON.errors.assignee){
                $('#assigneeError').html(data.responseJSON.errors.assignee);
                $('#assigneeError').css('display', 'block');
                $('#uploadAssignee').css('border', '1px solid red');
                $('#uploadAssignee').css('background-color', 'pink');
            }
            
            $('#submitDocumentForm').prop('disabled', false);
            $('#clearUploadBtn').prop('disabled', false);
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
});

var pos;
// Event to trigger after clicking the options in the select
$('#uploadFrom').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#uploadFromText').val('');
    $(this).css('border', '1px solid #ccc');
    $(this).css('background-color', 'white');
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

    $(this).css('border', '1px solid #ccc');
    $(this).css('background-color', 'white');
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

$('#softcopy').on('input', function(event){
    event.preventDefault();
    $('#fileError').css('display', 'none');
});

$('#uploadCategory').on('input', function(event){
    event.preventDefault();
    $('#categoryError').css('display', 'none');
});

$('#uploadStatus').on('input', function(event){
    event.preventDefault();
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
    $('#senderError').css('display', 'none');
});

$('#uploadToText').on('input', function(event){
    $('#uploadTo').selectpicker('deselectAll');
    $('#uploadTo').selectpicker('refresh');
    $('#recipientError').css('display', 'none');
});

// Show up the series no and memo number
$('#uploadDocType').on('input', function(event){
    event.preventDefault();

    if($(this).val() == 'Type0'){
        $('#memoInfo').css('display', 'block');
        $('#uploadSeriesNo').prop('required', true);
        $('#uploadMemoNo').prop('required', true);
    } else {
        $('#memoInfo').css('display', 'none');
        $('#uploadSeriesNo').prop('required', false);
        $('#uploadMemoNo').prop('required', false);
    }
});