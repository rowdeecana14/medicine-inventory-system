<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> EDIT MEDICINE INFORMATIONS
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div> 
            <div class="modal-body">
                <form id="edit-form">
                <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="card-body" >
                                <div class="image-gallery">
                                    <a href="../../public/assets/img/config/medicine.jpg" class="image_href">
                                        <img src="../../public/assets/img/config/medicine.jpg" alt="..." class="avatar-img  preview-image image_profile"  data-toggle="tooltip" data-placement="bottom" title="View Image">
                                    </a>
                                </div>

                                <div class="view-profile mt-3">
                                    <div class="d-flex justify-content-center">
                                        <div>
                                            <button type="button" class="btn btn-primary btn-sm  btn-border btn-round btn-open-camera mr-1" style="font-weight:900;"><i class="fas fa-camera-retro pl-1"></i> Camera</button>
                                        </div>
                                        <div>
                                            <input type="file" class="form-control required form-control edit-upload-image" id="edit_image" name="image" accept="image/*"  hidden>
                                            <label class="btn btn-primary btn-sm  btn-border btn-round ml-1" for="edit_image" style="font-weight:900; font-size: 12px !important;"><i class="fas fa-paperclip"></i> Choose</label>
                                        </div>
                                    </div>
                                </div>
                                <div class="user-profile text-center mt-2">
                                    <div class="name" style="font-size: 15px;">Take photo/choose file</div>
                                    <div id="profileImage">
                                        <input type="hidden" name="image_to_upload" class="image_to_upload">
                                    </div>
                                   
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="name">Name <span class="required-label">*</span></label>
                                <input type="text" class="form-control required input" id="name" name="name"  >
                            </div>

                            <div class="form-group">
                                <label for="description">Description <span class="required-label">*</span></label>
                                <input type="text" class="form-control required input" id="description" name="description"  >
                            </div>

                            <div class="form-group">
                                <label for="category_id">Category <span class="required-label">*</span></label>
                                <div class="select2-input">
                                    <select id="category_id" name="category_id" class="form-control select2-list required" 
                                        data-module="categories" data-action="select2">
                                        <option value="" disabled selected>Select Category</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="type_id">Type <span class="required-label">*</span></label>
                                <div class="select2-input">
                                    <select id="type_id" name="type_id" class="form-control select2-list required" 
                                        data-module="types" data-action="select2">
                                        <option value="" disabled selected>Select Type</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group text-left">
                                <label class="form-label">Status</label>
                                <div class="selectgroup w-100">
                                    <label class="selectgroup-item">
                                        <input type="radio" name="status" value="Active" class="selectgroup-input" id="status_active">
                                        <span class="selectgroup-button">Active</span>
                                    </label>
                                    <label class="selectgroup-item">
                                        <input type="radio" name="status" value="Inactive" class="selectgroup-input" id="status_inactive">
                                        <span class="selectgroup-button">Inactive</span>
                                    </label>
                                </div>
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