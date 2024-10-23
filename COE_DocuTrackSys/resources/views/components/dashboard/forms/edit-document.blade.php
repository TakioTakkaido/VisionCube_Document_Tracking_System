<div class="modal fade" id="editDocument" tabindex="-1" role="dialog" aria-labelledby="editDocumentModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="container" style="background-color:white;">
                <div class="row">
                    <!-- Column 1 -->
                    <div class="col-sm-6">
                        <div id="pdfPreview" style="margin-left: 10px; margin-top: 20px;">
                            <label>PDF Preview:</label>
                            <iframe 
                                id="pdfIframe" 
                                type="application/pdf" 
                                src="" 
                                frameborder="0" 
                                style="width: 95%; height: 80vh;">
                            </iframe>
                        </div>
                    </div>

                    <!-- Column 2 -->
                    <div class="col-sm-3">
                        <form class="editContent" id="editDocumentForm" method="post">
                            @csrf
                            @method('POST') 
                            <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                            <input type='hidden' name='document_id' id="documentId">
                            <input type='hidden' name='owner_id' id="ownerId">

                            <!-- Document Type -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="editDocType">Document Type</label>
                                    <select id="editDocType" class="editInput" name="type">
                                        <option value="">Select the Type of Document</option>
                                        @foreach ($docTypes as $index => $docType)
                                            @if ($docType->value !== 'default')
                                                <option value="{{$docType->value}}">{{$docType->value}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="error" id="typeError" style="display:none;"></span>
                                </div>
                            </div>

                            <!-- Series No. and Memo No. -->
                            <div class="row mb-2" id="memoInfo" style="display: none;">
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-sm-6">
                                            <label for="editSeriesNo">Series No.</label>
                                            <input id="editSeriesNo" class="editInput" type="number" name="seriesNo" min="1" max="99999">
                                            <span class="error" id="seriesError" style="display:none;"></span>
                                        </div>
                                        <div class="col-sm-6">
                                            <label for="editMemoNo">Memo No.</label>
                                            <input id="editMemoNo" class="editInput" type="number" name="memoNo" min="1" max="99999">
                                            <span class="error" id="memoError" style="display:none;"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Assignee -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="editAssignee">Assignee</label>
                                    <select id="editAssignee" class="editInput" name="assignee">
                                        <option value="">Select Assignee</option>
                                        @foreach ($roles as  $role)
                                            @if ($role->value !== 'default' && $role->value !== 'Admin')
                                                <option value="{{$role->value}}">{{$role->value}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="error" id="assigneeError" style="display:none;"></span>
                                </div>
                            </div>

                            <!-- Category -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="editCategory">Category</label>
                                    <select id="editCategory" class="editInput" name="category">
                                        <option value="">Select Category</option>
                                        @foreach ($docCategories as $docCategory)
                                            @if ($docCategory->value !== 'default')
                                                <option value="{{$docCategory->value}}">{{$docCategory->value}}</option>
                                            @endif
                                        @endforeach
                                    </select>
                                    <span class="error" id="categoryError" style="display:none;"></span>
                                </div>
                            </div>

                            <!-- Document (Softcopy) -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="editSoftcopy">Document (Softcopy)</label>
                                    <div class="custom-file">
                                        <input type="file" id="softcopy" class="custom-file-input" name="file">
                                        <label class="custom-file-label editInput" for="softcopy">Choose file</label>
                                        <span class="error" id="fileError" style="display:none;"></span>
                                    </div>
                                </div>
                            </div>

                            <!-- Date -->
                            <div class="row mb-2">
                                <div class="col-12">
                                    <label for="editDate">Date</label>
                                    <input id="editDate" type="date" class="editInput"  name="date">
                                    <span class="error" id="dateError" style="display:none;"></span>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- Column 3 -->
                    <div class="col-sm-3">
                        <!-- From -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="editFrom">From</label>
                                <select class="form-control selectpicker editInput editFrom p-0 border mb-1" id="editFrom" name="sender" data-live-search="true" multiple title="Select Sender (From)">
                                    @foreach($groups as $group)
                                        <option style="font-size: 15px !important;" value="{{ $group['id'] }}" data-level="{{$group['level']}}" data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                            {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input id="editFromText" class="editInput mb-2" type="text" name="sender" style="display: none;" placeholder="Other Sender">
                                <button type="button" class="btn btn-primary p-1 otherBtn" id="otherSender">Others</button>
                                <span class="error" id="senderError" style="display:none;"></span>
                            </div>
                        </div>

                        <!-- To -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="editTo">To</label>
                                <select class="form-control selectpicker editInput editTo p-0 border mb-1" id="editTo" name="recipient" data-live-search="true" multiple title="Select Recipient (To)">
                                    @foreach($groups as $group)
                                        <option style="font-size: 15px !important;" value="{{ $group['id'] }}" data-level="{{$group['level']}}" data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                            {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                        </option>
                                    @endforeach
                                </select>
                                <input id="editToText" class="editInput mb-2" type="text" name="recipient" style="display: none;" placeholder="Other Recipient">
                                <button type="button" class="btn btn-primary p-1 otherBtn" id="otherRecipient">Others</button>
                                <span class="error" id="recipientError" style="display:none;"></span>
                            </div>
                        </div>

                        <!-- Status -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="editStatus">Status</label>
                                <select id="editStatus" class="editInput" name="status">
                                    <option value="">Select Document Status</option>
                                    @foreach ($docStatuses as $docStatus)
                                        @if ($docStatus->value !== 'default')
                                            <option value="{{$docStatus->value}}">{{$docStatus->value}}</option>
                                        @endif
                                    @endforeach
                                </select>
                                <span class="error" id="statusError" style="display:none;"></span>
                            </div>
                        </div>

                        <!-- Subject -->
                        <div class="row mb-2">
                            <div class="col-12">
                                <label for="editSubject">Subject</label>
                                <textarea id="editSubject" class="editInput" name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                                <span class="error" id="subjectError" style="margin-top: -8px; display:none;"></span>
                            </div>
                        </div>

                        <!-- Buttons -->
                        <div class="row justify-content-end align-items-end">
                            <div class="col-12">
                                <button id="updateBtn" class="btn btn-primary editButton">Edit</button>
                                <button id="clearBtn" class="btn btn-danger editButton">Clear</button>
                                <button id="cancelBtn" class="btn btn-secondary editButton">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
