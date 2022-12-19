<?php $navbar_active = "reports_dispencing"; ?>
<?php include_once('../../app/template/BaseTemplate.php'); ?>
<?php notAuthenticate(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Medicine Dispensing Report - <?= app_code() ?></title>
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
										<h4 class="card-title"><i class="fas fa-arrow-circle-right pr-1"></i> Medicine Dispensing Report</h4>
										<button class="btn btn-primary btn-round ml-auto btn-filter" >
											<i class="fas fa-filter pr-1"></i>
											Filter 
										</button>
									</div>
								</div>
								<div class="card-body">
									<!-- Modal Filter -->
                                    <?php include_once('./modal/filter.php'); ?>
									<!-- End Modal Filter -->

									<!-- List Table -->
                                    <?php include_once('lists.php'); ?>
									<!-- End List Table -->
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<!-- Start Footer -->
			<?php include_once('../layouts/footer.php'); ?>
			<!-- End Footer -->
		</div>
	</div>
	
	<!-- Start Scripts -->
    <?php include_once('../layouts/scripts.php'); ?>
    <script src="../../public/custom/js/script.js"></script>
    <script src="../../public/custom/js/reports/stocks-despencing.js"></script>
	<!-- End Scripts -->
</body>
</html>