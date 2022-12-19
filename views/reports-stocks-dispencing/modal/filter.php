<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-filter pr-1"></i> FILTER RECORDS
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div>
            <div class="modal-body">
                <form id="filter-form">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="transaction_id">Transaction No.</label>
                                <div class="select2-input">
                                    <select id="transaction_id" name="transaction_id" class="form-control select2-list" 
                                        data-module="transactions" data-action="sd-filter" data-type="normal">
                                        <option value="" selected disabled>Select Transaction No.</option>
                                    </select>
                                </div>          
                            </div>

                            <div class="form-group">
                                <label for="dispenced_at">Date Dispensed</label>
                                <input type="text" class="form-control datepicker" id="dispenced_at" name="dispenced_at">
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <div class="select2-input">
                                    <select id="category_id" name="category_id" class="form-control select2-list" 
                                        data-module="categories" data-action="report-filter" data-type="normal" >
                                        <option value="" selected disabled>Select Category</option>
                                    </select>
                                </div>          
                            </div>
                        </div>
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                    <label for="type_id">Types</label>
                                    <select id="type_id" name="type_id" class="form-control select2-list" 
                                        data-module="types" data-action="report-filter"  data-type="normal" >
                                        <option value="" selected disabled>Select Type</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <label for="health_official_id">Health Official</label>
                                    <div class="select2-input">
                                        <select id="health_official_id" name="health_official_id" class="form-control select2-list" 
                                            data-module="health_officials" 
                                            data-action="report-filter" 
                                            data-type="advanced" 
                                            data-image-default="health-official.png"
                                            data-include-all="1"
                                        >
                                            <option value="" disabled selected>Select Health Official</option>
                                        </select>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="patient_id">Patient</label>
                                    <div class="select2-input">
                                        <select id="patient_id" name="patient_id" class="form-control select2-list" 
                                            data-module="patients" 
                                            data-action="report-filter" 
                                            data-type="advanced" 
                                            data-image-default="patient.png"
                                            data-include-all="1"
                                        >
                                            <option value="" disabled selected>Select Patient</option>
                                        </select>
                                    </div>
                                </div>

                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle pr-1"></i> Close</button>
                <button type="submit" form="filter-form" class="btn btn-secondary"><i class="fas fa-check-circle pr-1"></i> Submit</button>
            </div>
        </div>
    </div>
</div>