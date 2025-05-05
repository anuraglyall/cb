<?php 
defined('BASEPATH') OR exit('No direct script access allowed');

class Extra extends CI_Controller {

    public function __construct()
	{
	  parent::__construct();
	  $this->load->library('session');
	}
       
    public function get_currency_data() {
        $id = $_POST['id'];
        $currency = $this->db->query("SELECT * FROM `currency_masters` where id='$id'")->row_array();
        echo json_encode($currency);
    }

// User list to egenrate certificates 
    private function getUserListToCompletedCoursesCertification($course_id) {
        $allOngoingCourses = $this->db->query("SELECT a.child_id,a.course_id,c.full_name FROM mycart a 
        LEFT OUTER JOIN course_certificate b ON b.user_id = a.child_id AND b.course_id = a.course_id
        LEFT OUTER JOIN users c ON c.id = a.child_id 
        LEFT OUTER JOIN courses d ON d.id = a.course_id 
        WHERE b.certificate IS NULL AND d.id = '$course_id' and a.is_paid = 2 AND a.status != '2' AND c.status != '2' AND d.status != '2'")->result_array();
        if(count($allOngoingCourses) === 0) return [];
        //filteredAllOngoingCourses list of users whose course completed but certificate not generated
        $filteredAllOngoingCourseUsers = [];
        for ($i=0; $i < count($allOngoingCourses); $i++) { 
            $user_id =  $allOngoingCourses[$i]['child_id'];
            $user_name = $allOngoingCourses[$i]['full_name'];
            $course_id =  $allOngoingCourses[$i]['course_id'];
           
                    array_push($filteredAllOngoingCourseUsers,array('id' => $user_id, 'name' => "$user_name"));
                
        }
  
        if(count($filteredAllOngoingCourseUsers) > 0) {
            $filteredAllOngoingCourseUsers = array_map("unserialize", array_unique(array_map("serialize", $filteredAllOngoingCourseUsers)));
        }

        return $filteredAllOngoingCourseUsers;
    }

   
     

public function delete_data() {
    // Check if the id parameter is set and not empty
    $id = $this->input->post('id');
    $table = $this->input->post('table');
    if (!$id) {
        // Return an error response
        echo '<div id="success">Invalid id"!</div>'; 
        exit;
    }

    // Update the record's status to 2 (assuming 2 means deleted)
    if($table == 'chapter'){
        $ResMasterClasses = $this->db->query("SELECT * FROM master_classes WHERE chapter_id = '$id' and status= '0'")->result_array();
        if(count($ResMasterClasses) < 1 ){
            $status = array("status" => 2);
            $this->db->where('id', $id); 
            $this->db->update('chapter', $status);
        }else{
            echo '<div id="success">Entity In Use!</div>'; 
            exit;
        }
    }else{
        $status = array("status" => 2);
        $this->db->where('id', $id); 
        $this->db->update('chapter', $status);
    }
   
    // Check if the update was successful
    if ($this->db->affected_rows() > 0) {
        // Return a success response
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        // Return an error response if no rows were affected
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_data2() {
    // Check if the id parameter is set and not empty
    $id = $this->input->post('id');
    if (!$id) {
        // Return an error response
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    // Update the record's status to 2 (assuming 2 means deleted)
    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('course_exercise', $status);

    // Check if the update was successful
    if ($this->db->affected_rows() > 0) {
        // Return a success response
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        // Return an error response if no rows were affected
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_material_data() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('course_material', $status);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_folder_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $this->db->where('id', $id);
    $this->db->delete('course_gallery_folders');

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_gallery_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $this->db->where('id', $id);
    $this->db->delete('course_gallery');

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_quiz_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('quiz', $status);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_certificate_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $this->db->where('id', $id);
    $this->db->delete('course_certificate');

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}


public function delete_homework_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('homework', $status);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_quiz_option_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $status = array("status" => 2);
    $this->db->where('quiz_id', $id); 
    $this->db->update('quiz_options', $status);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_studentQuizAnswer_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }
    $child_quiz_details = $this->db->query("SELECT * FROM child_quiz_answer WHERE id = '$id' and status != '2'")->result_array();

    if(count($child_quiz_details) > 0  ){
          $user_id  = $child_quiz_details[0]['user_id']; 
          $main_quiz_id = $child_quiz_details[0]['main_quiz_id'];
          $result = $this->db->query("SELECT id FROM child_quiz_result WHERE user_id = '$user_id' and main_quiz_id = '$main_quiz_id' and status != '2'")->row_array();
    }

    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('child_quiz_answer', $status);
    if ($this->db->affected_rows() > 0) {
        $this->db->where('id', $result['id']); 
        $this->db->update('child_quiz_result', $status);
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function delete_studentQuizResult_record() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }
    $quiz_result_array = $this->db->query("select * from child_quiz_result where id = '$id' and status = '0' ")->row_array();
    $this->db->where('id', $id);
    $this->db->delete('child_quiz_result');

    if ($this->db->affected_rows() > 0) {
        $this->db->where('main_id', $quiz_result_array['main_quiz_id']);
        $this->db->where('notification_type', 'quiz');
        $this->db->delete('notification');
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function deleteMasterClassRecord() {
    $id = $this->input->post('id');
    if (!$id) {
         echo '<div id="success">Invalid id"!</div>'; 
         exit;
    }
    $ResClasses= $this->db->query("SELECT * FROM upcoming_classes WHERE master_class_id = '$id' and status= '0'")->result_array();
    if(count($ResClasses) < 1 ){
        $status = array("status" => 2);
        $this->db->where('id', $id); 
        $this->db->update('master_classes', $status);
    }else{
        echo '<div id="success">Entity In Use"!</div>'; 
         exit; 
    }
   
}

public function delete_classes_data() {
    $id = $this->input->post('id');
    if (!$id) {
        $response = array("success" => false, "message" => "Invalid id");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    $status = array("status" => 2);
    $this->db->where('id', $id); 
    $this->db->update('upcoming_classes', $status);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Data deleted successfully");
    } else {
        $response = array("success" => false, "message" => "Failed to delete data");
    }
}

public function updateClassStatus() {
    $data = $_POST;
    $id = $data['id'];
    $classStatus = $data['class_status'];
    if (!$id && !$classStatus) {
        $response = array("success" => false, "message" => "Invalid id or class status");
        $this->output->set_content_type('application/json')->set_output(json_encode($response));
        return;
    }

    // date_default_timezone_set("asia/Kuala_Lumpur");
    //date_default_timezone_set('Asia/Kolkata');
    $this->db->where('id', $id); 
    $this->db->update('upcoming_classes', array("class_status" => $classStatus,'updated_date' => date('Y-m-d H:i:s')));
    $this->sendNotificationToEnrolledStudents($id);

    if ($this->db->affected_rows() > 0) {
        $response = array("success" => true, "message" => "Class status updated successfully!");
    } else {
        $response = array("success" => false, "message" => "Failed to update class status!");
    }
}


//when class status reschedule or cancel
private function sendNotificationToEnrolledStudents($classId) {
    // date_default_timezone_set("asia/Kuala_Lumpur");
   // date_default_timezone_set('Asia/Kolkata');
    $enrolledStudents = $this->db->query("select f.id,b.name,e.title,e.start,e.end,e.upcoming_date,e.class_status from mycart a
    left outer join courses b on b.id = a.course_id
    left outer join chapter c on c.course_id = b.id
    left outer join master_classes d on d.chapter_id = c.id
    left outer join upcoming_classes e on e.master_class_id  = d.id
    left outer join users f on f.id = a.child_id 
    where a.is_paid = '2' and a.status != '2' and b.status != '2' and c.status != '2' 
    and d.status != '2' and e.status != '2' and f.status != '2' and e.id = '$classId'")->result_array();

    if(count($enrolledStudents) > 0) {
        //date_default_timezone_set('Asia/Kolkata');
        for ($i=0; $i < count($enrolledStudents); $i++) { 
            $receiverId = $enrolledStudents[$i]['id'];
            $courseName = $enrolledStudents[$i]['name'];
            $className = $enrolledStudents[$i]['title'];
            $classDate = date("Y-m-d", strtotime($enrolledStudents[$i]['upcoming_date']));    
            $classTime = $enrolledStudents[$i]['start'] . " - " . $enrolledStudents[$i]['end'];
            $classStatus = $enrolledStudents[$i]['class_status'];
            $message = "Course: $courseName, Class: $className, Date: $classDate, Timing: $classTime, Class Status: $classStatus";
            $message = ucfirst($message);
           
            $this->db->query("Insert into notification (main_id,sender_id,receiver_id,notification_type,message,added_date,updated_date) values('$classId','1','$receiverId','class_status_changed','$message',now(),now())");
        }
    }
}


    public function save_access() {
        $user_id = $_SESSION['id'];
        $handle = $_POST['handle'];
        $id = $_POST['id'];
        $id = array_merge($id, array("Action"));
        $id = implode(",", array_unique($id));

        $screenwise_columns_setting = $this->db->query("delete from screenwise_columns_setting where "
                . "handle='$handle' and user='$user_id'");

        $screenwise_columns_setting = $this->db->query("insert into screenwise_columns_setting set "
                . "handle='$handle',name='$id',user='$user_id'");
    }

    public function loadmostsearched() {
        $searchDatas = $_GET['search_datas'];
        $search = $_GET['search'];
        $selectId = $_GET['selectId'];
    }


     

    public function loadqcdata() {
        $id = $_POST['id'];
        $cond = $_POST['cond'];

        if ($cond == 1) {
            $order_qc1_steps = $this->db->query("select order_qc1_steps.*,qc.id as selected_id from "
                            . "order_qc1_steps"
                            . " LEFT JOIN order_qc1 qc ON qc.qc1=order_qc1_steps.id and qc.order_id='$id'"
                            . " where 1")->result_array();
            ?>
            <div class="card-body">
                <p class="text-muted">Please select/check steps you have complated! </p>
                <input readonly tabindex="-1" value="<?php echo $id; ?>" type="hidden" id="qc_order_id" name="qc_order_id" class="form-control" />
                <div class="live-preview">
                    <div class="list-group">
                        <?php
                        foreach ($order_qc1_steps as $order_qc1_steps_res) {
                            ?>
                            <label class="list-group-item">
                                <input <?php
                                if ($order_qc1_steps_res['selected_id'] > 0) {
                                    echo 'checked';
                                } else {
                                    echo '';
                                }
                                ?> class="form-check-input fs-14 mt-0 align-middle me-1" name="qc1[]" type="checkbox" value="<?php echo $order_qc1_steps_res['id']; ?>">
                                    <?php
                                    echo $order_qc1_steps_res['name'];
                                    ?>
                            </label>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="list-group">
                        <label>Comments</label>
                        <input class="form-control" required id="comments" name="comments"/>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" id="add-btn2" class="btn btn-primary ">Save QC 1</button>
                </div>
            </div>

            <?php
        }
        if ($cond == 2) {
            $order_qc1_steps = $this->db->query("select order_qc2_steps.*,qc.id as selected_id from "
                            . "order_qc2_steps"
                            . " LEFT JOIN order_qc2 qc ON qc.qc2=order_qc2_steps.id  and qc.order_id='$id'"
                            . "")->result_array();
            ?>
            <div class="card-body">
                <p class="text-muted">Please select/check steps you have complated! </p>
                <input readonly tabindex="-1" value="<?php echo $id; ?>" type="hidden" id="qc_order_id" name="qc_order_id" class="form-control" />
                <div class="live-preview">
                    <div class="list-group">
                        <?php
                        foreach ($order_qc1_steps as $order_qc1_steps_res) {
                            ?>
                            <label class="list-group-item">
                                <input <?php
                                if ($order_qc1_steps_res['selected_id'] > 0) {
                                    echo 'checked';
                                } else {
                                    echo '';
                                }
                                ?>  class="form-check-input fs-14 mt-0 align-middle me-1" name="qc2[]" type="checkbox" value="<?php echo $order_qc1_steps_res['id']; ?>">
                                    <?php
                                    echo $order_qc1_steps_res['name'];
                                    ?>
                            </label>
                            <?php
                        }
                        ?>
                    </div>
                    <div class="list-group">
                        <label>Comments</label>
                        <input class="form-control" required id="comments" name="comments"/>
                    </div>
                </div>
                <div class="modal-footer">
                    
                    <button type="submit" id="add-btn2" class="btn btn-primary ">Save QC 2</button>
                </div>
            </div>

            <?php
        }
    }


     
   // load tables data
    public function fetch_columns_data() {
        $this->load->library('session');
        $handle = $_POST['handle'];
        $user_id =  $this->session->userdata('id');
        $screenwise_columns_setting = $this->db->query("select * from screenwise_columns_setting "
                        . "where 1 and handle='$handle' and user='$user_id'")->row_array();
        //$arr = array();
        echo json_encode($screenwise_columns_setting);
    }
    public function replacement_cost() {
        $id= $_POST['id'];
        $value=$_POST['val'];
        $screenwise_columns_setting = $this->db->query("CALL `replacement_cost`($value)")->row_array();
        print_r(round($screenwise_columns_setting['total_amount']));
    }

    public function loadorderextradetails() {
        $id = $_POST['id'];
        $handle= $_POST['handle'];
        $order_details_extra = $_POST['order_details_extra'];
        $final_condition = $_POST['final_condition'];

        $orders = $this->db->query("SELECT *"
                        . ",(select style_id from styles where id=orders.styles limit 1) as style_id "
                        . ",(select name from metals where id=orders.metal limit 1) as metal_name "
//                        . ",(select rate from metals where id=orders.metal limit 1) as metal_rate "
                        . "from orders where id='$id' ")->row_array();
        
        $styles = $orders['styles'];
        $vendor = $orders['vendor'];
        $vendor = $this->db->query("SELECT * from partners_customer where id='$vendor'")->row_array();
        $value_added_rate = $vendor['value_added_rate'];
        $wastage_percentage = $vendor['wastage_percentage'];
        
        if($handle=='inventory')
        {
            $display_hide="1";    
        }
        if ($order_details_extra != 'edit_inventory') {
            ?>
            <!--1-->
            <div class="extra_div_css  col-md-2  pull-left" style="<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>float:left;">
                <label>Style Code</label>    
                <input placeholder="Enter Style Id" tabindex="-1" readonly tabindex="-1" value="<?php echo $orders['style_id'] ?>" type="text" <?php if($display_hide!='1') { ?> id="style_id" name="style_id" <?php } ?>
                       class="form-control" >
                <input readonly tabindex="-1" value="<?php echo $id; ?>" type="hidden" <?php if($display_hide!='1') { ?> id="order_id" name="order_id"  <?php } ?>
                       class="form-control" >
            </div>  

            <div class="extra_div_css  col-md-2  pull-left" style="<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>float:left;">
                <label>Serial No</label>    
                <input placeholder="Enter Serial No" tabindex="-1" readonly tabindex="-1" value="<?php echo $orders['style_serial_no'] ?>"  type="text" <?php if($display_hide!='1') { ?> id="serial_no" name="serial_no" <?php } ?> 
                       class="form-control" >
            </div>

            <div class="extra_div_css  col-md-2  pull-left" style="<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>float:left;">
                <label>Metal Type</label>    
                <input placeholder="Enter Metal Type" tabindex="-1" readonly tabindex="-1" value="<?php echo $orders['metal_name'] ?>" type="text" <?php if($display_hide!='1') { ?> id="metal_type" name="metal_type" <?php } ?>
                       class="form-control" >
            </div>
            <div class="extra_div_css col-md-2 pull-left" style="display:none;float:left;">
                <label>Metal Rate</label>    
                <input placeholder="Enter Metal Rate" tabindex="-1" readonly tabindex="-1" value="<?php echo $orders['metal_rate'] ?>" type="text" 
                    <?php if($display_hide!='1') { ?>  id="metal_rate" name="metal_rate" <?php } ?>
                       class="form-control metal_rate" >
            </div>

            <div class="extra_div_css  col-md-2  pull-left" style="float:left;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <label>Gross Weight(gms)</label>    
                <input <?php if($handle=='inventory') { echo 'readonly'; } ?> onchange="total_weight_calculation();" placeholder="Enter Gross Weight(gms)" focus value="<?php echo $orders['gross_weight']; ?>" required type="text" 
                                                                       <?php if($display_hide!='1') { ?>       id="gross_weight" name="gross_weight" <?php } ?>
                       class="form-control gross_weight" >
            </div> 

            <div class="extra_div_css  col-md-2  pull-left" style="float:left;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <?php $total_net_weight=trim($orders['total_net_weight']); ?>
                <label>Net Weight(gm):</label>
                <input placeholder="0.00" tabindex="-1" readonly onchange="total_weight_calculation();"  
                  value="<?php echo $total_net_weight;?>"
                  type="text" <?php if($display_hide!='1') { ?> name="total_net_weight" id="total_net_weight" <?php } ?> class="form-control total_net_weight"/>
            </div>  

            <div class="extra_div_css col-md-2 pull-left" style="float:left;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <label>Metal Cost</label>    
                <?php $metal_cost=trim($orders['metal_cost']); ?>
                <input placeholder="Enter Metal Cost" tabindex="-1" readonly tabindex="-1" value="<?php echo $metal_cost;?>" type="text" 
                     <?php if($display_hide!='1') { ?>  id="metal_cost" name="metal_cost" <?php } ?>
                       class="form-control metal_cost" >
            </div>

            <div class="extra_div_css col-md-2 pull-left" style="float:left;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <label>Value Added Rate</label>    
                <input placeholder="Enter Value Added Rate" tabindex="-1" readonly tabindex="-1" value="<?php echo $value_added_rate; ?>" type="text" 
                <?php if($display_hide!='1') { ?>       id="value_added_rate" name="value_added_rate" <?php } ?>
                       class="form-control value_added_rate" >
            </div>

            <div class="extra_div_css col-md-2 pull-left" style="float:left;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <label>Wastage %</label>    
                <input placeholder="Enter Wastage %" tabindex="-1" readonly tabindex="-1" value="<?php echo $wastage_percentage; ?>" type="text" 
                    <?php if($display_hide!='1') { ?>   id="wastage_percentage" name="wastage_percentage" <?php } ?>
                       class="form-control wastage_percentage" >
            </div>

            <div class="extra_div_css <?php
            if ($order_details_extra == '2') {
                echo 'col-md-12';
            } else {
                echo 'col-md-2';
            }
            ?> pull-left" style="<?php
                 if ($order_details_extra == '2') {
                     echo 'display:none;';
                 }
                 ?>float:left;">
                <label>Value Added Total</label>    
                <input placeholder="Enter Total Value Added" tabindex="-1" readonly tabindex="-1" value="<?php echo $orders['value_added_total']; ?>" type="text" 
                    <?php if($display_hide!='1') { ?>   id="value_added_total" name="value_added_total" <?php } ?>
                       class="form-control value_added_total" >
            </div>

            <?php if ($order_details_extra == '2') { ?>  
                <div class="extra_div_css <?php
                if ($order_details_extra == '2') {
                    echo 'col-md-12';
                } else {
                    echo 'col-md-2';
                }
                ?> pull-left" style="<?php
                     if ($order_details_extra == '2') {
                         echo 'display:block;';
                     } else {
                         echo 'display:none;';
                     }
                     ?>float:left;">
                    <label>Final Replacement Cost</label>    
                    <input placeholder="Enter Replacement Cost" tabindex="-1" readonly tabindex="-1"  type="text" 

                           class="form-control new_replacement_cost" >
                </div>
                <div class="extra_div_css <?php
                if ($order_details_extra == '2') {
                    echo 'col-md-12';
                } else {
                    echo 'col-md-2';
                }
                ?> pull-left" style="<?php
                     if ($order_details_extra == '2') {
                         echo 'display:block;';
                     } else {
                         echo 'display:none;';
                     }
                     ?>float:left;">
                    <div style="<?php
                         //echo $width1; 
                         $prices = $this->db->query("select * from prices")->result_array();
                         ?>">
                        <label class="form-label">Prices</label>
                        <select  data-select-id="prices" onchange="get_price_wise_multiple(this.value)"  class="my-select4 selectized form-control" <?php if($display_hide!='1') { ?> name="prices"  id="prices" <?php } ?>>
                            <option value="">--Suggested--</option>
                            <?php
                            foreach ($prices as $select_data) {
                                ?>
                                <option <?php echo $select_data['disabled']; ?> value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="extra_div_css  div_extra_total_multiple  col-md-12  pull-left" style="display:none;float:left;">

                    <label for="total_multiple" class="form-label">total_multiple</label>

                    <input placeholder="Select Prices Here..." type="text" <?php if($display_hide!='1') { ?> id="total_multiples" name="total_multiples" <?php } ?>
                           class="form-control" required />

                </div>    
                <div class="extra_div_css  col-md-12  pull-left" style="float:left;">

                    <label for="target_cost" class="form-label">Target Cost</label>

                    <input tabindex="15" onchange="calculate_formula_price(this.value);" placeholder="Enter Target Cost Here..." value="" 
                           type="number" <?php if($display_hide!='1') { ?> id="target_cost" name="target_cost" <?php } ?> class="form-control" required="">

                </div>
                <div class="extra_div_css  col-md-12  pull-left" style="float:left;">

                    <label for="formula_based_price" class="form-label">Formula Based Price</label>

                    <input tabindex="16" placeholder="Enter Formula Based Price Here..." value="" type="text" <?php if($display_hide!='1') { ?> id="formula_based_price" name="formula_based_price" <?php } ?> class="form-control" required="">

                </div>    

                <div class="extra_div_css  col-md-12  pull-left" style="float:left;">

                    <label for="ratail_price_int" class="form-label">Retail Price - International</label>

                    <input tabindex="17" placeholder="Enter Retail Price - International Here..." value="" type="text" 
                          <?php if($display_hide!='1') { ?> id="ratail_price_int" name="ratail_price_int" <?php } ?> class="form-control" required="">

                </div>

                <div class="extra_div_css  col-md-12  pull-left" style="float:left;">

                    <label for="ratail_price_ind" class="form-label">Retail Price - India</label>

                    <input tabindex="18" placeholder="Enter Retail Price - India Here..." value="" type="text" 
                          <?php if($display_hide!='1') { ?> id="ratail_price_ind" name="ratail_price_ind" <?php } ?> class="form-control" required="">

                </div>  

                <button style="float:right;" data-bs-dismiss="offcanvas" onclick="apply_replacement_cost();" aria-label="Close" type='button' class="btn btn-primary">Apply</button>


            <?php } ?>    

            <div class="extra_div_css col-md-4 pull-left" style="float:left;">
                &nbsp;
            </div>  

            <div class="extra_div_css  col-md-12  pull-left" style="float:left;clear:both;<?php
            if ($order_details_extra == '2') {
                echo 'display:none;';
            }
            ?>">
                <table id="scroll-vertical" class="table align-middle table-hover table-nowrap table-striped-columns mb-0" style="width:100%">
                    <thead class="table-light">
                        <tr >
                            <th>Diamond</th>
                            <th >Name</th>
                            <th>Cut</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Clarity</th>
                            <th>Pointers</th>
                            <th>Sieve Size</th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <?php 
                    $orders_diamonds=$this->db->query("SELECT "
                                        . "d.id,od.order_id,od.diamond_rate"
                                        . ",d.name as diamond_name"
                                        . ",dm.name as diamond_cut"
                                        . ",dm2.name as diamond_shape"
                                        . ",dm3.name as diamond_color"
                                        . ",dm4.name as diamond_clarity"
                                        . ",dm5.name as diamond_pointers"
                                        . ",dm6.name as diamond_sieve_size"
                                        . ",ow.weight as diamond_weight"
                                        . ",ow.no_of_peaces as no_of_peaces"
                                        . ",ow.avg_weight as avg_weight"
                                        . ",ow.cost as cost" 
//                                      . ",dm7.name as diamond_rate"
                                        . " FROM `orders_diamonds` od"
                                        . " JOIN diamonds d ON d.id=od.diamonds " 
                                        . " LEFT JOIN order_weight_logs ow ON d.id=ow.diamond_id and od.order_id=ow.order_id "
                                        . " LEFT JOIN diamond_masters dm ON od.diamond_cut=dm.id and dm.type='1' "
                                        . " LEFT JOIN diamond_masters dm2 ON od.diamond_shape=dm2.id and dm2.type='2' "
                                        . " LEFT JOIN diamond_masters dm3 ON od.diamond_color=dm3.id and dm3.type='3' "
                                        . " LEFT JOIN diamond_masters dm4 ON od.diamond_clarity=dm4.id and dm4.type='4' "
                                        . " LEFT JOIN diamond_masters dm5 ON od.diamond_pointers=dm5.id and dm5.type='5' "
                                        . " LEFT JOIN diamond_masters dm6 ON od.diamond_sieve_size=dm6.id and dm6.type='6' "
                                        . " WHERE od.`order_id` = '$id'")->result_array();
                    ?>
                    <tbody style="font-size:12px;">                                                            
                        <?php
                        $total0101=0;
                        $tota20202=0;
                        foreach ($orders_diamonds as $orders_diamonds_res) {
                            ?>
                            <tr style="font-size:11px;">
                                <th>
                                    Diamonds
                                </th>
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['diamond_name'];
//                                    echo '<pre>';
//                                    print_r($orders_diamonds_res);
//                                    echo '</pre>';
                                    ?>
                                    <input type="hidden" <?php if($display_hide!='1') { ?> name="diamond_id[]" <?php } ?>
                                           value="<?php echo $orders_diamonds_res['id']; ?>">
                                    <!--input type="hidden" <?php if($display_hide!='1') { ?> name="order_id[]" <?php } ?>
                                           value="<?php echo $orders_diamonds_res['order_id']; ?>"-->

                                </th>  
                                <th><?php echo $orders_diamonds_res['diamond_cut']; ?></th>  
                                <th> <?php echo $orders_diamonds_res['diamond_shape']; ?></th>                                                                
                                <th><?php echo $orders_diamonds_res['diamond_color']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_clarity']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_pointers']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_sieve_size']; ?></th>                                                                  
                                <th>
                                    <input type="text" <?php if($handle=='inventory') { echo 'readonly'; } ?>  
                                           class="form-control  no_of_peaces" 
                                           <?php if($display_hide!='1') { ?> name="diamond_no_of_peaces[]" onchange="total_weight_calculation();" <?php } ?>
                                           placeholder='Enter no of peaces' required    
                                           value="<?php
                                           if ($orders_diamonds_res['no_of_peaces'] != '') {
                                               echo $no_of_peaces=$orders_diamonds_res['no_of_peaces'];
                                           } else {
                                               echo '1';
                                           }
                                           ?>">
                                </th>
                                <th>
                                    <input type="text"  <?php if($handle=='inventory') { echo 'readonly'; } ?>    onchange="total_weight_calculation();"  
                                           class="form-control all_weight diamond_weight" 
                                           <?php if($display_hide!='1') { ?> name="diamond_weight[]"  <?php } ?>
                                           placeholder='Enter Weight(ct)' required    
                                           value="<?php
                                           if ($orders_diamonds_res['diamond_weight'] != '') {
                                               echo $diamond_weight=$orders_diamonds_res['diamond_weight'];
                                           } else {
                                               echo $diamond_weight='0';
                                           }
                                           $tota20202=$tota20202+$diamond_weight;
                                           ?>">

                                </th>
                                <th>
                                    <input type="text"  
                                           class="form-control  avg_weight" 
                                           <?php if($display_hide!='1') { ?> name="diamond_avg_weight[]" <?php } ?>
                                           
                                           tabindex="-1"  readonly tabindex="-1" 
                                           placeholder='Avg. Weight' required    
                                           value="<?php
                                           if ($orders_diamonds_res['avg_weight'] != '') {
                                               echo $avg_weight=$orders_diamonds_res['avg_weight'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>
                                <th>
                                    <input type="text"  
                                           class="form-control  no_of_rate" <?php if($display_hide!='1') { ?> name="rate[]" <?php } ?> tabindex="-1"  readonly tabindex="-1" 
                                           placeholder='Rate' required    
                                           value="<?php
                                           if ($orders_diamonds_res['diamond_rate'] != '') {
                                               echo $orders_diamonds_res['diamond_rate'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>


                                <th>
                                    <input type="text"  onchange="total_weight_calculation();"  
                                           class="form-control no_of_cost diamond_cost" readonly tabindex="-1" tabindex="-1" <?php if($display_hide!='1') { ?> name="cost[]" <?php } ?>
                                           placeholder='Enter Cost' required    
                                           value="<?php
                                           if ($orders_diamonds_res['cost'] != '') {
                                               echo $cost=$orders_diamonds_res['cost'];
                                               
                                           } else {
                                               echo $cost='0';
                                           }
                                           
                                           $total0101=$total0101+$cost;
                                           ?>"> 
                                </th>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tbody>
                        <tr>
                            <th colspan="9" style="text-align:right;" align="right">Total</th> 
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_diamond_weight" <?php } ?> tabindex="-1" class="form-control total_diamond_weight" readonly tabindex="-1" 
                                       placeholder='Total Weight' 
                                       value="<?php
                                       echo $orders['total_diamond_weight'];
                                       ?>">
                            </th> 
                            <th> </th> 
                            <th> </th> 
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_diamond_cost" <?php } ?> tabindex="-1" class="form-control total_diamond_cost" readonly tabindex="-1"  
                                       placeholder='Total Diamond Cost' 
                                       value="<?php echo $orders['total_diamond_cost'];; ?>">
                            </th>
                        </tr>
                    </tbody>
                    <?php 
//                    }
                    
                    $orders_diamonds = $this->db->query("SELECT "
                                        . "d.id,od.order_id"
                                        . ",od.gemstone_rate"
                                        . ",d.name as gemstone_name"
                                        . ",dm.name as gemstone_type"
                                        . ",dm1.name as gemstone_cut"
                                        . ",dm2.name as gemstone_shape"
                                        . ",dm3.name as gemstone_size"
                                        . ",dm4.name as gemstone_quality"
                                        . ",dm6.name as gemstone_origin"
                                        . ",ow.weight as diamond_weight"
                                        . ",ow.no_of_peaces as no_of_peaces"
                                        . ",ow.avg_weight as avg_weight"
                                        . ",ow.cost as cost"
                                        . " FROM `orders_gemstone` od"
                                        . " JOIN gemstone d ON d.id=od.gemstone "
                                        . " LEFT JOIN order_weight_logs ow ON d.id=ow.gemstone_id "
                                        . " LEFT JOIN gemstone_masters dm ON od.gemstone_type=dm.id "
                                        . "and dm.type='2' "
                                        . " LEFT JOIN gemstone_masters dm2 ON od.gemstone_shape=dm2.id "
                                        . "and dm2.type='3' "
                                        . " LEFT JOIN gemstone_masters dm1 ON od.gemstone_cut=dm1.id "
                                        . "and dm1.type='1' "
                                        . " LEFT JOIN gemstone_masters dm3 ON od.gemstone_size=dm3.id "
                                        . "and dm3.type='5' "
                                        . " LEFT JOIN gemstone_masters dm4 ON od.gemstone_quality=dm4.id "
                                        . "and dm4.type='4' "
                                        . " LEFT JOIN gemstone_masters dm6 ON od.gemstone_origin=dm6.id "
                                        . "and dm6.type='6' "

                                        . " WHERE od.`order_id` = '$id'")->result_array();
   
                    ?>
                    <thead class="table-light">
                        <tr >
                            <th >Gemstone</th>
                            <th>Name</th>
                            <th>Cut</th>
                            <th>Shape</th>
                            <th>Type</th>
                            <th>Quality</th>
                            <th>Origin</th>
                            <th>Size</th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                    </thead>
                    <tbody style="font-size:12px;">                                                            
                        <?php
                        foreach ($orders_diamonds as $orders_diamonds_res) {
                            ?>
                            <tr style="font-size:11px;">

                                <th>
                                    Gemstones
                                </th>
                                <th>
                                    <?php

                                    echo $orders_diamonds_res['gemstone_name'];
                                    ?>
                                    <input type="hidden" <?php if($display_hide!='1') { ?> name="gemstone_id[]" <?php } ?>
                                           value="<?php echo $orders_diamonds_res['id']; ?>">
                                    <input type="hidden" <?php if($display_hide!='1') { ?> name="order_id[]" <?php } ?>
                                           value="<?php echo $orders_diamonds_res['order_id']; ?>">


                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_cut'];
                                    ?>
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_shape'];
                                    ?>                                                                   
                                </th> 
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_type'];
                                    ?>
                                </th>                                                          

                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_quality'];
                                    ?>                                                                 
                                </th>                                                                  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_origin'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_size'];
                                    ?>                                                                 
                                </th>                                                                 
                                <th>
                                    <input type="text"  <?php if($handle=='inventory') { echo 'readonly'; } ?> 
                                           class="form-control  no_of_peaces" <?php if($display_hide!='1') { ?> name="gemstone_no_of_peaces[]" onchange="total_weight_calculation();" <?php } ?>
                                           placeholder='Enter no of peaces' required    
                                           value="<?php
                                           if ($orders_diamonds_res['no_of_peaces'] != '') {
                                               echo $orders_diamonds_res['no_of_peaces'];
                                           } else {
                                               echo '1';
                                           }
                                           ?>">
                                </th>
                                <th>

                                    <input onchange="total_weight_calculation();"  type="text" <?php if($handle=='inventory') { echo 'readonly'; } ?> 
                                           value="<?php
                                           if ($orders_diamonds_res['diamond_weight'] != '') {
                                               echo $orders_diamonds_res['diamond_weight'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>"
                                           class="form-control all_weight gemstone_weight" <?php if($display_hide!='1') { ?> name="gemstone_weight[]"  <?php } ?>
                                           placeholder='Enter Weight(ct)' required >
                                </th>
                                <th>
                                    <input type="text"  <?php if($handle=='inventory') { echo 'readonly'; } ?> 
                                           class="form-control  avg_weight" <?php if($display_hide!='1') { ?> name="gemstone_avg_weight[]" readonly <?php } ?>   tabindex="-1" 
                                           placeholder='Avg. Weight' required    
                                           value="<?php
                                           if ($orders_diamonds_res['avg_weight'] != '') {
                                               echo $orders_diamonds_res['avg_weight'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>
                                <th>
                                    <input type="text"  
                                           class="form-control  no_of_rate" <?php if($display_hide!='1') { ?> name="rate[]" <?php } ?>  readonly tabindex="-1" 
                                           placeholder='Rate' required    
                                           value="<?php
                                           if ($orders_diamonds_res['gemstone_rate'] != '') {
                                               echo $orders_diamonds_res['gemstone_rate'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>


                                <th>
                                    <input type="text"  onchange="total_weight_calculation();"  
                                           class="form-control no_of_cost gemstone_cost" readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="cost[]" <?php } ?>
                                           placeholder='Enter Cost' required    
                                           value="<?php
                                           if ($orders_diamonds_res['cost'] != '') {
                                               echo $orders_diamonds_res['cost'];
                                               ;
                                           } else {
                                               echo '0';
                                           }
                                           ?>"
                                           >
                                </th>



                            </tr>
                            <?php
                        }
                        ?>
                    </tbody> 
                    <tbody>
                        <tr>
                            <th colspan="9" style="text-align:right;" align="right">Total</th> 
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_gemstone_weight" <?php } ?> class="form-control total_gemstone_weight" readonly tabindex="-1" 
                                       placeholder='Total Weight' 
                                       value="<?php echo $orders['total_gemstone_weight'];; ?>">
                            </th> 
                            <th></th> 
                            <th></th> 
                            
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_gemstone_cost" <?php } ?> class="form-control total_gemstone_cost" readonly tabindex="-1" 
                                       placeholder='Total Gemstone Cost' 
                                       value="<?php echo $orders['total_gemstone_cost']; ?>">
                            </th>
                        </tr>
                    </tbody>
                    <?php 
//                    }
                    $orders_diamonds = $this->db->query("SELECT "
                                        . "d.id,od.order_id"
                                        . ",od.pearl_rate"
                                        . ",d.name as pearl_name"
                                        . ",dm.name as pearl_shape"
                                        . ",dm1.name as pearl_type"
                                        . ",dm2.name as pearl_color"
                                        . ",dm3.name as pearl_size"
                                        . ",dm4.name as pearl_unit"
                                        . ",ow.weight as diamond_weight"
                                        . ",ow.no_of_peaces as no_of_peaces"
                                        . ",ow.avg_weight as avg_weight"
                                        . ",ow.cost as cost"

                                        
                                        . " FROM `orders_pearls` od"
                                        . " JOIN pearls d ON d.id=od.pearl "
                                        . " LEFT JOIN order_weight_logs ow ON od.id=ow.pearls_id "
                                        . " LEFT JOIN pearl_masters dm1 ON od.pearl_type=dm1.id and dm1.type='1' "
                                        . " LEFT JOIN pearl_masters dm ON od.pearl_shape=dm.id and dm.type='2' "
                                        . " LEFT JOIN pearl_masters dm2 ON od.pearl_color=dm2.id and dm2.type='3' "
                                        . " LEFT JOIN pearl_masters dm3 ON od.pearl_size=dm3.id and dm3.type='4' "
                                        . " LEFT JOIN pearl_masters dm4 ON od.pearl_unit=dm4.id and dm4.type='5' "
                                        . " WHERE od.`order_id` = '$id'")->result_array();
   
                    ?>
                    <thead class="table-light">
                        <tr >
                            <th>Pearls</th>
                            <th >Name</th>
                            <th>Type</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Unit</th>
                            <th></th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                    </thead>
                    <tbody style="font-size:12px;">                                                            
                        <?php
                        
                        foreach ($orders_diamonds as $orders_diamonds_res) {
                            ?>
                            <tr style="font-size:11px;">
                                <th>Pearls</th>
                                <th>
                                    <?php

                                    echo $orders_diamonds_res['pearl_name'];
                                    ?>    
                                    <input type="hidden" <?php if($display_hide!='1') { ?> name="pearls_id[]" <?php } ?>
                                           value="<?php echo $orders_diamonds_res['id']; ?>">

                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_type'];
                                    ?>
                                </th>   
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_shape'];
                                    ?>
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_color'];
                                    ?>                                                                
                                </th>                                                                
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_size'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_unit'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php
//                                                                    echo $orders_diamonds_res['pearl_size'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <input type="text"  
                                           class="form-control  no_of_peaces" <?php if($display_hide!='1') { ?> name="pearls_no_of_peaces[]" onchange="total_weight_calculation();" <?php } ?>
                                           placeholder='Enter no of peaces' required    
                                           value="<?php
                                           if ($orders_diamonds_res['no_of_peaces'] != '') {
                                               echo $orders_diamonds_res['no_of_peaces'];
                                           } else {
                                               echo '1';
                                           }
                                           ?>">
                                </th>
                                <th>
                                    <input onchange="total_weight_calculation();" type="text" 
                                           class="form-control all_weight pearls_weight" 
                                           value="<?php
                                           if ($orders_diamonds_res['pearls_weight'] != '') {
                                               echo $orders_diamonds_res['pearls_weight'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>"
                                          <?php if($display_hide!='1') { ?> name="pearls_weight[]" <?php } ?>
                                           placeholder='Enter Weight(ct)' required   >
                                </th>
                                <th>
                                    <input type="text"  
                                           class="form-control  avg_weight" <?php if($display_hide!='1') { ?> name="pearls_avg_weight[]" <?php } ?> readonly tabindex="-1" 
                                           placeholder='Avg. Weight' required    
                                           value="<?php
                                           if ($orders_diamonds_res['avg_weight'] != '') {
                                               echo $orders_diamonds_res['avg_weight'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>
                                <th> 
                                    <input type="text"  
                                           class="form-control  no_of_rate" <?php if($display_hide!='1') { ?> name="rate[]" <?php } ?> readonly tabindex="-1" 
                                           placeholder='Rate' required    
                                           value="<?php
                                           if ($orders_diamonds_res['pearl_rate'] != '') {
                                               echo $orders_diamonds_res['pearl_rate'];
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>


                                <th>
                                    <input type="text"  onchange="total_weight_calculation();"  
                                           class="form-control no_of_cost pearls_cost" readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="cost[]" <?php } ?>
                                           placeholder='Enter Cost' required    
                                           value="<?php
                                           if ($orders_diamonds_res['cost'] != '') {
                                               echo $orders_diamonds_res['cost'];
                                               ;
                                           } else {
                                               echo '0';
                                           }
                                           ?>">
                                </th>

                            </tr>
                            <?php
                        }
                        ?>
                    
                    <tbody>
                        <tr>
                            <th colspan="9" style="text-align:right;" align="right">Total</th> 
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_pearls_weight" <?php } ?> class="form-control total_pearls_weight" readonly tabindex="-1" 
                                       placeholder='Total Weight' 
                                       value="<?php
                                       echo $orders['total_pearls_weight'];
                                       ?>">
                            </th> 
                            <th></th> 
                            <th></th> 
                            <th>
                                <input type="text"   
                                       readonly tabindex="-1" <?php if($display_hide!='1') { ?> name="total_pearls_cost" <?php } ?>  class="form-control total_pearls_cost" readonly tabindex="-1" 
                                       placeholder='Total Pearls Cost' 
                                       value="<?php echo $orders['total_pearls_cost']; ?>">
                            </th>
                        </tr>
                    </tbody>
                    <?php 
//                    }
                    ?>

                    <tfoot class="table-light">
                        <tr>
                            <th colspan="10"></th>
                            <th  colspan="3">
                                <div class="extra_div_css <?php
                                if ($order_details_extra == '2') {
                                    echo 'col-md-12';
                                } else {
                                    echo 'col-md-6';
                                }
                                ?> pull-left" style="<?php
                                     if ($order_details_extra == '2') {
                                         echo 'display:none;';
                                     }
                                     ?>float:left;">
                                    <label>Total Item Cost (Original)</label>    
                                    <input placeholder="Enter Total Item Cost (Original)" readonly tabindex="-1" value="<?php echo $orders['total_item_cost']; ?>" type="text" 
                                       <?php if($display_hide!='1') { ?>    id="total_item_cost" name="total_item_cost" <?php } ?> 
                                           class="form-control total_item_cost" >
                                </div>
                                <div style='float:right;' class='extra_div_css  col-md-6  pull-left'>
                                    <label>Total Stone Weight(ct):</label>
                                    <input placeholder="0.00" value="<?php echo $orders['total_weight_ct']; ?>" readonly required  type="text" 
                                         <?php if($display_hide!='1') { ?>  name="total_weight" id="total_weight" <?php } ?> class="form-control total_weight"/>
                                </div>
                                <div style='float:left;display:none;'  class='extra_div_css  col-md-4  pull-left'>
                                    <label>Total Stone Weight(gm):</label>
                                    <input placeholder="0.00" value="<?php echo $orders['total_weight_gm']; ?>" required readonly  type="text" 
                                          <?php if($display_hide!='1') { ?> name="total_weight_gm" id="total_weight_gm" <?php } ?>
                                          class="form-control total_weight_gm"/>
                                </div>
                                <div style='float:left;display:none;'  class='extra_div_css  col-md-4  pull-left'>
                                    <label>Total No of Peaces:</label>
                                    <input placeholder="0.00" value="<?php echo $orders['total_peaces_no']; ?>" required readonly type="text" 
                                          <?php if($display_hide!='1') { ?> name="total_peaces_no" id="total_peaces_no" <?php } ?> 
                                          class="form-control total_peaces_no"/>
                                </div>

                            </th>
                        </tr>                                                                                                                                                                                                                                                                </tr>
                    </tfoot>

                    </tbody>  
                </table>
            </div>
            </hr>

            <?php
            if ($order_details_extra != '2' && $handle!='inventory') {
                ?>
                <div class="modal-footer" style="clear:both;width:100%;">
                    
                    <button type="submit" class="btn btn-primary ">Save Orders</button>
                </div>
                <?php
            }
        }

        if ($order_details_extra == 'edit_inventory') {
            echo '2';
            $orders_diamonds = $this->db->query("SELECT "
                            . "d.id,od.diamond_rate"
                            . ",d.name as diamond_name"
                            . ",dm.name as diamond_cut"
                            . ",dm2.name as diamond_shape"
                            . ",dm3.name as diamond_color"
                            . ",dm4.name as diamond_clarity"
                            . ",dm5.name as diamond_pointers"
                            . ",dm6.name as diamond_sieve_size"
                            . ",ow.weight as diamond_weight"
                            . ",ow.no_of_peaces as no_of_peaces"
                            . ",ow.rate as rate"
                            . ",ow.cost as cost"
                            . ",ow.avg_weight as avg_weight"
//                                                                . ",dm7.name as diamond_rate"
                            . " FROM `orders_diamonds` od"
                            . " JOIN diamonds d ON d.id=d.diamonds "
                            . " JOIN order_weight_logs ow ON od.id=ow.diamond_id "
                            . " JOIN diamond_masters dm ON od.diamond_cut=dm.id and dm.type='1' "
                            . " JOIN diamond_masters dm2 ON od.diamond_shape=dm2.id and dm2.type='2' "
                            . " JOIN diamond_masters dm3 ON od.diamond_color=dm3.id and dm3.type='3' "
                            . " JOIN diamond_masters dm4 ON od.diamond_clarity=dm4.id and dm4.type='4' "
                            . " LEFT JOIN diamond_masters dm5 ON od.diamond_pointers=dm5.id and dm5.type='5' "
                            . " LEFT JOIN diamond_masters dm6 ON od.diamond_sieve_size=dm6.id and dm6.type='6' "
                            . " WHERE od.`order_id` = '$id'")->result_array();
            ?>
            <div class="extra_div_css  col-md-12  pull-left" style="float:left;clear:both">
                <table id="scroll-vertical" class="table align-middle table-hover table-nowrap table-striped-columns mb-0" style="width:100%">
                    <thead class="table-light" >
                        <tr style="text-align: center;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th>#</th>
                            <th >Name</th>
                            <th>Cut</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Clarity</th>
                            <th>Pointers</th>
                            <th>Sieve Size</th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:12px;<?php
                    if (count($orders_diamonds) == 0) {
                        echo 'display:none;';
                    }
                    ?>">                                                            
                               <?php
                               $total_pcs = 0;
                               foreach ($orders_diamonds as $orders_diamonds_res) {
                                   ?>
                            <tr style="font-size:11px;text-align: center;">
                                <th>
                                    Diamonds
                                </th>
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['diamond_name'];
                                    ?>
                                </th>  
                                <th><?php echo $orders_diamonds_res['diamond_cut']; ?></th>  
                                <th> <?php echo $orders_diamonds_res['diamond_shape']; ?></th>                                                                
                                <th><?php echo $orders_diamonds_res['diamond_color']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_clarity']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_pointers']; ?></th>                                                                  
                                <th><?php echo $orders_diamonds_res['diamond_sieve_size']; ?></th>                                                                  
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['no_of_peaces'] != '') {
                                        echo $orders_diamonds_res['no_of_peaces'];
                                        $total_pcs = $total_pcs + $orders_diamonds_res['no_of_peaces'];
                                    } else {
                                        echo '1';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['diamond_weight'] != '') {
                                        echo $orders_diamonds_res['diamond_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['avg_weight'] != '') {
                                        echo $orders_diamonds_res['avg_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['rate'] != '') {
                                        echo $orders_diamonds_res['rate'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>


                                <th>
                                    <?php
                                    if ($orders_diamonds_res['cost'] != '') {
                                        echo $orders_diamonds_res['cost'];
                                        ;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                    <tbody>
                        <tr style="text-align: center;font-size:12px;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th colspan="8" style="text-align:right;">Total</th> 
                            <th ><?php echo $total_pcs; ?></th> 
                            <th ></th> 
                            <th ></th> 
                            <th>
                                <?php
                                if ($orders['total_diamond_weight'] != '') {
                                    echo $orders['total_diamond_weight'];
                                    ;
                                } else {
                                    echo '0';
                                }
                                ?>
                            </th> 
                            <th class="final_total_diamond_cost">
                                <?php
                                if ($orders['total_diamond_cost'] != '') {
                                    echo $orders['total_diamond_cost'];
                                    ;
                                } else {
                                    echo '0';
                                }
                                ?>
                            </th>
                        </tr>
                    </tbody>
                    <?php
                    $orders_diamonds = $this->db->query("SELECT "
                                    . "d.id"
                                    . ",od.gemstone_rate"
                                    . ",d.name as gemstone_name"
                                    . ",dm.name as gemstone_type"
                                    . ",dm1.name as gemstone_cut"
                                    . ",dm2.name as gemstone_shape"
                                    . ",dm3.name as gemstone_size"
                                    . ",dm4.name as gemstone_quality"
                                    . ",dm6.name as gemstone_origin"
                                    . ",ow.weight as gemstone_weight"
                                    . ",ow.no_of_peaces as no_of_peaces"
                                    . ",ow.rate as rate"
                                    . ",ow.cost as cost"
                                    . ",ow.avg_weight as avg_weight"

                                    . " FROM `orders_gemstone` od"
                                    . " JOIN gemstone d ON d.id=od.gemstone "
                                    . " JOIN order_weight_logs ow ON d.id=ow.gemstone_id "
                                    . " JOIN gemstone_masters dm ON od.gemstone_type=dm.id "
                                    . "and dm.type='2' "
                                    . " JOIN gemstone_masters dm2 ON od.gemstone_shape=dm2.id "
                                    . "and dm2.type='3' "
                                    . " JOIN gemstone_masters dm1 ON od.gemstone_cut=dm1.id "
                                    . "and dm1.type='1' "
                                    . " JOIN gemstone_masters dm3 ON od.gemstone_size=dm3.id "
                                    . "and dm3.type='5' "
                                    . " JOIN gemstone_masters dm4 ON od.gemstone_quality=dm4.id "
                                    . "and dm4.type='4' "
                                    . " JOIN gemstone_masters dm6 ON od.gemstone_origin=dm6.id "
                                    . "and dm6.type='6' "

                                    . " WHERE od.`order_id` = '$id'")->result_array();
                    ?>
                    <thead class="table-light">
                        <tr style="text-align: center;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th >#</th>
                            <th>Name</th>
                            <th>Cut</th>
                            <th>Shape</th>
                            <th>Type</th>
                            <th>Quality</th>
                            <th>Origin</th>
                            <th>Size</th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                    </thead>
                    <tbody style="font-size:12px;<?php
                    if (count($orders_diamonds) == 0) {
                        echo 'display:none;';
                    }
                    ?>">                                                            
                               <?php
                               $total_pcs = 0;

                               foreach ($orders_diamonds as $orders_diamonds_res) {
                                   ?>
                            <tr style="text-align: center;font-size:12px;">

                                <th>
                                    Gemstones
                                </th>
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_name'];
                                    ?>
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_cut'];
                                    ?>
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_shape'];
                                    ?>                                                                   
                                </th> 
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_type'];
                                    ?>
                                </th>                                                          

                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_quality'];
                                    ?>                                                                 
                                </th>                                                                  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_origin'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['gemstone_size'];
                                    ?>                                                                 
                                </th>                                                                 
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['no_of_peaces'] != '') {
                                        $total_pcs = $total_pcs + $orders_diamonds_res['no_of_peaces'];
                                        echo $orders_diamonds_res['no_of_peaces'];
                                    } else {
                                        echo '1';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['gemstone_weight'] != '') {
                                        echo $orders_diamonds_res['gemstone_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['avg_weight'] != '') {
                                        echo $orders_diamonds_res['avg_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['rate'] != '') {
                                        echo $orders_diamonds_res['rate'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>


                                <th>
                                    <?php
                                    if ($orders_diamonds_res['cost'] != '') {
                                        echo $orders_diamonds_res['cost'];
                                        ;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody> 
                    <tbody>
                        <tr style="text-align: center;font-size:12px;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th colspan="8" style="text-align:right">Total</th> 
                            <th ><?php echo $total_pcs; ?> </th> 
                            <th ><?php //echo $total_pcs;   ?> </th> 
                            <th ><?php //echo $total_pcs;  ?> </th> 
                            <th>
                                <?php
                                if ($orders['total_gemstone_weight'] != '') {
                                    echo $orders['total_gemstone_weight'];
                                    ;
                                } else {
                                    echo '0';
                                }
                                ?>

                            </th> 
                            <th class="final_total_gemstone_cost">
                                <?php
                                if ($orders['total_gemstone_cost'] != '') {
                                    echo $orders['total_gemstone_cost'];
                                    ;
                                } else {
                                    echo '0';
                                }
                                ?>
                            </th>
                        </tr>
                    </tbody>
                    <?php
                    $orders_diamonds = $this->db->query("SELECT "
                                    . "d.id"
                                    . ",od.pearl_rate"
                                    . ",d.name as pearl_name"
                                    . ",dm.name as pearl_shape"
                                    . ",dm1.name as pearl_type"
                                    . ",dm2.name as pearl_color"
                                    . ",dm3.name as pearl_size"
                                    . ",dm4.name as pearl_unit"
                                    . ",ow.weight as pearls_weight"
                                    . ",ow.no_of_peaces as no_of_peaces"
                                    . ",ow.rate as rate"
                                    . ",ow.cost as cost"
                                    . ",ow.avg_weight as avg_weight"

                                    . " FROM `orders_pearls` od"
                                    . " JOIN pearls d ON d.id=od.pearl "
                                    . " LEFT JOIN order_weight_logs ow ON d.id=ow.pearls_id "
                                    . " JOIN pearl_masters dm1 ON od.pearl_type=dm1.id and dm1.type='1' "
                                    . " JOIN pearl_masters dm ON od.pearl_shape=dm.id and dm.type='2' "
                                    . " JOIN pearl_masters dm2 ON od.pearl_color=dm2.id and dm2.type='3' "
                                    . " JOIN pearl_masters dm3 ON od.pearl_size=dm3.id and dm3.type='4' "
                                    . " JOIN pearl_masters dm4 ON od.pearl_unit=dm4.id and dm4.type='5' "

                                    . " WHERE od.`order_id` = '$id'")->result_array();
                    ?>
                    <thead class="table-light">
                        <tr style="text-align: center;font-size:12px;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th>#</th>
                            <th >Name</th>
                            <th>Type</th>
                            <th>Shape</th>
                            <th>Color</th>
                            <th>Size</th>
                            <th>Unit</th>
                            <th></th>
                            <th>Pieces</th>
                            <th>Weight(ct)</th>
                            <th>Avg. Weight</th>
                            <th>Rate</th>
                            <th>Cost</th>
                    </thead>
                    <tbody >                                                            
                        <?php
                        $total_pcs = 0;
                        $total_cost = 0;

                        foreach ($orders_diamonds as $orders_diamonds_res) {
                            ?>
                            <tr style="text-align: center;font-size:12px;<?php
                            if (count($orders_diamonds) == 0) {
                                echo 'display:none;';
                            }
                            ?>">
                                <th>Pearls</th>
                                <th>
                                    <?php

                                    echo $orders_diamonds_res['pearl_name'];
                                    ?>    
                                    <input type="hidden" name="pearls_id[]" 
                                           value="<?php echo $orders_diamonds_res['id']; ?>">

                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_type'];
                                    ?>
                                </th>   
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_shape'];
                                    ?>
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_color'];
                                    ?>                                                                
                                </th>                                                                
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_size'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php
                                    echo $orders_diamonds_res['pearl_unit'];
                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php

                                    ?>                                                                 
                                </th>  
                                <th>
                                    <?php


                                    if ($orders_diamonds_res['no_of_peaces'] != '') {
                                        $total_pcs = $total_pcs + $orders_diamonds_res['no_of_peaces'];
                                        echo $orders_diamonds_res['no_of_peaces'];
                                    } else {
                                        echo '1';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['pearls_weight'] != '') {
                                        echo $orders_diamonds_res['pearls_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>
                                <th>
                                    <?php
                                    if ($orders_diamonds_res['avg_weight'] != '') {
                                        echo $orders_diamonds_res['avg_weight'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>

                                <th>
                                    <?php
                                    if ($orders_diamonds_res['rate'] != '') {
                                        echo $orders_diamonds_res['rate'];
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>


                                <th>
                                    <?php
                                    if ($orders_diamonds_res['cost'] != '') {
                                        $total_cost = $total_cost + $orders_diamonds_res['cost'];
                                        echo $orders_diamonds_res['cost'];
                                        ;
                                    } else {
                                        echo '0';
                                    }
                                    ?>
                                </th>

                            </tr>
                            <?php
                        }
                        ?>
                    <tbody>
                        <tr style="text-align: center;font-size:12px;<?php
                        if (count($orders_diamonds) == 0) {
                            echo 'display:none;';
                        }
                        ?>">
                            <th colspan="8" style="text-align:right">Total</th> 
                            <th ><?php echo $total_pcs; ?> </th> 
                            <th ><?php //echo $total_pcs;  ?> </th> 
                            <th ><?php //echo $total_pcs;  ?> </th> 
                            <th>
                                <?php

                                if ($orders['total_pearls_weight'] != '') {
                                    echo $orders['total_pearls_weight'];
                                    ;
                                } else {
                                    echo '0';
                                }
                                ?>

                            </th> 
                            <th class="final_total_pearls_cost">
            <?php echo $total_cost; ?>
                            </th>
                        </tr>
                    </tbody>
                    </tbody>  
                </table>
            </div>

            <?php
        }
    }

    public function update_order_status() {

        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1

        $main_status_id = explode(",", $_POST['main_status_id']);
        $main_status = $_POST['main_status'];
        $main_comments = addslashes($_POST['main_comments']);
        foreach ($main_status_id as $id) {
            $currency = $this->db->query("update orders set "
                    . "update_status='$main_status'"
                    . " where id='$id' ");

            $currency = $this->db->query("insert into order_history set "
                    . "status='$main_status',"
                    . "user_id='$user_id',"
                    . "order_id='$id',"
                    . "message='$main_comments',"
                    . "added_date=NOW()"
                    . "");
        }
    }

    public function load_pure_metal_weight() {
        $id = $_POST['id'];
        $type = $_POST['type'];
        if ($type == 'total_item_cost') {
//        print_r($_POST);
            echo $final = trim($_POST['metal_cost']) + trim($_POST['value_added_total']) + trim($_POST['final_total_diamond_cost']) + trim($_POST['final_total_gemstone_cost']) + trim($_POST['final_total_pearls_cost']);
        } else {
            $currency = $this->db->query("SELECT i.total_net_weight,i.metal,m.name,m.purity,mp.metal_purity_percentage from inventory i "
                            . " LEFT JOIN metals m ON m.id=i.metal "
                            . " LEFT JOIN metal_purity mp ON mp.id=m.purity "
                            . "where i.id='$id'")->row_array();
            //print_r($currency);  
            echo $load_pure_metal_weight = round(($currency['total_net_weight'] * $currency['metal_purity_percentage']), 2);
        }
    }

    public function loadorderdetails() {
        if ($_POST['handle'] == 'orders' || $_POST['handle'] == 'archive-orders' || $_POST['handle'] == 'inventory') {
            $id = $_POST['id'];
            $currency = $this->db->query("SELECT s.*"
                            . ",c.name as category_name "
                            . ",sc.name as subcategory_name "
                            . ",m.name as metal_name "
                            . ",mf.name as metal_finish_name "
                            . ",CONCAT(pc.first_name,' ',pc.last_name) as vendor_name"
                            . " "
                            . "FROM `styles` s "
                            . " LEFT JOIN categories c ON c.id=s.category "
                            . " LEFT JOIN subcategories sc ON sc.id=s.subcategory "
                            . " LEFT JOIN metals m ON m.id=s.metal "
                            . " LEFT JOIN metal_finishes mf ON mf.id=s.metal_finish "
                            . " LEFT JOIN partners_customer pc ON pc.id=s.vendor "
                            . " where s.id='$id'")->row_array();
            echo json_encode($currency);
            echo '||||';

            echo json_encode(array("value" => $currency['category'], "text" => $currency['category_name']));
            echo '||||';

            echo json_encode(array("value" => $currency['subcategory'], "text" => $currency['subcategory_name']));
            echo '||||';

            echo json_encode(array("value" => $currency['metal'], "text" => $currency['metal_name']));
            echo '||||';

            echo json_encode(array("value" => $currency['metal_finish'], "text" => $currency['metal_finish_name']));
            echo '||||';

            echo json_encode(array("value" => $currency['vendor'], "text" => $currency['vendor_name']));
            echo '||||';
            $style_id = $currency['id'];

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';
            
         
        }

        if ($_POST['handle'] == 'add-new-style') {
            $id = $_POST['id'];

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            echo '||||';

            $currency = $this->db->query("SELECT * FROM `styles_images` where 	style_id='$id'")->result_array();
            foreach ($currency as $currency_res) {
                $imageContent = file_get_contents($currency_res['img']);
                $base64Image = base64_encode($imageContent);
                $imageInfo = getimagesizefromstring($imageContent);
                $imageType = $imageInfo['mime'];
                $dataUri = "data:$imageType;base64,$base64Image";
                ?>
                <div class="upload__img-box"><div style="background-image: url(<?php echo $dataUri; ?>)" data-number="0" data-file="6ad5c861-fcd7-4a0d-bde1-ced97e09eb09.jpg" class="img-bg">
                        <input type="hidden" name="img_id" id="img_id" value="<?php echo $currency_res['id']; ?>" />    
                        <div class="upload__img-close"></div></div></div>

                <?php
            }

            echo '||||';
//            print_r($_POST);
//            echo $currency = ("SELECT * FROM `styles_history` where style_id='$id'");
            $currency = $this->db->query("SELECT h.*,CONCAT(u.firstname,' ',u.lastname) as full_name "
                            . " FROM `styles_history` h "
                            . " LEFT JOIN users u ON u.id=h.user_id"
                            . " where h.style_id='$id'")->result_array();
//            print_r($currency);
            $lm = 0;
            if (count($currency) > 0) {
                ?>
                <table id="comment_history" class="table table-dark table-striped table-nowrap mb-0" >
                    <thead >
                        <tr style="font-size:12px;height:50px;">
                            <th colspan="4" align="center" >
                                <h6 style="text-align:center;color:white;">User's Style History</h6>
                            </th>
                        </tr>    
                        <tr style="font-size:12px;height:50px;">
                            <th >#</th>
                            <th >User</th>
                            <th >Added Date</th>
                            <th >Message</th>
                    </thead>
                    <tbody  style="font-size:12px;">
                        <?php
                    }
                    foreach ($currency as $currency_res) {
                        $lm++;
                        ?>
                        <tr style="font-size:11px;">
                            <th>
                                <?php echo $lm; ?>
                            </th>  
                            <th>
                                <?php echo $currency_res['full_name']; ?>
                            </th>  
                            <th>
                                <?php echo $currency_res['added_date']; ?>
                            </th>  
                            <th>
                        <?php echo $currency_res['message']; ?>
                            </th>  
                        </tr>
                        <?php
                    }
                    if (count($currency) > 0) {
                        ?>
                    </tbody>  
                </table>                                                
                <?php
            }
        }
    }

    public function check_email_exist() {
        $id = trim($_POST['id']);
        $currency = $this->db->query("SELECT id "
                        . "FROM `users` where 1 and email_id like '$id' ")->result_array();
        echo count($currency);
    }

    public function user_managment_extra_details() {
        $id = trim($_POST['id']);
        $currency = $this->db->query("SELECT * FROM `partners_customer` WHERE `user_id` = '$id'"
                        . " "
                        . "")->row_array();

        echo json_encode($currency);
    }

    public function load_user_extra_details() {
        $id = trim($_POST['id']);
        $user_data = $this->db->query("SELECT * FROM `users` where id='$id'")->row_array();
        $type = ($user_data['type']);
        if ($type == 1) {
            $form_data = $this->db->query("SELECT * FROM `form_build` where master!='partner' order by sort ASC")->result_array();
        } else if ($type == 2) {
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='partner' order by sort ASC")->result_array();
        } else {
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='partner' order by sort ASC")->result_array();
        }

        $k = 0;
        $l = 0;
        foreach ($form_data as $perameters_res) {
            $k++;
            if ($perameters_res['type'] != 'hidden') {
                $l++;
            }
            ?>
            <div class="extra_div_css <?php if ($perameters_res['type'] == 'hidden') { ?> div_extra_<?php echo $perameters_res['id']; ?> <?php } ?> <?php
            if ($perameters_res['div_width'] != '') {
                echo $perameters_res['div_width'];
            } else {
                echo 'col-md-12';
            }
            ?>  pull-left" style="<?php
                 if ($perameters_res['type'] == 'hidden') {
                     echo 'display:none;';
                 }
                 ?>float:left;" >
                 <?php
                 if ($perameters_res['type'] == 'file' || $perameters_res['type'] == 'email' || $perameters_res['type'] == 'number' || $perameters_res['type'] == 'text' || $perameters_res['type'] == 'hidden') {
                     ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                    <input  <?php
                    if ($perameters_res['sort'] == '1') {
                        echo 'focus';
                    }
                    ?> tabindex="<?php echo $k; ?>" <?php echo $perameters_res['readonly tabindex="-1"']; ?> <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                        type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                        name="<?php echo $perameters_res['name']; ?>" 
                        class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                        <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                        <?php } ?> />
                        <?php
                    } else if ($perameters_res['type'] == 'textarea') {
                        ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>

                    <textarea tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>"
                              id="<?php echo $perameters_res['id']; ?>" 
                              name="<?php echo $perameters_res['name']; ?>" 
                              class="form-control" style="<?php echo $perameters_res['styles']; ?>" <?php echo $perameters_res['mandatory']; ?> 
                              <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                              <?php } ?>><?php echo $perameters_res['data']; ?></textarea>
                              <?php
                          } else if ($perameters_res['type'] == 'blankdiv') {
                              ?>
                    <div class="col-md-12" style='clear:both;'></div>

                    <?php
                } else if ($perameters_res['type'] == 'cotable') {
                    ?>
                    <div class="col-md-12">
                        <table id="scroll-vertical" class="table table-bordered dt-responsive nowrap align-middle mdl-data-table" style="width:100%;">
                            <thead class="text-muted table-light">
                                <tr style="font-size:11px;">
                                    <th>Lot No</th>
                                    <th>Shape</th>
                                    <th>Size</th>
                                    <th>Color</th>
                                    <th>Clarity</th>
                                    <th>Category</th>
                                    <th>PCS</th>
                                    <th>Qty</th>
                                    <th>Amt INR</th>
                                    <th>Amt USD</th>
                                    <th>Desc.</th>
                                    <th>#</th>
                                </tr>
                            </thead>
                            <tbody id="add_data_res" style="font-size:10px;">

                            </tbody>
                            <tbody id="edit_data_res" style="font-size:11px;">
                                <tr>
                                    <td colspan="12">
                                        <a class="btn btn-sm btn-primary" onclick='$(".first_data").toggle(), $("#second_data").toggle();' style="float:right;">Add Item</a>    

                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <?php
                } else if ($perameters_res['type'] == 'date') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <input tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" value="<?php echo $perameters_res['data']; ?>"  
                           type="<?php echo $perameters_res['type']; ?>" id="<?php echo $perameters_res['id']; ?>" 
                           name="<?php echo $perameters_res['name']; ?>" 
                           class="form-control" <?php echo $perameters_res['mandatory']; ?> 
                           <?php if ($perameters_res[5] != '') { ?> <?php echo $perameters_res[5]; ?>="<?php echo $perameters_res[6]; ?>('<?php echo $perameters_res[7]; ?>');"  
                           <?php } ?> />
                           <?php
                       } else if ($perameters_res['type'] == 'customdiv') {
                           ?>
                           <?php
                           echo $perameters_res['data'];
                           ?>
                           <?php
                       } else if ($perameters_res['type'] == 'hr') {
                           ?>
                    <hr/>
                    <?php
                } else if ($perameters_res['type'] == 'radio' || $perameters_res['type'] == 'checkbox') {
                    ?>
                    <div class="form-check mb-2">
                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                        <br>
                        <?php
                        foreach ($perameters_res['data'] as $select_data) {
                            ?>
                            <div style="width:50%;float:left;">
                                <input  <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  class="form-check-input" type="<?php echo $perameters_res['type']; ?>" 
                                                                                                                                                 id="<?php echo $perameters_res['id']; ?>" name="<?php echo $perameters_res['name']; ?>" 
                                                                                                                                                 class="form-control" value="<?php echo $select_data['id']; ?>" 
                    <?php echo $perameters_res['mandatory']; ?> 
                                                                                                                                                 >
                                <span><?php echo $select_data['name']; ?></span>
                            </div>
                            <?php
                        }
                        ?>
                    </div>
                    <?php
                } else if ($perameters_res['type'] == 'select2') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['multiple']; ?> <?php echo $perameters_res['multiple']; ?>  <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2  form-control"  name="<?php echo $perameters_res['name']; ?><?php
                    if ($perameters_res['multiple'] == 'multiple') {
                        echo '[]';
                    }
                    ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                        <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                        <?php
                        foreach ($perameters_res['data'] as $select_data) {
                            ?>
                            <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                } else if ($perameters_res['type'] == 'hr') {
                    ?>
                    <hr/>
                    <?php
                } else if ($perameters_res['type'] == 'cleardiv') {
                    ?>
                    <div style="clear:both  !important;width:100% !important;"></div>
                    <?php
                } else if ($perameters_res['type'] == 'select3') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['multiple']; ?>  <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2 form-control" name="<?php echo $perameters_res['name']; ?><?php
                    if ($perameters_res['multiple'] == 'multiple') {
                        echo '[]';
                    }
                    ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                        <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                        <?php
                        foreach ($perameters_res['data'] as $select_data) {
                            ?>
                            <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                } else if ($perameters_res['type'] == 'select4') {

                    $width1 = "width:100% !important;";
                    $width2 = "width:0%; !important";
                    $width_3 = "0";

                    if ($perameters_res['classname'] == 'col-md-3') {
                        $ext_width = 95;
                        $ext_width2 = 5;
                    }
                    if ($perameters_res['classname'] == 'col-md-2') {
                        $ext_width = 90;
                        $ext_width2 = 10;
                    }
                    if ($perameters_res['add_btn'] == '1') {
                        $width1 = "width:" . $ext_width . "% !important;float:left !important;";
                        $width2 = "width:" . $ext_width2 . "% !important;float:left !important;";
                        $width_3 = "15";
                    }
                    ?>
                    <div style="<?php echo $width1; ?>">
                        <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                        <select tabindex="<?php echo $k; ?>"  <?php echo $perameters_res['multiple']; ?>  style="<?php echo $width1; ?>"  data-select-id="<?php echo $perameters_res['name']; ?>"   <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select4 form-control" name="<?php echo $perameters_res['name']; ?><?php
                        if ($perameters_res['multiple'] == 'multiple') {
                            echo '[]';
                        }
                        ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                            <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                            <?php
                            foreach ($perameters_res['data'] as $select_data) {
                                ?>
                                <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                                <?php
                            }
                            if ($perameters_res['tags'] == 1) {
                                ?>
                                <option value="add">Add New</option>
                                <?php
                            }
                            ?>
                        </select>
                    </div>
                    <?php
                    if ($width_3 > 0) {
                        ?>
                        <div style="<?php echo $width2; ?>">
                            <a target="_blanks" href="<?php echo base_url() . '' . $perameters_res['add_btn_handle'] . '?add=1'; ?>" style="width: 100%;float:left !important;margin-top: 25px;padding:3px 0px;" 
                               class=''>
                                <i class="bx bx-pencil"></i>
                            </a>
                        </div>
                        <?php
                    }
                    ?>
                    <div class="hide_details" id="<?php echo $perameters_res['name'] . '_details'; ?>"><?php echo $perameters_res['tags']; ?></div>
                    <div class="col-md-12" id="<?php echo $perameters_res['name'] . '_custom_message'; ?>">

                    </div>
                    <?php
                } else if ($perameters_res['type'] == 'datalist') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select tabindex="<?php echo $k; ?>"  <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" class="my-select2 form-control" name="<?php echo $perameters_res['name']; ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                        <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                        <?php
                        foreach ($perameters_res['data'] as $select_data) {
                            ?>
                            <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                } else if ($perameters_res['type'] == 'select') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label"><?php echo $perameters_res['label']; ?></label>
                    <select tabindex="<?php echo $k; ?>"   <?php echo $perameters_res['readonly tabindex="-1"']; ?>  <?php echo $perameters_res['addfunction']; ?>  placeholder="<?php echo $perameters_res['placeholder']; ?>" 
                            class="form-control" name="<?php echo $perameters_res['name']; ?><?php
                            if ($perameters_res['multiple'] == 'multiple') {
                                echo '[]';
                            }
                            ?>"  <?php echo $perameters_res['mandatory']; ?>   id="<?php echo $perameters_res['id']; ?>">
                        <option value="">--Select <?php echo $perameters_res['label']; ?>--</option>
                        <?php
                        foreach ($perameters_res['data'] as $select_data) {
                            ?>
                            <option value="<?php echo $select_data['id']; ?>"><?php echo $select_data['name']; ?></option>
                            <?php
                        }
                        ?>
                    </select>
                    <?php
                } else if ($perameters_res['type'] == 'div') {
                    echo $perameters_res['data'];
                } else if ($perameters_res['type'] == 'image_div') {
//                                                    echo '1';    
                    $image_upload = $this->load->view('extra/load_image_upload_design', $data, true);
                    echo $image_upload;
                } else if ($perameters_res['label'] != '' && $perameters_res['id'] == '') {
                    ?>
                    <label for="<?php echo $perameters_res['name']; ?>" class="form-label" style="text-decoration:underline;color:blue;font-weight:bold;"><?php echo $perameters_res['label']; ?></label>
                    <?php
                }
                ?>

            </div>
            <?php
        }

    }

    public function load_record_data() {

        if ($_POST['type'] == 'vendor_id') {
            $value = $_POST['value'];
            if ($value != '') {
                $sql = " p.id='$value' ";
            }

            $currency = $this->db->query("SELECT p.id as value,CONCAT(p.first_name,' ',p.last_name,' ',c.name) as text "
                            . "FROM `partners_customer` p "
                            . " LEFT JOIN company_name c ON c.id=p.company_name "
                            . " where 1 and $sql having text!=NULL OR text!='' ")->row_array();
            echo $jsonResponse = json_encode($currency);
        }
    }

    public function userpageaccess() {
        $searchTerm = $_GET['search'];
        $value = $_GET['value'];
        $selectId = $_GET['selectId'];
        $id = $_GET['id'];

        if ($selectId == 'edit_users') {
            $data['user_access'] = $this->db->query("select * from user_access where user_id='$id'")->result_array();
            $data['user_id'] = $id;
            echo $message2 = "<div id='load_extra_data'>" . $this->load->view('extra/usermanagement', $data, true) . '</div>';
        }

//        if($selectId == 'vendor' || $selectId == 'vendor_id') {
//    
//        }        
    }
    
   public function viewformdata() {
        $data = $_POST;
        $id = $data['id'];
        $handle = $data['handle'];
        $main_final_url = $data['main_final_url'];
        $currentUrl = $data['currentUrl'];
        $tablename = $data['tablename'];
    ?>

<style>
  
</style> 
    <?php if ($tablename=='users' && $main_final_url == base_url().'student_user'){ 
            $result = $this->db->query("select a.*,b.name as student_type,c.name as course_name,d.name as race_name,e.name as dialect_name,
            f.name as rel_name,a.nric  as nric_name,h.name as nat_name,i.name as city_name,j.name as state_name,k.name as country_name from users a 
            left outer join user_type b on a.type=b.id 
            left outer join courses c on c.id=a.course 
            left outer join races d on d.id=a.race 
            left outer join dialect e on e.id = a.dialect 
            left outer join religion f on f.id = a.religion 
            left outer join nationlity h on h.id = a.nationlity 
            left outer join city i on i.id = a.city 
            left outer join state j on j.id = a.state 
            left outer join country k on k.id = a.country 
             where  a.status!='2'  and   a.id = '$id'")->row_array();
    ?>
   
        <div class="user-profile">
            <div class="row">
            <div class="col-md-6">
            <table style="table-layout: fixed">
                <tr>
                    <td><strong>Image:</strong></td>
                    <td>
                        <?php
                        if (!empty($result['image'])) {
                            ?>
                            <img class="rounded-circle avatar-xl" alt="200x200" src="<?php echo $result['image']; ?>" data-holder-rendered="true">
                            <?php
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>                   
                </tr>
                <tr>
                    <td><strong>Type:</strong></td>
                    <td><?php echo $result['student_type']; ?></td>
                  </tr>
                   <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $result['username']; ?></td>
                </tr>
                 <tr>
                       <td><strong>Full Name:</strong></td>
                    <td><?php echo $result['full_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Alias Name:</strong></td>
                    <td><?php echo $result['alias_name']; ?></td>
                </tr>
                
                    <td><strong>Age:</strong></td>
                    <td><?php echo $result['age']; ?></td>
                </tr>
                <tr> <td><strong>Date Of Birth:</strong></td>
                    <td><?php echo $result['dob']; ?></td></tr>
                <tr>
                    <td><strong>Number:</strong></td>
                    <td><?php echo $result['contact_number']; ?></td>
                </tr>
                <tr>
                    <td><strong>Created Date:</strong></td>
                    <td><?php echo date('F j, Y', strtotime($result['created_at'])); ?></td>
                </tr> 
                <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php echo date('F j, Y', strtotime($result['updated_at'])); ?></td>
                </tr>
                  <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $result['contact_email']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Firebase Token:</strong></td>
                    <td style="word-wrap: break-word"><?php echo $result['firebase_token']; ?></td>
                </tr>
                 <tr> <td><strong>Address:</strong></td>
                    <td><?php echo $result['address']; ?></td></tr>
            </table>

                </div>
                <div class="col-md-6">
                    <table>
                     <tr>
                    <td><strong>City:</strong></td>
                    <td><?php echo $result['city_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>State:</strong></td>
                    <td><?php echo $result['state_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Country:</strong></td>
                    <td><?php echo $result['country_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Race:</strong></td>
                    <td><?php echo $result['race_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Dialect:</strong></td>
                    <td><?php echo $result['dialect_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>NRIC:</strong></td>
                    <td><?php echo $result['nric_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Nationlity:</strong></td>
                    <td><?php echo $result['nat_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Student Id:</strong></td>
                    <td><?php echo $result['student_id']; ?></td>
                </tr>
                   <tr>
                    <td><strong>Course:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Emergency Relationship:</strong></td>
                    <td><?php echo $result['emergency_relationship']; ?></td>
                </tr>
                <tr>
                    <td><strong>Emergency Email:</strong></td>
                    <td><?php echo $result['emergency_email']; ?></td>
                </tr>
                <tr>
                    <td><strong>Religion:</strong></td>
                    <td><?php echo $result['rel_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Emergency Name:</strong></td>
                    <td><?php echo $result['emergency_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Emergency Number:</strong></td>
                    <td><?php echo $result['emergency_number']; ?></td>
                </tr>

            </table>
                </div>
        </div>
    </div>

    <?php 
       }
       ?>
<?php  if ($tablename=='users' && $main_final_url == base_url().'parent_user'){
    $result = $this->db->query("select a.*,b.name as parent_type,
                              i.name as city_name,j.name as state_name,k.name as country_name from users a "
                                ."left outer join user_type b on a.type=b.id "
                               ."left outer join city i on i.id = a.city "
                               ."left outer join state j on j.id = a.state " 
                               ."left outer join country k on k.id = a.country "
                              . " where  a.status!='2'  and   a.id = '$id' ")->row_array();
    $created_at = $result['created_at'];
    $updated_at = $result['updated_at'];
?>
   
        <div class="user-profile">
            <div class="row">
            <div class="col-md-6">
            <table>
                  <tr>
                    <td><strong>Image:</strong></td>
                    <td>
                        <?php
                        if (!empty($result['image'])) {
                            ?>
                            <img class="rounded-circle avatar-xl" alt="200x200" src="<?php echo $result['image']; ?>" data-holder-rendered="true">
                            <?php
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>                   
                </tr>
                <tr>
                    <td><strong>Type:</strong></td>
                    <td><?php echo $result['parent_type']; ?></td>
                </tr>
                   <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $result['username']; ?></td>
                </tr>
                 <tr>
                       <td><strong>Full Name:</strong></td>
                    <td><?php echo $result['full_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Alias Name:</strong></td>
                    <td><?php echo $result['alias_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Age:</strong></td>
                    <td><?php echo $result['age']; ?></td>
                </tr>
                <tr> 
                    <td><strong>Date Of Birth:</strong></td>
                    <td><?php echo $result['dob']; ?></td></tr>
               
               <tr>
    <td><strong>Created Date:</strong></td>
    <td>
        <?php 
        if($created_at == '0000-00-00 00:00:00') {
            echo "-";
        } else {
            echo date('F j, Y', strtotime($created_at)); 
        } 
        ?>
    </td>
</tr>
<tr>
    <td><strong>Updated Date:</strong></td>
    <td>
        <?php 
        if($updated_at == '0000-00-00 00:00:00') {
            echo "-";
        } else {
            echo date('F j, Y', strtotime($updated_at)); 
        } 
        ?>
    </td>
</tr>
    </table>
                </div>
                <div class="col-md-6">
                    <table>
                        <tr>
                    <td><strong>Number:</strong></td>
                    <td><?php echo $result['contact_number']; ?></td>
                </tr>
                        <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $result['contact_email']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Firebase Token:</strong></td>
                    <td><?php echo $result['firebase_token']; ?></td>
                </tr>
                 <tr> <td><strong>Address:</strong></td>
                    <td><?php echo $result['address']; ?></td></tr>
<!--                 <tr>
                    <td><strong>Password:</strong></td>
                    <td><?php echo $result['password']; ?></td>
                </tr> -->
                 <tr>
                    <td><strong>Social Id:</strong></td>
                    <td><?php echo $result['social_id']; ?></td>
                </tr>
                     <tr>
                    <td><strong>City:</strong></td>
                    <td><?php echo $result['city_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>State:</strong></td>
                    <td><?php echo $result['state_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Country:</strong></td>
                    <td><?php echo $result['country_name']; ?></td>
                </tr>
             
               
            </table>
                </div>
        </div>
    </div>
    <?php 
       }
       ?>
   
   <?php if($tablename=='religion'){
    $result = $this->db->query("SELECT * from religion where id = $id;" )->row_array();
    $updated_date=$result['updated_date'];
    $added_date=$result['added_date'];
       ?>
           
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                
                <tr>
                    <td><strong>Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
               </div>
               </div>
</div>
   
   <?php }
    ?>

<?php if($tablename=='add_account'){
    $result = $this->db->query("SELECT a.*, b.name as account_type from users a"
    ."  left outer join user_type b on b.id=a.type where a.id = $id "  
     )->row_array();
    $updated_date=$result['updated_at'];
    $added_date=$result['created_at'];
       ?>
           
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                
                <tr>
                    <td><strong>Name:</strong></td>
                    <td><?php echo $result['full_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $result['username']; ?></td>
                </tr>
                <tr>
                    <td><strong>Image:</strong></td>
                    <td><?php echo $result['image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Contact Email:</strong></td>
                    <td><?php echo $result['contact_email']; ?></td>
                </tr>
                <tr>
                    <td><strong>Type:</strong></td>
                    <td><?php echo $result['account_type']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
               </div>
               </div>
</div>
   
   <?php }
    ?>   

<?php if ($tablename =='nationlity'){
    
$result = $this->db->query("select * from nationlity where id=$id;")->row_array();
$updated_date=$result['updated_date'];
$added_date = $result['added_date'];
?>

  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Nationlity Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
               </table>
               </div>
               </div>
</div>
<?php } ?>  

<?php if ($tablename =='city')
{
    $result = $this->db->query("select * from city where id=$id;")->row_array();
    $updated_date='';
    $updated_date=$result['updated_date'];

    
    ?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>City Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                    <td><?php echo date('F j, Y', strtotime($result['added_date'])); ?></td>
                </tr>
                <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
                </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='country'){
    
$result = $this->db->query("select * from country where id=$id;")->row_array();
$added_date =$result['added_date'];
$updated_date =$result['updated_date'];

?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Country Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
          </table>
               </div>
               </div>
</div>
<?php } ?>

    <?php if ($tablename =='state'){
    
$result = $this->db->query("select * from state where id=$id;")->row_array();
$country_id = $result['country_id'];
$result2 = $this->db->query("select * from country where id=$country_id")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
            <tr>
                    <td><strong>State Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Country Name:</strong></td>
                    <td><?php echo $result2['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
          </table>
               </div>
               </div>
</div>
<?php } ?>
 
<?php if ($tablename =='races'){
    
$result = $this->db->query("select * from races where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Race Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
          </table>
               </div>
               </div>
</div>
<?php } ?>
 

<?php if ($tablename =='dialect'){
    
$result = $this->db->query("select * from dialect where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Dialect Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='age_group'){
    
$result = $this->db->query("select * from age_group where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>

  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Minimum age:</strong></td>
                    <td><?php echo $result['min_age']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Maximum age:</strong></td>
                    <td><?php echo $result['max_age']; ?></td>
                </tr>
               
               <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
       </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='upcoming_classes'){
    
    $result = $this->db->query("select a.*,b.chapter_name as chaptername,b.chapter_no as chapterno,c.name as course_name,d.name as type_name from upcoming_classes a " 
                                ."left outer join chapter b on b.id=a.chapter_id "
                               ." left outer join courses c on c.id=b.course_id "
                                ." left outer join course_type d on d.id=c.course_type_id "
                                 ." where a.id=$id and a.status!='2';")->row_array();
    $added_date=$result['added_date'];
    $updated_date=$result['updated_date'];
    ?>
    
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                <table>
                    <tr>
                        <td><strong>Chapter No:</strong></td>
                        <td><?php echo $result['chapterno']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Chapter Name:</strong></td>
                        <td><?php echo $result['chaptername']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Course Type :</strong></td>
                        <td><?php echo $result['type_name']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Course Name:</strong></td>
                        <td><?php echo $result['course_name']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Upcoming Date:</strong></td>
                        <td><?php echo $result['upcoming_date']; ?></td>
                    </tr>
                   
                   <tr>
                        <td><strong>Added Date:</strong></td>
                         <td><?php 
                        if($added_date=='0000-00-00 00:00:00')
                            {
                               echo  $added_date="-";    
                            }else
                            {
                                echo date ('F j,Y',strtotime($added_date)); 
                            } 
                        ?></td>
                    </tr>
                     <tr>
                        <td><strong>:</strong></td>
                        <td><?php 
                        if($updated_date=='0000-00-00 00:00:00')
                            {
                               echo  $updated_date="-";    
                            }else
                            {
                                echo date ('F j,Y',strtotime($updated_date)); 
                            } 
                        ?></td>
                    </tr>
           </table>
                   </div>
                   </div>
    </div>
    <?php } ?>

<?php if ($tablename =='event_transaction') {
    $result = $this->db->query("select b.username as parent_name, c.username as child_name, d.event as event_name, d.amount,
    a.payment_status, a.payment_method, a.card_type, a.transaction_id, a.created_at from event_transaction a
    left outer join users b on b.id = a.user_id
    left outer join users c on c.id = a.child_id
    left outer join events d on d.id = a.event_id where b.status != '2' and c.status != '2' and d.status != '2' and a.id = '$id'")->row_array();

    ?>
    
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                    <table>
                        <tr>
                            <td><strong>Parent Name :</strong></td>
                            <td><?php echo $result['parent_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Child Name :</strong></td>
                            <td><?php echo $result['child_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Event Name :</strong></td>
                            <td><?php echo $result['event_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Amount :</strong></td>
                            <td><?php echo $result['amount']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Payment Method :</strong></td>
                            <td><?php echo $result['payment_method']; ?></td>
                        </tr>                   
                        <tr>
                            <td><strong>Card Type :</strong></td>
                            <td><?php echo $result['card_type']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Transaction Id :</strong></td>
                            <td><?php echo $result['transaction_id']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Payment status :</strong></td>
                            <td><?php echo $result['payment_status']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created At :</strong></td>
                            <td><?php echo $result['created_at']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
    </div>
    <?php } ?>

    <?php if ($tablename =='user_tutorial_subscription') {
        $result = $this->db->query("select b.username as parent_name, c.username as child_name, a.start, a.end,
        a.auto_subscription, a.is_active, a.created_at, a.updated_at from user_tutorial_subscription a 
        left outer join users b on b.id = a.parent_id 
        left outer join users c on c.id = a.student_id where b.status != '2' and c.status != '2' and a.id = '$id'")->row_array();
    ?>
    
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                    <table>
                        <tr>
                            <td><strong>Parent Name :</strong></td>
                            <td><?php echo $result['parent_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Child Name :</strong></td>
                            <td><?php echo $result['child_name']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Start Date :</strong></td>
                            <td><?php echo $result['start']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>End Date :</strong></td>
                            <td><?php echo $result['end']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Auto Subscription :</strong></td>
                            <td><?php echo $result['auto_subscription']; ?></td>
                        </tr>     
                        <tr>
                            <td><strong>Is Active :</strong></td>
                            <td><?php echo $result['is_active']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Created At :</strong></td>
                            <td><?php echo $result['created_at']; ?></td>
                        </tr>
                        <tr>
                            <td><strong>Updated At :</strong></td>
                            <td><?php echo $result['updated_at']; ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>

<?php if ($tablename =='child_parent_relationship'){
    
$result = $this->db->query("select a.*,c.username as parentname,
    d.name as request,c.full_name as fullname,c.contact_email as email,c.social_id as social,
    c.alias_name as aliasname,c.dob as Dob,c.address as Address,c.contact_number,c.age from child_parent_relationship a 
                    left outer join users b on a.child_id=b.student_id 
                    left outer join users c on a.parent_id=c.id 
                    left outer join select_type d on a.request_status=d.id
                     where a.id='$id' and a.status !='2' ")->row_array();
$user_id = $result['parent_id'];

$result2 = $this->db->query("select a.*,c.username as parentname,
    d.name as request,c.full_name as fullname,c.contact_email as email,c.social_id as social,
    c.alias_name as aliasname,c.dob as Dob,c.address as Address,c.contact_number,c.age from child_parent_relationship a 
                    left outer join users b on a.child_id=b.student_id 
                    left outer join users c on a.parent_id=c.id 
                    left outer join select_type d on a.request_status=d.id
                    where a.parent_id='$user_id' and a.status !='2' ")->result_array();


$added_date=$result['added_date'];
$updated_date=$result['updated_date'];

?>

        <div class="user-profile">
            <div class="row">
                  <div class="col-md-6">

            <table>
                <tr>
                    <td><strong>Parent Name</strong></td>
                    <td><?php echo $result['parentname']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Fullname:</strong></td>
                    <td><?php echo $result['fullname']; ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $result['email']; ?></td>
                
                 
                </table>
                </div>
                <div class="col-md-6">
                    <table>
                
                <tr>
                    <td><strong>Number:</strong></td>
                    <td><?php echo $result['contact_number']; ?></td>
                </tr>
                 
               <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
                
            </table>
               </div>
               </div>
               </div>

<?php
$result = $this->db->query("select a.*,b.username as childname,e.name as course,
    d.name as request,b.full_name as fullname,b.contact_email as email,b.student_id as student,
    e.name as course_name,b.contact_number,b.emergency_name,b.emergency_relationship,b.emergency_number,b.emergency_email
    ,b.alias_name as aliasname,b.dob as Dob,b.address as Address from child_parent_relationship a 
                     left outer join users b on a.child_id=b.student_id 
                     left outer join mycart c on b.id=c.child_id 
                     left outer join courses e on c.course_id=e.id
                     left outer join select_type d on a.request_status=d.id
                     where a.parent_id='$user_id' and a.status !='2' ")->result_array();

$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
$i = 1;
?>

        <?php foreach ($result as $results): ?>
    <div class="user-profile">
            <div style="margin-top:30px;"class="row">
                  <div class="col-md-6">
                <h5 >Child <?php echo $i++;?></h5>
            <table>
                <tr>
                    <td><strong>Student Name</strong></td>
                    <td><?php echo $results['childname']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Fullname:</strong></td>
                    <td><?php echo $results['fullname']; ?></td>
                </tr>
                <tr>
                    <td><strong>Student:</strong></td>
                    <td><?php echo $results['student']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course:</strong></td>
                    <td><?php echo $results['course_name']; ?></td>
                </tr>
                                </table>
                </div>
                <div  class="col-md-6">
                    <h5  style="color:white;">Child </h5>
                    <table>
               <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($results['added_date'])); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($results['added_date'])); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Request Status:</strong></td>
                    <td><?php echo $results['request']; ?></td>
                </tr>
            </table>
               </div>
               </div>
               </div>

        <?php endforeach; ?>

<?php
} ?>


<?php if ($tablename =='courses'){
    
$result = $this->db->query("select a.id,count(d.course_id) as lesson,a.name,a.image,a.amount,a.lecture_days, CONCAT(c.min_age, ' - ', c.max_age)
                    as age_group_id,a.course_time,a.course_duration,a.description,a.course_details_description,
                    a.service_cost,a.addon,a.tax,a.added_date,a.updated_date,b.name as cName from courses a " 
                    ."left outer join course_type b on a.course_type_id=b.id "  
                     ."left outer join age_group c on c.id = a.age_group_id "
                    ."left outer join chapter d on d.course_id = a.id and d.status = '0' "
                    . " where a.id= '$id' and a.status!='2'  group by a.id ")->row_array();
$course_id = $result['id']; 
$course_duration = $this->calculateCourseDuration($course_id);
$courseTimeSlotAndDays = $this->getCourseAllTimeSlotAndDays($course_id);
$timeSlots = ($courseTimeSlotAndDays['timeSlots'] != '') ? implode(", ", $courseTimeSlotAndDays['timeSlots']) : '';
$lectureDays = ($courseTimeSlotAndDays['days'] != '') ? implode(", ", $courseTimeSlotAndDays['days']) : '';
$main_id = '';
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
<div class="user-profile">
    <div class="row">
        <div class="col-md-6">
    <table>
    <tr>
            <td style="font-size: 12px;"><str
            ong>Image:</str></td>
            <td style="text-align: center;">
     <div style="display: inline-block; text-align: center;">
        <?php
        if (!empty($result['image'])) {
            ?>
            <img class="avatar-xl" alt="150x150" style="width:200px; height: auto;" src="<?php echo $result['image']; ?>" data-holder-rendered="true">
            <?php
        } else {
            echo "-";
        }
        ?>
    </div>
</td>
</tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Course Type:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['cName']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Course Name:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['name']; ?></td>
                </tr>
                 
                 <tr>
                    <td style="font-size: 12px;"><strong>Lesson:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['lesson']; ?></td>
                </tr>
                 <tr>
                    <td style="font-size: 12px;"><strong>Amount:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['amount']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Lecture Days:</strong></td>
                    <td style="font-size: 12px;"><?php echo $lectureDays; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Time Slots:</strong></td>
                    <td style="font-size: 12px;"><?php echo $timeSlots; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Age Group:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['age_group_id']; ?></td>
                </tr>
                 </table>
                </div>
                <div class="col-md-6">
                    <table>
                <tr>
                    <td style="font-size: 12px;"><strong>Course Duration:</strong></td>
                    <td style="font-size: 12px;"><?php echo $course_duration; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Service Cost:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['service_cost']; ?></td>
                </tr>
                 <tr>
                    <td style="font-size: 12px;"><strong>Addon:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['addon']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Description:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['description']; ?></td>
                </tr>
                 <tr>
                    <td style="font-size: 12px;"><strong>Tax:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['tax']; ?></td>
                </tr>
                
                 <tr>
                    <td style="font-size: 12px;"><strong>Course Details Description:</strong></td>
                    <td style="font-size: 12px;"><?php echo $result['course_details_description']; ?></td>
                </tr>
                <tr>
                    <td style="font-size: 12px;"><strong>Added Date:</strong></td>
                     <td style="font-size: 12px;"><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td style="font-size: 12px;"><strong>Updated Date:</strong></td>
                    <td style="font-size: 12px;"><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
                
               </div>
               </div>
               </div>                 
  <!--LIST OF LESSONS-->
<div class="row cstm-modal">
   <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Lesson</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openUpdateForm('<?php echo $course_id; ?>','', '<?php echo $this->generateChapterNumber($course_id); ?>','');" ><i class="ri-add-line align-bottom me-1"></i> Add Lesson</button>
        </div>
    </div>  
<div class="table-responsive table-car">
<div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
    <table id="ajax_datatables2" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
    <thead class="table-light">
    <tr>
        <th>Action</th>
        <th>Lesson Number</th>
        <th>Lesson Name</th>
        <th>Created At</th>
        <th>Updated At</th>
    </tr>
</thead>
<tbody>
<?php 
    $check_chapter = $this->db->query("select a.*,b.name as course_name from chapter a 
    left outer join courses b on b.id = a.course_id
    where a.course_id = '$course_id' and a.status = '0' order by added_date desc ")->result_array();
    foreach ($check_chapter as $results){
        $chapter_no = $results['chapter_no'];
        $chapter_name = $results['chapter_name'];
        $chapterid = $results['id'];
?>
    <tr class="odd">
        <td>
            <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openUpdateForm('<?php echo $course_id; ?>','<?php echo $chapterid; ?>', '<?php echo $chapter_no; ?>','<?php echo $chapter_name; ?>');"><i class="bx bx-pencil"></i></a>
           <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteChapterRecord('<?php echo $chapterid; ?>','chapter');"><i class="ri-delete-bin-5-line"></i></a>
        </td>
        <td><?php echo $results['chapter_no']; ?></td>
        <td><?php echo $results['chapter_name'];  $main_id= $results['id']; ?></td>
        <td><?php echo $results['added_date']; ?></td>
        <td><?php echo $results['updated_date']; ?></td>
    </tr>
<?php } ?>
       </tbody>
    </table>
</div>
</div>
</div>


  <!--LIST OF MASTER CLASSES-->
<div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Master Classes</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openUpdateFormMasterClass('','', '', '');" ><i class="ri-add-line align-bottom me-1"></i> Add Master Class </button>
    </div>
</div>  
<div class="table-responsive table-car">
    <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
        <table id="ajax_datatables2_master" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
            <thead class="table-light">
                <tr>
                    <th>Action</th>
                    <th>Lesson Name</th>
                    <th>Class Name</th>
                    <th>Class Duration</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $masterClasses = $this->getCourseAllMasterClasses($course_id);
                foreach ($masterClasses as $results) {
                    $masterClassId = $results['id'];
                    $chapterId = $results['chapter_id'];
                    $className = $results['class_name'];
                    $classDuration = $results['class_duration'];
                ?>
                <tr class="odd">
                    <td>
                        <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openUpdateFormMasterClass('<?php echo $masterClassId; ?>','<?php echo $chapterId; ?>', '<?php echo $className; ?>', '<?php echo $classDuration; ?>');"><i class="bx bx-pencil"></i></a>
                        <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteMasterClassRecord('<?php echo $masterClassId; ?>','masterclass');"><i class="ri-delete-bin-5-line"></i></a>
                    </td>
                    <td><?php echo $results['chapter_name']; ?></td>
                    <td><?php echo $className; ?></td>
                    <td><?php echo $classDuration;  $main_id= $results['id']; ?></td>
                    <td><?php echo $results['created_at']; ?></td>
                    <td><?php echo $results['updated_at']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!--LIST OF ClASSES--> 
<div class="row cstm-modal">
   <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Classes</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" id="cldViewBtn" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;margin-right:20px" onclick="$('#formModalClassesView').modal('show');" ><i class="ri-add-line align-bottom me-1"></i> View Classes</button>    
        <button type="button"  class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openUpdateFormClasses('<?php echo $course_id; ?>');" ><i class="ri-add-line align-bottom me-1"></i> Add Classes</button>
        </div>
    </div>  
<div class="table-responsive table-car">
<div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
    <table id="ajax_datatables5" class="table align-middle table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
    <thead class="table-light">
    <tr>
        <th>Action</th>
        <th>Lesson Name</th>
        <th>Master Class Name</th>
        <th>Class Title</th>
        <th>Class Date</th>
        <th>Start Time</th>
        <th>Location</th>
        <th>Recurring Class</th>
        <th>Class Status</th>
        <th>Added Date</th>
        <th>Updated Date</th>
        <th>Qr Code</th>  
        <th>Attendance</th> 
    </tr>
</thead> 
<tbody>
<?php 

$event = $this->db->query("select a.upcoming_date, a.start,a.end, a.recurring,a.title from upcoming_classes a 
left outer join master_classes b on b.id = a.master_class_id
left outer join chapter f on b.chapter_id = f.id
left outer join class_location e on a.location = e.id
left outer join courses c on c.id = f.course_id
left outer join course_type d on d.id = c.course_type_id
where f.course_id = '$course_id' and a.status ='0' and b.status = '0' ")->result_array();
$event_data = array();
foreach($event as $list){
    $st = strtotime($list['upcoming_date']);
    $incoming_date = date('Y-m-d', $st);
    $end_date = $incoming_date.' '.$list['end'];
    $arr = array( 'start' => $list['upcoming_date'], 'end'=> $end_date, 'title'=> $list['title'], 'color'=> 'red');
    array_push($event_data, $arr);
}

$initialDate = date('Y-m-d');
$events = json_encode($event_data);
$check_location = $this->db->query("select * from class_location where status = '0' ")->result_array();
$check_master_query = "select a.* from master_classes a " 
." left outer join chapter f on a.chapter_id=f.id " 
." left outer join courses c on c.id=f.course_id "
 ." where f.course_id = '$course_id' and a.status ='0' and f.status = '0'" ;
$check_master_classes = $this->db->query($check_master_query)->result_array();

$classes_query = "select a.*,b.class_name as master_name,e.name as location_name,c.name as course_name,d.name as type_name, f.chapter_name as chapter_name from upcoming_classes a " 
." left outer join master_classes b on b.id=a.master_class_id " 
." left outer join chapter f on b.chapter_id=f.id " 
." left outer join class_location e on a.location=e.id "
." left outer join courses c on c.id=f.course_id "
." left outer join course_type d on d.id=c.course_type_id "
 ." where f.course_id = '$course_id' and a.status ='0' and b.status = '0' order by a.added_date desc" ;
 $classes_result = $this->db->query($classes_query)->result_array(); 

//  date_default_timezone_set("asia/Kuala_Lumpur");
//date_default_timezone_set("Asia/Calcutta");
 $currentDateTime = date('Y-m-d H:i:s');
 foreach ($classes_result as $results){
    $id = $results['id'];
    $classEndTime = date('Y-m-d H:i:s', strtotime('+1 hour', strtotime($results['upcoming_date'])));
?>   
    <tr class="odd" style="<?php if($currentDateTime > $classEndTime || $results['class_status'] === 'Cancel'){ echo "background-color: #c8c7c7"; } ?>">
        <td>
            <a style="padding:3px; <?php if($currentDateTime > $classEndTime || $results['class_status'] === 'Cancel') echo "pointer-events: none";?>" class="btn btn-primary btn-sm" onclick="rescheduleClass('<?php echo $results['id']; ?>','<?php echo $results['title']; ?>','<?php echo $results['master_name']; ?>','<?php echo $results['upcoming_date']; ?>','<?php echo $results['location_name']; ?>')">Reschedule</a>
            <a style="padding:3px; <?php if($currentDateTime > $classEndTime || $results['class_status'] === 'Cancel') echo "pointer-events: none";?>" onclick="updateClassStatus('<?php echo $results['id']; ?>','Cancel')" class="btn btn-danger btn-sm">Cancel</a>
            <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteClassesRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
        </td>
     
        <td><?php echo $results['chapter_name']; ?></td>
        <td><?php echo $results['master_name'];  $main_id= $results['id']; ?></td>
        <td><?php echo $results['title'] ?></td>
        <td><?php echo $results['upcoming_date'] ?></td>
        <td><?php echo $results['start'] ?></td>
        <td><?php echo $results['location_name'] ?></td> 
        <td><?php echo $results['recurring'] ?></td>
        <td><?php echo $results['class_status'] ?></td>
        <td><?php echo $results['added_date']; ?></td>
        <td><?php echo $results['updated_date']; ?></td>
        <td><a href="<?php echo 'download_qr/'.$id  ?>" class="btn btn-primary btn-sm btn-primary" style="padding:3px; <?php if($currentDateTime > $classEndTime || $results['class_status'] === 'Cancel') echo "pointer-events: none";?>">Qrcode</a></td>
        <td><button class="btn btn-primary btn-sm" onclick="openAttendanceList(<?php echo $results['id']; ?>)">Attendance</button></td>
    </tr>
<?php } ?>
       </tbody>
    </table>
</div>
</div>
</div
<!--LIST OF ClASSES--> 
<!--LIST OF EXERCISE--> 
<div class="row cstm-modal">
   <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Exercise</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openUpdateFormExercise('<?php echo $course_id; ?>','', '', '', '','','');" ><i class="ri-add-line align-bottom me-1"></i> Add Exercise</button>
        </div>
    </div>  
<div class="table-responsive table-car">
<div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
    <table id="ajax_datatables3" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
    <thead class="table-light">
    <tr>
        <th>Action</th>
        <th>Lesson Name</th>
        <th>Exercise Number</th>
        <th>Task</th>
        <th>File</th>
        <th>Duration</th>
        <th>Submit Date</th>
        <th>Added Date</th>
        <th>Updated Date</th>
    </tr>
</thead>
<tbody>
<?php 
    $currentDate = date('Y-m-d');
    $check_exercise = $this->db->query("select a.*,b.chapter_name as chapter_name,b.course_id from course_exercise a
        left outer join chapter b on b.id = a.chapter_id 
        where b.course_id = '$course_id' and a.status = '0' and a.submit_date >= '$currentDate' order by a.added_date desc;")->result_array();

        foreach ($check_exercise as $results){
            $exercise_no=$results['exercise_no'];
            $task=$results['task'];
            $chapter_name=$results['chapter_name'];
            $chapter_id=$results['chapter_id'];            
            $time=$results['time'];
            $exercise_id=$results['id'];
            $submit_date=$results['submit_date'];            
?> 
    <tr class="odd">
        <td>
        <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openUpdateFormExercise('<?php echo $course_id; ?>','<?php echo $exercise_id; ?>', '<?php echo $chapter_id; ?>', '<?php echo $exercise_no; ?>', '<?php echo $task; ?>', '<?php echo $time; ?>','<?php echo $submit_date; ?>');"><i class="bx bx-pencil"></i></a>
        <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteExerciseRecord('<?php echo $exercise_id; ?>');"><i class="ri-delete-bin-5-line"></i></a>
        </td>
        <td><?php echo $results['chapter_name'];  $main_id= $results['id']; ?></td>
        <td><?php echo $results['exercise_no']; ?></td>
        <td><?php echo $results['task']; ?></td>
        <td><?php echo $results['file']; ?></td>
        <td><?php echo $results['time']; ?></td>
        <td><?php echo $submit_date; ?></td>
        <td><?php echo $results['added_date']; ?></td>
        <td><?php echo $results['updated_date']; ?></td>
    </tr>
<?php } ?>
       </tbody>
    </table>
</div>
</div>
</div>


  <!--LIST OF HOMEWORK-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Homework</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openHomeworkForm('','<?php echo $course_id; ?>','','','false');" ><i class="ri-add-line align-bottom me-1"></i> Add Homework</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_homework" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Lesson Number</th>
                        <th>Lesson Name</th>
                        <th>Homework Title</th>
                        <th>Homework Material</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $Homeworks = $this->db->query("select a.*,c.chapter_no,c.chapter_name from homework a                    
                    left outer join course_exercise b on b.id = a.exercise_id
                    left outer join chapter c on c.id = b.chapter_id 
                    left outer join courses d on d.id = c.course_id
                    where d.id = '$course_id' and  a.status != '2' and b.status != '2' and c.status != '2' order by added_date desc")->result_array();
                    foreach ($Homeworks as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openHomeworkForm('<?php echo $results['id']; ?>','<?php echo $course_id; ?>','<?php echo $results['homework_title']; ?>','<?php echo $results['exercise_id']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteHomeworkRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['chapter_no']; ?></td>
                            <td><?php echo $results['chapter_name'];?></td>
                            <td><?php echo $results['homework_title']; ?></td>
                            <td><?php echo $results['homework_material']; ?></td>
                            <td><?php echo $results['added_date']; ?></td>        
                            <td><?php echo $results['updated_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--HOMEWORK MODEL-->
<div id="homeworkFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Homework</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
      <img class="avatar-xl" id="home-loader" alt="150x150" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);z-index: 100;display:none" src="uploads/courseimage/Loading_icon.gif" data-holder-rendered="true">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="homeworkId" name="homeworkId">
            <input type="hidden" id="courseId" name="courseId">
            <input type="hidden" id="hEdit" name="hEdit">

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Exercises</label>
                    <select class="col-md-12" name="exerciseId" id="exerciseId" size="3" multiple>
                        <?php
                        $currentDate = date('Y-m-d');
                        $exerciseList = $this->db->query("select a.id,a.task as name from course_exercise a 
                        left outer join chapter b on b.id = a.chapter_id 
                        left outer join courses c on c.id = b.course_id
                        where c.id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2' and a.submit_date >= '$currentDate';")->result_array(); ?>
                        <?php if(count($exerciseList) === 0) { echo '<option value="">Select exercise</option>'; }?>
                        <?php foreach ($exerciseList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="hTitle" class="form-label">Homework Title</label>
                    <input type="text" class="form-control" name="hTitle" id="hTitle" placeholder="Enter title name">
                </div>
                <div class="form-group col-md-12">
                    <label for="hMaterial" class="form-label">Homework Material</label>
                    <input type="file" multiple class="form-control" name="hMaterial" id="hMaterial">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" id="sub-home" class="btn btn-primary btn-block" onclick="homeworkSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    function openHomeworkForm(homeworkId, courseId, title, exerciseIds, edit) {
        
        $('#homeworkFormModal').modal('show');
        $('#modalTitle').text('Homework');
        $('#homeworkId').val(homeworkId);
        $('#courseId').val(courseId);
        $('#hEdit').val(edit);
        $('#hTitle').val(title);

        if(edit === 'true') {
            const preSelectedValues = exerciseIds.split(",");
            $('#exerciseId').val(preSelectedValues);
        }else{
            $('#exerciseId').val('');
        }
    }

    function homeworkSubmit() {
        $("#home-loader").show();
        $("#sub-home").prop("disabled", true) 
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const homeworkId = $('#homeworkId').val();
        const courseId = $('#courseId').val();
        const exerciseId = $('#exerciseId').val();        
        const hTitle = $('#hTitle').val();
        const hMaterial = $('#hMaterial').prop("files");
        const isEdit =  $('#hEdit').val();
        
        if (!courseId) {
            validationMessages('courseId');
            $("#home-loader").hide();
            $("#sub-home").prop("disabled", false) 
            return;
        }
        if (!exerciseId || exerciseId.length === 0) {
            validationMessages('exerciseId');
            $("#home-loader").hide();
            $("#sub-home").prop("disabled", false) 
            return;
        }
        if (exerciseId && exerciseId.length === 1 && exerciseId.includes('')) {
            validationMessages('exerciseId');
            $("#home-loader").hide();
            $("#sub-home").prop("disabled", false) 
            return;
        }
        if (!hTitle) {
            validationMessages('hTitle');
            $("#home-loader").hide();
            $("#sub-home").prop("disabled", false) 
            return;
        }
        if (isEdit === 'false' && (!hMaterial || hMaterial.length === 0)) {
            validationMessages('hMaterial');
            $("#home-loader").hide();
            $("#sub-home").prop("disabled", false) 
            return;
        }

        var formData = new FormData();                  
        formData.append('homeworkId', homeworkId);
        formData.append('courseId', courseId);
        formData.append('exerciseId', exerciseId);
        formData.append('hTitle', hTitle);
        if(hMaterial.length > 0) {
            for (let index = 0; index < hMaterial.length; index++) {            
                formData.append('hMaterial[]', hMaterial[index]);            
            }
        }else{
            formData.append('hMaterial[]', null); 
        }

        if (homeworkId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }
        $("#hTitle").val('');
        setTimeout(function() {
           
            $.ajax({
                    url: 'Extra/updateHomeworkSubmit',
                    type: 'POST',
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {

                        $("#home-loader").hide();
                        $("#sub-home").prop("disabled", false) 
                        Swal.fire({
                            icon: 'success',
                            title: 'Success!',
                            text: messages,
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                        $('#formModal').modal('hide');
                        const id = <?php echo $course_id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;
                    
                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
               
        }, 5000);

       
    }

    function deleteHomeworkRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_homework_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $course_id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }
</script>

<!--LIST OF MATERIAL-->
<div class="row cstm-modal">
   <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Material</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
        <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openUpdateFormMaterial('<?php echo $course_id; ?>','', '', '');" ><i class="ri-add-line align-bottom me-1"></i> Add Material</button>
        </div>
    </div>  
<div class="table-responsive table-car">
<div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
    <table id="ajax_datatables4" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
    <thead class="table-light">
    <tr>
        <th>Action</th>
        <th>Title</th>
        <th>Material Type</th>
        <th>Material Content</th>
        <th>Added Date</th>
        <th>Updated Date</th>
    </tr>
</thead>
<tbody>
<?php 

$check_material = $this->db->query("select * from course_material where course_id = '$course_id' and status = '0' order by added_date desc")->result_array();

            foreach ($check_material as $results){
            $title=$results['title'];
            $material_type=$results['material_type'];
            $material_content=$results['material_content'];
            $material_id=$results['id'];
?>
    <tr class="odd">
        <td>                                                  
            <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openUpdateFormMaterial('<?php echo $course_id; ?>','<?php echo $material_id; ?>', '<?php echo $title; ?>', '<?php echo $material_type; ?>');"><i class="bx bx-pencil"></i></a>
           <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteMaterialRecord('<?php echo $material_id; ?>');"><i class="ri-delete-bin-5-line"></i></a>
        </td>
        <td><?php echo $title;  $main_id= $results['id']; ?></td>
        <td><?php echo $material_type; ?></td>
        <td><?php echo $material_content; ?></td>
        <td><?php echo $results['added_date']; ?></td>
        <td><?php echo $results['updated_date']; ?></td>
    </tr>
<?php } ?>
       </tbody>
    </table>
</div>
</div>
</div>

  <!--LIST OF COURSE LEAVE-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Course Leave</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    </div>
</div>  
<div class="table-responsive table-car">
    <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
        <table id="ajax_datatables2_courseLeave" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
            <thead class="table-light">
                <tr>
                    <th>S.No.</th>
                    <th>Child Name</th>
                    <th>Reason</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $courseLeave = $this->getCourseAllLeave($course_id);
                $serial = 0;
                foreach ($courseLeave as $results) {
                    $serial++;
                ?>
                <tr class="odd">
                    <td><?php echo $serial; ?></td>
                    <td><?php echo $results['child_name']; ?></td>
                    <td><?php echo $results['message']; ?></td>
                    <td><?php echo $results['added_date']; ?></td>
                    <td><?php echo $results['updated_date']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

  <!--LIST OF ENROLLED STUDENT IN COURSE-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Enrolled Student</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
    </div>
</div>  
<div class="table-responsive table-car">
    <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
        <table id="ajax_datatables2_enrolledStudent" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
            <thead class="table-light">
                <tr>
                    <th>S.No.</th>
                    <th>Student Name</th>
                    <th>Created At</th>
                    <th>Updated At</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $enrolledStudent = $this->getEnrolledStudentInCourse($course_id);
                $enrollSerial = 0;
                foreach ($enrolledStudent as $results) {
                    $enrollSerial++;
                ?>
                <tr class="odd">
                    <td><?php echo $enrollSerial; ?></td>
                    <td><?php echo $results['full_name']; ?></td>
                    <td><?php echo $results['added_date']; ?></td>
                    <td><?php echo $results['updated_date']; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>
</div>

<!--LESSON MODEL-->
<div id="formModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Lesson</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
          <input type="hidden" id="operationType" name="operationType">
          <input type="hidden" id="chapter_id" name="chapter_id">
          <input type="hidden" id="course_id" name="course_id">
          <div class="row">
              <div class="form-group col-md-12">
                <label for="chapter_no" class="form-label">Lesson Number</label>
                <input type="text" class="form-control" readonly name="chapter_no" id="chapter_no">
              </div>
              <div class="form-group col-md-12">
                <label for="chapter_name" class="form-label">Lesson Name</label>
                <input type="text" class="form-control" name="chapter_name" id="chapter_name" placeholder="Enter lesson name"  required>
              </div>
              <div class="form-group col-md-12">
                  <button type="button" class="btn btn-primary btn-block" onclick="updateChapter()">Submit</button>
              </div>
          </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<!--MATERIAL MODEL-->
<div id="materialFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Material</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="operationType" name="operationType">
            <input type="hidden" id="material_id" name="material_id">
            <input type="hidden" id="course_id" name="course_id">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="form-label" for="title">Material title</label>
                    <input type="text" class="form-control" name="title" id="title" placeholder="Enter material title"  required>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label" for="material_type">Material type</label>
                    <select class="form-control" name="material_type" id="material_type">
                        <option value="">Select material type...</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="pdf">Document</option>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_content">Material Content</label>
                    <input type="file" class="form-control" name="material_content" id="material_content" required>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="updateMaterial()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<!--EXERCISE MODEL-->
<div id="exerciseformModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Exercise</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="operationType" name="operationType">
            <input type="hidden" id="exercise_id" name="exercise_id">
            <input type="hidden" id="course_id" name="course_id">
            <input type="hidden" id="exercise_no" name="exercise_no">
            <div class="row">
                <div class="form-group col-md-6">
                    <label class="form-label" for="task">Task</label>
                    <input type="text" class="form-control" name="task" id="task" placeholder="Enter task"  required>
                </div>
                <div class="form-group col-md-6">
                    <label class="form-label" for="chapter_id_1">Chapter</label>
                    <select class="form-control" name="chapter_id" id="chapter_id_1">
                        <option value="">Select chapter...</option>
                        <?php foreach ($check_chapter as $chapter): ?>
                            <option value="<?php echo $chapter['id']; ?>"><?php echo $chapter['chapter_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-12">
                    <label class="form-label" for="time">Exercise Duration</label>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label class="form-label" for="hourEx">Hour</label>
                            <input type="text" class="form-control" id="hourEx" name="hourEx" placeholder="Enter hour"  required>
                        </div>
                        <div class="form-group col-md-6 cstm-mb">
                            <label class="form-label" for="minuteEx">Minute</label>
                            <input type="text" class="form-control" id="minuteEx" name="minuteEx" placeholder="Enter minute"  required>
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="exercise_file">File</label>
                    <input type="file" class="form-control" name="file" id="exercise_file">
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="submit_date">Submit Date</label>
                    <input type="date" class="form-control" min="<?php echo  date('Y-m-d');?>" name="submit_date" id="submit_date" required>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="updateExercise()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<!--MASTER CLASS MODEL-->
<div id="masterclassformModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Master Class</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
        </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="master_class_id" name="master_class_id">
            <div class="row">
                <div class="form-group col-md-6">
                    <label for="chapter_id_2">Chapter</label>
                    <select class="form-control" name="chapter_id" id="chapter_id_2" required>
                        <option value="">Select chapter...</option>
                        <?php foreach ($check_chapter as $chapter): ?>
                            <option value="<?php echo $chapter['id']; ?>"><?php echo $chapter['chapter_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="class_name">Class Name</label>
                    <input type="text" class="form-control" name="class_name" id="class_name" placeholder="Enter Class Name" required>
                </div>
                <div class="col-md-12">
                    <label for="">Class Duration</label>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="mc-hour">Hour</label>
                            <input type="text" class="form-control" id="mc-hour" name="mc-hour" placeholder="Enter hour" required>
                        </div>
                        <div class="form-group col-md-6 mb-4 cstm-mb">
                            <label for="mc-minute">Minute</label>
                            <input type="text" class="form-control" id="mc-minute" name="mc-minute" placeholder="Enter minute">
                        </div>
                    </div>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" onclick="updateMasterClass()" class="btn btn-primary btn-block">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>


<!-- Attendance Modal  -->
<div id="attendanceModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Attendance</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <table id="ajax_datatables_attendance" class="table align-middle table-nowrap table-striped-columns mb-0 dataTable no-footer">
            <thead>
                <tr>
                    <th>S.NO.</th>
                    <th>UserName</th>
                    <th>Start Time</th>
                    <th>End Time</th>
                </tr>
            </thead>
            <tbody id="attendanceTBody">
                
            </tbody>
        </table>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<!--CLASSES MODEL-->
<div id="formModalClasses" class="inner-modal modal fade" data-bs-focus="false" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Add Classes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
    <div class="modal-body">
    <form class="tablelist-form first_data" enctype="multipart/form-data" method="post" autocomplete="off" id="add-category-form">                                                            
        <div class="row">
            <div class="col-md-6 form-group">
            <div class="">
                    <label for="master_class" class="form-label">Master Class</label>
                    <select class="form-control" name="master_class" id="master_class">
                        <option value="">Select Master Class...</option>
                        <?php foreach ($check_master_classes as $master_list): ?>
                            <option value="<?php echo $master_list['id']; ?>"><?php echo $master_list['class_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="">
                    <label for="class_name">Number of Occurrences.</label>
                    <input type="number" class="form-control" name="no_occ" id="no_occ" placeholder="Number of Occurrences max 100"  required onkeyup="if(value<0 || value>100 ) value=0;">
                </div>
            </div>   
            <div class="col-md-6 form-group">
                <div class="">
                    <label for="master_class" class="form-label">Recurrence</label>
                    <select class="form-control" name="recurring_type" id="recurring_type">
                        <option value="">Select Recurring Type...</option>
                        <option value="daily">Daily (Max 1 month)</option>
                        <option value="weekly">Weekly</option>
                        <option value="bi-weekly">Bi-Weekly</option>
                        <option value="monthly">Monthly</option>
                        <option value="no">No</option>
                    </select>
                </div>
                <div class="">
                    <label for="chapter_id_1" class="form-label">Location</label>
                    <select class="form-control" name="location" id="location">
                        <option value="">Select location...</option>
                        <?php foreach ($check_location as $location): ?>
                            <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>    
                                                                                                                                                                                                                                          
            <div class="col-md-12 form-group">
                <label id="cldBtn" for="upcoming_date" class="form-label btn btn-info">Add Class Date</label>
                <input type="hidden" name="upcoming_date" id="upcoming_date">
                <input type="hidden" name="class_id" id="class_id" value=null>
                <div id="calendar"></div>
            </div>      
            <div class="col-md-12 form-group">
                <button type="button" class="btn btn-primary btn-block" onclick="updateClasses()">Submit</button>                                                                                                                           
            </div>
        </div>
    </form>
    <div id="responseMessage"></div>
    </div>
    </div>
  </div>
</div>

<!--RESCHEDULE CLASSES MODEL-->
<div id="formModalClassesReschedule" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Reschedule Classes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
    <div class="modal-body" style="height: 800px;">
    <form class="tablelist-form first_data" enctype="multipart/form-data" method="post" autocomplete="off" id="add-category-form">                                                            
        <div class="row">
            <div class="col-md-12 form-group">
                <div class="">
                    <label for="chapter_id_1" class="form-label"><h4>Class Information</h4></label>
                    <span class="class_information"></span>
                </div>
            </div>    
            <div class="col-md-6 form-group">
                <div class="">
                    <label for="master_class" class="form-label">Master Class</label>
                    <select class="form-control" name="master_class" id="master_class_reschedule">
                        <option value="">Select Master Class...</option>
                        <?php foreach ($check_master_classes as $master_list): ?>
                            <option value="<?php echo $master_list['id']; ?>"><?php echo $master_list['class_name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
               
            </div>   
            <div class="col-md-6 form-group">
                <div class="">
                    <label for="chapter_id_1" class="form-label">Location</label>
                    <select class="form-control" name="location" id="location_reschedule">
                        <option value="">Select location...</option>
                        <?php foreach ($check_location as $location): ?>
                            <option value="<?php echo $location['id']; ?>"><?php echo $location['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>    
            <div class="col-md-12 form-group">
                <div class="check-input">
                    <input type="radio" class="reschedule_type_radio" name="reschedule_type" value="single_class">
                    <label for="html">Update Single Class</label><br>
                    <input type="radio"  class="reschedule_type_radio" name="reschedule_type" value="update_series">
                    <label for="css">Update All Series Future Class</label><br>
                </div>
            </div>                                                                                                                                                                                                                                 
            <div class="col-md-12 form-group">
                <input type="hidden" name="upcoming_date" id="upcoming_redate">
                <input type="hidden" name="class_id" id="class_reid" value=null>
                <div id="calendar-reschedule"></div>
            </div>      
            <div class="col-md-12 form-group">
                <button type="button" class="btn btn-primary btn-block" onclick="updateClasses()">Submit</button>                                                                                                                           
            </div>
        </div>
    </form>
    <div id="responseMessage"></div>
    </div>
    </div>
  </div>
</div>

<!--VIEW CLASSES MODEL-->
<div id="formModalClassesView" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Course Classes</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
    <div class="modal-body">
    <div id="calendar-view"></div>
    </div>
    </div>
  </div>
</div>
  <!--LIST OF COURSE CERTIFICATE-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Course Certificate</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openCertificateForm('','<?php echo $course_id; ?>','','','false');" ><i class="ri-add-line align-bottom me-1"></i> Add Certificate</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_courseCertificate" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>User Name</th>
                        <th>Certificate</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $Certificates = $this->db->query("select a.id,a.certificate,a.created_at,a.updated_at,b.id as user_id,b.full_name from course_certificate a 
                    left outer join users b on b.id = a.user_id 
                    left outer join courses c on c.id = a.course_id 
                    where c.id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2' order by a.created_at desc")->result_array();
                    foreach ($Certificates as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openCertificateForm('<?php echo $results['id']; ?>','<?php echo $course_id; ?>','<?php echo $results['user_id']; ?>','<?php echo $results['full_name']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteCertificateRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['full_name']; ?></td>
                            <td><?php echo $results['certificate'];?></td>
                            <td><?php echo $results['created_at']; ?></td>        
                            <td><?php echo $results['updated_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--CERTIFICATE MODEL-->
<div id="certificateFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Course Certificate</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="certificateId" name="certificateId">
            <input type="hidden" id="courseId1" name="courseId1">
            <input type="hidden" id="certificateEdit" name="certificateEdit">
            <input type="hidden" id="lastAppended" name="lastAppended">
            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Users</label>
                    <select class="form-control" name="userId1" id="userId1">
                        <option value="">Select user</option>
                        <?php $usersList1 = $this->getUserListToCompletedCoursesCertification($course_id); ?>
                        <?php foreach ($usersList1 as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="certificateFile" class="form-label">Certificate</label>
                    <input type="file" class="form-control" name="certificateFile" id="certificateFile">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="certificateSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
        
    function openCertificateForm(certificateId, courseId, userId, userName, edit) {
        $('#certificateFormModal').modal('show');
        $('#modalTitle').text('Certificate');
        $('#certificateId').val(certificateId);
        $('#courseId1').val(courseId);
        $('#certificateEdit').val(edit);

        if(edit === 'true') {
            var newOption = $('<option>', { value: userId, text: userName});
            $('#userId1').append(newOption);
            $('#userId1').val(userId);
            $('#lastAppended').val(userId);
            $('#userId1').prop('disabled', true);
        }else{
            const lastAppended = $('#lastAppended').val();
            if(lastAppended) {
                $('#userId1 option[value="' + lastAppended + '"]').remove();
            }else{
                $('#lastAppended').val('');
            }
            $('#userId1').val('');
            $('#userId1').prop('disabled', false);
        }
    }

    function certificateSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const certificateId = $('#certificateId').val();
        const courseId = $('#courseId1').val();
        const userId = $('#userId1').val();   
        const certificateFile = $('#certificateFile').prop("files")[0];
        console.log("certificateFile", certificateFile)
        const certificateEdit = $('#certificateEdit').val();
        const allowedExtensions = ["image/jpg", "image/jpeg", "image/png"];
        if (!courseId) {
            validationMessages('courseId1');
            return;
        }
        if (!userId) {
            validationMessages('userId1');
            return;
        }
        if (certificateEdit === 'false' && !certificateFile) {
            validationMessages('certificateFile');
            return;
        }
        if(!allowedExtensions.includes(certificateFile.type)) {
            console.log('Invalid file type');
            validationMessagesWithMessage('certificateFile', 'Invalid file type');
            return;
        }

        var formData = new FormData();                  
        formData.append('certificateId', certificateId);
        formData.append('courseId', courseId);
        formData.append('userId', userId);
        formData.append('certificateFile', certificateFile);

        if (certificateId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateCertificateSubmit',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messages,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                const id = <?php echo $course_id; ?>;
                const handle = pageNameBefore;
                const main_final_url = window.location.href;
                const currentUrl = pageNameBefore;
                const tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteCertificateRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_certificate_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $course_id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }

 //CLASSES CALENDAR
    var eventData  = <?php echo $events; ?> 
    var initialDate = <?php echo $initialDate; ?> 
    var eventArr = []
    var calendar; 
    document.getElementById("cldBtn").addEventListener("click", function() {
    var calendarEl = document.getElementById('calendar');
    var dateField = document.getElementById("upcoming_date");
    calendar = new FullCalendar.Calendar(calendarEl, {
            initialDate:initialDate, 
            navLinks: false, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            // timeZone:'Asia/Kuala_Lumpur',
            // timeZone:'UTC',
            initialView: 'timeGridWeek',                                                                                                                                                                                                                                                                                                                                                                                                                  
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            allDaySlot: false,
            editable: true,
            slotDuration: "00:15:00",
          //  slotMinTime: '08:00:00',
         //   slotMaxTime: '20:00:00',
            businessHours: false,   

            // Create new event
            select: function (arg) {
                var date = new Date(arg.start)
                console.log( "daaateee",date );
                var hours = date.getHours()
                var minutes = date.getMinutes()
                hours < 10 ? hours = '0'+hours : hours = hours
                minutes < 10 ? minutes = '0'+minutes : minutes = minutes
                var time = `${hours}:${minutes}`;
                console.log("time",time);
                var listEvent = calendar.getEvents();
                if(eventArr.length > 0){
                    eventArr.pop();
                    listEvent[listEvent.length-1].remove()
                }
                const { value: formValues } = Swal.fire({
                    title: "Class Details",
                    html: `
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="swal-title" class="swal2-input" >
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Start Time</label>
                        <input type="time" class="form-control" min="00:00" max="23:59" id="swal-startdate" class="swal2-input" value=${time} pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">End Time</label>
                        <input type="time" class="form-control" min="00:00" max="23:59" id="swal-enddate" class="swal2-input" pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]">
                    </div>
  
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return [
                            document.getElementById("swal-title").value,
                            document.getElementById("swal-enddate").value,
                            document.getElementById("swal-startdate").value
                        ];
                    },
                    didOpen: () => {
                            const swalContainer = document.querySelector('.swal2-container');
                            const modalBody = document.querySelector('#formModalClasses');
                            if (swalContainer && modalBody) {
                                modalBody.appendChild(swalContainer);
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if(result.value[0] && result.value[1]){
                                var endTime = result.value[1].split(":"); 
                                var startTime = result.value[2].split(":"); 
                                 endTimeInMinutes = parseInt(endTime[0]) * 60 +  parseInt(endTime[1])
                                 startTimeInMinutes =  parseInt(startTime[0]) * 60 +  parseInt(startTime[1])
                                 console.log(endTimeInMinutes+'='+startTimeInMinutes)
                                 var selectedDate = new Date(arg.start)
                                 var currDate = new Date()
                               // if(parseInt(endTime[0]) < parseInt(startTime[0]) || parseInt(endTime[0]) > 19){
                               // if(parseInt(endTime[0]) < parseInt(startTime[0])){
                                if(endTimeInMinutes < startTimeInMinutes || endTimeInMinutes - startTimeInMinutes < 30 || selectedDate.getTime() < currDate.getTime()){
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Wrong Time!',
                                        text: 'Endtime should be greater then start time atleast 30 minutes , not past date time and smaller then day schedule time!',
                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                        buttonsStyling: false
                                     });
                                }else{
                                    calendar.addEvent({
                                    title: result.value[0],
                                    start: arg.start,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                
                                eventArr.push({
                                    title: result.value[0],
                                    start: dateConverter(date),
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                dateField.value = JSON.stringify(eventArr)  
                                }
                               
                            }else{
                                Swal.fire({
                                icon: 'info',
                                title: 'Empty!',
                                text: 'Title and Endtime are required',
                                confirmButtonClass: "btn btn-primary w-xs mt-2",
                                buttonsStyling: false
                             });
                            }  
                        }
                    })
                  

                
                // console.log("EventArrAdd", eventData)
                calendar.unselect()
            },
            overlap: true,
          
            // Delete event
            eventClick: function (info) {
                const event = info.event;
                const slotSizeMinutes = 15;
                 getSlotsWithinEvent(event.start, event.end, slotSizeMinutes, calendar, eventArr, info, dateField);
                var listEvent = calendar.getEvents();
               
               if(eventArr.length > 0){
                   eventArr.pop();
                   listEvent[listEvent.length-1].remove()
               }
               // console.log('Available slots inside this event:', slots);
              
            },
            editable: false,
            dayMaxEvents: true, // allow "more" link when too many events
            events:eventData, 
            eventOverlap: true,
            validRange: function (nowDate) {
                return {
                    start: Date.now()
                };
            },
            eventRender: function(event, element) {
                if(event.rendering=='background'){
                    $('.fc-day[data-date="' + event.date + '"]').html('&nbsp;<span style="float:left">' + event.title + '</span>');
                }
            },
            eventOverlap: function(stillEvent, movingEvent) {
                return stillEvent.rendering == "background";
            }
     });                                        
                     
    calendar.render();
    });

function getSlotsWithinEvent(start, end, slotSizeMinutes,  calendar, eventArr, arg, dateField) {
  const slots = [];
  let current = new Date(start);
   console.log("current=", current)
  while (current < end) {
    let next = new Date(current.getTime() + slotSizeMinutes * 60000);
    if (next <= end) {
      slots.push({
        start: new Date(current),
        end: new Date(next)
      });
    }
    current = next;
  }

  Swal.fire({
  title: 'Select Start Time',
  html: `<form id="myForm"></form>`,
  showCancelButton: true,
  focusConfirm: false,
  didOpen: () => {
    const form = document.getElementById('myForm');

    // ?? Dynamically create a select element
    const select = document.createElement('select');
    select.id = 'dynamicSelect';
    select.className = 'swal2-select';

    // Add options
    console.log("slots", slots)
    const options = slots //['Option 1', 'Option 2', 'Option 3'];
    options.forEach(opt => {
      const option = document.createElement('option');
     // option.value = opt.toLowerCase().replace(/\s/g, '');
        var date = new Date(opt.start);
        var hours = date.getHours()
        var minutes = date.getMinutes()
        hours < 10 ? hours = '0'+hours : hours = hours
        minutes < 10 ? minutes = '0'+minutes : minutes = minutes
        var time = `${hours}:${minutes}`;                                                                                
       option.textContent = time;
       console.log("opt1", new Date(opt.start))
       select.appendChild(option);
    });

    // Append select to form    
    form.appendChild(select);

  },
  preConfirm: () => {
    const selected = document.getElementById('dynamicSelect').value;
    if (!selected) {
      Swal.showValidationMessage('Please fill out all fields');
    }
    return {
      selected,
    };
  }
}).then(result => {
  if (result.isConfirmed) {
    console.log('Selected:', result.value.selected);
      // console.log("cdddd", arg.event)
                var date = current
                console.log( "daaateee",current );
                // var hours = date.getHours()
                // var minutes = date.getMinutes()
                // hours < 10 ? hours = '0'+hours : hours = hours
                // minutes < 10 ? minutes = '0'+minutes : minutes = minutes
                 var time = result.value.selected //`${hours}:${minutes}`;
                console.log("time",time);
            

                const { value: formValues } = Swal.fire({
                    title: "Class Details",
                    html: `
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="swal-title" class="swal2-input" >
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Start Time</label>
                        <input type="time" class="form-control" min="00:00" max="23:59" id="swal-startdate" class="swal2-input" value=${time} pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">End Time</label>
                        <input type="time" class="form-control" min="00:00" max="23:59" id="swal-enddate" class="swal2-input" pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]">
                    </div>
  
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return [
                            document.getElementById("swal-title").value,
                            document.getElementById("swal-enddate").value,
                            document.getElementById("swal-startdate").value
                        ];
                    },
                    didOpen: () => {
                            const swalContainer = document.querySelector('.swal2-container');
                            const modalBody = document.querySelector('#formModalClasses');
                            if (swalContainer && modalBody) {
                                modalBody.appendChild(swalContainer);
                            }
                        }
                    }).then((result) => { console.log("okl",result)
                        if (result.isConfirmed) {
                            if(result.value[0] && result.value[1]){
                                var endTime = result.value[1].split(":"); 
                                var startTime = result.value[2].split(":"); 
                                 endTimeInMinutes = parseInt(endTime[0]) * 60 +  parseInt(endTime[1])
                                 startTimeInMinutes =  parseInt(startTime[0]) * 60 +  parseInt(startTime[1])
                                 console.log(endTimeInMinutes+'='+startTimeInMinutes)
                                 const updated = new Date(current); // clone to avoid mutating original
                                                updated.setHours(startTime[0]);
                                                updated.setMinutes(startTime[1]);
                                                updated.setSeconds(0);
                                                updated.setMilliseconds(0);    
                                 var selectedDate = updated// new Date(arg.start)
                                 var currDate = new Date()
                               // if(parseInt(endTime[0]) < parseInt(startTime[0]) || parseInt(endTime[0]) > 19){
                               // if(parseInt(endTime[0]) < parseInt(startTime[0])){
                                if(endTimeInMinutes < startTimeInMinutes || endTimeInMinutes - startTimeInMinutes < 30 || selectedDate.getTime() < currDate.getTime()){
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Wrong Time!',
                                        text: 'Endtime should be greater then start time atleast 30 minutes , not past date time and smaller then day schedule time!',
                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                        buttonsStyling: false
                                     });
                                }else{
                                                                                                         
                                    calendar.addEvent({
                                    title: result.value[0],
                                    start: updated,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                 console.log("updated", updated)

                                eventArr.push({
                                    title: result.value[0],
                                    start: updated,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                dateField.value = JSON.stringify(eventArr)  
                                }
                               
                            }else{
                                Swal.fire({
                                icon: 'info',
                                title: 'Empty!',
                                text: 'Title and Endtime are required',
                                confirmButtonClass: "btn btn-primary w-xs mt-2",
                                buttonsStyling: false
                             });
                            }  
                        }
                    })
                  

                
                // // console.log("EventArrAdd", eventData)
                // calendar.unselect()
               
            //     var title = prompt('Event Title:');
            //         if (title) {
            //             calendar.addEvent({
            //                 title: title,
            //                 start: arg.event.start,
            //                 end: arg.event.end,
            //                 allDay: arg.event.allDay
            //             })
                        
            //             eventArr.push({
            //                 title: title,
            //                 start: arg.event.start,
            //                 end: arg.event.end,
            //                 allDay: arg.event.allDay
            //             })
            //             console.log("EventArrAdd", eventArr)
            //             calendar.unselect()    
            //         dateField.value = JSON.stringify(eventArr)
            // }
  
  }
});


 // return slots;
}

// VIEW CALENDAR 

var eventData  = <?php echo $events; ?> 
var initialDate = <?php echo $initialDate; ?> 
    var eventArr = []
    var calendar; 
    document.getElementById("cldViewBtn").addEventListener("click", function() {
        setTimeout(() => {
            var calendarEl2 = document.getElementById('calendar-view');
            calendar2 = new FullCalendar.Calendar(calendarEl2, {
                    initialDate:initialDate,
                    navLinks: false, // can click day/week names to navigate views
                    selectable: true,
                    selectMirror: true,
                    // timeZone:'Asia/Kuala_Lumpur',
                    initialView: 'timeGridWeek',                                                                                                                                                                                                                                                                                                                                                                                                                  
                    nowIndicator: true,
                    headerToolbar: {
                        left: 'prev,next today',
                        center: 'title',
                        right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
                    },
                    allDaySlot: false,
                    editable: true,
                    slotDuration: "00:15:00",
                    // slotMinTime: '08:00:00',
                    // slotMaxTime: '20:00:00',
                    businessHours: false,   

                    // Create new event
                    select: function (arg) {
                        
                    },

                    // Delete event
                    eventClick: function (arg) {
                    
                    },
                    editable: false,
                    dayMaxEvents: true, // allow "more" link when too many events
                    events:eventData, 
                    eventOverlap: true,
                    validRange: function (nowDate) {
                        return {
                            start: Date.now()
                        };
                    },
                    eventRender: function(event, element) {
                        if(event.rendering=='background'){
                            $('.fc-day[data-date="' + event.date + '"]').html('&nbsp;<span style="float:left">' + event.title + '</span>');
                        }
                    },
                    eventOverlap: function(stillEvent, movingEvent) {
                        return stillEvent.rendering == "background";
                    }
            });                                        
                            
            calendar2.render();
        }, 1000);
    });

    function dateConverter(str){
        var date = new Date(str),
        mnth = ("0" + (date.getMonth()+1)).slice(-2),
        day  = ("0" + date.getDate()).slice(-2);
        hours  = ("0" + date.getHours()).slice(-2);
        minutes = ("0" + date.getMinutes()).slice(-2);
        seconds  = ("0" + date.getSeconds()).slice(-2);
        year = date.getFullYear();
        return `${year}-${mnth}-${day} ${hours}:${minutes}:${seconds}`
    }

    // LESSON SCRIPT    
    function openUpdateForm(course_id, chapter_id, chapter_no, chapter_name) 
    {
        $('#formModal').modal('show');
        $('#modalTitle').text('Lesson');
        $('#operationType').val('update');
        $('#course_id').val(course_id);
        $('#chapter_id').val(chapter_id);
        $('#chapter_no').val(chapter_no);
        $('#chapter_name').val(chapter_name);
    }
    
    function deleteChapterRecord(id,table=null) {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to recover this entity!.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/delete_data',
                type: "POST",
                data: {
                    "id": id,
                    "table": table
                },
                success: function (response) {
                    console.log("response", $($.parseHTML(response)).filter("#success").text())
                  
                   if($($.parseHTML(response)).filter("#success").text()){
                    Swal.fire({
                            icon: 'info',
                            title: 'No!',
                            text: $($.parseHTML(response)).filter("#success").text(),
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                   }else{ 
                    var id = <?php echo $course_id; ?>;
                    var handle = pageNameBefore;
                    var main_final_url = currentUrl;
                    var currentUrl = pageNameBefore;
                    var tablename = <?php echo json_encode($tablename); ?>;
                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete record. Please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe :)",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}

function updateClassStatus(classId, classStatus) {
    var currentUrl = window.location.href;
    var pathArray = window.location.pathname.split('/');
    var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
    var pageNameBefore = pathArray[pathArray.length - 2];
    Swal.fire({
        title: "Are you sure?",
        text: "You want to cancel the class!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes",
        cancelButtonText: "No",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/updateClassStatus',
                type: "POST",
                data: { "id": classId, class_status: classStatus },
                success: function (response) {
                    var id = <?php echo $course_id; ?>;
                    var handle = pageNameBefore;
                    var main_final_url = currentUrl;
                    var currentUrl = pageNameBefore;
                    var tablename = <?php echo json_encode($tablename); ?>;
                    $.ajax({
                        url: "Extra/viewformdata",
                        method: "POST",
                        data: { 
                            id: id,
                            handle: handle, 
                            main_final_url: main_final_url,
                            currentUrl: currentUrl, 
                            tablename: tablename 
                        },
                        success: function(data) {
                            $("#dataviwlist").html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                    Swal.fire({
                        icon: 'success',
                        title: 'Class Cancelled!',
                        text: 'Class Cancelled Successfully',
                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                        buttonsStyling: false
                    });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to cancel the class, please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe :)",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}

//reschedule class date time
function rescheduleClass(classId, title, master_name, upcoming_date,location_name ) {
    var infoStr = '<ul><li><b>Class Title:</b>'+title+'</li><li><b>Master Class:</b>'+master_name+'</li><li><b>Class Date:</b>'+upcoming_date+'</li><li><b>Location:</b>'+location_name+'</li></ul>'
    $('.class_information').html(infoStr) 
    $('#formModalClassesReschedule').modal('show');
    var eventData  = <?php echo $events; ?> 
    var initialDate = <?php echo $initialDate; ?> 
    var eventArr = []
    var calendar; 
    console.log("eventData",eventData)
    var calendarEl3 = document.getElementById('calendar-reschedule');
    var dateField = document.getElementById("upcoming_redate");
    setTimeout(() => {  
    calendar = new FullCalendar.Calendar(calendarEl3, {
            initialDate: initialDate,
            navLinks: false, // can click day/week names to navigate views
            selectable: true,
            selectMirror: true,
            // timeZone:'Asia/Kuala_Lumpur',
            initialView: 'timeGridWeek',                                                                                                                                                                                                                                                                                                                                                                                                                  
            nowIndicator: true,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,timeGridWeek,timeGridDay,listWeek'
            },
            allDaySlot: false,
            editable: true,
            slotDuration: "00:15:00",
            // slotMinTime: '08:00:00',
            // slotMaxTime: '20:00:00',
            businessHours: false,   

            // Create new event
            select: function (arg) {
                var date = new Date(arg.start)
                var hours = date.getHours()
                var minutes = date.getMinutes()
                hours < 10 ? hours = '0'+hours : hours = hours
                minutes < 10 ? minutes = '0'+minutes : minutes = minutes
                var time = `${hours}:${minutes}`;
                console.log("timeSelect",time);
                var listEvent = calendar.getEvents();
                if(eventArr.length > 0){
                    eventArr.pop();
                    listEvent[listEvent.length-1].remove()
                }
                const { value: formValues } = Swal.fire({
                    title: "Class Details",
                    html: `
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="swal-title" class="swal2-input" >
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Start Time</label>
                        <input type="time" class="form-control"  min="00:00" max="23:59"  id="swal-startdate" class="swal2-input" value=${time} pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">End Time</label>
                        <input type="time" class="form-control"  min="00:00" max="23:59" id="swal-enddate" class="swal2-input" pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]">
                    </div>
  
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return [
                            document.getElementById("swal-title").value,
                            document.getElementById("swal-enddate").value,
                            document.getElementById("swal-startdate").value
                        ];
                    },
                    didOpen: () => {
                            const swalContainer = document.querySelector('.swal2-container');
                            const modalBody = document.querySelector('#formModalClassesReschedule');
                            if (swalContainer && modalBody) {
                                modalBody.appendChild(swalContainer);
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if(result.value[0] && result.value[1]){
                                var endTime = result.value[1].split(":"); 
                                var startTime = result.value[2].split(":"); 
                                endTimeInMinutes = parseInt(endTime[0]) * 60 +  parseInt(endTime[1])
                                 startTimeInMinutes =  parseInt(startTime[0]) * 60 +  parseInt(startTime[1])
                                 console.log(endTimeInMinutes+'='+startTimeInMinutes)
                                 var selectedDate = new Date(arg.start)
                                 var currDate = new Date()
                                 console.log('se', selectedDate.getTime())
                                 console.log('cur', currDate.getTime())
                               // if(parseInt(endTime[0]) < parseInt(startTime[0]) || parseInt(endTime[0]) > 19){
                               // if(parseInt(endTime[0]) < parseInt(startTime[0])){
                                if(endTimeInMinutes < startTimeInMinutes || endTimeInMinutes - startTimeInMinutes < 30 ||  selectedDate.getTime() < currDate.getTime()){
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Wrong Time!',
                                        text: 'Endtime should be greater then start time atleast 30 minutes , not past date time and smaller then day schedule time!',
                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                        buttonsStyling: false
                                    });
                                }else{
                                
                                calendar.addEvent({
                                    title: result.value[0],
                                    start: arg.start,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                eventArr.push({
                                    title: result.value[0],
                                    start:dateConverter(date),
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                dateField.value = JSON.stringify(eventArr)  
                                }
                               
                            }else{
                                Swal.fire({
                                icon: 'info',
                                title: 'Empty!',
                                text: 'Title and Endtime are required',
                                confirmButtonClass: "btn btn-primary w-xs mt-2",
                                buttonsStyling: false
                             });
                            }  
                        }
                    })
                  
                // console.log("EventArrAdd", eventData)
                calendar.unselect()
            },

            // Delete event
            eventClick: function (arg) {
                console.log("arg",arg.event.start);
                var date = new Date(arg.event.start)
                var hours = date.getHours()
                var minutes = date.getMinutes()
                var time = `${hours}:00`;
                console.log("timeClick",time);
                var listEvent = calendar.getEvents();
                if(eventArr.length > 0){
                    eventArr.pop();
                    listEvent[listEvent.length-1].remove()
                }
                const { value: formValues } = Swal.fire({
                    title: "Class Details",
                    html: `
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Title</label>
                        <input type="text" class="form-control" id="swal-title" class="swal2-input" >
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Start Time</label>
                        <input type="time" class="form-control" min="00:00" max="23:59"  id="swal-startdate" class="swal2-input" value=${time} pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]" disabled>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">End Time</label>
                        <input type="time" class="form-control"  min="00:00" max="23:59" id="swal-enddate" class="swal2-input" pattern="([1]?[0-9]|2[0-3]):[0-5][0-9]">
                    </div>
  
                    `,
                    focusConfirm: false,
                    preConfirm: () => {
                        return [
                            document.getElementById("swal-title").value,
                            document.getElementById("swal-enddate").value,
                            document.getElementById("swal-startdate").value
                        ];
                    },
                    didOpen: () => {
                            const swalContainer = document.querySelector('.swal2-container');
                            const modalBody = document.querySelector('#formModalClassesReschedule');
                            if (swalContainer && modalBody) {
                                modalBody.appendChild(swalContainer);
                            }
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            if(result.value[0] && result.value[1]){
                                var endTime = result.value[1].split(":"); 
                                var startTime = result.value[2].split(":"); 
                                console.log(endTime[0]+'='+startTime[0])
                                if(parseInt(endTime[0]) < parseInt(startTime[0]) || parseInt(endTime[0]) > 19){
                                    Swal.fire({
                                        icon: 'info',
                                        title: 'Wrong Time!',
                                        text: 'Endtime should be greater then start time and smaller then day schedule time!',
                                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                                        buttonsStyling: false
                                    });
                                }else{
                                    calendar.addEvent({
                                    title: result.value[0],
                                    start: arg.event.start,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                eventArr.push({
                                    title: result.value[0],
                                    start: arg.event.start,
                                    end: result.value[1],
                                    allDay: arg.allDay
                                })
                                dateField.value = JSON.stringify(eventArr)  
                                }
                               
                            }else{
                                Swal.fire({
                                icon: 'info',
                                title: 'Empty!',
                                text: 'Title and Endtime are required',
                                confirmButtonClass: "btn btn-primary w-xs mt-2",
                                buttonsStyling: false
                             });
                            }  
                        }
                    })
                // console.log("EventArrAdd", eventData)
                calendar.unselect()
            },
            editable: false,
            dayMaxEvents: true, // allow "more" link when too many events
            events:eventData, 
            eventOverlap: true,
            validRange: function (nowDate) {
                return {
                    start: Date.now()
                };
            },
            eventRender: function(event, element) {
                if(event.rendering=='background'){
                    $('.fc-day[data-date="' + event.date + '"]').html('&nbsp;<span style="float:left">' + event.title + '</span>');
                }
            },
            eventOverlap: function(stillEvent, movingEvent) {
                return stillEvent.rendering == "background";
            }
     });                                        
     $('#class_reid').val(classId)                 
     calendar.render();
}, 1000);
}
//classes delete
function deleteClassesRecord(id) {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to recover this entity!.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/delete_classes_data',
                type: "POST",
                data: {
                    "id": id
                },
                success: function (response) {
               var id = <?php echo $course_id; ?>;
               var handle = pageNameBefore;
               var main_final_url = currentUrl;
               var currentUrl = pageNameBefore;
               var tablename = <?php echo json_encode($tablename); ?>;
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
              Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Deleted successfully',
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete record. Please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe :)",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}

function openAttendanceList(classId) {
        $.ajax({
            url: 'Dashboard/getAttendanceList',
            type: 'POST',
            data: {
                class_id:classId
            },
            success: function(response) {
                console.log('responseresponse',response);
                const classes = JSON.parse(response);
                $('#attendanceModal').modal('show');
                $('#modalTitle').text('Attendance');
                let options  = '';
                for (let index = 0; index < classes.length; index++) {
                    const username = classes[index].username;
                    const start = classes[index].start_time;
                    const end = classes[index].end_time	;
                    options += `<tr>
                                    <td>${index + 1}</td>
                                    <td>${username}</td>
                                    <td>${start}</td>
                                    <td>${end}</td>
                                </tr>`;
                }
                const tbody = document.querySelector('#attendanceTBody');
                tbody.innerHTML = options;
                var dataTable = $('#ajax_datatables_attendance').DataTable();
                dataTable.draw();  
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
}


 function updateChapter() {
         var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
        
        var course_id = $('#course_id').val();
        var chapter_id = $('#chapter_id').val();
        var chapter_no = $('#chapter_no').val();
        var chapter_name = $('#chapter_name').val();     

        if (!chapter_name) {
            validationMessages('chapter_name');
            return;
        }
        if (!chapter_no) {
            validationMessages('chapter_no');
            return;
        }

        if (chapter_id != 0) {
            var messges='Record updated successfully';
        }else {
            var messges='Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateChapter',
            type: 'POST',
            data: {
                course_id: course_id,
                chapter_id: chapter_id,
                chapter_no: chapter_no,
                chapter_name: chapter_name
            },
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messges,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                var id = <?php echo $course_id; ?>;
                var handle = pageNameBefore;
                var main_final_url = currentUrl;
                var currentUrl = pageNameBefore;
                var tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }


    function updateMaterial() {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
        var course_id = $('#course_id').val();
        var material_id = $('#material_id').val();
        var title = $('#title').val();
        var material_type = $('#material_type').val();   
        var material_content = $('#material_content').prop("files")[0];  
        var formData = new FormData();                  
        formData.append('course_id', course_id);
        formData.append('material_id', material_id);
        formData.append('title', title);
        formData.append('material_type', material_type);
        formData.append('material_content', material_content);
    
        if (!title) {
            validationMessages('title');
            return;
        }
        if (!material_type) {
            validationMessages('material_type');
            return;
        }
        if (!material_id && !material_content) {
            validationMessages('material_content');
            return;
        }
        if (material_id != 0) {
            var messges='Record updated successfully';
        }else{
            var messges='Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateMaterial',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messges,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#materialFormModal').modal('hide');
                var id = <?php echo $course_id; ?>;
                var handle = pageNameBefore;
                var main_final_url = currentUrl;
                var currentUrl = pageNameBefore;
                var tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
    

    function openUpdateFormMaterial(course_id,material_id,title,material_type) {
        $('#materialFormModal').modal('show');
        $('#modalTitle').text('Material');
        $('#operationType').val('update');
        $('#course_id').val(course_id);
        $('#material_id').val(material_id);
        $('#title').val(title);
        $('#material_type').val(material_type);
    }
    

// EXERCISE SCRIPT
function openUpdateFormExercise(course_id,exercise_id,chapter_id,exercise_no,task,time,submit_date) {
    $('#exerciseformModal').modal('show');
    $('#modalTitle').text('Exercise');
    $('#operationType').val('update');
    $('#course_id').val(course_id);
    $('#chapter_id_1').val(chapter_id);
    $('#exercise_id').val(exercise_id);
    $('#exercise_no').val(exercise_no);
    $('#task').val(task);
    $('#submit_date').val(submit_date);
    
    if(time != '') {
        const totalTime  = time.split(" ")[0];
        const total_minutes = totalTime % 60;        
        const total_hour = (totalTime - total_minutes) / 60;
        $('#hourEx').val(total_hour);
        $('#minuteEx').val(total_minutes);
    }else{
        $('#hourEx').val('');
        $('#minuteEx').val('');
    }
}
    function openUpdateFormMasterClass(masterClassId,chapterId,className,classDuration) {
        $('#masterclassformModal').modal('show');
        $('#modalTitle').text('Master Class');
        $('#master_class_id').val(masterClassId);
        $('#chapter_id_2').val(chapterId);
        $('#class_name').val(className);
        
        if(classDuration) {
            const totalTime  = classDuration.split(" ")[0];
            const total_minutes = totalTime % 60;        
            const total_hour = (totalTime - total_minutes) / 60;
            $('#mc-hour').val(total_hour);
            $('#mc-minute').val(total_minutes);
        }else{
            $('#mc-hour').val('');
            $('#mc-minute').val('');
        }
    }

    function deleteExerciseRecord(id) {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to recover this entity!.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/delete_data2',
                type: "POST",
                data: {
                    "id": id
                },
                success: function (response) {
               var id = <?php echo $course_id; ?>;
               var handle = pageNameBefore;
               var main_final_url = currentUrl;
               var currentUrl = pageNameBefore;
               var tablename = <?php echo json_encode($tablename); ?>;
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
              Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Deleted successfully',
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete record. Please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe ",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}

function deleteMaterialRecord(id) {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to recover this entity!.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/delete_material_data',
                type: "POST",
                data: {
                    "id": id
                },
                success: function (response) {
               var id = <?php echo $course_id; ?>;
               var handle = pageNameBefore;
               var main_final_url = currentUrl;
               var currentUrl = pageNameBefore;
               var tablename = <?php echo json_encode($tablename); ?>;
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
              Swal.fire({
                    icon: 'success',
                    title: 'Deleted!',
                    text: 'Deleted successfully',
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete record. Please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe :)",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}


function deleteMasterClassRecord(id,table=null) {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to recover this entity!.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Yes, delete it!",
        cancelButtonText: "No, cancel!",
        confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
        cancelButtonClass: "btn btn-danger w-xs mt-2",
        buttonsStyling: false,
        showCloseButton: true,
    }).then(function (result) {
        if (result.value) {
            $.ajax({
                url: 'Extra/deleteMasterClassRecord',
                type: "POST",
                data: {
                    "id": id,
                    "table": table
                },
                success: function (response) {
                    if($($.parseHTML(response)).filter("#success").text()){
                    Swal.fire({
                            icon: 'info',
                            title: 'No!',
                            text: $($.parseHTML(response)).filter("#success").text(),
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                   }else{ 
                      
                    var id = <?php echo $course_id; ?>;
                    var handle = pageNameBefore;
                    var main_final_url = currentUrl;
                    var currentUrl = pageNameBefore;
                    var tablename = <?php echo json_encode($tablename); ?>;
                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                }
                },
                error: function(xhr, status, error) {
                    // Handle error
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Failed to delete record. Please try again.',
                        confirmButtonClass: "btn btn-primary mt-2",
                        buttonsStyling: false
                    });
                }
            });
        } else if (result.dismiss === Swal.DismissReason.cancel) {
            // User cancelled deletion
            Swal.fire({
                title: "Cancelled",
                text: "Your data is safe :)",
                icon: "error",
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    });
}


 function updateExercise() {
         var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
        var course_id = $('#course_id').val();
        var exercise_id = $('#exercise_id').val();
        var chapter_id = $('#chapter_id_1').val(); 
        var exercise_no = $('#exercise_no').val();
        var task = $('#task').val();
        var hourEx = $('#hourEx').val().trim();
        var minuteEx = $('#minuteEx').val().trim();
        var submit_date = $('#submit_date').val();
        var file = $('#exercise_file').prop("files")[0];      

        console.log("exerise");
        if (!hourEx) {
            validationMessages('hourEx');
            return;
        }

        if (!minuteEx) {
            validationMessages('minuteEx');
            return;
        }
          
        if (isNaN(hourEx) || isNaN(minuteEx)) {
            Swal.fire({
                title: "Error",
                text: "Class Duration hours and minutes should be number!",
                icon: "info"
            });
            return;
        }
        var total_minutes = (Number(hourEx) * 60) + Number(minuteEx) + ' min';
      
          
        if (!task) {
            validationMessages('task');
            return;
        }
        if (!chapter_id) {
            validationMessages('chapter_id_1');
            return;
        }
        if(!submit_date) {
            Swal.fire({
                title: "Error",
                text: "Submit date can't be blank!",
                icon: "info"
            });
            return;
        }

        if (exercise_id !=0) {
            var messges='Record updated successfully';
        }else {
            var messges='Record inserted successfully';
        }

        var formData = new FormData();                  
        formData.append('course_id', course_id);
        formData.append('exercise_id', exercise_id);
        formData.append('chapter_id', chapter_id);
        formData.append('exercise_no', exercise_no);
        formData.append('task', task);
        formData.append('time', total_minutes);
        formData.append('submit_date', submit_date);
        formData.append('file', file);

        $.ajax({
            url: 'Extra/updateExercise',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messges,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
              var id = <?php echo $course_id; ?>;
               var handle = pageNameBefore;
               var main_final_url = currentUrl;
               var currentUrl = pageNameBefore;
               var tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function updateMasterClass() {
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        
        var masterClassId = $('#master_class_id').val();
        var chapterId = $('#chapter_id_2').val(); 
        var className = $('#class_name').val();
        var mcHour = $('#mc-hour').val();
        var mcMinute = $('#mc-minute').val();     
        

        if (parseInt(mcHour)*60 + parseInt(mcMinute) < 15) {
            Swal.fire({
                title: "Error",
                text: "Class Duration should be greater then 15 mins!",
                icon: "info"
            });
            return;
        }
          
        if (isNaN(mcHour) || isNaN(mcMinute)) {
            Swal.fire({
                title: "Error",
                text: "Class Duration hours and minutes should be number!",
                icon: "info"
            });
            return;
        }
        var total_minutes = (Number(mcHour) * 60) + Number(mcMinute) + ' min';
          
        if (!chapterId) {
            validationMessages('chapter_id_2');
            return;
        }
        if (!className) {
            validationMessages('class_name');
            return;
        }

        if (masterClassId != 0) {
            var messges='Record updated successfully';
        }else {
            var messges='Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateMasterClass',
            type: 'POST',
            data: {
                master_class_id: masterClassId,
                chapter_id: chapterId,
                class_name: className,
                time: total_minutes
            },
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messges,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
              var id = <?php echo $course_id; ?>;
               var handle = pageNameBefore;
               var main_final_url = currentUrl;
               var currentUrl = pageNameBefore;
               var tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function validationMessages(Id){
        const FS = document.getElementById(Id);
        FS.setCustomValidity('Please fill in this field');
        FS.reportValidity();
        $('#'+Id).focus();
    }
    function validationMessagesWithMessage(Id,message){
        const FS = document.getElementById(Id);
        FS.setCustomValidity(message);
        FS.reportValidity();
        $('#'+Id).focus();
    }

    function updateClasses() {
        var class_id = $('#class_reid').val()
        var formData = new FormData(); 
        var currentUrl = window.location.href;
        var pathArray = window.location.pathname.split('/');
        var pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        var pageNameBefore = pathArray[pathArray.length - 2];
        var upcoming_date = $('#upcoming_date').val();
        var messges='Record saved successfully';
        if ( class_id != 'null') {
            var reschedule_type = $("input[name='reschedule_type']:checked").val();
            var master_class = $('#master_class_reschedule').val(); 
            var upcoming_date = $('#upcoming_redate').val();
            var location = $('#location_reschedule').val()
            if (upcoming_date === '') {
                Swal.fire({
                    title: "Error",
                    text: "Class date can't be blank!",
                    icon: "info"
                });
                return;
            }
            if (!reschedule_type) {
                Swal.fire({
                    title: "Error",
                    text: "Select Reschedule Update Type!",
                    icon: "info"
                });
                return;
            }
            if (master_class == '') {
                validationMessages('master_class_reschedule')
                return;
            }
            if (location == '') {
                validationMessages('location_reschedule')
                return;
            }
            formData.append('master_class_id', master_class);
            formData.append('location', location);
            formData.append('reschedule_type', reschedule_type);
        }else {
            var upcoming_date = $('#upcoming_date').val();
            var master_class = $('#master_class').val(); 
            var recurring_type = $('#recurring_type').val()
            var no_occ = $('#no_occ').val()
            var location = $('#location').val()
            if (master_class == '') {
                validationMessages('master_class')
                return;
            }
            if (location == '') {
                validationMessages('location')
                return;
            }
            if(recurring_type != 'no'){
                if (no_occ == '') {
                    validationMessages('no_occ')
                    return;
                }
            }
          
            if (recurring_type == '') {
                validationMessages('recurring_type')
                return;
            }
            if (upcoming_date === '') {
                Swal.fire({
                    title: "Error",
                    text: "Class date can't be blank!",
                    icon: "info"
                });
                return;
            }

            if (recurring_type === 'daily' && no_occ > 30) {
                Swal.fire({
                    title: "Error",
                    text: "No of occurrences is exceeded with daily schedule",
                    icon: "info"
                });
                return;
            }


           

            var upcoming_date = $('#upcoming_date').val();
            var master_class_id = $('#master_class').val();  
            var location = $('#location').val()                 
            formData.append('master_class_id', master_class_id);
            formData.append('location', location);
            formData.append('recurring_type', recurring_type);
            formData.append('no_occ', no_occ);
        }
        formData.append('upcoming_date', upcoming_date);
        class_id != 'null' ? formData.append('class_id', class_id): formData.append('class_id', 0)
        $.ajax({
            url: 'Extra/updateClasses',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messges,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                var id = <?php echo $course_id; ?>;
                var handle = pageNameBefore;
                var main_final_url = window.location.href;
                var currentUrl = pageNameBefore;
                var tablename = <?php echo json_encode($tablename); ?>;
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                if(xhr.status ==403){
                Swal.fire({
                    title: "Cancelled",
                    text: "Class can not be added over lesson duration :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
                }
            }
        });
    }

// CLASSES SCRIPT    
function openUpdateFormClasses(course_id = '', classes_id = '', chapter_id = '', chaptername = '', recurring = '') 
{   $('#chapter_id_2').val(chapter_id)
    $('#class_id').val(classes_id)
    $('#formModalClasses').modal('show');
    $('#course_id').val(course_id);    
}
</script>



<?php  } if ($tablename =='course_type'){
    
$result = $this->db->query("select * from course_type where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Course Type Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } 
//user tutorial

 if ($tablename =='tutorial'){
    $supported_image = array(
        'gif',
        'jpg',
        'jpeg',
        'png',
        'svg'
    );
   
      $id = $_POST['id'];
      $result = $this->db->query("select * from  tutorial where id=$id")->row_array();
      $user_id = $result['user_id'];
      $tutorial_status = $result['is_active'];
      $this->load->library('Firebase');
      $firebase = new Firebase();
      $messages = $firebase->getMessages($id);
      $messages_count = count($messages);
      
     
      $admin_data = $this->db->query("select * from  users where is_admin=1")->row_array();
      $user_data = $this->db->query("select * from  users where id=$user_id")->row_array();
      $admin_logo = $admin_data['image'] ? $admin_data['image'] : base_url().'theme/assets/images/svg/user_circle_icon.png';
      $user_logo = $user_data['image'] ? $user_data['image'] : base_url().'theme/assets/images/svg/admin-user-circle-icon.svg';
      $user_name = $admin_data['username'];
    ?>
      <!-- Main container -->
    <div class="container">
      <!-- msg-header section starts -->
        <div class="msg-header">
            <div class="container1">
                <img src="<?php echo $admin_logo ?>" class="msgimg" alt="" class="rounded-circle avatar-xxs" />
                <div class="active">
                    <p><?php echo $user_name ?></p>
                </div>
            </div>
            <div class="container2">
                <a href="#" onclick="checkData('<?php echo $id ?>','<?php echo $messages_count ?>')"><span style="float:left;">Refresh Chat</span> <span style="float:right;display:none;" id="refresh-logo"><img src="<?php echo base_url().'theme/assets/images/refresh.gif' ?>" height="43" width="53" class="msgimg" alt="" class="rounded-circle avatar-xxs" /></span></a>
            </div>
        </div>
      <!-- msg-header section ends -->

      <!-- Chat inbox  -->
      <div class="chat-page">
        <div class="msg-inbox">
          <div class="chats">
            <!-- Message container -->
            <div class="msg-page" id="msg-page">
              <!-- Incoming messages -->
            <?php foreach($messages as $list){ 
               if($list['senderId'] == $user_id){ ?>
               <div class="received-chats">
                <div class="received-chats-img">
                <?php if(count($list['filename']) > 0 || $list['message'] != ''){ ?>
                  <img src="<?php echo $user_logo ?>" alt="" class="rounded-circle avatar-xxs" />
                <?php } ?>
                </div>
                <div class="received-msg">
                  <div class="received-msg-inbox">
                    <?php if($list['message'] != ''){ ?>
                    <p>
                      <?php echo $list['message']; ?>
                    </p>
                    <?php } ?>
                    <?php if(count($list['filename']) > 0){ 
                        foreach($list['filename'] as $list_file){
                            $src_file_name = $list_file;
                            $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                            if (in_array($ext, $supported_image)) { ?>
                               <br> <span><img src="<?php echo $list_file; ?>" style="width: 50%;border-radius: 0;"></span>
                           <?php } else { ?>
                            <br><span><a href="<?php echo $list_file; ?>" target="_blank" download><?php echo $list_file; ?></a></span>
                           <?php }
                           } 
                        }
                    if(count($list['filename']) > 0 || $list['message'] != ''){
                    ?>
                    <span class="time"> <?php echo $list['timestamp']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>

            <?php }else { ?>
                     <!-- Outgoing messages -->
              <div class="outgoing-chats">
                <div class="outgoing-chats-img">
                <?php if(count($list['filename']) > 0 || $list['message'] != ''){ ?>    
                  <img src="<?php echo $admin_logo ?>" alt="" class="rounded-circle avatar-xxs" />
                <?php } ?>
                </div>
                <div class="outgoing-msg">
                  <div class="outgoing-chats-msg">
                    <?php if($list['message'] != ''){ ?>
                    <p class="multi-msg">
                      <?php echo $list['message']; ?>
                    </p>
                    <?php } ?>
                    <?php if(count($list['filename']) > 0){ 
                        foreach($list['filename'] as $list_file){ 
                            $src_file_name = $list_file;
                            $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                            if (in_array($ext, $supported_image)) { ?>
                            <br> <span><img src="<?php echo $list_file; ?>" style="width: 50%;border-radius: 0;"></span>
                            <?php } else { ?>
                            <br> <span><a href="<?php echo $list_file; ?>" download><?php echo $list_file; ?></a></span>
                            <?php }
                      } 
                    }
                    if(count($list['filename']) > 0 || $list['message'] != ''){
                    ?>
                    <span class="time"> <?php echo $list['timestamp']; ?></span>
                    <?php } ?>
                  </div>
                </div>
              </div>
            <?php } } ?>
            </div>
          </div>

          <!-- msg-bottom section -->

          <div class="msg-bottom">
            <div class="input-group" id="write-board">
           
              <input type="text" class="form-control enter-submit"
                placeholder="Write message..."
                id="admin-chat"
              />
               
              <span class="input-group-text send-icon">
                <!-- <i class="bi bi-send"></i> -->
                
                <img class="bi bi-send" id="send-attachment" src="<?php  echo base_url().'theme/';?>assets/images/svg/upload.svg" width="20" height="20" />
                <input type="file" name="files[]" id="file" multiple>
                <div class="uploaded-file-info" style="max-width: 200px;max-height: 80px;white-space: nowrap;overflow: auto;"></div>
                <?php if($messages_count > 0 &&  $tutorial_status != 2){ ?>
                    <input type="hidden" id="toutorial-enter" value="<?php echo $id ?>">
                    <img class="bi bi-send" id="send-message"src="<?php  echo base_url().'theme/';?>assets/images/svg/send.svg" onclick="send_messages(<?php echo $id ?>);" width="20" height="20" />
                <?php }else { ?>
                    <input type="hidden" id="toutorial-enter" value="none">
                    <img class="bi bi-send" id="send-message"src="<?php  echo base_url().'theme/';?>assets/images/svg/send.svg" width="20" height="20" />   
               <?php } ?>
                
             </span>
            
            </div>
          </div>
        </div>
      </div>
    </div>
    <style>
    input:focus {
        outline: none;
        border: none !important;
        box-shadow: none !important;
    } 
    
    .input-group {
        float: right;
        margin-top: 13px;
        margin-right: 20px;
        outline: none !important;
        border-radius: 20px;
        width: 61% !important;
        background-color: #fff;
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    } 
    
    .input-group>.form-control {
        position: relative;
        flex: 1 1 auto;
        width: 1%;
        margin-bottom: 0;
    }
    
    .form-control {
        border: none !important;
        border-radius: 20px !important;
        display: block;
        height: calc(2.25rem + 2px);
        padding: 0.375rem 0.75rem;
        font-size: 1rem;
        line-height: 1.5;
        color: #495057;
        background-color: #fff;
        background-clip: padding-box;
        transition: border-color .15s ease-in-out, box-shadow .15s ease-in-out;
    } 

    .active {
        width: 150px;
        float: left;
        color: black;
        font-weight: bold;
        margin: 0 0 0 5px;
        height: 10%;
    
    } 

    #write-board {
        float: right;
        margin-top: 13px;
        margin-right: -31px;
        outline: none !important;
        border-radius: 20px;
        width: 100% !important;
        background-color: #fff;
        position: relative;
        display: flex;
        flex-wrap: wrap;
        align-items: stretch;
    }
</style>
    <script>
       
        $('#msg-page').scrollTop( $('#msg-page')[0].scrollHeight)
        $("#send-attachment").click(function () {
            $("input[type='file']").trigger('click');
        });

        $('input[type="file"]').on('change', function() {
        var listview = '<ul>'
        for (var i = 0; i < $(this).get(0).files.length; ++i) {
            listview +=  '<li>'+$(this).get(0).files[i].name+'</li>'
        }
        listview += '</ul>'
         $(this).siblings('div').html(listview);
        })

        function checkData(id, count)
        {   $('#refresh-logo').show();
            $.ajax({
                url: 'Extra/fetchTutorial',
                type: 'POST',
                // dataType: "json",
                data: {id:id},
                success: function(res) {
                    var formatted = res.replace(/<script.*?>([\w\W\d\D\s\S\0\n\f\r\t\v\b\B]*?)<\/script>/gi, '');
                    $('#refresh-logo').hide();
                    $('#admin-chat').val(''); // Reload the page after update/insert
                    $('#msg-page').html(formatted)  
                    $('#msg-page').scrollTop(  $('#msg-page')[0].scrollHeight)
                },
               
            });
        }
    </script>
    <?php } ?>
    
    <?php if ($tablename =='tutorial_credit_transactions'){
    
    $result = $this->db->query("select a.id, b.name,u.username, b.period, b.price, b.credits, a.tutorial_plan_quantity, a.credits as total_credit, a.amount as total_amount, 
            a.transaction_id, a.payment_method, a.payment_status, a.created_at 
            from tutorial_credit_transactions a
            left outer join tutorial_subscription_plan b on b.id = a.tutorial_plan_id 
            left outer join users u on u.id = a.user_id 
            where a.id = '$id' and b.status != '2';")->row_array();
    ?>
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                <table>
                    <tr>
                        <td><strong>User Name :</strong></td>
                        <td><?php echo $result['username']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Plan Name :</strong></td>
                        <td><?php echo $result['name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Period :</strong></td>
                        <td><?php echo $result['period']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Price :</strong></td>
                        <td><?php echo $result['price']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Credits :</strong></td>
                        <td><?php echo $result['credits']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Quantity :</strong></td>
                        <td><?php echo $result['tutorial_plan_quantity']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Credits :</strong></td>
                        <td><?php echo $result['total_credit']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Total Amount :</strong></td>
                        <td><?php echo $result['total_amount']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Transaction Id :</strong></td>
                        <td><?php echo $result['transaction_id']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Method :</strong></td>
                        <td><?php echo $result['payment_method']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Payment Status :</strong></td>
                        <td><?php echo $result['payment_status']; ?></td>
                    </tr>
                    <tr>
                       <td><strong>Created_at :</strong></td>
                       <td><?php echo $result['created_at']; ?></td>
                   </tr>
             </table>
                   </div>
                   </div>
    </div>
    <?php } ?>

<?php if ($tablename =='tutorial_subscription_plan'){
    
    $result = $this->db->query("select * from tutorial_subscription_plan where id = '$id' and status != '2'")->row_array();
    ?>
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                <table>
                    <tr>
                        <td><strong>Plan Name:</strong></td>
                        <td><?php echo $result['name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Period</strong></td>
                        <td><?php echo $result['period']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Price</strong></td>
                        <td><?php echo $result['price']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Currency</strong></td>
                        <td><?php echo $result['currency']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Discount</strong></td>
                        <td><?php echo $result['discount']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Credits</strong></td>
                        <td><?php echo $result['credits']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Credit expiry(In_days)</strong></td>
                        <td><?php echo $result['credit_expiry_in_days']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Description</strong></td>
                        <td><?php echo $result['description']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Image</strong></td>
                        <td><?php echo $result['bg_image']; ?></td>
                    </tr>
                    <tr>
                       <td><strong>Created_at:</strong></td>
                       <td><?php echo $result['created_at']; ?></td>
                   </tr>
                    <tr>
                        <td><strong>Updated_at</strong></td>
                        <td><?php echo $result['updated_at']; ?></td>
                    </tr>
             </table>
                   </div>
                   </div>
    </div>
    <?php } ?>

<?php if ($tablename =='course_exercise'){
    
        $result = $this->db->query("select a.*,b.chapter_name as Chapter_name,c.name as coursename from course_exercise a "
        . "left outer join chapter b on a.chapter_id=b.id "
        . "left outer join courses c on b.course_id=c.id"
        . " where a.id=$id and a.status!='2'")->row_array();
        $added_date=$result['added_date'];
        $updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Chapter Name:</strong></td>
                    <td><?php echo $result['Chapter_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['coursename']; ?></td>
                </tr>
                <tr>
                    <td><strong>Exercise No:</strong></td>
                    <td><?php echo $result['exercise_no']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Task:</strong></td>
                    <td><?php echo $result['task']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Time:</strong></td>
                    <td><?php echo $result['time']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='course_certificate'){
    
    $result = $this->db->query("select a.*,b.name as course_name,c.username  from course_certificate a
            left outer join courses b on b.id = a.course_id
            left outer join users c on c.id = a.user_id
            where a.id = $id and a.status != '2' and b.status != '2' and c.status != '2'")->row_array();
    ?>
      <div class="col-md-6">
            <div class="user-profile">
                <div>
                <table>
                     <tr>
                        <td><strong>Child Name :</strong></td>
                        <td><?php echo $result['username']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Course Name :</strong></td>
                        <td><?php echo $result['course_name']; ?></td>
                    </tr>
                    <tr>
                        <td><strong>Certificate :</strong></td>
                        <td><?php echo $result['certificate']; ?></td>
                    </tr>
                   
                    <tr>
                        <td><strong>Added Date :</strong></td>
                         <td><?php echo $result['created_at']; ?></td>
                    </tr>
                     <tr>
                        <td><strong>Updated Date :</strong></td>
                        <td><?php echo $result['updated_at']; ?></td>
                    </tr>
             </table>
                   </div>
                   </div>
    </div>
    <?php } ?>

<?php if ($tablename =='course_material'){
    
$result = $this->db->query("select a.*,b.name as course_name from course_material a"
        . " left outer join courses b on b.id=a.course_id  "
        . " where a.id=$id and a.status!='2'")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                 <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Title:</strong></td>
                    <td><?php echo $result['title']; ?></td>
                </tr>
                <tr>
                    <td><strong>Material Type:</strong></td>
                    <td><?php echo $result['material_type']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Material Content:</strong></td>
                    <td>
                        <?php
                        if (!empty($result['material_content'])) {
                            ?>
                            <img class="rounded-circle avatar-xl" alt="200x200" src="<?php echo $result['material_content']; ?>" data-holder-rendered="true">
                            <?php
                        } else {
                            echo "-";
                        }
                        ?>
                    </td>                   
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='course_rating'){
    
$result = $this->db->query("select a.*,b.name as course_name,c.username as user_name from course_rating a"
        . " left outer join courses b on b.id=a.course_id  "
        . "left outer join users c on c.id=a.user_id"
        . " where a.id=$id and a.status!='2'")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                 
                <tr>
                    <td><strong>Rating Value:</strong></td>
                    <td><?php echo $result['rating_value']; ?></td>
                </tr>
                <tr>
                    <td><strong>Rating Message:</strong></td>
                    <td><?php echo $result['rating_message']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>User Name:</strong></td>
                    <td><?php echo $result['user_name']; ?></td>
                </tr>
                  <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='ongoing_course'){
    
$result = $this->db->query("select a.*,b.name as course_name from ongoing_course a"
        . " left outer join courses b on b.id=a.course_id  "
        . " where a.id=$id and a.status!='2'")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                 <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                  <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='subject'){
    
$result = $this->db->query("select * from subject where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Subject Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='slider'){
    
$result = $this->db->query("select * from slider where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Slider Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Image:</strong></td>
                    <td><?php echo $result['image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Link:</strong></td>
                    <td><?php echo $result['link']; ?></td>
                </tr>
                <tr>
                    <td><strong>Description:</strong></td>
                    <td><?php echo $result['description']; ?></td>
                </tr>
                <tr>
                    <td><strong>Offer:</strong></td>
                    <td><?php echo $result['offer']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='get_started'){
    
$result = $this->db->query("select * from get_started where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong> Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Image:</strong></td>
                    <td><?php echo $result['url']; ?></td>
                </tr>
                    <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='payment'){
    
$result = $this->db->query("select * from payment where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Payment Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                    <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='badges'){
    
$result = $this->db->query("select * from badges where id=$id;")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Title:</strong></td>
                    <td><?php echo $result['title']; ?></td>
                </tr>
                <tr>
                    <td><strong> Image:</strong></td>
                    <td><?php echo $result['image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Sub Title:</strong></td>
                    <td><?php echo $result['sub_title']; ?></td>
                </tr>
               
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='billing_address'){
    
$result = $this->db->query("select a.id, d.full_name as user_name, b.name as course_name, (b.amount * a.quantity) as amount, 
            (b.service_cost * a.quantity) as service_cost, (b.addon * a.quantity) as addon,(b.tax * a.quantity) as tax, a.quantity, 
            c.total_payment as total, c.address, e.name as country, f.name as state, f.name as city, c.transaction_id, c.payment_method, c.payment_status, c.added_date, c.updated_date
            from main_cart a
            left outer join courses b on b.id = a.course_id
            left outer join billing_address c on c.cart_id = a.id
            left outer join users d on d.id = a.user_id 
            left outer join country e on e.id = c.country
            left outer join state f on f.id = c.state
            left outer join city g on g.id = c.city
            where a.id = '$id' and a.is_paid ='2' group by a.id;")->row_array();
?>
  <div class="user-profile">
            <div class="row">
            <div class="col-md-6">
            <table>
                <tr>
                    <td><strong>User Name :</strong></td>
                    <td><?php echo $result['user_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course Name :</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Amount :</strong></td>
                    <td><?php echo $result['amount']; ?></td>
                </tr>
               <tr>
                    <td><strong>Service Cost :</strong></td>
                    <td><?php echo $result['service_cost']; ?></td>
                </tr>
               
                <tr>
                    <td><strong>Addon :</strong></td>
                    <td><?php echo $result['addon']; ?></td>
                </tr>
               
                <tr>
                    <td><strong>Tax :</strong></td>
                    <td><?php echo $result['tax']; ?></td>
                </tr>
               <tr>
                    <td><strong>Quantity :</strong></td>
                    <td><?php echo $result['quantity']; ?></td>
                </tr>
               <tr>
                    <td><strong> Total :</strong></td>
                    <td><?php echo $result['total']; ?></td>
                </tr>
               <tr>
                    <td><strong>Address :</strong></td>
                    <td><?php echo $result['address']; ?></td>
                </tr>
               <tr>
                    <td><strong>Country :</strong></td>
                    <td><?php echo $result['country']; ?></td>
                </tr>
                 </table>
                </div>
                <div class="col-md-6">
                    <table>
                <tr>
                    <td><strong>State:</strong></td>
                    <td><?php echo $result['state']; ?></td>
                </tr>
                 <tr>
                    <td><strong>City :</strong></td>
                    <td><?php echo $result['city']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Transaction Id :</strong></td>
                    <td><?php echo $result['transaction_id']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Payment Method :</strong></td>
                    <td><?php echo $result['payment_method']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Payment Status :</strong></td>
                    <td><?php echo $result['payment_status']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Created At :</strong></td>
                    <td><?php echo $result['added_date']; ?></td>
                </tr> <tr>
                    <td><strong>Updated At :</strong></td>
                    <td><?php echo $result['updated_date']; ?></td>
                </tr>
         </table>
               </div>
               </div>
               </div>

<?php } ?>
<?php if ($tablename =='chapter'){
    
$result = $this->db->query("select a.*,b.name as course_name from chapter a"
        . " left outer join courses b on b.id=a.course_id  "
        . " where a.id=$id and a.status!='2'")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong> Chapter No:</strong></td>
                    <td><?php echo $result['chapter_no']; ?></td>
                </tr>
                <tr>
                    <td><strong>Chapter Name:</strong></td>
                    <td><?php echo $result['chapter_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Time:</strong></td>
                    <td><?php echo $result['time']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='check_in'){
    
$result = $this->db->query("select a.*,c.chapter_name as chaptername,c.chapter_no as chapterno,d.name as course_name,b1.class_name from check_in a
                            left outer join upcoming_classes b on b.id = a.upcoming_id
                            left outer join master_classes b1 on b1.id = a.master_class_id
                            left outer join chapter c on c.id = b1.chapter_id
                            left outer join courses d on d.id = c.course_id
                            where a.id=$id and a.status!='2' and b.class_status != 'Cancel'")->row_array();
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Course Name :</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Chapter Number :</strong></td>
                    <td><?php echo $result['chapterno']; ?></td>
                </tr>
                <tr>
                    <td><strong>Chapter Name :</strong></td>
                    <td><?php echo $result['chaptername']; ?></td>
                </tr>
                <tr>
                    <td><strong>Class Name :</strong></td>
                    <td><?php echo $result['class_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Start Time :</strong></td>
                    <td><?php echo $result['start_time']; ?></td>
                </tr>
                 <tr>
                    <td><strong>End Time :</strong></td>
                    <td><?php echo $result['end_time']; ?></td>
                </tr>                
                <tr>
                    <td><strong>Added Date :</strong></td>
                     <td><?php echo $result['added_date']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date :</strong></td>
                    <td><?php echo $result['updated_date']; ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='child_homework'){
    
$result = $this->db->query("select a.*,e.username as username,d.task as exercise_name,c.chapter_no as chapterno, c.chapter_name as chaptername,f.name as course_name from child_homework a
                            left outer join homework b on b.id = a.homework_id
                            left outer join course_exercise d on d.id = b.exercise_id
                            left outer join chapter c on c.id = d.chapter_id
                            left outer join users e on e.id = a.user_id
                            left outer join courses f on f.id = c.course_id
                           
                            where a.id = $id and a.status!='2'")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];

$child_homework_docs = $this->db->query("select * from ch_homework_doc where child_homework_id = '$result[id]' and status = '0' ")->result_array();
$html = '';
for ($i = 0; $i < count($child_homework_docs); $i++) { 
    $url = $child_homework_docs[$i]['doc'];
    $html.= "<div><a href=".$url." target='_blank' >".$child_homework_docs[$i]['doc']."</a></div>";
}

?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $result['username']; ?></td>
                </tr>
                <tr>
                    <td><strong>Exercise Name:</strong></td>
                    <td><?php echo $result['exercise_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Chapter No:</strong></td>
                    <td><?php echo $result['chapterno']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Chapter Name:</strong></td>
                    <td><?php echo $result['chaptername']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr> 
                <tr>
                    <td><strong>Submitted Homework:</strong></td>

                    <td><?php echo $html ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='complain'){
    
$result = $this->db->query("select a.*,b.username as user_name from complain a "
                  . " left outer join users b on a.user_id=b.id "
                    . " where a.id = $id and a.status!='2' ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Username:</strong></td>
                    <td><?php echo $result['user_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Order No:</strong></td>
                    <td><?php echo $result['order_no']; ?></td>
                </tr>
                <tr>
                    <td><strong>Message:</strong></td>
                    <td><?php echo $result['message']; ?></td>
                </tr><tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='contact_us'){
    
$result = $this->db->query("select a.*,b.name as subject_name from contact_us a "
                  . " left outer join subject b on a.subject=b.id "
                    . " where a.id = $id and a.status!='2' ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Subject Name:</strong></td>
                    <td><?php echo $result['subject_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Email:</strong></td>
                    <td><?php echo $result['email']; ?></td>
                </tr>
                <tr>
                    <td><strong>Document:</strong></td>
                    <td><?php echo $result['document_upload']; ?></td>
                </tr><tr>
                <tr>
                    <td><strong>Question:</strong></td>
                    <td><?php echo $result['question']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='events'){
$result = $this->db->query("select a.* , b.name as location from events a left outer join class_location b on b.id = a.location_id where a.id = $id; ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Event:</strong></td>
                    <td><?php echo $result['event']; ?></td>
                </tr>
                <tr>
                    <td><strong>Image:</strong></td>
                    <td><?php echo $result['image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Short Description:</strong></td>
                    <td><?php echo $result['short_description']; ?></td>
                </tr><tr>
                <tr>
                    <td><strong>Full Description:</strong></td>
                    <td><?php echo $result['full_description']; ?></td>
                </tr>
                <tr>
                    <td><strong>Event Date:</strong></td>
                    <td><?php echo $result['event_date']; ?></td>
                </tr>
                <tr>
                    <td><strong>Duration:</strong></td>
                    <td><?php echo $result['duration']; ?></td>
                </tr><tr>
                    <td><strong>Start Time:</strong></td>
                    <td><?php echo $result['start_time']; ?></td>
                </tr><tr>
                    <td><strong>End Time:</strong></td>
                    <td><?php echo $result['end_time']; ?></td>
                </tr>
                  <tr>
                    <td><strong>Location:</strong></td>
                    <td><?php echo $result['location']; ?></td>
                </tr><tr>
                    <td><strong>Amount:</strong></td>
                    <td><?php echo $result['amount']; ?></td>
                </tr>
                <tr>
                    <td><strong>Min Age:</strong></td>
                    <td><?php echo $result['min_age']; ?></td>
                </tr>
                <tr>
                    <td><strong>Max Age:</strong></td>
                    <td><?php echo $result['max_age']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='homework'){
$result = $this->db->query("select a.*,c.chapter_no as chapterno,c.chapter_name as chaptername,b.task as exercise_name,b.submit_date from homework a "
                        ."left outer join course_exercise b on b.id = a.exercise_id " 
                        ."left outer join chapter c on c.id = b.chapter_id " 
                       ."where a.id = $id and a.status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
$submit_date=$result['submit_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Chapter No:</strong></td>
                    <td><?php echo $result['chapterno']; ?></td>
                </tr>
                <tr>
                    <td><strong>Chapter Name:</strong></td>
                    <td><?php echo $result['chaptername']; ?></td>
                </tr>
                <tr>
                    <td><strong>Exercise:</strong></td>
                    <td><?php echo $result['exercise_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Homework Material Title:</strong></td>
                    <td><?php echo $result['homework_title']; ?></td>
                </tr>
                <tr>
                    <td><strong>Homework Material:</strong></td>
                    <td><?php echo $result['homework_material']; ?></td>
                </tr>
                <tr>
                    <td><strong>Submit Date:</strong></td>
                     <td><?php echo $submit_date;?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>
<?php if ($tablename =='course_gallery_folders'){
$result = $this->db->query("select a.id,a.folder_name,a.created_at,a.updated_at,b.name as course_name from course_gallery_folders a
left outer join courses b on b.id = a.course_id where a.id = $id and a.status!='2' and b.status !='2'")->row_array();
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Folder Name:</strong></td>
                    <td><?php echo $result['folder_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Created Date:</strong></td>
                     <td><?php echo $result['created_at'];?></td>
                </tr>
                <tr>
                    <td><strong>Updated Date:</strong></td>
                     <td><?php echo $result['updated_at']; ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='course_gallery') { ?>
    <!-- List of folders -->
    <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Gallery Folder</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openFolderForm('','<?php echo $id; ?>','','false');" ><i class="ri-add-line align-bottom me-1"></i> Add Folder</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_gallery" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Folder Name</th>
                        <th>Folder Image</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $Folders = $this->db->query("select a.* , b.gallery as folder_image from course_gallery_folders a
                    left outer join course_gallery b on b.gallery_folder_id = a.id  where b.id = '$id' and a.status != '2' and b.status !='2'")->result_array();
                    foreach ($Folders as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openFolderForm('<?php echo $results['id']; ?>','<?php echo $id; ?>','<?php echo $results['folder_name']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteFolderRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['folder_name']; ?></td>
                            <td><?php echo $results['folder_image']; ?></td>
                            <td><?php echo $results['created_at']; ?></td>        
                            <td><?php echo $results['updated_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--FOLDER MODEL-->
<div id="folderFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Folder</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="folderId" name="folderId">
            <input type="hidden" id="fCourseId" name="fCourseId">
            <input type="hidden" id="fEdit" name="fEdit">            

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="folderName" class="form-label">Folder Name</label>
                    <input type="text" class="form-control" name="folderName" id="folderName" placeholder="Enter folder name">
                </div>
                <div class="form-group col-md-12">
                    <label for="marks" class="form-label">Folder Image</label>
                    <input type="file" class="form-control" name="folderFile" id="folderFile">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="folderSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    function openFolderForm(folderId, courseId, folderName, edit) {
        $('#folderFormModal').modal('show');
        $('#modalTitle').text('Gallery Folder');
        $('#folderId').val(folderId);
        $('#fCourseId').val(courseId);
        $('#folderName').val(folderName);
        $('#fEdit').val(edit);
    }

    function folderSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const folderId = $.trim($('#folderId').val());
        const courseId = $.trim($('#fCourseId').val());
        const folderName = $.trim($('#folderName').val());
        const folderFile = $.trim($('#folderFile').prop("files")[0]);
        const fEdit = $('#fEdit').val();  
        
        if (!courseId) {
            validationMessages('fCourseId');
            return;
        }
        if (!folderName) {
            validationMessages('folderName');
            return;
        }

        if (fEdit === 'false' && !folderFile) {
            validationMessages('folderFile');
            return;
        }

        var formData = new FormData();                  
        formData.append('folderId', folderId);
        formData.append('courseId', courseId);
        formData.append('folderName', folderName);
        formData.append('folderFile', folderFile);

        if (folderId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateFolderSubmit',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messages,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                const id = <?php echo $id; ?>;
                const handle = pageNameBefore;
                const main_final_url = window.location.href;
                const currentUrl = pageNameBefore;
                const tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteFolderRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_folder_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }

    function validationMessagesWithMessage(Id,message){
        const FS = document.getElementById(Id);
        FS.setCustomValidity(message);
        FS.reportValidity();
        $('#'+Id).focus();
    }

    function validationMessages(Id){
        const FS = document.getElementById(Id);
        FS.setCustomValidity('Please fill in this field');
        FS.reportValidity();
        $('#'+Id).focus();
    }

    
</script>

    <!-- List of gallery -->
    <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Gallery</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openGalleryForm('','','false');" ><i class="ri-add-line align-bottom me-1"></i> Add Gallery</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Folder Name</th>
                        <th>Gallery</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $galleryList = $this->db->query("select a.*,b.folder_name from course_gallery a 
                        left outer join course_gallery_folders b on b.id = a.gallery_folder_id 
                        left outer join courses c on c.id = b.course_id 
                        where c.id = '$id' and a.status!='2' and b.status !='2' and c.status != '2'")->result_array();
                        foreach ($galleryList as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openGalleryForm('<?php echo $results['id']; ?>','<?php echo $results['gallery_folder_id']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteGalleryRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['folder_name']; ?></td>
                            <td><?php echo $results['gallery']; ?></td>
                            <td><?php echo $results['created_at']; ?></td>        
                            <td><?php echo $results['updated_at']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--GALLERY MODEL-->
<div id="galleryFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Gallery</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="galleryId" name="galleryId">
            <input type="hidden" id="gEdit" name="gEdit">

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Folder's</label>
                    <select class="form-control" name="folderId1" id="folderId1">
                        <option value="">Select folder</option>
                        <?php $folderList = $this->db->query("select a.id,a.folder_name as name from course_gallery_folders a
                        left outer join courses b on b.id = a.course_id  where b.id = '$id' and a.status != '2' and b.status !='2'")->result_array(); ?>
                        <?php foreach ($folderList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="marks" class="form-label">Gallery</label>
                    <input type="file" multiple class="form-control" name="galleryFile" id="galleryFile">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="gallerySubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    function openGalleryForm(galleryId, folderId, edit) {
        $('#galleryFormModal').modal('show');
        $('#modalTitle').text('Gallery');
        $('#galleryId').val(galleryId);
        $('#folderId1').val(folderId);
        $('#gEdit').val(edit);
    }

    function gallerySubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const galleryId = $('#galleryId').val();
        const folderId = $('#folderId1').val();
        const galleryFile = $('#galleryFile').prop("files");
        const gEdit = $('#gEdit').val();  
        
        if (!folderId) {
            validationMessages('folderId1');
            return;
        }
        if (gEdit === 'false' && (!galleryFile || galleryFile.length === 0)) {
            validationMessages('galleryFile');
            return;
        }

        var formData = new FormData();                  
        formData.append('galleryId', galleryId);
        formData.append('folderId', folderId);
        if(galleryFile.length > 0) {
            for (let index = 0; index < galleryFile.length; index++) {            
                formData.append('galleryFile[]', galleryFile[index]);            
            }
        }else{
            formData.append('galleryFile[]', null); 
        }

        if (galleryId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateGallerySubmit',
            type: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messages,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                const id = <?php echo $id; ?>;
                const handle = pageNameBefore;
                const main_final_url = window.location.href;
                const currentUrl = pageNameBefore;
                const tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteGalleryRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_gallery_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }
</script>
<?php } ?>

<?php if ($tablename =='announcement'){
$result = $this->db->query("select * from announcement where id = $id and status!='2'")->row_array();
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>Message:</strong></td>
                    <td><?php echo $result['message']; ?></td>
                </tr>
                <tr>
                    <td><strong>Created Date:</strong></td>
                     <td><?php echo $result['created_at'];?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='news'){
$result = $this->db->query("select * from news where id = $id and status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                <tr>
                    <td><strong>News Type</strong></td>
                    <td><?php echo $result['news_type']; ?></td>
                </tr>
                <tr>
                    <td><strong>News Image:</strong></td>
                    <td><?php echo $result['news_image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Description:</strong></td>
                    <td><?php echo $result['description']; ?></td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td><?php echo $result['date']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='reschedule_classes'){
$result = $this->db->query("select a.*,f.name as course_name, c.title as upcoming_name from reschedule_classes a "
                    . "left outer join upcoming_classes c on a.upcoming_id=c.id "
                    . "left outer join master_classes d on c.master_class_id=d.id "
                    . "left outer join chapter e on d.chapter_id=e.id "
                    . "left outer join courses f on e.course_id=f.id "
                    . " where a.id=$id and a.status!='2' and  c.status!='2' and  d.status!='2' and  e.status!='2' and  f.status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
$hkdate=$result['hk_date']
?>
<div class="col-md-6">
    <div class="user-profile">
        <div>
            <table>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Upcoming Name:</strong></td>
                    <td><?php echo $result['upcoming_name']; ?></td>
                </tr>

                <tr>
                    <td><strong>Rechedule Reason:</strong></td>
                    <td><?php echo $result['reschedule_reason']; ?></td>
                </tr>
                
                <tr>
                    <td><strong>Added Date:</strong></td>
                        <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                            echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                    <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                            echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
        </div>        
    </div>
</div>
<?php } ?>

<?php if ($tablename =='child_quiz_result'){
$result = $this->db->query("select a.*,b.title as badge_name,c.name as quiz_name from child_quiz_result a "
                . "left outer join badges b on a.badge_id=b.id "
                . " left outer join main_quiz c on a.main_quiz_id=c.id "
                . " where a.id=$id and a.status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
$hkdate=$result['hk_date']
?>
<div class="col-md-6">
    <div class="user-profile">
        <div>
            <table>
                <tr>
                    <td><strong>Badge Name:</strong></td>
                    <td><?php echo $result['badge_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Main quiz Name:</strong></td>
                    <td><?php echo $result['quiz_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Duration:</strong></td>
                    <td><?php echo $result['duration']; ?></td>
                </tr>
                <tr>
                    <td><strong>Score:</strong></td>
                    <td><?php echo $result['score']; ?></td>
                </tr><tr>
                    <td><strong>Correct Answer:</strong></td>
                    <td><?php echo $result['correct_ans']; ?></td>
                </tr>
                <tr>
                    <td><strong>Skipped Answer:</strong></td>
                    <td><?php echo $result['skipped_ans']; ?></td>
                </tr>
                <tr>
                    <td><strong>Wrong Answer:</strong></td>
                    <td><?php echo $result['wrong_ans']; ?></td>
                </tr>
                <tr>
                    <td><strong>Completion Question:</strong></td>
                    <td><?php echo $result['completion_ques']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                        <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                            echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                    <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                            echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
        </div>
    </div>
</div>
<?php } ?>

<?php if ($tablename =='main_quiz') {
    $result = $this->db->query("select a.*,b.title as badge_name,c.name as course_name from main_quiz a
                        left outer join badges b on a.badge_id = b.id
                        left outer join courses c on a.course_id = c.id
                        where a.id=$id and a.status!='2'  ")->row_array();
    $added_date=$result['added_date'];
    $updated_date=$result['updated_date'];
?>
<div class="col-md-6">
    <div class="user-profile">
        <div>
            <table>
                <tr>
                    <td><strong>Badge Name:</strong></td>
                    <td><?php echo $result['badge_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Course Name:</strong></td>
                    <td><?php echo $result['course_name']; ?></td>
                </tr>

                <tr>
                    <td><strong>Quiz Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Date:</strong></td>
                    <td><?php echo $result['date']; ?></td>
                </tr>
                <tr>
                    <td><strong>Percent Creteria:</strong></td>
                    <td><?php echo $result['percent_creteria']; ?></td>
                </tr>
                <tr>
                    <td><strong>Duration:</strong></td>
                    <td><?php echo $result['duration']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                        <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                            echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                    <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                            echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
            </table>
        </div>
        <input type="hidden" name="quizId" id="quizIdForm" value="<?php echo  $result['id']; ?>">
        <span style="float:right">
        <?php $checkActive = $result['active_status'] ==1 ? 'checked': '' ?>
        <?php $checkInactive = $result['active_status'] ==0 ? 'checked': '' ?>
        <label><input type="radio" id="act" class="active_status" name="active_status"  value=1  <?php echo $checkActive; ?>>Active</label><br>
        <label><input type="radio" id="inAct" class="active_status" name="active_status"  value=0  <?php echo $checkInactive; ?>>Inactive</label>
        </span>
    </div>
</div>

  <!--LIST OF QUIZ-->
    <?php
        $Quiz = $this->db->query("select a.id,a.name,a.marks,a.added_date,a.updated_date,b.id as quizTypeId,b.name as quizTypeName from quiz a 
                    left outer join quiz_type b on b.id = a.select_type where a.main_quiz_id = '$id' and a.status != '2' and b.status != '2' order by a.added_date desc")->result_array(); 
                    $toTalMarks = 0 ;
            foreach ($Quiz as $results) { 
            $toTalMarks += $results['marks']; 
        }
   ?>
<div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Quiz Questions</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openQuizForm('','<?php echo $id; ?>','', '','','<?php echo $toTalMarks; ?>');" ><i class="ri-add-line align-bottom me-1"></i> Add Quiz</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Quiz Name</th>
                        <th>Quiz Type Name</th>
                        <th>Marks</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    foreach ($Quiz as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openQuizForm('<?php echo $results['id']; ?>','<?php echo $id; ?>','<?php echo $results['name']; ?>', '<?php echo $results['quizTypeId']; ?>','<?php echo $results['marks']; ?>','<?php echo $toTalMarks; ?>');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteQuizRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['name']; ?></td>
                            <td><?php echo $results['quizTypeName'];?></td>
                            <td><?php echo $results['marks']; ?></td>
                            <td><?php echo $results['added_date']; ?></td>        
                            <td><?php echo $results['updated_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--QUIZ MODEL-->
<div id="quizFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Quiz Question</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="mainQuizId" name="mainQuizId">
            <input type="hidden" id="quizId" name="quizId">

            <div class="row">
                <div class="form-group col-md-12">
                    Available Marks : <input id="avaMark" type="text" value="0" disabled>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Quiz type</label>
                    <select class="form-control" name="quizTypeId" id="quizTypeId">
                        <option value="">Select quiz type...</option>
                        <?php $quizTypeList = $this->db->query("select id,name from quiz_type where status != '2'")->result_array(); ?>
                        <?php foreach ($quizTypeList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="quizName" class="form-label">Quiz Name</label>
                    <input type="text" class="form-control" name="quizName" id="quizName" placeholder="Enter quiz name">
                </div>
                <div class="form-group col-md-12">
                    <label for="marks" class="form-label">Marks</label>
                    <input type="number" class="form-control" name="marks" id="marks" placeholder="Enter marks">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="quizSubmit()" id="quizSubmitButton">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>

$('.active_status').change(function() {
    var textVal = this.value == 0 ? 'Do You Want make Quiz Inactive?' : 'Do You Want make Quiz Active?'
    var quizId = $('#quizIdForm').val();
    Swal.fire({
    title: textVal,
    showDenyButton: true,
    showCancelButton: false,
    confirmButtonText: "Save",
    denyButtonText: `Don't save`
    }).then((result) => {
    /* Read more about isConfirmed, isDenied below */
    if (result.isConfirmed) {
        $.ajax({
            url: 'Extra/updateQuizActiveStatus',
            type: 'POST',
            data: {
                quizId: quizId,
                activeStatus: this.value,
            },
            success: function(response) {
                Swal.fire("Saved!", "", "success");
            },
            error: function(xhr, status, error) {
                Swal.fire("some error occurs", "", "info");
            }
         }); 

        
    } else if (result.isDenied) {
        Swal.fire("Changes are not saved", "", "info");
        this.value == 0 ?  $('#act').prop('checked', true):  $('#inAct').prop('checked', true);
    }
    });
});

    function openQuizForm(quizId, mainQuizId, quizName, quizTypeId, marks, totalMarks) {
        $('#quizFormModal').modal('show');
        $('#modalTitle').text('Quiz');
        $('#quizId').val(quizId);
        $('#mainQuizId').val(mainQuizId);
        $('#quizName').val(quizName);
        $('#quizTypeId').val(quizTypeId);
        $('#marks').removeAttr('max');
        $('#marks').val(marks);
        var avaMarks = 100-totalMarks;
        $('#avaMark').val(avaMarks+ '%')
        if(((100-totalMarks) <= 0) && quizId == ''){
            $('#quizSubmitButton').attr('disabled','disabled');
           
        }else{
            $('#quizSubmitButton').removeAttr("disabled");
            $('#marks').attr('max', marks);
        }
    }

    function quizSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const quizId = $('#quizId').val();
        const mainQuizId = $('#mainQuizId').val();
        const quizName = $.trim($('#quizName').val());
        const quizTypeId = $('#quizTypeId').val();
        const marks = $('#marks').val();  
        const avaMarks = $('#avaMark').val();  
        const numValMark = parseInt(avaMarks.replace("%", "")); 
        
        if (!quizTypeId) {
            validationMessages('quizTypeId');
            return;
        }
        if (!quizName) {
            validationMessages('quizName');
            return;
        }
        if (!marks) {
            validationMessages('marks');
            return;
        }
        

        if (quizId != 0) {
            var messages = 'Record updated successfully';
        }else {
            if (marks > numValMark) {
                validationMessagesWithMessage('marks','Marks is greater then the available marks');
                return;
             }
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateQuizSubmit',
            type: 'POST',
            data: {
                quizId: quizId,
                mainQuizId: mainQuizId,
                quizName: quizName,
                quizTypeId: quizTypeId,
                marks: marks
            },
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messages,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                const id = <?php echo $id; ?>;
                const handle = pageNameBefore;
                const main_final_url = window.location.href;
                const currentUrl = pageNameBefore;
                const tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteQuizRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_quiz_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }

    function validationMessages(Id){
        const FS = document.getElementById(Id);
        FS.setCustomValidity('Please fill in this field');
        FS.reportValidity();
        $('#'+Id).focus();
    }

    function validationMessagesWithMessage(Id,message){
        const FS = document.getElementById(Id);
        FS.setCustomValidity(message);
        FS.reportValidity();
        $('#'+Id).focus();
    }
</script>

  <!--LIST OF QUIZ OPTION-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">Multiple Choice Questions(MCQ)</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openQuizOptionForm('','','','false');" ><i class="ri-add-line align-bottom me-1"></i> Add Answer Choices</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_quizOption" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>Quiz Name</th>
                        <!-- <th>Option Name</th>
                        <th>Option Pair</th>
                        <th>Answer</th> -->
                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $QuizOptions = $this->db->query("select a.id,a.name,a.pair,a.ans_name,a.added_date,a.updated_date,b.id as quizId,b.name as quizName, d.name as quizTypeName from quiz_options a 
                    left outer join quiz b on b.id = a.quiz_id
                    left outer join main_quiz c on c.id = b.main_quiz_id 
                    left outer join quiz_type d on d.id = b.select_type
                    where c.id = '$id' and a.status != '2' and b.status != '2' and c.status != '2' group by b.id order by a.added_date desc")->result_array();
                    foreach ($QuizOptions as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openQuizOptionForm('<?php echo $results['id']; ?>','<?php echo $results['quizId']; ?>','<?php echo $results['quizTypeName']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteQuizOptionRecord('<?php echo $results['quizId']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['quizName']; ?></td>

                            <!-- <td><?php echo $results['name'];?></td>
                            <td><?php echo $results['pair']; ?></td>
                            <td><?php echo $results['ans_name']; ?></td> -->

                            <td><?php echo $results['added_date']; ?></td>        
                            <td><?php echo $results['updated_date']; ?></td>
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="getQuizQuestionDetail('<?php echo $results['quizId']; ?>')">
                                    <?php if($results['quizTypeName'] === 'select') { echo "Show Options"; }else{ echo "Show Pairs";} ?>
                                </a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--QUIZ OPTION MODEL-->
<div id="quizFormOptionModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Quiz Option</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="resetQuizOptionForm()" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="quizOptionId" name="quizOptionId" #quizOptionId>
            <input type="hidden" id="quizEdit" name="quizEdit" #quizEdit>
            <input type="hidden" id="quizTypeName" name="quizTypeName">

            <div class="row" id="dynamicQuizForm">
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Quiz</label>
                    <select class="form-control" name="quizId1" id="quizId1" class="targetQuizDropdown" onchange="loadQuizOptionForm(this.value, quizOptionId.value, 'true', quizEdit.value === 'true' ? false : true)">
                        <option value="">Select quiz ...</option>
                        <?php $quizList = $this->db->query("select a.id,a.name from quiz a
                        left outer join quiz_type b on b.id = a.select_type where a.main_quiz_id = '$id' and b.name != 'written' and a.status != '2' and b.status != '2'")->result_array(); ?>
                        <?php foreach ($quizList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="quizOptionSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<!-- Quiz Question Detail Modal  -->
<div id="quizQuestionDetailModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Question Detail</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <div id="questionDetailModal">
            <p>Hello</p>
        </div>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    async function loadQuizOptionForm(quizId, quizOptionId, edit, load) {
        if(!load) {
            $("#quizId1").val(quizId);  
        }else{
            $.ajax({
            url: 'Dashboard/checkQuizTypeByQuizId',
            type: 'POST',
            data: { quizId: quizId, quizOptionId: quizOptionId },
            success: async function(response) {
                const quizInfo = JSON.parse(response);
                console.log("quizInfo============>",quizInfo);
                $('#quizOptionId').val(quizInfo[0].id);
                if(quizInfo.length > 0 && quizInfo[0].name === 'pairs') {
                    const targetDiv = document.getElementById('quizId1');
                    const alreadyAppended = document.getElementById('appendedQuizForm');
                    if(alreadyAppended) alreadyAppended.remove();

                    const htmlContent = `
                    <div id="appendedQuizForm" style="margin-top:16px;">
                        <div class="form-group col-md-12">
                            <label for="optionName" class="form-label">Option Name(Comma Separated List Of Option)</label>
                            <input type="text" class="form-control" name="optionName" id="optionName" placeholder="Ex-A,B,C,D">
                        </div>
                        <div class="form-group col-md-12">
                            <label for="optionPair" class="form-label">Option Pair(Comma Separated List Of Macthing Pairs)</label>
                            <input type="text" class="form-control" name="optionPair" id="optionPair" placeholder="Ex-a,b,c,d">
                        </div>
                       
                    </div>    
                    `;
                  
                    targetDiv.insertAdjacentHTML('afterend', htmlContent);
                    $('#quizTypeName').val(quizInfo[0].name);
                    if(edit === 'true' && quizId) await setQuizOptionFormPairs(quizInfo[0]);
                }else if(quizInfo.length > 0 && quizInfo[0].name === 'select') {
                    const targetDiv = document.getElementById('quizId1');
                    const alreadyAppended = document.getElementById('appendedQuizForm');
                    if(alreadyAppended) alreadyAppended.remove();

                    const htmlContent = `
                    <div id="appendedQuizForm" class="row" style="margin-top:16px;">
                        <div class="form-group col-md-6">
                            <label for="option1" class="form-label">Option 1</label>
                            <input type="text" class="form-control" name="option1" id="option1" placeholder="Enter option 1">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option2" class="form-label">Option 2</label>
                            <input type="text" class="form-control" name="option2" id="option2" placeholder="Enter option 2">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option3" class="form-label">Option 3</label>
                            <input type="text" class="form-control" name="option3" id="option3" placeholder="Enter option 3">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="option4" class="form-label">Option 4</label>
                            <input type="text" class="form-control" name="option4" id="option4" placeholder="Enter option 4">
                        </div>
                        
                        <div class="row">
                            <label for="optionAnswer" class="form-label">Choose Correct Answer</label>
                            <div class="col-md-3">
                                <input type="radio" class="cursor-pointor" name="optionAnswer" id="opt-1" value="1">
                                <label for="opt-1" class="cursor-pointor">Option 1</label>
                            </div>
                            <div class="col-md-3">
                                <input type="radio" class="cursor-pointor" name="optionAnswer" id="opt-2" value="2">
                                <label for="opt-2" class="cursor-pointor">Option 2</label>
                            </div>
                            <div class="col-md-3">
                                <input type="radio" class="cursor-pointor" name="optionAnswer" id="opt-3" value="3">
                                <label for="opt-3" class="cursor-pointor">Option 3</label>
                            </div>
                            <div class="col-md-3">
                                <input type="radio" class="cursor-pointor" name="optionAnswer" id="opt-4" value="4">
                                <label for="opt-4" class="cursor-pointor">Option 4</label>
                            </div>
                        </div>
                    </div>   
                    `;
                    
                    targetDiv.insertAdjacentHTML('afterend', htmlContent);
                    $('#quizTypeName').val(quizInfo[0].name);
                    if(edit === 'true' && quizId) await setQuizOptionFormOptions(quizId);
                }else{
                    const alreadyAppended = document.getElementById('appendedQuizForm');
                    if(alreadyAppended) alreadyAppended.remove();
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
        }
    }

    function resetQuizOptionForm() {
        const alreadyAppended = document.getElementById('appendedQuizForm');
        if(alreadyAppended) alreadyAppended.remove();
    }

    async function openQuizOptionForm(quizOptionId, quizId, quizTypeName, edit) {
        $('#quizFormOptionModal').modal('show');
        $('#modalTitle').text('Quiz');
        $('#quizOptionId').val(quizOptionId);
        $('#quizId1').val(quizId);
        $('#quizTypeName').val(quizTypeName); 
        $('#quizEdit').val(edit); 

        if(edit === 'true') await loadQuizOptionForm(quizId, quizOptionId, edit, true); 
    }

    async function setQuizOptionFormPairs(quizInfo) {
        var optionAns = JSON.parse(quizInfo['optionAnswer'])
        var option = '';
        var optionPair = '';
        for (var key in optionAns){
            option += key+','
            optionPair += optionAns[key]+','
        }
        $("#quizId1").val(quizInfo['quizId']);  
        $('#optionName').val(option.substring(0,option.length - 1));  
        $('#optionPair').val(optionPair.substring(0,optionPair.length - 1));  
        // $('#optionAnswer').val(quizInfo['optionAnswer']);  
    }

    async function setQuizOptionFormOptions(quizId) {
        $.ajax({
            url: 'Dashboard/getQuizAllOptionsByQuizId',
            type: 'POST',
            data: { quizId: quizId },
            success: function(response) {
                const quizOptions = JSON.parse(response);
                if(quizOptions.length > 0) {
                    for(let i = 0; i < quizOptions.length; i++) {
                        let optionValue = quizOptions[i]['name'];
                        let optionAnswer = quizOptions[i]['ans_name'];

                        if(i === 0) {
                            $('#option1').val(optionValue);
                            if(optionValue === optionAnswer) {
                                $('#opt-1').prop('checked', true);
                            }
                        } 
                        if(i === 1) {
                            $('#option2').val(optionValue);
                            if(optionValue === optionAnswer) {
                                $('#opt-2').prop('checked', true);
                            }
                        }
                        if(i === 2) {
                            $('#option3').val(optionValue);
                            if(optionValue === optionAnswer) {
                                $('#opt-3').prop('checked', true);
                            }
                        } 
                        if(i === 3) {
                            $('#option4').val(optionValue);
                            if(optionValue === optionAnswer) {
                                $('#opt-4').prop('checked', true);
                            }
                        }
                    }
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function quizOptionSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const quizOptionId = $('#quizOptionId').val();
        const quizId = $('#quizId1').val();
        const quizTypeName = $('#quizTypeName').val(); 
        
        let formData = {
            quizOptionId: quizOptionId,
            quizId: quizId,
            quizTypeName: quizTypeName
        };

        if (!quizId) {
            validationMessages('quizId1');
            return;
        }

        if(quizTypeName === 'pairs') {
            var optionName = $('#optionName').val();
            var optionPair = $('#optionPair').val();
            // const optionAnswer = $('#optionAnswer').val(); 
            if (optionName.charAt(optionName.length - 1) === ',') {
                optionName = optionName.slice(0, -1);
            }

            if (optionPair.charAt(optionPair.length - 1) === ',') {
                optionPair = optionPair.slice(0, -1);
            }
            var optionNameArr = optionName.split(',');
            var optionPairArr = optionPair.split(',');
          
            var optionNameLength = optionNameArr.length;
            var optionPairLength = optionPairArr.length;
            if (optionNameLength != optionPairLength) {
                validationMessagesWithMessage('optionName','Please use same length pair');
                return;
            }

            if (!optionName) {
                validationMessages('optionName');
                return;
            }
            if (!optionPair) {
                validationMessages('optionPair');
                return;
            }
            // if (!optionAnswer) {
            //     validationMessages('optionAnswer');
            //     return;
            // }

            formData = {...formData,
                optionName: optionName,
                optionPair: optionPair,
                // optionAnswer: optionAnswer               
            }                
        }else{
            const option1 = $('#option1').val().trim();
            const option2 = $('#option2').val().trim();
            const option3 = $('#option3').val().trim();
            const option4 = $('#option4').val().trim();
            let optionAnswer = $('input[name="optionAnswer"]:checked').val();

            if (option1 == '') {
                validationMessages('option1');
                return;
            }

            if (option2 == '') {
                validationMessages('option2');
                return;
            }

            if (option3 == '') {
                validationMessages('option3');
                return;
            }

            if (!option4) {
                validationMessages('option4');
                return;
            }

            if (!optionAnswer) {
                validationMessages('opt-2');
                return;
            }

            if(optionAnswer == '1') {
                optionAnswer = option1;
            }else if(optionAnswer == '2') {
                optionAnswer = option2;
            }else if(optionAnswer == '3') {
                optionAnswer = option3;
            }else{
                optionAnswer = option4;
            }

            formData = {...formData,
                option1: option1,
                option2: option2,
                option3: option3,
                option4: option4,
                optionAnswer: optionAnswer
            }
        }

        if (quizOptionId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

         $.ajax({
             url: 'Extra/updateQuizOptionSubmit',
             type: 'POST',
             data: formData,
             success: function(response) {
                   Swal.fire({
                     icon: 'success',
                     title: 'Success!',
                     text: messages,
                     confirmButtonClass: "btn btn-primary w-xs mt-2",
                     buttonsStyling: false
                 });
                 $('#formModal').modal('hide');
                 const id = <?php echo $id; ?>;
                 const handle = pageNameBefore;
                 const main_final_url = window.location.href;
                 const currentUrl = pageNameBefore;
                 const tablename = <?php echo json_encode($tablename); ?>;
               
                 $.ajax({
                     url: "Extra/viewformdata",
                     method: "POST",
                     data: { 
                         id: id,
                         handle: handle, 
                         main_final_url: main_final_url,
                         currentUrl: currentUrl, 
                         tablename: tablename 
                     },
                     success: function(data) {
                         $("#dataviwlist").html(data);
                     },
                     error: function(xhr, status, error) {
                         console.error(xhr.responseText);
                     }
                 });
             },
             error: function(xhr, status, error) {
                 console.error(xhr.responseText);
             }
         });
    }

    function deleteQuizOptionRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_quiz_option_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }


    function getQuizQuestionDetail(quizId) {
           $.ajax({
            url: 'Dashboard/getQuizAllOptionsByQuizId',
            type: 'POST',
            data: { quizId: quizId},
            success: function(response) {
                const detail = JSON.parse(response);
                $('#quizQuestionDetailModal').modal('show');
                $('#modalTitle').text('Question');
                
                let quizTypeName = '';
                if(detail.length > 0) quizTypeName = detail[0]?.quizTypeName;

                if(quizTypeName === 'select') { 
                    let options  = '<div class="quiz-option-container">';
                    for (let index = 0; index < detail.length; index++) {
                        const question = detail[index].question;
                        const option = detail[index].name;
                        const optionPair = detail[index].pair;
                        const answer = detail[index].ans_name;
    
                        if(index === 0) {
                            options += `<div><b>Question:</b> ${question} </div>
                                        <div class="quiz-option-modal">`;
                        }
    
                        options += `<div>
                                        ${index + 1}. ${option}
                                    </div>`;
    
                        if(index === 3) {
                        options += `    </div>
                                        <div>
                                            <b>Answer:</b> ${answer}
                                        </div>
                                        </div>`;
                        }
                    }

                    const body = document.getElementById('questionDetailModal');
                    body.innerHTML = options;                    
                }

                if(quizTypeName === 'pairs') { 
                    let options = `<div class="quiz-pair-container">`;
                    var optionAns = JSON.parse(detail[0]['ans_name'])
                    const question = detail[0].question;
                    index = 0;
                    for (var key in optionAns){
                        if(index === 0) {
                            options += `<div><b>Question:</b> ${question} </div>
                                        <table>
                                        <tr>
                                            <th>Option</th>
                                            <th>Pair</th>
                                        </tr>`;
                        }
    
                        options += `<tr>
                                        <td>${key}</td>
                                        <td>${optionAns[key]}</td>
                                    </tr>`;
    
                        if(index == Object.entries(optionAns).length -1) {
                            options += `</table></div>`;
                        }
                        index = index+1
                    }
                    
                    const body = document.getElementById('questionDetailModal');
                    body.innerHTML = options;                    
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }
</script>

  <!--LIST OF STUDENT QUIZ ANSWER-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Student Quiz Answer</h4>   
    </div>  
    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_quizAnswer" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>User Name</th>
                        <th>Quiz Name</th>
                        <th>Quiz Type Name</th>
                        <th>Answer</th>
                        <th>Is Checked</th>
                        <th>Is Skipped</th>
                        <th>Is Wrong</th>
                        <th>Is Correct</th>
                        <th>Is Pending</th>
                        <th>Marks</th>
                        <th>Duration</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $studentsQuizAnswers = $this->db->query("select a.*,b.name as main_quiz_name,c.name as quiz_name,a.quiz_type,e.full_name from child_quiz_answer a
                    left outer join main_quiz b on a.main_quiz_id = b.id
                    left outer join quiz c on a.quiz_id = c.id 
                    left outer join users e on a.user_id = e.id 
                    where b.id = '$id' and a.status!='2' and b.status != '2' and c.status != '2' 
                    and e.status != '2' order by a.added_date desc;")->result_array();
                    foreach ($studentsQuizAnswers as $results) { ?>
                        <tr class="odd">
                            <td>
                                <?php if($results['quiz_type'] === 'written') {?>
                                    <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openStudentQuizAnswerForm('<?php echo $results['id']; ?>','<?php echo $results['full_name']; ?>','<?php echo $results['quiz_name']; ?>',
                                    '<?php echo $results['quiz_type']; ?>','<?php echo $results['answer']; ?>','<?php echo $results['is_checked']; ?>','<?php echo $results['is_skipped']; ?>','<?php echo $results['is_wrong']; ?>','<?php echo $results['is_correct']; ?>',
                                    '<?php echo $results['is_pending']; ?>','<?php echo $results['marks']; ?>',);"><i class="bx bx-pencil"></i></a>
                                <?php } ?>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteStudentQuizAnswerRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['full_name']; ?></td>
                            <td><?php echo $results['quiz_name'];?></td>
                            <td><?php echo $results['quiz_type']; ?></td>
                            <td><?php echo $results['answer']; ?></td>
                            <td><?php echo $results['is_checked'];?></td>
                            <td><?php echo $results['is_skipped']; ?></td>
                            <td><?php echo $results['is_wrong']; ?></td>
                            <td><?php echo $results['is_correct'];?></td>
                            <td><?php echo $results['is_pending']; ?></td>
                            <td><?php echo $results['marks'];?></td>
                            <td><?php echo $results['duration']; ?></td>
                            <td><?php echo $results['added_date']; ?></td>        
                            <td><?php echo $results['updated_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--STUDENT QUIZ ANSWER MODEL-->
<div id="quizAnswerFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Quiz Answer</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="quizAnswerId" name="quizAnswerId">

            <div class="row">
                <div class="form-group col-md-12">
                    <label for="uFullName" class="form-label">Full Name</label>
                    <input type="text" class="form-control" name="uFullName" id="uFullName" placeholder="Enter Correct Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="quizName1" class="form-label">Quiz Name</label>
                    <input type="text" class="form-control" name="quizName1" id="quizName1" placeholder="Enter Correct Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="quizTypeName1" class="form-label">Quiz Type Name</label>
                    <input type="text" class="form-control" name="quizTypeName1" id="quizTypeName1" placeholder="Enter Correct Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="quizAnswer1" class="form-label">Answer</label>
                    <input type="text" class="form-control" name="quizAnswer1" id="quizAnswer1" placeholder="Enter Correct Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="isChecked" class="form-label">Is Checked</label>
                    <input type="text" class="form-control" name="isChecked" id="isChecked" placeholder="Enter Skipped Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="isSkipped" class="form-label">Is Skipped</label>
                    <input type="text" class="form-control" name="isSkipped" id="isSkipped" placeholder="Enter Wrong Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="isWrong" class="form-label">Is Wrong</label>
                    <input type="text" class="form-control" name="isWrong" id="isWrong" placeholder="Enter Completed Question">
                </div>
                <div class="form-group col-md-12">
                    <label for="isCorrect" class="form-label">Is Correct</label>
                    <input type="text" class="form-control" name="isCorrect" id="isCorrect" placeholder="Enter duration">
                </div>
                <div class="form-group col-md-12">
                    <label for="isPending" class="form-label">Is Pending</label>
                    <input type="text" class="form-control" name="isPending" id="isPending" placeholder="Enter score">
                </div>
                <div class="form-group col-md-12">
                    <label for="marks1" class="form-label">Marks</label>
                    <input type="text" class="form-control" name="marks1" id="marks1" placeholder="Enter score">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="studentQuizAnswerSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    function openStudentQuizAnswerForm(quizAnswerId, fullName, quizName, quizType, Answer, isChecked, isSkipped, isWrong, isCorrect, isPending, marks) {
        $('#quizAnswerFormModal').modal('show');
        $('#modalTitle').text('Quiz Answer');
        $('#quizAnswerId').val(quizAnswerId);
        $('#uFullName').val(fullName);
        $('#uFullName').attr('disabled', 'disabled');

        $('#quizName1').val(quizName); 
        $('#quizName1').attr('disabled', 'disabled');

        $('#quizTypeName1').val(quizType); 
        $('#quizTypeName1').attr('disabled', 'disabled');

        $('#quizAnswer1').val(Answer);
        $('#isChecked').val(isChecked);
        $('#isSkipped').val(isSkipped); 
        $('#isWrong').val(isWrong); 
        $('#isCorrect').val(isCorrect);
        $('#isPending').val(isPending);
        $('#marks1').val(marks); 
    }

    function studentQuizAnswerSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const quizAnswerId = $('#quizAnswerId').val();
        const quizAnswer = $('#quizAnswer1').val();
        const isChecked = $('#isChecked').val();
        const isSkipped = $('#isSkipped').val();
        const isWrong = $('#isWrong').val();
        const isCorrect = $('#isCorrect').val();
        const isPending = $('#isPending').val();
        const marks = $('#marks1').val();
        
        if (!quizAnswerId) {
            validationMessages('quizAnswerId');
            return;
        }
        if (!quizAnswer) {
            validationMessages('quizAnswer1');
            return;
        }
        if (!isChecked) {
            validationMessages('isChecked');
            return;
        }
        if (!isSkipped) {
            validationMessages('isSkipped');
            return;
        }
        if (!isWrong) {
            validationMessages('isWrong');
            return;
        }
        if (!isCorrect) {
            validationMessages('isCorrect');
            return;
        }
        if (!isPending) {
            validationMessages('isPending');
            return;
        }
        if (!marks) {
            validationMessages('marks1');
            return;
        }

        const formData = { quizAnswerId, quizAnswer, isChecked, isSkipped, isWrong, isCorrect, isPending, marks }

        if (quizAnswerId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateStudentQuizAnswerSubmit',
            type: 'POST',
            data: formData,
            success: function(response) {
                  Swal.fire({
                    icon: 'success',
                    title: 'Success!',
                    text: messages,
                    confirmButtonClass: "btn btn-primary w-xs mt-2",
                    buttonsStyling: false
                });
                $('#formModal').modal('hide');
                const id = <?php echo $id; ?>;
                const handle = pageNameBefore;
                const main_final_url = window.location.href;
                const currentUrl = pageNameBefore;
                const tablename = <?php echo json_encode($tablename); ?>;
               
                $.ajax({
                    url: "Extra/viewformdata",
                    method: "POST",
                    data: { 
                        id: id,
                        handle: handle, 
                        main_final_url: main_final_url,
                        currentUrl: currentUrl, 
                        tablename: tablename 
                    },
                    success: function(data) {
                        $("#dataviwlist").html(data);
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteStudentQuizAnswerRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_studentQuizAnswer_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }
</script>


  <!--LIST OF STUDENT QUIZ RESULT-->
  <div class="row cstm-modal">
    <div align="right" class="col-md-12">
        <h4 style="float:left; padding-top:15px;">List Of Quiz Result</h4>   
        <div class="btn-group" role="group" aria-label="Button group with nested dropdown">
            <button type="button" class="btn btn-primary add-btn btn-sm" style="padding:5px;float:right;" onclick="openStudentQuizResultForm('<?php echo $id; ?>','','','','','','','','','','','false');" ><i class="ri-add-line align-bottom me-1"></i>Add Quiz Result</button>
        </div>
    </div>  

    <div class="table-responsive table-car">
        <div id="ajax_datatables2_lessiondtl" class="dataTables_wrapper dt-bootstrap5 no-footer">
            <table id="ajax_datatables2_quizResult" class="table align-middle table-hover table-nowrap table-striped-columns mb-0 dataTable no-footer" aria-describedby="ajax_datatables2_info">
                <thead class="table-light">
                    <tr>
                        <th>Action</th>
                        <th>User Name</th>
                        <th>Badge Name</th>
                        <th>Correct Answer</th>
                        <th>Skipped Answer</th>
                        <th>Wrong Answer</th>
                        <th>Completion Question</th>
                        <th>Duration</th>
                        <th>Score</th>
                        <th>Created At</th>
                        <th>Updated At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $studentsQuizResults = $this->db->query("select a.*,b.title as badge_name,c.name as quiz_name,d.full_name from child_quiz_result a
                    left outer join badges b on a.badge_id = b.id
                    left outer join main_quiz c on a.main_quiz_id = c.id
                    left outer join users d on d.id = a.user_id
                    where c.id = '$id' and a.status != '2'  and c.status != '2' order by a.added_date desc;")->result_array();
                    foreach ($studentsQuizResults as $results) { ?>
                        <tr class="odd">
                            <td>
                                <a style="padding:3px;" class="btn btn-primary btn-sm btn-edit" onclick="openStudentQuizResultForm('<?php echo $id; ?>','<?php echo $results['id']; ?>','<?php echo $results['user_id']; ?>','<?php echo $results['full_name']; ?>','<?php echo $results['badge_id']; ?>', '<?php echo $results['correct_ans']; ?>','<?php echo $results['skipped_ans']; ?>','<?php echo $results['wrong_ans']; ?>','<?php echo $results['completion_ques']; ?>','<?php echo $results['duration']; ?>','<?php echo $results['score']; ?>','true');"><i class="bx bx-pencil"></i></a>
                                <a style="padding:3px;" class="btn btn-danger btn-sm btn-delete" onclick="deleteStudentQuizResultRecord('<?php echo $results['id']; ?>');"><i class="ri-delete-bin-5-line"></i></a>
                            </td>
                            <td><?php echo $results['full_name']; ?></td>
                            <td><?php echo $results['badge_name'];?></td>
                            <td><?php echo $results['correct_ans'];?></td>
                            <td><?php echo $results['skipped_ans'];?></td>
                            <td><?php echo $results['wrong_ans'];?></td>
                            <td><?php echo $results['completion_ques'];?></td>
                            <td><?php echo $results['duration'];?></td>
                            <td><?php echo $results['score'];?></td>
                            <td><?php echo $results['added_date']; ?></td>        
                            <td><?php echo $results['updated_date']; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!--STUDENT QUIZ RESULT MODEL-->
<div id="studentQuizResultFormModal" class="inner-modal modal fade" data-bs-backdrop='static'>
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalTitle">Quiz Result</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"> </button>
      </div>
      <div class="modal-body">
        <form id="updateForm" method="POST" class="tablelist-form">
            <input type="hidden" id="mainQuizId1" name="mainQuizId1">
            <input type="hidden" id="quizId" name="quizId">
            <input type="hidden" id="resultId" name="resultId">
            <input type="hidden" id="lastAppended1" name="lastAppended1">

            <div class="row">
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Users(Quiz Submitted)</label>
                    <select class="form-control" name="rUserId" id="rUserId" onchange=getQuizAnswer(this)>
                        <option value="">Select user...</option>
                        <?php $usersList = $this->db->query("select DISTINCT e.id, e.full_name as name from child_quiz_answer a
                        left outer join users e on e.id = a.user_id 
                        where a.status != '2' and e.status != '2' and a.main_quiz_id='$id'")->result_array(); ?>
                        <?php foreach ($usersList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Quiz(Quiz Submitted)</label>
                    <?php $mainQuiz = $this->db->query("select id,name from main_quiz where status != '2' and id='$id';")->row_array(); ?>
                    <input type="text" class="form-control" name="mainQuiz" id="selectedQuizId" value="<?php echo $mainQuiz['name'] ?>" disabled>
                   
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label class="form-label" for="material_type">Badges</label>
                    <select class="form-control" name="rBadgeId" id="rBadgeId">
                        <option value="">Select badge...</option>
                        <?php $badgeList = $this->db->query("select id,title as name from badges where status != '2';")->result_array(); ?>
                        <?php foreach ($badgeList as $q): ?>
                            <option value="<?php echo $q['id']; ?>"><?php echo $q['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group col-md-12">
                    <label for="correctAns" class="form-label">Correct Answer</label>
                    <input type="text" class="form-control" name="correctAns" id="correctAns" placeholder="Enter Correct Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="skippedAns" class="form-label">Skipped Answer</label>
                    <input type="text" class="form-control" name="skippedAns" id="skippedAns" placeholder="Enter Skipped Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="wrongAns" class="form-label">Wrong Answer</label>
                    <input type="text" class="form-control" name="wrongAns" id="wrongAns" placeholder="Enter Wrong Answer">
                </div>
                <div class="form-group col-md-12">
                    <label for="completedQuestion" class="form-label">Completed Question</label>
                    <input type="text" class="form-control" name="completedQuestion" id="completedQuestion" placeholder="Enter Completed Question">
                </div>
                <div class="form-group col-md-12">
                    <label for="quizDuration" class="form-label">Quiz Duration</label>
                    <input type="text" class="form-control" name="quizDuration" id="quizDuration" placeholder="Enter duration">
                </div>
                <div class="form-group col-md-12">
                    <label for="quizScore" class="form-label">Quiz Score</label>
                    <input type="text" class="form-control" name="quizScore" id="quizScore" placeholder="Enter score">
                </div>
                <div class="form-group col-md-12">
                    <button type="button" class="btn btn-primary btn-block" onclick="studentQuizResultSubmit()">Submit</button>
                </div>
            </div>
        </form>
        <div id="responseMessage"></div>
      </div>
    </div>
  </div>
</div>

<script>
    function openStudentQuizResultForm(mainQuizId, resultId, userId, userName, badgeId, correctAns, skippedAns, wrongAns, completedQuestion, duration, score, edit) {
            $('#studentQuizResultFormModal').modal('show');
            $('#modalTitle').text('Quiz');
            $('#mainQuizId1').val(mainQuizId);
            $('#resultId').val(resultId);
            $('#rBadgeId').val(badgeId);
            $('#correctAns').val(correctAns);
            $('#skippedAns').val(skippedAns);
            $('#wrongAns').val(wrongAns);
            $('#completedQuestion').val(completedQuestion);
            $('#quizDuration').val(duration);
            $('#quizScore').val(score);

        if(edit === 'true') {
            var newOption = $('<option>', { value: userId, text: userName});
            $('#rUserId').append(newOption);
            $('#rUserId').val(userId);
            $('#lastAppended1').val(userId);
            $('#rUserId').prop('disabled', true);
            if(parseInt(score) < 70 ){
                $('#rBadgeId').attr('disabled', true);
            }else{
                $('#rBadgeId').attr('disabled', false);
            }
        }else{
            const lastAppended = $('#lastAppended1').val();
            if(lastAppended) {
                $('#rUserId option[value="' + lastAppended + '"]').remove();
            }else{
                $('#lastAppended1').val('');
            }
            $('#rUserId').val('');
            $('#rUserId').prop('disabled', false);
        }
    }
    
    function studentQuizResultSubmit() {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        const mainQuizId = $('#mainQuizId1').val();
        const resultId = $('#resultId').val();
        const userId = $('#rUserId').val();
        var badgeId = $('#rBadgeId').val();
        const correctAns = $('#correctAns').val();
        const skippedAns = $('#skippedAns').val();
        const wrongAns = $('#wrongAns').val();
        const completedQuestion = $('#completedQuestion').val();
        const quizDuration = $('#quizDuration').val();
        const quizScore = $('#quizScore').val();
        
        if (!mainQuizId) {
            validationMessages('mainQuizId');
            return;
        }
        if (!userId) {
            validationMessages('rUserId');
            return;
        }
        if (!badgeId) {
            badgeId = 0;
        }
        if (!correctAns) {
            validationMessages('correctAns');
            return;
        }
        if (!skippedAns) {
            validationMessages('skippedAns');
            return;
        }
        if (!wrongAns) {
            validationMessages('wrongAns');
            return;
        }
        if (!completedQuestion) {
            validationMessages('completedQuestion');
            return;
        }
        if (!quizDuration) {
            validationMessages('quizDuration');
            return;
        }
        if (!quizScore) {
            validationMessages('quizScore');
            return;
        }

        const formData = { mainQuizId, resultId, userId, badgeId, correctAns, skippedAns, wrongAns, completedQuestion, quizDuration, quizScore}

        if (resultId != 0) {
            var messages = 'Record updated successfully';
        }else {
            var messages = 'Record inserted successfully';
        }

        $.ajax({
            url: 'Extra/updateStudentQuizResultSubmit',
            type: 'POST',
            data: formData,
            success: function(response) {
                    if($($.parseHTML(response)).filter("#success").text()){
                    Swal.fire({
                            icon: 'info',
                            title: 'No!',
                            text: $($.parseHTML(response)).filter("#success").text(),
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    }else{
                    Swal.fire({
                        icon: 'success',
                        title: 'Success!',
                        text: messages,
                        confirmButtonClass: "btn btn-primary w-xs mt-2",
                        buttonsStyling: false
                    });
                    $('#formModal').modal('hide');
                    const id = <?php echo $id; ?>;
                    const handle = pageNameBefore;
                    const main_final_url = window.location.href;
                    const currentUrl = pageNameBefore;
                    const tablename = <?php echo json_encode($tablename); ?>;
                
                    $.ajax({
                        url: "Extra/viewformdata",
                        method: "POST",
                        data: { 
                            id: id,
                            handle: handle, 
                            main_final_url: main_final_url,
                            currentUrl: currentUrl, 
                            tablename: tablename 
                        },
                        success: function(data) {
                            $("#dataviwlist").html(data);
                        },
                        error: function(xhr, status, error) {
                            console.error(xhr.responseText);
                        }
                    });
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
            }
        });
    }

    function deleteStudentQuizResultRecord(id) {
        const pathArray = window.location.pathname.split('/');
        const pageNameIndex = pathArray.lastIndexOf(window.location.pathname);
        const pageNameBefore = pathArray[pathArray.length - 2];
        
        Swal.fire({
            title: "Are you sure?",
            text: "You won't be able to recover this entity!.",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, cancel!",
            confirmButtonClass: "btn btn-primary w-xs me-2 mt-2",
            cancelButtonClass: "btn btn-danger w-xs mt-2",
            buttonsStyling: false,
            showCloseButton: true,
        }).then(function (result) {
            if (result.value) {
                $.ajax({
                    url: 'Extra/delete_studentQuizResult_record',
                    type: "POST",
                    data: {
                        "id": id
                    },
                    success: function (response) {
                        const id = <?php echo $id; ?>;
                        const handle = pageNameBefore;
                        const main_final_url = window.location.href;
                        const currentUrl = pageNameBefore;
                        const tablename = <?php echo json_encode($tablename); ?>;

                        $.ajax({
                            url: "Extra/viewformdata",
                            method: "POST",
                            data: { 
                                id: id,
                                handle: handle, 
                                main_final_url: main_final_url,
                                currentUrl: currentUrl, 
                                tablename: tablename 
                            },
                            success: function(data) {
                                $("#dataviwlist").html(data);
                            },
                            error: function(xhr, status, error) {
                                console.error(xhr.responseText);
                            }
                        });
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'Deleted!',
                            text: 'Deleted successfully',
                            confirmButtonClass: "btn btn-primary w-xs mt-2",
                            buttonsStyling: false
                        });
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Failed to delete record. Please try again.',
                            confirmButtonClass: "btn btn-primary mt-2",
                            buttonsStyling: false
                        });
                    }
                });
            } else if (result.dismiss === Swal.DismissReason.cancel) {
                Swal.fire({
                    title: "Cancelled",
                    text: "Your data is safe :)",
                    icon: "error",
                    confirmButtonClass: "btn btn-primary mt-2",
                    buttonsStyling: false
                });
            }
        });
    }
    function getQuizAnswer(event){
         var main_quiz_id = $('#mainQuizId1').val()
        if(event.value){
            $.ajax({
                    url: 'Dashboard/getQuizCaclulatedResult',
                    type: "POST",
                    data: {
                        "user_id": event.value,
                        "main_quiz_id": main_quiz_id
                    },
                    success: function (response) {
                        response = JSON.parse(response)
                        if(response.success){
                            $('#correctAns').val(response.correctAns)
                            $('#skippedAns').val(response.skippedAns)
                            $('#wrongAns').val(response.wrongAns)
                            $('#completedQuestion').val(response.completedQuestion)
                            $('#quizDuration').val(response.totalDuration)
                            $('#quizScore').val(response.quizScore)
                            if(parseInt(response.quizScore) < 70 ){
                                $('#rBadgeId').attr('disabled', true);
                            }else{
                                $('#rBadgeId').attr('disabled', false);
                            }

                        }else{
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: 'Result already created.',
                                confirmButtonClass: "btn btn-primary mt-2",
                                buttonsStyling: false
                            });

                        }
                       
                    }
                })
        }else{
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'User not selected, please select the user.',
                confirmButtonClass: "btn btn-primary mt-2",
                buttonsStyling: false
            });
        }
    }

    
</script>
<?php } ?>

<?php if ($tablename =='quiz'){
$result = $this->db->query("select a.*,b.name as main_quiz_name,c.name as typename from quiz a "
                    . " left outer join main_quiz b on a.main_quiz_id=b.id "
                        . "left outer join quiz_type c on a.select_type=c.id "
                    . " where a.id=$id and a.status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
                 <tr>
                    <td><strong>Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Main quiz Name:</strong></td>
                    <td><?php echo $result['main_quiz_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Select Type:</strong></td>
                    <td><?php echo $result['typename']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='quiz_options'){
$result = $this->db->query("select a.*,b.name as quiz_name from quiz_options a "
                    . " left outer join quiz b on a.quiz_id=b.id "
                    . " where a.id=$id and a.status!='2'  ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
               <tr>
                    <td><strong>Quiz Name:</strong></td>
                    <td><?php echo $result['quiz_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Option:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Answer Name:</strong></td>
                    <td><?php echo $result['ans_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Image:</strong></td>
                    <td><?php echo $result['image']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='quiz_type'){
$result = $this->db->query("select * from quiz_type where id=$id; ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
               <tr>
                    <td><strong> Name:</strong></td>
                    <td><?php echo $result['name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
<?php } ?>

<?php if ($tablename =='child_quiz_answer'){
$result = $this->db->query("select a.*,b.name as main_quiz_name,c.name as quiz_name,e.username as user_name from child_quiz_answer a "
                    . "left outer join main_quiz b on a.main_quiz_id=b.id "
                    . "left outer join quiz c on a.quiz_id=c.id "
                    // . "left outer join quiz_options d on a.pair_id=d.id "
                        . "left outer join users e on e.id=a.user_id "
                    . " where  a.id=$id and a.status!='2' ")->row_array();
$added_date=$result['added_date'];
$updated_date=$result['updated_date'];
?>
  <div class="col-md-6">
        <div class="user-profile">
            <div>
            <table>
               <tr>
                    <td><strong> Main Quiz Name:</strong></td>
                    <td><?php echo $result['main_quiz_name']; ?></td>
                </tr>
                <tr>
                    <td><strong>Quiz Name:</strong></td>
                    <td><?php echo $result['quiz_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Quiz Type:</strong></td>
                    <td><?php echo $result['quiz_type']; ?></td>
                </tr>
                 <tr>
                    <td><strong> User Name:</strong></td>
                    <td><?php echo $result['user_name']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Answer:</strong></td>
                    <td><?php echo $result['answer']; ?></td>
                </tr>
                 <tr>
                    <td><strong>Is Checked:</strong></td>
                    <td><?php echo $result['is_checked']; ?></td>
                </tr>
                 <tr>
                    <td><strong> Is Skipped:</strong></td>
                    <td><?php echo $result['is_skipped']; ?></td>
                </tr>
                 <tr>
                    <td><strong> Is Wrong:</strong></td>
                    <td><?php echo $result['is_wrong']; ?></td>
                </tr>
                <tr>
                    <td><strong> Is Correct:</strong></td>
                    <td><?php echo $result['is_correct']; ?></td>
                </tr>
                <tr>
                    <td><strong> Is Pending:</strong></td>
                    <td><?php echo $result['is_pending']; ?></td>
                </tr>
                <tr>
                    <td><strong> Marks:</strong></td>
                    <td><?php echo $result['marka']; ?></td>
                </tr>
                <tr>
                    <td><strong> Duration:</strong></td>
                    <td><?php echo $result['duration']; ?></td>
                </tr>
                  <tr>
                    <td><strong>Added Date:</strong></td>
                     <td><?php 
                    if($added_date=='0000-00-00 00:00:00')
                        {
                           echo  $added_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($added_date)); 
                        } 
                    ?></td>
                </tr>
                 <tr>
                    <td><strong>Updated Date:</strong></td>
                    <td><?php 
                    if($updated_date=='0000-00-00 00:00:00')
                        {
                           echo  $updated_date="-";    
                        }else
                        {
                            echo date ('F j,Y',strtotime($updated_date)); 
                        } 
                    ?></td>
                </tr>
         </table>
               </div>
               </div>
</div>
                                                  
<?php } ?>

    <?php      
}

public function updateChapter() {
    $chapter_id = (int) $this->input->post('chapter_id');
    $course_id = (int) $this->input->post('course_id');
    $chapter_no = $this->input->post('chapter_no');
    $chapter_name = $this->input->post('chapter_name');
    $date = date("Y-m-d H:i:s");

    if ($chapter_id > 0) {
        $this->db->set('chapter_name', $chapter_name);
        $this->db->set('updated_date', $date);
        $this->db->where('id', $chapter_id); 
        $this->db->update('chapter');

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record');
        }
    } else {
        $data = array(
            'chapter_no' => $chapter_no,
            'chapter_name' => ucfirst($chapter_name),
            'course_id' => $course_id,
            'added_date' => $date,
            'updated_date' => $date
        );
        $this->db->insert('chapter', $data);

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record');
        }
    }

    echo json_encode($response);
}

public function updateMaterial() {
    // Sanitize form data
    $data = $_POST;
    $course_id = (int) $data['course_id'];
    $material_id = (int) $data['material_id'];
    $title = $data['title'];
    $material_type = $data['material_type'];
    $date = date("Y-m-d H:i:s");

    if ($material_id > 0) {
        // Update data in database
        $material_content_file_path = null;
        if(!empty($_FILES["material_content"]["name"])) {
            $target_dir = "uploads/course_material/";
            $target_file = $target_dir . rand()."-".$_FILES["material_content"]["name"];
            if (move_uploaded_file($_FILES["material_content"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["material_content"]["name"])). " has been uploaded.";
                $material_content_file_path = base_url().$target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }

        $this->db->set('title', $title);
        $this->db->set('material_type', $material_type);
        $this->db->set('updated_date', $date);
        if($material_content_file_path != null) {
            $this->db->set('material_content', $material_content_file_path);
        }
        $this->db->where('id', $material_id); 
        $this->db->update('course_material');

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record');
        }
    } else {
        $material_content_file_path = null;
        if(!empty($_FILES["material_content"]["name"])) {
            $target_dir = "uploads/course_material/";
            $target_file = $target_dir . rand()."-".$_FILES["material_content"]["name"];
            if (move_uploaded_file($_FILES["material_content"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["material_content"]["name"])). " has been uploaded.";
                $material_content_file_path = base_url().$target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }   
       
        $data = array(
            'title' => ucfirst($title),
            'material_type' => $material_type,
            'material_content' => $material_content_file_path,
            'course_id' => $course_id,
            'added_date' => $date,
            'updated_date' => $date
        );
        $this->db->insert('course_material', $data);

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record');
        }
    }

    echo json_encode($response);
}

public function updateClasses() {
    $this->load->library('session');
    $data = $_POST;
    $this->load->model('MasterModel');
    $user_id =  $this->session->userdata('id');
    if($user_id == null){
        $user_id = 0 ;
    }
    $image_main_files = null;
    $data['table'] = 'upcoming_classes';
    $dimondmaster_id = $this->MasterModel->add_data($data,$user_id,$image_main_files);
    if ($dimondmaster_id > 0) {
        $response = array('success' => true, 'message' => 'Record inserted successfully');
    } elseif($dimondmaster_id == -1) {
        header('HTTP/1.1 403 Internal Server Error');
        header('Content-Type: application/json; charset=UTF-8');
        $response = array('status' => 'error', 'message' => 'Can not add class over lesson duration');
    }else {
        $response = array('status' => 'error', 'message' => 'Error inserting record');
    }
    echo json_encode($response);
   
}


public function updateExercise() {

    // Sanitize form data
    $chapter_id = (int) $this->input->post('chapter_id');
    $exercise_id = (int) $this->input->post('exercise_id');
    $course_id = (int) $this->input->post('course_id');
    $exercise_no = (int) $this->input->post('exercise_no');
    $task = $this->input->post('task');
    $time = $this->input->post('time');
    $submit_date = $this->input->post('submit_date');

    $date = date("Y-m-d H:i:s");

    if ($exercise_id > 0) {
        // Update data in database
        $exercise_file_path = null;
        if(!empty($_FILES["file"]["name"])) {
            $target_dir = "uploads/course_exercise/";
            if(!is_dir($target_dir)){
                mkdir($target_dir , 0777);
            }
            $target_file = $target_dir . rand()."-".$_FILES["file"]["name"];
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
                $exercise_file_path = base_url().$target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        } 

        $this->db->set('exercise_no', $exercise_no);
        $this->db->set('chapter_id', $chapter_id);
        $this->db->set('task', $task); 
        $this->db->set('time', $time);
        if($exercise_file_path != null) {
            $this->db->set('file', $exercise_file_path);
        }
        $this->db->set('submit_date', $submit_date);
        $this->db->set('updated_date', $date);
        $this->db->where('id', $exercise_id); 
        $this->db->update('course_exercise');

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record');
        }
    } else {
        if ($exercise_no === 0) {
            $exercisesrno = $this->db->query("SELECT COALESCE(MAX(a.exercise_no), 0) + 1 AS srno "
                    . "FROM course_exercise a "
                    . "left outer join chapter b on b.id = a.chapter_id "
                    . "WHERE a.status != 2 AND b.course_id = '$course_id'  ")->row_array();
            $exercisesrno = $exercisesrno['srno'];
        } else {
            $exercisesrno = $exercise_no;
        }

        $exercise_file_path = null;
        if(!empty($_FILES["file"]["name"])) {
            $target_dir = "uploads/course_exercise/";
            if(!is_dir($target_dir)){
                mkdir($target_dir , 0777);
            }
            $target_file = $target_dir . rand()."-".$_FILES["file"]["name"];
            if (move_uploaded_file($_FILES["file"]["tmp_name"], $target_file)) {
                echo "The file ". htmlspecialchars( basename( $_FILES["file"]["name"])). " has been uploaded.";
                $exercise_file_path = base_url().$target_file;
            } else {
                echo "Sorry, there was an error uploading your file.";
            }
        }  

        $data = array(
            'exercise_no' => $exercisesrno,
            'task' => $task,
            'chapter_id' => $chapter_id,
            'time' => $time,
            'file' => $exercise_file_path,
            'submit_date' => $submit_date,
            'added_date' => $date,
            'updated_date' => $date
        );
        $this->db->insert('course_exercise', $data);

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record');
        }
    }

    echo json_encode($response);
}

public function updateMasterClass() {
    // Sanitize form data
    $master_class_id = (int) $this->input->post('master_class_id');
    $chapter_id = (int) $this->input->post('chapter_id');
    $className = $this->input->post('class_name');
    $classDuration = $this->input->post('time');
    $date = date("Y-m-d H:i:s");

    if ($master_class_id > 0) {
        $this->db->set('chapter_id', $chapter_id);
        $this->db->set('class_name', $className); 
        $this->db->set('class_duration', $classDuration);
        $this->db->set('updated_at', $date);
        $this->db->where('id', $master_class_id); 
        $this->db->update('master_classes');

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record');
        }
    } else {
        $data = array(
            'chapter_id' => $chapter_id,
            'class_name' => ucfirst($className),
            'class_duration' => $classDuration,
            'created_at' => $date,
            'updated_at' => $date
        );
        $this->db->insert('master_classes', $data);

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record inserted successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error inserting record');
        }
    }

    echo json_encode($response);
}


    public function loadpagewisesearch() {
        $searchTerm = $_GET['search'];
        $value = $_GET['value'];
        $selectId = $_GET['selectId'];
        $course_type_id = $_GET['course_type_id'];
//        echo $course_type_id;
//        exit;
        if ($selectId == 'vendor' || $selectId == 'vendor_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.= "  (c.name  like '%$searchTerm%' OR p.first_name like '%$searchTerm%' OR p.last_name like '%$searchTerm%') and";
            }
            if ($value != '') {
                $sql.= " p.id='$value' and";
            }
            $sql .= " p.type='2' and";
            
            $sql.=" p.status!='2' and";
            
            
            $sql = rtrim($sql, "and");
            
            $currency = $this->db->query("SELECT p.id as value,CONCAT(p.first_name,' ',p.last_name,' ',c.name) as text "
                            . "FROM `partners_customer` p "
                            . " LEFT JOIN company_name c ON c.id=p.company_name "
                            . " where 1 and $sql having text!=NULL OR text!='' ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'category' || $selectId == 'category_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `categories` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'payment_id' || $selectId == 'payment_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `payment` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'pair_id' || $selectId == 'pair_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" ans_name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR ans_name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `quiz_options` where 1 and $sql ")->result_array();
              
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        
     if ($selectId == '	receiver_id' || $selectId == 'receiver_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,username as text FROM `users` where 1 and $sql ")->result_array();
              
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'sender_id' || $selectId == 'sender_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,username as text FROM `users` where 1 and $sql ")->result_array();
              
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
          if ($selectId == 'homework_id' || $selectId == 'homework_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" exercise_id  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR exercise_id='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,exercise_id as text FROM `homework` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        if ($selectId == 'main_quiz_id' || $selectId == 'main_quiz_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name  ='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `main_quiz` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
         if ($selectId == 'quiz_id' || $selectId == 'quiz_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `quiz` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        if ($selectId == 'time_slot_id' || $selectId == 'time_slot_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `course_time_slotsender` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
     if ($selectId == 'upcoming_id' || $selectId == 'upcoming_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" b.chapter_name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" a.id='$value' OR b.chapter_name='$value' and";
            }
            $sql.=" a.status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT a.id as value,b.chapter_name as text FROM `upcoming_classes` a "
                    . " left outer join chapter b on b.id = a.chapter_id "
                    . "  where 1 and $sql ")->result_array();
            
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'course_chapterwise_id' || $selectId == 'course_chapterwise_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" c.name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" c.id='$value' OR c.name='$value' and";
            }
            $sql.=" c.status!='2' and";
            
            $sql = rtrim($sql, "and");
            
           //  $currency = ("SELECT c.id as value,c.name as text FROM `couses` where 1 and $sql ");
               $currency = $this->db->query("SELECT c.id as value,c.name as text FROM `homework` a " 
                                 ."left outer join chapter b on b.id = a.chapter_id "
                               ."left outer join courses c on c.id=b.course_id where c.id is not null "   
                                 ."group by c.id   "
                             . "  where 1 and $sql ")->result_array();
            
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
    
       
        
        if ($selectId == 'badge_id' || $selectId == 'badge_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" title  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR title='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,title as text FROM `badges` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
          if ($selectId == 'user_id' || $selectId == 'user_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,username as text FROM `users` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'select_type' || $selectId == 'select_type') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `quiz_type` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
      
          if ($selectId == 'request_status' || $selectId == 'request_status') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
//                $sql.=" id='$value' OR  name='$value' and";
                $sql.="   name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `select_type` where 1 and $sql ")->result_array();
            
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
      
        
        if ($selectId == 'child_homework_id' || $selectId == 'child_homework_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" homework_id  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR homework_id='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,homework_id as text FROM `child_homework` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        if ($selectId == 'parent_id' || $selectId == 'parent_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and type = '2'";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,username as text FROM `users` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
         if ($selectId == 'child_id' || $selectId == 'child_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" student_id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and type = '1'";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT student_id as value,username as text FROM `users` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
   
             if ($selectId == 'state_id' || $selectId == 'state_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `state` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
         if ($selectId == 'lecture_days' || $selectId == 'lecture_days') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `course_days` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        
      if ($selectId == 'country_id' || $selectId == 'country') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `country` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        if ($selectId == 'child_id' || $selectId == 'child_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" username like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" student_id='$value' OR username='$value' and";
            }
            $sql.=" status!='2' and type = '1'";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT student_id as value,username as text FROM `users` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
       
        
        if ($selectId == 'course_chapterwise_id' || $selectId == 'course_chapterwise_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and ";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,student_id as text FROM `users` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
     
        
     
//        if ($selectId == 'subject' || $selectId == 'subject') {
//            $sql='';
//            if ($searchTerm != '') {
//                $sql.=" name like '%$searchTerm%' and";
//            }
//            if ($value != '') {
//                $sql.=" id='$value' OR name='$value' and";
//            }
//            $sql.=" status!='2' and";
//            
//            $sql = rtrim($sql, "and");
//            
////            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
//            $currency = $this->db->query("select c.id as value,b.chapter_name as text from `course_exercise` a 
//                    left outer join chapter b on a.chapter_id=b.id
//                    left outer join courses c on b.course_id=c.id
//                     group by b.id where 1 and $sql ")->result_array();
//            if ($value != '') {
//                $currency = $currency;
//            } else {
//                $currency;
//            }
//            $jsonResponse = json_encode($currency);
//            echo $jsonResponse;
//            exit;
//        }
//        
        
        if ($selectId == 'subject' || $selectId == 'subject') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `subject` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
         if ($selectId == 'chapter_id' || $selectId == 'chapter_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" chapter_name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR chapter_name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,CONCAT(chapter_no, ' ', chapter_no) as text FROM `chapter` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
//        
//          if ($selectId == 'chapter_id' || $selectId == 'chapter_id') {
//            $sql='';
//            if ($searchTerm != '') {
//                $sql.=" chapter_name like '%$searchTerm%' and";
//            }
//            if ($value != '') {
//                $sql.=" id='$value' OR chapter_name='$value' and";
//            }
//            $sql.=" status!='2' and";
//            
//            $sql = rtrim($sql, "and");
//            
////            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
//            $currency = $this->db->query("SELECT id as value,chapter_name as text FROM `chapter` where 1 and $sql ")->result_array();
//            if ($value != '') {
//                $currency = $currency;
//            } else {
//                $currency;
//            }
//            $jsonResponse = json_encode($currency);
//            echo $jsonResponse;
//            exit;
//        }
//       
        
          if ($selectId == 'billing_address_id' || $selectId == 'billing_address_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" full_name like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR full_name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,full_name as text FROM `billing_address` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
          if ($selectId == 'cart_id' || $selectId == 'cart_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" card_holder like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR card_holder='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,card_holder as text FROM `card` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
         if ($selectId == 'exercise_id' || $selectId == 'exercise_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" task like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR task='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,task as text FROM `course_exercise` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
          
        
        if ($selectId == 'exercise_id' || $selectId == 'exercise_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" task like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR task='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,task as text FROM `course_exercise` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
       
        
        if ($selectId == 'age_group_id' || $selectId == 'age_group_id') {
                $sql = '';
                if ($searchTerm != '') {
                    $sql .= " (min_age LIKE '%$searchTerm%' OR max_age LIKE '%$searchTerm%') AND";
                }
                if ($value != '') {
                    $sql .= " (id='$value' OR min_age='$value' OR max_age='$value') AND";
                }
                $sql .= " status != '2'";

                // Remove trailing "AND" if exists
                $sql = rtrim($sql, "AND");

                $currency = $this->db->query("SELECT id AS value, CONCAT(min_age, '-', max_age) as text from `age_group` where 1 and $sql ")->result_array(); 
                                             

                $jsonResponse = json_encode($currency);
                echo $jsonResponse;
                exit;
            }

        
         if ($selectId == 'upcoming_id' || $selectId == 'upcoming_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" task  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR task='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,task as text FROM `course_exercise` where 1 and $sql ")->result_array();
            
//            print_r($currency);
//            exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
                 if ($selectId == 'country' || $selectId == 'country') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `country` where 1 and $sql ")->result_array();
            
//            print_r($currency);
//            exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'state' || $selectId == 'state') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `state` where 1 and $sql ")->result_array();
            
//            print_r($currency);
//            exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'city' || $selectId == 'city') {
            $sql='';
            if ($searchTerm != '') {
                $sql.="name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `city` where 1 and $sql ")->result_array();
            
//            print_r($currency);
//            exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
          if ($selectId == 'course_id' || $selectId == 'course_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            if ($course_type_id != '' && $course_type_id != null) {
                $sql.=" course_type_id='$course_type_id' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
            $currency = $this->db->query("SELECT id as value,name as text FROM `courses` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'type' || $selectId == 'type') {
            $sql='';
            if ($searchTerm != '') {
                $sql.="name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `user_type` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'course' || $selectId == 'course') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `courses` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         if ($selectId == 'race' || $selectId == 'race') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `races` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
          if ($selectId == 'dialect' || $selectId == 'dialect') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `dialect` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        if ($selectId == 'religion' || $selectId == 'religion') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `religion` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        
           if ($selectId == 'nationlity' || $selectId == 'nationlity') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
           
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `nationlity` where 1 and $sql ")->result_array();
            
//           print_r($currency);
//           exit;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        
        
        if ($selectId == 'chapter' || $selectId == 'chapter') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" chapter_name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR chapter_name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,chapter_name as text FROM `chapter` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
        
        
        
        if ($selectId == 'course_type_id' || $selectId == 'course_type_id') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `course_type` where 1 and $sql ")->result_array();
            //echo $currency;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            
           
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        }
         
        if ($selectId == 'type_name' || $selectId == 'type_name') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `course_type` where 1 and $sql ")->result_array();
            //echo $currency;
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $value;
            echo $jsonResponse;
            exit;
        }
        
        if ($selectId == 'type' || $selectId == 'type') {
            $sql='';
            if ($searchTerm != '') {
                $sql.=" name  like '%$searchTerm%' and";
            }
            if ($value != '') {
                $sql.=" id='$value' OR name='$value' and";
            }
            $sql.=" status!='2' and";
            
            $sql = rtrim($sql, "and");
            
//            echo $currency = ("SELECT id as value,name as text FROM `categories` where 1 and $sql ");
            $currency = $this->db->query("SELECT id as value,name as text FROM `course_type` where 1 and $sql ")->result_array();
            if ($value != '') {
                $currency = $currency;
            } else {
                $currency;
            }
            $jsonResponse = json_encode($currency);
            echo $jsonResponse;
            exit;
        } 
//        echo json_encode($currency); 
    }

    public function check_style_exist() {
        $id = $_POST['id'];
        $currency = $this->db->query("SELECT count(1) as cnt FROM `styles` where style_id='$id'")->row_array();
        echo ($currency['cnt']);
    }

    public function get_price_wise_multiple() {
        $id = $_POST['id'];
        $currency = $this->db->query("SELECT * FROM `prices` where id='$id'")->row_array();
        echo ($currency['total_multiple']);
    }
    public function chaptereditdata () {
        $id = $_POST['chapterid'];
        $chapter_name = $_POST['chapter_name'];
        $chapter_no = $_POST['chapter_no'];
        $time = $_POST['time'];
        $currency = $this->db->query("update chapter set chapter_name='$chapter_name' where id='$id'")->result_array();
        echo 'total_multiple';
    }

    public function load_usermangement_function() {
        $id = $_POST['id'];

        $select_access = $this->db->query("select * from user_access where user_id='$id'")->result_array();
        $select_access2 = $this->db->query("select * from table_column_access where user_id='$id'")->result_array();
        $data['post'] = array_merge(array("user_access" => $select_access), array("column_access" => $select_access2));

//       print_r($data['post']);
        echo $message2 = $this->load->view('extra/usermanagement', $data, true);
    }
    // PHP


public function updateCourseExercise() {
    // Get form data
    $course_id = $this->input->post('course_id');
    $chapterId = $this->input->post('chapter_id');
    $exerciseNo = $this->input->post('exercise_no');
    $chapter_name = $this->input->post('chapter_name');
    $task = $this->input->post('task');
    $time = $this->input->post('time');
    $date = date("Y-m-d H:i:s");

    // Check if chapter_id is not empty for updating existing record
    if ($chapterId !== null && $chapterId !== '' && $chapterId !== 0) {
        // Update data in database
        $this->db->set('exercise_no', $exerciseNo);
        $this->db->set('chapter_name', $chapter_name);
        $this->db->set('time', $time);
        $this->db->set('updated_date', $date);
        $this->db->where('id', $chapterId); 
        $this->db->update('course_exercise');
        if ($this->db->affected_rows() > 0) {
            echo "Record updated successfully";
        } else {
            echo "Error updating record: " . $this->db->error();
        }
    } else {
        // Insert new record
        // Check if exerciseNo is empty for generating a new exercise number
        if($exerciseNo == '' || $exerciseNo === null) {
            $exerciseNo = $this->db->query("SELECT COALESCE(MAX(exercise_no), 0) + 1 AS srno FROM course_exercise WHERE status != 2 AND course_id = $course_id")->row_array();
            $exerciseNo = $exerciseNo['srno'];
        }
        
        $data = array(
            'exercise_no' => $exerciseNo,
            'chapter_name' => ucfirst($chapter_name),
            'course_id' => $course_id,
            'task' => $task,
            'time' => $time,
            'added_date' => $date
        );

        $this->db->insert('course_exercise', $data);
        if ($this->db->affected_rows() > 0) {
            echo "New record inserted successfully";
        } else {
            echo "Error inserting record: " . $this->db->error();
        }
    }
}
//send tutorial chat (Firestor)
public function updateTutorialStatus(){
  $tutorial_id = $_POST['tutorial_id'];
  $this->db->query("update tutorial set is_active=2 where id= '$tutorial_id'"); 
  echo json_encode(array('success' => true, 'message' => 'Repair Mark Completed Successfully!'));
}
public function sendTutorialMessages(){
    // $this->load->library('session');
    $id=$_POST['id'];
    $message=$_POST['message'];
    //$login_id =  $this->session->userdata('id');
    $this->load->library('Firebase');
    $firebase = new Firebase();
    $filename = [];
      if(isset($_FILES['file-0'])) {
       $uploaded = $firebase->uploadFileToStorage($_FILES);
          if (count($uploaded) > 0) {
              $filename =  $uploaded;
          } else {
              $filename[] = "Error, attachment couldn't be uploaded";
          } 
          
      }   
     
    $result = $this->db->query("select * from  tutorial where id=$id")->row_array();
    $user_id = $result['user_id'];
    $result = $this->db->query("select * from users where is_admin=1")->row_array();
    $this->db->query("update tutorial set is_active=1 where id= '$id'"); 
    $admin_logo = $result['image'] ? $result['image'] : base_url().'theme/assets/images/svg/user_circle_icon.png';
    // date_default_timezone_set("asia/Kuala_Lumpur");
    //date_default_timezone_set('Asia/Kolkata');
    $firebase->sendMessage( array
    (
        'tutorial_id' => $id,
        'senderId' => 0,
        'sender_username' => '',
        'sender_profile_pic' => '',
        'recipientId' => $result['id'],
        'recipient_username' => $result['username'],
        'recipient_profile_pic' => $admin_logo,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s'),
        'status' => 0,
        'read' => 0,
        'likes' => 0,
        'filename' => $filename
    ));
    $this->fetchTutorial($id);
}

//get new chat replies 
public function fetchTutorial($id=null){
    if(!$id){
        $id = $_POST['id'];
    }
    $supported_image = array(
        'gif',
        'jpg',
        'jpeg',
        'png',
        'svg'
    );
    $result = $this->db->query("select * from  tutorial where id=$id")->row_array();
    $user_id = $result['user_id'];
    // $login_id = $this->session->userdata('id');
    $admin_data = $this->db->query("select * from  users where is_admin=1")->row_array();
    $user_data = $this->db->query("select * from  users where id=$user_id")->row_array();
    $admin_logo = $admin_data['image'] ? $admin_data['image'] : base_url().'theme/assets/images/svg/user_circle_icon.png';
    $user_logo = $user_data['image'] ? $user_data['image'] : base_url().'theme/assets/images/svg/admin-user-circle-icon.svg';
    $user_name = $admin_data['username'];
    $this->load->library('Firebase');
    $firebase = new Firebase();
    $messages = $firebase->getMessages($id);
    $data = "";
    foreach($messages as $list){ 
      if($list['senderId'] == $user_id){
      $data .= '<div class="received-chats">
       <div class="received-chats-img">';
       if($list['filename'] != '' || $list['message'] != ''){
        $data .= '<img src='.$user_logo.' alt="" class="rounded-circle avatar-xxs" />';
       }
        $data .= '</div>
       <div class="received-msg">
         <div class="received-msg-inbox">';
            if($list['message'] != ''){
                $data .= '<p> '.$list['message'].'</p>';
            }
            if(count($list['filename']) > 0){
                foreach($list['filename'] as $list_file){
                    $src_file_name = $list_file;
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                    if (in_array($ext, $supported_image)) { 
                        $data .=  '<br> <span><img src='.$list_file.' style="width: 50%;border-radius: 0;"></span>';
                    }else{
                        $data .= '<br><span><a href='.$list_file.' target="_blank" download>'.$list_file.'</a></span>'; 
                    }
                    
                }
               
            }
            if(count($list['filename']) > 0 || $list['message'] != ''){
                $data .= '<span class="time"> '.$list['timestamp'].'</span>';
            }
            $data .='</div>
       </div>
     </div>';
     }else{ 
      $data .= '<div class="outgoing-chats">
       <div class="outgoing-chats-img">';
       if($list['filename'] != '' || $list['message'] != ''){
        $data .= '<img src='.$admin_logo.' alt="" class="rounded-circle avatar-xxs" />';
       }
        $data .= '</div>
       <div class="outgoing-msg">
         <div class="outgoing-chats-msg">';
            if($list['message'] != ''){
                $data .= '<p> '.$list['message'].'</p>';
            }
            if(count($list['filename']) > 0){
                foreach($list['filename'] as $list_file){
                    $src_file_name = $list_file;
                    $ext = strtolower(pathinfo($src_file_name, PATHINFO_EXTENSION)); // Using strtolower to overcome case sensitive
                    if (in_array($ext, $supported_image)) { 
                        $data .=  '<br> <span><img src="'.$list_file.'" style="width: 50%;border-radius: 0;"></span>';
                    }else{
                        $data .= '<br><span><a href="'.$list_file.'" download>'.$list_file.'</a></span>';
                    }
                } 
            }
            if(count($list['filename']) > 0 || $list['message'] != ''){
                $data .= '<span class="time"> '.$list['timestamp'].'</span>';
            }
            $data .= '</div>
       </div>
     </div>';
    } 
  }

  $dom = new DOMDocument();
  $dom->loadHTML($data);
  $tags_to_remove = array('script');
  foreach($tags_to_remove as $tag){
      $element = $dom->getElementsByTagName($tag);
      foreach($element  as $item){
          $item->parentNode->removeChild($item);
      }
  }
  $html = $dom->saveHTML();
    echo $html;
    exit;
}



public function deleteCorse_exercise() {
    // Retrieve chapter id from POST request
    $chapter_id = $this->input->post('chapter_id');

    // Perform validation if needed

    // Delete the chapter record
    $this->db->where('id', $chapter_id);
    $result = $this->db->delete('chapters');

    // Check the result and send response
    if($result) {
        echo "Chapter deleted successfully";
    } else {
        echo "Failed to delete chapter";
    }
}

private function calculateCourseDuration($course_id) {
    $masterClasses = $this->getCourseAllMasterClasses($course_id);
    if(count($masterClasses) === 0) {
        return '0h';
    }

    $course_duration = 0;
    for ($i = 0; $i < count($masterClasses); $i++) { 
        $time = explode(" ",$masterClasses[$i]['class_duration'])[0];
        $course_duration = $course_duration + $time;
    }
    $total_minutes = $course_duration % 60;        
    $total_hour = ($course_duration - $total_minutes) / 60;

    $final_course_duration  = ($total_minutes === 0) ? $total_hour."h" : $total_hour."h ".$total_minutes."min";
    return $final_course_duration;
}

private function getCourseAllTimeSlotAndDays($course_id) {
    $masterClasses = $this->getCourseAllMasterClasses($course_id);
    if(count($masterClasses) === 0) {
        return array('timeSlots' => [],'days' => []);        
    }

    $masterClassesAllIds = '';
    for ($i = 0; $i < count($masterClasses); $i++) {
        $masterClassId =  $masterClasses[$i]['id'];
        $masterClassesAllIds = $masterClassesAllIds.$masterClassId.',';
    }
    $masterClassesAllIds =  rtrim($masterClassesAllIds,",");

    $classes = $this->db->query("select * from upcoming_classes where master_class_id in($masterClassesAllIds) and status != '2' and class_status != 'Cancel';")->result_array();
    if(count($classes) === 0) {
       return array('timeSlots' => [],'days' => []);        
    }
    
    $timeSlots = [];
    $days = [];
    for ($i = 0; $i < count($classes); $i++) { 
        $classDateTime = $classes[$i]['upcoming_date'];
        $start = $classes[$i]['start'];
        $end = $classes[$i]['end'];
        $newTimeSlot = date('g:iA', strtotime($start)).'-'.date('g:iA', strtotime($end));
        $classDay = date('l', strtotime($classDateTime));
        array_push($timeSlots, $newTimeSlot);
        array_push($days, $classDay);
    }

    return array('timeSlots' => $timeSlots, 'days' => array_values(array_unique($days)));
}

private function getCourseAllMasterClasses($course_id) {
    return $this->db->query("select a.*,b.id as chapter_id,b.chapter_name,c.id as course_id,c.name as course_name 
    from master_classes a
    left outer join chapter b on b.id = a.chapter_id
    left outer join courses c on c.id = b.course_id
    where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2' order by created_at desc")->result_array();
}

private function getCourseAllLeave($course_id) {
    return $this->db->query("select a.*,b.name as course_name,d.full_name as child_name from course_leave a 
    left outer join courses b on a.course_id = b.id 
    left outer join users d on d.id = a.child_id
    where a.status!='2' and b.status != '2' and d.status != '2' and b.id = '$course_id' order by a.added_date desc")->result_array();
}

private function getEnrolledStudentInCourse($course_id) {
    return $this->db->query("select a.added_date,a.updated_date,c.full_name from mycart a
    left outer join courses b on b.id = a.course_id
    left outer join users c on c.id = a.child_id where b.id = '$course_id' and a.is_paid = '2' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
}

private function generateChapterNumber($course_id) {
    $chapters = $this->db->query("select * from chapter where course_id = $course_id and status != '2' order by added_date desc")->result_array();
    if(count($chapters) === 0) {
        return '1';
    }

    $lastChapterNumber = $chapters[0]['chapter_no'];
    $nextChapterNumber = (int) $lastChapterNumber + 1;
    return $nextChapterNumber;
}

    public function updateQuizSubmit() {
        $quizId = (int) $this->input->post('quizId');
        $mainQuizId = (int) $this->input->post('mainQuizId');
        $quizName = $this->input->post('quizName');
        $quizTypeId = $this->input->post('quizTypeId');
        $marks = $this->input->post('marks');
        $date = date("Y-m-d H:i:s");

        if ($quizId > 0) {
            $QuizAll = $this->db->query("select a.id,a.name,a.marks,a.added_date,a.updated_date,b.id as quizTypeId,b.name as quizTypeName from quiz a 
            left outer join quiz_type b on b.id = a.select_type where a.main_quiz_id = '$mainQuizId' and a.status != '2' and b.status != '2'")->result_array(); 
            $toTalMarks = 0 ;
            foreach ($QuizAll as $results) { 
                $toTalMarks += $results['marks']; 
            }
            $quiz = $this->db->query("select * from quiz where id='$quizId' and status != '2'")->row_array();
            if ((int)$toTalMarks >= 100 ){
                if((int)$quiz['marks'] > (int)$marks){
                    $this->db->set('marks', $marks);
                }
            }else{
                $this->db->set('marks', $marks);
            }
            $this->db->set('main_quiz_id', $mainQuizId);
            $this->db->set('name', $quizName);
            $this->db->set('select_type', $quizTypeId);
           
            $this->db->set('updated_date', $date);
            $this->db->where('id', $quizId); 
            $this->db->update('quiz');

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {
            $data = array(
                'main_quiz_id' => $mainQuizId,
                'name' => ucfirst($quizName),
                'select_type' => $quizTypeId,
                'marks' => $marks,
                'added_date' => $date,
                'updated_date' => $date
            );
            $this->db->insert('quiz', $data);

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error inserting record');
            }
        }

        echo json_encode($response);
        
    }

    public function updateQuizActiveStatus(){
        $quizId = (int) $this->input->post('quizId');
        $activeStatus = (int) $this->input->post('activeStatus');
        $this->db->set('active_status', $activeStatus);
        $this->db->where('id', $quizId); 
        $this->db->update('main_quiz');
        if($activeStatus ==1){
            //date_default_timezone_set('Asia/Kolkata');
            $date = date("Y-m-d H:i:s");
            $data =  $this->db->query("select * from main_quiz where id = '$quizId' and status != '2'")->row_array();
            $data3=array();
            $data3['type'] = 0;
            $data3['main_id'] = $data['course_id'];
            $data3['user_id'] = 0;
            $data3['notifier_id'] = 0;
            $data3['sender_id'] = 0;
            $data3['receiver_id'] = 0;
            $data3['notification_type'] = 'quiz_created';
            $data3['added_date'] = $date;
            $data3['updated_date'] = $date;
            $data3['message'] = $data['name'];   
            $data3['is_read'] = 0;
            $data3['is_all'] = 1;
            $data3['status'] = 0;
            $this->db->insert('notification', $data3);
            $course_id = $data['course_id'];
            $course = $this->db->query("select name from courses where id = '$course_id' and status != '2'")->row_array();
            $this->load->library('Firebase');
            $firebase = new Firebase();
            $data['topic'] = 'event_announcement';
            $data['notificationFor'] = 'newQuiz';
            $data['title'] = 'Upcoming new quiz '.$data['name'];
            $data['body'] = $course['name'].' quiz arriving soon';
            $data['main_quiz_id'] = $quizId;
            $data['type'] = 1;
          
            try { 
                $response =  $firebase->sendNotification($data);
            }catch (Exception $e) {
                //alert the user then kill the process
            
                // $final = array();
                // $final['status'] = false;
                // $final['message'] = $e->getMessage();
                // $this->response($final, REST_Controller::HTTP_OK);
            }
        }
        $response = array('success' => true, 'message' => 'Record updated successfully');
    }

    public function updateGallerySubmit() {    
        $galleryId = (int) $this->input->post('galleryId');
        $folderId = (int) $this->input->post('folderId');
        $courseId = (int) $this->input->post('courseId');
        $date = date("Y-m-d H:i:s");

        if ($galleryId > 0) {
            $galleryFilePath = null;
            if(isset($_FILES["galleryFile"])) {
                $total_count = count($_FILES['galleryFile']['name']);
                $targetDir = "uploads/course_gallery";
                for ($i=0; $i < $total_count; $i++) { 
                    if (!empty($_FILES["galleryFile"]['name'][$i])) {   
                        $uniqueFolderName = $last_insert_id;
                        $uploadDir = $targetDir . $uniqueFolderName . "/";
                        mkdir($uploadDir);
                        $tmpFilePath = $_FILES["galleryFile"]["tmp_name"][$i];
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $_FILES["galleryFile"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $galleryFilePath = $galleryFilePath.base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }
            }
    
            $this->db->set('gallery_folder_id', $folderId);
            if($galleryFilePath !== null) {
                $galleryFilePath = rtrim($galleryFilePath, ',');
                $this->db->set('gallery', $galleryFilePath);
            }
            $this->db->set('updated_at', $date);
            $this->db->where('id', $galleryId); 
            $this->db->update('course_gallery');
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {
            $galleryFilePath = null;
            if(isset($_FILES["galleryFile"])) {
                $total_count = count($_FILES['galleryFile']['name']);
                $targetDir = "uploads/course_gallery";
                for ($i=0; $i < $total_count; $i++) { 
                    if (!empty($_FILES["galleryFile"]['name'][$i])) {   
                        $uniqueFolderName = $last_insert_id;
                        $uploadDir = $targetDir . $uniqueFolderName . "/";
                        mkdir($uploadDir);
                        $tmpFilePath = $_FILES["galleryFile"]["tmp_name"][$i];
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $_FILES["galleryFile"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $galleryFilePath = $galleryFilePath.base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }
            }
    
            if($galleryFilePath !== null) $galleryFilePath = rtrim($galleryFilePath, ',');

            $data = array(
                'gallery_folder_id' => $folderId,
                'gallery' => $galleryFilePath,
                'created_at' => $date,
                'updated_at' => $date
            );
            $this->db->insert('course_gallery', $data);
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error inserting record');
            }
        }
    
        echo json_encode($response);
    }

    public function updateFolderSubmit() {    
        $folderId = (int) $this->input->post('folderId');
        $courseId = (int) $this->input->post('courseId');
        $folderName =  $this->input->post('folderName');
        //date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");

        if ($folderId > 0) {
            $folderPath = null;
            if (isset($_FILES['folderFile']) && !empty($_FILES['folderFile']['name'])) {   
                $targetDir = "uploads/course_gallery";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $tmpFilePath = $_FILES['folderFile']["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = '';
                    $fileName = $_FILES['folderFile']["name"];
                    $filePath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $folderPath .= base_url() . '' . $filePath ;
                    } else{
                        echo "failed file upload";
                    }
                }   
            }
    
            $this->db->set('course_id', $courseId);
            $this->db->set('folder_name', $folderName);
            if($folderPath !== null) {
                $this->db->set('folder_image', $folderPath);
            }
            $this->db->set('updated_at', $date);
            $this->db->where('id', $folderId); 
            $this->db->update('course_gallery_folders');
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {
            $folderPath = null;
            if (isset($_FILES['folderFile']) && !empty($_FILES['folderFile']['name'])) {   
                $targetDir = "uploads/course_gallery";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $tmpFilePath = $_FILES['folderFile']["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = '';
                    $fileName = $_FILES['folderFile']["name"];
                    $filePath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $folderPath .= base_url() . '' . $filePath ;
                    } else{
                        echo "failed file upload";
                    }
                }   
            }
    
            $data = array(
                'course_id' => $courseId,
                'folder_name' => ucfirst($folderName),
                'folder_image' => $folderPath,
                'created_at' => $date,
                'updated_at' => $date
            );
            $this->db->insert('course_gallery_folders', $data);
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error inserting record');
            }
        }
    
        echo json_encode($response);
    }
    
    public function updateStudentQuizAnswerSubmit() {
        $quizAnswerId = (int) $this->input->post('quizAnswerId');
        $quizAnswer =  $this->input->post('quizAnswer');
        $isChecked =  $this->input->post('isChecked');
        $isSkipped = $this->input->post('isSkipped');
        $isWrong = $this->input->post('isWrong');
        $isCorrect = $this->input->post('isCorrect');
        $isPending = $this->input->post('isPending');
        $marks = $this->input->post('marks');
        $date = date("Y-m-d H:i:s");

        $this->db->set('answer', $quizAnswer);
        $this->db->set('is_checked', $isChecked);
        $this->db->set('is_skipped', $isSkipped);
        $this->db->set('is_wrong', $isWrong);
        $this->db->set('is_correct', $isCorrect);
        $this->db->set('is_pending', $isPending);
        $this->db->set('marks', $marks);        
        $this->db->set('updated_date', $date);
        $this->db->where('id', $quizAnswerId); 
        $this->db->update('child_quiz_answer');

        if ($this->db->affected_rows() > 0) {
            $response = array('success' => true, 'message' => 'Record updated successfully');
        } else {
            $response = array('status' => 'error', 'message' => 'Error updating record');
        }

        echo json_encode($response);        
    }

    public function updateStudentQuizResultSubmit() {
        $mainQuizId = (int) $this->input->post('mainQuizId');
        $resultId = (int) $this->input->post('resultId');
        $userId = (int) $this->input->post('userId');
        $badgeId = $this->input->post('badgeId');
        $correctAns = $this->input->post('correctAns');
        $skippedAns = $this->input->post('skippedAns');
        $wrongAns = $this->input->post('wrongAns');
        $completedQuestion = $this->input->post('completedQuestion');
        $quizDuration = $this->input->post('quizDuration');
        $quizScore = $this->input->post('quizScore');
        $date = date("Y-m-d H:i:s");

        if ($resultId > 0) {
            $this->db->set('main_quiz_id', $mainQuizId);
            $this->db->set('user_id', $userId);
            $this->db->set('badge_id', $badgeId);
            $this->db->set('duration', $quizDuration);
            $this->db->set('score', $quizScore);
            $this->db->set('correct_ans', $correctAns);
            $this->db->set('skipped_ans', $skippedAns);
            $this->db->set('wrong_ans', $wrongAns);
            $this->db->set('completion_ques', $completedQuestion);
            $this->db->set('updated_date', $date);
            $this->db->where('id', $resultId); 
            $this->db->update('child_quiz_result');
            
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                  echo '<div id="success">Could not updated"!</div>'; 
                  exit;
            }
        } else {

            $quizSubmittedResult = $this->db->query("select * from child_quiz_result where main_quiz_id='$mainQuizId' and user_id='$userId' and status != '2'")->result_array();	
            if(count($quizSubmittedResult) > 0 ){
                echo '<div id="success">Result Already Submitted"!</div>'; 
                exit;
            }else{
                $data = array(
                    'user_id' => $userId,
                    'badge_id' => $badgeId,
                    'main_quiz_id' => $mainQuizId,
                    'duration' => $quizDuration,
                    'score' => $quizScore,
                    'correct_ans' => $correctAns,
                    'skipped_ans' => $skippedAns,
                    'wrong_ans' => $wrongAns,
                    'completion_ques' => $completedQuestion,
                    'added_date' => $date,
                    'updated_date' => $date
                );
                $this->db->insert('child_quiz_result', $data);
               // date_default_timezone_set('Asia/Kolkata');
                $currentDateTime = date('Y-m-d H:i:s');
                $this->db->query("INSERT INTO notification (main_id,sender_id,receiver_id,notification_type,added_date,updated_date) VALUES ('$mainQuizId','1','$userId','quiz','$currentDateTime','$currentDateTime')");

                $query_result = $this->db->query("select id,firebase_token,student_id from users where id = '$userId' and status != '2'")->row_array();
                $main_quiz = $this->db->query("select name from main_quiz where id = '$mainQuizId' and status != '2'")->row_array();
            
                if(count($main_quiz)> 0){
                    $name = $main_quiz['name'];
                    $course_id =  $main_quiz['course_id'];
                    $course = $this->db->query("select name from courses where id = '$course_id' and status != '2'")->row_array();
                    $courseName = $course['name'];
                }else{
                    $name = '';
                    $courseName = '';
                }
                if(!empty($query_result) && $query_result['firebase_token']) {
                    $query_result['topic'] = 'quiz';
                    $query_result['notificationFor'] = 'quiz';
                    $query_result['title'] = 'Results are out for '.$name;
                    $query_result['body'] =  'There is a new announcement';
                    $query_result['main_quiz_id'] = $mainQuizId;
                    $query_result['type'] = 2;
                    $this->load->library('Firebase');
                    $firebase = new Firebase();
                   
                    try { 
                        $response =  $firebase->sendNotification($query_result);
                    }catch (Exception $e) {
                        //alert the user then kill the process
                    
                        // $final = array();
                        // $final['status'] = false;
                        // $final['message'] = $e->getMessage();
                        // $this->response($final, REST_Controller::HTTP_OK);
                    }

                    
                }

               // if ($this->db->affected_rows() > 0) {
                    $response = array('success' => true, 'message' => 'Record inserted successfully');
                // } else {
                //     echo '<div id="success">Error inserting record"!</div>'; 
                //     exit;
                // }
            }
        }
        echo json_encode($response);        
    }

    public function updateQuizOptionSubmit() {
        $quizOptionId = (int) $this->input->post('quizOptionId');
        $quizId = (int) $this->input->post('quizId');
        $quizTypeName = $this->input->post('quizTypeName');

        $optionName = $this->input->post('optionName');
        $optionPair = $this->input->post('optionPair');
        // $optionAnswer = $this->input->post('optionAnswer');

        $option1 = $this->input->post('option1');
        $option2 = $this->input->post('option2');
        $option3 = $this->input->post('option3');
        $option4 = $this->input->post('option4');
        $date = date('Y-m-d H:i:s') ;
       
        if ($quizOptionId > 0) {  
            if($quizTypeName === 'pairs') { 
                $optionNameArr = explode(',', $optionName);
                $optionPairArr = explode(',', $optionPair);
                $optionAnswer = array_combine($optionNameArr,$optionPairArr);
                shuffle($optionPairArr );
                $j = 0;
                $this->db->where('quiz_id', $quizId);
                $this->db->delete('quiz_options');
              
                foreach($optionNameArr as $list){
                    $data = array(
                        'quiz_id' => $quizId,
                        'name' => ucfirst($list),
                        'pair' => ucfirst($optionPairArr[$j]),
                        'ans_name' => serialize($optionAnswer),
                        'added_date' => $date,
                        'updated_date' => $date
                    );
                    $this->db->insert('quiz_options', $data);
                    $j++;
                }
               
            }else{
                $optionAnswer = $this->input->post('optionAnswer');
                $allQuizOptions = $this->db->query("select * from quiz_options where quiz_id = '$quizId' and status != '2'")->result_array();
                for ($i = 0; $i < count($allQuizOptions); $i++) { 
                    $qoId = $allQuizOptions[$i]['id'];

                    $this->db->set('quiz_id', $quizId);   
                    $this->db->set('ans_name', $optionAnswer);
                    $this->db->set('updated_date', $date);
                    
                    if($i === 0) $this->db->set('name', $option1);
                    if($i === 1) $this->db->set('name', $option2);
                    if($i === 2) $this->db->set('name', $option3);
                    if($i === 3) $this->db->set('name', $option4);
                    
                    $this->db->where('id', $qoId); 
                    $this->db->update('quiz_options');
                }
                
            }

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {
            if($quizTypeName === 'pairs') { 
                $optionNameArr = explode(',', $optionName);
                $optionPairArr = explode(',', $optionPair);
                $optionAnswer = array_combine($optionNameArr,$optionPairArr);
                shuffle($optionPairArr );
                $i = 0;
                foreach($optionNameArr as $list){
                    
                    $data = array(
                        'quiz_id' => $quizId,
                        'name' => ucfirst($list),
                        'pair' => ucfirst($optionPairArr[$i]),
                        'ans_name' => serialize($optionAnswer),
                        'added_date' => $date,
                        'updated_date' => $date
                    );
                    $this->db->insert('quiz_options', $data);
                    $i++;
                }
               
            }else{
                $optionAnswer = $this->input->post('optionAnswer');
                $allQuizOptions = $this->db->query("select * from quiz_options where quiz_id = '$quizId' and status != '2'")->result_array();
                if(count($allQuizOptions) > 0) {
                    for ($i = 0; $i < count($allQuizOptions); $i++) { 
                        $qoId = $allQuizOptions[$i]['id'];

                        $this->db->set('quiz_id', $quizId);   
                        $this->db->set('ans_name', $optionAnswer);
                        $this->db->set('updated_date', $date);
                        
                        if($i === 0) $this->db->set('name', $option1);
                        if($i === 1) $this->db->set('name', $option2);
                        if($i === 2) $this->db->set('name', $option3);
                        if($i === 3) $this->db->set('name', $option4);
                        
                        $this->db->where('id', $qoId); 
                        $this->db->update('quiz_options');
                    }
                }else{
                    for ($i = 0; $i < 4; $i++) { 
                        $data = array(
                            'quiz_id' => $quizId,
                            'pair' => '',
                            'ans_name' => $optionAnswer,
                            'added_date' => $date,
                            'updated_date' => $date
                        );
                        if($i === 0) $data = array_merge($data, array('name' => $option1));
                        if($i === 1) $data = array_merge($data, array('name' => $option2));
                        if($i === 2) $data = array_merge($data, array('name' => $option3));
                        if($i === 3) $data = array_merge($data, array('name' => $option4));
                        $this->db->insert('quiz_options', $data);
                    }
                }
            }
            

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $error = $this->db->error();
                $response = array('status' => $error['message'], 'message' => 'Error inserting record');
            }
        }

        echo json_encode($response);  
    }
    
    public function updateCertificateSubmit() {
        // Sanitize form data
        $certificateId = (int) $this->input->post('certificateId');
        $userId = (int) $this->input->post('userId');
        $courseId = (int) $this->input->post('courseId');   
        $date = date("Y-m-d H:i:s");
    
        if ($certificateId > 0) {
            $certificatePath = null;
            if(!empty($_FILES["certificateFile"]["name"])) {
                $target_dir = "uploads/certificate/";
                if(!is_dir($target_dir)){
                    mkdir($target_dir , 0777);
                }
                $target_file = $target_dir . rand()."-".$_FILES["certificateFile"]["name"];
                if (move_uploaded_file($_FILES["certificateFile"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["certificateFile"]["name"])). " has been uploaded.";
                    $certificatePath = base_url().$target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            } 
    
            $this->db->set('user_id', $userId);
            $this->db->set('course_id', $courseId);
            if($certificatePath != null) {
                $this->db->set('certificate', $certificatePath);
            }
            
            $this->db->set('updated_at', $date);
            $this->db->where('id', $certificateId); 
            $this->db->update('course_certificate');
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {    
            $certificatePath = null;
            if(!empty($_FILES["certificateFile"]["name"])) {
                $target_dir = "uploads/certificate/";
                if(!is_dir($target_dir)){
                    mkdir($target_dir , 0777);
                }
                $target_file = $target_dir . rand()."-".$_FILES["certificateFile"]["name"];
                if (move_uploaded_file($_FILES["certificateFile"]["tmp_name"], $target_file)) {
                    echo "The file ". htmlspecialchars( basename( $_FILES["certificateFile"]["name"])). " has been uploaded.";
                    $certificatePath = base_url().$target_file;
                } else {
                    echo "Sorry, there was an error uploading your file.";
                }
            }  
    
            $data = array(
                'user_id' => $userId,
                'course_id' => $courseId,
                'certificate' => $certificatePath,
                'created_at' => $date,
                'updated_at' => $date
            );
            $this->db->insert('course_certificate', $data);
    
            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error inserting record');
            }
        }
    
        echo json_encode($response);
    }

    public function updateHomeworkSubmit() {
     
        $homeworkId = (int) $this->input->post('homeworkId');
        $courseId = (int) $this->input->post('courseId');
        $exerciseId = $this->input->post('exerciseId');
        $hTitle = $this->input->post('hTitle');
        $hkDate = $this->getHighestExerciseDateline($exerciseId);
        $date = date("Y-m-d H:i:s");

        if ($homeworkId > 0) {
            $homeworkFilePath = null;
            if(isset($_FILES["hMaterial"])) {
                $total_count = count($_FILES['hMaterial']['name']);
                $targetDir = "uploads/homeworkdocimage";
                for ($i=0; $i < $total_count; $i++) { 
                    if (!empty($_FILES["hMaterial"]['name'][$i])) {   
                        $uniqueFolderName = $last_insert_id;
                        $uploadDir = $targetDir . $uniqueFolderName . "/";
                        mkdir($uploadDir);
                        $tmpFilePath = $_FILES["hMaterial"]["tmp_name"][$i];
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $_FILES["hMaterial"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $homeworkFilePath = $homeworkFilePath.base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }
            }

            $this->db->set('course_id', $courseId);
            $this->db->set('exercise_id', $exerciseId);
            $this->db->set('homework_title', $hTitle);
            if($homeworkFilePath !== null) {
                $homeworkFilePath = rtrim($homeworkFilePath, ',');
                $this->db->set('homework_material', $homeworkFilePath);
            }
            $this->db->set('hk_date', $hkDate);                
            $this->db->set('updated_date', $date);
            $this->db->where('id', $homeworkId); 
            $this->db->update('homework');          

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record updated successfully');
            } else {
                $response = array('status' => 'error', 'message' => 'Error updating record');
            }
        } else {            
            $homeworkFilePath = null;
            if(isset($_FILES["hMaterial"])) {
                $total_count = count($_FILES['hMaterial']['name']);
                $targetDir = "uploads/homeworkdocimage";
                for ($i=0; $i < $total_count; $i++) { 
                    if (!empty($_FILES["hMaterial"]['name'][$i])) {   
                        $uniqueFolderName = $last_insert_id;
                        $uploadDir = $targetDir . $uniqueFolderName . "/";
                        mkdir($uploadDir);
                        $tmpFilePath = $_FILES["hMaterial"]["tmp_name"][$i];
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $_FILES["hMaterial"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $homeworkFilePath = $homeworkFilePath.base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }
            }

            if($homeworkFilePath !== null) $homeworkFilePath = rtrim($homeworkFilePath, ',');
        
            $data = array(
                'course_id' => $courseId,
                'exercise_id' => $exerciseId,
                'homework_title' => ucfirst($hTitle),
                'homework_material' => $homeworkFilePath,
                'hk_date' => $hkDate,
                'added_date' => $date,
                'updated_date' => $date
            );

            $this->db->insert('homework', $data);

            if ($this->db->affected_rows() > 0) {
                $response = array('success' => true, 'message' => 'Record inserted successfully');
            } else {
                $error = $this->db->error();
                $response = array('status' => $error['message'], 'message' => 'Error inserting record');
            }
        }

        echo json_encode($response);  
    }

    private function getHighestExerciseDateline($exerciseIds) {
        $exercises = $this->db->query("SELECT submit_date FROM course_exercise WHERE id IN($exerciseIds) and status != '2'")->result_array();
        $dates = array();
        for ($i=0; $i < count($exercises); $i++) { 
            array_push($dates, $exercises[$i]['submit_date']);
        }

        $dateTimes = array_map(function($date) {
            return new DateTime($date);
        }, $dates);

        $largestDate = max($dateTimes);
        $largestDateString = $largestDate->format('Y-m-d');

        return $largestDateString;
    }
}
?>

<script>
    var dataTable = $('#ajax_datatables2').DataTable();
    dataTable.draw();
    var dataTable = $('#ajax_datatables2_master').DataTable();
    dataTable.draw();   
    var dataTable = $('#ajax_datatables2_homework').DataTable();
    dataTable.draw(); 
    var dataTable = $('#ajax_datatables2_courseLeave').DataTable();
    dataTable.draw(); 
    var dataTable = $('#ajax_datatables2_enrolledStudent').DataTable();
    dataTable.draw(); 
    var dataTable = $('#ajax_datatables2_courseCertificate').DataTable();
    dataTable.draw();       
    
    var dataTable = $('#ajax_datatables2_quizOption').DataTable();
    dataTable.draw();   
    var dataTable = $('#ajax_datatables2_quizAnswer').DataTable();
    dataTable.draw();   
    var dataTable = $('#ajax_datatables2_quizResult').DataTable();
    dataTable.draw(); 

    var dataTable = $('#ajax_datatables2_gallery').DataTable();
    dataTable.draw(); 

    // var dataTable = $('#ajax_datatables_attendance').DataTable({sDom: 'lrtip'});
    // dataTable.draw(); 
    
    var dataTable = $('#ajax_datatables3').DataTable();
    dataTable.draw();
    var dataTable = $('#ajax_datatables4').DataTable();
    dataTable.draw();
    var dataTable = $('#ajax_datatables5').DataTable();
    dataTable.draw();
 
function course_exercise(course_id, chapter_id, exercise_no, task, chapter_name, time, operationType) {
    $('#course_exercise').modal('show');
    $('#modalTitle').text(operationType === 'add' ? 'Add Exercise' : 'Update Exercise');
    $('#operationType').val(operationType);
    $('#course_id').val(course_id);
    $('#chapter_id').val(chapter_id);
    $('#exercise_no').val(exercise_no);
    $('#task').val(task);
    $('#chapter_name').val(chapter_name);
    $('#time').val(time); 
}



function updateCourseExercise() {
    var operationType = $('#operationType').val();
    var course_id = $('#course_id').val();
    var chapter_id = $('#chapter_id').val();
    var exercise_no = $('#exercise_no').val();
    var chapter_name = $('#chapter_name').val();
    var task = $('#task').val();
    var time = $('#time').val();
    var date = new Date().toISOString().slice(0, 19).replace('T', ' ');

    // Prepare the data to send to the server
    var data = {
        course_id: course_id,
        chapter_id: chapter_id,
        exercise_no: exercise_no,
        chapter_name: chapter_name,
        task: task,
        time: time,
        date: date,
        operationType: operationType // Ensure that operationType is included
    };

    // Send the AJAX request
    $.ajax({
        url: 'Extra/updateCourseExercise',
        type: 'POST',
        data: data,
        success: function (response) {
            console.log(response);
            // Handle the response as needed
            location.reload(); // Reload the page after update/insert
        },
        error: function (xhr, status, error) {
            console.error(xhr.responseText);
        }
    });
}

function closeModal() {
    $('#course_exercise').modal('hide');
}



function send_messages(id){

var message = $.trim($('#admin-chat').val())

 var fd = new FormData(); 
//  $.each($('#file').prop('files'), function(i, file) {
//     fd.append('file', file);
// });
var fInput = []
$.each($('#file')[0].files, function(i, file) {
    fd.append('file-'+i, file);
    fInput.push(file)
});
 fd.append('id', id); 
 fd.append('message', message); 
 if(message != '' || fInput.length > 0 ) {
    $('#refresh-logo').show();
 $.ajax({
     url: 'Extra/sendTutorialMessages',
     type: 'POST',
     data: fd,
     contentType: false,
     processData: false,
     success: function (response) {
         // Handle the response as needed
         var formatted = response.replace(/<script.*?>([\w\W\d\D\s\S\0\n\f\r\t\v\b\B]*?)<\/script>/gi, '');
         console.log(formatted);
         $('#refresh-logo').hide();
         $('#admin-chat').val(''); // Reload the page after update/insert
         $('#msg-page').html(formatted)
         $('#msg-page').scrollTop(  $('#msg-page')[0].scrollHeight)
         $('.uploaded-file-info').html('')  
         $('#file').val('');
        // exit;
     },
     error: function (xhr, status, error) {
         console.error(xhr.responseText);
     }
 });
}
}

$('.enter-submit').keypress(function (e) {
   if (e.which == 13) {
     var id = $('#toutorial-enter').val();
     if(id && id !='none'){
        console.log("Helloooooo")
         send_messages(id)
     }
     return false;    //<---- Add this line
   }
 });
</script>