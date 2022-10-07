<div class="modal fade" id="filter-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-md modal-dialog-centered" role="document">
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
                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="category_id">Category</label>
                                <div class="select2-input">
                                    <select id="category_id" name="category_id" class="form-control select2-list" 
                                        data-module="categories" data-action="report-filter">
                                        <option value="" selected disabled>Select Category</option>
                                    </select>
                                </div>          
                            </div>
                            <div class="form-group">
                                <label for="type_id">Types</label>
                                <select id="type_id" name="type_id" class="form-control select2-list" 
                                    data-module="types" data-action="report-filter">
                                    <option value="" selected disabled>Select Type</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="level">Levels <span class="required-label">*</span></label>
                                <select id="level" name="level" class="form-control select2-list" 
                                    data-module="stock_levels" data-action="report-filter">
                                    <option value="" selected disabled>Select Level</option>
                                </select>
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