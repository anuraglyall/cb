<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Subcategory extends CI_Controller {

     public function index()
	{
                $data['title']="Sub Category";
                $data['title2']="Sub Category List";
                $this->load->model('CategoryModel');
                
                $data['all_category']=$this->CategoryModel->geallcategory();
                
		$this->load->view('masters/category/mainsubcategory',$data);
	}
        
        
        
        
        
        public function add_subcategory() {
        $category_name = $this->input->post('category_name');
        $sub_category_name = $this->input->post('sub_category_name');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the category already exists
        $this->load->model('SubCategoryModel');
        $category_exists = $this->SubCategoryModel->check_category_exists($category_name,$sub_category_name);

        if ($category_exists) {
            // Category already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Subcategory already exists'));
            return;
        }

        // Add the category
        $category_id = $this->SubCategoryModel->add_subcategory($category_name,$sub_category_name, $user_id);

        if ($category_id) {
            echo json_encode(array('success' => true, 'message' => 'Sub Category added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add sub category'));
        }
    }

    public function edit_subcategory() {
        $edit_category_name= $this->input->post('edit_category_name');
        $sub_category_id= $this->input->post('sub_category_id');
        $edit_sub_category_name= $this->input->post('edit_sub_category_name');
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        // Check if the category already exists
        $this->load->model('SubCategoryModel');
        $category_exists = $this->SubCategoryModel->check_category_exists($edit_category_name,$edit_sub_category_name);

        if ($category_exists) {
            // Category already exists, show an error message
            echo json_encode(array('success' => false, 'message' => 'Sub category already exists'));
            return;
        }
        // Edit the category
        $this->SubCategoryModel->edit_subcategory($edit_category_name, $sub_category_id, $edit_sub_category_name);

        echo json_encode(array('success' => true, 'message' => 'Category edited successfully'));
    }

    public function delete_category() {
        $this->load->model('SubCategoryModel');
        $category_id=$_POST['id'];
        $this->SubCategoryModel->delete_subcategory($category_id);

        echo json_encode(array('success' => true, 'message' => 'Sub category deleted successfully'));
    }

    public function fetch_data() { 
        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];

        // Load the Category model
        $this->load->model('SubCategoryModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->SubCategoryModel->fetchCategories($searchValue, $start, $length, $columnIndex, $columnSortDirection);

        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $this->SubCategoryModel->countAllCategories(),
            'recordsFiltered' => $this->SubCategoryModel->countFilteredCategories($searchValue),
            'data' => $data
        );

        echo json_encode($response);
         
        
        
    }




        
        
        
    
}