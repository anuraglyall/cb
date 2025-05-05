
<!doctype html>
<html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>

    <meta charset="utf-8" />
    <title>Sub Category</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    
    <link href="<?php echo base_url(); ?>theme/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />

     <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    
    
    
   <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
   <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
   <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
   <?php 
    $this->load->view('templates/headercss');
    ?>
</head>

<body>

        <!-- Begin page -->
         <div id="layout-wrapper">
         <?php 
              $this->load->view('templates/header');
         //    echo $this->load("templates/header");
         ?>
         <?php 
              $this->load->view('templates/menu');
         //    echo $this->load("templates/header");
         ?>
        <!-- Vertical Overlay-->
        <div class="vertical-overlay"></div>
        
        
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">

            <div class="page-content">
                <div class="container-fluid">

                    <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0"><?php echo $title; ?></h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                        <li class="breadcrumb-item active">Masters</li>
                                        <li class="breadcrumb-item active"><?php echo $title2; ?></li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                    </div>
                    

                    

                    
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                                <h5 class="card-title mb-0">
                                                <span style='float:left;'><?php echo $title2; ?></span>
                                                <div style='float:right;'>
                                                    <button type="button" class="btn btn-success add-btn btn-sm" data-bs-toggle="modal" id="create-btn" data-bs-target="#showModal"><i class="ri-add-line align-bottom me-1"></i> Add <?php echo $title; ?></button>
                                                </div>
                                                </h5>
                                </div>
                                <div class="modal fade" id="showModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Add <?php echo $title; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                        </div>  
                                        <form class="tablelist-form" enctype="multipart/form-data" method="post" id="add-category-form"  autocomplete="off">
                                            <div class="modal-body">
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_type" class="form-label">Partner Type</label>
                                                    <select class="form-control" onchange="select_partner_type();" required id="partner_type" name="partner_type">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <?php 
                                                        if($type=='partner')
                                                        {
                                                        ?>
                                                        <option value="1">Partner</option>
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                        <?php 
                                                        if($type=='customer')
                                                        {
                                                        ?>
                                                        <option value="2">Customer</option>
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_id" class="form-label">Partner ID</label>
                                                    <input type="text" id="partner_id" name="partner_id" class="form-control" readonly />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" id="first_name" name="first_name" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" id="last_name" name="last_name" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="company_name" class="form-label">Company Name</label>
                                                    <input type="text" id="company_name" name="company_name" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="email_address" class="form-label">Email Address</label>
                                                    <input type="email" id="email_address" name="email_address" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="phone_number" class="form-label">Phone Number</label>
                                                    <input type="tel" id="phone_number" name="phone_number" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="discount" class="form-label">Retail Price Discount(%)</label>
                                                    <input type="number" id="discount" name="discount" class="form-control" />
                                                </div>
                                                
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="billing_address" class="form-label">Billing Address</label>
                                                    <input type="text" id="billing_address_line1" name="billing_address_line1" class="form-control" placeholder="Address Line 1" required />
                                                    <input type="text" id="billing_address_line2" name="billing_address_line2" class="form-control" placeholder="Address Line 2" />
                                                    <input type="text" id="billing_city" name="billing_city" class="form-control" placeholder="City" required />
                                                    <input type="text" id="billing_state" name="billing_state" class="form-control" placeholder="State" required />
                                                    <input type="text" id="billing_country" name="billing_country" class="form-control" placeholder="Country" required />
                                                    <input type="text" id="billing_zip" name="billing_zip" class="form-control" placeholder="Zip Code" required />
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="shipping_address" class="form-label">Shipping Address</label>
                                                    <input type="text" id="shipping_address_line1" name="shipping_address_line1" class="form-control" placeholder="Address Line 1" required />
                                                    <input type="text" id="shipping_address_line2" name="shipping_address_line2" class="form-control" placeholder="Address Line 2" />
                                                    <input type="text" id="shipping_city" name="shipping_city" class="form-control" placeholder="City" required />
                                                    <input type="text" id="shipping_state" name="shipping_state" class="form-control" placeholder="State" required />
                                                    <input type="text" id="shipping_country" name="shipping_country" class="form-control" placeholder="Country" required />
                                                    <input type="text" id="shipping_zip" name="shipping_zip" class="form-control" placeholder="Zip Code" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="value_add_method" class="form-label">Value Add Method</label>
                                                    <select class="form-control" id="value_add_method" name="value_add_method">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <option value="1">Method 1</option>
                                                        <option value="2">Method 2</option>
                                                        <option value="new">Add New Method</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="wastage" class="form-label">Wastage (%)</label>
                                                    <input type="number" id="wastage" name="wastage" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="tax_id" class="form-label">Tax ID #</label>
                                                    <input type="text" id="tax_id" name="tax_id" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="gst_number" class="form-label">GST#</label>
                                                    <input type="text" id="gst_number" name="gst_number" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="pan_number" class="form-label">PAN#</label>
                                                    <input type="text" id="pan_number" name="pan_number" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_reference" class="form-label">Partner Reference #</label>
                                                    <input type="text" id="partner_reference" name="partner_reference" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="price_type" class="form-label">Price Type</label>
                                                    <select class="form-control" id="price_type" name="price_type">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <option value="1">Type 1</option>
                                                        <option value="2">Type 2</option>
                                                        <option value="new">Add New Type</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="level_of_access" class="form-label">Level of Access</label>
                                                    <input type="text" id="level_of_access" name="level_of_access" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="notes" class="form-label">Notes</label>
                                                    <textarea id="notes" style="height:17px;" name="notes" class="form-control"></textarea>
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="photo" class="form-label">Upload Photo</label>
                                                    <input type="file" id="photo" name="photo" class="form-control" accept="image/*" />
                                                </div>
                                                
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Add <?php echo $title; ?></button>
                                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                                
                                
                                <div class="modal fade" id="showModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-lg">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit <?php echo $title; ?></h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                        </div>  
                                        <form class="tablelist-form" enctype="multipart/form-data" method="post" id="edit-category-form"  autocomplete="off">
                                            <div class="modal-body">
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_type" class="form-label">Partner Type</label>
                                                    <select class="form-control" onchange="select_partner_type();" required id="edit_partner_type" name="partner_type">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <?php 
                                                        if($type=='partner')
                                                        {
                                                        ?>
                                                        <option value="1">Partner</option>
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                        <?php 
                                                        if($type=='customer')
                                                        {
                                                        ?>
                                                        <option value="2">Customer</option>
                                                        <?php
                                                        }
                                                        ?>
                                                        
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_id" class="form-label">Partner ID</label>
                                                    <input type="text" id="edit_partner_id" name="partner_id" class="form-control" readonly />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="first_name" class="form-label">First Name</label>
                                                    <input type="text" id="edit_first_name" name="first_name" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="last_name" class="form-label">Last Name</label>
                                                    <input type="text" id="edit_last_name" name="last_name" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="company_name" class="form-label">Company Name</label>
                                                    <input type="text" id="edit_company_name" name="company_name" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="email_address" class="form-label">Email Address</label>
                                                    <input type="email" id="edit_email_address" name="email_address" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="phone_number" class="form-label">Phone Number</label>
                                                    <input type="tel" id="edit_phone_number" name="phone_number" class="form-control" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="discount" class="form-label">Retail Price Discount(%)</label>
                                                    <input type="number" id="edit_discount" name="discount" class="form-control" />
                                                </div>
                                                
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="billing_address" class="form-label">Billing Address</label>
                                                    <input type="text" id="edit_billing_address_line1" name="billing_address_line1" class="form-control" placeholder="Address Line 1" required />
                                                    <input type="text" id="edit_billing_address_line2" name="billing_address_line2" class="form-control" placeholder="Address Line 2" />
                                                    <input type="text" id="edit_billing_city" name="billing_city" class="form-control" placeholder="City" required />
                                                    <input type="text" id="edit_billing_state" name="billing_state" class="form-control" placeholder="State" required />
                                                    <input type="text" id="edit_billing_country" name="billing_country" class="form-control" placeholder="Country" required />
                                                    <input type="text" id="edit_billing_zip" name="billing_zip" class="form-control" placeholder="Zip Code" required />
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="shipping_address" class="form-label">Shipping Address</label>
                                                    <input type="text" id="edit_shipping_address_line1" name="shipping_address_line1" class="form-control" placeholder="Address Line 1" required />
                                                    <input type="text" id="edit_shipping_address_line2" name="shipping_address_line2" class="form-control" placeholder="Address Line 2" />
                                                    <input type="text" id="edit_shipping_city" name="shipping_city" class="form-control" placeholder="City" required />
                                                    <input type="text" id="edit_shipping_state" name="shipping_state" class="form-control" placeholder="State" required />
                                                    <input type="text" id="edit_shipping_country" name="shipping_country" class="form-control" placeholder="Country" required />
                                                    <input type="text" id="edit_shipping_zip" name="shipping_zip" class="form-control" placeholder="Zip Code" required />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="value_add_method" class="form-label">Value Add Method</label>
                                                    <select class="form-control" id="edit_value_add_method" name="value_add_method">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <option value="1">Method 1</option>
                                                        <option value="2">Method 2</option>
                                                        <option value="new">Add New Method</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="wastage" class="form-label">Wastage (%)</label>
                                                    <input type="number" id="edit_wastage" name="wastage" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="tax_id" class="form-label">Tax ID #</label>
                                                    <input type="text" id="edit_tax_id" name="tax_id" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="gst_number" class="form-label">GST#</label>
                                                    <input type="text" id="edit_gst_number" name="gst_number" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="pan_number" class="form-label">PAN#</label>
                                                    <input type="text" id="edit_pan_number" name="pan_number" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="partner_reference" class="form-label">Partner Reference #</label>
                                                    <input type="text" id="edit_partner_reference" name="partner_reference" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="price_type" class="form-label">Price Type</label>
                                                    <select class="form-control" id="edit_price_type" name="price_type">
                                                        <option value="" selected>-- Please Select --</option>
                                                        <option value="1">Type 1</option>
                                                        <option value="2">Type 2</option>
                                                        <option value="new">Add New Type</option>
                                                    </select>
                                                </div>
                                                <div class="mb-3 col-md-3 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="level_of_access" class="form-label">Level of Access</label>
                                                    <input type="text" id="edit_level_of_access" name="level_of_access" class="form-control" />
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="notes" class="form-label">Notes</label>
                                                    <textarea id="edit_notes" style="height:17px;" name="notes" class="form-control"></textarea>
                                                </div>
                                                <div class="mb-3 col-md-6 pull-left" style="float:left;padding:0px 5px;">
                                                    <label for="photo" class="form-label">Upload Photo</label>
                                                    <input type="file" id="edit_photo" name="photo" class="form-control" accept="image/*" />
                                                </div>
                                                
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success btn-load" id="add-btn">Update <?php echo $title; ?>
                                                        
                                                    </button>
                                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                                
                                
                                
                                <div class="card-body">
                                    <table id="ajax_datatables2" class="display table table-bordered dt-responsive mt-3 mb-1" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Id</th>
                                                <th>Action</th>
                                                <th>Type</th>
                                                <th><?php echo $title ?> Id</th>
                                                <th>First Name</th>
                                                <th>Last Name</th>
                                                <th>Company</th>
                                                <th>Email</th>
                                                <th>Phone No</th>
                                                <th>Discount</th>
                                                <th>Value Added Mehtod</th>
                                                <th>Wastage</th>
                                                <th>Tax Id</th>
                                                <th>GST No</th>
                                                <th>Pan No</th>
                                                <th>Reference</th>
                                                <th>Price Type</th>
                                                <th>Access Level</th>
                                                <th>Notes</th>
                                                <th>Added Date</th>
                                                
                                            </tr>
                                        </thead>
                                        
                                    </table>
                                </div>
                            </div>
                        </div><!--end col-->
                    </div><!--end row-->

                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

        <?php 
        $this->load->view('templates/footer');
        ?>
        </div>
        <!-- end main content-->

    </div>
    <!-- END layout-wrapper -->
    
        
        
        
        <?php 
        $this->load->view('templates/setting');
        ?>

        <?php 
        $this->load->view('templates/footerjs');
        ?>
        
        <script src="<?php echo base_url(); ?>theme/assets/libs/prismjs/prism.js"></script>

        <script src="<?php echo base_url(); ?>assets/js/app.js"></script>
        
        


        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

        <!--datatable js-->
        <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
        <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
        <script src="<?php  echo base_url().'theme/';?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
        <script > 
        function initializeDataTable() 
        {
        var dataTable = $('#ajax_datatables2').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo base_url('partners/fetch_data'); ?>",
                        "data": {
                            "datatype": $("#type").val()
                        },
                        "type": "POST"
                    },
                             
                    "columns": [
                        { "data": "id" },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                var final="'"+row.id+"'"+","+"'"+row.partner_customer_type+"'"+","+"'"+row.partner_customer_id+"'";

                                return '<button class="btn btn-primary  btn-sm btn-edit" data-bs-toggle="modal" onclick="editrecord('+final+')" style="margin-right:10px;" data-bs-target="#showModal2" ><i class="ri-pencil-ruler-2-line"></i></button>' +
                                       '<button class="btn btn-danger btn-sm btn-delete"  onclick="deleterecord(' + row.id + ')" ><i class="ri-delete-bin-2-line"></i></button>';
                            }
                        },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                if(row.partner_customer_type==1)
                                {
                                var new_type="Partner";    
                                }
                                else
                                {
                                var new_type="Customer";    
                                }
                                return new_type;
                            }
                        },
                        { "data": "partner_customer_id" },
                        { "data": "first_name" },
                        { "data": "last_name" },
                        { "data": "company_name" },
                        { "data": "email_address" },
                        { "data": "phone_number" },
                        { "data": "discount" },
                        { "data": "value_add_method" },
                        { "data": "wastage" },
                        { "data": "tax_id" },
                        { "data": "gst_number" },
                        { "data": "pan_number" },
                        { "data": "partner_customer_reference" },
                        { "data": "price_type" },
                        { "data": "level_of_access" },
                        { "data": "notes" },
                        { "data": "added_date" },
                        
                    ],
                    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                    "pageLength": 10,
                    "order": [[19, "desc"]]

                });

                // Search event handler
                $('#search-input').on('keyup', function() {
                    dataTable.search(this.value).draw();
                });
        }
        $(document).ready(function() {
        initializeDataTable();
        });
        function editrecord(id,name,category_id)
        {   
           alert(id); 
//           alert(name); 
//           alert(category_id); 
           $.ajax({
                url: "<?php echo base_url('partners/get_partners_details'); ?>",
                type: "POST",
                data: {
                    id: id,
                    name: name,
                    category_id: category_id
                },
                success: function(response) {
                    var result = JSON.parse(response);
                    $("#edit_partner_type").val(result.partner_customer_type);
                    $("#edit_partner_id").val(result.partner_customer_id);
                    $("#edit_first_name").val(result.first_name);
                    $("#edit_last_name").val(result.last_name);
                    $("#edit_company_name").val(result.company_name);
                    $("#edit_email_address").val(result.email_address);
                    $("#edit_phone_number").val(result.phone_number);
                    $("#edit_discount").val(result.discount);
                    
                    $("#edit_billing_address_line1").val(result.billing_address_line1);
                    $("#edit_billing_address_line2").val(result.billing_address_line2);
                    $("#edit_billing_city").val(result.billing_city);
                    $("#edit_billing_state").val(result.billing_state);
                    $("#edit_billing_country").val(result.billing_country);
                    $("#edit_billing_zip").val(result.billing_zip);
                    
                    
                    $("#edit_shipping_address_line1").val(result.shipping_address_line1);
                    $("#edit_shipping_address_line2").val(result.shipping_address_line2);
                    $("#edit_shipping_city").val(result.shipping_city);
                    $("#edit_shipping_state").val(result.shipping_state);
                    $("#edit_shipping_country").val(result.shipping_country);
                    $("#edit_shipping_zip").val(result.shipping_zip);
                    $("#edit_value_add_method").val(result.value_add_method);
                    
                    $("#edit_wastage").val(result.wastage);
                    $("#edit_tax_id").val(result.tax_id);
                    $("#edit_gst_number").val(result.gst_number);
                    $("#edit_pan_number").val(result.pan_number);
                    $("#edit_partner_reference").val(result.partner_customer_reference);
                    $("#edit_price_type").val(result.price_type);
                    $("#edit_level_of_access").val(result.level_of_access);
                    $("#edit_notes").val(result.notes); 
                }
            });
           
           
           
           
            
           $("#sub_category_id").val(id);
           $('#edit_category_name').val(category_id); 
           $("#edit_sub_category_name").val(name);
        }
        function select_partner_type()
        {   
           var type=$("#partner_type").val();
           $.ajax({
                url: "<?php echo base_url('partners/select_typewise_id'); ?>",
                type: "POST",
                data: {
                    type: type
                },
                success: function(response) {
                    if(type==1)
                    {    
                    var response="Vend"+response;
                    }
                    if(type==2)
                    {    
                    var response="Cust"+response;
                    }
//                    alert(response);
                    $('#partner_id').val(response);  
                }
            });
           
           
           
           
        }
        
        function deleterecord(id)
        {
                var type=$("#type").val();
                $.ajax({
                url: "<?php echo base_url('partners/delete_partners'); ?>",
                type: "POST",
                data: {
                    id: id,
                    type: type,
                },
                success: function(response) {
                    var result = JSON.parse(response);
    //                alert(result.message);
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: result.message
                        });
                        var dataTable = $('#ajax_datatables2').DataTable();
                        dataTable.draw();
                    
                }
            });

        }
        
        $("#add-category-form").on('submit', (function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url('partners/add_partner'); ?>",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function ()
                        {
                           
                        }, 
                        success: function (data)
                        {
                                $('#add-category-form')[0].reset();
                                $('#showModal').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
//                                alert(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: "Data added successfully!"
                                });
                        },    
                        error: function (e)
                        {
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: "Something went wrong, please try again!!"
                                });
                        }
                    });
        }));
        
        $("#edit-category-form").on('submit', (function (e) {
                    e.preventDefault();
                    $.ajax({
                        url: "<?php echo base_url('partners/edit_partner'); ?>",
                        type: "POST",
                        data: new FormData(this),
                        contentType: false,
                        cache: false,
                        processData: false,
                        beforeSend: function ()
                        {
                           
                        }, 
                        success: function (data)
                        {
                                alert(data);
                                $('#edit-category-form')[0].reset();
                                $('#showModal2').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
//                                alert(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: "Data updated successfully!"
                                });
                        },    
                        error: function (e)
                        {
                            Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: "Something went wrong, please try again!!"
                                });
                        }
                    });
        }));
        
        </script>
        <input type="text" name="type" id="type" value="<?php echo $type; ?>" />
        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script src="<?php echo base_url().'theme/';?>assets/js/pages/select2.init.js"></script>



        <!-- App js -->
        <script src="<?php echo base_url().'theme/';?>assets/js/app.js"></script>
    
    
    
    
    
    
    
</body>
</html>