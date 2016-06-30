<?php
$page_title = 'Item for sale';
include ('includes/header.html');
include ('includes/left_content.html');
?>


<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form
	if (is_numeric($_SESSION['user_id'])) {
	
	
	// initialize an error array.
	$errors = array();
	
	 // Check for a Category:
	if (!isset($_POST['category'])) {
		$errors[] = 'You forgot to select a category.';
	} else {
		$category = mysqli_real_escape_string($dbc, trim($_POST['category']));
	}
	
	// Check for a item's name:
	if (empty($_POST['name'])) {
		$errors[] = "You forgot to enter item's name.";
	} else {
		$name = mysqli_real_escape_string($dbc, trim($_POST['name']));
	}
	
	// Check for a model name:
	if (!empty($_POST['model'])) {
		$model = mysqli_real_escape_string($dbc, trim($_POST['model']));
	} else {
		$model = NULL;
	}
	
	 // Check for a condition:
	if (!isset($_POST['condition'])) {
		$errors[] = 'You forgot to select a condition.';
	} else {
		$condition = mysqli_real_escape_string($dbc, trim($_POST['condition']));
	}
	
	
	
	// Check for a item's price:
	if (!empty($_POST['price'])) {
		$price = mysqli_real_escape_string($dbc, trim($_POST['price']));
	} else {
		$errors[] = "You forgot to enter item's price.";
	}
	
	if (is_numeric($price) && $price > 0) {
		$price = $price;
	}else{
		$errors[] = "The price must be numeric value. Please type a number without a $ sign.";
	}
		
	// Check for a item's description:
	if (empty($_POST['description'])) {
		$errors[] = "You forgot to enter item's description.";
	} else {
		$description = mysqli_real_escape_string($dbc, trim($_POST['description']));
	}
	
	//Function to get extension of the file
	function getExtension($str) {

         $i = strrpos($str,".");
         if (!$i) { return ""; } 

         $l = strlen($str) - $i;
         $ext = substr($str,$i+1,$l);
         return $ext;
    }

	//Check for uploaded image size and extention FOR 4 PICTURES
	// Change size, to fit web-site parameters FOR 4 PICTURES
	// Change name and add user id to a pictures
	define ("MAX_SIZE","40000");
	

	$image1 = $_FILES["upload1"]["name"];
	$uploadedfile = $_FILES['upload1']['tmp_name'];
	
	$image2 = $_FILES["upload2"]["name"];
	$uploadedfile = $_FILES['upload2']['tmp_name'];
	
	$image3 = $_FILES["upload3"]["name"];
	$uploadedfile = $_FILES['upload3']['tmp_name'];
	
	$image4 = $_FILES["upload4"]["name"];
	$uploadedfile = $_FILES['upload4']['tmp_name'];
	
		
	if ($image1 && $image2 && $image3 && $image4) {
		$filename = stripslashes($_FILES['upload1']['name']);
        $extension = getExtension($filename);
		$extension = strtolower($extension);
		
		$filename2 = stripslashes($_FILES['upload2']['name']);
        $extension2 = getExtension($filename2);
		$extension2 = strtolower($extension2);
		
		$filename3 = stripslashes($_FILES['upload3']['name']);
        $extension3 = getExtension($filename3);
		$extension3 = strtolower($extension3);
		
		$filename4 = stripslashes($_FILES['upload4']['name']);
        $extension4 = getExtension($filename4);
		$extension4 = strtolower($extension4);
		
	if (($extension != "jpg") && ($extension != "jpeg") && ($extension != "png") && ($extension != "gif")) {
		echo ' Unknown Image extension in file 1';
		$errors[] = "Unknown Image extension in file 1";
	}elseif (($extension2 != "jpg") && ($extension2 != "jpeg") && ($extension2 != "png") && ($extension2 != "gif")) {
		echo ' Unknown Image extension in file 2';
		$errors[] = "Unknown Image extension in file 2";
	}elseif (($extension3 != "jpg") && ($extension3 != "jpeg") && ($extension3 != "png") && ($extension3 != "gif")) {
		echo ' Unknown Image extension in file 3';
		$errors[] = "Unknown Image extension in file 3";
	}elseif (($extension4 != "jpg") && ($extension4 != "jpeg") && ($extension4 != "png") && ($extension4 != "gif")) {
		echo ' Unknown Image extension in file 4';
		$errors[] = "Unknown Image extension in file 4";
    }else{
		$size=filesize($_FILES['upload1']['tmp_name']);
		$size2=filesize($_FILES['upload2']['tmp_name']);
		$size3=filesize($_FILES['upload3']['tmp_name']);
		$size4=filesize($_FILES['upload4']['tmp_name']);
 
		if ($size > (MAX_SIZE*1024) or $size2 > (MAX_SIZE*1024) or $size3 > (MAX_SIZE*1024) or $size4 > (MAX_SIZE*1024)) {
			echo "You have exceeded the size limit";
			$errors[] = "You have exceeded the size limit";
		}
		
		$user_id = $_SESSION['user_id'];
		
		//Photo 1
		if($extension=="jpg" || $extension=="jpeg" ) {
			$uploadedfile = $_FILES['upload1']['tmp_name'];
			$src = imagecreatefromjpeg($uploadedfile);
		}else if ($extension=="png") {
			$uploadedfile = $_FILES['upload1']['tmp_name'];
			$src = imagecreatefrompng($uploadedfile); 
		}else{
			$src = imagecreatefromgif($uploadedfile);
		}
		list($width,$height)=getimagesize($uploadedfile);
		
		$newwidth=170;
		$newheight=($height/$width)*$newwidth;
		$tmp=imagecreatetruecolor($newwidth,$newheight);
		
		$newwidth1=600;
		$newheight1=($height/$width)*$newwidth1;
		$tmp1=imagecreatetruecolor($newwidth1,$newheight1);
		
		imagecopyresampled($tmp,$src,0,0,0,0,$newwidth,$newheight,$width,$height);
		imagecopyresampled($tmp1,$src,0,0,0,0,$newwidth1,$newheight1,$width,$height);

		$a = md5(uniqid(rand(), true));
		$filename = "images/uploaded_sized/".$a."_user_id=".$user_id. "_file_name=". $_FILES['upload1']['name'];
		$filename1 = "images/uploaded/".$a."_". $_FILES['upload1']['name'];

		imagejpeg($tmp,$filename,100);
		imagejpeg($tmp1,$filename1,100);

		imagedestroy($src);
		imagedestroy($tmp);
		imagedestroy($tmp1);
		
		//Photo 2
		if($extension2=="jpg" || $extension2=="jpeg" ) {
			$uploadedfile2 = $_FILES['upload2']['tmp_name'];
			$src2 = imagecreatefromjpeg($uploadedfile2);
		}else if ($extension2=="png") {
			$uploadedfile2 = $_FILES['upload2']['tmp_name'];
			$src2 = imagecreatefrompng($uploadedfile2); 
		}else{
			$src2 = imagecreatefromgif($uploadedfile2);
		}
		list($width2,$height2)=getimagesize($uploadedfile2);
		
		$newwidth2=170;
		$newheight2=($height2/$width2)*$newwidth2;
		$tmp2=imagecreatetruecolor($newwidth2,$newheight2);
		
		$newwidth12=600;
		$newheight12=($height2/$width2)*$newwidth12;
		$tmp12=imagecreatetruecolor($newwidth12,$newheight12);
		
		imagecopyresampled($tmp2,$src2,0,0,0,0,$newwidth2,$newheight2,$width2,$height2);
		imagecopyresampled($tmp12,$src2,0,0,0,0,$newwidth12,$newheight12,$width2,$height2);

		
		$a2 = md5(uniqid(rand(), true));
		$filename2 = "images/uploaded_sized/".$a2."_user_id=".$user_id. "_file_name=". $_FILES['upload2']['name'];
		$filename12 = "images/uploaded/".$a2."_". $_FILES['upload2']['name'];		

		imagejpeg($tmp2,$filename2,100);
		imagejpeg($tmp12,$filename12,100);

		imagedestroy($src2);
		imagedestroy($tmp2);
		imagedestroy($tmp12);

		//Photo 3
		if($extension3=="jpg" || $extension3=="jpeg" ) {
			$uploadedfile3 = $_FILES['upload3']['tmp_name'];
			$src3 = imagecreatefromjpeg($uploadedfile3);
		}else if ($extension3=="png") {
			$uploadedfile3 = $_FILES['upload3']['tmp_name'];
			$src3 = imagecreatefrompng($uploadedfile3); 
		}else{
			$src3 = imagecreatefromgif($uploadedfile3);
		}
		list($width3,$height3)=getimagesize($uploadedfile3);
		
		$newwidth3=170;
		$newheight3=($height3/$width3)*$newwidth3;
		$tmp3=imagecreatetruecolor($newwidth3,$newheight3);
		
		$newwidth13=600;
		$newheight13=($height2/$width2)*$newwidth13;
		$tmp13=imagecreatetruecolor($newwidth12,$newheight13);
		
		imagecopyresampled($tmp3,$src3,0,0,0,0,$newwidth3,$newheight3,$width3,$height3);
		imagecopyresampled($tmp13,$src3,0,0,0,0,$newwidth13,$newheight13,$width3,$height3);
	
		$a3 = md5(uniqid(rand(), true));
		$filename3 = "images/uploaded_sized/".$a3."_user_id=".$user_id. "_file_name=". $_FILES['upload3']['name'];
		$filename13 = "images/uploaded/".$a3."_". $_FILES['upload3']['name'];
		
		imagejpeg($tmp3,$filename3,100);
		imagejpeg($tmp13,$filename13,100);

		imagedestroy($src3);
		imagedestroy($tmp3);
		imagedestroy($tmp13);	

		//Photo 4
		if($extension4=="jpg" || $extension4=="jpeg" ) {
			$uploadedfile4 = $_FILES['upload4']['tmp_name'];
			$src4 = imagecreatefromjpeg($uploadedfile4);
		}else if ($extension4=="png") {
			$uploadedfile4 = $_FILES['upload4']['tmp_name'];
			$src4 = imagecreatefrompng($uploadedfile4); 
		}else{
			$src4 = imagecreatefromgif($uploadedfile4);
		}
		list($width4,$height4)=getimagesize($uploadedfile4);
		
		$newwidth4=170;
		$newheight4=($height4/$width4)*$newwidth4;
		$tmp4=imagecreatetruecolor($newwidth4,$newheight4);
		
		$newwidth14=600;
		$newheight14=($height4/$width4)*$newwidth14;
		$tmp14=imagecreatetruecolor($newwidth14,$newheight14);
		
		imagecopyresampled($tmp4,$src4,0,0,0,0,$newwidth4,$newheight4,$width4,$height4);
		imagecopyresampled($tmp14,$src4,0,0,0,0,$newwidth14,$newheight14,$width4,$height4);

		$a4 = md5(uniqid(rand(), true));
		$filename4 = "images/uploaded_sized/".$a4."_user_id=".$user_id. "_file_name=". $_FILES['upload4']['name'];
		$filename14 = "images/uploaded/".$a4."_". $_FILES['upload4']['name'];

		imagejpeg($tmp4,$filename4,100);
		imagejpeg($tmp14,$filename14,100);

		imagedestroy($src4);
		imagedestroy($tmp4);
		imagedestroy($tmp14);		
	}
}

	if (empty($errors)) { // If everything's OK.
	
		//Display a page with loaded information for final submition
		
		$get_category = "SELECT category_name FROM category WHERE category_id ='$category'";
		$r = @mysqli_query ($dbc, $get_category);
		$row = mysqli_fetch_array($r, MYSQLI_ASSOC);
		$categoty_name = $row['category_name'];
	
	}else{
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		exit();
		
	} // End of if (empty($errors)) IF.

} // End of the session_id conditionsl

} //End of main if POST statment

?>

<form action="<?php if($_SESSION['user_id'] == 38) {
						echo 'item_success_upload_admin.php'; 
					}else{
						echo 'item_success_upload.php'; 
					}
							?>" method="POST">
<script> $.validate(); </script> <!-- Add java script to validate form with sticky form -->
    <div class="center_content">
      <div class="center_title_bar">Plese, review and submit:</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>Category:</strong></label>
              <input type="text" data-validation="required" name="category" size="20" maxlength="20" value="<?php echo $categoty_name; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Item's name</strong></label>
              <input type="text" data-validation="required" name="name" size="20" maxlength="40" value="<?php echo $name; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Model</strong></label>
              <input type="text" name="model" size="20" maxlength="40" value="<?php echo $model; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Condition</strong></label>
              <input type="text" name="condition" data-validation="required" size="20" maxlength="60" value="<?php echo $condition; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Price</strong></label>
			  <input type="text" data-validation="required" name="price" size="30" maxlength="60" value="<?php echo $price; ?>" /></p>
			</div>
			<div class="form_row">
			   <label class="contact"><strong>Product description:</strong></label>
			   (<span id="pres-max-length">100</span> characters left)
			   <textarea name="description" id="description" data-validation="required" rows="5" cols="30"><?php echo $description; ?></textarea></p>
            </div>
			
			<p><label><strong>Photos:</strong></label></p>
				<div class="form_row">
				 <p></p>
				 <p><label class="contact"><strong>Photo 1:</strong></label><input type="radio" name="main" value="img_1" />Check this to be a main picture</p></p>
				<a href="javascript:popImage('<?php echo $filename1; ?>','Picture 1')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $filename; ?>" alt="" border="0" /></a>
				 <p></p>
				 <p><label class="contact"><strong>Photo 2:</strong></label><input type="radio" name="main" value="img_2" />Check this to be a main picture</p></p>
				<a href="javascript:popImage('<?php echo $filename12; ?>','Picture 2')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $filename2; ?>" alt="" border="0" /></a>
				 <p></p>
				 <p><label class="contact"><strong>Photo 3:</strong></label><input type="radio" name="main" value="img_3" />Check this to be a main picture</p></p>
				<a href="javascript:popImage('<?php echo $filename13; ?>','Picture 3')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $filename3; ?>" alt="" border="0" /></a>
				 <p></p>
				 <p><label class="contact"><strong>Photo 4:</strong></label><input type="radio" name="main" value="img_4" />Check this to be a main picture</p></p>
				<a href="javascript:popImage('<?php echo $filename14; ?>','Picture 4')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $filename4; ?>" alt="" border="0" /></a>
				 <p></p>
				</div>
				  <p></p>
				  <p></p>
				  <p></p>
				  <hr>
				<input type = "hidden" name="big_pic_1_name" value="<?php echo $filename1; ?>"/>
				<input type = "hidden" name="small_pic_1_name" value="<?php echo $filename; ?>"/>
				
				<input type = "hidden" name="big_pic_2_name" value="<?php echo $filename12; ?>"/>
				<input type = "hidden" name="small_pic_2_name" value="<?php echo $filename2; ?>"/>

				<input type = "hidden" name="big_pic_3_name" value="<?php echo $filename13; ?>"/>
				<input type = "hidden" name="small_pic_3_name" value="<?php echo $filename3; ?>"/>		

				<input type = "hidden" name="big_pic_4_name" value="<?php echo $filename14; ?>"/>
				<input type = "hidden" name="small_pic_4_name" value="<?php echo $filename4; ?>"/>	
				
				<p><input type="submit" name="submit" value="Submit" /></p>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
   </div>
</form>

<script>
// Restrict presentation length
$('#description').restrictLength( $('#pres-max-length') );
</script>

<?php
include ('includes/right_content.html');
include ('includes/footer.html');
?>