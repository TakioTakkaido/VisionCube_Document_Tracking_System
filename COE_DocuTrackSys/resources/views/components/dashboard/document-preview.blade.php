{{-- Document Preview    --}}
<div class="modal fade" id="documentPreview" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: none;">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content" style="height: 90vh">
            <div class="modal-body flex-row">
                <div id="pdfPreview">
                    <iframe 
                        id="documentPreviewIFrame" 
                        type="application/pdf" 
                        src="" 
                        style="width: 100%; height: 100%; border:none;">
                    </iframe>
                </div>
                <div id="documentInfo"> 
                    <h5>Document Preview</h5>
                    <span id="documentDate"></span><br>
                    <span id="documentType"></span><br>
                    <span id="documentVersion"></span><br>
                    <div id="documentMemoInfo" style="display:none;">
                        <span id="documentSeriesNo"></span><br>
                        <span id="documentMemoNo"></span><br>
                    </div>
                    <span id="documentSender"></span><br>
                    <span id="documentRecipient"></span><br>
                    <span id="documentSubject"></span><br>
                    <span id="documentAssignee"></span><br>
                    <span id="documentCategory"></span><br>
                    <span id="documentStatus"></span><br>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>