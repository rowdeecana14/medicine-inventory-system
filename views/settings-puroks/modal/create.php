<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> ADD PUROK
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
                                <label for="baranggay_id">Baranggay <span class="required-label">*</span></label>
                                <div class="select2-input">
                                    <select id="baranggay_id" name="baranggay_id" class="form-control select2-list required"
                                        data-module="baranggays" data-action="select2">
                                        <option value="" disabled selected>Select Baranggay</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="name">Purok Name <span class="required-label">*</span></label>
                                <input type="text" class="form-control" id="name" name="name" placeholder="" required>
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