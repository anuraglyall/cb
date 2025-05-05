<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Reports extends CI_Controller {

    
    public function stock_groupby() {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        if (empty($data)) {
            $data = $this->input->post();
        }
        $groupby_data = array();
        $groupby_data[] = array("id" => "Category", "name" => "Category");
        $groupby_data[] = array("id" => "Shape", "name" => "Shape");
        $groupby_data[] = array("id" => "Size", "name" => "Size");
        $groupby_data[] = array("id" => "Color", "name" => "Color");
        $groupby_data[] = array("id" => "Clarity", "name" => "Clarity");
        
//        
//        $result = array_merge(
//             array("groupby" => $groupby_data),                
//        );
//        
                $response = array(
            'data' => $groupby_data
        );
        echo json_encode($response);
        
        
//        $this->response(array(
//            "status" => 1,
//            "message" => "group by data",
//            "data" => $result
//                ), REST_Controller::HTTP_OK);
    }
        public function country_data() {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        if (empty($data)) {
            $data = $this->input->post();
        }
        $db2 = $this->load->database($data['db'], TRUE);        
        $query = $db2->select('countryid as id,countryname as name')->get('mcountry');        
        $data2 = $query->result();
        $query->free_result();        
        $db2->close();   
//        $result = array_merge(
//             array("country" => $data2),                
//        );
        
        
              $response = array(
                    'query'=>$query,
            'data' => $data2
        );
        echo json_encode($response);
        
//        $this->response(array(
//            "status" => 1,
//            "message" => "country data",
//            "data" => $result
//                ), REST_Controller::HTTP_OK);
    }    

        public function __construct() {
             parent::__construct();
//            $this->load->helper('cookie'); // Load the cookie helper
 
        }
     public function reports() {
        $jsonData = file_get_contents('php://input');
        $requestdata = json_decode($jsonData, true);
        if (empty($requestdata)) {
            $data = $this->input->post();
        }       
        $dbname = ($requestdata['db']);
        $type = ($requestdata['type']);
        $sql = '';
        if ($requestdata['search_customerid'] != '') 
        {
            $search_customerid=$requestdata['search_customerid'];
            $sql .= " and b.ac_code=$search_customerid";
        }
        if ($requestdata['search_categoryid'] != '') 
        {
            $search_categoryid=$requestdata['search_categoryid'];
            $sql .= " and d.categoryid=$search_categoryid";
        }
        if ($requestdata['search_shapeid'] != '') 
        {
            $search_shapeid=$requestdata['search_shapeid'];
            $sql .= " and d.shapeid=$search_shapeid ";
        }
        if ($requestdata['search_sizeid'] != '') 
        {
            $search_sizeid=$requestdata['search_sizeid'];
            $sql .= " and d.sizeid=$search_sizeid ";
        }
        if ($requestdata['search_colorid'] != '') 
        {
            $search_colorid=$requestdata['search_colorid'];
            $sql .= " and d.colorid=$search_colorid ";
        }
        if ($requestdata['search_clarityid'] != '') 
        {
            $search_clarityid=$requestdata['search_clarityid'];
            $sql .= " and d.clarityid=$search_clarityid ";
        }
        if ($requestdata['selecteddate'] != '' && $requestdata['selecteddatesecond'] != '') {
            $selecteddate=$requestdata['selecteddate'];
            $selecteddatesecond=$requestdata['selecteddatesecond'];
            
            $sql .= "  and a.invdate  between '$selecteddate' and  '$selecteddatesecond' ";         
        }
//        if ($invoiceno != '') {
//            $sql .= " a.invoiceno='$invoiceno' and ";
//        }
//        if ($todays == 'Today') {
//            $date = date("Y-m-d 00:00:00");
//            $date2 = date("Y-m-d 23:59:59");
//            $sql .= " a.invdate between '$date' and '$date2' and ";
//        }
//        if ($todays == 'Yesterday') {
//            $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
//            $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
//            // Calculate the timestamp for the end of yesterday
//            $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
//            $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);
//
//            $sql .= " a.invdate between '$endOfYesterday' and '$endOfYesterday' and ";
//        }
//        if ($todays == 'This Week') {
//       $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
//        $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
//        // Calculate the timestamp for the end of the week (Sunday)
//        $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
//        $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
//            $sql .= " a.invdate between '$startOfWeek' and '$endOfWeek' and ";
//        }       
//        if ($todays == 'This Month') {
//                $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
//                $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
//                // Calculate the timestamp for the end of the month
//                $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
//                $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
//                $sql .= " a.invdate between '$startOfMonth' and '$endOfMonth' and ";                
//        }        
//        if ($todays == 'This Year') {
//       // Calculate the timestamp for the start of the year
//                $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
//                $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
//                // Calculate the timestamp for the end of the year
//                $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
//                $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
//                $sql .= " a.invdate between '$startOfYear' and '$endOfYear' and ";        
//        }        


//        if ($code != '') {           
//            if($type=='SL')
//            {
//                $sql .= " a.doc_no in(select doc_no from trans1 where code='$code') and  ";
//            }else if($type=='CO')
//            {
//                 $sql .= " a.mainid in(select mainid from contran where code='$code') and  ";
//            }else if($type=='PF')
//            {
//                 $sql .= " a.mainid in(select mainid from tperforma where code='$code') and  ";
//            }
//        }   
//        if ($ascdesc == 'A') 
//        {           
//             $asc="Asc";
//        }
//        if ($ascdesc == 'D') 
//        {           
//             $asc="Desc";
//        }
        
//        if ($frmduedays != '' && $toduedays != '') {
//            $sql .= " a.duedays  between '$frmduedays' and  '$toduedays' and  ";
//        }
//        if ($frmamtrs != '' && $toamtrs != '') {
//            $sql .= " a.totalamt  between '$frmamtrs' and  '$toamtrs' and  ";
//        }
//        if ($frmamtusd != '' && $toamtusd != '') {
//            $sql .= " a.totalamt2  between '$frmamtusd' and  '$toamtusd' and  ";
//        }
//        if ($sql != '') {
//            $sql = rtrim($sql, " and  ");
//            if($type!='SL')
//            { 
//                $sql1 = " where " . $sql;
//            }else{
//                $sql1 = " where a.trn_type='SL' and " . $sql;
//            }            
//        }else 
//        {
//           if($type=='SL'){
//           $sql1 = " where a.trn_type='SL' " . $sql;
//           }
//        }   
        $maindb = $this->load->database($dbname, TRUE);
        $query = '';   
        
        if ($type=='CO')        
        {
            $query .= ("SELECT a.*,(SELECT SUM(qty) FROM trans1 WHERE contranid = a.contranid) AS sqty,(SELECT SUM(qty) FROM cnsret WHERE contranid = a.contranid) AS rqty,b.ac_code,b.invoiceno,b.description AS maindescr,IFNULL(c.ac_name, '') AS ac_name,IFNULL(c.ACID, 0) AS ACID,d.refno,d.cl_qty,d.certgrpid,d.op_amt,d.op_amt2,d.op_qty,IFNULL(d.descr,'') AS sdescr,d.categoryid,d.shapeid,d.colorid,d.clarityid,d.sizeid,IFNULL(e.shape, '') AS shape,IFNULL(f.size, '') AS size,IFNULL(g.color, '') AS color "
                .",IFNULL(h.clarity, '') AS clarity,IFNULL(i.category, '') AS category,IFNULL(j.symetry, '') AS symetry "
                .",IFNULL(k.polish, '') AS polish,IFNULL(l.cutname, '') AS cutname,IFNULL(m.NAME, '') AS SLNAME "
                .",(SELECT SUM(AMT)FROM trans1 WHERE code = a.code AND dcflag = 'D') AS DAMT,(SELECT SUM(AMT2) "
                ."FROM trans1 WHERE code = a.code AND dcflag = 'D') AS DAMT2,(SELECT SUM(qty)FROM trans1 " 
                ."WHERE code = a.code AND dcflag = 'D') AS drQTY,(SELECT MAX(INVDATE)FROM tbill WHERE contranid = a.contranid) AS dsldate,DATEDIFF(NOW(),b.invdate) AS days "
."FROM contran a LEFT OUTER JOIN maincon b ON a.mainid = b.mainid LEFT OUTER JOIN ledgmast c ON b.ac_code = c.ac_code "
."LEFT OUTER JOIN stock d ON a.code = d.code LEFT OUTER JOIN mshape e ON d.shapeid = e.shapeid "
."LEFT OUTER JOIN msize f ON d.sizeid = f.sizeid LEFT OUTER JOIN mcolor g ON d.colorid = g.colorid "
."LEFT OUTER JOIN mclarity h ON d.clarityid = h.clarityid LEFT OUTER JOIN mcategory i ON d.categoryid = i.categoryid "
."LEFT OUTER JOIN msymetry j ON d.symetryid = j.symetryid LEFT OUTER JOIN mpolish k ON d.polishID = k.polishid LEFT OUTER JOIN mcut l ON d.CUTID = l.cutid "
."LEFT OUTER JOIN acgrpname m ON c.acid = m.code WHERE a.trn_type = 'CO' ". $sql );
        }
//        else if($type=='PF')
//        {
//            $query .= ("select a.mainid,a.id,a.ac_code,a.invdate,a.invoiceno,a.crate,a.totalamt,a.totalamt2,a.totalpcs,a.totalqty,a.duedays,duedate,trim(isnull(a.description,'')) as description,trim(isnull(b.ac_name,''))as customer,trim(isnull(c.Name,''))as currency,a.trn_type,trim(isnull(a.customerid,'')) as docrefno from mperforma a "
//            ."left outer join ledgmast b on a.ac_code=b.ac_code "
//            ." left outer join Mcurrency c on a.id=c.Id " . $sql1 );        
//        }else if($type=='SL')
//        {
//            $query .= ("select a.doc_no as mainid,a.id,a.ac_code,a.invdate,a.invoiceno,a.crate,a.grossamt as totalamt,a.grossamt2 as totalamt2,a.totalpcs,a.totalqty,a.duedays,duedate,trim(isnull(a.description,'')) as description,trim(isnull(b.ac_name,''))as customer,trim(isnull(c.Name,''))as currency,a.trn_type,trim(isnull(a.remarks,'')) as docrefno from maintran a  "
//                    . "left outer join ledgmast b on a.ac_code=b.ac_code "
//                    . "left outer join Mcurrency c on a.id=c.Id " . $sql1 );
//        }
       
        
//                $page1=$data['page'];
//		if ($page1 == 1) {
//		    $offset = 0;
//		}
//		else 
//                {		
//		    $offset = ($page1-1)*10;
//		}                
                //               //     
		// echo $offset;
            // $page1 = $page * 10;
//            $query .= ' ORDER BY a.invdate ';
        $result = $maindb->query($query)->result();
// print_r($query);
//print_r($query);
        
//             $result = array_merge(
//             array("groupby" => $groupby_data),                
//        );
        
                $response = array(
                    'query'=>$query,
            'data' => $result
        );
        echo json_encode($response);
//                if(count($result)>0)
//                {	
//                    $result = array_merge(
//                            array("list" => $result)
//                    );
//                    $this->response(array(
//                        "status" => 1,
//                        "query" => $query,
//                        "message" => "co list ",
//                        "data" => $result
//                            ), REST_Controller::HTTP_OK);
//                }
//                else
//                {
//                         $this->response(array(
//                "status" => 0,
//                "message" => "No Record changed",
//                //        "data" => $students
//                    ), REST_Controller::HTTP_NOT_FOUND);
//                }	
    }
        
        function call_api($url,$data)
        {
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
              CURLOPT_POSTFIELDS =>$jsonData,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
        
        
        
        
//        public function process_login() {           
//        $jsonData = file_get_contents('php://input');
//        $dkapi_data = json_decode($jsonData, true);
////        if (!empty($dkapi_data)) {
////            $dkapi_data = ($_POST);
////        }    
////        echo '<pre>';  
//        
//        // Retrieve input values from the login form
//        if(!empty($dkapi_data))
//        {
//            $username = $dkapi_data['username'];
//            $password = $dkapi_data['password'];
//            $company_code = $dkapi_data['company_code'];
//            $conversion_rate = $dkapi_data['conversion_rate'];
//        }
//        else
//        {    
//        $username = $this->input->post('username');
//        $password = $this->input->post('password');
//        $remember_me = $this->input->post('remember_me');
//        $conversion_rate= $this->input->post('conversion_rate');
//        $company_code= $this->input->post('company_code');              
//        }
//        
//        $db2 = $this->load->database('db2', TRUE);
//        $result2=$db2->query("select * from Dts_CompanyAccess "
//                  . "where Co_Code='$company_code'")->row_array();
//        $Co_DataName=trim($result2['Co_DataName']);
//        
//        
//        if($Co_DataName=='')
//            {
//              if(empty($dkapi_data))
//              {
//              $this->session->set_flashdata('login_failed', 'Invalid username or password.');
//              redirect('login');  
//              }
//              else
//              {
//              echo json_encode(array('success' => false, 'message' => 'Invalid username or password.'));    
//              }    
//              exit;
//            }
//        
////        print_r(array('username' => $username, 'password' => md5($password)));
//        $maindb= $this->load->database($Co_DataName, TRUE);  
//        $query = $maindb->get_where('userlogin', array('username' => $username, 'password' => md5($password)));
////        $query->num_rows();
////        exit;
//        if($query->num_rows() === 1) {
//            $final=$query->row_array();
//			
//	    $ip_address=$this->input->ip_address();
//            $screen_resolution = $this->input->server('HTTP_USER_AGENT');
//            $device = $this->input->server('HTTP_USER_AGENT');
//            $browser = $this->input->server('HTTP_USER_AGENT');
//            
//            $log_data = array(
//            'user_id' => $final['userid'],
//            'timestamp' => date('Y-m-d H:i:s'),
//            'ip_address' => $ip_address,
//            'screen_resolution' => $screen_resolution,
//            'device' => $device,
//            'browser' => $browser
//            );
//
//            $maindb->insert('logs', $log_data);
//            $results=$query->row_array();
//        }
//		
//		
//        if ($results) {
//            
//            
//            $result=$final;
//            
//            $result=array_merge($result,array("dbname"=>$Co_DataName));
//            
//            $currency_names=$maindb->query("select name from mcurrency")->result_array();
//            $final= array_merge($result,array("conversion_rate"=>$conversion_rate),array("currency_name1"=>$currency_names[0]['name']),array("currency_name2"=>$currency_names[1]['name']));            
//            $this->session->set_userdata('username', $username);
//            $this->session->set_userdata('password', $password);
//            $this->session->set_userdata('dbname', $Co_DataName);
//            
//            $this->session->set_userdata("logged_in_user", $final);
//            
//            $session = $this->session->userdata("logged_in_user");
//            $data['userdata'] = $this->session->userdata('logged_in_user');    
//            if(empty($dkapi_data))
//            {
//                redirect('dashboard'); // Replace 'dashboard' with the desired page URL
//            }
//            else
//            {
//             echo json_encode(array('success' => true,'data'=> $final,'message' => 'Login Successfully'));     
//            }    
//        }  
//        else {
//            if(empty($dkapi_data))
//            {
//            $this->session->set_flashdata('login_failed', 'Invalid username or password.');
//            redirect('login');
//            }
//            else
//            {
//              echo json_encode(array('success' => false, 'message' => 'Invalid username or password.'));    
//            }  
//        }
//        
//        }
//        
//        
//    public function update_profile() {
//        $jsonData = file_get_contents('php://input');
//        $data = json_decode($jsonData, true);
//        if (empty($data)) {
//            $data = $this->input->post();
//        }
//        $dbname = $data['db'];
//        $user_id = ($data['user_id']);
//        $full_name = ($data['full_name']);
//        $email = ($data['email_id']);
//        $mobile_no = ($data['mobile_no']);
//        
////        if (isset($_FILES["profileimage"]) && !empty($_FILES["profileimage"]["name"][0])) {  
////                    $last_insert_id=$ac_code;
////                    $targetDir = "uploads/styles/"; // Your target directory where images will be saved
////                    $uniqueFolderName = $last_insert_id; // Generate a unique folder name
////                    // Create the directory with the unique name
////                    $uploadDir = $targetDir . $uniqueFolderName . "/";
////                    mkdir($uploadDir);
////                    $uploadedImages = count($_FILES["profileimage"]["name"]);
////                    
//////                    for ($i = 0; $i < $uploadedImages; $i++) {
//////                        $tmpFilePath = $_FILES["profileimage"]["tmp_name"][$i];
//////                           $eventfilename1 = ($eventfilename[$i]);
////////                           print_r($eventfilename1);
//////                           unset($data['eventfilename']);
//////                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
//////                            $fileName = '';
//////                            $fileName = $_FILES["profileimage"]["name"][$i];
//////                            $filePath = $uploadDir . $fileName;
//////                            if (move_uploaded_file($tmpFilePath, $filePath)) {
//////                                $main_files .= base_url() . '' . $filePath . ',';
//////                                $allfiles[] = array("img" => base_url() . '' . $filePath, "ac_code" => $last_insert_id, "user_id" => '1', "added_date" => date("Y-m-d H:i:s"),"filename" =>$eventfilename1);
//////                            } else {
//////
//////                            }
//////                        }
//////                    }
////                }
////        
//        $maindb = $this->load->database($dbname, TRUE);
//
//        $sql = "update userlogin 
//    SET full_name = '$full_name', 
//    mobile_no = '$mobile_no',  email_id ='$email' where userid=$user_id ";
//        
//       $students = $maindb->query($sql);
//       echo json_encode(array('success' => true, 'message' => 'profile updated successfully!'));  
////        $this->response(array(
////                "status" => 1,
////                "message" => "profile updated successfully!",
////                    ), REST_Controller::HTTP_OK);
//       
//    }     
//    
//    public function change_password() {
//        $jsonData = file_get_contents('php://input');
//        $data = json_decode($jsonData, true);
//        if (empty($data)) {
//            $data = $this->input->post();
//        }
//        $dbname = ($data['db']);
//        $user_id = ($data['user_id']);
//        unset($data['$user_id']);
//        $password = md5($data['password']);
//        unset($data['$password']);
//        $maindb = $this->load->database($dbname, TRUE);
//        $students = $maindb->query("update userlogin set password='$password' where userid=$user_id");
//            if ($students > 0) {
//                echo json_encode(array('success' => true, 'message' => 'password has been changed successfully!'));
//            } else {
//                echo json_encode(array('success' => false, 'message' => 'Password Not changed'));
//            }
//    }
//    
//public function contact_enquiry() { 
//        $jsonData = file_get_contents('php://input');
//        $data = json_decode($jsonData, true);
//        if (empty($data)) {
//            $data = $this->input->post();
//        }
//        $dbname = ($data['db']);        
//        $maindb = $this->load->database($dbname, TRUE);
//        $query = '';
//
//        $query .= ("select * from contact_enquiry_type");
//        $result = $maindb->query($query)->result();
//        $result = array_merge(
//                array("list" => $result)
//        );
//            
//            
//            $response = array(
//            'draw' => intval($requestData['draw']),
//            'data' => $result
//        );
//        echo json_encode($result);
////        $this->response(array(
////            "status" => 1,
////            "message" => "list ",
////            "data" => $result
////                ), REST_Controller::HTTP_OK);
//    }    
//    
//     public function contact_us_inquiry_upload() {
//        $jsonData = file_get_contents('php://input');
//        $data = json_decode($jsonData, true);
////        print_r($data);
////        exit;
////        if (empty($data)) {
////            $data = $this->input->post();
////        }		
////		$log_data = array(
////            'method' => $this->input->method(),
////            //          'uri' => $this->input->uri_string(),
////            'parameters' => json_encode($data),
////            'ip_address' => $this->input->ip_address(),
////            'timestamp' => date('Y-m-d H:i:s')
////        );
////        $this->log_request($log_data);
////        
////        
//        $dbname = ($data['db']);
//		unset($data['db']);
//        $maindb = $this->load->database($dbname, TRUE);
////		print_r($data);
//		$maindb->insert("contact_us_inquiry",$data);
//		
//	    $insertId = $maindb->insert_id();   
//            if ($insertId > 0) {
//                echo json_encode(array('success' => true, 'message' => 'data added successfully!'));
//            } else {
//                echo json_encode(array('success' => false, 'message' => 'failed'));
//            }
//                
////		if ($insertId > 0) {
////            $this->response(array(
////                "status" => 1,
////                "message" => "data added successfully!",
////                    ), REST_Controller::HTTP_OK);
////        } else {
////            $this->response(array(
////                "status" => 0,
////                "message" => "failed",
//////        "data" => $students
////                    ), REST_Controller::HTTP_NOT_FOUND);
////        }
//    }  

    
}
