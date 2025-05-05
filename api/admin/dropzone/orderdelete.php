<?php
	ini_set('session.gc_maxlifetime', 108000);
        $db_host = "localhost";
        $db_name = "aacwdbzarb";
        $db_user = "aacwdbzarb";
        $db_pass = "QvD93dW9az";

//	$db_host = "localhost";
//	$db_name = "marketpl_cms";
//	$db_user = "marketpl_content";
//	$db_pass = "%HFwl}=HO2_2";
	try{
		$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
		$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOException $e){
		echo $e->getMessage();
	}
	
	// print_r($_POST);
	$id=$_POST['id'];
	$stmtone02 = $db_con->prepare("update styles_images set status='2' where id='$id'");
	$stmtone02->execute();
	

?>