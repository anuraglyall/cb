
<!doctype html>
<html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light" data-sidebar-size="lg" data-sidebar-image="none" data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title><?php echo $title; ?></title>
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
                                                <?php 
//                                                echo '<pre>';
//                                                print_r($perameters);
//                                                echo '</pre>';
                                                
                                                
                                                foreach($perameters as $perameters_res)
                                                {
                                                ?>
                                                <div class="mb-3 col-md-12 pull-left" style="<?php if($perameters_res[1]=='hidden') { echo 'display:none;'; } ?>float:left;padding:0px 5px;">
                                                    <label for="<?php echo $perameters_res[0];  ?>" class="form-label"><?php echo $perameters_res[2];  ?></label>
                                                    <?php 
                                                    if($perameters_res[1]=='text' || $perameters_res[1]=='hidden')
                                                    {    
                                                    ?>
                                                    <input value="<?php echo $perameters_res[4];  ?>"  type="<?php echo $perameters_res[1];  ?>" id="<?php echo $perameters_res[0];  ?>" name="<?php echo $perameters_res[0];  ?>" class="form-control" <?php echo $perameters_res[3];  ?> <?php if($perameters_res[5]!='') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  <?php } ?> />
                                                    <?php 
                                                    }    
                                                    else if($perameters_res[1]=='select')
                                                    {
//                                                    echo '<pre>';    
//                                                    print_r($perameters_res);    
//                                                    echo '</pre>';    
                                                    ?>
                                                    <select class="form-control" <?php if($perameters_res[5]!='') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  <?php } ?>                  <?php echo $perameters_res[3];  ?>   id="<?php echo $perameters_res[0];  ?>" name="<?php echo $perameters_res[0];  ?>">
                                                        <option value="">--Select <?php echo $perameters_res[2];  ?>--</option>
                                                        <?php 
                                                        foreach($perameters_res[4] as $select_data)
                                                        {
                                                        ?>
                                                        <option value="<?php echo $select_data['id'];  ?>"><?php echo $select_data['name'];  ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php if($perameters_res[6]=='set_price_exchange_rate') { ?>
                                                    <div style="display:none;">
                                                        <table>
                                                            <?php 
                                                            foreach($perameters_res[4] as $select_data)
                                                            {
                                                            ?>
                                                            <tr >
                                                                <td>
                                                                 <?php echo $select_data['id']; ?>   
                                                                </td>
                                                                <td>
                                                                 <?php echo $select_data['name']; ?>   
                                                                </td>
                                                                <td id="currency_exchange_rate_<?php echo $select_data['id']; ?>"><?php echo $select_data['currency_exchange_rate']; ?></td>
                                                            </tr>
                                                            <?php 
                                                            }
                                                            ?> 
                                                        </table>
                                                    </div>
                                                    <script>
                                                    function <?php echo $perameters_res[6]; ?>(value)
                                                    {
                                                    var myArray = value.split("/");
//                                                    alert(myArray);
                                                    var id=$("#"+myArray[0]).val();
                                                    var data=$("#currency_exchange_rate_"+id).html();
//                                                    alert(data);
                                                    $("#"+myArray[1]).val(data);
                                                    
                                                    var data1=$("#"+myArray[2]).val();
                                                    var data2=$("#"+myArray[3]).val();
                                                    var data3=$("#"+myArray[4]).val();
                                                    
                                                    var final=(1/data)*data1*data2;
                                                    $("#"+myArray[4]).val(final);
//                                                    alert(data1);
//                                                    alert(data2);
//                                                    alert(final);
                                                    
                                                    }
                                                    </script>
                                                    
                                                    
                                                    
                                                    <?php } ?>
                                                    
                                                    
                                                    
                                                    <?php 
                                                    }    
                                                    ?>
                                                    
                                                </div>
                                                <?php 
                                                }
                                                ?>
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
                                                <?php 
//                                                echo '<pre>';
//                                                print_r($perameters);
//                                                echo '</pre>';
                                                
                                                
                                                foreach($perameters as $perameters_res)
                                                {
                                                ?>
                                                <div class="mb-3 col-md-12 pull-left" style="<?php if($perameters_res[1]=='hidden') { echo 'display:none;'; } ?>float:left;padding:0px 5px;">
                                                    <label for="<?php echo $perameters_res[0];  ?>" class="form-label"><?php echo $perameters_res[2];  ?></label>
                                                    <?php 
                                                    if($perameters_res[1]=='text' || $perameters_res[1]=='hidden')
                                                    {    
                                                    ?>
                                                    <input value="<?php echo $perameters_res[4];  ?>" type="<?php echo $perameters_res[1];  ?>" id="edit_<?php echo $perameters_res[0];  ?>" name="<?php echo $perameters_res[0];  ?>" class="form-control" <?php echo $perameters_res[3];  ?> />
                                                    <?php 
                                                    }    
                                                    else if($perameters_res[1]=='select')
                                                    {    
                                                    ?>
                                                    <select class="form-control" <?php echo $perameters_res[3];  ?>   id="edit_<?php echo $perameters_res[0];  ?>" name="<?php echo $perameters_res[0];  ?>">
                                                        <option value="">--Select <?php echo $perameters_res[2];  ?>--</option>
                                                        <?php 
                                                        foreach($perameters_res[4] as $select_data)
                                                        {
                                                        ?>
                                                        <option value="<?php echo $select_data['id'];  ?>"><?php echo $select_data['name'];  ?></option>
                                                        <?php
                                                        }
                                                        ?>
                                                    </select>
                                                    <?php 
                                                    }    
                                                    ?>
                                                    
                                                </div>
                                                <?php 
                                                }
                                                ?>
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
                                                <?php 
                                                foreach($display as $display_res)
                                                {
                                                ?>
                                                <th><?php echo $display_res; ?></th>
                                                <?php 
                                                }
                                                ?>
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
        $(document).ready(function(){
        
        var dataTable = $('#ajax_datatables2').DataTable({
                    "processing": true,
                    "serverSide": true,
                    "ajax": {
                        "url": "<?php echo base_url($master.'/fetch_data'); ?>",
                        "data": {
                            "type": "<?php echo $type; ?>",
                            "table": "<?php echo $table; ?>"
                        },
                        "type": "POST"
                    },
                    "columns": [
                        <?php 
                        foreach($display2 as $display_res)
                        {
                        if($display_res!='action')
                        {    
                        ?>
                        { "data": "<?php echo $display_res; ?>" },
                        <?php
                        }
                        }
                        ?>
                        {
                            "data": null,
                            "render": function(data, type, row) {
                                var final="'";
                                <?php
                                foreach($edits as $edits_res)
                                {
                                if($edits_res!='')
                                {    
                                ?>
                                var final=final+"<?php echo $edits_res; ?>///"+row.<?php echo $edits_res; ?>+"___";
                                <?php
                                }    
                                }    
                                ?>
                                var final=final+"'";
                                return '<button class="btn btn-primary  btn-sm btn-edit" data-bs-toggle="modal" onclick="editrecord('+final+')" style="margin-right:10px;" data-bs-target="#showModal2" ><i class="ri-pencil-ruler-2-line"></i></button>' +
                                       '<button class="btn btn-danger btn-sm btn-delete"  onclick="deleterecord(' + row.id + ')" ><i class="ri-delete-bin-2-line"></i></button>';
                            }
                        },
                        
                    ],
                    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                    "pageLength": 10,
                    'dom': 'Bfrtip',
                    'buttons': [
                      'copy', 'csv', 'excel',  'print'
                    ]
                    

                });
                
                
        $('#but_showhide').click(function(){
                    var checked_arr = [];var unchecked_arr = [];

                    // Read all checked checkboxes
                    $.each($('input[type="checkbox"]:checked'), function (key, value) {
                       checked_arr.push(this.value);
                    });

                    // Read all unchecked checkboxes
                    $.each($('input[type="checkbox"]:not(:checked)'), function (key, value) {
                       unchecked_arr.push(this.value);
                    });

                    // Hide the checked columns
                    empDataTable.columns(checked_arr).visible(false);

                    // Show the unchecked columns
                    empDataTable.columns(unchecked_arr).visible(true);
        });

                // Search event handler
        $('#search-input').on('keyup', function() {
                    dataTable.search(this.value).draw();
        });
        
//        initializeDataTable();
        });
        function editrecord(datas)
        {   
//           alert(datas); 
           var result = datas.split('___');
            for (var i = 0; i < result.length; i++)
            {
                var result2 = result[i].split('///');
//                alert(result2);
                $("#edit_"+result2[0]).val(result2[1]);
            }
        }  
        function deleterecord(id)
        {       
                $.ajax({
                url: "<?php echo base_url($master.'/delete_data'); ?>",
                type: "POST",
                data: {
                    "id": id,
                    "table": "<?php echo $table; ?>",
                    "heading": $("#heading").val()
                },
                success: function(response) {
                    var result = JSON.parse(response);
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
                        url: "<?php echo base_url($master.'/add_data'); ?>",
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
                                var category = JSON.parse(data); 
                                if(category.success==true)
                                {    
                                $('#add-category-form')[0].reset();
                                $('#showModal').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
//                                alert(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: category.message
                                });
                                }
                                if(category.success==false)
                                { 
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: category.message
                                });
                                }
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
                        url: "<?php echo base_url($master.'/edit_data'); ?>",
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
                                var category = JSON.parse(data); 
                                if(category.success==true)
                                {    
                                $('#edit-category-form')[0].reset();
                                $('#showModal2').modal('hide');
                                var dataTable = $('#ajax_datatables2').DataTable();
                                dataTable.draw();
//                                alert(data);
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Success',
                                    text: category.message
                                });
                                }
                                if(category.success==false)
                                {    
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: category.message
                                });
                                }
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