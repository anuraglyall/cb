<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Metalfinishes extends CI_Controller {

        public function index()
	{
                $data['title']="Metal Finishes";
                $data['title2']="Metal Finishes List";
		$this->load->view('masters/metal/mainmetal',$data);
	}
          
        
        public function add_metalfinishes() {
        $metal_name = $this->input->post('metal_finish_name');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MetalFinishesModel');
        $metal_exists = $this->MetalFinishesModel->check_metal_exists($metal_name);

        if ($metal_exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Metal already exists'));
            return;
        }

        // Add the metal
        $metal_id = $this->MetalFinishesModel->add_metal($metal_name, $user_id);

        if ($metal_id) {
            echo json_encode(array('success' => true, 'message' => 'Metal added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add metal'));
        }
        
    }

    public function edit_metal() {
        
        $metal_name = $this->input->post('metal_finish_name');
        $metal_id = $this->input->post('id');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the metal already exists
        $this->load->model('MetalFinishesModel');
        $metal_exists = $this->MetalFinishesModel->check_metal_exists($metal_name);

        if ($metal_exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Metal already exists'));
            return;
        }
        // Edit the metal
        $this->MetalFinishesModel->edit_metal($metal_id, $metal_name, $user_id);

        echo json_encode(array('success' => true, 'message' => 'Metal edited successfully'));
    
        
    }
    
    
    
    

    public function delete_metal() {
        $this->load->model('MetalFinishesModel');
        $metal_id=$_POST['id'];
        $this->MetalFinishesModel->delete_metal($metal_id);

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
        $this->load->model('MetalFinishesModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->MetalFinishesModel->fetchmetalfinishes($searchValue, $start, $length, $columnIndex, $columnSortDirection);

        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $this->MetalFinishesModel->countAllMetals(),
            'recordsFiltered' => $this->MetalFinishesModel->countFilteredMetals($searchValue),
            'data' => $data
        );

        echo json_encode($response);
         
        
        
    }
}