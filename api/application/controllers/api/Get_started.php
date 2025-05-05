<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
//not used by cssoft
class Get_started extends REST_Controller {

	public function __construct() {
	parent::__construct();
        $this->load->library('Authorization_Token');
	$this->load->model('user_model');
	}
        public function get_started_post() {
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Get started Results';
                $final['data'] = $this->db->query("select id,name ,url from get_started where status !=2")->result_array();
                $this->response($final, REST_Controller::HTTP_OK); 
        }
       
		
	
}
