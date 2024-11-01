{{-- Document Preview    --}}
<div class="modal fade" id="documentPreview" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered documentPreview" role="document" style="max-width: 1000px !important;">
        <div class="modal-content">
            <div class="modal-body container border rounded px-3 py-0">
                {{-- Breadcrumb here --}}
                <div class="row">
                    <div class="col-9 py-3">
                        <div class="row mb-3">
                            <div class="col">
                                <div class="row mb-0">
                                    <div class="col mb-0">
                                        <h2 id="documentSubject" class="mb-0"></h2>
                                    </div>
                                </div>
                                <div class="row" style="font-size: 12px;">
                                    <div class="col">
                                        <span id="documentType"></span>
                                        <span>/</span>
                                        <span id="documentCategory"></span>
                                    </div>                    
                                </div>
                            </div>
        
                            <div class="col">
                                <div class="row">
                                    <div class="col mr-1">
                                        <span><strong>Date Last Modified: </strong></span>
                                    </div>
                                    <div class="col" style="text-align: left;">
                                        <span id="documentLastModifiedDate"></span>                                
                                    </div>
                                </div>
        
                                <div class="row">
                                    <div class="col mr-1">
                                        <span><strong>Last Modified By: </strong></span>
                                    </div>
                                    <div class="col" style="text-align: left;">
                                        <span id="documentLastModifiedBy"></span>
                                    </div>
                                </div>
                            </div>              
                        </div>
        
                        <div class="row mb-2">
                            <div class="col-auto">
                                <button type="button" class="btn btn-primary" id="updateDocumentMenuBtn" data-id=""><i class='bx bx-edit-alt' style="font-size: 15px;"></i> Update Document</button>
                                <button type="button" class="btn btn-primary" id="viewDocumentHistoryBtn" data-id=""><i class='bx bx-history' style="font-size: 15px;"></i> View Document History</button>
                                <button type="button" class="btn btn-primary" id="viewDocumentAttachmentsBtn" data-id=""><i class='bx bx-paperclip bx-rotate-90' style="font-size: 15px;"></i> View Attachments</button>
                            </div>
                        </div>
        
                        <div class="row border-top" style="height: 650px; max-height: 650px;">
                            <div id="documentInfoContainer" class="col container" style="display: none;">
                            </div>

                            <div id="updateDocument" class="col container" style="overflow-x: none; max-height: inherit; overflow-y: scroll; display: none;">
                                <div class="row justify-content-between">
                                <div class="col p-2">
                                    <form class="editContent" id="editDocumentForm" method="post">
                                        <input type='hidden' name='document_id' id="documentId">
                                        
                                        <div class="col">
                                            <div class="row mb-2">
                                                <!-- Document Type -->
                                                <div class="col">
                                                    <label for="editUploadDocType">Document Type</label>
                                                    <select id="editUploadDocType" class="editInput" name="type">
                                                        <option value="">Select the Type of Document</option>
                                                        @foreach ($docTypes as $index => $docType)
                                                            @if ($docType->value !== 'default')
                                                                <option value="{{$docType->value}}">{{$docType->value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="error" id="editTypeError" style="display:none;"></span>
                                                </div>
            
                                                <!-- Series No. and Memo No. -->
                                                <div class="col" id="editMemoInfo" style="display: none;">
                                                    <div class="row">
                                                        <div class="col">
                                                            <label for="editUploadSeriesNo">Series No.</label>
                                                            <input id="editUploadSeriesNo" class="editInput" type="number" name="seriesNo" min="1" max="99999">
                                                            <span class="error" id="editSeriesError" style="display:none;"></span>
                                                        </div>
                                                        <div class="col">
                                                            <label for="editUploadMemoNo">Memo No.</label>
                                                            <input id="editUploadMemoNo" class="editInput" type="number" name="memoNo" min="1" max="99999">
                                                            <span class="error" id="editMemoError" style="display:none;"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
            
                                            <!-- From -->
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label for="editUploadFrom">From</label>
                                                    <select class="form-control selectpicker editInput editFrom p-0 border mb-1" id="editUploadFrom" name="sender" data-live-search="true" multiple title="Select Sender (From)">
                                                        @foreach($groups as $group)
                                                            <option style="font-size: 15px !important;" value="{{ $group['id'] }}" data-level="{{$group['level']}}" data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input id="editUploadFromText" class="editInput mb-2" type="text" name="sender" style="display: none;" placeholder="Other Sender">
            
                                                    <button type="button" class="btn btn-primary p-1 otherBtn" id="editOtherSender">Others</button>
                                                    <span class="error" id="editSenderError" style="display:none;"></span>
                                                </div>
                                            </div>
            
                                            <!-- To -->
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label for="editUploadTo">To</label>
                                                    <select class="form-control selectpicker editInput editTo p-0 border mb-1" id="editUploadTo" name="recipient" data-live-search="true" multiple title="Select Recipient (To)">
                                                        @foreach($groups as $group)
                                                            <option style="font-size: 15px !important;" value="{{ $group['id'] }}" data-level="{{$group['level']}}" data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                    <input id="editUploadToText" class="editInput mb-2" type="text" name="recipient" style="display: none;" placeholder="Other Recipient">
                                                    <button type="button" class="btn btn-primary p-1 otherBtn" id="editOtherRecipient">Others</button>
                                                    <span class="error" id="editRecipientError" style="display:none;"></span>
                                                </div>
                                            </div>
                                        </div>
            
                                        <div class="col">
                                            <!-- Assignee -->
                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label for="editUploadAssignee">Assignee</label>
                                                    <select id="editUploadAssignee" class="editInput" name="assignee">
                                                        <option value="">Select Assignee</option>
                                                        @foreach ($roles as  $role)
                                                            @if ($role->value !== 'default' && $role->value !== 'Admin')
                                                                <option value="{{$role->value}}">{{$role->value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="error" id="editAssigneeError" style="display:none;"></span>
                                                </div>

                                                <div class="col">
                                                    <label for="editUploadCategory">Category</label>
                                                    <select id="editUploadCategory" class="editInput" name="category">
                                                        <option value="">Select Category</option>
                                                        <option value="Incoming">Incoming</option>
                                                        <option value="Outgoing">Outgoing</option>
                                                        <option value="Archived">Archived</option>
                                                    </select>
                                                    <span class="error" id="editCategoryError" style="display:none;"></span>
                                                </div>

                                                <!-- Status -->
                                                <div class="col">
                                                    <label for="editUploadStatus">Status</label>
                                                    <select id="editUploadStatus" class="editInput" name="status">
                                                        <option value="">Select Document Status</option>
                                                        @foreach ($docStatuses as $docStatus)
                                                            @if ($docStatus->value !== 'default')
                                                                <option value="{{$docStatus->value}}">{{$docStatus->value}}</option>
                                                            @endif
                                                        @endforeach
                                                    </select>
                                                    <span class="error" id="editStatusError" style="display:none;"></span>
                                                </div>
                                            </div>
        
                                            <div class="row mb-2">
                                                <!-- Subject -->
                                                <div class="col">
                                                    <label for="editUploadSubject">Subject</label>
                                                    <textarea id="editUploadSubject" class="editInput" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                                                    <span class="error" id="editSubjectError" style="margin-top: -8px; display:none;"></span>
                                                </div>

                                                <!-- Document Date -->
                                                <div class="col">
                                                    <label for="editUploadDate">Date</label>
                                                    <input id="editUploadDate" type="date" class="editInput"  name="date">
                                                    <span class="error" id="editDateError" style="display:none;"></span>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <div class="col">
                                                    <label for="editUploadSoftcopy">Document Attachment/s: </label>
                                                    <div class="container rounded border p-2 d-flex justify-content-center align-items-center editUploadFiles" data-value="none" style="flex-wrap: wrap; height: 200px;">
                                                        <div>No files added</div>
                                                    </div>
                                                
                                                    <div class="form-group">
                                                        <label class="custom-file">
                                                            <label class="custom-file-label editInput" for="softcopy" id="editFileLink">Add file/s to the document</label>
                                                            <input type="file" id="editSoftcopy" class="editUploadInput custom-file-input" placeholder="Choose File" name="editFiles[]" multiple>
                                                        </label>
                                                    </div>
                                                    <span class="error" id="editFileError" style="display:none;"></span>
                                                </div>
                                            </div>

                                            <div class="row mb-2">
                                                <!-- Description -->
                                                <div class="col">
                                                    <label for="editDescription">Description</label>
                                                    <input type="text" id="editDescription" class="editInput">
                                                </div>
                                                <span class="error" id="editDescriptionError" style="display:none;"></span>
                                            </div>

                                            <div class="row align-items-end justify-content-end">
                                                <div class="col-auto">
                                                    <button id="submitEditDocumentBtn" class="btn btn-primary editButton"><i class='bx bxs-edit-alt'></i> Edit</button>
                                                    <button id="clearEditBtn" class="btn btn-warning editButton"><i class='bx bxs-eraser'></i>  Clear</button>
                                                </div>
                                            </div>
                                        </div>

                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Version or Attachment List --}}
                <div class="col-3 border-left p-0">
                    <div class="py-3">
                        <h5 id="documentInfoTitle" class="ml-2 mb-2"></h5>
                        <ul class="list-group list-group-flush" style="overflow-x: hidden; max-width: inherit;" id="documentInfoList">
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>