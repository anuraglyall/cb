<?php
//include database class
include_once 'db.php';
$db = new DB();


// echo '<pre>';
// print_r($_POST['ids']);
// exit;

//get images id and generate ids array
$idArray = explode(",",$_POST['ids']);

//update images order
$db->updateOrder($idArray);
?>