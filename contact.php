<?php
$page_title = 'Contact Page';
include ('includes/header.html');
include ('includes/left_content.html');
?>
<?php
//Get user info for easy update
if ( (isset($_SESSION['user_id']))) { // Check for a valid user ID
	$id = $_SESSION['user_id'];
	//Run a query for a user info
	$q_email_name = "SELECT first_name, last_name, email FROM user WHERE user_id = '$id'";
	$r = @mysqli_query ($dbc, $q_email_name);
	
	while ($row = mysqli_fetch_array($r, MYSQLI_ASSOC)) {
		$first_n = $row['first_name'];
		$last_n = $row['last_name'];
		$email = $row['email'];
}
}

	// Check for form submission:
if ($_SERVER['REQUEST_METHOD'] == 'POST') {


	function spam_scrubber($value) {
	
		// list of bad values
		$very_bad = array('to:', 'cc:', 'bcc:', 'content-type:', 'mime-version:', 'multipart-mixed:', 'content-transfer-encoding:');
		
		// if there are any bad things in return an empty string 
		
		foreach ($very_bad as $v) {
			if (stripos($value, $v) !== FALSE) return ' ';
			}
			
		// replace any new line with spaces 
		$value = str_replace(array( "\r", "\n", "%0a", "%0d"), ' ', $value);
		
		//return a value
		return trim($value);
		
	} //end of scrubber() function
	
	$scrubbed = array_map('spam_scrubber', $_POST);


	// Minimal form validation:
	if (!empty($scrubbed['name']) && !empty($scrubbed['email']) && !empty($scrubbed['message'])) {
	
		// Create the body:
		$body = "Name: {$scrubbed['name']}\n\nMessage: {$scrubbed['message']}";
		
		// make it no longer than 70 characters long:
		$body = wordwrap($body, 70);
		
		
		
		// Send the mail:)
		if (mail('ttaras89@yahoo.com', 'Contact Form Submission', $body, "From: {$scrubbed['email']}")) {
		
			// Print a message:
			echo '<p><em>Thank you for contacting us. We will reply some day.</em></p>';
			$scrubbed = array();
		} else {
			echo '<p>Error sending mail!</p>';
			$scrubbed = array();
		}
		
		// Clear $_POST (so that the form's not sticky):
		$scrubbed = array();
	
	} else {
		echo '<p style="font-weight: bold; color: #C00">Please fill out the form completely.</p>';
	}
	

} // end of main isset() IF.

// Create the HTML form:
?>
<form action="contact.php" method="post">
    <div class="center_content">
      <div class="center_title_bar">Contact Us</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact"><strong>Name:</strong></label>
              <input type="text" name="name" size="30" maxlength="60" value="<?php if(isset($first_n)){ echo $first_n;} if (isset($scrubbed['name'])) echo $scrubbed['name']; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Email:</strong></label>
              <input type="text" name="email" size="30" maxlength="80" value="<?php if(isset($email)){ echo $email;} if (isset($scrubbed['email'])) echo $scrubbed['email']; ?>" /></p>
            </div>
            <div class="form_row">
              <label class="contact"><strong>Message:</strong></label>
              <textarea name="message" rows="5" cols="30"><?php if (isset($scrubbed['message'])) echo $scrubbed['message']; ?></textarea></p>
            </div>
            <p><input type="submit" name="submit" value="Send!" /></p>
          </div>
		  
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
</form>

  <!-- end of main content -->
<?php
include ('includes/right_content.html');
include ('includes/footer.html');
?>