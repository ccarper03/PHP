<?php
include ('includes/header.html');
include ('includes/left_content.html');
?>
    
    <!-- end of left content -->
	
	<?php  
//GET UPLOADED ITEM OR ADMIN'S ITEM ID		
if (!empty ($_GET['upload_id'])) {
	$upload_id = $_GET['upload_id'];
	$item_id = 0;
}else{
	$item_id = $_GET['item_id'];
	$upload_id = 0;
}

	#require (MYSQL);// Conect to a database
	
	if ($upload_id > 0) {
			//Run a query for a user's item
		$q_item_info = "SELECT * FROM upload_item WHERE upload_id = '$upload_id' LIMIT 1";

	}else{
			//Run a query for a admin's item
		$q_item_info = "SELECT * FROM items WHERE item_id = '$item_id' LIMIT 1";		
	}
	
	// RUN QUERIES TO GET ITEMS INFO
	$r2 = @mysqli_query ($dbc, $q_item_info);
	if (mysqli_num_rows($r2) > 0) {	// user have items for sale
		
		while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			$category_id = $row['category_id'];
			$department_id = $row['department_id'];
			$name = $row['name'];
			$model = $row['model'];
			$condition = $row['item_condition'];
			$description = $row['description'];
			$price = $row['price'];
			$date = $row['date'];
			
			if ($upload_id > 0) {
				$q_pic_info = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
									small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
							FROM pictures WHERE upload_id = '$upload_id'";
			}else{
				$q_pic_info = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
									small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
							FROM pictures WHERE item_id = '$item_id'";		
			}
	
			
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
	}

	?>
		
    <div class="center_content">
	<?php if ($item_id > 0) { ?>
	   <div class="center_title_bar"><?php echo $name;?><div class="prod_details"> <a href="add_cart.php?item_id=<?php echo $item_id;?>" title="header=[Add to cart] body=[&nbsp;] fade=[on]"><img src="images/cart.gif" alt="" border="0" class="left_bt" /></a></div></div>
    <?php }else{ ?>
	   <div class="center_title_bar"><?php echo $name;?><div class="prod_details"> <a href="add_cart.php?upload_id=<?php echo $upload_id;?>" title="header=[Add to cart] body=[&nbsp;] fade=[on]"><img src="images/cart.gif" alt="" border="0" class="left_bt" /></a></div></div>
    <?php } ?>
		`<div class="prod_box_big">
		  <div class="top_prod_box_big"></div>
            <div class="center_prod_box_big">
              <div class="product_img_big"> <a href="javascript:popImage('<?php echo $main_picture_big; ?>','Some Title')" title="header=[Zoom] body=[&nbsp;] fade=[on]"><img src="<?php echo $main_picture; ?>" alt="" border="0" /></a>
            <div class="thumbs"> <a href="javascript:popImage('<?php echo $thumb_1; ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_1; ?>' alt="" border="0" /></a> 
		  <a href="javascript:popImage('<?php echo $thumb_2 ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_2; ?>' alt="" border="0" /></a> 			
		<a href="javascript:popImage('<?php echo $thumb_3; ?>', 'Some Title')"><img class="resize" src='<?php echo $thumb_3; ?>' alt="" border="0" /></a>           </div>
		   </div>
           
		   <div class="details_big_box">
              <div class="product_title_big"><?php echo $name; ?></div>
                <div class="specifications"> Availability: <span class="blue">In stoc</span><br />
               Model#: <span class="blue"><?php echo $model; ?></span><br />
              Condition: <span class="blue"><?php echo $condition; ?></span><br />
			 Description: <span class="blue"><?php echo $description; ?></span><br />
            
           <div class="prod_price_big"><span class="price">$<?php echo number_format($price); ?></span></div>
		   </div>
		</div>
        <div class="bottom_prod_box_big"></div>
	 </div>
       </div>
		  <?php
			} // Close loop to display all item that user has uploaded
		}//Close pictures loop
?>	 

      <div class="center_title_bar">Similar products</div>

	 <?php // GET SIMILAR ITEMS IN SAME CATEGORY AND DISPLAY
		$upload = FALSE;
	 	if ($upload_id > 0) {
			//Run a query for a user's item
		$q_item_similar = "SELECT * FROM upload_item WHERE category_id = '$category_id' ORDER BY date LIMIT 3";
		$upload = TRUE;
	}else{
			//Run a query for a admin's item
		$q_item_similar = "SELECT * FROM items WHERE category_id = '$category_id' ORDER BY date LIMIT 3";
	
	}
		
		$r3 = @mysqli_query ($dbc, $q_item_similar);
		if (mysqli_num_rows($r3) > 0) {	
			while ($row = mysqli_fetch_array($r3, MYSQLI_ASSOC)) {
			
				if($upload == TRUE) {
					$upload_id = $row['upload_id'];
				}else{
					$item_id = $row['item_id'];
				}
				$category_id = $row['category_id'];
				$department_id = $row['department_id'];
				$name = $row['name'];
				$model = $row['model'];
				$condition = $row['item_condition'];
				$description = $row['description'];
				$price = $row['price'];
				$date = $row['date'];
			
				if ($upload_id > 0) {
					$q_pic_info2 = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
										small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
								FROM pictures WHERE upload_id = '$upload_id'";
				}else{
					$q_pic_info2 = "SELECT big_pic_1_name, big_pic_2_name, big_pic_3_name, big_pic_4_name,
										small_pic_1_name, small_pic_2_name, small_pic_3_name, small_pic_4_name, main_img_num
								FROM pictures WHERE item_id = '$item_id'";		
				}
			
				$r5 = @mysqli_query ($dbc, $q_pic_info2);
				while ($row = mysqli_fetch_array($r5, MYSQLI_ASSOC)) {
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
		}
	 
	 ?>
	 
			<div class="prod_box">
			<div class="top_prod_box"></div>
			<div class="center_prod_box">
			
	<?php if($item_id !== 0){ ?>	
          <div class="product_title"><a href="details.php?item_id=<?php echo $item_id; ?>"><?php echo $name; ?></a></div>
          <div class="product_img"><a href="details.php?item_id=<?php echo $item_id; ?>"><img src="<?php echo $main_picture;?>" alt="" border="0" /></a></div>
	<?php }else{ ?>  
	      <div class="product_title"><a href="details.php?upload_id=<?php echo $upload_id; ?>"><?php echo $name; ?></a></div>
          <div class="product_img"><a href="details.php?upload_id=<?php echo $upload_id; ?>"><img src="<?php echo $main_picture;?>" alt="" border="0" /></a></div>
	<?php } ?>	  
          <div class="prod_price"><span class="reduce">$<?php $reduced = $price + ($price * 0.2); echo number_format(ceil($reduced)); ?>
								</span> <span class="price">$<?php echo number_format($price); ?></span></div>
        </div>
        <div class="bottom_prod_box"></div>
      </div>
		  <?php
			} // Close loop to display all item that user has uploaded
		}//Close pictures loop
?>
    </div>
<?php
include ('includes/right_content.html'); 
include ('includes/footer.html');
?>