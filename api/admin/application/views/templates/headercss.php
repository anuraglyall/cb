    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@100;200;300;400;500;&display=swap" rel="stylesheet">
    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php  echo base_url().'theme/';?>assets/images/favicon.ico">    <!-- Layout config Js -->
    <!-- plugin css -->
    <link href="<?php  echo base_url().'theme/';?>assets/libs/jsvectormap/css/jsvectormap.min.css" rel="stylesheet" type="text/css" />
    <script src="<?php  echo base_url().'theme/';?>assets/js/layout.js?ver=<?php echo date('Ymd'); ?>"></script>
    <!-- Bootstrap Css --> 
    <link href="<?php  echo base_url().'theme/';?>assets/css/bootstrap.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="<?php  echo base_url().'theme/';?>assets/css/icons.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="<?php  echo base_url().'theme/';?>assets/css/app.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
    <!-- custom Css-->
    <link href="<?php  echo base_url().'theme/';?>assets/css/custom.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
    
    <style>                
                .offcanvas.hiding, .offcanvas.show, .offcanvas.showing {
                    z-index: 9999;
                }
                input:read-only{
                background-color: #f2f2f2 !important;
                color: #666 !important;
                }
                .form-switch .form-check-input:checked { 
                    background-color: blue !important; 
                }
                input[type=checkbox]:checked { 
                    background-color: blue !important; 
                }

                .show_table
                {
                display:block;    
                }
                .hide_table
                {
                display:none;    
                } 
                .modal-fullscreen {
                width: 100vw;
                max-width: none;
                margin: 0;
                }
                .is-hidden
                {
                display:none;    
                }
                .navbar-menu .navbar-nav .nav-sm .nav-link.active {
                    color: white;
                    background-color: #1E2E50;
                    opacity: 0.7;
                    font-weight: bold; 
                    font-size: 14px;
                    padding: 0px;
                }
                .navbar-menu .navbar-nav .nav-sm .nav-link:before
                {
                    background-color: #1E2E50 !important;
                }
                .hide_details
                {
                    display:none;
                }
                .vendor_display
                {

                }

                .partners_display
                {
                    display:none;
                }

                .p-3 {
                    padding: 5px !important;
                }
                hr{
                    margin:2px 0px !important;
                    clear:both;
                }
                .modal-content {
                    height: 100%;
                    border-radius: 0;
                }

                .modal-body {
                    height: calc(100% - 56px); 
                    overflow-y: auto;
                }
                .column-toggle-container label
                {
                    padding: 0px 10px;
                    font-size:14px;
                    margin:0px;
                }

                .extra_div_css
                {
                    padding-right:5px;
                }  
                
                .table>:not(caption)>*>* {
                    padding: 0.55rem 0.4rem !important;
                }
                .empty-notification-elem
                {
                    display:none;
                }
                [data-layout=semibox] .page-title-box
                {
                    margin:-15px 0 0.5rem 0 !important;
                }
                .modal-body
                {
                    padding: 10px;
                }
                .form-control{
                    height: 30px !important;
                    font-size:14px;
                    font-weight: 400;
                }
                .selectize-input>*
                {
                font-size:14px !important;    
                }
                .selectize-input {
                padding: 5px 5px !important;    
                }
                .card {
                margin-bottom: 0px;
                }
                .form-label
                {
                    font-size:14px;
                        margin-bottom: 0.1rem;
                        font-weight: 400;
                }
                .btn
                {
                        font-size: 12px;
                        font-weight: 400;
                }
                #page-topbar {
                    left: 215px;
                }
                .main-content {
                    margin-left: 205px;
                }
                [data-layout=semibox] .page-title-box {
                    margin: -25px 0 0.0rem 0 !important;
                }
                .card-header {
                    padding: 5px 0px;
                }
                .card-body {
                    padding: 5px;
                 }
                .navbar-menu .navbar-nav .nav-sm .nav-link.active
                {
                    font-size: 12px;    
                }
                .page-content {
                    padding: calc(70px + 1.5rem) 0 0px 0;
                }
                .navbar-menu {
                    width: 210px;
                }
                ::placeholder {
                    font-size: 12px; 
                    font-weight:400;
                    padding:10px;
                }
                .selectize-dropdown-content {
                    border: 1px groove grey !important;
                    background-color: white!important;
                    padding: 10px 5px !important;
                }
                .form-control {
                    margin-bottom: 0.2rem!important;
                }
                .form-control.select {
                    border: none;
                }
                .selectize-dropdown, .selectize-input, .selectize-input input
                {
                    font-size: inherit !important;
                }
                .h5, h5 {
                    font-size: 14px;
                }
                
                .navbar-menu .navbar-nav .nav-link
                {
                 padding: 10px 15px;
                 font-size: 14px;   
                 font-weight:bold;
                }
                .btn-group-vertical>.btn, .btn-group>.btn {
                    font-weight: bold;
                }
            <?php
            $fullUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            if (strpos($fullUrl, 'add-new-style') !== false || strpos($fullUrl, 'user_management') !== false  || strpos($fullUrl, 'orders') !== false) {
            ?>
            .extra_div_css
                { 
                    min-height:0px !important;
                    height:auto;
                }
            .extra_div_css2
                { 
                    min-height:40px !important;
                    height:auto;
                }
            <?php
            }
            else
            {    
            ?>
            .extra_div_css
                {
                    min-height:0px;
                    height:auto;
                }    
            <?php
            }
            ?>
    .cstm-dropdown{
        border: 1px solid #d0d0d0;
        padding: 8px 8px;
        display: inline-block;
        width: 100%;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-sizing: border-box;
        box-shadow: inset 0 1px 1px rgba(0,0,0,.1);
        border-radius: 3px;
    }

    .cstm-form {
        display: flex;
        justify-content: center;
        gap: 20px;
        margin-bottom: 50px;
    }
    select#coursefolder {
        border: 1px solid #d0d0d0;
        padding: 8px 8px;
        display: inline-block;
        width: 100%;
        height: 35px;
        overflow: hidden;
        position: relative;
        z-index: 1;
        box-sizing: border-box;
        box-shadow: inset 0 1px 1px rgba(0, 0, 0, .1);
        border-radius: 3px;
    }

    input#folderText {
        height: 35px;
        width: 100%;
    }

    button.createFolderBtn {
        background-color: rgb(30 46 80);
        color: white;
        width: 20%;
        height: 35px;
        border-radius: 5px;
    }
    .cstm-modal {
        margin: 60px 0 60px 0;
        border-bottom: 1px solid black;
        padding: 5px;
    }
    
    .border {
        border: 1px solid black !important;
    }

    .marginBottom {
        margin-bottom: 25px !important;
    }
    .truncate {
      display: -webkit-box;
      -webkit-line-clamp: 1;
      -webkit-box-orient: vertical;
      overflow: hidden;
      text-overflow: ellipsis;
    }
        </style>    