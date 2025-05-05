<?php

defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH . '/libraries/REST_Controller.php');
//require(APPPATH.'/helpers/number_helper.php'); 

use Restserver\Libraries\REST_Controller;

class Home extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('user_model');

    }
// calculate the time difference in checkin api 
 public function calculateTimeDifference($start_time, $end_time) {
        // Convert the string times to DateTime objects
        $start_datetime = $this->createDateTime($start_time);
        $end_datetime = $this->createDateTime($end_time);

        // Calculate the difference
        $time_difference = $start_datetime->diff($end_datetime);

        // Format the difference in hours, minutes, and seconds
        $hours = $time_difference->format('%h');
        $minutes = $time_difference->format('%i');
        $seconds = $time_difference->format('%s');

        // Return the formatted time difference
        return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
    }

    private function createDateTime($time) {
        // Parse the time string to extract hours, minutes, and optional AM/PM
        $time_parts = date_parse_from_format('h:i:s A', $time);

        $hours = $time_parts['hour'];
        $minutes = $time_parts['minute'];
        $seconds = $time_parts['second'];

        // Create a DateTime object
        $datetime = new DateTime();
        $datetime->setTime($hours, $minutes, $seconds);

        return $datetime;
    }
    //API to check in class
    public function checkin_class_display_post() {
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
                $qr = $data['qr'];
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }
               
                if(!$qr) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'QR Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $validQR =  $this->db->query("SELECT * FROM download_qr WHERE qr = '$qr'")->result_array();
                if(count($validQR) < 1) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'QR Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $qrArray = explode("-", $qr);
             
                if(count($qrArray) !== 2) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Invalid QR Format, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);  
                }
               

                $qrString = $qrArray[0];
                $arIDupdateDate = explode("@", $qrArray[1]);
                $classId = $arIDupdateDate[0];
                $updatedTimeQr = date("Y-m-d H:i:s", strtotime($arIDupdateDate[1]) );
              
               // date_default_timezone_set("asia/Kuala_Lumpur");
                                                                                                                                        
                $currentDateTime = date('Y-m-d H:i:s');
                $currentDate = date('Y-m-d'); 
                $currentTime = date('H:i:s');
             

                $classDetail = $this->db->query("select a.*,c.id as course_id,c.name as course_name,d.name as course_type,b.chapter_name,
                b.chapter_no,b.course_id from upcoming_classes a 
                left outer join master_classes b1 on b1.id = a.master_class_id
                left outer join chapter b on b.id = b1.chapter_id  
                left outer join courses c on c.id = b.course_id
                left outer join course_type d on d.id = c.course_type_id
                where a.id = '$classId' and DATE(a.upcoming_date) = '$currentDate'
                and a.status != '2' and b1.status != '2' and b.status != '2' and c.status != '2' and d.status != '2' and a.class_status != 'Cancel'")->row_array();
                if(empty($classDetail)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Class was removed, please check with Teacher!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                 // date_default_timezone_set('Asia/Kolkata');
              // date_default_timezone_set("asia/Kuala_Lumpur");
                $start_time = $classDetail['start'];
                $currentTime = date('H:i:s');              
                $end_time = $classDetail['end'];
                if(strtotime($currentDateTime) > strtotime($classDetail['upcoming_date'])) {
               // if(strtotime($currentDateTime) > strtotime($updatedTimeQr)) {
                    $date1 = new DateTime();
                    $date2 = new DateTime($classDetail['upcoming_date']);
                    $interval = $date1->diff($date2);
                    $seconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
                
                    $twoMinutesInSeconds = 300;
                    if($seconds > $twoMinutesInSeconds) {
                        if(strtotime($currentTime) < strtotime($end_time)) {
                            $final = array();
                            $final['status'] = false;
                            $final['message'] = "Its 5 minutes over class time, please see Teacher for attendance!";
                            $this->response($final, REST_Controller::HTTP_OK);
                        }
                    }        
                }

                
                $alreadyCheckedIn =  $this->db->query("SELECT * FROM check_in WHERE upcoming_id = '$classId' AND user_id = '$user_id' AND status != '2'")->row_array();
                if(!empty($alreadyCheckedIn)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Check in successful, enjoy learning!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
               // date_default_timezone_set('Asia/Kolkata');
              // date_default_timezone_set("asia/Kuala_Lumpur");
              //  $currentTime = date('H:i:s');

                $course_id = $classDetail['course_id'];
               
             //   $end_time = $classDetail['end'];
                $masterClassId = $classDetail['master_class_id'];
                $cart = $this->db->query("select * from mycart where status = '0' and is_paid = '2' and child_id = '$user_id' and course_id = '$course_id'")->row_array();
                if (empty($cart)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'You are not registered for this class!, if there was a mistake please report to app support!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                 
                if(strtotime($currentTime) < strtotime($start_time)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "You are early! Please scan on class start time, and no longer than 5 minutes after class.: $start_time";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                if(strtotime($currentTime) > strtotime($end_time)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = " This class has ended, seek Teacher for manual attendance!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $userDetail = $this->db->query("select a.id,a.username,a.full_name,b.name as country_name , c.name as state_name,d.name as city_name from users a
                            left outer join country b on b.id = a.country
                            left outer join state c on c.id = a.state
                            left outer join city d on d.id = a.city where a.id = '$user_id' and a.status != '2'")->row_array();
                $classDetail['user_details'] = $userDetail;
                
                $this->db->query("INSERT INTO check_in (master_class_id,upcoming_id,course_id,user_id,start_time,end_time,added_date,updated_date)
                                VALUES('$masterClassId','$classId','$course_id','$user_id','$currentTime','$end_time','$currentDateTime','$currentDateTime')");
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Check In Succesfully!';
                    $final['checkin_time'] = $currentTime;
                    $final['data'] = $classDetail;
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Check In Failed, Please Check Data Again!';
                    $final['checkin_time'] = $currentTime;
                    $final['data'] = $classDetail;
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
        //API to check in class details
    public function checkin_post() {
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
                $qr = $data['qr'];
                if(!$user_id) {
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }  
                if(!$qr) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'QR Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $validQR =  $this->db->query("SELECT * FROM download_qr WHERE qr = '$qr'")->result_array();
                if(count($validQR) < 1) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'QR Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK); 
                }

                $qrArray = explode("-", $qr);
                if(count($qrArray) !== 2) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Invalid QR Format, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);  
                }

                $qrString = $qrArray[0];
                $arIDupdateDate = explode("@", $qrArray[1]);
                $classId = $arIDupdateDate[0];
                $updatedTimeQr = date("Y-m-d H:i:s", strtotime($arIDupdateDate[1]) );
                // $classDetail = $this->db->query("SELECT * FROM upcoming_classes WHERE id = '$classId' and qr_code = '$qr' AND status != '2' AND class_status != 'Cancel'")->row_array();
                $classDetail = $this->db->query("SELECT * FROM upcoming_classes WHERE id = '$classId' AND status != '2' AND class_status != 'Cancel'")->row_array();
                if(empty($classDetail)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Class was removed, please check with Teacher!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                // date_default_timezone_set("asia/Kuala_Lumpur");
              // date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                $end_time = date('H:i:s');
                if(strtotime($end_time) > strtotime($classDetail['end'])) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Class Already End!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $this->db->query("UPDATE check_in SET end_time = '$end_time', updated_date = '$currentDateTime' WHERE upcoming_id = '$classId' AND user_id = '$user_id' AND status != '2'");
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Student Check Out Successfully';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Student Check Out Failed,Please Check Data Again!';
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
      //API to check in class by parent
    public function parent_checkin_post() {
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
                $child_id =  !empty($data['child_id']) ? $data['child_id'] : '' ;  
                $user_id = !empty($data['user_id']) ? $data['user_id'] : ''; 
                $today = !empty($data['today']) ? $data['today'] : '';
                $this_week = !empty($data['week']) ? $data['week']: '';  
                $sql = '';

                if($today !=''){
                    $sql.= "and  DATE(a.added_date) =  '$today' ";
                }
                if($this_week !=''){
                    $sql.= "and  WEEK(a.added_date) = WEEK('$this_week')";
                }
                
                if($user_id==''){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $check = $this->db->query("select b.id as cart_id ,c.image as child_image,c.username,c.full_name,j.chapter_no ,j.chapter_name,"
                        . " g.name as course_name,h.name as course_type,e.name as state,d.name as country,f.name as city "
                        . " ,b.user_id as parent_id,a.* from check_in a  "
                        . " left outer join mycart b on b.child_id = a.user_id and b.course_id = a.course_id "
                        . " left outer join users c on c.id = a.user_id "
                        . " left outer join country d on d.id = c.country "
                        . " left outer join state e on e.id = c.state "
                        . " left outer join city f on f.id = c.city "
                        . " left outer join courses g on g.id = a.course_id "
                        . " left outer join course_type h on h.id = g.course_type_id "
                        . " left outer join upcoming_classes i on i.id = a.upcoming_id "
                        . " left outer join master_classes b1 on b1.id = i.master_class_id "
                        . " left outer join chapter j on j.id = b1.chapter_id"
                        . " where a.user_id = '$child_id' and b.status = '0' and is_paid = '2' and i.class_status != 'Cancel' and b.user_id  = '$user_id' $sql ORDER BY a.added_date DESC ")->result_array();

                if (count($check)>0) {
                    for ($i = 0; $i < count($check); $i++) { 
                        $check[$i]['spent_hours'] = $this->calculateTimeDifference($check[$i]['start_time'],$check[$i]['end_time']);
                    }

                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Parent Check-In Successfully';
                    $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Parent Check-In Failed,Please Check Data Again!';
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

     //API to get child infor to get display at frontend  
    public function child_display_post() {
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
                if($user_id==''){
                   $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again';
//                    $final['data'] =$check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } 
                $check = $this->db->query("select a.id as relation_id,b.id as child_id ,b.username ,b.full_name,b.student_id,b.image from child_parent_relationship a "
                        . "left outer join users b on b.student_id = a.child_id "
                        . " where a.parent_id = '$user_id' and a.request_status = 1 and b.status != '2'")->result_array();
                if (count($check)>0){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Child Details Results';
                                        $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Details Failed,Please Check Data Again!';
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
    
     //API to get news data added by admin
    public function news_display_post() {
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
                if(!$user_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank,Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($check_user)=== 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $check = $this->db->query("SELECT * FROM news WHERE status != '2' ORDER BY added_date DESC")->result_array();
                if(count($check) > 0 ) {
                    for ($i=0; $i < count($check); $i++) { 
                        $check[$i]['user_image'] = $check_user[0]['image'];
                        $check[$i]['username'] = $check_user[0]['username'];
                        $check[$i]['full_name'] = $check_user[0]['full_name'];
                    }
                }
                if (count($check)>0){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'News Results';
                    $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'News Failed,Please Check Data Again!';
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
       //API to slider data display at frontend 
    public function slider_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Slider Results';
        $final['data'] = $this->db->query("select * from slider where status !=2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK);
    }
    
       //API to search course with course type
    public function search_course_with_type_post() {
        $data = $_POST;
        $course_type = $data['course_type'];
        $type_of_course = $data['type_of_course'];
        $course_name = $data['course_name'];
        $user_id= $data['user_id'];

        if ($user_id== '') {
            $final = array();
            $final['status'] = false;
            $final['data'] = "User Cannot Be Blank,Please Check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $sql = "";
        if ($course_name != '') {
            $sql .= " and name like '$course_name%' ";
        }
        
        if ($course_type != '') {
            $sql .= " and course_type_id ='$course_type' ";
        }
        
        if($type_of_course === 'recommended'){
            $user = $this->db->query("SELECT * FROM users where id = $user_id AND status != '2'")->result_array();
            $courses = $this->getRecommendedCourses($user);
        }else if($type_of_course === 'trending'){
            $courses = $this->getTrendingCourses();
        }else{
            $courses = $this->db->query("select * from courses where status != '2' $sql")->result_array();
        }

        if (count($courses) > 0) {
            for ($i = 0; $i < count($courses); $i++) {
                $id = $courses[$i]['id'];
                $average_rating = $this->db->query("SELECT course_id,COALESCE(CAST(AVG(rating_value) AS CHAR), '') as average_rating FROM course_rating where course_id ='$id' and status !=2")->result_array();
                $courses[$i]['average_rating'] = $average_rating[0]['average_rating'] != '' ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                
                $bookmark = $this->db->query("select * from bookmark where course_id = '$id' and status != '2' and user_id = '$user_id' ")->result_array();
                $courses[$i]['bookmark_status']= (count($bookmark) > 0) ? '1' : '0';
                
                $student_count= $this->db->query("select * from mycart where course_id = '$id' and status ='0' and is_paid = '2' ")->result_array();  
                if(count($student_count) > 0){    
                    $student_id = '';
                    for($j = 0;$j < count($student_count);$j++){
                        $student_id= $student_id.$student_count[$j]['user_id'].","; 
                    }
                    $trimmedStr = rtrim($student_id, ", ");
                    $student_query = $this->db->query("SELECT id,image,username,full_name FROM users WHERE id IN ($trimmedStr) AND status != '2'")->result_array();
                    $lesson_details = $this->db->query("SELECT * FROM chapter WHERE course_id='$id' AND status != '2'")->result_array();
                    $courses[$i]['lesson'] =  (string)count($lesson_details);
                    $courses[$i]['course_student_details'] =  $student_query;
                    $courses[$i]['student_count'] =  count($student_query);
                }else{
                    $courses[$i]['course_student_details'] = array();
                    $courses[$i]['student_count'] =  count($student_count);
                }
                $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id ='$id' AND status !=2")->result_array();
                $total_lessons = (string) count($chapters);
                $course_duration = (string) $this->calculateCourseDuration($id);
                $courses[$i]['course_duration'] = $course_duration;
            }

            $final = array();
            $final['status'] = true;
            $final['message'] = "Course Results";
            $final['data'] = $courses;
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $final = array();
            $final['status'] = false;
            $final['message'] = "Search Course Not Found,Please Check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }

      //API to search course with keyword
    public function search_course_with_keyword_post() {
        $data = $_POST;
        $course_name = $data['course_name'];
        if(!$course_name) {
            $final = array();
            $final['status'] = false;
            $final['message'] = "Course Name Not Found, Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $check = $this->db->query( "select a.id,count( d.course_id) as lesson,a.name,a.image,a.amount,a.lecture_days, a.age_group_id,a.course_time,a.course_duration,a.description,a.course_details_description,
        a.service_cost,a.addon,a.tax,a.added_date,a.updated_date,c.name AS course_type from courses a
        left outer join course_rating b on b.course_id = a.id
        left outer join chapter d on d.course_id = a.id
        left outer join course_type c on a.course_type_id = c.id
        where a.name LIKE '$course_name%' and a.status = '0' group by a.id")->result_array();
        
        if (count($check) > 0) {
            for ($i = 0; $i < count($check); $i++) { 
                $course_id = $check[$i]['id'];
                $rate = $this->db->query("select count(id) as count from course_rating where course_id = '$course_id' and status != '2'")->result_array();
                $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) as average_rating FROM course_rating where course_id ='$course_id' and status !=2")->result_array();
                $check[$i]['average_rating'] =  ($average_rating[0]['average_rating'] !== NULL) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                $check[$i]['review_count'] = $rate[0]['count'];
            }
            $final = array();
            $final['status'] = true;
            $final['message'] = "Course Results";
            $final['data'] = $check;
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $final = array();
            $final['status'] = true;
            $final['data'] = [];
            $final['message'] = "Search Course Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }

//API to chapter using course id
    public function search_chapter_post() {
        $data = $_POST;

        $course_id = $data['course_id'];
        
        if($course_id ==''){
            $final = array();
            $final['status'] = false;
            $final['data'] = 'Course Cannot Be Blank,Please Check Data Again';
            $this->response($final, REST_Controller::HTTP_OK);
        
        }
        
        $check_courses= $this->db->query("select * from courses where id = '$course_id' and status = '0'")->result_array();
        
        if (count($check_courses) > 0) {
            $check_chapter = $this->db->query("select id,chapter_no,chapter_name from chapter where course_id = '$course_id' and status != '2'")->result_array() ;
            $final = array(); 
            $final['status'] = true;
            $final['data'] = $check_chapter; 
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $final = array();
            $final['status'] = false;
            $final['message'] = "Search Course Not Found,Please check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }

    //API to bookmarks data
    public function course_type_post() {
        $data = $_POST;
        $user_id = $data['user_id'];
        $type_of_course = isset($data['type_of_course']) ?  isset($data['type_of_course']) : '';
        if($user_id == ''){
            $final = array();
            $final['status'] = false;
            $final['message'] = 'User Id Not Found , Please Check Data Again';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
        if(count($user) === 0 ){
            $final = array();
            $final['status'] = false;
            $final['message'] = 'User Not Found , Please Check Data Again';
            $this->response($final, REST_Controller::HTTP_OK);
        }
    
        $bookmarked_courses = $this->db->query("SELECT * FROM bookmark WHERE user_id = '$user_id' AND status != '2'")->result_array();
        if(count($bookmarked_courses) > 0){
            if($type_of_course === 'recommended'){
                $courses = $this->getRecommendedCourses($user);
            }else if($type_of_course === 'trending') {
                $courses = $this->getTrendingCourses();
            }else{
                $courses =  $this->db->query("SELECT * FROM courses WHERE status != '2'  ORDER BY added_date DESC")->result_array();
            }

            if(count($courses) > 0) {
                for ($i=0; $i < count($courses); $i++) { 
                    $course_id = $courses[$i]['id'];                
                    if (in_array($course_id, array_column($bookmarked_courses, 'course_id')))
                       $courses[$i]['bookmark_status'] = '1';                
                    else 
                        $courses[$i]['bookmark_status'] = '0';
                }           
            } 
        }else{
            $courses = $this->db->query("SELECT *,'0' AS bookmark_status FROM courses WHERE status != '2'  ORDER BY added_date DESC")->result_array();
        }
    
        for ($i = 0; $i < count($courses); $i++) {
            $course_id = $courses[$i]['id'];
            $age_group_id = $courses[$i]['age_group_id'];
    
            $course_rating = $this->db->query("SELECT course_id, COALESCE(CAST(AVG(rating_value) AS CHAR), '') as average_rating FROM course_rating WHERE course_id ='$course_id' AND status !=2")->result_array();
            $age_group = $this->db->query("select * from age_group where status != 2 and id='$age_group_id' ")->result_array();                            
            $student_count = $this->db->query("select * from mycart where course_id = '$course_id' and status ='0' and is_paid = '2' ")->result_array();       
            if (count($student_count) > 0) {
                $student_id = '';
                for($j = 0; $j < count($student_count); $j++) {
                    $student_id = $student_id . $student_count[$j]['child_id'] . ",";
                }
                
                $trimmedStr = rtrim($student_id, ", ");
                $students_detail = $this->db->query("SELECT id,image,username,full_name FROM users WHERE id IN ($trimmedStr) AND status != '2'")->result_array();
            }else{
                $students_detail = array();
            }
            $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id ='$course_id' AND status !=2")->result_array();
            $total_lessons = (string) count($chapters);
            $course_duration = (string) $this->calculateCourseDuration($course_id);
            
            $courses[$i]['average_rating'] = $course_rating[0]['average_rating'] != '' ? number_format($course_rating[0]['average_rating'], 1) : number_format('0',1);
            $courses[$i]['min_age'] = $age_group[0]['min_age'];
            $courses[$i]['max_age'] = $age_group[0]['max_age'];
            $courses[$i]['student_count']= count($students_detail);
            $courses[$i]['course_student_details'] = $students_detail;
            $courses[$i]['lesson'] = $total_lessons;
            $courses[$i]['course_duration'] = $course_duration;
        }
        $course_type = $this->db->query("SELECT * FROM course_type WHERE status !=2")->result_array();
        $data2 = array();
        $data2['Course Type'] = $course_type;
        $data2['Course Details'] = $courses;
    
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Course Details Results';
        $final['data'] = $data2;
        $this->response($final, REST_Controller::HTTP_OK);
    }

      //API to serarch course
    public function search_course_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Search Course Results';
        $final['data'] = $this->db->query("select a.*,b.name as course_type_name from courses a  "
                . " left outer join course_type b on b.id = a.course_type_id "
                . " where a.status !=2")->result_array();
        $this->response($final, REST_Controller::HTTP_OK);
    }
  //API to recommendedCourse
    private function getRecommendedCourses($user) {
        if(count($user) === 0) {
            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
            return $recommended_courses;
        }
        
        $age = $user[0]['age'];    
        $age_group = $this->db->query("SELECT * FROM age_group WHERE status != '2' AND $age BETWEEN min_age AND max_age")->result_array();
        if(count($age_group) > 0) {
            $age_group_id = '';
            for ($i=0; $i < count($age_group); $i++) { 
              $age_group_id .= $age_group[$i]['id'].',';
            }
            $age_group_id = rtrim($age_group_id,',');
            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE age_group_id IN ($age_group_id) AND status != '2'")->result_array();
            if(count($recommended_courses) === 0) $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
        }else {
            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
        }

        return $recommended_courses;
    }
  //API to trending course 
    private function getTrendingCourses() {
        $trending_courses = $this->db->query("SELECT b.* FROM mycart a
        LEFT OUTER JOIN courses b ON a.course_id = b.id WHERE a.is_paid = '2' AND b.status != '2'
        GROUP BY a.course_id LIMIT 5;")->result_array();
        if(count($trending_courses) > 0){
            return $trending_courses;
        }

       return $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
    }

      //API to make book mark 
    public function bookmark_post() {
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
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User Id Not Found ,Please check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
        
                $bookmarked_courses = $this->db->query("SELECT *,b.id as course_id FROM bookmark a LEFT OUTER JOIN courses b ON b.id = a.course_id
                                                        WHERE a.user_id = '$user_id' AND b.status != '2'")->result_array();
                if (count($bookmarked_courses) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "Bookmarked Courses Not Found ,Please check Data Again!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                for ($i = 0; $i < count($bookmarked_courses); $i++) {
                    $course_id = $bookmarked_courses[$i]["course_id"];
                    $course = $this->db->query("select * from courses where id = '$course_id' and status != 2 ")->result_array();
                    $rate = $this->db->query("select * from course_rating where course_id = '$course_id'")->result_array();
                    $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) as average_rating FROM course_rating where course_id = '$course_id' and status !=2")->result_array();
                    
                    $student_count = $this->db->query("select * from mycart where course_id = '$course_id' and status = '0' and is_paid = '2' ")->result_array();  
                    if(count($student_count) > 0) {    
                        $student_id = '';
                        for($j = 0; $j < count($student_count); $j++) {
                            $student_id= $student_id.$student_count[$j]['child_id'].","; 
                        }
                        $trimmedStr = rtrim($student_id, ",");
                        $students_detail = $this->db->query("SELECT id,image,username,full_name FROM users WHERE id IN ($trimmedStr) AND status != '2'")->result_array();         
                    }else{
                        $students_detail = array();
                    }
                    
                    $course_type_id = $course[0]['course_type_id'];
                    $course_type = $this->db->query("select * from course_type where id = '$course_type_id' and status != 2 ")->result_array();
                    $course[0]['course_student_details'] = $students_detail;
                    $course[0]['student_count'] = count($student_count);
                    $course[0]['course_type_name'] = count($course_type) ? $course_type[0]['name'] : '';
                    $course[0]['review_count'] = count($rate);
                    $course[0]['average_rating'] = ($average_rating[0]['average_rating'] !== NULL) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                    $course[0]['bookmark_status'] = '1';
                    $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id = '$course_id' AND status != '2'")->result_array();
                    $course[0]['lesson'] = (string) count($chapters);
                    $course[0]['course_duration'] = (string) $this->calculateCourseDuration($course_id);  

                    $bookmarked_courses[$i]['Course Details'] = $course[0];
                }
    
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Bookmark Results';
                $final['data'] = $bookmarked_courses;
                $this->response($final, REST_Controller::HTTP_OK);
                
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }  
    }

       //API to get cart data
    public function my_cart_display_post() {
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
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $totalService = 0;
                $totalAddon = 0;
                $totalTax = 0;
                $subTotal = 0 ;
                $total = 0;
                $totalAmount = 0;

                $cart = $this->db->query("SELECT b.id,a.id as main_cart_id,b.item as item,b.course_id,c.amount,b.user_id,c.service_cost,c.addon,c.tax,b.child_id
                FROM main_cart a
                LEFT OUTER JOIN mycart b ON b.main_cart_id = a.id
                LEFT OUTER JOIN courses c ON c.id = b.course_id
                WHERE b.user_id = '$user_id' AND b.status = '0' AND a.status = '0' AND b.is_paid = '0'")->result_array();
               
                if(count($cart) === 0) {
                    $final['status'] = false;
                    $final['message'] = 'Cart Item Not Found, Please Cart Item!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                for($i = 0;$i < count($cart); $i++) { 
                    $item  = (int)$cart[$i]['item'];
                    $serviceCost =  ($item * (int) $cart[$i]['service_cost']);
                    $addon = ($item * (int) $cart[$i]['addon']);
                    $tax = ($item * (int) $cart[$i]['tax']);
                    $amount = ($item * (int) $cart[$i]['amount']);
                    $subTot = $serviceCost + $addon + $amount;

                    $totalService = $totalService + $serviceCost;
                    $totalAddon = $totalAddon + $addon;
                    $totalTax = $totalTax + $tax;
                    $totalAmount = $totalAmount + $amount;
                    $subTotal = $subTotal + $subTot;
                    $total = $total + ($subTot + $tax);
                    
                    $course_id = $cart[$i]["course_id"];                    
                    $course = $this->db->query("select * from courses where id = $course_id and status!= '2' ")->result_array();
                    if(count($course) > 0) {

                        $type_query = $course[0]['course_type_id'];
                        $type = $this->db->query("select * from course_type where  id='$type_query' and status != '2'")->result_array();
                        $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) as average_rating FROM course_rating where course_id ='$course_id' and status != '2' ")->result_array();
                        $aveRate = isset($average_rating[0]['average_rating']) ? $average_rating[0]['average_rating'] : 0;
                        $course[0]['course_type_name'] = count($type) ? $type[0]['name'] : '';
                        $course[0]['average_rating'] = count($average_rating) > 0 ? number_format($aveRate, 1) : number_format('0',1);   
                    }
                    $cart[$i]['Course Details'] = $course[0];

                    $mainCartId = $cart[$i]['main_cart_id'];
                    $studentList = array();
                   // $students = $this->db->query("SELECT * FROM mycart WHERE user_id = $user_id AND main_cart_id = $mainCartId")->result_array();
                  //  for ($j=0; $j < count($students); $j++) { 
                        $student_id = $cart[$i]['child_id'];
                        $studentDetail = $this->db->query("SELECT id,image,username,full_name FROM users WHERE id = '$student_id' AND status != '2'")->result_array();
                        $studentList[] = count($studentDetail) > 0 ? $studentDetail[0] : array('id' => '','image' => '','username' => '','full_name' => '');
                   // }
                    $cart[$i]['child_details'] = $studentList;
                }
                
                $final = array();
                $final['status'] = true;
                $final['message'] = 'My Cart Result!';
                $final['service_cost']= $totalService;
                $final['addon']= $totalAddon;
                $final['tax']= $totalTax;
                $final['total_amount']= $totalAmount;
                $final['sub_amount']= $subTotal;
                $final['main_total_amount']= $total;
                $final['data'] = $cart;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }


        //API to remove bookmark from the selected
    public function remove_bookmark_post() {
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
                
                $this->db->query("DELETE FROM bookmark WHERE course_id = '$course_id' AND user_id = '$user_id'");
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;                    
                    $final['message'] = 'Course Removed From Favorite Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Removed From Favorite Failed, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
      //API to add bookmarks
    public function add_bookmark_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank!';
            $this->response($final, REST_Controller::HTTP_OK);
        }     

        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $course_id = $data['course_id'];
                if (!$user_id) {
                    $final['status'] = false;
                    $final['message'] = "User Id Cannot Be Blank!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if (!$course_id) {
                    $final['status'] = false;
                    $final['message'] = "Course Id Cannot Be Blank!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $course = $this->db->query("SELECT * FROM courses WHERE id = '$course_id' AND status != '2'")->result_array();
                if(count($course) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                
                $alreadyBookmarked = $this->db->query("select * from bookmark where user_id = '$user_id' and course_id = '$course_id'")->result_array();
                if(count($alreadyBookmarked) > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['bookmark_id']= $alreadyBookmarked[0]['id'];
                    $final['message'] = 'Course Already Favorite!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $this->db->query("INSERT INTO bookmark (user_id,course_id) values ('$user_id','$course_id')");
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['bookmark_id']= $this->db->insert_id();
                    $final['message'] = 'Course Added Favorite Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Added Favorite Failed, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
   //API to remove cart data
    public function remove_cart_post() {
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
                $cart_id = !empty($data['cart_id']) ? (int)$data['cart_id'] : 0 ;
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$course_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Cart Id Not Found, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                if($cart_id > 0 ){
                    $cart = $this->db->query("SELECT * from mycart where id = '$cart_id' and course_id = '$course_id' and is_paid = '0'")->result_array();
                    if(count($cart) === 0 ) { 
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Cart Item Not Found, Please check data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                    $this->db->query("DELETE from mycart where id = '$cart_id' and course_id = '$course_id' and is_paid = '0'");

                }else{
                    $cart = $this->db->query("SELECT * from mycart where user_id = '$user_id' and course_id = '$course_id' and is_paid = '0'")->result_array();
                    if(count($cart) === 0 ) { 
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Cart Item Not Found, Please check data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                    $mainCartId = $cart[0]['main_cart_id'];
                    $this->db->query("DELETE from mycart where user_id = '$user_id' and course_id = '$course_id' and is_paid = '0'");
                    $this->db->query("DELETE from main_cart where id = '$mainCartId'");

                }

                if ($this->db->affected_rows() > 0) {                    
                    $final = array();
                    $final['status'] = true;                    
                    $final['message'] = 'Remove Cart Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Remove Cart Failed, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
 //API to get course rating
    public function course_rating_display_post() {
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
                $check = $this->db->query("select *  from course_rating where course_id = '$course_id'")->result_array();
                if(count($check)== 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Review Not Found, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                 
                    
                }
                
                if (count($check) > 0) {
                    
                    for($i=0;$i<count($check);$i++){
                        $user_id=$check[$i]['user_id'] ;
                        $user_query =$this->db->query("SELECT id,username,full_name,image FROM users WHERE id = '$user_id' AND status != '2'")->row_array();
                        $check[$i]['user_details']= $user_query;
                    }
                    
                    
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Course Rating Results!';
//                    $final['review_count'] = count($check);
                    $final['data'] = $check;

                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Rating Display Failed, Please check data Again!';
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
   //API to add to cart 
    public function add_cart_post() {
     //   date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
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
                $time_slot = $data['time_slot_id'];
                $course_days = $data['course_days_id'];
             //   $item = $data['item'];
                $child = json_decode($data['child_id']);
                $cart_quantity = json_decode($data['item']);
                
                if (!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else if (!$course_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Id cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else if (!$time_slot) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Time Slot cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else if (!$course_days) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Courses Day cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }  else  if (!$child) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else  if (count($child) == 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (count($child) != count($cart_quantity)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Quantity Does Not Equal To No. Of Selected Children!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }             
            
                for($i = 0; $i < count($child); $i++) {
                    $id = $child[$i];
                    $user = $this->db->query("select * from users where id = $id and status = '0'")->row_array();
                    
                    $alreadyExistCart = $this->db->query("select * from mycart where child_id = '$id' and course_id = '$course_id' and status = '0'")->result_array();
                    if(count($alreadyExistCart) > 0) {
                        if($alreadyExistCart[0]['is_paid'] == '2') {
                            $message = 'User Already Buy A Course For '.$user['full_name']; 
                        }else {
                            $message = 'Course Is Already Added For '.$user['full_name']. ',Please Check Data Again';
                        }
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = $message;
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
                }              
                
                $cart_amount = $this->db->query("select * from courses where id = '$course_id' and status !='2'")->result_array();
                $amount = $cart_amount[0]['amount'];
                $service = $cart_amount[0]['service_cost'];
                $addon = $cart_amount[0]['addon'];
                $tax = $cart_amount[0]['tax'];
               
                
                $check_main_cart = $this->db->query("INSERT into main_cart (user_id,course_id,quantity,added_date,updated_date,amount,service_cost,addon,tax) values('$user_id','$course_id','0','$date','$date','$amount','$service','$addon','$tax')");

                $main_cart_id = $this->db->insert_id();
                for($i = 0; $i < count($child); $i++) {
                    $quantity_no = $cart_quantity[$i];
                    $child_id = $child[$i];
                    $this->db->query("INSERT into mycart (main_cart_id,amount,service_cost,addon,tax,user_id,course_id,time_slot_id,course_days_id,item,child_id,updated_date,added_date) values('$main_cart_id','$amount','$service','$addon','$tax','$user_id','$course_id','$time_slot','$course_days','$quantity_no','$child_id','$date','$date')");
                }

                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['cart_id'] = $main_cart_id;
                    $final['message'] = 'Course added to Cart Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Add Cart Failed, Please check data Again!';
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
    //API to get single course rating 
    public function course_rating_post() {
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
                $rating_value = $data['rating_value'];
                $rating_message = ucfirst($data['rating_message']);
                // date_default_timezone_set("asia/Kuala_Lumpur");
              //  date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                if ($rating_value== '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Rating Value cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else if ($rating_message== '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Rating Message Cannot be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } 
                $check_user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($check_user)== 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);                 
                }

                $check = $this->db->query("insert into course_rating (user_id,course_id,rating_value,rating_message,added_date,updated_date) values($user_id,'$course_id','$rating_value','$rating_message','$currentDateTime','$currentDateTime')");
                
                
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['user_id'] = $user_id;
                    $final['course_id'] = $course_id;
                    $final['message'] = 'Add Course Rating  Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Add Course Rating Failed, Please check data Again!';
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
      public function convert_datetime_to_timestamp($date_time_str) {
        // Load CodeIgniter's date helper
        $this->load->helper('date');

        // Create a DateTime object from the provided date and time string
        $date_time = new DateTime($date_time_str, new DateTimeZone('Asia/Kolkata'));

        // Get the Unix timestamp from the DateTime object
        return $date_time->getTimestamp();
    }
    //API to get billing address 
    public function billing_address_post() {
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
                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                $cart_id = isset($data['cart_id']) ? json_decode($data['cart_id']): null;
                $full_name = ucfirst($data['full_name']);
                $company = $data['company'];
                $vat_number = $data['vat_number'];
                $address = !empty($data['address']) ? ucfirst($data['address']) : '';
                $city = !empty($data['city']) ? $data['city'] : 0 ;
                $state = !empty($data['state']) ? $data['state'] : 0;
                $country =!empty($data['country']) ? $data['country'] : 0;
                $email = $data['email'];
                $contact_number = $data['contact_number'];
                $user_id = $data['user_id'];
                $payment_option = $data['payment_option'];
                $card_type = $data['card_type'];
                $payment_status = $data['payment_status'];
                $payment_id = $data['payment_id'];
                $transaction_id= $data['transaction_id'];
                $card_id= isset($data['card_id']) ? $data['card_id']: null;
                $currency= isset($data['currency'])? $data['currency'] : '' ;
                $payment_method = $data['payment_method'];

                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$cart_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Cart Id Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$full_name) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'full_name Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$email) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Email Id Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$contact_number) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Contact Number Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$payment_option) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Payment Option Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$payment_status) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Payment Status Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$payment_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Payment Id Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if (!$transaction_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Transaction Id Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$card_type) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Card Type Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$payment_method) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Payment Mmethod Cannot Be Blank!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }         

                $user = $this->db->query("SELECT username FROM users  WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }


                $billing_address_id = array();
                if (is_array($cart_id)) {
                    $cartIds = '';
                    foreach ($cart_id as $value) {
                        $cartIds .= $value . ',';
                    }

                    $cartIds = rtrim($cartIds, ',');
                    $mainCart = $this->db->query("select * from main_cart where id in ($cartIds) and user_id = '$user_id'")->result_array();
                    if(count($mainCart) === 0) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = 'Main Cart Not Found, Please Check Data Again!';
                        $this->response($final, REST_Controller::HTTP_OK);
                    }    

                    for($j = 0; $j < count($cart_id); $j++) {
                        $cid = $cart_id[$j];
                        
                        $mainCartDetail = $this->db->query("SELECT a.service_cost, a.addon, a.tax, a.amount, b.item as quantity FROM  main_cart a left outer join mycart b on b.main_cart_id = a.id WHERE a.id = '$cid' and a.status ='0' and b.status ='0' and a.is_paid = '0'")->row_array();
                        if(count($mainCartDetail) > 0) {
                            $quantity = (int)$mainCartDetail['quantity'];
                            $service_cost = $quantity * (int)$mainCartDetail['service_cost'];
                            $addon = $quantity * (int)$mainCartDetail['addon'];
                            $tax = $quantity * (int)$mainCartDetail['tax'];
                            $amount = $quantity * (int)$mainCartDetail['amount'];
                            $sub_total = $service_cost + $addon + $amount;
                            $total = $sub_total + $tax;
    
                            $check = $this->db->query("INSERT INTO billing_address (cart_id,payment_option,service_cost,addon_rate,tax,total_payment,user_id,full_name
                                    ,company,vat_number,address,city,state,country,email,number,sub_total,payment_status,transaction_id,
                                    payment_id,card_id,currency,card_type,payment_method,added_date,updated_date) values 
                                    ('$cid','$payment_option','$service_cost','$addon','$tax','$total','$user_id','$full_name'
                                    ,'$company','$vat_number','$address','$city','$state','$country','$email','$contact_number','$sub_total','$payment_status','$transaction_id',
                                    '$payment_id','$card_id','$currency','$card_type','$payment_method','$currentDateTime','$currentDateTime')");
                            $billing_address_id[] = $this->db->insert_id();
                        }
    
                    }  
                }             

                if (count($billing_address_id) > 0) {
                    $ids = '';

                    if(count($cart_id) > 0){
                        for($i = 0; $i < count($cart_id); $i++){                        
                            if($payment_status === 'success'){
                                $mainCartId = $cart_id[$i];
                                $billingAddressId = $billing_address_id[$i];
                                $cart_query = $this->db->query("update mycart set is_paid = '2' where main_cart_id = '$mainCartId' ");
                                $cart_query2 = $this->db->query("update main_cart set is_paid = '2' where id = '$mainCartId'  ");
                                $course_subscribe = $this->db->query("INSERT INTO course_subscription (billing_address_id,cart_id,added_date) values ('$billingAddressId','$mainCartId','$currentDateTime')");
                                $sub_id = $this->db->insert_id();
                                $ids = $ids."$sub_id".",";
                            }
                        }
                        $ids = rtrim($ids, ',');
                    }

                    $final = array();
                    $final['status'] = true;
                    $final['course_subscription'] = $ids;
                    $final['message'] = 'Billing Address Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Billing Address Failed, Please check data Again!';
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
    
    //API to home screen display content
    public function home_screen_display_post() {
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
                $today = isset($data['today']) ?  $data['today'] : date('Y-m-d H:i:s');
                $status = '2';
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $this->db->query("SELECT * FROM users WHERE id = $user_id AND status != '2'")->result_array();
                if(count($user) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user_type_id =  $user[0]['type'];
                $user_type = $this->db->query("SELECT name FROM user_type WHERE id = $user_type_id")->row_array();
                if(empty($user_type)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Type Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $course_type = $this->db->query("SELECT * FROM course_type WHERE status != $status")->result_array();
                $slider = $this->db->query("SELECT * FROM slider WHERE status != $status")->result_array();

                $currentDate = date('Y-m-d');
                $latest_update = $this->db->query("SELECT * FROM news WHERE status != '2' ORDER BY added_date DESC")->result_array();
                if(count($latest_update) > 0 ) {
                    for ($i=0; $i < count($latest_update); $i++) { 
                        $latest_update[$i]['user_image'] = $user[0]['image'];
                        $latest_update[$i]['username'] = $user[0]['username'];
                        $latest_update[$i]['full_name'] = $user[0]['full_name'];
                    }
                }
                
                $recommended_courses = array();
                if($user_type['name'] === 'Parent') {
                   $request_status = '1';
                   $childs = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$user_id' AND request_status = $request_status AND status != $status")->result_array();

                   if(count($childs) === 0) {
                        $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != $status")->result_array();
                        $recommended_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($recommended_courses)); 
                        $trending_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($this->getTrendingCourses()));

                        $check = array();
                        $check['Course Type'] = $course_type;
                        $check['Slider'] = $slider;
                        $check['Latest Updates'] = $latest_update;
                        $check['upcoming_classes'] =  array();
                        $check['homework'] = array();
                        $check['Recommended Course'] = $recommended_courses;
                        $check['Trending Course'] = $trending_courses;
        
                        $final = array();
                        $final['status'] = true;
                        $final['message'] = 'Home Screen Display Successfully!';
                        $final['data'] = $check;
                        $this->response($final, REST_Controller::HTTP_OK);
                   } else {
                        $age_group_id = '';
                        for ($i=0; $i < count($childs); $i++) { 
                            $child_id = $childs[$i]['child_id'];
                            $age = $this->db->query("SELECT age FROM users WHERE student_id = '$child_id' AND status != '2'")->result_array();
                            $age = !empty($age) ? $age[0]['age'] : 0 ;
                            $age_group = $this->db->query("SELECT * FROM age_group WHERE '$age' BETWEEN min_age AND max_age and status != '2'")->result_array();
                            if(count($age_group) > 0) {
                                for ($j=0; $j < count($age_group); $j++) { 
                                    $age_group_id .= $age_group[$j]['id'] . ',';
                                }
                            }
                        }
                        if(!empty($age_group_id)){
                            $age_group_id = rtrim($age_group_id, ",");
                            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE age_group_id IN ($age_group_id) AND status != $status")->result_array();
                        } 
                        
                        if(count($recommended_courses) === 0) {
                            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != $status")->result_array();
                        }         
                        $recommended_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($recommended_courses));
                        $trending_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($this->getTrendingCourses()));
                   }                    
                } 

                if($user_type['name'] === 'Student') {                   
                    $upcoming_classes = $this->db->query("select a.id,a.start,a.end,b1.chapter_id,a.upcoming_date,a.class_status,c.id,c.name as course_name
                    ,b.chapter_no, b.chapter_name,d.name as course_type ,e.id as cart_id
                    from upcoming_classes a
                    left outer join master_classes b1 on b1.id = a.master_class_id
                    left outer join chapter b on b.id  = b1.chapter_id  
                    left outer join courses c on c.id = b.course_id
                    left outer join course_type d on c.course_type_id = d.id
                    left outer join mycart e on e.course_id = b.course_id
                    where e.child_id = '$user_id' and a.status != '2' and b1.status != '2' and b.status != '2' and c.status != '2' and d.status != '2'
                    and e.status ='0' and e.is_paid = '2' and a.upcoming_date >= '$today' and a.class_status != 'Cancel'")->result_array();

                    $date = date('Y-m-d'); 
                    $query = "SELECT a.id,a.homework_material,a.homework_title,h.exercise_no,h.task as exercise,a.hk_date,b.chapter_no, b.chapter_name,c.name AS course_name, c.id as course_id  
                    FROM homework a 
                    LEFT OUTER JOIN course_exercise h ON h.id = a.exercise_id 
                    LEFT OUTER JOIN chapter b ON b.id  = h.chapter_id 
                    LEFT OUTER JOIN courses c ON c.id = b.course_id 
                    LEFT OUTER JOIN course_type d ON c.course_type_id = d.id 
                    LEFT OUTER JOIN mycart e ON e.course_id = b.course_id                   
                    WHERE e.child_id = $user_id AND a.status != '2' AND e.is_paid = 2 AND e.status != '2' AND a.hk_date >= '$date'";   
                    $homework = $this->db->query($query)->result_array();

                    $age = $user[0]['age'];
                    $age_group = $this->db->query("SELECT * FROM age_group WHERE status != '2' AND '$age' BETWEEN min_age AND max_age and status != '2'")->result_array();
                    if(count($age_group) > 0) {
                        $age_group_id = '';
                        for ($j=0; $j < count($age_group); $j++) { 
                            $age_group_id .= $age_group[$j]['id'] . ',';
                        }
                        $age_group_id = rtrim($age_group_id, ',');
                        $recommended_courses = $this->db->query("SELECT * FROM courses WHERE age_group_id IN ($age_group_id) AND status != '2'")->result_array();
                    }

                    if(count($recommended_courses) === 0) {
                        $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != $status")->result_array();
                    }     
                    $recommended_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($recommended_courses));
                    $trending_courses = $this->addBookmarkStatus($user_id, $this->addMoreCourseDetail($this->getTrendingCourses()));
                }
               
                $check = array();
                $check['Course Type'] = $course_type;
                $check['Slider'] = $slider;
                $check['Latest Updates'] = $latest_update;
                $check['upcoming_classes'] = ($user_type['name'] === 'Student') ? $upcoming_classes : array();
                $check['homework'] =  ($user_type['name'] === 'Student') ? $homework : array();
                $check['Recommended Course'] = $recommended_courses;
                $check['Trending Course'] = $trending_courses;

                $final = array();
                $final['status'] = true;
                $final['message'] = 'Home Screen Display Successfully!';
                $final['data'] = $check;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
   //API to get bookmark status 
    private function addBookmarkStatus($user_id, $courses) {
        $bookmarkedCourses = $this->db->query("select * from bookmark where user_id = $user_id and status != '2'")->result_array();
        
        for ($i = 0; $i < count($courses); $i++) { 
            $course_id = $courses[$i]['id'];    
            if (in_array($course_id, array_column($bookmarkedCourses, 'course_id'))){
                $courses[$i]['bookmark_status'] = '1'; 
            }else{
                $courses[$i]['bookmark_status'] = '0'; 
            }
        }

        return $courses;
    }
 // Function to add more details for the course
    private function addMoreCourseDetail($courses) {
        if(count($courses) === 0) return $courses;

        for ($i = 0; $i < count($courses); $i++) {
            $course_id = $courses[$i]["id"];
            if ($course_id != '') {
                $courseTypeId = $courses[$i]['course_type_id'];
                $courseType = $this->db->query("select * from course_type where status != 2 and id = '$courseTypeId'")->result_array();
                
                $chapters = $this->db->query("SELECT * FROM chapter WHERE course_id ='$course_id' AND status !=2")->result_array();
                $total_lessons = (string) count($chapters);
                
                $course_duration = (string) $this->calculateCourseDuration($course_id);

                $average_rating = $this->db->query("SELECT course_id, COALESCE(CAST(AVG(rating_value) AS CHAR), '') AS average_rating FROM course_rating WHERE course_id = '$course_id' AND status != 2 ")->result_array();
               
                $courses[$i]['course_id'] = $course_id;
                $courses[$i]['lesson'] = $total_lessons;
                $courses[$i]['course_duration'] = $course_duration;
                $courses[$i]['course_days_name'] = '';
                $courses[$i]['lecture_days'] = '';
                $courses[$i]['course_type_name'] = !empty($courseType) ? $courseType[0]['name']: 'unknow course' ;
                $courses[$i]['average_rating'] = (count($average_rating) > 0 && $average_rating[0]['average_rating']) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
            }
        }
        return $courses;
    }
     // Api to get billing screen content at frontend 
    public function billing_address_display_post() {
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
                $check = $this->db->query("select (b.name) as country_name,(s.name) as state_name,(c.name) as city_name,a.* from"
                        . " users a left join  country b on b.id = a.country left join state s on s.id = a.state left join city c "
                        . "on c.id = a.city where a.id = '$user_id'")->row_array();
                $check_users = $this->db->query("SELECT *  FROM users  WHERE id = '$user_id' AND status != '2'")->result_array();
                if (count($check_users)> 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = "User Result";
                    $final['data'] = $check;
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found,Please Check Data Again!';
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

     // Api to get payment details
    public function payment_post() {
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
                if($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $totalService = 0;
                $totalAddon = 0;
                $totalTax = 0;
                $subTotal = 0 ;
                $total = 0;
                $totalAmount = 0;

                $cart = $this->db->query("SELECT b.id,a.id as main_cart_id,b.item,b.course_id,c.amount,b.user_id,c.service_cost,c.addon,c.tax,d.username as child_name, d.id as child_id
                FROM main_cart a
                LEFT OUTER JOIN mycart b ON b.main_cart_id = a.id
                LEFT OUTER JOIN courses c ON c.id = b.course_id
                LEFT OUTER JOIN users d ON d.id = b.child_id
                WHERE b.user_id = '$user_id' AND b.status = '0' AND a.status = '0' AND b.is_paid = '0'")->result_array();
                if(count($cart) === 0) {
                    $final['status'] = false;
                    $final['message'] = 'User Course Not Found,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
         
             
                for($i=0; $i < count($cart); $i++) { 
                    $item  = (int)$cart[$i]['item'];
                    $serviceCost =  ($item * (int) $cart[$i]['service_cost']);
                    $addon = ($item * (int) $cart[$i]['addon']);
                    $tax = ($item * (int) $cart[$i]['tax']);
                    $amount = ($item * (int) $cart[$i]['amount']);
                    $subTot = $serviceCost + $addon + $amount;

                    $totalService = $totalService + $serviceCost;
                    $totalAddon = $totalAddon + $addon;
                    $totalTax = $totalTax + $tax;
                    $totalAmount = $totalAmount + $amount;
                    $subTotal = $subTotal + $subTot;
                    $total = round($total + ($subTot + $tax), 2);
                }           
            
             //   $payment= $this->db->query("select id,name from payment where status ='0'")->result_array();           
                $final = array();
                $final['status'] = true;
                $final['message'] = "Payment Result";
                $final['amount'] = $amount;
                $final['service_cost']= $totalService;
                $final['addon']= $totalAddon;
                $final['tax']= $totalTax;
                $final['sub_amount']= $subTotal;
                $final['total_amount']= $total ;
               // $final['data'] = $payment;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

        // Api to add complain 
    public function complain_post() {
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
                $transaction_id = $data['order_no'];
                $message = ucfirst($data['message']);
                $subscription_id = $data['id'];
                
                 if ($user_id == '') {
                    $final['status'] = false;
                    $final['message'] = "User Id Cannot Be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }else  if ($subscription_id== '') {
                    $final['status'] = false;
                    $final['message'] = "Course Subscription Cannot Be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if ($message == '') {
                    $final['status'] = false;
                    $final['message'] = "Message Cannot Be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }else  if ($transaction_id == '') {
                    $final['status'] = false;
                    $final['message'] = "Order No. Cannot Be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $currentDateTime = date('Y-m-d H:i:s');
                $check= $this->db->query("insert into complain (message,order_no,course_subscription_id,user_id,added_date,updated_date) values ('$message','$transaction_id','$subscription_id','$user_id','$currentDateTime','$currentDateTime')");
                   
               $id = $this->db->insert_id();
                if ($this->db->affected_rows() > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['complain_id'] = $id;
                    $final['message'] = 'Complain Send Successfully!';
                    
                    $this->response($final, REST_Controller::HTTP_OK);
                } else {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Complain Send Failed,Please Check Data Again!';
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

        // Api to get of payment history 
    public function payment_history_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if(isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $filter = !empty($data['filter']) ? $data['filter'] : '';
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found , Please check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $course_subscription_payment = $this->db->query("select a.id,a.added_date as created_at,
                        f.item as quantity, b.payment_status,d.name,d.image, b.payment_method,
                        d.id as course_id,b.transaction_id,b.card_type,b.total_payment as amount                     
                        from course_subscription a  
                        left outer join billing_address b  on b.id = a.billing_address_id
                        left outer join main_cart c on c.id = a.cart_id 
                        left outer join courses d on d.id = c.course_id 
                        left outer join payment e on  e.id = b.payment_option
                        left outer join mycart f on f.main_cart_id = c.id where b.user_id = '$user_id' group by c.id")->result_array();
                if(count($course_subscription_payment) > 0) {
                    for($i = 0; $i < count($course_subscription_payment); $i++) {
                        $date = $this->convert_datetime_to_timestamp($course_subscription_payment[$i]['created_at']);
                        $course_subscription_payment[$i]['unix_timestamp'] = (string)$date;
                        $course_subscription_payment[$i]['pay_for'] = 'Course';
                    }
                }
            
                $user_subscription_payment = $this->db->query("SELECT a.id,a.amount,a.created_at,a.tutorial_plan_quantity as quantity,a.payment_status,b.period as name,
                b.bg_image as image,a.payment_method,'' as course_id,a.transaction_id,a.card_type FROM tutorial_credit_transactions a
                LEFT OUTER JOIN tutorial_subscription_plan b ON b.id = a.tutorial_plan_id WHERE a.user_id = $user_id ")->result_array();
                if(count($user_subscription_payment) > 0) {
                    for($i = 0; $i < count($user_subscription_payment); $i++) {
                        $date1 = $this->convert_datetime_to_timestamp($user_subscription_payment[$i]['created_at']);
                        $user_subscription_payment[$i]['unix_timestamp'] = (string)$date1;
                        $user_subscription_payment[$i]['pay_for'] = 'Tutorial Subscription';
                        $user_subscription_payment[$i]['name'] = ucfirst($user_subscription_payment[$i]['name']);                        
                    }
                }

                $event_payment = $this->db->query("SELECT a.id,a.amount,a.created_at,1 as quantity,a.payment_status,b.event as name,
                b.image,a.payment_method, '' as course_id,a.transaction_id,a.card_type FROM event_transaction a 
                LEFT OUTER JOIN events b ON b.id = a.event_id WHERE a.user_id = $user_id and a.amount > 0 ")->result_array();
                if(count($event_payment) > 0) {
                    for($i = 0; $i < count($event_payment); $i++) {
                        $date2 = $this->convert_datetime_to_timestamp($event_payment[$i]['created_at']);
                        $event_payment[$i]['unix_timestamp'] = (string)$date2;
                        $event_payment[$i]['pay_for'] = 'Event';
                    }
                }

                $merged_array = array_merge($course_subscription_payment,$user_subscription_payment,$event_payment);
                $filtered_array = $this->filterPaymentHistory($filter,$merged_array);
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Payment History Results!';
                $final['data'] = $filtered_array;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
 // Api to get payment history by filters
    private function filterPaymentHistory($filter,$array) {
        if($filter && $filter === 'A-Z') {
            usort($array, function($a, $b) {
                return strcmp($b['name'], $a['name']);
            });
            return $array;
        }else if($filter && $filter === 'Z-A') {
            usort($array, function($a, $b) {
                return strcmp($a['name'], $b['name']);
            });
            return $array;
        }else if($filter && $filter === 'Oldest-Latest') {
            usort($array, function($a, $b) {
                return $a['unix_timestamp'] - $b['unix_timestamp'];
            });
            return $array;
        }else if($filter && is_numeric($filter)) {
            $filteredData = array_filter($array, function($item) use ($filter) {
                return $item['course_id'] == $filter;
            });
            return array_values($filteredData);

        }else{
            usort($array, function($a, $b) {
                return $b['unix_timestamp'] - $a['unix_timestamp'];
            });

            return $array;
        }
    }

    // Api to search payment history
    public function payment_history_search_post() {
        $data = $_POST;
        $headers = $this->input->request_headers();
        
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if(isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $user_id = $data['user_id'];
                $search = $data['search'];
                if(!$user_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found , Please check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$search) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Search Not Found , Please check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $course_subscription_payment = $this->db->query("select a.id,b.total_payment as amount,a.added_date as created_at,
                        1 as quantity, b.payment_status,COALESCE(d.name,'')as name,COALESCE(d.image,'')as image,b.payment_method,
                        d.id as course_id, b.transaction_id                     
                        from course_subscription a  
                        left outer join billing_address b  on b.id = a.billing_address_id
                        left outer join main_cart c on c.id = a.cart_id 
                        left outer join courses d on d.id = c.course_id 
                        left outer join payment e on  e.id = b.payment_option
                        left outer join mycart f on f.main_cart_id = c.id where b.user_id = '$user_id' and d.name like '$search%' ")->result_array();
                if(count($course_subscription_payment) > 0) {
                    for($i = 0; $i < count($course_subscription_payment); $i++) {
                        $date = $this->convert_datetime_to_timestamp($course_subscription_payment[$i]['created_at']);
                        $course_subscription_payment[$i]['unix_timestamp'] = (string)$date;
                        $course_subscription_payment[$i]['pay_for'] = 'Course';
                    }
                }

                $user_subscription_payment = $this->db->query("SELECT a.id,a.amount,a.created_at,a.tutorial_plan_quantity as quantity,a.payment_status,b.period as name,
                b.bg_image as image,'' as payment_method,'' as course_id,a.transaction_id FROM tutorial_credit_transactions a
                LEFT OUTER JOIN tutorial_subscription_plan b ON b.id = a.tutorial_plan_id WHERE a.user_id = $user_id AND b.period LIKE '$search%' ")->result_array();
                if(count($user_subscription_payment) > 0) {
                    for($i = 0; $i < count($user_subscription_payment); $i++) {
                        $date1 = $this->convert_datetime_to_timestamp($user_subscription_payment[$i]['created_at']);
                        $user_subscription_payment[$i]['unix_timestamp'] = (string)$date1;
                        $user_subscription_payment[$i]['pay_for'] = 'Tutorial Subscription';
                        $user_subscription_payment[$i]['name'] = ucfirst($user_subscription_payment[$i]['name']);
                    }
                }

                $event_payment = $this->db->query("SELECT a.id,a.amount,a.created_at,1 as quantity,a.payment_status,b.event as name,
                b.image,'' as payment_method, '' as course_id,a.transaction_id FROM event_transaction a 
                LEFT OUTER JOIN events b ON b.id = a.event_id WHERE a.user_id = $user_id AND b.event LIKE '$search%' ")->result_array();
                if(count($event_payment) > 0) {
                    for($i = 0; $i < count($event_payment); $i++) {
                        $date2 = $this->convert_datetime_to_timestamp($event_payment[$i]['created_at']);
                        $event_payment[$i]['unix_timestamp'] = (string)$date2;
                        $event_payment[$i]['pay_for'] = 'Event';
                    }
                }

                $merged_array = array_merge($course_subscription_payment,$user_subscription_payment,$event_payment);
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Payment History Search Results!';
                $final['data'] = $merged_array;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

      // Api to search news 
    public function search_news_post() {
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
                $search = $data['search'];
                $status = '2';
                if($search == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Search Cannot Be Blank, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $news = $this->db->query("SELECT a.*,b.image AS user_image, b.username, b.full_name FROM news a
                LEFT OUTER JOIN users b ON a.user_id = b.id WHERE a.status != $status AND b.status != $status AND (b.full_name LIKE '" . $search . "%' OR a.description LIKE '" . $search . "%')")->result_array();    
                $final = array();
                $final['status'] = true;
                $final['message'] = 'News Search Result!';
                $final['data']= $news;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
    // Api to get single payment hostory 
    public function single_payment_history_post() {
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
                $transaction_id = $data['transaction_id'];
                $course_id = $data['course_id'];
                $paid = $data['pay_for'];
                if(!$user_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Cannot Be Blank, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$transaction_id){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Transaction Id Cannot Be Blank, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else if(!$paid){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Pay For Cannot Be Blank, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }elseif($paid === 'Course' && !$course_id) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Course Id Cannot Be Blank, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->result_array();
                if(count($user) === 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Not Found, Please check data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $payment_detail = array();
                if($paid === 'Course') {
                    $course_subscription_payment = $this->db->query("select a.id,d.amount as fee,a.added_date as created_at,
                    f.item as quantity, b.payment_status,d.name,d.image, b.payment_method,
                    d.id as course_id,b.transaction_id,description,d.addon,d.tax,d.service_cost,f.child_id,b.card_type               
                    from course_subscription a  
                    left outer join billing_address b  on b.id = a.billing_address_id
                    left outer join main_cart c on c.id = a.cart_id 
                    left outer join courses d on d.id = c.course_id 
                    left outer join payment e on  e.id = b.payment_option
                    left outer join mycart f on f.main_cart_id = c.id where b.user_id = '$user_id' and b.transaction_id = '$transaction_id' and c.course_id = $course_id ")->result_array();
                    if(count($course_subscription_payment) > 0) {
                        $item  = (int)$course_subscription_payment[0]['quantity'];
                        $serviceCost =  ($item * (int) $course_subscription_payment[0]['service_cost']);
                        $addon = ($item * (int) $course_subscription_payment[0]['addon']);
                        $tax = ($item * (int) $course_subscription_payment[0]['tax']);
                        $amount = ($item * (int) $course_subscription_payment[0]['fee']);
                        $subTotal = $serviceCost + $addon + $amount;
                        $total = $subTotal + $tax;


                        $date = $this->convert_datetime_to_timestamp($course_subscription_payment[0]['created_at']);
                        $course_subscription_payment[0]['unix_timestamp'] = (string)$date;
                        $course_subscription_payment[0]['service_cost'] = $serviceCost;
                        $course_subscription_payment[0]['fee'] = $amount;
                        $course_subscription_payment[0]['addon'] = $addon;
                        $course_subscription_payment[0]['tax'] = $tax;
                        $course_subscription_payment[0]['subtotal'] = $subTotal;
                        $course_subscription_payment[0]['total'] = $total;
                        $course_subscription_payment[0]['amount_in_words'] = 'Dollar '.$this->convertNumberToWords($total).' Only';
                        $course_subscription_payment[0]['ord_number'] = 'ord_'.$course_subscription_payment[0]['transaction_id'];

                        $course_id = $course_subscription_payment[0]['course_id'];
                        $course_rating = $this->db->query("SELECT  COALESCE(CAST(AVG(rating_value) AS CHAR), '') as average_rating FROM course_rating where course_id ='$course_id' and status != '2' ")->result_array();
                        $course_subscription_payment[0]['rating'] = count($course_rating) > 0 ? $course_rating[0]['average_rating'] : '0';
                        
                        $child_id = '';
                        for ($i=0; $i < count($course_subscription_payment); $i++) { 
                            $id = $course_subscription_payment[$i]['child_id'];
                            $child_id = $child_id.$id.',';
                        }
                        $child_id = rtrim($child_id, ',');
                        $child_details = $this->db->query("SELECT id,username,full_name,image,age FROM users WHERE id in ($child_id) AND status != '2'")->result_array();
                        if(count($child_details) > 0) {
                            $course_subscription_payment[0]['child_detail'] = $child_details;
                            $age = $child_details[0]['age'];
                            $age_group = $this->db->query("SELECT * FROM age_group WHERE $age BETWEEN min_age AND max_age")->result_array();                      
                            $course_subscription_payment[0]['age_group'] = count($age_group) > 0 ? ($age_group[0]['min_age'].'-'.$age_group[0]['max_age'])  : '';
                        }else{
                            $course_subscription_payment[0]['child_detail'] = '';  
                            $course_subscription_payment[0]['age_group'] = "";
                        }

                        $course_subscription_payment[0]['credit'] = "";
                        $course_subscription_payment[0]['pay_for'] = 'Course';
                        unset($child_id);
                        $payment_detail = $course_subscription_payment[0];
                    }
                }
                
                if($paid === 'Event') {
                    $event_payment = $this->db->query("SELECT a.id,a.amount as fee,a.created_at,'1' as quantity,a.payment_status,b.event as name,
                    b.image,a.payment_method, '' as course_id,a.transaction_id,b.short_description as description,'0' as rating,a.child_id,a.card_type FROM event_transaction a 
                    LEFT OUTER JOIN events b ON b.id = a.event_id WHERE a.user_id = $user_id AND a.transaction_id = '$transaction_id' ")->result_array();
                    if(count($event_payment) > 0) {
                        $date2 = $this->convert_datetime_to_timestamp($event_payment[0]['created_at']);
                        $event_payment[0]['unix_timestamp'] = (string)$date2;
                        $event_payment[0]['addon'] = '0';
                        $event_payment[0]['tax'] = '0';
                        $event_payment[0]['service_cost'] = '0';
                        $event_payment[0]['subtotal'] = $event_payment[0]['fee'];
                        $event_payment[0]['total'] = $event_payment[0]['fee'];
                        $event_payment[0]['amount_in_words'] = 'Dollar '.$this->convertNumberToWords($event_payment[0]['fee']).' Only';
                        $event_payment[0]['ord_number'] = 'ord_'.$event_payment[0]['transaction_id'];

                        $child_id = $event_payment[0]['child_id'];
                        $child_details = $this->db->query("SELECT id,username,full_name,image,age FROM users WHERE id = '$child_id' AND status != '2'")->result_array();
                        $event_payment[0]['child_detail'] = count($child_details) > 0 ? $child_details : [array("id" => "","username" => "","full_name" => "","image" => "","age" => "")];
                        $event_payment[0]['age_group'] = "";
                        $event_payment[0]['credit'] = "";
                        $event_payment[0]['pay_for'] = 'Event';
                        unset($child_id);
                        $payment_detail = $event_payment[0];
                    }
                }

                if($paid === 'Tutorial Subscription') {
                    $user_subscription_payment = $this->db->query("SELECT a.id,a.amount as fee,a.created_at,a.tutorial_plan_quantity as quantity,a.payment_status,b.period as name,
                    b.bg_image as image,a.payment_method,'' as course_id,a.transaction_id,b.description,'0' as rating,a.credits,a.card_type FROM tutorial_credit_transactions a
                    LEFT OUTER JOIN tutorial_subscription_plan b ON b.id = a.tutorial_plan_id WHERE a.user_id = $user_id AND  a.transaction_id = '$transaction_id'")->result_array();
                    if(count($user_subscription_payment) > 0) {
                        $date1 = $this->convert_datetime_to_timestamp($user_subscription_payment[0]['created_at']);
                        $user_subscription_payment[0]['unix_timestamp'] = (string)$date1;
                        $user_subscription_payment[0]['name'] = ucfirst($user_subscription_payment[0]['name']);
                        $user_subscription_payment[0]['addon'] = '0';
                        $user_subscription_payment[0]['tax'] = '0';
                        $user_subscription_payment[0]['service_cost'] = '0';
                        $user_subscription_payment[0]['subtotal'] = $user_subscription_payment[0]['fee'];
                        $user_subscription_payment[0]['total'] = $user_subscription_payment[0]['fee'];
                        $user_subscription_payment[0]['amount_in_words'] = 'Dollar '.$this->convertNumberToWords($user_subscription_payment[0]['fee']).' Only';
                        $user_subscription_payment[0]['ord_number'] = 'ord_'.$user_subscription_payment[0]['transaction_id'];  
                        $user_subscription_payment[0]['child_detail'] = [array("id" => "","username" => "","full_name" => "","image" => "","age" => "")];
                        $user_subscription_payment[0]['age_group'] = "";

                        $credits = $user_subscription_payment[0]['credits'];
                        $user_subscription_payment[0]['credit'] = $credits;
                        $user_subscription_payment[0]['pay_for'] = 'Tutorial Subscription';
                        unset($credits);
                        $payment_detail = $user_subscription_payment[0];
                    }
                }

                if(count($payment_detail) > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['data'] = $payment_detail;
                    $final['message'] = 'Payment Detail Result!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Payment Detail Not Found, Please check data Again!';
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
  // Api to get  badges to display at frontend 
    public function badges_display_post() {
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
                $badges = $this->db->query("select b.id,b.title,b.image from child_quiz_result a
                left outer join badges b on b.id = a.badge_id where a.user_id = '$user_id' and a.status != '2' and b.status != '2' group by b.id")->result_array();
            
                if(count($badges) > 0) {
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Badges Results!';
                    $final['data'] = $badges;
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Badges not found!';
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

/// convert word into number it is using single payment history api in home.php file 
     private function convertNumberToWords($number) {
        $fmt = new NumberFormatter("en", NumberFormatter::SPELLOUT);
        return $fmt->format($number);
    }

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

    private function getCourseAllMasterClasses($course_id) {
        return $this->db->query("select a.*,b.chapter_name from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    }    
}