<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Track extends CI_Controller {
        
        public function index()
	{   
                  $data['validate']="name,short_name,type";  
                  $fullUrl = base_url(uri_string());
                  ### partner masters
                  		  
		  if (strpos($fullUrl, base_url().'track-your-orders') !== false) { 
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='orders' order by sort ASC")->result_array();
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  $perameters2[]=$form_data_res;
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  }
                  } 
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  }
                      
                  $defult_file='masters/default/master/trackorders';
                  $this->load->view($defult_file,$data);
	}  
        
}