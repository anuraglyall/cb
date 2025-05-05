<!doctype html>
<html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">
<head>

            <meta charset="utf-8" />
            <title>Reset Password - 64FacetCRM</title>
            <link rel="shortcut icon" href="<?php  echo base_url().'theme/';?>assets/images/favicon.ico">
            <link href="<?php echo base_url(); ?>theme/assets/libs/sweetalert2/sweetalert2.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css?ver=<?php echo date('Ymd'); ?>" />
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css?ver=<?php echo date('Ymd'); ?>" />
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css?ver=<?php echo date('Ymd'); ?>">
            <?php
            $this->load->view('templates/headercss');
            ?>    

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
                                            <label for="username" class="form-label">Email</label>
                                            <input type="hidden" value="<?php echo $login; ?>" class="form-control" id="id" name='id' >
                                            <input type="email" class="form-control" id="email" name='email'  required  
                                                   placeholder="Enter Email">
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
            <?php
            $this->load->view('templates/footerjs');
            ?>
            <script src="<?php echo base_url(); ?>theme/assets/libs/prismjs/prism.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js?ver=<?php echo date('Ymd'); ?>" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <script src="<?php echo base_url() . 'theme/'; ?>assets/libs/sweetalert2/sweetalert2.min.js?ver=<?php echo date('Ymd'); ?>"></script>
            <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/css/selectize.min.css?ver=<?php echo date('Ymd'); ?>">
            <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js?ver=<?php echo date('Ymd'); ?>"></script>

            <script>
                $("#form_post").on('submit', (function (e) {
                e.preventDefault();
                $.ajax({
                url: "<?php echo base_url().'';?>login/temp_email_reset",
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
                            
                        var result = JSON.parse(data);
//                        alert(result.success);
//                        alert(result.message);
                        if(result.success==false)
                        {
                            Swal.fire({
                            icon: 'error',
                            title: 'Thanks!',
                            text: result.message,
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                            });                               
                        }    
                        else
                        {    
                            Swal.fire({
                            icon: 'success',
                            title: 'Thanks!',
                            text: result.message,
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                            });                               
                                                                                
                        }
                        },
                        error: function (e)
                        {
//                            Swal.fire({
//                            icon: 'success',
//                            title: 'Thanks!',
//                            text: "Something Went Wrong!",
//                            confirmButtonClass: "btn btn-primary w-xs mt-2",
//                            buttonsStyling: false
//                            });                               
                        
                        }
                });
                }));
    </script>
</body>
</html>