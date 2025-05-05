<?php

class User_model extends CI_Model {
    public function authenticate($username, $password) {
        // TODO: Implement your database checking logic to authenticate the user
        // Example code assuming you have a 'users' table with 'username' and 'password' fields

        $query = $this->db->get_where('users', array('username' => $username, 'password' => md5($password)));

        if ($query->num_rows() === 1) {
            
            $final=$query->row_array();
            
            print_r($final);
            
            $ip_address=$this->input->ip_address();
            $screen_resolution = $this->input->server('HTTP_USER_AGENT');
            $device = $this->input->server('HTTP_USER_AGENT');
            $browser = $this->input->server('HTTP_USER_AGENT');
            
            $log_data = array(
            'user_id' => $final['id'],
            'timestamp' => date('Y-m-d H:i:s'),
            'ip_address' => $ip_address,
            'screen_resolution' => $screen_resolution,
            'device' => $device,
            'browser' => $browser
            );

            $this->db->insert('logs', $log_data);
            
            return $query->row_array();
            
        } 

        return false;
    }
}