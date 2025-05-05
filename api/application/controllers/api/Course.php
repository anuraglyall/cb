<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\ResponseInterface;

class Course extends REST_Controller {

	public function __construct() {
	parent::__construct();
        $this->load->library('Authorization_Token');
	    $this->load->model('user_model');
	}
        
    // API to get course certificate after course completion     
    public function course_certificate_post() {
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
                $course_id = $data['course_id'];
                if(!$user_id){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$course_id){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                } 
                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($check_user)==0){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                  
                $check_course = $this->db->query("select * from courses where id = '$course_id' and status = '0' ")->result_array();
                if(count($check_course) == 0){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Not Found,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $check = $this->db->query("select * from course_certificate where user_id = '$user_id' and course_id = '$course_id' and status = '0' ")->row_array();
                if (!empty($check['id'])){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Certificate Results';
                    $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Certificate Failed,Please Check Data Again!';
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
    
      // API to get child homework
    public function child_homework_post() {
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
                $homework_id = $data['homework_id'];
                $exercise_id = $data['exercise_id'];
                $uploadedFiles = [];

                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$homework_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Homework Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$exercise_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Exercise Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_exists = $this->db->query("select * from child_homework where user_id = '$user_id' and homework_id ='$homework_id' and exercise_id = '$exercise_id' and status = '0' ")->result_array();
                if(count($check_exists) > 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Homework Upload Failed, User already submitted';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
              
                if (isset($_FILES["doc"]) && !empty($_FILES["doc"]["name"])) {
                    $targetDir = "uploads/homework/";
                    $uploadDir = $targetDir;
                    if(!is_dir($uploadDir)){
                        mkdir($uploadDir , 0777);
                    }

                    $total_count = count($_FILES['doc']['name']);
                    for ($i = 0; $i < $total_count; $i++) { 
                        if (isset($_FILES["doc"]) && !empty($_FILES["doc"]['name'][$i])) {   
                            $tmpFilePath = $_FILES["doc"]["tmp_name"][$i];
                            if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                                $fileName = '';
                                $fileName = $_FILES["doc"]["name"][$i];
                                $filePath = $uploadDir .rand(). '-'. $fileName;
                                if (move_uploaded_file($tmpFilePath, $filePath)) {
                                    $uploadedFiles[] = base_url() . '' . $filePath;
                                }
                            }
                        }
                    }
                }

                $check = $this->db->query("insert into child_homework (user_id,homework_id,exercise_id,added_date,updated_date) values ('$user_id','$homework_id','$exercise_id',now(),now())");
                if ($this->db->affected_rows() > 0) {
                    $contact_id = $this->db->insert_id();
                    for($j = 0; $j < count($uploadedFiles);$j++) {
                        $this->db->query("insert into ch_homework_doc (child_homework_id ,doc,added_date,updated_date) values ('$contact_id','$uploadedFiles[$j]',now(),now())");
                    }
            
                    $final = array();                    
                    $final['child_homework_id'] = $contact_id;
                    $final['status'] = true;
                    $final['message'] = 'Homework Upload Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Homework Upload Failed, Please Check Data Again!';
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
    
    //API to submit quiz answers by students
    public function child_quiz_answer_post(){
        
       // date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
        $input = trim(file_get_contents('php://input'));
        $data = json_decode($input);
     
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }

       if (isset($headers['Authorization'])) {
           $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

            if ($decodedToken['status']) {
                $user_id = $data->user_id;
                $main_quiz_id = $data->main_quiz_id;
                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(!$user_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank ,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }else if(!$main_quiz_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Main Quiz Cannot Be Blank ,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                    
                }else if(count($check_user) === 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $already_exist = $this->db->query("SELECT a.* FROM child_quiz_answer a LEFT OUTER JOIN quiz c on c.id = a.quiz_id WHERE a.user_id='$user_id' AND a.main_quiz_id='$main_quiz_id' AND a.status = '0' AND c.status != '2'")->result_array();
                if(count($already_exist) > 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Quiz Already Submitted!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }
              
                $duration_initial = 0;
                $l = 0;
                if(count($data->quiz_response) > 0) {
                    foreach($data->quiz_response as $list) {                    
                        $check_quiz = $this->db->query("SELECT * FROM quiz where id='$list->quiz_id' and main_quiz_id='$main_quiz_id' and status = '0'")->row_array();
                        $is_pending = 0;
                        $answer = '';
                        $is_checked = 0;
                        $is_correct = 0;
                        $is_wrong = 0;
                        $pair_option_id = 0;
                        $marks = 0;
                        $type = "none";
                    
                        $quiz_id = $list->quiz_id;
                        $quiz_option_id = $list->quiz_options_id;
                        $type = $list->quiz_type;

                        if($list->duration[$l]->screen == 1){
                            $duration = ($list->duration[$l]->duration);
                            $duration_initial = $list->duration[$l]->duration;
                            $duration =  ($duration/60);
                        }else{
                            $get_duration = $list->duration[$l]->duration - $duration_initial;
                            $duration_initial= $list->duration[$l]->duration;
                            $duration = ($get_duration/60);
                        }
                        
                        $duration = $duration < 1 ? 1 : (int)$duration;
                     
                        if($list->quiz_type == "select"){
                            $check_option = $this->db->query("SELECT * FROM quiz_options where id='$quiz_option_id' and quiz_id='$quiz_id' and status = '0'")->row_array();
                            if(!empty($check_option)){
                                if(strtolower($list->option_answer) == strtolower($check_option['ans_name'])){
                                    $is_checked = 1;
                                    $is_correct = 1;
                                    $is_pending = 0;
                                    $is_wrong = 0;
                                    $marks = $check_quiz['marks'];
                                }else {
                                    $is_checked = 1;
                                    $is_correct = 0;
                                    $is_pending = 0;
                                    $is_wrong = 1;
                                }
                            }else{
                                $is_checked = 0;
                                $is_correct = 0;
                                $is_pending = 0;
                                $is_wrong = 0;
                            }
                            $answer = $list->option_answer;
                        }else if($list->quiz_type == "pairs") {
                           
                            if(!empty($list->pairs_answer)){
                                $i=0;
                                $cr_count = array();
                                $wr_count = array();
                                $wr_ans = true;
                                $cr_ans = true;
                                foreach($list->pairs_answer as $key => $val){
                                    $val_arr = (array)$val;
                                    $key_arr = array_keys($val_arr)[0];
                                    $value = $val_arr[$key_arr];
                                    $option_id = (int)$val_arr['option_id'];
                                    $pair_option = $this->db->query("SELECT * FROM quiz_options where id='$option_id' and quiz_id='$list->quiz_id' and status = '0'")->row_array();
                                    if(!empty($pair_option)){
                                    $ansArray = unserialize($pair_option['ans_name']);
                                    if(!empty($ansArray)){
                                            if(ucfirst($ansArray[$key_arr]) == $value){
                                                array_push( $cr_count , $value);
                                                $wr_ans = false;
                                            }else{
                                                array_push( $wr_count , $value);
                                                $cr_ans = false;
                                            }
                                        }else{
                                            array_push( $wr_count , $value);
                                        }       
                                    }
                                    $i++;  
                                }
                                $is_checked = (int)count($cr_count)+ count($wr_count);
                                $is_correct = count($cr_count); //$cr_ans ? 1 : 0 ;
                                $is_pending = 0;
                                $is_wrong = count($wr_count); //$wr_ans ? 1 : 0 ; 
                                //$marks = (float)($check_quiz['marks']/$i)* count($cr_count);
                                
                                $marks = (float)($check_quiz['marks']/count($ansArray))* count($cr_count) ;
                                $answer = implode("|",$cr_count);
                   
                            }else{
                                $is_checked = 1 ;
                                $is_correct = 0 ; //count($cr_count);
                                $is_pending = 0;
                                $is_wrong =  1; //count($wr_count);
                                //$marks = (float)($check_quiz['marks']/$i)* count($cr_count);         
                                $$marks = 0;
                                $answer = null;
                                $list->skip = 1;
                            }
                          
                        }else if($list->quiz_type == "written"){
                            $is_pending = 1;
                            $is_checked = 0;
                            $is_correct = 0;
                            $is_wrong = 0;
                            $answer = $list->written_answer;  
                        }

                        if($list->skip == 1){
                            $is_pending = 0;
                            $is_checked = 0;
                            $is_correct = 0;
                            $is_wrong = 0;
                        }
                        
                    $this->db->query("insert into child_quiz_answer(quiz_type,duration,user_id,main_quiz_id,quiz_id,answer,is_checked,marks,added_date,updated_date,is_skipped,is_wrong,is_correct,is_pending) "
                        . "values ('$type','$duration','$user_id','$main_quiz_id','$quiz_id','$answer','$is_checked','$marks','$date','$date','$list->skip','$is_wrong','$is_correct','$is_pending') ");
                        $l++;
                    }
                    if ($this->db->affected_rows() > 0) {
                        $final = array();                    
                        $final['status'] = true;
                        $final['message'] = 'Quiz Submit Successfully!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    } else {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Quiz Submit Failed, Please Check Data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Quiz Submit Failed, Please Check Data Again!';
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

     //API to get quiz content 
    public function quiz_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        //date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');
        if(!empty($data['date'])){
            $date = $data['date'];
        }else{
            $date = $current_date;
        }
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
            
           if (isset($headers['Authorization'])) {
                $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
                if ($decodedToken['status']) {
                    $data1 = array();
                    if(isset($data['main_quiz_id'])){
                        $main_quiz_id = $data['main_quiz_id'];
                        $main_check_details = $this->db->query("SELECT * FROM main_quiz"
                        . " where id='$main_quiz_id' and ((date = '$current_date' and expiry_time >= '$current_time') or (date > '$current_date'))  and status = '0' and active_status = '1'")->row_array();
                        $main_quiz_id = $main_check_details['id'];
                            if (count($main_check_details) > 0) {
                            $check = $this->db->query("SELECT a.*,c.id as main_quiz_id, b.name AS quiz_type FROM quiz a "
                                . "left outer join main_quiz c on c.id = a.main_quiz_id  "
                            . " LEFT OUTER JOIN quiz_type b ON b.id = a.select_type where c.id='$main_quiz_id' and a.status = '0' and c.status = '0'")->result_array();
                            $st_value = [];
                            if(count($check) > 0){
                                foreach ($check as $value) {
                                        $quiz_id = $value['id'];
                                        $main_quiz_id = $value['main_quiz_id'];
                                        $check_options = $this->db->query("SELECT id,quiz_id,name,ans_name,pair  FROM quiz_options WHERE status != '2' and quiz_id = ?", array($quiz_id))->result_array();
                                        $answer = $this->db->query("select * from quiz_options where quiz_id  = '$quiz_id' and ans_name != ''")->row_array();
                                        $st_value[] = array(
                                            'main_quiz_id' => $value['main_quiz_id'],
                                            'quiz_id' => $value['id'],
                                            'marks' => $value['marks'],
                                            'quiz_name' => $value['name'],
                                            'quiz_type' => $value['quiz_type'],
                                            'quiz_options' => $check_options
                                        );
                                }
                                $data1 = array("main_detils" => $main_check_details, 'quiz_question' => $st_value);
                                $final = array();                    
                                $final['status'] = true;
                                $final['message'] = 'Quiz Questions Successfully!';
                                $final['data'] = $data1;
                                $this->response($final, REST_Controller::HTTP_OK);
                            }else{
                                $final = array();
                                $final['status'] = false;
                                $final['message'] = 'Quiz Questions Not Found, Please Check Data Again!';
                                $this->response($final, REST_Controller::HTTP_OK);
                            }
                        } else {
                            $final = array();
                            $final['status'] = false;
                            $final['message'] = 'Quiz Questions Not Found, Please Check Data Again!';
                            $this->response($final, REST_Controller::HTTP_OK);
                        }
                    }else{
                        $course_id = $data['course_id'];
                        if($course_id ==''){
                            $final = array();                    
                            $final['status'] = false;
                            $final['message'] = 'Course Cannot Be Blank, Please Check Data Again!';
                            $this->response($final, REST_Controller::HTTP_OK);
                        }
                        $st_value = array();
                        $main_check_details = $this->db->query("SELECT * FROM main_quiz"
                        . " where course_id = '$course_id' and ((date = '$current_date' and expiry_time >= '$current_time') or (date > '$current_date'))  and status = '0' and active_status = '1'")->result_array();
                    
                        if (count($main_check_details) > 0) {
                            foreach($main_check_details as $row){
                                $main_quiz_id = $row['id'];
                                $check = $this->db->query("SELECT a.*,c.id as main_quiz_id, b.name AS quiz_type FROM quiz a "
                                    . "left outer join main_quiz c on c.id = a.main_quiz_id  "
                                . " LEFT OUTER JOIN quiz_type b ON b.id = a.select_type where c.course_id = '$course_id' and c.id='$main_quiz_id' and a.status = '0' and c.status = '0'")->result_array();
                                $st_value = [];
                                foreach ($check as $value) {
                                        $quiz_id = $value['id'];
                                        $main_quiz_id = $value['main_quiz_id'];
                                        $check_options = $this->db->query("SELECT id,quiz_id,name,ans_name,pair  FROM quiz_options WHERE status != '2' and quiz_id = ?", array($quiz_id))->result_array();
                                        $answer = $this->db->query("select * from quiz_options where quiz_id  = '$quiz_id' and ans_name != ''")->row_array();
                                        $st_value[] = array(
                                            'main_quiz_id' => $value['main_quiz_id'],
                                            'quiz_id' => $value['id'],
                                            'marks' => $value['marks'],
                                            'quiz_name' => $value['name'],
                                            'quiz_type' => $value['quiz_type'],
                                            'quiz_options' => $check_options
                                        );
                                }
                                if(count($st_value)> 0){
                                    $data1['quiz_details'][] = array("main_detils" => $row, 'quiz_question' => $st_value);
                                } 
                            }
                            $final = array();                    
                            $final['status'] = true;
                            $final['message'] = 'Quiz Questions Successfully!';
                            $final['data'] = $data1;
                            $this->response($final, REST_Controller::HTTP_OK);
                        } else {
                            $final = array();
                            $final['status'] = false;
                            $final['message'] = 'Quiz Questions Not Found, Please Check Data Again!';
                            $this->response($final, REST_Controller::HTTP_OK);
                        }
                    }    
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Authorization Token Expired';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            }
    }
    
     //API to get child quiz submit result 
    public function child_quiz_result_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])){
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $main_quiz_id= $data['main_quiz_id'];
                $correct= $data['correct_ans'];
                $pending_ans= $data['pending_ans'];
                $marks= $data['marks'];
                $duration= $data['duration'];
                $badge= $data['badge_id'];
                $wrong= $data['wrong_ans'];
                $skipped= $data['skip_ans'];
                $compete_ques= $data['completion_ques'];
                $score= $data['score'];
                if($user_id == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($main_quiz_id== ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Main Quiz Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($duration== ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Duration Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($correct== ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Correct Answer Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($wrong== ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Wrong Answer Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($skipped == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Skipped Questions Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($compete_ques == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Completion Questions Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if($score == ''){
                    $final = array();                    
                    $final['status'] = false;
                    $final['message'] = 'Score Cannot Be Blank, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

            $check =$this->db->query("insert into child_quiz_result "
            . " (pending_ans,marks,user_id,main_quiz_id,correct_ans,wrong_ans,skipped_ans,completion_ques,score,added_date,duration,badge_id) "
            . " values "
            . " ('$pending_ans','$marks','$user_id','$main_quiz_id','$correct','$wrong','$skipped','$compete_ques','$score',now(),'$duration','$badge')") ;
    
            if ($this->db->affected_rows() >0) {
                    $final = array();                    
                    $final['status'] = true;
                    $final['result_id'] = $this->db->insert_id();
                    $final['main_quiz_id'] = $main_quiz_id;
                    $final['message'] = 'Quiz Result Send  Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Quiz Result Send Failed, Please Check Data Again!';
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
       //API to quiz details to show on leaderboard screen at frontend 
    public function leaderboard_display_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])){
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $main_quiz_id= $data['main_quiz_id'];
                $time = $data['time'];

                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$main_quiz_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Main Quiz Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }
                
                $sql = '';
                $current_date = date('Y-m-d');
                if($time === 'today'){
                    $sql.=" and DATE(a.added_date) = '$current_date' ";
                }else if($time === 'week'){ 
                    $sql.=" and week(a.added_date) = week('$current_date') ";
                }else if($time === 'month'){
                    $sql.=" and MONTH(a.added_date) = MONTH('$current_date') ";
                }

                $leaderBoard =$this->db->query("SELECT b.id,b.username,b.full_name,b.image,b.student_id, MAX(a.score) AS highest_score,
                MIN(a.duration) AS minimum_duration FROM child_quiz_result a 
                LEFT OUTER JOIN users b ON b.id = a.user_id 
                WHERE a.main_quiz_id = '$main_quiz_id' AND a.status != '2' AND b.status != '2' $sql
                GROUP BY a.user_id ORDER BY highest_score DESC, minimum_duration ASC")->result_array();
    
                if (count($leaderBoard)>0) {
                    $j= 0;
                    for($i = 0;$i < count($leaderBoard); $i++){
                        $j = $j + 1;
                        $leaderBoard[$i]['rank']= $j;
                    }
                } 

                $final = array();                    
                $final['status'] = true;
                $final['message'] = 'Leaderboard Results!';
                $final['data'] = $leaderBoard;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
    
     //API to get quiz result by date 
    public function quiz_result_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])){
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = !empty($data['user_id']) ? $data['user_id'] : null;
                $today= !empty($data['today']) ? $data['today'] : null; 
                $week = !empty($data['week']) ? $data['week'] : null;  
                $month = !empty($data['month']) ? $data['month'] : null;  
                $sql = '';
                if($today !=''){
                    $sql.=" and DATE(a.added_date) = '$today'";
                }
                if($week != ''){
                    $sql.=" and week(a.added_date) = week('$week')";
                    
                }
                if($month != ''){
                    $sql.=" and MONTH(a.added_date) = MONTH('$month')"; 
                }
            
               
            $check_course = $this->db->query("select  b.*,a.score,a.duration,COALESCE( c.title,'') as badge_title,COALESCE(c.image,'') "
            . " as badge_image,COALESCE(c.points,0)as badge_points,d.name as course_name,"
            . " (SELECT COUNT(*) FROM quiz WHERE main_quiz_id = b.id and status != '2') AS total_question"
            . " from child_quiz_result a "
            . " left outer join main_quiz b on b.id = a.main_quiz_id "
            //  . " left outer join quiz e on b.main_quiz_id = a.main_quiz_id "
            . " left outer join badges c on c.id = a.badge_id "
            . " left outer join courses d on d.id = b.course_id "
            . " where a.user_id = '$user_id' $sql  and a.status != '2' and b.status != '2' and d.status != '2'")->result_array();
    
            if (count($check_course)>0) {
                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'All Quiz Results!';
                    
                    $final['data'] = $check_course;
                    $this->response($final, REST_Controller::HTTP_OK);
                
            } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'All Quiz Failed, Please Check Data Again!';
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
    
      //API to get all quiz results details to show on frontend 
    public function quiz_result_detail_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])){
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $main_quiz_id= $data['main_quiz_id'];
                $check = $this->db->query("select b.name as quiz_name,c.name as course_name,COALESCE( d.title,'') as badge_title,(correct_ans +skipped_ans+wrong_ans+completion_ques) as total_value,COALESCE(d.image,'') "
                        . " as badge_image,COALESCE(d.points,0)as badge_points,a.* "
                        . " from child_quiz_result a "
                        . " left outer join quiz e on e.main_quiz_id = a.main_quiz_id "
                        . " left outer join main_quiz b on b.id = a.main_quiz_id "
                        . " left outer join courses c on c.id = b.course_id "
                        . " left outer join badges d on d.id = a.badge_id  "
                        . " where a.main_quiz_id = '$main_quiz_id' and a.user_id = '$user_id' and e.status != '2' and a.status != '2' and b.status != '2' and c.status != '2'")->row_array();
                $check_details = $this->db->query("select a.* from child_quiz_answer a left outer join quiz e on e.id = a.quiz_id where a.main_quiz_id = '$main_quiz_id' and a.user_id = '$user_id' and a.status != '2' and e.status != '2'  group by quiz_id")->result_array();
                if (count($check_details)>0) {            
            
                $j= 0;
                for($i=0;$i<count($check_details);$i++){
                    $j= $j+1;
                    $check_details[$i]['question_no']= $j;
                }
                $detail= array();
                $detail['quiz_detail']=$check;
                $detail['answer']=$check_details;
                $final = array();                    
                $final['status'] = true;
                $final['message'] = 'All Quiz Results!';
                $final['data'] = $detail;
                $this->response($final, REST_Controller::HTTP_OK);
                
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Quiz Result Failed, Please Check Data Again!';
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
       //API to get home work details 
    public function  homework_display_post() {
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
                $course_id = $data['course_id']; 
                $user_id = $data['user_id'];

                if($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User ID Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);                                                                                                                                                                                                                                                                                                                                              
                }
                $date = date('Y-m-d');
                // $sql = $course_id != '' ? `AND e.course_id = $course_id` : '';
                 $sql = $course_id != '' ? 'AND e.course_id ='.$course_id : '';
                
                $homework = $this->db->query("SELECT a.id, a.homework_material, a.homework_title, a.hk_date, a.exercise_id   
                FROM homework a 
                LEFT OUTER JOIN course_exercise h ON h.id = a.exercise_id 
                LEFT OUTER JOIN chapter b ON b.id  = h.chapter_id 
                LEFT OUTER JOIN courses c ON c.id = b.course_id 
                LEFT OUTER JOIN course_type d ON c.course_type_id = d.id 
                LEFT OUTER JOIN mycart e ON e.course_id = b.course_id                   
                WHERE e.child_id = $user_id $sql AND a.status != '2' AND e.is_paid = 2 AND b.status != '2' AND h.status != '2' AND e.status != '2' AND a.hk_date >= '$date';")->result_array();
                if(count($homework) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Homework Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
             
                $mergedHomework = array();
                for ($i = 0; $i < count($homework); $i++) {
                    $exerciseId = $homework[$i]['exercise_id'];
                    $result = $this->db->query("SELECT a.id as exercise_id,a.exercise_no, a.task as exercise, b.chapter_no, b.chapter_name, c.name AS course_name FROM course_exercise a
                    LEFT OUTER JOIN chapter b ON b.id  = a.chapter_id 
                    LEFT OUTER JOIN courses c ON c.id = b.course_id 
                    LEFT OUTER JOIN course_type d ON d.id = c.course_type_id  
                    WHERE a.id in ($exerciseId);")->result_array();

                    for ($j=0; $j < count($result); $j++) { 
                        $homework_id =  $homework[$i]['id'];
                        $exercise_id =  $result[$j]['exercise_id'];
                        $homework_check = $this->db->query("SELECT * FROM child_homework  WHERE user_id = '$user_id' AND homework_id = '$homework_id' AND exercise_id = '$exercise_id' AND status  != '2'")->result_array();
                      //  $homework_check = $this->db->query("SELECT * FROM child_homework  WHERE user_id = '$user_id' AND homework_id = '$homework_id' AND exercise_id = '$exercise_id' AND status  != '2' AND  (homework_status = '1' OR  homework_status = '0')")->result_array();
                            $mergedHomework[$i]['submit_check'] = false;
                            if(count($homework_check) > 0){ 
                                $mergedHomework[$i]['submit_check'] = true;
                            }
                            $mergedHomework[$i]['id'] = $homework[$i]['id'];
                            $mergedHomework[$i]['homework_material'] = $homework[$i]['homework_material'];
                            $mergedHomework[$i]['homework_title'] = $homework[$i]['homework_title'];
                            $mergedHomework[$i]['hk_date'] = $homework[$i]['hk_date'];
                            $mergedHomework[$i]['exercise_id'] = $result[$j]['exercise_id'];
                            $mergedHomework[$i]['exercise_no'] = $result[$j]['exercise_no'];
                            $mergedHomework[$i]['exercise'] = $result[$j]['exercise'];
                            $mergedHomework[$i]['chapter_no'] = $result[$j]['chapter_no'];
                            $mergedHomework[$i]['chapter_name'] = $result[$j]['chapter_name'];
                            $mergedHomework[$i]['course_name'] = $result[$j]['course_name'];
                        //  }
                           
                    }
                }
              
                $response = array();
                for ($i = 0; $i < count($mergedHomework); $i++) { 
                    $homework_material = explode(",",$mergedHomework[$i]['homework_material']);

                    $final_material = array();
                    for ($j=0; $j < count($homework_material); $j++) { 
                        $material = array();
                        $material['title'] = $mergedHomework[$i]['homework_title'];
                        $material['material'] = $homework_material[$j];
                        $material['extension'] = $this->getFileExtension($homework_material[$j]);
                        $final_material[$j] = $material;
                    }

                    unset($homework[$i]['homework_title']);
                    unset($homework[$i]['homework_material']);
                    $response[$i]['homework'] = $mergedHomework[$i];   
                    $response[$i]['homework_material'] = $final_material;
                }
                
                    $final = array();                    
                    $final['status'] = true;
                    $final['message'] = 'Homework Results!';
                    $final['data'] = $response;
                    $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

    private function getFileExtension($filePath) {
            $extension = explode(".",$filePath);
            if(count($extension) > 1) return $extension[count($extension) - 1];  
            return '';
    }
    
      //API to get course details 
    public function course_details_post() {
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
                $course_id = $data['course_id'];
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$course_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Id Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
        
                $check = $this->db->query("select a.*,b.name as course_type_name,c.min_age,c.max_age from courses a 
                        left outer join course_type b on b.id = a.course_type_id 
                        left outer join age_group c on c.id = a.age_group_id 
                         where a.id = '$course_id' and a.status != 2 ")->result_array();
                     
                if(count($check) > 0) { 
                    $check = $check[0];
                    $data1 = array();
                    $parent_relationship =  $this->db->query("select a.* from child_parent_relationship a left outer join users b on b.id = a.parent_id 
                    where a.parent_id = '$user_id' and a.status != '2' and b.status != '2' and a.request_status = '1'")->result_array();
                    $data1['parent_relationship'] = count($parent_relationship) > 0 ?  $parent_relationship : array();
                    $rate = $this->db->query("select * from course_rating where course_id = '$course_id' and status != '2'")->result_array();    
                    $age_query = $check['age_group_id']; 
                    $age= $this->db->query("select * from age_group where status != 2 and id='$age_query'")->result_array();
                    $type_query = $check['course_type_id'];
                    $type= $this->db->query("select * from course_type where status != 2 and id='$type_query' ")->result_array();
                    $average_rating= $this->db->query("SELECT course_id, AVG(rating_value) as average_rating FROM course_rating where course_id ='$course_id' and status !=2")->result_array();
                    $student_count= $this->db->query("select * from mycart where course_id = '$course_id' and status ='0' and is_paid = '2' ")->result_array();  
        
                    if(count($student_count) > 0) {    
                        $student_id = '';
                        for($i=0;$i<count($student_count);$i++){
                            $student_id= $student_id.$student_count[$i]['child_id'].","; 
                        }
                        $trimmedStr = rtrim($student_id, ", ");
                        $student_query = $this->db->query("SELECT id,image,username,full_name FROM users WHERE id IN ($trimmedStr) AND status != '2'")->result_array();
                        $check['course_student_details'] =  $student_query;
                    }
                    $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id = $course_id  AND status != '2'")->result_array();
                    $total_lessons = (string) count($chapters);
                    $course_duration = (string) $this->calculateCourseDuration($course_id);
                    //check course suggested 
                    $check_notification = $this->db->query("select * from notification where sender_id = '$user_id' and notification_type = 'Course Purchase Request' and main_id = $course_id and status != '2' ")->result_array();        
                    if(count($check_notification) > 0){  
                        $check['course_suggested'] = 2;
                    }else{
                        $check['course_suggested'] = 1;
                    }  

                    $check['lesson'] = $total_lessons;
                    $check['course_duration'] = $course_duration;
                    $check['review_count'] = count($rate);            
                    $check['student_count'] = count($student_count);         
                    $check['average_rating'] =  ($average_rating[0]['average_rating'] !== NULL) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                    $data1['timeSlotAndDays']= $this->getCourseAllTimeSlotAndDays($course_id);                    
                    $data1['Course Details'] = $check;
                    $child_details = $this->db->query("select * from child_parent_relationship where parent_id = '$user_id' and request_status = '1' ")->result_array();
                    
                    $isCourseBookmarked = $this->db->query("select * from bookmark where user_id = '$user_id' and course_id = '$course_id' and status != '2'")->result_array();
                    $data1['bookmark_status'] =  (count($isCourseBookmarked) > 0) ? '1' : '0';  
                    if(count($child_details)>0){                 
                        $id = '';
                        for($i=0;$i<count($child_details);$i++){
                            $id = $id."'".$child_details[$i]['child_id']."'".",";
                        }
                    
                        $trim_id = rtrim($id, ',');
                        $detail = $this->db->query("SELECT * FROM users WHERE student_id IN ($trim_id) AND status != '2'")->result_array();
                        $data1['Child Details']= $detail;
                    }else{
                        $data1['Child Details']= array();
                    }
                    
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Results';
                    $final['data'] = $data1;
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Not Found,Please check Data Again!';
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

      //Function to get course all time and date slots data
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
            $start = $classes[$i]['start'];
            $end = $classes[$i]['end'];
            $newTimeSlot = date('g:iA', strtotime($start)).'-'.date('g:iA', strtotime($end));
           // $newTimeSlot = date('g:iA', strtotime($classDateTime)).'-'.date('g:iA', strtotime($classDateTime . ' + 1 hour'));
            $classDay = date('l', strtotime($classDateTime));
            if (!in_array($newTimeSlot, $timeSlots)) {
                array_push($timeSlots, $newTimeSlot);
            }
            array_push($days, $classDay);
        }
    
        return array('timeSlots' => $timeSlots, 'days' => array_values(array_unique($days)));
    }
       //Function to get all master class fro the course
    private function getCourseAllMasterClasses($course_id) {
        return $this->db->query("select a.*,b.chapter_name from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    }
       //API to get ongoing course details
    public function ongoing_course_post() {
        $data= $_POST;
        $user_id = $data['user_id'];
        if($user_id ==''){
            $final = array();
            $final['status'] =false;
            $final['message'] ='User Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }    
    
        $check = $this->db->query("SELECT a.id AS cart_id,c.name AS course_type_name,b.*
            FROM mycart a
            LEFT OUTER JOIN courses b ON b.id = a.course_id
            LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
            WHERE a.child_id = '$user_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
        
        if(count($check) === 0){
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Ongoing Course Not Found,Please check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);  
        }

        for($i=0;$i<count($check);$i++){
            $course_id = $check[$i]['id'];        
            $rate = $this->db->query("select * from course_rating where course_id = '$course_id' and status != '2'")->result_array();   
            $rate = count($rate)>0 ? count($rate) : 0;

            $average_rating = $this->db->query("SELECT course_id, COALESCE(AVG(rating_value),'' ) as average_rating FROM course_rating where course_id ='$course_id' and status != '2'")->result_array();
            $average =  (count($average_rating) > 0 && $average_rating[0]['average_rating']) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
            $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id = '$course_id' AND status !='2' ")->result_array();
            $total_lessons = (string) count($chapters);
            $course_duration = (string) $this->calculateCourseDuration($course_id);
                
            $check[$i]['average_rating'] = $average;
            $check[$i]['review_count'] = $rate;
            $check[$i]['lesson'] = $total_lessons;
            $check[$i]['course_duration'] = $course_duration;
        }

        $final = array();
        $final['status'] = true;
        $final['message'] = 'Ongoing Course Results';
        $final['data'] = $check;
        $this->response($final, REST_Controller::HTTP_OK);
    }
       //API to get course details
    public function index_post() {
                //    $check = $this->db->query("select id,name from courses where status != 2 ")->result_array();
        $check = $this->db->query("select a.id , a.name ,COALESCE(b.name,'') as course_type_name from courses a "
        . " left outer join course_type b on b.id = a.course_type_id "
        . " where a.status != 2 ")->result_array();
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Results';
                    $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
    }
 

       //API to get about us content data
    public function about_us_post() {
        $check = $this->db->query("select * from about_us ")->row_array();
        $final = array();
        $final['status'] = true;                        
        $final['message'] = 'About Us Results';
        $final['data'] = $check;
        $this->response($final, REST_Controller::HTTP_OK);
    }
   //API to get states data
    public function state_post() {
        $data=$_POST;
        $country_id = $data['country_id'];
        $check = $this->db->query("select * from state where country_id= '$country_id' and status != '2' ")->result_array();
        if(count($check)>0){
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'State Results';
            $final['data'] = $check;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Country Id Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
  //API to getsearch states
    public function search_state_post() {
        $data=$_POST;
        $country_id = $data['country_id'];
        $state_name = $data['name'];
        if($country_id == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Country Id Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if($state_name == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "State Name Can Not Be Blank, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $state = $this->db->query("SELECT * FROM state WHERE country_id = $country_id AND name LIKE '" . $state_name . "%' and status != '2'")->result_array();
        $final = array(); 
        $final['status'] = true;
        $final['message'] = 'State Search Results!';
        $final['data'] = $state;
        $this->response($final, REST_Controller::HTTP_OK);
    }
          //API to get single ongoing course details
    public function single_ongoing_course_details_post() {
        $data=$_POST;
        $child_uid = $data['child_uid'];
        $course_id = $data['course_id'];
        if($child_uid == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Child Id Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if($course_id == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Course Id Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $course = $this->db->query("select b.name as course_type_name,a.id,a.name as course_name,a.lesson,a.course_time,a.course_duration,
                    a.description,a.lecture_days from courses a 
                    left outer join course_type b on b.id = a.course_type_id 
                    left outer join chapter d on d.course_id = a.id and d.status = '0'
                    where a.id = '$course_id' and a.status = '0'")->row_array();

        if(!empty($course)){
            $class = $this->db->query("SELECT id,chapter_no,chapter_name FROM chapter WHERE course_id = $course_id AND status != '2'")->result_array();
            if(count($class) > 0) {
                for ($i = 0; $i < count($class); $i++) { 
                    $chpater_id = $class[$i]['id'];
                    $classTimings =  $this->db->query("SELECT MAX(a.upcoming_date) AS class_dateline FROM upcoming_classes a
                    LEFT OUTER JOIN master_classes b ON b.id = a.master_class_id WHERE b.chapter_id = $chpater_id AND a.status != '2' AND b.status != '2' AND a.class_status != 'Cancel' GROUP BY b.chapter_id")->result_array();
                    $class[$i]['class_dateline'] = (count($classTimings) > 0) ? $classTimings[0]['class_dateline'] : '';
                }
            }
            $course['course_classes'] = $class;

            $average_rating= $this->db->query("SELECT course_id, COALESCE(AVG(rating_value),'0') as average_rating FROM course_rating where course_id ='$course_id' and status !=2")->result_array();
            $course['average_rating']= count($average_rating) > 0 ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
            
            $rate = $this->db->query("select count(*) as count from course_rating where course_id = '$course_id' and status !='2' ")->result_array();   
            $course['review_count']= count($rate) > 0 ? $rate[0]['count'] : '0';
            
            
            $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id = '$course_id' AND status !='2' ")->result_array();
            $course['lesson'] = (string) count($chapters);;
            $course['course_duration'] = (string) $this->calculateCourseDuration($course_id);

            $course_progress = $this->getCourseProgress($child_uid,$course_id);
            $course['course_progress'] = $course_progress;

            $certificate = '';
            if($course_progress == '100') {
                $checkCertificate = $this->db->query("SELECT * FROM course_certificate WHERE user_id = $child_uid AND course_id = $course_id AND status != '2'")->result_array();
                $certificate = count($checkCertificate) > 0 ? $checkCertificate[0]['certificate'] : '';
            }

            $course['certificate'] = $certificate;

            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Single Ongoing Course Results';
            $final['data'] = $course;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Single Ongoing Course Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }


    //API to get course applied leaves details
    public function course_apply_leave_post() {
        $data=$_POST;
        $course_id = $data['course_id'];
        $child_uid = $data['child_uid'];
        $message = ucfirst($data['message']);
                        
        if(!$course_id){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Course Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else if(!$child_uid){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Child Uid Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else if(!$message){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Message Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
     //   date_default_timezone_set('Asia/Kolkata');   
        $date = date("Y-m-d H:i:s");                      
        $message = str_replace("'","\'", ucfirst($message)); 
        $check = $this->db->query("insert into course_leave (course_id,child_id,message,added_date,updated_date) values ('$course_id','$child_uid','$message','$date','$date')");
        if($this->db->affected_rows()){ 
            $id = $this->db->insert_id();
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Course Leave Send Successfully!';
            $final['leave_id'] =$id;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Course Leave Send Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
        //API to get race from db
    public function race_post() {
        $check= $this->db->query("select id,name from races where status != 2 ")->result_array();
        $final = array();
        $final['status'] = true;
        $final['data']= $check;
        $final['message'] = 'Race Results';
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
         //API to get country 
    public function country_post() {
        $countries = $this->db->query("select * from country where status != '2' ")->result_array();
        $topCountries = ['Malaysia', 'Singapore'];
        foreach ($topCountries as $country) {
            foreach ($countries as $key => $countryData) {
                if ($countryData['name'] == $country) {
                    array_splice($countries, 0, 0, array_splice($countries, $key, 1));
                }
            }
        }
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Country Results';
        $final['data'] = $countries;
        $this->response($final, REST_Controller::HTTP_OK); 
    } 

       //API to get search country by name
    public function country_search_post() {
        $data = $_POST;
        $country_name = $data['country_name'];
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Country Results';
        $final['data'] = $this->db->query("SELECT * FROM country WHERE name LIKE '" . $country_name . "%' and status != '2'")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
 
    
       //API to getdialect
    public function dialect_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Dialect Results';
        $final['data'] = $this->db->query("select id,name from dialect where status != 2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
         //API to get religion 
    public function religion_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Religion Results';
        $final['data'] = $this->db->query("select id,name from religion where status != 2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
        
    public function nric_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'NRIC/Passport Results';
        $final['data'] = $this->db->query("select id,name from nric_types where status != 2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
          //API to get nationality 
    public function nationlity_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Nationlity Results';
        $final['data'] = $this->db->query("select id,name from nationlity where status != '2'")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    } 
        //API to get city 
    public function city_post() {
        $data=$_POST;
        $state_id = $data['state_id'];
        $sql='';
        if($state_id != ''){
            $sql.= " and state_id = '$state_id'";
        }
        $check = $this->db->query("select * from city where 1 $sql ")->result_array();
        $final = array(); 
        if(count($check)>0){
            $final['status'] = true;
            $final['message'] = 'City Results';    
            $final['data'] = $check;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final['status'] = false;
            $final['message'] = "State Id Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
   //API to search city by name 
    public function search_city_post() {
        $data=$_POST;
        $state_id = $data['state_id'];
        $city_name = $data['name'];
        if($state_id == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "State Id Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if($city_name == '') {
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "City Name Can Not Be Blank, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $state = $this->db->query("SELECT * FROM city WHERE state_id = $state_id AND name LIKE '" . $city_name . "%'")->result_array();
        $final = array(); 
        $final['status'] = true;
        $final['message'] = 'City Search Results!';
        $final['data'] = $state;
        $this->response($final, REST_Controller::HTTP_OK);
    }
    //API to get subject 
    public function subject_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Subject Results';
        $final['data'] = $this->db->query("select id,name from subject where status != 2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK); 
    }
         //API to reschedule classes for course
    public function reschedule_course_display_post() {
        $data=$_POST;
        $course_id = $data['course_id'];
        $user_id = $data['user_id'];
        if($course_id ==''){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Course Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        $check = $this->db->query("select a.* ,'10:00 AM - 12:00 AM' as timing ,b.course_id,c.name as course_type,b.chapter_no,b.chapter_name,d.course_time,d.name "
                . " as course_name from upcoming_classes a  "
                . "left outer join master_classes b1 on b1.id = a.master_class_id  "
                . "left outer join chapter b on b.id = b1.chapter_id  "
                . " left outer join courses d on d.id = b.course_id "
                . " left outer join course_type c on c.id = d.course_type_id "
                . "where b.course_id = '$course_id' and a.class_status = '0'")->result_array();
        if(count($check)>0){ 
            $check_user = $this->db->query("select * from mycart where child_id = '$user_id' and status ='0' and is_paid = '2' and course_id = '$course_id' ");
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Reschedule Course Results!';
            $final['data'] = $check;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = false;
            $final['message'] = "Reschedule Course Failed,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }    
    //API to add reschedule classes request for course
    public function reschedule_course_post() {
        $data=$_POST;
        $user_id = $data['user_id'];
        $upcoming_id = $data['upcoming_id'];
        $reason  = str_replace("'","\'",  $data['reason']); 
        if(!isset($data['user_id'])){
           
            $final = array();
            $final['status'] =false;
            $final['message'] ='User Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } else if(!isset($data['upcoming_id'])){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Upcomming Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } else if(!isset($data['reason'])){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Reason Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
        
        
        $alreadyRescheduleClass = $this->db->query("select * from reschedule_classes where user_id = '$user_id' and upcoming_id ='$upcoming_id' and status != '2'")->result_array();
        
        if(count($alreadyRescheduleClass) > 0){    
            $final = array(); 
            $final['status'] = true;
            $final['message'] = "You Have Already Requested To Reschedule This Class!";
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $upcoming_class_data = $this->db->query("select a.*,b.course_id from upcoming_classes a " 
            . "left outer join master_classes b1 on b1.id = a.master_class_id  "
            . "left outer join chapter b on b.id = b1.chapter_id  "
            . "where a.id ='$upcoming_id' and a.class_status !='Cancel' and a.status != '2'")->row_array();
           
            $start = strtotime($upcoming_class_data['start']);
            $end =  strtotime($upcoming_class_data['end']);
            $class_date =  strtotime($upcoming_class_data['upcoming_date']);

            $strat_time = date("g:iA",$start); 
            $end_time = date("g:iA",$end); 
            $time_slot_id = $strat_time."-".$end_time;
            $class_day = date('l', $class_date);
            $this->db->set('time_slot_id', $time_slot_id);
            $this->db->set('course_days_id', $class_day);
            $this->db->where('course_id', $upcoming_class_data['course_id']); 
            $this->db->where('child_id', $user_id); 
            $this->db->update('mycart');

            $reason = $reason." Reschedule class to this ". $time_slot_id." ".$class_day;

            $check = $this->db->query("insert into reschedule_classes(user_id, upcoming_id, reschedule_reason, added_date, updated_date ) values ('$user_id','$upcoming_id','$reason',now(),now()) ");
            $id = $this->db->insert_id();
            
            $final = array(); 
            $final['status'] = true;
            $final['reschedule_id'] = $id;
            $final['message'] = 'Your Reschedule Class Request Sent Successfully!';
            $final['data'] = $check;
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }    
      
       //API to get course exercise 
    public function course_exercise_post() {
        $data=$_POST;
        $course_id = $data['course_id'];
        
        if($course_id ==''){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Course Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
        
            $course_exercises = $this->db->query("SELECT a.*,b.chapter_no ,b.chapter_name, '0' as work_status, a.added_date as date
            FROM  course_exercise a 
            LEFT OUTER JOIN chapter b ON b.id = a.chapter_id 
            where b.course_id ='$course_id' AND b.status != '2' AND a.status != '2'")->result_array();
                
        if(count($course_exercises) > 0){    
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Course Exercise Results!';
            $final['data'] = $course_exercises;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = true;
            $final['message'] = "Course Exercise Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }

    //API to get single exercise data
    public function single_exercise_post() {
        $data=$_POST;
        $exercises_id = $data['exercises_id'];
        
        if($exercises_id ==''){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Exercise_id Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
        
            $course_exercises = $this->db->query("SELECT a.*,b.chapter_no ,b.chapter_name, '0' as work_status, a.added_date as date
            FROM  course_exercise a 
            LEFT OUTER JOIN chapter b ON b.id = a.chapter_id 
            where a.id ='$exercises_id' AND b.status != '2' AND a.status != '2'")->row_array();
                
        if(count($course_exercises) > 0){    
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Course Exercise Results!';
            $final['data'] = $course_exercises;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = true;
            $final['message'] = "Course Exercise Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
      //API to get materials data
    public function course_material_post() {
        $data=$_POST;
        $course_id = $data['course_id'];
        
        if($course_id ==''){
            $final = array();
            $final['status'] =false;
            $final['message'] ='Course Cannot Be Blank,Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
        
        $course_material= $this->db->query("select * from course_material where is_active ='0' and status ='0' and course_id = '$course_id'")->result_array();
                
        if(count($course_material) > 0){    
            $final = array(); 
            $final['status'] = true;
            $final['message'] = 'Course Material Results!';
            $final['data'] =$course_material;
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final = array(); 
            $final['status'] = true;
            $final['message'] = "Course Material Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }    
    
      //API to get all upcoming classes for the course
    public function classes_display_post() {
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
          //  date_default_timezone_set('Asia/Kolkata');
                $today = !empty($data['today']) ? $data['today'] : '';
                $user_id = !empty($data['user_id']) ? $data['user_id'] : '' ;
                $this_week =  !empty($data['week']) ? $data['week'] : '' ;
                $next_week =  !empty($data['next_week']) ? $data['next_week'] : '' ; 
                $course_id =  !empty($data['course_id']) ? $data['course_id'] : '' ; 
                $current_date = date('Y-m-d H:i:s');
                $current_time = date("H:i:s");
                $sql ='';
                if($today) {
                    $sql.= " and  DATE(a.upcoming_date) = '$today' and end > '$current_time'";
                }else if($next_week) {
                    // $sql.= " and YEARWEEK(a.upcoming_date, 1) = YEARWEEK('$next_week' + INTERVAL 1 WEEK, 1) ";
                      $sql.= " and YEARWEEK(a.upcoming_date, 1) = YEARWEEK('$next_week' + INTERVAL 1 WEEK, 1) ";
                }else if($this_week) {
                    $future = date("Y-m-d", strtotime("+3 month", strtotime($this_week)));
                    $past = date('Y-m-d', strtotime('-3 month', strtotime($this_week)));
                    $sql.=" and (a.upcoming_date <= '$future' and a.upcoming_date >= '$past')";

                   // $sql.= " and WEEK(a.upcoming_date) = WEEK('$this_week') ";
                 //   $sql.= " and WEEK(a.upcoming_date) = WEEK('$this_week') and  a.upcoming_date > '$current_date'";
                }else{ 
               
                     if($course_id) { 
                        $sql = " and a.upcoming_date > '$current_date' and c.id = '$course_id'";
                     }else{
                        $sql = " and a.upcoming_date > '$current_date'";
                     }
                   
                }
               
           
                $upcoming_classes = $this->db->query("select a.id,b1.chapter_id,a.recurring,a.upcoming_date,a.start,a.end,a.class_status,c.name as course_name,
                    b.course_id, b.chapter_name,b.chapter_no, (d.name) as course_type ,(e.id) as cart_id 
                    from upcoming_classes a
                    left outer join master_classes b1 on b1.id = a.master_class_id 
                    left outer join chapter b on b.id  = b1.chapter_id 
                    left outer join courses c on c.id = b.course_id
                    left outer join course_type d on c.course_type_id = d.id
                    left outer join mycart e on e.course_id = b.course_id
                    where e.child_id = '$user_id' and a.status != '2' and b1.status != '2' and b.status != '2' and 
                    c.status != '2' and d.status != '2' and e.status = '0' and e.is_paid = '2' and a.class_status != 'Cancel' $sql ")->result_array();
                 

                    if (count($upcoming_classes) === 0) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Classes Failed,Please check data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }

                    for ($i = 0; $i < count($upcoming_classes); $i++) { 
                        $course_id = $upcoming_classes[$i]['course_id'];
                        $cart_id = $upcoming_classes[$i]['cart_id'];
                        $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id = '$course_id' AND status !='2' ")->result_array();
                        $cart_data = $this->db->query("SELECT * FROM mycart WHERE id = '$cart_id' AND status !='2' ")->row_array();
                        $time_slot_arr= explode("-",$cart_data['time_slot_id']);

                        $upcoming_classes[$i]['user_slot_start'] = $time_slot_arr[0];
                        $upcoming_classes[$i]['user_slot_end'] = $time_slot_arr[1];
                        $upcoming_classes[$i]['user_slot_day'] = $cart_data['course_days_id'];
                        $upcoming_classes[$i]['date'] = $this->getDate($upcoming_classes[$i]['upcoming_date']);
                        $upcoming_classes[$i]['course_duration'] = (string) $this->calculateCourseDuration($course_id);
                    }
                
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Classes Results!';
                    $final['data'] = $upcoming_classes;
                    $this->response($final, REST_Controller::HTTP_OK);
              
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

    private function getDate($dateTime) {
        $date = explode(" ", $dateTime);
        return $date[0];
    }
    

      //API to check in the classa at frontend 
    public function check_in_post() {
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
                $course_id = $data['class_id'];
                
                $check = $this->db->query("insert into check_in () values () ");
                $id = $this->db->insert_id();
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['class_id '] = $id;
                    
                    $final['message'] = 'Check In Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Check In Failed,Please Check Data Again!';
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
    
      //Function to calculate the duration for course
    private function calculateCourseDuration($course_id) {
        $masterClasses = $this->getCourseAllMasterClasses($course_id);
        if(count($masterClasses) === 0) {
            return '0h';
        }
    
        $course_duration = 0;
        for ($i = 0; $i < count($masterClasses); $i++) { 
            $time = explode(" ",$masterClasses[$i]['class_duration'])[0];
            $course_duration = $course_duration + $time;
        }
        $total_minutes = $course_duration % 60;        
        $total_hour = ($course_duration - $total_minutes) / 60;
    
        $final_course_duration  = ($total_minutes === 0) ? $total_hour."h" : $total_hour."h ".$total_minutes."min";
        return $final_course_duration;
    }

        //API to get progress
    public function course_progress_post() {
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
                if(!$child_uid) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Uid Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
            $ongoing_courses = $this->db->query("SELECT b.id,b.image,b.name,c.name AS course_type_name
            FROM mycart a
            LEFT OUTER JOIN courses b ON b.id = a.course_id
            LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
            WHERE a.child_id = '$child_uid' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
            if(count($ongoing_courses) === 0) {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Course Progress Not Found!';
                $this->response($final, REST_Controller::HTTP_OK); 
            }

            for ($i=0; $i < count($ongoing_courses); $i++) { 
                $course_id = $ongoing_courses[$i]['id'];
                $ongoing_courses[$i]['course_progress'] = $this->getCourseProgress($child_uid,$course_id);
            }

            $final = array();
            $final['status'] = true;
            $final['data'] = $ongoing_courses;
            $final['message'] = 'Course Progress Result!';
            $this->response($final, REST_Controller::HTTP_OK);

            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

     //API to get progress of student for the course
    private function getCourseProgress($child_uid,$course_id) {
        $masterClasses = $this->db->query("select a.id from master_classes a 
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where c.id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
        
        if(count($masterClasses) > 0) {
            $masterClassesIds = '';
            for ($i = 0; $i < count($masterClasses); $i++) { 
               $id = $masterClasses[$i]['id'];
               $masterClassesIds = $masterClassesIds.$id.',';
            }
            
            $masterClassesIds = rtrim($masterClassesIds, ',');
            $attendance = $this->db->query("select a.master_class_id from check_in a 
            left outer join master_classes b1 on b1.id = a.master_class_id
            left outer join chapter c ON c.id = b1.chapter_id
            left outer join courses d ON d.id = c.course_id 
            where a.master_class_id in($masterClassesIds) and a.user_id = '$child_uid' and d.status != '2' and c.status!= '2' and b1.status!='2' group by a.master_class_id;")->result_array();
        
            return ceil((count($attendance) / count($masterClasses)) * 100);
        }

        return 0;
    }


       //API to get course type
    public function course_type_list_get() { 
        $courseTypeList = $this->db->query("SELECT * FROM course_type WHERE status != '2'")->result_array();
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Course Type List Result!';
        $final['data'] = $courseTypeList;
        $this->response($final, REST_Controller::HTTP_OK);
    }


       //API to get gallery for the course
    public function course_gallery_display_post() { 
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
                $month = $data['month'];
                if(!$child_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $currentMonthNumber = (int) date('m', strtotime(date('Y-m-d')));
                $sql = $month ? " AND MONTH(a.created_at) = $month " : " AND MONTH(a.created_at) = $currentMonthNumber ";

                $ongoingCourses =  $this->db->query("SELECT b.id AS course_id FROM mycart a
                LEFT OUTER JOIN courses b ON b.id = a.course_id
                LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
                WHERE a.child_id = '$child_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
                if(count($ongoingCourses) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Ongoing Courses And Course Gallery Not Found!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $finalGallery = array();
                for ($i = 0; $i < count($ongoingCourses); $i++) { 
                    $course_id = $ongoingCourses[$i]['course_id'];

                    $courseGallery =  $this->db->query("SELECT a.id,a.gallery,a.created_at,b.id as folder_id,b.folder_name,b.folder_image,c.id as course_id,c.name as course_name FROM course_gallery a 
                    LEFT OUTER JOIN course_gallery_folders b ON b.id = a.gallery_folder_id
                    LEFT OUTER JOIN courses c ON c.id = b.course_id WHERE b.course_id = $course_id AND a.status != '2' AND b.status != '2' $sql")->result_array();
                  
                    if(count($courseGallery) > 0) {
                        for ($j = 0; $j < count($courseGallery); $j++) { 
                            $allMedia = explode(",",$courseGallery[$j]['gallery']);
                            $courseGallery[$j]['total_files'] = count($allMedia) > 0 ? count($allMedia) : 0;
                            array_push($finalGallery,$courseGallery[$j]);
                        }
                    }
                }

                $final = array();
                $final['status'] = true;
                $final['message'] = 'Course Gallery Results!';
                $final['data'] = $finalGallery;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

      //API to get gallery details 
    public function course_gallery_detail_post() { 
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
                $folder_id = $data['folder_id'];
                if(!$folder_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Folder Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $courseGallery =  $this->db->query("SELECT a.id,a.gallery,a.created_at,b.folder_name,c.id as course_id,c.name as course_name FROM course_gallery a 
                LEFT OUTER JOIN course_gallery_folders b ON b.id = a.gallery_folder_id
                LEFT OUTER JOIN courses c ON c.id = b.course_id WHERE b.id = $folder_id AND a.status != '2' AND b.status != '2'")->result_array();
                if(count($courseGallery) > 0) {
                    $allMedia = explode(",",$courseGallery[0]['gallery']);
                    $modifiedMedia = array();
                    for ($i = 0; $i < count($allMedia); $i++) { 
                        $mediaInfo = array();
                        $mediaInfo['file'] = $allMedia[$i];
                        $mediaInfo['filename'] = basename($allMedia[$i]); 
                        array_push($modifiedMedia, $mediaInfo);                     
                    }

                    $courseGallery[0]['gallery'] = $modifiedMedia;
                }

                $final = array();
                $final['status'] = true;
                $final['message'] = 'Course Gallery Detail Results!';
                $final['data'] = count($courseGallery) ? $courseGallery[0] : [];
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

      //API to check class is currently running or not
    public function is_class_running_post() {
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
                $class_id = $data['class_id'];
                if(!$child_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Id Not Found!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $ongoing_courses = $this->db->query("SELECT b.id
                FROM mycart a
                LEFT OUTER JOIN courses b ON b.id = a.course_id
                LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
                WHERE a.child_id = '$child_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
                if(count($ongoing_courses) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Ongoing Course Not Found!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $currentDate = date('Y-m-d');
                $currentTime = date('H:i:s');
                $ongoing_course_ids = '';
                for ($i=0; $i < count($ongoing_courses); $i++) { 
                    $course_id = $ongoing_courses[$i]['id'];
                    $ongoing_course_ids = $ongoing_course_ids.$course_id.',';
                }
                $ongoing_course_ids = rtrim($ongoing_course_ids, ',');

                $currentClass = $this->db->query("select a.*,d.id as course_id,d.name as course_name,e.name as course_type,c.chapter_name,c.chapter_no
                from upcoming_classes a 
                left outer join master_classes b on b.id = a.master_class_id
                left outer join chapter c on c.id = b.chapter_id
                left outer join courses d on d.id = c.course_id
                left outer join course_type e on e.id = d.course_type_id
                where a.status != '2' and b.status != '2' and c.status != '2' and d.status != '2' and 
                d.id in ($ongoing_course_ids) and DATE(a.upcoming_date) = '$currentDate' and a.class_status != 'Cancel' and a.id = '$class_id'")->result_array();
                if(count($currentClass) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Today Class Not Found!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $classId = $currentClass[0]['id'];
               
                $alreadyCheckedIn =  $this->db->query("SELECT * FROM check_in WHERE upcoming_id = '$classId' AND user_id = '$child_id' AND status != '2'")->row_array();
                if(empty($alreadyCheckedIn)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Class Found But You Are Not Checked In!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
               
                if(strtotime($currentTime) > strtotime($alreadyCheckedIn['end_time'])) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Checked out!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $userDetail = $this->db->query("select a.id,a.username,a.full_name,b.name as country_name , c.name as state_name,d.name as city_name from users a
                left outer join country b on b.id = a.country
                left outer join state c on c.id = a.state
                left outer join city d on d.id = a.city where a.id = '$child_id' and a.status != '2'")->row_array();
                $currentClass[0]['user_details'] = $userDetail;

                $final = array();
                $final['status'] = true;
                $final['checkin_time'] = $alreadyCheckedIn['start_time'];
                $final['data'] = $currentClass[0];
                $final['message'] = 'Current Class Result!';
                $this->response($final, REST_Controller::HTTP_OK);
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
}
