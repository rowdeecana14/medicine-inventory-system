<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> EDIT PATIENT INFORMATION
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" ariarequired-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div> 
            <div class="modal-body">
                <div class="col-md-12 col-lg-12">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd tablist" id="pills-tab-without-border" role="tablist">
                        <li class="nav-item tab-link" >
                            <a class="nav-link active current basic_info default-tab" data-tab="basic_info"  id="pills-basic-info-2-tab-nobd" data-toggle="pill" href="#pills-basic-info-2-nobd" role="tab" aria-controls="pills-basic-info-2-nobd" aria-selected="false"><i class="fas fa-address-card pr-1"></i> Basic Information</a>
                        </li>
                        <li class="nav-item tab-link">
                            <a class="nav-link other_info" data-tab="other_info" id="pills-other-info-2-tab-nobd" data-toggle="pill" href="#pills--other-info-2-nobd" role="tab" aria-controls="pills--other-info-2-nobd" aria-selected="false"><i class="fas fa-info-circle pr-1"></i> Other Information</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                        <div class="tab-pane fade show active basic_info-tab-content default-tab-content" id="pills-basic-info-2-nobd" role="tabpanel" ariarequired-labelledby="pills-basic-info-2-tab-nobd">
                            <div class="card">
                                <div class="card-body">
                                    <form id="basic_info_form_edit">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="card card-profile mt-2" style="margin-bottom: 0;">
                                                    <div class="card-header" style="background-image: url('../../public/assets/img/blogpost.jpg')">
                                                        <div class="profile-picture">
                                                            <div class="avatar avatar-xl image-gallery">
                                                                <a href="../../public/assets/img/config/female.png" class="image_href">
                                                                    <img src="../../public/assets/img/config/female.png" alt="..." class="avatar-img rounded-circle preview-image image_profile"  data-toggle="tooltip" data-placement="bottom" title="View Image">
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-body" style="padding-bottom: 0px;">
                                                        <div class="view-profile">
                                                            <div class="d-flex justify-content-center">
                                                                <div>
                                                                    <button type="button" class="btn btn-primary btn-sm  btn-border btn-round btn-open-camera mr-1" style="font-weight:900;"><i class="fas fa-camera-retro pl-1"></i> Camera</button>
                                                                </div>
                                                                <div>
                                                                    <input type="file" class="form-control form-control edit-upload-image" id="edit_image" name="image" accept="image/*"  hidden>
                                                                    <label class="btn btn-primary btn-sm  btn-border btn-round ml-1" for="edit_image" style="font-weight:900; font-size: 12px !important;"><i class="fas fa-paperclip"></i> Choose</label>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="user-profile text-center mt-2">
                                                            <div style="font-size: 15px;">Take photo/choose file</div>
                                                            <div id="profileImage">
                                                                <input type="hidden" name="image_to_upload" class="image_to_upload">
                                                            </div>
                                                            <div class="form-group text-left">
                                                                <label class="formrequired-label">Status</label>
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
                                                </div>

                                                <div class="form-group">
                                                    <label for="first_name">First Name <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required input" id="first_name" name="first_name"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="middle_name">Middle Name <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required input" id="middle_name" name="middle_name"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="last_name">Last Name <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required input" id="last_name" name="last_name"  >
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group"  style="margin-top: 3rem;">
                                                    <label for="national_id">National ID </label>
                                                    <input type="text" class="form-control input" id="national_id" name="national_id"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_philhealth_member">Philhealth Member <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_philhealth_member" name="philhealth_member" class="form-control select-group required">
                                                        <option value="" disabled selected>Select Options</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_person_disability_id">Person with Disability <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_person_disability_id" name="person_disability_id" class="form-control select2-list required"
                                                            data-module="person_disabilities" data-action="select2">
                                                            <option value="" disabled selected>Select Disability</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="edit_gender_id">Gender <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_gender_id" name="gender_id" class="form-control select2-list required"
                                                            data-module="genders" data-action="select2">
                                                            <option value="" disabled selected>Select Gender</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="birth_date">Birth Date <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control datepicker required input" id="birth_date" name="birth_date">
                                                </div>

                                                <div class="form-group" >
                                                    <label for="edit_position_id">Position <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_position_id" name="position_id" class="form-control select2-list required"
                                                            data-module="occupations" data-action="select2">
                                                            <option value="" disabled selected>Select Position</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="edit_civil_status_id">Civil Status <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_civil_status_id" name="civil_status_id" class="form-control select2-list required"
                                                            data-module="civil_statuses" data-action="select2">
                                                            <option value="" disabled selected>Select Civil Status</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="edit_citizenship_id">Citizenship <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_citizenship_id" name="citizenship_id" class="form-control select2-list required"
                                                            data-module="citizenships" data-action="select2">
                                                            <option value="" disabled selected>Select Citizenship</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <div class="tab-pane fade other_info-tab-content" id="pills--other-info-2-nobd" role="tabpanel" ariarequired-labelledby="pills--other-info-2-tab-nobd">
                            <div class="card">
                                <div class="card-body">
                                    <form id="other_info_form_edit">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="weight">Weight (Kls) <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required input numbers-only" id="weight" name="weight"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="height">Height (Cms) <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required input numbers-only" id="height" name="height"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="edit_blood_type">Blood Type <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_blood_type" name="blood_type_id" class="form-control select2-list required"
                                                            data-module="blood_types" data-action="select2">
                                                            <option value="" disabled selected>Select Blood Type</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="edit_educational_attainment_id">Educational Attainment </label>
                                                    <div class="select2-input">
                                                        <select id="edit_educational_attainment_id" name="educational_attainment_id" class="form-control select2-list"
                                                            data-module="educational_attainments" data-action="select2">
                                                            <option value="" disabled selected>Select Educational Attainment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email</label>
                                                    <input type="text" class="form-control input" id="email" name="email"  >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="contact_no">Contact No. </label>
                                                    <input type="text" class="form-control input" id="contact_no" name="contact_no"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group" >
                                                    <label for="edit_baranggay_id">Baranggay <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_baranggay_id" name="baranggay_id" class="form-control select2-list required" 
                                                            data-module="baranggays" data-action="select2">
                                                            <option value="" disabled selected>Select Barrangay</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="edit_purok_id">Purok <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="edit_purok_id" name="purok_id" class="form-control select2-list required" 
                                                            data-module="puroks" data-action="select2">
                                                            <option value="" disabled selected>Select Purok</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-12 col-lg-12">
                                                <div class="form-group">
                                                    <label for="street_building_house">Street/Buiding No/House No <span class="required-label">*</span></label>
                                                    <textarea name="street_building_house" id="street_building_house" rows="3" class="form-control success input" required="" aria-invalid="false"></textarea>
                                                </div>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer no-bd">
                <button type="button" class="btn btn-danger" data-dismiss="modal"><i class="fas fa-times-circle"></i> Close</button>
                <button type="button" class="btn btn-secondary btn-save-edit"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>