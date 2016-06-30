<?php
$page_title = 'My Account';
include ('includes/header.html');
include ('includes/left_content.html');
?>

<?php  // Get user info if Login in.
if ( (isset($_SESSION['user_id']))) {
	$id = $_SESSION['user_id'];
	if ($id == 38) {
	// IF Admin Redirect:
	header( 'Location: my_acc_admin.php' ) ;
		}
	//Run a query for a user info
	$q_acc_info = "SELECT first_name, middle_name, last_name, email, DATE_FORMAT(registration_date, '%M %d, %Y') as date_r FROM user WHERE user_id = '$id'";
	$r = @mysqli_query ($dbc, $q_acc_info);
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$first_n = $row['first_name'];
		$middle_n = $row['middle_name'];
		$last_n = $row['last_name'];
		$email = $row['email'];
		$date_r = $row['date_r'];
	}
	//Run a query for a user address
	$q_address_info = "SELECT street, city, state, zip FROM address WHERE user_id = '$id'";
	$r = @mysqli_query ($dbc, $q_address_info);
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$street = $row['street'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
	}

	
}else{
	// Redirect:
	header( 'Location: login.php' ) ;
	}
?>

<!-- Script for My accaunt information Tab-->
<script>
$(function() {
$( "#tabs" ).tabs();
});
</script>


<div class="center_content" id="tabs">
<ul>
<li><a href="#tabs-1">Account Information:</a></li>
<li><a href="#tabs-2">Add new item for sale</a></li>
<li><a href="#tabs-3">View you items</a></li>
</ul>
<div id="tabs-1">
       <div class="prod_box_big2">
        <div class="top_prod_box_big"></div>
          <div class="center_prod_box_big">
           <div class="contact_form">
             <div class="form_row">
              <label class="contact"><strong>First Name:</strong></label>
			  <label class="contact_out"><?php echo $first_n; ?></label>
                </div>
			     <div class="form_row">
				  <label class="contact"><strong>Last Name:</strong></label>
				  <label class="contact_out"><?php echo $last_n; ?></label>
                   </div>
				    <div class="form_row">
                    <label class="contact"><strong>Middle Name:</strong></label>
					<label class="contact_out"><?php echo $middle_n; ?></label>
                    </div>
                   <div class="form_row">
				  <label class="contact"><strong>Email:</strong></label>
				  <label class="contact_out"><?php echo $email; ?></label> 
				 </div>
				<div class="form_row">
			   <label class="contact"><strong>Date Registered:</strong></label>
			   <label class="contact_out"><?php echo $date_r; ?></label>
				</div>
				 <div class="form_row">
				  <label class="contact"><strong>Street:</strong></label>
				  <label class="contact_out"><?php echo $street; ?></label>
                   </div>
				    <div class="form_row">
                    <label class="contact"><strong>City:</strong></label>
					<label class="contact_out"><?php echo $city; ?></label>
                    </div>
                   <div class="form_row">
				  <label class="contact"><strong>State:</strong></label>
				  <label class="contact_out"><?php echo $state; ?></label> 
				 </div>
				<div class="form_row">
               <label class="contact"><strong>Zip-Code:</strong></label>
			   <label class="contact_out"><?php echo $zip; ?></label>
            </div>
			<hr>
		<?php
			echo ("
   			<div class='form_row'>
		       <a href='edit_acc_info.php?user_id=$id'><p>Update your information</p></a>   
                </div>
			    <div class='form_row'>
		       <a href='change_password.php?user_id=$id'><p>Change password</p></a>   
            </div>
			");?>
			
          </div>
        </div>
		<div class="bottom_prod_box_big"></div>
	 </div>
</div>



<div id="tabs-2">
<form enctype="multipart/form-data" action="new_item.php" method="post">
<script> $.validate(); </script> <!-- Add java script to validate form with sticky form -->

     <div class="prod_box_big2">
        <div class="top_prod_box_big"></div>
          <div class="center_prod_box_big">
           <div class="contact_form">
             <div class="form_row">
              <label class="contact"><strong>*Category:</strong></label>
			   <p><select name="category" data-validation="required">
					<option value="">Select...</option>
					<option value="2">Automotive</option>
					<option value="3">Cameras & Photo</option>
					<option value="4">Cell Phones & Accessories</option>
					<option value="5">Computers/Tablets & Networking</option>
					<option value="6">Consumer Electronics</option>
					<option value="7">Computer Hardware</option>
					<option value="9">Video Games & Consoles</option>
					<option value="9">Office Products & Point of Sale</option>
					
				</select></p>
                  </div>
			        <div class="form_row">
				      <label class="contact"><strong>*Name:</strong></label>
				        <input type="text" name="name" data-validation="required" size="30" maxlength="60" value="<?php if (isset($_POST['name'])) echo $_POST['name']; ?>" /></p>
                          </div>
				           <div class="form_row">
				          <label class="contact"><strong>Model:</strong></label>
				        <input type="text" name="model" size="30" maxlength="60" value="<?php if (isset($_POST['model'])) echo $_POST['model']; ?>" /></p>
                      </div>
				    <div class="form_row">
				       <label class="contact"><strong>*Choose Condition:</strong></label>
						<select name="condition" data-validation="required">
						  <option value="" >Select...</option>
						  <option value="New">New</option>
						  <option value="Used">Used</option>
						  <option value="Parts">Parts</option>
						  <option value="New Parts">New Parts</option>
						  <option value="Used Parts">Used Parts</option>
						</select>
				     </div>
                   <div class="form_row">
				  <label class="contact"><strong>*Select a price:</strong></label>
				  <input type="text" name="price" size="10" maxlength="10" data-validation="required" value="<?php if (isset($_POST['price'])) echo $_POST['price']; ?>" /></p>
				 </div>
				<div class="form_row">
			   <label class="contact"><strong>Product description:</strong></label>
			   (<span id="pres-max-length">100</span> characters left)
			   <textarea name="description" id="description" data-validation="required" rows="5" cols="30"><?php if (isset($_POST['description'])) echo $_POST['description']; ?></textarea></p>
				</div>
				 <div class="form_row">	
				  <label class="contact"><strong>*Photo 1:</strong></label>
				  <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
				  <input type="file" data-validation="size mime required"
                   data-validation-size-error-msg="The file cant be larger than 400kb"
                   data-validation-error-msg="You must upload an image file (max 400 kb)"
                   data-validation-allowing="pjpeg, jpeg, JPG, X-PNG, PNG, png, x-png"
                   data-validation-max-size="400kb" name="upload1" />
				  </div>
				  <div class="form_row">
				  <label class="contact"><strong>Photo 2:</strong></label>
				  <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
				  <input type="file" data-validation="size mime required"
                   data-validation-size-error-msg="The file cant be larger than 400kb"
                   data-validation-error-msg="You must upload an image file (max 400 kb)"
                   data-validation-allowing="pjpeg, jpeg, JPG, X-PNG, PNG, png, x-png"
                   data-validation-max-size="400kb" name="upload2" />
                  </div>
				  <div class="form_row">
				  <label class="contact"><strong>Photo 3:</strong></label>
				  <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
				  <input type="file" data-validation="size mime required"
                   data-validation-size-error-msg="The file cant be larger than 400kb"
                   data-validation-error-msg="You must upload an image file (max 400 kb)"
                   data-validation-allowing="pjpeg, jpeg, JPG, X-PNG, PNG, png, x-png"
                   data-validation-max-size="400kb" name="upload3" />
				  </div>
				  <div class="form_row">
				  <label class="contact"><strong>Photo 4:</strong></label>
				  <input type="hidden" name="MAX_FILE_SIZE" value="524288" />
				  <input type="file" data-validation="size mime required"
                   data-validation-size-error-msg="The file cant be larger than 400kb"
                   data-validation-error-msg="You must upload an image file (max 400 kb)"
                   data-validation-allowing="pjpeg, jpeg, JPG, X-PNG, PNG, png, x-png"
                   data-validation-max-size="400kb" name="upload4" />
                  </div> 
			       <p>
					<input data-validation="required"
					data-validation-error-msg="You have to agree to our terms" type="checkbox">
					I agree to the terms of service
					</p>    
			  <p></p>
	     	  <p></p>
		      <p></p>
		    <hr>
           <p><input type="submit" name="submit" value="Add" /></p>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>
<script>
// Restrict presentation length
$('#description').restrictLength( $('#pres-max-length') );
</script>
</div>
<div id="tabs-3" >
<?php

//Number of records 
$display = 10;

//Determine how many pages are there
if(isset($GET_['p']) && is_numeric($GET['p'])) { //Already been determinated
	$pages  = $GET_['p'];
}else{ //Need to determine

// Count the number of redcords
$q_count = "SELECT COUNT(upload_id) FROM upload_item";
$r_count = @mysqli_query ($dbc, $q_count);
$row = @mysqli_fetch_array ($r_count, MYSQLI_NUM);
$records = $row[0];

	//calculate the number of pages
	if ($records > $display) { //More than 1 page.
		$pages = ceil ($records/$display);
	}else{
		$pages = 1;
	}
	
} // End of p IF
	
	//Determine where in the database to start running results...
	if(isset($_GET['s']) && is_numeric($_GET['s'])) {
	  $start = $_GET['s'];
	}else{
	  $start = 0;
	}


	//Run a query for a user's item
	$q_item_info = "SELECT upload_id, category_id, department_id, name, model, item_condition, description, price, date
					FROM upload_item WHERE user_id = '$id' ORDER BY date DESC LIMIT $start, $display";
					
	$r2 = @mysqli_query ($dbc, $q_item_info);
	if (mysqli_num_rows($r2) > 0) {	// user have items for sale
		
		while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			$upload_id = $row['upload_id'];
			$category_id = $row['category_id'];
			$department_id = $row['department_id'];
			$name = $row['name'];
			$model = $row['model'];
			$condition = $row['item_condition'];
			$description = $row['description'];
			$price = $row['price'];
			$date = $row['date'];
		
			$q_pic_info = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
									small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
							FROM pictures WHERE upload_id = '$upload_id'";
			$r3 = @mysqli_query ($dbc, $q_pic_info);
			while ($row = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
				$big_pic_1_name = $row['big_pic_1_name'];
				$big_pic_2_name = $row['big_pic_2_name'];
				$big_pic_3_name = $row['big_pic_3_name'];
				$big_pic_4_name = $row['big_pic_4_name'];
				
				$small_pic_1_name = $row['small_pic_1_name'];
				$small_pic_2_name = $row['small_pic_2_name'];
				$small_pic_3_name = $row['small_pic_3_name'];
				$small_pic_4_name = $row['small_pic_4_name'];
				
				$main_img = $row['main_img_num'];
				if ($main_img == 1) {   // Define which picture are main picture, selected by user, if not use picture #1
					$main_picture = $small_pic_1_name;
					$main_picture_big = $big_pic_1_name;
					$thumb_1 = $big_pic_2_name;
					$thumb_2 = $big_pic_3_name;
					$thumb_3 = $big_pic_4_name;
				}elseif ($main_img == 2) {
					$main_picture = $small_pic_2_name;
					$main_picture_big = $big_pic_2_name;
					$thumb_1 = $big_pic_1_name;
					$thumb_2 = $big_pic_3_name;
					$thumb_3 = $big_pic_4_name;
				}elseif ($main_img == 3) {
					$main_picture = $small_pic_3_name;
					$main_picture_big = $big_pic_3_name;
					$thumb_1 = $big_pic_1_name;
					$thumb_2 = $big_pic_2_name;
					$thumb_3 = $big_pic_4_name;
				}elseif ($main_img == 4) {
					$main_picture = $small_pic_4_name;
					$main_picture_big = $big_pic_4_name;
					$thumb_1 = $big_pic_1_name;
					$thumb_2 = $big_pic_2_name;
					$thumb_3 = $big_pic_3_name;
				}else{
					$main_picture = $small_pic_1_name;
					$main_picture_big = $big_pic_1_name;
					$thumb_1 = $big_pic_4_name;
					$thumb_2 = $big_pic_2_name;
					$thumb_3 = $big_pic_3_name;
				}
					
					

	
?>
<div class="center_content">
      <div class="prod_box_big2">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="product_img_big"> <a href="javascript:popImage('<?php echo $main_picture_big; ?>','Some Title')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $main_picture; ?>" alt="" border="0" /></a>
            <div class="thumbs"> <a href="javascript:popImage('<?php echo $thumb_1; ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_1; ?>' alt="" border="0" /></a> 
			<a href="javascript:popImage('<?php echo $thumb_2 ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_2; ?>' alt="" border="0" /></a> 			
			<a href="javascript:popImage('<?php echo $thumb_3; ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_3; ?>' alt="" border="0" /></a> 			</div>
           </div>
            <div class="details_big_box">
            <div class="product_title_big"><?php echo $name; ?></div>
            <div class="specifications"> Availability: <span class="blue">In stoc</span><br />
              Model#: <span class="blue"><?php echo $model; ?></span><br />
             Condition: <span class="blue"><?php echo $condition; ?></span><br />
			 Description: <span class="blue"><?php echo $description; ?></span><br />

            </div>
            <div class="prod_price_big"><span class="price">$<?php echo number_format($price); ?></span></div>
			
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
      </div>
    </div>
	<?php
			} // Close loop to display all item that user has uploaded
			}
	}else{
		echo '<p>You have no items for sale</p>';
		#exit(); 
	} //close if statment if threre is no items
mysqli_free_result ($r2);


// Make the links to other pages 

if($pages > 1) {

echo '<br /><p>';

$current_page = ($start/$display) + 1;

if ($current_page != 1) {
	echo '<a href="my_acc.php?s=' . ($start - $display) . '&p=' . $pages .'">Previous</a> ';
	}
	
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="my_acc.php?s=' .(($display * ($i - 1))) . '&p=' . $pages .'">' . $i . '</a>';
			}else{
				echo $i . ' ';
				}
				}
				
				if ($current_page !=$pages) {
					echo'<a href="my_acc.php?s=' .($start + $display) . '&p=' . $pages .'"> Next</a> ';
				}
}


?>

  </div>
</div>
 <!-- end of center content -->
  <!-- end of main content -->
<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>