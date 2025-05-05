<?php
error_reporting(-1);
//'hostname' => 'localhost',
//	'username' => 'aacwdbzarb',
//	'password' => 'QvD93dW9az',
//	'database' => 'aacwdbzarb',
if(isset($_POST['submit']))
{	
($user_id=$_POST['user_id']);
($style_id=$_POST['style_id']);
($cond=$_POST['cond']);
if($cond=='1')
{    
$i=0;
$target_path="images/";
ini_set('session.gc_maxlifetime', 108000);
$db_host = "localhost";
$db_name = "aacwdbzarb";
$db_user = "aacwdbzarb";
$db_pass = "QvD93dW9az";
	
try{
$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch(PDOException $e){
echo $e->getMessage();
}
$i=0;
$j=1;
foreach ($_FILES['imagesss']['name'] as $file_name) {
    $file_name = $_FILES['imagesss']['name'][$i];

    $ext = explode('.', basename($_FILES['imagesss']['name'][$i]));
    $file_extension = end($ext);

    // Create a folder for each style_id
    $style_folder = 'uploads/' . $style_id;
    if (!is_dir($style_folder)) {
        mkdir($style_folder, 0755, true);
    }

    $target_paths = $style_folder . '/' . md5(uniqid()) . "." . $ext[count($ext) - 1];
    
//    exit;
    if (move_uploaded_file($_FILES['imagesss']['tmp_name'][$i], $target_paths)) {
        $imagss = 'https://64facetscrm.com/dropzone/' . $target_paths;
        if ($imagss != '') {
//            echo "insert into styles_images set sort='$j',img_order='$j',"
//                . "`added_date`=NOW(),`user_id`='$user_id',`style_id`='$style_id',`img`='$imagss'";
//            
//            exit;  
            $stmtone02 = $db_con->prepare("insert into styles_images set sort='$j',img_order='$j',"
                . "`added_date`=NOW(),`user_id`='$user_id',`style_id`='$style_id',`img`='$imagss'");
            $stmtone02->execute();
        }
    }
    $i++;
    $j++;
}

}
$url= 'index.php?style_id='.$style_id.'&user_id='.$user_id;
//exit;
header('Location: '.$url);
exit();
}  

?>
<!DOCTYPE HTML>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Drag&amp;Drop Reorder</title>
<link href="style.css" rel="stylesheet" type="text/css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	$('.reorder_link').on('click',function(){
		$("ul.reorder-photos-list").sortable({ tolerance: 'pointer' });
		$('.reorder_link').html('save reordering');
		$('.reorder_link').attr("id","save_reorder");
		$('#reorder-helper').slideDown('slow');
		$('.image_link').attr("href","javascript:void(0);");
		$('.image_link').css("cursor","move");
		$("#save_reorder").click(function( e ){
			if( !$("#save_reorder i").length ){
				$(this).html('').prepend('<img src="images/refresh-animated.gif"/>');
				$("ul.reorder-photos-list").sortable('destroy');
				$("#reorder-helper").html( "Reordering Photos - This could take a moment. Please don't navigate away from this page." ).removeClass('light_box').addClass('notice notice_error');
	
				var h = [];
				$("ul.reorder-photos-list li").each(function() {  h.push($(this).attr('id').substr(9));  });
				
				$.ajax({
					type: "POST",
					url: "orderUpdate.php",
					data: {ids: " " + h + ""},
					success: function(){
						window.location.reload();
					}
				});	
				return false;
			}	
			e.preventDefault();		
		});
	});
});
</script>
<style>
.reorder_link {
    color: #3675B4;
    border: solid 2px #3675B4;
    border-radius: 3px;
    text-transform: uppercase;
    background: #fff;
    font-size: 12px;
    padding: 5px 5px;
    margin: 0px;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.35s;
    -moz-transition: all 0.35s;
    -webkit-transition: all 0.35s;
    -o-transition: all 0.35s;
    white-space: nowrap;
}
.reorder_link2 {
    color: #3675B4;
    border: solid 2px #3675B4;
    border-radius: 3px;
    text-transform: uppercase;
    background: #fff;
    font-size: 12px;
    padding: 5px 5px;
    margin: 0px;
    font-weight: bold;
    text-decoration: none;
    transition: all 0.35s;
    -moz-transition: all 0.35s;
    -webkit-transition: all 0.35s;
    -o-transition: all 0.35s;
    white-space: nowrap;
}
.gallery ul li {
    padding: 0px;
    border: 2px solid #ccc;
    float: left;
    margin: 2px 2px;
    background: none;
    width: auto;
    height: auto;
}
.gallery {
    width: 100%;
    float: left;
    margin-top: 10px;
}
.gallery img {
    width: 50px;
}
</style>
</head>
<body>
<div style="margin-top:5px;">
	<a href="javascript:void(0);" style="float:left;"  class="btn outlined mleft_no reorder_link btn-sm" id="save_reorder">reorder photos</a>
	<a  href="javascript:void(0);" style="float:right;" onclick="$('#main_file').click();" class="btn outlined reorder_link2 btn-sm" id="upload_photos">Upload photos</a>
	<div align="center" style="font-weight:bold;color:maroon;display:none;" id="display_error" >Please Wait....</div>
	
	<?php
	$id=$_GET['id'];
	$user_id=$_GET['user_id'];
	$style_id=$_GET['style_id'];
	// print_r($_GET);
	?>
        <div id="reorder-helper" class="light_box" style="display:none;">1. Drag photos to reorder.<br>2. Click 'Save Reordering' when finished.</div>
        <div class="gallery">
        <ul class="reorder_ul reorder-photos-list">
                <?php 
		ini_set('session.gc_maxlifetime', 108000);
		$db_host = "localhost";
		$db_name = "aacwdbzarb";
		$db_user = "aacwdbzarb";
		$db_pass = "QvD93dW9az";
		
		try{
			
			$db_con = new PDO("mysql:host={$db_host};dbname={$db_name}",$db_user,$db_pass);
			$db_con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		}
		catch(PDOException $e){
			echo $e->getMessage();
		}
		$stmtone3 = $db_con->prepare("update styles_images set sort=img_order where style_id='$style_id' and status IS NULL"); 			
		$stmtone3->execute();
		$stmtone2 = $db_con->prepare("SELECT * FROM styles_images where style_id='$style_id' and status IS NULL order by img_order ASC"); 			
		$stmtone2->execute();
		$result2 = $stmtone2->fetchAll(\PDO::FETCH_ASSOC);
                
//                print_r($result2);
                
                if(!empty($result2)){
		foreach($result2 as $row){
		?>
                <li id="image_li_<?php echo $row['id']; ?>" class="ui-sortable-handle"><a href="javascript:void(0);" style="float:none;" class="image_link">
		<img src="<?php echo $row['img']; ?>" alt=""></a>
		<div align="center"><a  onclick="delete_gallery('<?php echo $row['id']; ?>');" style="background-color:maroon;padding:5px;color:white;font-size:12px;">Delete</a></div>
		</li>
                <?php } } ?>
        </ul>
    </div>
</div>
<form id="main_form_datass" style="display:none;" enctype="multipart/form-data"
      action="index.php?style_id=<?php echo $style_id;?>&user_id=<?php echo $user_id;?>" method="POST">
	<input type="text" name="user_id" value="<?php echo $user_id;?>" />
	<input type="text" name="style_id" value="<?php echo $style_id;?>" />
	<input type="text" name="cond" id="cond" value="1" />
	<input type="submit" name = "submit" id="submit" value = "Submit">
	<input type="file" onchange="$('#display_error').css('display','block'),$('#cond').val('1'),$('#submit').click();" required id="main_file" name="imagesss[]" multiple value="<?php echo $id;?>" />
</form>

<script>
function delete_gallery(id)
{
	$('#display_error').css('display','block');
	
	
                                            $.ajax({type: "POST", url: "orderdelete.php",
                                            data: {id: id}}).done(function (html) {
											window.location.reload();
                                            });

}
</script>

</body>
</html>
