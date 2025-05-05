<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * User_model class.
 * 
 * @extends CI_Model
 */
class User_model extends CI_Model {

	/**
	 * __construct function.
	 * 
	 * @access public
	 * @return void
	 */
	public function __construct() {
		parent::__construct();
		$this->load->database();
	}
	
	/**
	 * create_user function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $email
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
        public function create_parent_user($fdata,$uid) {
            if($fdata['type']=='2')
	    {      
                
                if($uid=='')
                {    
                    $data = array(
                    'username'   => trim($fdata['username']),
                    'user_step'   => $fdata['step'],
			        'type'   => $fdata['type'],
                    'full_name'      => $fdata['full_name'],
                    'alias_name'      => $fdata['alias_name'],
                    'firebase_token'      => $fdata['firebase_token'],
                    'dob'      => $fdata['dob'],
                    'age'      => $fdata['age'],
                    'social_status'      => '',
                    'created_at' => date('Y-m-j H:i:s'),
                    );
                    $this->db->insert('users', $data);
                    $id=$this->db->insert_id();
                    
                }
                else
                {
                    $data = array(
                    'username'   => trim($fdata['username']),
                    'user_step'   => $fdata['step'],
                    'firebase_token'      => $fdata['firebase_token'],
			        'type'   => $fdata['type'],
                    'full_name'      => $fdata['full_name'],
                    'alias_name'      => $fdata['alias_name'],
                    'dob'      => $fdata['dob'],
                    'age'      => $fdata['age'],
                    'created_at' => date('Y-m-j H:i:s'),
                    );
                    $this->db->where('id', $uid);
                    $this->db->update('users', $data);
                    $id=$uid;
		        }
                
                return $id; 
            }
	}
	
	public function create_user($fdata,$uid) {
            if($fdata['type']=='1')
            {      
                if($uid=='')
                {    
                    $data = array(
			'username'   => trim($fdata['username']),
			'type'   => $fdata['type'],
			'user_step'   => $fdata['step'],
                        'student_id'   => $fdata['student_id'],
			'course'      => $fdata['course'],
			'full_name'      => trim($fdata['full_name']),
			'alias_name'      => $fdata['alias_name'],
			'firebase_token'      => $fdata['firebase_token'],
			'dob'      => $fdata['dob'],
			'age'      => $fdata['age'],
			'social_status'      => '',
			//'password'   => $password,  
			'created_at' => date('Y-m-j H:i:s'),
            'updated_at' => date('Y-m-j H:i:s'),
                    );
                    $this->db->insert('users', $data);
                    $id=$this->db->insert_id();
                    
                }
                else
                {
                    $data = array(
			'username'   => trim($fdata['username']),
			'type'   => $fdata['type'],
			'course'      => $fdata['course'],
                        'firebase_token'      => $fdata['firebase_token'],
			
			'user_step'   => $fdata['step'],
			'full_name'      => trim($fdata['full_name']),
			'alias_name'      => $fdata['alias_name'],
			'dob'      => $fdata['dob'],
			'age'      => $fdata['age'],
			'created_at' => date('Y-m-j H:i:s'),
                    );
                    $this->db->where('id', $uid);
                    $this->db->update('users', $data);
                    $id=$uid;
		}    
		
                
                $this->db->query("delete from user_courses where user_id='$uid'");    
		$courses=explode(",",$fdata['course']);
		foreach($courses as $coursesdata)
		{
		$data = array(
			'user_id'   => $id,
			'course_id'      => $coursesdata,
			'created_at' => date('Y-m-j H:i:s'),
		);
		$this->db->insert('user_courses', $data);    
		}
                return $id; 
		}
		
		
		
	}


	
	/**
	 * resolve_user_login function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @param mixed $password
	 * @return bool true on success, false on failure
	 */
    public function resolve_user_login($username, $password,$type) {
        $password= md5($password);    
        return $select= $this->db->query("SELECT * FROM users WHERE `type`='$type' AND `username`='$username' AND `password`='$password' AND status != '2'")->result_array();
	}
	
	/**
	 * get_user_id_from_username function.
	 * 
	 * @access public
	 * @param mixed $username
	 * @return int the user id
	 */
	public function get_user_id_from_username($username) {
		
		$this->db->select('id');
		$this->db->from('users');
		$this->db->where('username', $username);

		return $this->db->get()->row('id');
		
	}
        
	
	/**
	 * get_user function.
	 * 
	 * @access public
	 * @param mixed $user_id
	 * @return object the user object
	 */
	public function get_user($user_id) {
		
		$this->db->from('users');
		$this->db->where('id', $user_id);
		return $this->db->get()->row();
		
	}
	
	/**
	 * hash_password function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @return string|bool could be a string on success, or bool false on failure
	 */
	private function hash_password($password) {
		
		return password_hash($password, PASSWORD_BCRYPT);
		
	}
	
        
        
function number_to_words($number)
{
    $words = array(
        '0' => 'zero',
        '1' => 'one',
        '2' => 'two',
        '3' => 'three',
        '4' => 'four',
        '5' => 'five',
        '6' => 'six',
        '7' => 'seven',
        '8' => 'eight',
        '9' => 'nine'
    );

    // If the number is in the teens, handle it separately
    $teens = array(
        '11' => 'eleven',
        '12' => 'twelve',
        '13' => 'thirteen',
        '14' => 'fourteen',
        '15' => 'fifteen',
        '16' => 'sixteen',
        '17' => 'seventeen',
        '18' => 'eighteen',
        '19' => 'nineteen'
    );

    // Tens multiples
    $tens = array(
        '10' => 'ten',
        '20' => 'twenty',
        '30' => 'thirty',
        '40' => 'forty',
        '50' => 'fifty',
        '60' => 'sixty',
        '70' => 'seventy',
        '80' => 'eighty',
        '90' => 'ninety'
    );

    // Suffixes for large numbers (thousand, million, billion)
    $suffixes = array('', 'thousand', 'million', 'billion');

    $words_in_number = array();
    $suffix_index = 0;

    // Handle large numbers
    while ($number > 0) {
        $thousands = $number % 1000;

        if ($thousands > 0) {
            if ($thousands == 1 && $suffix_index > 0) {
                $words_in_number[] = $suffixes[$suffix_index - 1];
            } else {
                $words_in_number[] = number_to_words($thousands) . ' ' . $suffixes[$suffix_index];
            }
        }

        $number = floor($number / 1000);
        $suffix_index++;
    }

    // Concatenate the words
    $result = implode(' ', array_reverse($words_in_number));

    return $result;
}
        
        
	/**
	 * verify_password_hash function.
	 * 
	 * @access private
	 * @param mixed $password
	 * @param mixed $hash
	 * @return bool
	 */
	private function verify_password_hash($password, $hash) {
		
		return password_verify($password, $hash);
		
	}
	
}
