<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// not used by cssoft 
class Consignmantmaster extends CI_Controller {
        
        function call_api($url,$data)
        {
            $url = $url;
            // Convert the data to JSON
            $jsonData = json_encode($data);
//            exit;
            $curl = curl_init();

            curl_setopt_array($curl, array(
              CURLOPT_URL => $url,
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'POST',
              CURLOPT_POSTFIELDS =>$jsonData,
              CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
              ),
            ));

            $response = curl_exec($curl);

            curl_close($curl);
            return $response;
        }
        
    
    
        public function index()
	{   
                  $data['validate']="name,short_name,type";  
                  $data['master']="diamondmaster";
                  $fullUrl = base_url(uri_string());
                  ### consignment masters
                  
                  if (strpos($fullUrl, 'co') !== false) {
                  $data['edits']=array("id","name","code","person_name","person_email","person_mobile","location");     
                  $data['table']="company";    
                  $data['display']=array("Id","Name","Company Code","Person Name","Person Email","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","code","person_name","person_email","added_date","updated_date","action");
                  $data['title']="Consignment Outward";
                  $data['title2']="Consignment Outward List";
                  $dbname=($_SESSION['customer_data']['dbname']);

                  $currency=array();
                  $currency[]=array("id"=>"USD","name"=>"USD");
                  $currency[]=array("id"=>"THB","name"=>"THB");

                  
                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='co'")->result_array();

                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='customer')
                  {
                  $data2=array("db"=>$dbname);    
                  $url="https://mbapi.jewelsinfotech.com/index.php/api/customers";
                  $api_res=$this->call_api($url,$data2);
                  $api_res= json_decode($api_res,true);
                      
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters[]=$form_data_res;
                  }
                  if($form_data_res['id']=='currency')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  $data2=array("db"=>$dbname);    
                  $url="https://mbapi.jewelsinfotech.com/index.php/api/stock_search";
                  $api_res=$this->call_api($url,$data2);
                  $api_res= json_decode($api_res,true);
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }

                  
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  $data['type']="1";  
                  }
                  
                  $this->load->view('masters/co/master/mainmaster',$data);
	}  
        
        public function fetch_data() {   
            
        $requestData = $_POST;
//        $type=$requestData['type'];
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];

        // Load the Metal model
        $this->load->model('MasterModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->MasterModel->fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection,$requestData);

        

        echo json_encode($data);
        }
        
        public function add_data() {
        $data = $_POST;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MasterModel');
         $exists = $this->MasterModel->check_exists($match,$data);
//        exit;
        if ($exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => $heading.' already exists'));
            return;
        }

        // Add the dimondmaster
        $dimondmaster_id = $this->MasterModel->add_data($data,$user_id);

        if ($dimondmaster_id) {
            echo json_encode(array('success' => true, 'message' => $heading.' added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add '.$heading));
        }
        
    }

    public function edit_data() {
        
        $data = $_POST;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MasterModel');
        $exists = $this->MasterModel->check_exists($match,$data);
        if ($exists) {
            echo json_encode(array('success' => false, 'message' => $heading.' already exists'));
            return;
        }
        $this->MasterModel->edit_data($data,$user_id);
        echo json_encode(array('success' => true, 'message' => $heading.' edited successfully'));
    }  

    public function delete_data() {
        $this->load->model('MasterModel');
        $data = $_POST;
        $this->MasterModel->delete_data($data);
        echo json_encode(array('success' => true, 'message' => $data['heading'].' deleted successfully'));
    }

    
}