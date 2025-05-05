    <!doctype html>
    <html lang="en" data-layout="semibox" data-sidebar-visibility="show" data-topbar="light" data-sidebar="light" data-sidebar-size="sm" data-sidebar-image="none" data-preloader="disable">

        <head>
            <meta charset="utf-8" />
            <title><?php echo $title; ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
            <meta content="Themesbrand" name="author" />
            <link href="<?php echo base_url(); ?>theme/assets/libs/sweetalert2/sweetalert2.min.css" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css">
            <?php
            $this->load->view('templates/headercss');
            ?>
            <style>
                .is-hidden
                {
                display:none;    
                }
                .navbar-menu .navbar-nav .nav-sm .nav-link.active {
                    color: white;
                    background-color: rgb(104,124,254);
                    opacity: 0.7;
                    font-weight: bold;
                    font-size: 14px;
                    padding: 0px;
                }
                .navbar-menu .navbar-nav .nav-sm .nav-link:before
                {
                    background-color: rgb(104,124,254) !important;
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
                    margin:10px 0px !important;
                    clear:both;
                }
                .modal-content {
                    height: 100%;
                    border-radius: 0;
                }

                .modal-body {
                    height: calc(100% - 56px); /* Subtract header and footer height */
                    overflow-y: auto;
                }
                .column-toggle-container label
                {
                    padding: 0px 10px;
                    font-size:12px;
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
                    font-size: 12px;
                    font-weight: 400;
                }
                .selectize-input {
                padding: 5px 5px !important;    
                }
                .card {
                margin-bottom: 0px;
                }
                .form-label
                {
                    font-size:13px;
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
                    width: 200px;
                }
                ::placeholder {
                    font-size: 12px; /* Change this value to set the font size of the placeholder text */
                    font-weight:400;
                    padding:10px;
                }
                .selectize-dropdown-content {
                    border: 1px groove grey !important;
                    background-color: white!important;
                    padding: 10px 5px !important;
                }
                .form-control {
                    /*padding: 0px 0px !important;*/
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
                    font-size: 15px;
                }
                
                .navbar-menu .navbar-nav .nav-link
                {
                 padding: 10px 15px;
                 font-size: 14px;   
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
                    min-height:70px;
                    height:auto;
                }    
            <?php
            }
            ?>
             </style>    
        </head>
        <body>
            <div id="layout-wrapper">  
            <div class="main-content">
                <div style="padding:0px;" class="page-content">
                        <div class="container-fluid">
                            <div class="row">  
                                <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0"><?php
//                                        echo $maintitle2;
                                            if ($maintitle2 != '') {
                                                echo $maintitle2;
                                            } else {
                                                echo $title;
                                            }
                                            ?></h4>

                                        <div class="page-title-right">
                                            <ol class="breadcrumb m-0">
                                                <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                                                <li class="breadcrumb-item active"><?php echo $title2; ?></li>
                                            </ol>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="card">
                            <div class="card-body">
                                <form method="get">
                                <div class="row">
                                <div class="col-sm-4">
                                    <div class="search-box">
                                        <input type="text" value="<?php echo $_GET['orderno']; ?>" name="orderno" id="orderno" style="padding:15px;padding-left:30px;" 
                                               class="form-control" placeholder="Search your Order No / PO No etc..">
                                        <i class="ri-search-line search-icon"></i>
                                    </div>
                                </div>
                                <!--end col-->
                                <div class="col-sm-auto ms-auto">
                                    <div class="list-grid-nav hstack gap-1">
                                        <button class="btn btn-secondary addMembers-modal" data-bs-toggle="modal" data-bs-target="#addmemberModal"><i class="ri-add-fill me-1 align-bottom"></i> Search</button>
                                    </div>
                                </div>
                                </div>
                                </form>    
                            </div>
                            </div>
                            <?php 
                            if($_GET['orderno']!='')
                            {    
                            $orderno=$_GET['orderno'];    
//                            $orderno_exp=explode("-",$orderno);
//                            $style=$orderno_exp[0];
//                            $sr=$orderno_exp[1];
                            
//                            echo $orders=("select o.*,s.style_id"
//                                    . ",(select i.img from styles_images i where i.style_id=s.style_id limit 1 ) as img_link "
//                                    . "from  orders o "
//                                    . "LEFT JOIN styles s ON s.id=o.styles "
//                                    . "LEFT JOIN orders_tracking t ON t.id=o.id "
//                                    . "where 1 " 
//                                    . "and (t.styleno='$orderno')"
//                                    . "");      
                            $orders=$this->db->query("select o.*,s.style_id"
                                    . ",(select i.img from styles_images i where i.style_id=s.style_id limit 1 ) as img_link "
                                    . "from  orders o "
                                    . "LEFT JOIN styles s ON s.id=o.styles "
                                    . "LEFT JOIN orders_tracking t ON t.id=o.id "
                                    . "where 1 " 
                                    . "and (t.styleno='$orderno' OR t.po_no='$orderno')"
                                    . "")->result_array();       
                            
//                            echo '<pre>';
//                            print_r($orders);
//                            echo '</pre>';
                            ?>
                            <div class="card" style="margin-top:10px;">
                                                    <div class="card-body">
                                                        <h5 class="card-title mb-3">Order History</h5>
                                                        <div class="table-responsive">
                                                            <table class="table table-borderless mb-0">
                                                                <tbody>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Style Id :</th>
                                                                        <td class="text-muted">
                                                                        <?php
                                                                        echo $orders[0]['style_id'];
                                                                        ?>
                                                                        </td>
                                                                        <th class="ps-0" scope="row">Style Serial No :</th>
                                                                        <td class="text-muted">
                                                                            <?php
                                                                            echo $orders[0]['style_serial_no'];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Image :</th>
                                                                        <td class="text-muted">
                                                                            <img src="<?php
                                                                            echo $orders[0]['img_link'];
                                                                            ?>" width="250px" />
                                                                        </td>  
                                                                        <th class="ps-0" scope="row">Po No :</th>
                                                                        <td class="text-muted">
                                                                            <?php
                                                                            echo $orders[0]['po_no'];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" colspan="4" scope="row">
                                                                        <hr/>    
                                                                        </th>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Added Date :</th>
                                                                        <td class="text-muted">
                                                                            <?php
                                                                            echo $orders[0]['added_date'];
                                                                            ?>
                                                                        </td>  
                                                                        <th class="ps-0" scope="row">Target Delivery Date :</th>
                                                                        <td class="text-muted">
                                                                            <?php
                                                                            echo $orders[0]['target_delivery_date'];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                    <tr>
                                                                        <th class="ps-0" scope="row">Current Status :</th>
                                                                        <td class="text-muted">
                                                                            <?php
                                                                            echo $orders[0]['final_status'];
                                                                            ?>
                                                                        </td>  
                                                                        <th class="ps-0" scope="row"></th>
                                                                        <td class="text-muted">
                                                                            <?php
//                                                                            echo $orders[0]['target_delivery_date'];
                                                                            ?>
                                                                        </td>
                                                                    </tr>
                                                                </tbody>
                                                            </table>
                                                        </div>
                                                    </div><!-- end card body -->
                            </div>    
                            <?php 
                            }    
                            ?>
                        </div>
                    </div>
                    <?php
                    $this->load->view('templates/footer');
                    ?>
                </div>
            </div>
            <?php
            $this->load->view('templates/footerjs');
            ?>
            <script src="<?php echo base_url(); ?>theme/assets/libs/prismjs/prism.js"></script>
            <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
            <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
            <script src="https://cdn.datatables.net/responsive/2.2.9/js/dataTables.responsive.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/dataTables.buttons.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.print.min.js"></script>
            <script src="https://cdn.datatables.net/buttons/2.2.2/js/buttons.html5.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
            <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
            <script src="<?php echo base_url() . 'theme/'; ?>assets/libs/sweetalert2/sweetalert2.min.js"></script>
                <link
                rel="stylesheet"
                href="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/css/selectize.default.min.css"
                integrity="sha512-pTaEn+6gF1IeWv3W1+7X7eM60TFu/agjgoHmYhAfLEU8Phuf6JKiiE8YmsNC0aCgQv4192s4Vai8YZ6VNM6vyQ=="
                crossorigin="anonymous"
                referrerpolicy="no-referrer"
                />
            <script
                src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.15.2/js/selectize.min.js"
                integrity="sha512-IOebNkvA/HZjMM7MxL0NYeLYEalloZ8ckak+NDtOViP7oiYzG5vn6WVXyrJDiJPhl4yRdmNAG49iuLmhkUdVsQ=="
                crossorigin="anonymous"
                referrerpolicy="no-referrer"
            ></script>  
             <script>
                                                                    var selectizeInstance;
                                                                    $(document).ready(function () {
                                                                    $('.my-select4').each(function () {
                                                                    var selectElement = $(this);
//                                                                    console.log(selectElement);
                                                                    var selectId = selectElement.data('select-id');
//                                                                    console.log(selectId);
                                                                    
                                                                    
                                                                    var selectizedata = {
                                                                        load: function (query, callback) {
                                                                            if (!query.length)
                                                                                return callback();
                                                                            $.ajax({
                                                                                url: '<?php echo base_url(); ?>extra/loadpagewisesearch',
                                                                                dataType: 'json', 
                                                                                data: {
                                                                                    search_datas: '1',
                                                                                    search: query,
                                                                                    selectId: selectId
                                                                                },
                                                                                success: function (data) {
                                                                                    callback(data.map(function (item) {
                                                                                        return {
                                                                                            value: item.value,
                                                                                            text: item.text
                                                                                        };
                                                                                    }));
                                                                                }
                                                                            });
                                                                        },
                                                                        positionDropdown: 'below'
                                                                    };

                                                                    $(this).selectize(selectizedata);
                                                                });
                                                            });
                                                            function add_form()
                                                            {
                                                               $("#id").val(""); 
                                                            }
                                                            function loadorderdetails(type,value,id,handle)
                                                            {
//                                                                alert(type+" - "+value+" - "+id+" - "+handle);
                                                                if (typeof id === 'undefined' || id=='' || id=='undefined')
                                                                {    
                                                                var id=$("#id").val();
                                                                }
                                                                var handle=$("#url_handle").val();
                                                                
                                                                
                                                                $.ajax({
                                                                url:"<?php echo base_url(); ?>extra/loadorderdetails",
                                                                method:"POST",
                                                                data:{handle:handle,type:type,value:value,id:id},
                                                                success:function(data)
                                                                {
//                                                                        alert(data);
                                                                        var data_exp= data.split('||||');
                                                                       
                                                                        if(handle=='orders') 
                                                                        {    
//                                                                        var response=JSON.parse(data_exp[0]); 
                                                                        var response=JSON.parse(data_exp[1]); 
//                                                                        alert(response);
                                                                        
                                                                        var selectizeInstance = $('.my-select4[data-select-id="category"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            var existingOption = selectizeInstance.options[response.text];
                                                                            if(!existingOption) 
                                                                            {
                                                                                selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                            }
                                                                            selectizeInstance.setValue(response.value);
                                                                        }
                                                                        
                                                                        var response=JSON.parse(data_exp[2]); 
                                                                        var selectizeInstance = $('.my-select4[data-select-id="subcategory"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            var existingOption = selectizeInstance.options[response.text];
                                                                            if(!existingOption) 
                                                                            {
                                                                                selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                            }
                                                                            selectizeInstance.setValue(response.value);
                                                                        }  
                                                                        
                                                                        var response=JSON.parse(data_exp[3]); 
//                                                                        alert(response.text);
//                                                                        alert(response.value);
                                                                        var selectizeInstance = $('.my-select4[data-select-id="metal"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            var existingOption = selectizeInstance.options[response.text];
                                                                            if(!existingOption) 
                                                                            {
                                                                                selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                            }
                                                                            selectizeInstance.setValue(response.value);
                                                                        }    
                                                                        
                                                                        var response=JSON.parse(data_exp[4]); 
                                                                        var selectizeInstance = $('.my-select4[data-select-id="metal_finish"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            var existingOption = selectizeInstance.options[response.text];
                                                                            if(!existingOption) 
                                                                            {
                                                                                selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                            }
                                                                            selectizeInstance.setValue(response.value);
                                                                        }  
                                                                        var response=JSON.parse(data_exp[5]); 
                                                                        
                                                                        var selectizeInstance = $('.my-select4[data-select-id="vendor"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            var existingOption = selectizeInstance.options[response.text];
                                                                            if(!existingOption) 
                                                                            {
                                                                                selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                            }
                                                                            selectizeInstance.setValue(response.value);
                                                                        }  
                                                                        var response=JSON.parse(data_exp[0]); 
                                                                        $("#target_cost").val(response.target_cost);
                                                                        if(response['diamonds']!='')
                                                                        {
                                                                                load_record_data("diamonds",response['diamonds']);
                                                                        }
                                                                        if(response['gemstones']!='')
                                                                        {
                                                                                load_record_data("gemstones",response['gemstones']);
                                                                        }
                                                                        
                                                                        if(response['pearls']!='')
                                                                        {
                                                                                load_record_data("pearls",response['pearls']);
                                                                        }
                                                                        if(response['dimensions']!='')
                                                                        {
                                                                                load_record_data("dimensions",response['dimensions']);
                                                                        }
                                                                        }
//                                                                        alert(data_exp[10]);  
//                                                                        alert(data_exp[11]);  
                                                                        $(".upload__img-wrap").html(data_exp[10]);
                                                                        if(handle=='add-new-style')
                                                                        { 
                                                                        $("#comment_history_box").html(data_exp[11]);
                                                                        }
                                                                        $("#upload_file0").css("display","block");
                                                                }
                                                                });
                                                            }            
                                                            function load_record_data(type,value,default_id)
                                                            { 
                                                                var id=$("#id").val();
                                                                if(type=='edit_users')
                                                                {
                                                                var url="<?php echo base_url(); ?>extra/userpageaccess";    
                                                                }
                                                                else
                                                                {
                                                                var url="<?php echo base_url(); ?>extra/loadpagewisesearch";    
                                                                }  
                                                                $.ajax({
                                                                url:url,
                                                                method:"GET",
                                                                data:{default_id:default_id,id:id,selectId:type,value:value},
                                                                success:function(data)
                                                                {
//                                                                        alert(data);
                                                                        if(type=='edit_users')
                                                                        {
                                                                        $("#load_extra_data").html(data);
                                                                        }
                                                                        else
                                                                        {    
                                                                        var responses=JSON.parse(data); 
                                                                        var selectizeInstance = $('.my-select4[data-select-id="' + type + '"]').get(0).selectize;
                                                                        if (selectizeInstance) {
                                                                            for (var i = 0; i < responses.length; i++) {
                                                                                var response = responses[i];

                                                                                var existingOption = selectizeInstance.options[response.text];
                                                                                if (!existingOption) {
                                                                                    selectizeInstance.addOption({ value: response.value, text: response.text });
                                                                                }

                                                                                selectizeInstance.addItem(response.value);
                                                                            }
                                                                        }
                                                                        if(type=='type')
                                                                        {
//                                                                            alert(type);
                                                                            $(".company_display").css("display","none !important");
                                                                        }    
                                                                        }    
                                                                }
                                                                });
//                                                                });
                                                            }
                                                            function editrecord(datas)
                                                            {
                                                                
                                                                $('#add-category-form').attr('id', 'edit-category-form');
                                                                var edit_extra_function_id = $('#edit_extra_function_id').val();
                                                                var handle = $('#url_handle').val();
                                                                var result = datas.split('___');
                                                                var finals="";
                                                                for (var i = 0; i < result.length; i++)
                                                                {
                                                                    $("#add-btn").css("display", "none");
                                                                    $("#update-btn").css("display", "block");
                                                                    var result2 = result[i].split('///');
//                                                                    alert(result2[0]+'--'+result2[1]); 
                                                                    if(result2[0] == 'images' && handle=='orders')
                                                                    {
                                                                       loadorderdetails(result2[0],result2[1]); 
                                                                    }    
                                                                    else if(result2[0] == 'images' && handle=='add-new-style')
                                                                    {
                                                                       loadorderdetails(result2[0],result2[1]); 
                                                                    }    
                                                                    else if(result2[0] == 'vendor_id' 
                                                                            || result2[0] == 'vendor'
                                                                            || result2[0] == 'category_id'
                                                                            || result2[0] == 'category'
                                                                            || result2[0] == 'subcategory'
                                                                            || result2[0] == 'metal'
                                                                            || result2[0] == 'metal_finish'
                                                                            || result2[0] == 'prices'
                                                                            || result2[0] == 'purity'
                                                                            || result2[0] == 'pearl_type'
                                                                            || result2[0] == 'pearl_shape'
                                                                            || result2[0] == 'pearl_color'
                                                                            || result2[0] == 'pearl_size'
                                                                            || result2[0] == 'pearl_unit'
                                                                            || result2[0] == 'currency'
                                                                            || result2[0] == 'diamonds'
                                                                            || result2[0] == 'gemstones'
                                                                            || result2[0] == 'pearls'
                                                                            || result2[0] == 'type'
                                                                            || result2[0] == 'dimensions'
                                                                            || result2[0] == 'diamond_cut'
                                                                            || result2[0] == 'diamond_shape'
                                                                            || result2[0] == 'diamond_color'
                                                                            || result2[0] == 'diamond_clarity'
                                                                            || result2[0] == 'diamond_pointers'
                                                                            || result2[0] == 'diamond_sieve_size'
                                                                            || result2[0] == 'diamond_unit'
                                                                            || result2[0] == 'shipping_country'
                                                                            || result2[0] == 'billing_country'
                                                                            || result2[0] == 'edit_users'
                                                                            || result2[0] == 'styles'
                                                                            )
                                                                    {  
                                                                        load_record_data(result2[0],result2[1]);
                                                                    }
                                                                    else
                                                                    {
                                                                    $("#" + result2[0]).val(result2[1]);    
                                                                    }    
                                                                }
//                                                                alert(finals);
                                                            }
                                                            $(document).ready(function () {
                                                                var dataTable = $('#ajax_datatables2').DataTable({
                                                                    "processing": true,
                                                                    "serverSide": true,
                                                                    "ajax": {
                                                                        "url": "<?php echo base_url($master . '/fetch_data'); ?>",
                                                                        "data": {
                                                                            "type": "<?php echo $type; ?>",
                                                                            "table": "<?php echo $table; ?>",
                                                                            "controller": "<?php echo $master; ?>"
                                                                        },
                                                                        "type": "POST"
                                                                    },
                                                                    "columns": [
                                                                        {
                                                                            "data": null,
                                                                            "render": function (data, type, row) {
                                                                                var final = "'";
                                                                                <?php
                                                                                foreach ($edits as $edits_res) {
                                                                                if ($edits_res != '') {
                                                                                ?>
                                                                                var final = final + "<?php echo $edits_res; ?>///" + row.<?php echo $edits_res; ?> + "___";
                                                                                        <?php
                                                                                    }
                                                                                }
                                                                                ?>  
                                                                                var final = final + "'";
                                                                                editButton='';
                                                                                deleteButton='';
                                                                                order_extra_details ='';
                                                                                order_qc1 ='';
                                                                                order_qc2 ='';
                                                                                complete ='';
                                                                                <?php
                                                                                if($edit==1)
                                                                                {    
                                                                                ?>
                                                                                var editButton = '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" data-bs-toggle="modal" onclick="editrecord(' + final + ');" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen"  ><i class="bx bx-pencil"></i></a>';
                                                                                <?php
                                                                                }
                                                                                if($delete==1)
                                                                                {    
                                                                                ?>
                                                                                var deleteButton = '<a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleterecord(' + row.<?php echo $delete_id; ?> + ');"><i class="ri-delete-bin-5-line"></i></a>';
                                                                                <?php
                                                                                }
                                                                                if($complete==1)
                                                                                {    
                                                                                ?>
                                                                                var completeButton = '<a style="padding:3px;" class="btn btn-success btn-sm btn-success" onclick="completerecord(' + row.<?php echo $delete_id; ?> + ');"><i class="ri-rocket-line"></i>Finish?</a>';
                                                                                <?php
                                                                                }
                                                                                ?>
                                                                                var add_extra_btn = '<?php echo $add_extra_btn; ?>';
                                                                                if (add_extra_btn == '1')
                                                                                {
                                                                                    var deleteButton = deleteButton + "" + '<a style="margin-left:3px;padding:3px;" class="btn btn-success btn-sm waves-effect" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2" onclick="addextrarecord(' + row.<?php echo $delete_id; ?> + ');"><i class="ri-add-line align-bottom me-1"></i>Profile</a>';
                                                                                }
                                                                                <?php
                                                                                if($order_extra_details==1)
                                                                                {    
                                                                                ?>
                                                                                if(row.details_status=='' || row.details_status==null)
                                                                                {
                                                                                var details="Details";    
                                                                                }    
                                                                                else
                                                                                {
                                                                                var details=row.details_status;    
                                                                                }    
                                                                                var order_extra_details= '<a style="padding:3px;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2"  class="btn btn-info btn-sm btn-delete" onclick="order_details_extra(' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-pencil"></i>'+details+'</a>';
                                                                                <?php
                                                                                }    
                                                                                if($order_qc1==1)
                                                                                {    
                                                                                ?>
                                                                                if(row.qc_status=='' || row.qc_status==null)
                                                                                {
                                                                                var details="Qc 1 - Pending";    
                                                                                var details2="btn-soft-secondary";    
                                                                                }    
                                                                                else
                                                                                {
                                                                                var details=row.qc_status;    
                                                                                var details2="btn-soft-success";    
                                                                                }                        
                                                                                var order_qc1= '<a style="padding:3px;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2"  class="btn '+details2+' btn-sm btn-delete" onclick="order_qc_data(1,' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-pencil"></i>'+details+'</a>';
                                                                                <?php
                                                                                }    
                                                                                if($order_qc2==1)
                                                                                {    
                                                                                ?>
                                                                                if(row.qc2_status=='' || row.qc2_status==null)
                                                                                {
                                                                                var details2="btn-soft-secondary";      
                                                                                var details="Qc 2 - Pending";    
                                                                                }    
                                                                                else
                                                                                {
                                                                                var details=row.qc2_status;  
                                                                                var details2="btn-soft-success";  
                                                                                }                        
                                                                                var order_qc2= '<a style="padding:3px;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2"  class="btn '+details2+' btn-sm btn-delete" onclick="order_qc_data(2,' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-pencil"></i>'+details+'</a>';
                                                                                <?php
                                                                                }    
                                                                                ?>
                                                                                return editButton + ' ' + deleteButton+" "+order_extra_details+" "+order_qc1+" "+order_qc2+" "+completeButton;
                                                                            }
                                                                        },
                                                                        <?php
                                                                        foreach ($display as $display_res) {
                                                                            if($display_res=='id')
                                                                            {
                                                                            ?>
                                                                            {
                                                                                "data": "id",
                                                                                "render": function (data, type, row) {
                                                                                    return '<input type="checkbox" class="row-checkbox result_default_id" value="' + data + '"><br>'+data;
                                                                                }
                                                                            }, 
                                                                            <?php
                                                                            }
                                                                            else if($display_res=='main_status')
                                                                            {
                                                                            ?>
                                                                            {
                                                                            "data": "main_status",
                                                                            "render": function (data, type, row) {
                                                                             if(data!='')
                                                                             {
                                                                             var status="<a onclick='order_details_history("+row.id+");' href='#' data-bs-toggle='modal' data-bs-target='.exampleModalFullscreen2'>"+data+"</a>";   
                                                                             }  
                                                                             return status;
                                                                            }
                                                                            },
                                                                            <?php    
                                                                            }
                                                                            else if($display_res=='added_date')
                                                                            {
                                                                            ?>
                                                                            {
                                                                            "data": "added_date",
                                                                            "render": function (data, type, row) {
                                                                                // Parse the date string into a JavaScript Date object
                                                                                if(data!='')
                                                                                {    
                                                                                var date = new Date(data);

                                                                                // Define month names
                                                                                var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                    "August", "September", "October", "November", "December"];

                                                                                // Extract the date components
                                                                                var year = date.getFullYear();
                                                                                var month = monthNames[date.getMonth()];
                                                                                var day = date.getDate();

                                                                                // Create the formatted date string
                                                                                var formattedDate = month + ' ' + day + ', ' + year;
                                                                                }
                                                                                else
                                                                                {
                                                                                var formattedDate="-";    
                                                                                }
                                                                                 return formattedDate;
                                                                                
                                                                            }
                                                                            },
                                                                            <?php
                                                                            }
                                                                            else if($display_res=='updated_date')
                                                                            {
                                                                            ?>
                                                                            {
                                                                            "data": "updated_date",
                                                                            "render": function (data, type, row) {
                                                                                // Parse the date string into a JavaScript Date object
                                                                                if(data=='')
                                                                                {    
                                                                                var date = new Date(data);

                                                                                // Define month names
                                                                                var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                    "August", "September", "October", "November", "December"];

                                                                                // Extract the date components
                                                                                var year = date.getFullYear();
                                                                                var month = monthNames[date.getMonth()];
                                                                                var day = date.getDate();

                                                                                // Create the formatted date string
                                                                                var formattedDate = month + ' ' + day + ', ' + year;
                                                                                }
                                                                                else
                                                                                {
                                                                                var formattedDate="-";    
                                                                                }    
                                                                                // Return the formatted date
                                                                                return formattedDate;
                                                                                
                                                                            }
                                                                            },
                                                                            <?php
                                                                            }
                                                                            else 
                                                                            {    
                                                                            ?>
                                                                            {"data": "<?php echo $display_res; ?>"},
                                                                            <?php
                                                                            }
                                                                        }
                                                                        ?>

                                                                    ],
                                                                    "lengthMenu": [[10, 25, 50, 100], [10, 25, 50, 100]],
                                                                    "pageLength": 10,
                                                                    "dom": 'Bfrtip',
                                                                    "columnDefs": [
                                                                        {"orderable": false, "targets": 0} // Disable sorting for the first column
                                                                    ],
                                                                    "buttons": ['copy', 'csv', 'excel', 'print'],
                                                                    "order": [[1, "desc"]]
                                                                });

                                                                $('#search-input').on('keyup', function () {
                                                                    dataTable.search(this.value).draw();
                                                                });


                                                                // Add checkboxes for each column to control visibility
                                                                var columnToggleContainer = $('<div>').addClass('column-toggle-container');
                                                                dataTable.columns().every(function () {
                                                                    var column = this;
                                                                    var columnIdx = column.index();
                                                                    var columnName = $(column.header()).text().trim();

                                                                    // Create checkbox for the column
                                                                    var checkbox = $('<input>').attr({
                                                                        'type': 'checkbox',
                                                                        'class': 'column-toggle-checkbox',
                                                                        'data-column': columnIdx,
                                                                        'checked': 'checked'
                                                                    });
                                                                    var label = $('<label style="width:100%;">').text(columnName).prepend(checkbox);
                                                                    var label2 = '<li><a class="dropdown-item" href="#">' + label + '</a></li>';
                                                                    // Append checkbox to the container
                                                                    columnToggleContainer.append(label);
                                                                });
                                                                //    alert(columnToggleContainer);

                                                                // Append the column toggle container to the DataTable
                                                                $('.dropdown-menu2').prepend(columnToggleContainer);
                                                                //    $('#columns_display').prepend(columnToggleContainer);

                                                                // Show/hide columns based on checkbox selection
                                                                $('.column-toggle-checkbox').on('change', function () {
                                                                    var columnIdx = $(this).data('column');
                                                                    dataTable.column(columnIdx).visible($(this).is(':checked'));
                                                                });

                                                                //                initializeDataTable();
                                                            });
                                                            
                                                           
                                                            function deleterecord(id)
                                                            {

                                                                Swal.fire({
                                                                    title: "Are you sure?",
                                                                    text: "You won't be able to recover this entity!.",
                                                                    icon: "warning",
                                                                    showCancelButton: true,
                                                                    confirmButtonText: "Yes, delete it!",
                                                                    cancelButtonText: "No, cancel!",
                                                                    confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
                                                                    cancelButtonClass: "btn btn-danger w-xs mt-2",
                                                                    buttonsStyling: false,
                                                                    showCloseButton: true,
                                                                }).then(function (result) {
                                                                    if (result.value) {
                                                                        $.ajax({
                                                                            url: "<?php echo base_url($master . '/delete_data'); ?>",
                                                                            type: "POST",
                                                                            data: {
                                                                                "id": id,
                                                                                "delete_column": "<?php echo $delete_id; ?>",
                                                                                "table": "<?php echo $table; ?>",
                                                                                "heading": $("#heading").val()
                                                                            },
                                                                            success: function (response) {
                                                                                var result = JSON.parse(response);
                                                                                Swal.fire({
                                                                                    icon: 'success',
                                                                                    title: 'Deleted!',
                                                                                    text: result.message,
                                                                                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                                                                                    buttonsStyling: false
                                                                                });
                                                                                var dataTable = $('#ajax_datatables2').DataTable();
                                                                                dataTable.draw();
                                                                            }
                                                                        });
                                                                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                                                                        // User cancelled deletion
                                                                        Swal.fire({
                                                                            title: "Cancelled",
                                                                            text: "Your data is safe :)",
                                                                            icon: "error",
                                                                            confirmButtonClass: "btn btn-primary mt-2",
                                                                            buttonsStyling: false
                                                                        });
                                                                    }
                                                                });
                                                                
                                                            }

                                                            $("#add-category-form").on('submit', (function (e) {
                                                                e.preventDefault();
                                                                $.ajax({
                                                                    url: "<?php echo base_url($master . '/add_data'); ?>",
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
                                                                        var add_via_page = $("#add_via_page").val();
                                                                        //                            alert(add_via_page);


                                                                        if (category.success == true)
                                                                        {
                                                                            if (category.type == '1')
                                                                            {
                                                                                var dataTable = $('#ajax_datatables2').DataTable();
                                                                                dataTable.draw();
                                                                                Swal.fire({
                                                                                    icon: 'success',
                                                                                    title: 'Success',
                                                                                    text: category.message,
                                                                                    timer: 1500, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                                    showConfirmButton: false // Hide the "OK" button
                                                                                });
                                                                                
                                                                                $('#reset').click();

                                                                                if (add_via_page > 0)
                                                                                {
                                                                                    alert("Added Successfully!");
                                                                                    window.close();
                                                                                }

                                                                                $('.exampleModalFullscreen').modal('hide');
                                                                                $('#add-category-form')[0].reset();
                                                                                $('#edit-category-form')[0].reset();
//                                                                                location.reload()
                                                                                //                                    alert(add_via_page);

                                                                            } else if (category.type == '2')
                                                                            {
                                                                                var dataTable = $('#ajax_datatables2').DataTable();
                                                                                dataTable.draw();
                                                                                Swal.fire({
                                                                                    icon: 'success',
                                                                                    title: 'Success',
                                                                                    text: category.message,
                                                                                    timer: 1500, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                                    showConfirmButton: false // Hide the "OK" button
                                                                                });
                                                                                $('#reset').click();
//                                                                                Swal.fire({
//                                                                                    icon: 'success',
//                                                                                    title: 'Success',
//                                                                                    text: category.message
//                                                                                });
                                                                                if (add_via_page > 0)
                                                                                {
                                                                                    alert("Added Successfully!");
                                                                                    window.close();
                                                                                }
                                                                                $('.exampleModalFullscreen').modal('hide');
                                                                                $('#add-category-form')[0].reset();
                                                                                $('#edit-category-form')[0].reset();
                                                                            }


                                                                        }
                                                                        if (category.success == false)
                                                                        {
                                                                            Swal.fire({
                                                                                    icon: 'error',
                                                                                    title: 'Error',
                                                                                    text: category.message,
                                                                                    timer: 3000, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                                    showConfirmButton: false // Hide the "OK" button
                                                                            });
                                                                        }
                                                                    },
                                                                    error: function (e)
                                                                    {
                                                                        Swal.fire({
                                                                                    icon: 'error',
                                                                                    title: 'Error',
                                                                                    text: "Something went wrong, please try again!!",
                                                                                    timer: 3000, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                                    showConfirmButton: false // Hide the "OK" button
                                                                        });
                                                                    }
                                                                });
                                                            }));
                                                            $("#add-extra-form").on('submit', (function (e) {
                                                                e.preventDefault();
                                                                $.ajax({
                                                                    url: "<?php echo base_url($master . '/add_extra_data'); ?>",
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
//                                                                        alert(category);
//                                                                         alert(category.success);
//                                                                        var add_via_page = $("#add_via_page").val();
                                                                        if (category.success == true)
                                                                        {
                                                                            
                                                                                var dataTable = $('#ajax_datatables2').DataTable();
                                                                                dataTable.draw();
                                                                                Swal.fire({
                                                                                    icon: 'success',
                                                                                    title: 'Success',
                                                                                    text: category.message
                                                                                });

                                                                                location.reload();

                                                                            
                                                                        }
                                                                        if (category.success == false)
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
                                                                    url: "<?php echo base_url($master . '/edit_data'); ?>",
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
                                                                        if (category.success == true)
                                                                        {
                                                                            $('#edit-category-form')[0].reset();
                                                                            $('.exampleModalFullscreen2').modal('hide');
                                                                            var dataTable = $('#ajax_datatables2').DataTable();
                                                                            dataTable.draw();
                                                                            //                                alert(data);
                                                                            Swal.fire({
                                                                                icon: 'success',
                                                                                title: 'Success',
                                                                                text: category.message
                                                                            });
                                                                        }
                                                                        if (category.success == false)
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
            <script>
            <?php
            foreach ($perameters3 as $perameters3_res) {
                if ($perameters3_res['addfunction'] != '') {
                    echo $perameters3_res['addfunction'];
                }
            }
            ?>
            </script>

            <?php
            if ($footerjsextra_images == '1') {
                ?>
                <!-- dropzone min -->
                <script src="<?php echo base_url(); ?>theme/assets/libs/dropzone/dropzone-min.js"></script>
                <!-- filepond js -->
                <script src="<?php echo base_url(); ?>theme/assets/libs/filepond/filepond.min.js"></script>
                <script src="<?php echo base_url(); ?>theme/assets/libs/filepond-plugin-image-preview/filepond-plugin-image-preview.min.js"></script>
                <script src="<?php echo base_url(); ?>theme/assets/libs/filepond-plugin-file-validate-size/filepond-plugin-file-validate-size.min.js"></script>
                <script src="<?php echo base_url(); ?>theme/assets/libs/filepond-plugin-image-exif-orientation/filepond-plugin-image-exif-orientation.min.js"></script>
                <script src="<?php echo base_url(); ?>theme/assets/libs/filepond-plugin-file-encode/filepond-plugin-file-encode.min.js"></script>

                <script src="<?php echo base_url(); ?>theme/assets/js/pages/form-file-upload.init.js"></script>

                <?php
            }
            ?>


            <style>
                .upload__box {
                    padding: 10px;
                }
                .upload__inputfile {
                    width: 0.1px;
                    height: 0.1px;
                    opacity: 0;
                    overflow: hidden;
                    position: absolute;
                    z-index: -1;
                }
                .upload__btn {
                    display: inline-block;
                    font-weight: 600;
                    color: #fff;
                    text-align: center;
                    min-width: 116px;
                    padding: 5px;
                    transition: all 0.3s ease;
                    cursor: pointer;
                    border: 2px solid;
                    background-color: #4045ba;
                    border-color: #4045ba;
                    border-radius: 10px;
                    line-height: 26px;
                    font-size: 14px;
                }
                .upload__btn:hover {
                    background-color: unset;
                    color: #4045ba;
                    transition: all 0.3s ease;
                }
                .upload__btn-box {
                    margin-bottom: 10px;
                }
                .upload__img-wrap {
                    display: flex;
                    flex-wrap: wrap;
                    margin: 0 -10px;
                }
                .upload__img-box {
                    width: 100px;
                    padding: 0 10px;
                    margin-bottom: 12px;
                }
                .upload__img-close {
                    width: 24px;
                    height: 24px;
                    border-radius: 50%;
                    background-color: rgba(0, 0, 0, 0.5);
                    position: absolute;
                    top: 10px;
                    right: 10px;
                    text-align: center;
                    line-height: 24px;
                    z-index: 1;
                    cursor: pointer;
                }
                .upload__img-close:after {
                    content: "✖";
                    font-size: 14px;
                    color: white;
                }

                .img-bg {
                    background-repeat: no-repeat;
                    background-position: center;
                    background-size: cover;
                    position: relative;
                    padding-bottom: 100%;
                }

            </style>

            <script>
                jQuery(document).ready(function () {
                    ImgUpload();
                });

                function ImgUpload() {
                    var imgWrap = "";
                    var imgArray = [];

                    $('.upload__inputfile').each(function () {
                        $(this).on('change', function (e) {
                            imgWrap = $(this).closest('.upload__box').find('.upload__img-wrap');
                            var maxLength = $(this).attr('data-max_length');

                            var files = e.target.files;
                            var filesArr = Array.prototype.slice.call(files);
                            var iterator = 0;
                            filesArr.forEach(function (f, index) {

                                if (!f.type.match('image.*')) {
                                    return;
                                }

                                if (imgArray.length > maxLength) {
                                    return false
                                } else {
                                    var len = 0;
                                    for (var i = 0; i < imgArray.length; i++) {
                                        if (imgArray[i] !== undefined) {
                                            len++;
                                        }
                                    }
                                    if (len > maxLength) {
                                        return false;
                                    } else {
                                        imgArray.push(f);

                                        var reader = new FileReader();
                                        reader.onload = function (e) {
                                            var html = "<div class='upload__img-box'><div style='background-image: url(" + e.target.result + ")' data-number='" + $(".upload__img-close").length + "' data-file='" + f.name + "' class='img-bg'><div class='upload__img-close'></div></div></div>";
                                            imgWrap.append(html);
                                            iterator++;
                                        }
                                        reader.readAsDataURL(f);
                                    }
                                }
                            });
                        });
                    });

                    $('body').on('click', ".upload__img-close", function (e) {
                        var file = $(this).parent().data("file");
                        for (var i = 0; i < imgArray.length; i++) {
                            if (imgArray[i].name === file) {
                                imgArray.splice(i, 1);
                                break;
                            }
                        }
                        $(this).parent().parent().remove();
                    });
                }
            </script>  

            <?php
            if ($_GET['add'] == 1) {
                ?>
                <script>
                    $(".add-btn").click();
                </script>
                <?php
            }
            ?>

            <script >
                
                  document.addEventListener('DOMContentLoaded', function() {
                    // Your JavaScript code here
                    pagechecking();
                  });
                  function pagechecking()
                  {
                  //alert('1');
                    var all_accessbile_url=$(".all_accessbile_url").html();
                    var jsonArray = JSON.parse(all_accessbile_url);
                    var currentUrl = '<?php echo $url_handle; ?>';
                    var session = "<?php echo $_SESSION['id']; ?>";
                    var urlFound = false;
//                    alert(currentUrl);
//                    if(session==1)
                    if(session>1)
                    {    
                    for (var i = 0; i < jsonArray.length; i++) {
                        if (jsonArray[i].includes(currentUrl)) {
                            urlFound = true;
                            break; // Exit the loop if the URL is found
                        }
                    }

                    if (urlFound) {
                        $("#layout-wrapper").css("display","block");
                        $("#default_extra_loading").css("display","none");
                    } else {
//                        $("#layout-wrapper").css("display","block");
//                        $("#default_extra_loading").css("display","none");
                        
                        $("#layout-wrapper").css("display","none");
                        $("#default_extra_loading").css("display","block");
                        $("#loading_custom_message").css("display","none");
                        $("#load_custom_message").css("padding","20px");
                        $("#load_custom_message").html("You Dont't Have Access This Page!");
                    }
                    }
                    else
                    {
                        $("#layout-wrapper").css("display","block");
                        $("#default_extra_loading").css("display","none");
                    }    
                  }
                
                $(document).ready(function () {
                    // Function to be executed after the delay
                    function delayedClick() {
                        var current_url = $("#current_url").html();
                        var current_url = current_url.trim();
                        var page_id1 = getCookie('page_id1');
                        var page_id2 = getCookie('page_id2');
                        //            var href=$('[data-id="'+page_id1+'"]').attr("href");
                        //            $(".classname_"+page_id1).trigger('click');
                        //            $('.classname_masters').trigger('click');
                        //            alert(page_id1);
                        //            alert(page_id2);
                        //            alert(href);
                        $('#main_' + page_id1).click();

                        $('[data-id="main_' + page_id1 + '"]').removeClass('collapsed');

                        $('[data-id="main_' + page_id1 + '"]').attr('aria-expanded', 'true');

                        $('.main_sub_' + page_id1).addClass('show');



                        $('[data-id="main2_' + page_id2 + '"]').removeClass('collapsed');

                        $('[data-id="main2_' + page_id2 + '"]').attr('aria-expanded', 'true');

                        $('.main_sub2_' + page_id2).addClass('show');



                        //            $('#main2_'+page_id2).removeClass('collapsed');
                        //            
                        //            $('#main2_'+page_id2).attr('aria-expanded','true');
                        //            
                        //            $('.sub_sub_main_'+page_id2).addClass('show');
                        $('a[href="' + current_url + '"]').addClass('active');

                        //            $('a').addClass('show');

                    }

                    // Set a timeout to call the delayedClick function after 5 seconds
                    setTimeout(function () {
                        delayedClick();
                    }, 1000); // 5000 milliseconds = 5 seconds

                    // Example: Click event on a button
                    $('#myButton').on('click', function () {
                        alert("Normal click event executed!");
                    });
                });
                function set_menus_id(data1, data2)
                {
                    setCookie("page_id1", data1, 7);
                    setCookie("page_id2", data2, 7);
                }
                function getCookie(name) {
                    var nameEQ = name + "=";
                    var cookies = document.cookie.split(';');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = cookies[i];
                        while (cookie.charAt(0) === ' ') {
                            cookie = cookie.substring(1, cookie.length);
                        }
                        if (cookie.indexOf(nameEQ) === 0) {
                            return cookie.substring(nameEQ.length, cookie.length);
                        }
                    }
                    return null;
                }


                function setCookie(name, value, days) {
                    var expires = "";
                    if (days) {
                        var date = new Date();
                        date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
                        expires = "; expires=" + date.toUTCString();
                    }
                    document.cookie = name + "=" + (value || "") + expires + "; path=/";
                }

            </script>


            <input type="hidden" name="type" id="type" value="<?php echo $type; ?>" />
            <input type="hidden" name="dbname" id="dbname" value="<?php echo ($_SESSION['customer_data']['dbname']); ?>" />
            <input type="hidden" name="edit_extra_function_id" id="edit_extra_function_id" value="<?php echo $edit_extra_function_id; ?>" />
            <input type="hidden" name="edit_extra_function" id="edit_extra_function" value="<?php echo $edit_extra_function; ?>" />
            <input type="hidden" name="add_via_page" id="add_via_page" value="<?php echo $_GET['add']; ?>" />
            <input type="hidden" name="url_handle" id="url_handle" value="<?php echo $url_handle; ?>" />
            <?php
            foreach ($perameters as $perameters_res) {
            if($perameters_res['focus']=='1')
            {    
            ?>
            <input type="hidden" name="focus_id" id="focus_id" value="<?php echo $perameters_res['id']; ?>" />
            <?php
            }
            }
            ?>
        </body>
    </html>    
            
                                                   