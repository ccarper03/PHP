<?php
$page_title = 'My Account';
include ('includes/header.html');
include ('includes/left_content.html');
?>
<div class="center_content" id="tabs">
<div id="tabs-3" >
<?php
//GET UPLOADED ITEM OR ADMIN'S ITEM ID		

if (!empty ($_GET['category_id'])) {
	$category_id = $_GET['category_id'];
	unset ($_SESSION['category_id']);
}else{
	foreach ($_SESSION['category_id'] as $category_id => $value) {
		$category_id = $category_id;
		}
}

//Number of records 
$display = 10;

//Determine how many pages are there
if(isset($GET_['p']) && is_numeric($GET['p'])) { //Already been determinated
	$pages  = $GET_['p'];
}else{ //Need to determine

// Count the number of redcords for admin's items
$q_count = "SELECT COUNT(item_id) FROM items WHERE category_id = $category_id ";
$r_count = @mysqli_query ($dbc, $q_count);
$row = @mysqli_fetch_array ($r_count, MYSQLI_NUM);
$records1 = $row[0];


// Count the number of redcords for user's items
$q_count2 = "SELECT COUNT(upload_id) FROM upload_item WHERE category_id = $category_id";
$r_count2 = @mysqli_query ($dbc, $q_count2);
$row = @mysqli_fetch_array ($r_count2, MYSQLI_NUM);
$records2 = $row[0];


$records = $records1 + $records2;

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

	$one_page = TRUE;
	if ($records2 > 10 && $start == 0) {
		$one_page = FALSE;
	}elseif ($start !== 0 && $records2 > 10) {
		$one_page = TRUE;
	}

//Run a query for a user's item
	$q_item_info = "SELECT * FROM upload_item WHERE category_id = $category_id ORDER BY date DESC LIMIT $start, $display";
					
	$r1 = @mysqli_query ($dbc, $q_item_info);
	if (mysqli_num_rows($r1) > 0) {	// user have items for sale
		
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
		  <div class="prod_details"> <a href="add_cart.php?upload_id=<?php echo $upload_id;?>" title="header=[Add to cart] body=[&nbsp;] fade=[on]"><img src="images/cart.gif" alt="" border="0" class="left_bt" /></a> <a href="#" title="header=[Specials] body=[&nbsp;] fade=[on]"><img src="images/favs.gif" alt="" border="0" class="left_bt" /></a></div>
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
		 
	} //close if statment if threre is no items

if ($one_page == TRUE) {
//Run a query for a admin's item
	$q_item_info = "SELECT * FROM items WHERE category_id = $category_id";# ORDER BY date DESC LIMIT $start, $display";
					
	$r2 = @mysqli_query ($dbc, $q_item_info);

		
		while ($row = mysqli_fetch_array($r2, MYSQLI_ASSOC)) {
			$item_id = $row['item_id'];
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
							FROM pictures WHERE item_id = '$item_id'";
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

}//Close if statment for one page


if (mysqli_num_rows($r1) == 0) {
	echo '<p>There is no items in this category</p>';
	mysqli_free_result ($r1);
	mysqli_free_result ($r2); // free up the resources
	
}else{


// Make the links to other pages 

if($pages > 1) {
$_SESSION['category_id'][$category_id] = $category_id;
echo '<br /><p>';

$current_page = ($start/$display) + 1;

if ($current_page != 1) {
	echo '<a href="category.php?s=' . ($start - $display) . '&p=' . $pages .',">Previous</a> ';
	}
	
	for ($i = 1; $i <= $pages; $i++) {
		if ($i != $current_page) {
			echo '<a href="category.php?s=' .(($display * ($i - 1))) . '&p=' . $pages .'">' . $i . '</a>';
			}else{
				echo $i . ' ';
				}
				}
				
				if ($current_page !=$pages) {
					echo'<a href="category.php?s=' .($start + $display) . '&p=' . $pages .'"> Next</a> ';
				}
}

	}
?>

  </div>
</div>

<?php
include ('includes/right_content.html');  
include ('includes/footer.html');
?>