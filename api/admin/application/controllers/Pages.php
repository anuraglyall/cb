<?php
defined('BASEPATH') OR exit('No direct script access allowed');
     // this file is not used by cssoft 
//echo ($currency_name1);
//exit;
class pages extends CI_Controller {
    public function __construct()
        {
            parent::__construct();
            $this->db2 = $this->load->database('db2', TRUE);
//            $this->globaldb = $this->load->database('globaldb', TRUE);
        }        
        public function add_prtransaction_add_details()
        {
            $dbname=($_SESSION['dbname']);
            $data['postdata']=($_POST);            
            echo $message2 = $this->load->view('extra/prdetails', $data, true);  
        }
        public function add_voucher_add_details()
        {
            $dbname=($_SESSION['dbname']);
            $data['postdata']=($_POST);            
            echo $message2 = $this->load->view('extra/voucherdetails', $data, true);  
        }
        public function search_stock()
        {
            $dbname=($_SESSION['dbname']);
            $final=array("db"=>$dbname,"search_key"=>$_POST['id']);
            $search_key=$_POST['id'];
            if($search_key!='')
            {
            $sql=" WHERE a.code ='$search_key' ";    
            }    
            $maindb = $this->load->database($dbname, TRUE);  
            $result=$maindb->query("SELECT a.code as id,a.descr,a.refno as name,a.rapprice,a.cl_qty,b.shape,c.size,d.color,e.clarity " 
             ."FROM stock a LEFT JOIN mshape b ON a.shapeid=b.shapeid"
              ." LEFT JOIN msize c ON a.sizeid=c.sizeid "
              ." LEFT JOIN mcolor d ON a.colorid=d.colorid "
              ." LEFT JOIN mclarity e ON a.clarityid=e.clarityid"
              . " $sql "
              . "")->result_array();
           
//            $data2= json_decode($result,true);
            echo $data2= json_encode($result[0]);
        }        
        public function search_brockerper()
        {
            $dbname=($_SESSION['dbname']);
            $final=array("db"=>$dbname,"search_key"=>$_POST['id']);
            $search_key=$_POST['id'];
            if($search_key!='')
            {
                $sql=" WHERE a.code ='$search_key' ";    
            }    
            $maindb = $this->load->database($dbname, TRUE);  
            $result=$maindb->query("SELECT a.ac_code as id,a.brokerper FROM ledgmast a ")->result_array();           
//          $data2= json_decode($result,true);
            echo $data2= json_encode($result[0]);
        }
        
        public function load_singles_items()
	{
            ?>
            <tr>
                <td>              
                <?php 
            
                    $stockid=$_POST['code'];
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code[]" value="<?php print_r($_POST['code']); ?>"  />
                <input type="hidden" name="contranid[]" value="<?php print_r($_POST['contranid']); ?>"  />
                </td>                
                <td>
                <?php 
                print_r($_POST['pcs']);
                ?>
                <input type="hidden" name="pcs[]" value="<?php print_r($_POST['pcs']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r(round($_POST['qty']));
                ?>
                <input type="hidden" name="qty[]" value="<?php print_r($_POST['qty']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['rap_price']);
                ?>  
                <input type="hidden" value="<?php print_r($_POST['rap_price']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r($_POST['discper']);
                ?>
                <input type="hidden" name="discper[]" value="<?php print_r($_POST['discper']); ?>"  />
                <input type="hidden" name="dcflag[]" value="<?php print_r($_POST['dcflag']); ?>"  />
                <input type="hidden" name="trn_type[]" value="<?php print_r($_POST['trn_type']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r($_POST['rate']);
                ?>
                <input type="hidden" name="rate[]" value="<?php print_r($_POST['rate']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r($_POST['amt']);
                ?>
                <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['rate2']);
                ?>
                <input type="hidden" name="rate2[]" value="<?php print_r($_POST['rate2']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['amt2']);
                ?>
                <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>"  />
                </td>    
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>    
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
                
            </tr>
            <?php
            
            
	}  
        
        public function load_singles_item_sl_pu()
	{            
            ?>
            <tr>
                <td>              
                <?php 
                    $stockid=$_POST['code'];
//                    print_r($_POST);
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code[]" value="<?php print_r($_POST['code']); ?>"  />
                <input type="hidden" name="prikey[]" value="<?php print_r($_POST['prikey']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['pcs']);
                ?>
                <input type="hidden" name="pcs[]" value="<?php print_r($_POST['pcs']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r(round($_POST['qty']));
                ?>
                <input type="hidden" name="qty[]" value="<?php print_r($_POST['qty']); ?>"  />
                </td>    
                
<!--                <td>
                <?php 
                print_r($_POST['rap_price']);
                ?>  
                <input type="hidden" value="<?php print_r($_POST['rap_price']); ?>"  />
                </td>  -->
<!--                
                <td>
                <?php 
                print_r($_POST['discper']);
                ?>
              
                <input type="hidden" name="invrate[]" value="<?php print_r($_POST['invrate']); ?>"/>
                <input type="hidden" name="invrate2[]" value="<?php print_r($_POST['invrate2']); ?>"/>
                <input type="hidden" name="invamt[]" value="<?php print_r($_POST['invamt']); ?>"/>
                <input type="hidden" name="invamt2[]" value="<?php print_r($_POST['invamt2']); ?>"/>
                <input type="hidden" name="invcostrate[]" value="<?php print_r($_POST['invcostrate']); ?>"/>
                <input type="hidden" name="invcostrate2[]" value="<?php print_r($_POST['invcostrate2']); ?>"/>
                <input type="hidden" name="invcostamt[]" value="<?php print_r($_POST['invcostamt']); ?>"/>
                <input type="hidden" name="invcostamt2[]" value="<?php print_r($_POST['invcostamt2']); ?>"/>
                
                </td>    -->
                <td>
                <?php 
                print_r($_POST['rate']);
                ?>
                <input type="hidden" name="discper[]" value="<?php print_r($_POST['discper']); ?>"  />
                <input type="hidden" name="dcflag[]" value="<?php print_r($_POST['dcflag']); ?>"  />
                <input type="hidden" name="trn_type[]" value="<?php print_r($_POST['trn_type']); ?>"/>
                <input type="hidden" name="contranid[]" value="<?php print_r($_POST['contranid']); ?>"/>
                <input type="hidden" name="rate[]" value="<?php print_r($_POST['rate']); ?>"  />
                </td>    
                <td>
                <?php 
                print_r($_POST['amt']);
                ?>
                <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['rate2']);
                ?>
                <input type="hidden" name="rate2[]" value="<?php print_r($_POST['rate2']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['amt2']);
                ?>
                <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>"  />
                </td>    
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>    
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
                
            </tr>
            <?php
	}  
                public function add_pu_sl_detail()
	{     
            ?>
            
            <?php
//            print_r('bhairu');
                    $currency_name1=($_SESSION['logged_in_user']['currency_name1']);
                    $currency_name2=($_SESSION['logged_in_user']['currency_name2']);
                    ?>
                    <div class="mb-3 col-md-12">                                                          
                        <table  class="table table-bordered" style="width: 100%;overflow:scroll;" aria-describedby="scroll-vertical_info">
                                                            <thead >
                                                            <tr style="font-size:13px;">  
                                                                <th>Stock No</th>
                                                                <th>Pcs</th>
                                                                <th>Qty</th>
<!--                                                            <th>Rap. Price</th>
                                                                <th>Rap.Disc.</th>-->
                                                                <th>Rate <span><?php echo $currency_name1?></span></th>
                                                                <th>Amt <span><?php  echo $currency_name1?></span></th>
                                                                <th>Rate <span><?php  echo $currency_name2?></span></th>
                                                                <th>Amt<span><?php  echo $currency_name2?></span></th>
                                                                <th>Description</th>
                                                                <th>#</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="add_data_res" style="font-size:12px;">
                                                             <?php 
                                                                        if($_POST['id']!='')
                                                                        {
                                                                          $dbname=($_SESSION['dbname']);
                                                                          $maindb=$this->load->database($dbname, TRUE);    
                                                                          $id=$_POST['id'];                                                      
                                                                          $tablesname=explode(",",$_POST['maintabls']);                                                                          
                                                                          $tablesname=$tablesname[1];                                                                
//                                                                          if ($tablesname=='contran')
//                                                                          {
//                                                                              $sql="select a.*,b.refno from contran a left join stock b  on a.code=b.code where a.mainid='$id'";
//                                                                          }
//                                                                          else if ($tablesname=='tperforma')
//                                                                          {
//                                                                              $sql="select a.*,b.refno from tperforma a left join stock b  on a.code=b.code where a.mainid='$id'";
//                                                                          }
//                                                                          else if ($tablesname=='trans1')
//                                                                          {
                                                                              $sql="select a.*,b.refno from trans1 a left join stock b  on a.code=b.code where a.doc_no='$id'";
//                                                                          }                                                                          
                                                                          $customer=$maindb->query($sql)->result_array();
                                                                        }//                                                            
                                                                        foreach($customer as $customer_res)
                                                                        {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                            <?php 
//                                                                            echo '<pre>';
//                                                                            print_r($customer_res);
//                                                                            echo '</pre>';
                                                                            print_r($customer_res['refno']);
                                                                            ?>
                                                                            <input type="hidden" name="code[]" value="<?php print_r($customer_res['code']); ?>"  />
                                                                            <input type="hidden" name="prikey[]" value="<?php print_r($customer_res['prikey']); ?>"  />
                                                                            <input type="hidden" name="contranid[]" value="<?php print_r($customer_res['contranid']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['pcs']);
                                                                            ?>
                                                                            <input type="hidden" name="pcs[]" value="<?php print_r($customer_res['pcs']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r(round($customer_res['qty']));
                                                                            ?>
                                                                            <input type="hidden" name="qty[]" value="<?php print_r($customer_res['qty']); ?>"  />
                                                                            
                                                                            <input type="hidden" name="dcflag[]" value="<?php print_r($customer_res['dcflag']); ?>"  />
                                                                            <input type="hidden" name="trn_type[]" value="<?php print_r($customer_res['trn_type']); ?>"  />
                                                                            </td>    
  
<!--                                                                            <td  >
                                                                            <?php 
//                                                                            print_r($customer_res['rap_price']);
                                                                            ?>
                                                                            <input type="hidden" value="<?php print_r($customer_res['rap_price']); ?>"  />
                                                                            </td>    -->
<!--                                                                            <td  >
                                                                            <?php 
//                                                                            print_r($customer_res['discper']);
                                                                            ?>
                                                                            <input type="hidden" name="discper[]" value="<?php print_r($customer_res['discper']); ?>"  />
                                                                            
                                                                            </td>    -->
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate']);
                                                                            ?>
                                                                            <input type="hidden" name="rate[]" value="<?php print_r($customer_res['rate']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt']);
                                                                            ?>
                                                                            <input type="hidden" name="amt[]" value="<?php print_r($customer_res['amt']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate2']);
                                                                            ?>
                                                                            <input type="hidden" name="rate2[]" value="<?php print_r($customer_res['rate2']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt2']);
                                                                            ?>
                                                                            <input type="hidden" name="amt2[]" value="<?php print_r($customer_res['amt2']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['description']);
                                                                            ?>
                                                                            <input type="hidden" name="itemdescription[]" value="<?php print_r($customer_res['description']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                                <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                                                                            </td>      

                                                                        </tr> 
                                                                        <?php 
                                                                        }    
                                                                        ?>    
                                                            </tbody>
                                                            <tbody id="edit_data_res" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                        <a class="btn btn-sm btn-primary" onclick='load_add_more();'
                                                                           style="float:right;">Add Item
                                                                        </a>    
                                                                        
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                            <tbody id="edit_data_res2" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                       
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

            <?php
	}
        
        
        
        
        public function add_pr_sr_detail()
	{     
            ?>
               <?php
                    $currency_name1=($_SESSION['logged_in_user']['currency_name1']);
                    $currency_name2=($_SESSION['logged_in_user']['currency_name2']);
                    ?>
                    <div class="mb-3 col-md-12">                                                          
                        <table  class="table table-bordered" style="width: 100%;overflow:scroll;" aria-describedby="scroll-vertical_info">
                                                            <thead >
                                                            <tr style="font-size:13px;">  
                                                                <th>Stock No</th>
                                                                <th>Pcs</th>
                                                                <th>Qty</th>                                                                
                                                                <th>Rate <span><?php echo $currency_name1?></span></th>
                                                                <th>Amt<span><?php  echo $currency_name1?></span></th>
                                                                <th>Rate <span><?php  echo $currency_name2?></span></th>
                                                                <th>Amt <span><?php echo $currency_name2?></span></th>                                                                
                                                                <th>#</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="add_data_res" style="font-size:12px;">
                                                             <?php 
                                                                        if($_POST['id']!='')
                                                                        {
                                                                          $dbname=($_SESSION['dbname']);
                                                                          $maindb=$this->load->database($dbname, TRUE);    
                                                                          $id=$_POST['id'];                                                      
                                                                          $tablesname=explode(",",$_POST['maintabls']);                                                                          
                                                                          $tablesname=$tablesname[1];                                                                
//                                                                          if ($tablesname=='contran')
//                                                                          {
//                                                                              $sql="select a.*,b.refno from contran a left join stock b  on a.code=b.code where a.mainid='$id'";
//                                                                          }
//                                                                          else if ($tablesname=='tperforma')
//                                                                          {
//                                                                              $sql="select a.*,b.refno from tperforma a left join stock b  on a.code=b.code where a.mainid='$id'";
//                                                                          }
//                                                                          else if ($tablesname=='trans1')
//                                                                          {
                                                                              $sql="select a.*,b.refno from trans1 a left join stock b  on a.code=b.code where a.doc_no='$id'";
//                                                                          }                                                                          
                                                                          $customer=$maindb->query($sql)->result_array();
                                                                        }//                                                            
                                                                        foreach($customer as $customer_res)
                                                                        {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                            <?php 
//                                                                            echo '<pre>';
//                                                                            print_r($customer_res);
//                                                                            echo '</pre>';
                                                                            print_r($customer_res['refno']);
                                                                            ?>
                                                                            <input type="hidden" name="code[]" value="<?php print_r($customer_res['code']); ?>"  />
                                                                            <input type="hidden" name="prikey[]" value="<?php print_r($customer_res['prikey']); ?>"  />
                                                                            <input type="hidden" name="contranid[]" value="<?php print_r($customer_res['contranid']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['pcs']);
                                                                            ?>
                                                                            <input type="hidden" name="pcs[]" value="<?php print_r($customer_res['pcs']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r(round($customer_res['qty']));
                                                                            ?>
                                                                            <input type="hidden" name="qty[]" value="<?php print_r($customer_res['qty']); ?>"  />
                                                                                                                                                        <input type="hidden" name="dcflag[]" value="<?php print_r($customer_res['dcflag']); ?>"  />
                                                                            <input type="hidden" name="trn_type[]" value="<?php print_r($customer_res['trn_type']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate']);
                                                                            ?>
                                                                            <input type="hidden" name="rate[]" value="<?php print_r($customer_res['rate']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt']);
                                                                            ?>
                                                                            <input type="hidden" name="amt[]" value="<?php print_r($customer_res['amt']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate2']);
                                                                            ?>
                                                                            <input type="hidden" name="rate2[]" value="<?php print_r($customer_res['rate2']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt2']);
                                                                            ?>
                                                                            <input type="hidden" name="amt2[]" value="<?php print_r($customer_res['amt2']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                                <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                                                                            </td>      

                                                                        </tr> 
                                                                        <?php 
                                                                        }    
                                                                        ?>    
                                                            </tbody>
                                                            <tbody id="edit_data_res" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                        <a class="btn btn-sm btn-primary" onclick='load_add_more();'
                                                                           style="float:right;">Add Item
                                                                        </a>    
                                                                        
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                            <tbody id="edit_data_res2" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                       
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

            <?php
	}
        
        
        
        public function  load_receiptvoucher()
        {
            $dbname=($_SESSION['dbname']);
            $maindb = $this->load->database($dbname, TRUE);
            $ac_code1=$_POST['ac_code1'];
            $exchgac=$_POST['exchgac'];
            $dbac_code=$_POST['dbac_code'];
            $exchgentry=$_POST['exchgentry'];
            $exch1=$_POST['exch1'];
            $exch2=$_POST['exch2'];
            
            $sql=("select ac_name as ac_name FROM ledgmast  WHERE ac_code='$ac_code1'");
            $customer=$maindb->query($sql)->row_array();
            
            $sql=("select ac_name as ac_name FROM ledgmast  WHERE ac_code='$dbac_code'");
            $dbac_name=$maindb->query($sql)->row_array();
            
            $sql=("select ac_name as ac_name FROM ledgmast  WHERE ac_code='$exchgac'");
            $exchgac=$maindb->query($sql)->row_array(); 
            ?>
            <tr>
                <td>              
                <?php
                    echo $customer['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($_POST['ac_code1']); ?>"  />
                </td> 
                
                <td>              
                <?php 
                    echo 'C';             
                    ?>
                     <input type="hidden" name="dcflag[]" value="C" />
                </td>   
                
                <td>              
                <?php 
                    print_r(round($_POST['amt']));          
                    ?>
                     <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>" />
                </td>    
                
                <td>              
                <?php 
                    print_r(round($_POST['amt2']));          
                    ?>
                   <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>" />
                </td>
                
                <td>              
                <?php 
                    echo $_POST['actype'];             
                    ?>
                 <input type="hidden" name="actype[]" value="<?php print_r($_POST['actype']); ?>"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
           <!----<Second Entry Pass Here>------>
            <tr>
                <td>              
                <?php 
                    
                    echo $dbac_name['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($_POST['dbac_code']); ?>"  />
                </td> 
                
                <td>              
                <?php 
                    echo 'D';             
                    ?>
                     <input type="hidden" name="dcflag[]" value="D" />
                </td>   
                <td>              
                <?php 
                    print_r(round($_POST['amt']));          
                    ?>
                     <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>" />
                </td>    
                
                <td>              
                <?php 
                    print_r(round($_POST['amt2']));          
                    ?>
                   <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>" />
                </td>
                
                <td>              
                <?php 
                    echo $_POST['actype'];             
                    ?>
                 <input type="hidden" name="actype[]" value="<?php print_r($_POST['actype']); ?>"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
            
            
            <?php 
            if ($exchgentry==1){
                if ($exch1>0){
            ?>
            <tr>
                <td>              
                <?php
                    echo $exchgac['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($exchgac); ?>"  />
                </td> 
                <td>              
                <?php 
                if ($exch1>0){
                    echo 'D';  
                }
                if ($exch1<0){
                    echo 'C';  
                }
                    ?>                
		<input type="hidden" name="dcflag[]" value="<?php echo ($exch1 < 0) ? 'D' : 'C'; ?>" />
                </td>   
                <td>              
                <?php 
                    echo $exch1;          
                    ?>
                     <input type="hidden" name="amt[]" value="<?php print_r($exch1); ?>" />
                </td>    
                
                <td>              
                <?php 
                    ?>
                   <input type="hidden" name="amt2[]" value="<?php  ?>" />
                </td>
                
                <td>              
                <?php 
                    echo 'OA';             
                    ?>
                 <input type="hidden" name="actype[]" value="OA"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
		
            <tr>
                <td>              
                <?php
                    echo $customer['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($ac_code1); ?>"  />
                </td> 
                <td>              
                <?php 
                if ($exch1>0){
                    echo 'D';  
                }
                if ($exch1<0){
                    echo 'C';  
                }
                    ?>                
		<input type="hidden" name="dcflag[]" value="<?php echo ($exch1 < 0) ? 'D' : 'C'; ?>" />
                </td>   
                <td>              
                <?php 
                    echo $exch1;          
                    ?>
                     <input type="hidden" name="amt[]" value="<?php print_r($exch1); ?>" />
                </td>    
                
                <td>              
                <?php 
                    ?>
                   <input type="hidden" name="amt2[]" value="<?php  ?>" />
                </td>
                
                <td>              
                <?php 
                    echo 'OA';             
                    ?>
                 <input type="hidden" name="actype[]" value="OA"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
	
		
			
            <?php		 
            }
             ?>

             <?php	
            
                 if ($exch2>0){
            ?>
            <tr>
                <td>              
                <?php
                    echo $exchgac['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($exchgac); ?>"  />
                </td> 
                <td>              
                <?php 
                if ($exch2>0){
                    echo 'D';  
                }
                if ($exch2<0){
                    echo 'C';  
                }
                    ?>                
		<input type="hidden" name="dcflag[]" value="<?php echo ($exch2 < 0) ? 'D' : 'C'; ?>" />
                </td>   
                <td>              
                <?php 
                    echo $exch1;          
                    ?>
                     <input type="hidden" name="amt[]" value="" />
                </td>    
                
                <td>              
                <?php 
                    ?>
                   <input type="hidden" name="amt2[]" value="<?php print_r($exch2); ?>" />
                </td>
                
                <td>              
                <?php 
                    echo 'OA';             
                    ?>
                 <input type="hidden" name="actype[]" value="OA"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
		
            <tr>
                <td>              
                <?php
                    echo $customer['ac_name'];             
                    ?>
                    <input type="hidden" name="ac_code[]" value="<?php print_r($ac_code1); ?>"  />
                </td> 
                <td>              
                <?php 
                if ($exch2>0){
                    echo 'D';  
                }
                if ($exch2<0){
                    echo 'C';  
                }
                    ?>                
		<input type="hidden" name="dcflag[]" value="<?php echo ($exch2 < 0) ? 'D' : 'C'; ?>" />
                </td>   
       
                
                <td>              
                <?php 
                    ?>
                   <input type="hidden" name="amt[]" value="<?php  ?>" />
                </td>
                           <td>              
                <?php 
                    echo $exch2;          
                    ?>
                     <input type="hidden" name="amt2[]" value="<?php print_r($exch2); ?>" />
                </td>  
                <td>              
                <?php 
                    echo 'OA';             
                    ?>
                 <input type="hidden" name="actype[]" value="OA"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
            </tr>
		
            <?php		 
            }
             ?>
            
				
	   <?php		
            }
          ?>


            <?php
        }
        
        
        public function load_stockmixing()
	{            
            ?>
            <tr>
                <td>              
                <?php 
                    $stockid=$_POST['code'];
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code[]" value="<?php print_r($_POST['code']); ?>"  />
                <input type="hidden" name="prikey[]" value="<?php print_r($_POST['prikey']); ?>"  />
                </td>    
                
                <td>              
                <?php 
                    $stockid=$_POST['code_out'];
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code[]" value="<?php print_r($_POST['code_out']); ?>"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                </td>                    
                
                <td>
                <?php 
                print_r('C');
                ?>
                <input type="hidden" name="dcflag[]" value="C"  />
                </td>
                <td>
                <?php 
                print_r(round($_POST['qty']));
                ?>
                <input type="hidden" name="qty[]" value="<?php print_r($_POST['qty']); ?>"  />
                </td>
                <td>
                <?php 
                print_r(round($_POST['pcs']));
                ?>
                <input type="hidden" name="pcs[]" value="<?php print_r($_POST['pcs']); ?>"  />
                <input type="hidden" name="trn_type[]" value="<?php print_r($_POST['trn_type']); ?>"/>

                </td> 
        
<!--                <td>
                <?php 
                print_r($_POST['discper']);
                ?>
                <input type="hidden" name="contranid[]" value="<?php print_r($_POST['contranid']); ?>"/>
                <input type="hidden" name="invrate[]" value="<?php print_r($_POST['invrate']); ?>"/>
                <input type="hidden" name="invrate2[]" value="<?php print_r($_POST['invrate2']); ?>"/>
                <input type="hidden" name="invamt[]" value="<?php print_r($_POST['invamt']); ?>"/>
                <input type="hidden" name="invamt2[]" value="<?php print_r($_POST['invamt2']); ?>"/>
                <input type="hidden" name="invcostrate[]" value="<?php print_r($_POST['invcostrate']); ?>"/>
                <input type="hidden" name="invcostrate2[]" value="<?php print_r($_POST['invcostrate2']); ?>"/>
                <input type="hidden" name="invcostamt[]" value="<?php print_r($_POST['invcostamt']); ?>"/>
                <input type="hidden" name="invcostamt2[]" value="<?php print_r($_POST['invcostamt2']); ?>"/>
                
                </td>    -->
                
                <td>
                <?php 
                print_r($_POST['rate2']);
                ?>
                <input type="hidden" name="rate2[]" value="<?php print_r($_POST['rate2']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['rate']);
                ?>
                <input type="hidden" name="rate[]" value="<?php print_r($_POST['rate']); ?>"  />
                </td>    
           
                
                
                
                <td>
                <?php 
                print_r($_POST['amt2']);
                ?>
                <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>"  />
                </td>    
        <td>
                <?php 
                print_r($_POST['amt']);
                ?>
                <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>"  />
                </td> 
                
                
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
                
            </tr>
            <?php
            ///SECOND entry
            ?>
            <tr>
                <td>              
                <?php 
                    $stockid=$_POST['code_out'];
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code_out[]" value="<?php print_r($_POST['code_out']); ?>"  />
                <input type="hidden" name="prikey[]" value="<?php print_r($_POST['prikey']); ?>"  />
                </td>    
                
                <td>              
                <?php 
                    $stockid=$_POST['code'];
                    $dbname=($_SESSION['dbname']);
                     $maindb = $this->load->database($dbname, TRUE);  
                    $sql=("select refno as stock_name FROM stock  WHERE code='$stockid'");
                    $customer=$maindb->query($sql)->row_array();
                    echo $customer['stock_name'];             
                    ?>
                <input type="hidden" name="code[]" value="<?php print_r($_POST['code']); ?>"  />                
                </td>                
                
                <td>
                <?php 
                echo ($_POST['description']);
                ?>
                <input type="hidden" name="itemdescription[]" value="<?php print_r($_POST['description']); ?>"  />
                                <input type="hidden" name="trn_type[]" value="<?php print_r($_POST['trn_type']); ?>"/>

                </td>                    
                
                <td>
                <?php 
                print_r('D');
                ?>
                <input type="hidden" name="dcflag[]" value="D"  />
                </td>                                
                <td>
                <?php 
                print_r(round($_POST['qty']));
                ?>
                <input type="hidden" name="qty[]" value="<?php print_r($_POST['qty']); ?>"  />
                </td>
                <td>
                <?php 
                print_r(round($_POST['pcs']));
                ?>
                <input type="hidden" name="pcs[]" value="<?php print_r($_POST['pcs']); ?>"  />
                </td> 
        
<!--                <td>
                <?php 
                print_r($_POST['discper']);
                ?>
                <input type="hidden" name="trn_type[]" value="<?php print_r($_POST['trn_type']); ?>"/>
                <input type="hidden" name="contranid[]" value="<?php print_r($_POST['contranid']); ?>"/>
                <input type="hidden" name="invrate[]" value="<?php print_r($_POST['invrate']); ?>"/>
                <input type="hidden" name="invrate2[]" value="<?php print_r($_POST['invrate2']); ?>"/>
                <input type="hidden" name="invamt[]" value="<?php print_r($_POST['invamt']); ?>"/>
                <input type="hidden" name="invamt2[]" value="<?php print_r($_POST['invamt2']); ?>"/>
                <input type="hidden" name="invcostrate[]" value="<?php print_r($_POST['invcostrate']); ?>"/>
                <input type="hidden" name="invcostrate2[]" value="<?php print_r($_POST['invcostrate2']); ?>"/>
                <input type="hidden" name="invcostamt[]" value="<?php print_r($_POST['invcostamt']); ?>"/>
                <input type="hidden" name="invcostamt2[]" value="<?php print_r($_POST['invcostamt2']); ?>"/>
                
                </td>    -->
                
                <td>
                <?php 
                print_r($_POST['rate2']);
                ?>
                <input type="hidden" name="rate2[]" value="<?php print_r($_POST['rate2']); ?>"  />
                </td>    
                
                <td>
                <?php 
                print_r($_POST['rate']);
                ?>
                <input type="hidden" name="rate[]" value="<?php print_r($_POST['rate']); ?>"  />
                </td>    
           
                
                
                
                <td>
                <?php 
                print_r($_POST['amt2']);
                ?>
                <input type="hidden" name="amt2[]" value="<?php print_r($_POST['amt2']); ?>"  />
                </td>    
        <td>
                <?php 
                print_r($_POST['amt']);
                ?>
                <input type="hidden" name="amt[]" value="<?php print_r($_POST['amt']); ?>"  />
                </td> 
                
                
                <td>
                    <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                </td>      
                
            </tr>
            <?php
            
            
	}
        
        public function add_stockmixing_detail()
	{     
            ?>
                    <?php
                    $currency_name1=($_SESSION['logged_in_user']['currency_name1']);
                    $currency_name2=($_SESSION['logged_in_user']['currency_name2']);
                    ?>
                    <div class="mb-3 col-md-12">                                                          
                        <table  class="table table-bordered" style="width: 100%;overflow:scroll;" aria-describedby="scroll-vertical_info">
                                                            <thead >
                                                            <tr style="font-size:13px;">  
                                                                <th>Stock No</th>
                                                                <th>Opp.Ref.No</th>
                                                                <th>Description</th>
                                                                <th>DR/CR</th>
                                                                <th>Qty</th>
                                                                <th>Pcs.</th>
                                                                <th>Rate <?php echo $currency_name2 ?></th>
                                                                <th>Rate <?php echo $currency_name1 ?></th>
                                                                <th>Amt <?php echo $currency_name2 ?></th>
                                                                <th>Amt <?php echo $currency_name1 ?></th>                                                                                                                        
                                                                <th></th>
                                                                <th>#</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="add_data_res" style="font-size:12px;">
                                                             <?php 
                                                                        if($_POST['id']!='')
                                                                        {
                                                                          $dbname=($_SESSION['dbname']);
                                                                          $maindb=$this->load->database($dbname, TRUE);    
                                                                          $id=$_POST['id'];                                                      
                                                                          $tablesname=explode(",",$_POST['maintabls']);                                                                          
                                                                          $tablesname=$tablesname[1];
                                                                          $sql="select a.*,b.refno as refno,b.refno as refno2 from trans1 a left join stock b  on a.code=b.code left join stock c on a.orderid=c.code where a.doc_no='$id' and a.trn_type='MX'";                                                  
                                                                          $customer=$maindb->query($sql)->result_array();
                                                                          $i=0;
                                                                        }                                                  
                                                                        foreach($customer as $customer_res)
                                                                        {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                            <?php 
//                                                                            echo '<pre>';
//                                                                            print_r($customer_res);
//                                                                            echo '</pre>';
                                                                            print_r($customer_res['refno']);
                                                                            ?>
                                                                            <input type="hidden" name="code[]" value="<?php print_r($customer_res['code']); ?>"  />
                                                                            <input type="hidden" name="prikey[]" value="<?php print_r($customer_res['prikey']); ?>"  />
                                                                            <input type="hidden" name="contranid[]" value="<?php print_r($customer_res['contranid']); ?>"  />
                                                                            </td>   
                                                                            
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['refno2']);
                                                                            ?>
                                                                            <input type="hidden" name="orderid[]" value="<?php print_r($customer_res['orderid']); ?>"  />
                                                                            </td>   
                                                                            
                                                                            
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['description']);
                                                                            ?>
                                                                            <input type="hidden" name="itemdescription[]" value="<?php print_r($customer_res['description']); ?>"  />
                                                                            </td>
                                                                            
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['dcflag']);
                                                                            ?>
                                                                            <input type="hidden" name="dcflag[]" value="<?php print_r($customer_res['dcflag']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r(round($customer_res['qty']));
                                                                            ?>
                                                                            <input type="hidden" name="qty[]" value="<?php print_r($customer_res['qty']); ?>"  />
                                                                            </td> 

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['pcs']);
                                                                            ?>
                                                                            <input type="hidden" name="pcs[]" value="<?php print_r($customer_res['pcs']); ?>"  />
                                                                            </td>    
                                                               
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate2']);
                                                                            ?>
                                                                            <input type="hidden" name="rate2[]" value="<?php print_r($customer_res['rate2']); ?>"  />
                                                                            </td> 
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate']);
                                                                            ?>
                                                                            <input type="hidden" name="rate[]" value="<?php print_r($customer_res['rate']); ?>"  />
                                                                            <input type="hidden" name="trn_type[]" value="<?php print_r($customer_res['trn_type']); ?>"  />

                                                                            </td>  
                         
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt2']);
                                                                            ?>
                                                                            <input type="hidden" name="amt2[]" value="<?php print_r($customer_res['amt2']); ?>"  />
                                                                            </td>    
                                                             
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt']);
                                                                            ?>
                                                                            <input type="hidden" name="amt[]" value="<?php print_r($customer_res['amt']); ?>"  />
                                                                            </td>    

                                                                      

                                                              
                                                                            <td>
                                                                                <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                                                                            </td>      

                                                                        </tr> 
                                                                        <?php 
                                                                        }    
                                                                        ?>    
                                                            </tbody>
                                                            <tbody id="edit_data_res" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                        <a class="btn btn-sm btn-primary" onclick='load_add_more();'
                                                                           style="float:right;">Add Item
                                                                        </a>    
                                                                        
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                            <tbody id="edit_data_res2" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                       
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

            <?php
	}
        
        public function add_voucher_detail()
	{     
            ?>
                    <?php
                    $currency_name1=($_SESSION['logged_in_user']['currency_name1']);
                    $currency_name2=($_SESSION['logged_in_user']['currency_name2']);
                    ?>
                    <div class="mb-3 col-md-12">                                                          
                        <table  class="table table-bordered" style="width: 100%;overflow:scroll;" aria-describedby="scroll-vertical_info">
                                                            <thead >
                                                            <tr style="font-size:13px;">  
                                                                <th>A/C Name</th>
                                                                <th>Debit/Credit</th>
                                                                <th>Amt <?php echo $currency_name1 ?></th>
                                                                <th>Amt <?php echo $currency_name2 ?></th>                                                                                                                        
                                                                <th>A/c Type</th>
                                                                <th>Description</th>
                                                                <th>#</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="add_data_res" style="font-size:12px;">
                                                             <?php 
                                                                        if($_POST['id']!='')
                                                                        {
                                                                          $dbname=($_SESSION['dbname']);
                                                                          $maindb=$this->load->database($dbname, TRUE);    
                                                                          $id=$_POST['id']; 
                                                                          $tablesname=explode(",",$_POST['maintabls']);                                                                          
                                                                          $tablesname=$tablesname[2];
                                                                          
                                                                          $sql="select a.*,b.ac_name from trans a left join ledgmast b on a.ac_code=b.ac_code  where a.doc_no='$id'";                                                  
                                                                          $customer=$maindb->query($sql)->result_array();
                                                                          $i=0;
                                                                        }                                                  
                                                                        foreach($customer as $customer_res)
                                                                        {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                            <?php 
//                                                                  
                                                                            print_r($customer_res['ac_name']);
                                                                            ?>
                                                                            <input type="hidden" name="transid[]" value="<?php print_r($customer_res['transid']); ?>"  />
                                                                            <input type="hidden" name="ac_code[]" value="<?php print_r($customer_res['ac_code']); ?>"  />
                                                                            <input type="hidden" name="trn_type[]" value="<?php print_r($customer_res['trn_type']); ?>"  />
                                                                            </td>   
                                                                            
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['dcflag']);
                                                                            ?>
                                                                            <input type="hidden" name="dcflag[]" value="<?php print_r($customer_res['dcflag']); ?>"  />
                                                                            </td>   
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt']);
                                                                            ?>
                                                                            <input type="hidden" name="amt[]" value="<?php print_r($customer_res['amt']); ?>"  />
                                                                            </td>   
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt2']);
                                                                            ?>
                                                                            <input type="hidden" name="amt2[]" value="<?php print_r($customer_res['amt2']); ?>"  />
                                                                            </td>   
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['actype']);
                                                                            ?>
                                                                            <input type="hidden" name="actype[]" value="<?php print_r($customer_res['actype']); ?>"  />
                                                                            </td>   
                                                                            
                                                                            
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['description']);
                                                                            ?>
                                                                            <input type="hidden" name="itemdescription[]" value="<?php print_r($customer_res['description']); ?>"  />
                                                                            </td>
                                                              
                                                                            <td>
                                                                                <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                                                                            </td>      

                                                                        </tr> 
                                                                        <?php 
                                                                        }    
                                                                        ?>    
                                                            </tbody>
                                                            <tbody id="edit_data_res" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                        <a class="btn btn-sm btn-primary" id="item-add" onclick='load_add_more();'
                                                                           style="float:right;">Add Item
                                                                        </a>    
                                                                        
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                            <tbody id="edit_data_res2" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                       
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

            <?php
	}
        

        
        public function add_consignment_detail1()
	{            
            ?>
                    <?php
                    $currency_name1=($_SESSION['logged_in_user']['currency_name1']);
                    $currency_name2=($_SESSION['logged_in_user']['currency_name2']);
                    ?>
  
                    <div class="mb-3 col-md-12">                                                          
                        <table  class="table table-bordered" style="width: 100%;overflow:scroll;" aria-describedby="scroll-vertical_info">
                                                            <thead >
                                                            <tr style="font-size:13px;">  
                                                                <th>Stock No</th>
                                                                <th>Pcs</th>
                                                                <th>Qty</th>
                                                                <th>Rap. Price</th>
                                                                <th>Rap.Disc.</th>
                                                                <th>Rate <span><?php echo $currency_name1?></span></th>
                                                                <th>Amt <span><?php echo $currency_name1?></span></th>
                                                                <th>Rate <span><?php echo $currency_name2?></span></th>
                                                                <th>Amt <span><?php echo $currency_name2?></span></th>
                                                                <th>Description</th>
                                                                <th>#</th>
                                                            </tr>
                                                            </thead>
                                                            <tbody id="add_data_res" style="font-size:12px;">
                                                             <?php 
                                                                        if($_POST['id']!='')
                                                                        {
//                                                                            print_r($_POST);
                                                                            
                                                                          $dbname=($_SESSION['dbname']);
                                                                          $maindb=$this->load->database($dbname, TRUE);    
                                                                          $id=$_POST['id'];                                                      
                                                                          $tablesname=explode(",",$_POST['maintabls']);                                                                          
                                                                          $tablesname=$tablesname[1];                                                                          
                                                                          if ($tablesname=='contran')
                                                                          {
                                                                              $sql="select a.*,b.refno from contran a left join stock b  on a.code=b.code where a.mainid='$id'";
                                                                          }
                                                                          else if ($tablesname=='tperforma')
                                                                          {
                                                                              $sql="select a.*,b.refno from tperforma a left join stock b  on a.code=b.code where a.mainid='$id'";
                                                                          }
//                                                                          $real_querys = "SELECT AC_NAME as name,AC_CODE as id FROM LEDGMAST ORDER BY AC_NAME";
                                                                          $customer=$maindb->query($sql)->result_array();
                                                                        }//                                                            
                                                                        foreach($customer as $customer_res)
                                                                        {
                                                                        ?>
                                                                        <tr>
                                                                            <td>
                                                                            <?php 
//                                                                            echo '<pre>';
//                                                                            print_r($customer_res);
//                                                                            echo '</pre>';
                                                                            print_r($customer_res['refno']);
                                                                            ?>
                                                                            <input type="hidden" name="code[]" value="<?php print_r($customer_res['code']); ?>"  />
                                                                            <input type="hidden" name="contranid[]" value="<?php print_r($customer_res['contranid']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['pcs']);
                                                                            ?>
                                                                            <input type="hidden" name="pcs[]" value="<?php print_r($customer_res['pcs']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r(round($customer_res['qty']));
                                                                            ?>
                                                                            <input type="hidden" name="qty[]" value="<?php print_r($customer_res['qty']); ?>"  />
                                                                            </td>    
  
                                                                            <td  >
                                                                            <?php 
                                                                            print_r($customer_res['rap_price']);
                                                                            ?>
                                                                            <input type="hidden" value="<?php print_r($customer_res['rap_price']); ?>"  />
                                                                            </td>    
                                                                            <td  >
                                                                            <?php 
                                                                            print_r($customer_res['discper']);
                                                                            ?>
                                                                            <input type="hidden" name="discper[]" value="<?php print_r($customer_res['discper']); ?>"  />
                                                                            <input type="hidden" name="dcflag[]" value="<?php print_r($customer_res['dcflag']); ?>"  />
                                                                            <input type="hidden" name="trn_type[]" value="<?php print_r($customer_res['trn_type']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate']);
                                                                            ?>
                                                                            <input type="hidden" name="rate[]" value="<?php print_r($customer_res['rate']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt']);
                                                                            ?>
                                                                            <input type="hidden" name="amt[]" value="<?php print_r($customer_res['amt']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['rate2']);
                                                                            ?>
                                                                            <input type="hidden" name="rate2[]" value="<?php print_r($customer_res['rate2']); ?>"  />
                                                                            </td>    

                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['amt2']);
                                                                            ?>
                                                                            <input type="hidden" name="amt2[]" value="<?php print_r($customer_res['amt2']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                            <?php 
                                                                            print_r($customer_res['description']);
                                                                            ?>
                                                                            <input type="hidden" name="itemdescription[]" value="<?php print_r($customer_res['description']); ?>"  />
                                                                            </td>    
                                                                            <td>
                                                                                <a href="#" onclick="deleteRow(this)" class="btn btn-sm btn-danger deleteButton"><i class="ri-delete-bin-line"></i></a>
                                                                            </td>      

                                                                        </tr> 
                                                                        <?php 
                                                                        }    
                                                                        ?>    
                                                            </tbody>
                                                            <tbody id="edit_data_res" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                        <a class="btn btn-sm btn-primary" onclick='load_add_more();'
                                                                           style="float:right;">Add Item
                                                                        </a>    
                                                                        
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                            <tbody id="edit_data_res2" style="font-size:12px;">
                                                                <tr>
                                                                    <td colspan="12">
                                                                       
                                                                    </td> 
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </div>

            <?php
	}
       
        public function add_consignment_detail2()
	{
            
            ?>
            
                                                        <div class="mb-3 col-md-12">  
                                                        <div class="mb-3  col-md-3  pull-left" style="float:left;padding:0px 5px;">
                                                            <label for="stock_no" class="form-label">Stock No</label>
                                                    
                                                            <input list="data_list_stock" placeholder="Search stock here..." type="text" 
                                                                   id="stock_no[]" name="stock_no" class="form-control">
                                                            <datalist id="data_list_stock">
                                                                                                                      <option>DISCOUNT                                                              </option>
                                                                                                                        <option>EXCHANGE DIFF                                                         </option>
                                                                                                                        <option>LABOUR                                                                </option>
                                                                                                                        <option>PURCHASE A/C                                                          </option>
                                                                                                                        <option>SALES A/C</option>
                                                                                                                        <option>TEST DATA                                                             </option>
                                                            </datalist>

                                                        </div>
                                                        </div>

            <?php
	}
    
}