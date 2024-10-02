// Event listener for the side panel buttons
// Upload button for uploading documents
$('#uploadBtn').on('click', function(event){
    event.preventDefault();
    // Remove previous errors
});

// Account Button
$('#accountBtn').on('click', function(event){
    event.preventDefault();
    // Show the dropdown
    $('#accountsDropdown').toggleClass('show');
});

// Account Dropdown Buttons
// Deactivated Accounts
$('#deactivatedBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get deactivated accounts into
    // the system

    $.ajax({
        type: "GET",
        url: window.routes.showAllDeactivatedAccounts,
        datatype: "json",
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the accounts table
            console.log("[ACCOUNTS:] " + data.accounts);

            // Log success message
            console.log("[SUCCESS:] " + data.success)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining deactivated accounts")
            console.log(data.errors)
        }
    });
});


// Document Button
$('#documentBtn').on('click', function(event){
    event.preventDefault();
    // Show the dropdown
    $('#documentsDropdown').toggleClass('show');
});

// Document Dropdown Buttons
// Incoming Document
$('#incomingBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get incoming documents into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showIncoming,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the documents table
            console.log(data.documents);

            // Log success message
            console.log(data.documents)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining incoming documents")
            console.log(data.errors)
        }
    });
});

// Outgoing Document
$('#outgoingBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get outgoing documents into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showOutgoing,
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the documents table
            console.log(data.documents);

            // Log success message
            console.log(data.documents)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining outgoing documents")
            console.log(data.errors)
        }
    });
});

// Archived Button 
$('#archivedBtn').on('click', function(event){
    event.preventDefault();
    // Show the dropdown
    $('#archivedDropdown').toggleClass('show');
});

// Archived Dropdown Buttons
// Letters
$('#archivedLetterBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get archived letters into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showArchived.replace(':id', 'Letter'),
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the documents table
            console.log(data.documents);

            // Log success message
            console.log(data.documents)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining archived letters")
            console.log(data.errors)
        }
    });
});

// Requisitions
$('#archivedRequisitionsBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get archived requisitions into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showArchived.replace(':id', 'Requisition'),
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the documents table
            console.log(data.documents);

            // Log success message
            console.log(data.documents)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining archived requisitions")
            console.log(data.errors)
        }
    });
});

// Memoranda
$('#archivedMemorandaBtn').on('click', function(event){
    event.preventDefault();
    // AJAX Request to get incoming memoranda into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showArchived.replace(':id', 'Memoranda'),
        success: function (data) {
            // Parse the data from the json response
            // var data = JSON.parse(response);

            // Display the data at the documents table
            console.log(data.documents);

            // Log success message
            console.log(data.documents)
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining archived memoranda")
            console.log(data.errors)
        }
    });
});