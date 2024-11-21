<div class="border rounded p-3 table dashboardTable container p-0">
    <div class="container p-0 mb-3">
        <div class="row p-0 justify-content-between align-items-center">
            <div class="col-auto text-left mr-auto">
                <h6 class="dashboardTableTitle p-0 font-weight-bold"></h6>
            </div>
            <div class="col text-right mr-2">
                <span id="archivedTitle">Select Date: </span>
            </div>
            <div class="col">
                <input type="text" class="form-control" id="archivedDatePicker">
            </div>
        </div>
    </div>
    
    <div class="row">
        
        @if ($maintenance != true)
        <div class="overlay" title="Account editing can only be done under maintenance."></div>  
    @endif
        <div class="col">
            <div class="tableContentstable dashboardTableContents">
                <table id="dashboardTable" class="table cell-border table-bordered hover pt-1">
                </table>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirmDeleteDocumentModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        Confirm deleting document/s?
                    </h5>
                </div>
                <div class="modal-body text-justify">
                    Please note that once documents are deleted from the system, they cannot be recovered. However, files moved to the trash will remain there indefinitely, allowing you to restore them at any time, as long as they are not permanently deleted.
                </div>
                <div class="modal-footer">
                    <button type="button" data-ids="" class="btn btn-primary confirmDeleteBtn">
                        Delete Document/s
                    </button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                </div>
            </div>
        </div>
    </div>
</div>