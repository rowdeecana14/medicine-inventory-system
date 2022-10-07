<?php $navbar_active = "profile"; ?>
<?php include_once('../../app/template/BaseTemplate.php'); ?>
<?php notAuthenticate(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>User Profile - SIMS</title>
	<?php include_once('../layouts/header.php'); ?>
</head>
<body>
    <!-- Loader Screen -->
    <?php include_once('../layouts/loader.php'); ?>
    <!-- End Loader Screen -->
	<div class="wrapper">
		<div class="main-header">
			<!-- Logo Header -->
			<?php include_once('../layouts/logo_header.php'); ?>
			<!-- End Logo Header -->

			<!-- Navbar Header -->
			<?php include_once('../layouts/nav_bar.php'); ?>
			<!-- End Navbar -->
		</div>

		<!-- Sidebar -->
		<?php include_once('../layouts/side_bar.php'); ?>
		<!-- End Sidebar -->

		<div class="main-panel">
            <div class="content">
				<div class="page-inner">
					<div class="row">

						<div class="col-md-12">
							<div class="card">
								<div class="card-header">
									<div class="d-flex align-items-center">
										<h4 class="card-title"><i class="fas fa-address-card"></i> User profile</h4>
									</div>
								</div>
								<div class="card-body row" >
                                    <div class="col-md-6 col-lg-6">
                                        <div class="card card-profile mt-2" >
                                            <div class="card-header" style="background-image: url('../../public/assets/img/blogpost.jpg'); height: 150px !important;">
                                                <div class="profile-picture">
                                                    <div class="avatar avatar-xl  image-gallery" style="height: 12rem !important; width: 12rem !important; margin-top: 50px">
                                                        <a href="../../public/assets/img/config/female.png" class="image_href">
                                                            <img src="../../public/assets/img/config/female.png" alt="..." class="avatar-img rounded-circle preview-image" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="View Image">
                                                        </a>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-body">
                                                <div class="view-profile mt-3">
                                                    <div class="d-flex justify-content-center">
                                                        <div>
                                                            <button type="button" class="btn btn-primary btn-sm  btn-border btn-round btn-open-camera mr-1 " style="font-weight:900;"><i class="fas fa-camera-retro pl-1"></i> Camera</button>
                                                        </div>
                                                        <div>
                                                            <input type="file" class="form-control  form-control create-upload-image" id="image" name="image" accept="image/*"  hidden>
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
                                        <div class="mb-5 ml-2 mr-2" style="margin-top: 55px;">
                                            <button type="button" class="btn btn-block btn-secondary btn-save-avatar"><i class="fas fa-check-circle"></i> Update</button>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-6">
                                        <form id="account-form">
                                            <div class="form-group has-success">
                                                <label for="username">Username <span class="required-label">*</span></label>
                                                <input type="text" class="form-control required" id="username" name="username" aria-invalid="false">
                                            </div>
                                            <div class="form-group">
                                                <label for="current_password">Current Password <span class="required-label">*</span></label>
                                                <input type="password" class="form-control required" id="current_password" name="current_password" aria-invalid="false" autocomplete >
                                            </div>
                                            <div class="form-group">
                                                <label for="password">New Password <span class="required-label">*</span></label>
                                                <input type="password" class="form-control required" id="password" name="password" aria-invalid="false" autocomplete > 
                                            </div>
                                            <div class="form-group">
                                                <label for="confirm_password">Confirm Password <span class="required-label">*</span></label>
                                                <input type="password" class="form-control required" id="confirm_password" name="confirm_password" autocomplete >
                                            </div>
                                        </form>
                                        <div class="mt-3 mb-5 ml-2 mr-2">
                                            <button type="submit" form="account-form" class="btn btn-block btn-secondary"><i class="fas fa-check-circle"></i> Update</button>
                                        </div>
                                    </div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>

            <!-- Camera Modal -->
            <?php include_once('./modal/camera.php'); ?>
            <!-- End Camera Modal -->

			<!-- Start Footer -->
			<?php include_once('../layouts/footer.php'); ?>
			<!-- End Footer -->
		</div>
	</div>
	
	<!-- Start Scripts -->
    <?php include_once('../layouts/scripts.php'); ?>
    <script src="../../public/custom/js/script.js"></script>
    <script src="../../public/custom/js/profile.js"></script>
    <script src="../../public/assets/js/plugin/webcamjs/webcam.min.js"></script>
	<!-- End Scripts -->
</body>
</html>