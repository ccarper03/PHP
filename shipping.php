<?php
$page_title = 'Contact Page';
include ('includes/header.html');
include ('includes/left_content.html');
?>
<?php
if ( (isset($_SESSION['user_id']))) {
	$id_admin = $_SESSION['user_id'];
	if ($id_admin == 38) {
		// IF Admin Redirect:
		header( 'Location: shipping_admin.php' ) ;
	}else{
		$id_admin = NULL;
	}
	

}
?>
<?php 

// Check for a valid user ID, through GET or POST
	// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	

	// Check for number in database:
	if (!isset($_POST['track'])) {
		$errors[] = 'Please enter your Confirmation Number.';
	} else {
		$track = mysqli_real_escape_string($dbc, trim($_POST['track']));
	}

	if (empty($errors)) { // If everything's OK.
	
		//Run a query for a admin's item
		$q_item_info = "SELECT * FROM shop_cart WHERE confirmation_num = '$track' LIMIT 1";	
		$r2 = @mysqli_query ($dbc, $q_item_info);
		
		if (mysqli_num_rows($r2) > 0) {	// item found
			
			while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
				$order_id = $row['order_id'];
				$user_id = $row['user_id'];
				$item_id = $row['item_id'];
				$upload_id = $row['upload_id'];
				$date_ordered = $row['date_ordered'];
				$date_shipped = $row['date_shipped'];
			}
			
			
			if ($date_shipped == '0000-00-00 00:00:00') {
				$messag = 'Has not been shipped yet';
			}else{
				$messag = 'Has been shipped!';
			}	
			
		}else{
			echo "<p>Your confirmation number was not found</p>";
			exit();
		}
		//Define IF its uploaded item by user or Admin
		if ($item_id == 0) {
			$product_id = $upload_id;
			
			//Run a query for a admin's item
			$q_upload = "SELECT * FROM upload_item WHERE upload_id = '$product_id' LIMIT 1";	
			$r4 = @mysqli_query ($dbc, $q_upload);
		}else{
			$product_id = $item_id;
			
			//Run a query for a admin's item
			$q_item = "SELECT * FROM items WHERE item_id = '$product_id' LIMIT 1";	
			$r4 = @mysqli_query ($dbc, $q_item);			
		}
		
		while ($row = mysqli_fetch_array($r4, MYSQLI_ASSOC)) {
			$item_name = $row['name'];
		}
?>
		<form action="" method="">
		<script> $.validate(); </script> <!-- Add java script to validate form with sticky form -->
			<div class="center_content">
			  <div class="center_title_bar">Item Found</div>
			  <div class="prod_box_big">
				<div class="top_prod_box_big"></div>
				<div class="center_prod_box_big">
				  <div class="contact_form">
					<div class="form_row">
					  <label class="contact_out4"><strong><h2>Item Info:</h2></strong></label>
					  <label class="contact_out4"><strong>Item name:</strong></label>
					  <label class="contact_out4"><strong><u><?php  echo $item_name;?></u></strong></label>
					  <label class="contact_out4"><strong><?php echo $messag; ?></strong></label>
				  </div>
				  
				</div>
				<div class="bottom_prod_box_big"></div>
			  </div>
			</div>
			</div>
		</form>

		
<?php
	
	}else{
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		exit();		
	}	
	
} // end of main isset() IF.

// Create the HTML form:
?>

<form action="shipping.php" method="post">
<script> $.validate(); </script> <!-- Add java script to validate form with sticky form -->
    <div class="center_content">
      <div class="center_title_bar">Track Shipping</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>Confirmation Number:</strong></label>
              <input type="text" name="track" data-validation="required" size="37" maxlength="100" value="<?php if (isset($_POST['track'])) echo $_POST['track']; ?>" /></p>
            </div>
            <p><input type="submit" name="submit" value="Track" /></p>
          </div>
		  
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>
    <!-- end of center content -->
<?php
include ('includes/footer.html');
?>