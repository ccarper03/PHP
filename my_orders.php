<?php
$page_title = 'My Orders';
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



<div class="center_content" id="tabs">
<div id="tabs-3" >
<?php

//Number of records 
$display = 10;

//Determine how many pages are there
if(isset($GET_['p']) && is_numeric($GET['p'])) { //Already been determinated
	$pages  = $GET_['p'];
}else{ //Need to determine

// Count the number of redcords
$q_count = "SELECT COUNT(upload_id + item_id)  FROM shop_cart";
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
	
	//Run a query for a admin's item
	$q_cart = "SELECT * FROM shop_cart WHERE user_id = '$id' ";	
	$r1 = @mysqli_query ($dbc, $q_cart);
		
	if (mysqli_num_rows($r1) == 0) {	// item found
		echo "<p>Your have not order any items.</p>";
		exit();
	}
		
			
		while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
			$order_id = $row['order_id'];
			$user_id = $row['user_id'];
			$item_id = $row['item_id'];
			$upload_id = $row['upload_id'];
			$date_ordered = $row['date_ordered'];
			$date_shipped = $row['date_shipped'];
		
			
			
			if ($date_shipped == '0000-00-00 00:00:00') {
				$messag = 'Has not been shipped yet';
			}else{
				$messag = 'Has been shipped!';
			}	
				

				//Run a query for a user's item
				$q_item_info = "SELECT upload_id, category_id, department_id, name, model, item_condition, description, price, date
								FROM upload_item WHERE upload_id = '$upload_id' ORDER BY date DESC LIMIT $start, $display";
								
								
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
										FROM pictures WHERE upload_id = '$upload_id' ";
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
						} 
						} // Close loop to display all item that user has uploaded
					} // Close loop to display all picture that user has uploaded

				
		
		

}

mysqli_free_result ($r2);

// Make the links to other pages 

if($pages > 1) {

echo '<br /><p>';

$current_page = ($start/$display) + 1;

if ($current_page != 1) {
	echo '<a href="my_orders.php?s=' . ($start - $display) . '&p=' . $pages .'">Previous</a> ';
	}
	
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="my_orders.php?s=' .(($display * ($i - 1))) . '&p=' . $pages .'">' . $i . '</a>';
			}else{
				echo $i . ' ';
				}
				}
				
				if ($current_page !=$pages) {
					echo'<a href="my_orders.php?s=' .($start + $display) . '&p=' . $pages .'"> Next</a> ';
				}
}


?>
    </div>
	 </div>
<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>