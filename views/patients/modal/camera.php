<div class="modal fade" id="camera-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-image pr-1"></i> IMAGE PROFILE
                </h5>
                <button type="button" class="btn btn-circle btn-close-modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <div class="d-flex justify-content-center">
                    <div>
                        <img src=""  class="preview-image-captured" style="width: 320px; height: 240px">
                    </div>
                    <div class="text-center camera" id="my_camera">
                    </div>
                </div>

                <div style="margin-top: 15px">
                    <div class="d-flex justify-content-center">
                        <div>
                            <button type="button" class="btn btn-primary btn-sm btn-camera-reset btn-border btn-round mr-1 pl-3 pr-3" style="font-weight:900;"><i class="fas fa-history pl-1"></i> Reset</button>
                        </div>
                        <div>
                            <button class="btn btn-primary btn-sm  btn-border btn-round ml-1 btn-camera-capture" style="font-weight:900; font-size: 12px !important;"><i class="fas fa-paperclip"></i> Capture</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger btn-close-modal"><i class="fas fa-times-circle"></i> Close</button>
            </div>
        </div>
    </div>
</div>