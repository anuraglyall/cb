
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
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Add Sub Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                        </div>  
                                        <form class="tablelist-form" method="post" id="add-category-form"  autocomplete="off">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="category_name" class="form-label">Category Name</label>
                                                    <select class="form-control" required id="category_name" name='category_name' data-choices >
                                                            <option value="" selected>--Please Select Category---</option>
                                                            <?php
                                                            foreach($all_category as $all_category_res)
                                                            {    
                                                            ?>
                                                            <option value="<?php echo $all_category_res['id'] ?>"><?php echo $all_category_res['category_name'] ?></option>
                                                            <?php 
                                                            }
                                                            ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a sub category name.</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="sub_category_name" class="form-label">Sub Category Name</label>
                                                    <input type="text" id="sub_category_name" name="sub_category_name" class="form-control" placeholder="Enter Name" required />
                                                    <div class="invalid-feedback">Please enter a sub category name.</div>
                                                </div>
                                                
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Add Category</button>
                                                    <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                </div>
                                <div class="modal fade" id="showModal2" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content">
                                        <div class="modal-header bg-light p-3">
                                            <h5 class="modal-title" id="exampleModalLabel">Edit Category</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                        </div>
                                        <form class="tablelist-form" method="post" id="edit-category-form"  autocomplete="off">
                                            <div class="modal-body">
                                                <div class="mb-3">
                                                    <label for="category_name" class="form-label">Category Name</label>
                                                    <select class="form-control" required id="edit_category_name" name='edit_category_name'  data-choices name="edit_category_name" >
                                                            <option value="" selected>--Please Select Category---</option>
                                                            <?php
                                                            foreach($all_category as $all_category_res)
                                                            {    
                                                            ?>
                                                            <option value="<?php echo $all_category_res['id'] ?>"><?php echo $all_category_res['category_name'] ?></option>
                                                            <?php 
                                                            }
                                                            ?>
                                                    </select>
                                                    <div class="invalid-feedback">Please enter a sub category name.</div>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="edit_category_name" class="form-label">Category Name</label>
                                                    <input type="hidden" id="sub_category_id" name="sub_category_id" class="form-control" placeholder="Enter Name" required />
                                                    <input type="text" id="edit_sub_category_name" name="edit_sub_category_name" class="form-control" placeholder="Enter Name" required />
                                                    <div class="invalid-feedback">Please enter a customer name.</div>
                                                </div>

                                                
                                            </div>
                                            <div class="modal-footer">
                                                <div class="hstack gap-2 justify-content-end">
                                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                                    <button type="submit" class="btn btn-success" id="add-btn">Update Category</button>
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
                                                <th>Sub Category</th>
                                                <th>Category</th>
                                                <th>Added Date</th>
                                                <th>Updated Date</th>
                                                <th>Action</th>
                                                
                                                
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
                        "url": "<?php echo base_url('subcategory/fetch_data'); ?>",
                        "type": "POST"
                    },
                    "columns": [
                        { "data": "id" },
                        { "data": "subcategory_name" },
                        { "data": "category_name" },
                        { "data": "added_date" },
                        { "data": "updated_date" },
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                var final="'"+row.id+"'"+","+"'"+row.subcategory_name+"'"+","+"'"+row.category_id+"'";

                                return '<button class="btn btn-primary  btn-sm btn-edit" data-bs-toggle="modal" onclick="editrecord('+final+')" style="margin-right:10px;" data-bs-target="#showModal2" ><i class="ri-pencil-ruler-2-line"></i></button>' +
                                       '<button class="btn btn-danger btn-sm btn-delete"  onclick="deleterecord(' + row.id + ')" ><i class="ri-delete-bin-2-line"></i></button>';
                            }
                        }

                    ],
                    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                    "pageLength": 10,

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
//           alert(id); 
//           alert(name); 
//           alert(category_id);  
//            
//           exit; 
           $("#sub_category_id").val(id);
           $('#edit_category_name').val(category_id); 
           $("#edit_sub_category_name").val(name);
        }
        function deleterecord(id)
        {

                $.ajax({
                url: "<?php echo base_url('subcategory/delete_category'); ?>",
                type: "POST",
                data: {
                    id: id
                },
                success: function(response) {
                    var result = JSON.parse(response);
    //                alert(result.message);
                    if (result.message === 'Sub category deleted successfully') {
                        // Show a success message or perform any other actions
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: result.message
                        });
                        var dataTable = $('#ajax_datatables2').DataTable();
                        dataTable.draw();
                    } else {
                        // Show an error message or handle the error case
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: result.message
                        });
                    }
                }
            });

        }

        $('#add-category-form').on('submit', function(e) {
                    e.preventDefault();
                    // Get the category name from the form
                    var category_name=$('#category_name').val();
                    var sub_category_name=$('#sub_category_name').val();

                    // AJAX request to add the category
                    $.ajax({
                        url: "<?php echo base_url('subcategory/add_subcategory'); ?>",
                        type: "POST",
                        data: {
                            category_name: category_name,
                            sub_category_name: sub_category_name
                        },
                        success: function(response) {
                            var category = JSON.parse(response);    
//                            alert(category);
                            if(category.message=='Sub Category added successfully')
                            {
                                $('#add-category-form')[0].reset();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: category.message
                                });
                                $('#add-category-form')[0].reset();
                                $('#showModal').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
                            }
                            else
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: category.message
                                });
                            }
                        }
                    });

        });
        $('#edit-category-form').on('submit', function(e) {
                    e.preventDefault();

                    // Get the category name from the form
                    var edit_category_name = $('#edit_category_name').val();
                    var edit_sub_category_name= $('#edit_sub_category_name').val();
                    var sub_category_id = $('#sub_category_id').val();
                    
                    // AJAX request to add the category
                    $.ajax({
                        url: "<?php echo base_url('subcategory/edit_subcategory'); ?>",
                        type: "POST",
                        data: {
                            edit_category_name: edit_category_name,
                            sub_category_id: sub_category_id,
                            edit_sub_category_name: edit_sub_category_name
                        },
                        success: function(response) {

    //                        alert(response);  
    //                        exit;
                            var category = JSON.parse(response);                        
                            if(category.message=='Category edited successfully')
                            {
                                $('#add-category-form')[0].reset();
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: category.message
                                });
                                $('#edit-category-form')[0].reset();
                                $('#showModal2').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
                            }
                            else
                            {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: category.message
                                });
                            }
                        }
                    });

        });
        </script>

        
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

        <script src="<?php echo base_url().'theme/';?>assets/js/pages/select2.init.js"></script>



        <!-- App js -->
        <script src="<?php echo base_url().'theme/';?>assets/js/app.js"></script>
    
    
    
    
    
    
    
</body>
</html>