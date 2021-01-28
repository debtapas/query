<?php
 /* 
	 Template Name: Custom Login page
	 https://www.youtube.com/watch?v=HafkLf1EPdw&t=25s
  */ 
	 get_header();
?>

<main id="site-content" role="main">

	<div class="section-inner">
		<h1>Log in page</h1>
		
		<?php
			global $user_ID;
			global $wpdb;

			if(!$user_ID){

				if($_POST){

					$username = $wpdb->escape($_POST['username']);
					$password = $wpdb->escape($_POST['password']);

					$login_array = array();
					$login_array['user_login'] = $username;
					$login_array['user_password'] = $password;

					$verify_user = wp_signon($login_array, true);

					if(!is_wp_error($verify_user)) {
						echo "<script>window.location = '".site_url()."' </script>";
					}else{

						echo "<p>Invalid Credentials</p>";
					}

				}else{
					//user in logged out state
				
				?>
					<form method="post">
						<p>
							<label for="username">User Name / Email</label>
							<input type="text" id="username" name="username" placeholder="Type your user Name or Mail id">
						</p>
						<p>
							<label for="password">Password</label>
							<input type="password" id="password" name="password" placeholder="Type your password">
						</p>
						<p>
							<button type="submit" name="btn_submit">Login</button>
						</p>
					</form>
				<?php }
				
			}else{
				//user is logged in
				

			}
		?>

		
	</div>

</main><!-- #site-content -->

<?php get_footer();?>