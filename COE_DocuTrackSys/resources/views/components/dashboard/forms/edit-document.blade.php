<div class="modal fade" id="editDocument" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="flex-row">
        <div id="pdfPreview" style=" margin-left: 10px; margin-top: 20px;">
            <label>PDF Preview:</label>
            <iframe 
                id="pdfIframe" 
                type="application/pdf" 
                src="" 
                frameborder="0" 
                style="width: 95%; height: 80vh;">
            </iframe>
        </div>
        <div class="modal-body custom-modal-body">
            <!-- Modal content goes here -->
            <form class="uploadContent" id="editDocumentForm" method="post">
                @csrf
                @method('POST') 
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                <input type='hidden' name='document_id' id="documentId">
                <input type='hidden' name='owner_id' id="ownerId">
                <label for="editUploadDocType">Document Type:</label>
                <div class="flex-row">
                    <select id="editUploadDocType" class="editInput" name="type" placeholder="Enter the Type of Document">
                        <option value="">Select the Type of Document</option>
                        {{-- Obtained document types using Laravel--}}
                        @foreach ($docTypes as $index => $docType)
                            @if ($docType->value !== 'default')
                                <option value="{{$docType->value}}">{{$docType->value}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="error" id="editTypeError" style="display:none;"></span>

                    <div class="flex-row" id="editMemoInfo" style="display:none;">
                        <label for="editUploadSeriesNo">Series No.:</label>
                        <input id="editUploadSeriesNo" class="editInput" type="number" name="seriesNo" name="seriesNo" min="1" max="99999">
                        <span class="error"  id="editSeriesError" style="display:none;"></span>
        
                        <label for="editUploadMemoNo">Memo No.:</label>
                        <input id="editUploadMemoNo" class="editInput" type="number" name="memoNo" name="memoNo" min="1" max="99999">
                        <span class="error" id="editMemoError" style="display:none;"></span>

                    </div>
                </div>                    

                <div class="flex-row">
                    <label for="editUploadFrom">From:</label>
                    <select class="form-control selectpicker" style="border: 1px solid #ccc;" class="editInput" id="editUploadFrom" name="sender" data-live-search="true" multiple data-header="Select Sender (From)">
                        @foreach($groups as $group)
                        <option title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                            </option>
                        @endforeach
                    </select>                
                    <span>Others:
                        <input id="editUploadFromText" type="text" name="sender">
                    </span>
                    <span class="error" id="editSenderError" style="display:none;"></span>
                </div>
                
                
                <div class="flex-row">
                    <label for="editUploadTo">To:</label>
                    <select class="form-control selectpicker" style="border: 1px solid #ccc;" class="editInput" id="editUploadTo" name="recipient" data-live-search="true" multiple data-header="Select Recipient (To)">
                        @foreach($groups as $group)
                            <option title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                            </option>
                        @endforeach
                    </select>
                    <span>Others:
                        <input id="editUploadToText" type="text" name="recipient">
                    </span>
                    <span class="error" id="editRecipientError" style="display:none;"></span>
                </div>
                
                <div class="flex-row">
                    <label for="editUploadSubject">Subject:</label>
                    <textarea id="editUploadSubject" class="editInput" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                    <span class="error" id="editSubjectError" style="display:none;"></span>
                    
                    <label for="editUploadDate">Date:</label>
                    <input id="editUploadDate" class="editInput" type="date" name="date">
                    <span class="error" id="editDateError" style="display:none;"></span>
                </div>

                <div class="flex-row"> 
                    <div>
                        <label for="editUploadSoftcopy">Document (Softcopy):</label>
                        <input type="file" class="editInput" id="editSoftcopy" name="file">
                        <span id="fileLink" style="margin-left: 10px;">
                        </span>
                        <span class="error" id="editFileError" style="display:none;"></span>
                    </div>  
                    <div>
                        <label for="editUploadCategory">Category:</label>
                        <select id="editUploadCategory" class="editInput"  name="category" placeholder="SELECT">
                            <option value="">Select Category</option>
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
                        <select id="editUploadStatus" class="editInput" name="status">
                            <option value="">Select Document Status</option>
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
                    <select id="editUploadAssignee" class="editInput" name="assignee">
                        <option value="">Select Assignee</option>
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

                

            </form>
            
        </div>
        </div>
        <div class="modal-footer custom-modal-footer">
            {{-- PLACE UPDATING DOCUMENT --}}
            <button type="submit" id="submitEditDocumentBtn"    class="btn btn-primary">Edit</button>
            <button type="button" id="clearEditBtn"             class="btn btn-secondary" >Clear</button>
            <button type="button" id="cancelEditBtn"            class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
        </div>
    </div>
</div>