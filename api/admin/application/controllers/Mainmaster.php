<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// It is main controller to access frontedn page dinamically 
class Mainmaster extends CI_Controller {
        
    public function __construct()
	{
	  parent::__construct();
	  $this->load->library('session');
	}
    function call_api($url,$data){
        $url = $url;
        // Convert the data to JSON
        $jsonData = json_encode($data);
    //            exit;
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
            CURLOPT_POSTFIELDS =>$jsonData,
            CURLOPT_HTTPHEADER => array(
            'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        return $response;
    }
        
    function get_extra_data(){
        if($_POST['type']=='diamonds')
        {
            if(!empty($_POST['selectedValues']))
            {    
                $selectedValues=implode(",",$_POST['selectedValues']);
                if($selectedValues!='')
                {    
                    $data= $this->db->query("select *,d.name as diamond_name"
                    . ",d2.name as cut"
                    . ",d3.name as shape "
                    . ",d4.name as color "
                    . ",d5.name as clarity "
                    . ",d6.name as pointers "
                    . ",d7.name as sieve_size "
                    . "from diamonds d "
                    . " LEFT JOIN diamond_masters d2 ON d2.id=d.diamond_cut "
                    . " LEFT JOIN diamond_masters d3 ON d3.id=d.diamond_shape "
                    . " LEFT JOIN diamond_masters d4 ON d4.id=d.diamond_color "
                    . " LEFT JOIN diamond_masters d5 ON d5.id=d.diamond_clarity "
                    . " LEFT JOIN diamond_masters d6 ON d6.id=d.diamond_pointers "
                    . " LEFT JOIN diamond_masters d7 ON d7.id=d.diamond_sieve_size "
                    . "where d.id  IN ($selectedValues)")->result_array();  
                    foreach($data as $data_res)
                    { ?>
                        <tr >
                            <td>
                                <?php echo $data_res['diamond_name']; ?>
                                <input type="hidden" name="diamond_name[]" value="<?php echo $data_res['diamond_name']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['cut']; ?>
                                <input type="hidden" name="diamond_cut[]" 
                                value="<?php echo $data_res['diamond_cut']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['shape']; ?>
                                <input type="hidden" name="diamond_shape[]" 
                                value="<?php echo $data_res['diamond_shape']; ?>" />
                            </td>                                                                
                            <td>
                                <?php echo $data_res['color']; ?>
                                <input type="hidden" name="diamond_color[]" 
                                value="<?php echo $data_res['diamond_color']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['clarity']; ?>
                                <input type="hidden" name="diamond_clarity[]" 
                                value="<?php echo $data_res['diamond_clarity']; ?>" />
                            </td>                                                                
                            <td>
                                <?php echo $data_res['pointers']; ?>
                                <input type="hidden" name="diamond_pointers[]" 
                                value="<?php echo $data_res['diamond_pointers']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['sieve_size']; ?>
                                <input type="hidden" name="diamond_sieve_size[]" 
                                value="<?php echo $data_res['diamond_sieve_size']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['diamond_rate']; ?>
                                <input type="hidden" name="diamond_diamond_rate[]" 
                                value="<?php echo $data_res['diamond_rate']; ?>" />
                            </td>
                        </tr>
                <?php
                    }    
                }    
            }else{
                echo '';    
            }    
        }else if($_POST['type']=='gemstones')
        {
            if(!empty($_POST['selectedValues']))
            {    
                $selectedValues=implode(",",$_POST['selectedValues']);
                if($selectedValues!='')
                {   
                    $selectedValues=implode(",",$_POST['selectedValues']);   
                    $data= $this->db->query("select *,d.name as gemstone_name"
                            . ",d2.name as type"
                        . ",d3.name as cut "
                        . ",d4.name as shape "
                        . ",d5.name as quality "
                        . ",d6.name as size "
                        . ",d7.name as origin "
                        . " from gemstone d "
                        . " LEFT JOIN gemstone_masters d2 ON d2.id=d.gemstone_type "
                        . " LEFT JOIN gemstone_masters d3 ON d3.id=d.gemstone_cut "
                        . " LEFT JOIN gemstone_masters d4 ON d4.id=d.gemstone_shape "
                        . " LEFT JOIN gemstone_masters d5 ON d5.id=d.gemstone_quality "
                        . " LEFT JOIN gemstone_masters d6 ON d6.id=d.gemstone_size "
                        . " LEFT JOIN gemstone_masters d7 ON d7.id=d.gemstone_origin "
                        . "where d.id  IN ($selectedValues)")->result_array();   
                    foreach($data as $data_res)
                    { ?>
                        <tr >                                                                
                            <td>
                                <?php echo $data_res['gemstone_name']; ?>
                                <input type="hidden" name="gemstone_name[]" value="<?php echo $data_res['gemstone_name']; ?>" />
                            
                            </td>  
                            <td>
                                <?php echo $data_res['type']; ?>
                                <input type="hidden" name="gemstone_type[]" 
                                        value="<?php echo $data_res['gemstone_type']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['cut']; ?>
                                <input type="hidden" name="gemstone_cut[]" 
                                        value="<?php echo $data_res['gemstone_cut']; ?>" />
                            </td>                                                                
                            <td>
                                <?php echo $data_res['shape']; ?>
                                <input type="hidden" name="gemstone_shape[]" 
                                        value="<?php echo $data_res['gemstone_shape']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['quality']; ?>
                                <input type="hidden" name="gemstone_quality[]" 
                                        value="<?php echo $data_res['gemstone_quality']; ?>" />
                            </td>                                                                
                            <td>
                                <?php echo $data_res['size']; ?>
                                <input type="hidden" name="gemstone_size[]" 
                                        value="<?php echo $data_res['gemstone_size']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['origin']; ?>
                                <input type="hidden" name="gemstone_origin[]" 
                                        value="<?php echo $data_res['gemstone_origin']; ?>" />
                            </td>  
                            <td>
                                <?php echo $data_res['gemstone_rate']; ?>
                                <input type="hidden" name="gemstone_rate[]" 
                                        value="<?php echo $data_res['gemstone_rate']; ?>" />
                            </td>  
                            
                        </tr>
                <?php
                    }    
                }
            }else {
                 echo '';    
            }
        }else if($_POST['type']=='pearls')
            {
                if(!empty($_POST['selectedValues']))
                {    
                    $selectedValues=implode(",",$_POST['selectedValues']);
                    if($selectedValues!='')
                    {    
                        $selectedValues=implode(",",$_POST['selectedValues']);   
                        $data= $this->db->query("select d.*,d.name as pearls_name"
                            . ",d2.name as type"
                            . ",d3.name as shape "
                            . ",d4.name as color "
                            . ",d5.name as size "
                            . ",d6.name as unit "
                            . " from pearls d "
                            . " LEFT JOIN pearl_masters d2 ON d2.id=d.pearl_type "
                            . " LEFT JOIN pearl_masters d3 ON d3.id=d.pearl_shape "
                            . " LEFT JOIN pearl_masters d4 ON d4.id=d.pearl_color "
                            . " LEFT JOIN pearl_masters d5 ON d5.id=d.pearl_size "
                            . " LEFT JOIN pearl_masters d6 ON d6.id=d.pearl_unit "
                            
                            . "where d.id  IN ($selectedValues)")->result_array();   
                        foreach($data as $data_res)
                        { ?>
                            <tr >
                                
                                <td>
                                    <?php echo $data_res['pearls_name']; ?>
                                    <input type="hidden" name="pearl_name[]" value="<?php echo $data_res['pearls_name']; ?>" />
                                
                                </td>  
                                <td>
                                    <?php echo $data_res['type']; ?>
                                    <input type="hidden" name="pearl_type[]" 
                                            value="<?php echo $data_res['pearl_type']; ?>" />
                                </td>  
                                <td>
                                    <?php echo $data_res['shape']; ?>
                                    <input type="hidden" name="pearl_shape[]" 
                                            value="<?php echo $data_res['pearl_shape']; ?>" />
                                </td>                                                                
                                <td>
                                    <?php echo $data_res['color']; ?>
                                    <input type="hidden" name="pearl_color[]" 
                                            value="<?php echo $data_res['pearl_color']; ?>" />
                                </td>  
                                <td>
                                    <?php echo $data_res['size']; ?>
                                    <input type="hidden" name="pearl_size[]" 
                                            value="<?php echo $data_res['pearl_size']; ?>" />
                                </td>                                                                
                                <td>
                                    <?php echo $data_res['unit']; ?>
                                    <input type="hidden" name="pearl_unit[]" 
                                            value="<?php echo $data_res['pearl_unit']; ?>" />
                                </td>   
                                <td>
                                    <?php echo $data_res['pearl_rate']; ?>
                                    <input type="hidden" name="pearl_rate[]" 
                                            value="<?php echo $data_res['pearl_rate']; ?>" />
                                </td>
                            </tr>
                <?php   }    
                }
            }
            else
            {
                echo '';    
            }
        }
        else if($_POST['type']=='dimensions')
        {
    
            $styles=$_POST['id'];   
            if($styles=='')
            {
                $styles=$_POST['styles'];   
            }    
            $url_handle=$_POST['url_handle'];   
            if($styles!='' && ($url_handle=='orders' || $url_handle=='inventory'))
            {
                $sql=",(select dimensions_value from orders_dimensions where order_id='$styles' and dimensions=d.id) as dimensions_value_added"
                        . ",(select dimensions_value from styles_dimensions where style_id='$styles' and dimensions=d.id) as dimensions_value_added2";    
            }    
            else if($styles!='' && $url_handle!='orders')
            {
            $sql=",(select dimensions_value from styles_dimensions where style_id='$styles' and dimensions=d.id) as dimensions_value_added";    
            }    
            if(!empty($_POST['selectedValues']))
            {  

                $selectedValues=implode(",",$_POST['selectedValues']);
                if($selectedValues!='')
                {    
                $selectedValues=implode(",",$_POST['selectedValues']);   
                $data="select dm.name as unit,d.* $sql "
                        . " from dimensions d "
                        . " LEFT JOIN dimensions_masters dm ON dm.id=d.dimensions_unit "
                        . ""
                        . "where d.id  IN ($selectedValues)";     
                $data= $this->db->query("select dm.name as unit,d.* $sql "
                        . " from dimensions d "
                        . " LEFT JOIN dimensions_masters dm ON dm.id=d.dimensions_unit "
                        . ""
                        . "where d.id  IN ($selectedValues)")->result_array();   
                foreach($data as $data_res)
                {  ?>
                    <tr >
                    <td>
                    <?php

                    echo $data_res['name']; 
                    ?>
                    <input type="hidden" name="dimensions_name[]" value="<?php echo $data_res['name']; ?>" />
                    </td>  
                    <td>
                    <?php
                    if($data_res['dimensions_value_added']!='')
                    {
                        $dmn=$data_res['dimensions_value_added'];    
                    }    
                    else if($data_res['dimensions_value_added2']!='')
                    {
                        $dmn=$data_res['dimensions_value_added2'];    
                    }    
                    if($dmn=='')
                    {    
                    ?>
                    <input <?php if($dmn!='') { echo 'readonly'; } ?> type="<?php if($data_res['dimensions_value_added']>0) { echo 'text';  } ?>"  
                        placeholder="Dimesions Value" class="form-control" name="dimensions_value[]" 
                    value="<?php echo $dmn; ?>" />
                    <?php 
                    }
                    else
                    {
                        ?>
                        <input <?php if($dmn!='') { echo 'readonly'; } ?>  type="<?php if($data_res['dimensions_value_added']>0) { echo 'text';  } ?>"  
                            placeholder="Dimesions Value" class="form-control" name="dimensions_value[]" 
                        value="<?php echo $dmn; ?>" />
                        
                        <?php
            
                        }    
                        ?>
                    
                        </td>
                        <td>
                        <?php
                            echo $data_res['unit']; 
                        ?>
                        </td>    
                        </tr>
                        <?php
                    }    
                }
            }else
            {
            echo '';    
            }
        }
    }
    
 // Page load based on the page url 
    public function index(){   
        $data['validate']="name,short_name,type";  
        $fullUrl = base_url(uri_string());
        ### partner masters
        $handle=$_POST['handle'];
        $main_final_url=$_POST['main_final_url'];
        if($main_final_url!='')
        {
        $fullUrl =$main_final_url;    
        }
           
        $url_details = $this->db->query("select access_scope from urls where handle='$fullUrl' and status!='2'")->row_array();
        if(isset($url_details)){
            if(count($url_details) > 0 ){
                $session_id = $_SESSION['id'];
                $user_type = $this->db->query("select a.*,b.name as access_type from users a " 
                            ."  left outer join user_type b on b.id=a.type"         
                            ."  where a.id='$session_id' and a.status!='2'")->row_array();
                $access = $user_type['access_type'];
                if($access != $url_details['access_scope'] && $url_details['access_scope'] != 'All'){
                    $data = array();
                    $this->load->view('extra/unauthorize', $data, true);
                    exit;
                }
            
            }
        }
        $data['main_final_url']=$fullUrl ;
        if (strpos($fullUrl, base_url().'religion') !== false) {            
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master ='religion' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
        
        } else if (strpos($fullUrl, base_url().'nric') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='nric' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                //                  $data['display2']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
            //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
            //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
        
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        } else if (strpos($fullUrl, base_url().'age_group') !== false) {
                        
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='age_group' ) order by sort ASC")->result_array();
    //                 
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
        
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='stock_no')
                    {
                        unset($form_data_res['data']);
                        $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                        $perameters2[]=$form_data_res;
                    }
                }
                else
                {    
                    $perameters2[]=$form_data_res;
                }
            }
            if($part=='3')
            {
                $perameters3[]=$form_data_res;
            }
        }
        $data['perameters']=$perameters;
        $data['perameters2']=$perameters2;
        $data['perameters3']=$perameters3;
        if($handle!='')
        {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
        }

    } else if (strpos($fullUrl, base_url().'add_course') !== false) {
                        
        $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='add_course' ) order by sort ASC")->result_array();                 
        foreach($form_data as $form_data_res)
        {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='user_id')
                    {
                        $users = $this->db->query("select id,full_name as name from users "
                        . " where status ='0' and type = '1'")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $users));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='change_course_id')
                    {
                        $course = $this->db->query("select * from courses where status != '2'")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $course));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
        
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='stock_no')
                    {
                        unset($form_data_res['data']);
                        $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                        $perameters2[]=$form_data_res;
                    }
                }
                else
                {    
                    $perameters2[]=$form_data_res;
                }
            }
            if($part=='3')
            {
                $perameters3[]=$form_data_res;
            }
        }
        $data['perameters']=$perameters;
        $data['perameters2']=$perameters2;
        $data['perameters3']=$perameters3;
        if($handle!='')
        {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
        } 
        
        } else if (strpos($fullUrl, base_url().'country') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='country' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
                $data['perameters']=$perameters;
                $data['perameters2']=$perameters2;
                $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        } 

        else if (strpos($fullUrl, base_url().'class_location') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='class_location' ) order by sort ASC")->result_array();                 
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));               
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                    
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                    
        }else if (strpos($fullUrl, base_url().'payment') !== false) {
                      
                $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='payment' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
    //                  $data['display2']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
    //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
    //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                }
                }
                $data['perameters']=$perameters;
                $data['perameters2']=$perameters2;
                $data['perameters3']=$perameters3;
                if($handle!='')
                {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
                }
                  
        }else if (strpos($fullUrl, base_url().'city') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='city' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }  
                    else if($form_data_res['id']=='state_id')
                    {
                        $state = $this->db->query("select * from state where status != '2'")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $state));
                        $perameters[] = $form_data_res;
                    }else if($form_data_res['id']=='country_id')
                    {
                        $country = $this->db->query("select * from country where status != '2'")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $country));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                    }
                    if($part=='2')
                    {
                        if($form_data_res['data']=='blank')
                        {
                            if($form_data_res['id']=='stock_no')
                            {
                                unset($form_data_res['data']);
                                $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                                $perameters2[]=$form_data_res;
                            }
                        }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'state') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='state' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }  
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    } 
                    else if($form_data_res['id']=='country_id')
                    {
                        $country = $this->db->query("select id,name  as name from country where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $country));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'billing_address') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='billing_address' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'mycart') !== false) {
                
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='mycart' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'users') !== false) {
                      
        $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='users' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'student_user') !== false) {

            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='student_user' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    } 
                    else if($form_data_res['id']=='course') {
                        $currency = $this->db->query("select id,name  as name from courses where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='nationlity') {
                        $currency = $this->db->query("select id,name  as name from nationlity where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='religion') {
                        $currency = $this->db->query("select id,name  as name from religion where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='country') {
                        $currency = $this->db->query("select id,name  as name from country where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='state') {
                        $currency = $this->db->query("select id,name  as name from state where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='race') {
                        $currency = $this->db->query("select id,name  as name from races where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }  else if($form_data_res['id']=='dialect') {
                        $currency = $this->db->query("select id,name  as name from dialect where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                            if($form_data_res['id']=='stock_no')
                            {
                                unset($form_data_res['data']);
                                $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                                $perameters2[]=$form_data_res;
                            }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'parent_user') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='parent_user' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }  
                    else if($form_data_res['id']=='country')
                    {
                        $currency = $this->db->query("select id,name  as name from country where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } else if($form_data_res['id']=='state')
                    {
                        $currency = $this->db->query("select id,name  as name from state where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } 
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                if($part=='2')
                {
                        if($form_data_res['data']=='blank')
                        {
                            if($form_data_res['id']=='stock_no')
                            {
                                unset($form_data_res['data']);
                                $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                                $perameters2[]=$form_data_res;
                            }
                        }
                        else
                        {    
                        $perameters2[]=$form_data_res;
                        }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'race') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='race' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'main_quiz') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='main_quiz' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }                   
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }  
                    else if($form_data_res['id']=='course_id')
                    {
                        $courses = $this->db->query("select id,name from courses where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $courses));
                        $perameters[] = $form_data_res;
                    } 
                    else if($form_data_res['id']=='expiry_time')
                    {   $time_arr = [];
                        $expiry_day_time =  [];
                        for($i = 1; $i <= 24;  $i++){
                          $expiry_day_time['id'] = $i.":00";
                          $expiry_day_time['name'] = $i.":00";
                          array_push($time_arr, $expiry_day_time );
                        }
                           
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $time_arr));
                        $perameters[] = $form_data_res;
                    } 
                   
                    else if($form_data_res['id']=='badge_id')
                    {
                        $badges = $this->db->query("select id,title as name from badges where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $badges));
                        $perameters[] = $form_data_res;
                    }  
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'child_quiz_answer') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='child_quiz_answer' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }  
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='main_quiz_id')
                    {
                        $main_quiz = $this->db->query("select id, name from main_quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $main_quiz));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='quiz_id')
                    {
                        $quiz = $this->db->query("select id, name from quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $quiz));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                    
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
                $data['perameters']=$perameters;
                $data['perameters2']=$perameters2;
                $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
         } else if (strpos($fullUrl, base_url().'tr_homework_doc') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='tr_homework_doc' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
    //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
    //                  $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        } else if (strpos($fullUrl, base_url().'upcoming_classes') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='upcoming_classes' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }
                    else if($form_data_res['id']=='course_type_id')
                    {
                        $currency = $this->db->query("select id,name as name from course_type where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='chapter_id')
                    {
                        $currency = $this->db->query("select id,CONCAT(chapter_no, '. ', chapter_name)as name from chapter where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        } else if (strpos($fullUrl, base_url().'reschedule_classes') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='reschedule_classes' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    } 
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                                $data['type']=$form_data_res['data'];  
                                $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        } else if (strpos($fullUrl, base_url().'quiz_type') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='quiz_type' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='quiz_id')
                    {
                        $quiz_type = $this->db->query("select id,name from quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $quiz_type));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'quiz_options') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='quiz_options' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    } 
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='quiz_id')
                    {
                        $quiz_type = $this->db->query("select id,name from quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $quiz_type));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='main_quiz_id')
                    {
                        $main_quiz_type = $this->db->query("select id,name from main_quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $main_quiz_type));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                    $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'quiz') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='quiz' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }
                    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }   
                    else if($form_data_res['id']=='main_quiz_id')
                    {
                        $main_quiz = $this->db->query("select id,name from main_quiz where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $main_quiz));
                        $perameters[] = $form_data_res;
                    } 
                    else if($form_data_res['id']=='select_type')
                    {
                        $quiz_type = $this->db->query("select id,name from quiz_type where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $quiz_type));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'logs') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='logs' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        } else if (strpos($fullUrl, base_url().'ongoing_course') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='ongoing_course' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    } 
                    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'homework') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='homework' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    } 
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='course_id')
                    {
                        $courses = $this->db->query("select id,name from courses where status != '2' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $courses));
                        $perameters[] = $form_data_res;
                    } 
                    else if($form_data_res['id']=='homework_chapter_id')
                    {
                        $perameters[] = $form_data_res;
                    } 
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                }
                else
                {    
                    $perameters2[]=$form_data_res;
                }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }        
        else if (strpos($fullUrl, base_url().'course_certificate') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_certificate' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    } 
                    else if($form_data_res['id']=='user_id')
                    {
                        $users = $this->getUserListToCompletedCoursesCertification();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $users));
                        $perameters[] = $form_data_res;
                    }  
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }
        else if (strpos($fullUrl, base_url().'announcement') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='announcement' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }
        else if (strpos($fullUrl, base_url().'course_gallery_folders') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_gallery_folders' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }
                    else if($form_data_res['id']=='course_id')
                    {
                        $courses = $this->db->query("select id,name from courses where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $courses));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'course_gallery') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_gallery' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            { 
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'news') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='news' ) order by sort ASC")->result_array();
            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    } 
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else if($form_data_res['id']=='news_type')
                    {
                        $news = array();
                        $news[0] = array('id' => 'event','name'=>'Event');
                        $news[1] = array('id' => 'announcement','name'=>'Announcement');
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $news));
                        $perameters[] = $form_data_res;
                    } 
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                    
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                    $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
                echo $message2 = $this->load->view('extra/loadform', $data, true);
                exit;        
            }
                    
        }else if (strpos($fullUrl, base_url().'get_started') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='get_started' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                    $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                    $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        }else if (strpos($fullUrl, base_url().'events') !== false) {
                      
            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='events' ) order by sort ASC")->result_array();

            foreach($form_data as $form_data_res)
            {
                $part=$form_data_res['part'];
                if($part=='1')
                {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='type')
                        {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                        }
                    }else if($form_data_res['id']=='location_id')
                    {
                        $result = $this->db->query("select id, name from class_location"
                      . " where status!='2'")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $result));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                }
                  
                if($part=='2')
                {
                    if($form_data_res['data']=='blank')
                    {
                        if($form_data_res['id']=='stock_no')
                        {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                        }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                }
                if($part=='3')
                {
                $perameters3[]=$form_data_res;
                
                }
            }
            $data['perameters']=$perameters;
            $data['perameters2']=$perameters2;
            $data['perameters3']=$perameters3;
            if($handle!='')
            {
            echo $message2 = $this->load->view('extra/loadform', $data, true);
            exit;        
            }
                  
        } 
                 
                  else if (strpos($fullUrl, base_url().'course_subscription') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_subscription' ) order by sort ASC")->result_array();

                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                    
                  
                   else if (strpos($fullUrl, base_url().'course_rating') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_rating' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }  
                     else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }  else if($form_data_res['id']=='course_id')
                  {
                    $course = $this->db->query("select id,name from courses where 1 and status = '0' ")->result_array();
                    unset($form_data_res['data']);
                    $form_data_res = array_merge($form_data_res, array("data" => $course));
                    $perameters[] = $form_data_res;
                  } 
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }  else if (strpos($fullUrl, base_url().'course_material') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_material' ) order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  } 
                     else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }                   
                  
                  else if (strpos($fullUrl, base_url().'course_exercise') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_exercise' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                   else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  
                   else if (strpos($fullUrl, base_url().'contact_us') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='contact_us' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  else if (strpos($fullUrl, base_url().'complain') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='complain' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  } 
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  else if (strpos($fullUrl, base_url().'ch_homework_doc') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='ch_homework_doc' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                    
                  
                  else if (strpos($fullUrl, base_url().'child_quiz_result') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='child_quiz_result' ) order by sort ASC")->result_array();

                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  } 
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else if($form_data_res['id']=='main_quiz_id')
                  {
                      $quiz_main = $this->db->query("select id,name from main_quiz where status != '2' ")->result_array();
                      unset($form_data_res['data']);
                      $form_data_res = array_merge($form_data_res, array("data" => $quiz_main));
                      $perameters[] = $form_data_res;
                  }
                  else if($form_data_res['id']=='badge_id')
                  {
                      $badge = $this->db->query("select id,title as name from badges where status != '2' ")->result_array();
                      unset($form_data_res['data']);
                      $form_data_res = array_merge($form_data_res, array("data" => $badge));
                      $perameters[] = $form_data_res;
                  }
                  else if($form_data_res['id']=='user_id')
                  {
                    $result = $this->db->query("select e.id, e.username as name from child_quiz_answer a "
                        . "left outer join users e on e.id=a.user_id "
                    . " where a.status!='2' group by a.user_id")->result_array();
                      unset($form_data_res['data']);
                      $form_data_res = array_merge($form_data_res, array("data" => $result));
                      $perameters[] = $form_data_res;
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                    echo $message2 = $this->load->view('extra/loadform', $data, true);
                    exit;        
                  }
                  
                  } 
                  
                  else if (strpos($fullUrl, base_url().'child_parent_relationship') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='child_parent_relationship' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }
                   else if($form_data_res['id']=='request_status')
                    {
                        $currency = $this->db->query("select id,name  as name from select_type where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    } 
                  
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                   else if (strpos($fullUrl, base_url().'child_homework') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='child_homework' ) order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }  
                 else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else if($form_data_res['id']=='course_id')
                  {
                    $courses = $this->db->query("select id,name from courses where status != '2' ")->result_array();
                    unset($form_data_res['data']);
                    $form_data_res = array_merge($form_data_res, array("data" => $courses));
                    $perameters[] = $form_data_res;
                  }
                  else if($form_data_res['id']=='user_id')
                  {
                    $users = $this->db->query("select id,username as name from users where status != '2' ")->result_array();
                    unset($form_data_res['data']);
                    $form_data_res = array_merge($form_data_res, array("data" => $users));
                    $perameters[] = $form_data_res;
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                    else if (strpos($fullUrl, base_url().'check_in') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='check_in' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  } 
                
                  
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  
                   else if (strpos($fullUrl, base_url().'chapter') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='chapter' ) order by sort ASC")->result_array();

//$chaptersrno = $this->db->query("SELECT COUNT(*) + 1 AS srno FROM chapter  where status!=2 ")->row_array();
//            $chaptersrno = $chaptersrno['srno'];  
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
//                  else if($form_data_res['id']=='chapter_no')
//                  {
//                     unset($form_data_res['data']);
//                      $form_data_res = array_merge($form_data_res, array("data" => $chaptersrno));
//                      $perameters[]=$form_data_res;
//                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  } 
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  
                    else if (strpos($fullUrl, base_url().'card') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='card' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                   else if (strpos($fullUrl, base_url().'bookmark') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='bookmark' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }else if (strpos($fullUrl, base_url().'badges') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='badges' ) order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }else if (strpos($fullUrl, base_url().'add_account') !== false) {
                    $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='add_account' ) order by sort ASC")->result_array();
                    foreach($form_data as $form_data_res)
                    {
                    $part=$form_data_res['part'];
                    if($part=='1')
                    {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {

                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {

                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else if($form_data_res['id']=='account_id')
                    {
                        $main_quiz = $this->db->query("select id, name from user_type where status != '2' ")->result_array();
                        $typeArr = array();
                        foreach($main_quiz as $list){
                            if($list['name'] != 'Student' && $list['name'] != 'Parent'){
                                array_push($typeArr,$list);
                            }
                            
                        }
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $typeArr));
                        $perameters[] = $form_data_res;
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                    }
                    
                    if($part=='2')
                    {
                    if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='stock_no')
                    {
                        unset($form_data_res['data']);
                        $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                        $perameters2[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters2[]=$form_data_res;
                    }
                    }
                    if($part=='3')
                    {
                        $perameters3[]=$form_data_res;
                    
                    }
                    }
                        $data['perameters']=$perameters;
                        $data['perameters2']=$perameters2;
                        $data['perameters3']=$perameters3;
                        if($handle!='')
                        {
                            echo $message2 = $this->load->view('extra/loadform', $data, true);
                            exit;        
                        }
                    
                    }  
                  
                  else if (strpos($fullUrl, base_url().'dialect') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='dialect' ) order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                    $data['display']=(explode(",",$form_data_res['data']));
                    $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
                    $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
                    $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                    $data['table']=$form_data_res['data'];    
                    $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                    $data['title']=$form_data_res['data']."";
                    $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                    $data['type']=$form_data_res['data'];  
                    $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                    $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                    unset($form_data_res['data']);
                    $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                    $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                    $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                     $perameters3[]=$form_data_res;
                  
                  }
                  }
                    $data['perameters']=$perameters;
                    $data['perameters2']=$perameters2;
                    $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                     echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }else if (strpos($fullUrl, base_url().'slider') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='slider' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  else if (strpos($fullUrl, base_url().'course_type') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='course_type' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  else if (strpos($fullUrl, base_url().'tutorials') !== false) {
                    $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='tutorial' ) order by sort ASC")->result_array();
                    foreach($form_data as $form_data_res)
                    {
                    $part=$form_data_res['part'];
                    if($part=='1')
                    {
                    if($form_data_res['id']=='maincontroller')
                    {
                        $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                        $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                        $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                        $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                    }    
                    else if($form_data_res['id']=='maintitle')
                    {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                    $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                        $perameters[]=$form_data_res;
                    }
                    }
                    
                    if($part=='2')
                    {
                    if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='stock_no')
                    {
                    unset($form_data_res['data']);
                    $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                    $perameters2[]=$form_data_res;
                    }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                    }
                    if($part=='3')
                    {
                    $perameters3[]=$form_data_res;
                    
                    }
                    }
                    $data['perameters']=$perameters;
                    $data['perameters2']=$perameters2;
                    $data['perameters3']=$perameters3;
                    if($handle!='')
                    {
                    echo $message2 = $this->load->view('extra/loadform', $data, true);
                    exit;        
                    }
                   
                    }

                    else if (strpos($fullUrl, base_url().'tutorial_credit_transactions') !== false) {
                        $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='tutorial_credit_transactions' ) order by sort ASC")->result_array();
                        foreach($form_data as $form_data_res)
                        {
                        $part=$form_data_res['part'];
                        if($part=='1')
                        {
                        if($form_data_res['id']=='maincontroller')
                        {
                        $data['master']=$form_data_res['data'];
                        }    
                        else if($form_data_res['id']=='delete_id')
                        {
                        $data['delete_id']=$form_data_res['data'];
                        }    
                        else if($form_data_res['id']=='maindisplay')
                        {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                        }    
                        else if($form_data_res['id']=='maindisplay2')
                        {
                        $data['display2']=(explode(",",$form_data_res['data']));
                        }    
                        else if($form_data_res['id']=='edits')
                        {
                        $data['edits']=(explode(",",$form_data_res['data']));
                        }    
                        else if($form_data_res['id']=='table')
                        {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                        }    
                        else if($form_data_res['id']=='maintitle')
                        {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                        }    
                        else if($form_data_res['data']=='blank')
                        {
                        if($form_data_res['id']=='type')
                        {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                        }
                        }
                        else
                        {    
                        $perameters[]=$form_data_res;
                        }
                        }
                        
                        if($part=='2')
                        {
                        if($form_data_res['data']=='blank')
                        {
                        if($form_data_res['id']=='stock_no')
                        {
                        unset($form_data_res['data']);
                        $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                        $perameters2[]=$form_data_res;
                        }
                        }
                        else
                        {    
                        $perameters2[]=$form_data_res;
                        }
                        }
                        if($part=='3')
                        {
                        $perameters3[]=$form_data_res;
                        
                        }
                        }
                        $data['perameters']=$perameters;
                        $data['perameters2']=$perameters2;
                        $data['perameters3']=$perameters3;
                        if($handle!='')
                        {
                        echo $message2 = $this->load->view('extra/loadform', $data, true);
                        exit;        
                        }
                       
                        }

                        else if (strpos($fullUrl, base_url().'tutorial_subscription_plan') !== false) {
                      
                            $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='tutorial_subscription_plan' ) order by sort ASC")->result_array();
          //                  print_r($form_data);
          //                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                            foreach($form_data as $form_data_res)
                            {
                            $part=$form_data_res['part'];
                            if($part=='1')
                            {
                            if($form_data_res['id']=='maincontroller')
                            {
                            $data['master']=$form_data_res['data'];
                            }    
                            else if($form_data_res['id']=='delete_id')
                            {
                            $data['delete_id']=$form_data_res['data'];
                            }    
                            else if($form_data_res['id']=='maindisplay')
                            {
                            $data['display']=(explode(",",$form_data_res['data']));
          //                  $data['display2']=(explode(",",$form_data_res['data']));
                            $data['edits']=$data['display2'];     
                            }    
                            else if($form_data_res['id']=='maindisplay2')
                            {
          //                  $data['display']=(explode(",",$form_data_res['data']));
                            $data['display2']=(explode(",",$form_data_res['data']));
                            }    
                            else if($form_data_res['id']=='edits')
                            {
          //                  $data['display']=(explode(",",$form_data_res['data']));
                            $data['edits']=(explode(",",$form_data_res['data']));
                            }    
                            else if($form_data_res['id']=='table')
                            {
                            $data['table']=$form_data_res['data'];    
                            $perameters[]=$form_data_res;
                            }    
                            else if($form_data_res['id']=='maintitle')
                            {
                            $data['title']=$form_data_res['data']."";
                            $data['title2']=$form_data_res['data']." List";
                            }  
                            else if($form_data_res['id']=='period')
                            {
                                $planName = array();
                                $planName[0] = array('id' => 'annually','name'=>'Annually');
                                $planName[1] = array('id' => 'quaterly','name'=>'Quaterly');
                                $planName[2] = array('id' => 'monthly','name'=>'Monthly');
                                unset($form_data_res['data']);
                                $form_data_res = array_merge($form_data_res, array("data" => $planName));
                                $perameters[] = $form_data_res;
                            }    
                            else if($form_data_res['data']=='blank')
                            {
                            if($form_data_res['id']=='type')
                            {
                            $data['type']=$form_data_res['data'];  
                            $perameters[]=$form_data_res;
                            }
                            }
                            else
                            {    
                            $perameters[]=$form_data_res;
                            }
                            }
                            
                            if($part=='2')
                            {
                            if($form_data_res['data']=='blank')
                            {
                            if($form_data_res['id']=='stock_no')
                            {
                            unset($form_data_res['data']);
                            $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                            $perameters2[]=$form_data_res;
                            }
                            }
                            else
                            {    
                            $perameters2[]=$form_data_res;
                            }
                            }
                            if($part=='3')
                            {
                            $perameters3[]=$form_data_res;
                            
                            }
                            }
                            $data['perameters']=$perameters;
                            $data['perameters2']=$perameters2;
                            $data['perameters3']=$perameters3;
                            if($handle!='')
                            {
                            echo $message2 = $this->load->view('extra/loadform', $data, true);
                            exit;        
                            }
                           
                            }
                  else if (strpos($fullUrl, base_url().'get_started') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='get_started' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  }                  
                  
                  else if (strpos($fullUrl, base_url().'subject') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='subject' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  
                else if (strpos($fullUrl, base_url().'courses') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='courses' ) order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='course_type_id')
                    {
                        $currency = $this->db->query("select id,name as name from course_type where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='age_group_id')
                    {
                        $currency = $this->db->query("select id,CONCAT(min_age, '-', max_age)as name from age_group where 1 and status = '0' ")->result_array();
                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $currency));
                        $perameters[] = $form_data_res;
                    }
                    else if($form_data_res['id']=='lecture_days')
                    {
                        $days = array();
                        $days[0] = array('id' => 'Sunday','name'=>'Sunday');
                        $days[1] = array('id' => 'Monday','name'=>'Monday');
                        $days[2] = array('id' => 'Tuesday','name'=>'Tuesday');
                        $days[3] = array('id' => 'Wednesday','name'=>'Wednesday');
                        $days[4] = array('id' => 'Thursday','name'=>'Thursday');
                        $days[5] = array('id' => 'Friday','name'=>'Friday');
                        $days[6] = array('id' => 'Saturday','name'=>'Saturday');

                        unset($form_data_res['data']);
                        $form_data_res = array_merge($form_data_res, array("data" => $days));
                        $perameters[] = $form_data_res;
                    }
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
                  
                  } 
                  
                  else if (strpos($fullUrl, base_url().'event_transaction') !== false) {
                      
                    $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='event_transaction' ) order by sort ASC")->result_array();
                    foreach($form_data as $form_data_res)
                    {
                    $part=$form_data_res['part'];
                    if($part=='1')
                    {
                    if($form_data_res['id']=='maincontroller')
                    {
                    $data['master']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='delete_id')
                    {
                    $data['delete_id']=$form_data_res['data'];
                    }    
                    else if($form_data_res['id']=='maindisplay')
                    {
                    $data['display']=(explode(",",$form_data_res['data']));
                    $data['edits']=$data['display2'];     
                    }    
                    else if($form_data_res['id']=='maindisplay2')
                    {
                    $data['display2']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='edits')
                    {
                    $data['edits']=(explode(",",$form_data_res['data']));
                    }    
                    else if($form_data_res['id']=='table')
                    {
                    $data['table']=$form_data_res['data'];    
                    $perameters[]=$form_data_res;
                    }
                    else if($form_data_res['id']=='maintitle')
                    {
                    $data['title']=$form_data_res['data']."";
                    $data['title2']=$form_data_res['data']." List";
                    }    
                    else if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='type')
                    {
                    $data['type']=$form_data_res['data'];  
                    $perameters[]=$form_data_res;
                    }
                    }
                    else
                    {    
                    $perameters[]=$form_data_res;
                    }
                    }
                    
                    if($part=='2')
                    {
                    if($form_data_res['data']=='blank')
                    {
                    if($form_data_res['id']=='stock_no')
                    {
                    unset($form_data_res['data']);
                    $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                    $perameters2[]=$form_data_res;
                    }
                    }
                    else
                    {    
                    $perameters2[]=$form_data_res;
                    }
                    }
                    if($part=='3')
                    {
                    $perameters3[]=$form_data_res;
                    
                    }
                    }
                    $data['perameters']=$perameters;
                    $data['perameters2']=$perameters2;
                    $data['perameters3']=$perameters3;
                    if($handle!='')
                    {
                    echo $message2 = $this->load->view('extra/loadform', $data, true);
                    exit;        
                    }
                    
                    } 
                                    
                    else if (strpos($fullUrl, base_url().'user_tutorial_subscription') !== false) {
                      
                        $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='user_tutorial_subscription' ) order by sort ASC")->result_array();
                        foreach($form_data as $form_data_res)
                        {
                        $part=$form_data_res['part'];
                        if($part=='1')
                        {
                        if($form_data_res['id']=='maincontroller')
                        {
                        $data['master']=$form_data_res['data'];
                        }    
                        else if($form_data_res['id']=='delete_id')
                        {
                        $data['delete_id']=$form_data_res['data'];
                        }    
                        else if($form_data_res['id']=='maindisplay')
                        {
                        $data['display']=(explode(",",$form_data_res['data']));
                        $data['edits']=$data['display2'];     
                        }    
                        else if($form_data_res['id']=='maindisplay2')
                        {
                        $data['display2']=(explode(",",$form_data_res['data']));
                        }    
                        else if($form_data_res['id']=='edits')
                        {
                        $data['edits']=(explode(",",$form_data_res['data']));
                        }    
                        else if($form_data_res['id']=='table')
                        {
                        $data['table']=$form_data_res['data'];    
                        $perameters[]=$form_data_res;
                        }
                        else if($form_data_res['id']=='maintitle')
                        {
                        $data['title']=$form_data_res['data']."";
                        $data['title2']=$form_data_res['data']." List";
                        }    
                        else if($form_data_res['data']=='blank')
                        {
                        if($form_data_res['id']=='type')
                        {
                        $data['type']=$form_data_res['data'];  
                        $perameters[]=$form_data_res;
                        }
                        }
                        else
                        {    
                        $perameters[]=$form_data_res;
                        }
                        }
                        
                        if($part=='2')
                        {
                        if($form_data_res['data']=='blank')
                        {
                        if($form_data_res['id']=='stock_no')
                        {
                        unset($form_data_res['data']);
                        $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                        $perameters2[]=$form_data_res;
                        }
                        }
                        else
                        {    
                        $perameters2[]=$form_data_res;
                        }
                        }
                        if($part=='3')
                        {
                        $perameters3[]=$form_data_res;
                        
                        }
                        }
                        $data['perameters']=$perameters;
                        $data['perameters2']=$perameters2;
                        $data['perameters3']=$perameters3;
                        if($handle!='')
                        {
                        echo $message2 = $this->load->view('extra/loadform', $data, true);
                        exit;        
                        }
                        
                        } 
                  else if (strpos($fullUrl, base_url().'nationlity') !== false) {
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where ( master='$handle' OR master='nationlity' ) order by sort ASC")->result_array();
//                  print_r($form_data);
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                    $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                    $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                    $data['display']=(explode(",",$form_data_res['data']));
                    $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='maindisplay2')
                  {
                    $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
                    $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  if($handle!='')
                  {
                    echo $message2 = $this->load->view('extra/loadform', $data, true);
                    exit;        
                  }
                  
                  }
                  
                  
                  
		  else if (strpos($fullUrl, base_url().'user_management') !== false) { 
                  
                  $form_data=$this->db->query("SELECT * FROM `form_build` where (master='$handle' OR master='user_management') order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {    
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }
                  else if($form_data_res['id']=='company_name')
                  {
                  $currency=$this->db->query("select id,name from company_name")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }   
                  else if($form_data_res['id']=='user_type')
                  {
                  $currency=$this->db->query("select id,name from user_type order by sort ASC  ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
		  
                  else if($form_data_res['id']=='usermanagement_customdiv')
                  {
                  unset($form_data_res['data']);
                  $message2 = "<div id='load_extra_data'>".$this->load->view('extra/usermanagement', $data, true).'</div>';
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='gemstones_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="gemstones";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='pearls_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="pearls";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='edit_extra_function')
                  {
                  $data['edit_extra_function_id']=$form_data_res['data'];
                  $data['edit_extra_function']=$form_data_res['addfunction'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  } 
                  else if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
//                  exit;
                  $perameters[]=$form_data_res;
                  }
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['maintitle2']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['id']=='maintitle2')
                  {
                  $data['maintitle2']=$form_data_res['data']."";
                  }    
                      
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  if($part=='11')
                  {
                  if($form_data_res['name']=='shipping_country' || $form_data_res['name']=='billing_country')
                  {
                  $currency=$this->db->query("select id,name from country ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters11[]=$form_data_res;    
                  }
                  else
                  {
                  $perameters11[]=$form_data_res;    
                  }      
                  
                  }
                  }
                    $data['perameters']=$perameters;
                    $data['perameters2']=$perameters2;
                    $data['perameters3']=$perameters3;
                    $data['perameters11']=$perameters11;
                    $data['add_extra_btn']="1";
                  
                  if($handle!='')
                  {
                  echo $message2 = $this->load->view('extra/loadform', $data, true);
                  exit;        
                  }
//                  exit;
                  }  
                  		  
		  else if (strpos($fullUrl, base_url().'inventory') !== false) { 
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='inventory'  order by sort ASC")->result_array();
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }
                  else if($form_data_res['id']=='vendor')
                  {
                  $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='2' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
				  else if($form_data_res['id']=='customer')
                  {
                  $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='2' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
				  else if($form_data_res['id']=='date')
                  {
                  unset($form_data_res['data']);
				  $date=date("Y-m-d");
                  $form_data_res= array_merge($form_data_res,array("data"=>$date));  
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='category')
                  {
                  $currency=$this->db->query("select id,name as name from categories where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='subcategory')
                  {
                  $currency=$this->db->query("select id,name as name from subcategories where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='metal')
                  {
                  $currency=$this->db->query("select id,name as name from metals where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='metal_finish')
                  {
                  $currency=$this->db->query("select id,name as name from metal_finishes where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='diamonds')
                  {
                  $currency=$this->db->query("select id,name as name from diamonds where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='gemstones')
                  {
                  $currency=$this->db->query("select id,name as name from gemstone where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='dimensions')
                  {
                  $currency=$this->db->query("select id,name as name from dimensions where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='partners')
                  {
                  $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='1' ")->result_array();
                  
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='pearls')
                  {
                  $currency=$this->db->query("select id,name as name from pearls where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='diamonds_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="diamonds";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='gemstones_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="gemstones";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='pearls_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="pearls";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  } 
                  else if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
//                  exit;
                  $perameters[]=$form_data_res;
                  }
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  }
                  
                  else if (strpos($fullUrl, base_url().'style_estimator') !== false) { 
                      
                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='style_estimator' order by sort ASC")->result_array();
//                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='category' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }
                  else if($form_data_res['id']=='vendor')
                  {
                  $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='2' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
				  else if($form_data_res['id']=='customer')
                  {
                  $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='2' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
				  else if($form_data_res['id']=='date')
                  {
                  unset($form_data_res['data']);
				  $date=date("Y-m-d");
                  $form_data_res= array_merge($form_data_res,array("data"=>$date));  
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='category')
                  {
                  $currency=$this->db->query("select id,name as name from categories where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='subcategory')
                  {
                  $currency=$this->db->query("select id,name as name from subcategories where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='metal')
                  {
                  $currency=$this->db->query("select id,name as name from metals where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='metal_finish')
                  {
                  $currency=$this->db->query("select id,name as name from metal_finishes where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='diamonds')
                  {
                  $currency=$this->db->query("select id,name as name from diamonds where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='gemstones')
                  {
                  $currency=$this->db->query("select id,name as name from gemstone where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='dimensions')
                  {
                  $currency=$this->db->query("select id,name as name from dimensions where type='1' ")->result_array();
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='partners')
                  {
                    $currency=$this->db->query("select id,partner_customer_id as name from partners_customer where type='1' ")->result_array();
                    
                    unset($form_data_res['data']);
                    $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                    $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='pearls')
                  {
                    $currency=$this->db->query("select id,name as name from pearls where type='1' ")->result_array();
                    unset($form_data_res['data']);
                    $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                    $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='diamonds_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="diamonds";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='gemstones_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="gemstones";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  else if($form_data_res['id']=='pearls_customdiv')
                  {
                  unset($form_data_res['data']);
                  $data['type']="pearls";
                  $message2 = $this->load->view('extra/design_diamond_part', $data, true);
//                  $data=$this->load->view('extra/design_diamond_part', $data, true);
                  $form_data_res= array_merge($form_data_res,array("data"=>$message2));
                  $perameters[]=$form_data_res;
                  }  
                  
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
//                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  } 
                  else if($form_data_res['id']=='type')
                  {
                  $data['type']=$form_data_res['data'];  
//                  exit;
                  $perameters[]=$form_data_res;
                  }
                  else if($form_data_res['id']=='maindisplay2')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='edits')
                  {
//                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['edits']=(explode(",",$form_data_res['data']));
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  $perameters[]=$form_data_res;
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  }
                  		  
                  else if (strpos($fullUrl, 'form-build') !== false) {
                  
                  $currency=array();
                  $currency[]=array("id"=>"text","name"=>"text");
                  $currency[]=array("id"=>"select","name"=>"select");
                  $currency[]=array("id"=>"select2","name"=>"select2");
                  $currency[]=array("id"=>"date","name"=>"date");
                  $currency[]=array("id"=>"hidden","name"=>"hidden");
                  $currency[]=array("id"=>"textarea","name"=>"textarea");
                  $currency[]=array("id"=>"radio","name"=>"radio");
                  $currency[]=array("id"=>"checkbox","name"=>"checkbox");
                  
                  
                  $form_data=$this->db->query("SELECT * FROM `form_build` where master='form' order by sort ASC")->result_array();
                  foreach($form_data as $form_data_res)
                  {
                  $part=$form_data_res['part'];
                  if($part=='1')
                  {
                  if($form_data_res['id']=='maincontroller')
                  {
                  $data['master']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='delete_id')
                  {
                  $data['delete_id']=$form_data_res['data'];
//                  $data['delete_column']=$form_data_res['data'];
                  }    
                  else if($form_data_res['id']=='maindisplay')
                  {
                  $data['display']=(explode(",",$form_data_res['data']));
                  $data['display2']=(explode(",",$form_data_res['data']));
                  $data['edits']=$data['display2'];     
                  }    
                  else if($form_data_res['id']=='table')
                  {
                  $data['table']=$form_data_res['data'];    
                  }    
                  else if($form_data_res['id']=='maintitle')
                  {
                  $data['title']=$form_data_res['data']."";
                  $data['title2']=$form_data_res['data']." List";
                  }    
                  else if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='type')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$currency));
                  $perameters[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters[]=$form_data_res;
                  }
                  }
                  
                  if($part=='2')
                  {
                  if($form_data_res['data']=='blank')
                  {
                  if($form_data_res['id']=='stock_no')
                  {
                  unset($form_data_res['data']);
                  $form_data_res= array_merge($form_data_res,array("data"=>$api_res['data']));
                  $perameters2[]=$form_data_res;
                  }
                  }
                  else
                  {    
                  $perameters2[]=$form_data_res;
                  }
                  }
                  if($part=='3')
                  {
                  $perameters3[]=$form_data_res;
                  
                  }
                  }
                  $data['perameters']=$perameters;
                  $data['perameters2']=$perameters2;
                  $data['perameters3']=$perameters3;
                  $data['type']="1";  
                  }
                  
                  #categorys master
                  
                  else if (strpos($fullUrl, base_url().'login') !== false) {
                  
                  if(empty($_SESSION['id']))
                  {    
                  $defult_file='masters/default/master/default';
                  }
                  else
                  {    
                  redirect(base_url()."dashboard");
                  }
                  
                  }
                  else if (strpos($fullUrl, base_url().'dashboard') !== false) {
                  $defult_file='masters/default/master/default';
                  $data['mainpage']='dashboard';
                  }
                  
                  if($handle=='')
                  {
                  if($defult_file=='')
                  {    
                  $defult_file='masters/default/master/default';
                  }
                  
                        $this->load->view($defult_file,$data);
                  }
                  
	}  
        
    public function fetch_data() {  
        $requestData = $_POST;
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];
        $this->load->model('MasterModel');
        $data = $this->MasterModel->fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection,$requestData);
        echo json_encode($data);
    }
        
    public function add_extra_data() {
        $data = $_POST;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MasterModel');
        $extra_id=$data['extra_id'];
        unset($data['extra_id']);
        $check=$this->db->query("select * from partners_customer where user_id='$extra_id'")->result_array();
        $users=$this->db->query("select * from users where id='$extra_id'")->result_array();
        $type=($users[0]['type']);
        if(count($check)==0)
        {
            $added_date=date("Y-m-d H:i:s");    
            $data= array_merge($data,array("user_id"=>$extra_id),array("type"=>$type));   
            $data= array_merge($data,array("first_name"=>$users[0]['firstname']),array("last_name"=>$users[0]['lastname']));   
            $data= array_merge($data,array("email_address"=>$users[0]['email_id']),array("company_name"=>$users[0]['company_name']));   
            $data= array_merge($data,array("phone_number"=>$users[0]['mobile_no']),array("partner_customer_type"=>$type));   
            $data= array_merge($data,array("added_date"=>$added_date));   
            
            $this->db->insert('partners_customer', $data);
            echo json_encode(array('success' => true,'type' => '1', 'message' => 'Data added successfully'));
        }
        else 
        {
            $added_date=date("Y-m-d H:i:s");    
            $data= array_merge($data,array("user_id"=>$extra_id),array("type"=>$type));   
            $data= array_merge($data,array("first_name"=>$users[0]['firstname']),array("last_name"=>$users[0]['lastname']));   
            $data= array_merge($data,array("email_address"=>$users[0]['email_id']),array("company_name"=>$users[0]['company_name']));   
            $data= array_merge($data,array("phone_number"=>$users[0]['mobile_no']),array("partner_customer_type"=>$type));   
            $data= array_merge($data,array("updated_date"=>$added_date));
            
            $this->db->where('user_id', $extra_id);
            $this->db->update('partners_customer', $data);

            echo json_encode(array('success' => true,'type' => '1', 'message' => 'Data updated successfully'));
            }
        }
        
    public function add_data() {
        $data = $_POST;
        $profileimage = $_FILES;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $table = $data['table'];
        $uid = $data['id'];

        if($table == 'class_location'){
            $image_query = $this->db->query("select image from class_location where id = '$uid'")->row_array();
            if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
            {   
              $targetDir = "uploads/location";
              $uniqueFolderName = $last_insert_id;
              $uploadDir = $targetDir . $uniqueFolderName . "/";
              mkdir($uploadDir);
              $tmpFilePath = $profileimage["image"]["tmp_name"];
                  if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                      $fileName = '';
                      $fileName = $profileimage["image"]["name"];
                      $filePath = $uploadDir . $fileName;
                      if (move_uploaded_file($tmpFilePath, $filePath)) {
                          $image_main_files .= base_url() . '' . $filePath ;
                      } 
                  } 
            }
            
            if(empty($image_main_files)){
                $image_main_files= $image_query['image'];
            }else{
                $image_main_files= $image_main_files;
            }      

        }
        
        if($table == 'courses'){
            $image_query = $this->db->query("select image from courses where id = '$uid'")->row_array();
            if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
            {   
                $targetDir = "uploads/courseimage";
                $uniqueFolderName = $last_insert_id;
                $uploadDir = $targetDir . $uniqueFolderName . "/";
                mkdir($uploadDir);
                $tmpFilePath = $profileimage["image"]["tmp_name"];
                if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                    $fileName = '';
                    $fileName = $profileimage["image"]["name"];
                    $filePath = $uploadDir . $fileName;
                    if (move_uploaded_file($tmpFilePath, $filePath)) {
                        $image_main_files .= base_url() . '' . $filePath ;
                    } 
                }   
            }
            if(empty($image_main_files)){
                $image_main_files= $image_query['image'];
            }else{
                $image_main_files= $image_main_files;
            }      

        }
        
        if($table == 'homework'){
            $image_query = $this->db->query("select homework_material from homework where id = '$uid'")->row_array();
            $files = array_filter($profileimage['homework_material']['name']);
            $total_count = count($profileimage['homework_material']['name']);
            $targetDir = "uploads/homeworkdocimage";
            for ($i=0; $i < $total_count; $i++) { 
                if (isset($profileimage["homework_material"]) && !empty($profileimage["homework_material"]['name'][$i])) 
                {   
                    $uniqueFolderName = $last_insert_id;
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $tmpFilePath = $profileimage["homework_material"]["tmp_name"][$i];
                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $profileimage["homework_material"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $image_main_files .= base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }
                if(empty($image_main_files)){
                    $image_main_files= $image_query['homework_material'];
                }else{
                    $image_main_files= rtrim($image_main_files,",");
                }      
            }

            if($table == 'course_certificate'){
                $image_query = $this->db->query("select certificate from course_certificate where id = '$uid'")->row_array();
                if (isset($profileimage["certificate"]) && !empty($profileimage["certificate"]['name'])) 
                {   
                    $targetDir = "uploads/certificate";
                    $uniqueFolderName = $last_insert_id;
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $tmpFilePath = $profileimage["certificate"]["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $profileimage["certificate"]["name"];
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $image_main_files .= base_url() . '' . $filePath ;
                        } 
                    }   
                }
                if(empty($image_main_files)){
                    $image_main_files= $image_query['certificate'];
                }else{
                    $image_main_files= $image_main_files;
                }     
            }

            if($table == 'course_gallery'){
                $image_query = $this->db->query("select gallery from course_gallery where id = '$uid'")->row_array();
                $files = array_filter($profileimage['gallery']['name']);
                $total_count = count($profileimage['gallery']['name']);
                $targetDir = "uploads/course_gallery";
                for ($i=0; $i < $total_count; $i++) { 
                    if (isset($profileimage["gallery"]) && !empty($profileimage["gallery"]['name'][$i])) {   
                        $uniqueFolderName = $last_insert_id;
                        $uploadDir = $targetDir . $uniqueFolderName . "/";
                        mkdir($uploadDir);
                        $tmpFilePath = $profileimage["gallery"]["tmp_name"][$i];

                        if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                            $fileName = '';
                            $fileName = $profileimage["gallery"]["name"][$i];
                            $filePath = $uploadDir .rand(). '-'. $fileName;
                            if (move_uploaded_file($tmpFilePath, $filePath)) {
                                $image_main_files .= base_url() . '' . $filePath . ',' ;
                            }
                        }
                    }
                }

                if(empty($image_main_files)){
                    $image_main_files= $image_query['gallery'];
                }else{
                    $image_main_files= rtrim($image_main_files,",");
                }      
            }

            if($table == 'news'){
                $image_query = $this->db->query("select news_image from news where id = '$uid'")->row_array();
                 if (isset($profileimage["news_image"]) && !empty($profileimage["news_image"]['name'])) 
                 {   
                  $targetDir = "uploads/events";
                  $uniqueFolderName = $last_insert_id;
                  $uploadDir = $targetDir . $uniqueFolderName . "/";
                  mkdir($uploadDir);
                  $tmpFilePath = $profileimage["news_image"]["tmp_name"];
                      if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                          $fileName = '';
                          $fileName = $profileimage["news_image"]["name"];
                          $filePath = $uploadDir . rand().'-'.$fileName;
                          if (move_uploaded_file($tmpFilePath, $filePath)) {
                              $image_main_files .= base_url() . '' . $filePath ;
                          } else {                            
                          }
                      }
                 }
                 if(empty($image_main_files)){
                 $image_main_files= $image_query['news_image'];
                 }else{
                 $image_main_files= $image_main_files;
                 }      
 
             }

             if($table == 'tutorial_subscription_plan'){
                $image_query = $this->db->query("select bg_image from tutorial_subscription_plan where id = '$uid'")->row_array();
                 if (isset($profileimage["bg_image"]) && !empty($profileimage["bg_image"]['name'])) 
                 {   
                  $targetDir = "uploads/tutorials";
                  $uniqueFolderName = $last_insert_id;
                  $uploadDir = $targetDir . $uniqueFolderName . "/";
                  mkdir($uploadDir);
                  $tmpFilePath = $profileimage["bg_image"]["tmp_name"];
                      if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                          $fileName = '';
                          $fileName = $profileimage["bg_image"]["name"];
                          $filePath = $uploadDir . rand().'-'.$fileName;
                          if (move_uploaded_file($tmpFilePath, $filePath)) {
                              $image_main_files .= base_url() . '' . $filePath ;
                          } else {                            
                          }
                      }
                 }
                 if(empty($image_main_files)){
                 $image_main_files= $image_query['bg_image'];
                 }else{
                 $image_main_files= $image_main_files;
                 }      
 
             }

            if($table == 'events'){
                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                 $targetDir = "uploads/events";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["image"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["image"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
            }

            if($table == 'course_material'){
                 
             $image_query = $this->db->query("select material_content from course_material where id = '$uid'")->row_array();
                if (isset($profileimage["material_content"]) && !empty($profileimage["material_content"]['name'])) 
                {   
                 $targetDir = "uploads/course_material";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["material_content"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["material_content"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
                if(empty($image_main_files)){
                    $image_main_files= $image_query['material_content'];
                }else{
                    $image_main_files= $image_main_files;
                }      
             }
           
            if($table == 'quiz_options'){
                $image_query = $this->db->query("select image from quiz_options where id = '$uid'")->row_array();

                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                 $targetDir = "uploads/quizoptionsimage";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["image"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["image"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
                if(empty($image_main_files)){
                   $image_main_files= $image_query['image'];
                }else{
                   $image_main_files= $image_main_files;
                }      
            }
         
            if($table == 'users' || $main_final_url == base_url().'student_user' ||  $main_final_url == base_url().'parent_user' ){
                $password = md5($data['password']);
                 $data['password'] = $password;
                 
                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                 $targetDir = "uploads/usersimage";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["image"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["image"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
            }

            if($table == 'tr_homework_doc'){
                if (isset($profileimage["doc"]) && !empty($profileimage["doc"]['name'])) 
                {   
                 $targetDir = "uploads/homeworkdocimage";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["doc"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["doc"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }  
            }

            if($table == 'contact_us'){
                if (isset($profileimage["document_upload"]) && !empty($profileimage["document_upload"]['name'])) 
                {   
                 $targetDir = "uploads/contactimge";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["document_upload"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["document_upload"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
                
            }

             if($table == 'get_started'){
                $image_query = $this->db->query("select url from get_started where id = '$uid'")->row_array();

                if (isset($profileimage["url"]) && !empty($profileimage["url"]['name'])) 
                {   
                 $targetDir = "uploads/getstarted";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["url"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["url"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
                 if(empty($image_main_files)){
                    $image_main_files= $image_query['url'];
                }else{
                    $image_main_files= $image_main_files;
                }       
            }
            
             if($table == 'ch_homework_doc'){
                if (isset($profileimage["doc"]) && !empty($profileimage["doc"]['name'])) 
                {   
                 $targetDir = "uploads/ch_homeworkimage";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["doc"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["doc"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
            }

            if($table == 'badges'){
                $image_query = $this->db->query("select image from badges where id = '$uid'")->row_array();
                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                 $targetDir = "uploads/badges";
                 $uniqueFolderName = $last_insert_id;
                 $uploadDir = $targetDir . $uniqueFolderName . "/";
                 mkdir($uploadDir);
                 $tmpFilePath = $profileimage["image"]["tmp_name"];
                     if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                         $fileName = '';
                         $fileName = $profileimage["image"]["name"];
                         $filePath = $uploadDir . $fileName;
                         if (move_uploaded_file($tmpFilePath, $filePath)) {
                             $image_main_files .= base_url() . '' . $filePath ;
                         } else {                            
                         }
                     }
                     else
                     {
                     }
                }
                if(empty($image_main_files)){
                    $image_main_files= $image_query['image'];
                }else{
                    $image_main_files= $image_main_files;
                }      
            }

            if($table == 'slider'){
               $id=$data['id'];
               $imagedata = $this->db->query("select * from slider where id='$id'")->row_array();

               if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                    $targetDir = "uploads/sliderimage/";
                    $uniqueFolderName = $last_insert_id;
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $tmpFilePath = $profileimage["image"]["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $profileimage["image"]["name"];
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $image_main_files .= base_url() . '' . $filePath ;
                        } else {                            
                        }
                    }
                    else
                    {
                    }
               }
               if (empty($image_main_files))
               {
                  $image_main_files=$imagedata['image'];
               }
           }
          
            $this->load->model('MasterModel');
           
            if($match!='' && $table !== 'child_homework' && $table !== 'course_gallery_folders' && $table !== 'course_gallery' && $table !== 'announcement' && $table !== 'add_course' && $table !== 'add_account')
            {   
            if($image_main_files != '')   {
                $data['image'] = $image_main_files;
            }
            $name_exp = array_filter(explode(",", $match));
            $table = $data['table'];
            $sql="";
            if (!empty($name_exp)) {
                $i=0;
                foreach ($name_exp as $names) {                
                    if($i==0)
                    {
                        if($data['type']!='')
                        {    
                        $type=$data["type"];
                        $sql.= "type='$type' and ";    
                        }
                    }
                    if ($names != '') {
                        $mdata = $this->replaceSingleQuote($data[$names]);
                        $sql.=$names."='$mdata' and ";
                    }
                    $i++;
                }
            }

            $sql=rtrim($sql,'and ');
            if($table === 'badges') {
                $query = $this->db->query("select count(1) as cnt from `$table` where status != '2' and $sql")->result_array();
                $query[0]['cnt'] = 0;
            }else {
                $query = $this->db->query("select count(1) as cnt from `$table` where 1 and $sql")->result_array();
            }
            $idCheck = !empty($data['id']) ? (int)$data['id'] : 0 ;
            if($idCheck  > 0){
                $cnt=0;   
            }else{
              $cnt=$query[0]['cnt'];   
            }
           
            
            if ($cnt>0) {
                // Metal already exists, show an error message
                echo json_encode(array('success' => false, 'message' => $heading.' already exists'));
                return;
            }
        }  
        if($data['id']=='')
        { 
            if($table == 'users'){
                $username = $data['username'];
                $fullname = $data['full_name'];
                $email = $data['contact_email'];
                $number = $data['number'];
                
                $check_user = $this->db->query("select * from users where username = '$username' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Username Already Exists'));
                    exit;
                }
                $check_user1 = $this->db->query("select * from users where status = '0' and full_name = '$fullname' ")->result_array();
                if(count($check_user1)>0){
                    echo json_encode(array('success' => false, 'message' => 'Fullname Already Exists'));
                    exit;
                    
                }
               
                $check_user3 = $this->db->query("select * from users where status = '0' and contact_email = '$email' ")->result_array();
                if(count($check_user3)>0){
                    echo json_encode(array('success' => false, 'message' => 'Email Already Exists'));
                    exit;
                }
                $check_user4 = $this->db->query("select * from users where status = '0' and number = '$number' ")->result_array();
                if(count($check_user4)>0){
                    echo json_encode(array('success' => false, 'message' => 'Number Already Exists'));
                    exit;
                }

            }
            
           if($table == 'chapter'){
                $chapter_no = $data['chapter_no'];
                $chapter_name = $data['chapter_name'];
                $course_id = $data['course_id'];                
                $check_user = $this->db->query("select * from chapter where chapter_no = '$chapter_no' and course_id = '$course_id' and status = '0' ")->result_array();

                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Chapter No Already Exists'));
                    exit;
                }
                $check_user1 = $this->db->query("select * from chapter where status = '0' and chapter_name = '$chapter_name'  and course_id = '$course_id'   ")->result_array();
                if(count($check_user1)>0){
                    echo json_encode(array('success' => false, 'message' => 'Chapter Name Already Exists'));
                exit;
                    
                }
            }

             if($table == 'course_exercise'){
                $exercise_no = $data['exercise_no'];
                $task = $data['task'];
                
                $check_user = $this->db->query("select * from course_exercise where exercise_no = '$exercise_no' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Exercise No Already Exists'));
                    exit;
                }
                $check_user1 = $this->db->query("select * from course_exercise where status = '0' and task = '$task' ")->result_array();
                if(count($check_user1)>0){
                    echo json_encode(array('success' => false, 'message' => 'task Already Exists'));
                exit;
                    
                }
             }

              if($table == 'course_material'){
                    $title = $data['title'];
              
                $check_user = $this->db->query("select * from course_material where statud='0' and title = '$title' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'title Already Exists'));
                    exit;
                }
             }

            if($table == 'billing_address'){
                $full_name = $data['full_name'];
                $email = $data['email'];
                $number = $data['number'];

                $check_user = $this->db->query("select * from billing_address where full_name =  '$full_name' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'fullname Already Exists'));
                    exit;
                }
                $heck_user = $this->db->query("select * from billing_address where email = '$email' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'email Already Exists'));
                    exit;
                }
                 $check_user = $this->db->query("select * from billing_address where number = '$number' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'number Already Exists'));
                    exit;
                }
               
            }
             
            if ($table == 'child_parent_relationship'){
                    $child_id = $data['child_id'];
                    $parent_id = $data['parent_id'];

                    foreach ($child_id  as $id ){
                        $student = $id;
                        $check_relation = $this->db->query("select a.*,b.username from child_parent_relationship a left outer join users b on b.student_id = a.child_id where a.parent_id = '$parent_id' and a.child_id = '$student' and a.status = '0' ")->result_array();
                        
                        if(count($check_relation)>0){
                            $student_name = $check_relation[0]['username'];
                            echo json_encode(array('success' => false, 'message' => $student_name.' Already Exists In Relationship'));
                            exit; 
                        }
                    }               
            }
            if ($table == 'main_quiz'){
            
                $course_id = $data['course_id'];
                $percent_creteria = (int)$data['percent_creteria'];
                $mainQuiz = $this->db->query("SELECT id, percent_creteria FROM main_quiz WHERE course_id = $course_id AND status != '2'")->result_array();
                $availableCriteria = 0 ;
                foreach ($mainQuiz as $results) { 
                    $availableCriteria += $results['percent_creteria']; 
                }
                $availableCriteriaTotal = (int)$availableCriteria + (int)$percent_creteria;
                $remaining  = 100 - $availableCriteria;
                if ($remaining > 0 ){
                    $criteria_message = 'Percentage criteria exceeded remaining only '.$remaining;
                }else{
                    $criteria_message = 'Percentage criteria exceeded';
                }
                
                if($availableCriteriaTotal > 100){
                    echo json_encode(array('success' => false,'type' => '1', 'message' => $heading.' '.$criteria_message));
                    exit;
                }
               
            }
            if($table == 'add_account'){
                $username = $data['username'];
                $check_user = $this->db->query("select * from users where status='0' and username = '$username' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Username Already Exists'));
                    exit;
                }
                $contact_email = $data['contact_email'];
                $check_user = $this->db->query("select * from users where status='0' and contact_email = '$contact_email' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Contact email Already Exists'));
                    exit;
                }

                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                    $targetDir = "uploads/usersimage/";
                    $uniqueFolderName = $last_insert_id;
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $tmpFilePath = $profileimage["image"]["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $profileimage["image"]["name"];
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $image_main_files .= base_url() . '' . $filePath ;
                        } else {                            
                        }
                    }
                    else
                    {
                    }
               }

                $data['type'] = $data['account_id'];
                $password = md5($data['password']);
                $data['password'] = $password;
                $data['full_name'] = $data['name'];
                $data['is_admin'] = 1;
                unset($data['name']);
                unset($data['account_id']);
            }
            $dimondmaster_id = $this->MasterModel->add_data($data,$user_id,$image_main_files);       
            if($dimondmaster_id && $dimondmaster_id === 'exist'){
                echo json_encode(array('success' => true,'type' => '1', 'message' => $heading.'already exist'));
            }else if($dimondmaster_id && ($dimondmaster_id === 'User Not Found!' || $dimondmaster_id === 'Course Not Found!' || $dimondmaster_id === 'Course Already Purchased!' || $dimondmaster_id === 'Course Assigned SuccessFully!')){
                echo json_encode(array('success' => true,'type' => '2', 'message' => $dimondmaster_id));
            }else if ($dimondmaster_id && $dimondmaster_id !== "exist" && !isset($dimondmaster_id['message'])) {
                echo json_encode(array('success' => true,'type' => '1', 'message' => $heading.' added successfully'));
            }else if (isset($dimondmaster_id['message'])){
                echo json_encode(array('success' => false,'type' => '1', 'message' => $dimondmaster_id['message']));
            }else {
                echo json_encode(array('success' => false,'type' => '1', 'message' => 'Failed to add '.$heading));
            }
        }
        else 
        {
            if ($table == 'users' ) {
                $username = $data['username'];
                $fullname = $data['full_name'];
                $student_id = $data['student_id'];
                $email = $data['contact_email'];
                $number = $data['contact_number'];
                $id = $data['id'];
                $type = $data['type'];

        // Check if type is 1
            if ($type == 1) {
            $check_user = $this->db->query("select * from users where username = '$username' and id != '$id'")->result_array();
            if (count($check_user) > 0) {
                echo json_encode(array('success' => false, 'message' => 'Username Already Exists'));
                exit;
            }
            $check_user1 = $this->db->query("select * from users where status = '0' and full_name = '$fullname' and id != '$id' ")->result_array();
            if (count($check_user1) > 0) {
                echo json_encode(array('success' => false, 'message' => 'Fullname Already Exists'));
                exit;
            }
            $check_user2 = $this->db->query("select * from users where status = '0' and student_id = '$student_id' and id != '$id' ")->result_array();
            if (count($check_user2) > 0) {
                echo json_encode(array('success' => false, 'message' => 'Student Id Already Exists'));
                exit;
            }
            $check_user3 = $this->db->query("select * from users where status = '0' and contact_email = '$email' and id != '$id' ")->result_array();
            if (count($check_user3) > 0) {
                echo json_encode(array('success' => false, 'message' => 'Email Already Exists'));
                exit;
            }
            $check_user4 = $this->db->query("select * from users where status = '0' and contact_number = '$number' and id != '$id' ")->result_array();
            if (count($check_user4) > 0) {
                echo json_encode(array('success' => false, 'message' => 'Number Already Exists'));
                exit;
            }
            } 

            else if ($type == 2){
                    $check_user = $this->db->query("select * from users where username = '$username' and id != '$id'")->result_array();
                    if (count($check_user) > 0) {
                        echo json_encode(array('success' => false, 'message' => 'Username Already Exists'));
                        exit;
                    }
                    $check_user1 = $this->db->query("select * from users where status = '0' and full_name = '$fullname' and id != '$id' ")->result_array();
                    if (count($check_user1) > 0) {
                        echo json_encode(array('success' => false, 'message' => 'Fullname Already Exists'));
                        exit;
                    }
                    $check_user3 = $this->db->query("select * from users where status = '0' and contact_email = '$email' and id != '$id' ")->result_array();
                    if (count($check_user3) > 0) {
                        echo json_encode(array('success' => false, 'message' => 'Email Already Exists'));
                        exit;
                    }
                    $check_user4 = $this->db->query("select * from users where status = '0' and contact_number = '$number' and id != '$id' ")->result_array();
                    if (count($check_user4) > 0) {
                        echo json_encode(array('success' => false, 'message' => 'Number Already Exists'));
                        exit;
                    }
                }else {
                    
                }
            }
            if($table == 'chapter'){
                $chapter_no = $data['chapter_no'];
                $chapter_name = $data['chapter_name'];
                $check_user = $this->db->query("select * from chapter where chapter_no = '$chapter_no'  and course_id = '$course_id' and status !='2' and chapter_name = '$chapter_name'  ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'rahul No Already Exists'));
                    exit;
                }
                $check_user1 = $this->db->query("select * from chapter where status = '0' and chapter_name = '$chapter_name' ")->result_array();
                if(count($check_user1)>0){
                    echo json_encode(array('success' => false, 'message' => 'Chapter Name Already Exists'));
                    exit;
                }
            }               
            if ($table == 'child_parent_relationship'){
                    $main_id = $data['id'];
                    $child_id = $data['child_id'];
                    $parent_id = $data['parent_id'];

                    foreach ($child_id  as $id ){
                        $student = $id;
                        $check_relation = $this->db->query("select a.*,b.username from child_parent_relationship a left outer join users b on b.student_id = a.child_id where a.parent_id = '$parent_id' and a.child_id = '$student' and a.status = '0' and a.id != '$main_id'  ")->result_array();
                        
                        if(count($check_relation)>0){
                            $student_name = $check_relation[0]['username'];
                            echo json_encode(array('success' => false, 'message' => $student_name.' Already Exists In Relationship'));
                            exit; 
                        }
                    }               
            }
            if($table == 'course_exercise'){
            $exercise_no = $data['exercise_no'];
            $task = $data['task'];
            $check_user = $this->db->query("select * from course_exercise where exercise_no = '$exercise_no' ")->result_array();
                if(count($check_user)>0){
                    echo json_encode(array('success' => false, 'message' => 'Exercise No Already Exists'));
                    exit;
                }
            $check_user1 = $this->db->query("select * from course_exercise where status = '0' and task = '$task' ")->result_array();
                if(count($check_user1)>0){
                    echo json_encode(array('success' => false, 'message' => 'task Already Exists'));
                exit;
                    
                }
            }

            if($table == 'add_account'){
                

                if (isset($profileimage["image"]) && !empty($profileimage["image"]['name'])) 
                {   
                    $targetDir = "uploads/usersimage/";
                    $uniqueFolderName = $last_insert_id;
                    $uploadDir = $targetDir . $uniqueFolderName . "/";
                    mkdir($uploadDir);
                    $tmpFilePath = $profileimage["image"]["tmp_name"];
                    if (!empty($tmpFilePath) && is_uploaded_file($tmpFilePath)) {
                        $fileName = '';
                        $fileName = $profileimage["image"]["name"];
                        $filePath = $uploadDir . $fileName;
                        if (move_uploaded_file($tmpFilePath, $filePath)) {
                            $image_main_files .= base_url() . '' . $filePath ;
                        } else {                            
                        }
                    }
                    else
                    {
                    }
               }

                $data['type'] = $data['account_id'];
                $password = md5($data['password']);
                $data['password'] = $password;
                $data['full_name'] = $data['name'];
                $data['is_admin'] = 1;
                unset($data['name']);
                unset($data['account_id']);
            }

            if ($table == 'main_quiz'){
            
                $course_id = $data['course_id'];
                $percent_creteria = (int)$data['percent_creteria'];
                $mainQuiz = $this->db->query("SELECT id, percent_creteria FROM main_quiz WHERE course_id = $course_id AND status != '2'")->result_array();
                $availableCriteria = 0 ;
                foreach ($mainQuiz as $results) { 
                    $availableCriteria += $results['percent_creteria']; 
                }
                $availableCriteriaTotal = (int)$availableCriteria + (int)$percent_creteria;
                $remaining  = 100 - $availableCriteria;
                if ($remaining > 0 ){
                    $criteria_message = 'Percentage criteria exceeded remaining only '.$remaining;
                }else{
                    $criteria_message = 'Percentage criteria exceeded';
                }
                
                if($availableCriteriaTotal > 100){
                    echo json_encode(array('success' => false,'type' => '1', 'message' => $heading.' '.$criteria_message));
                    exit;
                }
               
            }
               
            $id = $this->MasterModel->edit_data($data,$user_id,$image_main_files);
            if($id === 'exist') {
                echo json_encode(array('success' => true,'type' => '2', 'message' => $heading.'already exist'));
            }else{
                echo json_encode(array('success' => true,'type' => '2', 'message' => $heading.' updated successfully'));
            }
        }
        
    }
    public function edit_data() {
        $data = $_POST;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MasterModel');
        $exists = $this->MasterModel->check_exists($match,$data);
     
        if ($exists) {
            echo json_encode(array('success' => false, 'message' => $heading.' already exists'));
            return;
        }
        $this->MasterModel->edit_data($data,$user_id);
        echo json_encode(array('success' => true, 'type' => '1','message' => $heading.' edited successfully'));
        
    }  

    public function delete_data() {
        $this->load->model('MasterModel');
        $data = $_POST;
        $mes = $this->MasterModel->delete_data($data);
        if($data['table'] == 'tutorial'){
            $this->load->library('Firebase');
            $firebase = new Firebase();
            $firebase->deleteTutorial($data['id']);
        }
        if($mes != ''){
            echo json_encode(array('success' => false, 'message' => $data['heading'].' '.$mes));
        }else{
            echo json_encode(array('success' => true, 'message' => $data['heading'].' deleted successfully'));
        }
        
    }

    public function homework_update_status() {
        $this->load->model('MasterModel');
        $data = $_POST;

        $data2 = array();
        if($data['homework_status'] == 'approved'){
          $data2['homework_status'] = 1;
        }elseif($data['homework_status'] == 'incomplete'){
            $data2['homework_status'] = 2;
        }else{
            $data2['homework_status'] = 0; 
        }
        
            $homeworkId = $data['id'];
            $this->db->where('id', $data['id']);
            $this->db->update('child_homework', $data2);
            $homework_data = $this->db->query("select * from child_homework where id = '$homeworkId' and status != '2'")->row_array();
            
            $userId = $homework_data['user_id'];
            $currentDateTime = date('Y-m-d H:i:s');
            $main_homework_id = $homework_data['homework_id'];
            $homework_details = $this->db->query("select * from homework where id = '$main_homework_id' and status != '2'")->row_array();
            // print_r( $homework_details); die("nn");
            if(!empty($homework_details)){
                $course_id = $homework_details['course_id'];
                $course_details =  $this->db->query("select * from courses where id = '$course_id' and status != '2'")->row_array();
               // date_default_timezone_set('Asia/Kolkata');
                $query_result = $this->db->query("select id,full_name,firebase_token,student_id from users where id = '$userId' and status != '2'")->row_array();
                $message = $query_result['full_name'].' submitted homework '.$course_details['name'].' Status is '.$data['homework_status'];
                $this->db->query("INSERT INTO notification (main_id,sender_id,receiver_id,notification_type,message,added_date,updated_date) VALUES ('$course_id','1','$userId','homework_status','$message','$currentDateTime','$currentDateTime')");
                $query_result['topic'] = 'homework_status';
                $query_result['notificationFor'] = 'homework_status';
                $query_result['title'] = 'Homework Status';
                $query_result['body'] = $message;
               
                $this->load->library('Firebase');
                $firebase = new Firebase();
               
                if(!empty($query_result['firebase_token'])){
                    try { 
                        $response =  $firebase->sendNotification($query_result);
                    }catch (Exception $e) {
                        //alert the user then kill the process
                    
                     
                    }
                   
                }
              

            }
            echo json_encode(array('success' => true, 'message' => $data['heading'].' updated successfully'));
    
    }

    private function getUserListToCompletedCoursesCertification() {
        $allOngoingCourses = $this->db->query("SELECT a.child_id,a.course_id,c.full_name FROM mycart a 
        LEFT OUTER JOIN course_certificate b ON b.user_id = a.child_id AND b.course_id = a.course_id
        LEFT OUTER JOIN users c ON c.id = a.child_id 
        LEFT OUTER JOIN courses d ON d.id = a.course_id 
        WHERE b.certificate IS NULL AND a.is_paid = 2 AND a.status != '2' AND c.status != '2' AND d.status != '2'")->result_array();

        if(count($allOngoingCourses) === 0) return [];

        //filteredAllOngoingCourses list of users whose course completed but certificate not generated
        $filteredAllOngoingCourseUsers = [];
        for ($i=0; $i < count($allOngoingCourses); $i++) { 
            $user_id =  $allOngoingCourses[$i]['child_id'];
            $user_name = $allOngoingCourses[$i]['full_name'];
            $course_id =  $allOngoingCourses[$i]['course_id'];

            $classes = $this->db->query("select count(a.id) as count from upcoming_classes a 
            left outer join master_classes b1 on b1.id = a.master_class_id
            left outer join chapter b ON b.id = b1.chapter_id
            left outer join courses c ON c.id = b.course_id where c.id = $course_id and c.status != '2' and b.status!= '2' and b1.status!= '2' and a.status!='2'")->result_array();
            
            if($classes[0]['count'] > 0) {
                $attendance = $this->db->query("select count(a.id) as count from check_in a 
                left outer join upcoming_classes b ON b.id = a.upcoming_id
                left outer join master_classes b1 on b1.id = b.master_class_id
                left outer join chapter c ON c.id = b1.chapter_id
                left outer join courses d ON d.id = c.course_id 
                where a.course_id = $course_id and a.user_id = $user_id and d.status != '2' and c.status!= '2' and b.status!='2'")->result_array();
                if((int)$classes[0]['count'] === (int)$attendance[0]['count']) {
                        array_push($filteredAllOngoingCourseUsers,array('id' => $user_id, 'name' => "$user_name"));
                }
            }        
        }

        if(count($filteredAllOngoingCourseUsers) > 0) {
            $filteredAllOngoingCourseUsers = array_map("unserialize", array_unique(array_map("serialize", $filteredAllOngoingCourseUsers)));
        }

        return $filteredAllOngoingCourseUsers;
    }

    private function replaceSingleQuote($string) {
        $new_string = str_replace("'", "/", $string);
        return $new_string;
    }
}
