<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta content='width=device-width, initial-scale=1.0, shrink-to-fit=no' name='viewport' />
<link rel="icon" href="../../public/assets/img/config/mis-sm.png">

<!-- Fonts and icons -->
<script src="../../public/assets/js/plugin/webfont/webfont.min.js"></script>
<script>
	WebFont.load({
		google: {"families":["Lato:300,400,700,900"]},
		custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../../public/assets/css/fonts.min.css']},
		active: function() {
			sessionStorage.fonts = true;
		}
	});

	let auth_user = <?=auth_user() ?>;
	let app_code = "<?= app_code() ?>" ;
	let app_title =  "<?= app_title() ?>";
	let app_api_url = "<?= api_url() ?>";
	let base_url ="<?= base_url() ?>";
	let login_count = <?= loginCount() ?>;
	let app_csrf_token = "<?= csrf() ?>";
	let app_uploaded_patient_path = "<?= app_uploaded_path('patient') ?>";
	let app_uploaded_medicine_path = "<?= app_uploaded_path('medicine') ?>";
	let app_uploaded_health_official_path = "<?= app_uploaded_path('health-official') ?>";
	let app_uploaded_config = "<?= app_config_path() ?>";
</script>
<!-- CSS Loader -->
<link rel="stylesheet" href="../../public/assets/js/plugin/loader/waitMe.min.css">

<!-- Datatables -->
<link rel="stylesheet" href="../../public/assets/js/plugin/datatables/datatables.min.css">
<link rel="stylesheet" href="../../public/assets/js/plugin/datatables/Buttons-2.2.2/css/buttons.bootstrap5.min.css">
<link rel="stylesheet" href="../../public/assets/js/plugin/datatables/Responsive-2.2.9/css/responsive.bootstrap4.min.css">

<!-- Select2 -->
<link rel="stylesheet" href="../../public/assets/js/plugin/select2/select2.min.css">

<!-- Daterangepicker -->
<link rel="stylesheet" href="../../public/assets/js/plugin/daterangepicker/daterangepicker.css">

<!-- CSS Files -->
<link rel="stylesheet" href="../../public/assets/css/bootstrap.min.css">
<link rel="stylesheet" href="../../public/assets/css/atlantis.css">
<link rel="stylesheet" href="../../public/assets/css/plugins.css">

<!-- CSS Just for demo purpose, don't include it in your project -->
<link rel="stylesheet" href="../../public/assets/css/demo.css">

<!-- Custom CSS -->
<link rel="stylesheet" href="../../public/custom/css/style.css">

