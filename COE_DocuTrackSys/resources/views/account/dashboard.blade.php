{{-- 
VISION CUBE SOFTWARE CO. 

View: Dashboard
The dashboard of the COE Document Tracking System.
It contains:
-Displays the recent documents created in the system.
-Displays the recent documents created in the system.
-Allows the user to upload, edit, and delete document
-Allows the user to view various document versions
-Allows the user to also access its account to edit information and logout

Contributor/s: 
Calulut, Joshua Miguel C. 
Sanchez, Shane David U.
--}}

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    {{-- Title --}}
    <title>Dashboard | Document Tracking System</title>

    {{-- Boxicons --}}
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>

    {{-- JQuery --}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>

    {{-- Bootstrap --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" 
    integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" 
    integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

    {{-- Bootstrap Select --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/bootstrap-select.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.14/dist/js/i18n/defaults-*.min.js"></script>

    {{-- Datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    {{-- Assets --}}
    <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
    @vite(['resources/css/dashboard.css'])

    {{-- Scripts --}}
    @vite([
        // Homepage
        'resources/js/dashboard/homepage.js',

        // Panel
        'resources/js/dashboard/topPanel.js',
        'resources/js/dashboard/sidePanel.js',

        // Upload Form
        'resources/js/dashboard/uploadForm.js',

        // Edit Form
        'resources/js/dashboard/editForm.js',

        // System Settings
        // Account
        'resources/js/dashboard/systemSettings/account/access.js',
        'resources/js/dashboard/systemSettings/account/addAccount.js',

        // Document
        'resources/js/dashboard/systemSettings/document/maintenance.js',
        'resources/js/dashboard/systemSettings/document/participant.js',
        'resources/js/dashboard/systemSettings/document/group.js',
        'resources/js/dashboard/systemSettings/document/member.js',
        'resources/js/dashboard/systemSettings/document/type.js',
        'resources/js/dashboard/systemSettings/document/status.js',
        'resources/js/dashboard/systemSettings/document/fileExtension.js',

        // Notifications
        'resources/js/notification.js'
    ])
</head>
<body>
<script>
    $(document).ready(function(){
        $('body').css('pointer-events', 'none');
        var documentStatistics = $.ajax({
        method: 'GET',
        url: window.routes.getDocumentStatistics,
        success: function(response) {
            $('#incomingBadge').html(response.incoming);
            $('#outgoingBadge').html(response.outgoing);
            $('#archivedBadge').html(response.archived);
        }});

        $.when(documentStatistics).then(function() {
            // Re-enable pointer events once loading is complete
            $('body').css('pointer-events', 'all');
            
            // Initialize the DataTable after AJAX completes
            $('#accountAccessTable').DataTable();
        });

        $('#homePageBtn').trigger('click');
    });
</script>

{{-- NOTIFICATION --}}
<x-notification />

{{-- TOP BAR --}}
<x-dashboard.top-panel />

{{-- MIDDLE PANEL --}}
<x-dashboard.middle-panel />

{{-- INFORMATION TABLES --}}
<x-dashboard.info.log />

<x-dashboard.info.document-version />

{{-- DOCUMENT VERSIONS --}}
<x-dashboard.tables.document-version />

{{-- DOCUMENT PREVIEW --}}
<x-dashboard.document-preview />



{{-- Routes retrieving document, since AJAX cannot get this as a link, when inserted directly --}}


<script>


    window.routes = {
        // Homepage Routes
        getDocumentStatistics: "{{route('document.getStatistics')}}",

        // Maintenance
        updateMaintenance: "{{route('settings.update')}}",

        // Accounts Routes
        logout: "{{route('account.logout')}}",
        showAccount: "{{route('account.show', ':id')}}",
        showAllActiveAccounts: "{{route('account.showAllActiveAccounts')}}",
        editAccountRole: "{{route('account.editAccountRole', [':id', ':role'])}}",
        showAllDeactivatedAccounts: "{{route('account.showAllDeactivatedAccounts')}}",
        deactivateAccount: "{{route('account.deactivate', ':id')}}",
        reactivateAccount: "{{route('account.reactivate', ':id')}}",
        updateRoleAccess: "{{route('account.editAccess')}}",
        
        // Document Routes
        showDocuments: "{{route('document.showAll', ':id')}}",
        showDocument: "{{route('document.show', ':id')}}",
        showDocumentVersions: "{{route('document.showDocumentVersions', ':id')}}",
        showAttachments: "{{route('document.showAttachments', ':id')}}",
        downloadDocument: "{{route('document.download', ':id')}}",
        editDocument: "{{route('document.edit', ':id')}}",
        moveDocument: "{{route('document.move')}}",
        uploadDocument: "{{route('document.upload')}}",
        previewDocument: "{{route('document.preview', ':id')}}",

        // Document Version Routes
        showDocumentVersion: "{{route('version.show', ':id')}}",

        // Attachment Routes
        showAttachment: "{{route('attachment.show', ':id')}}",

        // Logs
        showAllLogs: "{{route('log.showAll')}}",
        logInfo: "{{route('log.show', ':id')}}",
        
        // System Settings
        // Participant
        updateParticipant: "{{route('participant.update')}}",
        deleteParticipant: "{{route('participant.delete')}}",

        // Participant Group
        updateParticipantGroup: "{{route('participantGroup.update')}}",
        deleteParticipantGroup: "{{route('participantGroup.delete')}}",
        getParticipantGroupMembers : "{{route('participantGroup.getParticipantGroupMembers', ':id')}}",
        updateParticipantGroupMembers: "{{route('participantGroup.updateParticipantGroupMembers')}}",

        // Status
        updateStatus: "{{route('status.update')}}",
        deleteStatus: "{{route('status.delete')}}",

        // Type
        updateType: "{{route('type.update')}}",
        deleteType: "{{route('type.delete')}}",

        // File Extensions
        updateFileExtensions: "{{route('fileExtension.update')}}",
    };
</script>

</body>
</html>