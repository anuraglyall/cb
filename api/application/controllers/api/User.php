<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/* On your database, open a SQL terminal paste this and execute: */
// CREATE TABLE IF NOT EXISTS `users` (
//   `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
//   `username` varchar(255) NOT NULL DEFAULT '',
//   `email` varchar(255) NOT NULL DEFAULT '',
//   `password` varchar(255) NOT NULL DEFAULT '',
//   `avatar` varchar(255) DEFAULT 'default.jpg',
//   `created_at` datetime NOT NULL,
//   `updated_at` datetime DEFAULT NULL,
//   `is_admin` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   `is_confirmed` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
//   PRIMARY KEY (`id`)
// );
// CREATE TABLE IF NOT EXISTS `ci_sessions` (
//   `id` varchar(40) NOT NULL,
//   `ip_address` varchar(45) NOT NULL,
//   `timestamp` int(10) unsigned DEFAULT 0 NOT NULL,
//   `data` blob NOT NULL,
//   PRIMARY KEY (id),
//   KEY `ci_sessions_timestamp` (`timestamp`)
// );

/**
 * User class.
 * 
 * @extends REST_Controller
 */
require(APPPATH . '/libraries/REST_Controller.php');

use Restserver\Libraries\REST_Controller;

class User extends REST_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('user_model');
    }

    /**
     * register function.
     * 
     * @access public
     * @return void
     */
     //Api to get child details
    public function child_image_post() {
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
                        . " where a.parent_id = '$user_id' and a.request_status = '1' ")->result_array();
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
    //Api for social login at frontend 
    public function social_login_post() {
        $data = $_POST;
        $social_type = $data['social_type'];
        $social_id = $data['social_id'];

        if(!$social_id) return $this->requiredFieldError('Social Id');
        if(!$social_type) return $this->requiredFieldError('Social Type');

        $alreadyExist = $this->db->query("select * from users where social_id = '$social_id' and social_type = '$social_type' and status != '2'")->result_array();
        if (count($alreadyExist) > 0) {
            $token_data['uid'] = $alreadyExist[0]['id'];
            $token_data['full_name'] = $alreadyExist[0]['full_name'];
            $tokenData = $this->authorization_token->generateToken($token_data);
            $final = array();
            $final['status'] = true;
            $final['is_registered'] = true;
            $final['data'] = array('access_token' => $tokenData, 'userInfo' => $alreadyExist[0]);
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $final = array();
            $final['status'] = true;
            $final['is_registered'] = false;
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }

   
  //Api for forget password at frontend 
    public function forgot_password_post() {
        $data = $_POST;
        $email = $data['email'];
    
        if (!$email) return $this->requiredFieldError('Email');
      
      //  date_default_timezone_set('Asia/Kolkata');
        $user = $this->db->query("select * from users where contact_email = '$email' and status != '2'")->result_array();
        if (count($user) === 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Email Address';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $id = $user[0]['id'];
        $contact_email = $user[0]['contact_email'];
        $username = $user[0]['username'];
        $this->sendEmailToken($username, $contact_email, $id );
      //  $hashedPassword = md5($newPassword);
      //  $this->db->query("update users set password = '$hashedPassword' where id = '$id'");

        $final = array();
        $final['status'] = true;
        $final['message'] = 'We have sent you otp at your register email';
        $this->response($final, REST_Controller::HTTP_OK);
    }

    //Api to send email token 
    private function sendEmailToken($username,$to, $id ){
      //  date_default_timezone_set('Asia/Kolkata');
        $data = $_POST;
        $this->load->library('phpmailer_lib');        
        $mail = $this->phpmailer_lib->load();
        $date = date("Y-m-d H:i:s");
        $n = 4;
        $otp =  $this->generateNumericOTP($n);
        $data = array ('otp' => $otp,'updated_at' => $date);
            $this->db->where('id', $id);
            $this->db->update('users', $data);
        // SMTP configuration

        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'testotp90@gmail.com';
        $mail->Password = 'nomiehfimdpoqimi';
        $mail->SMTPSecure = 'ssl';
        $mail->Port     = 465;
       
        $mail->setFrom('testotp90@gmail.com', 'Code Bright Tution');
        $mail->addAddress($to);
        
        $mail->Subject = "Welcome to Code Bright Tution - Password Token";        
        $mailContent = "<p>Dear <b>$username,</b></p>
        <p>Your otp code <b>". $otp ."</b> please use this token to update your password.</p>";
        $mail->Body = $mailContent;
        $mail->isHTML(true);
        $mail->send();
      
    }


    // Function to generate OTP 
    private function generateNumericOTP($n) { 
      
    // Take a generator string which consist of 
    // all numeric digits 
    $generator = "1357902468"; 
    $result = ""; 
    for ($i = 1; $i <= $n; $i++) { 
        $result .= substr($generator, (rand()%(strlen($generator))), 1); 
    } 
  
    // Return result 
    return $result; 
} 

  //Api to submit otp
public function submit_otp_post() {
    $data = $_POST;
    $otp = $data['otp'];
    if (!$otp) return $this->requiredFieldError('OTP');
    $user = $this->db->query("select * from users where otp = '$otp' and status != '2'")->result_array();
    $newtimestamp = strtotime( $user[0]['updated_at'].' + 16 minute');
    $validTime =  date('Y-m-d H:i:s', $newtimestamp);
    $date = date("Y-m-d H:i:s");
    if (count($user) === 0 || strtotime($date) > strtotime($validTime) ) {
        $final = array();
        $final['status'] = false;
        $final['message'] = 'Invalid otp or Expired!';
        $this->response($final, REST_Controller::HTTP_OK);
    }

    $id = $user[0]['id'];
    $final = array();
    $final['status'] = true;
    $final['id'] =  $id;
    $final['message'] = 'Otp confirmed Successfully!';
    $this->response($final, REST_Controller::HTTP_OK);
}

   //Api to create new password at fronted 
    public function create_new_password_post() {
        $data = $_POST;

        $newPassword = $data['newPassword'];
        $confirmPassword = $data['confirmPassword'];
        $confirmPassword = $data['confirmPassword'];
        $id = $data['id'];
        if (!$newPassword) return $this->requiredFieldError('New Password');
        if (!$confirmPassword) return $this->requiredFieldError('Confirm Password');
        if ($newPassword !== $confirmPassword) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'New Password And Confirm Password Are Different!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $user = $this->db->query("select * from users where id = '$id' and status != '2'")->result_array();
        if (count($user) === 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'User Not Found';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $id = $user[0]['id'];
        $hashedPassword = md5($newPassword);
        $this->db->query("update users set password = '$hashedPassword' where id = '$id'");

        $final = array();
        $final['status'] = true;
        $final['message'] = 'New Password Created Successfully!';
        $this->response($final, REST_Controller::HTTP_OK);
    }
//Api to get parent child relationship data
    public function parent_child_post() {
        $data = $_POST;
        $user_id = $data['user_id'];
        $headers = $this->input->request_headers();
     
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                if ($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User id cannot be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                    $student_data_id = array();
                    $parentData = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$user_id' AND status != '2'")->result_array();
                    foreach($parentData as $list){
                        $stId = $list['child_id'];
                        $student_data = $this->db->query("SELECT * FROM users WHERE student_id = '$stId' AND status != '2'")->row_array();
                        if(!empty($student_data)){
                            array_push($student_data_id, $student_data );
                        }
                    }
                
                    if (empty($student_data)) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = "Child Id not found";
                        $this->response($final, REST_Controller::HTTP_OK);
                    }else{
                        $final = array();
                        $final['status'] = true;
                        $final['message'] = 'Parents child result';
                        $final['data'] = $student_data_id;
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }
    //Api to get profile content data ata frontend 
    public function profile_display_post() {
        $data = $_POST;
        $user_id = $data['user_id'];
        $headers = $this->input->request_headers();
     
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                if ($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User id cannot be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $this->db->query("SELECT * FROM users WHERE id = '$user_id' AND status != '2'")->row_array();
                if (empty($user)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User Id not found";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
                $city_id = $user["city"];
                $city = $this->db->query("select * from city where id = '$city_id' and status != 2")->row_array();
                $city_name = $city["name"];
                $user['city_name'] = $city_name;

                $state_id = $user["state"];
                $state = $this->db->query("select * from state where id = '$state_id'  and status != 2")->row_array();
                $state_name = $state["name"];
                $user['state_name'] = $state_name;

                $country_id = $user["country"];
                $country = $this->db->query("select * from country where id = '$country_id' and status != 2")->row_array();
                $country_name = $country["name"];
                $user['country_name'] = $country_name;

                $race_id = $user["race"];
                $race = $this->db->query("select * from races where id = '$race_id' and status != 2")->row_array();
                $race_name = $race["name"];
                $user['race_name'] = $race_name;

                $nationlity_id = $user["nationlity"];
                $nationlity = $this->db->query("select * from nationlity where id = '$nationlity_id'")->row_array();
                $nationlity_name = $nationlity["name"];
                $user['nationlity_name'] = $nationlity_name;

                $dialect_id = $user["dialect"];
                $dialect = $this->db->query("select * from dialect where id = '$dialect_id'")->row_array();
                $dialect_name = $dialect["name"];
                $user['dialect_name'] = $dialect_name;

                $religion_id = $user["religion"];
                $religion = $this->db->query("select * from religion where id = '$religion_id'")->row_array();
                $religion_name = $religion["name"];
                $user['religion_name'] = $religion_name;

                $courseIds = $user["course"];
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
                    $user = array_merge($user, array("Course Details" => $courseList));
                }else{
                    $user['Course Details'] = array();
                }

                $final = array();
                $final['status'] = true;
                $final['message'] = 'User Profile Results';
                $final['data'] = $user;
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }

    // paraent linked child
    public function parent_child_display_post() {
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
                $parent_id = $data['user_id'];
                if($parent_id==''){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'User Cannot Be Blank, Please Check Data Again';
                    $this->response($final, REST_Controller::HTTP_OK);
                } 

                $child_data = $this->db->query("select a.id as relation_id,b.id,b.username ,b.full_name,b.student_id,b.image,b.dob,b.age from child_parent_relationship a "
                        . "left outer join users b on b.student_id = a.child_id "
                        . " where a.parent_id = '$parent_id' and a.request_status = 1")->result_array();
                if (count($child_data) === 0){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Child Details Failed,Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $user = $child_data[0];
                $user_id = $user['id'];

                $upcoming_classes = [];
                $ongoing_courses = $this->db->query("SELECT b.id,b.image,b.name,c.name AS course_type_name
                FROM mycart a
                LEFT OUTER JOIN courses b ON b.id = a.course_id
                LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
                WHERE a.child_id = '$user_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
               
                if(count($ongoing_courses) > 0) {
                    $current_date = date('Y-m-d H:i:s');
                    for ($i=0; $i < count($ongoing_courses); $i++) { 
                        $course_id = $ongoing_courses[$i]['id'];
                        $ongoing_courses[$i]['course_progress'] = $this->getCourseProgress($user_id,$course_id);
        
                        $class = $this->db->query("select a.id,a.start,a.end,a.upcoming_date,b.chapter_no,b.chapter_name,c.name as course_name,d.name as course_type_name from upcoming_classes a 
                        left outer join master_classes b1 on b1.id = a.master_class_id
                        left outer join chapter b ON b.id = b1.chapter_id
                        left outer join courses c ON c.id = b.course_id
                        left outer join course_type d ON d.id = c.course_type_id 
                        where c.id = $course_id and a.upcoming_date > '$current_date' and c.status != '2' and b.status!= '2' and a.status!='2' and b1.status!='2' and a.class_status != 'Cancel'")->result_array();
                        if(count($class) > 0) $upcoming_classes = array_merge($upcoming_classes,$class);
                    }
                }
        
                $date = date('Y-m-d');
                $events = $this->db->query("select * from events where event_date >= '$date' and status != '2'")->result_array();
        
                $badges = $this->db->query("select b.id,b.title,b.image from child_quiz_result a
                left outer join badges b on b.id = a.badge_id where a.user_id = '$user_id' and a.status != '2' and b.status != '2' group by b.id")->result_array();
        
                $recommended_courses = array();
                $age = $user['age'];
                $age_group = $this->db->query("SELECT * FROM age_group WHERE status != '2' AND $age BETWEEN min_age AND max_age")->result_array();
                if(count($age_group) > 0) {
                    $age_group_id = '';
                    for ($j=0; $j < count($age_group); $j++) { 
                        $age_group_id .= $age_group[$j]['id'] . ',';
                    }
                    $age_group_id = rtrim($age_group_id, ',');
                    $recommended_courses = $this->db->query("SELECT * FROM courses WHERE age_group_id IN ($age_group_id) AND status != '2'")->result_array();
                }
        
                if(count($recommended_courses) === 0) $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
                for ($i=0; $i < count($recommended_courses) ; $i++) { 
                    $course_id = $recommended_courses[$i]['id'];
                    $chapters = $this->db->query("select * from chapter where status != '2' and course_id = '$course_id'")->result_array();
                    $recommended_courses[$i]['lesson'] = (string) count($chapters);

                    $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) AS average_rating FROM course_rating where course_id = $course_id and status != '2' GROUP BY course_id;")->result_array();
                    $recommended_courses[$i]['average_rating'] = (count($average_rating) > 0 && $average_rating[0]['average_rating']) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
                }
                
                $result = array();
                $result['courseCount'] = (string) $this->getAllOngoingCoursesCount($user_id);
                $result['attendanceAverage'] = (string) $this->getAllOngoingCoursesAttendanceAverage($user_id);
                $result['leaderboardAverage'] = (string) $this->getAllQuizAverage($user_id);
                $result['courseProgressAverage'] = (string) $this->getOngoingCourseProgressAverage($user_id);
                $result['reportCount'] = (string) $this->getOngoingCoursesReportCount($user_id);
                $result['homeworkAverage'] = "";
                $result['galleryCount'] = "";
                $result['user_details'] = $user;
                $result['child_data'] = $child_data;
                $result['upcoming_classes'] = $upcoming_classes;
                $result['ongoing_courses'] = $ongoing_courses; 
                $result['event'] = $events;
                $result['badges'] = $badges;
                $result['recommended_courses'] = $this->addBookmarkStatus($user_id, $recommended_courses);


                $final = array();
                $final['status'] = true;
                $final['message'] = "User Display Results";
                $final['data'] = $result;
                $this->response($final, REST_Controller::HTTP_OK); 
            } else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
   }

   //Api to get all ongoing courses attendance average
    private function getAllOngoingCoursesAttendanceAverage($user_id) {
        $ongoingCourses = $this->getOngoingCourses($user_id);
        if(count($ongoingCourses) === 0) return 0;
        
        // date_default_timezone_set("asia/Kuala_Lumpur");
        //date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('Y-m-d H:i:s');
        $totalAttendance = [];

        for ($a = 0; $a < count($ongoingCourses); $a++) { 
            $courseId = $ongoingCourses[$a]['course_id'];
            $attendance = $this->getAttendanceBasedOnMasterClass($user_id, $courseId);
            array_push($totalAttendance, $attendance);
        }

        return number_format(array_sum($totalAttendance) / count($ongoingCourses), 2);
    }   
  //Api to get all ongoing courses count
    private function getAllOngoingCoursesCount($user_id) {
        $ongoingCourses = $this->db->query("SELECT count(*) as count
        FROM mycart a
        LEFT OUTER JOIN courses b ON b.id = a.course_id
        LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
        WHERE a.child_id = '$user_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
        $ongoingCoursesCount = (int)$ongoingCourses[0]['count'];

        return $ongoingCoursesCount;
    }
    //Api to get all quiz average
    private function getAllQuizAverage($user_id) {
        $quizScoreAverage = 0;
        $attemptedQuiz = $this->db->query("SELECT score FROM child_quiz_result WHERE user_id = $user_id AND status != '2'")->result_array();
        if(count($attemptedQuiz) > 0) {
            $quizScores = [];
            for ($i = 0; $i < count($attemptedQuiz); $i++) { 
                array_push($quizScores,(int)$attemptedQuiz[$i]['score']);
            }

            $quizScoreAverage = number_format(array_sum($quizScores) / (count($attemptedQuiz) * 100) * 100, 2);
        }

        return $quizScoreAverage;
    }


    //Api to get home work average 
    private function getHomeworkAverage($user_id) {
        $homeworkAverage = 0;
        $ongoingCourses = $this->getOngoingCourses($user_id);
        if(count($ongoingCourses) === 0) return $homeworkAverage;

        $currentDate = date('Y-m-d');
        for ($i = 0; $i < count($ongoingCourses); $i++) { 
            $course_id = $ongoingCourses[$i]['course_id'];
            $assignHomework = $this->db->query("SELECT * FROM homework WHERE course_id = $course_id AND hk_date <= '$currentDate' AND status != '2'")->result_array();
            $totalHomeworkExercises = [];
            if(count($assignHomework) > 0) {
                for ($j = 0; $j < count($assignHomework); $j++) { 
                    $exercises = explode(",",$assignHomework[$j]['exercise_id']);

                    for ($z = 0; $z < count($exercises); $z++) { 
                        if(!in_array($exercises[$z], $totalHomeworkExercises)) {
                            array_push($totalHomeworkExercises, $exercises[$z]);
                        }
                    }
                }

                $totalHomeworkExercisesStr = implode(",",$totalHomeworkExercises);
                $attemptedHomework = $this->db->query("SELECT count(*) as count FROM child_homework WHERE user_id = $user_id AND exercise_id IN($totalHomeworkExercisesStr) AND status != '2'")->result_array();
                $homeworkAverage = number_format(((int)$attemptedHomework[0]['count'] / count($totalHomeworkExercises)) * 100, 2); 
            }
        }

        return $homeworkAverage;
    }
 //Api to get  ongoing course gallery count
    private function getOngoingCourseGalleryCount($user_id) {
        $gallerCount = 0;
        $ongoingCourses = $this->getOngoingCourses($user_id);
        if(count($ongoingCourses) === 0) return $gallerCount;

        $ongingCoursesGalleryCount = array();
        for ($i=0; $i < count($ongoingCourses); $i++) { 
            $course_id = $ongoingCourses[$i]['course_id'];
            $courseGallery = $this->db->query("SELECT count(id) as count FROM course_gallery_folders WHERE course_id = $course_id AND status != '2'")->result_array();
            array_push($ongingCoursesGalleryCount, (int)$courseGallery[0]['count']);
        }

        if(count($ongingCoursesGalleryCount) > 0) {
            $gallerCount = array_sum($ongingCoursesGalleryCount);
        }

        return $gallerCount;
    }
     //Api to get ongoing course progress average
    private function getOngoingCourseProgressAverage($user_id) {
        $courseProgressAverage = 0;

        $ongoingCourses = $this->getOngoingCourses($user_id);
        if(count($ongoingCourses) === 0) return $courseProgressAverage;

        $totalClasses = [];
        $attendClasses = [];
        for ($i = 0; $i < count($ongoingCourses); $i++) { 
            $course_id = $ongoingCourses[$i]['course_id'];

            $masterClasses = $this->db->query("select count(a.id) as count from master_classes a 
            left outer join chapter b on b.id = a.chapter_id
            left outer join courses c on c.id = b.course_id
            where c.id = $course_id and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    
            if($masterClasses[0]['count'] > 0) {
                $attendance = $this->db->query("select count(a.master_class_id) as count from check_in a 
                left outer join master_classes b1 on b1.id = a.master_class_id
                left outer join chapter c ON c.id = b1.chapter_id
                left outer join courses d ON d.id = c.course_id 
                where a.course_id = $course_id and a.user_id = $user_id and d.status != '2' and c.status!= '2' and b1.status!='2' group by a.master_class_id")->result_array();
                if(isset($attendance[0]['count'])){

                    array_push($attendClasses, (int) $attendance[0]['count']);
                    array_push($totalClasses, (int) $masterClasses[0]['count']);
                }else{
                    array_push($attendClasses, 0);
                    array_push($totalClasses, (int) $masterClasses[0]['count']);
                }
              
            }    
        }

        if(count($totalClasses) > 0) {
            $courseProgressAverage = number_format((array_sum($attendClasses) / array_sum($totalClasses)) * 100, 2);
        }

        return $courseProgressAverage;
    }
      //Api to get ongoing courses report count
    private function getOngoingCoursesReportCount($user_id) {
        $reportCount = 0;
        // date_default_timezone_set("asia/Kuala_Lumpur");
       // date_default_timezone_set('Asia/Kolkata');
        $currentDateTime = date('Y-m-d H:i:s');          

        $ongoingCourses = $this->getOngoingCourses($user_id);
        if(count($ongoingCourses) === 0) return $reportCount;
 
        $monthlyReport = [];
        for ($a = 0; $a < count($ongoingCourses); $a++) { 
          
            $ongoingCoursesId = $ongoingCourses[$a]['course_id'];
            $purchase_date = $ongoingCourses[$a]['purchase_date'];
            $months = $this->getCourseMonths($purchase_date);
         
            if(count($months) > 0) {  
               
                for ($i = 0; $i < count($months); $i++) { 
                    $month = $months[$i]['month'];
                    $year = $months[$i]['year'];
                    $classes = $this->db->query("select * from upcoming_classes a 
                    left outer join master_classes b1 on b1.id = a.master_class_id
                    left outer join chapter b ON b.id = b1.chapter_id
                    left outer join courses c ON c.id = b.course_id 
                    left outer join course_type d ON d.id = c.course_type_id
                    where c.id = $ongoingCoursesId and MONTH(a.upcoming_date) = '$month' and YEAR(a.upcoming_date) = '$year' and a.upcoming_date < '$currentDateTime' and
                    c.status != '2' and b.status != '2' and a.status != '2' and b1.status!='2' and a.class_status != 'Cancel' group by c.id")->result_array();
                    if(count($classes) > 0) {
                        array_push($monthlyReport, count($classes));
                    }
                }
            }
        }  
        
        if(count($monthlyReport) > 0) {
            $reportCount = array_sum($monthlyReport);
        }

        return $reportCount;
    }
    //Api to get ongoing courses 
    private function getOngoingCourses($user_id) {
        return $this->db->query("SELECT b.id AS course_id,a.added_date AS purchase_date FROM mycart a
        LEFT OUTER JOIN courses b ON b.id = a.course_id
        LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
        WHERE a.child_id = '$user_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
    }

    //user display screen
    public function user_display_post() {
        $data = $_POST;
        $user_id = $data['user_id'];
        if ($user_id == '') {
            $final = array();
            $final['status'] = false;
            $final['message'] = "User id cannot be Blank";
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $user = $this->db->query("select id,image,username,full_name,dob,age,student_id from users where id = '$user_id'")->row_array();
        if (empty($user)) {
            $final = array();
            $final['status'] = false;
            $final['message'] = "User Not Found, Please Check Data Again!";
            $this->response($final, REST_Controller::HTTP_OK);
        }


        $upcoming_classes = [];
        $ongoing_courses = $this->db->query("SELECT b.id,b.image,b.name,c.name AS course_type_name
        FROM mycart a
        LEFT OUTER JOIN courses b ON b.id = a.course_id
        LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
        WHERE a.child_id = '$user_id' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
     
        if(count($ongoing_courses) > 0) {
          //  date_default_timezone_set('Asia/Kolkata');
            $current_date = date('Y-m-d H:i:s');
            $date = date('Y-m-d');
            $current_time = date("H:i:s");
            for ($i=0; $i < count($ongoing_courses); $i++) { 
                $course_id = $ongoing_courses[$i]['id'];
               
                $ongoing_courses[$i]['course_progress'] = $this->getCourseProgress($user_id,$course_id);
               
                $class = $this->db->query("select a.id,a.start,a.end,a.upcoming_date,b.chapter_no,b.chapter_name,c.name as course_name,d.name as course_type_name from upcoming_classes a 
                left outer join master_classes b1 on b1.id = a.master_class_id
                left outer join chapter b ON b.id = b1.chapter_id
                left outer join courses c ON c.id = b.course_id
                left outer join mycart e on e.course_id = b.course_id
                left outer join course_type d ON d.id = c.course_type_id where c.id = $course_id and Date(a.upcoming_date) = '$date' and end > '$current_time' and c.status != '2' and b.status!= '2' and a.status!='2' and e.child_id = '$user_id' and e.is_paid = '2' and b1.status!='2' and a.class_status != 'Cancel'")->result_array();
                if(count($class) > 0) $upcoming_classes = array_merge($upcoming_classes,$class);
            }
            
        }

       // $date = date('Y-m-d');
        $events = $this->db->query("select * from events where event_date >= '$date' and status != '2'")->result_array();

        $badges = $this->db->query("select b.id,b.title,b.image from child_quiz_result a
        left outer join badges b on b.id = a.badge_id where a.user_id = '$user_id' and a.status != '2' and b.status != '2' group by b.id")->result_array();

        $recommended_courses = array();
        $age = $user['age'];
        $age_group = $this->db->query("SELECT * FROM age_group WHERE status != '2' AND $age BETWEEN min_age AND max_age")->result_array();
        
        if(count($age_group) > 0) {
            $age_group_id = '';
            for ($j=0; $j < count($age_group); $j++) { 
                $age_group_id .= $age_group[$j]['id'] . ',';
            }
            $age_group_id = rtrim($age_group_id, ',');
            $recommended_courses = $this->db->query("SELECT * FROM courses WHERE age_group_id IN ($age_group_id) AND status != '2'")->result_array();
        }
        
        if(count($recommended_courses) === 0) $recommended_courses = $this->db->query("SELECT * FROM courses WHERE status != '2'")->result_array();
        for ($i=0; $i < count($recommended_courses) ; $i++) { 
            $course_id = $recommended_courses[$i]['id'];
            $chapters = $this->db->query("select * from chapter where status != '2' and course_id = '$course_id'")->result_array();
            $recommended_courses[$i]['lesson'] = (string) count($chapters);

            $average_rating = $this->db->query("SELECT course_id, AVG(rating_value) AS average_rating FROM course_rating where course_id = $course_id and status != '2' GROUP BY course_id;")->result_array();
            $recommended_courses[$i]['average_rating'] = (count($average_rating) > 0 && $average_rating[0]['average_rating']) ? number_format($average_rating[0]['average_rating'], 1) : number_format('0',1);
        }
     
        $result = array();
        $result['attendanceAverage'] = (string) $this->getAllOngoingCoursesAttendanceAverage($user_id);       
        $result['homeworkAverage'] = (string) $this->getHomeworkAverage($user_id);
        $result['courseCount'] = (string) $this->getAllOngoingCoursesCount($user_id);
        $result['leaderboardAverage'] = (string) $this->getAllQuizAverage($user_id);
        $result['courseProgressAverage'] = (string) $this->getOngoingCourseProgressAverage($user_id);
        $result['reportCount'] = (string) $this->getOngoingCoursesReportCount($user_id);
        $result['galleryCount'] = (string) $this->getOngoingCourseGalleryCount($user_id);
        $result['user_details'] = $user;
        $result['upcoming_classes'] = $upcoming_classes;
        $result['ongoing_courses'] = $ongoing_courses; 
        $result['event'] = $events;
        $result['badges'] = $badges;
        $result['recommended_courses'] = $this->addBookmarkStatus($user_id, $recommended_courses);
       
        $final = array();
        $final['status'] = true;
        $final['message'] = "User Display Results";
        $final['data'] = $result;
        $this->response($final, REST_Controller::HTTP_OK);  
    }
     // Api to add bookmark status 
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
     // Api to register user
    public function register_post() {
        $data = $_POST; 
       // date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
        // 1st Screen
        $social_type = !empty($data['social_type']) ? $data['social_type']: '';
        $social_id = !empty($data['social_id']) ? $data['social_id']: ''; 
        $socialLoginTypes = array("GOOGLE", "FACEBOOK", "APPLE", "MOBILE");
        $course = !empty($data['course']) ? $data['course']: ''; 
        $full_name = ucfirst($data['full_name']);
        $alias_name = !empty($data['alias_name']) ? ucfirst($data['alias_name']): '';  
        $dob = $data["dob"];
        $age = $data["age"];
        $firebase_token = $data['firebase_token'];
        $type = $data["type"];
        $student_id = $data["student_id"];
        $username = ucfirst($data['username']);
        if (!$full_name) return $this->requiredFieldError('Full Name');
        if (!$dob) return $this->requiredFieldError('Date Of Birth'); 
        if (!$age) return $this->requiredFieldError('Age');
        if (!$firebase_token) return $this->requiredFieldError('Firebase Token');
        if (!$type) return $this->requiredFieldError('Type');
        if (!$student_id) return $this->requiredFieldError('Student Id');
        if (!$username) return $this->requiredFieldError('Username');

        $alreadyExistUsername = $this->db->query("select * from users where username = '$username' and status != '2'")->result_array();
        if(count($alreadyExistUsername) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Username Already Exist, Please Try Different Username!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if($age < 5) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Age Cannot Be Less Than 5!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        if (!$this->isValidStudentId($student_id)) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Student Id Serial!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
                
        $dateTime = DateTime::createFromFormat('Y-m-d', $dob);
        if ($dateTime === false || array_sum(DateTime::getLastErrors()) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Date Of Birth Format!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        // 2nd Screen
        $nric = $data['nric'];
        $dialect = $data['dialect'];
        $religion = $data['religion'];
        $race = $data['race'];
        $nationality = $data['nationality'];                
        if (!$race) return $this->requiredFieldError('Race');
        if (!$dialect)return $this->requiredFieldError('Dialect');
        if (!$religion) return $this->requiredFieldError('Religion');
        if (!$nationality) return $this->requiredFieldError('Nationality');


        // 3rd Screen
        $contact_email = $data['contact_email'];
        $address = !empty($data['address']) ? ucfirst($data['address']) : '' ;
        $country = !empty($data['country']) ? $data['country'] : 0 ;
        $state = !empty($data['state']) ? $data['state'] : 0 ; 
        $city = !empty($data['city']) ? $data['city'] : 0 ; 
        $contact_number = $data['contact_number'];
        $iso_code = $data['iso_code'];
        $country_code = $data['country_code'];

        if (!$contact_email) return $this->requiredFieldError('Contact Email');
        if (filter_var($contact_email, FILTER_VALIDATE_EMAIL) != true) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Contact Email Format!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 

        $alreadyExistEmail = $this->db->query("select * from users where contact_email = '$contact_email' and status != '2'")->result_array();
        if(count($alreadyExistEmail) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Contact Email Already Exist!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if(!$this->isAllowedChildAccountNumber($contact_number)) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'This Contact Number Is Not Linked With Any Parent Account And Already Taken By A Child Account!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
      
        if (!$contact_number) return $this->requiredFieldError('Contact Number');
        if (!$iso_code) return $this->requiredFieldError('ISO Code');
        if (!$country_code) return $this->requiredFieldError('Country Code');


        // 4th Screen
        $e_iso_code = $data['e_iso_code'];
        $e_country_code = $data['e_country_code'];
        $emergency_name = ucfirst($data['emergency_name']);
        $emergency_relationship = ucfirst($data['emergency_relationship']);
        $emergency_number = $data['emergency_number'];
        $emergency_email = $data['emergency_email'];
        if (!$emergency_name) return $this->requiredFieldError('Emergency Name');
        if (!$emergency_relationship) return $this->requiredFieldError('Emergency Relationship');
        if (!$emergency_number) return $this->requiredFieldError('Emergency Number');
        if (!$e_iso_code) return $this->requiredFieldError('Emergency ISO Code');
        if (!$e_country_code) return $this->requiredFieldError('Emergency Country Code');
        if (filter_var($emergency_email, FILTER_VALIDATE_EMAIL) != true) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Emergency Email Format!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        // 5th Screen
        $credit = 5;
        if($social_id && in_array($social_type, $socialLoginTypes)) {
         //   date_default_timezone_set('Asia/Kolkata');
            $this->db->query("INSERT INTO users (username, social_status, firebase_token, social_final, student_id, image, course, password, created_at, updated_at, type, social_type,full_name,credit,
            alias_name, dob, age, race, dialect, religion, nric, nationlity, address, country, city, state, verify_number, iso_code, country_code, contact_number, contact_email, social_email, social_id,
            emergency_name, emergency_relationship, emergency_number, emergency_email, e_iso_code, e_country_code)
            VALUES ('$username', 'Social Login', '$firebase_token', '', '$student_id', '','', '', '$date', '$date', '$type', '$social_type', '$full_name','$credit',
            '$alias_name', '$dob', '$age', '$race', '$dialect', '$religion', '$nric', '$nationality', '$address', '$country', '$city', '$state', '', '$iso_code', '$country_code', '$contact_number', '$contact_email', '', '$social_id',
            '$emergency_name', '$emergency_relationship', '$emergency_number', '$emergency_email', '$e_iso_code', '$e_country_code');");   
        }else {
            $new_password = $data['new_password'];
            $confirm_new_password = $data['confirm_password'];
            if (!$new_password) 
            if (!$confirm_new_password)
            if ($new_password !== $confirm_new_password) {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'New Password And Confirm Password Not Matched!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
            $hashedPassword = md5($new_password); 
          //  date_default_timezone_set('Asia/Kolkata');
            $this->db->query("INSERT INTO users (username, social_status, firebase_token, social_final, student_id, image, course, password, created_at, updated_at, type, social_type, full_name,credit,
            alias_name, dob, age, race, dialect, religion, nric, nationlity, address, country, city, state, verify_number, iso_code, country_code, contact_number, contact_email, social_email, social_id,
            emergency_name, emergency_relationship, emergency_number, emergency_email, e_iso_code, e_country_code)
            VALUES ('$username', '', '$firebase_token', '', '$student_id', '','$course', '$hashedPassword', '$date', '$date', '$type', '', '$full_name','$credit',
            '$alias_name', '$dob', '$age', '$race', '$dialect', '$religion', '$nric', '$nationality', '$address', '$country', '$city', '$state', '', '$iso_code', '$country_code', '$contact_number', '$contact_email', '', '',
            '$emergency_name', '$emergency_relationship', '$emergency_number', '$emergency_email','$e_iso_code', '$e_country_code');");
        }

        if ($this->db->affected_rows() > 0) {
            $lastInsertedId = $this->db->insert_id();
            $this->sendWelcomeMail($username, $contact_email);

            $userInfo = $this->db->query("select * from users where id = '$lastInsertedId' and status != '2'")->row_array();
            $token_data['uid'] = $lastInsertedId;
            $token_data['username'] = $userInfo['username'];
            $tokenData = $this->authorization_token->generateToken($token_data);


            $final = array();
            $final['status'] = true;
            $final['data'] = array('access_token' => $tokenData, 'userInfo' => $userInfo);
            $final['message'] = 'Student Registration Successfull!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else {
            $final = array();
            $final['status'] = true;
            $final['message'] = 'Student Registration Failed!';
            $this->response($final, REST_Controller::HTTP_OK);
        }       
    }
    
    private function requiredFieldError($name) {
        $final = array();
        $final['status'] = false;
        $final['message'] = $name. ' Can Not Be Blank!';

        return $this->response($final, REST_Controller::HTTP_OK);
    }
    // Api to register parent user
    public function parent_register_post() {
       // date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
        $data = $_POST;

        // 1st Screen
        $social_type = !empty($data['social_type']) ? $data['social_type'] : '';
        $social_id = !empty($data['social_id']) ? $data['social_id'] : ''; 
        $socialLoginTypes = array("GOOGLE", "FACEBOOK", "APPLE", "MOBILE");

        $full_name = !empty($data['full_name']) ? ucfirst($data['full_name']) : '';
        $alias_name = !empty($data['alias_name']) ? ucfirst($data['alias_name']) : '';  ;
        $dob = $data['dob'];
        $age = $data['age'];
        $firebase_token = $data['firebase_token'];
        $type = $data['type'];
        $username = $data['username'];

        if (!$full_name) return $this->requiredFieldError('Full Name');
        if (!$dob) return $this->requiredFieldError('DOB');
        if (!$age) return $this->requiredFieldError('Age');
        if (!$firebase_token) return $this->requiredFieldError('Firebase Token');
        if (!$type) return $this->requiredFieldError('Type');
        if (!$username) return $this->requiredFieldError('Username');
        $alreadyExistUsername = $this->db->query("select * from users where username = '$username' and status != '2'")->result_array();
        if(count($alreadyExistUsername) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Username Already Exist, Please Try Different Username!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        if ($age < 18) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Age Can Not Be Less Than 18!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        $dateTime = DateTime::createFromFormat('Y-m-d', $dob);
        if ($dateTime === false || array_sum(DateTime::getLastErrors()) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Date Format in Date of Birth!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        // 2nd Screen        
        $contact_email = $data['contact_email'];
        $address = !empty($data['address']) ? ucfirst($data['address']) : '' ;
        $country = !empty($data['country']) ? $data['country']: 0 ;
        $state =  !empty($data['state']) ? $data['state']: 0; 
        $city = !empty($data['city']) ? $data['city']: 0; 
        $contact_number = $data['contact_number'];
        $iso_code = $data['iso_code'];
        $country_code = $data['country_code'];
        if (!$contact_email) return $this->requiredFieldError('Contact Email');
        if (filter_var($contact_email, FILTER_VALIDATE_EMAIL) != true) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Invalid Email Format in Contact Email!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 

        $alreadyExistEmail = $this->db->query("select * from users where contact_email = '$contact_email' and status != '2'")->result_array();
        if(count($alreadyExistEmail) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Contact Email Already Exist!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $alreadyExistContactNumber = $this->db->query("select * from users where contact_number = '$contact_number' and type = '2' and status != '2'")->result_array();
        if(count($alreadyExistContactNumber) > 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Contact Number Already Exist!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

      
        if (!$contact_number) return $this->requiredFieldError('Contact Number');   
        if (!$iso_code) return $this->requiredFieldError('ISO Code');
        if (!$country_code) return $this->requiredFieldError('Country Code');
                
     
        // 3rd Screen
        if($social_id && in_array($social_type, $socialLoginTypes)) {
            $this->db->query("INSERT INTO users (username, social_status, firebase_token, social_final, student_id, image, course, password, created_at, updated_at, type, social_type, full_name,
            alias_name, dob, age, race, dialect, religion, nric, nationlity, address, country, city, state, verify_number, iso_code, country_code, contact_number, contact_email, social_email, social_id,
            emergency_name, emergency_relationship, emergency_number, emergency_email, e_iso_code, e_country_code)
            VALUES ('$username', 'Social Login', '$firebase_token', '', '', '','', '', '$date', '$date', '$type', '$social_type', '$full_name',
            '$alias_name', '$dob', '$age', '', '', '', '', '', '$address', '$country', '$city', '$state', '', '$iso_code', '$country_code', '$contact_number', '$contact_email', '', '$social_id',
            '', '', '', '','','');");
        }else {
            $new_password = $data['new_password'];
            $confirm_new_password = $data['confirm_password'];
            if (!$new_password) return $this->requiredFieldError('New Password');
            if (!$confirm_new_password) return $this->requiredFieldError('Confirm Password');
            if ($new_password !== $confirm_new_password) {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'New Password And Confirm Password Not Matched!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
            $hashedPassword = md5($new_password);

            $this->db->query("INSERT INTO users (username, social_status, firebase_token, social_final, student_id, image, course, password, created_at, updated_at, type, social_type, full_name,
            alias_name, dob, age, race, dialect, religion, nric, nationlity, address, country, city, state, verify_number, iso_code, country_code, contact_number, contact_email, social_email, social_id,
            emergency_name, emergency_relationship, emergency_number, emergency_email)
            VALUES ('$username', '', '$firebase_token', '', '', '','', '$hashedPassword', '$date',' $date', '$type', '', '$full_name',
            '$alias_name', '$dob', '$age', '', '', '', '', '', '$address', '$country', '$city', '$state', '', '$iso_code', '$country_code', '$contact_number', '$contact_email', '', '',
            '', '', '', '');");
        }

        if ($this->db->affected_rows() > 0) {
            $lastInsertedId = $this->db->insert_id();
            $this->sendWelcomeMail($username, $contact_email);

            $userInfo = $this->db->query("select * from users where id = '$lastInsertedId' and status != '2'")->row_array();
            $token_data['uid'] = $lastInsertedId;
            $token_data['username'] = $userInfo['username'];
            $tokenData = $this->authorization_token->generateToken($token_data);

            $final = array();
            $final['status'] = true;
            $final['data'] = array('access_token' => $tokenData, 'userInfo' => $userInfo);
            $final['message'] = 'Parent Registration Successfull!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else {
            $final = array();
            $final['status'] = true;
            $final['message'] = 'Parent Registration Failed!';
            $this->response($final, REST_Controller::HTTP_OK);
        }  

    }

    public function search_child_post() {
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Child Results';
        $final['data'] = $this->db->query("select * from users where type = 1")->result_array();
        $this->response($final, REST_Controller::HTTP_OK);
    }

    function generateOTP($length = 6) {
        // Generate a random number
        $otp = rand(pow(10, $length - 1), pow(10, $length) - 1);

        // You can also use more secure random number generation functions like random_int or openssl_random_pseudo_bytes
        // Hash the random number to make it more secure
        $hashedOTP = hash('sha256', $otp);

        // Take the first $length characters as the OTP
        $finalOTP = substr($hashedOTP, 0, $length);

        return $finalOTP;
    }

    /**
     * login function.
     * 
     * @access public
     * @return void
     */
    public function login_post() {
        // set validation rules
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        if ($this->form_validation->run() == false) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Validation rules violated';
            $this->response($final, REST_Controller::HTTP_OK);
        } else {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            $type = $this->input->post('type');
            $firebase_token= $this->input->post('firebase_token');

            if ($this->user_model->resolve_user_login($username, $password, $type)) {
                
                $user_id = $this->user_model->get_user_id_from_username($username);
                $this->db->query("update users set firebase_token= '$firebase_token' where id = '$user_id'");

                $user = $this->user_model->get_user($user_id);
                $_SESSION['user_id'] = (int) $user->id;
                $_SESSION['username'] = (string) $user->username;
                $_SESSION['logged_in'] = (bool) true;
                $_SESSION['is_confirmed'] = (bool) $user->is_confirmed;
                $_SESSION['is_admin'] = (bool) $user->is_admin;

                // user login ok
                $userInfo = $this->db->query("select * from users where id = '$user_id' and status != '2'")->row_array();

                $token_data['uid'] = $user_id;
                $token_data['username'] = $userInfo['username'];
                $tokenData = $this->authorization_token->generateToken($token_data);

                $final = array();
                $final['status'] = true;
                $final['data']= array('access_token' => $tokenData, 'userInfo' => $userInfo);
                $final['message'] = 'Login successfully!';
                $this->response($final, REST_Controller::HTTP_OK);
            } else {
                 $select= $this->db->query("SELECT * FROM users WHERE `type`='$type' AND `username`='$username' AND `password`='$password' AND status = '2'")->result_array();
                if(count($select) > 0 ){
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Your account has been deleted administrative panel.';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Wrong username or password.';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                
            }
        }
    }

    /**
     * logout function.
     * 
     * @access public
     * @return void  
     */
    public function logout_post() {

        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

            // remove session datas
            foreach ($_SESSION as $key => $value) {
                unset($_SESSION[$key]);
            }

            // user logout ok
            $this->response(['Logout success!'], REST_Controller::HTTP_OK);
        } else {

          
            $this->response(['There was a problem. Please try again.'], REST_Controller::HTTP_OK);
        }
    }

    //api to search nationality 
    public function search_nationality_display_post() {
        $data = $_POST;
        $nationality_name = $data['nationality'];
        if($nationality_name == '') {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Nationality Can not Be Blank, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $nationality = $this->db->query("SELECT * FROM nationlity WHERE name LIKE '" . $nationality_name . "%' and status != '2'")->result_array();
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Nationality Search Result!';
        $final['data']= $nationality;
        $this->response($final, REST_Controller::HTTP_OK);
    }
 //Function to send welcome mail after successful registeration
    private function sendWelcomeMail($username,$to){
        $data = $_POST;
        $this->load->library('phpmailer_lib');        
        $mail = $this->phpmailer_lib->load();
        
        // SMTP configuration
        $mail->isSMTP();
        $mail->Host     = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'testotp90@gmail.com';
        $mail->Password = 'nomiehfimdpoqimi';
        $mail->SMTPSecure = 'ssl';
        $mail->Port     = 465;
        
        $mail->setFrom('testotp90@gmail.com', 'Code Bright Tution');
        $mail->addAddress($to);
        
        $mail->Subject = "Welcome to Code Bright Tution - Let's Learn Together!";        
        $mailContent = "<p>Dear <b>$username,</b></p>
        <p>Welcome to <b>Code Bright Tution!</b> We're thrilled to have you join our educational community where learning knows no bounds.</p>";
        $mail->Body = $mailContent;
        $mail->isHTML(true);
   
        if(!$mail->send()){
            // echo 'Message could not be sent.';
            // echo 'Mailer Error: ' . $mail->ErrorInfo;
        }else{
            // echo 'Message has been sent';
        }
    }
  //API to get course progress for the child
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

      //API to get attaendance summary for child
    public function attendance_summary_post() {
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
                    $final['message'] = 'Child Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
        
                $ongoing_courses = $this->db->query("SELECT a.added_date AS purchase_date,c.name AS course_type_name,b.*
                FROM mycart a
                LEFT OUTER JOIN courses b ON b.id = a.course_id
                LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
                WHERE a.child_id = '$child_uid' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
                if(count($ongoing_courses) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Ongoing Courses Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                } 
        
                // date_default_timezone_set("asia/Kuala_Lumpur");
               // date_default_timezone_set('Asia/Kolkata');
                $current_date = date('Y-m-d H:i:s');
                $result = array();
                $j = 0;
                for ($a = 0; $a < count($ongoing_courses); $a++) { 
                    $course_id = $ongoing_courses[$a]['id'];
                    $purchase_date = $ongoing_courses[$a]['purchase_date'];
                    $courseMonths = $this->getCourseMonths($purchase_date);
                   
                    for($i = 0; $i < count($courseMonths); $i++) {
                        $month = $courseMonths[$i]['month'];
                        $year = $courseMonths[$i]['year'];
                        $class = $this->db->query("select *,c.name as course_name,d.name as loc_name,d.address as loc_address,d.image as loc_image from upcoming_classes a 
                        left outer join master_classes b1 on b1.id = a.master_class_id
                        left outer join chapter b ON b.id = b1.chapter_id
                        left outer join courses c ON c.id = b.course_id
                        left outer join class_location d ON d.id = a.location	  
                        where c.id = $course_id and MONTH(a.upcoming_date) = '$month' and YEAR(a.upcoming_date) = '$year' and a.upcoming_date < '$current_date' and
                        c.status != '2' and b.status != '2' and a.status != '2' and b1.status != '2' and a.class_status != 'Cancel' group by b1.id")->result_array();
                        $total_classes = count($class);
                        
                        if($total_classes > 0) {
                            $present = 0;
                            $absent = 0;
                            $late = 0;
                            $average_hours = 0;
            
                            $attendance = $this->db->query("select * from check_in a 
                            left outer join upcoming_classes b ON b.id = a.upcoming_id
                            left outer join master_classes b1 on b1.id = b.master_class_id
                            left outer join chapter c ON c.id = b1.chapter_id
                            left outer join courses d ON d.id = c.course_id 
                            where a.course_id = $course_id and a.user_id = $child_uid and MONTH(a.added_date) = '$month' and YEAR(a.added_date) = '$year' and 
                            a.start_time < ADDTIME(b.start, '00:10:00') and b.upcoming_date < '$current_date' and
                            d.status != '2' and c.status!= '2' and b.status!='2' and b1.status!='2' and b.class_status != 'Cancel' group by b1.id;")->result_array();
                          
                            if(count($attendance) > 0) {
                                $present = count($attendance);
                                for ($k = 0; $k < count($attendance); $k++) { 
                                    $start_time = $attendance[$k]['start_time'];
                                    $end_time = $attendance[$k]['end_time'];
                                    $difference =  $this->getSeconds($start_time, $end_time);
                                    $average_hours = $average_hours + $difference;
                                }
                            }

                            $late_attendance = $this->db->query("select * from check_in a 
                            left outer join upcoming_classes b ON b.id = a.upcoming_id
                            left outer join master_classes b1 on b1.id = b.master_class_id
                            left outer join chapter c ON c.id = b1.chapter_id
                            left outer join courses d ON d.id = c.course_id 
                            where a.course_id = $course_id and a.user_id = $child_uid and MONTH(a.added_date) = '$month' and YEAR(a.added_date) = '$year' and
                            a.start_time > ADDTIME(b.start, '00:10:00') and b.upcoming_date < '$current_date' and
                            d.status != '2' and c.status!= '2' and b.status!='2' and b1.status!='2' and b.class_status != 'Cancel';")->result_array();
                            if(count($late_attendance) > 0) {
                                $late = count($late_attendance);
                                for ($z=0; $z < count($late_attendance); $z++) { 
                                    $start_time = $late_attendance[$z]['start_time'];
                                    $end_time = $late_attendance[$z]['end_time'];
                                    $difference =  $this->getSeconds($start_time, $end_time);
                                    $average_hours = $average_hours + $difference;
                                }
                            }

                            $absent = $total_classes - ($present + $late);
                            $average_hours = $average_hours / $total_classes;
            
                            $month_name = date('F', mktime(0, 0, 0, $month, 10));
                            $result[$j]['total_classes'] = $total_classes;
                            $result[$j]['month'] = $month_name;
                            $result[$j]['year'] = $year;
                            $result[$j]['present'] = ($present / $total_classes) * 100;
                            $result[$j]['present_days'] =  $present;
                            $result[$j]['absent'] = (int)($absent / $total_classes) * 100;
                            $result[$j]['absent_days'] =  $absent;
                            $result[$j]['late'] = (int)($late / $total_classes) * 100;
                            $result[$j]['late_days'] = $late;
                            $result[$j]['attendance'] = (int)$this->getAttendanceBasedOnMasterClass($child_uid, $course_id);
                            $result[$j]['course_name'] = $class[0]['course_name'];
                            $result[$j]['decription'] = $class[0]['description'];
                            $result[$j]['loc_name'] = $class[0]['loc_name'];
                            $result[$j]['loc_address'] = $class[0]['loc_address'];
                            $result[$j]['loc_image'] = $class[0]['loc_image'];
                            $result[$j]['average_hours'] = ($average_hours > 0) ? $this->secondToHour($average_hours) : $average_hours.'hrs';
            
                            $j++;
                        }
                    }
                }
        
                if(count($result) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Attendance Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = true;
                    $final['data'] = $result;
                    $final['message'] = 'Attendance Result!';
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

   
 //Function to get attaendance by master classes
    private function getAttendanceBasedOnMasterClass($userId, $courseId) {
        $totalAttendance = 0;
        $allMasterClasses = $this->db->query("select a.id
        from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$courseId' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
        if(count($allMasterClasses) === 0) return $totalAttendance;

        $masterClassIds = '';
        for ($i=0; $i < count($allMasterClasses); $i++) { 
            $id = $allMasterClasses[$i]['id'];
            $masterClassIds = $masterClassIds.$id.',';
        }
        $masterClassIds = rtrim($masterClassIds, ',');
        $attendedMasterClass = $this->db->query("select * from check_in where master_class_id in($masterClassIds) and user_id = '$userId' and status != '2' group by master_class_id")->result_array();
        if(count($attendedMasterClass) > 0) {
            $totalAttendance = (count($attendedMasterClass) / count($allMasterClasses)) * 100;
        }
        if($userId ==270 && $courseId == 99){
            echo count($attendedMasterClass);
            echo "===========================================================";
            echo count($allMasterClasses);
            die("ok");
          }
        return  $totalAttendance;
    }

    private  function getSeconds($time1,$time2){
        $time1 = new DateTime($time1);
        $time2 = new DateTime($time2);
        // Calculate the difference
        $interval = $time2->diff($time1);
        // Convert the difference to seconds
        $seconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
        return $seconds;
    }

    private  function secondToHour($seconds){    
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $remainingSeconds = $seconds % 60;
        return $hours . "hrs " . $minutes . "min " . $remainingSeconds . "sec";
    }

    public function getCourseMonths($date) {
        
        $dateTime = new DateTime($date);
        $purchase_date = strtotime($dateTime->modify('first day of this month')->format('Y-m-d H:i:s'));
        $current_date = strtotime(date('Y-m-d H:i:s'));
        $result = [];
     
        while ($purchase_date <= $current_date) {
            $month = date('m', $purchase_date);
            $year = date('Y', $purchase_date);
        
            $exists = false;
           
           if( array_search($month,$result) != '' && array_search($year,$result) != ''){
                 $exists = true;
           }

        
            if (!$exists) {
                $result[] = array('month' => $month, 'month_name' => date('F', mktime(0, 0, 0, $month, 10)), 'year' => $year);
            }
        
            $purchase_date = strtotime('+1 month', $purchase_date);
        }

        return $result;
    }


     //API to add report 
    public function report_post() {
        $data = $_POST;
        $child_uid = $data['child_uid'];
        $month = $data['month'] ? $data['month'] : date('m', strtotime(date('Y-m-d H:i:s')));
        $year = $data['year'] ? $data['year'] : date('Y', strtotime(date('Y-m-d H:i:s')));
        if(!$child_uid) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Child Id Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }

        $user =  $this->db->query("SELECT image,full_name FROM users WHERE id = $child_uid and status != '2'")->result_array();
        $ongoing_courses = $this->db->query("SELECT a.added_date AS purchase_date,c.name AS course_type_name,b.*
        FROM mycart a
        LEFT OUTER JOIN courses b ON b.id = a.course_id
        LEFT OUTER JOIN course_type c ON c.id = b.course_type_id
        WHERE a.child_id = '$child_uid' AND a.status != '2' AND a.is_paid = '2' AND b.status != '2'")->result_array();
        
        if(count($ongoing_courses) === 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Ongoing Courses Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
         
        // date_default_timezone_set("asia/Kuala_Lumpur");
       // date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d H:i:s');
        $result = array();
        $months = array();
        $j = 0;
 
        for ($a=0; $a < count($ongoing_courses); $a++) { 
            $course_id = $ongoing_courses[$a]['id'];
            $purchase_date = $ongoing_courses[$a]['purchase_date'];
            $months = array_merge($months ,$this->getCourseMonths($purchase_date));
            $class = $this->db->query("select *,c.id as course_id,c.name as course_name,d.name as course_type_name from upcoming_classes a 
            left outer join master_classes b1 on b1.id = a.master_class_id
            left outer join chapter b ON b.id = b1.chapter_id
            left outer join courses c ON c.id = b.course_id 
            left outer join course_type d ON d.id = c.course_type_id
            where c.id = $course_id and MONTH(a.upcoming_date) = '$month' and YEAR(a.upcoming_date) = '$year' and a.upcoming_date <= '$current_date' and
            c.status != '2' and b.status != '2' and a.status != '2' and b1.status != '2' and a.class_status != 'Cancel'")->result_array();
            $total_classes = count($class);
            if($total_classes > 0) {
                $average_hours = 0;

                $attendance = $this->db->query("select * from check_in a 
                left outer join upcoming_classes b ON b.id = a.upcoming_id
                left outer join master_classes b1 on b1.id = b.master_class_id
                left outer join chapter c ON c.id = b1.chapter_id
                left outer join courses d ON d.id = c.course_id 
                where a.course_id = $course_id and a.user_id = $child_uid and MONTH(a.added_date) = '$month' and YEAR(a.added_date) = '$year' and 
                b.upcoming_date < '$current_date' and
                d.status != '2' and c.status!= '2' and b.status!='2' and b1.status != '2' and b.class_status != 'Cancel';")->result_array();
                if(count($attendance) > 0) {
                    for ($k=0; $k < count($attendance); $k++) { 
                        $start_time = $attendance[$k]['start_time'];
                        $end_time = $attendance[$k]['end_time'];
                        $difference =  $this->getSeconds($start_time, $end_time);
                        $average_hours = $average_hours + $difference;
                    }
                }
    
                $month_name = date('F', mktime(0, 0, 0, $month, 10));
                $result[$j]['course_id'] = $class[0]['course_id'];
                $result[$j]['course_type_name'] = $class[0]['course_type_name'];
                $result[$j]['course_name'] = $class[0]['course_name'];
                $result[$j]['total_classes'] = $total_classes;
                $result[$j]['average_hours'] = ($average_hours > 0) ? $this->secondToHour($average_hours) : $average_hours.'hrs';
                $result[$j]['session'] = 4;
                $result[$j]['date-range'] = $purchase_date.'-'.$current_date;
                $result[$j]['month'] = $month_name;
                $result[$j]['year'] = $year;

                $j++;
            }
           
        } 
        
        if(count($result) === 0) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Report Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $reponse = array();
            $response['user_info'] = $user[0];
            $response['months'] = array_map('unserialize', array_unique(array_map('serialize', $months)));;
            $response['course_info'] = $result;
            $final = array();
            $final['status'] = true;
            $final['data'] = $response;
            $final['message'] = 'Report Result!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
  // Api to get child progress report for all course content 
    public function progress_report_post() {
        $data = $_POST;
        $child_uid = $data['child_uid'];
        $course_id = $data['course_id'];
        $month = $data['month'];
        $year = $data['year']; 
        // date_default_timezone_set("asia/Kuala_Lumpur");
      //  date_default_timezone_set('Asia/Kolkata');
        $current_date = date('Y-m-d H:i:s');
        if(!$child_uid) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Child Id Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if(!$course_id) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Course Id Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if(!$month) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Month Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        if(!$year) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Year Not Found, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        $class = $this->db->query("select *,c.name as course_name,d.name as course_type_name from upcoming_classes a 
        left outer join master_classes b1 on b1.id = a.master_class_id
        left outer join chapter b ON b.id = b1.chapter_id
        left outer join courses c ON c.id = b.course_id 
        left outer join course_type d ON d.id = c.course_type_id
        where c.id = $course_id and MONTH(a.upcoming_date) = '$month' and YEAR(a.upcoming_date) = '$year' and a.upcoming_date <= '$current_date' and
        c.status != '2' and b.status != '2' and a.status != '2' and b1.status != '2' and a.class_status != 'Cancel'")->result_array();
        $total_classes = count($class);
        if($total_classes > 0) {
            $average_hours = 0;

            $attendance = $this->db->query("select * from check_in a 
            left outer join upcoming_classes b ON b.id = a.upcoming_id
            left outer join master_classes b1 on b1.id = b.master_class_id
            left outer join chapter c ON c.id = b1.chapter_id
            left outer join courses d ON d.id = c.course_id 
            where a.course_id = $course_id and a.user_id = $child_uid and MONTH(a.added_date) = '$month' and YEAR(a.added_date) = '$year' and 
            b.upcoming_date < '$current_date' and
            d.status != '2' and c.status!= '2' and b.status!='2' and b1.status != '2' and b.class_status != 'Cancel';")->result_array();
            if(count($attendance) > 0) {
                for ($k=0; $k < count($attendance); $k++) { 
                    $start_time = $attendance[$k]['start_time'];
                    $end_time = $attendance[$k]['end_time'];
                    $difference = $this->getSeconds($start_time, $end_time);
                    $average_hours = $average_hours + $difference;
                }
            }

            //chapter percentage
            $total_chapter = 0;
            $complete_chapter = 0;
            $chapter_percentage = 0;

            $allChapters = $this->db->query("select * from chapter where course_id = $course_id and status != '2'")->result_array();
            $total_chapter = count($allChapters);
            if($total_chapter > 0) {
                $allCompletedChapters = $this->db->query("select * from upcoming_classes a
                left outer join master_classes b1 on b1.id = a.master_class_id
                left outer join chapter b on b.id = b1.chapter_id
                left outer join courses c on c.id = b.course_id
                where course_id = $course_id and a.status != '2' and b.status != '2' and c.status != '2' and b1.status != '2' and a.class_status != 'Cancel' and a.upcoming_date < '$current_date' group by b1.chapter_id;")->result_array();
                $complete_chapter = count($allCompletedChapters);
                $chapter_percentage = ceil(($complete_chapter / $total_chapter) * 100);
            }


            //activities percentage
            $total_activity = 0;
            $completed_activity = 0;
            $activity_percentage = 0;

            $date = date('Y-m-d');
            $allActivities = $this->db->query("select d.id as exercise_id from mycart a 
            left outer join courses b on b.id = a.course_id
            left outer join chapter c on c.course_id = b.id
            left outer join course_exercise d on d.chapter_id = c.id
            where a.course_id = '$course_id' and a.child_id = '$user_id' and a.is_paid = 2 and a.status != '2'
            and b.status != '2' and c.status != '2' and d.status != '2' and d.submit_date < '$date';")->result_array();
            $total_activity = count($allActivities);
            if($total_activity > 0) {
                $exercise_id = '';
                for ($i=0; $i < $total_activity; $i++) { 
                    $id = $allActivities[$i]['exercise_id'];
                    $exercise_id = $exercise_id.$id.',';
                }
                $exercise_id = rtrim($exercise_id,",");

                $allCompletedActivities = $this->db->query("select count(id) as count from child_homework where user_id = '$user_id' and exercise_id in ($exercise_id) and status!= '2';")->result_array();
                $activity_percentage = ceil(($allCompletedActivities[0]['count'] / $total_activity) * 100);
            }

            //test percentage
            $total_test = 0;
            $completed_test = 0;
            $test_percentage = 0;

            $allTest = $this->db->query("select * from main_quiz where course_id = $course_id and status != '2'")->result_array();
            $total_test = count($allTest);
            if($total_test > 0) {
                $test_id = '';
                for ($i=0; $i < $total_test; $i++) { 
                    $id = $allTest[$i]['id'];
                    $test_id = $test_id.$id.',';
                }
                $test_id = rtrim($test_id,",");
                $allCompletedTest = $this->db->query("select * from child_quiz_result where main_quiz_id in('$test_id') and user_id = $child_uid and status != '2'")->result_array();
                $completed_test = count($allCompletedTest);
                $test_percentage = ceil(($completed_test / $total_test) *100);
            }


            //session data
            $month_start_date = date("$year"."-"."$month"."-"."01");
            $session = $this->getMonthlySessionReport($month_start_date,$child_uid,$course_id);
 
            $result = array();
            $result['course_type_name'] = $class[0]['course_type_name'];
            $result['course_name'] = $class[0]['course_name'];
            $result['total_classes'] = $total_classes;
            $result['average_hours'] = ($average_hours > 0) ? $this->secondToHour($average_hours) : $average_hours.'hrs';

            $result['total_chapter'] = $total_chapter;
            $result['complete_chapter'] = $complete_chapter;
            $result['chapter_percentage'] = $chapter_percentage;

            $result['total_activity'] = $total_activity;
            $result['completed_activity'] = $completed_activity;
            $result['activity_percentage'] = $activity_percentage;

            $result['total_test'] = $total_test;
            $result['completed_test'] = $completed_test;
            $result['test_percentage'] = $test_percentage;

            $result['course_progress'] = $chapter_percentage;
            $result['session'] = 4;
            $result['session_list'] = $session;

            $final = array();
            $final['status'] = true;
            $final['data'] = $result;
            $final['message'] = 'Progress Report Results!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Progress Report Not Found';
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
   // Function to get monthly session report
    private function getMonthlySessionReport($month_start_date,$child_uid,$course_id) {
        $session = array();
        $session['session1'] = $this->getWeekReport(1,$month_start_date,$child_uid,$course_id);
        $session['session2'] = $this->getWeekReport(2,$month_start_date,$child_uid,$course_id);
        $session['session3'] = $this->getWeekReport(3,$month_start_date,$child_uid,$course_id);
        $session['session4'] = $this->getWeekReport(4,$month_start_date,$child_uid,$course_id);

        return $session;
    }
    // Function to get weekly report
    private function getWeekReport($week,$month_start_date,$child_uid,$course_id) {
        $week_report = array();
        $homework_list = array();
        // date_default_timezone_set("asia/Kuala_Lumpur");
      //  date_default_timezone_set('Asia/Kolkata');
        $date = new DateTime($month_start_date);
        if($week === 1) {
            $week_start_date = $date->format('Y-m-d');
            $week_end_date = date('Y-m-d',strtotime("+6 day", strtotime($week_start_date)));
        }elseif ($week === 2) {
            $week_start_date = date('Y-m-d',strtotime("+7 day", strtotime($month_start_date)));
            $week_end_date = date('Y-m-d',strtotime("+6 day", strtotime($week_start_date)));
        }elseif ($week === 3) {
            $week_start_date = date('Y-m-d',strtotime("+14 day", strtotime($month_start_date)));
            $week_end_date = date('Y-m-d',strtotime("+6 day", strtotime($week_start_date)));
        }else{
            $week_start_date = date('Y-m-d',strtotime("+21 day", strtotime($month_start_date)));
            $week_end_date = $date->format('Y-m-t');
        }
       
        $homeworkExercises = $this->db->query("SELECT a.id as homework_id,a.exercise_id,a.homework_title,b.chapter_name FROM homework a 
        LEFT OUTER JOIN course_exercise h ON h.id = a.exercise_id 
        LEFT OUTER JOIN chapter b ON b.id  = h.chapter_id 
        LEFT OUTER JOIN courses c ON c.id = b.course_id 
        LEFT OUTER JOIN course_type d ON c.course_type_id = d.id 
        LEFT OUTER JOIN mycart e ON e.course_id = b.course_id                   
        WHERE a.status != '2' AND b.status != '2' AND c.status != '2' AND d.status != '2' AND e.status != '2' 
        AND c.id = $course_id AND hk_date  BETWEEN '$week_start_date' AND '$week_end_date' group by a.exercise_id;")->result_array();
        if(count($homeworkExercises) > 0) {
            for ($i = 0; $i < count($homeworkExercises); $i++) { 
                $allId = array_unique(explode(",",$homeworkExercises[$i]['exercise_id']));
                $exercises = array();    
                $exercise_score = array();
                for ($j = 0; $j < count($allId); $j++) { 
                    $exercise_id = $allId[$j];
                    $homeworkExerciseInfo = $this->db->query("SELECT id,task as name FROM course_exercise WHERE id = $exercise_id AND status != '2'")->result_array();
                    if(count($homeworkExerciseInfo) > 0) {
                        $isSubmitted = $this->db->query("SELECT * FROM child_homework WHERE exercise_id = $exercise_id AND user_id = $child_uid AND status != '2'")->result_array();
                        $homeworkExerciseInfo[0]['score'] = count($isSubmitted) > 0 ? $isSubmitted[0]['score_percentage'] : '0';
                        $exercises[$j] = $homeworkExerciseInfo[0];
                        array_push($exercise_score,(int)$homeworkExerciseInfo[0]['score']);
                    }
                }
                $homework_list[$i]['name'] = $homeworkExercises[$i]['homework_title'];
                $homework_list[$i]['score'] = (string)ceil(array_sum($exercise_score)/count($allId));
                $homework_list[$i]['exercises'] = $exercises;
                $week_report[$i]['chapter_name'] = $homeworkExercises[$i]['chapter_name'];
                $week_report[$i]['homewok_list'] = array_values($homework_list);
            }
        }else{
            $homework_list[0]['name'] = [];
            $homework_list[0]['score'] = [];
            $homework_list[0]['exercises'] = [];
            $week_report[0]['chapter_name'] = '';
            $week_report[0]['homewok_list'] = [];
        }
            $course_details =  $this->db->query("SELECT name FROM courses WHERE id='$course_id' AND status != '2' ")->row_array();
            $quiz_list = $this->db->query("SELECT a.marks ,b.name FROM child_quiz_result a 
            LEFT OUTER JOIN main_quiz b ON b.id = a.main_quiz_id   
            LEFT OUTER JOIN courses c ON c.id = b.course_id  
            WHERE a.status != '2' AND b.status != '2' AND c.status != '2' AND a.user_id = $child_uid  AND c.id = $course_id AND a.added_date BETWEEN '$week_start_date' AND '$week_end_date'")->result_array();
            if(count($quiz_list) > 0){
                $week_report[0]['quiz'] = ['course_name' => $course_details['name'],
                'quiz_list' => $quiz_list ];
            }else{
                $week_report[0]['quiz'] = array("course_name"=>'', "quiz_list"=>[]);
            }
            
        return $week_report;
    }

     
    public function getStudentId_get() {
        $final = array();
        $final['status'] = true;
        $final['data'] = $this->generateStudentId();
        $final['message'] = 'Student Id!';
        $this->response($final, REST_Controller::HTTP_OK);
    }
    // Function to generate student ID
    private function generateStudentId() {
        $studentId = '';
        $currentYear = date("Y");
        $allStudents = $this->db->query("select a.id, a.student_id from users a left outer join user_type b on b.id = a.type 
        where a.status != '2' and a.type = '1' order by a.created_at desc")->result_array();

        if(count($allStudents) > 0) {
            $lastStudentId = $allStudents[0]['student_id'];
            if($currentYear != substr($lastStudentId, 2, 4)) {
                $studentId = 'ST'.$currentYear.'0001';
            }else{
                $studentIdSerialNumber = substr($lastStudentId, 2, 8);
                $studentId = 'ST'.((int) $studentIdSerialNumber + 1);
            }             
        }else {
            $studentId = 'ST'.$currentYear.'0001';
        }

        return $studentId;
    }

    private function isValidStudentId($studentId) {
        return ($studentId === $this->generateStudentId()) ? true : false;
    }
// Function  to get all valid day time slots for course
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
            array_push($timeSlots, $newTimeSlot);
            array_push($days, $classDay);
        }
    
        return array('timeSlots' => $timeSlots, 'days' => array_values(array_unique($days)));
    }

     // Function to course all master classes
    private function getCourseAllMasterClasses($course_id) {
        return $this->db->query("select a.*,b.id as chapter_id,b.chapter_name,c.id as course_id,c.name as course_name 
        from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    }

     // API to check username 
    public function isUserNameExist_post() {
        $data = $_POST;
        $username = $data['username'];
        if(!$username) return $this->requiredFieldError('Username');

        $alreadyTaken = $this->db->query("select * from users where username = '$username' and status != '2'")->result_array();

        $final = array();
        $final['status'] = true;
        if(count($alreadyTaken) > 0){
            $final['message'] = 'UserName Already Taken!';
            $this->response($final, REST_Controller::HTTP_OK);
        }else{
            $final['message'] = 'UserName Available!';
            $this->response($final, REST_Controller::HTTP_OK);
        }    
    }
  // API to check contact and email exists
    public function isContactEmailAndNumberExist_post() {
        $data = $_POST;
        $contactEmail = $data['contactEmail'];
        $contactNumber = $data['contactNumber'];
        $contactType = $data['type'];
        if(!$contactEmail) return $this->requiredFieldError('Contact Email');
        if(!$contactNumber) return $this->requiredFieldError('Contact Number');
        if($contactType == 'student'){
            $isContactEmailTaken = $this->db->query("select * from users where contact_email = '$contactEmail' and status != '2'")->result_array();
            if(count($isContactEmailTaken) > 0) {
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Contact Email Already Taken!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }else{
            $isContactEmailTaken = $this->db->query("select * from users where contact_email = '$contactEmail' and status != '2'")->result_array();
            if(count($isContactEmailTaken) > 0) {
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Contact Email Already Taken!';
                $this->response($final, REST_Controller::HTTP_OK);
            }

            $isContactNumberTaken = $this->db->query("select * from users where contact_number = '$contactNumber' and status != '2'")->result_array();
            if(count($isContactNumberTaken) > 0) {
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Contact Number Already In Use!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
        
        $final = array();
        $final['status'] = true;
        $final['message'] = 'Contact Email And Number Are Available!';
        $this->response($final, REST_Controller::HTTP_OK);
    }
   
    private function isAllowedChildAccountNumber($number) {
        $users = $this->db->query("select * from users where contact_number = '$number' and status != '2'")->result_array();
        if(count($users) === 0) return true;
    
        $isParentAccount = array_filter($users, function($user) { return $user['type'] == 2; });
        return count($isParentAccount) > 0 ? true : false;
    }

    public function student_slot_post(){
        $data = $_POST;
        $user_id = $data['user_id'];
        $headers = $this->input->request_headers();
     
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                if ($user_id == '') {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User id cannot be Blank";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                    $student_data_id = array();
                    $parentData = $this->db->query("SELECT * FROM child_parent_relationship WHERE parent_id = '$user_id' AND status != '2'")->result_array();
                    foreach($parentData as $list){
                        $stId = $list['child_id'];
                        $student_data = $this->db->query("SELECT * FROM users WHERE student_id = '$stId' AND status != '2'")->row_array();
                        array_push($student_data_id, $student_data );
                    }
                
                    if (empty($student_data)) {
                        $final = array();
                        $final['status'] = false;
                        $final['message'] = "Child Id not found";
                        $this->response($final, REST_Controller::HTTP_OK);
                    }else{
                        $final = array();
                        $final['status'] = true;
                        $final['message'] = 'Parents child result';
                        $final['data'] = $student_data_id;
                        $this->response($final, REST_Controller::HTTP_OK);
                    }
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }
    }


     // API to delete user
    public function delete_user_post(){
        $data = $_POST;
        $user_id = $data['user_id'];
        $headers = $this->input->request_headers();
     
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        }
        
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                if ($user_id == '' || !is_numeric($user_id)) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User id cannot be Blank or Invalid";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
               
                $userInfo = $this->db->query("select * from users where id = '$user_id' and status != '2'")->result_array();
               
                if (count($userInfo) === 0) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = "User not found!";
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                $this->db->query("update users set status = '2' where id = '$user_id'");

                $mainCartData = $this->db->query("select * from main_cart where user_id = '$user_id'")->result_array();
                if (count($mainCartData) > 0) { 
                    $this->db->query("update main_cart set status = '2' where user_id = '$user_id'");
                    $mainCardId = $mainCartData[0]['id'];
                    $this->db->query("update mycart set status = '2' where main_cart_id = '$mainCardId'");
                }
                $userInfo = $this->db->query("select * from users where id = '$user_id'")->row_array();
                if (!empty($userInfo) && (int)$userInfo['status'] === 2) { 
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = "User deleted successfully";
                    $this->response($final, REST_Controller::HTTP_OK);
                }else{
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Failed to delete user';
                    $final['data'] = $student_data_id;
                    $this->response($final, REST_Controller::HTTP_OK);
                }
            }else {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Authorization Token Expired';
                $this->response($final, REST_Controller::HTTP_OK);
            }
        }

    }
    public function logout_user_post(){
        $data = $_POST;
        $user_id = $data['user_id'];
        $headers = $this->input->request_headers();
    
       if (empty($headers['Authorization'])) {
           $final['status'] = false;
           $final['message'] = 'Authorization token Cannot be Blank';
           $this->response($final, REST_Controller::HTTP_OK);
       }
       
        if (isset($headers['Authorization'])) {
            $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
            if ($decodedToken['status']) {
                $values = null;
                $this->db->query("update users set firebase_token = '$values' where id = '$user_id'");
                $final = array();
                $final['status'] = true;
                $final['message'] = "User logout successfully";
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

