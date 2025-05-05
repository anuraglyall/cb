<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Add_child extends REST_Controller {

	public function __construct() {
	parent::__construct();
        $this->load->library('Authorization_Token');
	$this->load->model('user_model');
	}
        
    // Api to approved paraent request 
    public function child_approval_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $child_id = $data['child_id'];
                $relationship_id = $data['relationship_id'];
                $status = $data['status'];
                // date_default_timezone_set("asia/Kuala_Lumpur");
                //date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                if(!$child_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Id Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$relationship_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Relationship Id Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$status) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Status Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $sql = '';
                $relationship = $this->db->query("select * from child_parent_relationship where id = '$relationship_id' and status != '2' ")->result_array();
                if(count($relationship) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Relationship Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $this->db->query("update child_parent_relationship set request_status = '$status' , updated_date = '$currentDateTime' where id = '$relationship_id'");
                $sender = $relationship[0]['parent_id'];
                $this->db->query("update notification set added_date = '$currentDateTime', sender_id = $child_id, receiver_id = $sender where sender_id = '$sender' and receiver_id = '$child_id'");
             
                $message = 'Child Approval Successfully!';
                if($status == 2 ){
                    $message = 'Child Decline Your Request!';
                }
                $final = array();
                $final['access_token'] = $headers['Authorization'];
                $final['status'] = true;
                $final['message'] = $message;
                $final['data'] = $this->db->query("select * from child_parent_relationship where id = '$relationship_id'")->row_array();
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
    
     // Api to suggest corse to parent
    public function course_suggest_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = !empty($data['user_id']) ? $data['user_id'] : null;
                $course_id = !empty($data['course_id']) ? $data['course_id'] : null; 
                
                // date_default_timezone_set("asia/Kuala_Lumpur");
                //date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                
                if(!$user_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$course_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Cannot Be Blank,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_user  = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($check_user )=== 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_course  = $this->db->query("select * from courses where id = '$course_id' and status != '2'")->result_array();
                if(count($check_course ) == 0 ){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }  

                $student_id = $check_user[0]['student_id'];                 
                $check_parent = $this->db->query("select * from child_parent_relationship where child_id = '$student_id' and request_status = '1' and status != '2'  ")->result_array();
                if(count($check_parent) === 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Parent Not Found ,Please Make Relationship To Your Parent!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $parent_id  = $check_parent[0]['parent_id'];
                $check_notification = $this->db->query("select * from notification where sender_id = '$user_id' and receiver_id = '$parent_id' and notification_type = 'Course Purchase Request' and main_id = $course_id and status != '2' ")->result_array();        
                if(count($check_notification) > 0){  
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Already Course Suggestion Sent To Parent!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }    

                $parentInfo = $this->db->query("select * from users where id = '$parent_id' and status != '2'")->result_array();
                $studentName = $check_user[0]['full_name'];
                $paraentName = $parentInfo[0]['full_name'];
                $courseName = $check_course[0]['name'];
                $query_result = array(
                    'id' => $parentInfo[0]['id'],
                    'firebase_token' => $parentInfo[0]['firebase_token'],
                    'student_id' => $check_user[0]['student_id'],
                    'topic' => 'courseSuggest',
                    'title' => 'Course '.$courseName.' suggested',
                    'body' => $studentName.' suggested course to user '.$paraentName,
                );
               
                if(!empty($query_result) && $query_result['firebase_token']) {
                    $this->load->library('Firebase');
                    $firebase = new Firebase();
                    
                    try { 
                        $response =  $firebase->sendNotification($query_result);
                    }catch (Exception $e) {
                       
                    }
                }
                //date_default_timezone_set('Asia/Kolkata');
                $this->db->query("insert into notification (added_date,updated_date,notification_type,sender_id,receiver_id,notifier_id,main_id ) values ('$currentDateTime','$currentDateTime','Course Purchase Request','$user_id','$parent_id','$parent_id','$course_id')");
                $last_insert_id = $this->db->insert_id();
                
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['access_token'] = $headers['Authorization'];
                    $final['status'] = true;
                    $final['message'] = 'Course Suggestion Send Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Suggestion Send Failed!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
     
      // Api to make parent child relationship in db
    public function parent_child_relationship_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
         
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $parent_id = $data['parent_id'];
                $child_id = $data['child_id'];

                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                
                if (!$parent_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Parent Id cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if (!$child_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Id cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }            
            
                $childInfo = $this->db->query("SELECT * FROM users WHERE student_id = '$child_id' AND status != '2'")->result_array();            
                if (count($childInfo) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Student Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $alreadyHaveParent = $this->db->query("SELECT * FROM child_parent_relationship WHERE child_id = '$child_id' AND status != '2' AND request_status = '1'")->result_array();
                if(count($alreadyHaveParent) > 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Student Have Already Parent!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $alreadyExist = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$parent_id' AND child_id = '$child_id' AND status != '2' ")->result_array();
                $relationship_id = $alreadyExist[0]['id'];
                if(count($alreadyExist) > 0 && $alreadyExist[0]['request_status'] == 2 ){
                    $status = 0;
                    $this->db->query("update child_parent_relationship set request_status = '$status' , updated_date = '$currentDateTime' where id = '$relationship_id'");
                    $this->db->query("DELETE FROM notification WHERE notification_type = 'relationship' AND main_id = '$relationship_id'");
                } elseif (count($alreadyExist) > 0 && $alreadyExist[0]['request_status'] == 0 ){
                    $this->db->query("DELETE FROM notification WHERE notification_type = 'relationship' AND main_id = '$relationship_id'");
                }else{
                    $this->db->query("INSERT INTO child_parent_relationship (parent_id,child_id,added_date) values ('$parent_id','$child_id','$currentDateTime')");
                    $relationship_id = $this->db->insert_id();
                    if ($this->db->affected_rows() === 0) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Parent Child Relationship Failed,Please Check Data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    } 
                }
               
                
                $user_id = $childInfo[0]['id'];
                $notification = $this->db->query("INSERT INTO notification (notifier_id,sender_id,receiver_id,notification_type,main_id,added_date, updated_date) values ('$user_id','$parent_id','$user_id','relationship','$relationship_id','$currentDateTime','$currentDateTime')");
                
                $query_result = array(
                    'id' => $childInfo[0]['id'],
                    'firebase_token' => $childInfo[0]['firebase_token'],
                    'student_id' => $childInfo[0]['student_id'],
                    'topic' => 'parentRequest',
                    'title' => 'A Parent Request',
                    'body' => 'Parent request to user '. $childInfo[0]['name']
                );
                
                if(!empty($query_result) && $query_result['firebase_token']) {
                    $this->load->library('Firebase');
                    $firebase = new Firebase();
                    
                    try { 
                        $response =  $firebase->sendNotification($query_result);
                    }catch (Exception $e) {
                     
                    }
                }

                $final = array();
                $final['access_token'] = $headers['Authorization'];
                $final['status'] = true;
                $final['relationship_id'] = $relationship_id;
                $final['message'] = 'Parent child Relationship Successfully!';
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

      // Api to get inbox db notification
    public function notification_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])){
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $this->db->query("SELECT full_name, created_at FROM users WHERE id = $user_id AND status != '2'")->result_array();
                if(count($user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
              
                $userCreatedDate = $user[0]['created_at'];
                $currentDate = date('Y-m-d');
                $yesterdayDate = date('Y-m-d', strtotime('-1 day'));
                $allNotifications = array();
                $todayNotifications = $this->db->query("SELECT * FROM notification where is_read = 0 AND status != '2' AND added_date > '$userCreatedDate' AND DATE(added_date) = '$currentDate' AND (receiver_id = '$user_id' OR is_all = '1') ORDER BY added_date DESC")->result_array();
                if(count($todayNotifications) > 0) {
                    $todayNotifications = $this->addMoreDetailToNotification($todayNotifications,$user);
                    $today = array();
                    $today['type'] = 'Today';
                    $today['fitered_data'] = $todayNotifications;
                    $allNotifications[] = $today;
                }

                $yesterdayNotifications = $this->db->query("SELECT * FROM notification where is_read = 0 AND status != '2' AND DATE(added_date) = '$yesterdayDate' AND added_date > '$userCreatedDate' AND (receiver_id = '$user_id' OR is_all = '1') ORDER BY added_date DESC")->result_array();
                if(count($yesterdayNotifications) > 0) {
                    $yesterdayNotifications = $this->addMoreDetailToNotification($yesterdayNotifications,$user);
                    $yesterday = array();
                    $yesterday['type'] = 'Yesterday';
                    $yesterday['fitered_data'] = $yesterdayNotifications;
                    $allNotifications[] = $yesterday;
                }
                
                $olderNotifications = $this->db->query("SELECT * FROM notification where is_read = 0 AND status != '2' AND DATE(added_date) < '$yesterdayDate' AND added_date > '$userCreatedDate' AND (receiver_id = '$user_id' OR is_all = '1') ORDER BY added_date DESC")->result_array();
                if(count($olderNotifications) > 0) {
                    $olderNotifications = $this->addMoreDetailToNotification($olderNotifications,$user);
                    $older = array();
                    $older['type'] = 'Older';
                    $older['fitered_data'] = $olderNotifications;
                    $allNotifications[] = $older;
                }

                $final = array();
                $final['status'] = true;
                $final['message'] = 'Notification Result';
                $final['data'] = $allNotifications;
                $this->response($final, REST_Controller::HTTP_OK);
            }else{
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

     // Function to add some extra information in inbox db notification
    private function addMoreDetailToNotification($notifications,$user) {
            for($i = 0; $i < count($notifications); $i++){
                $notification_type = $notifications[$i]['notification_type'];
                $main_id = $notifications[$i]['main_id'];

                if($notification_type == 'Course Purchase Request' || $notification_type == 'homework_status'){
                    $course = $this->db->query("SELECT name FROM courses WHERE id = '$main_id' and status != '2'")->row_array();
                    $notifications[$i]['course_name'] = (!empty($check_course)) ? $check_course['name'] : '';
                }else {
                    $notifications[$i]['course_name'] = '';
                }

                if($notification_type == 'Event'){
                    $event = $this->db->query("SELECT * FROM events WHERE id = '$main_id' and status != '2'")->row_array();
                    $notifications[$i]['message'] = (!empty($event)) ? $event['short_description'] : '';
                    $notifications[$i]['event_name'] = (!empty($event)) ? $event['event'] : '';
                }else {
                    $notifications[$i]['event_name'] = '';
                }

                if($notification_type == 'relationship'){
                    $relation = $this->db->query("SELECT request_status FROM child_parent_relationship WHERE id = '$main_id' and status != '2'")->row_array();
                    $notifications[$i]['request_data'] = (!empty($relation)) ? $relation['request_status'] : '';
                }else {
                    $notifications[$i]['request_data'] = '';
                }

                if($notification_type == 'All_Event'){
                    $event = $this->db->query("SELECT event,short_description FROM events WHERE id = '$main_id' and status != '2'")->row_array();
                    $notifications[$i]['message'] = (!empty($event)) ? $event['short_description'] : '';
                    $notifications[$i]['event_name'] = (!empty($event)) ? $event['event'] : '';
                }

                if($notification_type == 'All_Announcement'){
                    $announcement = $this->db->query("SELECT name,message FROM announcement WHERE id = '$main_id' and status != '2'")->row_array();
                    $notifications[$i]['message'] = (!empty($announcement)) ? $announcement['message'] : '';
                    $notifications[$i]['announcement_name'] = (!empty($announcement)) ? $announcement['name'] : '';
                }else{
                    $notifications[$i]['announcement_name'] = '';
                }

                
                $notifications[$i]['day'] = $notifications[$i]['updated_date'];
                $notifications[$i]['full_name'] = $user[0]['full_name'];
                $parent_id = $notifications[$i]['sender_id'];
                $user_detail = $this->db->query("SELECT id,full_name,username,image FROM users WHERE id = '$parent_id' AND status != '2'")->result_array();
                if(count($user_detail) > 0){$notifications[$i]['user_details']= $user_detail[0];}
            }

        return $notifications;
    }

    // Api to purchase the event 
    public function event_purchase_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $child_uid = $data['child_uid'];
                $event_id = $data['event_id'];
                $amount = $data['amount'];
                $payment_method = $data['payment_method'];
                $card_type = $data['card_type'];
                $transaction_id = $data['transaction_id'];
                $payment_status = $data['payment_status'];
                if(!$child_uid) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child UID Cannot Be Blank, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$event_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if($amount && $amount > 0) {
                    if(!$payment_method) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Payment Method Cannot Be Blank,Please Check Data Again';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                    if(!$card_type) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Card Type Cannot Be Blank,Please Check Data Again';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                    if(!$transaction_id) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Transaction Id Cannot Be Blank,Please Check Data Again';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                    if(!$payment_status) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Payment Status Cannot Be Blank,Please Check Data Again';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                }

                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$child_uid' AND status != '2'")->result_array();                
                if(count($check_user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_event = $this->db->query("SELECT * FROM events WHERE id = '$event_id' AND status != '2'")->result_array();
                if(count($check_event) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $student_id = $check_user[0]['student_id'];                
                $check_parent = $this->db->query("SELECT parent_id FROM child_parent_relationship WHERE child_id = '$student_id' and request_status = '1'")->result_array();
                if(count($check_parent) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Parent Relationship Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $parent_id = $check_parent[0]['parent_id'];

                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $created_at_date = date('Y-m-d H:i:s');
                $event_transaction = array('user_id' => $parent_id,'child_id' => $child_uid, 'event_id' => $event_id, 'amount' => $amount, 'payment_method' => "$payment_method", 'card_type' => "$card_type",'transaction_id' => "$transaction_id", 'payment_status' => "$payment_status", 'created_at' => "$created_at_date");
                $this->db->insert('event_transaction', $event_transaction);
                if ($this->db->affected_rows() === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Transaction Failed!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Event Transaction Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

     // Api to event purchase request to parent
    public function event_purchase_request_post() {
                $data = $_POST;
                $re_request = false;
                $message  = 'Purchase Event Notification Sent Successfully';
                $headers = $this->input->request_headers();
                if (empty($headers['Authorization'])) {
                    $final['status'] = false;
                    $final['message'] = 'Authorization token Cannot be Blank';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                if (isset($headers['Authorization'])) {
                    $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
                    if ($decodedToken['status']) {
                    $child_uid = $data['child_uid'];
                    $event_id = $data['event_id'];
                
                if(!$child_uid) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child UID Cannot Be Blank, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$event_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Id Cannot Be Blank, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }


                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$child_uid' AND status != '2'")->result_array();                
                if(count($check_user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_event = $this->db->query("SELECT * FROM events WHERE id = '$event_id' AND status != '2'")->result_array();
              
                if(count($check_event) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $student_id = $check_user[0]['student_id'];                
                $relationship = $this->db->query("SELECT parent_id FROM child_parent_relationship WHERE child_id = '$student_id' and request_status = '1'")->result_array();
                if(count($relationship) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Parent Relationship Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $currentDate = date('Y-m-d H:i:s');
                $parent_id = $relationship[0]['parent_id'];
                $request_check = $this->db->query("select * from notification where main_id = '$event_id' and sender_id = '$child_uid' and receiver_id= '$parent_id' and status != '2'")->result_array();
                if(count($request_check) > 0){
                    $re_request = true;
                    $message  = 'You have already sent notification for this event';
                }else{
                    $this->db->query("INSERT INTO notification (main_id,sender_id,receiver_id,notification_type,added_date,updated_date) VALUES ('$event_id','$child_uid','$parent_id','Event','$currentDate','$currentDate')");
                }
              
                
                $parentInfo = $this->db->query("select * from users where id = '$parent_id' and status != '2'")->result_array();
                $query_result = array(
                    'id' => $parentInfo[0]['id'],
                    'firebase_token' => $parentInfo[0]['firebase_token'],
                    'student_id' => $parentInfo[0]['student_id'],
                    'topic' => 'eventSuggest',
                    'title' => 'Event suggested',
                    'body' => $check_user[0]['full_name'].' suggested event '.$check_event[0]['event'].' to user '. $parentInfo[0]['full_name']  ,
                );
                if(!empty($query_result) && $query_result['firebase_token']) {
                    $this->load->library('Firebase');
                    $firebase = new Firebase();
                    

                    try { 
                        $response =  $firebase->sendNotification($query_result);
                    }catch (Exception $e) {
                        //alert the user then kill the process
                    
                        // $final = array();
                        // $final['status'] = false;
                        // $final['message'] = $e->getMessage();
                        // $this->response($final, REST_Controller::HTTP_OK);
                    }
                }

            
                    $final = array();
                    $final['status'] = true;
                    $final['re_request'] = $re_request;
                    $final['message'] = $message ;
                    $this->response($final, REST_Controller::HTTP_OK);
            
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

      // Api to check if event already purchased 
    public function is_already_purchased_event_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $event_id = $data['event_id'];
                $user_id = $data['user_id'];

                if(!$event_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Event Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $alreadyPurchased = $this->db->query("select * from event_transaction where 
                child_id = $user_id  and event_id = $event_id and payment_status = 'success' and status != '2'")->result_array(); 
                if(count($alreadyPurchased) > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['already_purchased'] = true;
                    $final['message'] = 'Event Already Purchased!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $final = array();
                $final['status'] = true;
                $final['already_purchased'] = false;
                $final['message'] = 'You Can Purchase Event!';
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
         // Api to cget child data by student id
    public function search_child_by_student_id_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $parent_id = !empty($data['parent_id']) ? $data['parent_id'] : null ;
                $student_id = !empty($data['student_id']) ? $data['student_id'] : null ;   
                if (!$parent_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Parent Id cannot be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if (!$student_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Student Id cannot be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
        
                $student_detail = $this->db->query("SELECT * FROM users WHERE student_id = '$student_id' AND status != '2'")->row_array();     
                if (empty($student_detail)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Student Detail Not Found, Please Check Data Again!";
                    $final['data'] = array();
                    $this->response($final, REST_Controller::HTTP_OK);
                }
        
                $city_id = $student_detail["city"];
                $city = $this->db->query("select * from city where id = '$city_id' and status != 2")->row_array();
                $city_name = $city["name"];
                $student_detail['city_name'] = $city_name;
        
                $state_id = $student_detail["state"];
                $state = $this->db->query("select * from state where id = '$state_id'  and status != 2")->row_array();
                $state_name = $state["name"];
                $student_detail['state_name'] = $state_name;
        
                $country_id = $student_detail["country"];
                $country = $this->db->query("select * from country where id = '$country_id' and status != 2")->row_array();
                $country_name = $country["name"];
                $student_detail['country_name'] = $country_name;
        
                $race_id = $student_detail["race"];
                $race = $this->db->query("select * from races where id = '$race_id' and status != 2")->row_array();
                $race_name = $race["name"];
                $student_detail['race_name'] = $race_name;
        
                $nationlity_id = $student_detail["nationlity"];
                $nationlity = $this->db->query("select * from nationlity where id = '$nationlity_id'")->row_array();
                $nationlity_name = $nationlity["name"];
                $student_detail['nationlity_name'] = $nationlity_name;
        
                $dialect_id = $student_detail["dialect"];
                $dialect = $this->db->query("select * from dialect where id = '$dialect_id'")->row_array();
                $dialect_name = $dialect["name"];
                $student_detail['dialect_name'] = $dialect_name;
        
                $religion_id = $student_detail["religion"];
                $religion = $this->db->query("select * from religion where id = '$religion_id'")->row_array();
                $religion_name = $religion["name"];
                $student_detail['religion_name'] = $religion_name;
        
                $pending_requests = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$parent_id' AND child_id = '$student_id' AND request_status = '0' AND status != '2'")->result_array();
                $student_detail['pending_request'] = count($pending_requests) === 0 ? '1' : '0' ;
               
                $courseIds = $student_detail["course"];
                $courseList = array();
               
                if($courseIds) {
                    $courseList = $this->db->query("select * from courses where id in($courseIds) and status != '2'")->result_array();
                    for ($i = 0; $i < count($courseList); $i++) { 
                        $courseId = $courseList[$i]['id'];
                        $courseTypeId = $courseList[$i]['course_type_id'];
                        $courseTypeInfo = $this->db->query("SELECT * FROM course_type where id = $courseTypeId and status != '2'")->row_array();     
                        $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) as average_rating FROM course_rating where course_id ='$courseId' and status != '2'")->result_array();
                        $courseList[$i]['course_type_name'] = (!empty($courseTypeInfo)) ? $courseTypeInfo['name'] : '';
                        $courseList[$i]['average_rating'] = ($average_rating[0]['average_rating'] !== NULL) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                        $courseList[$i]['timeSlotAndDays'] = $this->getCourseAllTimeSlotAndDays($courseId);
                    }
                }
        
                $mergedArray = array_merge($student_detail, array("Course Details" => (count($courseList) > 0) ? $courseList : []));
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Student Profile Results';
                $final['data'] = $mergedArray;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

  // Api to get all the pending child request
    public function pending_child_request_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();

        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
            
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {   
                $parent_id = $data['parent_id'];
                $pending_status = $request_status = '0';
                $status = '2';
                if ($parent_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Parent Id Not Found,Please check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $pending_requests = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$parent_id' AND request_status = $request_status AND status != $status")->result_array();
                if (count($pending_requests) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Pending Child Request Not Found,Please check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                

                $childs_list = array();
                for ($i = 0; $i < count($pending_requests); $i++) { 
                    $child_id = $pending_requests[$i]['child_id'];
                    $student_detail = $this->db->query("SELECT *,$pending_status AS pending_request FROM users WHERE student_id = '$child_id' AND status != $status")->row_array();
                   
                    $city_id = $student_detail["city"];
                    $city = $this->db->query("select * from city where id = '$city_id' and status != 2")->row_array();
                    $city_name = $city["name"];
                    $student_detail['city_name'] = $city_name;
            
                    $state_id = $student_detail["state"];
                    $state = $this->db->query("select * from state where id = '$state_id'  and status != 2")->row_array();
                    $state_name = $state["name"];
                    $student_detail['state_name'] = $state_name;
            
                    $country_id = $student_detail["country"];
                    $country = $this->db->query("select * from country where id = '$country_id' and status != 2")->row_array();
                    $country_name = $country["name"];
                    $student_detail['country_name'] = $country_name;
            
                    $race_id = $student_detail["race"];
                    $race = $this->db->query("select * from races where id = '$race_id' and status != 2")->row_array();
                    $race_name = $race["name"];
                    $student_detail['race_name'] = $race_name;
            
                    $nationlity_id = $student_detail["nationlity"];
                    $nationlity = $this->db->query("select * from nationlity where id = '$nationlity_id'")->row_array();
                    $nationlity_name = $nationlity["name"];
                    $student_detail['nationlity_name'] = $nationlity_name;
            
                    $dialect_id = $student_detail["dialect"];
                    $dialect = $this->db->query("select * from dialect where id = '$dialect_id'")->row_array();
                    $dialect_name = $dialect["name"];
                    $student_detail['dialect_name'] = $dialect_name;
            
                    $religion_id = $student_detail["religion"];
                    $religion = $this->db->query("select * from religion where id = '$religion_id'")->row_array();
                    $religion_name = $religion["name"];
                    $student_detail['religion_name'] = $religion_name;

                    $childs_list[$i] = $student_detail;
                }
                
                $final = array();
                $final['status'] = true;
                $final['message'] = "Pending Child Requests List!";
                $final['childs_list'] = $childs_list;
                $this->response($final, REST_Controller::HTTP_OK);
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }


     // Function to provide course all day time slots information
    private function getCourseAllTimeSlotAndDays($course_id) {
        $masterClasses = $this->getCourseAllMasterClasses($course_id);
        if(count($masterClasses) === 0) {
            return array('timeSlots' => [],'days' => []);        
        }
    
        $masterClassesAllIds = '';
        for ($i = 0; $i < count($masterClasses); $i++) {
            $masterClassId =  $masterClasses[$i]['id'];
            $masterClassesAllIds = $masterClassesAllIds.$masterClassId.',';
        }
        $masterClassesAllIds =  rtrim($masterClassesAllIds,",");
    
        $classes = $this->db->query("select * from upcoming_classes where master_class_id in($masterClassesAllIds) and status != '2' and class_status != 'Cancel';")->result_array();
        if(count($classes) === 0) {
           return array('timeSlots' => [],'days' => []);        
        }
        
        $timeSlots = [];
        $days = [];
        for ($i = 0; $i < count($classes); $i++) { 
            $classDateTime = $classes[$i]['upcoming_date'];
            $newTimeSlot = date('g:iA', strtotime($classDateTime)).'-'.date('g:iA', strtotime($classDateTime . ' + 1 hour'));
            $classDay = date('l', strtotime($classDateTime));
            array_push($timeSlots, $newTimeSlot);
            array_push($days, $classDay);
        }
    
        return array('timeSlots' => $timeSlots, 'days' => array_values(array_unique($days)));
    }
  // Function to get all master classes for course
    private function getCourseAllMasterClasses($course_id) {
        return $this->db->query("select a.*,b.id as chapter_id,b.chapter_name,c.id as course_id,c.name as course_name 
        from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    }
}
