<?php # Script 18.11 - change_password.php
// This page allows a user to reset their password, if forgotten.
$page_title = 'Change Your Password';
include ('includes/header.html');

// If no first_name session variable exists, redirect the user:
if (!isset($_SESSION['user_id'])) {
	
	$url = BASE_URL . 'index.php'; // define the URL.
	ob_end_clean(); // Delete the buffer.
	header("Location: $url");
	exit();	// Quit the script.
	
} 

if ($_SERVER['REQUEST_METHOD'] == 'POST') { // Handle the form
	require (MYSQL); // Connect to the db.
	
	// Check for a new password and match against the confirmed password:
	$p = FALSE;
	if (preg_match ('/^\w{4,20}$/', $_POST['password1'])) {
		if ($_POST['password1'] == $_POST['password2']){
			$p = mysqli_real_escape_string($dbc, $_POST['password1']);	
		} else {
			echo '<p class="error">Your password did not match the confirmed password</p>';
		}
	} else {
		echo '<p class="error">Please enter a valid password!</p>';
	}
	
	if ($p) { // everything is ok.
	
		// Make the query:
		$q = "UPDATE user SET pass=SHA1('$p') WHERE user_id={$_SESSION['user_id']} LIMIT 1";
		$r = @mysqli_query ($dbc, $q) or trigger_error("Query: $q\n<br />MySQL Error: " . mysqli_error($dbc));	
		if (mysqli_affected_rows($dbc) == 1) { // If it ran OK.
		
			// Send an email, if desired:
			echo '<h3>Your password has been changed.</h3>';
			mysqli_close($dbc);
			include ('includes/footer.html');
			exit(); 	// stop the script.
			
		} else { // If it did not run ok.
			echo '<p class="error">Your password was not changed. Make sure your new password is different than the current password. Contact the system administrator if you think an error occurred.</p>';
		
		}
		
	} else { // Failed the validation test.
		echo '<p class="error">Please try again.</p>';
	}
	
	mysqli_close($dbc);
	
} // end of the main submit conditional.
?>

<h1>Change Your Password</h1>
<form action="change_password.php" method="post">
    <div class="center_content">
      <div class="center_title_bar">Change my password</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>New Password:</strong></label>
              <input type="password" name="password1" size="20" maxlength="20" /> 
              </div>
              <div class="form_row">
              <label class="contact"><strong>Confirm New Password:</strong></label>
              <input type="password" name="password2" size="20" maxlength="20" /></p>
            </div>
            <p><input type="submit" name="submit" value="Change My Password" /></p>
          </div>
		  
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>

<?php include ('includes/footer.html');?>