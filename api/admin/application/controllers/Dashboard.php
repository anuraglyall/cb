<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function __construct()
	{
	  parent::__construct();
	  $this->load->library('session');
	}
	public function index()
	{   
		if (empty($this->session->userdata('id'))) {
			$this->load->view('content/login');
		} else {
			//$this->load->view('dashboard/index');
			$this->load->view('masters/default/master/default');
		}
	}

	//download qr code
	public function download_qr_code($id) {
		$class_id = $id;
		//date_default_timezone_set('Asia/Kolkata');
		$date = date("m/d/Y H:i:s");
		$updatedDate = date("Y-m-d H:i:s");
		require_once(APPPATH . 'libraries/qrcode/qrlib.php');
		$unique_id = uniqid()."-".$class_id."@".$date; 
		$path = 'uploads/qrcode/';
		$file =  $path . "qr". ".png";
		$ecc = 'L';
		$pixel_Size = 10;
		$frame_Size = 10;
		QRcode::png($unique_id, $file, $ecc, $pixel_Size, $frame_Size);
 
		$data =  array('qr' => $unique_id, 'added_date' => $updatedDate, 'updated_date' => $updatedDate);
		$this->db->insert('download_qr', $data);
		$file_name = base_url().$file;
		header('Content-Description: File Transfer');
		header('Content-Type: application/octet-stream');
		header('Content-Disposition: attachment; filename="qrcode.png"');
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
		header('Pragma: public');
		header('Content-Length: ' . filesize($file_name)); //Absolute URL
		ob_clean();
		flush();
		readfile($file_name); //Absolute URL
		exit();
	}

	public function getAttendanceList() {
		$class_id = $this->input->post('class_id');
		$attendance = $this->db->query("select a.*,c.username,b1.class_name from check_in a
		left outer join upcoming_classes b on b.id = a.upcoming_id
		left outer join master_classes b1 on b1.id = a.master_class_id
		left outer join users c on c.id = a.user_id
		where a.status = '0' and b.status != '2' and c.status != '2' and a.upcoming_id = '$class_id'")->result_array();
		echo json_encode($attendance);
	}    

	public function checkQuizTypeByQuizId() {
		$quizId = (int) $this->input->post('quizId');
		$quizOptionId = (int) $this->input->post('quizOptionId');
		$row = $this->db->query("select * from quiz_options where quiz_id = '$quizId' and status != '2'")->row_array();
		if(!empty($row['id'])){
			$quizOptionId = $row['id'];
		}
		if($quizOptionId) {
			$response = $this->db->query("select c.id,b.id as quizId, a.name,c.name as optionName, c.pair as optionPair, c.ans_name as optionAnswer from quiz_type a left outer join quiz b on a.id = b.select_type
			left outer join quiz_options c on c.quiz_id = b.id where b.id = '$quizId'  and c.id = '$quizOptionId' and a.status != '2' and b.status != '2'")->result_array();
			$response[0]['optionAnswer'] = json_encode(unserialize($response[0]['optionAnswer']));
		}else {
			$response = $this->db->query("select a.name from quiz_type a 
        	left outer join quiz b on a.id = b.select_type where b.id = '$quizId' and a.status != '2' and b.status != '2'")->result_array();
		}
        echo json_encode($response);
    }

	public function getQuizAllOptionsByQuizId() {
		$quizId = (int) $this->input->post('quizId');
        $response = $this->db->query("select a.*,b.name as question,c.name as quizTypeName from quiz_options a left outer join quiz b on b.id = a.quiz_id left outer join quiz_type c on c.id = b.select_type where a.quiz_id = '$quizId' and a.status != '2'")->result_array();
		if($response[0]['quizTypeName'] == 'pairs'){
			$response[0]['ans_name'] = json_encode(unserialize($response[0]['ans_name']));
		}
		
		echo json_encode($response);
	}

	public function getSubmittedQuiz() {
		$user_id = $_POST['user_id'];
		$getSubmittedQuiz  = $this->db->query("select DISTINCT b.id, b.name from child_quiz_answer a
						left outer join main_quiz b on a.main_quiz_id = b.id
						where a.user_id = '$user_id' and a.status !='2' 
						and main_quiz_id not in 
						(
						  select main_quiz_id from child_quiz_result
						)")->result_array();
		echo json_encode($getSubmittedQuiz);
		exit;
	}

	public function getQuizCaclulatedResult() {
		$main_quiz_id = $_POST['main_quiz_id'];
		$user_id = $_POST['user_id'];
		$quizSubmittedResult = $this->db->query("select * from child_quiz_result where main_quiz_id='$main_quiz_id' and user_id='$user_id' and status != '2'")->result_array();	
		$quizSubmittedAnswers = $this->db->query("select a.* from child_quiz_answer a 
		left outer join quiz b on a.quiz_id = b.id where a.main_quiz_id='$main_quiz_id' and a.user_id='$user_id' and a.status != '2' and b.status != '2'")->result_array();	
	    $correctAns = 0;
		$skippedAns = 0;
		$wrongAns = 0;
		$completedQuestion = 0;
		$totalDuration = 0;
		$quizScore	= 0;
		foreach($quizSubmittedAnswers as $list){
			$correctAns += (int)$list['is_correct'];
			$skippedAns += (int)$list['is_skipped'];
			$wrongAns += (int)$list['is_wrong'];
			if($list['is_pending'] == 0){
				$completedQuestion += 1;
			}
			$quizScore += (int)$list['marks'];
			$totalDuration += (int)$list['duration'];	
		}
		if(count($quizSubmittedResult) > 0){
			$message = false;
		}else{
			$message = true;
		}
		$data = array(
			'correctAns' => $correctAns,
			'skippedAns' => $skippedAns,
			'wrongAns' => $wrongAns,
			'completedQuestion' => $completedQuestion,
			'totalDuration' => $totalDuration,
			'quizScore'	=> $quizScore,
			'success' => $message
		);
	
		echo json_encode($data);
		exit;
	}
	
}
