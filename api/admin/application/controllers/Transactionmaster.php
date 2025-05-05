<?php

defined('BASEPATH') OR exit('No direct script access allowed');
// this file is not used by cssoft 
class Transactionmaster extends CI_Controller {

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
//        $currency_name1 = ($_SESSION['currency_name1']);
//        echo '<pre>'; 
//        print_r($_SESSION);
//        exit;
        
        $maindb = $this->load->database($dbname, TRUE);
        $data['validate'] = "name,short_name,type";
        $fullUrl = base_url(uri_string());
        if (strpos($fullUrl, base_url() . 'consignment-outward') !== false) {
            $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
//            print_r('bhairu');
//                  $real_querys = "SELECT AC_NAME as name,AC_CODE as id FROM LEDGMAST ORDER BY AC_NAME";
//                  $customer=$maindb->query($real_querys)->result_array();                   
            $real_querys = "select a.code AS id, a.descr, a.rapprice,a.refno AS name,a.cl_qty AS conclqty,b.shape,c.size,"
                    . "d.color,e.clarity FROM stock a LEFT JOIN mshape b ON a.shapeid=b.shapeid LEFT JOIN msize c "
                    . "ON a.sizeid=c.sizeid LEFT JOIN mcolor d ON a.colorid=d.colorid LEFT JOIN mclarity e ON "
                    . "a.clarityid=e.clarityid WHERE a.cl_qty > 0 ORDER BY a.rapprice DESC";
            $stock_no = $maindb->query($real_querys)->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='consignment-outward' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id') {
                        unset($form_data_res['data']);
//                  if($form_data_res['id']=='ac_code')
//                  {            
//                  $form_data_res= array_merge($form_data_res,array("data"=>''));
//                  }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    }
                     else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    } 
                    else if ($form_data_res['id'] == 'plus_minus') {
                        $currency = array();
                        $currency[] = array("id" => "1", "name" => "-");
                        $currency[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $currency, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    } 
                    else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }
        else if (strpos($fullUrl, base_url() . 'proforma-invoice') !== false) {
            $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
            $real_querys = "select a.code AS id, a.descr, a.rapprice,a.refno AS name,a.cl_qty AS conclqty,b.shape,c.size,"
                    . "d.color,e.clarity FROM stock a LEFT JOIN mshape b ON a.shapeid=b.shapeid LEFT JOIN msize c "
                    . "ON a.sizeid=c.sizeid LEFT JOIN mcolor d ON a.colorid=d.colorid LEFT JOIN mclarity e ON "
                    . "a.clarityid=e.clarityid WHERE a.cl_qty > 0 ORDER BY a.rapprice DESC";
            $stock_no = $maindb->query($real_querys)->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='proforma-invoice' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id') {
                        unset($form_data_res['data']);
//                  if($form_data_res['id']=='ac_code')
//                  {            
//                  $form_data_res= array_merge($form_data_res,array("data"=>''));
//                  }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    }else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
                        $perameters[] = $form_data_res;
                    }  else if ($form_data_res['id'] == 'delete_id') {
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    } else if ($form_data_res['id'] == 'plus_minus') {
                        $currency = array();
                        $currency[] = array("id" => "1", "name" => "-");
                        $currency[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $currency, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }
///Added by bhairu code        
        else if (strpos($fullUrl, base_url() . 'ledger-creation') !== false) {
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='ledger-creation' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller')
                    {
                        $data['master'] = $form_data_res['data'];
//                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                    } else if ($form_data_res['id'] == 'oprt') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res; 
                    }
                    else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id') 
                    {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'ac_code') {
                            $form_data_res = array_merge($form_data_res, array("data" => ''));
                        }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } 
                    else if ($form_data_res['id'] == 'delete_id') {
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
                if ($part == '1') {
                    $perameters3[] = $form_data_res;
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
                if ($part == '11') {
//                    if ($form_data_res['id'] == 'actype') {
//                        $actype1 = array();
//                        $actype1[] = array("id" => "1", "name" => "Cash");
//                        $actype1[] = array("id" => "2", "name" => "Bank");
//                        $actype1[] = array("id" => "3", "name" => "Broker");
//                        $actype1[] = array("id" => "4", "name" => "Other");
//                        $form_data_res = array_merge($form_data_res, array("data" => $actype1));
//                        $perameters11[] = $form_data_res;
//                    }  
//                    if ($form_data_res['id'] == 'class') {
//                        $class = array();
//                        $class[] = array("id" => "1", "name" => "Customer");
//                        $class[] = array("id" => "2", "name" => "Supplier");
//                        $class[] = array("id" => "3", "name" => "Other");
//                        $form_data_res = array_merge($form_data_res, array("data" => $class));
//                        $perameters11[] = $form_data_res;
//                    }
                      if ($form_data_res['id'] == 'oprt') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters11[] = $form_data_res; 
                    }
                    else {
                        $perameters11[] = $form_data_res;
                    }
                }
                if ($part == '12') { 
                    if ($form_data_res['type'] == 'birthday_details') {
                        $message2 = $this->load->view('extra/loadbirthdaysreminder', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters12[] = $form_data_res;
                    }
                     else if ($form_data_res['id'] == 'ship_addcheck') {
                        unset($form_data_res['data']);
                        $ry=array();
                        $ry[]=array("id"=>"1","name"=>"Same as Registered Address?");
                        $form_data_res = array_merge($form_data_res, array("data" => $ry));
                        $perameters12[] = $form_data_res;                   
                    }
                    else if ($form_data_res['type'] == 'ledger_contact_details') { 
                        
                        $message2 = $this->load->view('extra/ledger_contact_detal', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters12[] = $form_data_res;
                    } 
                    
                    else {
                        $perameters12[] = $form_data_res;
                    }
                }
                if ($part == '13') {
                    if ($form_data_res['type'] == 'bankdtl_details') {

                        $message2 = $this->load->view('extra/lodbankdetilsreminder', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters13[] = $form_data_res;
                    } else {
                        $perameters13[] = $form_data_res;
                    }
                }
                if ($part == '14') {
                    if ($form_data_res['type'] == 'file_details') {
                        $message2 = $this->load->view('extra/ledger_document_party', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters14[] = $form_data_res;
                    } else {
                        $perameters14[] = $form_data_res;
                    }
//                    $perameters14[] = $form_data_res;
                }
                if ($part == '15') {
                    $perameters15[] = $form_data_res;
                }
            }
            if ($form_data_res['id'] == 'wizard') {
                unset($form_data_res['data']);
                $data['type'] = "ledger-creation";
                $data['part1'] = $perameters11;
                $data['part2'] = $perameters12;
                $data['part3'] = $perameters13;
                $data['part4'] = $perameters14;
                $data['part5'] = $perameters15;
                $message2 = $this->load->view('extra/load_ledger_creation_wizard', $data, true);
                $form_data_res = array_merge($form_data_res, array("data" => $message2));
                $perameters[] = $form_data_res;
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        } 
        else if (strpos($fullUrl, base_url() . 'purchase') !== false) {            
        $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='purchase' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                        
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                        if ($form_data_res['id'] == 'id') {
                            
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    } 
                    else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
                        $perameters[] = $form_data_res;
                    } 
//                    else if ($form_data_res['id'] == 'passbrokerage') {
//                        unset($form_data_res['data']);
//                        $currency=array();
//                        $currency[]=array("id"=>"1","name"=>"Set Brokarage?");
////                        $curDate = date("Y-m-d"); 
////                        print_r($currentDate);
//                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
//                        $perameters[] = $form_data_res;
//                    } 
                        else if ($form_data_res['id'] == 'delete_id') {
                        $data['delete_id'] = $form_data_res['data'];
                    } 
                    else if ($form_data_res['id'] == 'maindisplay') {
                        $data['display'] = (explode(",", $form_data_res['data']));
                        $data['edits'] = $data['display2'];
                    } 
                    else if ($form_data_res['id'] == 'type') {
                        $data['type'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maindisplay2') {
                        $data['display2'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'edits') {
                        $data['edits'] = (explode(",", $form_data_res['data']));
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     }else if ($form_data_res['type'] == 'charges_details') {
                        $message2 = $this->load->view('extra/loadothercharges', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    }    
                         else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    }  else {
                        $perameters[] = $form_data_res;
                    }
                }
                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
//                   echo '<pre>';   
//                   print_r($api_res); 
//                  exit;     
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    } else if ($form_data_res['id'] == 'plus_minus') {
                        $currency = array();

                        $currency[] = array("id" => "1", "name" => "-");
                        $currency[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $currency, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }        
        else if (strpos($fullUrl, base_url() . 'prtransaction') !== false) {            
        $data['prdetails_ajax']="1";    
        $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
            $form_data = $this->db->query("SELECT * FROM `form_build` where master='prtransaction' order by sort ASC")->result_array();
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
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
                    }else if ($form_data_res['type'] == 'charges_details') {
                        $message2 = $this->load->view('extra/loadothercharges', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     } else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    }  else {
                        $perameters[] = $form_data_res;
                    }
                }                
                if ($part == '2') { 
//                    unset($form_data_res['data']);
//                    if ($form_data_res['type']== 'ret_details')
//                    {
//                        $message2 = $this->load->view('extra/prdetails', $data, true);
//                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
//                        $perameters2[] = $form_data_res;
//                    } else {
//                        $perameters2[] = $form_data_res;
//                    } 
                }
                
//                if ($part == '2') {
//
//                    if ($form_data_res['id'] == 'stock_no') 
//                    {
//                        if ($form_data_res['id'] == 'stock_no') {
//                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
//                        }
//                        $perameters2[] = $form_data_res;
//                    } else if ($form_data_res['id'] == 'plus_minus') {
//                        $currency = array();
//                        $currency[] = array("id" => "1", "name" => "-");
//                        $currency[] = array("id" => "2", "name" => "+");
//                        $form_data_res = array_merge($form_data_res, array("data" => $currency, "hide_select" => '1'));
//                        $perameters2[] = $form_data_res;
//                    }else {
//                        $perameters2[] = $form_data_res;
//                    }
//                }
                
                
       
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }                
        else if (strpos($fullUrl, base_url() . 'sales') !== false) {
        $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
        $form_data = $this->db->query("SELECT * FROM `form_build` where master='sales' order by sort ASC")->result_array();
        
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                        if ($form_data_res['id'] == 'id') {
                            unset($form_data_res['data']);
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
                        $perameters[] = $form_data_res;
                    } 
                    else if ($form_data_res['id'] == 'passbrokerage') {
                        unset($form_data_res['data']);
                        $curredr=array();
                        $curredr[]=array("id"=>"1","name"=>"Set Brokarage?");
//                        $curDate = date("Y-m-d"); 
//                        print_r($currentDate);
                        $form_data_res = array_merge($form_data_res, array("data" => $curredr));
                        $perameters[] = $form_data_res;
                    } 
                    else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters[] = $form_data_res;
                    } 
                    else if ($form_data_res['id'] == 'delete_id') {
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     }else if ($form_data_res['type'] == 'charges_details') {
                        $message2 = $this->load->view('extra/loadothercharges', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    }  else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
//                   echo '<pre>';   
//                   print_r($api_res); 
//                  exit;     
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    }
                    else if ($form_data_res['id'] == 'plus_minus') {
                        unset($form_data_res['data']);
                        $currency1 = array();
                        $currency1[] = array("id" => "1", "name" => "-","selected"=>"selected");
                        $currency1[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $currency1));
//                        print_r($form_data_res);
//                        exit;
                        $perameters2[] = $form_data_res;
                    } 
                     else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }        
        else if (strpos($fullUrl, base_url() . 'srtransaction') !== false) {     
       $data['prdetails_ajax']="1";    
        $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
        $form_data = $this->db->query("SELECT * FROM `form_build` where master='srtransaction' order by sort ASC")->result_array();                      
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                        if ($form_data_res['id'] == 'id') {
                            
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $curDate = date("Y-m-d");
                        $form_data_res = array_merge($form_data_res, array("data" => $curDate));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
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
                    }else if ($form_data_res['type'] == 'charges_details') {
                        $message2 = $this->load->view('extra/loadothercharges', $data, true);
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'table') {
                        $data['table'] = $form_data_res['data'];
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     } else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    }  else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
//                   echo '<pre>';   
//                   print_r($api_res); 
//                  exit;     
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    } else if ($form_data_res['id'] == 'plus_minus') {
                        $cr1 = array();
                        $cr1[] = array("id" => "1", "name" => "-");
                        $cr1[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $cr1, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }
        
       else if (strpos($fullUrl, base_url() . 'receipt-voucher') !== false) {     
       $data['voucherdetails_ajax']="1";    
        $curre = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
        $form_data = $this->db->query("SELECT * FROM `form_build` where master='receipt-voucher' order by sort ASC")->result_array();                      
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                       if ($form_data_res['id'] == 'id') 
                       {
                            unset($form_data_res['data']);
                            $form_data_res = array_merge($form_data_res, array("data" => $curre));
                       }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'invdate') {
                        unset($form_data_res['data']);
                        $cDate = date("Y-m-d");
                        $form_data_res = array_merge($form_data_res, array("data" => $cDate));
                        $perameters[] = $form_data_res;
                    }else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     } else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    } else {
                        $perameters[] = $form_data_res;
                    }
                }
                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
    
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
//                  print_r($api_res);                  
//                  exit;
                    }
                    else if ($form_data_res['id'] == 'plus_minus') {
                        $plismins = array();
                        $plismins[] = array("id" => "1", "name" => "-");
                        $plismins[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $plismins, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    }else if ($form_data_res['id'] == 'crate2') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
                        $form_data_res = array_merge($form_data_res, array("data" => $conversion_rate));
                        $perameters2[] = $form_data_res;
                    }
                    else if ($form_data_res['id'] == 'exchgentry') {
                        unset($form_data_res['data']);
                        $cexcentry=array();
                        $cexcentry[]=array("id"=>"1","name"=>"Pass Exchange Diff Entry?");
                        $form_data_res = array_merge($form_data_res, array("data" => $cexcentry));
                        $perameters2[] = $form_data_res;
                    }
                    else if ($form_data_res['id'] == 'actype') {
                        $actype = array();
                        $actype[] = array("id" => "OA", "name" => "On A/C");
                        $actype[] = array("id" => "SL", "name" => "Sales");
                        $actype[] = array("id" => "QR", "name" => "Cheque Received");
                        $actype[] = array("id" => "CS", "name" => "Labour");
                        $form_data_res = array_merge($form_data_res, array("data" => $actype));
                        $perameters2[] = $form_data_res;
                    }
                    else if ($form_data_res['id'] == 'ret_details') {
                        unset($form_data_res['data']);
                        $message2 = $this->load->view('extra/voucherdetails', $data, true);  
                        $form_data_res = array_merge($form_data_res, array("data" => $message2));
                        $perameters2[] = $form_data_res;
                    }
                    else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }
        
        else if (strpos($fullUrl, base_url() . 'stkmixing-transaction') !== false) {           
        $currency = $maindb->query("select Id as id,Name as name,selected from mcurrency")->result_array();
        $transaction = $maindb->query("select prikey as id,typename as name from mpusltype")->result_array();
        $form_data = $this->db->query("SELECT * FROM `form_build` where master='stkmixing-transaction' order by sort ASC")->result_array();                      
            foreach ($form_data as $form_data_res) {
                $part = $form_data_res['part'];
                if ($part == '1') {
                    if ($form_data_res['id'] == 'maincontroller') {
                        $data['master'] = $form_data_res['data'];
                    } else if ($form_data_res['id'] == 'ac_code' || $form_data_res['id'] == 'id' || $form_data_res['id'] == 'pusltypeid') {
                        unset($form_data_res['data']);
                        if ($form_data_res['id'] == 'pusltypeid') {
                            $form_data_res = array_merge($form_data_res, array("data" => $transaction));
                        }
                        if ($form_data_res['id'] == 'id') {
                            $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        }
                        $perameters[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'crate') {
                        unset($form_data_res['data']);
                        $conversion_rate = ($_SESSION['logged_in_user']['conversion_rate']);
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
                    } else if ($form_data_res['id'] == 'maintitle') {
                        $data['title'] = $form_data_res['data'] . "";
                        $data['title2'] = $form_data_res['data'] . " List";
                     } else if ($form_data_res['id'] == 'transaction_type') {
                        $data['transaction_type'] = $form_data_res['data'];
			$perameters[] = $form_data_res;
                    }  else {
                        $perameters[] = $form_data_res;
                    }
                }

                if ($part == '2') {
                    if ($form_data_res['id'] == 'stock_no') {
//                   echo '<pre>';   
//                   print_r($api_res); 
//                  exit;     
                        if ($form_data_res['id'] == 'stock_no') {
                            $form_data_res = array_merge($form_data_res, array("data" => $stock_no));
                        }
                        $perameters2[] = $form_data_res;
                    } else if ($form_data_res['id'] == 'plus_minus') {
                        $curyt1= array();
                        $curyt1[] = array("id" => "1", "name" => "-");
                        $curyt1[] = array("id" => "2", "name" => "+");
                        $form_data_res = array_merge($form_data_res, array("data" => $curyt1, "hide_select" => '1'));
                        $perameters2[] = $form_data_res;
                    } else {
                        $perameters2[] = $form_data_res;
                    }
                }
                if ($part == '3') {
                    $perameters3[] = $form_data_res;
                }
            }
            $data['perameters'] = $perameters;
            $data['perameters2'] = $perameters2;
            $data['perameters3'] = $perameters3;
        }
        $this->load->view('masters/default/master/default', $data);
    }
    public function fetch_data() {
        $jsonData = file_get_contents('php://input');
        $requestData = json_decode($jsonData, true);
        if (empty($requestData)) {
            $requestData = $_POST; 
            $dbname = ($_SESSION['dbname']); 
        }
        else
        {
             $dbname = ($requestData['db']);
        }
//        print_r($jsonData); 
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
	$transaction_type = $requestData['transaction_type'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];
        $tables = explode(",", $requestData['table']);
        $table = $tables[0];
        $maindb = $this->load->database($dbname, TRUE);
        if ($table == 'maincon') {
            
           if (empty($requestData)) {
               $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "select d.mainid, d.srno, DATE(d.invdate) AS invdate, d.duedays,"
                    . "DATE(d.duedate) as duedate, d.trn_type, d.ac_code, d.id, d.description, d.invoiceno, d.crate, d.salesmanid,"
                    . "d.doc_no, d.totalamt, d.totalamt2, d.totalqty, d.salesqty, d.returnqty, d.balanceqty, d.totalpcs,"
                    . "d.salespcs,d.returnpcs, d.balancepcs, d.customerid, d.slrepid, d.prepby, d.shipby, d.recdby,shipdate, d.mnote,"
                    . "c.name AS currency_name, l.ac_name AS ac_codes FROM $table AS d"
                    . " LEFT JOIN mcurrency c ON c.ID = d.id LEFT JOIN ledgmast l ON l.ac_code = d.ac_code";
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
           }
           else 
           {
               if($requestData['child_dataid']==''){ 
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys = "select d.mainid, d.srno, DATE(d.invdate) AS invdate, d.duedays,"
                        . "DATE(d.duedate) as duedate, d.trn_type, d.ac_code, d.id, d.description, d.invoiceno, d.crate, d.salesmanid,"
                        . "d.doc_no, d.totalamt, d.totalamt2, d.totalqty, d.salesqty, d.returnqty, d.balanceqty, d.totalpcs,"
                        . "d.salespcs,d.returnpcs, d.balancepcs, d.customerid, d.slrepid, d.prepby, d.shipby, d.recdby,shipdate, d.mnote,"
                        . "c.name AS currency_name, l.ac_name AS ac_codes FROM $table AS d"
                        . " LEFT JOIN mcurrency c ON c.ID = d.id LEFT JOIN ledgmast l ON l.ac_code = d.ac_code";
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";
               }
               else 
               {
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys .= ("select a.mainid,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM contran a "
                        . "LEFT JOIN maincon b ON a.mainid=b.mainid "
                        . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code");                
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";
                }
               
           }            
            $real_querys2.= ' where 1 ';
            $real_querys.= ' where 1 ';
            if ($requestData['child_dataid']!='')           
            {
                $xchildid= $requestData['child_dataid'];
               $real_querys .= " and  a.mainid='$xchildid'";
            }else{  
   
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and d.mainid ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                 $search_invoicenode=$requestData['search_invoiceno']; 
                $real_querys .= " and d.invoiceno='$search_invoicenode'  ";
            }        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and d.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and d.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and d.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and d.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and d.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddatesecond=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and d.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and l.ac_name ='$customer'   ";
            }
            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and d.mainid in(select mainid from contran where code='$code1')   ";                 
            }         
            if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
                $frmduedays=$requestData['search_frmduedays'];
                $toduedays=$requestData['search_toduedays'];

                $real_querys .= " and d.duedays  between '$frmduedays' and  '$toduedays' ";
            }
            if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
                $frmamtrs=$requestData['search_frmamt1'];
                $toamtrs=$requestData['search_toamt1'];
                $real_querys .= " and d.totalamt  between '$frmamtrs' and  '$toamtrs'  ";
            }
            if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
                $frmamtusd=$requestData['search_frmamt2'];
                $toamtusd=$requestData['search_toamt2'];

                $real_querys .= " and d.totalamt2  between '$frmamtusd' and  '$toamtusd'   ";
            }        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  d.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  d.invdate desc ';
            }            
            }
         if ($searchValue != '') {
                $search_data2 = ' and ';               
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'description') {
                        $search_data2 .= 'd.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'ac_codes') {
                        $search_data2 .= "l.ac_name like '%$searchValue%' OR ";
                    
                    } else if ($clm == 'invoiceno') {
                        $search_data2 .= "d.invoiceno like '%$searchValue%' OR ";
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
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';          
            if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }      
            


            
            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }

            
            $final_q = $real_querys . ' ' . $query;
            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        }         
        else if ($table == 'mperforma') {
            if (empty($requestData)) {
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys = "select d.mainid, d.srno, DATE(d.invdate) AS invdate, d.duedays,"
                        . "DATE(d.duedate) as duedate, d.trn_type, d.ac_code, d.id, d.description, d.invoiceno, d.crate, d.salesmanid,"
                        . "d.doc_no, d.totalamt, d.totalamt2, d.totalqty, d.salesqty, d.returnqty, d.balanceqty, d.totalpcs,"
                        . "d.salespcs,d.returnpcs, d.balancepcs, d.customerid, d.slrepid, d.prepby, d.shipby, d.recdby,shipdate, d.mnote,"
                        . "c.name AS currency_name, l.ac_name AS ac_codes FROM $table AS d"
                        . " LEFT JOIN mcurrency c ON c.ID = d.id LEFT JOIN ledgmast l ON l.ac_code = d.ac_code";
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";
            }else
            {
                if($requestData['child_dataid']=='')
                { 
                    $maindb = $this->load->database($dbname, TRUE);
                $real_querys = "select d.mainid, d.srno, DATE(d.invdate) AS invdate, d.duedays,"
                        . "DATE(d.duedate) as duedate, d.trn_type, d.ac_code, d.id, d.description, d.invoiceno, d.crate, d.salesmanid,"
                        . "d.doc_no, d.totalamt, d.totalamt2, d.totalqty, d.salesqty, d.returnqty, d.balanceqty, d.totalpcs,"
                        . "d.salespcs,d.returnpcs, d.balancepcs, d.customerid, d.slrepid, d.prepby, d.shipby, d.recdby,shipdate, d.mnote,"
                        . "c.name AS currency_name, l.ac_name AS ac_codes FROM $table AS d"
                        . " LEFT JOIN mcurrency c ON c.ID = d.id LEFT JOIN ledgmast l ON l.ac_code = d.ac_code";
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";
                }else
                {
                    $maindb = $this->load->database($dbname, TRUE);
                    $real_querys .= ("select a.mainid,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM tperforma a "
                            . "LEFT JOIN mperforma b ON a.mainid=b.mainid "
                            . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code");
                    $real_querys2 = "select count(*) as cnt from $table as d "
                            . " ";   
                }
            }                        
            $real_querys.=' where 1';
            $real_querys1.=' where 1';     
             
            if ($searchValue != '') {
                $search_data2 = ' and ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'description') {
                        $search_data2 .= 'd.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'ac_codes') {
                        $search_data2 .= "l.ac_name like '%$searchValue%' OR ";                 
                    } else if ($clm == 'invoiceno') {
                        $search_data2 .= "d.invoiceno like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }
            

            if($requestData['child_dataid']!='')
                {
                    $xchildid= $requestData['child_dataid'];
               $real_querys .= " and  a.mainid='$xchildid'";
             }else{
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and d.mainid ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                $search_invoicenode=$requestData['search_invoiceno']; 
                $real_querys .= " and d.invoiceno='$search_invoicenode'  ";
            }        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and d.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and d.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and d.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and d.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and d.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and d.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and l.ac_name ='$customer'   ";
            }

            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and d.mainid in(select mainid from tperforma where code='$code1')   ";                 
            }  
            
            
            
   
                    
        if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
            $frmduedays=$requestData['search_frmduedays'];
            $toduedays=$requestData['search_toduedays'];
            
            $real_querys .= " and d.duedays  between '$frmduedays' and  '$toduedays' ";
        }
        if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
            $frmamtrs=$requestData['search_frmamt1'];
            $toamtrs=$requestData['search_toamt1'];
            $real_querys .= " and d.totalamt  between '$frmamtrs' and  '$toamtrs'  ";
        }
        if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
            $frmamtusd=$requestData['search_frmamt2'];
            $toamtusd=$requestData['search_toamt2'];
            
            $real_querys .= " and d.totalamt2  between '$frmamtusd' and  '$toamtusd'   ";
        }
        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  d.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  d.invdate desc ';
            }
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';
            if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }

            }
            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }
            $final_q = $real_querys . ' ' . $query;
            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        } 
        else if ($table == 'maintran' && $transaction_type=='PU') {	
            
            if(empty($requestData))
            {            
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                        . "left join mcurrency as b on a.id=b.id  "
                        . "left join  ledgmast as c on a.ac_code=c.ac_code "
                        . "left join  ledgmast as d on a.salesid=d.ac_code"
                        . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                        . "ledgmast as f on a.taxac_code=f.ac_code "
                        . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='PU'";
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";            
            }else
            {
                if ($requestData['child_dataid']=='')
                {
                    $maindb = $this->load->database($dbname, TRUE);
                    $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                            . "left join mcurrency as b on a.id=b.id  "
                            . "left join  ledgmast as c on a.ac_code=c.ac_code "
                            . "left join  ledgmast as d on a.salesid=d.ac_code"
                            . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                            . "ledgmast as f on a.taxac_code=f.ac_code "
                            . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='PU'";
                    $real_querys2 = "select count(*) as cnt from $table as d "
                            . " ";           
                }else
                {    
                $xchildid= $requestData['child_dataid'];
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys .= ("select a.doc_no,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM trans1 a "
                        . "LEFT JOIN maintran b ON a.doc_no=b.doc_no "
                        . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code where a.doc_no='$xchildid'  and a.trn_type='PU' ");
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";                    
                }
            }
            if ($searchValue != '') {
                $search_data2 = ' and ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'description') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'doc_no') {
                        $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }   
        if ($requestData['child_dataid']=='')
        {
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                $search_invoicenode=$requestData['search_invoiceno'];
                $real_querys .= " and a.invoiceno='$search_invoicenode'  ";
            }        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }
            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and a.doc_no in(select doc_no from trans1 where code='$code1')   ";                 
            }   
        if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
            $frmduedays=$requestData['search_frmduedays'];
            $toduedays=$requestData['search_toduedays'];
            
            $real_querys .= " and a.duedays  between '$frmduedays' and  '$toduedays' ";
        }
        if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
            $frmamtrs=$requestData['search_frmamt1'];
            $toamtrs=$requestData['search_toamt1'];
            $real_querys .= " and a.netamt  between '$frmamtrs' and  '$toamtrs'  ";
        }
        if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
            $frmamtusd=$requestData['search_frmamt2'];
            $toamtusd=$requestData['search_toamt2'];
            
            $real_querys .= " and a.netamt2  between '$frmamtusd' and  '$toamtusd'   ";
        }        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
            $query1 = '';
            if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }            
        }
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ',' . $requestData['length'];
            }
            $final_q = $real_querys . ' ' . $query;
            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        } 
        else if ($table == 'maintran' && $transaction_type=='SL') {	

            if (empty($requestData))
            {
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                        . "left join mcurrency as b on a.id=b.id  "
                        . "left join  ledgmast as c on a.ac_code=c.ac_code "
                        . "left join  ledgmast as d on a.salesid=d.ac_code"
                        . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                        . "ledgmast as f on a.taxac_code=f.ac_code "
                        . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='SL'";
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";
            }else 
            {
                if ($requestData['child_dataid']=='')
                {                    
                        $maindb = $this->load->database($dbname, TRUE);
                        $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                                . "left join mcurrency as b on a.id=b.id  "
                                . "left join  ledgmast as c on a.ac_code=c.ac_code "
                                . "left join  ledgmast as d on a.salesid=d.ac_code"
                                . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                                . "ledgmast as f on a.taxac_code=f.ac_code "
                                . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='SL'";
                        $real_querys2 = "select count(*) as cnt from $table as d "
                                . " ";
                }else
                {
                    $xchildid= $requestData['child_dataid'];
                    $maindb = $this->load->database($dbname, TRUE);
                    $real_querys .= ("select a.doc_no,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM trans1 a "
                            . "LEFT JOIN maintran b ON a.doc_no=b.doc_no "
                            . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code where a.doc_no='$xchildid'  and a.trn_type='SL' ");
                    $real_querys2 = "select count(*) as cnt from $table as d "
                            . " ";                    
                }
                
            }
            
            
            if ($searchValue != '') {
                $search_data2 = ' and ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'description') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'doc_no') {
                        $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
                    }
                }
                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }
            
              
        if ($requestData['child_dataid']==''){
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                                $search_invoicenode=$requestData['search_invoiceno'];

                $real_querys .= " and a.invoiceno='$search_invoicenode'  ";
            }
        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }                
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }
            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and a.doc_no in(select doc_no from trans1 where code='$code1')   ";                 
            }                
        if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
            $frmduedays=$requestData['search_frmduedays'];
            $toduedays=$requestData['search_toduedays'];
            
            $real_querys .= " and a.duedays  between '$frmduedays' and  '$toduedays' ";
        }
        if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
            $frmamtrs=$requestData['search_frmamt1'];
            $toamtrs=$requestData['search_toamt1'];
            $real_querys .= " and a.netamt  between '$frmamtrs' and  '$toamtrs'  ";
        }
        if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
            $frmamtusd=$requestData['search_frmamt2'];
            $toamtusd=$requestData['search_toamt2'];
            
            $real_querys .= " and a.netamt2  between '$frmamtusd' and  '$toamtusd'   ";
        }        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
            
            if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }
            
        }
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';
            
            
            if ($requestData['length'] != '') {
                $query = ' limit ' . $requestData['start'] . ' , ' . $requestData['length'];
            }
            $final_q = $real_querys . ' ' . $query;
            $all_data = $maindb->query($final_q)->result_array();
            $maindb->close();
            $data2 = $maindb->query($real_querys2)->row_array();
            $recordsTotal = $data2['cnt'];
        }
        else if ($table == 'maintran' && $transaction_type=='PR') {                        
        if (empty($requestData))
        {
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                    . "left join mcurrency as b on a.id=b.id  "
                    . "left join  ledgmast as c on a.ac_code=c.ac_code "
                    . "left join  ledgmast as d on a.salesid=d.ac_code"
                    . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                    . "ledgmast as f on a.taxac_code=f.ac_code "
                    . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='PR'";
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
        }
        else{
            
            if ($requestData['child_dataid']=='')
            {
                
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
                    . "left join mcurrency as b on a.id=b.id  "
                    . "left join  ledgmast as c on a.ac_code=c.ac_code "
                    . "left join  ledgmast as d on a.salesid=d.ac_code"
                    . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
                    . "ledgmast as f on a.taxac_code=f.ac_code "
                    . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='PR'";
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";
            }else
            {                
                $xchildid= $requestData['child_dataid'];
                $maindb = $this->load->database($dbname, TRUE);
                $real_querys .= ("select a.doc_no,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM trans1 a "
                        . "LEFT JOIN maintran b ON a.doc_no=b.doc_no "
                        . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code where a.doc_no='$xchildid'  and a.trn_type='PR' ");
                $real_querys2 = "select count(*) as cnt from $table as d "
                        . " ";                     
            }
        }            
        
    if ($searchValue != '') {
        $search_data2 = ' and ';
        foreach ($requestData['columns'] as $columns) {
            $clm = $columns['data'];
            if ($clm == 'description') {
                $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
            } else if ($clm == 'doc_no') {
                $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
            }
        }
        $search_data2 = rtrim($search_data2, 'OR ');
        $search_data = ' ' . $search_data2 . '';
        $real_querys .= $search_data;
    }
    
      
   if ($requestData['child_dataid']==''){
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                                                $search_invoicenode=$requestData['search_invoiceno'];

                $real_querys .= " and a.invoiceno='$search_invoicenode'  ";
            }
        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }

            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and a.doc_no in(select doc_no from trans1 where code='$code1')   ";                 
            }   
if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
            $frmduedays=$requestData['search_frmduedays'];
            $toduedays=$requestData['search_toduedays'];
            
            $real_querys .= " and a.duedays  between '$frmduedays' and  '$toduedays' ";
        }
        if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
            $frmamtrs=$requestData['search_frmamt1'];
            $toamtrs=$requestData['search_toamt1'];
            $real_querys .= " and a.netamt  between '$frmamtrs' and  '$toamtrs'  ";
        }
        if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
            $frmamtusd=$requestData['search_frmamt2'];
            $toamtusd=$requestData['search_toamt2'];
            
            $real_querys .= " and a.netamt2  between '$frmamtusd' and  '$toamtusd'   ";
        }
        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }
   }            
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                $real_querys .= ' order by ' . $columnss . ' ' . $ascdesc . ' ';
            }
            $query1 = '';
            
    if ($requestData['length'] != '') {
        $query = ' OFFSET ' . $requestData['start'] . ' ROWS FETCH NEXT ' . $requestData['length'] . ' ROWS ONLY';
    }
    $final_q = $real_querys . ' ' . $query;
    $all_data = $maindb->query($final_q)->result_array();
    $maindb->close();
    $data2 = $maindb->query($real_querys2)->row_array();
    $recordsTotal = $data2['cnt'];
}
        else if ($table == 'maintran' && $transaction_type=='SR') {	
    $maindb = $this->load->database($dbname, TRUE);
    
    if (empty($requestData)){
        
        
    $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
            . "left join mcurrency as b on a.id=b.id  "
            . "left join  ledgmast as c on a.ac_code=c.ac_code "
            . "left join  ledgmast as d on a.salesid=d.ac_code"
            . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
            . "ledgmast as f on a.taxac_code=f.ac_code "
            . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='SR'";
    $real_querys2 = "select count(*) as cnt from $table as d "
            . " ";
    
    }
    else
    {
        
        if ($requestData['child_dataid']=='')
        {
                $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name,c.ac_name,d.ac_name as brockers,e.ac_name as discac_name,f.ac_name as taxac_name,g.typename as pusl_ac_name from $table a  "
            . "left join mcurrency as b on a.id=b.id  "
            . "left join  ledgmast as c on a.ac_code=c.ac_code "
            . "left join  ledgmast as d on a.salesid=d.ac_code"
            . " left join  ledgmast as e on a.discac_code=e.ac_code left join  "
            . "ledgmast as f on a.taxac_code=f.ac_code "
            . "LEFT JOIN mpusltype as g ON a.pusltypeid=g.prikey where a.trn_type='SR'";
    $real_querys2 = "select count(*) as cnt from $table as d "
            . " ";
        }else {
            $xchildid= $requestData['child_dataid'];
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys .= ("select a.doc_no,a.code,a.qty,a.rate,a.amt,a.rate2,a.amt2,b.invoiceno,c.ac_name,d.refno,d.descr as description FROM trans1 a "
                    . "LEFT JOIN maintran b ON a.doc_no=b.doc_no "
                    . "LEFT JOIN ledgmast c ON b.ac_code=c.ac_code left join stock d on a.code=d.code where a.doc_no='$xchildid'  and a.trn_type='SR' ");
            $real_querys2 = "select count(*) as cnt from $table as d "
                    . " ";    
        }
        
        
        
    }
    if ($searchValue != '') {
        $search_data2 = ' and ';
        foreach ($requestData['columns'] as $columns) {
            $clm = $columns['data'];
            if ($clm == 'description') {
                $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
            } else if ($clm == 'doc_no') {
                $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
            }
        }
        $search_data2 = rtrim($search_data2, 'OR ');
        $search_data = ' ' . $search_data2 . '';
        $real_querys .= $search_data;
    }

         if ($requestData['child_dataid']==''){
   
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                                                                $search_invoicenode=$requestData['search_invoiceno'];

                $real_querys .= " and a.invoiceno='$search_invoicenode'  ";
            }
        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";         
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }

            if ($requestData['search_code']  != '') 
            {
                $code1=$requestData['search_code'];
                $real_querys .= " and a.doc_no in(select doc_no from trans1 where code='$code1')   ";                 
            }   
    if ($requestData['search_frmduedays'] != '' && $requestData['search_toduedays'] != '') {
            $frmduedays=$requestData['search_frmduedays'];
            $toduedays=$requestData['search_toduedays'];
            
            $real_querys .= " and a.duedays  between '$frmduedays' and  '$toduedays' ";
        }
        if ($requestData['search_frmamt1'] != '' && $requestData['search_toamt1'] != '') {
            $frmamtrs=$requestData['search_frmamt1'];
            $toamtrs=$requestData['search_toamt1'];
            $real_querys .= " and a.netamt  between '$frmamtrs' and  '$toamtrs'  ";
        }
        if ($requestData['search_frmamt2'] != '' && $requestData['search_toamt2'] != '') {
            $frmamtusd=$requestData['search_frmamt2'];
            $toamtusd=$requestData['search_toamt2'];
            
            $real_querys .= " and a.netamt2  between '$frmamtusd' and  '$toamtusd'   ";
        }
        
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
              if ($requestData['search_lengthss'] != '') {
                $lengst=$requestData['search_lengthss'];
                if ($requestData['start']==1)
                {
                    $offset = 0;
                }else
                {
                   $page1=$requestData['start'];
                   $offset = ($page1-1)*10; 
                }
                $query = ' limit ' . $offset . ',' . $lengst;
            }
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
        else if ($table == 'maintran' && $transaction_type=='MX') {	
    $maindb = $this->load->database($dbname, TRUE);
    $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name from $table a  "
            . "left join mcurrency as b on a.id=b.id  "
            . "left join  ledgmast as c on a.ac_code=c.ac_code where a.trn_type='MX'";
    $real_querys2 = "select count(*) as cnt from $table as d "
            . " ";
    if ($searchValue != '') {
        $search_data2 = ' and ';
        foreach ($requestData['columns'] as $columns) {
            $clm = $columns['data'];
            if ($clm == 'description') {
                $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
            } else if ($clm == 'doc_no') {
                $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
            }
        }
        $search_data2 = rtrim($search_data2, 'OR ');
        $search_data = ' ' . $search_data2 . '';
        $real_querys .= $search_data;
    }
    
    
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                $real_querys .= " and a.invoiceno='$search_invoiceno'  ";
            }        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";          
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }
//            if ($requestData['search_code']  != '') 
//            {
//                $code1=$requestData['search_code'];
//                $real_querys .= " and a.doc_no in(select code from trans1 where code='$code1')   ";                 
//            }   
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
    
    $position = $requestData['order'][0]['column'];
    $ascdesc = $requestData['order'][0]['dir'];
    if (isset($ascdesc)) {
        $columnss = $requestData['columns'][$position]['data'];
        $real_querys .= ' order by  ' . $columnss . ' ' . $ascdesc . ' ';
    }
    $query1 = '';
    if ($requestData['length'] != -1) {
        $query = ' limit ' . $requestData['start'] .','. $requestData['length'];
    }
    $final_q = $real_querys . ' ' . $query;
    $all_data = $maindb->query($final_q)->result_array();
    $maindb->close();
    $data2 = $maindb->query($real_querys2)->row_array();
    $recordsTotal = $data2['cnt'];
}
        else if ($table == 'maintran' && $transaction_type=='RE') {	
    $maindb = $this->load->database($dbname, TRUE);
    $real_querys = "select a.*,DATE(a.invdate) as invdate,DATE(a.duedate) as duedate ,b.NAME as currency_name from $table a  "
            . "left join mcurrency as b on a.id=b.id  "
            . "left join  ledgmast as c on a.ac_code=c.ac_code where a.trn_type='RE'";
    $real_querys2 = "select count(*) as cnt from $table as d "
            . " ";
    if ($searchValue != '') {
        $search_data2 = ' and ';
        foreach ($requestData['columns'] as $columns) {
            $clm = $columns['data'];
            if ($clm == 'description') {
                $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
            } else if ($clm == 'doc_no') {
                $search_data2 .= "c.ac_name like '%$searchValue%' OR ";
            }
        }
        $search_data2 = rtrim($search_data2, 'OR ');
        $search_data = ' ' . $search_data2 . '';
        $real_querys .= $search_data;
    }
    
    
            if($requestData['search_invoicenoid']!='')
            {
                $search_invoiceno22=$requestData['search_invoicenoid']; 
               $real_querys .= " and a.doc_no ='$search_invoiceno22' "; 
            }           
            if ($requestData['search_invoiceno']!='') {
                $real_querys .= " and a.invoiceno='$search_invoiceno'  ";
            }        
            if ($requestData['search_dayswise'] !='') {
                
                 if ($requestData['search_dayswise']=='Today')
                 {
                     $date = date("Y-m-d 00:00:00");
                    $date2 = date("Y-m-d 23:59:59");
                    $real_querys .= " and a.invdate between '$date' and '$date2'  "; 
                 }
                 else   if ($requestData['search_dayswise'] == 'Yesterday') 
                 {
                $startOfYesterdayTimestamp = strtotime("-1 day", $currentDateTime);
                $startOfYesterday = date("Y-m-d 00:00:00", $startOfYesterdayTimestamp);
                // Calculate the timestamp for the end of yesterday
                $endOfYesterdayTimestamp = strtotime("today", $startOfYesterdayTimestamp) - 1;
                $endOfYesterday = date("Y-m-d 23:59:59", $endOfYesterdayTimestamp);

                $real_querys .= " and a.invdate between '$endOfYesterday' and '$endOfYesterday'  ";
            } 
            else if ($requestData['search_dayswise'] == 'This Week') {
           $startOfWeekTimestamp = strtotime("monday this week", $currentDateTime);
            $startOfWeek = date("Y-m-d 00:00:00", $startOfWeekTimestamp);
            // Calculate the timestamp for the end of the week (Sunday)
            $endOfWeekTimestamp = strtotime("sunday this week", $currentDateTime) + 86399; // 86399 seconds in a day
            $endOfWeek = date("Y-m-d 23:59:59", $endOfWeekTimestamp);
                $real_querys .= " and a.invdate between '$startOfWeek' and '$endOfWeek'  ";
            }
            else if ($requestData['search_dayswise'] == 'This Month') {
                    $startOfMonthTimestamp = strtotime("first day of this month", $currentDateTime);
                    $startOfMonth = date("Y-m-d 00:00:00", $startOfMonthTimestamp);
                    // Calculate the timestamp for the end of the month
                    $endOfMonthTimestamp = strtotime("last day of this month", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfMonth = date("Y-m-d 23:59:59", $endOfMonthTimestamp);
                    $real_querys .= " and a.invdate between '$startOfMonth' and '$endOfMonth'  ";                
            } 
            else if ($requestData['search_dayswise'] == 'This Year') {
           // Calculate the timestamp for the start of the year
                    $startOfYearTimestamp = strtotime("first day of January this year", $currentDateTime);
                    $startOfYear = date("Y-m-d 00:00:00", $startOfYearTimestamp);
                    // Calculate the timestamp for the end of the year
                    $endOfYearTimestamp = strtotime("last day of December this year", $currentDateTime) + 86399; // 86399 seconds in a day
                    $endOfYear = date("Y-m-d 23:59:59", $endOfYearTimestamp);
                    $real_querys .= " and a.invdate between '$startOfYear' and '$endOfYear' ";        
            }   
               
            }
            if ($requestData['search_selecteddate'] != '' && $requestData['search_selecteddatesecond'] != '') {
                $selecteddate=$requestData['search_selecteddate'];
                $selecteddate=$requestData['search_selecteddatesecond'];

            $real_querys  .= " and a.invdate  between '$selecteddate' and  '$selecteddatesecond'  ";          
        }
            if ($requestData['search_customer'] != '') {
                $customer=$requestData['search_customer'];
                $real_querys .= " and c.ac_name ='$customer'   ";
            }
//            if ($requestData['search_code']  != '') 
//            {
//                $code1=$requestData['search_code'];
//                $real_querys .= " and a.doc_no in(select code from trans1 where code='$code1')   ";                 
//            }   
            if ($requestData['search_ascdesc'] == 'A') 
            {           
                 $real_querys .= ' order by  a.invdate asc ';
            }
            if ($requestData['search_ascdesc'] == 'D')  
            {
                 $real_querys .= ' order by  a.invdate desc ';
            }
    
    $position = $requestData['order'][0]['column'];
    $ascdesc = $requestData['order'][0]['dir'];
    if (isset($ascdesc)) {
        $columnss = $requestData['columns'][$position]['data'];
        $real_querys .= ' order by  ' . $columnss . ' ' . $ascdesc . ' ';
    }
    $query1 = '';
    if ($requestData['length'] != -1) {
        $query = ' limit ' . $requestData['start'] .','. $requestData['length'];
    }
    $final_q = $real_querys . ' ' . $query;
    $all_data = $maindb->query($final_q)->result_array();
    $maindb->close();
    $data2 = $maindb->query($real_querys2)->row_array();
    $recordsTotal = $data2['cnt'];
}
        else if ($table == 'ledgmast') {
            $maindb = $this->load->database($dbname, TRUE);
            $real_querys = "SELECT a.*,b.head,DATE(a.knowdate) as knowdate,b.head,d.countryname,e.statename,f.ac_name AS salesman,g.name as ac_actype,h.name as ac_class FROM ledgmast a LEFT JOIN mhead b ON a.headcode = b.headcode LEFT JOIN  mcountry d ON a.countryid=d.countryid LEFT JOIN mstate e ON a.stateid = e.stateid LEFT JOIN ledgmast f ON a.saleid = f.ac_code  LEFT JOIN ledger_actype_details g ON a.actype=g.id  LEFT JOIN ledger_class_details h ON a.class=h.id ";
            $real_querys2 = "select count(*) as cnt from $table as d  ";
//            print_r($real_querys);
            $real_querys.=" where 1 ";
            $real_querys2.=" where 1 ";
            if ($searchValue != '') {
                $search_data2 = ' AND ';
                foreach ($requestData['columns'] as $columns) {
                    $clm = $columns['data'];
                    if ($clm == 'ac_name') {
                        $search_data2 .= 'a.' . $clm . " like '%$searchValue%' OR ";
                    } else if ($clm == 'ac_codes') {
                        $search_data2 .= "a.ac_name like '%$searchValue%' OR ";
                    } else if ($clm == 'head') {
                        $search_data2 .= "b.head like '%$searchValue%' OR ";
                    } else if ($clm == 'invoiceno') {
                        $search_data2 .= 'd.' . $clm . " like '%$searchValue%' OR ";
                    }
                }

                $search_data2 = rtrim($search_data2, 'OR ');
                $search_data = ' ' . $search_data2 . '';
                $real_querys .= $search_data;
            }
            if($requestData['search_data']!='')
            {
                $search_data22=$requestData['search_data']; 
               $real_querys .= " and a.ac_name like '%$search_data22%' "; 
            }
            
            $position = $requestData['order'][0]['column'];
            $ascdesc = $requestData['order'][0]['dir'];
            if (isset($ascdesc)) {
                $columnss = $requestData['columns'][$position]['data'];
                if ($columnss == 'doc_no') {
                    $columnss = 'd.doc_no';
                }
                $real_querys .= ' ORDER BY ' . $columnss . ' ' . $ascdesc . ' ';
            } 

            $query1 = '';
            if ($requestData['length'] != -1) {
                $query = ' limit ' . $requestData['start'].','.$requestData['length'];
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
            'query'=> $final_q,
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsTotal,
            'requested' => $requestData,
            'data' => $all_data
        );
        echo json_encode($response);
    }
    public function add_data() {
        $jsonData = file_get_contents('php://input');
        $data = json_decode($jsonData, true);
        if (empty($data)) {
//            $data = $_POST; 
//            $dbname = ($_SESSION['dbname']);
           $data = $_POST;
           $dbname = ($_SESSION['dbname']);
           $tables = explode(",", $data['table']);
           $table = $tables[0];
           $table2 = $tables[1];
           $table3 = $tables[2];
           $table4 = $tables[3];
           $table5 = $tables[4];          
        }
        else
        {
           $dbname = ($data['db']);
           $tables = explode(",", $data['table']);
           $table = $tables[0];
           $table2 = $tables[1];
           $table3 = $tables[2];
           $table4 = $tables[3];
           $table5 = $tables[4];
        } 
        $maindb = $this->load->database($dbname, TRUE);
         if ($table == 'maincon') {
            $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id'];// Assuming a user is logged in and their user ID is 1            
            unset($data['user_id']);
            $contranid = ($data['contranid']);
            $mainid = $data['mainid'];            
            unset($data['mainid']);  
            $transactiontype = $data['transactiontype'];            
            unset($data['transactiontype']);  
            unset($data['db']);
            $items = ($data['items']);
            unset($data['items']);
            $invdate = $data['invdate'];            
            $invoiceno2 = $data['invoiceno'];            
            unset($data['invdate']);            
            $description = $data['description'];            
            unset($data['description']);
            $final = array_merge($data, array("trn_type" => 'CO'), array("invdate" => $invdate));

            if (isset($mainid)) {
                $cntmid = 0;
//                print_r('bhairu');
            } else {
                $cntmid =$mainid ; 
            }
            
         
            $cnt = $maindb->query("SELECT COUNT(mainid) AS cnt FROM $table WHERE invoiceno='$invoiceno2' and mainid!='$cntmid' ")->row()->cnt;
            if($cnt > 0) {
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Record already exists!'));
                exit;
            }

            
            
            
            $columnsToUnset = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table2'")->result_array();
            $balnk_data=$columnsToUnset[1];
            $bankdetails = array();
            $clm='';
                foreach ($columnsToUnset as $column){
                   
                $clm = $column['column_name'];
                if (!empty($data['itemdescription'])){
                    $bankdetails = array_merge($bankdetails,array('itemdescription' => $data['itemdescription']));                        
                    unset($final['itemdescription']); 
                }
                if (!empty($data[$clm]))
                {    
                  $bankdetails = array_merge($bankdetails,array($clm => $data[$clm]));                                                            
                } 
                unset($final[$clm]); 
                    
                }
          
//       print_r($bankdetails);
//    exit;

            $final = array_merge($final,array("invdate" => $invdate),array("trn_type" => 'CO',"description"=>$description));
            unset($data['daysname']);
            unset($final['match']);
            unset($final['table']);
            unset($final['type']);
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
                $allfiles = array();
                $main_files = '';
                $i = 0;
                $all_final = array();
                $last_ids = "";
                if (empty($items)){
                    foreach ($bankdetails['qty'] as $qty_rs)
                    {
                        $fdata=array();                        
                        $fdata = array("mainid" => $insertId);
                        $fdata = array("invdate" => $invdate);
                        $fdata = array_merge($fdata, array(                        
                        "contranid" => $bankdetails['contranid'][$i],
                        "description" => $bankdetails['itemdescription'][$i],                            
                        "code" => $bankdetails['code'][$i],
                        "qty" => $qty_rs,
                        "pcs" =>$bankdetails['pcs'][$i],
                        "trn_type" => $bankdetails['trn_type'][$i],
                        "dcflag" =>$bankdetails['dcflag'][$i],
                        "rate" => $bankdetails['rate'][$i],
                        "amt" =>  $bankdetails['amt'][$i],
                        "rate2" => $bankdetails['rate2'][$i],
                        "amt2" =>  $bankdetails['amt2'][$i],
                        "invdate" =>$bankdetails['invdate'][$i],
                        "discper" =>$bankdetails['discper'][$i],
                        "mainid" =>$insertId,
                       "invdate" => $invdate      
                    ));
                    $contranid=$bankdetails['contranid'][$i];
                    if(empty($bankdetails['contranid'][$i]))  
                    {
                        $maindb->insert("contran",$fdata);
                        $insertIds_3 = $maindb->insert_id();
                        $last_ids .= $insertIds_3 . ',';

                    } else {
                        $maindb->where("contranid",$contranid);
                        $maindb->update("contran",$fdata);
                        $last_ids .= $contranid . ',';
                    }
                    $i++;
                }    
                }
                else 
                {
//                 print_r($items);
//                 exit;
                    $i = 0;
                foreach ($items as $qty_data) {
                    
//                    $fdata=array();  
//                    $fdata = array("mainid" => $insertId);
//                     $fdata = array("invdate" => $invdate); 
                     $fdata = array(                      
                        "qty" => $items[$i]['qty'],
                        "pcs" => $items[$i]['pcs'],
                        "description" => $items[$i]['itemdescription'],
                         "code" => $items[$i]['code'],
                         "discper" => $items[$i]['discper'],
                          "rate" => $items[$i]['rate'],
                          "rate2" => $items[$i]['rate2'],
                          "amt" => $items[$i]['amt'],
                          "amt2" => $items[$i]['amt2'],
                          "amt2" => $items[$i]['amt2'],
                          "mainid" => $insertId,
                          "invdate" => $invdate
                         
                             );
                        $contranid=$items['contranid'][$i];
//                        print_r($qty_data);
//                        print_r($fdata);  
                        
//                        exit;
                        if(empty($items_data['contranid'][$i]))  
                        {
                            $maindb->insert("contran",$fdata);
                            $insertIds_3 = $maindb->insert_id();
                            $last_ids .= $insertIds_3 . ',';

                        } else {
                            $maindb->where("contranid",$contranid);
                            $maindb->update("contran",$fdata);
                            $last_ids .= $contranid . ',';
                        }
                        $i++;
                        
                    }
//              exit;
                }
                
                
                
            }
                if ($mainid != '') 
                {
                    if ($last_ids != '') 
                    {
                        $last_ids= rtrim($last_ids, ',');
                       $maindb->query("delete from contran where contranid NOT IN($last_ids) and mainid=$mainid");
                    }
                }          
            if ($insertId > 0) {
                if ($insertId) {

                    if ($mainid == '') 
                    {               
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
        else  if ($table == 'mperforma') {
         $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id'];// Assuming a user is logged in and their user ID is 1            
            unset($data['user_id']);
            $contranid = ($data['contranid']);
            $mainid = $data['mainid'];            
            unset($data['mainid']);  
            $transactiontype = $data['transactiontype'];            
            unset($data['transactiontype']);  
            unset($data['db']);
            $items = ($data['items']);
            unset($data['items']);
            $invdate = $data['invdate'];            
            $invoiceno2 = $data['invoiceno'];            
            unset($data['invdate']);            
            $description = $data['description'];            
            unset($data['description']);
            
            $final = array_merge($data, array("trn_type" => 'PF'), array("invdate" => $invdate));
            
            if (isset($mainid)) {
                $cntmid = 0;
//                print_r('bhairu');
            } else {
                $cntmid =$mainid ; 
            }
            $cnt = $maindb->query("SELECT COUNT(mainid) AS cnt FROM $table WHERE invoiceno='$invoiceno2' and mainid!=$mainid ")->row()->cnt;
            if($cnt > 0) {
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Record already exists!'));
                exit;
            }

            $columnsToUnset = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table2'")->result_array();
            $balnk_data=$columnsToUnset[1];
            $bankdetails = array();
            $clm='';
                foreach ($columnsToUnset as $column){                    
                    $clm = $column['column_name'];
                    if (!empty($data['itemdescription'])){
                        $bankdetails = array_merge($bankdetails,array('itemdescription' => $data['itemdescription']));                        
                        unset($final['itemdescription']); 
                    }
                    if (!empty($data[$clm]))
                    {    
                      $bankdetails = array_merge($bankdetails,array($clm => $data[$clm]));                                                            
                    } 
                    unset($final[$clm]);   
                }   
             
            $final = array_merge($final,array("invdate" => $invdate),array("trn_type" => 'PF'),array("description" => $description));
                   
            unset($data['daysname']);
            unset($final['match']);
            unset($final['table']);
            unset($final['type']);
          
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
              
                $allfiles = array();
                $main_files = '';
                $i = 0;
                $all_final = array();
                $last_ids = "";
//                print_r($bankdetails);
//                exit;
                    foreach ($bankdetails['qty'] as $qty_rs)
                    {
                        $fdata=array();                        
                        $fdata = array("mainid" => $insertId);
                        $fdata = array("invdate" => $invdate);
                        $fdata = array_merge($fdata, array(                        
                        "contranid" => $bankdetails['contranid'][$i],
                        "description" => $bankdetails['itemdescription'][$i],                            
                        "code" => $bankdetails['code'][$i],
                        "qty" => $qty_rs,
                        "pcs" =>$bankdetails['pcs'][$i],
                        "trn_type" => $bankdetails['trn_type'][$i],
                        "dcflag" =>$bankdetails['dcflag'][$i],
                        "rate" => $bankdetails['rate'][$i],
                        "amt" =>  $bankdetails['amt'][$i],
                        "rate2" => $bankdetails['rate2'][$i],
                        "amt2" =>  $bankdetails['amt2'][$i],
                        "invdate" =>$bankdetails['invdate'][$i],
                        "discper" =>$bankdetails['discper'][$i],
                        "mainid" =>$insertId,
                       "invdate" => $invdate      
                    ));
//                        
                    $contranid=$bankdetails['contranid'][$i];
                    if(empty($bankdetails['contranid'][$i]))  
                    {       
                        $maindb->insert("tperforma",$fdata);
                        $insertIds_3 = $maindb->insert_id();
                        $last_ids .= $insertIds_3 . ',';

                    } else {
                        $maindb->where("contranid",$contranid);
                        $maindb->update("tperforma",$fdata);
                        $last_ids .= $contranid . ',';
                    }
                    $i++;
                }          
            }
                if ($mainid != '') 
                {
                    if ($last_ids != '') 
                    {
                        $last_ids= rtrim($last_ids, ',');
                       $maindb->query("delete from tperforma where contranid NOT IN($last_ids) and mainid=$mainid");
                    }
                }          
            if ($insertId > 0) {
                if ($insertId) {

                    if ($mainid == '') 
                    {               
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
        else  if ($table == 'maintran') {
            $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1            
            unset($data['user_id']);
            $doc_no = $data['doc_no'];         
            $crate = $data['crate']; 
            unset($data['crate']);
            $id = $data['id']; 
            unset($data['id']);         
            unset($data['doc_no']);  
            $invdate = $data['invdate'];            
            $invoiceno2 = $data['invoiceno'];            
            unset($data['invdate']);    

            $plusminse = $data['plusminse'];            
            unset($data['plusminse']);
            $keyid= $data['keyid'];            
            unset($data['keyid']);    
            $charges_ac= $data['charges_ac'];            
            unset($data['charges_ac']);
            $charges_per= $data['charges_per'];            
            unset($data['charges_per']);
            $charges_amt2= $data['charges_amt2'];            
            unset($data['charges_amt2']);
            $charges_amt1= $data['charges_amt1'];            
            unset($data['charges_amt1']);    
            
          
            $code_out = $data['code_out'];
            unset($data['code_out']);
            $transaction_type = $data['transaction_type'];
	    unset($data['transaction_type']);
            $description = $data['description'];            
            unset($data['description']);
           
            if (empty($doc_no)) {
                $cntmid = 0;
            } else {
                $cntmid =$doc_no ; 
            }
            
            $cnt = $maindb->query("SELECT COUNT(doc_no) AS cnt FROM $table WHERE invoiceno='$invoiceno2' and doc_no!=$cntmid  and trn_type='$transaction_type' ")->row()->cnt;
             if($cnt > 0) {
//                print_r('bhairu');
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Record already exists!'));
                exit;
            }
            /////Trans1 Data
            $final = array_merge($data, array("trn_type" => $transaction_type), array("invdate" => $invdate),array("id" => $id), array("crate" => $crate));
            $columnsToUnset = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table2'")->result_array();
            $balnk_data=$columnsToUnset[1];
            $bankdetails = array();
            $clm='';
            foreach ($columnsToUnset as $column)
            {       
                $clm = $column['column_name'];
                if (!empty($data['itemdescription']))
                {
                    $bankdetails = array_merge($bankdetails,array('itemdescription' => $data['itemdescription']));                        
                    unset($final['itemdescription']); 
                }
                if (!empty($data[$clm]))
                {    
                  $bankdetails = array_merge($bankdetails,array($clm => $data[$clm]));                                                            
                } 
                unset($final[$clm]);
                
            }
            $final = array_merge($final, array("trn_type" => $transaction_type), array("invdate" => $invdate),array("id" => $id), array("crate" => $crate));

           if  ($transaction_type=='RE') {
                $columnsToUnset2 = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table3'")->result_array();
                $balnk_data2=$columnsToUnset2[1];
                $transdetl = array();
                $clm1='';
                foreach ($columnsToUnset2 as $column)
                {                    
                    $clm = $column['column_name'];
                    if (!empty($data['itemdescription']))
                    {
                        $transdetl = array_merge($transdetl,array('itemdescription' => $data['itemdescription']));                        
                        unset($final['itemdescription']); 
                    }
                    if (!empty($data['actype']))
                    {
                        $transdetl = array_merge($transdetl,array('type' => $data['actype']));                        
                        unset($final['actype']); 
                    }
                    if (!empty($data[$clm]))
                    {    
                      $transdetl = array_merge($transdetl,array($clm1 => $data[$clm1]));                                                            
                    } 
                    unset($final[$clm]);   
                }    
            
           }
            $final = array_merge($final,array("invdate" => $invdate),array("trn_type" => $transaction_type),array("description" => $description),array("id" => $id), array("crate" => $crate));  
            $pusltypeids=$final['pusltypeid'];
            unset($data['daysname']);
            unset($final['match']);
            unset($final['table']);
            unset($final['type']);
            $sql=("select puac_code,pucommission,slac_code,slcommission from mpusltype WHERE prikey='$pusltypeids'");
//            print_r($final);
//            exit;
            /////Maintran Table Save Data
            $temp_data=$maindb->query($sql)->row_array();
            if ($doc_no == '') 
            {
                  $maindb->insert($table, $final);
                  $insertId = $maindb->insert_id();
              }
            else {
                  $where_condition = array(
                      'doc_no' => $doc_no,
                  );
                  $maindb->where($where_condition);
                  $maindb->update($table, $final);
                  $insertId = $doc_no;
              }
            if ($insertId > 0) {  
                
                /////Receipt Entry Pass Trans Table Data Save
                $maindb->query("delete from trans where doc_no='$doc_no'");
                $i = 0;
                 foreach ($transdetl['ac_code'] as $ac_code_rs)
                {
                    $fdata=array();
                    $fdata = array_merge($fdata, array(                        
                    "transid" => $transdetl['transid'][$i],
                    "description" =>  $transdetl['itemdescription'][$i],
                    "ac_code" => $transdetl['ac_code'][$i],
                    "amt" => $transdetl['amt'][$i],
                    "amt2" =>$transdetl['amt2'][$i],
                    "trn_type" => $transaction_type,
                    "dcflag" =>$transdetl['dcflag'][$i],
                    "id" =>$id,
                    "crate" =>$crate, 
                    "doc_no" =>$insertId,
                   "invdate" => $invdate      
                ));
                 
                $prikey=$transdetl['transid'][$i];
                if(empty($transdetl['transid'][$i]))  
                {       
                    $maindb->insert("trans",$fdata);
                    $insertIds_3 = $maindb->insert_id();
                    $last_ids .= $insertIds_3 . ',';
                } 
                else {
                    $maindb->where("transid",$prikey);
                    $maindb->update("trans",$fdata);
                    $last_ids .= $prikey . ',';
                }
                $i++;
            }
//            print_r($bankdetails);
//            exit;
            /////Trans1 Table Data Save
                $allfiles = array();
                $main_files = '';
                $i = 0; 
                $all_final = array();
                $last_ids = "";
                foreach ($bankdetails['qty'] as $qty_rs)
                {
                    $fdata=array();                        
                    $fdata = array("mainid" => $insertId);
                    $fdata = array("invdate" => $invdate);
                    $fdata = array_merge($fdata, array(                        
                    "prikey" => $bankdetails['prikey'][$i],
                    "description" => ($bankdetails['itemdescription'][$i] !== null) ? $bankdetails['itemdescription'][$i] : '' ,
                    "code" => $bankdetails['code'][$i],
                    "qty" => $qty_rs,
                    "pcs" =>$bankdetails['pcs'][$i],
                    "trn_type" => $bankdetails['trn_type'][$i],
                    "dcflag" =>$bankdetails['dcflag'][$i],
                    "rate" => $bankdetails['rate'][$i],
                    "amt" =>  $bankdetails['amt'][$i],
                    "rate2" => $bankdetails['rate2'][$i],
                    "amt2" =>  $bankdetails['amt2'][$i],
                    "invdate" =>$bankdetails['invdate'][$i],
                    "discper" =>isset($bankdetails['discper'][$i]) ? $bankdetails['discper'][$i] : 0,
                    "invcostrate" =>$bankdetails['rate'][$i],
                    "invcostrate2" =>$bankdetails['rate2'][$i],                            
                    "invrate" =>$bankdetails['rate'][$i],
                    "invrate2" =>$bankdetails['rate2'][$i],
                    "invamt" =>$bankdetails['amt'][$i],
                    "invamt2" =>$bankdetails['amt2'][$i],
                    "invcostamt" =>$bankdetails['amt'][$i],
                    "invcostamt2" =>$bankdetails['amt2'][$i],
                    "orgprikey" => isset($bankdetails['orgprikey'][$i]) ? $bankdetails['orgprikey'][$i] : 0,
                    "id" =>$id,
                    "crate" =>$crate,
                    "doc_no" =>$insertId,
                   "invdate" => $invdate      
                ));
                $prikey=$bankdetails['prikey'][$i];
                if(empty($bankdetails['prikey'][$i]))  
                {       
                    $maindb->insert("trans1",$fdata);
                    $insertIds_3 = $maindb->insert_id();
                    $last_ids .= $insertIds_3 . ',';
                } else {
                    $maindb->where("prikey",$prikey);
                    $maindb->update("trans1",$fdata);
                    $last_ids .= $prikey . ',';
                }
                $i++;
            }
       if ($transaction_type == 'PU') {
           /////trans_other_charges Data Insert Utility
                $i = 0;
                $all_final = array();
                $last_ids = "";
                $maindb->query("delete from trans_other_charges where doc_no='$insertId'");
                foreach ($charges_ac as $charges_acname) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("id" => $keyid[$i]), array("percentage" => $charges_per[$i]), array("amt1" => $charges_amt1[$i]), array("amt2" => $charges_amt2[$i]),array("doc_no" => $insertId),array("ac_code" => $charges_ac[$i]),array("plusmins" => $plusminse[$i]),array("type" => 'PU') );
                    $keyid2 = $keyid[$i];
//                    if (empty($keyid2)) {
                        $maindb->insert("trans_other_charges",$fdata);
                        $insertIds45 = $maindb->insert_id();
                        $last_ids23 .= $insertIds45 . ',';
                    $i++;
                }
                
           /////tran2 Table Data SAVED
        $maindb->query("delete from trans2 where doc_no='$insertId'");
        $fdatatrans2 = array(
              "ac_code" => $data["ac_code"],"trn_type" => $transaction_type,"dcflag" => 'C',"amount" => $data["netamt"],
              "amount2" => $data["netamt2"],"balamt" => $data["netamt"],
              "balamt2" => $data["netamt2"],"duedays" => $data["duedays"],
              "invno" => $data["invoiceno"],"date" => $invdate,"duedate" => $data["duedate"],"doc_no" => $insertId ,
             "crate" => $crate,
              "id" => $id                
            );
            $maindb->insert("trans2",$fdatatrans2);
        if ($final['ac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['netamt2'],"amt" => $final['netamt'],
                "dcflag" => 'C',"trn_type" => 'PU',
                "type" => 'PU',"ac_code" => $final['ac_code']
            );
        }
        
        if ($temp_data['puac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['grossamt2'],"amt" => $final['grossamt'],"dcflag" => 'D',"trn_type" => 'PU',"type" => 'PU',"ac_code" => $temp_data['puac_code']);
        }
        if ($final['discac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['discamt2'],"amt" => $final['discamt'],"dcflag" => 'C',"trn_type" => 'PU',"type" => 'PU',"ac_code" => $final['discac_code']);
        }
        if ($final['taxac_code'] != '') {
            $trans_data[] = array("amt2" => $final['taxamt2'],"amt" => $final['taxamt'],
                "dcflag" => 'D',"trn_type" => 'PU',"type" => 'PU',"ac_code" => $final['taxac_code']);
        }

        if ($final['salesid'] != '' && $final['salespercent'] != '') {
//        print_r($final['passbrokerage']);
//        exit;
            $brockrageamtusd = ((($final['grossamt2'] - $final['discamt2']) * $final['salespercent'])) / 100;
            $brockrageamtinr = ((($final['grossamt'] - $final['discamt']) * $final['salespercent'])) / 100;        
            $trans_data[] = array("amt2" => $brockrageamtusd,
                "amt" => $brockrageamtinr,"dcflag" => 'C',
                "trn_type" => 'PU',"type" => 'PU',"ac_code" => $final['salesid']
            );
            $trans_data[] = array(
                "amt2" => $brockrageamtusd,"amt" => $brockrageamtinr,"dcflag" => 'D'
                ,"trn_type" => 'PU',"type" => 'PU',"ac_code" => $final['salesid']
            );
        }
        
         $i = 0;
        foreach ($charges_ac as $charges_acname) {
            $trans_data[] = array(
                "amt" => $charges_amt1[$i],
                "amt2" => $charges_amt2[$i],
                "ac_code" => $charges_ac[$i],
                "type" => 'PU',
                "trn_type" => 'PU',
                "dcflag" => ($plusminse[$i] == '-') ? 'C' : 'D'  // Use a ternary operator to conditionally set the value
            );

            $i++;  
        }
        }
        else if ($transaction_type == 'SL') {
             $i = 0;
                $all_final = array();
                $last_ids = "";
                $maindb->query("delete from trans_other_charges where doc_no='$insertId'");
                foreach ($charges_ac as $charges_acname) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("id" => $keyid[$i]), array("percentage" => $charges_per[$i]), array("amt1" => $charges_amt1[$i]), array("amt2" => $charges_amt2[$i]),array("doc_no" => $insertId),array("ac_code" => $charges_ac[$i]),array("plusmins" => $plusminse[$i]),array("type" => 'SL') );
                    $keyid2 = $keyid[$i];
//                    if (empty($keyid2)) {
                        $maindb->insert("trans_other_charges",$fdata);
                        $insertIds45 = $maindb->insert_id();
                        $last_ids23 .= $insertIds45 . ',';
                    $i++;
                }
                
             $maindb->query("delete from trans2 where doc_no='$insertId'");
                $fdatatrans2 = array(
                      "ac_code" => $data["ac_code"],"trn_type" => $transaction_type,"dcflag" => 'D',
                      "amount" => $data["netamt"],"amount2" => $data["netamt2"],
                      "balamt" => $data["netamt"],"balamt2" => $data["netamt2"],"duedate" => $data["duedate"],
                      "duedays" => $data["duedays"],"invno" => $data["invoiceno"],
                      "date" => $invdate,"doc_no" => $insertId ,
                     "crate" => $crate,"id" => $id                
                    );
                    $maindb->insert("trans2",$fdatatrans2);
            
            
            $trans_data = array();    
                if ($final['ac_code'] != '') {
                    $trans_data[] = array("amt2" => $final['netamt2'],
                        "amt" => $final['netamt'],"dcflag" => 'D',"trn_type" => 'SL',"type" => 'SL',"ac_code" => $final['ac_code']
                    );
                }
                if ($temp_data['slac_code'] != '') {
                    $trans_data[] = array(
                        "amt2" => $final['grossamt2'],"amt" => $final['grossamt'],"dcflag" => 'C',
                        "trn_type" => 'SL',"type" => 'SL',"ac_code" => $temp_data['slac_code']
                    );
                }
                if ($final['discac_code'] != '') {
                    $trans_data[] = array(
                        "amt2" => $final['discamt2'],"amt" => $final['discamt'],"dcflag" => 'D',"trn_type" => 'SL',
                        "type" => 'SL',"ac_code" => $final['discac_code']
                    );
                }
                if ($final['taxac_code'] != '') {
                    $trans_data[] = array(
                        "amt2" => $final['taxamt2'],"amt" => $final['taxamt'],"dcflag" => 'C',"trn_type" => 'SL',
                        "type" => 'SL',"ac_code" => $final['taxac_code']
                    );
                } 
                if ($final['salesid'] != '' && $final['salespercent'] != '') {
                    $brockrageamtusd = ((($final['grossamt2'] - $final['discamt2']) * $final['salespercent'])) / 100;
                      $brockrageamtinr = ((($final['grossamt'] - $final['discamt']) * $final['salespercent'])) / 100;        
                    $trans_data[] = array(
                        "amt2" => $brockrageamtusd,"amt" => $brockrageamtinr,
                        "dcflag" => 'D',"trn_type" => 'SL',"type" => 'SL',"ac_code" => $final['salesid']
                    );
                    $trans_data[] = array(
                        "amt2" => $brockrageamtusd,"amt" => $brockrageamtinr,
                        "dcflag" => 'C',"trn_type" => 'SL',
                        "type" => 'SL',"ac_code" => $final['salesid']
                    );
                }
                $i = 0;
                foreach ($charges_ac as $charges_acname) {
                    $trans_data[] = array(
                        "amt" => $charges_amt1[$i],
                        "amt2" => $charges_amt2[$i],
                        "ac_code" => $charges_ac[$i],
                        "type" => 'SL',
                        "trn_type" => 'SL',
                        "dcflag" => ($plusminse[$i] == '-') ? 'C' : 'D'  // Use a ternary operator to conditionally set the value
                    );

                    $i++;
                }
        }
        else if ($transaction_type == 'PR') {
                       /////trans_other_charges Data Insert Utility
                $i = 0;
                $all_final = array();
                $last_ids = "";
                $maindb->query("delete from trans_other_charges where doc_no='$insertId'");
                foreach ($charges_ac as $charges_acname) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("id" => $keyid[$i]), array("percentage" => $charges_per[$i]), array("amt1" => $charges_amt1[$i]), array("amt2" => $charges_amt2[$i]),array("doc_no" => $insertId),array("ac_code" => $charges_ac[$i]),array("plusmins" => $plusminse[$i]),array("type" => 'PU') );
                    $keyid2 = $keyid[$i];
//                    if (empty($keyid2)) {
                        $maindb->insert("trans_other_charges",$fdata);
                        $insertIds45 = $maindb->insert_id();
                        $last_ids23 .= $insertIds45 . ',';
                    $i++;
                }
                
            $trans_data = array();    
            if ($final['ac_code'] != '') {
                $trans_data[] = array(
                    "amt2" => $final['netamt2'],"amt" => $final['netamt'],"dcflag" => 'D',
                    "trn_type" => 'PR',"type" => 'PR',"ac_code" => $final['ac_code']
                );
            }    
            if ($temp_data['slac_code'] != '') {
                $trans_data[] = array(
                    "amt2" => $final['grossamt2'],"amt" => $final['grossamt'],
                    "dcflag" => 'C',"trn_type" => 'PR',"type" => 'PR',"ac_code" => $temp_data['slac_code']
                );
            }    
            if ($final['discac_code'] != '') {
                $trans_data[] = array(
                    "amt2" => $final['discamt2'],"amt" => $final['discamt'],
                    "dcflag" => 'D',"trn_type" => 'PR',"type" => 'PR',"ac_code" => $final['discac_code']
                );
            }    
            if ($final['taxac_code'] != '') {
                $trans_data[] = array(
                    "amt2" => $final['taxamt2'],"amt" => $final['taxamt'],"dcflag" => 'C',"trn_type" => 'PR',
                    "type" => 'PR',"ac_code" => $final['taxac_code']
                );
            }
            $i = 0;
            foreach ($charges_ac as $charges_acname) {
                $trans_data[] = array(
                    "amt" => $charges_amt1[$i],
                    "amt2" => $charges_amt2[$i],
                    "ac_code" => $charges_ac[$i],
                    "type" => 'PR',
                    "trn_type" => 'PR',
                    "dcflag" => ($plusminse[$i] == '-') ? 'C' : 'D'  // Use a ternary operator to conditionally set the value
                );
                $i++;
            }
        }
        else  if ($transaction_type == 'SR') {
                /////trans_other_charges Data Insert Utility
                $i = 0;
                $all_final = array();
                $last_ids = "";
                $maindb->query("delete from trans_other_charges where doc_no='$insertId'");
                foreach ($charges_ac as $charges_acname) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("id" => $keyid[$i]), array("percentage" => $charges_per[$i]), array("amt1" => $charges_amt1[$i]), array("amt2" => $charges_amt2[$i]),array("doc_no" => $insertId),array("ac_code" => $charges_ac[$i]),array("plusmins" => $plusminse[$i]),array("type" => 'PU') );
                    $keyid2 = $keyid[$i];
//                    if (empty($keyid2)) {
                        $maindb->insert("trans_other_charges",$fdata);
                        $insertIds45 = $maindb->insert_id();
                        $last_ids23 .= $insertIds45 . ',';
                    $i++;
                }
                
        if ($final['ac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['netamt2'],"amt" => $final['netamt'],"dcflag" => 'C',"trn_type" => 'SR',"type" => 'SR',"ac_code" => $final['ac_code']
            );
        }
        if ($temp_data['puac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['grossamt2'],"amt" => $final['grossamt'],"dcflag" => 'D',"trn_type" => 'SR',"type" => 'SR',"ac_code" => $temp_data['puac_code']
            );
        }
        if ($final['discac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['discamt2'],"amt" => $final['discamt'],"dcflag" => 'C',"trn_type" => 'SR',"type" => 'SR',"ac_code" => $final['discac_code']
            );
        }
        if ($final['taxac_code'] != '') {
            $trans_data[] = array(
                "amt2" => $final['taxamt2'],"amt" => $final['taxamt'],"dcflag" => 'D',"trn_type" => 'SR',"type" => 'SR',"ac_code" => $final['taxac_code']
            );
        }       
        if ($final['salesid'] != '') {
            $brockrageamtusd = ((($final['grossamt2'] - $final['discamt2']) * $final['salespercent'])) / 100;
            $brockrageamtinr = ((($final['grossamt'] - $final['discamt']) * $final['salespercent'])) / 100;        
            $trans_data[] = array("amt2" => $brockrageamtusd,"amt" => $brockrageamtinr,"dcflag" => 'C',
                "trn_type" => 'SR',"type" => 'SR',"ac_code" => $final['salesid']
            );
            $trans_data[] = array(
                "amt2" => $brockrageamtusd,"amt" => $brockrageamtinr,
                "dcflag" => 'D',"trn_type" => 'SR',"type" => 'SR',"ac_code" => $final['salesid']
            );
        }
            $i = 0;
            foreach ($charges_ac as $charges_acname) {
                $trans_data[] = array(
                    "amt" => $charges_amt1[$i],
                    "amt2" => $charges_amt2[$i],
                    "ac_code" => $charges_ac[$i],
                    "type" => 'SR',
                    "trn_type" => 'SR',
                    "dcflag" => ($plusminse[$i] == '-') ? 'C' : 'D'  // Use a ternary operator to conditionally set the value
                );

                $i++;
            }
        
        
        }
            $r = 0;
            foreach ($trans_data as $transaction) {
            $transaction["doc_no"] = $insertId;
            $fdatas = array(
                  "ac_code" => $transaction["ac_code"],"trn_type" => $transaction["trn_type"],"dcflag" => $transaction["dcflag"],"amt" => $transaction["amt"],
                  "amt2" => $transaction["amt2"],"type" => $transaction["type"],"crate" => $crate,"id" => $id,"invdate" => $invdate,"doc_no" => $insertId              
                );
                $maindb->insert("trans",$fdatas);
                  $transIds_3 = $maindb->insert_id();
                $r++;
            } 
        }
            if ($doc_no != '') 
            {
                if ($last_ids != '') 
                {
                    $last_ids= rtrim($last_ids, ',');
                   $maindb->query("delete from trans1 where prikey NOT IN($last_ids) and doc_no=$doc_no");
                }
            }         
            if ($insertId > 0) {
                if ($insertId) {

                    if ($doc_no == '') 
                    {               
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
        else if ($table == 'ledgmast') 
        {
            $heading = $_POST['heading'];
            $match = $_POST['match'];
            $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1     
            unset($data['user_id']);
            $eventdate = ($data['eventdate']);
            unset($data['eventdate']);
            unset($data['ship_addcheck']);
            
            $contact_person= ($data['contact_person']);
            unset($data['contact_person']);
            $positon= ($data['positon']);
            unset($data['positon']);
            $mobile_no= ($data['mobile_no']);
            unset($data['mobile_no']);
            $telephone_no= ($data['telephone_no']);
            unset($data['telephone_no']);
            $email_id= ($data['email_id']);
            unset($data['email_id']);
            $contactid= ($data['contactid']);
            unset($data['contactid']);
            
            
            $eventname = ($data['eventname']);
            unset($data['eventname']);
            unset($data['id']);
            $eventfilename = ($data['eventfilename']);                           
            $documentslink= ($data['documentslink']);                           
            unset($data['eventfilename']);
            unset($data['documentslink']);
            $keyid = ($data['keyid']);
            $ac_code = $data['ac_code'];            
            $documentsid = $data['documentsid'];            
            unset($data['documentsid']);            
            unset($data['ac_code']);            
            unset($data['keyid']);
            $columnsToUnset = $maindb->query("SELECT column_name FROM INFORMATION_SCHEMA.COLUMNS WHERE table_name = '$table3'")->result_array();
            $balnk_data=$columnsToUnset[1];
            $bankdetails = array();
            $clm='';
                foreach ($columnsToUnset as $column){                    
                    $clm = $column['column_name'];                                    
                    if (!empty($data[$clm]))
                    {    
                      $bankdetails = array_merge($bankdetails,array($clm => $data[$clm]));                                                            
                    } 
                    unset($data[$clm]);  
                }
            $date = ($data['date']);
            unset($data['date']);
            $daysname = ($data['daysname']);
            unset($data['daysname']);
            unset($data['match']);
            unset($data['table']);
            unset($data['type']);
            $final = array_merge($data);         
            if ($ac_code == '') {
                  $maindb->insert($table, $final);
                  $insertId = $maindb->insert_id();
              } else {
                  $where_condition = array(
                      'ac_code' => $ac_code,
                  );
                  $maindb->where($where_condition);
                  $maindb->update($table, $final);
                  $insertId = $ac_code;
              }
            if ($insertId > 0) {                
                //./////Image file save code                
                $allfiles = array();
                $main_files = '';
 
                if (isset($_FILES["eventfile"]) && !empty($_FILES["eventfile"]['name'])) 
                {  
                    $last_insert_id=$insertId;
                    $targetDir = "uploads/styles/"; // Your target directory where images will be saved
                    $uniqueFolderName = $last_insert_id; // Generate a unique folder name
                    // Create the directory with the unique name
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $uploadedImages = count($_FILES["eventfile"]["name"]);
                    $d=0;
                    for ($i = 0; $i < $uploadedImages; $i++) {
                        
                        $tmpFilePath = $_FILES["eventfile"]["tmp_name"][$i];
                        $eventfilename1 = ($eventfilename[$i]);
//                           print_r($eventfilename1);
                           unset($data['eventfilename']);
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $_FILES["eventfile"]["name"][$i];
                            $filePath = $uploadDir . $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $main_files .= base_url() . '' . $filePath . ',';
                                $allfiles[] = array("img" => base_url() . '' . $filePath, "ac_code" => $insertId, "user_id" => '1', "added_date" => date("Y-m-d H:i:s"),"filename" =>$eventfilename1);
                            } else {
//                                    $allfiles[] = array("img" => base_url() . '' . $filePath, "ac_code" => $insertId, "user_id" => '1', "added_date" => date("Y-m-d H:i:s"),"filename" =>$eventfilename1);
                            
                            }
                        }
                        else
                        {
                            $documentslinks=$documentslink[$d];
                                $allfiles[] = array("img" => $documentslinks, "ac_code" => $insertId, "user_id" => '1', "added_date" => date("Y-m-d H:i:s"),"filename" =>$eventfilename1);
                        }
                        $d++;
                    } 
                }
//              print_r($allfiles);
//              exit; 
                if (!empty($allfiles && !empty($allfiles[0]['filename']))) {
 
                    $this->db->query("delete from ledger_documents where ac_code = '$insertId'");                    
                    $main_files = rtrim($main_files, ',');
                    $this->db->insert_batch("ledger_documents", $allfiles);
                }
                
//                $this->db->query("delete from memorabledate where ac_code = '$insertId'");                    
                $i = 0;
                $all_final = array();
                $last_ids = "";
                foreach ($eventname as $daysname) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("keyid" => $keyid[$i]), array("daysname" => $daysname[$i]),array("ac_code" => $insertId),array("daysname" => $daysname),array("date" => date('Y-m-d 00:00:00', strtotime($eventdate[$i]))));

                    $keyid2 = $keyid[$i];
                    if (empty($keyid2)) {
                        $maindb->insert("memorabledate",$fdata);
                        $insertIds2 = $maindb->insert_id();
                        $last_ids2 .= $insertIds2 . ',';
                    } else {
                       
                        $maindb->where("keyid",$keyid2);
                        $maindb->update("memorabledate",$fdata);
                        $last_ids2 .= $keyid2 . ',';                    
                    }
                    $i++;
                }
                
//                $this->db->query("delete from ledger_contact_details where ac_code = '$insertId'");                    
                $i = 0;
                $all_final = array();
                $last_ids = "";
             
                foreach ($contact_person as $contact_per) {
                    $fdata=array();
                    $fdata = array("ac_code" => $insertId);
                    $fdata = array_merge($fdata,array("contact_person" => $contact_per[$i]),
                            array("ac_code" => $insertId),array("mobile_no" => $mobile_no[$i]),
                            array("positon" => ($positon[$i])),
                            array("telephone_no" => ($telephone_no[$i])),
                            array("email_id" => ($email_id[$i]))
                            );
             $contactid = $contactid[$i];
                    
                       

                    if (empty($contactid)) {
                        $maindb->insert("ledger_contact_details",$fdata);
                        $insertIds44 = $maindb->insert_id();
                        $last_ids44 .= $insertIds44 . ',';
                    } else {
                       
                        $maindb->where("id",$contactid);
                        $maindb->update("ledger_contact_details",$fdata);
                        $last_ids44 .= $contactid . ',';                    
                    }
                    $i++;
                }
                
                                
         
                $i = 0;
                $all_final = array();
                $last_ids = "";
                
                    foreach ($bankdetails['bankname'] as $bankname_name)
                    {
                        $fdata=array();
                        $fdata = array("ac_code" => $insertId);
                        $fdata = array_merge($fdata, array(
                        "bankid" => $bankdetails['bankid'][$i],
                        "bankname" => $bankname_name,
                        "bankaddress" =>$bankdetails['bankaddress'][$i],
                        "bankacno" => $bankdetails['bankacno'][$i],
                        "swiftcode" =>$bankdetails['swiftcode'][$i],
                        "bankcode" => $bankdetails['bankcode'][$i],
                        "branchcode" =>  $bankdetails['branchcode'][$i],
                        "currency" => $bankdetails['currency'][$i],
                        "acshort" =>  $bankdetails['acshort'][$i],
                        "acname" =>  $bankdetails['acname'][$i],
                        "bankacno2" =>$bankdetails['bankacno2'][$i],
                        "bankacno3" =>$bankdetails['bankacno3'][$i],
                        "currency2" =>$bankdetails['currency2'][$i],
                        "currency3" =>$bankdetails['currency3'][$i],
                        "interswiftcode" =>$bankdetails['interswiftcode'][$i],
                        "interbankname" =>$bankdetails['interbankname'][$i],
                        "companyadd" => $bankdetails['companyadd'][$i]
                    ));
                    $bankid=$bankdetails['bankid'][$i];
                    if(empty($fdata['bankid'][$i]))  
                    {       
//                        print_r('Bhairu');
                        $maindb->insert("ledmastbank",$fdata);
                        $insertIds_3 = $maindb->insert_id();
                        $last_ids .= $insertIds_3 . ',';

                    } else {
//                        print_r('Bhairu___');
                        $maindb->where("bankid",$bankid);
                        $maindb->update("ledmastbank",$fdata);
                        $last_ids .= $bankid . ',';
                    }
                    $i++;
                }          
            }            
            /*
                Eveent Date Code Here
             */
                if ($ac_code != '') 
                {                    
                    if ($last_ids2 != '') 
                    {          
                       $last_ids2 = rtrim($last_ids2, ',');
                       $maindb->query("delete from memorabledate where keyid NOT IN($last_ids2) and ac_code=$ac_code");
                    }
                    if ($last_ids != '') 
                    {
                        $last_ids= rtrim($last_ids, ',');
                       $maindb->query("delete from ledmastbank where bankid NOT IN($last_ids) and ac_code=$ac_code");
                    }
                    if ($last_ids44 != '') 
                    {
                        $last_ids44= rtrim($last_ids44, ',');
                       $maindb->query("delete from ledger_contact_details where contactid NOT IN($last_ids44) and ac_code=$ac_code");
                    }
                }          
            if ($insertId > 0) {
                if ($insertId) {

                    if ($ac_code == '') 
                    {               
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
            $rs = $maindb->query("SELECT TOP 1 COLUMN_NAME
        FROM INFORMATION_SCHEMA.KEY_COLUMN_USAGE
        WHERE OBJECTPROPERTY(OBJECT_ID(CONSTRAINT_SCHEMA + '.' + CONSTRAINT_NAME), 'IsPrimaryKey') = 1
          AND TABLE_NAME = '$table'")->row_array();

            $idrd = $rs['COLUMN_NAME'];
            $iddata = $data[$idrd];
            if ($match != '') {

                $match_exp = explode(",", $match);
                $sql22 = '';
                foreach ($match_exp as $match_res) {
                    $data_val = $data[$match_res];
                    $sql22 .= " $match='$data_val' and";
                }

                $sql22 = rtrim($sql22, 'and');

                if ($sql22 != '') {
//                echo "select * from $table where $sql22 ";
                    $check_count = $maindb->query("select * from $table where $sql22 ")->result_array();
                    if (count($check_count) > 0) {
                        echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Record already exist!'));
                    }
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


//           if ($table=='maincon'){
//            $itemdescription=($data['itemdescription']);  
//            $mainid=($data['mainid']);
//            unset($data['mainid']);
//            unset($data['itemdescription']);
//            $code=($data['code']);  
//            unset($data['code']);
//            $pcs=($data['pcs']);  
//            unset($data['pcs']);
//            $qty=($data['qty']);  
//            unset($data['qty']);
//            $discper=($data['discper']);  
//            unset($data['discper']);
//            $dcflag=($data['dcflag']);  
//            unset($data['dcflag']);
//            $tyn_type=($data['tyn_type']);  
//            unset($data['tyn_type']);
//            $rate=($data['rate']); 
//            unset($data['rate']);
//            $rate2=($data['rate2']);  
//            unset($data['rate2']);
//            $amt=($data['amt']);  
//            unset($data['amt']);
//            $amt2=($data['amt2']);  
//            unset($data['amt2']);  
//            unset($data['match']);  
//            unset($data['table']);  
//            unset($data['type']);  
//            $invdate=($data['invdate']);  
//            $timestamp = strtotime($invdate);
//            $formattedDateTime2 = date('Y-m-d 00:00:00', $timestamp);
//            $duedate=($data['duedate']);  
//            $timestamp = strtotime($duedate);
//            $formattedDateTime3 = date('Y-m-d 00:00:00', $timestamp);
//            unset($data['duedate']);
//            unset($data['invdate']);
//            $main_tyn_type=$tyn_type[0];
//            $final=array_merge($data,array("trn_type"=>$main_tyn_type),array("invdate"=>$formattedDateTime2),array("duedate"=>$formattedDateTime3)); 
//            $maindb->insert($table,$final);
//            $insertId = $maindb->insert_id();
//            if($insertId>0)
//            {  
//            $i=0;
//            $all_final=array();
//            foreach($qty as $qty_data)
//            {    
//            $fdata=array("mainid"=>$insertId);    
//            $fdata=array_merge($fdata,array("qty"=>$qty_data));    
//            $fdata=array_merge($fdata,array("pcs"=>$pcs[$i]));    
//            $fdata=array_merge($fdata,array("discper"=>$discper[$i]));    
//            $fdata=array_merge($fdata,array("dcflag"=>$dcflag[$i]));    
//            $fdata=array_merge($fdata,array("trn_type"=>'CO'));  
//            $fdata=array_merge($fdata,array("rate"=>$rate[$i]));  
//            $fdata=array_merge($fdata,array("rate2"=>$rate2[$i]));  
//            $fdata=array_merge($fdata,array("amt"=>$amt[$i]));  
//            $fdata=array_merge($fdata,array("amt2"=>$amt2[$i]));  
//            $fdata=array_merge($fdata,array("description"=>$itemdescription[$i]));  
//            $fdata=array_merge($fdata,array("code"=>$code[$i]));
//            $sql1='';
//            $sql2='';
//            if($insertId!='')
//            {
//            
//            $sql1.='"mainid",';    
//            $sql2.=$insertId.',';    
//            
//            $sql3.='"mainid"="'.$insertId.'",';    
//            }
//            if($qty_data!='')
//            {
//            $sql1.='"qty",';    
//            $sql2.=$qty_data.',';    
//            $sql3.='"qty"="'.$qty_data.'",';    
//            
//            
//            }
//            if($pcs[$i]!='')
//            {
//            $sql1.='"pcs",';    
//            $sql2.=$pcs[$i].',';  
//            $sql3.='"pcs"="'.$pcs[$i].'",';  
//            }
//            if($discper[$i]!='')
//            {
//            $sql1.='"discper",';    
//            $sql2.=$discper[$i].',';  
//            $sql3.='"discper"="'.$discper[$i].'",';  
//            }
//            if($tyn_type[$i]!='')
//            {
//            $sql1.='"trn_type",';    
//            $sql2.="'".$tyn_type[$i]."',";
//            $sql3.='"trn_type"="'.$tyn_type[$i].'",'; 
//            }
//            if($rate[$i]!='')
//            {
//            $sql1.='"rate",';    
//            $sql2.=$rate[$i].',';  
//            $sql3.='"rate"="'.$rate[$i].'",'; 
//            }
//            if($rate2[$i]!='')
//            {
//            $sql1.='"rate2",';    
//            $sql2.=$rate2[$i].','; 
//            $sql3.='"rate2"="'.$rate2[$i].'",'; 
//            }
//            if($amt[$i]!='')
//            {
//            $sql1.='"amt",';    
//            $sql2.=$amt[$i].','; 
//            $sql3.='"amt"="'.$amt[$i].'",'; 
//            }
//            if($amt2[$i]!='')
//            {
//            $sql1.='"amt2",';    
//            $sql2.=$amt2[$i].',';  
//            $sql3.='"amt2"="'.$amt2[$i].'",'; 
//            }
//            if($itemdescription[$i]!='')
//            {
//            $sql1.='"description",';    
//            $sql2.="'".$itemdescription[$i]."',";   
//            $sql3.='"description"="'.$itemdescription[$i].'",'; 
//            }
//            if($code[$i]!='')
//            {
//            $sql1.='"code",';    
//            $sql2.=$code[$i].',';  
//            $sql3.='"code"="'.$code[$i].'",'; 
//            }
//            $sql1= rtrim($sql1,',');
//            $sql2= rtrim($sql2,',');
//            $sql3= rtrim($sql3,',');
//            
//            
//            if($mainid=='')
//            {    
//            $sql="INSERT INTO CONTRAN ('.$sql1.',trn_type,dcflag) '
//                    . ' VALUES ('.$sql2.','CO','C')";
//            }
//            else
//            {
//              echo $sql3;  
//                
//              exit;  
//              $sql="INSERT INTO CONTRAN ('.$sql1.',trn_type,dcflag) '
//                    . ' VALUES ('.$sql2.','CO','C')";
//              
//            }    
//            $maindb->query($sql);
//            $all_final[]=$fdata;
//            $i++;
//            }
//        } 
//        }
//        
        if ($table == 'mperforma') {
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
            $final = array_merge($data, array("trn_type" => 'PF'), array("invdate" => $formattedDateTime2), array("duedate" => $formattedDateTime3));
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
                    $fdata = array_merge($fdata, array("trn_type" => 'PF'));
                    $fdata = array_merge($fdata, array("rate" => $rate[$i]));
                    $fdata = array_merge($fdata, array("rate2" => $rate2[$i]));
                    $fdata = array_merge($fdata, array("amt" => $amt[$i]));
                    $fdata = array_merge($fdata, array("amt2" => $amt2[$i]));
                    $fdata = array_merge($fdata, array("description" => $itemdescription[$i]));
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
        }

        if ($insertId > 0) {
            if ($insertId) {
                echo json_encode(array('success' => true, 'type' => '1', 'message' => 'Record edited successfully'));
            } else {
                echo json_encode(array('success' => false, 'type' => '1', 'message' => 'Failed to update ' . $heading));
            }
        } else {
            $this->MasterModel->edit_data($data, $user_id);
            echo json_encode(array('success' => true, 'type' => '2', 'message' => $heading . ' updated successfully'));
        }
    }
    public function delete_data() {
        $data = $_POST;
        $dbname = ($_SESSION['dbname']);
        $data2 = array("db" => $dbname
            , "requestData" => $data
        );
        $maindb = $this->load->database($dbname, TRUE);
//        print_r($data2);
//exit;
        $tables = explode(",", $data['table']);
        $delete_column = $data['delete_column'];
        $id = $data['id'];
        $delete_column = $data['delete_column'];
        $table = $tables[0];
        $table2 = $tables[1];

        
        $columns = $this->db->list_fields($tables[0]);
        $idrd = $columns[0];
        
        
        if ($table === 'ledgmast')
        {
            $colid1 = $maindb->query("SELECT mainid FROM maincon WHERE ac_code='$id' ")->row_array();
            $colid2 = $maindb->query("SELECT doc_no FROM trans WHERE ac_code='$id' ")->row_array();
            $colid3 = $maindb->query("SELECT doc_no FROM trans2 WHERE ac_code='$id' ")->row_array();
            $colid4 = $maindb->query("SELECT mainid FROM mperforma WHERE ac_code='$id' ")->row_array();
            if ($colid) {
                echo json_encode(array('success' => false, 'message' => "Record can't delete. Approval entries are there"));
            }
            else if($colid2)
            {
                echo json_encode(array('success' => false, 'message' => "Record can't delete. Entries are there against this account"));
            }
            else if($colid3)
            {
               echo json_encode(array('success' => false, 'message' => "Record can't delete. Invoice is part received/paid"));
            }
            else if($colid4)
            {
               echo json_encode(array('success' => false, 'message' => "Record can't delete. Proforma entries are there"));
            }  
                else 
            {
                $maindb->query("DELETE FROM $table WHERE $idrd='$id'");
                echo json_encode(array('success' => true, 'message' => 'Deleted successfully'));
            }
        }

        
        
        else 
        {
             $maindb->query("delete from $table where $idrd='$id'");
             echo json_encode(array('success' => true, 'message' => ' deleted successfully'));
        }
        
//        $idrd = $rs['COLUMN_NAME'];
       
    }
}
