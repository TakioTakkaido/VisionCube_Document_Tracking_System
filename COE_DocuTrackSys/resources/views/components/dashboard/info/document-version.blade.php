{{-- Document Preview    --}}
<div class="modal fade" id="viewDocumentVersion" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="documentVersion">
        <div class="modal-content" style="height: 90vh">
            <div class="modal-body flex-row">
                <div id="pdfPreview">
                    <iframe 
                        id="documentVersionIFrame" 
                        type="application/pdf" 
                        src="" 
                        style="width: 100%; height: 100%; border:none;">
                    </iframe>
                </div>
                <div id="documentVersionInfo"> 
                    <h5>Document Version</h5>
                    <span id="documentVersionModifiedDate"></span><br>
                    <span id="documentVersionModifiedBy"></span><br>
                    <span id="documentVersionDate"></span><br>
                    <span id="documentVersionType"></span><br>
                    <div id="documentVersionMemoInfo" style="display:none;">
                        <span id="documentVersionSeriesNo"></span><br>
                        <span id="documentVersionMemoNo"></span><br>
                    </div>
                    <span id="documentVersionSender"></span><br>
                    <span id="documentVersionRecipient"></span><br>
                    <span id="documentVersionSubject"></span><br>
                    <span id="documentVersionAssignee"></span><br>
                    <span id="documentVersionCategory"></span><br>
                    <span id="documentVersionStatus"></span><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="closeDocumentVersion">Close</button>
            </div>
        </div>
    </div>
</div>