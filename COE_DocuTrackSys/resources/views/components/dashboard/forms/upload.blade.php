<div class="container-fluid border p-3 rounded">
    <h6 class="p-0 font-weight-bold">Upload Document Form<small class="pt-0" style="font-size: 11px; color: red">  (* - required)</small></h6>
    
    {{-- Upload Document Form --}}
    <form class="uploadContent w-100" id="uploadDocumentForm" method="post">
        @csrf
        @method('POST')
        
        {{-- Document Subject --}}
        <div class="row mb-1">
            <div class="col">
                <label for="uploadSubject">Subject<small style="color: red">*</small></label>
                <textarea id="uploadSubject" class="uploadInput"  name="subject" rows="2" placeholder="Enter the Subject of the Document"></textarea>
                <span class="error" id="subjectError" style="margin-top: -8px; display:none;"></span>
            </div>
        </div>

        {{-- Document Sender and Recipient --}}
        <div class="row mb-2">
            <div class="col">
                <label for="uploadFrom">From<small style="color: red">*</small></label>
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

            <div class="col">
                <label for="uploadTo">To<small style="color: red">*</small></label>
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

        <div class="row mb-2">
            <div class="col">
                {{-- Document Type --}}
                <label for="uploadDocType">Document Type<small style="color: red">*</small></label>
                <select id="uploadDocType" class="uploadInput" name="type">
                    <option value="" disabled selected>Select the Type of Document</option>
                    {{-- Obtained document types using Laravel--}}
                    @foreach ($docTypes as $index => $docType)
                        @if ($docType->value !== 'default')
                            <option value="{{$docType->value}}">{{$docType->value}}</option>
                        @endif
                    @endforeach
                </select>
                <span class="error" id="typeError" style="display:none;"></span>
            </div>
            
            {{-- Series Number and Memo Number --}}
            <div class="col p-0" id="memoInfo" style="display: none;">
                <div class="col">
                    <label for="uploadSeriesNo">Series No.<small style="color: red">*</small></label>
                    <input id="uploadSeriesNo" class="uploadInput" type="number" name="seriesNo" min="1" max="99999">
                    <span class="error"  id="seriesError" style="display:none;"></span>
                </div>
                    
                <div class="col">
                    <label for="uploadMemoNo">Memo No.<small style="color: red">*</small></label>
                    <input id="uploadMemoNo" class="uploadInput" type="number" name="memoNo" min="1" max="99999">
                    <span class="error" id="memoError" style="display:none;"></span>
                </div>
            </div>
        </div>

        <div class="row mb-2">
            {{-- Document Date --}}
            <div class="col">
                <label for="uploadDate">Date<small style="color: red">*</small></label>
                <input id="uploadDate" type="date" class="uploadInput"  name="date" max="{{now()->toDateString()}}">
                <span class="error" id="dateError" style="display:none;"></span>
            </div>
        </div>

        <div class="row mb-2">
            {{-- Document Status --}}
            <div class="col">
                <label for="uploadStatus">Status<small style="color: red">*</small></label>
                <select id="uploadStatus" class="uploadInput" name="status">
                    <option value="" disabled selected>Select Document Status</option>
                    {{-- Obtained document statuses using Laravel--}}
                    @foreach ($docStatuses as $docStatus)
                        @if ($docStatus->value !== 'default')
                            <option value="{{$docStatus->value}}" data-color="{{$docStatus->color}}" style="background-color: {{$docStatus->color}}">{{$docStatus->value}}</option>
                        @endif
                    @endforeach
                </select>
                <span class="error" id="statusError" style="display:none;"></span>
            </div>

            {{-- Assignee --}}
            <div class="col">
                <label for="uploadAssignee">Assignee<small style="color: red">*</small></label>
                <select id="uploadAssignee" class="uploadInput" name="assignee">
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

            {{-- Category --}}
            <div class="col">
                <label for="uploadCategory">Category<small style="color: red">*</small></label>
                    <select id="uploadCategory" class="uploadInput" name="category">
                        <option value="" disabled selected>Select Category</option>
                        <option value="Incoming">Incoming</option>
                        <option value="Outgoing">Outgoing</option>
                    </select>
                <span class="error" id="categoryError" style="display:none;"></span>
            </div>
        </div>

        {{-- Document Attachments --}}
        <div class="row mb-2">
            <div class="col">
                <label class="fileUploadLabel" for="uploadSoftcopy">Document Attachment/s: <small style="color: red">*</small></label>
                <div class="container rounded border p-2 d-flex justify-content-center align-items-center uploadFiles uploadInput" data-value="none" style="flex-wrap: wrap; height: 200px;">
                    <div>No files added</div>
                </div>
                
                <div class="form-group mb-0">
                    <label class="custom-file">
                        <label class="custom-file-label uploadInput" for="softcopy" id="fileLink">Add file/s to the document</label>
                        <input type="file" id="softcopy" class="uploadInput custom-file-input" placeholder="Choose File" name="files[]" multiple>
                    </label>
                </div>
                <span class="error" id="fileError" style="display:none;"></span>
            </div>
        </div>

        <div class="row mb-2">
            <div class="col">
                <label for="documentFolder">Google Drive to Store Document: </label>
                <select id="documentFolder" class="uploadInput" name="category">
                    @foreach ($drives as $drive)
                        <option value="{{$drive->id}}">{{$drive->email}}</option>
                    @endforeach
                </select>
            </div>
        </div>

        {{-- Document Details --}}
        <div class="container rounded border p-3 mb-3">
            <div class="row d-flex justify-content-between align-items-center">
                <div class="col-auto text-left">
                    <h6 class="p-0 mb-0 font-weight-bold" style="font-size: 15px;">Document Details (optional)</h6>
                </div>
            
                <div class="col-auto text-right">
                    <h6 class="p-0 mb-0" style="font-size: 15px; color: #a50b0b" id="documentDetailsBtn">Show Document Details</h6>
                </div>
            </div>
                
            <div style="display: none;" id="documentDetails">
                <div class="row mb-1">
                    {{-- Document Event Description --}}
                    <div class="col">
                        <label for="uploadEventDescription">Event Description</label>
                        <textarea id="uploadEventDescription" class="uploadInput mb-2"  name="event_description" rows="3" placeholder="Enter the Description of the Event"></textarea>
                    </div>
                </div>
        
                <div class="row mb-1">
                    {{-- Document Venue --}}
                    <div class="col">
                        <label for="uploadEventVenue">Event Venue</label>
                        <input id="uploadEventVenue" class="uploadInput mb-2" type="text" name="event_venue" placeholder="Enter Venue of the Event">
                    </div>
                </div>

                <div class="row mb-2">
                    {{-- Document Venue --}}
                    <div class="col">
                        <label for="uploadEventDate">Event Date</label>
                        <input id="uploadEventDate" class="uploadInput" type="date" name="event_date">
                    </div>
                </div>
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



