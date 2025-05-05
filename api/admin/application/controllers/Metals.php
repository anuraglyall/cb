<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Metals extends CI_Controller {

        public function index()
	{
                $data['title']="Metals";
                $data['title2']="Metals List";
                $data['vendor']= $this->db->query("SELECT id,partner_customer_id FROM `partners_customer` "
                        . "where partner_customer_type='1'")->result_array();
                $data['metal_purity']= $this->db->query("SELECT * FROM `metal_purity` "
                        . "")->result_array();
		$this->load->view('masters/metals/mainmetal',$data);
	}
          
        
        public function add_metals() {
        $data = $_POST;
        $metal_name = $_POST['metal_name'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MetalModel');
        $metal_exists = $this->MetalModel->check_metal_exists($metal_name);

        if ($metal_exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Metal already exists'));
            return;
        }

        // Add the metal
        $metal_id = $this->MetalModel->add_metal($data, $user_id);

        if ($metal_id) {
            echo json_encode(array('success' => true, 'message' => 'Metal added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add metal'));
        }
        
    }

    public function edit_metal() {
        
        $metal_name = $this->input->post('metal_name');
        $data = $_POST;
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the metal already exists
        $this->load->model('MetalModel');
        $metal_exists = $this->MetalModel->check_metal_exists($metal_name);

        if ($metal_exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Metal already exists'));
            return;
        }
        // Edit the metal
        $this->MetalModel->edit_metal($data, $metal_name, $user_id);

        echo json_encode(array('success' => true, 'message' => 'Metal edited successfully'));
    
        
    }
    
    
    
    

    public function delete_metal() {
        $this->load->model('MetalModel');
        $metal_id=$_POST['id'];
        $this->MetalModel->delete_metal($metal_id);

        echo json_encode(array('success' => true, 'message' => 'Metal deleted successfully'));
    }

    public function fetch_data() { 
        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];

        // Load the Metal model
        $this->load->model('MetalModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->MetalModel->fetchmetal($searchValue, $start, $length, $columnIndex, $columnSortDirection);

        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $this->MetalModel->countAllMetals(),
            'recordsFiltered' => $this->MetalModel->countFilteredMetals($searchValue),
            'data' => $data
        );

        echo json_encode($response);
         
        
        
    }
}