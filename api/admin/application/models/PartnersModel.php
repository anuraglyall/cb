<?php
class PartnersModel extends CI_Model {
    
    public function add_partners($data,$uploadfile,$user_id) 
    {
        $date=date("Y-m-d H:i:s");
        $final=array_merge($data,array("user_id"=>$user_id,"added_date"=>$date));
        $partner_type=$final['partner_type'];
        $partner_id=$final['partner_id'];        
        $partner_reference=$final['partner_reference'];        
        unset($final['partner_type']);
        $final=array_merge($final,array("partner_customer_type"=>$partner_type));
        unset($final['partner_id']);
        $final=array_merge($final,array("partner_customer_id"=>$partner_id));
        $final=array_merge($final,array("status"=>'1'));
        
        unset($final['partner_reference']);
        $final=array_merge($final,array("partner_customer_reference"=>$partner_reference,"upload_photo"=>$uploadfile));
        $this->db->insert('partners_customer', $final);
        return $this->db->insert_id();
    }
    public function edit_partners($data,$uploadfile,$user_id) 
    {
        $date=date("Y-m-d H:i:s");
        $final=array_merge($data,array("user_id"=>$user_id,"updated_date"=>$date));
        $partner_type=$final['partner_type'];
        $partner_id=$final['partner_id'];        
        $partner_reference=$final['partner_reference'];        
        unset($final['partner_type']);
        $final=array_merge($final,array("partner_customer_type"=>$partner_type));
        unset($final['partner_id']);
        $final=array_merge($final,array("partner_customer_id"=>$partner_id));
        $final=array_merge($final,array("status"=>'1'));
        unset($final['partner_reference']);
        $final=array_merge($final,array("partner_customer_reference"=>$partner_reference,"upload_photo"=>$uploadfile));
        $conditions = array(
            'partner_customer_id' => $partner_id
        );
        $this->db->where($conditions);
        $this->db->update('partners_customer', $final);
        return $this->db->affected_rows();
    }
    
    public function check_category_exists($category_name,$sub_category_name) {
        
        $conditions = array(
            'subcategory_name' => $category_name,
            'category_id' => $sub_category_name
        );
        $this->db->where($conditions);
        $query = $this->db->get('subcategories');
        return $query->num_rows() > 0;
    }

   

    public function delete_partners($category_id) {
        $date=date("Y-m-d H:i:s");
        $this->db->where('id', $category_id);
        $data = array(
            'status' => '2',
            'updated_date' =>$date
        );
        $this->db->update('partners_customer',$data);
    }

   
    
    public function fetchPartners($searchValue, $start, $length, $columnIndex, $columnSortDirection,$type) 
    {
        // Modify this method to fetch categories from your database based on the search, pagination, sorting, and limit parameters
        // You can use the $searchValue, $start, $length, $columnIndex, and $columnSortDirection values in your database query

        // Example code to fetch categories from a hypothetical 'categories' table
        $this->db->select('*');
        $this->db->from('partners_customer');
//        $this->db->join('categories', 'subcategories.category_id = categories.id');
        $this->db->where('partners_customer.partner_customer_type', $type);
        $this->db->where('partners_customer.status', '1');
        $this->db->order_by($columnIndex, $columnSortDirection);
        $this->db->limit($length, $start);
        $query = $this->db->get();   
        return $query->result_array();
    }

    public function countAllPartners() {
        // Modify this method to return the total count of categories from your database
        // You can use this method to get the total count for pagination

        // Example code to count categories from a hypothetical 'categories' table
        return $this->db->count_all('partners_customer');
    }

    public function countFilteredPartners($searchValue) {
        // Modify this method to return the count of filtered categories from your database
        // You can use this method to get the count of filtered categories for pagination

        // Example code to count filtered categories from a hypothetical 'categories' table
//        $this->db->like('category_name', $searchValue);
        return $this->db->count_all_results('partners_customer');
    }
    
    
    

}

