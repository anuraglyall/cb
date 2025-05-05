<?php
//print_r($user_id);
    $urls = $this->db->query("select * from urls "
            . " where status=1 and url_access='1' "
            . "order by url_access_sort ASC,id ASC")->result_array();
    $column_alias = $this->db->query("select * from user_column_alias ")->result_array();
    ?>
<div class="row">                        <hr>
                                        <div class="col-lg-2">
                                            <div class="nav nav-pills flex-column nav-pills-tab custom-verti-nav-pills text-center" role="tablist" aria-orientation="vertical">
                                                <?php
                                                $d = 0;
                                                $f=0;
                                                foreach ($urls as $urls_res) {                                                
                                                if ($urls_res['table'] != '') {
                                                $d++;
                                                $date_nodata=$urls_res['date_nodata'];
                                                $url_id=$urls_res['id'];
//                                                $user_access= $this->db->query("select * from user_access where url_id='$url_id' and user_id='$user_id'")->result_array();
                                                ?> 
                                                
                                                <a style="padding:0px;" class="nav-link <?php if($d==1) { ?> show active <?php } ?> " 
                                                   id="custom-v-pills-home-tab-<?php echo $d; ?>" data-bs-toggle="pill" 
                                                   href="#custom-v-pills-home-<?php echo $d; ?>" 
                                                   role="tab" aria-controls="custom-v-pills-home-<?php echo $d; ?>" 
                                                   aria-selected="true">
                                                <i class="fa fa-home"></i> 
                                                <?php echo $urls_res['name']; ?>
                                                
                                                </a>  
                                                <?php
                                                }
                                                }
                                                ?>
                                            </div>
                                        </div> <!-- end col-->
                                        <div class="col-lg-10">
                                            <div class="tab-content text-muted mt-3 mt-lg-0">
                                                <?php
                                                $d = 0;
                                                $f=0;
                                                foreach ($urls as $urls_res) {                                                
                                                if ($urls_res['table'] != '') {
                                                $d++;
                                                $date_nodata=$urls_res['date_nodata'];
                                                $url_id=$urls_res['id'];
                                                $table=$urls_res['table'];
                                                $user_access= $this->db->query("select *,"
                                                        . "(select id from url_feature_access where "
                                                        . "access_type=url_feature.name and url_feature.url_id=url_feature_access.url_id and url_feature_access.user_id='$user_id' limit 1) as status_id "
                                                        . "from url_feature where url_id='$url_id'")->result_array();  
                                                
                                                
                                                
                                                ?> 
                                                <div class="tab-pane fade <?php if($d==1) { ?> show active <?php } ?>" 
                                                     id="custom-v-pills-home-<?php echo $d; ?>" role="tabpanel" aria-labelledby="custom-v-pills-home-tab-<?php echo $d; ?>">
                                                    <div style="display:none;">
                                                    <input type="text" id="url_ids" name="url_ids[]" value="<?php echo $url_id; ?>" />
                                                    </div>
                                                    <div class="col-md-12 pull-left">
                                                    <div class="col-md-3 pull-left"  style="float:left;">
                                                    <h5><?php echo $urls_res['name']; ?> Access</h5>
                                                    <p class="text-muted">Please check below access.</p>
                                                    </div>
                                                        
                                                    <div class="col-md-9 pull-left" style="float:right;">
                                                    <?php
//                                                    echo '<pre>';
//                                                    print_r($user_access);
//                                                    echo '</pre>';
                                                
                                                    
                                                    
                                                    $j=0;
                                                    foreach($user_access as $user_access_res)
                                                    {
                                                        
                                                        
                                                    $j++;    
                                                    $name=$user_access_res['name'];
                                                    $status_id=$user_access_res['status_id'];
//                                                    echo '<br>'; 
//                                                    print_r($user_access_res);
                                                    ?>
                                                    <div class="form-check mb-2 col-md-3 pull-left" style="float:left;">
                                                    <input <?php if($status_id>0) { echo 'checked'; } ?> type="checkbox" class="form-check-input" value="<?php echo $name; ?>" 
                                                               name="access_type_<?php echo $url_id; ?>[]" id="<?php echo $table; ?>_<?php echo $j; ?>">
                                                    <label class="form-check-label" for="<?php echo $table; ?>_<?php echo $j; ?>">
                                                    <?php 
                                                    echo $user_access_res['name'];
                                                    ?>
                                                    </label>
                                                    </div>
                                                    <?php 
                                                    }    
                                                    ?>
                                                    </div>
                                                    </div>
                                                    <hr/>
                                                    <?php
                                                    
//                                                    echo '<pre>';
//                                                    print_r($urls_res);
//                                                    print_r($user_access); 
//                                                    echo '</pre>';
                                                    
                                                    
                                                    ?>
                                                    
                                                </div>
                                                <?php
                                                }
                                                }
                                                ?>
                                            </div>
                                        </div> <!-- end col-->
<!--                                    </div>
<style>
    .table>:not(caption)>*>* {
        padding: 0.5rem 1rem !important;
    }
</style>
<div class="card-body" >
    <div class="live-preview" style="margin-top:10px;">
        <div class="accordion custom-accordionwithicon custom-accordion-border accordion-border-box accordion-success" id="accordionBordered">
            <?php
            $d = 0;
            $f=0;
            foreach ($urls as $urls_res) {
                $d++;
                if ($urls_res['table'] != '') {
                    $date_nodata=$urls_res['date_nodata'];
                    $url_id=$urls_res['id'];
                    $user_access= $this->db->query("select * from user_access where url_id='$url_id' and user_id='$user_id'")->result_array();
//                    echo '<pre>';
//                    print_r($user_access);
//                    echo '</pre>';
                    ?> 
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse" 
                                    data-bs-target="#accor_borderedExamplecollapse1_<?php echo $d; ?>" aria-expanded="true" aria-controls="accor_borderedExamplecollapse1">
                                        <?php echo $urls_res['name']; ?>
                            </button>
                            <input type="hidden" name="maintable[]" id="maintable" value="<?php echo $table_name = $urls_res['table']; ?>" /> 
                            <input type="hidden" name="url_id[]" id="urlid" value="<?php echo $url_id; ?>" /> 
                        </h2>
                        <div id="accor_borderedExamplecollapse1_<?php echo $d; ?>" class="accordion-collapse collapse" aria-labelledby="accordionborderedExample1" data-bs-parent="#accordionBordered">
                            <div class="accordion-body" style="background-color: #FBF7F4;">
                                <div class="row">
                                    <?php
                                    $fields='';
                                    $table = $urls_res['table'];
                                    
                                    if($table=='orders')
                                    {
                                    $colmn_width=12/6;    
                                    }
                                    else
                                    {
                                    $colmn_width=12/3;    
                                    }
                                    

                                    if ($table !== '') {
                                    if ($table == '#') {
                                            
                                    } else {
                                            $fields = $this->db->list_fields($table);
                                    }
                                    }
                                    
                                    $useraccess=$post['user_access'];
                                    $found=0;
                                    foreach($useraccess as $useracc){
                                    if ($useracc['table']==$table){
                                    $found=$useracc;
                                    }
                                    }
                                    ?>
                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" value="<?php
                                                if($user_access[0]['write']=='1')
                                                {
                                                    echo '1';
                                                }    
                                                else if ($found['write'] == 1) {
                                                    echo '1';
                                                } else if ($found['write'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>"
                                                       <?php
                                                       if($user_access[0]['write']=='1')
                                                       {
                                                           echo 'checked';
                                                       }
                                                       else if ($found['write'] == 1) {  
                                                           echo 'checked';
                                                       } else if ($found['write'] == 0) {
                                                           echo '';
                                                       } else { ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                           $('#<?php echo $table_name; ?>_writeaccess_show').val('1'),$('.<?php echo $table_name; ?>_row_selection').css('pointer-events','visible');
                                                                       } else {
                                                                           $('#<?php echo $table_name; ?>_writeaccess_show').val('0'),$('.<?php echo $table_name; ?>_row_selection').css('pointer-events','none');
                                                                       }"
                                                       >
                                                    
                                                  
                                                    <label class="form-check-label" for="writeaccess">Write Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['write']=='1')
                                                {
                                                echo '1';
                                                }
                                                else if ($found['write'] == 1) {
                                                    echo '1';
                                                } else if ($found['write'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>"  id="<?php echo $table_name; ?>_writeaccess_show" name="<?php echo $table_name; ?>_writeaccess_show" />
                                                  
                                        </div>

                                        <?php
                                        if ($table !== '') {
//                                            echo count($fields);
                                            if(count($fields)>1)
                                            {    
                                            ?>
                                            <table class="<?php echo $table_name; ?>_row_selection table table-borderless table-hover table-nowrap align-middle mb-0" style="
                                                        <?php if ($found['write'] == 1) {  
                                                           echo 'pointer-events:visible;';
                                                       } else if ($found['write'] == 0) {
                                                           echo 'pointer-events:none;';
                                                       } else { 
                                                           echo 'pointer-events:visible;';
                                                       } ?>
                                                       background-color:white;">
                                                    <thead class="table-light">
                                                    <tr class="text-muted">
                                                        <th scope="col"><input  type="checkbox"  id="<?php echo $table_name; ?>checkAll" /></th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Column Alas</th>
                                                    </tr>
                                                    </thead>
                                                   
                                                    <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach ($fields as $field) {
                                                        $i++;

                                                        $found2 = 0;
                                                        foreach ($post['column_access'] as $column_access) {
                                                            if ($column_access['type'] == 'write') {
                                                                if ($column_access['column'] == $field && $found['id'] == $column_access['user_access_id']) {
//                                            
                                                                    $found2 = $column_access;
                                                                }
                                                            }
                                                        }


                                                        $column_access = $post['column_access'][$i - 1];

                                                        foreach ($found2 as $key => $col_ac) {
                                                            if ($key == 'column' && $col_ac == $field) {
                                                                $xchek = 1;
                                                            }
                                                            if ($xchek == 1 && $key == 'status') {
                                                                $finalval = $col_ac;
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="<?php echo $table_name; ?>_checkbox-item" id="write_check" name="write_check[]" onclick="if ($(this).prop('checked')) { $('#<?php echo $table_name; ?>_write_column_name_<?php echo $i; ?>').val('1'); } else { $('#<?php echo $table_name; ?>_write_column_name_<?php echo $i; ?>').val('0'); }" 
                                                                       value="<?php
                                                                       if ($finalval == 1) {
                                                                           echo '1';
                                                                       } else if ($finalval == 0) {
                                                                           echo '0';
                                                                       } else {
                                                                           ?>0<?php } ?>"  <?php
                                                                       if ($finalval == 1) {
                                                                           echo 'checked';
                                                                       } else if ($finalval == 0) {
                                                                           echo '';
                                                                       } else {
                                                                           ?>  checked <?php } ?> />

                                                                <input type="hidden" style="height:30px !important;" name="<?php echo $table_name; ?>_write_column_name[]" 
                                                                       id="<?php echo $table_name; ?>_write_column_name_<?php echo $i; ?>" 
                                                                       value="<?php
                                                                       if ($finalval == 1) {
                                                                           echo '1';
                                                                       } else if ($finalval == 0) {
                                                                           echo '0';
                                                                       } else { echo '0'; } ?>" <?php echo $field ?> class="form-control" placholder="Display Name" />    

                                                                <input type="hidden"  style="height:30px !important;" name="<?php echo $table_name; ?>_write_display_column_name[]" 
                                                                       value="<?php echo $field ?>" class="form-control" placholder="Display Name" />    
                                                            </td>
                                                            <td><?php echo $field ?></td>
                                                            <td>
                                                                <input type="text" value="<?php 
                                                                $found_alias='';
                                                                foreach($column_alias as $column_alias_res)
                                                                {
                                                                if($column_alias_res['name']==$field)
                                                                {
                                                                $found_alias=$column_alias_res['alias'];    
                                                                }    
                                                                }
                                                                if($found2['alias']!='')
                                                                {
                                                                echo $found2['alias'];     
                                                                }
                                                                else
                                                                {
                                                                echo $found_alias;    
                                                                }    
                                                                ?>"  style="height:30px !important;" name="<?php echo $table_name; ?>_write_alias[]" id="write_alias" class="form-control" placholder="Display Name" />    
                                                            </td>
                                                        </tr>                    

                                                        <?php
                                                    }
                                                    ?>



                                                </tbody>
                                            </table>                 
                                            <?php
                                            }
                                        }
                                        ?>
                                    </div>

                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" value="<?php
                                                if($user_access[0]['edit']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['edit'] == 1) {
                                                    echo '1';
                                                } else if ($found['edit'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>" 
                                                       <?php
                                                       if($user_access[0]['edit']=='1')
                                                       {
                                                            echo 'checked';
                                                       }
                                                       else if ($found['edit'] == 1) {
                                                           echo 'checked';
                                                       } else if ($found['edit'] == 0) {
                                                           echo '';
                                                       } else {
                                                           ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                       $('#<?php echo $table_name; ?>_editaccess_show').val('1'),$('.<?php echo $table_name; ?>_row_selection').css('pointer-events','visible');;
                                                                       } else {
                                                                       $('#<?php echo $table_name; ?>_editaccess_show').val('0'),$('.<?php echo $table_name; ?>_row_selection').css('pointer-events','none');;
                                                                       }"
                                                       >
                                                    <label class="form-check-label" for="editaccess">Edit Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['edit']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['edit'] == 1) {
                                                    echo '1';
                                                } else if ($found['edit'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>"  id="<?php echo $table_name; ?>_editaccess_show" 
                                                    name="<?php echo $table_name; ?>_editaccess_show" />
                                            
                                            
                                        </div>
                                        <?php
                                        if ($table !== '') {
                                            if(count($fields)>1)
                                            { 
                                            ?>
                                            <table class="<?php echo $table_name; ?>_row_selection  table table-borderless table-hover table-nowrap align-middle mb-0" style="
                                                   <?php if ($found['edit'] == 1) {  
                                                           echo 'pointer-events:visible;';
                                                       } else if ($found['edit'] == 0) {
                                                           echo 'pointer-events:none;';
                                                       } else { 
                                                           echo 'pointer-events:visible;';
                                                       } ?>
                                                   background-color:white;">
                                                <thead class="table-light">
                                                    <tr class="text-muted">
                                                        <th scope="col">
                                                            <input  type="checkbox"  id="<?php echo $table_name; ?>checkAll2" /></th>
                                                        <th scope="col">Name</th>
                                                        <th scope="col">Column Alas</th>
                                                    </tr>
                                                </thead>
                                                 
                                                <tbody>
                                                    <?php
                                                    $i = 0;
                                                    foreach ($fields as $field) {
                                                        $i++;

                                                        $found2 = 0;
                                                        foreach ($post['column_access'] as $column_access) {
                                                            if ($column_access['type'] == 'edit') {
                                                                if ($column_access['column'] == $field && $found['id'] == $column_access['user_access_id']) {
//                                            
                                                                    $found2 = $column_access;
                                                                }
                                                            }
                                                        }



                                                        $column_access = $post['column_access'][$i - 1];

                                                        foreach ($found2 as $key => $col_ac) {
                                                            if ($key == 'column' && $col_ac == $field) {
                                                                $xchek = 1;
                                                            }
                                                            if ($xchek == 1 && $key == 'status') {
                                                                $finalval = $col_ac;
                                                            }
                                                        }
                                                        ?>
                                                        <tr>
                                                            <td>
                                                                <input type="checkbox" class="<?php echo $table_name; ?>_checkbox-item2" id="edit_check" name="edit_check[]" onclick="if ($(this).prop('checked')) {
                                                                                            $('#<?php echo $table_name; ?>_edit_column_name_<?php echo $i; ?>').val('1');
                                                                                        } else {
                                                                                            $('#<?php echo $table_name; ?>_edit_column_name_<?php echo $i; ?>').val('0');
                                                                                        }" 
                                                                       value="<?php
                                                                       if ($finalval == 1) {
                                                                           echo '1';
                                                                       } else if ($finalval == 0) {
                                                                           echo '0';
                                                                       } else {
                                                                           ?>1<?php } ?>"    <?php
                                                                       if ($finalval == 1) {
                                                                           echo 'checked';
                                                                       } else if ($finalval == 0) {
                                                                           echo '';
                                                                       } else {
                                                                           ?>  checked <?php } ?> />

                                                                <input type="hidden" style="height:30px !important;" name="<?php echo $table_name; ?>_edit_column_name[]" 
                                                                       id="<?php echo $table_name; ?>_edit_column_name_<?php echo $i; ?>" value="<?php
                                                                       if ($finalval == 1) {
                                                                           echo '1';
                                                                       } else if ($finalval == 0) {
                                                                           echo '0';
                                                                       } else { echo '0'; } ?>" <?php echo $field ?> class="form-control" placholder="Display Name" />    

                                                                <input type="hidden" style="height:30px !important;" name="<?php echo $table_name; ?>_edit_display_column_name[]" 
                                                                       value="<?php echo $field ?>" class="form-control" placholder="Display Name" />    
                                                            </td>
                                                            <td><?php echo $field ?></td>
                                                            <td>
                                                                <input type="text" value="<?php 
                                                                 $found_alias='';
                                                                foreach($column_alias as $column_alias_res)
                                                                {
                                                                if($column_alias_res['name']==$field)
                                                                {
                                                                $found_alias=$column_alias_res['alias'];    
                                                                }    
                                                                }
                                                                if($found2['alias']!='')
                                                                {
                                                                echo $found2['alias'];     
                                                                }
                                                                else
                                                                {
                                                                echo $found_alias;    
                                                                }
                                                                //echo $found2['alias']; ?>" style="height:30px !important;" name="<?php echo $table_name; ?>_edit_alias[]" id="edit_alias" class="form-control" placholder="Display Name" />    
                                                            </td>
                                                        </tr>                    

                                                        <?php
                                                    }
                                                    ?>
                                                </tbody>
                                            </table>                 
                                            <?php
                                        }
                                        }
                                        ?>
                                    </div>

                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <?php 
//                                            echo $table_name;
                                            ?>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" value="<?php
                                                if($user_access[0]['delete']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['delete'] == 1) {
                                                    echo '1';
                                                } else if ($found['delete'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>" 
                                                       <?php
                                                       if($user_access[0]['delete']=='1')
                                                       {
                                                            echo 'checked';
                                                       }
                                                       else if ($found['delete'] == 1) {
                                                           echo 'checked';
                                                       } else if ($found['delete'] == 0) {
                                                           echo '';
                                                       } else {
                                                           ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                           $('#<?php echo $table_name; ?>_deleteaccess_show').val('1');
                                                                       } else {
                                                                           $('#<?php echo $table_name; ?>_deleteaccess_show').val('0');
                                                                       }"

                                                       >
                                                    <label class="form-check-label" for="deleteaccess">Delete Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['delete']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['delete'] == 1) {
                                                    echo '1';
                                                } else if ($found['delete'] == 0) {
                                                    echo '0';
                                                } else {
                                                ?>1<?php } ?>"  id="<?php echo $table_name; ?>_deleteaccess_show" name="<?php echo $table_name; ?>_deleteaccess_show" />
                                        </div>
                                    </div>    
                                    <?php 
                                    if($table=='orders')
                                    {    
                                    ?>
                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <?php 
//                                            echo $table_name;
                                            ?>
                                            <div class="form-check form-switch mb-3">
                                                <input class="form-check-input" type="checkbox" value="<?php
                                                if($user_access[0]['details']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['details'] == 1) {
                                                    echo '1';
                                                } else if ($found['details'] == 0) {
                                                    echo '0';
                                                } else {
                                                    ?>1<?php } ?>" 
                                                       <?php
                                                       if($user_access[0]['details']=='1')
                                                       {
                                                            echo 'checked';
                                                       }
                                                       else if ($found['details'] == 1) {
                                                           echo 'checked';
                                                       } else if ($found['details'] == 0) {
                                                           echo '';
                                                       } else {
                                                           ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                           $('#<?php echo $table_name; ?>_detailsaccess_show').val('1');
                                                                       } else {
                                                                           $('#<?php echo $table_name; ?>_detailsaccess_show').val('0');
                                                                       }"

                                                       >
                                                    <label class="form-check-label" for="detailsaccess">Details Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['delete']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['delete'] == 1) {
                                                    echo '1';
                                                } else if ($found['delete'] == 0) {
                                                    echo '0';
                                                } else {
                                                ?>1<?php } ?>"  id="<?php echo $table_name; ?>_detailsaccess_show" name="<?php echo $table_name; ?>_detailsaccess_show" />
                                        </div>
                                    </div>   
                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <?php 
//                                            echo $table_name;
                                            ?>
                                            <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox" value="<?php
                                                        if($user_access[0]['qc1']=='1')
                                                        {
                                                            echo '1';
                                                        }
                                                        else if ($found['qc1'] == 1) {
                                                            echo '1';
                                                        } else if ($found['qc1'] == 0) {
                                                            echo '0';
                                                        } else {
                                                        ?>1<?php } ?>" 
                                                       <?php
                                                       if($user_access[0]['qc1']=='1')
                                                       {
                                                            echo 'checked';
                                                       }
                                                       else if ($found['qc1'] == 1) {
                                                           echo 'checked';
                                                       } else if ($found['qc1'] == 0) {
                                                           echo '';
                                                       } else {
                                                           ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                           $('#<?php echo $table_name; ?>_qc1access_show').val('1');
                                                                       } else {
                                                                           $('#<?php echo $table_name; ?>_qc1access_show').val('0');
                                                                       }"

                                                       >
                                                    <label class="form-check-label" for="qc1access">QC 1 Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['qc1']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['qc1'] == 1) {
                                                    echo '1';
                                                } else if ($found['qc1'] == 0) {
                                                    echo '0';
                                                } else {
                                                ?>1<?php } ?>"  id="<?php echo $table_name; ?>_qc1access_show" name="<?php echo $table_name; ?>_qc1access_show" />
                                        </div>
                                    </div>   
                                    <div class="col md-<?php echo $colmn_width; ?>" align="center">
                                        <div class=" card"> 
                                            <?php 
//                                            echo $table_name;
                                            ?>
                                            <div class="form-check form-switch mb-3">
                                                        <input class="form-check-input" type="checkbox" value="<?php
                                                        if($user_access[0]['qc2']=='1')
                                                        {
                                                            echo '1';
                                                        }
                                                        else if ($found['qc2'] == 1) {
                                                            echo '1';
                                                        } else if ($found['qc2'] == 0) {
                                                            echo '0';
                                                        } else {
                                                        ?>1<?php } ?>" 
                                                       <?php
                                                       if($user_access[0]['qc2']=='1')
                                                       {
                                                            echo 'checked';
                                                       }
                                                       else if ($found['qc2'] == 1) {
                                                           echo 'checked';
                                                       } else if ($found['qc2'] == 0) {
                                                           echo '';
                                                       } else {
                                                           ?>  checked <?php } ?>
                                                       onclick="if ($(this).prop('checked')) {
                                                                           $('#<?php echo $table_name; ?>_qc2access_show').val('1');
                                                                       } else {
                                                                           $('#<?php echo $table_name; ?>_qc2access_show').val('0');
                                                                       }"

                                                       >
                                                    <label class="form-check-label" for="qc1access">QC 2 Access</label>
                                            </div>
                                            <input type="hidden" value="<?php
                                                if($user_access[0]['qc2']=='1')
                                                {
                                                    echo '1';
                                                }
                                                else if ($found['qc2'] == 1) {
                                                    echo '1';
                                                } else if ($found['qc2'] == 0) {
                                                    echo '0';
                                                } else {
                                                ?>1<?php } ?>"  id="<?php echo $table_name; ?>_qc2access_show" name="<?php echo $table_name; ?>_qc2access_show" />
                                        </div>
                                    </div>   
                                    <?php 
                                    }    
                                    ?>
                                    
                                    <script>
                                                    /*
                                                      $('#<?php echo $table_name; ?>checkAll').on('click', function() {
                                                        var isChecked = $(this).prop('checked');
//                                                        alert('1');
                                                        $('.<?php echo $table_name; ?>_checkbox-item').click();
                                                      });

                                                      
                                                      $('.<?php echo $table_name; ?>_checkbox-item').on('click', function() {
//                                                        /alert('2');
                                                        $('#<?php echo $table_name; ?>checkAll').prop('checked', $('.<?php echo $table_name; ?>_checkbox-item:not(:checked)').length === 0);
                                                      });
                                                      
                                                      
                                                      $('#<?php echo $table_name; ?>checkAll2').on('click', function() {
//                                                        alert('3');
                                                        var isChecked = $(this).prop('checked');
                                                        $('.<?php echo $table_name; ?>_checkbox-item2').click();
                                                      });

                                                      
                                                      $('.<?php echo $table_name; ?>_checkbox-item2').on('click', function() {
//                                                        alert('4');
                                                        $('#<?php echo $table_name; ?>checkAll2').prop('checked', $('.<?php echo $table_name; ?>_checkbox-item2:not(:checked)').length === 0);
                                                      });
                                                   */ 
                                    </script>

                                </div>
                            </div>
                        </div>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
    </div>
</div>


-->