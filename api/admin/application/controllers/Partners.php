<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Partners extends CI_Controller {

     public function index($id)
	{
         
                $data['type']=$id;
//                echo $id;
//                exit;
                if($id=='partner')
                {    
                $data['title']="Partners";
                $data['title2']="Partners List";
                } 
                if($id=='customer')
                {    
                $data['title']="Customer";
                $data['title2']="Customers List";
                }
                $this->load->model('CategoryModel');
                
                $data['all_category']=$this->CategoryModel->geallcategory();
                
		$this->load->view('masters/partners/mainpartners',$data);
	}
        
        function select_typewise_id()
        {  
        ($type= $this->input->post('type'));    
        
        $count= $this->db->query("select count(*) as cnt from partners_customer where partner_customer_type='$type'")->result_array();
              
//        print_r($count);       
        
        if($type=='1')
        { 
        $cnt=$count[0]['cnt']+1;    
        echo $cnt;
//        print_r($count);
        }   
        
        else if($type=='2')
        { 
        $cnt=$count[0]['cnt']+1;    
        echo $cnt;
//        print_r($count);
        }
        }
        
        public function add_partner() {
        
                    $uploaddir="/public_html/cms/uploads/partnercustomers/";
                    $tmp_name = $_FILES["photo"]["tmp_name"];
                    $filename = $_FILES['photo']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $name2 = $type . date('ymdHis') . '.' . $ext;
                    $uploadfile = $uploaddir . basename($name2);
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
                             $upload_file=$name2;
                    } else {
                        $upload_file='';
//                             $message = 'File Upload Failed Try Again!';
                    }    
//                    echo $upload_file;
//                    
//                    exit;
                    $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
                    $this->load->model('PartnersModel');

                    $category_id = $this->PartnersModel->add_partners($_POST,$upload_file,$user_id);


                    if ($category_id) { 
                        echo json_encode(array('success' => true, 'message' => 'Details added successfully'));
                    } else {
                        echo json_encode(array('success' => false, 'message' => 'Failed to add sub category'));
                    }
    }

    public function edit_partner() {
        
                    if($_FILES['photo']['name']!='')
                    {    
                    $uploaddir="/public_html/cms/uploads/partnercustomers/";
                    $tmp_name = $_FILES["photo"]["tmp_name"];
                    $filename = $_FILES['photo']['name'];
                    $ext = pathinfo($filename, PATHINFO_EXTENSION);
                    $name2 = $type . date('ymdHis') . '.' . $ext;
                    $uploadfile = $uploaddir . basename($name2);
                    if (move_uploaded_file($_FILES['photo']['tmp_name'], $uploadfile)) {
                             $upload_file=$name2;
                    } else {
                        $upload_file='';
                    }   
                    }  
                    $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
                    $this->load->model('PartnersModel');
                    $category_id = $this->PartnersModel->edit_partners($_POST,$upload_file,$user_id);
                    echo json_encode(array('success' => true, 'message' => 'Details updated successfully'));
    }

    public function delete_partners() {
        $this->load->model('PartnersModel');
        $category_id=$_POST['id'];
        $type=$_POST['type'];
        $this->PartnersModel->delete_partners($category_id,$type);
        if($type==1)
        {
        $type="Partner";    
        }    
        else
        {
        $type="Customer";    
        }
        
        echo json_encode(array('success' => true, 'message' => $type.' deleted successfully'));
    }
    
    function get_partners_details()
    {
//        print_r($_POST);
        
        $id=$_POST['id'];
        $data= $this->db->query("select * from partners_customer where id='$id'")->row_array();
        echo json_encode($data);
        
//        return $data; 
        
        
    }
    

    public function fetch_data() { 
        
        $type=$_POST['datatype'];
        if($type=='partner')
        {
        $type="1";    
        }    
        else
        {
        $type="2";    
        }    
//        echo $type;
        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        
//        log_message('debug', 'Custom log message: ');

        
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];
        $this->load->model('PartnersModel');
        $data = $this->PartnersModel->fetchPartners($searchValue, $start, $length, $columnIndex, $columnSortDirection,$type);
        $response = array(   
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $this->PartnersModel->countAllPartners(),
            'recordsFiltered' => $this->PartnersModel->countFilteredPartners($searchValue),
            'data' => $data
        );

        echo json_encode($response);
         
        
        
    }




        
        
        
    
}