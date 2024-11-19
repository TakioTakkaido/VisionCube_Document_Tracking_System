import { documentStatistics } from "./homepage";
import { getDocumentStatistics } from "./homepage/documentStatistics";
import { showActive, showDeactivated } from "./tables/account";
import { showDocument } from "./tables/document/show";
import { showLogs } from "./tables/log";

// Event listener for the side panel buttons
// Upload button for uploading documents
$('#homePageBtn').on('click', function(event){
    event.preventDefault();
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');
    if (!$('.homepage').hasClass('active')){
        $('.homepage').addClass('active');
        $('.dashboard-table').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    if($('#uploadDocumentBtn').data('upload') == 1){
        $('#reports').removeClass('active');
        $('#upload').addClass('active');
        $('#uploadDocumentBtn').data('upload', 1);
        $('#uploadDocumentBtn').html(`<strong><i class='bx bx-stats'></i>  Back to Statistics</strong>`);
    } else {
        documentStatistics();
        $('#reports').addClass('active');
        $('#upload').removeClass('active');
        $('#uploadDocumentBtn').data('upload', 0);
        $('#uploadDocumentBtn').html(`<strong><i class='bx bx-upload'></i>  Upload New Document</strong>`);
    }
});

// Upload Document Button
$('#uploadDocumentBtn').on('click', function(event){
    event.preventDefault();

    // Turn on the toggle
    if(!$(this).data('upload')){
        $(this).data('upload', 1);
        $(this).html(`<strong><i class='bx bx-stats'></i>  Back to Statistics</strong>`);
    } else {
        $(this).data('upload', 0);
        $(this).html(`<strong><i class='bx bx-upload'></i>  Upload New Document</strong>`);
        documentStatistics();
    }

    $('#reports').toggleClass('active');
    $('#upload').toggleClass('active');
})

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
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    // Show all active accounts
    showActive();
});

// Deactivated Accounts
$('#deactivatedBtn').on('click', function(event){
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    showDeactivated();
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
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    showDocument("Incoming");
});

// Outgoing Document
$('#outgoingBtn').on('click', function(event){
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active');
    }

    // AJAX Request to get outgoing documents into
    // the system
    showDocument("Outgoing");
});

// Trash Button 
$('#recycleBinBtn').on('click', function(event){
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    // Show the dropdown
    showDocument("Trash");
});

// Archived Button 
$('#archivedBtn').on('click', function(event){
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }

    // Show the dropdown
    showDocument("Archived");
});

// Log Button
$('#logBtn').on('click', function(event){
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.dashboard-table').hasClass('active')){
        $('.dashboard-table').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.system-settings').removeClass('active'); 
    }
    
    showLogs();
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
// Document Setting
$('#profileSettingsBtn').on('click', function (event) {
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.profile-settings').hasClass('active')){
        $('.system-settings').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').addClass('active');
        $('.account-settings').removeClass('active');
        $('.document-settings').removeClass('active');
        $('.dashboard-table').removeClass('active');
    }
});

$('#accountSettingsBtn').on('click', function (event) {
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.account-settings').hasClass('active')){
        $('.system-settings').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.account-settings').addClass('active');
        $('.document-settings').removeClass('active');
        $('.dashboard-table').removeClass('active');
    }
});

$('#documentSettingsBtn').on('click', function (event) {
    event.preventDefault();
    $('.home-btn').removeClass('active');
    $('.side-panel-section a').removeClass('active');
    $(this).addClass('active');

    if (!$('.document-settings').hasClass('active')){
        $('.system-settings').addClass('active');
        $('.homepage').removeClass('active');
        $('.profile-settings').removeClass('active');
        $('.document-settings').addClass('active'); 
        $('.account-settings').removeClass('active');
        $('.dashboard-table').removeClass('active');
    }
});