<div class="modal fade" id="uploadDocument" tabindex="-1" role="dialog" aria-hidden="true" aria-labelledby="exampleModalLabel">
    <div class="modal-dialog modal-lg" role="editAccount">
        <div class="modal-content">
        <div class="modal-body uploadDocument">
            
            {{-- Upload Document Form --}}
            <form class="uploadContent" id="uploadDocumentForm" method="post">
                @csrf
                @method('POST')
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

            </form>
        </div>

        <div class="modal-footer">
            {{-- Submit form data using AJAX in uploadform.js --}}
            <button type="submit" class="btn btn-primary" type="button">Submit</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal" id="cancelBtn">Cancel</button>
        </div>

        </div>
    </div>
</div>