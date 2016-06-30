<?php
$page_title = 'Contact Page';
include ('includes/header.html');
include ('includes/left_content.html');
?>
<form action="shipping.php" method="post">
    <div class="center_content">
      <div class="center_title_bar">Track Shipping</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
<?php		  
		  	$get_items = "SELECT * FROM shop_cart WHERE date_shipped = '0000-00-00 00:00:00' "; // GET ALL THE ITEMS WHERE SHIPPED DATE 0000-00-00 00:00:00
			$r3 = @mysqli_query ($dbc,$get_items);
			if (mysqli_num_rows($r3) > 0) {	// user have items for sale
				while ($row = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
					$order_id = $row['order_id'];
					$item_id = $row['item_id'];
					$temp_order_id = $row['temp_order_id'];
					$user_id = $row['user_id'];
					$upload_id = $row['upload_id'];
					$date_ordered = $row['date_ordered'];
					$date_shipped = $row['date_shipped'];
					$confirmation_num = $row['confirmation_num'];
				
?>			
            <div class="form_row">
              <label class="contact_out4"><strong>Order ID:</strong></label>
			  <label class="contact_out4"><?php echo $order_id; ?></label>
            </div>
            <div class="form_row">
              <label class="contact_out4"><strong>Item ID:</strong></label>
			  <label class="contact_out4"><?php echo $item_id + $upload_id; ?></label>
            </div>
		    <div class="form_row">
              <label class="contact_out4"><strong>Temp order ID:</strong></label>
			  <label class="contact_out4"><?php echo $temp_order_id; ?></label>
            </div>
			<div class="form_row">
              <label class="contact_out4"><strong>User ID:</strong></label>
			  <label class="contact_out4"><?php echo $user_id; ?></label>
            </div>
		    <div class="form_row">
              <label class="contact_out4"><strong>Date ordered:</strong></label>
			  <label class="contact_out4"><?php echo $date_ordered; ?></label>
            </div>
			<div class="form_row">
              <label class="contact_out4"><strong>Date shipped:</strong></label>
			  <label class="contact_out4"><?php echo $date_shipped; ?></label>
            </div>
			<a href='shipping_admin.php?order_id= <?php echo $order_id; ?>'><strong>Ship item</strong></a></td>
			 <hr>
			 
<?php  
				}

			}else{
				echo 'No items needs to be shipped'; // DEFAULT MESSAGE IN NO ITEMS ARE TO BE SHIPPED
				exit();
			}

if (!empty ($_GET['order_id'])) { // UPDATE SHIPPING DATE WITH CURRENT DATE
 mysqli_query($dbc,"UPDATE shop_cart SET date_shipped = NOW() WHERE order_id='".($_GET['order_id'])."'");
 Header("Refresh: 1;url=shipping_admin.php"); // REFRESH PAGE AND REDISPLAY
}			
?> 
		</div>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>
    <!-- end of center content -->
<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>