<?php
//$db_host = "localhost";
//$db_name = "aacwdbzarb";
//$db_user = "aacwdbzarb";
//$db_pass = "QvD93dW9az";

class DB{
	//database configuration
	private $dbHost     = "localhost";
	private $dbUsername = "aacwdbzarb";
	private $dbPassword = "QvD93dW9az";
	private $dbName     = "aacwdbzarb";
	private $imgTbl     = 'styles_images';
	
	function __construct(){
		if(!isset($this->db)){
            // Connect to the database
            $conn = new mysqli($this->dbHost, $this->dbUsername, $this->dbPassword, $this->dbName);
            if($conn->connect_error){
                die("Failed to connect with MySQL: " . $conn->connect_error);
            }else{
                $this->db = $conn;
            }
        }
	}
	
	function getRows(){
		
		$id=$_GET['id'];
		$sku_id=$_GET['sku_id'];
		$query = $this->db->query("SELECT * FROM ".$this->imgTbl." where id='$id' ORDER BY img_order ASC limit 10");
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$result[] = $row;
			}
		}else{
			$result = FALSE;
		}
		if(empty($result))
		{
		$query = $this->db->query("SELECT * FROM ".$this->imgTbl." where sku_id='$sku_id' ORDER BY img_order ASC limit 10");
		if($query->num_rows > 0){
			while($row = $query->fetch_assoc()){
				$result[] = $row;
			}
		}else{
			$result = FALSE;
		}	
		}
		
		return $result;
	}
	
	function updateOrder($id_array){
		$count = 1;
		foreach ($id_array as $id){
			$update = $this->db->query("UPDATE ".$this->imgTbl." SET img_order = $count WHERE id = $id");
			$count ++;	
		}
		return TRUE;
	}
}
?>