
<!doctype html>
<html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">


<!-- Mirrored from themesbrand.com/velzon/html/corporate/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 17 Jun 2023 09:21:08 GMT -->
<head>

    <meta charset="utf-8" />
    <title>Sign In</title>
    <link rel="shortcut icon" href="<?php  echo base_url().'theme/';?>assets/images/favicon.ico">
    <script src="<?php  echo base_url().'theme/';?>assets/js/layout.js"></script>
    <link href="<?php  echo base_url().'theme/';?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo base_url().'theme/';?>assets/css/icons.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo base_url().'theme/';?>assets/css/app.min.css" rel="stylesheet" type="text/css" />
    <link href="<?php  echo base_url().'theme/';?>assets/css/custom.min.css" rel="stylesheet" type="text/css" />

</head>

<body>

    <div class="auth-page-wrapper pt-5">
        <!-- auth page bg -->
        <div class="auth-one-bg-position auth-one-bg" id="auth-particles">
            <div class="bg-overlay"></div>

            <div class="shape">
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1440 120">
                    <path d="M 0,36 C 144,53.6 432,123.2 720,124 C 1008,124.8 1296,56.8 1440,40L1440 140L0 140z"></path>
                </svg>
            </div>
        </div>

        <!-- auth page content -->
        <div class="auth-page-content">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center mt-sm-5 mb-4 text-white-50">
                            <div>
                                <a href="<?php  echo base_url().'';?>" class="d-inline-block auth-logo">
                                    <img src="https://64facets.com/cdn/shop/files/64FACETS_LOGO_FINAL_white_8110538b-04e2-4e78-81b0-c320e6e64ad3_150x.png?v=1631703384" alt="" height="20">
                                </a>
                            </div>
                            <p class="mt-3 fs-15 fw-medium">Welcome to 64facets</p>
                        </div>
                    </div>
                </div>
                <!-- end row -->

                <div class="row justify-content-center">
                    <div class="col-md-8 col-lg-6 col-xl-5">
                        <div class="card mt-4">

                            <div class="card-body p-4">
                                <div class="text-center mt-2">
                                    <h5 class="text-primary">Welcome Back !</h5>
                                    <p class="text-muted">Sign in to continue to <?php  echo COMPANYNAME;?>.</p>
                                </div>
                                <div class="p-2 mt-4">
                                    <?php if ($this->session->flashdata('login_failed')) : ?>
                                        <div >
                                            <div class="alert alert-secondary" role="alert">
                                                    <strong>Oops !</strong> <b><?php echo $this->session->flashdata('login_failed'); ?></b>
                                              </div>
                                            
                                        </div>
                                    <?php endif; ?> 

                                    <form method="post" id="form_post" action="<?php echo site_url('login/process_login'); ?>">
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Password</label>
                                            <input type="hidden" value="<?php echo $login; ?>" class="form-control" id="id" name='id' >
                                            <input type="password" min="8" class="form-control" minlength="6" id="add_password" name='add_password'  required  placeholder="Enter username">
                                        </div>
                                        <div class="mb-3">
                                            <label for="username" class="form-label">Re Password</label>
                                            <input type="password" min="8"  class="form-control" minlength="6"  id="re_add_password" name='re_add_password'  required  placeholder="Enter username">
                                        </div>
                                        <div class="mt-4">
                                            <button id="publish-button" type="submit" class="btn btn-secondary w-100" type="submit">Submit</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!-- end card body -->
                        </div>
                        <!-- end card -->

                       

                    </div>
                </div>
                <!-- end row -->
            </div>
            <!-- end container -->
        </div>
        <!-- end auth page content -->

        <!-- footer -->
        <footer class="footer">
            <div class="container">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="text-center">
                            <p class="mb-0 text-muted">&copy;
                                <script>document.write(new Date().getFullYear())</script> <?php  echo COMPANYNAME;?>. Crafted with <i class="mdi mdi-heart text-danger"></i> by <?php  echo COMPANYNAME;?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </footer>
        <!-- end Footer -->
    </div>
    <!-- end auth-page-wrapper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        
    <!-- JAVASCRIPT -->
    <script src="<?php  echo base_url().'theme/';?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?php  echo base_url().'theme/';?>assets/libs/simplebar/simplebar.min.js"></script>
    <script src="<?php  echo base_url().'theme/';?>assets/libs/node-waves/waves.min.js"></script>
    <script src="<?php  echo base_url().'theme/';?>assets/libs/feather-icons/feather.min.js"></script>
    <script src="<?php  echo base_url().'theme/';?>assets/js/pages/plugins/lord-icon-2.1.0.js"></script>
    <script src="<?php  echo base_url().'theme/';?>assets/js/plugins.js"></script>
    

    <!-- particles js -->
    <script src="<?php  echo base_url().'theme/';?>assets/libs/particles.js/particles.js"></script>
    <!-- particles app js -->
    <script src="<?php  echo base_url().'theme/';?>assets/js/pages/particles.app.js"></script>
    <!-- password-addon init -->
    <script src="<?php  echo base_url().'theme/';?>assets/js/pages/password-addon.init.js"></script>
    <script>
                $("#form_post").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                url: "<?php echo base_url().'';?>login/process_login",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function ()
                        {
//                        alert('11');        
                        },
                        success: function (data)
                        {
                        if(data==1)
                        {
                        window.location.href='dashboard';    
                        }    
                        else
                        {    
                        alert("login failed, please try again!");
                        }
                        },
                        error: function (e)
                        {
                        alert("login failed, please try again!");    
                        }
                });
                }));
    </script>
</body>


<!-- Mirrored from themesbrand.com/velzon/html/corporate/auth-signin-basic.html by HTTrack Website Copier/3.x [XR&CO'2014], Sat, 17 Jun 2023 09:21:08 GMT -->
</html>