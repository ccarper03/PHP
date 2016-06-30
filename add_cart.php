<?php
$page_title = 'Add to Cart';
include ('includes/header.html');
include ('includes/left_content.html');
?>
<div class="center_content">
  <div class="center_title_bar">Add to Cart</div>
	<div class="prod_box_big">
      <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
<?php
//CHECK IF USER LOGGED IN
if (isset($_SESSION['user_id'])) {
	$id = $_SESSION['user_id'];
	
}else{
	// Redirect:
	header( 'Location: login.php' ) ;
	}

//GET UPLOADED ITEM OR ADMIN'S ITEM ID		
if (!empty ($_GET['upload_id'])) {
	$upload_id = $_GET['upload_id'];
	$item_id = FALSE;
}else{
	$item_id = $_GET['item_id'];
	$upload_id = FALSE;
}
	// Check if the cart already contains one of these prints;
	// if so, increment the quantity:
if (isset($_SESSION['cart'][$upload_id])) {
		
		$_SESSION['cart'][$upload_id]['quantity']++; // add another
		
		// Display a message:
		echo '<p><h3>Another product has been added to your shopping cart.</h3></p>';		

	}elseif (isset($_SESSION['cart2'][$item_id])) {

		$_SESSION['cart2'][$item_id]['quantity']++; // add another
		
		// Display a message:
		echo '<p><h3>Another product has been added to your shopping cart.</h3></p>';
		
	} else { // New Product added
		
		// Get the print's price from the database.
	if ($item_id == TRUE) {
		$q = "SELECT price FROM items WHERE item_id=$item_id";
		$r = mysqli_query ($dbc, $q);
		if (mysqli_num_rows($r) == 1) { // Valid print id.
		
			// Fetch the information.
			list($price) = mysqli_fetch_array ($r, MYSQLI_NUM);
			
			// Add to the cart:
			$_SESSION['cart2'][$item_id] = array('quantity' => 1, 'price' => $price);
			
			// Display a message:
			echo '<p><h3>The item has been added to your shopping cart.</h3></p>';
			
			} else { // Not a valid print ID.
				echo '<div align="center">This page has been accessed in error!</div>';
			}
	}else{
		$q = "SELECT price FROM upload_item WHERE upload_id=$upload_id";
		$r = mysqli_query ($dbc, $q);
		if (mysqli_num_rows($r) == 1) { // Valid print id.
		
			// Fetch the information.
			list($price) = mysqli_fetch_array ($r, MYSQLI_NUM);
			
			// Add to the cart:
			$_SESSION['cart'][$upload_id] = array('quantity' => 1, 'price' => $price);
			
			// Display a message:
			echo '<p><h3>The item has been added to your shopping cart.</h3></p>';
	
		} else { // Not a valid print ID.
			echo '<div align="center">This page has been accessed in error!</div>';
		}
		
	
	}
}
?>
</div>
  </div>
    </div>
	   <div class="bottom_prod_box_big"></div>
	    </div>
         </div>

<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>