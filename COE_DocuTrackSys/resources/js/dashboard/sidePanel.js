import { showAllActiveAccounts, showAllDeactivatedAccounts } from "./tables/account";
import { showArchived, showIncoming, showOutgoing } from "./tables/document";
import { showAllLogs } from "./tables/log";

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
// Active Accounts
$('#activeBtn').on('click', function(event){
    event.preventDefault();

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    // Show all active accounts
    showAllActiveAccounts();
});

// Deactivated Accounts
$('#deactivatedBtn').on('click', function(event){
    event.preventDefault();
    
    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    showAllDeactivatedAccounts();
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

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    showIncoming();
});

// Outgoing Document
$('#outgoingBtn').on('click', function(event){
    event.preventDefault();

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active');
    }

    // AJAX Request to get outgoing documents into
    // the system
    showOutgoing();
});

// Archived Button 
$('#archivedBtn').on('click', function(event){
    event.preventDefault();

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    // Show the dropdown
    showArchived();
});

// Log Button
$('#logBtn').on('click', function(event){
    event.preventDefault();

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
    
    showAllLogs();
});

// System Settings Button
$('#systemSettingsBtn').on('click', function (event) {
    event.preventDefault();

    // Show the dropdown
    $('#systemSettingsDropdown').toggleClass('show');
});

// Account System Settings
// Any table
// system settings
// Accoun settings
// Document Settingas
$('#accountSettingsBtn').on('click', function (event) {
    event.preventDefault();

    if (!$('.account-settings').hasClass('active')){
        $('.system-settings').addClass('active');
        $('.account-settings').addClass('active');
        $('.document-settings').removeClass('active');
        $('.dashboard-table').removeClass('active');
    }
});

$('#documentSettingsBtn').on('click', function (event) {
    event.preventDefault();

    if (!$('.document-settings').hasClass('active')){
        $('.system-settings').addClass('active');
        $('.document-settings').addClass('active'); 
        $('.account-settings').removeClass('active');
        $('.dashboard-table').removeClass('active');
    }
});