// Event listener for the side panel buttons
// Upload button for uploading documents
$('#uploadBtn').on('click', function(event){
    // Remove previous errors
});

// Account Button
$('#accountBtn').on('click', function(event){
    // Show the dropdown
    $('#accountsDropdown').toggleClass('show');
});

// Account Dropdown Buttons
// Deactivated Accounts
$('#deactivatedBtn').on('click', function(event){
    // AJAX Request to get deactivated accounts into
    // the system

    $.ajax({
        method: "GET",
        url: window.routes.showDeactivatedAccounts,
        success: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Display the data at the accounts table
            console.log(data.accounts);

            // Log success message
            console.log('All deactivated accounts obtained.')
        },
        error: function (data) {
            // Parse the data from the json response
            var data = JSON.parse(data.responseText);

            // Log error
            console.log("Error occured while obtaining deactivated accounts")
            console.log(response.errors)
        }
    });
});


// Document Button
$('#documentBtn').on('click', function(event){
    // Show the dropdown
    $('#documentsDropdown').toggleClass('show');
});

// Document Dropdown Buttons
// Incoming Document
$('#incomingBtn').on('click', function(event){

});

// Outgoing Document
$('#outgoingBtn').on('click', function(event){

});

// Archived Button 
$('#archivedBtn').on('click', function(event){
    // Show the dropdown
    $('#archivedDropdown').toggleClass('show');
});

// Archived Dropdown Buttons
// Letters
$('#archivedLetterBtn').on('click', function(event){

});

// Requisitions
$('#archivedRequisitionsBtn').on('click', function(event){

});

// Memoranda
$('#archivedMemorandaBtn').on('click', function(event){

});