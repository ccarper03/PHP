<?php
include ('includes/header.html');
include ('includes/left_content.html');
?>
<div class="center_content">
     <div class="center_title_bar">Latest Products</div>
<?php
	//Run a query for a user's items
	$q_upload_info = "SELECT * FROM upload_item ORDER BY date DESC LIMIT 6";
	$r1 = @mysqli_query ($dbc, $q_upload_info);
		
		while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
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
			$r4 = @mysqli_query ($dbc, $q_pic_info);
			while ($row = mysqli_fetch_array($r4, MYSQLI_ASSOC)) {
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
    <!-- end of left content -->


      <div class="prod_box">
        <div class="top_prod_box"></div>
        <div class="center_prod_box">
          <div class="product_title"><a href="details.php?upload_id=<?php echo $upload_id; ?>"><?php echo "$name  $model";?></a></div>
          <div class="product_img"><a href="details.php?upload_id=<?php echo $upload_id; ?>"><img src="<?php echo $main_picture;?>" alt="" border="0" /></a></div>
          <div class="prod_price"><span class="reduce">$<?php $reduced = $price + ($price * 0.2); echo number_format(ceil($reduced));?></span>
											<span class="price">$<?php echo number_format($price);?></span></div>
        </div>
        <div class="bottom_prod_box"></div>
	   		
        <div class="prod_details_tab"><a href="add_cart.php?upload_id=<?php echo $upload_id;?>" title="header=[Add to cart] body=[&nbsp;] fade=[on]"><img src="images/cart.gif" alt="" border="0" class="left_bt" /></a> <a href="details.php?upload_id=<?php echo $upload_id; ?>" class="prod_details">details</a> </div>
      </div>
<?php
	  	} // Close loop to display all item that user has uploaded
			}//Close pictures loop
?>
	 <div class="center_title_bar">Recommended Products</div>
<?php
			//Run a query for a admin items
			$q_items_info = "SELECT * FROM items ORDER BY date DESC LIMIT 3";
			$r3 = @mysqli_query ($dbc, $q_items_info);
				while ($row = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
					$item_id = $row['item_id'];
					$category_id = $row['category_id'];
					$department_id = $row['department_id'];
					$item_name = $row['name'];
					$item_model = $row['model'];
					$item_description = $row['description'];
					$item_price = $row['price'];
					$item_date = $row['date'];
					
					
					$q_pic_info = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
									small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
							FROM pictures WHERE item_id = '$item_id'";
					$r4 = @mysqli_query ($dbc, $q_pic_info);
					while ($row = mysqli_fetch_array($r4, MYSQLI_ASSOC)) {
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
							$main_picture2 = $small_pic_1_name;
							$main_picture_big = $big_pic_1_name;
							$thumb_1 = $big_pic_2_name;
							$thumb_2 = $big_pic_3_name;
							$thumb_3 = $big_pic_4_name;
						}elseif ($main_img == 2) {
							$main_picture2 = $small_pic_2_name;
							$main_picture_big = $big_pic_2_name;
							$thumb_1 = $big_pic_1_name;
							$thumb_2 = $big_pic_3_name;
							$thumb_3 = $big_pic_4_name;
						}elseif ($main_img == 3) {
							$main_picture2 = $small_pic_3_name;
							$main_picture_big = $big_pic_3_name;
							$thumb_1 = $big_pic_1_name;
							$thumb_2 = $big_pic_2_name;
							$thumb_3 = $big_pic_4_name;
						}elseif ($main_img == 4) {
							$main_picture2 = $small_pic_4_name;
							$main_picture_big = $big_pic_4_name;
							$thumb_1 = $big_pic_1_name;
							$thumb_2 = $big_pic_2_name;
							$thumb_3 = $big_pic_3_name;
						}else{
							$main_picture2 = $small_pic_1_name;
							$main_picture_big = $big_pic_1_name;
							$thumb_1 = $big_pic_4_name;
							$thumb_2 = $big_pic_2_name;
							$thumb_3 = $big_pic_3_name;
						}
	
?>

      <div class="prod_box">
        <div class="top_prod_box"></div>
        <div class="center_prod_box">
          <div class="product_title"><a href="details.php?item_id=<?php echo $item_id; ?>"><?php echo "$item_name  $item_model"; ?></a></div>
          <div class="product_img"><a href="details.php?item_id=<?php echo $item_id; ?>"><img src="<?php echo $main_picture2;?>" alt="" border="0" /></a></div>
          <div class="prod_price"><span class="reduce">$<?php $reduced = $item_price + ($item_price * 0.2); echo number_format(ceil($reduced)); ?>
								</span> <span class="price">$<?php echo number_format($item_price); ?></span></div>
        </div>
        <div class="bottom_prod_box"></div>
        <div class="prod_details_tab"><a href="add_cart.php?item_id=<?php echo $item_id;?>" title="header=[Add to cart] body=[&nbsp;] fade=[on]"><img src="images/cart.gif" alt="" border="0" class="left_bt" /></a> <a href="details.php?item_id=<?php echo $item_id; ?>" class="prod_details">details</a> </div>
      </div>
<?php
					} //Close pictures loop
				} // Close loop to display all item that admin has uploaded

?>

</div>       <!-- end of center content -->

<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>
