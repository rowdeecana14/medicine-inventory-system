<?php $navbar_active = "dashboard"; ?>
<?php include_once('../../app/template/BaseTemplate.php'); ?>
<?php notAuthenticate(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
	<title>Dashboard - <?= app_code() ?></title>
	<?php include_once('../layouts/header.php'); ?>
</head>
<body>
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
					<div class="row mt-3">
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-info card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-user-lock"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Users</p>
												<h4 class="card-title users">0</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-success card-round">
								<div class="card-body">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-user-md"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Health Officials</p>
												<h4 class="card-title health_officials">0</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-secondary card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-users"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Patients</p>
												<h4 class="card-title patients">0</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-sm-6 col-md-3">
							<div class="card card-stats card-warning card-round">
								<div class="card-body ">
									<div class="row">
										<div class="col-5">
											<div class="icon-big text-center">
												<i class="fas fa-medkit"></i>
											</div>
										</div>
										<div class="col-7 col-stats">
											<div class="numbers">
												<p class="card-category">Inventories</p>
												<h4 class="card-title inventories">0</h4>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title"><i class="fas fa-list pr-1"></i> Low stock list quantity (Top 10)</div>
								</div>
								<div class="card-body pb-0 low-stocks" >
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title"><i class="fas fa-list pr-1"></i>  Expiring stock list quantity (Top 10)</div>
								</div>
								<div class="card-body pb-0 expiring-stocks">
								</div>
							</div>
						</div>

						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title"><i class="fas fas fa-chart-pie pr-1"></i> Available stocks (By categories)</div>
								</div>
								<div class="card-body">
									<div class="chart-container">
										<canvas id="available-stocks" style="width: 50%; height: 50%"></canvas>
									</div>
								</div>
							</div>
						</div>
						
						<div class="col-md-6">
							<div class="card">
								<div class="card-header">
									<div class="card-title"><i class="fas fas fa-chart-pie pr-1"></i> Expired stocks (By categories)</div>
								</div>
								<div class="card-body">
									<div class="chart-container">
										<canvas id="expired-stocks" style="width: 50%; height: 50%"></canvas>
									</div>
								</div>
							</div>
						</div>
						

						<div class="col-md-12 col-lg-12">
							<div class="card">
								<div class="card-header">
									<div class="card-title"><i class="fas fa-chart-line pr-1"></i>Stocks Movement (By Monthly)</div>
								</div>
								<div class="card-body">
									<div class="chart-container">
										<canvas id="stock-movements" style="width: 50%; height: 50%"></canvas>
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
	<!-- Chart JS -->
	<script src="../../public/assets/js/plugin/chart.js/chart.min.js"></script>
	<script src="../../public/custom/js/script.js"></script>
    <script src="../../public/custom/js/dashboard.js"></script>
	<!-- End Scripts -->
</body>
</html>