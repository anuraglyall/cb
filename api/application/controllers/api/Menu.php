<?php
defined('BASEPATH') OR exit('No direct script access allowed');
require(APPPATH.'/libraries/REST_Controller.php');
use Restserver\Libraries\REST_Controller;
// not used by cs soft development
class Menu extends REST_Controller {

	public function __construct() {
	    parent::__construct();
        $this->load->library('Authorization_Token');
        $this->load->model('user_model');
	}
        
    public function parent_profile_update_post() {
        $data = $_POST;
        $profileimage = $_FILES;
        
        $headers = $this->input->request_headers();
        if (empty($headers['Authorization'])) {
            $final['status'] = false;
            $final['message'] = 'Authorization token Cannot be Blank';
            $this->response($final, REST_Controller::HTTP_OK);
        } 
        if (isset($headers['Authorization'])) {
        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
        if ($decodedToken['status']) {     
            $uid=$data['id'];
            $full_name = $data['full_name'];
            $username = $data['username'];
            $alias_name = $data['alias_name'];
            $type = $data['type'];
            $address = !empty($data['address']) ? $data['address'] : '' ;
            $city =  !empty($data['city']) ? $data['city'] : 0 ; 
            $state= !empty($data['state']) ? $data['state'] : 0;  
            $country=  !empty($data['country']) ? $data['country'] : 0;  
            $iso_code = $data['iso_code'];
            $country_code = $data['country_code']; 
            $contact_number = $data['contact_number']; 
            $users = $this->db->query("select * from users where contact_number = '$contact_number' and status != '2'")->result_array();
       
            if(count($users) ===0 ) {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'This Contact Number Is Not Linked With Any Parent Account And Already Taken By A Child Account!';
                $this->response($final, REST_Controller::HTTP_OK);
            }  

            $image_query = $this->db->query("select image from users where id = '$uid'")->row_array();
        
            if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) {   
                $targetDir = "uploads/parentprofile/"; // Your target directory where images will be saved
                $uniqueFolderName = $last_insert_id; // Generate a unique folder name
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $tmpFilePath = $profileimage["image"]["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $profileimage["image"]["name"];
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $main_files .= base_url() . '' . $filePath ;
                        }
                    }
            }
            if(empty($main_files)){
                $main_image= $image_query['image'];
            }else{
                $main_image= $main_files;
            }

            if($type=='2'){
                $check=$this->db->query("update users set full_name='$full_name',country='$country',username = '$username',alias_name='$alias_name', image= '$main_image',address='$address',city='$city',state='$state',contact_number = '$contact_number', iso_code = '$iso_code', country_code = '$country_code' where id='$uid' ");
            }

            if($this->db->affected_rows() > 0){
                $final = array();
                $final['access_token'] = $headers['Authorization'];
                $final['status'] = true;
                $final['uid'] = $uid;
                $final['message'] = 'Profile Updated Successfully!';
                $this->response($final, REST_Controller::HTTP_OK);  
            }else{
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Profile Already Updated!';
                $this->response($final, REST_Controller::HTTP_OK);  
            }        
        }else{
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Authorization Token Expired';
            $this->response($final, REST_Controller::HTTP_OK);     
        } 
    }  
}

// child profile update function
       
public function child_profile_update_post() {
    $data = $_POST;
    $profileimage = $_FILES;
    $headers = $this->input->request_headers();
    if (empty($headers['Authorization'])) {
        $final['status'] = false;
        $final['message'] = 'Authorization token Cannot be Blank';
        $this->response($final, REST_Controller::HTTP_OK);
    }
    if (isset($headers['Authorization'])) {
        $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);
        if ($decodedToken['status']) {    
            $uid = $data['id'];
            $full_name = $data['full_name'];
            $type = $data['type'];
            $alias_name = $data['alias_name'];
            $country= !empty($data['country']) ? $data['country']: 0;
            $address = !empty($data['address']) ? $data['address']: '' ; 
            $city =  !empty($data['city']) ? $data['city']: 0;  
            $state =  !empty($data['state']) ? $data['state']: 0; 
            $iso_code = $data['iso_code'];
            $country_code = $data['country_code']; 
            $contact_number = $data['contact_number'];
            $race = $data['race'];
            $dialect = $data['dialect'];
            $religion = $data['religion'];
            $nationality = $data['nationality'];
            $nric = $data['nric'];
            $emergency_name = $data['emergency_name'];
            $emergency_relationship = $data['emergency_relationship'];
            $e_iso_code = $data['e_iso_code'];
            $e_country_code = $data['e_country_code']; 
            $emergency_number = $data['emergency_number']; 
            $emergency_email = $data['emergency_email'];
            $image_query = $this->db->query("select image from users where id = '$uid' and status != '2'")->row_array();
        
       
            if(!$this->isAllowedChildAccountNumber($contact_number)) {
                $final = array();
                $final['status'] = false;
                $final['message'] = 'This Contact Number Is Not Linked With Any Parent Account And Already Taken By A Child Account!';
                $this->response($final, REST_Controller::HTTP_OK);
            }
                        
            if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) {   
                $targetDir = "uploads/childprofile/"; // Your target directory where images will be saved
                $uniqueFolderName = $last_insert_id; // Generate a unique folder name
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $tmpFilePath = $profileimage["image"]["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = '';
                    $fileName = $profileimage["image"]["name"];
                    $filePath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $main_files .= base_url() . '' . $filePath ;
                    }
                }
            }
        
            if(empty($main_files)){
                $main_image = $image_query['image'];
            }else{
                $main_image = $main_files;
            }
        
            if($type == '1') {
                $this->db->query("update users set full_name='$full_name',image='$main_image',country= '$country',alias_name='$alias_name',address='$address',city='$city',state='$state', contact_number='$contact_number',
                iso_code = '$iso_code',country_code ='$country_code', race='$race',dialect='$dialect',religion='$religion',nationlity='$nationality',nric='$nric',emergency_name='$emergency_name',emergency_number='$emergency_number',
                emergency_relationship='$emergency_relationship', emergency_email='$emergency_email', e_iso_code='$e_iso_code', e_country_code='$e_country_code' where id='$uid' "); 
            }

            if ($this->db->affected_rows() > 0) {				
                $final = array();         
                $final['access_token'] = $headers['Authorization'];
                $final['uid'] = $uid;
                $final['status'] = true;
                $final['message'] = 'Profile Updated Successfully!';
                $this->response($final, REST_Controller::HTTP_OK);  
            }else{
                $final = array();
                $final['status'] = true;
                $final['message'] = 'Profile Already Updated!';
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


private function isAllowedChildAccountNumber($number) {
    $users = $this->db->query("select * from users where contact_number = '$number' and status != '2'")->result_array();
    if(count($users) === 0) return true;

    $isParentAccount = array_filter($users, function($user) { return $user['type'] == 2; });
    return count($isParentAccount) > 0 ? true : false;
}

public function change_password_post() {
    $data=$_POST;
    $headers = $this->input->request_headers(); 

    if (empty($headers['Authorization'])) {
        $final['status'] = false;
        $final['message'] = 'Authorization token Cannot be Blank';
        $this->response($final, REST_Controller::HTTP_OK);
    }
    if (isset($headers['Authorization'])) {
    $decodedToken = $this->authorization_token->validateToken($headers['Authorization']);

    if ($decodedToken['status'])
    {     
        $new_password=$data['password'];
        $current_password=$data['current_password'];
        $uid=$data['id'];
        $confirm_new_password=$data['confirm_password'];
        $check_password = $this->db->query("select password from users where id = '$uid'")->row_array();

     if( md5($current_password) != $check_password['password']   ){
         $final['status']= false;
         $final['message'] = 'Current Password Is Wrong,Please Check Data Again!';
         $this->response($final, REST_Controller::HTTP_OK);
    }
    if($new_password==$confirm_new_password) {
        unset($data['id']);                       
        unset($data['current_password']);
        unset($data['password']);                       
        unset($data['confirm_password']);
        $data=array_merge($data,array("password"=>md5($new_password)));
         
        $this->db->where("id",$uid);
        $this->db->update("users",$data);
        
        $final = array();
        $final['access_token'] = $headers['Authorization'];
        $final['status'] = true;
        $final['uid'] = $uid;
        $final['message'] = 'Password Changed Successfully!';
        $this->response($final, REST_Controller::HTTP_OK);  
        }
        else{
                $final = array();
                $final['status'] = false;
                $final['message'] = 'Password & Confirm password can"t Match!';
                $this->response($final, REST_Controller::HTTP_OK);  
        }
    }

    }
    else
    {
            $final = array();
            $final['status'] = false;
            $final['message'] = 'Authorization Token Expired';
            $this->response($final, REST_Controller::HTTP_OK);     
    }        
    
}

    public function contact_us_post() {
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
                $uid = $data['id'];
                $email = $data['email'];
                $subject = ucfirst($data['subject']);
                $question = ucfirst($data['question']);
                if(!$uid) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'User Id Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$email) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Email Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$subject) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Subject Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }
                if(!$question) {
                    $final = array();
                    $final['status'] = false;
                    $final['message'] = 'Question Not Found, Please Check Data Again!';
                    $this->response($final, REST_Controller::HTTP_OK);
                }

                $uploadedFileName = '';
                if (isset($_FILES['document_upload']) && !empty($_FILES['document_upload']['name'])) {   
                    $targetDir = "uploads/contactUs/";                   
                    if (!file_exists($targetDir)) mkdir($targetDir, 0777, true);                    
                
                    $tmpFilePath = $_FILES['document_upload']["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $_FILES['document_upload']["name"];
                        $uniqueFileName = rand() . '-' . $fileName;
                        $filePath = $targetDir . $uniqueFileName;
                        
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $uploadedFileName = base_url().$filePath;
                        }                           
                    }
                }

                $check = $this->db->query("insert into contact_us (email,document_upload,subject,question,user_id,added_date,updated_date) values('$email','$uploadedFileName','$subject','$question',$uid,now(),now())");
                if($this->db->affected_rows() > 0){
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Message Sent Successfully!';
                    $this->response($final, REST_Controller::HTTP_OK);     
                }else{
                    $final = array();
                    $final['status'] = true;
                    $final['message'] = 'Message Sent Failed!';
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
}
