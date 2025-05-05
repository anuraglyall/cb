<?php

if (empty($this->session->userdata('id'))) {
    $this->load->view('content/login');
}
else {    
    ?>
    <!doctype html>
    <html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" 
          data-layout-position="scrollable" data-sidebar-size="lg" data-sidebar-image="none"  
          data-sidebar-visibility="show"
          >  
            <meta charset="utf-8" />
            <title><?php echo $title; ?></title>
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <meta content="64Facets Admin Panel" name="description" />
            <meta content="64Facets" name="author" />
            <link href="<?php echo base_url(); ?>theme/assets/libs/sweetalert2/sweetalert2.min.css?ver=<?php echo date('Ymd'); ?>" rel="stylesheet" type="text/css" />
            <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css?ver=<?php echo date('Ymd'); ?>" />
            <link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.2.9/css/responsive.bootstrap.min.css?ver=<?php echo date('Ymd'); ?>" />
            <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.2.2/css/buttons.dataTables.min.css?ver=<?php echo date('Ymd'); ?>">

            <link rel="stylesheet" href="<?php  echo base_url().'theme/assets/css/admin.css';?>?ver=<?php echo date('Ymd'); ?>">
            
            <?php
            $this->load->view('templates/headercss');
            ?>    
        </head>  
        <body>
            <div id="layout-wrapper" >
            <?php
            $_SERVER["REQUEST_URI"];
            $this->load->view('templates/header');
            ?>
            <div class="app-menu navbar-menu">
            <div class="navbar-brand-box">
                <a href="<?php echo base_url().'dashboard' ?>" class="logo logo-dark">
                    <span class="logo-sm">
                        <img src="<?php // echo base_url().'theme/';?>small-64facets.png" alt="" height="22">
                    </span>
                    <span class="logo-lg">
                        <img src="<?php  //echo base_url().'theme/';?>64ee4e5eedb17_20230829200030.png" alt="" height="70">
                    </span>
                </a>
                <a href="<?php echo base_url().'dashboard' ?>" class="logo logo-light">
                    <span class="logo-sm">
                        <img class="logo-codebright" src="codebright-rb.png">
                    </span>
                    <span class="logo-lg">
                        <img class="logo-codebright" src="codebright-rb.png">
                    </span>
                </a>
                <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover" id="vertical-hover">
                    <i class="ri-record-circle-line"></i>
                </button>
            </div>
            <div id="current_url" style="display:none;">
            <?php
            echo $fullUrl = base_url(uri_string());
            ?>
            </div>            
            <div id="current_url2">
            </div>            
            <div id="scrollbar">
                <div class="container-fluid">
                    <div id="two-column-menu">
                    </div>
                    <ul class="navbar-nav" id="navbar-nav">
                        <li class="menu-title"><span data-key="t-menu">Features / Menu</span></li>
                        <?php 
//                        echo $_SERVER['REQUEST_URI'];
                      //  if(base_url()=='http://47.128.72.3/Codebright/api/admin/')
                      //  {    
                        $url_handle=explode("/",$_SERVER['REQUEST_URI']);
                      //  }
//                        print_r($url_handle);
                        $url_handle=$url_handle[1];
//                        echo '<br>';
                        $url_handle=explode("?",$url_handle);
                        $url_handle=$url_handle[0];
                        $url_last_handle=$url_handle[2];
                        function generateSlug($string) {
                            $string = strtolower($string);
                            $string = str_replace(' ', '-', $string);
                            $string = preg_replace('/[^a-z0-9\-]/', '', $string);
                            $string = preg_replace('/-+/', '-', $string);
                            $string = trim($string, '-');
                            return $string;
                        }
                        $access=($_SESSION['access_urls']);
                                             
                        $session_id=($_SESSION['id']);
                        $urls='';
                        $finals_with_access=array();
                        foreach($access as $access_res)
                        {
                        $urls.=$access_res['url_ids'].',';    
                        }  
                        
                        $url_ids=rtrim($urls,',');
                        
                        if($_SESSION['id']=='1')
                        {
//                         $sql="";   
                        }
                        else  
                        {    
                        
                        if($url_ids!='')
                        {    
                        $sql=" and urls.id IN($url_ids)";
                        $sql1=" and id IN($url_ids)";
                        }
                        }
                        
                        
                        $data=("select urls.* from urls where urls.menu_level='0' $sql and urls.status='1' order by url_access_sort ASC");
                        $data=$this->db->query($data)->result_array();
                        $menu1="submenu_level_";
                        $menu2="sub_submenu_level_";
                        $i=0;
                        $k=0;
                        $final_dk=0;
                        
                        $all_urls="";
                        foreach($data as $data_res)
                        {
                        
                        $read=$data_res['read'];
                        $write=$data_res['write'];
                        $edit=$data_res['edit'];
                        $delete=$data_res['delete'];
                        $id=$data_res['id'];    
                        
                        if($data_res['name']=='Masters')
                        { 
                            $sql1='';    
                            $final_dk=$final_dk+10;
                        }   
                        $user_type = $this->db->query("select a.*,b.name as access_type from users a " 
                        ."  left outer join user_type b on b.id=a.type"         
                        ."  where a.id=$session_id and a.status!='2'")->row_array();
                        $access = $user_type['access_type'];
                        
                        $data2=$this->db->query("select *"
                                . " from urls where 1 $sql1 and menu_level='1' and submenu_level='$id' and status='1' and access_scope in ('All','$access')")->result_array();    
                        
                        if(!empty($data2))
                        {
                            $i++;    
                            $menu1=$menu1.''.$i;    
                        }
                        
                        $main_name=$data_res['name'];
                        $main_name = generateSlug($main_name);

                        ?>    
                        <li class="nav-item">
                            <a class="nav-link menu-link" 
                               href="<?php 
                               if($data_res['menu_handle']=='#')
                               {
                               if($menu1=='' ) 
                               {
                                if($data_res['menu_handle']!='') { 
                                    echo $data_res['menu_handle']; 
                                    if (strpos($data_res['menu_handle'], $url_handle) !== false) {
                                        $finals_with_access[]=$data_res;
                                    }
                                    
                                    $all_urls.=$data_res['menu_handle'].',';
                                } 
                                else 
                                { 
                                    echo '#'; 
                                } 
                               }
                               else
                               {
                                echo base_url().'#'.$menu1;    
                               }    
                               }
                               else
                               {
                               echo $data_res['menu_handle'];    
                               if (strpos($data_res['menu_handle'], $url_handle) !== false) {
                                    $finals_with_access[]=$data_res;
                               }
                                    $all_urls.=$data_res['menu_handle'].',';
                               }    
                               ?>"
                               <?php if(count($data2)) { echo ' '
                                   . 'data-bs-toggle="collapse" data-id="main_'.$main_name.'" '
                                       . 'id="main_'.$menu1.'" role="button" aria-expanded="false" '
                                       . 'aria-controls="'.$menu1.'"'; } ?>
                               >
                               <?php echo $data_res['icons']; ?>
                               <span data-key="t-widgets">
                                   <?php echo $data_res['name']; ?>
                               </span>
                            </a>
                            <?php 
                            $k=0;
                            foreach($data2 as $data2_res)
                            {
                            $read=$data2_res['read'];
                            $write=$data2_res['write'];
                            $edit=$data2_res['edit'];
                            $delete=$data2_res['delete'];
                        
                            $id2=$data2_res['id'];    
                            $data3=$this->db->query("select *"
                                    . ",'$read' as `read`, "
                                    . "'$write' as `write`, "
                                    . "'$edit' as `edit`, "
                                    . "'$delete' as `delete`  "                                            
                                    . " from urls where menu_level='1'"
                                    . " and submenu_level='$id2'  and status='1'")->result_array(); 
                            
                            $main_name2=$data2_res['name'];
                            $main_name2 = generateSlug($main_name2);
                            
                            if(!empty($data3))
                            {
                            $k++;    
                            $menu2=$menu2.''.$k;    
                            }
                            ?>
                            <div class="collapse menu-dropdown <?php echo 'main_sub_'.$main_name; ?>" id="<?php echo $menu1; ?>">
                               <ul class="nav nav-sm flex-column">
                               <li class="nav-item" >
                               <a   class="nav-link menu-link <?php if(count($data2)) { echo 'collapsed'; } ?>" 
                               href="<?php 
                               if($data2_res['menu_handle']=='#')
                               {    
                               if($menu2=='') 
                               {
                                if($data_res['menu_handle']!='') {
                                    echo $data_res['menu_handle']; 
                                    
                                    if (strpos($data2_res['menu_handle'], $url_handle) !== false) {
                                    $finals_with_access[]=$data2_res;
                                    }
                                    
                                    $all_urls.=$data_res['menu_handle'].',';
                                } 
                                else 
                                {
                                    echo '#';
                                    $show_anchor2='';
                                } 
                               }
                               else
                               {
                               echo base_url().'#'.$menu2;   
                               $show_anchor2='';
                               }    
                               }
                               else 
                               {
                               echo $data2_res['menu_handle']; 
                               if (strpos($data2_res['menu_handle'], $url_handle) !== false) {
                                    $finals_with_access[]=$data2_res;
                               }
                               $all_urls.=$data2_res['menu_handle'].',';
                               $show_anchor2='1';
                               }  
                               ?>"
                                <?php if($show_anchor2==1) { ?> onclick="set_menus_id('<?php echo $main_name; ?>','<?php echo $main_name2; ?>')" <?php } ?>
                                   <?php if($data_res['menu_handle']=='#') 
                                   {
                                   if(count($data3)) { 
                                       echo ' data-id="main2_'.$main_name2.'" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="'.$menu2.'"'; 
                                   }
                                   }
                                   else {
                                       $all_urls.=$data_res['menu_handle'].',';
                                       if (strpos($data_res['menu_handle'], $url_handle) !== false) {
                                        $finals_with_access[]=$data2_res;
                                        }
                                    
                                       echo $data_res['menu_handle']; 
                                   }
                                   ?>
                               class="nav-link" ><?php echo $data2_res['name'];
                               //' '.$data2_res['menu_handle']; ?>  </a>
                               <?php 
                               foreach($data3 as $data2_res_res)
                               {    
                               ?> 
                               <div class="collapse menu-dropdown <?php echo 'main_sub2_'.$main_name2; ?>" id="<?php echo $menu2; ?>">
                               <ul class="nav nav-sm flex-column">
                               <li class="nav-item" >
                                   <a class="nav-link menu-link" onclick="set_menus_id('<?php echo $main_name; ?>','<?php echo $main_name2; ?>')" href="<?php 
                                   echo $data2_res_res['menu_handle']; 
                                   $all_urls.=$data2_res_res['menu_handle'].',';
                                    if (strpos($data2_res_res['menu_handle'], $url_handle) !== false) {
                                    $finals_with_access[]=$data2_res_res;
                                    }
                                    
                                   ?>">
                                    <?php echo $data2_res_res['name']; ?>
                                   </a>
                               </li>
                               </ul>    
                               </div>
                               <?php 
                               }   
                               ?>
                               </li>
                               </ul>    
                            </div>
                            <?php
                            }                            
                            ?>
                        </li>      
                        <?php    
                        } 
                        $all_url_list= json_encode(explode(",",$all_urls));
                        
                        
                        
                        ?>
                    </ul>
                </div>
            </div>
                
                
       
            <div class="sidebar-background"></div>
            <div class="sidebar-background all_accessbile_url" style="display:none;"><?php echo $all_url_list; ?></div>  
            </div>
            <div class="vertical-overlay"></div>
            <div class="main-content">
                    <?php 
                    
                    if($mainpage!='dashboard')
                    {    
                    ?>
                    <div class="page-content">
                        <div class="container-fluid">
                                    <?php 
//                                    echo '<pre>';
                                    $fullUrl = base_url(uri_string());
                                    
                                    $add=0;
                                    $edit=0;
                                    $delete=0;
                                    $sold=0;
                                    $repair=0;
                                    $retired=0;
                                    $profile=0;
                                    $order_qc1=0;
                                    $order_qc2=0;
                                    $calculator=0;
                                    $complete=0;
                                    $return_status=0;
                                    $status=0;
                                  
//                                    echo '<pre>';
                                    foreach($_SESSION['access_fields'] as $access_fields)
                                    {
                                    if($fullUrl==$access_fields['menu_handle'])
                                    {    
//                                    print_r($access_fields);    
                                    if(strtoupper($access_fields['access_type'])=='ADD')
                                    {    
                                    $add=$add+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='EDIT')
                                    {    
                                    $edit=$edit+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='DELETE')
                                    {    
                                    $delete=$delete+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='SOLD STATUS')
                                    {    
                                    $sold=$sold+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='REPAIR STATUS')
                                    {    
                                    $repair=$repair+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='RETURNS STATUS')
                                    {    
                                    $return_status=$return_status+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='RETIRED STATUS')
                                    {    
                                    $retired=$retired+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='PROFILE')
                                    {    
                                    $profile=$profile+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='QC1')
                                    {    
                                    $order_qc1=$order_qc1+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='QC2')
                                    {    
                                    $order_qc2=$order_qc2+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='CALCULATOR')
                                    {    
                                    $calculator=$calculator+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='FINISH')
                                    {    
                                    $complete=$complete+1;
                                    }
                                    if(strtoupper($access_fields['access_type'])=='STATUS')
                                    {    
                                    $status=$status+1;
                                    }
                                    }     
                                    } 
                                    
                                     
                                    $add=1;
                                    $edit=1;
                                    $delete=1;
                                    $sold=0;
                                    $repair=0;
                                    $retired=0;
                                    $profile=0;
                                    $order_qc1=0;
                                    $order_qc2=0;
                                    $calculator=0;
                                    $complete=0;
                                    $return_status=0;
                                    $status=0;
                                    $segment1 = $this->uri->segment(1);
                                    
                                   
//                                    echo '<pre>';
//                                    print_r($_SESSION['access_fields']);
//                                    echo '</pre>';
                                    $final_dk=0;
                                    $final_dk1=0;
                                    $final_dk2=0;
                                    foreach($_SESSION['access_fields'] as $data2_res)
                                    {
//                                     print_r($data2_res);
//                                     echo '<br>';
                                     if(strtoupper($data2_res['name'])=='MASTERS' && strtoupper($data2_res['access_type'])=='ADD')
                                     {
                                     $final_dk=10;    
                                     }
                                     if(strtoupper($data2_res['name'])=='MASTERS' && strtoupper($data2_res['access_type'])=='EDIT')
                                     {
                                     $final_dk1=10;    
                                     }
                                     if(strtoupper($data2_res['name'])=='MASTERS' && strtoupper($data2_res['access_type'])=='DELETE')
                                     {
                                     $final_dk2=10;    
                                     }
                                    }
                                    if($final_dk==10 || $final_dk1==10 || $final_dk2==10)
                                    {
//                                        echo '<pre>';
                                        $check=$this->db->query("SELECT * FROM `urls` WHERE "
                                                . "`submenu_level` IN (2,13,22,33,40)"
                                                . " and handle='$main_final_url'"
                                                . "")->result_array();
                                        if(count($check)>0)
                                        {    
                                        if($final_dk==10)
                                        {
                                        $add=1;
                                        }
                                        if($final_dk1==10)
                                        {
                                        $edit=1;
                                        }
                                        if($final_dk2==10)
                                        {
                                        $delete=1;
                                        }
                                        }
//                                        echo '</pre>';
                                    }    
//                                    echo '</pre>';
                                    
//                                    echo $final_dk;
//                                    echo '<br>';
//                                    echo $final_dk1;
//                                    echo '<br>';
//                                    echo $final_dk2;
//                                    echo '<br>';
//                                    echo '<br>';
//                                    echo '<br>';
                                    
                                    
//                                    $edit=1;
                                    
//                                    $add=1;
                                    if($_SESSION['id']=='1')
                                    {
                                        $add=1;
                                        $edit=1;
                                        $delete=1;
            //                         $sql="";   
                                    }
                                    ?>
                                    <div class="row">
                                    <div class="col-12">
                                    <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                        <h4 class="mb-sm-0"><?php
//                                        echo $maintitle2;
                                            if (isset($maintitle2)) {
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
                            <div class="row">
                                <div class="col-lg-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5 class="card-title mb-0">
                                                <div align="right" class="col-md-12" >

                                                    <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
                                                        <?php 
                                                        if ($add=='1' && $title != 'Tutorials' && $title != 'Student Quiz Answer' && $title != 'Student' && $title != 'Parent' && $title != 'Pre Register Event' && $title != 'User Tutorial Subscription' 
                                                        && $title != 'Student Parent Relationship' && $title != 'Gallery' && $title != 'Course Rating' && $title != 'Payments' && $title != 'Student Homework' && $title != 'Complain' && $title != 'Contact us' && $title != 'Tutorial Transactions' && trim($title) != 'Reschedule Classes') {
                                                            ?>
                                                        <!-- Static Backdrop -->
                                                           <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen" id="create-btn" onclick="add_form()" ><i class="ri-add-line align-bottom me-1"></i> Add <?php echo $title; ?></button>
                                                             <?php
                                                        }
                                                        if ($sold=='1') {
                                                            ?>
                                                            <button type="button" class="btn btn-success bg-gradient waves-effect waves-light" style="padding:5px;float:right;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" id="order_status" onclick="add_order_status('<?php echo $order_status_type; ?>')" ><i class="ri-add-line align-bottom me-1"></i>Record Sales</button>
                                                            <?php
                                                        }
                                                        if ($return_status=='1') {
                                                            ?>
                                                            <button type="button" class="btn btn-success bg-gradient waves-effect waves-light" style="padding:5px;float:right;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" id="order_status" onclick="add_order_status('<?php echo $order_status_type; ?>')" ><i class="ri-add-line align-bottom me-1"></i>Return Status</button>
                                                            <?php
                                                        }
                                                        if ($repair=='1') {
                                                            ?>
                                                            <button type="button" class="btn btn-success bg-gradient waves-effect waves-light" style="padding:5px;float:right;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" id="order_status" onclick="add_order_status('<?php echo $order_status_type; ?>')" ><i class="ri-add-line align-bottom me-1"></i>Record Repairs</button>
                                                            <?php
                                                        } 
                                                        if ($retired=='1') {  
                                                            ?>
                                                            <button type="button" class="btn btn-success bg-gradient waves-effect waves-light" style="padding:5px;float:right;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" id="order_status" onclick="add_order_status('<?php echo $order_status_type; ?>')" ><i class="ri-add-line align-bottom me-1"></i>Record Retired</button>
                                                            <?php
                                                        }  
//                                                        $status=1;
                                                        if ($status=='1') {
                                                            ?>
                                                            <button type="button" class="btn btn-info btn-sm " onclick="change_status();" style="padding:5px;float:left;" 
                                                                    ><i class="ri-add-line align-bottom me-1"></i> Change Status
                                                            </button> 
                                                            <?php
                                                        }
                                                        ?>
                                                        <div class="btn-group" role="group">
                                                            <div class="dropdown topbar-head-dropdown ms-1 header-item" style="height:40px;" id="notificationDropdown">
                                                                <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" id="page-header-notifications-dropdown" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-haspopup="true" aria-expanded="false">
                                                                    <i class="ri-filter-2-line"></i>

                                                                </button>
                                                                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0" aria-labelledby="page-header-notifications-dropdown" style="">
                                                                    <div class="dropdown-head bg-primary bg-pattern rounded-top">
                                                                        <div class="px-2 pt-2">
                                                                            <ul class="nav nav-tabs dropdown-tabs nav-tabs-custom" data-dropdown-tabs="true" id="notificationItemsTab" role="tablist">
                                                                                <li class="nav-item waves-effect waves-light" role="presentation">
                                                                                    <a class="nav-link active" data-bs-toggle="tab" href="#all-noti-tab" role="tab" aria-selected="true">
                                                                                        All Columns
                                                                                    </a>
                                                                                </li>
                                                                            </ul>
                                                                        </div>
                                                                    </div>

                                                                    <div class="tab-content position-relative" id="notificationItemsTabContent">
                                                                        <div class="tab-pane fade py-2 ps-2 active show" id="all-noti-tab" role="tabpanel">
                                                                            <div class="notification-actions">
                                                                                <div class="d-flex text-muted justify-content-center dropdown-menu2">

                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </h5>
                                        </div>
                                        
                                        <div class="modal fade exampleModalFullscreen2"
                                             data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" 
                                             aria-hidden="true"
                                             >
                                            <div class="modal-dialog modal-fullscreen" >
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="exampleModalLabel"><?php if($title=='User') { echo 'User Details'; }elseif($title == 'Tutorials'){echo $title.' replies';} else { ?><?php echo $title.' Details'; } ?> </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>    
                                                    <div class="modal-body" style="overflow-x: hidden;">
                                                        
                                                        <form class="tablelist-form" enctype="multipart/form-data" method="post" id="add-extra-form"  autocomplete="off">
                                                            <input type="hidden" name="extra_id" id="extra_id" value="" />
                                                            <?php
                                                            $k = 0;
                                                            $l = 0;
                                                            foreach ($perameters11 as $perameters_res) {
                                                                $k++;
                                                                if ($perameters_res['type'] != 'hidden') {
                                                                    $l++;
                                                                }
                                                                ?>
                                                                <div class="extra_div_css <?php if ($perameters_res['type'] == 'hidden') { ?> div_extra_<?php echo $perameters_res['id']; ?> <?php } ?> <?php
                                                                if ($perameters_res['div_width'] != '') {
                                                                    echo $perameters_res['div_width'];
                                                                } else {
                                                                    echo 'col-md-12';
                                                                }
                                                                ?>  pull-left" style="<?php
                                                                     if ($perameters_res['styles'] != '') {
                                                                         echo $perameters_res['styles'];
                                                                     }
                                                                     if ($perameters_res['type'] == 'hidden') {
                                                                         echo 'display:none;';
                                                                     }
                                                                     ?>float:left;<?php if($perameters_res['type']=='hr') { echo 'height:20px;'; } ?>" >
                                                                     <?php
//                                                                     echo $perameters_res['type'];
//                                                                        echo '<pre>';
//                                                                        print_r($perameters_res);
//                                                                        echo '</pre>';
                                                                     if ($perameters_res['type'] == 'file' || $perameters_res['type'] == 'email' || $perameters_res['type'] == 'number' || $perameters_res['type'] == 'text' || $perameters_res['type'] == 'hidden') {
                                                                         ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                                                                        <input  <?php
                                                                        if ($perameters_res['sort'] == '1') {
                                                                            echo 'focus';
                                                                        }
                                                                        ?> tabindex="<?php echo $k; ?>" <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?> <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                                                                            type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                                                                            name="<?php echo $perameters_res['name']; ?>" 
                                                                            class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                                                            <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                            <?php } ?> />
                                                                            <?php
                                                                        } else if ($perameters_res['type'] == 'textarea') {
                                                                            ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                                                                        <textarea tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>"
                                                                                  id="<?php echo $perameters_res['id']; ?>" 
                                                                                  name="<?php echo $perameters_res['name']; ?>" 
                                                                                  class="form-control" style="<?php echo $perameters_res['styles']; ?>" <?php echo $perameters_res['mandatory']; ?> 
                                                                                  <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                                  <?php } ?>><?php echo $perameters_res['data']; ?></textarea>
                                                                                  <?php
                                                                              } else if ($perameters_res['type'] == 'blankdiv') {
                                                                                  ?>
                                                                        <div class="col-md-12" style='clear:both;'></div>

                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'cotable') {
                                                                        ?>
                                                                        <div class="col-md-12 ">
                                                                            <table id="scroll-vertical" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table" style="width:100%;">
                                                                                <thead class="text-muted table-light">
                                                                                    <tr style="font-size:11px;">
                                                                                        <th>Lot No</th>
                                                                                        <th>Shape</th>
                                                                                        <th>Size</th>
                                                                                        <th>Color</th>
                                                                                        <th>Clarity</th>
                                                                                        <th>Category</th>
                                                                                        <th>PCS</th>
                                                                                        <th>Qty</th>
                                                                                        <th>Amt INR</th>
                                                                                        <th>Amt USD</th>
                                                                                        <th>Desc.</th>
                                                                                        <th>#</th>
                                                                                    </tr>
                                                                                </thead>
                                                                                <tbody id="add_data_res" style="font-size:10px;">

                                                                                </tbody>
                                                                                <tbody id="edit_data_res" style="font-size:11px;">
                                                                                    <tr>
                                                                                        <td colspan="12">
                                                                                            <a class="btn btn-sm btn-primary" onclick='$(".first_data").toggle(), $("#second_data").toggle();' style="float:right;">Add Item</a>    

                                                                                        </td>
                                                                                    </tr>
                                                                                </tbody>
                                                                            </table>
                                                                        </div>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'date') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                        <input tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                                                                               type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                                                                               name="<?php echo $perameters_res['name']; ?>" 
                                                                               class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                                                               <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                               <?php } ?> />
                                                                               <?php
                                                                           } else if ($perameters_res['type'] == 'customdiv') {
                                                                               ?>
                                                                               <?php
                                                                               echo $perameters_res['data'];
                                                                               ?>
                                                                               <?php
                                                                           } else if ($perameters_res['type'] == 'hr') {
                                                                               ?>
                                                                        <hr/>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'radio' || $perameters_res['type'] == 'checkbox') {
                                                                        ?>
                                                                        <div class="form-check mb-2">
                                                                            <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                            <br>
                                                                            <?php
                                                                            foreach ($perameters_res['data'] as $select_data) {
                                                                                ?>
                                                                                <div style="width:50%;float:left;">
                                                                                    <input  <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  class="form-check-input" type="<?php echo $perameters_res['type']; ?>" 
                                                                                                                                                                                       id="<?php echo $perameters_res['id']; ?>" name="<?php echo $perameters_res['name']; ?>" 
                                                                                                                                                                                       class="form-control" value="<?php echo $select_data['id']; ?>" 
                                                                                                                                                                                       <?php echo $perameters_res['mandatory']; ?> 
                                                                                                                                                                                       >
                                                                                    <span><?php echo $select_data['name']; ?></span>
                                                                                </div>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    else if ($perameters_res['type'] == 'select2') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                        <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['multiple']; ?> <?php echo $perameters_res['multiple']; ?>  <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2"  name="<?php echo $perameters_res['name']; ?><?php
                                                                        if ($perameters_res['multiple'] == 'multiple') {
                                                                            echo '[]';
                                                                        }
                                                                        ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                                                                            <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                                                                            <?php
                                                                            foreach ($perameters_res['data'] as $select_data) {
                                                                                ?>
                                                                                <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'hr') {
                                                                        
                                                                        ?>
                                                                        <hr/>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'cleardiv') {
                                                                        ?>
                                                                        <div style="clear:both  !important;width:100% !important;"></div>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'select3') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                        <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['multiple']; ?>  <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2" name="<?php echo $perameters_res['name']; ?><?php
                                                                        if ($perameters_res['multiple'] == 'multiple') {
                                                                            echo '[]';
                                                                        }
                                                                        ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                                                                            <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                                                                            <?php
                                                                            foreach ($perameters_res['data'] as $select_data) {
                                                                                ?>
                                                                                <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'select4') {
                                                                        $width1 = "width:100% !important;";
                                                                        $width2 = "width:0%; !important";
                                                                        $width_3 = "0";

                                                                        if ($perameters_res['classname'] == 'col-md-3') {
                                                                            $ext_width = 95;
                                                                            $ext_width2 = 5;
                                                                        }
                                                                        if ($perameters_res['classname'] == 'col-md-2') {
                                                                            $ext_width = 90;
                                                                            $ext_width2 = 10;
                                                                        }
                                                                        if ($perameters_res['add_btn'] == '1') {
                                                                            $width1 = "width:" . $ext_width . "% !important;float:left !important;";
                                                                            $width2 = "width:" . $ext_width2 . "% !important;float:left !important;";
                                                                            $width_3 = "15";
                                                                        }
//                                                                        echo '<pre>';
//                                                                        print_r($perameters_res['data']);  
//                                                                        echo '</pre>';
                                                                        ?>
                                                                        <div style="<?php echo $width1; ?>">
                                                                            <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                            <select tabindex="<?php echo $k; ?>"  <?php echo $perameters_res['multiple']; ?>  style="<?php echo $width1; ?>"  data-select-id="<?php echo $perameters_res['name']; ?>"   <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select4" name="<?php echo $perameters_res['name']; ?><?php
                                                                            if ($perameters_res['multiple'] == 'multiple') {
                                                                                echo '[]';
                                                                            }
                                                                            ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                                                                                <option value="" disabled>--Suggested--</option>
                                                                                <?php
                                                                                foreach ($perameters_res['data'] as $select_data) {
                                                                                    ?>
                                                                                    <option <?php echo $select_data['disabled']; ?> value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                                                                    <?php
                                                                                }
                                                                                if ($perameters_res['tags'] == 1) {
                                                                                    ?>
                                                                                    <option value="add">Add New</option>
                                                                                    <?php
                                                                                }
                                                                                ?>
                                                                            </select>
                                                                        </div>
                                                                        <?php
                                                                        if ($width_3 > 0) {
                                                                            ?>
                                                                            <div style="<?php echo $width2; ?>">
                                                                                <a target="_blanks" href="<?php echo base_url() . '' . $perameters_res['add_btn_handle'] . '?add=1'; ?>" style="width: 100%;float:left !important;margin-top: 25px;padding:3px 0px;" 
                                                                                   class=''>
                                                                                    <i class="bx bx-pencil"></i>
                                                                                </a>
                                                                            </div>
                                                                            <?php
                                                                        }
                                                                        ?>
                                                                        <div class="hide_details" id="<?php echo $perameters_res['name'] . '_details'; ?>"><?php echo $perameters_res['tags']; ?></div>
                                                                        <div class="col-md-12" id="<?php echo $perameters_res['name'] . '_custom_message'; ?>">

                                                                        </div>
                                                                        <?php
                                                                    }
                                                                    else if ($perameters_res['type'] == 'datalist') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                        <input type="text" tabindex="<?php echo $k; ?>"  <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  
                                                                            <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                                                                            class="my-select2 form-control" 
                                                                            name="<?php echo $perameters_res['name']; ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">

                                                                        
                                                                        <?php
                                                                    }
                                                                    else if ($perameters_res['type'] == 'select') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                                                                        <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                                                                                name="<?php echo $perameters_res['name']; ?><?php
                                                                                if ($perameters_res['multiple'] == 'multiple') {
                                                                                    echo '[]';
                                                                                }
                                                                                ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                                                                            <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                                                                            <?php
                                                                            foreach ($perameters_res['data'] as $select_data) {
                                                                                ?>
                                                                                <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                                                                <?php
                                                                            }
                                                                            ?>
                                                                        </select>
                                                                        <?php
                                                                    } else if ($perameters_res['type'] == 'div') {
                                                                        echo $perameters_res['data'];
                                                                    } else if ($perameters_res['type'] == 'image_div') {
//                                                    echo '1';    
                                                                        $image_upload = $this->load->view('extra/load_image_upload_design', $data, true);
                                                                        echo $image_upload;
                                                                    } else if ($perameters_res['label'] != '' && $perameters_res['id'] == '') {
                                                                        ?>
                                                                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label" style="text-decoration:underline;color:blue;font-weight:bold;"><?php echo $perameters_res['label']; ?></label>
                                                                        <?php
                                                                    }
                                                                    ?>

                                                                </div>
                                                                <?php
                                                            }
                                                            ?>

                                                            <div class="modal-footer" style="width:100%;clear:both;">
                                                                <div class="hstack gap-2 justify-content-end">
                                                                    
                                                                    <button type="submit" class="btn btn-success" id="add-extra-btn">Save Extra <?php echo $title; ?></button>
                                                                </div>   
                                                            </div> 
                                                        </form> 
                                                    </div>    
                                                </div>    
                                            </div>    
                                        </div>    

                                        
                                        <div class="modal fade flip exampleModalFullscreen"
                                             data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" 
                                             aria-hidden="true"
                                             >
                                            <div class="modal-dialog modal-fullscreen" > 
<!--                                        <div class="modal fade exampleModalFullscreen" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">-->
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="exampleModalLabel">Add <?php echo $title; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>  
                                                    <div class="modal-body" style="overflow-x: hidden;">
                                                        <form class="tablelist-form first_data" enctype="multipart/form-data" method="post" id="add-category-form"  autocomplete="off">
                                                            
                                                        </form>  
                                                    </div>
                                                    
                                                    <div class="modal-footer">
                                                        
                                                        
                                                        <button type="button" id="add-btn2" onclick="$('#add-btn').click();" 
                                                                class="btn btn-primary ">Save <?php echo $title; ?></button>
                                                        <button type="button" id="update-btn2" style="display:none;" onclick="$('#update-btn').click();" 
                                                                class="btn btn-primary ">Update <?php echo $title; ?></button> 
                                                        <div class="loading-icon" style="display: none;">
                                                            <!-- Add your loading spinner or icon HTML here -->
                                                            Loading...
                                                        </div>
        

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="modal fade flip exampleModalFullscreen10"
                                             data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" 
                                             aria-hidden="true"
                                             >
                                            <div class="modal-dialog modal-fullscreen" > 
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="exampleModalLabel"> <?php echo $title; if($title == 'Tutorials' ){ echo ' Chat';}else{ echo 'Details'; } ?> </h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>  
                                                    <div class="modal-body" id="dataviwlist" style="overflow-x: hidden;">
                                                        
                                                    </div>
                                                    
                                                    <div class="modal-footer">
<!--                                                        <button type="button" id="add-btn2" onclick="$('#add-btn').click();" 
                                                                class="btn btn-primary ">Save <?php echo $title; ?></button>
                                                        <button type="button" id="update-btn2" style="display:none;" onclick="$('#update-btn').click();" 
                                                                class="btn btn-primary ">Update <?php echo $title; ?></button> 
                                                        <div class="loading-icon" style="display: none;">
                                                             Add your loading spinner or icon HTML here 
                                                            Loading...
                                                        </div>-->
        

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="modal fade flip exampleModalFullscreen1vidwedit"
                                             data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" 
                                             aria-hidden="true"
                                             >
                                            <div class="modal-dialog  modal-xl" > 
<!--                                        <div class="modal fade exampleModalFullscreen" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">-->
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="exampleModalLabel"> <?php echo $title; ?> Details</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>  
                                                    <div class="modal-body" style="overflow-x: hidden;">
                                                        <form method="post" action="?usermanagemenet=1" enctype="multipart/form-data">
                                                        <div class="row g-3">
                            <div class="col-xxl-12">
                                <div>
                                    <label for="firstName" class="form-label">Profile Name</label>
                                    <input type="text"  class="form-control" id="full_name_1" name="full_name_1" required placeholder="Enter Profile Name">
                                    <input type="hidden"  class="form-control" id="userid_1" name="userid_1">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="firstName" class="form-label">User Name</label>
                                    <input type="text" class="form-control" id="username_1" name="username_1" required placeholder="Enter User Name">
                                </div>
                            </div>
                            <div class="col-xxl-12">
                                <div>
                                    <label for="firstName" class="form-label">Password</label>
                                    <input type="password"  class="form-control" id="password_1" name="password_1" required placeholder="Enter Your Password">
                                </div>
                            </div>

                            <div class="col-xxl-12">
                                <div>
                                    <label for="lastName" class="form-label">User Type</label>
                                                                        <select class="form-control" name="usertype_1" id="usertype_1" required >
                                        <option value="">--Select--</option>
                                                                                            <option value="42">CUSTOMER</option> 
                                                                                                <option value="43">STAFF</option> 
                                                                                                <option value="44">ADMIN</option> 
                                                                                    </select> 
                                </div>
                            </div>
                            <!--end col-->
                            <div class="col-xxl-12">
                                <label for="emailInput" class="form-label">Contact Number</label>
                                <input type="number"   class="form-control" id="mobile_no_1" name="mobile_no_1" maxlength="10" required placeholder="Enter your Contact No">
                            </div>
                            <!--end col-->
                            <div class="col-xxl-12">
                                <label for="emailInput" class="form-label">Email ID</label>
                                <input type="email"  class="form-control" id="email_id_1" name="email_id_1" required placeholder="Enter your email" required>
                            </div>

                            <!--end col-->
                            <div class="col-lg-12">
                                <div class="hstack gap-2 justify-content-end">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                    <button type="submit" class="btn btn-primary">Submit</button>
                                </div>
                            </div>
                            <!--end col-->
                        </div>
                                                        </form>
                                                    </div>  
                                                </div>
                                            </div>
                                        </div>
                                        
                                        
                                        <div class="modal fade flip exampleModalFullscreen4"
                                             data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" role="dialog" aria-labelledby="staticBackdropLabel" 
                                             aria-hidden="true"
                                             >
                                            <div class="modal-dialog modal-lg" > 
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="extra_model_box_title"></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>  
                                                    <div class="modal-body" style="overflow-x: hidden;">
                                                        <form class="tablelist-form extra_model_box_title_box" enctype="multipart/form-data" method="post" autocomplete="off">
                                                            
                                                        </form>  
                                                    </div>
                                                    
                                                    
                                                </div>
                                            </div>
                                        </div>
                                        
                                        

                                        <div class="offcanvas offcanvas-start"  id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                                            <div class="offcanvas-header" style="padding:10px;color:white;background-color: darkseagreen;;">
                                                <h5 class="offcanvas-title" style="color:white;" id="offcanvasExampleLabel" id="left_bar_title"></h5>
                                                
                                            </div>
                                            <div class="offcanvas-body" style="padding:10px;background-color:white;;" id="left_bar_body">
                                                Please wait....
                                            </div>
                                        </div>
                                        
                                        <div class="offcanvas offcanvas-end border-0" tabindex="-1" id="theme-settings-offcanvas" style="width: 100%;">
                                            
                                            <div class="d-flex align-items-center bg-primary bg-gradient p-3 offcanvas-header">
                                                <h5 class="m-0 me-2 text-white" id="model_extra_datas">
                                                </h5>
                                                <button type="button" class="btn-close btn-close-white ms-auto" id="customizerclose-btn" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                                            </div>
                                            <form class="tablelist-form" enctype="multipart/form-data" method="post" 
                                                  id="add-master-extra-form"  autocomplete="off">
                                            <div class="offcanvas-body p-10" id="model_extra_datas_res">  
                                                

                                            </div>
                                            <div class="offcanvas-footer border-top p-3 text-center">
                                                <div class="row">
                                                    <div class="col-10">
                                                        
                                                    </div>    
                                                    <div class="col-2">
                                                        <button type="submit" class="btn btn-primary w-100" >Save</button>
                                                    </div>
                                                </div>
                                            </div>
                                            </form>      
                                        </div>
                                        
                                        <div style="display:none;"> 
                                        <button type="button" id="success_toast_bar" data-toast="" data-toast-text="Welcome Back ! This is a Toast Notification" data-toast-gravity="top" data-toast-position="right" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">Bottom Center</button>    
                                        <button type="button" id="error_toast_bar"  data-toast="" data-toast-text="Error ! An error occurred." data-toast-gravity="top" data-toast-position="right" data-toast-classname="danger" data-toast-duration="3000" class="btn btn-light w-xs">Error</button>
<!--                                        <button type="button" id="fail_toast_bar" data-toast="" 
                                                data-toast-text="Please wait..." data-toast-position="right" data-toast-duration="3000" data-toast-close="close" class="btn btn-light w-xs">Click Me</button>-->
                                        </div>  
                                        
                                        <div class="card-body">
                                        <div class="table-responsive table-car">
                                            <table id="ajax_datatables2" class="table align-middle table-hover table-nowrap table-striped-columns mb-0">
                                                <thead class="table-light">
                                                    <tr>
                                                        <!------////bhairu------>
                                                        <th <?php
                                                        if ($add_extra_btn == '1') {
                                                            echo 'style="width:19%;"';
                                                        }
                                                        ?> >Action</th> 
                                                            <?php
                                                             foreach ($display2 as $display_res) {
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
                                    </div>
                                </div><!--end col-->
                            </div><!--end row-->

                        </div>
                        <!-- container-fluid -->
                    </div>
                    <!-- End Page-content -->

                    <?php
                    }
                    else
                    {
                    ?>
                    <div class="page-content">
                        <div class="container-fluid">
                           <!-- start page title -->
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Code Bright Dashboard</h4>

                                <div class="page-title-right">
                                    
                                </div>

                            </div>
                        </div>
                    </div>
                    <!-- end page title -->
<!--
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="card crm-widget">
                                <div class="card-body p-0">
                                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                        <div class="col">
                                            <div class="px-3">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Vendors <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="las la-rocket fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from users where type='1'")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Customers <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-exchange-dollar-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from users where type='2'")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Staff <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-pulse-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from users where type='4'")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        
                                    </div> end row 
                                </div> end card body 
                            </div> end card 
                        </div> end col 
                    </div> end row -->
<!--                    
                    <div class="row" style="margin-top:10px;">
                        <div class="col-xl-12">
                            <div class="card crm-widget">
                                <div class="card-body p-0">
                                    <div class="row row-cols-xxl-5 row-cols-md-3 row-cols-1 g-0">
                                        <div class="col">
                                            <div class="px-3">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Styles <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="las la-rocket fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from styles where 1")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Orders <i class="ri-arrow-up-circle-line text-success fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-exchange-dollar-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from orders where 1")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Active Orders <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-pulse-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from orders where final_status IS NULL")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Archive Orders <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-pulse-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from orders where final_status='Completed'")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        
                                        <div class="col">
                                            <div class="mt-3 mt-md-0 px-3" style="padding-top:5px;padding-bottom:5px;">
                                                <h5 class="text-muted text-uppercase fs-13 mb-3">Inventory <i class="ri-arrow-down-circle-line text-danger fs-18 float-end align-middle"></i></h5>
                                                <div class="d-flex align-items-center">
                                                    <div class="flex-shrink-0">
                                                        <i class="ri-pulse-line fs-3 text-muted"></i>
                                                    </div>
                                                    <div class="flex-grow-1 ms-3">
                                                        <?php
//                                                        $count1=$this->db->query("select count(*) as cnt from inventory where 1")->row_array();
                                                        ?>
                                                        
                                                        <h4 class="mb-0"><span class="counter-value" data-target="<?php echo $count1['cnt']; ?>">
                                                        <?php echo $count1['cnt']; ?>
                                                        </span>
                                                        </h4>
                                                    </div>
                                                </div>
                                            </div>
                                        </div> end col 
                                        
                                    </div> end row 
                                </div> end card body 
                            </div> end card 
                        </div> end col 
                    </div> end row -->

                    
                        </div>    
                    </div>     
                    <?php
//                    echo 'Coming Soon';    
                    }    
//                    $this->load->view('templates/footer');
                    ?>
                    
                </div>
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
            <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.11/index.global.min.js'></script>
            <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/4.2.0/timegrid/main.js"></script> -->
            <div class="modal fade exampleModal_status"  aria-labelledby="exampleModalFullscreenLabel" aria-hidden="true">
                                            <div class="modal-dialog modal-fullscreen">
                                                <div class="modal-content">
                                                    <div class="modal-header bg-light p-3">
                                                        <h5 class="modal-title" id="exampleModalLabel">Change Status <?php echo $title; ?></h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="close-modal"></button>
                                                    </div>  
                                                    <div class="modal-body" style="overflow-x: hidden;">

                                                        <form class="tablelist-form"   enctype="multipart/form-data" method="post" id="update-status-form"  autocomplete="off">
                                                            <input type="hidden" name="main_status_id" id="main_status_id" value="" />
                                                            <div class="form-check mb-2">
                                                                <label>Status</label>
                                                            <select class="form-control" id="main_status" name="main_status">
                                                                <option value="">--Change Status--</option>  
                                                                <?php
                                                                foreach($style_status as $style_status_res)
                                                                {    
                                                                ?>
                                                                <option value="<?php echo $style_status_res['id']; ?>"><?php echo $style_status_res['name']; ?></option>  
                                                                <?php
                                                                }    
                                                                ?>
                                                            </select>
                                                            </div>    
                                                            <div class="form-check mb-2">
                                                                <label>Comments</label>
                                                                <textarea class="form-control" name="main_comments" id="main_comments"></textarea>
                                                            </div>    
                                                            <div class="form-check mb-2" align="center">
                                                                <button class="btn btn-success waves-effect waves-light">Save</button>
                                                            </div>    
                                                        </form>
                                                    </div>    
                                                </div>    
                                            </div>    
            </div>    
            <input type="hidden" id="main_final_url" value="<?php echo base_url(uri_string()); ?>" />
          

            <script>
                
                                                                $(".btn-close").click(function() {     
                                                                    $("div.modal-backdrop").remove();
                                                                });
                
                                                                function calculate_order_wise_price()
                                                                {
                                                                    var totalPrice = 0;

                                                                    // Loop through each input with class 'order_wise_price'
                                                                    $('.order_wise_price').each(function() {
                                                                      // Get the value of each input and convert it to a number
                                                                      var price = parseFloat($(this).val()) || 0;

                                                                      // Add the price to the total
                                                                      totalPrice += price;
                                                                    });

                                                                    // Update the total price in the div
                                                                    $('.total_order_wise_price').val(totalPrice.toFixed(2));
                                                                }
                                                                function select_change_order_status_customers(type='',id='')
                                                                {
                                                                                                    var customers=$("#search_orders").val();
                                                                                                    $(".extra_model_box_title_box").attr("id","select_change_order_status_customers");
//                                                                                                    alert(customers);
                                                                                                    $.ajax({ 
                                                                                                    url: "<?php echo base_url('extra/select_change_order_status_customers'); ?>",
                                                                                                    method: "POST",
                                                                                                    data: {customers:customers,type:type,id:id},
                                                                                                    success: function (data) { 
//                                                                                                    alert(data);
                                                                                                    $("#change_order_status_customer_data").html(data);
                                                                                                    }, 
                                                                                                    complete: function() {
                                //                                                                        $('.my-select4').click();

                                                                                                    }
                                                                                                });
                                                                                                        
                                                                }
                                                                function add_order_status(form_type,id='')
                                                                {
//                                                                    alert(form_type);
//                                                                    alert(id);
                                                                                                if(form_type=='sold')
                                                                                                {    
                                                                                                $("#extra_model_box_title").html("Mark Sold Status");  
                                                                                                }
                                                                                                if(form_type=='repair')
                                                                                                {    
                                                                                                $("#extra_model_box_title").html("Mark Repair Status");  
                                                                                                }
                                                                                                if(form_type=='retired')
                                                                                                {    
                                                                                                $("#extra_model_box_title").html("Mark Retired Status");  
                                                                                                }
                                                                                                if(form_type=='on-hand')
                                                                                                {    
                                                                                                $("#extra_model_box_title").html("Mark Return Status");  
                                                                                                }
//                                                                                                var form_type = 'add';
//                                                                                                var id = '';
                                                                                                var main_final_url = "<?php echo base_url().''; ?>"+name;
                                                                                                $.ajax({
                                                                                                    url: "<?php echo base_url('extra/change_order_status'); ?>",
                                                                                                    method: "POST",
                                                                                                    data: { id: id, form_type: form_type,main_final_url:main_final_url},
                                                                                                    success: function (data) {   
                                                                                                        
                                                                                                        if(id!='')
                                                                                                        {
                                                                                                         $('.exampleModalFullscreen').modal('hide');
                                                                                                        }
                                                                                                        $(".extra_model_box_title_box").html(data);
                                                                                                        $('.my-select4').each(function () {
                                                                                                        initializeSelectize($(this));
                                                                                                        });
                                                                                                    }, 
                                                                                                    complete: function() {
                                //                                                                        $('.my-select4').click();

                                                                                                    }
                                                                                                });
                                                                }
                                                                
                                                                
                                                               

                                                                function select_change_order_status_customers_send()
                                                                { 
                                                                    var form = document.getElementById('select_change_order_status_customers');
                                                                    var formData = new FormData(form);
                                                                    $.ajax({
                                                                        url: "<?php echo base_url($master . '/add_order_data'); ?>",
                                                                        type: "POST",
                                                                        data: formData,
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        beforeSend: function ()
                                                                        {
//                                                                        alert('1');    
                                                                        $('.loading-icon').show();
                                                                        },
                                                                        success: function (data)
                                                                        {
//                                                                          alert('2');  
//                                                                           alert(data);
                                                                           
                                                                           var category = JSON.parse(data);
                                                                           
                                                                           if (category.success == true)
                                                                            {
                                                                                    var dataTable = $('#ajax_datatables2').DataTable();
                                                                                    dataTable.draw();
                                                                                    $("#success_toast_bar").attr("data-toast-text", "Thanks, "+category.message);
                                                                                    $("#success_toast_bar").click();     
                                                                                    $('.exampleModalFullscreen').modal('hide');
                                                                                    $('.exampleModalFullscreen2').modal('hide');
                                                                                    $('.exampleModalFullscreen3').modal('hide');
                                                                                    $(".exampleModalFullscreen4").modal('hide');
                                                                            }    
                                                                        },
                                                                        error: function (e)
                                                                        {
                                                                            $('.loading-icon').hide();
                                                                            $("#error_toast_bar").attr("data-toast-text", "Oops, Something Went Wrong!");
                                                                            $("#error_toast_bar").click();     
                                                                            
                                                                        }
                                                                    });
                                                                }
                                                                
                                                                
                                                                
                                                                
                                                                function set_sub_pages(name)
                                                                {
                                                                                                $("#model_extra_datas").html("Add "+name);
                                                                                                var form_type = 'add';
                                                                                                var id = '';
                                //                                                                var handle = $("#url_handle").val();
                                                                                                var main_final_url = "<?php echo base_url().''; ?>"+name;
                                //                                                                alert(name);              
                                                                                                $.ajax({
                                                                                                    url: "<?php echo base_url('mainmaster/index'); ?>",
                                                                                                    method: "POST",
                                                                                                    data: { id: id, form_type: name, handle: name,main_final_url:main_final_url},
                                                                                                    success: function (data) {  
                                                                                                        $("#model_extra_datas_res").html(data);
                                //                                                                        $('.my-select4').each(function () {
                                //                                                                        initializeSelectize($(this));
                                //                                                                        });
                                                                                                    }, 
                                                                                                    complete: function() {
                                //                                                                        $('.my-select4').click();

                                                                                                    }
                                                                                                });
                                                                }
                                                                function total_weight_calculation() {
                                                                var sum = 0;

                                                                $('.no_of_rate').each(function (index) {
                                                                    var rate = parseFloat($(this).val());
                                                                    var Weight = parseFloat($('.all_weight').eq(index).val());
                                                                    var no_of_peaces = parseFloat($('.no_of_peaces').eq(index).val());

                                                                    var avg_weight = (Weight / no_of_peaces).toFixed(2);
                                                                    sum += parseFloat(Weight);

                                                                    var cost = (rate * Weight).toFixed(0);
                                                                    $('.no_of_cost').eq(index).val(cost);
                                                                    $('.avg_weight').eq(index).val(avg_weight);
                                                                });

                                                                var total_diamond_weight = $('.diamond_weight').get().reduce((sum, el) => sum + parseFloat(el.value), 0).toFixed(2);
                                                                $(".total_diamond_weight").val(total_diamond_weight);

                                                                var total_diamond_cost = $('.diamond_cost').get().reduce((sum, el) => sum + parseInt(el.value), 0).toFixed(0);
                                                                $(".total_diamond_cost").val(total_diamond_cost);

                                                                var total_gemstone_weight = $('.gemstone_weight').get().reduce((sum, el) => sum + parseFloat(el.value), 0).toFixed(2);
                                                                $(".total_gemstone_weight").val(total_gemstone_weight);

                                                                var total_gemstone_cost = $('.gemstone_cost').get().reduce((sum, el) => sum + parseFloat(el.value), 0).toFixed(0);
                                                                $(".total_gemstone_cost").val(total_gemstone_cost);

                                                                var total_pearls_weight = $('.pearls_weight').get().reduce((sum, el) => sum + parseFloat(el.value), 0).toFixed(2);
                                                                $(".total_pearls_weight").val(total_pearls_weight);

                                                                var total_pearls_cost = $('.pearls_cost').get().reduce((sum, el) => sum + parseFloat(el.value), 0).toFixed(0);
                                                                $(".total_pearls_cost").val(total_pearls_cost);

                                                                var sum2 = parseFloat(sum) / 5;
                                                                $('.total_weight').val(parseFloat(sum).toFixed(2));
                                                                $('.total_weight_gm').val(sum2);

                                                                var gross_weight = $('.gross_weight').val();
                                                                if (gross_weight > 0) {
                                                                    var total_net_weight = parseFloat(gross_weight) - sum2;
                                                                    $(".total_net_weight").val(total_net_weight.toFixed(2));
                                                                    $(".total_peaces_no").val(total_pearls_cost);
                                                                }

                                                                var total_net_weight = $(".total_net_weight").val();
                                                                var metal_rate = $(".metal_rate").val();
                                                                var metal_cost = (parseFloat(total_net_weight) * parseFloat(metal_rate)).toFixed(0);
                                                                $(".metal_cost").val(metal_cost);

                                                                var gross_weight = $(".gross_weight").val();
                                                                var value_added_rate = $(".value_added_rate").val();
                                                                var wastage_percentage = $(".wastage_percentage").val();
                                                                var final_value_added_total = (gross_weight * value_added_rate) + ((wastage_percentage / 100) * total_net_weight * metal_rate);
//                                                                $(".value_added_total").val(final_value_added_total);
                                                                $(".value_added_total").val(final_value_added_total.toFixed(0));
                                                                var metal_cost = parseFloat(metal_cost);  
                                                                var total_diamond_cost = $(".total_diamond_cost").val();
                                                                var total_gemstone_cost = $(".total_gemstone_cost").val();
                                                                var total_pearls_cost = $(".total_pearls_cost").val();
                                                                var final_value_added_total2 = metal_cost + final_value_added_total + parseFloat(total_diamond_cost) + parseFloat(total_gemstone_cost) + parseFloat(total_pearls_cost);
                                                                $(".new_replacement_cost, .total_item_cost").val(final_value_added_total2);
                                                            }

                                                                
                                                                
                                                                var selectizeInstances = [];

                                                                function initializeSelectize(selectElement) {
                                                                    var selectId = selectElement.data('select-id');

                                                                    var selectizedata = {
                                                                        load: function (query, callback) {
                                                                            if (!query.length) {
                                                                                // If no query, load default data based on most searched
                                                                                // getDefaultData(callback);
                                                                            } else {
                                                                                // If there is a query, perform the regular search
                                                                                $.ajax({
                                                                                    url: '<?php echo base_url(); ?>extra/loadpagewisesearch',
                                                                                    dataType: 'json',
                                                                                    data: {
                                                                                        search_datas: '1',
                                                                                        search: query,
                                                                                        selectId: selectId,
                                                                                        category_id: $("#category").val(),
                                                                                        course_type_id: $("#course_type_id").val()
                                                                                    },
                                                                                    success: function (data) {
                                                                                        callback(data.map(function (item) {
                                                                                            return {
                                                                                                value: item.value,
                                                                                                text: item.text
                                                                                            };
                                                                                        }));
                                                                                        exit
                                                                                    }
                                                                                });
                                                                            }
                                                                        },
                                                                        positionDropdown: 'below'
                                                                    };

                                                                    var instance = selectElement.selectize(selectizedata)[0];
                                                                    selectizeInstances.push(instance);
                                                                }

                                                                function reinitializeSelectize() {
                                                                    selectizeInstances.forEach(function (instance) {
                                                                        if (instance && typeof instance.destroy === 'function') {
                                                                            instance.destroy(); 
                                                                            initializeSelectize($(instance.$input[0]));
                                                                        }
                                                                    });
                                                                }
                                                                
                                                                $(document).ready(function () {
                                                                    initializeSelectize($('.my-select4'));

                                                                    // Event delegation to capture changes on any .my-select4 element
                                                                    $(document).on('.my-select4', function () {
                                                                        initializeSelectize($(this));
                                                                    });
                                                                    
                                                                });
                                                                
                                                                function add_form(forms='') {
                                                                    
                                                                if (forms == '') {
                                                                    $(".first_data").removeAttr("id");
                                                                    $('.first_data').attr('id', 'add-category-form');
                                                                } else {
                                                                    $(".first_data").removeAttr("id");
                                                                    $('.first_data').attr('id', 'edit-category-form');
                                                                }

                                                                var id = $("#id").val();
                                                                var form_type = 'add';
                                                                var handle = $("#url_handle").val();
                                                                
                                                                if(handle=='sold-inventory')
                                                                {                                                                
                                                                $('.exampleModalFullscreen4').modal('show');    
                                                                add_order_status('sold',forms);   
                                                                }
                                                                else
                                                                {
                                                                var main_final_url = $("#main_final_url").val();
                                                                var img = '<img src="<?php echo base_url().'loadingimg.gif'; ?>"  />';
                                                                $(".first_data").html(img);
                                                                $.ajax({
                                                                    url: "<?php echo base_url($master . '/index'); ?>",
                                                                    method: "POST",
                                                                    data: { id: id, form_type: form_type, handle: handle, main_final_url: main_final_url },
                                                                    success: function (data) {
                                                                       
                                                                        $(".first_data").html(data);
                                                                        
                                                                     
                                                                        $('.my-select4').each(function () {
                                                                        initializeSelectize($(this));
                                                                        });
                                                                        if (forms != '') {
                                                                            editrecord(forms);
                                                                        }
                                                                     
                                                                    }, 
                                                                    complete: function() {
                                                                                                                      
                                                                    }
                                                                });
                                                                } 
                                                              
                                                            }
                                                            
                                                            
    //change tutorial status
    function closeTutorial(id){
        if(confirm("Are you sure you want to close this tutorial ?")){
            $.ajax({
                url: 'Extra/updateTutorialStatus',
                method: "POST",
                data: { tutorial_id: id },
                success: function (data) {
                    location.reload();
                    
                }, 
                complete: function() {
                                                                    
                }
            });
        }
        else{
            return false;
        }
    }                                                        
                                                         
                                                            
    function view_data_form(id) {
    var handle = $("#url_handle").val();
    var main_final_url = $("#main_final_url").val();
    var currentUrl = '<?php echo $url_handle; ?>';
    var table= '<?php echo $table; ?>';
    // Uncomment or define 'img' if necessary
    // var img = '<img src="<?php echo base_url().'loadingimg.gif'; ?>" />';
    // $(".first_data").html(img);
    $.ajax({
        url: "<?php echo base_url(); ?>extra/viewformdata",
        method: "POST",
        data: { 
            id: id,
            handle: handle, 
            main_final_url: main_final_url,
            currentUrl: currentUrl, 
            tablename:table 
        },
        success: function(data) {
            $("#dataviwlist").html(data);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}
                                                            
           function table_pop(id) {
    var handle = $("#url_handle").val();
    
    var main_final_url = $("#main_final_url").val();
    var currentUrl = '<?php echo $url_handle; ?>';
    var table= '<?php echo $table; ?>';
//    alert(table);
    // Uncomment or define 'img' if necessary
    // var img = '<img src="<?php echo base_url().'loadingimg.gif'; ?>" />';
    // $(".first_data").html(img);
    $.ajax({
        url: "<?php echo base_url(); ?>extra/viewformdata",
        method: "POST",
        data: { 
            id: id,
            handle: handle, 
            main_final_url: main_final_url,
            currentUrl: currentUrl, 
            tablename:table 
        },
        success: function(data) {
            $("#dataviwlist").html(data);
        },
        error: function(xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

                                                            
                                                            
                                                            
                                                            
                                                                function loadorderdetails(type,value,id,handle)
                                                                {
                                                                if(typeof id === 'undefined' || id=='' || id=='undefined')
                                                                {    
                                                                var id=$("#id").val();
                                                                }
                                                                var handle=$("#url_handle").val();
                                                                if(handle=='orders')
                                                                {
                                                                var id=$("#styles").val();    
                                                                } 
                                                                
//                                                                if(handle=='orders' && id!='')
//                                                                {
//                                                                call_set_images(id);   
//                                                                }
                                                                $.ajax({
                                                                url:"<?php echo base_url(); ?>extra/loadorderdetails",
                                                                method:"POST",
                                                                data:{handle:handle,type:type,value:value,id:id},
                                                                success:function(data)
                                                                {
//                                                                        alert(data);
//                                                                        alert(handle);
                                                                        var data_exp= data.split('||||');
                                                                        if(handle=='orders' || handle=='inventory'  || handle=='archive-orders') 
                                                                        {    
                                                                        var response=JSON.parse(data_exp[1]); 
                                                                        
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
                                                                        $("#ratail_price_int").val(response.ratail_price_int);
                                                                        $("#ratail_price_ind").val(response.ratail_price_ind);
                                                                        $("#notes").val(response.notes);  
//                                                                        alert(response['gemstones']);
//                                                                        alert(response['diamonds']);
//                                                                        alert(response['pearls']);
//                                                                        alert(response['dimensions']);   
                                                                        if(response['diamonds']=='' || response['diamonds']=='null' || response['diamonds']==null)
                                                                        {
                                                                        $(".diamonds_customdiv").remove();
                                                                        }    
                                                                        else
                                                                        {
                                                                        $(".diamonds_customdiv").removeClass("is-hidden");
                                                                        }
                                                                        
                                                                        if(response['gemstones']=='' || response['gemstones']=='null' || response['gemstones']==null)
                                                                        {
                                                                        $(".gemstones_customdiv").remove();  
                                                                        }
                                                                        else
                                                                        {
                                                                        $(".gemstones_customdiv").removeClass("is-hidden");
                                                                        }
                                                                        
                                                                        if(response['pearls']=='' || response['pearls']=='null' || response['pearls']==null)
                                                                        {
                                                                        $(".pearls_customdiv").remove();    
                                                                        }
                                                                        else
                                                                        {
                                                                        $(".pearls_customdiv").removeClass("is-hidden");
                                                                        }
                                                                        
                                                                        if(response['dimensions']=='' || response['dimensions']=='null' || response['dimensions']==null)
                                                                        {
                                                                        $(".dimensions_customdiv").remove();   
                                                                        }
                                                                        else
                                                                        {
                                                                        $(".dimensions_customdiv").removeClass("is-hidden");
                                                                        }
                                                                        
                                                                        
//                                                                        alert(response['diamonds']);
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
                                                                        // comment by dk $(".upload__img-wrap").html(data_exp[10]);
                                                                        if(handle=='add-new-style')
                                                                        { 
//                                                                        alert(data_exp[11]);    
                                                                        $("#comment_history_box").html(data_exp[11]);
                                                                        call_set_images(handle);   
                                                                        }
                                                                        else if(handle=='orders')
                                                                        { 
                                                                        call_set_images('edit');   
                                                                        }  
                                                                        else if(handle=='archive-orders')
                                                                        { 
                                                                        call_set_images('edit');   
                                                                        }  
                                                                        else if(handle=='inventory')
                                                                        { 
                                                                        call_set_images('edit');   
                                                                        }  
                                                                        
                                                                        
                                                                        
                                                                        
                                                                        // comment by dk $("#upload_file0").css("display","block");
                                                                }
                                                                });
                                                            }            
                                                                function load_record_data(type,value,default_id)
                                                                { 
    //                                                                alert(type+"-"+value+"-"+default_id);
                                                                    var id=$("#id").val();    
    //                                                                var styles=$("#styles").val();    
    //                                                                alert(styles);
                                                                    if(type=='edit_users')
                                                                    {
                                                                    var url="<?php echo base_url(); ?>extra/userpageaccess";    
                                                                    }
                                                                    else
                                                                    {
                                                                    var url="<?php echo base_url(); ?>extra/loadpagewisesearch";    
                                                                    }  
    //                                                                var handle = $('#url_handle').val();
    //                                                                alert(handle);
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
                                                                                // Extract unique options from responses
                                                                                var uniqueOptions = responses.filter(function (response) {
                                                                                    return !selectizeInstance.options[response.text];
                                                                                });

                                                                                // Add all unique options in one go
                                                                                selectizeInstance.addOption(uniqueOptions);

                                                                                // Add all values to the Selectize instance
                                                                                var valuesToAdd = uniqueOptions.map(function (option) {
                                                                                    return option.value;
                                                                                });
                                                                                selectizeInstance.addItems(valuesToAdd);

                                                                                // Reload the dropdown to reflect the changes
                                                                                selectizeInstance.refreshItems();

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

                                                                function load_pure_metal_weight(type, value) {
//                                                                    alert('1');  
                                                                    var id = $("#id").val();
                                                                    var metal_cost = $("#metal_cost").val();
                                                                    var value_added_total = $("#value_added_total").val();
                                                                    var final_total_diamond_cost = $(".final_total_diamond_cost").html();
                                                                    var final_total_gemstone_cost = $(".final_total_gemstone_cost").html();
                                                                    var base_url = "<?php echo base_url() ?>"
                                                                    var final_total_pearls_cost = $(".final_total_pearls_cost").html();
                                                                    $.ajax({
                                                                        url: base_url+'extra/load_pure_metal_weight',
                                                                        method: "POST",
                                                                        data: {
                                                                            id: id,
                                                                            type: type,
                                                                            metal_cost: metal_cost,
                                                                            value_added_total: value_added_total,
                                                                            final_total_diamond_cost: final_total_diamond_cost,
                                                                            final_total_gemstone_cost: final_total_gemstone_cost,
                                                                            final_total_pearls_cost: final_total_pearls_cost
                                                                        },
                                                                        success: function(data) {
//                                                                            alert(data);
                                                                            if (type != 'total_item_cost') {
                                                                                $("#pure_metal_weight").val(data);
                                                                                load_pure_metal_weight('total_item_cost', '');
                                                                            } else {
//                                                                                alert('1');
                                                                                $("#replacement_cost").css("width", "100%");
                                                                                $("#replacement_cost").css("float", "left");
                                                                            }  
                                                                        }
                                                                    });
                                                                }

                                                                function load_replacement_cost(id,val)
                                                                {
//                                                                alert(id);    
//                                                                alert(val);  
                                                                   var base_url ="<?php echo base_url() ?>" 
                                                                
                                                                $.ajax({
                                                                        url: base_url+'extra/replacement_cost',
                                                                        method: "POST",
                                                                        data: {
                                                                            id: id,
                                                                            val: val,
                                                                        },
                                                                        success: function(data) {
//                                                                            alert(data);
                                                                            $("#replacement_cost").val(data);
                                                                        }
                                                                    });
                                                                
                                                                
                                                                
                                                                
                                                                }
                                                                function editrecord(datas)
                                                                {

//                                                                    alert(datas); 
                                                                    var datas=$("#edit-records-"+datas).val();
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
//                                                                        alert(handle);
                                                                        var last_haldlerUrl = '<?php echo $url_last_handle; ?>';
                                                                        
//                                                                       
                                                                        if(result2[0] == 'images' && 
                                                                                ( handle=='add-new-style' || handle=='inventory' || handle=='sold-inventory' ||  
                                                                                handle=='orders'  || handle=='sold-inventory' ||  handle=='archive-orders' ||  handle=='inventory'))
                                                                        {   
                                                                           if(handle=='orders')
                                                                           {
                                                                            $("#no_of_peaces").attr("readonly","readonly");    
                                                                           }
                                                                           
                                                                           loadorderdetails(result2[0],result2[1]);   
                                                                           $(".no_of_peaces_data").css("display","none !important");    
                                                                           if(handle=='inventory')
                                                                           {
                                                                           var id=$("#id").val();
                                                                           order_details_extra(id,'direct');
                                                                           }
//                                                                           call_set_images();   
                                                                        }
                                                                        else if(result2[0] == 'certificate' && handle=='inventory')
                                                                        {    
                                                                           var defult_id=$("#id").val();    
                                                                           if(result2[1]!='')
                                                                           {
                                                                           $("#certificate").css("width","100%");
                                                                           $("#certificate").css("float","left");
                                                                           $("#certificate_link").remove();
                                                                           if(result2[1]!='' || result2[1]!=null || result2[1]!='null')
                                                                           {    
                                                                           var certificate_link="<a id='certificate_link' target='_blanks' href='"+result2[1]+"'>View Link</a>";
                                                                           $("#certificate").before(certificate_link);
                                                                           }
                                                                           $("#certificate_link").attr("width","20%");
                                                                           $("#certificate_link").css("float","right");

                                                                           }    
                                                                           order_details_extra(defult_id,"edit_inventory");
                                                                        }       
                                                                              
                                                                        else if(result2[0] == 'pure_metal_weight' && (handle=='inventory'  || handle=='sold-inventory'))
                                                                        { 
                                                                           load_pure_metal_weight(result2[0],result2[1]); 
                                                                        }      
                                                                        else if(result2[0] == 'order_id' && (handle=='inventory'))
                                                                        { 
                                                                           load_replacement_cost(result2[0],result2[1]); 
                                                                        }      
                                                                        else if(result2[0] == 'vendor_id' 
                                                                                || result2[0] == 'vendor'
                                                                                || result2[0] == 'course_type_id'
                                                                                || result2[0] == 'category_id'
                                                                                || result2[0] == 'category'
                                                                                || result2[0] == 'subcategory'
                                                                                || result2[0] == 'metal'
                                                                                || result2[0] == 'metal_finish'
                                                                                || result2[0] == 'shipping_country'
                                                                                || result2[0] == 'billing_country'
                                                                                || result2[0] == 'po_no'
                                                                                || result2[0] == 'edit_users'
                                                                                || result2[0] == 'styles'
                                                                                || result2[0] == 'course'
                                                                                || result2[0] == 'race'
                                                                                || result2[0] == 'dialect'
                                                                                || result2[0] == 'religion'
                                                                                || result2[0] == 'nric'
                                                                                || result2[0] == 'nationlity'
                                                                                || result2[0] == 'state'
                                                                                || result2[0] == 'city'
                                                                                || result2[0] == 'country'
                                                                                || result2[0] == 'class_location'
                                                                                || result2[0] == 'type'
                                                                                || result2[0] == 'age_group_id'
                                                                                || result2[0] == 'course_id'
                                                                                || result2[0] == 'chapter_id'
                                                                                || result2[0] == 'course_id'
                                                                                || result2[0] == 'course_type_id'
                                                                                || result2[0] == 'badge_id'
                                                                                || result2[0] == 'main_quiz_id'
                                                                                || result2[0] == 'parent_id'
                                                                                || result2[0] == 'child_id'
                                                                                || result2[0] == 'quiz_id'
                                                                                || result2[0] == 'user_id'
                                                                                || result2[0] == 'pair_id'
                                                                                || result2[0] == 'select_type'
                                                                                || result2[0] == 'request_status'
                                                                                || result2[0] == 'subject'
                                                                                || result2[0] == 'exercise_id'
                                                                                || result2[0] == 'country_id'
                                                                                || result2[0] == 'state_id'
                                                                                    
                                                                                ) 
                                                                        {  
                                                                            load_record_data(result2[0],result2[1]);
                                                                        }
                                                                        else
                                                                        {

                                                                        if(result2[1]==null 
                                                                                || result2[1]=='null'
                                                                                || result2[1]=='undefined'
                                                                                )
                                                                        {
                                                                        var finalssss="";    
                                                                        }
                                                                        else
                                                                        {
                                                                        var finalssss=result2[1];    
                                                                        }
                                                                        
                                                                        
                                                                        

                                                                        $("#" + result2[0]).val(finalssss);    
                                                                        }      
                                                                    }

                                                                    ImgUpload();  
    //                                                                 reinitializeSelectize();
    //                                                                alert(finals);
                                                                }
                                                                $(document).ready(function () {
                                                                  var table = "<?php echo $table; ?>"
                                                                  var column = [];
                                                                  if(table == 'users'){
                                                                    column = [1,2,3,4,5,6,7,8,9,10,11,12,13]
                                                                    }else if(table == 'child_parent_relationship'){
                                                                        column = [1,2,3,4]
                                                                    }else if(table == 'add_course'){
                                                                        column = [1,2,3]
                                                                    }else if(table == 'event_transaction'){
                                                                        column = [1,2,3,4,5,6]
                                                                    }else if(table == 'user_tutorial_subscription'){
                                                                        column = [1,2,3,4,5,6]
                                                                    }else if(table == 'add_account'){
                                                                        column = [1,2,4,5,6,7]
                                                                    }else if(table == 'courses'){
                                                                        column = [1,2,3,4,5,6,7,8,9,10,12,13]
                                                                    }else if(table == 'course_rating'){
                                                                        column = [1,2,3,4,5,6]
                                                                    }else if(table == 'billing_address'){
                                                                        column = [1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17] 
                                                                    }else if(table == 'tutorial'){
                                                                        column = [1,2,3,4,5,6]
                                                                    }else if(table == 'course_gallery'){
                                                                        column = [1]
                                                                    }else if(table == 'tutorial_credit_transactions'){
                                                                        column = [1,2,3,4,5,6,7,8,9,10,11,12]
                                                                    }else if(table == 'main_quiz'){
                                                                        column = [1,2,3,4,5,6,7]
                                                                    }else if(table == 'child_homework'){
                                                                        column = [1,2,3,4,5,7,8,9,10] 
                                                                    }else if(table == 'complain'){
                                                                        column = [1,2,3]
                                                                    }else if(table == 'contact_us'){
                                                                        column =  [1,2,3,4] 
                                                                    }else if(table == 'events'){
                                                                        column = [1,3,4,5,6,7,8,9,10]
                                                                    }else if(table == 'news'){
                                                                        column = [1,2]
                                                                    }else if(table == 'announcement'){
                                                                        column = [1,2,3]
                                                                    }else if(table == 'religion'){
                                                                        column = [1,2,3]
                                                                    }else if(table == 'nationlity'){
                                                                        column = [1,2,3]
                                                                    }else if(table == 'city'){
                                                                        column = [1,2,3,4,5]
                                                                    }else if(table == 'state'){
                                                                        column = [1,2,3,4]
                                                                    }else {
                                                                        column = [1,2]
                                                                    }
                                                               
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
                                                                                    var final = "";
    //                                                                                var jsonData = {};
                                                                                    
                                                                                    
                                                                                    <?php
                                                                                    foreach ($edits as $edits_res) {
                                                                                    if ($edits_res != '') {
                                                                                    ?>
                                                                                    var final = final + "<?php echo $edits_res; ?>///" + row.<?php echo $edits_res; ?> + "___";
                                                                                            <?php
                                                                                        }
                                                                                    }
                                                                                    ?>  
                                                                                    var final = final + "";
                                                                                    editButton='';
                                                                                    deleteButton='';
                                                                                    order_extra_details ='';
                                                                                    order_qc1 ='';
                                                                                    order_qc2 ='';
                                                                                    complete ='';
                                                                                    completeButton ='';
                                                                                    var main_id_for_edit=row.<?php echo $delete_id; ?>;


                                                                                    <?php
                                                                                    if($edit==1 && $url_handle=='sold-inventory')
                                                                                    {    
                                                                                    ?>
                                                                                    var status="'sold'";                        
                                                                                    var editButton = '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" \n\
                                                                                     data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" onclick="add_order_status('+status+','+main_id_for_edit+');" \n\
                                                                                    ><i class="bx bx-pencil"></i></a><textarea style="display:none;" id="edit-records-' + main_id_for_edit + '">'+final+'</textarea>';
                                                                                    <?php
                                                                                    }
                                                                                    else if($edit==1 && $url_handle=='retired-inventory')
                                                                                    {    
                                                                                    ?>
                                                                                    var status="'retired'";                        
                                                                                    var editButton = '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" \n\
                                                                                     data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" onclick="add_order_status('+status+','+main_id_for_edit+');" \n\
                                                                                    ><i class="bx bx-pencil"></i></a><textarea style="display:none;" id="edit-records-' + main_id_for_edit + '">'+final+'</textarea>';
                                                                                    <?php
                                                                                    }
                                                                                    else if($edit==1 && $url_handle=='in-repair')
                                                                                    {    
                                                                                    ?>
                                                                                    var status="'repair'";                        
                                                                                    var editButton = '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" \n\
                                                                                     data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen4" onclick="add_order_status('+status+','+main_id_for_edit+');" \n\
                                                                                    ><i class="bx bx-pencil"></i></a><textarea style="display:none;" id="edit-records-' + main_id_for_edit + '">'+final+'</textarea>';
                                                                                    var editButton =editButton+ '<a onclick="finish_repair('+main_id_for_edit+')" class="btn btn-sm btn-success">Finish?</a>'; 
                                                                                    <?php
                                                                                    }
                                                                                    else if($edit==1 && ($url_handle!='parent_user' || $url_handle!='in-repair' || $url_handle!='retired-inventory') && $title !='Tutorials' && $title !='Course Certificate'
                                                                                    && $title != 'Pre Register Event' && $title != 'User Tutorial Subscription' && $title !='User Course' && $title != 'Gallery'  && $title != 'Payments' && $title != 'Tutorial Transactions' && trim($title) != 'Reschedule Classes' && trim($title) != 'Student Homework')
                                                                                    {    
                                                                                    ?>
                                                                                    var editButton = '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" data-bs-toggle="modal"  onclick="add_form(' + main_id_for_edit + ');" \n\
                                                                                    data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen"  ><i class="bx bx-pencil"></i></a><textarea style="display:none;" id="edit-records-' + main_id_for_edit + '">'+final+'</textarea>';
                                                                                    
                                                                                    <?php
                                                                                    }
                                                                                    if($delete==1 && $title != 'Pre Register Event' && $title != 'User Tutorial Subscription' && $title !='Gallery'  && $title != 'Payments' && $title != 'Tutorial Transactions')
                                                                                    {    
                                                                                    ?>
                                                                                    
                                                                                    var deleteButton = '<a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleterecord(' + row.<?php echo $delete_id; ?> + ');"><i class="ri-delete-bin-5-line"></i></a>';
                                                                                    <?php
                                                                                    }
                                                                                   if($url_handle!='parent_user' && $title !='User Course')
                                                                                    {    
                                                                                       
                                                                                    ?>
                                                                                    var completeButton = '<a style="padding:3px;" class="btn btn-success btn-sm btn-success" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen10"  onclick="view_data_form(' + row.<?php echo $delete_id; ?> + ');"><i class="ri-rocket-line"></i><?php if($title=='Tutorials'){ echo "Reply" ; }else { echo "View"; } ?></a>';
                                                                                    <?php
                                                                                    }
                                                                                 
                                                                                    if($profile==1)
                                                                                    {    
                                                                                    ?>
                                                                                    
                                                                                        var deleteButton = deleteButton + "" + '<a style="margin-left:3px;padding:3px;" class="btn btn-success btn-sm waves-effect" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2" onclick="addextrarecord(' + row.<?php echo $delete_id; ?> + ',' + row.type + ');"><i class="ri-add-line align-bottom me-1"></i>Profile</a>';
                                                                                    <?php
                                                                                    }
                                                                                    if($calculator==1 || $title == 'Tutorials')
                                                                                    {      
                                                                                    ?>
                                                                                    if(row.details_status=='' || row.details_status==null)
                                                                                    {
                                                                                        var title = "<?php echo $title; ?>"
                                                                                        if(title == 'Tutorials'){
                                                                                            var details=" Close"; 
                                                                                        }else{
                                                                                            var details=" Details"; 
                                                                                        }
                                                                                       
                                                                                    }    
                                                                                    else
                                                                                    {
                                                                                    var details=row.details_status;    
                                                                                    }  
                                                                                    if(title == 'Tutorials'){
                                                                                        var order_extra_details= '<a style="padding:3px;" class="btn btn-info btn-sm btn-delete" onclick="closeTutorial(' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-microphone-off"></i>'+details+'</a>';
                                                                                    }else{
                                                                                        var order_extra_details= '<a style="padding:3px;" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen2"  class="btn btn-info btn-sm btn-delete" onclick="order_details_extra(' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-pencil"></i>'+details+'</a>';
                                                                                    }  
                                                                                      
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
                                                                                    if($title == 'Student Homework'){    
                                                                                    ?>
                                                                                    
                                                                                     var order_qc1= '<a style="padding:3px;"  class="btn btn-primary btn-sm btn-edit" onclick="homework_status(1,' + row.<?php echo $delete_id; ?> + ');"><i class="bx bx-check"></i>Homework Status</a>';
                                                                                     <?php } ?>
                                                                                    return editButton + ' ' + deleteButton+" "+order_extra_details+" "+order_qc1+" "+order_qc2+" "+completeButton ;
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
                                                                                        return '<input type="checkbox" class="row-checkbox result_default_id" value="' + data + '"><br>';
                                                                                    }
                                                                                }, 
                                                                                <?php
                                                                                }
                                                                                else if($display_res=='selling_price' || $display_res=='repair_cost'
                                                                                        || $display_res=='replacement_cost'
                                                                                        || $display_res=='total_item_cost'
                                                                                        || $display_res=='ratail_price_int'
                                                                                        || $display_res=='ratail_price_ind'
                                                                                        || $display_res=='rate'
                                                                                        || $display_res=='diamond_rate'
                                                                                        || $display_res=='gemstone_rate'
                                                                                        || $display_res=='pearl_rate'
                                                                                        )
                                                                                {
                                                                                ?>
                                                                                {
                                                                                    
                                                                                    "data": "<?php echo $display_res; ?>",
                                                                                    "render": function (data, type, row) {
                                                                                        if(data>0)
                                                                                        {    
                                                                                        return "₹"+data;  
                                                                                        }
                                                                                        else
                                                                                        {    
                                                                                        return "₹0";  
                                                                                        }
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
                                                                                else if($display_res=='inventory_status')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "inventory_status",
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
                                                                                   if(data=='0000-00-00 00:00:00')
                                                                                    {
                                                                                    var formattedDate="-";    
                                                                                    }    
                                                                                    else if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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
                                                                                else if($display_res=='qr_code')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "qr_code",
                                                                                "render": function (data, type, row) {
                                                                                    // Parse the date string into a JavaScript Date object

                                                                                if (data != '') {
                                                                                      return '<a style="padding:3px;" class="btn btn-primary btn-sm btn-primary"  onclick="if(\'' + data + '\' !== \'\'){ var link = document.createElement(\'a\'); link.href = \'' + data + '\'; link.download = \'image.jpg\'; document.body.appendChild(link); link.click(); document.body.removeChild(link); }">Qrcode</a>';

//                                                                                    return  '<a style="padding:3px;" class="btn btn-success btn-sm btn-success" data-bs-toggle="modal" data-bs-target=".exampleModalFullscreen10"  onclick=" "><i class="ri-rocket-line"></i>View</a>';
                                                                                    
//                                                                                    return '<a href="' + data + '" download><img src="' + data + '" alt="Placeholder Image" style="height: 30px;"></a>';
                                                                                }
                                                                                }
                                                                                },
                                                                                 
                                                                                 
                                                                                <?php
                                                                                }
                                                                   else if ($display_res === 'request_status') { ?>
                                                                    {
                                                                        "data": "request_status",
                                                                        "render": function (data, type, row) { 
                                                                            console.log("Data received:", data); // Debugging statement

                                                                            if (data === 'Accepted') {
                                                                                console.log("Status is Accepted"); // Debugging statement
                                                                                return '<span style="background-color:green; color:white;padding:10px; border-radius:5px;">' + data + '</span>'; // Status pending ke liye green background
                                                                            } else if (data === 'Pending') {
                                                                                console.log("Status is Pending"); // Debugging statement
                                                                                return '<span style="background-color:orange; color:white;padding:10px; border-radius:5px;">' + data + '</span>'; // Status approved ke liye blue background
                                                                            } else if (data === 'Rejected') {
                                                                                console.log("Status is Rejected"); // Debugging statement
                                                                                return '<span style="background-color:red; color:white;padding:10px; border-radius:5px;">' + data + '</span>'; // Status rejected ke liye blue background
                                                                            } 
                                                                        }
                                                                    },
                                                                            <?php } 

                                                                                else if($display_res=='dob')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "dob",
                                                                                "render": function (data, type, row) {
                                                                                    // Parse the date string into a JavaScript Date object
                                                                                   if(data=='0000-00-00 00:00:00')
                                                                                    {
                                                                                    var formattedDate="-";    
                                                                                    }    
                                                                                    else if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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
                                                                                
                                                                                
                                                                                else if($display_res=='approved_date')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "approved_date",
                                                                                "render": function (data, type, row) {
                                                                                    // Parse the date string into a JavaScript Date object
                                                                                   if(data=='0000-00-00 00:00:00')
                                                                                    {
                                                                                    var formattedDate="-";    
                                                                                    }    
                                                                                    else if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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
                                                                                
                                                                                
                                                                                else if($display_res=='hk_date')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "hk_date",
                                                                                "render": function (data, type, row) {
                                                                                    // Parse the date string into a JavaScript Date object
                                                                                    if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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
                                                                                
                                                                                  else if($display_res=='reject_date')
                                                                                {
                                                                                ?>
                                                                                {
                                                                                "data": "reject_date",
                                                                                "render": function (data, type, row) {
                                                                                    // Parse the date string into a JavaScript Date object
                                                                                    if(data=='0000-00-00 00:00:00')
                                                                                    {
                                                                                    var formattedDate="-";    
                                                                                    }    
                                                                                    else if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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
                                                                                    if(data=='0000-00-00 00:00:00')
                                                                                    {
                                                                                    var formattedDate="-";    
                                                                                    }    
                                                                                    else if(data!='')
                                                                                    {    
                                                                                    var date = new Date(data);
                                                                                    var monthNames = ["January", "February", "March", "April", "May", "June", "July",
                                                                                        "August", "September", "October", "November", "December"];
                                                                                    var year = date.getFullYear();
                                                                                    var month = monthNames[date.getMonth()];
                                                                                    var day = date.getDate();
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

                                                                        <?php if($table=='child_parent_relationship'){ ?>
                                                                            "buttons": ['copy', {
                                                                                        extend: 'print',
                                                                                        exportOptions: {
                                                                                            columns: column
                                                                                        }
                                                                                    },],

                                                                        <?php  }else{ ?>
                                                                            "buttons": [ 'csv', 'excel', {
                                                                                        extend: 'print',
                                                                                        exportOptions: {
                                                                                            columns: column
                                                                                        }
                                                                                    },
                                                                                    {
                                                                                        extend: 'copy',
                                                                                        exportOptions: {
                                                                                            columns: column
                                                                                        }
                                                                                    },
                                                                                
                                                                                
                                                                                ],

                                                                        <?php } ?>
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
                                                                            'value': columnName,
                                                                            'checked': 'checked',
                                                                        });
                                                                        var setcolumn_alias=cleanAndFormatString(columnName, '_');
    //                                                                    var setcolumn_alias=removeSpecialCharactersAndLowerCase(columnName);
                                                                        if(columnName=='Action')
                                                                        {    
                                                                        var label = $('<label id="set_label_'+setcolumn_alias+'" style="display:none;width:100%;">').text(columnName).prepend(checkbox);
                                                                        }
                                                                        else
                                                                        {
                                                                        var label = $('<label id="set_label_'+setcolumn_alias+'"  style="width:100%;">').text(columnName).prepend(checkbox);    
                                                                        }    
                                                                        var label2 = '<li><a class="dropdown-item" href="#">' + label + '</a></li>';
                                                                        columnToggleContainer.append(label);
                                                                    });
                                                                    $('.dropdown-menu2').prepend(columnToggleContainer);
                                                                    $('.column-toggle-checkbox').on('change', function () {
                                                                        var columnIdx = $(this).data('column');
                                                                        dataTable.column(columnIdx).visible($(this).is(':checked'));
                                                                    });

                                                                    //                initializeDataTable();
                                                                });
                                                                
                                                                function cleanAndFormatString(inputString, replaceSpaceWith) {
                                                                    // Remove special characters using regex
                                                                    var cleanedString = inputString.replace(/[^\w\s]/g, '');

                                                                    // Replace spaces with the specified character (or remove them)
                                                                    cleanedString = cleanedString.replace(/\s+/g, replaceSpaceWith || '');

                                                                    // Convert to lowercase
                                                                    var lowerCaseString = cleanedString.toLowerCase();

                                                                    return lowerCaseString;
                                                                }  
                                                                
                                                                function set_extra_model_open(forms)
                                                                {
                                                                    if (forms == '') {
                                                                        $(".first_data").removeAttr("id");
                                                                        $('.first_data').attr('id', 'add-category-form');
                                                                    } else {
                                                                        $(".first_data").removeAttr("id");
                                                                        $('.first_data').attr('id', 'edit-category-form');
                                                                    }

                                                                    var id = $("#id").val();
                                                                    var form_type = 'add';
                                                                    var handle =forms;
                                                                    alert(handle);
                                                                    var main_final_url = handle;
    //                                                                var img = '<img src="<?php echo base_url().'loadingimg.gif'; ?>"  />';
    //                                                                $(".first_data").html(img);


                                                                    $.ajax({
                                                                        url: "<?php echo base_url($master . '/index'); ?>",
                                                                        method: "POST",
                                                                        data: { id: id, form_type: form_type, handle: handle, main_final_url: main_final_url },
                                                                        success: function (data) {
    //                                                                        alert(data);
                                                                            $("#left_bar_body").html(data);

    //                                                                        $('.my-select4').each(function () {
    //                                                                        initializeSelectize($(this));
    //                                                                        });

                                                                        }, 
                                                                        complete: function() {
    //                                                                        $('.my-select4').click();

                                                                        }
                                                                    });



                                                                }
                                                                
                                                                $(document).ready(function () {
                                                                    // Assuming you want to fetch the checked values when some event occurs
                                                                    $('.column-toggle-checkbox').on('click', function () {
                                                                        // Fetch checked values of checkboxes with class 'column-checkbox' in the first column
                                                                        var checkedValues = $('.column-toggle-checkbox:checked').map(function () {
                                                                            return $(this).val();
                                                                        }).get();

                                                                        // Do something with the checked values
                                                                        savecolumns_access(checkedValues);
                                                                    });
                                                                    var handle = $("#url_handle").val();
                                                                    $.ajax({
                                                                        url: '<?php echo base_url().'extra/fetch_columns_data' ?>',
                                                                        type: "POST",
                                                                        data: {
                                                                                    "handle": handle,
                                                                        },
                                                                        success: function (data) { 
                                                                            var result = JSON.parse(data);
                                                                            if(result.name!='')
                                                                            {    
                                                                            var cat=result.name;
                                                                            var data_exp= cat.split(',');
                                                                            var finals='';
                                                                            var finals2='';
                                                                            var concatenatedValues = '';

                                                                            $.each(data_exp, function(index, value) {
                                                                                if(value=='action' || value=='Action')
                                                                                {

                                                                                }
                                                                                else
                                                                                {     

                                                                                finals=finals+"|"+value;    
                                                                                finals2=finals2+"|"+value;    
    //                                                                            var values=removeSpecialCharactersAndLowerCase(value);
                                                                                var values = cleanAndFormatString(value, '_');
                                                                                //alert(values);
                                                                                $("#set_label_"+values).prop('checked', true);
    //                                                                            alert(values);  
    //                                                                            $("#set_label_"+values).click();
                                                                                }                                                                            
                                                                            });

                                                                            $('.column-toggle-checkbox').each(function() 
                                                                            {
                                                                                if($(this).is(':checked')) {
                                                                                    // Concatenate the value to the result string
                                                                                    concatenatedValues += $(this).val() + '|';
                                                                            }
                                                                            });




    //                                                                        alert(concatenatedValues);
    //                                                                        alert(finals);

                                                                            var array1 = concatenatedValues.split('|');
                                                                            var array2 = finals.split('|');

                                                                            var differences = getArrayDifference(array2, array1);
    //                                                                        alert(differences[0]);

                                                                            $.each(differences, function(index, value) {
                                                                                if(value!='Action')
                                                                                {    
                                                                                var values = cleanAndFormatString(value, '_');
                                                                                $("#set_label_"+values).click();
                                                                                }

                                                                            });


    //                                                                        alert(differences);   



    //                                                                        alert(finals2);


                                                                            }   
                                                                         }
                                                                    });  

                                                                });

                                                                function getArrayDifference(array1, array2) {
                                                                    return array1.filter(x => !array2.includes(x)).concat(array2.filter(x => !array1.includes(x)));
                                                                }

                                                                function savecolumns_access(id)
                                                                {
    //                                                               alert(id); 

                                                                   var handle = $("#url_handle").val();
                                                                    $.ajax({
                                                                                url: "<?php echo base_url('extra/save_access'); ?>",
                                                                                type: "POST",
                                                                                data: {
                                                                                    "id": id,
                                                                                    "handle": handle,
                                                                                },
                                                                                success: function (response) {
                                                                                     Swal.fire({
                                                                                        icon: 'success',
                                                                                        title: 'Congrats!',
                                                                                        text: "Column Access Given Successfully!",
                                                                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                                                                        buttonsStyling: false
                                                                                    });

                                                                                }
                                                                            });
                                                                }
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
                                                                                    if(!result.success){
                                                                                        var icon = 'info'
                                                                                        var title = 'No!'
                                                                                    }else{
                                                                                        var icon = 'success'
                                                                                        var title = 'Deleted!'
                                                                                    }

                                                                                    Swal.fire({
                                                                                        icon: icon,
                                                                                        title: title,
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

                                                                function homework_status(row, id)
                                                                {
                                                                    const { value: formValues } = Swal.fire({
                                                                    title: "Homework Status",
                                                                    html: `
                                                                    <div class="mb-3">
                                                                    <label for="exampleInputPassword1" class="form-label">Update Homework Status</label>
                                                                    <select name="homework_status" id="home_status">
                                                                        <option value="approved">Approved</option>
                                                                        <option value="incomplete">Incomplete</option>
                                                                        <option value="unapproved">Unapproved</option>
                                                                    </select>
                                                                    </div>
                                                                    <input type="hidden" id="home_update_id" value=${id}>
                                                                    `,
                                                                    focusConfirm: false,
                                                                    preConfirm: () => {
                                                                        return [
                                                                            document.getElementById("home_status").value,
                                                                            document.getElementById("home_update_id").value
                                                                
                                                                        ];
                                                                    },
                                                                    didOpen: () => {
                                                                            const swalContainer = document.querySelector('.swal2-container');
                                                                            const modalBody = document.querySelector('#formModalClasses');
                                                                            if (swalContainer && modalBody) {
                                                                                modalBody.appendChild(swalContainer);
                                                                            }
                                                                        }
                                                                    }).then((result) => {
                                                                        if (result.isConfirmed) {
                                                                                console.log("res", result)
                                                                                $.ajax({
                                                                                url: "<?php echo base_url($master . '/homework_update_status'); ?>",
                                                                                type: "POST",
                                                                                data: {
                                                                                    "id": id,
                                                                                    "homework_status": result.value[0],
                                                                                    "delete_column": "<?php echo $delete_id; ?>",
                                                                                    "table": "<?php echo $table; ?>",
                                                                                    "heading": $("#heading").val()
                                                                                },
                                                                                success: function (response) {
                                                                                    var result = JSON.parse(response);
                                                                                    if(!result.success){
                                                                                        var icon = 'info'
                                                                                        var title = 'No!'
                                                                                    }else{
                                                                                        var icon = 'success'
                                                                                        var title = 'Updated!'
                                                                                    }
                                                                                   

                                                                                    Swal.fire({
                                                                                        icon: icon,
                                                                                        title: title,
                                                                                        text: result.message,
                                                                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                                                                        buttonsStyling: false
                                                                                    });
                                                                                    console.log("Ye");
                                                                                    var dataTable = $('#ajax_datatables2').DataTable();
                                                                                    dataTable.draw();
                                                                                }
                                                                            })
                                                                            }else{
                                                                            //     Swal.fire({
                                                                            //     icon: 'info',
                                                                            //     title: 'Empty!',
                                                                            //     text: 'Title and Endtime are required',
                                                                            //     confirmButtonClass: "btn btn-primary w-xs mt-2",
                                                                            //     buttonsStyling: false
                                                                            //  });
                                                                            }  
                                                                    })
                                                                }
                                                                function cleanup(formData){
                                                                    
                                                                    var clean_data = true
                                                                    for (var [key, value] of formData.entries()) { 
                                                                        if(typeof(value) == 'string' && key != 'id' ){
                                                                           if( value.trim() === '' ){
                                                                             clean_data = false
                                                                           
                                                                           }
                                                                        }                                                                                                                                           
                                                                    }
                                                                    return clean_data
                                                                  
                                                                }
                                                                $("#add-category-form").on('submit', (function (e) {
                                                                    e.preventDefault();
                                                                 var check_data = cleanup(new FormData(this))
                                                                 
                                                                  if(!check_data){
                                                                    Swal.fire({
                                                                        icon: 'error',
                                                                        title: 'Error',
                                                                        text: "Empty String is not accepted",
                                                                        //  timer: 3000, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                        showConfirmButton: true // Hide the "OK" button
                                                                        });
                                                                  }else{
                                                                    $.ajax({
                                                                        url: "<?php echo base_url($master . '/add_data'); ?>",
                                                                        type: "POST",
                                                                        data: new FormData(this),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        beforeSend: function ()
                                                                        {
                                                                        $('.loading-icon').show();
                                                                        },
                                                                        success: function (data)
                                                                        {
                                                                            $('.loading-icon').hide();
                                                                            var category = JSON.parse(data);
                                                                            var add_via_page = $("#add_via_page").val();
//                                                                            alert(data); 
                                                                            if (category.success == true)
                                                                            {
                                                                                if (category.type == '1')
                                                                                {
                                                                                    var dataTable = $('#ajax_datatables2').DataTable();
                                                                                    dataTable.draw();
                                                                                    $("#success_toast_bar").attr("data-toast-text", "Thanks, "+category.message);
                                                                                    $("#success_toast_bar").click();     
                                                                                    
//                                                                                    Swal.fire({
//                                                                                        icon: 'success',
//                                                                                        title: 'Success',
//                                                                                        text: category.message,
//                                                                                        timer: 1500, // Set the timer duration in milliseconds (3 seconds in this example)
//                                                                                        showConfirmButton: false // Hide the "OK" button
//                                                                                    });

                                                                                    if (add_via_page > 0)
                                                                                    {
                                                                                        alert("Added Successfully!");
                                                                                        window.close();
                                                                                    }

                                                                                    $('.exampleModalFullscreen').modal('hide');
                                                                                    $('.exampleModalFullscreen2').modal('hide');
                                                                                    setTimeout(function () {
                                                                                        //location.reload();
                                                                                    }, 1500);


                                                                                }
                                                                                else if (category.type == '2')
                                                                                {
                                                                                    var dataTable = $('#ajax_datatables2').DataTable();
                                                                                    dataTable.draw();
                                                                                    $("#success_toast_bar").attr("data-toast-text", "Thanks, "+category.message);
                                                                                    $("#success_toast_bar").click();     
                                                                                    if (add_via_page > 0)
                                                                                    {
                                                                                        alert("Added Successfully!");
                                                                                        window.close();
                                                                                    }
                                                                                    $('.exampleModalFullscreen').modal('hide');
                                                                                    $('.exampleModalFullscreen2').modal('hide');
                                                                                    setTimeout(function () {
                                                                                        //location.reload();
                                                                                    }, 1500);

                                                                                }


                                                                            }
                                                                            if (category.success == false)
                                                                            {
                                                                                $("#error_toast_bar").attr("data-toast-text", "Oops, "+category.message);
                                                                                $("#error_toast_bar").click();   
                                                                            }
                                                                        },
                                                                        error: function (e)
                                                                        {
                                                                            $('.loading-icon').hide();
                                                                            Swal.fire({
                                                                                        icon: 'error',
                                                                                        title: 'Error',
                                                                                        text: "Something went wrong, please try again!!!",
                                                                                        timer: 3000, // Set the timer duration in milliseconds (3 seconds in this example)
                                                                                        showConfirmButton: false // Hide the "OK" button
                                                                            });
                                                                        }
                                                                    });
                                                                    }
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
                                                                            $('.loading-icon').show();
                                                                        },
                                                                        success: function (data)
                                                                        {
                                                                            $('.loading-icon').hide();
                                                                            var category = JSON.parse(data);
                                                                            if (category.success == true)
                                                                            {

                                                                                    var dataTable = $('#ajax_datatables2').DataTable();
                                                                                    dataTable.draw();
//                                                                                    Swal.fire({
//                                                                                        icon: 'success',
//                                                                                        title: 'Success',
//                                                                                        text: category.message
//                                                                                    });
                                                                                   $("#success_toast_bar").attr("data-toast-text", category.message);
                                                                                   $("#success_toast_bar").click();  
                                                                                    
                                                                                   $(".exampleModalFullscreen2").modal("hide");
                                                                                   
//                                                                                   setTimeout(function () {
////                                                                                        location.reload();
//                                                                                    }, 1500);
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
                                                                            $('.loading-icon').hide();
                                                                            Swal.fire({
                                                                                icon: 'error',
                                                                                title: 'Error',
                                                                                text: "Something went wrong, please try again!!"
                                                                            });
                                                                        }
                                                                    });
                                                                }));
                                                                $("#add-master-extra-form").on('submit', (function (e) {
                                                                    e.preventDefault();
                                                                    $.ajax({
                                                                        url: "<?php echo base_url('mainmaster/add_data'); ?>",
                                                                        type: "POST",
                                                                        data: new FormData(this),
                                                                        contentType: false,
                                                                        cache: false,
                                                                        processData: false,
                                                                        beforeSend: function ()
                                                                        {
//                                                                            $('.loading-icon').show();
                                                                        },
                                                                        success: function (data)
                                                                        {
                                                                            var category = JSON.parse(data);
                                                                            if (category.success == true)
                                                                            {
                                                                                    Swal.fire({
                                                                                        icon: 'success',
                                                                                        title: 'Success',
                                                                                        text: category.message
                                                                                    });
                                                                                    $("#customizerclose-btn").click();
                                                                            }
                                                                            else if (category.success == false)
                                                                            {
                                                                                alert(category.message);
//                                                                                Swal.fire({
//                                                                                    icon: 'error',
//                                                                                    title: 'Error',
//                                                                                    text: category.message
//                                                                                });
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
                                                                               setTimeout(function () {
                                                                                        //location.reload();
                                                                                    }, 1500);

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
                                                            if ($footerjsextra_images == '1') 
                                                            {
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
                                                                    padding: 0px 0px;
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
                                                                    margin-bottom:0px;
                                                                }
                                                                .upload__img-wrap {
                                                                    display: flex;
                                                                    flex-wrap: wrap;
                                                                    margin: 0 -10px;
                                                                }
                                                                .upload__img-box {
                                                                    width: 80px;
                                                                    padding: 0px 2px;
                                                                    margin-bottom: 5px;
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
                                                                    background-size: cover;
                                                                    position: relative;
                                                                    padding-bottom: 100%;
                                                                }

                                                            </style>

                                                            <script>
                                                              
//                                                                document.addEventListener('keydown', function (e) {
//                                                                  if (e.key === 'Tab') {
//                                                                    const readonlyInputs = document.querySelectorAll('input[readonly]');
//                                                                    readonlyInputs.forEach(function (input) {
//                                                                      if (document.activeElement === input) {
//                                                                        e.preventDefault();
//                                                                        const nextTabIndex = parseInt(input.getAttribute('tabindex')) + 1;
//                                                                        const nextInput = document.querySelector(`[tabindex="${nextTabIndex}"]`);
//                                                                        if (nextInput) {
//                                                                          nextInput.focus();
//                                                                        }
//                                                                      }
//                                                                    });
//                                                                  }
//                                                                });  
                                                                
                                                                
                                                                
                                                                function check_images(all)
                                                                {
//                                                                    alert(all);
                                                                    if(all!='') {
//                                                                        alert(all);  
                                                                        $('#upload_file').css('display','block');
                                                                        $('#upload_file0').css('display','block'); 
//                                                                        ImgUpload();
                                                                    }
                                                                    
                                                                }  
                                                                document.addEventListener('DOMContentLoaded', function() {
                                                                    ImgUpload();
                                                                });

                                                                function ImgUpload() {
//                                                                    alert('1');  
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
//                                                                                    alert(page_id1);
//                                                                                    alert(page_id2);
//                                                                                    alert(href);   

                                                                        var url_handle=$('#url_handle').val();
                                                                        if(url_handle=='add-new-style' 
                                                                                || url_handle=='dashboard'
                                                                                || url_handle=='track-your-orders'
                                                                                )
                                                                        {
                                                                         $('a[href="' + current_url + '"]').addClass('active');   
                                                                        }
                                                                        else
                                                                        {    
                                                                        $('#main_' + page_id1).click();

                                                                        $('[data-id="main_' + page_id1 + '"]').removeClass('collapsed');

                                                                        $('[data-id="main_' + page_id1 + '"]').attr('aria-expanded', 'true');

                                                                        $('.main_sub_' + page_id1).addClass('show');



                                                                        $('[data-id="main2_' + page_id2 + '"]').removeClass('collapsed');

                                                                        $('[data-id="main2_' + page_id2 + '"]').attr('aria-expanded', 'true');

                                                                        $('.main_sub2_' + page_id2).addClass('show');
                                                                        

//                                                                        alert(current_url);
//                                                                        alert(url_handle);

                                                                        //            $('#main2_'+page_id2).removeClass('collapsed');
                                                                        //            
                                                                        //            $('#main2_'+page_id2).attr('aria-expanded','true');
                                                                        //            
                                                                        //            $('.sub_sub_main_'+page_id2).addClass('show');
                                                                        $('a[href="' + current_url + '"]').addClass('active');
                                                                        }
                                                                        //            $('a').addClass('show');

                                                                    }

                                                                    // Set a timeout to call the delayedClick function after 5 seconds
                                                                    setTimeout(function () {
                                                                        delayedClick();
                                                                    }, 1000); // 5000 milliseconds = 5 seconds
                                                                    
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

                                                               $(document).ready(function() {
                                                                    $('input[type="number"]').on('input', function() {
                                                                        console.log("Function is running...");

                                                                        // Get the current value of the input
                                                                        let value = $(this).val();

                                                                        // Remove non-numeric characters using regex
                                                                        value = value.replace(/[^0-9]/g, '');

                                                                        // Update the input value
                                                                        $(this).val(value);

                                                                        // Display an alert
                                                                        alert("Non-numeric characters have been removed!");
                                                                    });
                                                                });


                                                                function vieweditdtl(id)
                                                                {
                                                                  $('.exampleModalgrid').modal('show');
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
                                                            <input type="hidden" name="focus_id" id="focus_id" value="<?php echo $perameters_res['id']; ?>" />
                                                            <?php
                                                            }
                                                            }

                                                            ?>
                                                            
        </body>
    </html>
    <?php
}

?>
    
    