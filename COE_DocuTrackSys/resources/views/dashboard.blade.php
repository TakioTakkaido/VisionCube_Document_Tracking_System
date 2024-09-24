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
    <title>Document Tracking System</title>
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.1.7/css/dataTables.dataTables.css" />
    <link rel="icon" href="{{Vite::asset('resources/img/COE.png')}}" type="image/x-icon">
    
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>    
    <script src="https://cdn.datatables.net/2.1.7/js/dataTables.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    @vite([
        'resources/js/sidepanel-active.js',
        'resources/js/sidepanel-archived.js',
        'resources/js/uploadform.js',
        'resources/css/dashboard.css'
    ])
</head>
<body>
<!--...........TOP BAR.............-->
<div class="top-panel">
    <img src="{{Vite::asset('resources/img/COE.png')}}" alt="Logo" class="header-logo">
    <div class="header-text">Document Tracking System</div>
<div class="profile">
    <div class="account-info">
    <img src="{{Vite::asset('resources/img/user-photo.jpg')}}" alt="User Photo" class="account-photo">
    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#editAccountModal">
        {{$user->name}}  •  {{$user->role}}
    </button>
    </div>
</div>
</div>
<!--...........SIDE PANEL/BAR.............-->
<div class="side-panel">
    <div class="side-panel-section" id="dropdown-arrow">
        <a class="sidepanel-btn" id="outgoing-button">
            <span class="dropdown-arrow"><i class='bx bx-paper-plane' ></i>
            </span> Outgoing
        </a>
        <a class="sidepanel-btn" id="incoming-button">
            <span class="dropdown-arrow"><i class='bx bx-envelope' ></i>
            </span> Incoming
        </a>
        <a class="sidepanel-btn" id="archived-button">
            <span class="dropdown-arrow"><i class='bx bx-archive-in' ></i>
            </span> Archived
        </a>
        <div class="dropdown-container" id="archived-dropdown">
            <a><i class='bx bx-notepad'></i>Letters</a>
            <a><i class='bx bx-notepad'></i>Requistion</a>
            <a><i class='bx bx-notepad'></i>Memoranda</a>
        </div>
<!--.............BUTTON TRIGGER UPLOAD..............-->
    <button class="upload-btn" id="uploadBtn">
        <i class='bx bx-upload bx-flashing'></i>
        <span>Upload</span>
    </button>
<!--..............MODAL FORM....................-->      
    <div class="uploadModal" id="uploadModal">
        <form action="{{route('document.store')}}" id="uploadModalForm" method="post">
            @csrf
            @method('POST')
            <div class="uploadContent">
                <label for="uploadDocType">Document Type:</label>
                <select id="uploadDocType" name="type" placeholder="Enter the Type of Document">
                    <option value="" disabled selected>Select the Type of Document</option>
                    {{-- Obtained document types using Laravel--}}
                    @foreach ($docTypes as $index => $docType)
                        @if ($docType->value !== 'default')
                            <option value="{{$docType->value}}">{{$docType->value}}</option>
                        @endif
                    @endforeach
                </select>
                <span class="error" id="typeError" style="display:none;">This field is required!</span>
                
                <label for="uploadFrom">From:</label>
                <input type="text" id="uploadFrom" name="sender" placeholder="Enter the Sender's Name/Department">
                <span class="error" id="senderError" style="display:none;"></span>
        
                <label for="uploadTo">To:</label>
                <input type="text" id="uploadTo" name="recipient" placeholder="Enter the Recipient's Name/Department">
                <span class="error" id="recipientError" style="display:none;"></span>
        
                <label for="uploadSubject">Subject:</label>
                <textarea id="uploadSubject" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                <span class="error" id="subjectError" style="display:none;"></span>
        
                <div class="flex-row"> 
                    <div>                 
                        {{-- 
                            TEMPORARILY MADE NULLABLE FOR TESTING PURPOSES
                            NEEDED IMPLEMENTATION TO STORE FILES SECURELY AND
                            EFFECTIVELY
                        --}}
                        <label for="uploadSoftcopy">Document (Softcopy):</label>
                        <input type="file" id="softcopy" name="file">
                        <span class="error" id="fileError" style="display:none;"></span>
                    </div>  
                    <div>
                        <label for="uploadCategory">Category:</label>
                        <select id="uploadCategory" name="category" placeholder="SELECT">
                            <option value="" disabled selected>Select Category</option>
                            {{-- Obtained document categories using Laravel--}}
                            @foreach ($docCategories as $docCategory)
                                @if ($docCategory->value !== 'default')
                                    <option value="{{$docCategory->value}}">{{$docCategory->value}}</option>
                                @endif
                            @endforeach
                        </select>
                        @error('record')
                            
                        @enderror
                        <span class="error" id="categoryError" style="display:none;"></span>
                    </div>
                </div>
        
                <div class="flex-row">
                    <div>
                        <label for="uploadStatus">Status:</label>
                        <select id="uploadStatus" name="status">
                            <option value="" disabled selected>Select Document Status</option>
                            {{-- Obtained document statuses using Laravel--}}
                            @foreach ($docStatuses as $docStatus)
                                @if ($docStatus->value !== 'default')
                                    <option value="{{$docStatus->value}}">{{$docStatus->value}}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="error" id="statusError" style="display:none;"></span>
                    </div>
                    <div>
                        <label for="uploadAssignee">Assignee:</label>
                        <select id="uploadAssignee" name="assignee">
                            <option value="" disabled selected>Select Assignee</option>
                            {{-- Obtained assignees through account roles using Laravel--}}
                            @foreach ($roles as  $role)
                                @if ($role->value !== 'default' && $role->value !== 'Admin')
                                    <option value="{{$role->value}}">{{$role->value}}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="error" id="assigneeError" style="display:none;"></span>
                    </div>
                </div>
        
                <div class="button-group">
                    <button type="button" class="cancel-btn" id="cancelBtn">Cancel</button>
                    {{-- Submit form data using AJAX in uploadform.js --}}
                    <button type="submit" class="submit-btn" id="submitBtn" type="button">Submit</button>
                </div>
            </div>
        </form>
    </div>
    </div>
</div>

{{-- Document Table --}}
<table id="docuTable">
    <thead>
        <tr>
            <th>Type</th>
            <th>Status</th>
            <th>File</th>
            <th>Sender</th>
            <th>Recipient</th>
            <th>Subject</th>
            <th>Assignee</th>
            <th>Category</th>
        </tr>
    </thead>
    <tbody>        </tbody>
</table>

{{-- Modal for Editing Account --}}
<!-- Modal -->
<div class="modal fade" id="editAccountModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
<div class="modal-dialog" role="document">
    <div class="modal-content">
    <div class="modal-header custom-modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Edit Account</h5>
    </div>
    <div class="modal-body custom-modal-body">
        <!-- Modal content goes here -->
        <p>Edit account details here.</p>
    </div>
    <div class="modal-footer custom-modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <form method="POST" action="{{route('account.logout')}}">
            @csrf
            @method('POST')
            <button type="submit" class="btn btn-primary">Log Out</button>
        </form>
    </div>
    </div>
</div>
</div>
{{-- Routes retrieving document, since AJAX cannot get this as a link, when inserted directly --}}
<script>
    window.dashboardRoutes = {
        showIncoming: "{{ route('documents.showIncoming') }}",
        showOutgoing: "{{ route('documents.showOutgoing') }}",
        downloadUrl: "{{route('document.download', ':id')}}"
    };
</script>
<script>

    console.log("{{$errors}}");
</script>
</body>
</html>