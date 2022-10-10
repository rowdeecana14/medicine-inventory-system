<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> ADD USER
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="create-form">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="health_official_id">Name <span class="required-label">*</span></label>
                                <div class="select2-input">
                                    <select id="health_official_id" name="health_official_id" class="form-control select2-list required" 
                                        data-module="health_officials" data-action="select2"
                                        data-type="advanced" data-image-default="health-official.png"
                                    >
                                        <option value="" disabled selected>Select Name</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="username">Username <span class="required-label">*</span></label>
                                <input type="text" class="form-control" id="username" name="username" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password <span class="required-label">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="password" name="password" placeholder="" required autocomplete=""> 
                                    <div class="input-group-append">
                                        <span class="input-group-text fa fa-eye password-visibility" onclick="showHide(this, '#password')"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="confirm_password">Confirm Password <span class="required-label">*</span></label>
                                <div class="input-group">
                                    <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="" required autocomplete="">
                                    <div class="input-group-append">
                                        <span class="input-group-text fa fa-eye password-visibility"  onclick="showHide(this, '#confirm_password')"></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                <button type="submit" form="create-form" class="btn btn-secondary"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>