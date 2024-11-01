<div class="container border p-3 rounded w-75">
    <h6 class="p-0 font-weight-bold">Upload Document Form</h6>
    {{-- Upload Document Form --}}
    <form class="uploadContent w-100" id="uploadDocumentForm" method="post">
        @csrf
        @method('POST')
        {{-- CSRF Token --}}
        <input type="hidden" name="_token" id="token" value="{{ csrf_token() }}">
        
        <div class="row mb-2">
            <div class="col-4">
                {{-- Document Type --}}
                <div class="row mb-2">
                    <div class="col">
                        <label for="uploadDocType">Document Type</label>
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
                    </div>
                </div>

                {{-- Series Number and Memo Number --}}
                <div class="row mb-2" id="memoInfo" style="display: none;">
                    <div class="col">
                        <label for="uploadSeriesNo">Series No.</label>
                        <input id="uploadSeriesNo" class="uploadInput" type="number" name="seriesNo" min="1" max="99999">
                        <span class="error"  id="seriesError" style="display:none;"></span>
                    </div>
                        
                    <div class="col">
                        <label for="uploadMemoNo">Memo No.</label>
                        <input id="uploadMemoNo" class="uploadInput" type="number" name="memoNo" min="1" max="99999">
                        <span class="error" id="memoError" style="display:none;"></span>
                    </div>
                </div>

                {{-- Document Status --}}
                <div class="row mb-2">
                    <div class="col">
                        <label for="uploadStatus">Status</label>
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
                </div>

                {{-- Assignee --}}
                <div class="row mb-2">
                    <div class="col">
                        <label for="uploadAssignee">Assignee</label>
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

                {{-- Document Category --}}
                <div class="row">
                    <div class="col">
                        <label for="uploadCategory">Category</label>
                            <select id="uploadCategory" class="uploadInput" name="category">
                                <option value="">Select Category</option>
                                <option value="Incoming">Incoming</option>
                                <option value="Outgoing">Outgoing</option>
                                <option value="Archived">Archived</option>
                            </select>
                        <span class="error" id="categoryError" style="display:none;"></span>
                    </div>
                </div>
            </div>
            
            {{-- Document Sender and Recipient --}}
            <div class="col-8">
                <div class="row mb-2">
                    <div class="col">
                        <label for="uploadFrom">From</label>
                        <select class="form-control selectpicker uploadInput uploadFrom p-0 border mb-1"  id="uploadFrom" name="sender" data-live-search="true" multiple title="Select Sender (From)">
                            @foreach($groups as $group)
                                <option style="font-size: 15px !important;" title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                    {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                </option>
                            @endforeach
                        </select>
    
                        <input id="uploadFromText" class="uploadInput mb-2" type="text" name="sender" style="display: none;" placeholder="Other Sender">

                        <button type="button" class="btn btn-primary p-1 otherBtn" id="otherSender">Others</button>
                        <span class="error" id="senderError" style="display:none;"></span>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col">
                        <label for="uploadTo">To</label>
                        <select class="form-control selectpicker uploadInput uploadTo p-0 border mb-1"  style="border: 1px solid #ccc;" id="uploadTo" name="recipient" data-live-search="true" multiple title="Select Recipient (To)">
                            @foreach($groups as $group)
                                <option style="font-size: 15px !important;" title="" value="{{ $group['id'] }}" data-level={{$group['level']}} data-name="{{ $group['value'] }}" data-parent="{{$group['parent']}}" data-participant="{{$group['participant']}}">
                                    {!! str_repeat('&nbsp;', $group['level'] * 4) !!} {{ $group['value'] }}
                                </option>
                            @endforeach
                        </select>

                        <input id="uploadToText" style="display: none;" class="uploadInput mb-2" type="text" name="recipient" placeholder="Other Recipient">

                        <button type="button" class="btn btn-primary p-1 otherBtn" id="otherRecipient">Others</button>

                        <span class="error" id="recipientError" style="display:none;"></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            {{-- Document Subject --}}
            <div class="col">
                <label for="uploadSubject">Subject</label>
                <textarea id="uploadSubject" class="uploadInput"  name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                <span class="error" id="subjectError" style="margin-top: -8px; display:none;"></span>
            </div>
            
            {{-- Document Date --}}
            <div class="col">
                <label for="uploadDate">Date</label>
                <input id="uploadDate" type="date" class="uploadInput"  name="date">
                <span class="error" id="dateError" style="display:none;"></span>
            </div>
        </div>

        {{-- Document Attachments --}}
        <div class="row mb-2">
            <div class="col">
                <label for="uploadSoftcopy">Document Attachment/s: </label>
                <div class="container rounded border p-2 d-flex justify-content-center align-items-center uploadFiles" data-value="none" style="flex-wrap: wrap; height: 200px;">
                    <div>No files added</div>
                </div>
                
                <div class="form-group">
                    <label class="custom-file">
                        <label class="custom-file-label uploadInput" for="softcopy" id="fileLink">Add file/s to the document</label>
                        <input type="file" id="softcopy" class="uploadInput custom-file-input" placeholder="Choose File" name="files[]" multiple>
                    </label>
                </div>
                <span class="error" id="fileError" style="display:none;"></span>
            </div>
        </div>

        <div class="row justify-content-end align-items-end">
            <div class="col-auto">
                {{-- Submit form data using AJAX in uploadform.js --}}
                <button type="submit" id="submitDocumentForm"   class="btn btn-primary" type="button" {{$canUpload == false ? 'style=cursor:not-allowed disabled' : ''}}><i class='bx bx-upload'></i>  Upload</button>
                <button type="button" id="clearUploadBtn"       class="btn btn-warning"><i class='bx bxs-eraser'></i>  Clear</button>
            </div>
        </div>
    </form>
</div>



