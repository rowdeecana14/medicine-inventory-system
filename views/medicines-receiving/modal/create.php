<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> RECEIVING FORM
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div> 
            <div class="modal-body">
                <form id="create-form">
                    <div class="row">

                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="health_official_id">Receiver <span class="required-label">*</span></label>
                                <div class="select2-input">
                                    <select id="health_official_id" name="health_official_id" class="form-control select2-list required" 
                                        data-module="health_officials" data-action="select2" data-type="advanced" data-image-default="health-official.png">
                                        <option value="" disabled selected>Select Health Officials</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="received_at">Date Received<span class="required-label">*</span></label>
                                <input type="text" class="form-control datepicker required current-date input" id="received_at" name="received_at">
                            </div>
                        </div>

                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="delivery_person">Delivery Person <span class="required-label">*</span></label>
                                <input type="text" class="form-control required input" id="delivery_person" name="delivery_person"  >
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="contact_no">Contact No. <span class="required-label">*</span></label>
                                <input type="text" class="form-control required input" id="contact_no" name="contact_no"  >
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12">
                            <div class="table-responsive">
                                <table class="display table table-striped table-hover table-medicine" >
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th width="60%">Medicine</th>
                                            <th width="20%">Quantity</th>
                                            <th width="100px">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>1</td>
                                            <td>
                                                <div class="select2-input">
                                                    <select name="medicine_id[]" class="medicine_id form-control select2-list select-field required" 
                                                        data-module="medicines" data-action="select2" data-type="advanced" data-image-default="medicine.jpg">
                                                        <option value="" disabled selected>Select Medicines</option>
                                                    </select>
                                                </div>          
                                            </td>
                                            <td>
                                                <div class="form-group">
                                                    <input type="number" min="1" class="quantity form-control required input" name="quantity[]">
                                                </div>
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-icon btn-round btn-primary btn-add-medicine" data-toggle="tooltip" data-placement="top" title="Add row">
                                                    <i class="fas fa-plus-circle"></i>
                                                </button>
                                                <button type="button" class="btn btn-icon btn-round btn-danger btn-remove-medicine d-none" data-toggle="tooltip" data-placement="top" title="Delete row">
                                                    <i class="fas fa-trash-alt"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="remarks">Remarks</label>
                                <textarea name="remarks" id="remarks" rows="3" class="form-control input" aria-invalid="false"></textarea>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                <button type="button" class="btn btn-secondary btn-save"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>