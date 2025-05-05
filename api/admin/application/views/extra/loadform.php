<?php


        $k = 0;
        $l = 0;
        foreach ($perameters as $perameters_res) {
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
                    if ($perameters_res['type'] == 'hidden') {
                        echo 'display:none;';
                    }
                    ?>float:left;<?php if($perameters_res['type']=='hr') { echo 'min-height:20px;'; } ?>" >
                
                    <?php
                    if ($perameters_res['type'] == 'file' || $perameters_res['type'] == 'email' || $perameters_res['type'] == 'number' || $perameters_res['type'] == 'text' || $perameters_res['type'] == 'hidden') {
                        ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                    <input  <?php
                    if ($perameters_res['sort'] == '1') {
                        echo 'focus';
                    }
                    ?> <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?>   <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?> <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                        type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                        name="<?php echo $perameters_res['name']; ?>" 
                        class="form-control" <?php echo $perameters_res['mandatory']; ?> <?php if ($perameters_res['type'] == 'file') echo $perameters_res['multiple']; ?> 
                        <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                        <?php } ?> 
                        <?php if ($perameters_res['label'] == 'Duration (In minutes for quiz)') { ?>  max="1000" <?php } ?>  />
                        <?php
                    } else if ($perameters_res['type'] == 'textarea') {
                        ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                    <textarea    <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>"
                                id="<?php echo $perameters_res['id']; ?>" 
                                name="<?php echo $perameters_res['name']; ?>" 
                                class=<?php if($perameters_res['master'] !== 'courses'){echo "form-control";}else{echo "";}?> style="<?php echo $perameters_res['styles']; ?>" <?php echo $perameters_res['mandatory']; ?>
                                <?php if($perameters_res['master'] === 'courses'){?>
                                rows="5" cols="50"
                            <?php } ?> 
                                <?php if ($perameters_res[5] != '') {  echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                <?php } ?>><?php echo trim($perameters_res['data']); ?></textarea>
                                <?php
                            } else if ($perameters_res['type'] == 'blankdiv') {
                                ?>
                    <div class="col-md-12" style='clear:both;'></div>

                    <?php
                } else if ($perameters_res['type'] == 'cotable') {
                    ?>
                    <div class="col-md-12">
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
                    <input    <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                            type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                            name="<?php echo $perameters_res['name']; ?>" 
                            <?php if($perameters_res['master'] === 'events') { $currentDate = date('Y-m-d');?> 
                            min = <?php echo $currentDate; ?>
                            <?php } ?>
                            class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                            <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                            <?php } ?> />
                            <?php
                        }
                        else if ($perameters_res['type'] == 'time') {
                        ?>
                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                        <input    <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                                type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                                name="<?php echo $perameters_res['name']; ?>" 
                                class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                <?php } ?> />
                                <?php
                            }
                            else if ($perameters_res['type'] == 'customdiv') {
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
                                <input   <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  class="form-check-input" type="<?php echo $perameters_res['type']; ?>" 
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
                } else if ($perameters_res['type'] == 'select2') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select   <?php echo $perameters_res['multiple']; ?> <?php echo $perameters_res['multiple']; ?>   <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2  form-control"  name="<?php echo $perameters_res['name']; ?><?php
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
                    <select   <?php echo $perameters_res['multiple']; ?>   <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2 form-control" name="<?php echo $perameters_res['name']; ?><?php
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
//                                                                        echo '<pre>';
//                                                                        print_r($perameters_res);
//                                                                        echo '</pre>';


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
                        <select  <?php echo $perameters_res['multiple']; ?>  style="<?php echo $width1; ?>"  data-select-id="<?php echo $perameters_res['name']; ?>"    <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                        class="<?php if($perameters_res['master'] != 'homework' && $perameters_res['master'] != 'course_gallery' && $perameters_res['master'] != 'course_certificate') {echo 'my-select4';}else{echo 'cstm-dropdown';} ?> dynamic-option" name="<?php echo $perameters_res['name']; ?><?php
                        if ($perameters_res['multiple'] == 'multiple') {
                            echo '[]';
                        }
                        ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                            <option value="">--Suggested--</option>
                            <?php
                            foreach ($perameters_res['data'] as $select_data) {
                                ?>
                                <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
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
                        $add_btn_handle=$perameters_res['add_btn_handle'];
                        ?>
                        <div style="<?php echo $width2; ?>">
                            <!-- target="_blanks"  href="<?php echo base_url() . '' . $perameters_res['add_btn_handle'] . '?add=1'; ?>" -->
                            <a class="btn" onclick="set_sub_pages('<?php echo $add_btn_handle; ?>')" data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas" aria-controls="theme-settings-offcanvas" style="width:100%;float:left !important;margin-top: 25px;padding:3px 0px;" >
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
                

                }else if ($perameters_res['type'] == 'datalist') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <input type="text"   <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  
                        <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                        class="my-select2 form-control" style="<?php echo $perameters_res['styles']; ?>"
                        list="<?php echo $perameters_res['name']; ?>_alllist" name="<?php echo $perameters_res['name']; ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                    <datalist id="<?php echo $perameters_res['name']; ?>_alllist">
                    <?php
                    foreach ($perameters_res['data'] as $select_data) {
                    ?>
                    <option value="<?php echo $select_data['name']; ?>">
                    <?php
                    }
                    ?>
                            
                    </datalist>
                    
                    <?php
                }
                else if ($perameters_res['type'] == 'select') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select    <?php if($perameters_res['readonly']=='readonly') { ?> tabindex="-1" <?php } ?> <?php echo $perameters_res['readonly']; ?> <?php echo $perameters_res['focus']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                            class="form-control" name="<?php echo $perameters_res['name']; ?><?php
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
                } else if ($perameters_res['type'] == 'div') { ?>
                    <h1 class="info"><?php  print_r($perameters_res['data']); ?></h1>
                    
                <?php  } 
                else if ($perameters_res['type'] == 'image_div') {
//                                                    echo '1';    
                    $image_upload = $this->load->view('extra/load_image_upload_design', $data, true);
                    echo $image_upload;
                }
                else if ($perameters_res['type'] == 'normal_div') {
//                                                    echo '1';     ?>
                    <div class="" style="margin-bottom:20px;">
                        <div class="accordion" id="default-accordion-example">
<div class="accordion-item">
<h2 class="accordion-header" id="headingOne">
<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
    Other Details
</button>
</h2>
<div id="collapseOne" class="accordion-collapse collapse show" style='border:0px solid white;' aria-labelledby="headingOne" data-bs-parent="#default-accordion-example" style="">
<div class="accordion-body final_calculator_section" style='border:0px solid white;padding:0px;' >
    Please wait loading...
</div>
</div>
</div>
</div>
                    </div>    
                    
                <?php     
                }
                else if ($perameters_res['label'] != '' && $perameters_res['id'] == '') {
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
            <div style="display:none;" class="hstack gap-2 justify-content-end">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success" id="add-btn">Add <?php echo $title; ?></button>
                <button type="reset" class="btn btn-success"  id="reset">Reset <?php echo $title; ?></button>
                <button type="submit" class="btn btn-secondary" style="display:none;" id="update-btn">Update <?php echo $title; ?></button>
                <!-- <button type="button" class="btn btn-success" id="edit-btn">Update</button> -->
            </div>   
        </div>    
    </form>
    <div id="second_data" style="display:none;">
        <form id="form_data_2">   
            <?php
//                                                print_r($perameters2);
            foreach ($perameters2 as $perameters_res) {
                ?>
                <div class="<?php
                if ($perameters_res['div_width'] != '') {
                    echo $perameters_res['div_width'];
                } else {
                    echo 'col-md-12';
                }
                ?>  pull-left" style="<?php
                        if ($perameters_res['type'] == 'hidden') {
                            echo 'display:none;';
                        }
                        ?>float:left;">
                        <?php
                        if ($perameters_res['type'] == 'text' || $perameters_res['type'] == 'hidden') {
                            ?>

                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                        <input <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                                                                                type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                                                                                name="<?php echo $perameters_res['name']; ?>" 
                                                                                class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                                                                <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                                <?php } ?> />
                                                                                <?php
                                                                            } else if ($perameters_res['type'] == 'textarea') {
                                                                                ?>
                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                        <textarea <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>"
                                                                                    id="<?php echo $perameters_res['id']; ?>" 
                                                                                    name="<?php echo $perameters_res['name']; ?>" 
                                                                                    class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                                                                    <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                                    <?php } ?>><?php echo $perameters_res['data']; ?></textarea>
                                                                                    <?php
                                                                                } else if ($perameters_res['type'] == 'cotable') {
                                                                                    ?>

                        <?php
                        echo $perameters_res['data'];
                        ?>
                        <?php
                    } else if ($perameters_res['type'] == 'date') {
                        ?>

                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?>hhh</label>

                        <input <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                                                                                type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                                                                                name="<?php echo $perameters_res['name']; ?>" 
                                                                                class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                                                                                <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                                                                                <?php } ?> />
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
                                    <input <?php echo $perameters_res['addfunction']; ?>  class="form-check-input" type="<?php echo $perameters_res['type']; ?>" 
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
                    } else if ($perameters_res['type'] == 'select') {
                        ?>

                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                        <select <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2 form-control" name="<?php echo $perameters_res['name']; ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
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
                    } else if ($perameters_res['type'] == 'button') {
                        ?>

                        <button <?php echo $perameters_res['addfunction']; ?> <?php echo $perameters_res['styles']; ?> class="<?php echo $perameters_res['classname']; ?>" type="<?php echo $perameters_res['type']; ?>"><?php echo $perameters_res['label']; ?></button>
                        <?php
                    }
                    ?>

                </div>
                <?php
            }
            ?>
            <div style="height:400px;min-height:400px;">

            </div>
            <script>
                
                function onCourseChange(event){
                    const course_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getChaptersByCourseId',
                        type: 'POST',
                        data: {
                            course_id: course_id
                        },
                        success: function(response) {
                            const chapters = JSON.parse(response);
                            let options  = `<option value=''>Select chapter</option>`;
                            for (let index = 0; index < chapters.length; index++) {
                                const id = chapters[index].id;
                                const name = chapters[index].name;
                                options += `<option value=${id}>${name}</option>`;
                            }
                            console.log('options',options);
                            const dropdown = document.querySelector('#homework_chapter_id');
                            dropdown.innerHTML = options;
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                function onChapterChange(event){
                    const chapter_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getExercisesByChapterId',
                        type: 'POST',
                        data: {
                            chapter_id: chapter_id
                        },
                        success: function(response) {
                            const exercises = JSON.parse(response);
                            let options  = `<option value=''>Select exercise</option>`;
                            for (let index = 0; index < exercises.length; index++) {
                                const id = exercises[index].id;
                                const name = exercises[index].name;
                                options += `<option value=${id}>${name}</option>`;
                            }
                            const dropdown = document.querySelector('#homework_exercise_id');
                            dropdown.innerHTML = options;
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                function updateEventEndTime(event){
                    const value = event.target.value;
                    const duration = $('#duration').val();
                    const pre = '0'
                    if(duration){
                        const mins = duration%60
                        const startTime = value.split(':');
                        const totalMins = (parseInt(startTime[1]) + parseInt(mins))
                        const hour = parseInt(duration/60) + parseInt(startTime[0])
                        if(totalMins >60 && hour < 24 ){
                            var getNewMins = totalMins%60
                            var getNewHour = parseInt(hour) + 1
                            if(getNewHour >= 24 ){
                                $('#end_time').val('00:00')
                                $('#duration').val('')
                            }else{
                                if(parseInt(getNewMins) < 10){
                                    getNewMins = pre+getNewMins
                                }
                                if(parseInt(getNewHour) < 10){
                                    getNewHour = pre+getNewHour
                                }
                                const updatedVal = getNewHour+':'+getNewMins
                                $('#end_time').val(updatedVal)
                            }
                            
                        }else if(hour >= 24){
                             $('#end_time').val('00:00')
                             $('#duration').val('')
                        }else {
                            var getNewMins = totalMins
                            if(parseInt(getNewMins) < 10){
                                    getNewMins = pre+getNewMins
                            }
                            var getNewHour = hour
                            if(parseInt(getNewHour) < 10){
                                getNewHour = pre+getNewHour
                            }
                            const updatedVal = getNewHour+':'+getNewMins
                            $('#end_time').val(updatedVal)
                        } 
                    }else{
                        $('#end_time').val(value)
                    }
                }

                function mainQuizChange(event){
                    const main_quiz_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getQuizByMainQuizId',
                        type: 'POST',
                        data: {
                            main_quiz_id: main_quiz_id
                        },
                        success: function(response) {
                            const quiz = JSON.parse(response);                                                
                                var selectizeInstance = $('.my-select4[data-select-id="quiz_id"]').get(0).selectize;
                                if (selectizeInstance) {
                            
                                selectizeInstance.clearOptions(true);
                                    for (let index = 0; index < quiz.length; index++) {
                                        const id = quiz[index].id;
                                        const name = quiz[index].name;
                                            selectizeInstance.addOption({ value: id, text: name });
                                
                                }
                            
                                }
                            console.log("Hello");
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
                //check password format
                function checkPasswordFormat() { 
                    const FS = document.getElementById('password');
                    FS.setCustomValidity('');
                    var regex = new Array();
                    regex.push("[A-Z]"); //Uppercase Alphabet.
                    regex.push("[a-z]"); //Lowercase Alphabet.
                    regex.push("[0-9]"); //Digit.
                    regex.push("[!@#$%^&*]"); //Special Character.
                    var passed = 0;
                    for (var i = 0; i < regex.length; i++) {
                        if (new RegExp(regex[i]).test(FS.value)) {
                            passed++;
                        }
                    }
                    
                    if (passed > 3 && FS.value.length > 7) {
                       return true
                    }else {
                        console.log("passed", passed)
                        console.log("=length", FS.value.length)
                        FS.setCustomValidity("Pasword must contain 8 characters in length, at least 1 capital letter,\n\n1 small letter, 1 number and 1 special character");
                        FS.reportValidity();
                        //$('#password').val('');   
                     //   $('#password').focus();   
                        return false   
                    }
                }

                //check email format
                function checkEmailFormat() { 
                    var emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4}$/;
                    const FS = document.getElementById('contact_email');
                    FS.setCustomValidity('');
                    if (emailPattern.test(FS.value)) {
                        return true
                    } else {
                    
                        console.log("=email", FS.value.length)
                        FS.setCustomValidity("Email is not valid");
                        FS.reportValidity();
                        return false   
                    }
                }
                //course change add quiz for criteria
                function changeCourseForCriteria(event){
                    const course_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/changeCourseForCriteria',
                        type: 'POST',
                        data: {
                            course_id: course_id
                        },
                        success: function(response) {
                            const criteria = JSON.parse(response);                                                
                                $('#percent_creteria').val(100-parseInt(criteria))
                            console.log("Hello");
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                //course change add quiz for criteria
                function getStateCity(event){

                    const state_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getStateCity',
                        type: 'POST',
                        data: {
                            state_id: state_id
                        },
                        success: function(response) {
                            const city = JSON.parse(response);                                                
                                var selectizeInstance = $('.my-select4[data-select-id="city"]').get(0).selectize;
                                // if (selectizeInstance) {
                                selectizeInstance.clearOptions(true);
                                    for (let index = 0; index < city.length; index++) {
                                        const id = city[index].id;
                                        const name = city[index].name;
                                        selectizeInstance.addOption({ value: id, text: name });
                                
                                }
                            
                               // }
                            console.log("Hello2");
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                function getUserChanges(event){
                    const user_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getUserCourse',
                        type: 'POST',
                        data: {
                            user_id: user_id
                        },
                        success: function(response) {
                            const quiz = JSON.parse(response);                                                
                                var selectizeInstance = $('.my-select4[data-select-id="mycart_id"]').get(0).selectize;
                                if (selectizeInstance) {
                                selectizeInstance.clearOptions(true);
                                    for (let index = 0; index < quiz.length; index++) {
                                        const id = quiz[index].id;
                                        const name = quiz[index].name;
                                        selectizeInstance.addOption({ value: id, text: name });
                                
                                }
                            
                                }
                            console.log("Hello");
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                function onCourseChangeGalleryFolders(event){
                    const course_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getGalleryFoldersByCourseId',
                        type: 'POST',
                        data: {
                            course_id: course_id
                        },                             
                        success: function(response) {
                            const folders = JSON.parse(response);
                            let options  = `<option value=''>Select folder</option>`;
                            for (let index = 0; index < folders.length; index++) {
                                const id = folders[index].id;
                                const name = folders[index].name;
                                options += `<option value=${id}>${name}</option>`;
                            }

                            const dropdown = document.querySelector('#gallery_folder_id');
                            dropdown.innerHTML = options;
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }

                function onUserChangeCourseCertificate(event){
                    const user_id = event.target.value;
                    $.ajax({
                        url: 'Loadform/getCourseListToCertification',
                        type: 'POST',
                        data: {
                            user_id: user_id
                        },
                        success: function(response) {
                            const courses = JSON.parse(response);
                            let options  = `<option value=''>Select course</option>`;
                            for (let index = 0; index < courses.length; index++) {
                                const id = courses[index].id;
                                const name = courses[index].name;
                                options += `<option value=${id}>${name}</option>`;
                            }

                            const dropdown = document.querySelector('#course_id');
                            dropdown.innerHTML = options;
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            </script>
