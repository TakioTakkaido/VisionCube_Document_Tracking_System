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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/css/bootstrap.min.css" 
    integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.3/dist/umd/popper.min.js" 
    integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.1.3/dist/js/bootstrap.min.js" 
    integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>

    {{-- Datatable --}}
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.8/css/dataTables.dataTables.css" />
    <script src="https://cdn.datatables.net/2.1.8/js/dataTables.js"></script>

    {{-- Assets --}}
    <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
    @vite(['resources/css/dashboard.css'])

    {{-- Scripts --}}
    @vite([
        // Panel
        'resources/js/dashboard/topPanel.js',
        'resources/js/dashboard/sidePanel.js',

        // Upload Form
        'resources/js/dashboard/uploadForm.js'

        // System Settings
        'resources/js/dashboard/systemSettings.js'
    ])
</head>
<body>

{{-- TOP BAR --}}
<x-dashboard.top-panel />

{{-- MIDDLE PANEL --}}
<x-dashboard.middle-panel />

{{-- UPLOAD FORM --}}
<x-dashboard.forms.upload />

{{-- EDIT DOCUMENT FORM --}}
<x-dashboard.forms.edit-document />

{{-- DOCUMENT PREVIEW --}}
<x-dashboard.document-preview />

{{-- VIEW ACCOUNT --}}
{{-- <x-dashboard.view-account /> --}}

{{-- Routes retrieving document, since AJAX cannot get this as a link, when inserted directly --}}
<script>
    window.routes = {
        // Accounts Routes
        create: "{{route('account.create')}}",
        logout: "{{route('account.logout')}}",
        showAccount: "{{route('account.show', ':id')}}",
        showAllActiveAccounts: "{{route('account.showAllActiveAccounts')}}",
        editAccountRole: "{{route('account.editAccountRole', [':id', ':role'])}}",
        showAllDeactivatedAccounts: "{{route('account.showAllDeactivatedAccounts')}}",
        reactivateAccount: "{{route('account.reactivate', ':id')}}",
        
        // Document Routes
        showIncoming: "{{route('document.showIncoming')}}",
        showOutgoing: "{{route('document.showOutgoing')}}",
        showArchived: "{{route('document.showArchived')}}",
        showDocument: "{{route('document.show', ':id')}}",
        showDocumentVersions: "{{route('document.showDocumentVersions', ':id')}}",
        downloadDocument: "{{route('document.download', ':id')}}",
        editDocument: "{{route('document.edit', ':id')}}",
        moveDocument: "{{route('document.move')}}",
        uploadDocument: "{{route('document.upload')}}",
        previewDocument: "{{route('document.preview', ':id')}}",
        showAllLogs: "{{route('log.showAll')}}",
        
        // System Settings
        updateStatus: "{{route('status.update')}}",
        deleteStatus: "{{route('status.delete')}}",
        updateFileExtensions: "{{route('fileExtension.update')}}",

        updateCategory: "{{route('category.update')}}",
        deleteCategory: "{{route('category.delete')}}",

        updateType: "{{route('type.update')}}",
        deleteType: "{{route('type.delete')}}",

        updateRoleAccess: "{{route('account.editAccess')}}",

        displayTable: "{{route('display.table')}}"
    };
</script>

</body>
</html>