<?php
class MetalModel extends CI_Model {
    
    public function add_metal($data, $user_id) {
        $date=date("Y-m-d H:i:s");
//        $data = array(
//            'metal_finish_name' => $metal_name,
//            'user_id' => $user_id,
//            'added_date' => $date,
//        );
        $data2= array_merge($data,$data = array('user_id' => $user_id,'added_date'=>$date));

        $this->db->insert('metals', $data2);
        return $this->db->insert_id();
    } 

    public function check_metal_exists($metal_name) {
        $this->db->where('metal_name', $metal_name);
        $query = $this->db->get('metals');
        return $query->num_rows() > 0;
    }
    public function geallmetal() { 
        $this->db->select('partners_customer.partner_customer_id,metals.id, metals.vendor_id, metals.metal_name, metals.metal_purity, metals.metal_rate, metals.added_date, metals.updated_date, metals.user_id');
        $this->db->from('metals');
        $this->db->join('partners_customer', 'partners_customer.id = metals.vendor_id');
        $this->db->like('metals.metal_name', $searchValue);
        $this->db->order_by($columnIndex, $columnSortDirection);
         $this->db->limit($length, $start);
        $query = $this->db->get();  
        return $query->result_array();
    }

    public function edit_metal($data, $metal_id, $user_id) {
        $date=date("Y-m-d H:i:s");
        $id=$data['id'];        
        $data=array_merge($data,array('updated_date'=>$date));
        
        $this->db->where('id', $id);
        $this->db->update('metals', $data);
    }

    public function delete_metal($metal_id) {
        $this->db->where('id', $metal_id);
        $this->db->delete('metals');
    }  
   
    
    public function fetchmetal($searchValue, $start, $length, $columnIndex, $columnSortDirection) {
        $this->db->select('partners_customer.partner_customer_id,metals.id, metals.vendor_id, metals.metal_name,metal_purity.name,metals.metal_purity,metals.metal_rate, metals.added_date, metals.updated_date, metals.user_id');
        $this->db->from('metals');
        $this->db->join('partners_customer', 'partners_customer.id = metals.vendor_id');
        $this->db->join('metal_purity', 'metal_purity.id = metals.metal_purity');
        $this->db->like('metals.metal_name', $searchValue);
        $this->db->order_by($columnIndex, $columnSortDirection);
         $this->db->limit($length, $start);
        $query = $this->db->get();  
        return $query->result_array();
    }

    public function countAllMetals() {
        // Modify this method to return the total count of metals from your database
        // You can use this method to get the total count for pagination

        // Example code to count metals from a hypothetical 'metals' table
        return $this->db->count_all('metals');
    }

    public function countFilteredMetals($searchValue) {
        // Modify this method to return the count of filtered metals from your database
        // You can use this method to get the count of filtered metals for pagination

        // Example code to count filtered metals from a hypothetical 'metals' table
        $this->db->like('metal_name', $searchValue);
        return $this->db->count_all_results('metals');
    }
    
    
    

}

