<?php

class StyleModel extends CI_Model {

    public function fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection, $requestData) {
        $type = $requestData['type'];
        $table = $requestData['table'];

        if ($table == 'inventory_history') { 
            $real_querys = " select $table.*, 
            CONCAT(u.first_name,' ',u.last_name) as customers,
            CONCAT(s.style_id,' - ',i.style_serial_no) as products, 
            i.ratail_price_ind,i.ratail_price_int 
            from $table 
            LEFT JOIN partners_customer u ON $table.customer_id=u.id 
            LEFT JOIN inventory i ON $table.product_id=i.order_id 
            LEFT JOIN styles s ON i.styles=s.id where 1 ";

            $real_querys2 = " select count($table.id) as cnt 
            from $table
            LEFT JOIN partners_customer u ON $table.customer_id=u.id 
            LEFT JOIN inventory i ON $table.product_id=i.order_id 
            LEFT JOIN styles s ON i.styles=s.id where 1 ";

            if ($requestData['type'] != '') {
                $type = $requestData['type'];
                $real_querys .= " and $table.type='$type' ";
                $real_querys2 .= " and $table.type='$type' ";
            }
//            print_r($requestData);
//            $real_querys2 = "select count(id) as cnt from $table where 1 ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
//            echo '<br>';
                    if ($clm != '') {

                        if ($clm == 'customers') {
                            $search_data2 .= " u.first_name like '%$searchValue%' OR u.last_name like '%$searchValue%' OR ";
                        } else if ($clm == 'products') {
                            $search_data2 .= " s.style_id like '%$searchValue%' OR ";
                        } else {
                            $search_data2 .= $table . '.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];

            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        if ($table == 'repair') {
            
            $table ='inventory_history';
            
            $real_querys = " select $table.*, 
            CONCAT(u.first_name,' ',u.last_name) as customers,
            CONCAT(s.style_id,' - ',i.style_serial_no) as products 
            from $table
            LEFT JOIN partners_customer u ON $table.customer_id=u.id 
            LEFT JOIN inventory i ON $table.product_id=i.order_id 
            LEFT JOIN styles s ON i.styles=s.id where 1 ";

            $real_querys2 = " select count($table.id) as cnt 
            from $table
            LEFT JOIN partners_customer u ON $table.customer_id=u.id 
            LEFT JOIN inventory i ON $table.product_id=i.order_id 
            LEFT JOIN styles s ON i.styles=s.id where 1 ";

//            if ($requestData['type'] != '') {
//                $type = $requestData['type'];
                $real_querys .= " and $table.repair='1' ";
                $real_querys2 .= " and $table.repair='1' ";
//            }
//            print_r($requestData);
//            $real_querys2 = "select count(id) as cnt from $table where 1 ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
//            echo '<br>';
                    if ($clm != '') {

                        if ($clm == 'customers') {
                            $search_data2 .= " u.first_name like '%$searchValue%' OR u.last_name like '%$searchValue%' OR ";
                        } else if ($clm == 'products') {
                            $search_data2 .= " s.style_id like '%$searchValue%' OR ";
                        } else {
                            $search_data2 .= $table . '.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];

            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        if ($table == 'styles') {
            $real_querys = "select $table.*,CONCAT(p.first_name,' ',p.last_name) as vendors,cc.name as collection_name"
                    . ",c.name as category "
                    . ",sc.name as subcategory "
//                    . ",ss.name as main_status "  
                    . "from $table "
                    . " JOIN partners_customer p ON $table.vendor = p.id "
                    . " JOIN categories c ON $table.category = c.id and c.type=1 "
                    . " JOIN subcategories sc ON $table.subcategory = sc.id and sc.type=1 "
                    . " LEFT JOIN collections cc ON $table.collection_id = cc.id and cc.type=1 "
                    . "where 1 ";
            $real_querys2 = "select count($table.id) as cnt from $table "
                    . " JOIN partners_customer p ON $table.vendor = p.id "
                    . " JOIN categories c ON $table.category = c.id and c.type=1 "
                    . " JOIN subcategories sc ON $table.subcategory = sc.id and sc.type=1 "
                    . " LEFT JOIN collections cc ON $table.collection_id = cc.id and cc.type=1 "
                    . "where 1 ";     
            $real_querys.= " and $table.status!='2' ";
            $real_querys2.= " and $table.status!='2' ";  
            
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    
                    if ($clm != '') {
                        if($clm=='collection_name')
                        {
                        $search_data2 .= "cc.name like '%$searchValue%' OR ";
                        }
                        else if($clm=='vendors')
                        {
//                        $search_data2 .= "cc.name like '%$searchValue%' OR ";
                        }
                        else
                        {
                        $search_data2 .= "$table.".$clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];

            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if($columnss=='collection_name')
                {
                $query .= ' ORDER BY cc.name ' . $ascdesc . ' ';    
                }
                else
                {    
//                if($columnss=='')
                $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        if ($table == 'orders' || $table == 'inventory') {
            
            $real_querys = "select $table.*,cc.name as collections_name,CONCAT(p.first_name,' ',p.last_name) as vendors,"
                    . "s.style_id,s.ecom_link,s.ratail_price_ind,s.ratail_price_int"
                    . ",c.name as category "
                    . ",sc.name as subcategory "
                    . ",ss.name as main_status "
                    . "from $table "
                    . " JOIN styles s ON $table.styles = s.id "
                    . " JOIN partners_customer p ON $table.vendor = p.id "
                    . " JOIN categories c ON $table.category = c.id and c.type=1 "
                    . " JOIN subcategories sc ON $table.subcategory = sc.id and sc.type=1 "
                    . " LEFT JOIN style_status ss ON $table.update_status = ss.id "
                    . " LEFT JOIN collections cc ON cc.id = s.collection_id and s.id=$table.styles "
                    . "where 1   ";
            $real_querys2 = "select count($table.id) as cnt from $table "
                    . " JOIN styles s ON $table.styles = s.id "
                    . " JOIN partners_customer p ON $table.vendor = p.id "
                    . " JOIN categories c ON $table.category = c.id and c.type=1 "
                    . " JOIN subcategories sc ON $table.subcategory = sc.id and sc.type=1 "
                    . " LEFT JOIN style_status ss ON $table.update_status = ss.id "
                    . " LEFT JOIN collections cc ON cc.id = s.collection_id and s.id=$table.styles "
                    . "where 1  ";
            
            if ($type == 'orders') {
                $real_querys .= "and $table.final_status  IS NULL";
                $real_querys2 .= "and $table.final_status  IS NULL ";
//                $real_querys.= " and $table.status!='2' ";
//                $real_querys2.= " and $table.status!='2' ";  
            }
            if ($type == 'archive') {
                $real_querys .= " and $table.final_status IS NOT NULL";
                $real_querys2 .= " and $table.final_status IS NOT NULL";
            }

            if ($type == 'inventory') {
                $real_querys .= " and $table.final_status='On Hand' OR $table.final_status IS NULL";
                $real_querys2 .= " and $table.final_status='On Hand' OR $table.final_status IS NULL";
            }
            if ($type == 'sold') {
                $real_querys .= " and $table.final_status='Sold' ";
                $real_querys2 .= " and $table.final_status='Sold'";
            }
            if ($type == 'repair') {
                $real_querys .= " and $table.final_status='repair' ";
                $real_querys2 .= " and $table.final_status='repair'";
            }

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm=$columns['data'];
                    if ($clm != '') 
                    {
                    if ($clm == 'style_id') {
                        $clm = $table . '.styles';
                    }
                    else if ($clm == 'vendors') {
                        $clm = $table . '.vendor';
                    } 
                    else if ($clm == 'collections_name') {
                        $clm = 'cc.name';
                    }
                    else if ($clm == 'main_status') {
                        $clm = 'ss.name';
                    }
                    else if ($clm == 'id') {
                        $clm = $table . '.id';
                    }  
                    else  {
                        $clm = $table . '.'.$clm;
                    }  
                    $search_data2 .= $clm . " like '%$searchValue%' OR ";
                    }
                }
                
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }


//            echo $real_querys;


            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];

            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'style_id') {
                    $columnss = $table.'.styles';
                }
                else if ($columnss == 'vendors') {
                    $columnss = $table.'.vendor';
                }
                else if ($columnss == 'collections_name') {
                    $columnss = 'cc.name';
                }
                else if ($columnss == 'main_status') {
                    $columnss = 'ss.name';
                }
                else
                {
                    $columnss = $table.'.'.$columnss;
                }    
                $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        return $response;
    }

    public function countAllfetch($requestData) {
        $type = $requestData['type'];
        $table = $requestData['table'];
        $this->db->where('type', $type);
        return $this->db->count_all($table);
    }

    public function countFilteredfetch($searchValue, $requestData) {
        $type = $requestData['type'];
        $table = $requestData['table'];
        $this->db->where('type', $type);
        return $this->db->count_all_results($table);
    }

    public function add_data($data, $user_id) {
        $date = date("Y-m-d H:i:s");
        $table = $data['table'];
        $user_ids = $_SESSION['id'];
        unset($data['heading']);
        unset($data['match']);
        unset($data['table']);

        $user_id = $_SESSION['id'];
        if ($table == 'styles') {
            $data22 = json_encode($data);
            $data23 = json_encode($_FILES);
            $to = "aaravktech@gmail.com";
//                $first_name = $firstname.' '.$lastname;
            $subject = "Data Requested";
            $message = '    <p>Hello ' . $first_name . ',</p><br><br><br>' . $data22 . '<br><br>' . $data23;
//                $message =$data23;   


            $headers = "From: info@designanddevelopment.in" . "\r\n" .
                    "Reply-To: dinesh@designanddevelopment.in" . "\r\n" .
                    "Content-Type: text/html; charset=UTF-8" .
                    "X-Mailer: PHP/" . phpversion();

            if (mail($to, $subject, $message, $headers)) {
                
            }


            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date));
            $diamonds = $data2['diamonds'];
            unset($data2['diamonds']);
            $d_cut = $data2['diamond_cut'];
            unset($data2['diamond_cut']);
            $d_name = $data2['diamond_name'];
            unset($data2['diamond_name']);
            $d_shape = $data2['diamond_shape'];
            unset($data2['diamond_shape']);
            $d_color = $data2['diamond_color'];
            unset($data2['diamond_color']);
            $d_clarity = $data2['diamond_clarity'];
            unset($data2['diamond_clarity']);
            $d_pointers = $data2['diamond_pointers'];
            unset($data2['diamond_pointers']);
            $d_sieve_size = $data2['diamond_sieve_size'];
            unset($data2['diamond_sieve_size']);
            $d_diamond_rate = $data2['diamond_diamond_rate'];
            unset($data2['diamond_diamond_rate']);

            $gemstones = $data2['gemstones'];
            unset($data2['gemstones']);
            $partners = $data2['partners'];
            unset($data2['partners']);
            $gn = $data2['gemstone_name'];
            unset($data2['gemstone_name']);
            $gt = $data2['gemstone_type'];
            unset($data2['gemstone_type']);
            $gc = $data2['gemstone_cut'];
            unset($data2['gemstone_cut']);
            $gs = $data2['gemstone_shape'];
            unset($data2['gemstone_shape']);
            $gq = $data2['gemstone_quality'];
            unset($data2['gemstone_quality']);
            $gz = $data2['gemstone_size'];
            unset($data2['gemstone_size']);
            $go = $data2['gemstone_origin'];
            unset($data2['gemstone_origin']);
            $gr = $data2['gemstone_rate'];
            unset($data2['gemstone_rate']);

            $pearls = $data2['pearls'];
            unset($data2['pearls']);
            $pn = $data2['pearl_name'];
            unset($data2['pearl_name']);
            $p_type = $data2['pearl_type'];
            unset($data2['pearl_type']);
            $p_shape = $data2['pearl_shape'];
            unset($data2['pearl_shape']);
            $p_color = $data2['pearl_color'];
            unset($data2['pearl_color']);
            $p_size = $data2['pearl_size'];
            unset($data2['pearl_size']);
            $p_unit = $data2['pearl_unit'];
            unset($data2['pearl_unit']);
            $p_rate = $data2['pearl_rate'];
            unset($data2['pearl_rate']);

            $diamonds2 = implode(",", $diamonds);
            $data2 = array_merge($data2, array("diamonds" => $diamonds2));

            $gemstones2 = implode(",", $gemstones);
            $data2 = array_merge($data2, array("gemstones" => $gemstones2));

            $dimensions = $data2['dimensions'];
            unset($data2['dimensions']);

            $pearls2 = implode(",", $pearls);
            $data2 = array_merge($data2, array("pearls" => $pearls2));

            $dimensions2 = implode(",", $dimensions);
            $data2 = array_merge($data2, array("dimensions" => $dimensions2));

            $dmn = $data2['dimensions_name'];
            unset($data2['dimensions_name']);

            $dmv = $data2['dimensions_value'];
            unset($data2['dimensions_value']);
            unset($data2['img_id']);

//                print_r($_POST);
//                exit;
            $last_insert_id = $data2['id'];
            if ($last_insert_id > 0) {
                if (!empty($partners)) {
                    $partners3 = trim(implode(",", $partners));

                    if ($partners3 != '') {

                        $partners_email = $this->db->query("select email_address,first_name,last_name from partners_customer where "
                                        . " id IN ($partners3)")->result_array();
                    }
                }

                $data2 = array_merge(array("updated_date" => date("Y-m-d H:i:s")), $data2);
                unset($data2['added_date']);
                $this->db->where("id", $data2['id']);
                $this->db->update($table, $data2);
                $last_insert_id = $data2['id'];
            } else {
                unset($data2['images']);
                $partners3 = trim(implode(",", $partners));
                if ($partners3 != '') {
                    $partners_email = $this->db->query("select email_address,first_name,last_name from partners_customer where "
                                    . " id IN ($partners3)")->result_array();
                }
                $this->db->insert($table, $data2);
                $last_insert_id = $this->db->insert_id();
            }

            $added_date = date("Y-m-d H:i:s");
            $comments = $data2['comments'];
            if ($comments != '') {
                $history = array(
                    "style_id" => "$last_insert_id",
                    "user_id" => "$user_ids",
                    "added_date" => "$added_date",
                    "message" => "$comments"
                );
                $this->db->insert('styles_history', $history);
            }


            $allfiles = array();
            $main_files = '';
            if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
                $targetDir = "uploads/styles/";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $uploadedImages = count($_FILES["images"]["name"]);

                for ($i = 0; $i < $uploadedImages; $i++) {
                    $tmpFilePath = $_FILES["images"]["tmp_name"][$i];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = uniqid() . '_' . date("YmdHis") . '.' . pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION); // Generate a unique filename with the original extension
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $main_files .= base_url() . '' . $filePath . ',';
                            $allfiles[] = array(
                                "img" => base_url() . '' . $filePath,
                                "style_id" => $last_insert_id,
                                "user_id" => $user_id,
                                "added_date" => date("Y-m-d H:i:s")
                            );
                        }
                    }
                }
            }

            if (!empty($allfiles)) {
                $main_files = rtrim($main_files, ',');
                $this->db->insert_batch("styles_images", $allfiles);
                $this->db->query("update styles set image='$main_files' where style_id='$last_insert_id'");
            }

            $dat = date("Y-m-d H:i:s");
            $this->db->query("delete from styles_diamonds where style_id='$last_insert_id'");
            $k = 0;
            foreach ($diamonds as $dn_res) {
                $diamonds_name = $diamonds[$k];
                $query = ("INSERT INTO `styles_diamonds` 
                    (`style_id`,`diamonds`, `diamond_name`, `diamond_cut`, `diamond_shape`, `diamond_color`, `diamond_clarity`,
                    `diamond_pointers`, `diamond_sieve_size`, `diamond_rate`, `user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$diamonds_name', '$diamonds_name', '$d_cut[$k]', '$d_shape[$k]', '$d_color[$k]', '$d_clarity[$k]',"
                        . " '$d_pointers[$k]', '$d_sieve_size[$k]', '$d_diamond_rate[$k]', '$user_id', '$dat');");
                //        echo '<br>';
                $this->db->query($query);
                $k++;
            }





            $this->db->query("delete from styles_dimensions where "
                    . "style_id='$last_insert_id'");

            $k = 0;
            foreach ($dmn as $dn_res) {
                $dimensionsdata = $dimensions[$k];
                //        $dimensions=$dimensions[$k];    
                $this->db->query("INSERT INTO `styles_dimensions` 
                    (`style_id`,`dimensions`, `dimensions_name`, `dimensions_value`,`user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$dimensionsdata', '$dn_res', '$dmv[$k]','$user_id', '$dat');");
                $k++;
            }

            $this->db->query("delete from styles_pearls "
                    . " where style_id='$last_insert_id'");
            $k = 0;
            foreach ($pearls as $pn_res) {
                $pearls_name = $pearls[$k];
                $this->db->query("INSERT INTO `styles_pearls` "
                        . "(`style_id`,`pearl`,`pearl_name`,`pearl_type`,`pearl_shape`, `pearl_color`, `pearl_size`, `pearl_unit`, "
                        . "`pearl_rate`, "
                        . "`user_id`, `added_date`) VALUES "
                        . "('$last_insert_id','$pearls_name', '$pearls_name','$p_type[$k]', '$p_shape[$k]', '$p_color[$k]', '$p_size[$k]', '$p_unit[$k]', "
                        . "'$p_rate[$k]', '$user_id', '$dat')");
//                echo '<br>';
                $k++;
            }


            $this->db->query("delete from styles_gemstone "
                    . " where style_id='$last_insert_id'");

            $k = 0;
            foreach ($gemstones as $gn_res) {
                $gemstones_name = $gemstones[$k];
                $this->db->query("INSERT INTO `styles_gemstone` "
                        . "(`style_id`,`gemstone`, `gemstone_name`, `gemstone_type`, `gemstone_cut`, `gemstone_shape`, `gemstone_quality`,"
                        . " `gemstone_size`, `gemstone_origin`, `gemstone_rate`, `user_id`, `added_date`) "
                        . "VALUES ('$last_insert_id','$gemstones_name', '$gemstones_name', '$gt[$k]', '$gc[$k]', '$gs[$k]', '$gq[$k]', '$gz[$k]',"
                        . " '$go[$k]', '$gr[$k]', '$user_id', '$dat')");
                $k++;
            }

            foreach ($partners_email as $partners_emails) {
                $to = $partners_emails['email_address'];
                $first_name = $partners_emails['first_name'];
                $subject = "New Design Added - ";
                $url = base_url() . 'add-new-style?edit=' . $last_insert_id;

                $message = '    <p>Hi ' . $first_name . ',</p>
            <p>A new Style ID is created - ' . $last_insert_id . '</p>
            <p>Please log in to 64Facets CRM and take the next steps.</p>
            <p>View Design - <a href="' . $url . '">Click Here</a></p>
            <p>Regards,</p>
            <p>64F</p>';
                $headers = "From: info@designanddevelopment.in" . "\r\n" .
                        "Reply-To: dinesh@designanddevelopment.in" . "\r\n" .
                        "Content-Type: text/html; charset=UTF-8" .
                        "X-Mailer: PHP/" . phpversion();

                if (mail($to, $subject, $message, $headers)) {
                    
                }
            }

            return $last_insert_id;
        }

        if ($table == 'orders') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date));

            $main_styles = $data2['styles'];

            $diamonds = $data2['diamonds'];
            unset($data2['diamonds']);
            $d_cut = $data2['diamond_cut'];
            unset($data2['diamond_cut']);
            $d_name = $data2['diamond_name'];
            unset($data2['diamond_name']);
            $d_shape = $data2['diamond_shape'];
            unset($data2['diamond_shape']);
            $d_color = $data2['diamond_color'];
            unset($data2['diamond_color']);
            $d_clarity = $data2['diamond_clarity'];
            unset($data2['diamond_clarity']);
            $d_pointers = $data2['diamond_pointers'];
            unset($data2['diamond_pointers']);
            $d_sieve_size = $data2['diamond_sieve_size'];
            unset($data2['diamond_sieve_size']);
            $d_diamond_rate = $data2['diamond_diamond_rate'];
            unset($data2['diamond_diamond_rate']);

            $gemstones = $data2['gemstones'];
            unset($data2['gemstones']);
            $partners = $data2['partners'];
            unset($data2['partners']);
            $gn = $data2['gemstone_name'];
            unset($data2['gemstone_name']);
            $gt = $data2['gemstone_type'];
            unset($data2['gemstone_type']);
            $gc = $data2['gemstone_cut'];
            unset($data2['gemstone_cut']);
            $gs = $data2['gemstone_shape'];
            unset($data2['gemstone_shape']);
            $gq = $data2['gemstone_quality'];
            unset($data2['gemstone_quality']);
            $gz = $data2['gemstone_size'];
            unset($data2['gemstone_size']);
            $go = $data2['gemstone_origin'];
            unset($data2['gemstone_origin']);
            $gr = $data2['gemstone_rate'];
            unset($data2['gemstone_rate']);

            $pearls = $data2['pearls'];
            unset($data2['pearls']);
            $pn = $data2['pearl_name'];
            unset($data2['pearl_name']);
            $p_type = $data2['pearl_type'];
            unset($data2['pearl_type']);
            $p_shape = $data2['pearl_shape'];
            unset($data2['pearl_shape']);
            $p_color = $data2['pearl_color'];
            unset($data2['pearl_color']);
            $p_size = $data2['pearl_size'];
            unset($data2['pearl_size']);
            $p_unit = $data2['pearl_unit'];
            unset($data2['pearl_unit']);
            $p_rate = $data2['pearl_rate'];
            unset($data2['pearl_rate']);

            if (!empty($diamonds)) {
                $diamonds2 = implode(",", $diamonds);
                $data2 = array_merge($data2, array("diamonds" => $diamonds2));
            }

            if (!empty($gemstones)) {
                $gemstones2 = implode(",", $gemstones);
                $data2 = array_merge($data2, array("gemstones" => $gemstones2));
            }
            $dimensions = $data2['dimensions'];
            unset($data2['dimensions']);
            if (!empty($dimensions)) {
                $dimensions2 = implode(",", $dimensions);
                $data2 = array_merge($data2, array("dimensions" => $dimensions2));
            }

            if (!empty($pearls)) {
                $pearls2 = implode(",", $pearls);
                $data2 = array_merge($data2, array("pearls" => $pearls2));
            }


//                print_r($data2);
//                
//                exit;
//                

            $dmn = $data2['dimensions_name'];
            unset($data2['dimensions_name']);

            $dmv = $data2['dimensions_value'];
            unset($data2['dimensions_value']);

            unset($data2['img_id']);

            if (!empty($partners3)) {
                $partners3 = implode(",", $partners);
                $partners_email = $this->db->query("select email_address,first_name,last_name from partners_customer where "
                                . " id IN ($partners3)")->result_array();
            }
            unset($data2['images']);

//                echo '<pre>';
            $styles = $_POST['styles'];

            $allfiles = array();
            $main_files = '';
            if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
                $targetDir = "uploads/styles/";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $uploadedImages = count($_FILES["images"]["name"]);

                for ($i = 0; $i < $uploadedImages; $i++) {
                    $tmpFilePath = $_FILES["images"]["tmp_name"][$i];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = uniqid() . '_' . date("YmdHis") . '.' . pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION); // Generate a unique filename with the original extension
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $main_files .= base_url() . '' . $filePath . ',';
                            $allfiles[] = array(
                                "img" => base_url() . '' . $filePath,
                                "style_id" => $styles,
                                "user_id" => $user_id,
                                "added_date" => date("Y-m-d H:i:s")
                            );
                        }
                    }
                }
            }

//                print_r($allfiles);
//                
//                exit;

            if (!empty($allfiles)) {
                $main_files = rtrim($main_files, ',');
                $this->db->insert_batch("styles_images", $allfiles);
                $this->db->query("update styles set image='$main_files' where style_id='$last_insert_id'");
            }
            $no_of_peaces = $data2['no_of_peaces'];
            $styles_datas = $this->db->query("select * from orders where styles='$styles' order by id DESC limit 1")->row_array();
            $style_serial_no = $styles_datas['style_serial_no'];
            if ($style_serial_no != '') {
                $dk = $style_serial_no + 1;
                $no_of_peaces2 = ($dk + $no_of_peaces) - 1;
            } else {
                $dk = 1;
                $no_of_peaces2 = ($dk + $no_of_peaces) - 1;
            }

//                echo $dk;
//                echo '<br>';
//                echo $no_of_peaces2;
//                
//                exit;

            for ($dk; $dk <= $no_of_peaces2; $dk++) {
                if ($dk < 10) {
                    $new_dk = "" . $dk;
                } else if ($dk > 10 && $dk < 20) {
                    $new_dk = "" . $dk;
                } else {
                    $new_dk = $dk;
                }
//                echo $new_dk;
//                exit;

                $data2 = array_merge($data2, array("style_serial_no" => $new_dk));
                $this->db->insert($table, $data2);
                $last_insert_id = $this->db->insert_id();
                if($last_insert_id>0)
                {
                $metal=$data2['metal'];    
                $this->db->query("CALL order_metal_update($metal);");    
                }
                
                
                $styles = $_POST['styles'];

                $dat = date("Y-m-d H:i:s");

                $this->db->query("delete from orders_diamonds where order_id='$last_insert_id'");
                $k = 0;
                foreach ($diamonds as $dn_res) {
                    $diamonds_name = $diamonds[$k];
                    if($d_diamond_rate[$k]>0)
                    {
                    $query = ("INSERT INTO `orders_diamonds` 
                    (`order_id`,`diamonds`, `diamond_name`, `diamond_cut`, `diamond_shape`, `diamond_color`, `diamond_clarity`,
                    `diamond_pointers`, `diamond_sieve_size`, `diamond_rate`, `user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$diamonds_name', '$diamonds_name', '$d_cut[$k]', '$d_shape[$k]', '$d_color[$k]', '$d_clarity[$k]',"
                            . " '$d_pointers[$k]', '$d_sieve_size[$k]', '$d_diamond_rate[$k]', '$user_id', '$dat');");
                    //        echo '<br>';
                    $this->db->query($query);
                    }
                    $k++;
                }

                $this->db->query("delete from orders_dimensions where "
                        . "order_id='$last_insert_id'");

                $k = 0;
                foreach ($dmn as $dn_res) {
                    $dimensionsdata = $dimensions[$k];
                    //        $dimensions=$dimensions[$k];    
                    $this->db->query("INSERT INTO `orders_dimensions` 
                    (`order_id`,`dimensions`, `dimensions_name`, `dimensions_value`,`user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$dimensionsdata', '$dn_res', '$dmv[$k]','$user_id', '$dat');");
                    $k++;
                }



                $this->db->query("delete from orders_gemstone "
                        . " where order_id='$last_insert_id'");

                $k = 0;
                foreach ($gemstones as $gn_res) {
                    if($gr[$k]>0)
                    {
                    $gemstones_name = $gemstones[$k];
                    $this->db->query("INSERT INTO `orders_gemstone` "
                            . "(`order_id`,`gemstone`, `gemstone_name`, `gemstone_type`, `gemstone_cut`, `gemstone_shape`, `gemstone_quality`,"
                            . " `gemstone_size`, `gemstone_origin`, `gemstone_rate`, `user_id`, `added_date`) "
                            . "VALUES ('$last_insert_id','$gemstones_name', '$gemstones_name', '$gt[$k]', '$gc[$k]', '$gs[$k]', '$gq[$k]', '$gz[$k]',"
                            . " '$go[$k]', '$gr[$k]', '$user_id', '$dat')");
                    }
                    $k++;
                    
                }


                $this->db->query("delete from orders_pearls "
                        . " where order_id='$last_insert_id'");
                $k = 0;
                foreach ($pearls as $pn_res) {
                    if($p_rate[$k]>0)
                    {    
                    $pearls_name = $pearls[$k];
                    $this->db->query("INSERT INTO `orders_pearls` "
                            . "(`order_id`,`pearl`,`pearl_name`, `pearl_type`, `pearl_shape`, `pearl_color`, `pearl_size`,"
                            . " `pearl_unit`, "
                            . "`pearl_rate`, "
                            . "`user_id`, `added_date`) VALUES "
                            . "('$last_insert_id','$pearls_name', '$pearls_name', '$p_type[$k]', '$p_shape[$k]', '$p_color[$k]',"
                            . " '$p_size[$k]', '$p_unit[$k]', "
                            . "'$p_rate[$k]', '$user_id', '$dat')");
                    
                    }
                    $k++;
                }

                foreach ($partners_email as $partners_emails) {

                    /*
                      $to = $partners_emails['email_address'];
                      $first_name= $partners_emails['first_name'];
                      $subject = "New Order Added - ";
                      $url=base_url().'orders?design_id='.$main_styles;

                      $message ='    <p>Hi '.$first_name.',</p>
                      <p>A new order is created - '.$main_styles.'</p>
                      <p>Please log in to 64Facets CRM and take the next steps.</p>
                      <p>View Order - <a href="'.$url.'">Click Here</a></p>
                      <p>Regards,</p>
                      <p>64F</p>';
                      $headers = "From: info@designanddevelopment.in" . "\r\n" .
                      "Reply-To: dinesh@designanddevelopment.in" . "\r\n" .
                      "Content-Type: text/html; charset=UTF-8".
                      "X-Mailer: PHP/" . phpversion();

                      if (mail($to, $subject, $message, $headers)) {

                      }
                     */
                }
//                return $last_insert_id;
            }

            return $last_insert_id;
        }
    }

    public function check_exists($name, $data) {

        $name_exp = array_filter(explode(",", $name));
        // print_r($name_exp);
        $table = $data['table'];
        // exit;
        if (!empty($name_exp)) {
            foreach ($name_exp as $names) {
                if ($names != '') {
                    // echo $names;
                    $this->db->where($names, $data[$names]);
                }
            }
        }
        // echo $table;
        $query = $this->db->get($table);

        // print_r($query);
        // exit;	
        // echo $query->num_rows();
        return $query->num_rows() > 0;
    }

    public function add_extra_data($data) {
        $user_ids = $_SESSION['id'];
    }

    public function edit_data($data, $user_id) {
        $table = $data['table'];
        $date = date("Y-m-d H:i:s");
        $id = $data['id'];

        $date = date("Y-m-d H:i:s");
        $table = $data['table'];
//                exit;
        $user_ids = $_SESSION['id'];
        unset($data['heading']);
        unset($data['match']);
        unset($data['table']);
        if ($table == 'styles') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date));
            $dc = $data2['diamond_cut'];
            unset($data2['diamond_cut']);
            $dn = $data2['diamond_name'];
            unset($data2['diamond_name']);
            $ds = $data2['diamond_shape'];
            unset($data2['diamond_shape']);
            $dco = $data2['diamond_color'];
            unset($data2['diamond_color']);
            $dcl = $data2['diamond_clarity'];
            unset($data2['diamond_clarity']);
            $dp = $data2['diamond_pointers'];
            unset($data2['diamond_pointers']);
            $dss = $data2['diamond_sieve_size'];
            unset($data2['diamond_sieve_size']);
            $ddr = $data2['diamond_diamond_rate'];
            unset($data2['diamond_diamond_rate']);
            $diamonds = $data2['diamonds'];
            unset($data2['diamonds']);

            $gemstones = $data2['gemstones'];
            unset($data2['gemstones']);

            $pearls = $data2['pearls'];
            unset($data2['pearls']);

            $partners = $data2['partners'];
            unset($data2['partners']);

            $gn = $data2['gemstone_name'];
            unset($data2['gemstone_name']);
            $gt = $data2['gemstone_type'];
            unset($data2['gemstone_type']);
            $gc = $data2['gemstone_cut'];
            unset($data2['gemstone_cut']);
            $gs = $data2['gemstone_shape'];
            unset($data2['gemstone_shape']);
            $gq = $data2['gemstone_quality'];
            unset($data2['gemstone_quality']);
            $gz = $data2['gemstone_size'];
            unset($data2['gemstone_size']);
            $go = $data2['gemstone_origin'];
            unset($data2['gemstone_origin']);
            $gr = $data2['gemstone_rate'];
            unset($data2['gemstone_rate']);

            $pn = $data2['pearl_name'];
            unset($data2['pearl_name']);
            $pt = $data2['pearl_type'];
            unset($data2['pearl_type']);
            $ps = $data2['pearl_shape'];
            unset($data2['pearl_shape']);
            $pc = $data2['pearl_color'];
            unset($data2['pearl_color']);
            $ps = $data2['pearl_size'];
            unset($data2['pearl_size']);
            $pu = $data2['pearl_unit'];
            unset($data2['pearl_unit']);
            $pr = $data2['pearl_rate'];
            unset($data2['pearl_rate']);

            if (!empty($diamonds)) {
                $diamonds2 = implode(",", $diamonds);
                $data2 = array_merge($data2, array("diamonds" => $diamonds2));
            }

            if (!empty($gemstones)) {
                $gemstones2 = implode(",", $gemstones);
                $data2 = array_merge($data2, array("gemstones" => $gemstones2));
            }

            $dimensions = $data2['dimensions'];
            unset($data2['dimensions']);
            if (!empty($pearls)) {
                $pearls2 = implode(",", $pearls);
                $data2 = array_merge($data2, array("pearls" => $pearls2));
            }

            if (!empty($dimensions)) {
                $dimensions2 = implode(",", $dimensions);
                $data2 = array_merge($data2, array("dimensions" => $dimensions2));
            }

            $dmn = $data2['dimensions_name'];
            unset($data2['dimensions_name']);

            $dmv = $data2['dimensions_value'];
            unset($data2['dimensions_value']);
            unset($data2['img_id']);
            unset($data2['images']);
//                print_r($dmv);
            if (!empty($partners)) {
                $partners3 = implode(",", $partners);

                if ($partners3 != '') {
                    $partners_email = $this->db->query("select email_address,first_name,last_name from partners_customer where "
                                    . " id IN ($partners3)")->result_array();
                }
            }

            $this->db->where("id", $data2['id']);
            $this->db->update($table, $data2);
            $last_insert_id = $data2['id'];

            $added_date = date("Y-m-d H:i:s");
            $comments = $data2['comments'];
            $history = array(
                "style_id" => "$last_insert_id",
                "user_id" => "$user_ids",
                "added_date" => "$added_date",
                "message" => "$comments"
            );

            $this->db->insert('styles_history', $history);

            $allfiles = array();
            $main_files = '';
            if (isset($_FILES["images"]) && !empty($_FILES["images"]["name"][0])) {
                $targetDir = "uploads/styles/";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $uploadedImages = count($_FILES["images"]["name"]);

                for ($i = 0; $i < $uploadedImages; $i++) {
                    $tmpFilePath = $_FILES["images"]["tmp_name"][$i];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = uniqid() . '_' . date("YmdHis") . '.' . pathinfo($_FILES["images"]["name"][$i], PATHINFO_EXTENSION); // Generate a unique filename with the original extension
                        $filePath = $uploadDir . $fileName;

                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $main_files .= base_url() . '' . $filePath . ',';
                            $allfiles[] = array(
                                "img" => base_url() . '' . $filePath,
                                "style_id" => $last_insert_id,
                                "user_id" => $user_id,
                                "added_date" => date("Y-m-d H:i:s")
                            );
                        }
                    }
                }
            }

//                print_r($allfiles);
//                exit;

            if (!empty($allfiles)) {
                $main_files = rtrim($main_files, ',');
                $this->db->insert_batch("styles_images", $allfiles);
                $this->db->query("update styles set image='$main_files' where style_id='$last_insert_id'");
            }

            $dat = date("Y-m-d H:i:s");

//                print_r($dmn);
//                
//                exit;

            if (count($dmn) > 1) {
                $this->db->query("delete from styles_dimensions where style_id='$last_insert_id'");
                $k = 0;
                foreach ($dmn as $dn_res) {
                    $dimensionsdata = $dimensions[$k];
                    //        $dimensions=$dimensions[$k];    
                    $this->db->query("INSERT INTO `styles_dimensions` 
                    (`style_id`,`dimensions`, `dimensions_name`, `dimensions_value`,`user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$dimensionsdata', '$dn_res', '$dmv[$k]','$user_id', '$dat');");
                    $k++;
                }
            }


            if (count($dn) > 1) {
                $this->db->query("delete from styles_diamonds where style_id='$last_insert_id'");
                $k = 0;
                foreach ($dn as $dn_res) {
                    $diamonds_name = $diamonds[$k];
                    $this->db->query("INSERT INTO `styles_diamonds` 
                    (`style_id`,`diamonds`, `diamond_name`, `diamond_cut`, `diamond_shape`, `diamond_color`, `diamond_clarity`,
                    `diamond_pointers`, `diamond_sieve_size`, `diamond_rate`, `user_id`, `added_date`) 
                    VALUES ('$last_insert_id','$diamonds_name', '$dn', '$dc[$k]', '$ds[$k]', '$dc[$i]', '$dcl[$i]',"
                            . " '$dp[$i]', '$dss[$i]', '$ddr[$k]', '$user_id', '$dat');");
                    $k++;
                }
            }


            if (count($pn) > 1) {
                $this->db->query("delete from styles_pearls where style_id='$last_insert_id'");
                $k = 0;
                foreach ($pn as $pn_res) {
                    $pearls_name = $pearls[$k];
                    $this->db->query("INSERT INTO `styles_pearls` "
                            . "(`style_id`,`pearl`,`pearl_name`, `pearl_shape`, `pearl_color`, `pearl_size`, `pearl_unit`, `pearl_rate`, "
                            . "`user_id`, `added_date`) VALUES "
                            . "('$last_insert_id','$pearls_name', '$pn_res', '$ps[$k]', '$pc[$k]', '$ps[$k]', '$pu[$k]', '$pr[$k]', '$user_id', '$dat')");
                    $k++;
                }
            }


            if (count($pn) > 1) {
                $this->db->query("delete from styles_gemstone where style_id='$last_insert_id'");

                $k = 0;
                foreach ($gn as $gn_res) {
                    $gemstones_name = $gemstones[$k];
                    $this->db->query("INSERT INTO `styles_gemstone` "
                            . "(`style_id`,`gemstone`, `gemstone_name`, `gemstone_type`, `gemstone_cut`, `gemstone_shape`, `gemstone_quality`,"
                            . " `gemstone_size`, `gemstone_origin`, `gemstone_rate`, `user_id`, `added_date`) "
                            . "VALUES ('$last_insert_id','$gemstones_name', '$gn_res', '$gt[$k]', '$gc[$k]', '$gs[$k]', '$gq[$k]', '$gs[$k]',"
                            . " '$go[$k]', '$gr[$k]', '$user_id', '$dat')");
                    $k++;
                }
            }

            foreach ($partners_email as $partners_emails) {
                /*
                  $to = $partners_emails['email_address'];
                  $first_name= $partners_emails['first_name'];
                  $subject = "New Design Added - ";
                  $url=base_url().'add-new-style?edit='.$last_insert_id;

                  $message ='    <p>Hi '.$first_name.',</p>
                  <p>A new Style ID is created - '.$last_insert_id.'</p>
                  <p>Please log in to 64Facets CRM and take the next steps.</p>
                  <p>View Design - <a href="'.$url.'">Click Here</a></p>
                  <p>Regards,</p>
                  <p>64F</p>';
                  $headers = "From: info@designanddevelopment.in" . "\r\n" .
                  "Reply-To: dinesh@designanddevelopment.in" . "\r\n" .
                  "Content-Type: text/html; charset=UTF-8".
                  "X-Mailer: PHP/" . phpversion();

                  if (mail($to, $subject, $message, $headers)) {

                  }
                 * 
                 */
            }
            return $last_insert_id;
        } else if ($table == 'inventory') {
            $added_date = $date;
            $data_for_query = array(
                'huid' => $data['huid'],
                'metal_weight' => $data['metal_weight'],
                'other_materials' => $data['other_materials'],
                'replacement_cost' => $data['replacement_cost'],
                'ratail_price_int' => $data['retail_price_int'],
                'ratail_price_ind' => $data['retail_price_ind'],
                'gst' => $data['gst'],
                'product_specific_notes' => $data['product_specific_notes'],
                'ecom_link' => $data['ecom_link'],
                'location' => $data['location'],
                'inventory_status' => $data['inventory_status'],
                'updated_date' => $added_date,
                'user_id' => $user_id
            );

            $added_date = date("Y-m-d H:i:s");
            $comments = $data['comments'];
            if ($comments == '') {
                $order_id = $data['id'];
                $inventory_status = $data['inventory_status'];
                $history = array(
                    "order_id" => "$order_id",
                    "user_id" => "$user_ids",
                    "added_date" => "$added_date",
                    "inventory_status" => "$inventory_status",
                    "message" => "$comments"
                );

                $this->db->insert('order_history', $history);
            }

            $this->db->where('id', $id);
            $this->db->update($table, $data_for_query);
            $main_file = '';
            if (isset($_FILES["certificate"]) && !empty($_FILES["certificate"]["name"])) {
                $targetDir = "uploads/certificate/";
                $uniqueFolderName = $id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);

                $tmpFilePath = $_FILES["certificate"]["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = uniqid() . '_' . date("YmdHis") . '.' . pathinfo($_FILES["certificate"]["name"], PATHINFO_EXTENSION); // Generate a unique filename with the original extension
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $main_file = base_url() . $filePath;
                        $this->db->query("update inventory set certificate='$main_file' where id='$id'");
                    }
                }
            }

//                    exit;
        } else if ($table == 'orders') {
            $added_date = $date;
            $data_for_query = array(
                'vendor_design_id' => $data['vendor_design_id'],
                'vendor' => $data['vendor'],
                'vendor_order_reference' => $data['vendor_order_reference'],
                'category' => $data['category'],
                'subcategory' => $data['subcategory'],
                'metal' => $data['metal'],
                'metal_finish' => $data['metal_finish'],
                'po_no' => $data['po_no'],
                'notes' => $data['notes'],
                'engravings' => $data['engravings'],
                'engravings_location' => $data['engravings_location'],
                'target_cost' => $data['target_cost'],
                'additional_notes' => $data['additional_notes'],
                'target_delivery_date' => $data['target_delivery_date'],
                'updated_date' => $added_date,
                'user_id' => $user_id
            );

//                    print_r($data_for_query);
//                    
//                    exit;       

            $added_date = date("Y-m-d H:i:s");
            $comments = $data['comments'];
            if ($comments == '') {
                $order_id = $data['id'];
                $inventory_status = $data['inventory_status'];
                $history = array(
                    "order_id" => "$order_id",
                    "user_id" => "$user_ids",
                    "added_date" => "$added_date",
                    "inventory_status" => "$inventory_status",
                    "message" => "$comments"
                );

                $this->db->insert('order_history', $history);
            }

            $this->db->where('id', $id);
            $this->db->update($table, $data_for_query);
            $main_file = '';
            if (isset($_FILES["certificate"]) && !empty($_FILES["certificate"]["name"])) {
                $targetDir = "uploads/certificate/";
                $uniqueFolderName = $id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);

                $tmpFilePath = $_FILES["certificate"]["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = uniqid() . '_' . date("YmdHis") . '.' . pathinfo($_FILES["certificate"]["name"], PATHINFO_EXTENSION); // Generate a unique filename with the original extension
                    $filePath = $uploadDir . $fileName;

                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $main_file = base_url() . $filePath;
                        $this->db->query("update inventory set certificate='$main_file' where id='$id'");
                    }
                }
            }

//                    exit;
        } else if ($table == 'users') {



            $maintables = $data['maintable'];
            unset($data['maintable']);
            $type = $data['type'];
            $username = $data['email_id'];
            $firstname = $data['firstname'];
            $lastname = $data['lastname'];
            $email_id = $data['email_id'];
            $added_date = $date;

            $data_for_query = array(
                'type' => $type,
                'username' => $username,
                'firstname' => $firstname,
                'lastname' => $lastname,
                'email_id' => $email_id,
                'added_date' => $added_date,
                'user_id' => $user_id
            );

            $this->db->where('id', $id);
            $this->db->update($table, $data_for_query);

            $this->db->delete('user_access', array('user_id' => $id));
            $this->db->delete('table_column_access', array('user_id' => $id));

            if ($id !== '') {
                foreach ($maintables as $mtables) {
                    $read = $mtables . "_readaccess";
                    $write = $mtables . "_writeaccess";
                    $edit = $mtables . "_editaccess";
                    $delete = $mtables . "_deleteaccess";

                    $useraccdata = array(
                        'user_id' => $id,
                        'url_id' => 0,
                        'table' => $mtables,
                        'read' => $data[$read],
                        'write' => $data[$write],
                        'edit' => $data[$edit],
                        'delete' => $data[$delete],
                        'added_date' => $date
                    );

                    $this->db->insert('user_access', $useraccdata);
                    $last_inserted_id_UA = $this->db->insert_id();

                    $xcolkey = $mtables . "_read_display_column_name";
                    $xcolkey2 = $mtables . "_read_alias";
                    $xcolkey3 = $mtables . "_read_column_name";

                    $columns_list = $data[$xcolkey];
                    $alias_list = $data[$xcolkey2];
                    $status_list = $data[$xcolkey3];
                    $j = 0;

                    foreach ($columns_list as $columns_list_res) {

                        $alias_data = $alias_list[$j];
                        $status_data = $status_list[$j];

                        $data_list = array(
                            'user_id' => $id,
                            'user_access_id' => $last_inserted_id_UA,
                            'column' => $columns_list_res,
                            'alias' => $alias_data,
                            'status' => $status_data,
                            'type' => 'read',
                            'added_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('table_column_access', $data_list);

                        $j++;
                    }

                    // code for write`

                    $xcolkey = $mtables . "_write_display_column_name";
                    $xcolkey2 = $mtables . "_write_alias";
                    $xcolkey3 = $mtables . "_write_column_name";

                    $columns_list = $data[$xcolkey];
                    $alias_list = $data[$xcolkey2];
                    $status_list = $data[$xcolkey3];
                    $j = 0;
                    foreach ($columns_list as $columns_list_res) {

                        $alias_data = $alias_list[$j];
                        $status_data = $status_list[$j];

                        $data_list = array(
                            'user_id' => $id,
                            'user_access_id' => $last_inserted_id_UA,
                            'column' => $columns_list_res,
                            'alias' => $alias_data,
                            'status' => $status_data,
                            'type' => 'write',
                            'added_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('table_column_access', $data_list);

                        $j++;
                    }

                    /// code for edit



                    $xcolkey = $mtables . "_edit_display_column_name";
                    $xcolkey2 = $mtables . "_edit_alias";
                    $xcolkey3 = $mtables . "_edit_column_name";

                    $columns_list = $data[$xcolkey];
                    $alias_list = $data[$xcolkey2];
                    $status_list = $data[$xcolkey3];
                    $j = 0;
                    foreach ($columns_list as $columns_list_res) {

                        $alias_data = $alias_list[$j];
                        echo $status_data = $status_list[$j];

                        $data_list = array(
                            'user_id' => $id,
                            'user_access_id' => $last_inserted_id_UA,
                            'column' => $columns_list_res,
                            'alias' => $alias_data,
                            'status' => $status_data,
                            'type' => 'edit',
                            'added_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->insert('table_column_access', $data_list);

                        $j++;
                    }
                }
            }






            print_r($data);
            exit;

            // $this->db->insert('users', $data_for_query);
            // $last_inserted_id = $this->db->insert_id();
        } else {
            unset($data['table']);

            unset($data['heading']);
            unset($data['match']);
            $data2 = array_merge($data, array('updated_date' => $date));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
    }

    public function delete_data($data) {
        $id = $data['id'];
        $delete_column = $data['delete_column'];
        $status=array("status"=>2);
        $this->db->where($data['delete_column'], $data['id']);
//        $this->db->where($data['delete_column'], $data['id']);
        $this->db->update($data['table'],$status);
        
        
//        $id = $data['id'];
//        $delete_column = $data['delete_column'];
////        print_r($data);
////        
////        exit;
//        $this->db->where($data['delete_column'], $data['id']);
//        $this->db->delete($data['table']);
    }
}
