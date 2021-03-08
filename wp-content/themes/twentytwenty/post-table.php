<?php
/**
 * Template Name: Post Table

 */


get_header(); ?>



<main id="site-content" role="main">

<!-- Ajax call bautton script and html -->

	<script type="text/javascript">
		function fetch(){

			jQuery.ajax({
				url : "<?php echo admin_url('admin-ajax.php'); ?>",
				method:'post',
				data : {action: 'my_action'},
				dataType: 'json',
				success: function(response){
                   //alert('Ajax  success');
                   jQuery('#datafetch').append(response.html);
				}
			});
	}
	</script>


	<div class="container">
		<div class="section-inner" id="datafetch">

		<!-- Ajax call bautton script and html -->
			<h4>Click this button for ajax call posts</h4>
			<button onclick="fetch()">Click here</button>
		</div>
	</div><!-- #main-content -->



	<div class="section-inner">
		<h4>Display data from Database</h4>
			<table id="example" class="display" style="width:100%">
	        <thead>
	            <tr>
	                <th>Post ID</th>
	                <th>Author</th>
	                <th>Post Date</th>
	                <th>Post Title</th>
	                <th>Post Content</th>
	            </tr>
	        </thead>
	        <tbody>
	        	<?php
		        	global $wpdb;
		        	$all_posts = $wpdb->get_results("SELECT * from wp_posts");

		        	foreach ($all_posts as $print) { ?>		        		
		        		<tr>
							<td><?php echo ($print->ID);?></td>
							<td><?php echo ($print->post_author);?></td>
							<td><?php echo ($print->post_date);?></td>
							<td><?php echo ($print->post_title);?></td>
							<td><?php echo ($print->post_content);?></td>
						</tr>	
		        <?php } ?>

	        </tbody>
	        <tfoot>
	            <tr>
	                <th>Name</th>
	                <th>Position</th>
	                <th>Office</th>
	                <th>Age</th>
	                <th>Start date</th>
	                <th>Salary</th>
	            </tr>
	        </tfoot>
	    </table>

	</div>
</main><!-- #site-content -->


<?php get_footer(); ?>
