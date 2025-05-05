<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// not used in cssoft solutions
class Filterreports extends CI_Controller {

    function call_api($url, $data) {
        $url = $url;
        $jsonData = json_encode($data);
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $jsonData,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $dbname = ($_SESSION['dbname']);
        $maindb = $this->load->database($dbname, TRUE);
        $data['validate'] = "name,short_name,type";
        $fullUrl = base_url(uri_string());
        
//        print_r($fullUrl);
        
        if(!empty($_GET['report_type']))
        {
         $data['data']=$_GET;   
         $this->load->view('report/'.$_GET['report_type'], $data);
        }    
        else
        {
        if (strpos($fullUrl, base_url() . 'consignment-reports') !== false) {
           $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='consignment-reports'"
                            . " order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'ac_code') {

                            $form_data_res = array_merge($form_data_res, array("data" => $customer));
                        }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);

//                  exit;
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } else if ($form_data_res['id'] == 'type') {
                        $data['type'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                        $data['report_name'] = "consignment-reports";
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        } 
        else if (strpos($fullUrl, base_url() . 'stock-report-asonlist') !== false) {
           $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='stock-report-asonlist'"
                            . " order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'ac_code') {

                            $form_data_res = array_merge($form_data_res, array("data" => $customer));
                        }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);

//                  exit;
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } else if ($form_data_res['id'] == 'type') {
                        $data['type'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                        $data['report_name'] = "stock-report-asonlist";
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        } 
        $this->load->view('masters/default/master/defaultreports', $data);
        }
    }
    public function fetch_data() {
        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);
        if (empty($requestData)) {
            $requestData = $_POST; 
            $dbname = ($_SESSION['dbname']); 
//            $dbname = ($requestData['dbname']);
        }
        else
        {
             $dbname = ($requestData['db']);
        }
//        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];
        $tables = explode(",", $requestData['table']);
        $table = $tables[0]; 
//        $dbname = ($_SESSION['dbname']);        
        $maindb = $this->load->database($dbname, TRUE);        
        if ($table == 'maincon') {
            $maindb = $this->load->database($dbname, TRUE);        
             $real_querys = "select d.mainid, d.srno, DATE(d.invdate) AS invdate, d.duedays,"
                    . "DATE(d.duedate) as duedate, d.trn_type, d.ac_code, d.id, d.description, d.invoiceno, d.crate, d.salesmanid,"
                    . "d.doc_no, d.totalamt, d.totalamt2, d.totalqty, d.salesqty, d.returnqty, d.balanceqty, d.totalpcs,"
                    . "d.salespcs,d.returnpcs, d.balancepcs, d.customerid, d.slrepid, d.prepby, d.shipby, d.recdby,shipdate, d.mnote,"
                    . "c.name AS currency_name, l.ac_name AS ac_codes FROM $table AS d"
                    . " LEFT JOIN mcurrency c ON c.ID = d.id LEFT JOIN ledgmast l ON l.ac_code = d.ac_code";
            $real_querys2 = "select count(*) as cnt from $table as d "
                   . " ";
            if ($searchValue != '') {
                $search_data2 = ' where ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'description') {
                        $search_data2 .= 'd.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'ac_codes') {
                        $search_data2 .= "l.AC_NAME like '%$searchValue%' OR ";
                    }
                }

                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
//            $real_querys2.=$search_data;   
            }
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';

            if ($requestData['length'] != -1) {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }
           $final_q = $real_querys . ' ' . $query;

            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        } 
        else if ($table == 'mpusltype') {
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "select s.*,trim(b.ac_name) as puac_name,trim(c.ac_name) as slac_name,trim(d.ac_name) as pucommissionname,trim(e.ac_name) as slcommissionname "
                    . " from $table  s left outer join ledgmast b on s.puac_code=b.ac_code left outer join ledgmast c on s.slac_code=c.ac_code left outer join ledgmast d on s.pucommission=d.ac_code  left outer join ledgmast e on s.slcommission=e.ac_code  ";
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
            if ($searchValue != '') {
                $search_data2 = ' where ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'puac_name') {
                        $search_data2 .= "b.ac_name like '%$searchValue%' OR ";
                    } else if ($clm == 'puac_name') {
                        $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
                    } else if ($clm == 'pucommission') {
                        $search_data2 .= "d.ac_name like '%$searchValue%' OR ";
                    } else if ($clm == 'slcommission') {
                        $search_data2 .= "e.ac_name like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }


            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            }

            $query1 = '';

            if ($requestData['length'] != -1) {
                $query = ' OFFSET ' . $requestData['start'] . ' ROWS FETCH NEXT ' . $requestData['length'] . ' ROWS ONLY';
            }
            $final_q = $real_querys . ' ' . $query;
//        exit;

            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        } 
        else if ($table == 'stock') {
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "SELECT 
                a.*,b.color,c.shape,d.clarity,e.cutname,f.polish,g.symetry,h.flour,i.labname,j.girdle1,k.culet,n.category,m.unitname,"
            ." o.ac_name AS supplier,p.size as csize,q.stkname AS localgroup,r.certgroup,s.name as orderby,"
            ." t.stocktype as stocktype,u.fcolor as fcolor,v.fcategory as fcategory"
            ." FROM $table a LEFT OUTER JOIN mcolor b ON a.colorid = b.colorid LEFT OUTER JOIN "
            ." mshape c ON a.shapeid = c.shapeid LEFT OUTER JOIN mclarity d ON a.clarityid = d.clarityid"
            ." LEFT OUTER JOIN mcut e ON a.cutid = e.cutid LEFT OUTER JOIN mpolish f ON a.polishid = f.polishid"
            ." LEFT OUTER JOIN msymetry g ON a.symetryid = g.symetryid LEFT OUTER JOIN mflour h ON a.flourid = h.flourid"
            ." LEFT OUTER JOIN mlab i ON a.labid = i.labid LEFT OUTER JOIN mgirdle1 j ON a.girdle1id = j.girdle1id"
            ." LEFT OUTER JOIN mculet k ON a.culetid = k.culetid LEFT OUTER JOIN mcategory n ON a.categoryid = n.categoryid"
            ." LEFT OUTER JOIN munit m ON a.unitid = m.unitid LEFT OUTER JOIN ledgmast o ON a.supplierid = o.ac_code "
            ." LEFT OUTER JOIN msize p ON a.sizeid = p.sizeid LEFT OUTER JOIN stkcode q ON a.localgrpid = q.ID"
            ." LEFT OUTER JOIN mcertgroup r ON a.certgrpid = r.prikey LEFT OUTER JOIN acgrpname s ON a.orderid = s.code"
            ." LEFT OUTER JOIN mstocktype t ON a.stocktypeid = t.stocktypeid LEFT OUTER JOIN fcolor u ON a.invop_amt = u.fcolorid LEFT OUTER JOIN fcategory v ON a.invop_amt2 = v.fcategoryid";
                        $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
            
             $real_querys.=" where 1 ";
            $real_querys2.=" where 1 ";
            
            if ($searchValue != '') {
                $search_data2 = ' and ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'category') {
                        $search_data2 .= "n.category like '%$searchValue%' OR ";
                    } else if ($clm == 'shape') {
                        $search_data2 .= "c.shape like '%$searchValue%' OR ";
                    } else if ($clm == 'refno')
                    {
                        $search_data2 .= "a.refno like '%$searchValue%' OR ";                    
                    
                    } else if ($clm == 'color')
                    {
                        $search_data2 .= "b.color like '%$searchValue%' OR ";                    
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }

            
                if($requestData['search_data']!='')
            {
                $search_data22=$requestData['search_data']; 
               $real_querys .= " and a.refno like '%$search_data22%' "; 
            }
            

            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }

            $query1 = '';

            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }
            $final_q = $real_querys . ' ' . $query;

            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        }         
        else {
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "select * from $table d ";
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
            
            $real_querys.=" where 1 ";
            $real_querys2.=" where 1 ";
            if ($searchValue != '') {
                $search_data2 = ' and ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
//            echo '<br>';
                    if ($clm != '') {
                        $search_data2 .=   $clm . " like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');

                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }
            
                       
            
            
            //////App Wise Search Data
            if ($requestData['search_data'] != '') {
        $columnsQuery = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table'");
        $columnsToSearch = $columnsQuery->result_array();
        if (!empty($columnsToSearch)) {
            $searchConditions = [];
            $search_data22 = $requestData['search_data'];
            // Loop through each column and build the search condition
            foreach ($columnsToSearch as $column) {
                $column_name = $column['column_name'];
                $searchConditions[] = "$column_name LIKE '%$search_data22%'";
            }
            $real_querys .= ' AND (' . implode(' OR ', $searchConditions) . ')';
        }
    }

            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            }

            $query1 = '';

            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }
            $final_q = $real_querys . ' ' . $query;

            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        }
//        'query' => $final_q,
        $response = array(
            'draw' => intval($requestData['draw']),
            
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'requested' => $requestData,
            'data' => $all_data
        );
        echo json_encode($response);
    }
    public function add_data() {
        $data = $_POST;
        $tables = explode(",", $data['table']);
        $table = $tables[0];
        $table2 = $tables[1];
        $dbname = ($_SESSION['dbname']);
        $maindb = $this->load->database($dbname, TRUE);

        if ($table == 'maincon') {
            $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
            $itemdescription = ($data['itemdescription']);
            $mainid = ($data['mainid']);
            unset($data['mainid']);
            unset($data['itemdescription']);
            $code = ($data['code']);
            unset($data['code']);
            $pcs = ($data['pcs']);
            unset($data['pcs']);
            $qty = ($data['qty']);
            unset($data['qty']);
            $discper = ($data['discper']);
            unset($data['discper']);
            $dcflag = ($data['dcflag']);
            unset($data['dcflag']);
            $tyn_type = ($data['tyn_type']);
            unset($data['tyn_type']);
            $rate = ($data['rate']);
            unset($data['rate']);
            $rate2 = ($data['rate2']);
            unset($data['rate2']);
            $amt = ($data['amt']);
            unset($data['amt']);
            $amt2 = ($data['amt2']);
            unset($data['amt2']);
            $contranid = ($data['contranid']);
            unset($data['contranid']);
            unset($data['match']);
            unset($data['table']);
            unset($data['type']);
            $invdate = ($data['invdate']);
            $timestamp = strtotime($invdate);
            $formattedDateTime2 = date('Y-m-d 00:00:00', $timestamp);
            $duedate = ($data['duedate']);
            $timestamp = strtotime($duedate);
            $formattedDateTime3 = date('Y-m-d 00:00:00', $timestamp);
            unset($data['duedate']);
            unset($data['invdate']);
            $main_tyn_type = $tyn_type[0];
            $final = array_merge($data, array("trn_type" => $main_tyn_type), array("invdate" => $formattedDateTime2), array("duedate" => $formattedDateTime3));

            if ($mainid == '') {
                $maindb->insert($table, $final);
                $insertId = $maindb->insert_id();
            } else {
                $where_condition = array(
                    'mainid' => $mainid,
                );

                $maindb->where($where_condition);
                $maindb->update($table, $final);
                $insertId = $mainid;
            }
            if ($insertId > 0) {
                $i = 0;
                $all_final = array();
                $last_ids = "";
                foreach ($qty as $qty_data) {
                    $fdata = array("mainid" => $insertId);
                    $fdata = array_merge($fdata, array("qty" => $qty_data));
                    $fdata = array_merge($fdata, array("pcs" => $pcs[$i]));
                    $fdata = array_merge($fdata, array("discper" => $discper[$i]));
                    $fdata = array_merge($fdata, array("dcflag" => $dcflag[$i]));
                    $fdata = array_merge($fdata, array("trn_type" => $tyn_type[$i]));
                    $fdata = array_merge($fdata, array("rate" => $rate[$i]));
                    $fdata = array_merge($fdata, array("rate2" => $rate2[$i]));
                    $fdata = array_merge($fdata, array("amt" => $amt[$i]));
                    $fdata = array_merge($fdata, array("amt2" => $amt2[$i]));
                    $fdata = array_merge($fdata, array("description" => ucfirst($itemdescription[$i])));
                    $fdata = array_merge($fdata, array("code" => $code[$i]));
                    $sql1 = '';
                    $sql2 = '';
                    $sql3 = '';
                    if ($insertId != '') {
                        $sql1 .= '"mainid",';
                        $sql2 .= $insertId . ',';
                        $sql3 .= '"mainid"=';
                        $sql3 .= "'" . $insertId . "',";
                    }
                    if ($qty_data != '') {
                        $sql1 .= '"qty",';
                        $sql2 .= $qty_data . ',';

                        $sql3 .= '"qty"=';
                        $sql3 .= "'" . $qty_data . "',";
                    }
                    if ($pcs[$i] != '') {
                        $sql1 .= '"pcs",';
                        $sql2 .= $pcs[$i] . ',';
                        $sql3 .= '"pcs"=';
                        $sql3 .= "'" . $pcs[$i] . "',";
                    }
                    if ($discper[$i] != '') {
                        $sql1 .= '"discper",';
                        $sql2 .= $discper[$i] . ',';
                        $sql3 .= '"discper"=';
                        $sql3 .= "'" . $discper[$i] . "',";
                    }
                    if ($tyn_type[$i] != '') {
                        $sql1 .= '"trn_type",';
                        $sql2 .= "'" . $tyn_type[$i] . "',";
                        $sql3 .= '"trn_type"=';
                        $sql3 .= "'" . $tyn_type[$i] . "',";
                    }
                    if ($rate[$i] != '') {
                        $sql1 .= '"rate",';
                        $sql2 .= $rate[$i] . ',';
                        $sql3 .= '"rate"=';
                        $sql3 .= "'" . $rate[$i] . "',";
                    }
                    if ($rate2[$i] != '') {
                        $sql1 .= '"rate2",';
                        $sql2 .= $rate2[$i] . ',';
                        $sql3 .= '"rate2"=';
                        $sql3 .= "'" . $rate2[$i] . "',";
                    }
                    if ($amt[$i] != '') {
                        $sql1 .= '"amt",';
                        $sql2 .= $amt[$i] . ',';
                        $sql3 .= '"amt"=';
                        $sql3 .= "'" . $amt[$i] . "',";
                    }
                    if ($amt2[$i] != '') {
                        $sql1 .= '"amt2",';
                        $sql2 .= $amt2[$i] . ',';
                        $sql3 .= '"amt2"=';
                        $sql3 .= "'" . $amt2[$i] . "',";
                    }
                    if ($itemdescription[$i] != '') {
                        $sql1 .= '"description",';
                        $sql2 .= "'" . $itemdescription[$i] . "',";
                        $sql3 .= '"description"=';
                        $sql3 .= "'" . $itemdescription[$i] . "',";
                    }
                    if ($code[$i] != '') {
                        $sql1 .= '"code",';
                        $sql2 .= $code[$i] . ',';
                        $sql3 .= '"code"=';
                        $sql3 .= "'" . $code[$i] . "',";
                    }
                    $contranid2 = $contranid[$i];
//            echo '<br>';
                    $sql1 = rtrim($sql1, ',');
                    $sql2 = rtrim($sql2, ',');
                    $sql3 = rtrim($sql3, ',');

                    if (empty($contranid2)) {
                        $sql = 'INSERT INTO CONTRAN (' . $sql1 . ') '
                                . ' VALUES (' . $sql2 . ')';
                        $maindb->query($sql);
                        $insertIds = $maindb->insert_id();
                        $last_ids .= $insertIds . ',';
                    } else {
                        $last_ids .= $contranid2 . ',';
                    }
                    $i++;
                }
            }
            if ($mainid != '') {
                if ($last_ids != '') {
                    $last_ids = rtrim($last_ids, ',');
                    $maindb->query("delete from CONTRAN where contranid NOT IN($last_ids) and mainid='$mainid' ");
                }
            }
            if ($insertId > 0) {
                if ($insertId) {
                    if ($mainid == '') {
                        echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record added successfully'));
                    } else {
                        echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record updated successfully'));
                    }
                } else {
                    echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Failed to add ' . $heading));
                }
            } else {
                $this->MasterModel->edit_data($data, $user_id);
                echo json_encode(array('success' => true, 'type' => '2', 'message' => $heading . ' updated successfully'));
            }
        }
        else {
            
            $match = $data['match'];
            unset($data['type']);
            unset($data['table']);
            unset($data['match']);
            unset($data['headcode']);
            unset($data['id']);
            $columns = $maindb->list_fields($table);
            $idrd = $columns[0];

            $iddata = $data[$idrd];

            $maindb->trans_start(); // Start the transaction

            try {
                if ($match != '') {
                    $name_exp = array_filter(explode(",", $match));
                    if(!empty($name_exp)) {
                        foreach ($name_exp as $names) {
                            if ($names != '') {
                                $maindb->where($names, $data[$names]);
                            }
                        }
                    }
                    $query = $maindb->get($table);
                    $cnt = $query->num_rows();
                    if($cnt > 0) {
                        $maindb->trans_rollback(); // Rollback the transaction if the record exists
                        echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Record already exists!'));
                        exit;
                    }
                }

                if ($iddata == '') {
                    unset($data[$idrd]);
                    $data2 = array_merge($data, array('user_id' => $user_id), array('added_date' => date("Y-m-d H:i:s")));
                    $maindb->insert($table, $data2);
                    echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record added successfully'));
                } else {
                    unset($data[$idrd]);
                    $data2 = array_merge($data, array('user_id' => $user_id), array('updated_date' => date("Y-m-d H:i:s")));
                    $maindb->where($idrd, $iddata);
                    $maindb->update($table, $data2);
                    echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record Updated successfully'));
                }

                $maindb->trans_complete(); // Complete the transaction

                if ($maindb->trans_status() === FALSE) {
                    $maindb->trans_rollback(); // Rollback if any database error occurs
                }
            } catch (Exception $e) {
                $maindb->trans_rollback(); // Rollback in case of any exception
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'An error occurred: ' . $e->getMessage()));
                exit;
            }
        }
    }
    public function edit_data() {

        $heading = $_POST['heading'];
        $match = $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $dbname = ($_SESSION['dbname']);
        $maindb = $this->load->database($dbname, TRUE);
        $tables = explode(",", $data['table']);
        $table = $tables[0];
        $table2 = $tables[1];
        $itemdescription = ($data['itemdescription']);
        $mainid = ($data['mainid']);

        unset($data['mainid']);
        unset($data['itemdescription']);
        $code = ($data['code']);
        unset($data['code']);
        $pcs = ($data['pcs']);
        unset($data['pcs']);
        $qty = ($data['qty']);
        unset($data['qty']);
        $discper = ($data['discper']);
        unset($data['discper']);
        $dcflag = ($data['dcflag']);
        unset($data['dcflag']);
        $tyn_type = ($data['tyn_type']);
        unset($data['tyn_type']);
        $rate = ($data['rate']);
        unset($data['rate']);
        $rate2 = ($data['rate2']);
        unset($data['rate2']);
        $amt = ($data['amt']);
        unset($data['amt']);
        $amt2 = ($data['amt2']);
        unset($data['amt2']);
        unset($data['match']);
        unset($data['table']);
        unset($data['type']);
        $invdate = ($data['invdate']);
        $timestamp = strtotime($invdate);
        $formattedDateTime2 = date('Y-m-d 00:00:00', $timestamp);
        $duedate = ($data['duedate']);
        $timestamp = strtotime($duedate);
        $formattedDateTime3 = date('Y-m-d 00:00:00', $timestamp);
        unset($data['duedate']);
        unset($data['invdate']);
        $main_tyn_type = $tyn_type[0];
        $final = array_merge($data, array("trn_type" => $main_tyn_type), array("invdate" => $formattedDateTime2), array("duedate" => $formattedDateTime3));
        $maindb->insert($table, $final);
        $insertId = $maindb->insert_id();
        if ($insertId > 0) {
            $i = 0;
            $all_final = array();
            foreach ($qty as $qty_data) {
                $fdata = array("mainid" => $insertId);
                $fdata = array_merge($fdata, array("qty" => $qty_data));
                $fdata = array_merge($fdata, array("pcs" => $pcs[$i]));
                $fdata = array_merge($fdata, array("discper" => $discper[$i]));
                $fdata = array_merge($fdata, array("dcflag" => $dcflag[$i]));
                $fdata = array_merge($fdata, array("trn_type" => $tyn_type[$i]));
                $fdata = array_merge($fdata, array("rate" => $rate[$i]));
                $fdata = array_merge($fdata, array("rate2" => $rate2[$i]));
                $fdata = array_merge($fdata, array("amt" => $amt[$i]));
                $fdata = array_merge($fdata, array("amt2" => $amt2[$i]));
                $fdata = array_merge($fdata, array("description" => ucfirst($itemdescription[$i])));
                $fdata = array_merge($fdata, array("code" => $code[$i]));
                $sql1 = '';
                $sql2 = '';
                if ($insertId != '') {

                    $sql1 .= '"mainid",';
                    $sql2 .= $insertId . ',';

                    $sql3 .= '"mainid"="' . $insertId . '",';
                }
                if ($qty_data != '') {
                    $sql1 .= '"qty",';
                    $sql2 .= $qty_data . ',';
                    $sql3 .= '"qty"="' . $qty_data . '",';
                }
                if ($pcs[$i] != '') {
                    $sql1 .= '"pcs",';
                    $sql2 .= $pcs[$i] . ',';
                    $sql3 .= '"pcs"="' . $pcs[$i] . '",';
                }
                if ($discper[$i] != '') {
                    $sql1 .= '"discper",';
                    $sql2 .= $discper[$i] . ',';
                    $sql3 .= '"discper"="' . $discper[$i] . '",';
                }
                if ($tyn_type[$i] != '') {
                    $sql1 .= '"trn_type",';
                    $sql2 .= "'" . $tyn_type[$i] . "',";
                    $sql3 .= '"trn_type"="' . $tyn_type[$i] . '",';
                }
                if ($rate[$i] != '') {
                    $sql1 .= '"rate",';
                    $sql2 .= $rate[$i] . ',';
                    $sql3 .= '"rate"="' . $rate[$i] . '",';
                }
                if ($rate2[$i] != '') {
                    $sql1 .= '"rate2",';
                    $sql2 .= $rate2[$i] . ',';
                    $sql3 .= '"rate2"="' . $rate2[$i] . '",';
                }
                if ($amt[$i] != '') {
                    $sql1 .= '"amt",';
                    $sql2 .= $amt[$i] . ',';
                    $sql3 .= '"amt"="' . $amt[$i] . '",';
                }
                if ($amt2[$i] != '') {
                    $sql1 .= '"amt2",';
                    $sql2 .= $amt2[$i] . ',';
                    $sql3 .= '"amt2"="' . $amt2[$i] . '",';
                }
                if ($itemdescription[$i] != '') {
                    $sql1 .= '"description",';
                    $sql2 .= "'" . $itemdescription[$i] . "',";
                    $sql3 .= '"description"="' . $itemdescription[$i] . '",';
                }
                if ($code[$i] != '') {
                    $sql1 .= '"code",';
                    $sql2 .= $code[$i] . ',';
                    $sql3 .= '"code"="' . $code[$i] . '",';
                }
                $sql1 = rtrim($sql1, ',');
                $sql2 = rtrim($sql2, ',');
                $sql3 = rtrim($sql3, ',');

                if ($mainid == '') {
                    $sql = 'INSERT INTO CONTRAN (' . $sql1 . ') '
                            . ' VALUES (' . $sql2 . ')';
                } else {
                    echo $sql3;

                    exit;
                    $sql = 'INSERT INTO CONTRAN (' . $sql1 . ') '
                            . ' VALUES (' . $sql2 . ')';
                }
                $maindb->query($sql);
                $all_final[] = $fdata;
                $i++;
            }
        }


        if ($insertId > 0) {
            if ($insertId) {
                echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record edited successfully'));
            } else {
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Failed to update ' . $heading));
            }
        } else {

            echo '2';

            exit;
            $this->MasterModel->edit_data($data, $user_id);
            echo json_encode(array('success' => true, 'type' => '2', 'message' => $heading . ' updated successfully'));
        }
    }
    public function delete_data() {
        $this->load->model('MasterModel');
        $data = $_POST;
        $dbname = ($_SESSION['dbname']);
        $data2 = array("db" => $dbname
            , "requestData" => $data
        );
        $maindb = $this->load->database($dbname, TRUE);
        $tables = explode(",", $data['table']);
        $delete_column = $data['delete_column'];
        $id = $data['id'];
        $delete_column = $data['delete_column'];

        $table = $tables[0];
        $table2 = $tables[1];
        $columns = $this->db->list_fields($table);
        $idrd = $columns[0];
        $maindb->query("delete from $table where $idrd='$id'");

        echo json_encode(array('success' => true, 'message' => ' deleted successfully'));
    }
}
