<head>
<script type="text/javascript" src="includes/js/jquery-1.11.1.min.js" charset="utf=8"></script>
<script type="text/javascript" src="includes/js/login.js" charset="utf=8"></script>
</head>
<?php # Script 12.3 - login.php
// This page processes the login form submission.
// Upon successful login, the user is redirected.
// Two included files are necessary.
// Send NOTHING to the Web browser prior to the setcookie() lines!

// Check if the form has been submitted:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

	// For processing the login:
	require ('includes/login_functions.inc.php');
	
	// Need the database connection:
	require ('../mysqli_connect_project.php');
		
	// Check the login:
	list ($check, $data) = check_login($dbc, $_POST['email'], $_POST['pass']);
	
	if ($check) { // OK!
		

		//Set the session data:
		session_start();
		$_SESSION['user_id'] = $data['user_id'];
		$_SESSION['first_name'] = $data['first_name'];
		
		// Store the HTTP_USER_AGENT:
		$_SESSION['agent'] = md5($_SERVER['HTTP_USER_AGENT']);
		
		// Redirect:
		redirect_user('loggedin.php');
			
	} else { // Unsuccessful!

		// Assign $data to $errors for error reporting
		// in the login_page.inc.php file.
		$errors = $data;

	}
		
	mysqli_close($dbc); // Close the database connection.

} // End of the main submit conditional.

// Create the page:
include ('includes/login_page.inc.php');
?>