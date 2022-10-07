<?php include_once('./app/template/BaseTemplate.php'); ?>
<?php isAuthenticate(); ?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>Login - SIMS</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <link rel="icon" href="public/assets/img/config/escalante.png">
  <!-- Bootstrap 3.3.6 -->
  <link rel="stylesheet" href="public/assets/css/bootstrap.min.css">
  <script src="public/assets/js/plugin/webfont/webfont.min.js"></script>
  <script>
    WebFont.load({
      google: {"families":["Lato:300,400,700,900"]},
      custom: {"families":["Flaticon", "Font Awesome 5 Solid", "Font Awesome 5 Regular", "Font Awesome 5 Brands", "simple-line-icons"], urls: ['../../public/assets/css/fonts.min.css']},
      active: function() {
        sessionStorage.fonts = true;
      }
    });

    let app_code = "<?= app_code() ?>" ;
    let app_title =  "<?= app_title() ?>";
    let app_api_url = "<?= api_url() ?>";
    let base_url ="<?= base_url() ?>";
    let app_csrf_token = "<?= csrf() ?>";
  </script>
  <link rel="stylesheet" href="public/admin-lte/css/custom.min.css">
  <!-- AdminLTE Skins. -->
  <link rel="stylesheet" href="public/admin-lte/skins/_all-skins.min.css">
 <style type="text/css">
   .modal {
      text-align: center;
      padding: 0!important; 
    }

    .modal:before {
      content: '';
      display: inline-block;
      height: 100%;
      vertical-align: middle;
      margin-right: -4px; /* Adjusts for spacing */
    }

    .modal-dialog {
      display: inline-block;
      text-align: left;
      vertical-align: middle;
    }
 </style>
  

</head>
<!-- ADD THE CLASS layout-top-nav TO REMOVE THE SIDEBAR. -->
<body class="hold-transition skin-red-light layout-top-nav">
<div class="wrapper">

    <header class="main-header">
      <nav class="navbar navbar-static-top" style="background-color:#357ca5; border-bottom: 2px solid #0a4e72">
        <div class="container">
          <div class="navbar-header">
              <a href="index.php" class="navbar-brand"><b style="color:white; font-size: 24px"><?= strtoupper(app_title()) ?></b></a>
          </div>
        </div>
      </nav>
    </header>

    <div class="content-wrapper" style="background-image: url(public/assets/img/config/background.jpg); background-size: cover; min-height: 600px;">
      <div class="container">
            <section class="content" >
                <!--Client Form-->
                <div class="row">
                  <div class="col-lg-6">
                      <div class="pull-right"  style=" margin-top: 120px; margin-left: 50px; color: black">
                          <center>
                            <br><br>
                            <h4>
                              <b id="date"></b>
                              <b id="timer"></b>
                            </h4>
                          <img id="default_logo" src="public/assets/img/config/escalante-lg.png" width="200px" height="20%">
                        </center>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-6" >
                      <form id="login-form" method="post" width="100%">
                          <div id="#addModal" class="modal-dialog modal-primary modal-fade modal-md pull-left" style="width:400px !important; margin-top: 120px">
                              <div class="modal-content">
                                  <div class="modal-header">
                                      <center><h3 class="modal-title"><img src="public/assets/img/config/login.png" height="45px"><?= app_code() ?> Login </h3></center>
                                      </div>
                                  <div class="modal-body">
                                      <div class="row">
                                          <div class="col-md-12">
                                          <div class="form-group">
                                              <label style="font-size: 18px;">Username:</label>
                                              <div class="input-group">
                                                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                                                <input type="text" class="form-control input-md" name="username" id="username" placeholder="Username" autofocus required>
                                              </div>

                                          </div>
                                              <div class="form-group">
                                                  <label style="font-size: 18px;">Password:</label>
                                                  <div class="input-group">
                                                      <span class="input-group-addon"><i class="fa fa-key"></i></span>
                                                      <input type="password" class="form-control input-md" name="password" id="password" placeholder="Password" autofocus required>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                    <button type="reset" class="btn btn-default btn-md btn-active" style="font-weight: bold"><i class="fa fa-times-circle"> </i> Cancel</button>
                                      <button type="submit" class="btn btn-default btn-md btn-active" style="font-weight: bold"><i class="fa fa-sign-in"> </i> Login</button>
                                  </div>
                                  
                              </div>
                          </div>
                      </form>
                    </div>
            
                </div>
              </section>
          </div>
      </div>
        
    <!-- jQuery -->
  <script src="public/assets/js/core/jquery.3.2.1.min.js"></script>
  <script src="public/assets/js/core/popper.min.js"></script>
  <!-- Bootstrap -->
  <script src="public/assets/js/core/bootstrap.min.js"></script>
  <!-- ADMIN LTE JS -->
  <script src="public/admin-lte/js/custom.min.js"></script>
  <script src="./public/assets/js/plugin/sweetalert/sweetalert.min.js"></script>
  <script src="./public/assets/js/plugin/jquery.validate/jquery.validate.min.js"></script>
  <script src="./public/assets/js/plugin/loader/waitMe.min.js"></script>
  <script src="./public/custom/js/api.js"></script>
  <script src="./public/custom/js/auth.js"></script>
</body>
</html>