<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;

class Event extends REST_Controller {

	public function __construct() {
	    parent::__construct();
        $this->load->library('Authorization_Token');
	    $this->load->model('user_model');
	}
          //API to get events data
    public function event_display_post() {
        $data = $_POST;
        $event_date = $data['event_date'];
        $date = date('Y-m-d');
        $sql = '';
        if($event_date){
            $sql .= " and event_date = '$event_date'";                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                  
        }else{
            $sql = " and event_date >= '$date'";
        }
        
        $events = $this->db->query("select a.*, b.name as location_name from events a
        left outer join class_location b on b.id = a.location_id
        where a.status != '2' $sql ")->result_array();
        if(count($events) > 0){       
            $final = array();
            $final['status'] = true;
            $final['message'] = 'Event Results';
            $final['data'] = $events;
            $this->response($final, REST_Controller::HTTP_OK); 
        }else{
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Event Not Found, Please Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
       
          //API to get single event details
    public function event_details_post() {
        $data = $_POST;
        $event_id = $data['event_id'];
        $user_id = !empty($data['user_id']) ? (int)$data['user_id'] : 0 ;
        $type = !empty($data['type']) ? $data['type'] : null ;
        $paid = false;
        if(!$event_id) {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Event Id Cannot Be Blank, Please Check Data Again!';
            $this->response($final, REST_Controller::HTTP_OK); 
        }
       
        if($user_id  > 0 ){
           if( $type == 'child') {
            $request_check_child = $this->db->query("select * from event_transaction where event_id = '$event_id' and child_id = '$user_id' and payment_status = 'success' and status != '2'")->result_array();
            if(count($request_check_child) > 0 ){
                $paid = true;
            }
           }
        }

        $event = $this->db->query("select a.*, b.name as location_name from events a
        left outer join class_location b on b.id = a.location_id
        where a.id = '$event_id'and a.status != '2'")->result_array();
        if(count($event)>0){       
            $final = array();
            $final['status'] = true;
            $final['message'] = 'Event Details Results';
            $final['data'] = $event[0];
            $final['paid'] =  $paid;
            $this->response($final, REST_Controller::HTTP_OK); 
        }else{
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Event Not Found, Please Data Again!';
            $this->response($final, REST_Controller::HTTP_OK);
        }
    }
}
