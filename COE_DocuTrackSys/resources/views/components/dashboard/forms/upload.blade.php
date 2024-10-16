<div class="modal fade" id="uploadDocument" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="editAccount">
        <div class="modal-content">
        <div class="modal-body uploadDocument">
            {{-- Upload Document Form --}}
            <form class="uploadContent" id="uploadDocumentForm" method="post">
                @csrf
                @method('POST')
                {{-- CSRF Token --}}
                <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
                
                <div class="flex-row">
                    <label for="uploadDocType">Document Type:</label>
                    <select id="uploadDocType" class="uploadInput" name="type">
                        <option value="">Select the Type of Document</option>
                        {{-- Obtained document types using Laravel--}}
                        @foreach ($docTypes as $index => $docType)
                            @if ($docType->value !== 'default')
                                <option value="{{$docType->value}}">{{$docType->value}}</option>
                            @endif
                        @endforeach
                    </select>
                    <span class="error" id="typeError" style="display:none;"></span>
                    
                    <div class="flex-row" id="memoInfo" style="display:none;">
                        <label for="uploadSeriesNo">Series No.:</label>
                        <input id="uploadSeriesNo" class="uploadInput" type="number" name="seriesNo" min="1" max="99999">
                        <span class="error"  id="seriesError" style="display:none;"></span>
        
                        <label for="uploadMemoNo">Memo No.:</label>
                        <input id="uploadMemoNo" class="uploadInput" type="number" name="memoNo" min="1" max="99999">
                        <span class="error" id="memoError" style="display:none;"></span>
                    </div>
                </div>
                
                <div class="flex-row">                
                    <label for="uploadFrom">From:</label>
                    <select class="form-control selectpicker uploadInput" style="border: 1px solid #ccc;" id="uploadFrom" name="sender" data-live-search="true" multiple data-header="Select Sender (From)">
                        @foreach($groups as $group)
                            <option title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                            </option>
                        @endforeach
                    </select>

                    <span>Others:
                        <input id="uploadFromText" class="uploadInput" type="text" name="sender">
                    </span>
                    <span class="error" id="senderError" style="display:none;"></span>
                </div>

                <div class="flex-row">
                    <label for="uploadTo">To:</label>
                    <select class="form-control selectpicker uploadInput"  style="border: 1px solid #ccc;" id="uploadTo" name="recipient" data-live-search="true" multiple data-header="Select Recipient (To)">
                        @foreach($groups as $group)
                            <option title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                            </option>
                        @endforeach
                    </select>
                    <span>Others:
                        <input id="uploadToText" type="text" name="recipient">
                    </span>
                    <span class="error" id="recipientError" style="display:none;"></span>
                </div>
                
                <div class="flex-row">
                    <label for="uploadSubject">Subject:</label>
                    <textarea id="uploadSubject" class="uploadInput"  name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                    <span class="error" id="subjectError" style="display:none;"></span>

                    <label for="uploadDate">Date:</label>
                    <input id="uploadDate" type="date" class="uploadInput"  name="date">
                    <span class="error" id="dateError" style="display:none;"></span>
                </div>
                
                
                <div class="flex-row"> 
                    <div>
                        <label for="uploadSoftcopy">Document (Softcopy):</label>
                        <input type="file" id="softcopy" class="uploadInput"  name="file">
                        <span class="error" id="fileError" style="display:none;"></span>
                    </div>  
                    <div>
                        <label for="uploadCategory">Category:</label>
                            <select id="uploadCategory" class="uploadInput" name="category">
                                <option value="">Select Category</option>
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
                        <select id="uploadStatus" class="uploadInput" name="status">
                            <option value="">Select Document Status</option>
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
                        <select id="uploadAssignee" class="uploadInput" name="assignee">
                            <option value="">Select Assignee</option>
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
            <button type="submit" id="submitDocumentForm"   class="btn btn-primary" type="button">Submit</button>
            <button type="button" id="clearUploadBtn"       class="btn btn-secondary" >Clear</button>
            <button type="button" id="cancelBtn"            class="btn btn-secondary" data-dismiss="modal" >Cancel</button>
        </div>
        </div>
    </div>
</div>


