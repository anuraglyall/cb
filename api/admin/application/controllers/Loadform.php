<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/*  
This file contains miscellaneous code to add contents in frontend form 
*/ 
class Loadform extends CI_Controller {

    public function getChaptersByCourseId() {
        $data = $_POST;
        $course_id = $data['course_id'];
        $chapters = $this->db->query("SELECT id,chapter_name as name FROM chapter WHERE course_id = $course_id AND status != '2'")->result_array();
        echo json_encode($chapters);
    }

    public function getExercisesByChapterId() {
        $data = $_POST;
        $chapter_id = $data['chapter_id'];
        $exercises = $this->db->query("SELECT id,task as name FROM course_exercise WHERE chapter_id = $chapter_id AND status != '2'")->result_array();
        echo json_encode($exercises);
    }

    public function getQuizByMainQuizId() {
        $data = $_POST;
        $main_quiz_id = $data['main_quiz_id'];
        $quiz = $this->db->query("SELECT id, name FROM quiz WHERE main_quiz_id = $main_quiz_id AND status != '2'")->result_array();
        echo json_encode($quiz);
    }

    public function changeCourseForCriteria() {
        $data = $_POST;
        $course_id = $data['course_id'];
        $mainQuiz = $this->db->query("SELECT id, percent_creteria FROM main_quiz WHERE course_id = $course_id AND status != '2'")->result_array();
        $availableCriteria = 0 ;
        foreach ($mainQuiz as $results) { 
            $availableCriteria += $results['percent_creteria']; 
        }
        echo json_encode($availableCriteria);
    }

    public function getStateCity() {
        $data = $_POST;
        $state_id = $data['state_id'];
        $cityData = $this->db->query("SELECT id, name FROM city WHERE state_id = $state_id")->result_array();
        
        echo json_encode($cityData);
    }

    public function getUserCourse() {
        $data = $_POST;
        $user_id = $data['user_id'];
        $quiz = $this->db->query("select a.*,b.name from mycart a"
        . " left outer join courses b on b.id=a.course_id "
        . " where a.child_id=$user_id and a.status!='2' and b.id > 0")->result_array();
        echo json_encode($quiz);
    }

    public function getGalleryFoldersByCourseId() {
        $data = $_POST;
        $course_id = $data['course_id'];
        $folders = $this->db->query("SELECT id, folder_name as name FROM course_gallery_folders WHERE course_id = $course_id AND status != '2'")->result_array();
        echo json_encode($folders);
    }

    public function getCourseListToCertification() {
        $data = $_POST;
        $user_id = $data['user_id'];
        
        $courses = [];
        $ongoingCourses = $this->db->query("SELECT b.id,b.name FROM mycart a 
        LEFT OUTER JOIN courses b ON b.id = a.course_id
        LEFT OUTER JOIN users c ON c.id = a.child_id 
        LEFT OUTER JOIN course_certificate d ON d.user_id = a.child_id AND d.course_id = a.course_id
        WHERE a.child_id = $user_id AND a.is_paid = 2 AND d.certificate IS NULL AND a.status != '2' AND b.status != '2' AND c.status != '2'")->result_array();

        if(count($ongoingCourses) > 0) {
            for ($i=0; $i < count($ongoingCourses); $i++) { 
               $course_id = $ongoingCourses[$i]['id'];
               $course_name = $ongoingCourses[$i]['name'];

               $classes = $this->db->query("select count(a.id) as count from upcoming_classes a 
               left outer join master_classes b1 on b1.id = a.master_class_id
               left outer join chapter b ON b.id = b1.chapter_id
               left outer join courses c ON c.id = b.course_id where c.id = $course_id and c.status != '2' and b.status!= '2' and a.status!='2' and a.class_status != 'Cancel'")->result_array();
               
               if($classes[0]['count'] > 0) {
                   $attendance = $this->db->query("select count(a.id) as count from check_in a 
                   left outer join upcoming_classes b ON b.id = a.upcoming_id
                   left outer join master_classes b1 on b1.id = b.master_class_id
                   left outer join chapter c ON c.id = b1.chapter_id
                   left outer join courses d ON d.id = c.course_id 
                   where a.course_id = $course_id and a.user_id = $user_id and d.status != '2' and c.status!= '2' and b.status!='2' and b.class_status != 'Cancel'")->result_array();
                    if($classes[0]['count'] === $attendance[0]['count']) {                        
                        array_push($courses,array('id' => $course_id, 'name' => "$course_name"));
                    }
               }  
            }
        }

        echo json_encode($courses);
    }
}