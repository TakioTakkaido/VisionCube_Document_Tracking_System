<div class="modal fade" id="editDocument" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
        <div class="modal-body custom-modal-body">
        <div class="container p-1" style="background-color:white;">
            <div class="row justify-content-between">
                <!-- Column 1 -->
                <div class="col border rounded p-2 mr-1">
                    <div id="pdfPreview">
                        <h6 class="p-0 font-weight-bold" style="font-size: 14px;">Document File Preview</h6>
                        <iframe 
                            id="pdfIframe" 
                            class="border rounded"
                            type="application/pdf" 
                            src="" 
                            frameborder="0" 
                            style="width: 100%; height: 90vh;"> 
                        </iframe>
                    </div>
                </div>

                {{-- Column 2 --}}
                <div class="col border rounded p-2">
                    <h6 class="p-0 font-weight-bold" style="font-size: 14px;">Edit Document</h6>
                    <form class="editContent" id="editDocumentForm" method="post">
                        @csrf
                        @method('POST')
                        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                        <input type='hidden' name='document_id' id="documentId">
                        <input type='hidden' name='owner_id' id="ownerId">
                        
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
                                <div class="col-12" id="editMemoInfo" style="display: none;">
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

                        <!-- Column 3 -->
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
                                        <option value="">Select Category</option>
                                        <option value="Incoming">Incoming</option>
                                        <option value="Outgoing">Outgoing</option>
                                        <option value="Archived">Archived</option>
                                    </select>
                                    <span class="error" id="editCategoryError" style="display:none;"></span>
                                </div>
                            </div>

                            <!-- Document (Softcopy) -->
                            <div class="row mb-2">
                                <div class="col">
                                    <label for="editUploadSoftcopy">Document (Softcopy)</label>
                                    <div class="custom-file">
                                        <input type="file" id="editSoftcopy" class="custom-file-input" name="file">
                                        <label class="custom-file-label editInput" for="softcopy" id="fileLink">Choose file</label>
                                        <span class="error" id="editFileError" style="display:none;"></span>
                                    </div>
                                </div>  

                                <div class="col">
                                    <label for="editUploadDate">Date</label>
                                    <input id="editUploadDate" type="date" class="editInput"  name="date">
                                    <span class="error" id="editDateError" style="display:none;"></span>
                                </div>
                            </div>

                            <div class="row mb-2">
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

                                <!-- Subject -->
                                <div class="col">
                                    <label for="editUploadSubject">Subject</label>
                                    <textarea id="editUploadSubject" class="editInput" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                                    <span class="error" id="editSubjectError" style="margin-top: -8px; display:none;"></span>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Buttons -->
            <div class="row p-2 justify-content-end align-items-end">
                <div class="col-auto">
                    <button id="submitEditDocumentBtn" class="btn btn-primary editButton"><i class='bx bxs-edit-alt'></i> Edit</button>
                    <button id="clearEditBtn" class="btn btn-warning editButton"><i class='bx bxs-eraser'></i>  Clear</button>
                    <button id="cancelBtn" class="btn btn-secondary editButton" data-dismiss="modal"><i class='bx bx-x'></i>Cancel</button>
                </div>
            </div>
        </div>

        </div>
    </div>
    </div>
</div>
