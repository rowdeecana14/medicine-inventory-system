<?php $navbar_active = "reports"; ?>
<?php include_once('../../app/template/BaseTemplate.php'); ?>
<?php notAuthenticate(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Reports - SIMS</title>
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
										<h4 class="card-title"><i class="fas fa-list-alt"></i> Payment Reports</h4>
									</div>
								</div>
								<div class="card-body filter" >
									<form id="filter-form" class="col-12" style="margin-bottom: 50px;">
										<div class="row">
											<div class="col-md-3 col-lg-3">
												<div class="form-group">
													<label for="types">Types<span class="required-label">*</span></label>
													<div class="select2-input">
														<select id="types" name="types" class="form-control  required select-field">
															<option value="all" selected>All</option>
															<option value="business">Business Permit</option>
															<option value="sanitary">Sanitary Permit</option>
															<option value="medical">Medical Certifcate</option>
															<option value="exhumation">Exhumation Certifcate</option>
															<option value="transfer">Transfer Of Cadaver</option>
														</select>
													</div>
												</div>
											</div>
											<div class="col-md-3 col-lg-3">
												<div class="form-group">
													<label for="date_from">Date from<span class="required-label">*</span></label>
													<input type="text" class="form-control required datepicker" id="date_from" name="date_from">
												</div>
											</div>
											<div class="col-md-3 col-lg-3">
												<div class="form-group">
													<label for="date_end">Date end<span class="required-label">*</span></label>
													<input type="text" class="form-control required datepicker" id="date_end" name="date_end">
												</div>
											</div>
											<div class="col-md-3 col-lg-3">
												<button class="btn btn-primary btn-round ml-auto btn-search" style="margin-top: 40px" type="submit">
													<i class="fas fa-search "></i>
													Search 
												</button>
											</div>
										</div>
									</form>
									<div class="col-md-12 col-lg-12">
										<div class="table-responsive filter-result" hidden>
											<div class="row">
												<div class="col-md-6 col-lg-6">
													<h3 class="filter-result-title"></h3>
												</div>
												<div class="col-md-6 col-lg-6">
													<div class="float-right">
														<button type="button" class="btn btn-icon btn-round btn-secondary btn-print"
															data-toggle="tooltip" data-placement="top" title="Print record">
															<i class="fa fa-print"></i>
														</button>
														<a type="button" class="btn btn-icon btn-round btn-success btn-excel"
															download="title"  onclick="return ExcellentExport.excel(this, 'table-export', 'sheet 1');"
															data-toggle="tooltip" data-placement="top" title="Download as excel">
															<i class="fas fa-file-excel" style="padding-top: 10px; color: white"></i>
														</a>
													</div>
												</div>
											</div>

											<div style=" height: 25rem; overflow-y: scroll">
												<table class="display table table-striped table-hover" id="table-export">
													<thead>
														<tr>
															<th>#</th>
															<th>OR No.</th>
															<th>Amount</th>
															<th>Date Paid</th>
														</tr>
													</thead>
													<tbody>
													
													</tbody>
												</table>
											</div>
										</div>
									</div>
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
    <script src="../../public/custom/js/reports.js"></script>
	<script src="../../public/assets/js/plugin/excellentexport/excellentexport.js"></script>
	<!-- End Scripts -->
</body>
</html>