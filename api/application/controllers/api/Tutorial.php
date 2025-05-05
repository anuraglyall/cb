<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Tutorial extends REST_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('user_model');
	}
  //API to get all tutorials list data
    public function tutorial_post() {
     //   date_default_timezone_set("Asia/Calcutta");
        $data = $_POST;
        $headers = $this->input->request_headers();
      //  date_default_timezone_set("asia/Kuala_Lumpur");
    

        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

            if ($decodedToken['status']) {                
                $user_id = !empty($data['user_id']) ? $data['user_id'] : null;
                $course_id =  !empty($data['course_id']) ? $data['course_id'] : null; 
                $chapter_id =  !empty($data['chapter_id']) ? $data['chapter_id'] : null;  
                $question =  !empty($data['question']) ? ucfirst($data['question']) : null;  
                $files =  !empty($_FILES["files"]) ? $_FILES["files"] : null;   
                $uniqueFileName = null;
                $status = '2';
                if($user_id == '') {
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if($course_id == '') {
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Course Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if($chapter_id == '') {
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Chapter Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
       
                $user = $this->db->query("SELECT * FROM users WHERE id = $user_id AND status != $status")->result_array();
                if(count($user) === 0){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Invalid User Id, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $course = $this->db->query("SELECT * FROM courses WHERE id = $course_id AND status != $status")->num_rows();
                if($course === 0){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Invalid Course Id, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $chapter = $this->db->query("SELECT * FROM chapter WHERE id = $chapter_id AND course_id = $course_id AND status != $status")->num_rows();
                if($chapter === 0){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Invalid Chapter Id, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $studentData = $this->db->query("SELECT * FROM users WHERE id = $user_id AND status != $status")->row_array();
                $user_tutorial_chat_count = $this->getStudentTutorialChatCount($user_id);
                $user_tutorial_subscription = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE student_id = $user_id AND status != $status AND is_active = '1'")->num_rows();
           
               // if($user_tutorial_chat_count >= 5) {
                    if($user_tutorial_subscription === 0){
                        if((int)$studentData['credit'] < 1 ){
                            $this->sendTutorialSubscriptionPurchaseNotificationToParent($user_id);
                            $final = array();                    
                            $final['status'] = true;
                            $final['message'] = "Please refer to parent for subscriptions!";
                            $this->response($final, REST_Controller::HTTP_OK);
                        }
                    }
               // }
               
                $already_exist_tutorial = $this->db->query("SELECT * FROM tutorial WHERE chapter_id = $chapter_id AND course_id = $course_id AND user_id = $user_id AND status != $status")->result_array();
                if(count($already_exist_tutorial) > 0) {
                    $final = array();                    
                    $final['status'] = true;
                    $final['tutorial_id'] = $already_exist_tutorial[0]['id'];
                    $final['time'] = date("Y-m-d H:i:s");
                    $final['message'] = 'Tutorial Already Exist, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
              
                if (isset($files) && !empty($files['name'])) {   
                    $targetDir = "uploads/tutorial/";                   
                    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);                    
                
                    $tmpFilePath = $files["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $files["name"];
                        $uniqueFileName = rand() . '-' . $fileName;
                        $filePath = $targetDir . $uniqueFileName;
                        move_uploaded_file($tmpFilePath, $filePath);                           
                    }
                }

                $current_date = date('Y-m-d H:i:s');
                $data = array('user_id'=>$user_id,'course_id'=>$course_id,'chapter_id'=>$chapter_id,'question'=>$question,'files'=>$uniqueFileName,'added_date'=>"$current_date",'updated_date'=>"$current_date");
                $this->db->insert('tutorial',$data);
                $insert_id = $this->db->insert_id();   
                if ($this->db->affected_rows() > 0) { 
                    if($user_tutorial_subscription === 0){
                        if((int)$studentData['credit'] > 0){
                            $credit = (int)$studentData['credit']-1;
                            $this->db->query("UPDATE users  SET credit = $credit WHERE id = $user_id ");
                        }
                    }
                          
                    $final = array();                    
                    $final['status'] = true;
                    $final['tutorial_id'] = $insert_id;
                    $final['time'] = date("Y-m-d H:i:s");
                    $final['message'] = 'Tutorial Uploaded Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Upload Failed, Please Check Data Again!';
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


     //API to get replies on tutorial 
    public function reply_count_post() {        
        $user_id = $_POST['user_id'];
        $this->load->library('Firebase');
        $firebase = new Firebase();
        $count = $firebase->getCount($user_id);
    
        $final = array(); 
        $tutorials = array();
        $tutorials['reply_count'] = $count;               
        $final['status'] = true;
        $final['message'] = 'Tutorial Results!';
        $final['data'] = $tutorials;
        $this->response($final, REST_Controller::HTTP_OK);
    }
  //API to get student chat count for tutorial
    private function getStudentTutorialChatCount($user_id) {
        $this->load->library('Firebase');
        $firebase = new Firebase();
        $count = $firebase->getCount($user_id);
        return $count;
    }
     //API to get tutorial data
    public function tutorial_display_post() {
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
                $filter =  !empty($data['filter']) ? $data['filter'] : null; 
                $course_type_id = !empty($data['course_type']) ? $data['course_type'] : null;  
    
                if($user_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);                
                }

                $sql = '';               
                if($filter && $filter === 'A-Z') {
                    $sql = 'ORDER BY courses.name ASC';
                }
                if($filter && $filter === 'Z-A') {
                    $sql =  'ORDER BY courses.name DESC';
                }
                if($filter && $filter === 'Oldest-Latest') {
                    $sql =  'ORDER BY tutorial.added_date ASC';
                }
                if($filter && is_numeric($filter)) {
                    $sql =  "AND courses.id = $filter";
                }
                if($course_type_id && is_numeric($course_type_id)) {
                    $sql =  "AND course_type.id = $course_type_id";
                }
                if(($filter && ($filter === 'recent' || $filter === 'Latest-Oldest')) || $sql === '') {
                    $sql = 'ORDER BY tutorial.added_date DESC';
                }
                 
                $tutorials = $this->db->query("SELECT tutorial.id,courses.name,courses.image,courses.description,chapter.chapter_name,
                tutorial.question,tutorial.is_active, tutorial.added_date,tutorial.files,tutorial.replies,course_type.name as course_type_name
                FROM tutorial 
                LEFT OUTER JOIN users ON tutorial.user_id = users.id 
                LEFT OUTER JOIN courses ON tutorial.course_id = courses.id
                LEFT OUTER JOIN course_type ON course_type.id = courses.course_type_id
                LEFT OUTER JOIN chapter ON tutorial.chapter_id = chapter.id 
                WHERE tutorial.user_id = $user_id AND tutorial.status != '2' $sql ")->result_array();
               
                if (count($tutorials)> 0){
                    $tutorials = $this->dynamicReplies($tutorials);
                    $tutorials = $this->replaceNullValueToEmptyString($tutorials);
                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'Tutorial Results!';
                    $final['data'] = $tutorials;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Data Not Found';
                    $final['data'] = $tutorials;
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
    //API to search tutorial by user_id

    public function tutorial_search_post() {
        $data = $_POST;
        $user_id = $data['user_id'];
        $search = $data['search'];

        if(!$user_id){
            $final = array();                    
            $final['status'] = false;
            $final['message'] = 'User Id Not Found , Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);                
        }
        if(!$search){
            $final = array();                    
            $final['status'] = false;
            $final['message'] = 'Search Not Found , Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);                
        }

        $tutorials = $this->db->query("SELECT courses.name,courses.image,courses.description,chapter.chapter_name,tutorial.id,
        tutorial.question,tutorial.added_date,tutorial.files,tutorial.replies,course_type.name as course_type_name
        FROM tutorial LEFT OUTER JOIN users ON tutorial.user_id = users.id 
        LEFT OUTER JOIN courses ON tutorial.course_id = courses.id
        LEFT OUTER JOIN course_type ON course_type.id = courses.course_type_id
        LEFT OUTER JOIN chapter ON tutorial.chapter_id = chapter.id
        WHERE tutorial.user_id = $user_id AND tutorial.status != '2' AND (chapter.chapter_name LIKE '$search%' OR courses.name LIKE '$search%');")->result_array();
        if (count($tutorials)> 0){
            $tutorials = $this->dynamicReplies($tutorials);
            $tutorials = $this->replaceNullValueToEmptyString($tutorials);
            $final = array();                    
            $final['status'] = true;
            $final['message'] = 'Tutorial Search Results!';
            $final['data'] = $tutorials;
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Tutorial Search Data Not Found';
            $final['data'] = $tutorials;
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
    
       //API to tutorial chat subscription 
    public function check_tutorial_chat_subscription_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        // date_default_timezone_set("asia/Kuala_Lumpur");
     //   date_default_timezone_set('Asia/Kolkata');
       
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

            if ($decodedToken['status']) { 
                $user_id = $data['user_id'];
                if($user_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = "User Id Not Found, Please Check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $user_tutorial_chat_count = $this->getStudentTutorialChatCount($user_id);
              

                $final = array();                    
                $final['status'] = true;
                $final['chat'] = true;
                $final['time'] = date('Y-m-d H:i:s');
                $final['message'] = "You Have Subscription For More Request!";
                $this->response($final, REST_Controller::HTTP_OK);
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }   
    }
     //API to manage tutorial subscription 
    public function manage_tutorial_subscription_display_post() {
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
                $user_id = $data['user_id'];
                if($user_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = "User Id Not Found, Please Check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $childs = $this->db->query("SELECT a.*, b.username, b.image , b.full_name , b.dob , b.age FROM child_parent_relationship a 
                         LEFT OUTER JOIN users b ON b.student_id = a.child_id
                         WHERE a.parent_id = '$user_id' AND a.request_status = '1' AND a.status != '2' AND b.status != '2'")->result_array();

                if(count($childs) > 0) {
                    for ($i=0; $i < count($childs); $i++) { 
                        $student_id = $childs[$i]['child_id'];
                        $user = $this->db->query("SELECT id FROM users WHERE student_id = '$student_id' AND status != '2'")->result_array();
                        $id = $user[0]['id'];
                        $childs[$i]['child_uid'] = $id;

                        $user_tutorial_subscription = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE student_id = $id AND status !='2' AND is_active = '1'")->result_array(); 
                        if(count($user_tutorial_subscription) > 0) {
                            $childs[$i]['auto_subscription'] = (int) $user_tutorial_subscription[0]['auto_subscription'];
                        }else{
                            $childs[$i]['auto_subscription'] = 0;
                        }
                    }
                }

                $user_credit_info = $this->calculateUserTutorialCredits($user_id);

                $data = array();
                $data['childs'] = $childs;
                $data['user_credit_info'] = $user_credit_info;

                $final = array();
                $final['status'] = true;
                $final['data'] = $data;
                $final['message'] = 'Childs And Credit Info Results!';
                $this->response($final, REST_Controller::HTTP_OK);
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }   
    }


        //API to tutorial subscription plans details
    public function tutorial_subscription_plan_display_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        $current_date = date('Y-m-d H:i:s');
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {  
                $user_id = $data['user_id'];
                if($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $tutorial_subscription_plan = $this->db->query("SELECT * FROM tutorial_subscription_plan WHERE status != '2'")->result_array();
                $user_credit_info = $this->calculateUserTutorialCredits($user_id);

                $data1 = array();
                if(count($tutorial_subscription_plan) > 0) {
                    for ($i=0; $i < count($tutorial_subscription_plan); $i++) { 
                        $plan_id = $tutorial_subscription_plan[$i]['id'];
                        $check_plan = $this->db->query("SELECT * FROM tutorial_credit_transactions WHERE tutorial_plan_id = '$plan_id' and user_id = '$user_id' and payment_status= 'success' and credit_expiry > '$current_date'")->result_array();
                        $subscribed =  false;
                        if(count($check_plan) > 0 ){
                            $subscribed =  true;
                        }
                        $data1[$i]['tutorial_subscription_plan'] = $tutorial_subscription_plan[$i];
                        $data1[$i]['user_credit_info'] = $user_credit_info;
                        // $data1[$i]['user_credit_info'] = $user_credit_info;
                        $data1[$i]['subscribed'] =  $subscribed;
                    }
                }

                $final = array();                    
                $final['status'] = true;
                $final['data'] = $data1;
                $final['message'] = 'Tutorial Subscription Plan Results!';
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
    //API to purchase tutorial subscription
    public function tutorial_subscription_purchase_post() {
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
                $user_id = $data['user_id'];
                $tutorial_plan_id = $data['tutorial_plan_id'];
                $tutorial_plan_quantity = (int) $data['tutorial_plan_quantity'];
                $payment_method = $data['payment_method'];
                $card_type = $data['card_type'];
                $transaction_id = $data['transaction_id'];
                $payment_status = $data['payment_status'];

                // date_default_timezone_set("asia/Kuala_Lumpur");
             //   date_default_timezone_set('Asia/Kolkata');
                $current_date = date('Y-m-d H:i:s');
                $success_payment_status = 'success';

                if(!$user_id){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$tutorial_plan_id){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Subscription Plan Id Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$tutorial_plan_quantity){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Plan Quantity Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$payment_method){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Payment Method Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$card_type){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Card Type Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$transaction_id){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Transaction Id Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$payment_status){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Payment Status Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $this->db->query("SELECT * FROM users WHERE id = $user_id AND status != '2'")->result_array();
                if(count($user) === 0){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'User Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $tutorial_subscription_plan = $this->db->query("SELECT * FROM tutorial_subscription_plan WHERE id = '$tutorial_plan_id' AND status != '2'")->result_array();
                if(count($tutorial_subscription_plan) === 0) {
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Subscription Plan Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $tutorial_credit_transactions_check = $this->db->query("SELECT * FROM tutorial_credit_transactions WHERE user_id = '$user_id' AND payment_status = 'success' AND credit_expiry > '$current_date'")->result_array();
                if(count($tutorial_credit_transactions_check) > 0) {
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Is Already subscribed !';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $total_amount = $tutorial_subscription_plan[0]['price'] * $tutorial_plan_quantity;
                $total_credits = $tutorial_subscription_plan[0]['credits'] * $tutorial_plan_quantity;
                $creditExpiryInDays = $tutorial_subscription_plan[0]['credit_expiry_in_days'];
                $current_date = date('Y-m-d H:i:s');
                $credit_expiry_date = date('Y-m-d H:i:s', strtotime("+$creditExpiryInDays day", strtotime($current_date)));
                $tutorial_credit_transaction = array('user_id'=>$user_id,'tutorial_plan_id'=>$tutorial_plan_id,'tutorial_plan_quantity'=>$tutorial_plan_quantity,'credits'=>$total_credits,'credit_expiry'=>"$credit_expiry_date",'amount'=>$total_amount,'payment_method'=>"$payment_method",'card_type'=>"$card_type",'transaction_id'=>"$transaction_id",'payment_status'=>"$payment_status",'created_at' => "$current_date");
                $this->db->insert('tutorial_credit_transactions',$tutorial_credit_transaction);

                if ($this->db->affected_rows() > 0){
                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'Tutorial Subscription Credit Transaction Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Tutorial Subscription Credit Transaction Failed!';
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
   //API to check auto subscription 
    public function auto_subscription_post() {
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
                $student_id = $data['student_id'];
                $auto_subscription = $data['auto_subscription'];

                if($parent_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Parent Id Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if($student_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Student Id Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if($auto_subscription == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Auto Subscription Not Found , Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $already_exist_subscription = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE parent_id = $parent_id AND student_id = $student_id AND status != '2' AND is_active = '1'")->result_array();
                $auto_subscription = (int) $auto_subscription;
                if($auto_subscription === 0) {
                    if(count($already_exist_subscription) > 0) {
                        $id = $already_exist_subscription[0]['id'];
                        $this->db->query("UPDATE user_tutorial_subscription  SET auto_subscription = $auto_subscription WHERE id = $id ");
                    }
                    
                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'Auto Subscription Deactivated Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user_credit_info = $this->calculateUserTutorialCredits($parent_id);
                if($user_credit_info['available_credits'] > 0) {
                    $current_date = date('Y-m-d H:i:s');
                    $subscriptionExpiryInDays = 30;
                    $start_date = $current_date;
                    $end_date = date('Y-m-d H:i:s', strtotime("+$subscriptionExpiryInDays day", strtotime($start_date)));

                    if(count($already_exist_subscription) > 0) {
                        $id = $already_exist_subscription[0]['id'];
                        $this->db->query("UPDATE user_tutorial_subscription  SET auto_subscription = $auto_subscription WHERE id = $id "); 
                    }else{
                        $user_tutorial_subscription = array('parent_id'=> $parent_id,'student_id'=> $student_id,'start'=> "$start_date",'end'=> "$end_date",'auto_subscription'=> $auto_subscription,'is_active'=> 1,'created_at'=> "$current_date",'updated_at'=> "$current_date");
                        $this->db->insert('user_tutorial_subscription',$user_tutorial_subscription);
                    }

                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'Auto Subscription Activated Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "You Don't Have Enough Credits To Activate Auto Subscription!";
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
 //Function to calculate tutorials credit 
    private function calculateUserTutorialCredits($user_id) {
        $valid_credits = 0;
        $available_credits = 0;
        $used_credits = 0;
        $credit_to_expired = 0;
        $credit_expiry_date = "";
        $current_date = date('Y-m-d H:i:s');
        $payment_status = 'success';

        $validCredits = $this->db->query("SELECT * FROM tutorial_credit_transactions WHERE user_id = $user_id AND payment_status = '$payment_status' AND credit_expiry > '$current_date' ORDER BY credit_expiry")->result_array();
       
        if(count($validCredits) > 0) {
            foreach ($validCredits as $key => $value) {                
                $valid_credits = $valid_credits + $value['credits'];
            }
            $credit_to_expired = $validCredits[0]['credits'];
            $credit_expiry_date = $validCredits[0]['credit_expiry'];
        }

        $childActiveAutoSubscription = $this->db->query("SELECT count(*) as active_user FROM user_tutorial_subscription WHERE parent_id = $user_id AND auto_subscription = '1' AND is_active = '1' AND status != '2'")->result_array();

        $usedCredits = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE parent_id = $user_id ")->result_array();
        if(count($usedCredits) > 0) {
            foreach ($usedCredits as $key => $value) {                
                $used_credits = $used_credits + $value['used_credits'];
            }
        }
   
        $available_credits = ($valid_credits > $used_credits) ? ($valid_credits - $used_credits) : 0;
        $data = array();
        $data['available_credits'] = $available_credits;
        $data['used_credits'] = (int) $used_credits;
        $data['active_user'] = count($childActiveAutoSubscription) > 0 ? (int) $childActiveAutoSubscription[0]['active_user'] : 0;
        $data['credit_to_expired'] = ($available_credits > $credit_to_expired) ? (int) $credit_to_expired : (int) $available_credits;
        $data['credit_expiry_date'] = $credit_expiry_date;
        return $data;
    }
 
    private function replaceNullValueToEmptyString($array) {
        for ($i=0; $i < count($array); $i++) { 
            if ($array[$i]['question'] == null) {
                $array[$i]['question'] = "";
            }
            if ($array[$i]['files'] == null) {
                $array[$i]['files'] = "";
            }
        }

        return $array;
    }
   //Api to send tutorial subscription purchase notificationToParent
    private function sendTutorialSubscriptionPurchaseNotificationToParent($user_id) {
        $student_id = $this->db->query("SELECT student_id FROM users WHERE id = $user_id AND status != '2'")->result_array();
        if(count($student_id) > 0) {
            $child_id = $student_id[0]['student_id'];
            $parent_id = $this->db->query("SELECT * FROM child_parent_relationship WHERE child_id = '$child_id' AND status != '2' AND request_status = '1'")->result_array();
            if(count($parent_id) === 0) {
                $final = array();                    
                $final['status'] = false;
                $final['message'] = "You Don't Have Parent Relationship!";
                $this->response($final, REST_Controller::HTTP_OK);
            }

            $parentId = $parent_id[0]['parent_id'];
            $parentInfo = $this->db->query("select * from users where id = '$parentId' and status != '2'")->result_array();
            $query_result = array(
                'id' => $parentInfo[0]['id'],
                'firebase_token' => $parentInfo[0]['firebase_token'],
                'student_id' => $parentInfo[0]['student_id'],
                'topic' => 'tutorialSubscriptionRequest',
                'title' => 'Request to purchase tutorial subscription',
                'body' => 'Tutorial subscription request to user '.$parentInfo[0]['full_name']
            );
            if(!empty($query_result) && $query_result['firebase_token']) {
                $this->load->library('Firebase');
                $firebase = new Firebase();
               
                try { 
                    $response =  $firebase->sendNotification($query_result);
                }catch (Exception $e) {
                  
                }
            }
            
            $receiver_id = $parent_id[0]['parent_id'];
            // date_default_timezone_set("asia/Kuala_Lumpur");
          //  date_default_timezone_set('Asia/Kolkata');
            $current_date = date('Y-m-d H:i:s');
            $currentDate = date('Y-m-d');
            $notification_type = "tutorial_subscription_purchase";
            $message = "Tutorial Subscription Purchase";
            $already_exist = $this->db->query("SELECT * FROM notification WHERE receiver_id = $receiver_id AND sender_id = $user_id AND notification_type = 'tutorial_subscription_purchase' AND DATE(added_date)  = '$currentDate' AND status != '2';")->result_array();
            if(count($already_exist) === 0) {
                $this->db->query("INSERT INTO notification (notifier_id, sender_id, receiver_id, notification_type, message, added_date, updated_date)
                VALUES ($receiver_id, $user_id, $receiver_id, '$notification_type', '$message', '$current_date', '$current_date');");
            }
        }
    }
  
    private function dynamicReplies($tutorials) {
        $this->load->library('Firebase');
        $firebase = new Firebase();
        for ($i=0; $i < count($tutorials); $i++) { 
            $tutorial_id = $tutorials[$i]['id'];
            $replies = $firebase->getMessages($tutorial_id);
            $tutorials[$i]['replies'] = (string) count($replies);
        }
        return $tutorials;
    }
}