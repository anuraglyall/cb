<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Style extends CI_Controller {

    function call_api($url, $data) {
        $url = $url;
        // Convert the data to JSON
        $jsonData = json_encode($data);
//            exit;
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    function get_extra_data() {
        if ($_POST['type'] == 'diamonds') {
            $selectedValues = implode(",", $_POST['selectedValues']);
            $data = $this->db->query("select *,d.name as diamond_name"
                            . ",d2.name as cut"
                            . ",d3.name as shape "
                            . ",d4.name as color "
                            . ",d5.name as clarity "
                            . ",d6.name as pointers "
                            . ",d7.name as sieve_size "
                            . "from diamonds d "
                            . " LEFT JOIN diamond_masters d2 ON d2.id=d.diamond_cut "
                            . " LEFT JOIN diamond_masters d3 ON d3.id=d.diamond_shape "
                            . " LEFT JOIN diamond_masters d4 ON d4.id=d.diamond_color "
                            . " LEFT JOIN diamond_masters d5 ON d5.id=d.diamond_clarity "
                            . " LEFT JOIN diamond_masters d6 ON d6.id=d.diamond_pointers "
                            . " LEFT JOIN diamond_masters d7 ON d7.id=d.diamond_sieve_size "
                            . "where d.id  IN ($selectedValues)")->result_array();
            foreach ($data as $data_res) {
                ?>
                <tr style="font-size:11px;">

                    <th>
                        <?php
//                                                                    print_r($data_res);
                        echo $data_res['diamond_name'];
                        ?>
                        <input type="hidden" name="diamond_name[]" value="<?php echo $data_res['diamond_name']; ?>" />

                    </th>  
                    <th>
                        <?php echo $data_res['cut']; ?>
                        <input type="hidden" name="diamond_cut[]" 
                               value="<?php echo $data_res['diamond_cut']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['shape']; ?>
                        <input type="hidden" name="diamond_shape[]" 
                               value="<?php echo $data_res['diamond_shape']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['color']; ?>
                        <input type="hidden" name="diamond_color[]" 
                               value="<?php echo $data_res['diamond_color']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['clarity']; ?>
                        <input type="hidden" name="diamond_clarity[]" 
                               value="<?php echo $data_res['diamond_clarity']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['pointers']; ?>
                        <input type="hidden" name="diamond_pointers[]" 
                               value="<?php echo $data_res['diamond_pointers']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['sieve_size']; ?>
                        <input type="hidden" name="diamond_sieve_size[]" 
                               value="<?php echo $data_res['diamond_sieve_size']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['diamond_rate']; ?>
                        <input type="hidden" name="diamond_diamond_rate[]" 
                               value="<?php echo $data_res['diamond_rate']; ?>" />
                    </th>
                </tr>
                <?php
            }
        } else if ($_POST['type'] == 'gemstones') {
            $selectedValues = implode(",", $_POST['selectedValues']);
            $data = $this->db->query("select *"
                            . ",d2.name as type"
                            . ",d3.name as cut "
                            . ",d4.name as shape "
                            . ",d5.name as quality "
                            . ",d6.name as size "
                            . ",d7.name as origin "
                            . " from gemstone d "
                            . " LEFT JOIN gemstone_masters d2 ON d2.id=d.gemstone_type "
                            . " LEFT JOIN gemstone_masters d3 ON d3.id=d.gemstone_cut "
                            . " LEFT JOIN gemstone_masters d4 ON d4.id=d.gemstone_shape "
                            . " LEFT JOIN gemstone_masters d5 ON d5.id=d.gemstone_quality "
                            . " LEFT JOIN gemstone_masters d6 ON d6.id=d.gemstone_size "
                            . " LEFT JOIN gemstone_masters d7 ON d7.id=d.gemstone_origin "
                            . "where d.id  IN ($selectedValues)")->result_array();
            foreach ($data as $data_res) {
                ?>
                <tr style="font-size:11px;">

                    <th>
                        <?php echo $data_res['name']; ?>
                        <input type="hidden" name="gemstone_name[]" value="<?php echo $data_res['name']; ?>" />

                    </th>  
                    <th>
                        <?php echo $data_res['type']; ?>
                        <input type="hidden" name="gemstone_type[]" 
                               value="<?php echo $data_res['type']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['cut']; ?>
                        <input type="hidden" name="gemstone_cut[]" 
                               value="<?php echo $data_res['cut']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['shape']; ?>
                        <input type="hidden" name="gemstone_shape[]" 
                               value="<?php echo $data_res['gemstone_shape']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['quality']; ?>
                        <input type="hidden" name="gemstone_quality[]" 
                               value="<?php echo $data_res['gemstone_quality']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['size']; ?>
                        <input type="hidden" name="gemstone_size[]" 
                               value="<?php echo $data_res['gemstone_size']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['origin']; ?>
                        <input type="hidden" name="gemstone_origin[]" 
                               value="<?php echo $data_res['gemstone_origin']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['gemstone_rate']; ?>
                        <input type="hidden" name="gemstone_rate[]" 
                               value="<?php echo $data_res['gemstone_rate']; ?>" />
                    </th>  

                </tr>
                <?php
            }
        } else if ($_POST['type'] == 'pearls') {
            $selectedValues = implode(",", $_POST['selectedValues']);
            $data = $this->db->query("select *"
                            . ",d2.name as type"
                            . ",d3.name as shape "
                            . ",d4.name as color "
                            . ",d5.name as size "
                            . ",d6.name as unit "
                            . " from pearls d "
                            . " LEFT JOIN pearl_masters d2 ON d2.id=d.pearl_type "
                            . " LEFT JOIN pearl_masters d3 ON d3.id=d.pearl_shape "
                            . " LEFT JOIN pearl_masters d4 ON d4.id=d.pearl_color "
                            . " LEFT JOIN pearl_masters d5 ON d5.id=d.pearl_size "
                            . " LEFT JOIN pearl_masters d6 ON d6.id=d.pearl_unit "
                            . "where d.id  IN ($selectedValues)")->result_array();
            foreach ($data as $data_res) {
                ?>
                <tr style="font-size:11px;">

                    <th>
                        <?php echo $data_res['name']; ?>
                        <input type="hidden" name="pearl_name[]" value="<?php echo $data_res['name']; ?>" />

                    </th>  
                    <th>
                        <?php echo $data_res['type']; ?>
                        <input type="hidden" name="pearl_type[]" 
                               value="<?php echo $data_res['pearl_type']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['shape']; ?>
                        <input type="hidden" name="pearl_shape[]" 
                               value="<?php echo $data_res['shape']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['color']; ?>
                        <input type="hidden" name="pearl_color[]" 
                               value="<?php echo $data_res['pearl_color']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['size']; ?>
                        <input type="hidden" name="pearl_size[]" 
                               value="<?php echo $data_res['pearl_size']; ?>" />
                    </th>                                                                
                    <th>
                        <?php echo $data_res['unit']; ?>
                        <input type="hidden" name="pearl_unit[]" 
                               value="<?php echo $data_res['pearl_unit']; ?>" />
                    </th>  
                    <th>
                        <?php echo $data_res['pearl_rate']; ?>
                        <input type="hidden" name="pearl_rate[]" 
                               value="<?php echo $data_res['pearl_rate']; ?>" />
                    </th>  

                </tr>
                <?php
            }
        }
    }

    public function index() {
        $data['validate'] = "name,short_name,type";
        $fullUrl = base_url(uri_string());
        ### partner masters


        $handle = $_POST['handle'];
        $main_final_url = $_POST['main_final_url'];
        if ($main_final_url != '') {
            $fullUrl = $main_final_url;
        }


        if (strpos($fullUrl, base_url() . 'user_management') !== false) {

            $form_data = $this->db->query("SELECT * FROM `form_build` where master='user_management' order by sort ASC")->result_array();
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'date') {
                        unset($form_data_res['data']);
                        $date = date("Y-m-d");
                        $form_data_res = array_merge($form_data_res, array("data" => $date));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'metal') {
                        $currency = $this->db->query("select id,name as name from metals where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'metal_finish') {
                        $currency = $this->db->query("select id,name as name from metal_finishes where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'diamonds') {
                        $currency = $this->db->query("select id,name as name from diamonds where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'gemstones') {
                        $currency = $this->db->query("select id,name as name from gemstone where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'dimensions') {
                        $currency = $this->db->query("select id,name as name from dimensions where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'partners') {
                        $currency = $this->db->query("select id,partner_customer_id as name from partners_customer where type='1' ")->result_array();

                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'pearls') {
                        $currency = $this->db->query("select id,name as name from pearls where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'usermanagement_customdiv') {
                        unset($form_data_res['data']);
                        $message2 = "<div id='load_extra_data'>" . $this->load->view('extra/usermanagement', $data, true) . '</div>';
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'gemstones_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "gemstones";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'pearls_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "pearls";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'edit_extra_function') {
                        $data['edit_extra_function_id'] = $form_data_res['data'];
                        $data['edit_extra_function'] = $form_data_res['addfunction'];
                    } else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } else if ($form_data_res['id'] == 'type') {
                        $data['type'] = $form_data_res['data'];
//                  exit;
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['data'] == 'blank') {
                        if ($form_data_res['id'] == 'stock_no') {
                            unset($form_data_res['data']);
                            $form_data_res = array_merge($form_data_res, array("data" => $api_res['data']));
                            $perameters2[] = $form_data_res;
                        }
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        } 
        else if (strpos($fullUrl, base_url() . 'add-new-style') !== false) {

            $form_data = $this->db->query("SELECT * FROM `form_build` where (master='$handle' OR master='add-new-style') order by sort ASC")->result_array();
            $currency = '';
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'date') {
                        unset($form_data_res['data']);
                        $date = date("Y-m-d");
                        $form_data_res = array_merge($form_data_res, array("data" => $date));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'diamonds_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "diamonds";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'comment_history_box') {
                        unset($form_data_res['data']);
                        $message2 = "<div id='comment_history_box'></div>";
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'dimensions_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "dimensions";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  exit;
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'gemstones_customdiv') {
                        $data['type'] = "gemstones";
                        unset($form_data_res['data']);
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'pearls_customdiv') {
                        $data['type'] = "pearls";
                        unset($form_data_res['data']);
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    }
                    else if ($form_data_res['id'] == 'vendor') {
                        $currency = $this->db->query("select * from vendor_distinct limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if ($form_data_res['id'] == 'prices') {
                        $currency = $this->db->query("select * from prices limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'category') {
                        $currency = $this->db->query("select * from category_distinct limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'subcategory') {
                        $currency = $this->db->query("select * from subcategory_distinct limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'metal') {
                        $currency = $this->db->query("select * from metal_distinct limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'metal_finish') {
                        $currency = $this->db->query("select * from metal_finish_distinct limit 5")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'vendor') {
                        $currency = $this->db->query("select id,name as name from prices where type='1' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } else if ($form_data_res['id'] == 'type') {
                        $data['type'] = '1';
//                  exit;
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['data'] == 'blank') {
                        if ($form_data_res['id'] == 'stock_no') {
                            unset($form_data_res['data']);
                            $form_data_res = array_merge($form_data_res, array("data" => $api_res['data']));
                            $perameters2[] = $form_data_res;
                        }
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['handle'] = 'add-new-style';
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;

            if ($handle != '') {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;
            }
        } else if (
                strpos($fullUrl, base_url() . 'orders') !== false || strpos($fullUrl, base_url() . 'archive-orders') !== false || strpos($fullUrl, base_url() . 'inventory') !== false || strpos($fullUrl, base_url() . 'sold-inventory') !== false || strpos($fullUrl, base_url() . 'in-repair') !== false || strpos($fullUrl, base_url() . 'retired-inventory') !== false
        ) {

            $data['style_status'] = $this->db->query("select * from style_status")->result_array();

            if (strpos($fullUrl, base_url() . 'orders') !== false) {
//                  $data['status']=1;    
//                  $data['order_extra_details']=1;    
//                  $data['order_qc1']=1;    
//                  $data['order_qc2']=1;    
//                  $data['complete']=1;      
                $form_data = $this->db->query("SELECT * FROM `form_build` where (master='$handle' OR master='orders') order by sort ASC")->result_array();
            } else if (strpos($fullUrl, base_url() . 'archive-orders') !== false) {
//                  $data['add']=0;    
//                  $data['status']=0;    
//                  $data['order_extra_details']=1;    
//                  $data['order_qc1']=0;    
//                  $data['order_qc2']=0;    
//                  $data['complete']=0;      
//                  $data['write_data']=0;      
//                  $data['edit']=0;      
//                  $data['delete']=0;      
                $form_data = $this->db->query("SELECT * FROM `form_build` where  (master='$handle' OR master='archive-orders') "
                                . "order by sort ASC")->result_array();
            } else if (strpos($fullUrl, base_url() . 'sold-inventory') !== false) {
//                  $data['order_status']=1;    
//                  $data['order_extra_details']=0;    
//                  $data['order_qc1']=0;    
//                  $data['order_qc2']=0;    
//                  $data['complete']=0;      
//                  $data['write_data']=0;      
//                  $data['edit']=0;      
//                  $data['delete']=0;      
                $data['order_status_type'] = 'sold';
                $form_data = $this->db->query("SELECT * FROM `form_build` where  (master='$handle' OR master='sold-inventory') "
                                . "order by sort ASC")->result_array();
            } else if (strpos($fullUrl, base_url() . 'in-repair') !== false) {
//                  $data['add']=0;    
//                  $data['order_status']=1;    
//                  $data['order_extra_details']=0;    
//                  $data['order_qc1']=0;    
//                  $data['order_qc2']=0;    
//                  $data['complete']=0;      
//                  $data['write_data']=0;      
//                  $data['edit']=0;      
//                  $data['delete']=0;      
                $data['order_status_type'] = 'repair';
                $form_data = $this->db->query("SELECT * FROM `form_build` where  (master='$handle' OR master='in-repair') "
                                . "order by sort ASC")->result_array();
            } else if (strpos($fullUrl, base_url() . 'retired-inventory') !== false) {
//                  $data['add']=0;    
//                  $data['order_status']=1;    
//                  $data['order_extra_details']=0;    
//                  $data['order_qc1']=0;    
//                  $data['order_qc2']=0;    
//                  $data['complete']=0;      
//                  $data['write_data']=0;      
//                  $data['edit']=0;      
//                  $data['delete']=0;      
                $data['order_status_type'] = 'retired';
                $form_data = $this->db->query("SELECT * FROM `form_build` where  (master='$handle' OR master='retired-inventory') "
                                . "order by sort ASC")->result_array();
            } else {
                $data['order_status_type'] = 'on-hand';
                $form_data = $this->db->query("SELECT * FROM `form_build` where  (master='$handle' OR master='inventory') order by sort ASC")->result_array();
            }
            $currency = '';
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'diamonds_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "diamonds";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'date') {
                        unset($form_data_res['data']);
                        $date = date("Y-m-d");
                        $form_data_res = array_merge($form_data_res, array("data" => $date));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'target_delivery_date') {
                        unset($form_data_res['data']);
                        $date = date("Y-m-d");
//                  $date = "2023-08-13"; // Your initial date

                        $newDate = date("Y-m-d", strtotime($date . " +4 weeks"));

                        $form_data_res = array_merge($form_data_res, array("data" => $newDate));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'dimensions_customdiv') {
                        unset($form_data_res['data']);
                        $data['type'] = "dimensions";
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  exit;
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'load_edit_inventory_customdiv') {
                        unset($form_data_res['data']);
                        $message2 = "<div id='load_edit_inventory_customdiv'></div>";
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'gemstones_customdiv') {
                        $data['type'] = "gemstones";
                        unset($form_data_res['data']);
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'inventory_status') {
                        unset($form_data_res['data']);
                        $inventory_status = array();

                        $inventory_status[] = array("id" => "In Production", "name" => "In Production");
                        $inventory_status[] = array("id" => "On Hand", "name" => "On Hand");
                        $inventory_status[] = array("id" => "Sold", "name" => "Sold");
                        $inventory_status[] = array("id" => "Retired", "name" => "Retired");

                        $form_data_res = array_merge($form_data_res, array("data" => $inventory_status));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'pearls_customdiv') {
                        $data['type'] = "pearls";
                        unset($form_data_res['data']);
                        $message2 = $this->load->view('extra/design_diamond_part', $data, true);
                        $data['type'] = "1";
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } else if ($form_data_res['id'] == 'type') {
                        $data['type'] = '1';
//                  exit;
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
//                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['data'] == 'blank') {
                        if ($form_data_res['id'] == 'stock_no') {
                            unset($form_data_res['data']);
                            $form_data_res = array_merge($form_data_res, array("data" => $api_res['data']));
                            $perameters2[] = $form_data_res;
                        }
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }

                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }

                if ($part == '11') {
                    $perameters11[] = $form_data_res;
                }
            }
            $data['handle'] = 'add-new-style';
            
            if (strpos($fullUrl, base_url() . 'orders') !== false) {
                $data['type'] = 'orders';
            }
            if (strpos($fullUrl, base_url() . 'inventory') !== false) { 
                $data['type'] = 'inventory';
            }
//            exit;
            if (strpos($fullUrl, base_url() . 'archive-orders') !== false) {
                $data['type'] = 'archive';
            }
            if (strpos($fullUrl, base_url() . 'sold-inventory') !== false) {
                $data['type'] = 'sold';
            }
            if (strpos($fullUrl, base_url() . 'in-repair') !== false) {
                $data['type'] = 'repair';
            }
            if (strpos($fullUrl, base_url() . 'retired-inventory') !== false) {
                $data['type'] = 'retired';
            }   

            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
            $data['perameters11'] = $perameters11;

            if ($handle != '') {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;
            }
        }







        $this->load->view('masters/default/master/default', $data);
    }

    public function fetch_data() {

        $requestData = $_POST;
//        echo '<pre>';
//        print_r($requestData);
//        
//        
//        exit;

        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];
        $this->load->model('StyleModel');
        $data = $this->StyleModel->fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection, $requestData);

        echo json_encode($data);
    }

    public function add_order_data() {
        $data = $_POST;
        $user_ids = $_SESSION['id'];
        if ($data['form_type'] == 'on-hand') {
            $user_id = $_SESSION['id'];
            $form_type = $data['form_type'];
            $date = $data['date'];
            $customers = $data['customers'];
            $total_price = $data['total_price'];
            $notes = addslashes($data['notes']);

            $i = 0;
            foreach ($data['return_notes'] as $return_notes) {
                $id = $data['id'][$i];
                $insert = $this->db->query("INSERT INTO `inventory_sold_history` 
                (`form_type`, `customer_id`, `order_id`, `notes`,`return_notes`, `user_id`, 
                `added_date`)
                VALUES ('$form_type', '$customers', '$id', '$notes', '$return_notes', '$user_id',"
                        . " NOW())");

                $check_history = $this->db->query("select * from inventory_history "
                                . "where product_id='$id'")->result_array();
                if (count($check_history) > 0) {
                    $insert = $this->db->query("update `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='On Hand',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "return_notes='$return_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . "where product_id='$id'"
                            . "");
                } else {
                    $insert = $this->db->query("INSERT INTO `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='On Hand',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "return_notes='$return_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . "");
                }

                $insert = $this->db->query("update `inventory` set final_status='On Hand' where id='$id'");
                $insert = $this->db->query("update `orders` set final_status='On Hand' where id='$id'");
                $i++;
            }



            echo json_encode(array('success' => true, 'message' => 'Return marked successfully!'));
        }
        if ($data['form_type'] == 'sold') {
            $user_id = $_SESSION['id'];
            $form_type = $data['form_type'];
            $date = $data['date'];
            $customers = $data['customers'];
            $total_price = $data['total_price'];
            $notes = addslashes($data['notes']);

            $i = 0;
            foreach ($data['price'] as $price) {
                $id = $data['id'][$i];
                $insert = $this->db->query("INSERT INTO `inventory_sold_history` 
                (`form_type`, `customer_id`, `price`, `order_id`, `total_price`, `notes`, `user_id`, 
                `added_date`)
                VALUES ('$form_type', '$customers', '$price', '$id', '$total_price', '$notes', '$user_id',"
                        . " NOW())");

                $check_history = $this->db->query("select * from inventory_history "
                                . "where product_id='$id'")->result_array();
                if (count($check_history) > 0) {
                    $insert = $this->db->query("update `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='Sold',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "selling_price='$price',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . "where product_id='$id'"
                            . "");
                } else {
                    $insert = $this->db->query("INSERT INTO `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='Sold',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "selling_price='$price',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . ""
                            . "");
                }

                $insert = $this->db->query("update `inventory` set final_status='Sold' where id='$id'");
                $insert = $this->db->query("update `orders` set final_status='Sold' where id='$id'");
                $i++;
            }



            echo json_encode(array('success' => true, 'message' => 'Sold marked successfully!'));
        }
        if ($data['form_type'] == 'repair') {
            $user_id = $_SESSION['id'];
            $form_type = $data['form_type'];
            $customers = $data['customers'];
            $vendor_id=$data['vendor_id'];
            $date = $data['date'];
            $total_price = $data['total_price'];
            $notes = addslashes($data['notes']);
            $i = 0;
            foreach ($data['price'] as $price) {
                $id = $data['id'][$i];
                
                $repair_notes = addslashes($data['repair_notes'][$i]);
                
                $check=$this->db->query("select * from inventory "
                                . "where id='$id'")->row_array();
                
                $final_status=$check['final_status'];
                $final_status2=($final_status);
                if($final_status2=='On Hand')
                {
                $final_status2="On Hand";    
                }    
                $insert = $this->db->query("INSERT INTO `inventory_sold_history` 
                (`form_type`,`repair`, `customer_id`, `vendor_id`, `repair_cost`, `order_id`, `total_price`, `notes`, `user_id`, 
                `added_date`,`repair_notes`)
                VALUES ('$final_status','1', '$customers','$vendor_id', '$price', '$id', '$total_price', '$notes', '$user_id',"
                        . " NOW(),'$repair_notes')");

                $check_history = $this->db->query("select * from inventory_history "
                                . "where product_id='$id'")->result_array();
                if (count($check_history) > 0) {
                    $insert = $this->db->query("update `inventory_history` set "
                            . "sold_date='$date',"
                            . "customer_id='$customers',"
                            . "vendor_id='$vendor_id',"
                            . "product_id='$id',"
                            . "repair_cost='$price',"
                            . "repair='1',"
                            . "type='$final_status2',"
                            . "repair_notes='$repair_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . "where product_id='$id'"
                            . "");
                }
                else 
                {
                    $insert = $this->db->query("INSERT INTO `inventory_history` set "
                            . "sold_date='$date',"
                            . "customer_id='$customers',"
                            . "vendor_id='$vendor_id',"
                            . "product_id='$id',"
                            . "type='$final_status2',"
                            . "repair='1',"
                            . "repair_cost='$price',"
                            . "repair_notes='$repair_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . ""
                            . ""); 
                }



//                $insert = $this->db->query("update `inventory` set final_status='Repair' where id='$id'");
//                $insert = $this->db->query("update `orders` set final_status='Repair' where id='$id'");
                $i++;
            }

            echo json_encode(array('success' => true, 'message' => 'Repair marked successfully!'));
        }
        if ($data['form_type'] == 'retired') {
            $user_id = $_SESSION['id'];
            $form_type = $data['form_type'];
            $customers = $data['vendor'];
            $date = $data['date'];
//            $repair_notes= addslashes($data['repair_notes']);
            $total_price = $data['total_price'];
            $notes = addslashes($data['notes']);
            $i = 0;
            foreach ($data['price'] as $price) {
                $id = $data['id'][$i];
                $repair_notes = addslashes($data['repair_notes'][$i]);
                $replacement_cost= addslashes($data['replacement_cost'][$i]);
                
                $insert = $this->db->query("INSERT INTO `inventory_sold_history` 
                (`form_type`, `customer_id`, `price`,`replacement_cost`, `order_id`, `total_price`, `notes`, `user_id`, 
                `added_date`,`repair_notes`)
                VALUES ('$form_type', '$customers', '$price','$replacement_cost', '$id', '$total_price', '$notes', '$user_id',"
                        . " NOW(),'$repair_notes')");

                $check_history = $this->db->query("select * from inventory_history "
                                . "where product_id='$id'")->result_array();
                if (count($check_history) > 0) {
                    $insert = $this->db->query("update `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='Retired',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "selling_price='$price',"
                            . "replacement_cost='$replacement_cost',"
                            . "repair_notes='$repair_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . "where product_id='$id'"
                            . "");
                } else {
                    $insert = $this->db->query("INSERT INTO `inventory_history` set "
                            . "sold_date='$date',"
                            . "type='Retired',"
                            . "customer_id='$customers',"
                            . "product_id='$id',"
                            . "selling_price='$price',"
                            . "replacement_cost='$replacement_cost',"
                            . "repair_notes='$repair_notes',"
                            . "notes='$notes',"
                            . "user_id='$user_ids',"
                            . "added_date=NOW()"
                            . ""
                            . "");
                }



                $insert = $this->db->query("update `inventory` set final_status='Retired' where id='$id'");
                $insert = $this->db->query("update `orders` set final_status='Retired' where id='$id'");
                $i++;
            }

            echo json_encode(array('success' => true, 'message' => 'Retired marked successfully!'));
        }
    }

    public function add_extra_data() {
        $data = $_POST;
//        $data = $_POST;
//        .
//                
//         print_r($data);       
//         exit;            
        if ($data['qc_order_id'] > 0) {
            $qc_order_id = $data['qc_order_id'];
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
            $date = date("Y-m-d H:i:s");
            if (count($data['qc1']) > 0) {
                $cnt = $this->db->query("select count(*) as cnt from order_qc1_steps")->row_array();
                $final_cnt = $cnt['cnt'];
                $this->db->query("delete from order_qc1 where order_id='$qc_order_id'");

                $i = 0;
                foreach ($data['qc1'] as $qc1) {
                    $this->db->query("insert into order_qc1 set added_date='$date',order_id='$qc_order_id', qc1='$qc1',user_id='$user_id'");
                    $i++;
                }

//            
//            exit;

                if ($final_cnt == $i) {
                    $this->db->query("update orders set qc_status='Qc 1 - Finished' where id='$qc_order_id'");

                    $status_ids = $this->db->query("SELECT id,name FROM `style_status` where name='QC L1'")->row_array();
                    $style_status_id = $status_ids['id'];

//                exit;
                    $update = $this->db->query("update orders set update_status='$style_status_id' where id='$qc_order_id'");

                    $message = $_POST['comments'];
                    $update = $this->db->query("insert into order_history set "
                            . "status='$style_status_id',"
                            . "order_id='$qc_order_id',"
                            . "user_id='$user_id',"
                            . "added_date=NOW(),"
                            . "message='$message'"
                            . " ");
//             echo '11111';   
                } else if ($final_cnt != $i && $i > 0) {
                    $this->db->query("update orders set qc_status='Qc 1 - Partialy Done' where id='$qc_order_id'");
                } else if ($final_cnt != $i && $i == 0) {
                    $this->db->query("update orders set qc_status='Qc 1 - Pending' where id='$qc_order_id'");
                }
            } else if (count($data['qc2']) > 0) {
                $cnt = $this->db->query("select count(*) as cnt from order_qc2_steps")->row_array();
                $final_cnt = $cnt['cnt'];
//            exit;
                $this->db->query("delete from order_qc2 where order_id='$qc_order_id'");
                $i = 0;
                foreach ($data['qc2'] as $qc1) {
                    $i++;
                    $this->db->query("insert into order_qc2 set added_date='$date',order_id='$qc_order_id', qc2='$qc1',user_id='$user_id'");
                }
                if ($final_cnt == $i) {
                    $this->db->query("update orders set qc2_status='Qc 2 - Finished' where id='$qc_order_id'");
                    $status_ids = $this->db->query("SELECT id,name FROM `style_status` where name='QC L2'")->row_array();
                    $style_status_id = $status_ids['id'];
                    $update = $this->db->query("update orders set update_status='$style_status_id' where id='$qc_order_id'");

                    $message = $_POST['comments'];
                    $update = $this->db->query("insert into order_history set "
                            . "status='$style_status_id',"
                            . "order_id='$qc_order_id',"
                            . "user_id='$user_id',"
                            . "added_date=NOW(),"
                            . "message='$message'"
                            . " ");
                } else if ($final_cnt != $i && $i > 0) {
                    $this->db->query("update orders set qc2_status='Qc 2 - Partialy Done' where id='$qc_order_id'");
                } else if ($final_cnt != $i && $i == 0) {
                    $this->db->query("update orders set qc2_status='Qc 2 - Pending' where id='$qc_order_id'");
                }
            }


//        echo 'dkkkk';
            echo json_encode(array('success' => true, 'message' => 'Qc detail updated successfully!'));
        } else {
            $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
            $this->load->model('StyleModel');
            $order_id = $data['order_id'];
            if (is_array($order_id)) {
                $order_id = $data['order_id'][0];
            } else {
                $order_id = $data['order_id'];
            }
//            echo '<br>';

            
            
            $check = $this->db->query("SELECT * FROM `orders` where id='$order_id'")->row_array();
            $styles_id = ($check['styles']);
            $gross_weight = $data['gross_weight'];
            $total_weight_ct = $data['total_weight'];
            $total_weight_gm = $data['total_weight_gm'];
            $total_net_weight = $data['total_net_weight'];
            $no_of_peaces = $data['total_peaces_no'];
            $metal_cost = $data['metal_cost'];
            $metal_rate = $data['metal_rate'];
            $total_diamond_weight = $data['total_diamond_weight'];
            $total_diamond_cost = $data['total_diamond_cost'];
            $total_gemstone_weight = $data['total_gemstone_weight'];
            $total_gemstone_cost = $data['total_gemstone_cost'];
            $total_pearls_weight = $data['total_pearls_weight'];
            $total_pearls_cost = $data['total_pearls_cost'];
            $value_added_total = $data['value_added_total'];
            $value_added_rate = $data['value_added_rate'];
            $wastage_percentage = $data['wastage_percentage'];
            $total_item_cost = $data['total_item_cost'];
            $date = date("Y-m-d H:i:s");
            $this->db->query("update orders set "
                    . "gross_weight='$gross_weight',"
                    . "gross_weight='$gross_weight',"
                    . "total_weight_ct='$total_weight_ct',"
                    . "total_weight_gm='$total_weight_gm',"
                    . "total_peaces_no='$no_of_peaces',"
                    . "metal_cost='$metal_cost',"
                    . "metal_rate='$metal_rate',"
                    . "value_added_total='$value_added_total',"
                    . "total_item_cost='$total_item_cost',"
                    . "value_added_rate='$value_added_rate',"
                    . "wastage_percentage='$wastage_percentage',"
                    . "total_diamond_weight='$total_diamond_weight',"
                    . "total_diamond_cost='$total_diamond_cost',"
                    . "total_gemstone_weight='$total_gemstone_weight',"
                    . "total_gemstone_cost='$total_gemstone_cost',"
                    . "total_pearls_weight='$total_pearls_weight',"
                    . "total_pearls_cost='$total_pearls_cost',"
                    . "details_status='Details Filled',"
                    . "total_net_weight='$total_net_weight'"
                    . "where id='$order_id'");

            $this->db->query("update inventory set "
                    . "gross_weight='$gross_weight',"
                    . "gross_weight='$gross_weight',"
                    . "total_weight_ct='$total_weight_ct',"
                    . "total_weight_gm='$total_weight_gm',"
                    . "total_peaces_no='$no_of_peaces',"
                    . "metal_cost='$metal_cost',"
                    . "metal_rate='$metal_rate',"
                    . "value_added_total='$value_added_total',"
                    . "total_item_cost='$total_item_cost',"
                    . "value_added_rate='$value_added_rate',"
                    . "wastage_percentage='$wastage_percentage',"
                    . "total_diamond_weight='$total_diamond_weight',"
                    . "total_diamond_cost='$total_diamond_cost',"
                    . "total_gemstone_weight='$total_gemstone_weight',"
                    . "total_gemstone_cost='$total_gemstone_cost',"
                    . "total_pearls_weight='$total_pearls_weight',"
                    . "total_pearls_cost='$total_pearls_cost',"
                    . "details_status='Details Filled',"
                    . "total_net_weight='$total_net_weight'"
                    . "where order_id='$order_id'");

            $i = 0;
            foreach ($data['diamond_id'] as $diamond_id) {
                $this->db->query("delete from order_weight_logs where diamond_id='$diamond_id' and order_id='$order_id'");
                $diamond_id = $data['diamond_id'][$i];
//                $order_id= $data['order_id'][$i];
                $diamond_weight = $data['diamond_weight'][$i];
                $no_of_peaces = $data['diamond_no_of_peaces'][$i];
                $diamond_avg_weight = $data['diamond_avg_weight'][$i];
                $rate = $data['rate'][$i];
                $cost = $data['cost'][$i];
                $this->db->query("insert into order_weight_logs set "
                        . "order_id='$order_id', "
                        . "weight='$diamond_weight',"
                        . "avg_weight='$diamond_avg_weight',"
                        . "no_of_peaces='$no_of_peaces',"
                        . "rate='$rate',"
                        . "user_id='$user_id',"
                        . "added_date='$date',"
                        . "cost='$cost',"
                        . "diamond_id='$diamond_id'");
                $i++;
            }
            
//            echo '<pre>';
//            print_r($data);
//            
//            
//            
//            exit;

            $i = 0;
            foreach ($data['gemstone_id'] as $gemstone_id) {
                $this->db->query("delete from order_weight_logs where gemstone_id='$gemstone_id' and order_id='$order_id'");
                $gemstone_id = $data['gemstone_id'][$i];
                $gemstone_weight = $data['gemstone_weight'][$i];
                $diamond_avg_weight = $data['gemstone_avg_weight'][$i];
                $no_of_peaces = $data['gemstone_no_of_peaces'][$i];
                $rate = $data['rate'][$i];
                $cost = $data['cost'][$i];

                $this->db->query("insert into order_weight_logs set "
                        . "order_id='$order_id',"
                        . "no_of_peaces='$no_of_peaces',"
                        . "avg_weight='$diamond_avg_weight',"
                        . "weight='$gemstone_weight',"
                        . "rate='$rate',"
                        . "user_id='$user_id',"
                        . "added_date='$date',"
                        . "cost='$cost',"
                        . "gemstone_id='$gemstone_id'");
                $i++;
            }

            $i = 0;
            foreach ($data['pearls_id'] as $pearls_id) {
                $this->db->query("delete from order_weight_logs where pearls_id='$pearls_id' and order_id='$order_id'");
                $pearls_id = $data['pearls_id'][$i];
                $pearls_weight = $data['pearls_weight'][$i];
                $no_of_peaces = $data['pearls_no_of_peaces'][$i];
                $diamond_avg_weight = $data['pearls_avg_weight'][$i];
                $rate = $data['rate'][$i];
                $cost = $data['cost'][$i];

                $this->db->query("insert into order_weight_logs set "
                        . "order_id='$order_id',"
                        . "weight='$pearls_weight',"
                        . "rate='$rate',"
                        . "cost='$cost',"
                        . "user_id='$user_id',"
                        . "added_date='$date',"
                        . "avg_weight='$diamond_avg_weight',"
                        . "no_of_peaces='$no_of_peaces',"
                        . "pearls_id='$pearls_id'");
                $i++;
            }
            
            
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
            $firstname = $_SESSION['firstname']; // Assuming a user is logged in and their user ID is 1
            $lastname = $_SESSION['lastname']; // Assuming a user is logged in and their user ID is 1
            $final_name = $firstname . ' ' . $lastname;
            $status_ids = $this->db->query("SELECT id,name FROM `style_status` where name='Details Filled'")->row_array();
            $style_status_id = $status_ids['id'];
            $update = $this->db->query("update orders set update_status='$style_status_id' where id='$order_id'");

            $message = "Details Filled By " . $final_name;
            $update = $this->db->query("insert into order_history set "
//                . "status='Details Filled',"
                    . "status='$style_status_id',"
                    . "order_id='$order_id',"
                    . "user_id='$user_id',"
                    . "added_date=NOW(),"
                    . "message='$message'"
                    . " ");

            echo json_encode(array('success' => true, 'message' => 'Details updated successfully!'));
        }
    }

    public function add_data() {
        $data = $_POST;
        $heading = $_POST['heading'];
        $match = $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('StyleModel');
        if ($match != '') {
            $exists = $this->StyleModel->check_exists($match, $data);
            if ($exists) {
                // Metal already exists, show an error message
                echo json_encode(array('success' => false, 'message' => $heading . ' already exists'));
                return;
            }
        }

        if ($data['id'] == '') {
            $dimondmaster_id = $this->StyleModel->add_data($data, $user_id);

            if ($dimondmaster_id) {
                echo json_encode(array('success' => true, 'type' => '1', 'message' => $heading . ' added successfully'));
            } else {
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Failed to add ' . $heading));
            }
        } else {   
            if ($data['table'] == 'styles' && $data['id'] > 0) {
                $this->StyleModel->add_data($data, $user_id);
            } else if ($data['table'] == 'orders' && $data['id'] > 0) {
                $heading = "orders";
                $this->StyleModel->edit_data($data, $user_id);
            } else {
                $heading = "Inventory";
                $this->StyleModel->edit_data($data, $user_id);
            }
            echo json_encode(array('success' => true, 'type' => '2', 'message' => $heading . ' updated successfully'));
        }
    }

    public function edit_data() {

        $data = $_POST;

//            exit;
        $heading = $_POST['heading'];
        $match = $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('StyleModel');
        $exists = $this->StyleModel->check_exists($match, $data);
        if ($exists) {
            echo json_encode(array('success' => false, 'message' => $heading . ' already exists'));
            return;
        }
        $this->StyleModel->edit_data($data, $user_id);
        echo json_encode(array('success' => true, 'message' => $heading . ' edited successfully'));
    }

    public function delete_data() {
        $this->load->model('StyleModel');
        $data = $_POST;
//        print_r($data); 
//        exit;
        $this->StyleModel->delete_data($data);
        echo json_encode(array('success' => true, 'message' => $data['heading'] . ' deleted successfully'));
    }
}
