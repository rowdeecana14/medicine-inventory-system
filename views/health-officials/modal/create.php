<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> REGISTER HEALTH OFFICIAL
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" aria-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div> 
            <div class="modal-body">
                <form id="create-form">
                    <div class="row">
                        <div class="col-md-6 col-lg-6">
                            <div class="card card-profile mt-2" style="margin-bottom: 20px;">
                                <div class="card-header" style="background-image: url('../../public/assets/img/config/blogpost.jpg')">
                                    <div class="profile-picture">
                                        <div class="avatar avatar-xl  image-gallery">
                                            <a href="../../public/assets/img/config/female.png" class="image_href">
                                                <img src="../../public/assets/img/config/female.png" alt="..." class="avatar-img rounded-circle preview-image"  data-toggle="tooltip" data-placement="bottom" title="View Image">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-body" >
                                    <div class="view-profile">
                                        <div class="d-flex justify-content-center">
                                            <div>
                                                <button type="button" class="btn btn-primary btn-sm  btn-border btn-round btn-open-camera mr-1 " style="font-weight:900;"><i class="fas fa-camera-retro pl-1"></i> Camera</button>
                                            </div>
                                            <div>
                                                <input type="file" class="form-control required form-control create-upload-image" id="image" name="image" accept="image/*"  hidden>
                                                <label class="btn btn-primary btn-sm  btn-border btn-round ml-1" for="image" style="font-weight:900; font-size: 12px !important;"><i class="fas fa-paperclip"></i> Choose</label>
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

                            <div class="form-group">
                                <label for="first_name">First Name <span class="-label">*</span></label>
                                <input type="text" class="form-control required" id="first_name" name="first_name"  >
                            </div>

                            <div class="form-group">
                                <label for="middle_name">Middle Name <span class="-label">*</span></label>
                                <input type="text" class="form-control required" id="middle_name" name="middle_name"  >
                            </div>

                            <div class="form-group">
                                <label for="last_name">Last Name <span class="-label">*</span></label>
                                <input type="text" class="form-control required" id="last_name" name="last_name"  >
                            </div>

                            <div class="form-group">
                                <label for="suffix">Suffix</label>
                                <input type="text" class="form-control" id="suffix" name="suffix"  >
                            </div>

                            <div class="form-group">
                                <label for="gender_id">Gender <span class="-label">*</span></label>
                                <div class="select2-input">
                                    <select id="gender_id" name="gender_id" class="form-control select2-list required"
                                        data-module="genders" data-action="select2">
                                        <option value="" disabled selected>Select Gender</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-6 col-lg-6">
                            <div class="form-group">
                                <label for="license_no">License No </label>
                                <input type="text" class="form-control" id="license_no" name="license_no"  >
                            </div>

                             <div class="form-group" >
                                <label for="position_id">Position <span class="-label">*</span></label>
                                <div class="select2-input">
                                    <select id="position_id" name="position_id" class="form-control select2-list required"
                                        data-module="occupations" data-action="select2">
                                        <option value="" disabled selected>Select Position</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="birth_date">Birth Date <span class="-label">*</span></label>
                                <input type="text" class="form-control datepicker required" id="birth_date" name="birth_date">
                            </div>

                            <div class="form-group">
                                <label for="civil_status_id">Civil Status <span class="-label">*</span></label>
                                <div class="select2-input">
                                    <select id="civil_status_id" name="civil_status_id" class="form-control select2-list required"
                                        data-module="civil_statuses" data-action="select2">
                                        <option value="" disabled selected>Select Civil Status</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="email">Email <span class="-label">*</span></label>
                                <input type="text" class="form-control required" id="email" name="email"  >
                            </div>

                            <div class="form-group">
                                <label for="contact_no">Contact No. <span class="-label">*</span></label>
                                <input type="text" class="form-control required" id="contact_no" name="contact_no"  >
                            </div>

                            <div class="form-group" >
                                <label for="baranggay_id">Baranggay <span class="-label">*</span></label>
                                <div class="select2-input">
                                    <select id="baranggay_id" name="baranggay_id" class="form-control select2-list required" 
                                        data-module="baranggays" data-action="select2">
                                        <option value="" disabled selected>Select Barrangay</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="purok_id">Purok <span class="-label">*</span></label>
                                <div class="select2-input">
                                    <select id="purok_id" name="purok_id" class="form-control select2-list required" 
                                        data-module="puroks" data-action="select2">
                                        <option value="" disabled selected>Select Purok</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12 col-lg-12">
                            <div class="form-group">
                                <label for="street_building_house">Street/Buiding No/House No <span class="-label">*</span></label>
                                <input type="text" class="form-control required input" id="street_building_house" name="street_building_house">
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                <button type="button" class="btn btn-secondary btn-create-save"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>