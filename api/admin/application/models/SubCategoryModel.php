<?php
class SubCategoryModel extends CI_Model {
    
    public function add_subcategory($category_name,$sub_category_name, $user_id) 
    {
        $date=date("Y-m-d");
        $data = array(
            'subcategory_name' => ucfirst($sub_category_name),
            'category_id' => ucfirst($category_name),
            'user_id' => $user_id,
            'added_date' => $date,
        );

        $this->db->insert('subcategories', $data);
        return $this->db->insert_id();
    }

    public function check_category_exists($category_name,$sub_category_name) {
        
        $conditions = array(
            'subcategory_name' => ucfirst($category_name),
            'category_id' => ucfirst($sub_category_name)
        );
        $this->db->where($conditions);
        $query = $this->db->get('subcategories');
        return $query->num_rows() > 0;
    }

    public function edit_subcategory($edit_category_name, $sub_category_id, $edit_sub_category_name) {
        $date=date("Y-m-d H:i:s");
        $data = array(
            'subcategory_name' => $edit_sub_category_name,
            'category_id' => $edit_category_name,
            'updated_date' => $date,
            'user_id' => $user_id
        );
        $this->db->where('id', $sub_category_id);
        $this->db->update('subcategories', $data);
    }

    public function delete_subcategory($category_id) {
        $this->db->where('id', $category_id);
        $this->db->delete('subcategories');
    }

    public function fetchCategories($searchValue, $start, $length, $columnIndex, $columnSortDirection) {
        // Modify this method to fetch categories from your database based on the search, pagination, sorting, and limit parameters
        // You can use the $searchValue, $start, $length, $columnIndex, and $columnSortDirection values in your database query

        // Example code to fetch categories from a hypothetical 'categories' table
        $this->db->select('subcategories.id, subcategories.subcategory_name, categories.category_name,subcategories.category_id, subcategories.added_date, subcategories.updated_date');
        $this->db->from('subcategories');
        $this->db->join('categories', 'subcategories.category_id = categories.id');
        $this->db->like('subcategory_name', $searchValue);
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

