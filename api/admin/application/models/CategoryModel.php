<?php
class CategoryModel extends CI_Model {
    
    public function add_category($category_name, $user_id) {
        $date=date("Y-m-d");
        $data = array(
            'category_name' => $category_name,
            'user_id' => $user_id,
            'added_date' => $date,
        );

        $this->db->insert('categories', $data);
        return $this->db->insert_id();
    }

    public function check_category_exists($category_name) {
        $this->db->where('category_name', $category_name);
        $query = $this->db->get('categories');
        return $query->num_rows() > 0;
    }
    public function geallcategory() {
        $this->db->select('*');
        $this->db->from('categories');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function edit_category($category_id, $category_name, $user_id) {
        $data = array(
            'category_name' => $category_name,
            'user_id' => $user_id
        );

        $this->db->where('id', $category_id);
        $this->db->update('categories', $data);
    }

    public function delete_category($category_id) {
        $this->db->where('id', $category_id);
        $this->db->delete('categories');
    }

   
    
    public function fetchCategories($searchValue, $start, $length, $columnIndex, $columnSortDirection) {
        // Modify this method to fetch categories from your database based on the search, pagination, sorting, and limit parameters
        // You can use the $searchValue, $start, $length, $columnIndex, and $columnSortDirection values in your database query

        // Example code to fetch categories from a hypothetical 'categories' table
        $this->db->select('*');
        $this->db->from('categories');
        $this->db->like('category_name', $searchValue);
        $this->db->order_by($columnIndex, $columnSortDirection);
        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countAllCategories() {
        // Modify this method to return the total count of categories from your database
        // You can use this method to get the total count for pagination

        // Example code to count categories from a hypothetical 'categories' table
        return $this->db->count_all('categories');
    }

    public function countFilteredCategories($searchValue) {
        // Modify this method to return the count of filtered categories from your database
        // You can use this method to get the count of filtered categories for pagination

        // Example code to count filtered categories from a hypothetical 'categories' table
        $this->db->like('category_name', $searchValue);
        return $this->db->count_all_results('categories');
    }
    
    
    

}

