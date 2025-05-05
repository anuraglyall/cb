<?php
echo '<pre>';
print_r($_FILES);
  
exit;
($vid=$_POST['variant_id']);
($user_id=$_POST['user_id']);
($sku_id=$_POST['sku_id']);
$i=0;
$target_path="images/";

ini_set('session.gc_maxlifetime', 108000);
$db_host = "localhost";
	$db_name = "marketpl_cms";
	$db_user = "marketpl_content";
	$db_pass = "%HFwl}=HO2_2";
	
	try{
		
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}

$i=0;
foreach($_FILES['imagesss']['name'] as $file_name)
{
$i++;	
	
$file_name = $_FILES['imagesss']['name'][$i];
$ext = explode('.', basename($_FILES['imagesss']['name'][$i])); //explode file name from dot(.) 
$file_extension = end($ext);
$target_paths = $target_path . md5(uniqid()) .
                        "." . $ext[count($ext) - 1]; 
	// echo '<br>';
	
	if (move_uploaded_file($_FILES['imagesss']['tmp_name'][$i], $target_paths)) {
	$imagss = 'http://content.bajaao.org/content2022/dropzone/' . $target_paths;
	//echo '<br>';
	if($imagss!='')
	{	
	$stmtone02 = $db_con->prepare("insert into cms_sku_images set img_order='$i',sort='$i',`added_date`=NOW(),`user_id`='$user_id',`sku_id`='$sku_id',`sku_variants_id`='$vid',`image_url`='$imagss'");
    $stmtone02->execute();
	}
	}		
						
						
						
}



exit;

// header('Location: index.php?id='.$vid.'&user_id='.$user_id.'&sku_id='.$sku_id');

   
?>
<script>
history.go(-1);
</script>