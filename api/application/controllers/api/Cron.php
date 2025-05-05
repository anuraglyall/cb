<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
/* Cron job file to provide 5 free credit to each use every month
and check tutorial subscription for expire 
*/
class Cron extends REST_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('user_model');
	}

    public function auto_subscription_get() {

        $current_date = date('Y-m-d H:i:s');
        $current_date_without_time = date('Y-m-d');
        $first_date_month = date("Y-m-01", strtotime($current_date));
        //refresh students credit 
        if(strtotime($current_date_without_time) == strtotime($first_date_month)){
            $allStudentUser = $this->db->query("SELECT id FROM users where type='1' and status != '2'")->result_array();
            foreach($allStudentUser as $list){
                $id=$list['id'];
                $updateStudentsCredit = $this->db->query("UPDATE users SET credit=5 WHERE id = '$id'");
            }
        }
       
        $updateAllExpiredCredits = $this->db->query("UPDATE tutorial_credit_transactions SET is_expired = 1 WHERE is_expired = 0 AND credit_expiry < '$current_date';");
        $allParentUsers = $this->db->query("SELECT parent_id FROM user_tutorial_subscription GROUP BY parent_id;")->result_array();
       
        if(count($allParentUsers) > 0) {
            for ($i=0; $i < count($allParentUsers); $i++) { 
                $parent_id = $allParentUsers[$i]['parent_id'];
                $activeChildSubscriptions = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE parent_id = $parent_id AND status != '2' AND is_active = '1'")->result_array();
                if(count($activeChildSubscriptions) > 0) {
                    for ($j=0; $j < count($activeChildSubscriptions); $j++) { 
                        $subscriptionId = $activeChildSubscriptions[$j]['id'];
                        $subscriptionEndDate = $activeChildSubscriptions[$j]['end'];
                        $student_id = $activeChildSubscriptions[$j]['student_id'];
                        $auto_subscription = $activeChildSubscriptions[$j]['auto_subscription'];
                        
                        if($current_date > $subscriptionEndDate){
                            if($auto_subscription == '0') {
                                $studentCredit =  5;
                                $this->db->query("UPDATE users  SET credit = $studentCredit WHERE id = $student_id ");
                                $subscriptionEnd = $this->db->query("UPDATE user_tutorial_subscription SET is_active = 0 WHERE id = $subscriptionId");
                            }

                            if($auto_subscription == '1') {
                                $available_credits = $this->calculateUserTutorialCredits($parent_id);
                                if($available_credits > 0) {
                                    $subscriptionExpiryInDays = 30;
                                    $start_date = $current_date;
                                    $end_date = date('Y-m-d H:i:s', strtotime("+$subscriptionExpiryInDays day", strtotime($start_date)));
                                    $subscriptionEnd = $this->db->query("UPDATE user_tutorial_subscription SET is_active = 0 WHERE id = $subscriptionId ");
                                    $user_tutorial_subscription = array('parent_id'=> $parent_id,'student_id'=> $student_id,'start'=> "$start_date",'end'=> "$end_date",'auto_subscription'=> 1,'is_active'=> 1,'created_at'=> "$current_date",'updated_at'=> "$current_date");
                                    $this->db->insert('user_tutorial_subscription',$user_tutorial_subscription);
                                }else{
                                    $subscriptionEnd = $this->db->query("UPDATE user_tutorial_subscription SET auto_subscription = 0, is_active = 0 WHERE id = $subscriptionId ");
                                }
                            }
                        }

                    }
                }

            }
        }
    }

    private function calculateUserTutorialCredits($user_id) {
  
        $valid_credits = 0;
        $used_credits = 0;
        $current_date = date('Y-m-d H:i:s');
        $payment_status = 'success';
        $available_credits = 0;

        $validCredits = $this->db->query("SELECT * FROM tutorial_credit_transactions WHERE user_id = $user_id AND payment_status = '$payment_status' AND credit_expiry > '$current_date' ORDER BY credit_expiry")->result_array();
        if(count($validCredits) > 0) {
            foreach ($validCredits as $key => $value) {                
                $valid_credits = $valid_credits + $value['credits'];
            }
        }

        $usedCredits = $this->db->query("SELECT * FROM user_tutorial_subscription WHERE parent_id = $user_id ")->result_array();
        if(count($usedCredits) > 0) {
            foreach ($usedCredits as $key => $value) {                
                $used_credits = $used_credits + $value['used_credits'];
            }
        }

        $available_credits = (int) $valid_credits - (int) $used_credits;
        return $available_credits;
    }

    public function recurring_classes_get() {
   
        $yesterdayDate = date("Y-m-d", strtotime("-1 day"));
        $currentTime = date('H:i:s');
        $currentDateTime = date('Y-m-d H:i:s');
        $recurringClasses = $this->db->query("select * from upcoming_classes where recurring = 'yes' and DATE(upcoming_date) = '$yesterdayDate' and status != '2' and class_status != 'Cancel'")->result_array();
        if(count($recurringClasses) === 0) return;

        for ($i = 0; $i < count($recurringClasses); $i++) {
            $id = $recurringClasses[$i]['id'];
            $title = $recurringClasses[$i]['title'];
            $masterClassId = $recurringClasses[$i]['master_class_id'];
            $recurring = 'yes';
            $qrCode = '';
            $start = $recurringClasses[$i]['start'];
            $end = $recurringClasses[$i]['end'];
            $location = $recurringClasses[$i]['location'];
            $upcomingClassDate = $recurringClasses[$i]['upcoming_date'];
            $status = 0;

            //Updating old recurring class's recurring status
            $this->db->set('recurring', 'no');
            $this->db->set('updated_date', $currentDateTime);
            $this->db->where('id', $id); 
            $this->db->update('upcoming_classes');


            //Creating new recurring class
            $newClass = array(
                'title' => $title,
                'master_class_id' => $masterClassId,
                'qr_code' => $qrCode,
                'start' => $start,
                'end' => $end,
                'location' => $location,
                'recurring' => $recurring,
                'upcoming_date' => $this->getNextWeekDate($upcomingClassDate),
                'added_date' => $currentDateTime,
                'updated_date' => $currentDateTime
            );
            $this->db->insert('upcoming_classes', $newClass);
        }
    }

    private function getNextWeekDate($upcomingClassDate) {
    
        $date_obj = date_create($upcomingClassDate);
        date_add($date_obj, date_interval_create_from_date_string("7 days"));
        $new_date = date_format($date_obj, "Y-m-d H:i:s");
        return $new_date;
    }
}