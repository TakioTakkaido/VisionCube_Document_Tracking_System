{{-- Document Preview    --}}
<div class="modal fade" id="documentPreview" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: none;">
    <div class="modal-dialog modal-lg documentPreview" role="document" style="max-width: 1000px !important;">
        <div class="modal-content" style="height: 90vh; max-width: 1000px !important;">
            <div class="modal-body container-fluid border rounded p-3">
                {{-- Breadcrumb here --}}
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
                    <div class="col-6">
                        <button type="button" class="btn btn-primary">Update Document</button>
                        <button type="button" class="btn btn-primary">View Attachments</button>
                    </div>
                </div>
                {{-- Document History --}}
                <div class="row">
                    <div class="col">
                        
                        <h6 class="font-weight-bold p-0 mb-0">Document History</h6>
                        <div class="documentVersionsTable table border rounded dashboardTable container pt-1 pl-2 pr-2 pb-1 m-0">
                            <div class="documentVersionsTableContents p-0">
                                <table id="documentVersionsTable" class="table compact table-bordered hover">
                                    <thead>
                                        <tr>
                                            <th>Version</th>
                                            <th>Modified At</th>
                                            <th>Modified By</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th>Version</th>
                                            <th>Modified At</th>
                                            <th>Modified By</th>
                                            <th>Description</th>
                                            <th>Status</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>