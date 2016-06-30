<?php
include ('includes/header.html');
?>

<?php
// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	require (MYSQL);// Conect to a database
	
	// Trim all the incoming data:
	$trimmed = array_map('trim', $_POST);
	
	// initialize an error array.
	$errors = array();

	// Check for a first name:
	if (empty($trimmed['first_name'])) {
		$errors[] = 'You forgot to enter your first name.';
	} else {
		$fn = mysqli_real_escape_string($dbc, trim($trimmed['first_name']));
	}
	
	// Check for a last name:
	if (empty($trimmed['last_name'])) {
		$errors[] = 'You forgot to enter your last name.';
	} else {
		$ln = mysqli_real_escape_string($dbc, trim($trimmed['last_name']));
	}
	
	// Check for a middle name:
	if (!empty($trimmed['middle_name'])) {
		$mn = mysqli_real_escape_string($dbc, trim($trimmed['middle_name']));
	} else {
		$mn = NULL;
	}

	// Check for an email address:
	
	if (filter_var($trimmed['email'], FILTER_VALIDATE_EMAIL)) {
	
		if (empty($trimmed['email']) && filter_var($e, FILTER_VALIDATE_EMAIL)) {
			$errors[] = 'You forgot to enter your email address.';
		} else {
			$e = mysqli_real_escape_string($dbc, trim($trimmed['email']));
		}
	}else{
		$errors[] = 'Email address is not in the correct format.';
	}

	// Check for a password and match againt the confirmed password:
	if (!empty($trimmed['pass1'])) {
		if ($trimmed['pass1'] != $trimmed['pass2']) {
			$errors[] = 'Your password did not match the confirmed password';
		} else {
			$p = mysqli_real_escape_string($dbc, trim($trimmed['pass1']));
		}
	} else {
		$errors[] = 'You forgot to enter your password.';
	}
	
//Shipping Address Info Validation
	
	// Check for a Street name:
	if (empty($trimmed['street'])) {
		$errors[] = 'You forgot to enter your street name.';
	} else {
		$st = mysqli_real_escape_string($dbc, trim($trimmed['street']));
	}
	
	// Check for a city:
	if (empty($trimmed['city'])) {
		$errors[] = 'You forgot to enter your city name.';
	} else {
		$city = mysqli_real_escape_string($dbc, trim($trimmed['city']));
	}

	// Check for an state:
	if (empty($trimmed['state']) || strlen($trimmed['state']) != 2 || is_numeric($trimmed['state'])) {
		$errors[] = 'You forgot to enter your state.';
	} else {
		$state = mysqli_real_escape_string($dbc, trim($trimmed['state']));
	}
		// Check for zip-code:
	if (empty($trimmed['zip']) || strlen($trimmed['zip']) != 5 || !is_numeric($trimmed['zip'])) {
		$errors[] = 'You forgot to enter your zip-code.';
	} else {
		$zip = mysqli_real_escape_string($dbc, trim($trimmed['zip']));
	}

	if (empty($errors)) { // If everything's OK.
	
	    // Make sure the email address is available:
		$q_check = "SELECT user_id FROM user WHERE email='$e'";
		$r = @mysqli_query ($dbc, $q_check) or trigger_error("Query: $q_check\n<br />MySQL Error: " . mysqli_error($dbc));
		
		if (mysqli_num_rows($r) == 0) { // Available
		
			// Create the activation code:
			$a = md5(uniqid(rand(), true));
		
			// Register the user in the database...
			// Make the query for a new user:
			$q = "INSERT INTO user (first_name, last_name, middle_name, email, pass, registration_date, active) VALUES ('$fn', '$ln', '$mn', '$e', SHA1('$p'), NOW(), '$a' )";
			$r = @mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc)); // Run the query.
			
			// Make the query for address:
			$user_id = mysqli_insert_id($dbc);
			$q = "INSERT INTO address (city, street, state, zip, user_id) VALUES ('$city', '$st', '$state', '$zip', '$user_id' )";
			$r = @mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc)); // Run the query.
			if ($r) { // If it ran OK.
			
			    // Send the email:
				$body = "Thank you for registering at <Electronics.com>. To activate your account, please click on this link:\n\n";
				$body .= BASE_URL . 'activate.php?x=' . urlencode($e) . "&y=$a";
				mail($trimmed['email'], 'Registration Confirmation', $body, 'From: admin@electronics.com');
			
				// Print a message:
				echo '<h1>Thank you!</h1>
				<h3>Thank you for registering! A confirmation email has been sent to your address. Please click the link in that email in order to activate your account.</h3>';
				include ('includes/footer.html');
			
				mysqli_close($dbc);
				exit();
			}else{ // If it did not run OK.
				echo '<p class="error">You could not be registered due to a system error. We apologize for any inconvenience.</p>';
			}
			
		} else { // The email address is not available.
			echo '<p class="error">That email address has already been registered. Please, try again.</p>';
					
		} // End of if ($r) IF.
	
		mysqli_close($dbc); // Close the database connection.
	
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

<form action="register.php" method="post">
<script> $.validate(); </script> <!-- Add java script to validate form with sticky form -->
    <div class="center_content">
      <div class="center_title_bar">Register New User:</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>First Name:</strong></label>
              <input type="text" data-validation="required" name="first_name" size="20" maxlength="20" value="<?php if (isset($trimmed['first_name'])) echo $trimmed['first_name']; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Last Name:</strong></label>
              <input type="text" data-validation="required" name="last_name" size="20" maxlength="40" value="<?php if (isset($trimmed['last_name'])) echo $trimmed['last_name']; ?>" /></p>
            </div>
			<div class="form_row">
              <label class="contact"><strong>Middle Name:</strong></label>
              <input type="text" name="middle_name" size="20" maxlength="40" value="<?php if (isset($trimmed['middle_name'])) echo $trimmed['middle_name']; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Email Address:</strong></label>
              <input type="text" data-validation="email" data-validation-error-msg="E-mail is not valid" name="email" size="20" maxlength="60" value="<?php if (isset($trimmed['email'])) echo $trimmed['email']; ?>" /></p>
            </div>
            <div class="form_row">
             <label class="contact" for="password"><strong>Password:</strong></label>
             <input type="password" data-validation="required" name="pass1" size="20" maxlength="20" value="<?php if (isset($trimmed['pass1'])) echo $trimmed['pass1']; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Confirm Password:</strong></label>
              <input type="password" data-validation="required" name="pass2" size="20" maxlength="20" value="<?php if (isset($trimmed['pass2'])) echo $trimmed['pass2']; ?>" /></p>
			</div>
			  <p></p>
			  <p></p>
			<div class="form_row">
              <label class="contact"><strong><u>Shipping Information:</u></strong></label>
			</div>
			<div class="form_row">
			<label class="contact"><strong>Street Name:</strong></label>
			  <input type="text" data-validation="required" name="street" size="30" maxlength="60" value="<?php if (isset($trimmed['street'])) echo $trimmed['street']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>City:</strong></label>
			  <input type="text" data-validation="required" name="city" size="30" maxlength="60" value="<?php if (isset($trimmed['city'])) echo $trimmed['city']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>State:</strong></label>
			  <input type="text" data-validation="required" name="state" size="2" maxlength="2" value="<?php if (isset($trimmed['state'])) echo $trimmed['state']; ?>" /></p>
            </div>
			<div class="form_row">
			  <label class="contact"><strong>Zip-Code:</strong></label>
			  <input type="text" data-validation="required" name="zip" size="5" maxlength="5" value="<?php if (isset($trimmed['zip'])) echo $trimmed['zip']; ?>" /></p>
            </div>
			  <p></p>
			  <p></p>
			  <p></p>
			  <hr>
            <p><input type="submit" name="submit" value="Register" /></p>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>

<?php
include ('includes/footer.html');
?>