<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends CI_Controller {

        public function index()
	{
                $data['title']="Category";
		$this->load->view('masters/category/maincategory',$data);
	}
        
        
        public function add_category() {
            
            
        $category_name = $this->input->post('category_name');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the category already exists
        $this->load->model('CategoryModel');
        $category_exists = $this->CategoryModel->check_category_exists($category_name);

        if ($category_exists) {
            // Category already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Category already exists'));
            return;
        }

        // Add the category
        $category_id = $this->CategoryModel->add_category($category_name, $user_id);

        if ($category_id) {
            echo json_encode(array('success' => true, 'message' => 'Category added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add category'));
        }
        
    }

    public function edit_category() {
        
        $category_name = $this->input->post('category_name');
        $category_id = $this->input->post('category_id');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the category already exists
        $this->load->model('CategoryModel');
        $category_exists = $this->CategoryModel->check_category_exists($category_name);

        if ($category_exists) {
            // Category already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Category already exists'));
            return;
        }
        // Edit the category
        $this->CategoryModel->edit_category($category_id, $category_name, $user_id);

        echo json_encode(array('success' => true, 'message' => 'Category edited successfully'));
    
        
    }
    
    
    
    

    public function delete_category() {  
        $this->load->model('CategoryModel');
        $category_id=$_POST['id'];
        $this->CategoryModel->delete_category($category_id);

        echo json_encode(array('success' => true, 'message' => 'Category deleted successfully'));
    }

    public function fetch_data() { 
        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];

        // Load the Category model
        $this->load->model('CategoryModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->CategoryModel->fetchCategories($searchValue, $start, $length, $columnIndex, $columnSortDirection);

        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $this->CategoryModel->countAllCategories(),
            'recordsFiltered' => $this->CategoryModel->countFilteredCategories($searchValue),
            'data' => $data
        );

        echo json_encode($response);
         
        
        
    }

    
}