<?php
$page_title = 'Success!';
include ('includes/header.html');
include ('includes/left_content.html');
?>


<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form
	if (is_numeric($_SESSION['user_id'])) {
			
		//Assign form info to the variables
		
		$category_name = $_POST['category']; //Get category id
		$q = "SELECT category_id FROM category WHERE category_name = '$category_name' " ;
		$r = @mysqli_query ($dbc, $q);
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$category_id = $row['category_id'];
		}
		
		//Get Departmemnt ID
		if ($category_id > 0) {
			if ($category_id == 2) {
				$department_id = 1;
			}elseif ($category_id == 7 or $category_id == 8) {
				$department_id = 3;
			}else{
				$department_id = 2;
			}
		}

		$item_name = $_POST['name'];
		$model = $_POST['model'];
		$condition = $_POST['condition'];
		$price = $_POST['price'];	
		$description = $_POST['description'];
		
		// Save pictures path to the directory into a database for a future revival
		$big_pic_1_name = $_POST['big_pic_1_name'];
		$big_pic_2_name = $_POST['big_pic_2_name'];
		$big_pic_3_name = $_POST['big_pic_3_name'];
		$big_pic_4_name = $_POST['big_pic_4_name'];

		$small_pic_1_name = $_POST['small_pic_1_name'];	
		$small_pic_2_name = $_POST['small_pic_2_name'];
		$small_pic_3_name = $_POST['small_pic_3_name'];
		$small_pic_4_name = $_POST['small_pic_4_name'];

		//Define which picture is main picture
		if (isset($_POST['main'])) {
			$main_item_pic = $_POST['main'];
			
			if ($main_item_pic == 'img_1') {
				$main_img_num = 1;
			}elseif ($main_item_pic == 'img_2') {
				$main_img_num = 2;
			}elseif ($main_item_pic == 'img_3') {
				$main_img_num = 3;
			}else{
				$main_img_num = 4;
			}
		}else{
			$main_img_num = 1;
		}

	}else{
		echo "<p><strong>You must be logedin!</strong></p>";
		exit();
		
	} // Close session if statment
} //Close main IF statment

// Add new intem in the database...
// Make the query
//Get user_id
$user_id = $_SESSION['user_id'];

$q2 = "INSERT INTO upload_item (user_id, category_id, department_id, name, model, item_condition, description, price, date) 
		VALUES ('$user_id', '$category_id', '$department_id', '$item_name', '$model', '$condition', '$description', '$price', NOW() )";
$r2 = @mysqli_query ($dbc, $q2); // Run the query.

// get last inserted id from upload table
$upload_id = mysqli_insert_id($dbc);

$q3 = "INSERT INTO pictures (user_id, item_id, upload_id, big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
		small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num)
		VALUES ('$user_id', 'NULL' , '$upload_id', '$big_pic_1_name', '$big_pic_2_name', '$big_pic_3_name', '$big_pic_4_name',
				'$small_pic_1_name', '$small_pic_2_name',  '$small_pic_3_name',  '$small_pic_4_name', $main_img_num)";
				
$r3 = @mysqli_query ($dbc, $q3); // Run the query.	

if ($r3 && $r2) { // If it ran OK.
	// Get user's email
	$q4 = "SELECT email FROM user WHERE user_id = '$user_id' " ;
	$r4 = @mysqli_query ($dbc, $q4);
	while ($row = mysqli_fetch_array($r4, MYSQLI_ASSOC)) {
		$email = $row['email'];
	}
	 // Send the email:
	$body = "Thank you! Your item was uploaded for sale at <Electronics.com>.\n";
	#$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
	mail($email, 'Upload Confirmation', $body, 'From: admin@electronics.com');
			
	// Print a message:
	echo "<div class='center_content' id='tabs'>";
	  echo "<div class='top_prod_box_big'></div>";
	    echo "<div class='prod_box_big1'>";
		  echo "<div class='center_prod_box_big'>";
	echo '<h1>Thank you!</h1>
	<h3>A confirmation email has been sent to your address.</h3>
	<h3>You can now go to you account and view, edit or delete your item</h3>';
	      echo "</div>";
	    echo "</div>";
	   echo "<div class='bottom_prod_box_big'></div>";
	  echo "</div>";
	echo "</div>";

}else{ // If it did not run OK.
	echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
	exit();
	}
include ('includes/right_content.html');
include ('includes/footer.html');
?>