<?php # Script 12.2 - login_functions.inc.php
// This page defines two functions used by the login/logout process.

/* This function determines an absolute URL and redirects the user there.
 * The function takes one argument: the page to be redirected to.
 * The argument defaults to index.php.
 */
function redirect_user ($page = 'index.php') {

	// Start defining the URL...
	// URL is http:// plus the host name plus the current directory:
	$url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']);
	
	// Remove any trailing slashes:
	$url = rtrim($url, '/\\');
	
	// Add the page:
	$url .= '/' . $page;
	
	// Redirect the user:
	header("Location: $url");
	exit(); // Quit the script.

} // End of redirect_user() function.


/* This function validates the form data (the email address and password).
 * If both are present, the database is queried.
 * The function requires a database connection.
 * The function returns an array of information, including:
 * - a TRUE/FALSE variable indicating success
 * - an array of either errors or the database result
 */
function check_login($dbc, $email = '', $pass = '') {

	$errors = array(); // Initialize error array.
	// Validate the email address:
	if (empty($email)) {
		$errors[] = 'You forgot to enter your email address.';
		$e = NULL;
	} else {
		$e = mysqli_real_escape_string($dbc, trim($email));
	}

	// Validate the password:
	if (empty($pass)) {
		$errors[] = 'You forgot to enter your password.';
		$p = NULL;
	} else {
		$p = mysqli_real_escape_string($dbc, trim($pass));
	}
	
	
	// Check if used activated account:
	$q_check_active = "SELECT active FROM user WHERE email='$e' AND pass=SHA1('$p')";		
	$r1 = @mysqli_query ($dbc, $q_check_active); // Run the query.
	
	if ($r1) {
	
	// IF not, add an error:
		while ($row = mysqli_fetch_array($r1, MYSQLI_ASSOC)) {
			if ($row['active'] !== NULL) {
				$errors[] = 'A confirmation email has been sent to your registration email, please follow the link in it, to complete registration.';
			}
		}
	}else{
		$errors[] = 'The email address and password entered do not match those on file.';
	}
		
	if (empty($errors)) { // If everything's OK.
	
		if (filter_var($e, FILTER_VALIDATE_EMAIL)) { // Validate email address to have a corecct formatting

			// Retrieve the user_id and first_name for that email/password combination:
			$q = "SELECT user_id, first_name FROM user WHERE email='$e' AND pass=SHA1('$p')";		
			$r = @mysqli_query ($dbc, $q); // Run the query.
			
			// Check the result:
			if (mysqli_num_rows($r) == 1) {

				// Fetch the record:
				$row = mysqli_fetch_array ($r, MYSQLI_ASSOC);
		
				// Return true and the record:
				return array(true, $row);
				
			} else { // Not a match!
				$errors[] = 'The email address and password entered do not match those on file.';
				$errors[] = "Click <a href='forgot_password.php'>here</a></h3> to retrive your password";
			}
		}else{
		// Bad formatting
		$errors[] = 'Email address is not in the correct format.';
		}
		
		
	} // End of empty($errors) IF.
	
	// Return false and the errors:
	return array(false, $errors);

} // End of check_login() function.