<?php
class MetalFinishesModel extends CI_Model {
    
    public function add_metal($metal_name, $user_id) {
        $date=date("Y-m-d H:i:s");
        $data = array(
            'metal_finish_name' => $metal_name,
            'user_id' => $user_id,
            'added_date' => $date,
        );

        $this->db->insert('metal_finishes', $data);
        return $this->db->insert_id();
    } 

    public function check_metal_exists($metal_name) {
        $this->db->where('metal_finish_name', $metal_name);
        $query = $this->db->get('metal_finishes');
        return $query->num_rows() > 0;
    }
    public function geallmetal() {
        $this->db->select('*');
        $this->db->from('metal_finishes');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function edit_metal($metal_id, $metal_name, $user_id) {
        $date=date("Y-m-d H:i:s");
        $data = array(
            'metal_finish_name'=>$metal_name,
            'updated_date'=>$date,
            'user_id'=>$user_id
        );

        $this->db->where('id', $metal_id);
        $this->db->update('metal_finishes', $data);
    }

    public function delete_metal($metal_id) {
        $this->db->where('id', $metal_id);
        $this->db->delete('metal_finishes');
    }  
   
    
    public function fetchmetalfinishes($searchValue, $start, $length, $columnIndex, $columnSortDirection) {
        // Modify this method to fetch metals from your database based on the search, pagination, sorting, and limit parameters
        // You can use the $searchValue, $start, $length, $columnIndex, and $columnSortDirection values in your database query

        // Example code to fetch metals from a hypothetical 'metals' table
        $this->db->select('*');
        $this->db->from('metal_finishes');
        $this->db->order_by($columnIndex, $columnSortDirection);
        $this->db->limit($length, $start);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function countAllMetals() {
        // Modify this method to return the total count of metals from your database
        // You can use this method to get the total count for pagination

        // Example code to count metals from a hypothetical 'metals' table
        return $this->db->count_all('metal_finishes');
    }

    public function countFilteredMetals($searchValue) {
        // Modify this method to return the count of filtered metals from your database
        // You can use this method to get the count of filtered metals for pagination

        // Example code to count filtered metals from a hypothetical 'metals' table
        $this->db->like('metal_finish_name', $searchValue);
        return $this->db->count_all_results('metal_finishes');
    }
    
    
    

}

