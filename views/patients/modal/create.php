<div class="modal fade" id="create-modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header no-bd">
                <h5 class="modal-title">
                    <i class="fas fa-edit pr-1"></i> REGISTER PATIENT
                </h5>
                <button type="button" class="btn btn-circle" data-dismiss="modal" ariarequired-label="Close" data-toggle="tooltip" data-placement="top" title="Close Modal">
                    <i class="fa fa-times"></i>
                </button>
            </div> 
            <div class="modal-body">
                <div class="col-md-12 col-lg-12">
                    <ul class="nav nav-pills nav-secondary nav-pills-no-bd tablist" id="pills-tab-without-border" role="tablist">
                        <li class="nav-item tab-link" >
                            <a class="nav-link active current basic_info default-tab" data-tab="basic_info"  id="pills-basic-info-tab-nobd" data-toggle="pill" href="#pills-basic-info-nobd" role="tab" aria-controls="pills-basic-info-nobd" aria-selected="false"><i class="fas fa-address-card pr-1"></i> Basic Information</a>
                        </li>
                        <li class="nav-item tab-link">
                            <a class="nav-link other_info" data-tab="other_info" id="pills-other-info-tab-nobd" data-toggle="pill" href="#pills--other-info-nobd" role="tab" aria-controls="pills--other-info-nobd" aria-selected="false"><i class="fas fa-info-circle pr-1"></i> Other Information</a>
                        </li>
                    </ul>
                    <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                        <div class="tab-pane fade show active basic_info-tab-content default-tab-content" id="pills-basic-info-nobd" role="tabpanel" ariarequired-labelledby="pills-basic-info-tab-nobd">
                            <div class="card">
                                <div class="card-body">
                                    <input type="file" class="form-control  form-control create-upload-image" id="image" name="image" accept="image/*"  hidden>

                                    <form id="basic_info_form">
                                        <div class="row">
                                            <div class="col-md-6 col-lg-6">
                                                <div class="card card-profile mt-2" style="margin-bottom: 20px;">
                                                    <div class="card-header" style="background-image: url('../../public/assets/img/blogpost.jpg')">
                                                        <div class="profile-picture">
                                                            <div class="avatar avatar-xl  image-gallery">
                                                                <a href="../../public/assets/img/config/default.png" class="image_href">
                                                                    <img src="../../public/assets/img/config/default.png" alt="..." class="avatar-img rounded-circle preview-image"  data-toggle="tooltip" data-placement="bottom" title="View Image">
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
                                                    <label for="first_name">First Name <span class="requiredrequired-label">*</span></label>
                                                    <input type="text" class="form-control required " id="first_name" name="first_name"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="middle_name">Middle Name <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required" id="middle_name" name="middle_name"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="last_name">Last Name <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control required" id="last_name" name="last_name"  >
                                                </div>
                                            
                                            </div>
                                            
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="national_id">National ID</label>
                                                    <input type="text" class="form-control" id="national_id" name="national_id"  >
                                                </div>

                                                <div class="form-group">
                                                    <label for="philhealth_member">Philhealth Member <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="philhealth_member" name="philhealth_member" class="form-control select-group required">
                                                        <option value="" disabled selected>Select Options</option>
                                                            <option value="Yes">Yes</option>
                                                            <option value="No">No</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="person_disability_id">Person with Disability <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="person_disability_id" name="person_disability_id" class="form-control select2-list required"
                                                            data-module="person_disabilities" data-action="select2">
                                                            <option value="" disabled selected>Select Disability</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="gender_id">Gender <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="gender_id" name="gender_id" class="form-control select2-list required"
                                                            data-module="genders" data-action="select2">
                                                            <option value="" disabled selected>Select Gender</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="form-group">
                                                    <label for="birth_date">Birth Date <span class="required-label">*</span></label>
                                                    <input type="text" class="form-control datepicker required" id="birth_date" name="birth_date">
                                                </div>

                                                <div class="form-group" >
                                                    <label for="position_id">Position <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="position_id" name="position_id" class="form-control select2-list required"
                                                            data-module="occupations" data-action="select2">
                                                            <option value="" disabled selected>Select Position</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="civil_status_id">Civil Status <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="civil_status_id" name="civil_status_id" class="form-control select2-list required"
                                                            data-module="civil_statuses" data-action="select2">
                                                            <option value="" disabled selected>Select Civil Status</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="citizenship_id">Citizenship <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="citizenship_id" name="citizenship_id" class="form-control select2-list required"
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

                        <div class="tab-pane fade other_info-tab-content" id="pills--other-info-nobd" role="tabpanel" ariarequired-labelledby="pills--other-info-tab-nobd">
                            <div class="card">
                                <div class="card-body">
                                    <form id="other_info_form">
                                        <div class="row">
                                        <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="weight">Weight (Kls) </label>
                                                    <input type="number" class="form-control numbers-only" id="weight" name="weight"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="height">Height (Cms) </label>
                                                    <input type="number" class="form-control numbers-only"  id="height" name="height"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="blood_type_id">Blood Type <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="blood_type_id" name="blood_type_id" class="form-control select2-list required"
                                                            data-module="blood_types" data-action="select2">
                                                            <option value="" disabled selected>Select Blood Type</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="educational_attainment_id">Educational Attainment <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="educational_attainment_id" name="educational_attainment_id" class="form-control select2-list required"
                                                            data-module="educational_attainments" data-action="select2">
                                                            <option value="" disabled selected>Select Educational Attainment</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="email">Email </label>
                                                    <input type="text" class="form-control" id="email" name="email"  >
                                                </div>
                                            </div>
                                            
                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="contact_no">Contact No. </label>
                                                    <input type="text" class="form-control" id="contact_no" name="contact_no"  >
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group" >
                                                    <label for="baranggay_id">Baranggay <span class="required-label">*</span></label>
                                                    <div class="select2-input">
                                                        <select id="baranggay_id" name="baranggay_id" class="form-control select2-list required" 
                                                            data-module="baranggays" data-action="select2">
                                                            <option value="" disabled selected>Select Barrangay</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6 col-lg-6">
                                                <div class="form-group">
                                                    <label for="purok_id">Purok <span class="required-label">*</span></label>
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
                                                    <label for="street_building_house">Street/Buiding No/House No <span class="required-label">*</span></label>
                                                    <textarea name="street_building_house" id="street_building_house" rows="3" class="form-control success" required="" aria-invalid="false"></textarea>
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
                <button type="button" class="btn btn-secondary btn-save-create"><i class="fas fa-check-circle"></i> Save</button>
            </div>
        </div>
    </div>
</div>