<?php

class MasterModel extends CI_Model {

    public function fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection, $requestData) {
       
        $type = $requestData['type'];
        $type_name = $requestData['columns'][1]['data'];
//        print_r($type_name);
//        exit;
        $table = $requestData['table'];
        if ($table == 'form_build') {

            $real_querys = "select * from $table where 1 ";
            $real_querys2 = "select count(id) as cnt from $table where 1 ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
//            echo '<br>';
                    if ($clm != '') {
                        $search_data2 .= $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];

            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            } else {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY mainid DESC';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }

            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'requested' => $requestData,
                'recordsFiltered' => $recordsTotal,
                'data' => $res
            );
        } 
        
        else if ($table == 'courses') {

            $real_querys = "select a.id,a.name,a.course_type_id,a.image,count(d.course_id) as lesson,a.amount,a.lecture_days, CONCAT(c.min_age, ' - ', c.max_age)
                    as age_group_id,a.course_time,a.course_duration,a.description,a.course_details_description,
                    a.service_cost,a.addon,a.tax,a.added_date,a.updated_date,b.name as type_name from courses a " 
                    ."left outer join course_type b on a.course_type_id=b.id "  
                     ."left outer join age_group c on c.id = a.age_group_id "
                    . " left outer join chapter d on d.course_id = a.id and d.status = '0'  "
                    . " where 1 and a.status !='2' group by a.id ";                 

            $real_querys2 = "select count(a.id) as cnt from $table a "
                    ."left outer join course_type b on a.course_type_id=b.id "  
                     ."left outer join age_group c on c.id = a.age_group_id "
                    . " where 1 and a.status!='2' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'type_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'course_type') {
                    $columnss = "type_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'type_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            for ($i=0; $i < count($res); $i++) { 
                $course_id = $res[$i]['id'];
                $res[$i]['course_duration'] = $this->calculateCourseDuration($course_id);
                $timeSlotAndDays = $this->getCourseAllTimeSlotAndDays($course_id);
                $res[$i]['lecture_days'] = ($timeSlotAndDays['days'] != '') ? implode(",",$timeSlotAndDays['days']) : '';
            }

            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'event_transaction') {
           

            $real_querys = "select a.id,b.username as parent_name,c.username as child_name,d.event as event_name,d.amount,a.payment_status,a.created_at from event_transaction a
            left outer join users b on b.id = a.user_id
            left outer join users c on c.id = a.child_id
            left outer join events d on d.id = a.event_id where b.status != '2' and c.status != '2' and d.status != '2' ";                 

            $real_querys2 = "select count(a.id) as cnt from event_transaction a
            left outer join users b on b.id = a.user_id
            left outer join users c on c.id = a.child_id
            left outer join events d on d.id = a.event_id where b.status != '2' and c.status != '2' and d.status != '2' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'type_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } elseif ($clm == 'parent_name') { 
                        $search_data2 .= "b.username  like '%$searchValue%' OR ";
                    } elseif ($clm == 'child_name') { 
                        $search_data2 .= "c.username  like '%$searchValue%' OR ";
                    } elseif ($clm == 'event_name') { 
                        $search_data2 .= "d.event like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                // $real_querys2 .= $search_data;
            }
         //   echo $real_querys2 ; echo "@"; die("ok");
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'course_type') {
                    $columnss = "type_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'type_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= '';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'user_tutorial_subscription') {

            $real_querys = "select a.id, b.username as parent_name, c.username as child_name, a.start,
            a.end, a.auto_subscription, a.is_active from user_tutorial_subscription a 
            left outer join users b on b.id = a.parent_id 
            left outer join users c on c.id = a.student_id where b.status != '2' and c.status != '2' ";                 

            $real_querys2 = "select count(a.id) as cnt from user_tutorial_subscription a 
            left outer join users b on b.id = a.parent_id 
            left outer join users c on c.id = a.student_id where b.status != '2' and c.status != '2' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'type_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";

                    } elseif ($clm == 'parent_name') {
                        $search_data2 .= "b.username like '%$searchValue%' OR ";          
                    } elseif ($clm == 'child_name') {
                        $search_data2 .= "c.username like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                // $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'course_type') {
                    $columnss = "type_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'type_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= '';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
        
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        else if ($table == 'tutorial_credit_transactions') {

            $real_querys = "select a.id, c.full_name as user_name, b.name, b.period, b.price, b.credits, a.tutorial_plan_quantity, a.credits as total_credit, a.amount as total_amount, 
            a.transaction_id, a.payment_method, a.payment_status, a.created_at 
            from tutorial_credit_transactions a
            left outer join tutorial_subscription_plan b on b.id = a.tutorial_plan_id
            left outer join users c on c.id = a.user_id where b.status != '2' order by a.created_at desc ";                 

            $real_querys2 = "select count(a.id) as cnt from tutorial_credit_transactions a
            left outer join tutorial_subscription_plan b on b.id = a.tutorial_plan_id
            left outer join users c on c.id = a.user_id where b.status != '2' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'type_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'course_type') {
                    $columnss = "type_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'type_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= '';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        else if ($table == 'chapter') {

                
            $real_querys = "select a.*,b.name as course_name from chapter a left outer join courses b on a.course_id=b.id"
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "join courses b on a.course_id=b.id"
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'course_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'courses') {
                    $columnss = "b.name";
                    $columnss1 = "a.chapter_no";
                    
                    $query .= ' ORDER BY hemant ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ,'. $table . '.' . $columnss1 . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "b.name";
                    $columnss1 = "a.chapter_no";
                    
                    $query .= ' ORDER BY ' . $columnss . ' ASC ' . ' ,'. $table . '.' . $columnss1 . ' ' . ' ASC ' . ' ';
                } else {
                    $columnss = "b.name";
                    $columnss1 = "a.chapter_no";
                    
                    $query .= ' ORDER BY ' . $columnss. ' ' . ' ASC ' . ' , '. $columnss1 . ' ' . ' ASC ' . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }       
        else if ($table == 'upcoming_classes') {


            $real_querys = "select a.*,b.chapter_name as chaptername,b.chapter_no as chapterno,c.name as course_name,d.name as type_name from upcoming_classes a " 
                            ."left outer join chapter b on b.id=a.chapter_id "
                           ." left outer join courses c on c.id=b.course_id "
                            ." left outer join course_type d on d.id=c.course_type_id "
                             ." where 1 and a.status!='2' " ;

            $real_querys2 = "select count(a.id) as cnt from $table a "
                  ."left outer join chapter b on b.id=a.chapter_id "
                           ." left outer join courses c on c.id=b.course_id "
                            ." left outer join course_type d on d.id=c.course_type_id "
                             ." where 1 and a.status!='2' " ;
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'chaptername') {
                        $search_data2 .= "b.chapter_name like '%$searchValue%' OR ";
                    }else if ($clm == 'course_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    } else if ($clm == 'chapterno') {
                        $search_data2 .= "b.chapter_no like '%$searchValue%' OR ";
                    }
                    else if ($clm == 'course_name') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    }
                     else if ($clm == 'type_name') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'courses') {
                    $columnss = "course_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "c.name";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'c.name' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
        else if ($table == 'users' && $type_name == 'student_type') { 
           
            $real_querys = "select a.*,b.name as student_type,d.name as race_name,e.name as dialect_name,case when a.status = 2 then 'Deleted' else 'Active' end as user_status, 
            f.name as rel_name,a.nric as nric_name,h.name as nat_name,i.name as city_name,j.name as state_name,k.name as country_name from users a 
            left outer join user_type b on a.type=b.id 
            left outer join races d on d.id=a.race 
            left outer join dialect e on e.id = a.dialect
            left outer join religion f on f.id = a.religion 
            left outer join nationlity h on h.id = a.nationlity 
            left outer join city i on i.id = a.city 
            left outer join state j on j.id = a.state 
            left outer join country k on k.id = a.country 
            where 1 and a.username !='admin' and a.type = '1'";

            $real_querys2 = "select count(a.id) as cnt,b.name from users a 
            left outer join user_type b on a.type=b.id 
            left outer join races d on d.id=a.race 
            left outer join dialect e on e.id = a.dialect 
            left outer join religion f on f.id = a.religion 
            left outer join nationlity h on h.id = a.nationlity 
            left outer join city i on i.id = a.city 
            left outer join state j on j.id = a.state 
            left outer join country k on k.id = a.country 
            where 1 and a.username !='admin' and a.type = '1' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'race_name') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'dialect_name') {
                        $search_data2 .= "e.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'rel_name') {
                        $search_data2 .= "f.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'nric_name') {
                        $search_data2 .= "g.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'nat_name') {
                        $search_data2 .= "h.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'city_name') {
                        $search_data2 .= "i.name like '%$searchValue%' OR ";
                    } 
                    elseif ($clm == 'state_name') {
                        $search_data2 .= "j.name like '%$searchValue%' OR ";
                    } 
                    elseif ($clm == 'country_name') {
                        $search_data2 .= "k.name like '%$searchValue%' OR ";
                    } 
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'user_type') {
                    $columnss = "student_type";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'student_type') {
                    $columnss = "a.created_at";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.created_at' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            for ($i=0; $i < count($res); $i++) { 
                $courseIds = $res[$i]['course'];
                $idUser = $res[$i]['id'];
                if(!empty($courseIds)) {
                    $userEnrollCourses = $this->db->query("SELECT b.name FROM user_courses a
                    LEFT OUTER JOIN courses b ON b.id = a.course_id WHERE a.course_id IN ($courseIds) AND a.user_id = '$idUser' AND b.status != '2'")->result_array();
                    $courseNames = '';
                    if(count($userEnrollCourses) > 0) {
                        for ($j = 0; $j < count($userEnrollCourses); $j++) { 
                            $courseNames = $courseNames.$userEnrollCourses[$j]['name'].',';
                        }
                        $res[$i]['course_name'] = rtrim($courseNames, ',');
                    }else{
                        $res[$i]['course_name'] = $courseNames;
                    }
                }else{
                    $res[$i]['course_name'] = $courseNames;
                }
            }
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
       
         else if ($table == 'users' && $type_name == 'parent_type') {


            $real_querys = "select a.*,b.name as parent_type,c.name as course_name,d.name as race_name,e.name as dialect_name,case when a.status = 2 then 'Deleted' else 'Active' end as user_status,
                               f.name as rel_name,a.nric as nric_name,h.name as nat_name,i.name as city_name,j.name as state_name,k.name as country_name from users a "
                                ."left outer join user_type b on a.type=b.id "
                               ."left outer join courses c on c.id=a.course "
                               ."left outer join races d on d.id=a.race "
                               ."left outer join dialect e on e.id = a.dialect "
                               ."left outer join religion f on f.id = a.religion " 
                               ."left outer join nationlity h on h.id = a.nationlity "
                               ."left outer join city i on i.id = a.city "
                               ."left outer join state j on j.id = a.state " 
                               ."left outer join country k on k.id = a.country "
                              . " where 1  and a.type = '2'";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                              ."left outer join user_type b on a.type=b.id "
                               ."left outer join courses c on c.id=a.course "
                               ."left outer join races d on d.id=a.race "
                               ."left outer join dialect e on e.id = a.dialect "
                               ."left outer join religion f on f.id = a.religion " 
                               ."left outer join nationlity h on h.id = a.nationlity "
                               ."left outer join city i on i.id = a.city "
                               ."left outer join state j on j.id = a.state " 
                               ."left outer join country k on k.id = a.country "
                                . " where 1 and a.type = '2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'parent_type') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    }
                    elseif ($clm == 'course_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'race_name') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'dialect_name') {
                        $search_data2 .= "e.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'rel_name') {
                        $search_data2 .= "f.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'nric_name') {
                        $search_data2 .= "g.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'nat_name') {
                        $search_data2 .= "h.name like '%$searchValue%' OR ";
                    } 
                      elseif ($clm == 'city_name') {
                        $search_data2 .= "i.name like '%$searchValue%' OR ";
                    } 
                    elseif ($clm == 'state_name') {
                        $search_data2 .= "j.name like '%$searchValue%' OR ";
                    } 
                    elseif ($clm == 'country_name') {
                        $search_data2 .= "k.name like '%$searchValue%' OR ";
                    } 
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'user_type') {
                    $columnss = "parent_type";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'parent_type') {
                    $columnss = "a.created_at";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.created_at' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        else if ($table == 'complain') {


            $real_querys = "select a.*,b.username as user_name from complain a left outer join users b on a.user_id=b.id "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . "left outer join users b on a.user_id=b.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "b.username like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "b.username";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'b.username' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        else if ($table == 'billing_address') {
            $real_querys = "select a.id, d.username as user_name, b.name as course_name, (b.amount * h.item) as amount, 
            (b.service_cost * h.item) as service_cost, (b.addon * h.item) as addon,(b.tax * h.item) as tax, h.item as quantity, 
            c.total_payment as total, c.address, e.name as country, f.name as state, f.name as city, c.transaction_id, c.payment_method, c.payment_status, c.added_date, c.updated_date
            from main_cart a
            left outer join courses b on b.id = a.course_id
            left outer join mycart h on h.main_cart_id = a.id
            left outer join billing_address c on c.cart_id = a.id
            left outer join users d on d.id = a.user_id 
            left outer join country e on e.id = c.country
            left outer join state f on f.id = c.state
            left outer join city g on g.id = c.city
            where a.status != '2' and a.is_paid ='2'";

            $real_querys2 = "select count(a.id) as cnt
            from main_cart a
            left outer join courses b on b.id = a.course_id
            left outer join mycart h on h.main_cart_id = a.id
            left outer join billing_address c on c.cart_id = a.id
            left outer join users d on d.id = a.user_id 
            left outer join country e on e.id = c.country
            left outer join state f on f.id = c.state
            left outer join city g on g.id = c.city
            where a.status != '2' and a.is_paid ='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'payment_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } elseif ($clm == 'user_name') {
                        $search_data2 .= "d.full_name like '%$searchValue%' OR ";
                    } elseif ($clm == 'course_name') {
                            $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } elseif ($clm == 'total') {
                        $search_data2 .= "c.total_payment like '%$searchValue%' OR ";
                    } elseif ($clm == 'address') {
                        $search_data2 .= "c.address like '%$searchValue%' OR ";
                    } elseif ($clm == 'country') {
                        $search_data2 .= "e.name like '%$searchValue%' OR ";
                    } elseif ($clm == 'state') {
                        $search_data2 .= "f.name like '%$searchValue%' OR ";
                    }elseif ($clm == 'city') {
                        $search_data2 .= "g.name like '%$searchValue%' OR ";
                    }elseif ($clm == 'transaction_id' || $clm == 'payment_method' || $clm == 'payment_status') {
                        $search_data2 .= 'c.' . $clm . " like '%$searchValue%' OR ";
                    }else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                 $real_querys2 .= $search_data;
            }
          //  $data2 = $this->db->query($real_querys2)->row_array();
             $data2 =  $this->db->query($real_querys2) ? $this->db->query($real_querys2)->row_array() :[] ;
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
                $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
            }
            $final_q = $query . ' ' . $query1;
            //  echo $final_q; die("ok");
          //  $res = $this->db->query($final_q)->result_array();
            $res  = $this->db->query($final_q) ? $this->db->query($final_q)->result_array() : [];
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
      
        else if ($table == 'check_in') {
            $real_querys ="select a.*,c.chapter_name as chaptername,c.chapter_no as chapterno,d.name as course_name,e.username as user_name,b1.class_name from check_in a
            left outer join upcoming_classes b on b.id = a.upcoming_id
            left outer join master_classes b1 on b1.id = a.master_class_id
            left outer join chapter c on c.id = b1.chapter_id 
            left outer join courses d on d.id = c.course_id
            left outer join users e on e.id = a.user_id
            where a.status = '0' and b.class_status != 'Cancel' ";

            $real_querys2 = "select count(a.id) as cnt from check_in a
            left outer join upcoming_classes b on b.id = a.upcoming_id
            left outer join master_classes b1 on b1.id = a.master_class_id
            left outer join chapter c on c.id = b1.chapter_id 
            left outer join courses d on d.id = c.course_id
            left outer join users e on e.id = a.user_id
            where a.status = '0' and b.class_status != 'Cancel' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'chaptername') {
                        $search_data2 .= "c.chapter_name like '%$searchValue%' OR ";
                    }
                    elseif ($clm == 'chapterno') {
                        $search_data2 .= "c.chapter_no like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'course_name') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    } elseif ($clm == 'user_name') {
                        $search_data2 .= "e.username like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                 if ($columnss == 'chapter') {
                    $columnss = "chaptername";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'chaptername') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
        
        else if ($table == 'child_homework') {
            $real_querys ="select a.*, COALESCE(e.username,'') as username,d.task as exercise_name,c.chapter_no as chapterno, c.chapter_name as chaptername,f.name as course_name,case when a.homework_status = 0 then 'Unapproved' when a.homework_status = 1 then 'Approved' else 'Incomplete' end as homework_status from child_homework a "
                            ."left outer join homework b on b.id = a.homework_id "
                            ."left outer join course_exercise d on d.id = b.exercise_id "
                            ."left outer join chapter c on c.id = d.chapter_id "
                            ."left outer join users e on e.id = a.user_id " 
                            ."left outer join courses f on f.id=c.course_id " 
                            ."where a.status = '0' ";
   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                            ."left outer join homework b on b.id = a.homework_id "
                            ."left outer join course_exercise d on d.id = b.exercise_id "
                            ."left outer join chapter c on c.id = d.chapter_id "
                            ."left outer join users e on e.id = a.user_id " 
                            ."left outer join courses f on f.id=c.course_id " 
                                 . " where 1 and a.status = '0' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'exercise_name') {
                        $search_data2 .= "d.task like '%$searchValue%' OR ";
                    }
                    elseif ($clm == 'username') {
                        $search_data2 .= "e.username like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'chapterno') {
                        $search_data2 .= "c.chapter_no like '%$searchValue%' OR ";
                    }
                    elseif ($clm == 'course_name') {
                        $search_data2 .= "f.name like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'chaptername') {
                        $search_data2 .= "c.chapter_name like '%$searchValue%' OR ";
                    }
                    
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                 if ($columnss == 'course_exercise') {
                    $columnss = "exercise_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'exercise_name') {
                    $columnss = "d.task";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $final_arr = [];
           foreach($res as $list){
            $child_homework_docs = $this->db->query("select * from ch_homework_doc where child_homework_id = '$list[id]' and status = '0' ")->result_array();
            $html = '';
            for ($i = 0; $i < count($child_homework_docs); $i++) { 
                $url = $child_homework_docs[$i]['doc'];
                $html.= "<div><a href=".$url." target='_blank' >".$child_homework_docs[$i]['doc']."</a></div>";
            }
            $list['doc_list'] = $html;
            $final_arr[] = $list;
           }
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $final_arr
            );
        }
        
        else if ($table == 'homework') {         
            $real_querys = "select a.*,c.chapter_no as chapterno,c.chapter_name as chaptername,b.task as exercise_name from homework a "                       
                       ."left outer join course_exercise b on b.id = a.exercise_id " 
                       ."left outer join chapter c on c.id = b.chapter_id " 
                       ."where 1 and a.status!='2' ";
            $real_querys2 = "select count(a.id) as cnt from $table a left outer join course_exercise b on b.id = a.exercise_id 
                    left outer join chapter c on c.id = b.chapter_id 
                     where 1 and a.status != '2'  ";
            if($searchValue != '' ) {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'chapterno') {
                        $search_data2 .= "c.chapter_no like '%$searchValue%' OR ";
                    }
                    elseif ($clm == 'chaptername') {
                        $search_data2 .= "c.chapter_name like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'exercise_name') {
                        $search_data2 .= "b.task like '%$searchValue%' OR ";
                    }

                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'chapter') {
                    $columnss = "chapterno";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'chapterno') {
                    $columnss = "c.chapter_no";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'c.chapter_no' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'announcement') {         
            $real_querys = "select * from announcement where status != '2'";
            $real_querys2 = "select count(id) as cnt from announcement where status != '2'";
            if($searchValue != '' ) {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm != '') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            $query .= ' ORDER BY ' . 'created_at' . ' ' . $ascdesc . ' ';
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'course_gallery_folders') {         
            $real_querys = "select a.id,a.folder_name,b.name as course_name from course_gallery_folders a
            left outer join courses b on b.id = a.course_id  where a.status != '2' and b.status !='2' ";
            $real_querys2 = "select count(a.id) as cnt from course_gallery_folders a
            left outer join courses b on b.id = a.course_id  where a.status != '2' and b.status != '2' ";
            if($searchValue != '' ) {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm != '') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        else if ($table == 'course_gallery') {         
            $real_querys = "select * from courses where status != '2'";
            $real_querys2 = "select count(id) as cnt from courses where status != '2'";
            if($searchValue != '' ) {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm != '') {
                        $search_data2 .= $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        // else if ($table == 'course_gallery') {         
        //     $real_querys = "select a.id,a.gallery,b.folder_name,c.name as course_name from course_gallery a 
        //                     left outer join course_gallery_folders b on b.id = a.gallery_folder_id 
        //                     left outer join courses c on c.id = b.course_id 
        //                     where 1 and a.status!='2' and b.status !='2' and c.status != '2'";
        //     $real_querys2 = "select count(a.id) as cnt from $table a 
        //                     left outer join course_gallery_folders b on b.id = a.gallery_folder_id 
        //                     left outer join courses c on c.id = b.course_id 
        //                     where 1 and a.status!='2' and b.status !='2' and c.status != '2'";
        //     if($searchValue != '' ) {
        //         $search_data2 = '';
        //         foreach ($requestData['columns'] as $columns) {
        //             $clm = $columns['data'];
        //             if ($clm != '') {
        //                 $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
        //             }
        //         }
        //         $search_data2 = rtrim($search_data2, 'OR ');
        //         $search_data = ' and (' . $search_data2 . ')';
        //         $real_querys .= $search_data;
        //         $real_querys2 .= $search_data;
        //     }

        //     $data2 = $this->db->query($real_querys2)->row_array();
        //     $recordsTotal = $data2['cnt'];
        //     $query = $real_querys;
        //     $position = $requestData['order'][0]['column'];
        //     $ascdesc = $requestData['order'][0]['dir'];
        //     $query1 = '';
        //     if ($requestData['length'] != -1) {
        //         $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
        //     }
        //     $final_q = $query . ' ' . $query1;

        //     $res = $this->db->query($final_q)->result_array();
        //     $this->db->close();
        //     $response = array(
        //         'draw' => intval($requestData['draw']),
        //         'recordsTotal' => $recordsTotal,
        //         'recordsFiltered' => $recordsTotal,
        //         'requested' => $requestData,
        //         'data' => $res
        //     );
        // }

        else if ($table == 'course_certificate') {         
            $real_querys = "select a.id,a.certificate,a.created_at,b.full_name as user_name,c.name as course_name from course_certificate a 
            left outer join users b on b.id = a.user_id 
            left outer join courses c on c.id = a.course_id 
            where 1 and a.status!='2' and b.status !='2' and c.status != '2'";
            $real_querys2 = "select count(a.id) as cnt from $table a 
            left outer join users b on b.id = a.user_id 
            left outer join courses c on c.id = a.course_id 
            where 1 and a.status!='2' and b.status !='2' and c.status != '2'";
            if($searchValue != '' ) {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm != '') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        else if ($table == 'news') {
                        $real_querys = "select * from $table where status != '2' ";
                        $real_querys2 = "select count(id) as cnt from $table where status != '2'  ";
                        if($searchValue != '' ) {
                            $search_data2 = '';
                            foreach ($requestData['columns'] as $columns) {
                                $clm = $columns['data'];
                                if ($clm == 'description') {
                                    $search_data2 .= "description like '%$searchValue%' OR ";
                                }
                                elseif ($clm == 'news_type') {
                                    $search_data2 .= "news_type like '%$searchValue%' OR ";
                                }            
                                else {
                                    if ($clm != '') {
                                        $search_data2 .= $clm . " like '%$searchValue%' OR ";
                                    }
                                }
                            }
                            $search_data2 = rtrim($search_data2, 'OR ');
                            $search_data = ' and (' . $search_data2 . ')';
                            $real_querys .= $search_data;
                            $real_querys2 .= $search_data;
                        }

                        $data2 = $this->db->query($real_querys2)->row_array();
                        $recordsTotal = $data2['cnt'];
                        $query = $real_querys;
                        $position = $requestData['order'][0]['column'];
                        $ascdesc = $requestData['order'][0]['dir'];
                        if (isset($ascdesc)) {
                            $columnss = $requestData['columns'][$position]['data'];
                            if ($columnss == 'news_type') {
                                $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                            } else {
                                $query .= ' ORDER BY ' . 'added_date' . ' ' . $ascdesc . ' ';
                            }
                        }
                        $query1 = '';
                        if ($requestData['length'] != -1) {
                            $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
                        }
                        $final_q = $query . ' ' . $query1;
            
                        $res = $this->db->query($final_q)->result_array();
                        $this->db->close();
                        $response = array(
                            'draw' => intval($requestData['draw']),
                            'recordsTotal' => $recordsTotal,
                            'recordsFiltered' => $recordsTotal,
                            'requested' => $requestData,
                            'data' => $res
                        );
                    }
        else if ($table == 'child_parent_relationship') {
            $real_querys = "SELECT a.*, b.username AS childname,d.name AS request_status, CASE 
            WHEN ROW_NUMBER() OVER (PARTITION BY a.parent_id ORDER BY a.id) = 1 THEN c.username  ELSE '-' END AS parentname 
            FROM  child_parent_relationship a 
            LEFT OUTER JOIN  users b ON a.child_id = b.student_id
            LEFT OUTER JOIN  users c ON a.parent_id = c.id
            LEFT OUTER JOIN  select_type d ON a.request_status = d.id
            JOIN (SELECT @prevParentName := NULL) AS init WHERE  1 AND a.status != '2' ";

            $real_querys2 = "SELECT count(a.id) as cnt FROM  $table a
            LEFT OUTER JOIN  users b ON a.child_id = b.student_id
            LEFT OUTER JOIN  users c ON a.parent_id = c.id
            LEFT OUTER JOIN  select_type d ON a.request_status = d.id
            JOIN (SELECT @prevParentName := NULL) AS init WHERE  1 AND a.status != '2'"; 
  
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'childname') {
                        $search_data2 .= "b.username like '%$searchValue%' OR ";
                    }
                    else if ($clm == 'parentname') {
                        $search_data2 .= "c.username like '%$searchValue%' OR ";
                    }
                    else if ($clm == 'request_status') {
                        $search_data2 .= "d.name like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            
            
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "parentname";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ','.  'a.added_date ASC ';
                } else if ($columnss == 'parentname') {
                    $columnss = "c.username";
                    $query .=  ' ORDER BY ' . $columnss . ' ' . $ascdesc . ','.  'a.added_date ASC ';
                } else {
                    $query .=  ' ORDER BY ' . 'c.username' . ' ' . $ascdesc . ','.  'a.added_date ASC ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'course_subscription') {


            $real_querys = "select a.*,b.full_name as Name,c.card_holder as Name from course_subscription a "
                    . "left outer join billing_address b on a.billing_address_id=b.id "
                    . "left outer join card c on c.id=a.cart_id "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.full_name from $table a "
                    . "join billing_address b on a.billing_address_id=b.id"
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'Name') {
                        $search_data2 .= "b.full_name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'billing_address') {
                    $columnss = "Name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'Name') {
                    $columnss = "b.full_name";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'b.full_name' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
        
        else if ($table == 'reschedule_classes') {
            $real_querys = "select a.*,f.name as course_name from reschedule_classes a "
                    . "left outer join upcoming_classes c on a.upcoming_id=c.id "
                    . "left outer join master_classes d on c.master_class_id=d.id "
                    . "left outer join chapter e on d.chapter_id=e.id "
                    . "left outer join courses f on e.course_id=f.id "
                    . " where 1 and a.status!='2' and c.status!='2' and d.status!='2' and e.status!='2' and f.status!='2'";

            $real_querys2 = "select count(a.id) as cnt,f.name from $table a "
            . "left outer join upcoming_classes c on a.upcoming_id=c.id "
            . "left outer join master_classes d on c.master_class_id=d.id "
            . "left outer join chapter e on d.chapter_id=e.id "
            . "left outer join courses f on e.course_id=f.id "
            . " where 1 and a.status!='2' and c.status!='2' and d.status!='2' and e.status!='2' and f.status!='2'";
           
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'course_name') {
                        $search_data2 .= "f.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
          
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'couses') {
                    $columnss = "course_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "f.name";
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                } else { 
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
       // tutorials
       else if ($table == 'tutorial') {
    
        $real_querys = "select a.*,b.full_name,c.name,d.chapter_name, case when a.is_active = 0 then 'Pending' when a.is_active = 1 then 'Answered' else 'Closed' end as tutorial_status from tutorial a " 
        ."left outer join users b on a.user_id=b.id "
        ."left outer join courses c on a.course_id=c.id "
        ."left outer join chapter d on a.chapter_id=d.id "
        . " where 1 and a.status!='2' ";
                   
        $real_querys2 = "select count(a.id) as cnt,a.id from tutorial a " 
        ."left outer join users b on a.user_id=b.id "
        ."left outer join courses c on a.course_id=c.id "
        ."left outer join chapter d on a.chapter_id=d.id "
        . " where 1 and a.status!='2' ";
        if ($searchValue != '') {
            $search_data2 = '';
            foreach ($requestData['columns'] as $columns) {
                $clm = $columns['data'];
                if ($clm == 'id') {
                    $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                } else {
                    if ($clm != '') {
                        $search_data2 .=  'a'.$clm . " like '%$searchValue%' OR ";
                    }
                }
            }
            $search_data2 = rtrim($search_data2, 'OR ');
            $search_data = ' and (' . $search_data2 . ')';
            $real_querys .= $search_data;
            $real_querys2 .= $search_data;
        }
        $data2 = $this->db->query($real_querys2)->row_array();
        $recordsTotal = $data2['cnt'];
        $query = $real_querys;
        $position = $requestData['order'][0]['column'];
        $ascdesc = $requestData['order'][0]['dir'];
        if (isset($ascdesc)) {
            $columnss = $requestData['columns'][$position]['data'];
            if ($columnss == 'id') {
                $columnss = "a.id";
                $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
            } else if ($columnss == 'question') {
                $columnss = "a.added_date";
                $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            } else {
                $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
            }
        }
        $query1 = '';
        if ($requestData['length'] != -1) {
            $query1 = ' LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
        }
        $final_q = $query . ' ' . $query1;

        $res = $this->db->query($final_q)->result_array();
        $this->db->close();
        $response = array(
            'draw' => intval($requestData['draw']),
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'requested' => $requestData,
            'data' => $res
        );
    }  else if ($table == 'course_exercise') {


            $real_querys = "select a.*,b.chapter_name as Chapter_name,c.name as coursename from course_exercise a "
                    . "left outer join chapter b on a.chapter_id=b.id "
                    . "left outer join courses c on b.course_id=c.id"
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt from $table a "
                     . "left outer join chapter b on a.chapter_id=b.id "
                    . "left outer join courses c on b.course_id=c.id"
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'Chapter_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'coursename') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'chapter') {
                    $columnss = "Chapter_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'Chapter_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }  else if ($table == 'course_material') {


            $real_querys = "select a.*,b.name as course_name from course_material a left outer join courses b on a.course_id=b.id"
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "left outer join courses b on a.course_id=b.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'course_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'couses') {
                    $columnss = "course_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'course_rating') {


            $real_querys = "select a.*,b.name as course_name,c.username as user_name from course_rating a "
                   . "left outer join courses b on a.course_id=b.id "
                     . "left outer join users c on c.id=a.user_id"
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . "left outer join courses b on a.course_id=b.id "
                     . "left outer join users c on c.id=a.user_id"
                          . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'course_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    }else if ($clm == 'user_name') {
                        $search_data2 .= "c.username like '%$searchValue%' OR ";
                    } 
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'couses') {
                    $columnss = "course_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'ongoing_course') {


            $real_querys = "select a.*,b.name as course_name from ongoing_course a left outer join courses b on a.course_id=b.id"
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "left outer join courses b on a.course_id=b.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'course_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'couses') {
                    $columnss = "course_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'course_name') {
                    $columnss = "b.name";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'b.name' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'child_quiz_result') {


            $real_querys = "select a.*,b.title as badge_name,c.name as quiz_name from child_quiz_result a "
                    . "left outer join badges b on a.badge_id=b.id "
                    . " left outer join main_quiz c on a.main_quiz_id=c.id "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt from $table a "
                  . "left outer join badges b on a.badge_id=b.id "
                    . " left outer join main_quiz c on a.main_quiz_id=c.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'badge_name') {
                        $search_data2 .= "b.title like '%$searchValue%' OR ";
                    }
                     elseif ($clm == 'quiz_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    }
                    
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'badges') {
                    $columnss = "badge_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'badge_name') {
                    $columnss = "b.title";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'b.title' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'main_quiz') {


            $real_querys = "select a.*,b.name as Name from main_quiz a "
                    . "left outer join courses b on a.course_id=b.id "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "left outer join courses b on a.course_id=b.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'Name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'couses') {
                    $columnss = "Name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'Name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'quiz') {


            $real_querys = "select a.*,b.name as main_quiz_name,c.name as type_name from quiz a "
                        ." left outer join main_quiz b on a.main_quiz_id=b.id "
                        ." left outer join quiz_type c on a.select_type=c.id "
                         ." where 1 and a.status!='2' ";
  
            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                     ." left outer join main_quiz b on a.main_quiz_id=b.id "
                        ." left outer join quiz_type c on a.select_type=c.id "
                         ." where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'main_quiz_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    }
                   else if ($clm == 'type_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    } 
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'main_quiz') {
                    $columnss = "main_quiz_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'main_quiz_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'quiz_options') {


            $real_querys = "select a.*,b.name as quiz_name from quiz_options a left outer join quiz b on a.quiz_id=b.id "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "join quiz b on a.quiz_id=b.id "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'quiz_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'quiz') {
                    $columnss = "quiz_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'quiz_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
         else if ($table == 'quiz_type') {


            $real_querys = "select a.* from quiz_type a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'payment') {


            $real_querys = "select a.* from payment a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'add_account') {
            $table ='users';
            $user_type = $this->db->query("select id from user_type where name='Teacher' or name='Admin';")->result_array();
            $idArr = array();
            foreach($user_type as $list){
                array_push($idArr, $list['id']);
            }
            $type = implode(",",$idArr);
            $real_querys = "select a.id,a.full_name as name, a.username, a.image, a.contact_email, a.created_at, a.updated_at, b.name as type from users a " 
            ."  left outer join user_type b on b.id=a.type"         
            ."  where a.type in ($type) and a.status!='2' ";    
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where a.type in ($type) and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'username') {
                        $search_data2 .= "a.username like '%$searchValue%' OR ";
                    }elseif ($clm == 'name') {
                        $search_data2 .= "a.full_name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                // $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "username";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.created_at";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.created_at' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'badges') {
            $real_querys = "select a.* from badges a " 
                     . " where 1 and a.status!='2' ";    
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
         else if ($table == 'city') {
            $real_querys = "select a.*,b.name as state_name,c.name as country_name from city a "
                    . "left outer join state b on b.id=a.state_id "
                    . "left outer join country c on c.id=a.country_id " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                      . "left outer join state b on b.id=a.state_id "
                    . "left outer join country c on c.id=a.country_id " 
                     . " where 1 and a.status!='2' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'state_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'state') {
                    $columnss = "state_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'state_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
         else if ($table == 'state') {


            $real_querys = "select a.*,b.name as country_name from state a "
                    . "left outer join country b on b.id=a.country_id" 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                . "left outer join country b on b.id=a.country_id" 
                   . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'country_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else { 
                    $query .= ' ORDER BY ' . 'a.id' . ' ' . $ascdesc . ' ';
                }
            }
          
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'nationlity') {

  
            $real_querys = "select a.* from nationlity a "
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                   . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'country_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
         else if ($table == 'races') {


            $real_querys = "select a.* from races a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        
         else if ($table == 'country') {


            $real_querys = "select a.* from country a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        else if ($table == 'class_location') {


            $real_querys = "select a.* from class_location a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        
        else if ($table == 'dialect') {


            $real_querys = "select a.* from dialect a " 
                     . " where 1 and a.status!='2' ";

                   
            $real_querys2 = "select count(a.id) as cnt from $table a "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'user_name') {
                        $search_data2 .= "a.added_date like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'users') {
                    $columnss = "user_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'user_name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

//            echo $final_q;
//            exit;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if ($table == 'child_quiz_answer') {


            $real_querys = "select a.*,b.name as main_quiz_name,c.name as quiz_name,a.quiz_type,e.username as user_name from child_quiz_answer a "
                    . "left outer join main_quiz b on a.main_quiz_id=b.id "
                    . "left outer join quiz c on a.quiz_id=c.id "
                    // . "left outer join quiz_options d on a.pair_id=d.id "
                   . "left outer join users e on a.user_id=e.id "
                           . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                 . "left outer join main_quiz b on a.main_quiz_id=b.id "
                    . "left outer join quiz c on a.quiz_id=c.id "
                    // . "left outer join quiz_options d on a.pair_id=d.id "
                    . "left outer join users e on e.id=a.user_id "
                         . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'main_quiz_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } 
                      else if ($clm == 'quiz_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    }
                 
                    else if ($clm == 'user_name') {
                        $search_data2 .= "e.username like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'main_quiz') {
                    $columnss = "Name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'Name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } else if ($table == 'contact_us') {


            $real_querys = "select a.*,b.name as subject_name from contact_us a left outer join subject b on b.id=a.subject "
                    . " where 1 and a.status!='2' ";

            $real_querys2 = "select count(a.id) as cnt,b.name from $table a "
                    . "join subject b on b.id=a.subject "
                    . " where 1 and a.status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'subject_name') {
                        $search_data2 .= "b.name like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'subject') {
                    $columnss = "subject_name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'subject_name') {
                    $columnss = "b.name";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        } 
        else if ($table == 'tutorial_subscription_plan') {
            $real_querys = "select * from tutorial_subscription_plan where status!='2' ";

            $real_querys2 = "select count(id) as cnt from tutorial_subscription_plan where status!='2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'credit') {
                        $search_data2 .= "credit like '%$searchValue%' OR ";
                    } else {
                        if ($clm != '') {
                            $search_data2 .=  $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'credit') {
                    $columnss = "credit";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'created_at' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }else if ($table == 'add_course') { 
            $real_querys = "select a.*, b.full_name as users,c.name as course_name from main_cart a
            left outer join mycart b1 on b1.main_cart_id = a.id
            left outer join billing_address b2 on b2.cart_id = a.id
            left outer join users b on b.id = b1.child_id
            left outer join courses c on a.course_id = c.id where a.status != '2' and b.status != '2' 
            and c.status != '2' and b1.status != '2' and b2.status != '2' and b2.payment_method = 'Offline'";

            $real_querys2 = "select count(a.id) as cnt from main_cart a
            left outer join mycart b1 on b1.main_cart_id = a.id
            left outer join billing_address b2 on b2.cart_id = a.id
            left outer join users b on b.id = b1.child_id
            left outer join courses c on a.course_id = c.id where a.status != '2' and b.status != '2' 
            and c.status != '2' and b1.status != '2' and b2.status != '2' and b2.payment_method = 'Offline'";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'users') {
                        $search_data2 .= "b.full_name like '%$searchValue%' OR ";
                    } 
                      else if ($clm == 'course_name') {
                        $search_data2 .= "c.name like '%$searchValue%' OR ";
                    }
                    else {
                        if ($clm != '') {
                            $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                        }
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'main_quiz') {
                    $columnss = "Name";
                    $query .= ' ORDER BY ' . $table . '.' . $columnss . ' ' . $ascdesc . ' ';
                } else if ($columnss == 'Name') {
                    $columnss = "a.added_date";
                    $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
                } else {
                    $query .= ' ORDER BY ' . 'a.added_date' . ' ' . $ascdesc . ' ';
                }
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
           
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else if($table == 'events') {
            $real_querys = "select a.*, b.name as location_id from events a 
            left outer join class_location b on b.id = a.location_id where a.status != '2' and b.status != '2'";

            $real_querys2 = "select count(id) as cnt from events where status != '2' ";
            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm != '') {
                        $search_data2 .= $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }
            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY ' . 'added_date' . ' ' . $ascdesc . ' ';
            }
            $query1 = '';
            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            $res = $this->db->query($final_q)->result_array();
            $this->db->close();
            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }
        else { 

            $real_querys = "select * from $table where 1 ";
            $real_querys2 = "select count(id) as cnt from $table where 1 ";
            if ($type != '') {
                $real_querys .= " and type='$type' ";
                $real_querys2 .= " and type='$type' ";
            }

            $real_querys .= " and status != '2' ";
            $real_querys2 .= " and type != '2' and status = '0' ";

            if ($searchValue != '') {
                $search_data2 = '';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
//            echo '<br>';
                    if ($clm != '') {
                        $search_data2 .= $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' and (' . $search_data2 . ')';
                $real_querys .= $search_data;
                $real_querys2 .= $search_data;
            }

            $data2 = $this->db->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
 
            $query = $real_querys;
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $query .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query1 = 'LIMIT ' . $requestData['start'] . ', ' . $requestData['length'];
            }
            $final_q = $query . ' ' . $query1;

            
            $res = $this->db->query($final_q)->result_array();
            $this->db->close();

            $response = array(
                'draw' => intval($requestData['draw']),
                'recordsTotal' => $recordsTotal,
                'recordsFiltered' => $recordsTotal,
                'requested' => $requestData,
                'data' => $res
            );
        }

        return $response;
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

    public function countAllfetch($requestData) {
        $type = $requestData['type'];
        $table = $requestData['table'];
        $this->db->where('type', $type);
//        print_r($requestData);
//        
//        exit;
        return $this->db->count_all($table);
    }

    public function countFilteredfetch($searchValue, $requestData) {
        $type = $requestData['type'];
        $table = $requestData['table'];
        $this->db->where('type', $type);
        return $this->db->count_all_results($table);
    }

    public function add_data($data, $user_id, $image_main_files) {
        try{
           // date_default_timezone_set('Asia/Kolkata');
        $date = date("Y-m-d H:i:s");
        $table = $data['table'];
        
        unset($data['heading']);
        unset($data['match']);
        unset($data['table']);
        if ($table == 'slider') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date, 'image' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
       } 
       else if ($table == 'courses') {
            // $data['lecture_days'] = implode(",", $data['lecture_days']);
            $data2 = array_merge($data, array('user_id' => $user_id,'added_date' => $date, 'updated_date' => $date,  'image' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'homework') {   
            $data['exercise_id'] = implode(",",$data['exercise_id']);
            $data['homework_material'] = $data['image'];           
            unset($data['image']);
            unset($data['chapter_id']);
            $hk_date = $this->getHighestExerciseDateline($data['exercise_id']);
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date, 'hk_date' => $hk_date, 'homework_material' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'add_course') {   
            $childId = $data['user_id'];
            $courseId = $data['change_course_id'];
            return $this->offlineCoursePurchase($childId, $courseId, $date);
        }
        else if($table == 'course_gallery_folders'){         
            $data2 = array_merge($data, array('created_at' => $date,'updated_at' => $date));
            if ($table == '') {
                $table = "form_build";
            }

            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if($table == 'announcement'){         
            $data2 = array_merge($data, array('created_at' => $date,'updated_at' => $date));
            if ($table == '') {
                $table = "form_build";
            }
            $this->db->insert($table, $data2);
            $lastInsertedId = $this->db->insert_id();  
            $this->load->library('Firebase');
            $firebase = new Firebase();
            $data['topic'] = 'event_announcement';
            $data['notificationFor'] = 'Announcement';
            $data['title'] = 'New Announcement '.$data2['name'];
            $data['body'] = $data2['message'];
           
            try { 
                $response =  $firebase->sendNotification($data);
            }catch (Exception $e) {
                //alert the user then kill the process
            
                // $final = array();
                // $final['status'] = false;
                // $final['message'] = $e->getMessage();
                // $this->response($final, REST_Controller::HTTP_OK);
            }
          
            $this->db->query("INSERT INTO notification (main_id,notification_type,is_all,added_date,updated_date) VALUES ($lastInsertedId,'All_Announcement',1,'$date','$date')");
        }
        else if ($table == 'course_gallery') {   
            $data['gallery'] = $data['image'];           
            unset($data['image']);
            unset($data['course_id']);
            $gallery_folder_id = $data['gallery_folder_id'];
            $alreadyExist = $this->db->query("SELECT * FROM course_gallery WHERE gallery_folder_id = $gallery_folder_id AND status != '2'")->result_array();
            if(count($alreadyExist) > 0) {
                return 'exist';
            }
            $data2 = array_merge($data, array('created_at' => $date,'updated_at' => $date, 'gallery' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }

            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'course_certificate') {
            unset($data['image']);
            $data2 = array_merge($data, array('created_at' => $date,'updated_at' => $date, 'certificate' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }

            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'child_homework') {           
            unset($data['course_id']);
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'news') {
            unset($data['image']);
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,'news_image' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'tutorial_subscription_plan') {
            $data['credits'] = (int) $data['credits'];
            $data['credit_expiry_in_days'] = (int) $data['credit_expiry_in_days'];
            unset($data['image']);
            $data2 = array_merge($data, array('bg_image' => $image_main_files,'created_at' => $date, 'updated_at' => $date));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'get_started') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date, 'url' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        } 
        else if ($table == 'ch_homework_doc') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'doc' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        } 
        else if ($table == 'course_material') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'material_content' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'events') {
            $sdes = trim($data['short_description']);
            $fdes = trim($data['full_description']);
            $event = trim($data['event']);
            if($sdes == '' || $fdes == ''  || $event == ''){
                return array("message" => $error = 'can not save empty fields');
            }else{
            $data['short_description']  = str_replace("'","\'",  $data['short_description']); 
            $data['full_description'] = str_replace("'","\'",  $data['full_description']); 
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date, 'image' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            
            if($this->db->insert($table, $data2)){
                $lastInsertedId = $this->db->insert_id();  
                $this->load->library('Firebase');
                $firebase = new Firebase();
                $data['topic'] = 'event_announcement';
                $data['notificationFor'] = 'Event';
                $data['title'] = 'New Event '.$data2['event'] ;
                $data['body'] = $data2['short_description'] ;
                $message = $data2['short_description'] ;
                $data['type'] = null;
                try { 
                    $response =  $firebase->sendNotification($data);
                }catch (Exception $e) {
                    //alert the user then kill the process
                
                    // $final = array();
                    // $final['status'] = false;
                    // $final['message'] = $e->getMessage();
                    // $this->response($final, REST_Controller::HTTP_OK);
                }
               // date_default_timezone_set('Asia/Kolkata');
                if ($this->db->query("INSERT INTO notification (main_id,notification_type,message,is_all,added_date,updated_date) VALUES ($lastInsertedId,'All_Event','$message',1,'$date','$date')")) {
                    
                }else{
                    return array("message" => $error = $this->db->error()["message"]);
                }
            };
        }
        } 
        else if ($table == 'quiz_options') {
            unset($data['main_quiz_id']);
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'image' => $image_main_files));

            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }              
        else if ($table == 'users') {
            $data2 = array_merge($data, array( 'created_at' => $date));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }else if ($table == 'add_account') {
            $data2 = array_merge($data, array( 'created_at' => $date,'updated_at' => $date,'image' => $image_main_files));
            $table = "users";
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        } 
        else if ($table == 'chapter') {
             $course_id=$data['course_id'];
             $chapter_no_id =$data['chapter_no'];

             if($chapter_no_id == ''){
             $chaptersrno = $this->db->query("SELECT COALESCE(MAX(chapter_no), 0) + 1 AS srno FROM chapter WHERE status != 2 AND course_id = $course_id")->row_array();
             $chaptersrno = $chaptersrno['srno'];
             }else{
                $chaptersrno= $chapter_no_id;
             }
             
            $data2 = array_merge($data, array('user_id' => $user_id,'added_date' => $date, 'updated_date' => $date, 'chapter_no' => $chaptersrno)); 
            if ($table == '') {
                $table = "form_build";
            }   
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table == 'tr_homework_doc') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'doc' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
                
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if ($table== 'child_parent_relationship'){
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date));
            $child_id = $data['child_id'];
            foreach ($child_id as $id ){
            $student = $id;
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date,'child_id' => $student));
                if ($this->db->insert($table, $data2)) {
                    
                }else{
                    return array("message" => $error = $this->db->error()["message"]);
                }
            }
        }
        else if ($table== 'upcoming_classes'){    
            $unique_id = $this->guidv4();
            $class_id = $data['class_id'];
            $data['recurring'] = $data['recurring_type'];
            unset($data['class_id']);
            unset($data['recurring_type']);
            $incomming_date_data = json_decode($data['upcoming_date'])[0];
            $upcoming_date = $incomming_date_data->start;
            $end_time = $incomming_date_data->end;  
            $title = $incomming_date_data->title;
            $main_id = null;
            $st = strtotime($upcoming_date);
            $data['upcoming_date'] =  date('Y-m-d H:i:s', $st);
            $start_time = date('H:i:s', $st);
            $next_date = '';
            // date_default_timezone_set("asia/Kuala_Lumpur");
           // date_default_timezone_set('Asia/Kolkata');
            $date = date("Y-m-d H:i:s");
            if($class_id != 0){
                $reschedule_type = $data['reschedule_type'];
                unset($data['reschedule_type']);
                $class_data = $this->db->query("select * from upcoming_classes where id = '$class_id' and status != '2'")->row_array();
                if($reschedule_type == 'update_series'){
                    $unique_id = $class_data['unique_rec_id'];
                    $current_date = date('Y-m-d H:i:s'); 
                    $no_occ = $class_data['no_occ'];
                    $this->db->query("delete from upcoming_classes where unique_rec_id='$unique_id' and upcoming_date > '$current_date'");
                    $remaining_class = $this->db->query("select * from upcoming_classes where unique_rec_id = '$unique_id' and status != '2'")->result_array();
                    if(count($remaining_class)> 0 ){
                       $rec_count = (int)$class_data['no_occ'] - count($remaining_class);
                    }else{
                        $rec_count = $class_data['no_occ'];
                    }
                    if($class_data['recurring'] !='no'){
                        for($i=1; $i<=(int)$rec_count; $i++){
                            if($i != 1){
                                $next_date_st = strtotime($next_date);
                                switch($class_data['recurring']){
                                    case 'daily':
                                        $next_date = date('Y-m-d H:i:s', strtotime('+1 day',$next_date_st));
                                    break;
                                    case 'weekly':
                                        $next_date = date('Y-m-d H:i:s', strtotime('+1 week', $next_date_st));
                                    break;
                                    case 'bi-weekly':
                                        $next_date = date('Y-m-d H:i:s', strtotime('+14 day',$next_date_st));
                                    break;
                                    case 'monthly':
                                        $next_date = date('Y-m-d H:i:s', strtotime('+1 months',$next_date_st));
                                    break;
                                    default:    
                                    $next_date = null;
                                }
                            }else{
                                $next_date = $data['upcoming_date'];
                            }
                            $data['class_status'] = $class_id != 0 ? 'Reschedule' : 'Active';
                            $data2 = array_merge($data, array('no_occ'=> $no_occ,'unique_rec_id'=> $unique_id,'upcoming_date'=>$next_date,'user_id' => $user_id, 'added_date' => $date,'updated_date' => $date,'start' =>  $start_time, 'end'=>$end_time, 'title'=>ucfirst($title)));   
                            if ($this->db->insert($table, $data2)) {
                                $main_id = $this->db->insert_id();
                            }else{
                                echo $this->db->error()["message"];
                            }
                        }
                    }else{
                        $next_date = $data['upcoming_date'];
                        $data['class_status'] = $class_id != 0 ? 'Reschedule' : 'Active';
                        $data2 = array_merge($data, array('no_occ'=> $no_occ,'unique_rec_id'=> $unique_id,'upcoming_date'=>$next_date,'user_id' => $user_id, 'added_date' => $date,'updated_date' => $date,'start' =>  $start_time, 'end'=>$end_time, 'title'=>ucfirst($title)));   
                        $this->db->insert($table, $data2);
                    }
                }else if($reschedule_type == 'single_class'){
                    $data['class_status'] = $class_id != 0 ? 'Reschedule' : 'Active';
                    $data2 = array('master_class_id'=> $data['master_class_id'] ,'location'=> $data['location'],'upcoming_date'=>$data['upcoming_date'],'updated_date' => $date,'start' =>  $start_time, 'end'=>$end_time, 'title'=>ucfirst($title),'class_status'=>$data['class_status']);
                    $this->db->where('id', $class_id);
                    $this->db->update($table, $data2); 
                } 
            }else{
                if($data['recurring'] !='no'){
                    for($i=1; $i<=(int)$data['no_occ']; $i++){
                        if($i != 1){
                            $next_date_st = strtotime($next_date);
                            switch($data['recurring']){
                                case 'daily':
                                    $next_date = date('Y-m-d H:i:s', strtotime('+1 day',$next_date_st));
                                break;
                                case 'weekly':
                                    $next_date = date('Y-m-d H:i:s', strtotime('+1 week', $next_date_st));
                                break;
                                case 'bi-weekly':
                                    $next_date = date('Y-m-d H:i:s', strtotime('+14 day',$next_date_st));
                                break;
                                case 'monthly':
                                    $next_date = date('Y-m-d H:i:s', strtotime('+1 months',$next_date_st));
                                break;
                                default:    
                                $next_date = null;
                            }
                        }else{
                            $next_date = $data['upcoming_date'];
                        }
                        $data['class_status'] = $class_id != 0 ? 'Reschedule' : 'Active';
                        $data2 = array_merge($data, array('unique_rec_id'=> $unique_id,'upcoming_date'=>$next_date,'user_id' => $user_id, 'added_date' => $date,'updated_date' => $date,'start' =>  $start_time, 'end'=>$end_time, 'title'=>ucfirst($title)));   
                        if ($this->db->insert($table, $data2)) {
                            $main_id = $this->db->insert_id();
                        }else{
                            echo $this->db->error()["message"];
                        }
                    }
                }else{
                    $next_date = $data['upcoming_date'];
                    $data['class_status'] = $class_id != 0 ? 'Reschedule' : 'Active';
                    $data2 = array_merge($data, array('unique_rec_id'=> $unique_id,'upcoming_date'=>$next_date,'user_id' => $user_id, 'added_date' => $date,'updated_date' => $date,'start' =>  $start_time, 'end'=>$end_time, 'title'=>ucfirst($title)));   
                    $this->db->insert($table, $data2);
                }  
            }
        }else if ($table == 'contact_us') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'document_upload' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
               
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        } 
        else if ($table == 'badges') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'image' => $image_main_files));
            if ($table == '') {
                $table = "form_build";
            }
            if ($this->db->insert($table, $data2)) {
               
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        } 
        else if ($table == 'class_location') {
            $data2 = array_merge($data, array('user_id' => $user_id, 'added_date' => $date, 'updated_date' => $date,  'image' => $image_main_files));
          
            if ($this->db->insert($table, $data2)) {
               
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }
        else if($table == 'child_quiz_result'){ 
            $user_id = $data['user_id'];
            $mainQuizId = $data['main_quiz_id'];
            if( $user_id ){
                $query_result = $this->db->query("select id,firebase_token,student_id from users where id = '$user_id' and status != '2'")->row_array();
                $main_quiz = $this->db->query("select name from main_quiz where id = '$mainQuizId' and status != '2'")->row_array();
                $course_id =  $main_quiz['course_id'];
              
                if(count($main_quiz)> 0){
                    $course = $this->db->query("select name from courses where id = '$course_id' and status != '2'")->row_array();
                    $courseName = $course['name'];
                    $name = $main_quiz['name'];
                }else{
                    $name = '';
                    $courseName = '';
                }

                if(!empty($query_result) && $query_result['firebase_token']) {
                    $query_result['title'] = 'Results are out for '.$name;
                    $query_result['body'] = 'There is a new announcement';
                    $query_result['type'] = 2;
                    $query_result['notification_type'] = 'quiz';
                    $query_result['main_quiz_id'] = $data['main_quiz_id'];
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
             
                if(!empty($response)){
                    $student_id = $query_result['student_id'];
                    $child_parent_rel = $this->db->query("select id from child_parent_relationship where child_id = '$student_id' and status != '2'")->row_array();
                    if(!empty($data['main_quiz_id'])){
                      $main_quiz_id = $data['main_quiz_id'];
                    }else{
                        $main_quiz_id = 0;
                    }
                  //  date_default_timezone_set('Asia/Kolkata');
                    $data2=array();
                    $data2['type'] = 0;
                    $data2['main_id'] = $main_quiz_id;
                    $data2['user_id'] = $data['user_id'];
                    $data2['notifier_id'] = $data['user_id'];
                    $data2['sender_id'] = $user_id;
                    $data2['receiver_id'] = $data['user_id'];
                    $data2['notification_type'] = 'quiz';
                    $data2['added_date'] = $date;
                    $data2['updated_date'] = $date;
                    $data2['is_read'] = 0;
                    $data2['status'] = 0;
                    $this->db->insert('notification', $data2);
                }
            }
         
            $data['added_date'] = $date;
            $data['updated_date'] = $date;
            $this->db->insert($table,$data);
            }else {
            
            if(!empty($data['user_id'])){
                $admin_user_id = 0;
                $user_id = 0;
            }
           
            $data2 = array_merge($data, array('user_id' => 0,'active_status'=> 0, 'added_date' => $date, 'updated_date' => $date, 'updated_date' => $date));
            if ($table == '') {
                $table = "form_build";
            }

            if($table === 'main_quiz' ) {
             
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
                  
            }
            if($table === 'religion') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $data2 = array_merge($data, array('user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
                  
            }

            if($table === 'nationlity') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $data2 = array_merge($data, array('user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
            }

            if($table === 'state') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $data2 = array_merge($data, array('user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
            }

            if($table === 'country') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $data2 = array_merge($data, array('user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
            }

            if($table === 'course_type') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $data2 = array_merge($data, array('user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
            }

            if($table === 'city') {
                unset($data['match']);
                unset($data['id']);
                unset($data['table']);
                $country_id= $data['country_id'];
                $state_id= $data['state_id'];
                $state = $this->db->query("select name from state where id = '$state_id' and status != '2'")->row_array();
                $country = $this->db->query("select name from country where id = '$country_id' and status != '2'")->row_array();
                $data2 = array_merge($data, array('wikiDataId'=>'', 'longitude'=>'', 'latitude'=>'','country_name'=>$country['name'],'state_name'=> $state['name'],'country_code'=>'','state_code'=>'','user_id' => 0, 'added_date' => $date, 'updated_date' => $date));
                $this->db->insert($table, $data2);
                $lastInsertedId = $this->db->insert_id(); 
                  
            }

            if ($lastInsertedId > 0) {
                return $lastInsertedId;   
            }else{
                return array("message" => $error = $this->db->error()["message"]);
            }
        }

        if($table== 'upcoming_classes'){
            return $main_id;   
        }else{
            return $this->db->insert_id();
        }
    } catch (Exception $e) {
       return array("message" => $e->getMessage());
    }
 }

    public function check_exists($name, $data) {
        $name_exp = array_filter(explode(",", $name));
        $table = $data['table'];
        $sql = "";
        if (!empty($name_exp)) {
            $i = 0;
            foreach ($name_exp as $names) {
                if ($i == 0) {
                    if ($data['type'] != '') {
                        $type = $data["type"];
                        $sql .= "type='$type' and ";
                    }
                }
                if ($names != '') {
                    $mdata = $data[$names];
                    $sql .= $names . "='$mdata' and ";
                }
                $i++;
            }
        }
        $sql = rtrim($sql, 'and ');

        $query = $this->db->query("select count(1) as cnt from `$table` where 1 and $sql")->result_array();

        $query[0]['cnt'];
    }

    public function edit_data($data, $user_id, $image_main_files = null) {
        $table = $data['table'];
        $date = date("Y-m-d H:i:s");
        $id = $data['id'];
        if ($table == 'users') {
            
            $maintables = $data['maintable'];
            unset($data['maintable']);

            $url_id = $data['url_id'];
            unset($data['url_id']);

            $type = $data['type'];
            $username = $data['username'];
            $firebase_token = $data['firebase_token'];

            $image = $data['image'];
            $student_id = $data['student_id'];
            $course = $data['course'];
            $password = $data['password'];
            $full_name = $data['full_name'];
            $alias_name = $data['alias_name'];
            $dob= $data['dob'];
            $age = $data['age'];
            $race= $data['race'];
            $dialect = $data['dialect'];
            $nric = $data['nric'];
            $nationlity = $data['nationlity'];
            $address = $data['address'];
            $country = $data['country'];
            $city = $data['city'];
            $state = $data['state'];
            $verify_number = $data['verify_number'];
            $number = $data['number'];
            $contact_email = $data['contact_email'];
            $social_email = $data['social_email'];
            $id = $data['id'];
            $added_date = $date;
            
            $data_for_query = array(
                'type' => $type,
                'username' => $username,
                'user_step' => '7',
                'firebase_token' => $firebase_token,
                'image' => $image,
                'student_id' => $student_id,
                'course' => $course,
                'updated_at' => 'now()',
                'full_name' => $full_name,
                'alias_name' => $alias_name,
                'dob' => $dob,
                'age' => $age,
                'race' => $race,
                'dialect' => $dialect,
                'nric' => $nric,
                'nationlity' => $nationlity,
                'address' => $address,
                 'country' => $country,
                'city' => $city,
                'state' => $state,
                'verify_number' => $verify_number,
                'contact_number' => $number,
                'contact_email' => $contact_email,
                'social_email' => $social_email
                    
            );
            if ($password !== '')
            {
                $data_for_query['password'] = $password;
            }
            
            $this->db->where('id', $id);
            $this->db->update($table, $data_for_query);

            $del = $this->db->query("delete from url_feature_access where user_id='$id'");
     
            foreach ($data['url_ids'] as $url_ids) {
                foreach ($data['access_type_' . $url_ids] as $access_type_name) {
                    $del = $this->db->query("insert into url_feature_access set "
                            . "`user_id`='$id',`url_id`='$url_ids',`access_type`='$access_type_name',`added_date`=NOW()");
                }
            }
          
        } 
        else if ($table == 'slider') {
            $type = $data['type'];
            $name = $data['name'];
            $description = $data['description'];
            $updated_date = $date;
            $link =  $data['link'];
            $offer =  $data['offer'];
            $data2 = array_merge(array('offer' => $offer,  'link' => $link, 'user_id' => $user_id, 'name' => $name, 'description' => $description, 'type' => $type,'added_date' => $date, 'updated_date' => $date, 'image' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } else if ($table == 'child_parent_relationship') {
            unset($data['table']);
            unset($data['heading']);
            unset($data['match']);
            

            $child_id = $data['child_id'];
            $id = $data['id'];
            $request_status = $data['request_status'];

            foreach ($child_id as $ids ){
                
            $student = $ids;
            $data2 = array_merge( array('user_id' => $user_id,'request_status' => $request_status, 'updated_date' => $date,'child_id' => $student));
     
            $this->db->where('id', $id);   
            $this->db->update($table, $data2);
            }
        } 
        else if ($table == 'courses') {
            $type = $data['type'];
            $course_type_id=$data['course_type_id'];
            // $data['lecture_days'] = implode(",", $data['lecture_days']);
            $data2 = array_merge(array('user_id' => $user_id,'course_type_id'=>$course_type_id,'type' => $type,'image' => $image_main_files),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        else if ($table == 'homework') {
            $data['exercise_id'] = implode(",", $data['exercise_id']);
            unset($data['chapter_id']);
            unset($data['image']);
            $hk_date = $this->getHighestExerciseDateline($data['exercise_id']);
            $data2 = array_merge(array('user_id' => $user_id, 'hk_date' => $hk_date, 'updated_date' => $date, 'homework_material' => $image_main_files),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        else if ($table == 'course_gallery_folders') {
            $updated_date = $date;
            $data2 = array_merge(array('updated_at' => $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        else if ($table == 'announcement') {
            $updated_date = $date;
            $data2 = array_merge(array('updated_at' => $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }  
        else if ($table == 'course_gallery') {
            unset($data['course_id']);
            unset($data['image']);
            $updated_date = $date;
            $gallery_folder_id = $data['gallery_folder_id'];
            $alreadyExist = $this->db->query("SELECT * FROM course_gallery WHERE gallery_folder_id = $gallery_folder_id AND status != '2'")->result_array();
            if(count($alreadyExist) > 0) {
                return 'exist';
            }
            $data2 = array_merge(array('gallery' => $image_main_files, 'updated_at' => $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        else if ($table == 'course_certificate') {
            $updated_date = $date;
            $data2 = array_merge(array('certificate' => $image_main_files, 'updated_at' => $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        else if ($table == 'child_homework') {
            unset($data['course_id']);
            unset($data['table']);
            unset($data['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data);
        } 
        else if ($table == 'news') {
            unset($data['image']);
            $data2 = array_merge(array('user_id' => $user_id,'news_image' => $image_main_files,'updated_date' => $date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        else if ($table == 'tutorial_subscription_plan') {
            unset($data['image']);
            $data2 = array_merge(array('bg_image' => $image_main_files,'updated_at' => $date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 

        else  if ($table == 'badges') {
            $type = $data['type'];
            $title = $data['sub_title'];
            $data2 = array_merge(array('user_id' => $user_id, 'sub_title' => $title, 'type' => $type, 'image' => $image_main_files, 'updated_date' => $date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        
        else if ($table == 'course_material') {
            $type = $data['type'];
            $title = $data['title'];
            $material_type = $data['material_type'];
            $updated_date = $date;
            $data2 = array_merge(array('user_id' => $user_id, 'type' => $type,'title'=>$title,'material_type'=>$material_type, 'material_content' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }    
        
        else if ($table == 'events') {
            $type = $data['type'];
            $data2 = array_merge($data,array('user_id' => $user_id, 'type' => $type, 'image' => $image_main_files, 'updated_date' => $date));
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
        
        else if ($table == 'contact_us') {
            $type = $data['type'];
            $updated_date = $date;
            $data3 = array_merge(array('user_id' => $user_id, 'type' => $type, 'document_upload' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data3);
        } 

        else if ($table == 'tr_homework_doc') {
            $type = $data['type'];
            $updated_date = $date;
            $data2 = array_merge(array('user_id' => $user_id, 'type' => $type, 'doc' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }

        
       else  if ($table == 'course_type') {
            $type = $data['type'];
            $name = $data['name'];
            $updated_date = $date;
            $data2 = array_merge(array('updated_date' => $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        
        else if ($table == 'quiz_options') {
            unset($data2['match']);
            $type = $data['type'];
            $updated_date = $date;
            $data2 = array_merge(array('user_id' => $user_id, 'type' => $type, 'image' => $image_main_files, 'updated_date'=> $updated_date),$data);
            unset($data2['id']);
            unset($data2['table']);
            unset($data2['match']);
            unset($data2['main_quiz_id']);
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        
        else if ($table == 'get_started') {
            $type = $data['type'];
            $updated_date = $date;
            $data2 = array_merge(array('user_id' => $user_id, 'type' => $type, 'url' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 

        else if ($table == 'ch_homework_doc') {
            $type = $data['type'];
            $updated_date = $date;
            $data2 = array_merge(array('user_id' => $user_id, 'type' => $type, 'doc' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        } 
         

        else if ($table == 'users') {
            $type = $data['type'];
            $updated_date = $date;
            $data2 = array_merge(array( 'type' => $type, 'image' => $image_main_files));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        
  
       else  if ($table == 'payment') {
            $type = $data['type'];
            $name=$data['name'];
            $updated_date = $date;
            $data2 = array_merge(array( 'type' => $typen,'name'=>$name));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        else  if ($table == 'add_course') {
            $childId = $data['user_id'];
            $courseId = $data['change_course_id'];
            return $this->offlineCoursePurchase($childId, $courseId, $date);
        }else if ($table == 'add_account') {
            $table = 'users';
            $updated_date = $date;
            $data2 = array_merge(array( 'image' => $image_main_files,'updated_at' =>  $updated_date), $data);
            $id = $data['id'];
            unset($data2['table']);
            unset($data2['id']);
            unset($data2['match']);
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
        else {
            unset($data['table']);
            unset($data['heading']);
            unset($data['match']);
            $data2 = array_merge($data,array('updated_date' => $date));
            $id = $data['id'];
            $this->db->where('id', $id);
            $this->db->update($table, $data2);
        }
    }

    public function delete_data($data) {
        $id = $data['id'];
        if($data['table'] == 'users'){
           $this->delete_user($data);
        }elseif($data['table'] == 'announcement'){
            $this->deleteAnnouncement($data);
        }elseif($data['table'] == 'add_course'){
            $this->deleteOfflinePaidCourse($data);
        }elseif($data['table'] == 'events'){
            $this->deleteEvent($data);
        }elseif($data['table'] == 'add_account'){
            $data['table'] = 'users';
            $delete_column = $data['delete_column'];
            $status = array("status" => 2);
            $this->db->where($data['delete_column'], $data['id']);
            $this->db->update($data['table'], $status); 
        }elseif($data['table'] == 'courses'){
            $course_id = $data['id'];
            $resChapter = $this->db->query("SELECT * FROM chapter WHERE course_id = '$course_id' and status= '0'")->result_array();
            $resMyCart = $this->db->query("SELECT * FROM mycart WHERE course_id = '$course_id' and is_paid = '2' and status= '0'")->result_array();
            if(count($resMyCart) < 1 && count($resChapter) < 1 ){
                $delete_column = $data['delete_column'];
                $status = array("status" => 2);
                $this->db->where($data['delete_column'], $data['id']);
                $this->db->update($data['table'], $status); 
            }else{
                return 'Entity In Use!';
            }
        }else{ 
            $delete_column = $data['delete_column'];
            $status = array("status" => 2);
            $this->db->where($data['delete_column'], $data['id']);
            $this->db->update($data['table'], $status); 
        }
       
    }

    public function delete_user($data=null){
    //     $res = $this->db->query("SELECT * FROM information_schema.columns WHERE column_name = 'user_id'")->result_array();
    //    foreach($res as $list){
    //      $table =  $list['TABLE_NAME'];
    //        $check = $this->db->query("SELECT * FROM information_schema.columns WHERE table_name = '$table' AND column_name = 'status'")->result_array();
    //           if(count($check) > 0){
    //              $status = array("status" => 2);
    //               $this->db->where('user_id', $data['id']);
    //               $this->db->update($list['TABLE_NAME'], $status); 
    //           }    
    //    }
        $id = $data['id'];
        $delete_column = $data['delete_column'];
        $status = array("status" => 2);
        $this->db->where($data['delete_column'], $id);
        $this->db->update($data['table'], $status);
    }

    private function deleteAnnouncement($data){
        $id = $data['id'];
        $delete_column = $data['delete_column'];
        $status = array("status" => 2);
        $this->db->where($data['delete_column'], $id);
        $this->db->update($data['table'], $status);

        $this->db->query("UPDATE notification SET status = '2' WHERE main_id = $id AND notification_type = 'All_Announcement'");
    }

    private function deleteOfflinePaidCourse($data){
        $id = $data['id'];
        $this->db->query("UPDATE main_cart SET status = '2' WHERE id = $id");
        $this->db->query("UPDATE mycart SET status = '2' WHERE main_cart_id = $id");
        $this->db->query("UPDATE billing_address SET status = '2' WHERE cart_id = $id");
        $this->db->query("UPDATE course_subscription SET status = '2' WHERE cart_id = $id");
    }

    private function deleteEvent($data){
        $id = $data['id'];
        $delete_column = $data['delete_column'];
        $status = array("status" => 2);
        $this->db->where($data['delete_column'], $id);
        $this->db->update($data['table'], $status);

        $this->db->query("UPDATE notification SET status = '2' WHERE main_id = $id AND notification_type = 'All_Event'");
    }

    private function getHighestExerciseDateline($exerciseIds) {
        $exercises = $this->db->query("SELECT added_date FROM course_exercise WHERE id IN($exerciseIds) and status != '2'")->result_array();
        $dates = array();
        for ($i=0; $i < count($exercises); $i++) { 
            array_push($dates, $exercises[$i]['added_date']);
        }

        $datetime_objects = array_map(function($date) {
            return DateTime::createFromFormat('Y-m-d H:i:s', $date);
        }, $dates);

        $largest_date = max($datetime_objects);
        $largest_date_string = $largest_date->format('Y-m-d');

        return $largest_date_string;
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
    
        $classes = $this->db->query("select * from upcoming_classes where master_class_id in($masterClassesAllIds) and status != '2';")->result_array();
        if(count($classes) === 0) {
           return array('timeSlots' => [],'days' => []);        
        }
        
        $timeSlots = [];
        $days = [];
        for ($i = 0; $i < count($classes); $i++) { 
            $classDateTime = $classes[$i]['upcoming_date'];
            $newTimeSlot = date('g:iA', strtotime($classDateTime)).'-'.date('g:iA', strtotime($classDateTime . ' + 1 hour'));
            $classDay = date('l', strtotime($classDateTime));
            array_push($timeSlots, $newTimeSlot);
            array_push($days, $classDay);
        }
    
        return array('timeSlots' => $timeSlots, 'days' => array_values(array_unique($days)));
    }
    
    private function getCourseAllMasterClasses($course_id) {
        return $this->db->query("select a.*,b.chapter_name from master_classes a
        left outer join chapter b on b.id = a.chapter_id
        left outer join courses c on c.id = b.course_id
        where b.course_id = '$course_id' and a.status != '2' and b.status != '2' and c.status != '2'")->result_array();
    }

    private function offlineCoursePurchase($childId, $courseId, $currentDateTime) {
        $date = date("Y-m-d H:i:s");

        $userInfo = $this->db->query("select * from users where id = '$childId' and status != '2'")->result_array();
        if(count($userInfo) === 0) {
            return 'User Not Found!';
        }

        $courseAmountInfo = $this->db->query("select * from courses where id = '$courseId' and status != '2'")->result_array();
        if(count($courseAmountInfo) === 0) {
            return 'Course Not Found!';
        }

        $alreadyPurchased = $this->db->query("select * from mycart where course_id = '$courseId' and child_id = '$childId' and is_paid = '2' and status != '2'")->result_array();
        if(count($alreadyPurchased) > 0) {
            return 'Course Already Purchased!';
        }

        $amount = $cart_amount[0]['amount'];
        $service = $cart_amount[0]['service_cost'];
        $addon = $cart_amount[0]['addon'];
        $tax= $cart_amount[0]['tax'];
        $full_name = $userInfo[0]['full_name'];
        $number = $userInfo[0]['number'];

        $this->db->query("INSERT into main_cart (course_id,quantity,added_date,updated_date,amount,service_cost,addon,tax,is_paid) 
        values('$courseId','1','$date','$date','$amount','$service','$addon','$tax','2')");
        $mainCartId = $this->db->insert_id();
    
        $this->db->query("INSERT into mycart (main_cart_id,amount,service_cost,addon,tax,course_id,item,child_id,is_paid, added_date, updated_date) 
        values('$mainCartId','$amount','$service','$addon','$tax','$courseId','1','$childId','2', '$date','$date')");
        $full_name = ucfirst($full_name);
        $this->db->query("INSERT INTO billing_address (cart_id,payment_option,service_cost,addon_rate,tax,total_payment,full_name,
        company,vat_number,address,email,number,sub_total,payment_status,transaction_id,payment_id,card_id,currency,card_type,payment_method,added_date, updated_date) 
        values ('$mainCartId','','$service','$addon','$tax','','$full_name','','','','','$number','','success','','','','','','Offline','$date','$date')");
        $billingAddressId = $this->db->insert_id();

        $this->db->query("INSERT INTO course_subscription (billing_address_id,cart_id,added_date,updated_date)
        values ('$billingAddressId','$mainCartId','$currentDateTime','$currentDateTime')");
        return 'Course Assigned SuccessFully!';       
    }
    public function guidv4($data = null) {
        // Generate 16 bytes (128 bits) of random data or use the data passed into the function.
        $data = $data ?? random_bytes(16);
        assert(strlen($data) == 16);
    
        // Set version to 0100
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        // Set bits 6-7 to 10
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);
    
        // Output the 36 character UUID.
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
