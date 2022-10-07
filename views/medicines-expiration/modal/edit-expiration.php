<div class="modal fade" id="edit-expiration-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content" style="box-shadow: 0 4px 8px 0 #6861ce, 0 6px 20px 0 rgb(104 97 206);">
            <div class="modal-header no-bd bg-purple">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> EDIT EXPIRATION
                </h5>
                <button type="button" class="btn btn-circle color-purple" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="edit-form">
                    <div class="row">
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="name">Quantity <span class="required-label">*</span></label>
                                <input type="number" class="form-control input" id="quantity" name="quantity" min="1" required>
                            </div>

                            <div class="form-group">
                                <label for="expired_at">Date Expired <span class="required-label">*</span></label>
                                <input type="text" class="form-control datepicker required input" id="expired_at" name="expired_at">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                <button type="button" class="btn btn-secondary btn-edit-save"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>