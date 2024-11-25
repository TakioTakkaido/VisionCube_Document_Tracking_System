<div class="modal fade" id="reportPreview" tabindex="-1" role="dialog" aria-hidden="true" style="overflow-y: none;">
    <div class="modal-dialog modal-lg modal-dialog-centered reportPreview" role="document" style="max-width: 1000px !important;">
        <div class="modal-content">
            <div class="modal-body container border rounded px-3 py-0">
                {{-- Breadcrumb here --}}
                <div class="row">
                    <div class="col py-3">
                        <div class="row mb-3">
                            <div class="col-6">
                                <div class="row mb-0">
                                    <div class="col mb-0">
                                        <h2 id="reportName" class="mb-0" style="overflow-wrap: anywhere;"></h2>
                                    </div>
                                </div>
                                <div class="row" style="font-size: 12px;">
                                    <div class="col">
                                        <span id="reportDriveFolder"></span>
                                    </div>                    
                                </div>
                            </div>
        
                            <div class="col-6">
                                <div class="row">
                                    <div class="col mr-1">
                                        <span><strong>Date Generated: </strong></span>
                                    </div>
                                    <div class="col" style="text-align: left;">
                                        <span id="reportDateGenerated"></span>                                
                                    </div>
                                </div>
                            </div>              
                        </div>
        
                        <div class="row border-top" style="height: 650px; max-height: 650px;">
                            <div id="loadingReport" class="reportPreviewInfo col container justify-content-center align-items-center">
                                <div class="spinner-border" role="status">
                                    <span class="sr-only">Loading...</span>
                                </div>
                            </div>

                            <div id="reportInfoContainer" class="reportPreviewInfo col container" style="overflow-x: none; max-height: inherit; overflow-y: scroll; display: none; overflow-y: scroll;">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>