<?php
 /* 
	 Template Name: Custom Registration page
	 https://www.youtube.com/watch?v=HafkLf1EPdw&t=25s
  */ 
	 get_header();
?>

<!-- STEPS TO VALIDATE FROM FIELDS
	==============================
	
	1. Check no space within username:
	strpos($username,'')

	2. Check username nust have value:
	empty($username)

	3. Check usernmae existence :
	username_exists($username)

	4. Check email validation
	is_email($email)

	5. Check email existence
	email_exists($email)
-->


<?php
	
	global $wpdb;

	if ($_POST) {

		$username = $wpdb->escape($_POST['txtUsername']);
		$email = $wpdb->escape($_POST['txtEmail']);
		$password = $wpdb->escape($_POST['txtPassword']);
		$confPassword = $wpdb->escape($_POST['txtConfirmPassword']);
	}

	$error = array();

	//Check no space within username
	if(strpos($username,' ') !== FALSE){
		$error['username_space'] = "Username has space";
	}

	//Check username must have value:
	if(empty($username)){
		$error['username_empty'] = "Needed Username must";
	}

	//Check usernmae existence
	if(username_exists($username)){
		$error['username_exists'] = "Username already exists";
	}

	//Check email validation
	if(!is_email($email)){
		$error['email_valid'] = "Email has no valid value";
	}

	//Check email existence
	if(email_exists($email)){
		$error['email_existance'] = "Email already exists";
	}

	//Password validation
	if(strcmp($password, $confPassword) !== 0){
		$error['password'] = "Password didn't match";
	}

	if(count($error) == 0){
		wp_create_user($username, $password, $email);
		echo "User created successfully.";
	}else{
		print_r($error);
	}
?>





<main id="site-content" role="main">

	<div class="section-inner">
		<h1>Register Here</h1>

		<form method="post">
			<p>
				<label for="txtUsername">User Name</label>
				<input type="text" name="txtUsername" id="txtUsername" placeholder="User Name">
			</p>
			<p>
				<label for="txtEmail">Email ID</label>
				<input type="email" name="txtEmail" id="txtEmail" placeholder="Email">
			</p>
			<p>
				<label for="txtPassword">Password</label>
				<input type="password" name="txtPassword" id="txtPassword" placeholder="Password">
			</p>
			<p>
				<label for="txtConfirmPassword">Confirm Password</label>
				<input type="password" name="txtConfirmPassword" id="txtConfirmPassword" placeholder="Password">
			</p>
			<input type="submit" name="btnSubmit">
		</form>
		
	</div>
</main><!-- #site-content -->

<?php get_footer();?>