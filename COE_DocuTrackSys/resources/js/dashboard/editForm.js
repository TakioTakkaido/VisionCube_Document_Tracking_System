// Edit Document Btn
var senderName = '';
var recipientName = '';

export function editDocument(id){
    $.ajax({
        method: 'GET',
        url: window.routes.showDocument.replace(':id', id),
        success: function (response) {
            $('#documentId').val(response.document.id);
            $('#ownerId').val(response.document.owner_id);
            $('#editUploadDocType').val(response.document.type);
            senderName = response.document.sender;
            var senderArray = JSON.parse(response.senderArray);
            for (var index = 0; index < senderArray.length; index++) {
                const sender = senderArray[index];
                console.log($('#editUploadFrom option').length);
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
            
            recipientName = response.document.recipient;
            var recipientArray = JSON.parse(response.recipientArray);
            for (var index = 0; index < recipientArray.length; index++) {
                const recipient = recipientArray[index];
                console.log($('#editUploadTo option').length);
                for (var index2 = 0; index2 < $('#editUploadTo option').length; index2++) {
                    var element = $('#editUploadTo option')[index2];
                    console.log($(element).data('name'));
                    console.log($(element).val())
                    if ($(element).data('parent') === recipient['parent'] &&
                        $(element).data('name') === recipient['value'] && 
                        $(element).data('level') == recipient['level']){
                        element.selected = true;
                        console.log('selected');
                    }
                }    
            }
            $('#editUploadTo').selectpicker('refresh');
            if (senderArray.length === 0) {
                $('#editUploadToText').val(recipientName);
            }

            $('#editUploadSubject').val(response.document.subject);
            $('#fileLink').html(response.document.file);
            $('#editUploadCategory').val(response.document.category);
            $('#editUploadStatus').val(response.document.status);
            $('#editUploadAssignee').val(response.document.assignee);
            $('#pdfIframe').attr('src', response.fileLink);

            $('#editDocument').modal('show');
        },
        error: function (xhr){  
            console.log(xhr.responseJSON);
        }
    });
}

$('#submitEditDocumentBtn').on('click', function(event) {
    event.preventDefault();

    var formData = new FormData();
    var senderArray = [];
    var recipientArray = [];

    for (var index = 0; index < $('#editUploadFrom').val().length; index++) {
        if($.inArray($($('#editUploadFrom option')[index]).val(), $('#uploadFrom').val())){
            senderArray.push({
                name: $('#editUploadFrom').val()[index],
                value: $($('#editUploadFrom option')[index]).data('name')
            });
        }
    }

    for (var index = 0; index < $('#editUploadTo').val().length; index++) {
        if($.inArray($($('#editUploadTo option')[index]).val(), $('#editUploadTo').val())){
            recipientArray.push({
                name: $('#editUploadTo').val()[index],
                value: $($('#editUploadTo option')[index]).data('name')
            });
        }
    }

    // Manually append fields from the form to the FormData object
    formData.append('_token', $('#token').val());
    formData.append('document_id', $('#documentId').val());
    formData.append('type', $('#editUploadDocType').val());
    formData.append('subject', $('#editUploadSubject').val());

    // var senderName = '';
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
                    for (var index2 = index - 1; index2 > 0; index2--){
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

    // var recipientName = '';
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

    formData.append('status', $('#editUploadStatus').val());
    formData.append('assignee', $('#editUploadAssignee').val());
    formData.append('category', $('#editUploadCategory').val());

    var fileInput = $('#editSoftcopy')[0];
    if (fileInput.files.length > 0) {
        console.log('hasfile');
        console.log(fileInput.files[0])
        formData.append('file', fileInput.files[0]);
    }

    $.ajax({
        method: 'POST',
        url: window.routes.editDocument.replace(':id', $('#documentId').val()),
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            alert('Document updated successfully');
            $('#editDocument').modal('hide');
            $('#dashboardTable').DataTable().ajax.reload();
        },
        error: function(xhr) {
            alert('Error: ' + xhr.responseJSON?.message || 'Update failed');
        }
    });
});

// Clear Upload Form
$('#clearEditBtn').on('click', function (event){
    $('#editDocumentForm').trigger('reset');
    $('#editUploadFrom').selectpicker('deselectAll');
    $('#editUploadTo').selectpicker('deselectAll');
});

var pos;
$('#editUploadFrom').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#editUploadFromText').val('');
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

    $(this).selectpicker('refresh');
}).on('refreshed.bs.select', function(event){
    $(this).parent().find('.dropdown-menu.inner').scrollTop(pos);
});

$('#editUploadTo').selectpicker().on('changed.bs.select', function(event, clickedIndex, isSelected, previousValue){
    event.preventDefault();
    $('#editUploadToText').val('');
    pos = $(this).parent().find('.dropdown-menu.inner').scrollTop();
    var level = $(this.options[clickedIndex]).data('level');
    $(this).data('name', 'chichi');

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
    
    

    $(this).selectpicker('refresh');
}).on('refreshed.bs.select', function(event){
    $(this).parent().find('.dropdown-menu.inner').scrollTop(pos);
});

// For other text input, uncheck all of the choices
$('#editUploadFromText').on('input', function(event){
    $('#editUploadFrom').selectpicker('deselectAll');
    $('#editUploadFrom').selectpicker('refresh');
});

$('#editUploadToText').on('input', function(event){
    $('#editUploadTo').selectpicker('deselectAll');
    $('#editUploadTo').selectpicker('refresh');
});