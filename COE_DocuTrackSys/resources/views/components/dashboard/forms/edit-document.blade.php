<div class="modal fade" id="editDocument" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        {{-- Display PDF viewer --}}
        {{-- <iframe src="documents/1UcOq0oCzMlw1eydvqjaf0pEbEw74eIUNxhq7IQY.pdf" frameborder="0" style="width:100%; height:1200px;"></iframe> --}}
        <div class="modal-body custom-modal-body">
            <!-- Modal content goes here -->
            <form class="uploadContent" id="editDocumentForm" method="post">
                @csrf
                @method('POST')
                <input type='hidden' name='document_id' id="documentId">
                <input type='hidden' name='owner_id' id="ownerId">
                <label for="editUploadDocType">Document Type:</label>
                <select id="editUploadDocType" name="type" placeholder="Enter the Type of Document">
                    <option value="" disabled selected>Select the Type of Document</option>
                    {{-- Obtained document types using Laravel--}}
                    @foreach ($docTypes as $index => $docType)
                        @if ($docType->value !== 'default')
                            <option value="{{$docType->value}}">{{$docType->value}}</option>
                        @endif
                    @endforeach
                </select>
                <span class="error" id="editTypeError" style="display:none;">This field is required!</span>
                    
                <label for="editUploadFrom">From:</label>
                <input type="text" id="editUploadFrom" name="sender" placeholder="Enter the Sender's Name/Department">
                <span class="error" id="ediSenderError" style="display:none;"></span>
        
                <label for="editUploadTo">To:</label>
                <input type="text" id="editUploadTo" name="recipient" placeholder="Enter the Recipient's Name/Department">
                <span class="error" id="editRecipientError" style="display:none;"></span>
        
                <label for="editUploadSubject">Subject:</label>
                <textarea id="editUploadSubject" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                <span class="error" id="editSubjectError" style="display:none;"></span>
        
                <div class="flex-row"> 
                    <div>
                        <label for="editUploadSoftcopy">Document (Softcopy):</label>
                        <input type="file" id="editSoftcopy" name="file">
                        <span id="fileLink" style="margin-left: 10px;">
                        </span>
                        <span class="error" id="editFileError" style="display:none;"></span>
                    </div>  
                    <div>
                        <label for="editUploadCategory">Category:</label>
                        <select id="editUploadCategory" name="category" placeholder="SELECT">
                            <option value="" disabled selected>Select Category</option>
                            {{-- Obtained document categories using Laravel--}}
                            @foreach ($docCategories as $docCategory)
                                @if ($docCategory->value !== 'default')
                                    <option value="{{$docCategory->value}}">{{$docCategory->value}}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="error" id="editCategoryError" style="display:none;"></span>
                    </div>
                </div>
        
                <div class="flex-row">
                    <div>
                        <label for="editUploadStatus">Status:</label>
                        <select id="editUploadStatus" name="status">
                            <option value="" disabled selected>Select Document Status</option>
                            {{-- Obtained document statuses using Laravel--}}
                            @foreach ($docStatuses as $docStatus)
                                @if ($docStatus->value !== 'default')
                                    <option value="{{$docStatus->value}}">{{$docStatus->value}}</option>
                                @endif
                            @endforeach
                        </select>
                        <span class="error" id="editStatusError" style="display:none;"></span>
                    </div>
                    <div>
                    <label for="editUploadAssignee">Assignee:</label>
                    <select id="editUploadAssignee" name="assignee">
                        <option value="" disabled selected>Select Assignee</option>
                        {{-- Obtained assignees through account roles using Laravel--}}
                        @foreach ($roles as  $role)
                            @if ($role->value !== 'Guest' && $role->value !== 'Admin')
                                <option value="{{$role->value}}">{{$role->value}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="error" id="editAssigneeError" style="display:none;"></span>
                    </div>
                </div>

                <div id="pdfPreview" style="margin-top: 20px;">
                    <label>PDF Preview:</label>
                    <iframe 
                        id="pdfIframe" 
                        type="application/pdf" 
                        src="" 
                        frameborder="0" 
                        style="width: 100%; height: 400px;">
                    </iframe>
                </div>

            </form>
        </div>
        <div class="modal-footer custom-modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            {{-- PLACE UPDATING DOCUMENT --}}
            <button type="submit" id="editDocumentBtn" class="btn btn-primary">Edit</button>
        </div>
        </div>
    </div>
</div>