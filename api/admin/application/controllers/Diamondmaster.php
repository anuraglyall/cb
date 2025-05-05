<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// not used by cssoft
class Diamondmaster extends CI_Controller {

        public function index()
	{   
                  $perameters[]=array("id","hidden","type","required","");  
                  
                  
                  $data['validate']="name,short_name,type";  
                   
                  $data['master']="diamondmaster";
                  
                  
                   
                  $fullUrl = base_url(uri_string());
                  
                  
                  ### diamond masters
                  
                  if (strpos($fullUrl, 'diamonds-cut') !== false) {
                  $data['edits']=array("id","name","short_name");     
                  $data['table']="diamond_masters";    
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['title']="Diamond Cut";
                  $data['title2']="Diamond Cut List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("name","text","Description","required");  
                  $perameters[]=array("name","text","Status","required");  
                  $perameters[]=array("short_name","text","Short Name","");
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Cut");  
                  $data['perameters']=$perameters;
                  $data['type']="1";  
                  
                  }
                  
                  else if (strpos($fullUrl, 'diam-master') !== false) {
                      
                  $data['edits']=array("id","vendor","diamond_name","diamond_cut","diamond_shape","diamond_color"
                      ,"diamond_clarity","diamond_clarity","diamond_pointers","diamond_sieve_size","diamond_unit"
                      ,"diamond_rate");     
                   
                  $data['title']="Diamonds";
                  $data['title2']="Diamonds List";
                  $data['table']="diamonds";
                  $vendors=$this->db->query("select id,partner_customer_id as name "
                          . "from partners_customer "
                          . "where partner_customer_type='1'")->result_array();

                  $perameters[]=array("vendor","select","Vendor","required",$vendors);  
                  $perameters[]=array("diamond_name","text","Diamond Name","required","");  
                  
                  $diamond_master=$this->db->query("select * "
                          . "from diamond_masters "
                          . "where 1")->result_array();  
                  
                  $diamond_cut=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='1')
                  {    
                  $diamond_cut[]=$diamond_master_res;    
                  }
                  }
                  
                  $diamond_shape=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='2')
                  {    
                  $diamond_shape[]=$diamond_master_res;    
                  }
                  }
                  $diamond_color=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='3')
                  {    
                  $diamond_color[]=$diamond_master_res;    
                  }
                  }
                  
                  $diamond_clarity=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='4')
                  {    
                  $diamond_clarity[]=$diamond_master_res;    
                  }
                  }
                  $diamond_pointers=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='5')
                  {    
                  $diamond_pointers[]=$diamond_master_res;    
                  }
                  }
                  
                  $diamond_seive_size=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='6')
                  {    
                  $diamond_seive_size[]=$diamond_master_res;    
                  }
                  }
                  
                  $diamond_unit=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='7')
                  {    
                  $diamond_unit[]=$diamond_master_res;    
                  }
                  }
                  
                  
                  $perameters[]=array("diamond_cut","select","Diamond Cut","required",$diamond_cut);  
                  $perameters[]=array("diamond_shape","select","Diamond Shape","required",$diamond_shape);  
                  $perameters[]=array("diamond_color","select","Diamond Color","required",$diamond_color);  
                  $perameters[]=array("diamond_clarity","select","Diamond Clarity","required",$diamond_clarity);  
                  $perameters[]=array("diamond_pointers","select","Diamond Pointers","required",$diamond_pointers);  
                  $perameters[]=array("diamond_sieve_size","select","Diamond Seive Size","required",$diamond_seive_size);  
                  $perameters[]=array("diamond_unit","select","Diamond Unit","required",$diamond_unit);  
                  $perameters[]=array("diamond_rate","text","Diamond Rate","required",'');  
                  
                  
                  $perameters[]=array("table","hidden","type","required","diamonds");  
                  $perameters[]=array("match","hidden","type","required","vendor,diamond_name,type");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Diamonds");  
                  $data['perameters']=$perameters;
                  
                  $data['type']="1";  
                  $data['display']=array("Id","Vendor","Diamond Name","Diamond Cut","Diamond Shape","Diamond Color","Diamond Clarity","Diamond Pointers","Diamond Sieve Size","Diamond Unit","Diamond Rate","Added Date","Update Date","Action");
                  $data['display2']=array("id","partner_customer_id","diamond_name","diamond_cuts","diamond_shapes","diamond_colors","diamond_claritys","diamond_pointerss","diamond_sieve_sizes","diamond_units","diamond_rate","added_date","updated_date","action");
                  }     
                  
                  else if (strpos($fullUrl, 'diamonds-shape') !== false) {
                  
                  $data['edits']=array("id","name","short_name");         
                      
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="diamond_masters";    
                  $data['title']="Diamond Shape";
                  $data['title2']="Diamond Shape List";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");  
                  $perameters[]=array("type","hidden","type","required","2");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Shape");  
                  $data['perameters']=$perameters;
                  $data['type']="2";  
                  } 
                  
                  else if (strpos($fullUrl, 'diamonds-color') !== false) { 
                      
                  $data['edits']=array("id","name","short_name");         
                      
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="diamond_masters";    
                  $data['title']="Diamond Color";
                  $data['title2']="Diamond Color List";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");  
                  $perameters[]=array("type","hidden","type","required","3");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Color");  
                  $data['perameters']=$perameters;
                  $data['type']="3";  
                  }    
                  
                  else if (strpos($fullUrl, 'diamonds-clarity') !== false) {
                      
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                      
                  $data['title']="Diamond Clarity";
                  $data['title2']="Diamond Clarity List";
                  $data['table']="diamond_masters";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","4");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Clarity");  
                  $data['perameters']=$perameters;
                  $data['type']="4";  
                  }    
                  
                  else if (strpos($fullUrl, 'diamonds-pointers') !== false) { 
                      
                  $data['edits']=array("id","name","short_name");         
                      
                  
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="diamond_masters";    
                  $data['title']="Diamond Pointers";
                  $data['title2']="Diamond Pointers List";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","5");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Pointers");  
                  $data['perameters']=$perameters;
                  $data['type']="5";  
                  }    
                  
                  else if (strpos($fullUrl, 'diamonds-sieve-size') !== false) { 
                      
                  $data['edits']=array("id","name","short_name");         
                      
                  
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="diamond_masters";    
                  $data['title']="Diamond Sieve Size";
                  $data['title2']="Diamond Sieve List";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","6");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Sieve Size");  
                  $data['perameters']=$perameters;
                  $data['type']="6";  
                  }    
                  
                  else if (strpos($fullUrl, 'diamonds-unit') !== false) { 
                      
                  $data['edits']=array("id","name","short_name");         
                      
                  
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="diamond_masters";
                  $data['title']="Diamond Unit";
                  $data['title2']="Diamond Unit List";
                  
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","diamond_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","7");
                  $perameters[]=array("heading","hidden","heading","required","Diamond Unit");  
                  $data['perameters']=$perameters;
                  $data['type']="7";  
                  }    
                  
                  ## gemstone masters
                  
                  else if (strpos($fullUrl, 'gemstone-type') !== false) {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone Type";
                  $data['title2']="Gemstone Type List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone Type");  
                  $data['perameters']=$perameters;
                  $data['type']="1";  
                  }      
                  
                  else if (strpos($fullUrl, 'gemstone-cut') !== false) {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone Cut";
                  $data['title2']="Gemstone Cut List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","2");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone Cut");  
                  $data['perameters']=$perameters;
                  $data['type']="2";  
                  }
                  
                  else if (strpos($fullUrl, 'gemstone-shape') !== false) {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone shape";
                  $data['title2']="Gemstone shape List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","3");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone shape");  
                  $data['perameters']=$perameters;
                  $data['type']="3";  
                  }
                  
                  else if (strpos($fullUrl, 'gemstone-quality') !== false) {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone quality";
                  $data['title2']="Gemstone quality List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","4");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone quality");  
                  $data['perameters']=$perameters;
                  $data['type']="4";  
                  }
                  
                  else if (strpos($fullUrl, 'gemstone-size') !== false) {
                      
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone size";
                  $data['title2']="Gemstone size List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","5");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone quality");  
                  $data['perameters']=$perameters;
                  $data['type']="5";  
                  
                  }
                  
                  else if (strpos($fullUrl, 'gemstone-origin') !== false) {
                      
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone origin";
                  $data['title2']="Gemstone origin List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","6");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone quality");  
                  $data['perameters']=$perameters;
                  $data['type']="6";  
                  
                  }
                  
                  else if (strpos($fullUrl, 'gemstone-unit') !== false) {
                      
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="gemstone_masters";
                  $data['title']="Gemstone unit";
                  $data['title2']="Gemstone unit List";
                  $perameters[]=array("name","text","Name","required");   
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","gemstone_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","7");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone unit");  
                  $data['perameters']=$perameters;
                  $data['type']="7";  
                  
                  }
                  
                  else if (strpos($fullUrl, 'diam-gemstone') !== false) {
                        
                  $data['edits']=array("id","vendor","gemstone_name","gemstone_type","gemstone_cut","gemstone_shape"
                      ,"gemstone_quality","gemstone_size","gemstone_origin","gemstone_unit","gemstone_rate");     
                   
                  $data['title']="Gemstone";
                  $data['title2']="Gemstone List";
                  $data['table']="gemstone";
                  $vendors=$this->db->query("select id,partner_customer_id as name "
                          . "from partners_customer "
                          . "where partner_customer_type='1'")->result_array();

                  $perameters[]=array("vendor","select","Vendor","required",$vendors);  
                  $perameters[]=array("gemstone_name","text","Gemstone Name","required","");  
                  
                  $diamond_master=$this->db->query("select * "
                          . "from gemstone_masters "
                          . "where 1")->result_array();  
                  
                  $data1=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='1')
                  {    
                  $data1[]=$diamond_master_res;    
                  }
                  }
                  
                  $data2=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='2')
                  {    
                  $data2[]=$diamond_master_res;    
                  }
                  }
                  $data3=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='3')
                  {    
                  $data3[]=$diamond_master_res;    
                  }
                  }
                  
                  $data4=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='4')
                  {    
                  $data4[]=$diamond_master_res;    
                  }
                  }
                  $data5=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='5')
                  {    
                  $data5[]=$diamond_master_res;    
                  }
                  }
                  
                  $data6=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='6')
                  {    
                  $data6[]=$diamond_master_res;    
                  }
                  }
                  
                  $data7=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='7')
                  {    
                  $data7[]=$diamond_master_res;    
                  }
                  }
                  
                  
                  $perameters[]=array("gemstone_type","select","Gemstone Type","required",$data1);  
                  $perameters[]=array("gemstone_cut","select","Gemstone Cut","required",$data2);  
                  $perameters[]=array("gemstone_shape","select","Gemstone Shape","required",$data3);  
                  $perameters[]=array("gemstone_quality","select","Gemstone Quality","required",$data4);  
                  $perameters[]=array("gemstone_size","select","Gemstone Size","required",$data5);  
                  $perameters[]=array("gemstone_origin","select","Gemstone Origin","required",$data6);  
                  $perameters[]=array("gemstone_unit","select","Gemstone Unit","required",$data7);  
                  $perameters[]=array("gemstone_rate","text","Gemstone Rate","required",'');  
                  
                  
                  $perameters[]=array("table","hidden","type","required","gemstone");  
                  $perameters[]=array("match","hidden","type","required","vendor,gemstone_name,type,gemstone_rate");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Gemstone");  
                  $data['perameters']=$perameters;
                  
                  $data['type']="1";  
                  $data['display']=array("Id","Vendor","Gemstone Name","Gemstone Type","Gemstone Cut","Gemstone Shape",
                      "Gemstone Quality","Gemstone Size","Gemstone Origin","Gemstone Unit","Gemstone Rate","Added Date","Update Date","Action");
                  $data['display2']=array("id","partner_customer_id","gemstone_name","gemstone_types","gemstone_cuts","gemstone_shapes",
                      "gemstone_qualitys","gemstone_sizes","gemstone_origins","gemstone_units","gemstone_rate","added_date","updated_date","action");
                  }     
                  
                  
                  ## pearl masters
                  
                  else if(strpos($fullUrl, 'pearl-type') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="pearl_masters";
                  $data['title']="Pearl Type";
                  $data['title2']="Pearl Type List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","pearl_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Pearl Type");  
                  $data['perameters']=$perameters;
                  $data['type']="1";  
                  }  
                  
                  else if(strpos($fullUrl, 'pearl-shape') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="pearl_masters";
                  $data['title']="Pearl Shape";
                  $data['title2']="Pearl Shape List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","pearl_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","2");
                  $perameters[]=array("heading","hidden","heading","required","Pearl Shape");  
                  $data['perameters']=$perameters;
                  $data['type']="2";  
                  }  
                  
                  else if(strpos($fullUrl, 'pearl-color') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="pearl_masters";
                  $data['title']="Pearl color";
                  $data['title2']="Pearl color List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","pearl_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","3");
                  $perameters[]=array("heading","hidden","heading","required","Pearl color");  
                  $data['perameters']=$perameters;
                  $data['type']="3";  
                  }  
                  
                  else if(strpos($fullUrl, 'pearl-size') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="pearl_masters";
                  $data['title']="Pearl size";
                  $data['title2']="Pearl size List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","pearl_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","4");
                  $perameters[]=array("heading","hidden","heading","required","Pearl size");  
                  $data['perameters']=$perameters;
                  $data['type']="4";  
                  }  
                  
                  else if(strpos($fullUrl, 'pearl-unit') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="pearl_masters";
                  $data['title']="Pearl unit";
                  $data['title2']="Pearl unit List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","pearl_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","5");
                  $perameters[]=array("heading","hidden","heading","required","Pearl unit");  
                  $data['perameters']=$perameters;
                  $data['type']="5";  
                  }
                  else if (strpos($fullUrl, 'diam-pearl') !== false) {
                        
                  $data['edits']=array("id","vendor","pearl_name","pearl_type","pearl_shape","pearl_color"
                      ,"pearl_size","pearl_unit","pearl_rate");     
                   
                  $data['title']="Pearls";
                  $data['title2']="Pearls List";
                  $data['table']="pearls";
                  $vendors=$this->db->query("select id,partner_customer_id as name "
                          . "from partners_customer "
                          . "where partner_customer_type='1'")->result_array();

                  $perameters[]=array("vendor","select","Vendor","required",$vendors);  
                  $perameters[]=array("pearl_name","text","Pearl Name","required","");  
                  
                  $diamond_master=$this->db->query("select * "
                          . "from pearl_masters "
                          . "where 1")->result_array();  
                  
                  $data1=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='1')
                  {    
                  $data1[]=$diamond_master_res;    
                  }
                  }
                  
                  $data2=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='2')
                  {    
                  $data2[]=$diamond_master_res;    
                  }
                  }
                  $data3=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='3')
                  {    
                  $data3[]=$diamond_master_res;    
                  }
                  }
                  
                  $data4=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='4')
                  {    
                  $data4[]=$diamond_master_res;    
                  }
                  }
                  $data5=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='5')
                  {    
                  $data5[]=$diamond_master_res;    
                  }
                  }
                  
                  $perameters[]=array("pearl_type","select","Pearl Type","required",$data1);  
                  $perameters[]=array("pearl_shape","select","Pearl Shape","required",$data2);  
                  $perameters[]=array("pearl_color","select","Pearl Color","required",$data3);  
                  $perameters[]=array("pearl_size","select","Pearl Size","required",$data4);  
                  $perameters[]=array("pearl_unit","select","Pearl Unit","required",$data5);  
                  $perameters[]=array("pearl_rate","text","Pearl Rate","required",'');  
                  
                  $perameters[]=array("table","hidden","type","required","pearls");  
                  $perameters[]=array("match","hidden","type","required","vendor,pearl_name,type,pearl_rate");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Pearl");  
                  $data['perameters']=$perameters; 
                  $data['type']="1";  
                  $data['display']=array("Id","Vendor","Pearl Name","Pearl Type","Pearl Shape","Pearl Color",
                      "Pearl Size","Pearl Unit","Pearl Rate","Added Date","Update Date","Action");
                  $data['display2']=array("id","partner_customer_id","pearl_name","pearl_types","pearl_shapes","pearl_colors","pearl_sizes",
                      "pearl_units","pearl_rate","added_date","updated_date","action");
                  }  
                  
                  
                  
                  
                  ## dimension masters
                  
                  else if(strpos($fullUrl, 'dimensions-unit') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="dimensions_masters";
                  $data['title']="Dimensions Type";
                  $data['title2']="Dimensions Type List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","dimensions_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Dimensions Type");  
                  $data['perameters']=$perameters;
                  $data['type']="1";  
                  }  
                  
                  
                  else if(strpos($fullUrl, 'dimensions-country') !== false) 
                  {
                  $data['edits']=array("id","name","short_name");        
                  $data['display']=array("Id","Name","Short Code","Added Date","Update Date","Action");
                  $data['display2']=array("id","name","short_name","added_date","updated_date","action");
                  $data['table']="dimensions_masters";
                  $data['title']="Dimensions country";
                  $data['title2']="Dimensions country List";
                  $perameters[]=array("name","text","Name","required");  
                  $perameters[]=array("short_name","text","Short Name","");  
                  $perameters[]=array("table","hidden","type","required","dimensions_masters");  
                  $perameters[]=array("match","hidden","type","required","name,short_name,type");
                  $perameters[]=array("type","hidden","type","required","2");
                  $perameters[]=array("heading","hidden","heading","required","Dimensions country");  
                  $data['perameters']=$perameters;
                  $data['type']="2";  
                  }
                  else if (strpos($fullUrl, 'diam-dimensions') !== false) {
                        
                  $data['edits']=array("id","dimensions_name","dimensions_unit","dimensions_country");     
                   
                  $data['title']="Dimensions";
                  $data['title2']="Dimensions List";
                  $data['table']="dimensions";
                  $perameters[]=array("dimensions_name","text","Dimensions Name","required","");  
                  
                  $diamond_master=$this->db->query("select * "
                          . "from dimensions_masters "
                          . "where 1")->result_array();  
                  
                  $data1=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='1')
                  {    
                  $data1[]=$diamond_master_res;    
                  }
                  }
                  
                  $data2=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='2')
                  {    
                  $data2[]=$diamond_master_res;    
                  }
                  }
                  
                  
                  $perameters[]=array("dimensions_unit","select","Dimensions unit","required",$data1);  
                  $perameters[]=array("dimensions_country","select","Dimensions country","required",$data2);  
                  $perameters[]=array("table","hidden","type","required","dimensions");  
                  $perameters[]=array("match","hidden","type","required","dimensions_name,dimensions_unit,dimensions_country");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Dimensions");  
                  $data['perameters']=$perameters; 
                  $data['type']="1";  
                  $data['display']=array("Id","Dimensions Name","Dimensions Unit","Dimensions Country","Added Date","Update Date","Action");
                  $data['display2']=array("id","dimensions_name","dimensions_units","dimensions_countrys","added_date","updated_date","action");
                  }
                  
                  
                  ## currency masters
                  
                  else if(strpos($fullUrl, 'currency') !== false) 
                  {
                  $data['edits']=array("id","currency_name","currency_exchange_rate");        
                  $data['display']=array("Id","Currency Name","INR exchange rate","Added Date","Update Date","Action");
                  $data['display2']=array("id","currency_name","currency_exchange_rate","added_date","updated_date","action");
                  $data['table']="currency_masters";
                  $data['title']="Currency";
                  $data['title2']="Currency List";
                  $perameters[]=array("currency_name","text","Currency Name","required");  
                  $perameters[]=array("currency_exchange_rate","text","INR exchange rate","");  
                  $perameters[]=array("table","hidden","type","required","currency_masters");  
                  $perameters[]=array("match","hidden","type","required","currency_name,currency_exchange_rate");
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Currency");  
                  $data['perameters']=$perameters;
                  $data['type']="1";  
                  } 
                  
                  
                  
                  ## prices masters
                  
                  else if (strpos($fullUrl, 'prices') !== false) {
                        
                  $data['edits']=array("id","price_name","currency");     
                   
                  $data['title']="Prices";
                  $data['title2']="Prices List";
                  $data['table']="prices";
                  $perameters[]=array("price_name","text","Price Name","required","");
//                  
                  $diamond_master=$this->db->query("select type,id,currency_name as name,currency_exchange_rate "
                          . "from currency_masters "
                          . "where 1")->result_array();  
                  
                  $data1=array();
                  foreach($diamond_master as $diamond_master_res)
                  {
                  if($diamond_master_res['type']=='1')
                  {    
                  $data1[]=$diamond_master_res;    
                  }
                  }
                  
                  $perameters[]=array("currency","select","Currency","required",$data1,"onchange","set_price_exchange_rate","currency/exchange_rate/landing_cost_multiple/price_multiple/total_multiple");  
                  $perameters[]=array("exchange_rate","text","Exchange Rate","required","","onchange","set_price_exchange_rate","currency/exchange_rate/landing_cost_multiple/price_multiple/total_multiple");  
                  $perameters[]=array("landing_cost_multiple","text","Land Cost Multiple","required","","onchange","set_price_exchange_rate","currency/exchange_rate/landing_cost_multiple/price_multiple/total_multiple");  
                  $perameters[]=array("price_multiple","text","Price Multiple","required","","onchange","set_price_exchange_rate","currency/exchange_rate/landing_cost_multiple/price_multiple/total_multiple");  
                  $perameters[]=array("total_multiple","text","Total Multiple","required","","onchange","set_price_exchange_rate","currency/exchange_rate/landing_cost_multiple/price_multiple/total_multiple");  
                  $perameters[]=array("table","hidden","type","required","prices");  
                  $perameters[]=array("match","hidden","type","required","price_name,currency");  
                  $perameters[]=array("type","hidden","type","required","1");
                  $perameters[]=array("heading","hidden","heading","required","Prices");  
                  $data['perameters']=$perameters; 
                  $data['type']="1";  
                  
                  $data['display']=array("Id","Price Name","Currency","Currency Exchange Rate","Landing Cost Multiple","Price Multiple","Total Multiple","Added Date","Update Date","Action");
                  $data['display2']=array("id","price_name","currencys","exchange_rate","landing_cost_multiple","price_multiple","total_multiple","added_date","updated_date","action");
                  }
                  
                  
                  
                  
                  
                  $this->load->view('masters/diamonds/master/mainmaster',$data);
	}  
        
        public function fetch_data() {   
            
        $requestData = $_POST;
//        $type=$requestData['type'];
        $searchValue = $requestData['search']['value'];
        $start = $requestData['start'];
        $length = $requestData['length'];
        $columnIndex = $requestData['order'][0]['column'];
        $columnSortDirection = $requestData['order'][0]['dir'];

        // Load the Metal model
        $this->load->model('MasterModel');

        // Fetch data from the model based on search, pagination, sorting, and limit parameters
        $data = $this->MasterModel->fetchdetails($searchValue, $start, $length, $columnIndex, $columnSortDirection,$requestData);

        

        echo json_encode($data);
        }
        
        public function add_data() {
        $data = $_POST;
        $heading= $_POST['heading'];
        $match= $_POST['match'];
        $user_id = $_SESSION['id']; // Assuming a user is logged in and their user ID is 1
        $this->load->model('MasterModel');
         $exists = $this->MasterModel->check_exists($match,$data);
//        exit;
        if ($exists) {
            // Metal already exists, show an error message
            echo json_encode(array('success' => false, 'message' => $heading.' already exists'));
            return;
        }

        // Add the dimondmaster
        $dimondmaster_id = $this->MasterModel->add_data($data,$user_id);

        if ($dimondmaster_id) {
            echo json_encode(array('success' => true, 'message' => $heading.' added successfully'));
        } else {
            echo json_encode(array('success' => false, 'message' => 'Failed to add '.$heading));
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
        echo json_encode(array('success' => true, 'message' => $heading.' edited successfully'));
    }  

    public function delete_data() {
        $this->load->model('MasterModel');
        $data = $_POST;
        $this->MasterModel->delete_data($data);
        echo json_encode(array('success' => true, 'message' => $data['heading'].' deleted successfully'));
    }

    
}