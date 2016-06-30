<?php # Script 19.11 - checkout.php

$page_title = 'Order Confirmation';
include ('includes/header.html');
?>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') { //CHECK IF FORM WAS POSTED
	
	if ((isset($_SESSION['user_id']))) { //GET USER ID
		$user_id2 = $_SESSION['user_id'];		
	}else{
		echo '<p>system error occurred</p>';
		exit();
	}
	require (MYSQL);// Conect to a database
	
	//GET users email to sent confirmation number
	$q_email = "SELECT email FROM user WHERE user_id = $user_id2";
	$r_e = mysqli_query($dbc, $q_email);
	$row = mysqli_fetch_array($r_e, MYSQLI_ASSOC);
	$email = $row['email'];
	
	
	//Count how many items need confirmation number
	$q_count = "SELECT count(order_id) FROM shop_cart WHERE temp_order_id = 1 + $user_id2";
	$r1 = mysqli_query($dbc, $q_count);
	$row = mysqli_fetch_array($r1, MYSQLI_ASSOC);
	$count_rows = $row['count(order_id)'];
	
	if($count_rows > 1) {
	
		for ($x = 0; $x <= $count_rows; $x++) { // Send the email for each item with unique confirmation number
			
			$confirmation_num = md5(uniqid(rand(), true));
			$q_confir1 = "UPDATE shop_cart SET confirmation_num = '$confirmation_num' WHERE confirmation_num = 1 AND temp_order_id = 1 + $user_id2 LIMIT 1";
			$r1 = mysqli_query($dbc, $q_confir1);

			
			$item_info_e = "SELECT item_id, upload_id FROM shop_cart WHERE confirmation_num = '$confirmation_num' LIMIT 1"; // GET SELLING ITEM INFO
			$r6 = mysqli_query($dbc, $item_info_e);
			while ($row = mysqli_fetch_array($r6, MYSQLI_ASSOC)) {
				$item_id = $row['item_id'];
				$upload_id = $row['upload_id'];
			}
			if ($item_id > 0) { //DEFINE IF IT ADMIN'S ITEM OR USER.
				$item_info_ = "SELECT name, model, price FROM items WHERE item_id = '$item_id'";
				$r7 = mysqli_query($dbc, $item_info_);
				while ($row = mysqli_fetch_array($r7, MYSQLI_ASSOC)) {
					$name = $row['name'];
					$model = $row['model'];
					$price = $row['price'];
				}	
			
			}else{
				$upload_info_ = "SELECT name, model, price FROM upload_item WHERE upload_id = '$upload_id'";
				$r7 = mysqli_query($dbc, $upload_info_);
				while ($row = mysqli_fetch_array($r7, MYSQLI_ASSOC)) {
					$name = $row['name'];
					$model = $row['model'];
					$price = $row['price'];
				}				
			}
			
			 // Send the email for each item with unique confirmation number
			$body = "Thank you! You can track your items using confirmation number below\n  $confirmation_num \n\n\n Item name: $name $model \n Price: $price";
			mail($email, 'Checkout Confirmation', $body, 'From: admin@electronics.com');
		}
		
	}else{
		$confirmation_num = md5(uniqid(rand(), true));
		$q_confir = "UPDATE shop_cart SET confirmation_num = '$confirmation_num' WHERE temp_order_id = 1 + $user_id2  LIMIT 1";
		$r = mysqli_query($dbc, $q_confir);
		
			$item_info_e = "SELECT item_id, upload_id FROM shop_cart WHERE confirmation_num = '$confirmation_num' LIMIT 1"; // GET SELLING ITEM INFO
			$r6 = mysqli_query($dbc, $item_info_e);
			while ($row = mysqli_fetch_array($r6, MYSQLI_ASSOC)) {
				$item_id = $row['item_id'];
				$upload_id = $row['upload_id'];
			}
			if ($item_id > 0) { //DEFINE IF IT ADMIN'S ITEM OR USER.
				$item_info_ = "SELECT name, model, price FROM items WHERE item_id = '$item_id'";
				$r7 = mysqli_query($dbc, $item_info_);
				while ($row = mysqli_fetch_array($r7, MYSQLI_ASSOC)) {
					$name = $row['name'];
					$model = $row['model'];
					$price = $row['price'];
				}	
			
			}else{
				$upload_info_ = "SELECT name, model, price FROM upload_item WHERE upload_id = '$upload_id'";
				$r7 = mysqli_query($dbc, $upload_info_);
				while ($row = mysqli_fetch_array($r7, MYSQLI_ASSOC)) {
					$name = $row['name'];
					$model = $row['model'];
					$price = $row['price'];
				}				
			}		
		
		
		// Send the email for each item with unique confirmation number
		$body = "Thank you! You can track your items using confirmation number below\n  $confirmation_num \n\n\n Item name: $name $model \n Price: $price";
		mail($email, 'Checkout Confirmation', $body, 'From: admin@electronics.com');		
	}
	
	$q_update = "UPDATE shop_cart SET temp_order_id = 'NULL' WHERE temp_order_id = 1 + $user_id2 ";	
	$r = mysqli_query($dbc, $q_update);
	
	if(isset($_SESSION['cart2'])) {  // CLEAR SESSION FOR 2 CARTS
		unset ($_SESSION['cart2']);
	}
	if(isset($_SESSION['cart'])) {
		unset ($_SESSION['cart']);
	}
	
	
// Print a message:
echo '<h1>Thank you!</h1>
<h3>Thank you for your business! You can track your items using confirmation number that has been send to your email address.</h3>';	

exit();
}// CLOSE IF POST STATEMENT



if ( (isset($_GET['total']))) { // GET TOTAL AMOUNT 
	$total = $_GET['total'];
}else{
echo '<p>system error occurred</p>';
exit();
}
if ( (isset($_SESSION['user_id']))) { //GET USER ID
	$user_id = $_SESSION['user_id'];
}else{
echo '<p>system error occurred</p>';
exit();
}
require (MYSQL);// Conect to a database

?>
<form action="checkout.php" method="post">
    <div class="center_content">
      <div class="center_title_bar">Order Confirmation</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
              <label class="contact"><strong><h2>Items:</h2></strong></label>
			  
<?php

if(!empty($_SESSION['cart2'])) {

	// Retrieve all of the information for the admin items in the cart:
	foreach ($_SESSION['cart2'] as $item_id => $value) {
		$q_item_cart = "SELECT * FROM items WHERE item_id = $item_id";
		$r1 = mysqli_query ($dbc, $q_item_cart);
		
		$upload_id = 0;
		$temp_order_id = 1 + $user_id; //GET UNIQUE temporary NUMBER THAT CONSIST WITH USER ID
		$quantity = $_SESSION['cart2'][$item_id]['quantity'];
		$q_order = "INSERT INTO shop_cart (temp_order_id, user_id, item_id, upload_id, quantity, date_ordered) VALUES ($temp_order_id, $user_id, $item_id, $upload_id, $quantity, NOW() )";	
		$r = mysqli_query($dbc, $q_order);

		while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
			$item_name = $row['name'];
			$item_model = $row['model'];
					
			$full_name1 = "$item_name $item_model";
				
?>
<div class="form_row">
<label class="contact_out2"><strong><?php echo $full_name1; ?></strong></label>	
</div>
<?php
		}
	}
}
?>

<?php
if(!empty($_SESSION['cart'])) {

	// Retrieve all of the information for the user items in the cart:
	foreach ($_SESSION['cart'] as $upload_id => $value) {
		$q_upload_cart = "SELECT * FROM upload_item WHERE upload_id = $upload_id";
		$r = mysqli_query ($dbc, $q_upload_cart);
		
		
		$item_id = 0;
		$temp_order_id = 1 + $user_id;
		$quantity = $_SESSION['cart'][$upload_id]['quantity'];
		$q_order = "INSERT INTO shop_cart (temp_order_id, user_id, item_id, upload_id, quantity, date_ordered) VALUES ($temp_order_id, $user_id, $item_id, $upload_id, $quantity, NOW() )";	
		$r_2 = mysqli_query($dbc, $q_order);
		
		while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
			$upload_name = $row['name'];
			$upload_model = $row['model'];
			
			$full_name = "$upload_name $upload_model";
?>
<div class="form_row">		
<label class="contact_out2"><strong><?php echo $full_name; ?></strong></label>	
</div>
<?php			
		}
	}	
}	
?>

<div class="form_row">
   <label class="contact_out3"><strong>Total:</strong></label>
     </div> 
	 
<div class="form_row">
   <hr>
     <label class="contact_out3"><strong><?php echo $total; ?></strong></label>
       </div>
          
		  
<label class="contact"><strong><h2>Shipping Address:</h2></strong></label>		  
<?php
	$q_user_info = "SELECT * FROM address WHERE user_id = $user_id"; // GET USER SHIPPING ADDRESS
	$r3 = mysqli_query ($dbc, $q_user_info);

	while ($row = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
		$street = $row['street'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];	
    
?>
<div class="form_row">		
<label class="contact_out2"><strong><?php echo $street; ?></strong></label>	
</div>
<div class="form_row">		
<label class="contact_out2"><strong><?php echo $city; ?></strong></label>	
</div>
<div class="form_row">		
<label class="contact_out2"><strong><?php echo $state; ?></strong></label>	
</div>
<div class="form_row">		
<label class="contact_out2"><strong><?php echo $zip; ?></strong></label>	
</div>
<?php
}	
?>		  
			<p><input type="submit" name="submit" value="Confirm" /></p>
           </div>
		   <div class="bottom_prod_box_big"></div>
        </div>
      </div>
    </div>
<?php

?>	
</form>
    <!-- end of center content -->
<?php
include ('includes/footer.html');
?>