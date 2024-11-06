{{-- Log Information --}}
<div class="modal fade" id="logInfo" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title p-0">Log Information</h4>
            </div>
            <div class="modal-body logInfo">
                <span id="logDate"></span><br>
                <span id="logDescription"></span><br>
                <span class="mb-2" id="logAccount"></span><br>
                <div class="logDetails p-0" id="accountDetailLog" data-id="">
                    <div class="p-2 container border rounded" style="max-height: 300px; overflow-y: scroll;">
                        <h6 class="p-0 font-weight-bold mb-3" id="logDetailTitle" style="font-size: 15px;"></h6>
                        <div id="logDetails"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>