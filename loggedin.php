<?php # Script 12.4 - loggedin.php
// The user is redirected here from login.php.


// If no cookie is present, redirect the user:
if (!isset($_SESSION['agent']) or ($_SESSION['agent'] != md5($_SERVER['HTTP_USER_AGENT']))) {

	// Need the functions:
	require ('includes/login_functions.inc.php');
	redirect_user();	
}
include ('includes/footer.html');
?>