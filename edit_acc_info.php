<?php
include ('includes/header.html');
?>

<?php
// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check for a valid user ID, through GET or POST:

	
	if ((isset($_GET['id'])) && (is_numeric($_GET['id'])) ) { 
	$id = $_GET['id'];
} elseif ((isset($_POST['id'])) && (is_numeric($_POST['id'])) ) { // Form submission
	$id = $_POST['id'];
} else { // No valid ID, kill the script.
	echo '<p class="error">This page has been accessed in error.</p>';
	include ('includes/footer.html');
	exit();
}
    require (MYSQL);// Conect to a database
	$errors = array(); // initialize an error array.
	
	// Check for a first name:
	if (empty($_POST['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($_POST['first_name']));
	}
	
	// Check for a last name:
	if (empty($_POST['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($_POST['last_name']));
	}
	
	// Check for a middle name:
	if (!empty($_POST['middle_name'])) {
		$mn = mysqli_real_escape_string($dbc, trim($_POST['middle_name']));
	} else {
		$mn = NULL;
	}

	// Check for an email address:
	
	if (filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
	
		if (empty($_POST['email']) && filter_var($e, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'You forgot to enter your email address.';
		} else {
			$e = mysqli_real_escape_string($dbc, trim($_POST['email']));
		}
	}else{
		$errors[] = 'Email address is not in the correct format.';
	}


	
//Shipping Address Info Validation
	
	// Check for a Street name:
	if (empty($_POST['street'])) {
		$errors[] = 'You forgot to enter your street name.';
	} else {
		$st = mysqli_real_escape_string($dbc, trim($_POST['street']));
	}
	
	// Check for a city:
	if (empty($_POST['city'])) {
		$errors[] = 'You forgot to enter your city name.';
	} else {
		$city = mysqli_real_escape_string($dbc, trim($_POST['city']));
	}

	// Check for an state:
	if (empty($_POST['state']) || strlen($_POST['state']) != 2) {
		$errors[] = 'You forgot to enter your state.';
	} else {
		$state = mysqli_real_escape_string($dbc, trim($_POST['state']));
	}
		// Check for zip-code:
	if (empty($_POST['zip']) || strlen($_POST['zip']) != 5) {
		$errors[] = 'You forgot to enter your zip-code.';
	} else {
		$zip = mysqli_real_escape_string($dbc, trim($_POST['zip']));
	}

	if (empty($errors)) { // If everything's OK.
		
		// Register the user in the database...
		
		
		// update the query for a user:
		$q = "UPDATE user SET first_name = '$fn', last_name = '$ln', middle_name = '$mn', email = '$e' WHERE user_id = '$id' ";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		
		// update the query for address:
		$q = "UPDATE address SET city = '$city', street = '$st', state = '$state', zip = '$zip' WHERE user_id = '$id' ";
		$r = @mysqli_query ($dbc, $q); // Run the query.
		if ($r) { // If it ran OK.
			
			// Print a message:
			echo '<h1>Thank you '.$fn.'!</h1>
			<p>You account has been updated.</p><p><br /></p>';
			include ('includes/footer.html');
			
			
			mysqli_close($dbc);
			exit();
			
		} else { // If it did not run OK.
			
			// Public message:
			echo '<h1>System Error</h>
			<p class="error">You could not update your account due to a system error. We apologize for any inconvenience.</p>';
			
			// Debugging message:
			echo '<p>' . mysqli_error($dbc) . '<br /><br />Query: ' . $q . '</p>';
			
		} // End of if ($r) IF.
		
		mysqli_close($dbc); // Close the database connection.
		
		exit();
	
	} else { // Report the errors.
	
		echo '<h1>Error!</h1>
		<p class="error">The following error(s) occurred:<br />';
		foreach ($errors as $msg) { // Print each error.
			echo " - $msg<br />\n";
		}
		echo '</p><p>Please try again.</p><p><br /></p>';
		
	} // End of if (empty($errors)) IF.
	
	mysqli_close($dbc); // Close the database connection.
	
} // End of the main Submit conditional.

?>

<?php
//Get user info for easy update
if ( (isset($_SESSION['user_id']))) {
	$id = $_SESSION['user_id'];
	require (MYSQL);// Conect to a database
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
		$st = $row['street'];
		$city = $row['city'];
		$state = $row['state'];
		$zip = $row['zip'];
	}
	
}else{
	// Redirect:
	header( 'Location: login.php' ) ;
	}
?>
<form action="edit_acc_info.php" method="post">
    <div class="center_content">
      <div class="center_title_bar">Register New User:</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>First Name:</strong></label>
              <input type="text" name="first_name" size="20" maxlength="20" value="<?php echo $first_n; if (isset($_POST['first_name'])) echo $_POST['first_name']; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Last Name:</strong></label>
              <input type="text" name="last_name" size="20" maxlength="40" value="<?php echo $last_n; if (isset($_POST['last_name'])) echo $_POST['last_name']; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Middle Name:</strong></label>
              <input type="text" name="middle_name" size="20" maxlength="40" value="<?php echo $middle_n; if (isset($_POST['middle_name'])) echo $_POST['middle_name']; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Email Address:</strong></label>
              <input type="text" name="email" size="20" maxlength="60" value="<?php echo $email; if (isset($_POST['email'])) echo $_POST['email']; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong><u>Shipping Information:</u></strong></label>
			</div>
			<div class="form_row">
			<label class="contact"><strong>Street Name:</strong></label>
			  <input type="text" name="street" size="30" maxlength="60" value="<?php echo $st; if (isset($_POST['street'])) echo $_POST['street']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>City:</strong></label>
			  <input type="text" name="city" size="30" maxlength="60" value="<?php echo $city; if (isset($_POST['city'])) echo $_POST['city']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>State:</strong></label>
			  <input type="text" name="state" size="2" maxlength="2" value="<?php echo $state; if (isset($_POST['state'])) echo $_POST['state']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>Zip-Code:</strong></label>
			  <input type="text" name="zip" size="5" maxlength="5" value="<?php echo $zip; if (isset($_POST['zip'])) echo $_POST['zip']; ?>" /></p>
            </div>
			  <p></p>
			  <p></p>
			  <p></p>
			  <hr>
            <p><input type="submit" name="submit" value="update" /></p>
		  <?php echo("	<input type='hidden' name='id' value='$id' />  "); ?>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>
<?php
include ('includes/footer.html');
?>