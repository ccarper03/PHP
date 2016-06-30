<?php
// Include the header:

include ('header.html');

// Print any error messages, if they exist:
if (isset($errors) && !empty($errors)) {
	echo '<h1>Error!</h1>
	<p class="error">The following error(s) occurred:<br />';
	foreach ($errors as $msg) {
		echo " - $msg<br />\n";
	}
	echo '</p><p>Please try again.</p>';
}

// Display the form:
?>

<form action="login.php" method="post" id="login">
    <div class="center_content">
      <div class="center_title_bar">Sign In:</div>
      <div class="prod_box_big">
        <div class="top_prod_box_big"></div>
        <div class="center_prod_box_big">
          <div class="contact_form">
            <div class="form_row">
              <label class="contact" id="emailP"><strong>Email Address:</strong></label>
              <input type="text" name="email" id="email" size="20" maxlength="60" /> </p>
            </div>
			<div class="form_row">
              <label class="contact" id="passwordP"><strong>Password:</strong></label>
              <input type="password" name="pass" id="password" size="20" maxlength="20" /></p>
            </div>
			  <p></p>
			  <p></p>
			  <p></p>
			  <hr>
            <p><input type="submit" name="submit" id="submit" value="Login" /></p>
          </div>
        </div>
        <div class="bottom_prod_box_big"></div>
      </div>
    </div>
<?php include ('footer.html'); ?>