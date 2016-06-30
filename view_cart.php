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
			
<?php

// Check if the form has been submitted (to update the cart):
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	
if(!empty($_SESSION['cart2'])){
	// change the quantities:
	foreach ($_POST['qty'] as $k => $v) {
	
		// Must be integers!
		$item_id = (int) $k;
		$qty = (int) $v;
		
		if ($qty == 0) { // delete.
			unset ($_SESSION['cart2'][$item_id]);
		} elseif ( $qty > 0 ) { // change the quantity.
			$_SESSION['cart2'][$item_id]['quantity'] = $qty;
		}
		
	} // End of FOREACH.
}
if(!empty($_SESSION['cart'])){	
		// change the quantities:
	foreach ($_POST['qty'] as $k => $v) {
	
		// Must be integers!
		$upload_id = (int) $k;
		$qty = (int) $v;
		
		if ($qty == 0) { // delete.
			unset ($_SESSION['cart'][$upload_id]);
		} elseif ( $qty > 0 ) { // change the quantity.
			$_SESSION['cart'][$upload_id]['quantity'] = $qty;
		}
		
	} // End of FOREACH.
}
	
} // End of SUBMITTED IF

// Display the cart if it's not empty...
if (empty($_SESSION['cart']) && empty($_SESSION['cart2'])) {

	echo '<p>Your cart is currently empty.</p>';
	
} elseif (empty($_SESSION['cart']) && !empty($_SESSION['cart2'])) {

	// Create a form and a table:
	echo '<div class="form_row">
	<form action="view_cart.php" method="post">
	<table id="table" border="0" width="90%" cellspacing="3" align="left">
	<tr>
		<td align="left" width="30%"><b>Name</b></td>
		<td align="left" width="30%"><b>Model</b></td>
		<td align="right" width="10%"><b>Price</b></td>
		<td align="center" width="10%"><b>Qty</b></td>
		<td align="right" width="10%"><b>Total Price</b></td>
	</tr> ';
     echo '</div>';

	// Retrieve all of the information for the prints in the cart2:
	foreach ($_SESSION['cart2'] as $item_id => $value) {
		
		$q_items_cart = "SELECT * FROM items WHERE item_id = $item_id";
		$r = mysqli_query ($dbc, $q_items_cart);
	

		// Print each item
	
			if(!isset($total_2)) {
				$total_2 = 0;
			}else{
				$total_2 = $total_2;
			} // Total cost of the order.
			
			if (!isset($total_1)) {
				$total_1 = 0;
			}else{
				$total_1 = $total_1;
			}
	
		while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
			
			// Calculate the total and sub-totals.
			$subtotal_1 = $_SESSION['cart2'][$row['item_id']]['quantity'] * $_SESSION['cart2'][$row['item_id']]['price'];
			$total_1 += $subtotal_1;

		echo '<div class="form_row">';	
			// Print the row:
			echo "\t<tr>
			<td align=\"left\">{$row['name']}</td>
			<td align=\"left\">{$row['model']}</td>
			<td align=\"right\">\${$_SESSION['cart2'][$row['item_id']]['price']}</td>
			<td align=\"center\"><input type=\"text\" size=\"3\" name=\"qty[{$row['item_id']}]\" value=\"{$_SESSION['cart2'][$row['item_id']]['quantity']}\" /></td>
			<td align=\"right\">$" . number_format ($subtotal_1, 2) . "</td>
			</tr>\n";
	     
		} // end of while loop
	}
	
	// Print the total, close the table, and the form:
	echo '<tr>
		<td colspan="4" align="right"><b>Total:</b></td>
		<td align="right">$' . number_format ($total_2 + $total_1, 2) . '</td>
	</tr>
	</table>
	<div align="center"><input type="submit" name="submit" value="Update My Cart" /></div>
	</form><p align="center">Enter a quantity of 0 to remove an item.
	<br /><br /><a href="checkout.php?total=' . number_format ($total_2 + $total_1, 2) . '">Checkout</a></p>';
	echo '</div>';
	
} elseif (!empty($_SESSION['cart']) && empty($_SESSION['cart2'])) {

		// Create a form and a table:
		echo '<div class="form_row">
		<form action="view_cart.php" method="post">
		<table id="table" border="0" width="90%" cellspacing="3" align="left">
		<tr>
			<td align="left" width="30%"><b>Name</b></td>
			<td align="left" width="30%"><b>Model</b></td>
			<td align="right" width="10%"><b>Price</b></td>
			<td align="center" width="10%"><b>Qty</b></td>
			<td align="right" width="10%"><b>Total Price</b></td>
		</tr> ';
		 echo '</div>';

		// Retrieve all of the information for the prints in the cart2:
		foreach ($_SESSION['cart'] as $upload_id => $value) {

			
			$q_upload_cart = "SELECT * FROM upload_item WHERE upload_id = $upload_id";
			$r = mysqli_query ($dbc, $q_upload_cart);
				// Create a  table:

			// Print each item
			if(!isset($total_1)) {
				$total_1 = 0;
			}else{
				$total_1 = $total_1;
			}
			
			if (!isset($total_2)) {
				$total_2 = 0;
			}else{
				$total_2 = $total_2;
			}
			
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
				
				// Calculate the total and sub-totals.
				$subtotal_2 = $_SESSION['cart'][$row['upload_id']]['quantity'] * $_SESSION['cart'][$row['upload_id']]['price'];
				$total_2 += $subtotal_2;

			echo '<div class="form_row">';	
				// Print the row:
				echo "\t<tr>
				<td align=\"left\">{$row['name']}</td>
				<td align=\"left\">{$row['model']}</td>
				<td align=\"right\">\${$_SESSION['cart'][$row['upload_id']]['price']}</td>
				<td align=\"center\"><input type=\"text\" size=\"3\" name=\"qty[{$row['upload_id']}]\" value=\"{$_SESSION['cart'][$row['upload_id']]['quantity']}\" /></td>
				<td align=\"right\">$" . number_format ($subtotal_2, 2) . "</td>
				</tr>\n";
			 
			} // end of while loop
		}
		
		
		// Print the total, close the table, and the form:
		echo '<tr>
			<td colspan="4" align="right"><b>Total:</b></td>
			<td align="right">$' . number_format ($total_2 + $total_1, 2) . '</td>
		</tr>
		</table>
		<div align="center"><input type="submit" name="submit" value="Update My Cart" /></div>
		</form><p align="center">Enter a quantity of 0 to remove an item.
		<br /><br /><a href="checkout.php?total=' . number_format ($total_2 + $total_1, 2) . '">Checkout</a></p>';
		echo '</div>';

} elseif (!empty($_SESSION['cart']) && !empty($_SESSION['cart2'])) {

		// Create a form and a table:
		echo '<div class="form_row">
		<form action="view_cart.php" method="post">
		<table id="table" border="0" width="90%" cellspacing="3" align="left">
		<tr>
			<td align="left" width="30%"><b>Name</b></td>
			<td align="left" width="30%"><b>Model</b></td>
			<td align="right" width="10%"><b>Price</b></td>
			<td align="center" width="10%"><b>Qty</b></td>
			<td align="right" width="10%"><b>Total Price</b></td>
		</tr> ';
		 echo '</div>';

			// Retrieve all of the information for the prints in the cart2:
		foreach ($_SESSION['cart2'] as $item_id => $value) {
			
			$q_items_cart = "SELECT * FROM items WHERE item_id = $item_id";
			$r = mysqli_query ($dbc, $q_items_cart);
				// Create a  table:

			// Print each item
			if(!isset($total_1)) {
				$total_1 = 0;
			}else{
				$total_1 = $total_1;
			}
			
			if (!isset($total_2)) {
				$total_2 = 0;
			}else{
				$total_2 = $total_2;
			}
				
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
				
				// Calculate the total and sub-totals.
				$subtotal_1 = $_SESSION['cart2'][$row['item_id']]['quantity'] * $_SESSION['cart2'][$row['item_id']]['price'];
				$total_1 += $subtotal_1;

				echo '<div class="form_row">';	
				// Print the row:
				echo "\t<tr>
				<td align=\"left\">{$row['name']}</td>
				<td align=\"left\">{$row['model']}</td>
				<td align=\"right\">\${$_SESSION['cart2'][$row['item_id']]['price']}</td>
				<td align=\"center\"><input type=\"text\" size=\"3\" name=\"qty[{$row['item_id']}]\" value=\"{$_SESSION['cart2'][$row['item_id']]['quantity']}\" /></td>
				<td align=\"right\">$" . number_format ($subtotal_1, 2) . "</td>
				</tr>\n";
			 
			} // end of while loop
		}
			
		
		// Retrieve all of the information for the prints in the cart2:
		foreach ($_SESSION['cart'] as $upload_id => $value) {

			$q_upload_cart = "SELECT * FROM upload_item WHERE upload_id = $upload_id";
			$r = mysqli_query ($dbc, $q_upload_cart);
				// Create a  table:

			// Print each item
			if(!isset($total_2)) {
				$total_2 = 0;
			}else{
				$total_2 = $total_2;
			}
			
			if (!isset($total_1)) {
				$total_1 = 0;
			}else{
				$total_1 = $total_1;
			}
			
			while ($row = mysqli_fetch_array ($r, MYSQLI_ASSOC)) {
				
				// Calculate the total and sub-totals.
				$subtotal_2 = $_SESSION['cart'][$row['upload_id']]['quantity'] * $_SESSION['cart'][$row['upload_id']]['price'];
				$total_2 += $subtotal_2;

			echo '<div class="form_row">';	
				// Print the row:
				echo "\t<tr>
				<td align=\"left\">{$row['name']}</td>
				<td align=\"left\">{$row['model']}</td>
				<td align=\"right\">\${$_SESSION['cart'][$row['upload_id']]['price']}</td>
				<td align=\"center\"><input type=\"text\" size=\"3\" name=\"qty[{$row['upload_id']}]\" value=\"{$_SESSION['cart'][$row['upload_id']]['quantity']}\" /></td>
				<td align=\"right\">$" . number_format ($subtotal_2, 2) . "</td>
				</tr>\n";
			 
			} // end of while loop
		}
			

		// Print the total, close the table, and the form:
		echo '<tr>
			<td colspan="4" align="right"><b>Total:</b></td>
			<td align="right">$' . number_format ($total_2 + $total_1, 2) . '</td>
		</tr>
		</table>
		<div align="center"><input type="submit" name="submit" value="Update My Cart" /></div>
		</form><p align="center">Enter a quantity of 0 to remove an item.
		<br /><br /><a href="checkout.php?total=' . number_format ($total_2 + $total_1, 2) . '">Checkout</a></p>';
		echo '</div>';
}
?>
 </div>
   <div class="bottom_prod_box_big"></div>
	 </div>
      </div>
    	</div>
    	 </div>
<?php
include ('includes/footer.html');
?>